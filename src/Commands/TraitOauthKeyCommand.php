<?php

declare(strict_types=1);

namespace Wolf\Authentication\Oauth2\Commands;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputOption;
 

use Wolf\Authentication\Oauth2\ConfigTrait;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

trait  TraitOauthKeyCommand
{

    use ConfigTrait;
    /**
     * 
     * container
     * @var ContainerInterface $container
     */
    private  $container;



    protected function configure()
    {
        $this->addOption('force','f',InputOption::VALUE_NONE,'Overwrite keys they already exist');
        $this->addOption('length','l',InputOption::VALUE_NONE,'The length of the private key');
        $this->setDescription('Create the encryption keys for API authentication');
    }

    public function handle():int {
       if(!extension_loaded('openssl')) {
          $this->output->writeln('Extension \'openssl\' is not available' );
          return SymfonyCommand::FAILURE;
       }
      
       $force = $this->input->getOption('force');
       $length = $this->input->getOption('length');
       $filePrivateKey = (string)$this->getConfigArray($this->container,'private_key');
       $filePublicKey  =  (string)$this->getConfigArray($this->container,'public_key');

       if(file_exists($filePrivateKey) && file_exists($filePublicKey) && !$force) {
           $this->output->writeln('Encryption keys already exist. Use the --force option to overwrite them.');
           return SymfonyCommand::FAILURE;
       }
       $dir = dirname($filePublicKey);
       $filesystem = new Filesystem();
       if(!$filesystem->exists($dir)) {
          $filesystem->mkdir($dir);
       }
       // Generate public/private keys with OpenSSL
        $config = [
            'private_key_bits' => $bits = $length > 0 ? $length : 4096,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];
       $fileEncryptionKey = sprintf('%s/encryption.key',$dir);
       $this->output->writeln(sprintf('Using %d bits to generate key of type RSA' . "\n\n", $bits));
       // Private key
        $res = openssl_pkey_new($config);

        openssl_pkey_export($res, $privateKey);
        $filesystem->dumpFile($filePrivateKey,$privateKey);
        $this->output->writeln(sprintf("Private key stored in:\n%s\n", $filePrivateKey));
        // Public key
        $publicKey = openssl_pkey_get_details($res);
        $filesystem->dumpFile($filePublicKey, $publicKey['key']);
        $this->output->writeln(sprintf("Public key stored in:\n%s\n", $filePublicKey));
        // Encryption key
        $encKey = base64_encode(random_bytes(32));
        $filesystem->dumpFile($fileEncryptionKey,$encKey);
        $this->output->writeln("Encryption key stored in");
       return SymfonyCommand::SUCCESS;
    }

    
   
}