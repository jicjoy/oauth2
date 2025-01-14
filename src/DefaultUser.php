<?php

namespace Wolf\Authentication\Oauth2;

use Wolf\Authentication\Oauth2\Api\UserInterface;
 
final class DefaultUser implements UserInterface
{

    private $repository = null;

    private string $identity;

    /** @psalm-var array<int|string, string> */
    private $roles;

    /** @psalm-var array<string, mixed> */
    private $details;

    /**
     * @psalm-param array<int|string, string> $roles
     * @psalm-param array<string, mixed> $details
     */
    public function __construct(string $identity = '', array $roles = [], array $details = [])
    {
        $this->identity = $identity;
        $this->roles    = $roles;
        $this->details  = $details;
    }


     /**
     * Get the unique user identity (id, username, email address or ...)
     */
    public function getIdentity(): string {

        return $this->identity;

    }

    /**
     * Get all user roles
     *
     * @psalm-return iterable<int|string, string>
     */
    public function getRoles(): iterable {

        return $this->roles;
    }

    /**
     * Get a detail $name if present, $default otherwise
     *
     * @param null|mixed $default
     * @return mixed
     */
    public function getDetail(string $name, $default = null) {

        return $this->details[$name] ?? $default;
    }

    public function hasRole(string $role): bool {
        return in_array($role, $this->roles);
    }

    public function setDetail($name,$value):static {
         $this->details[$name] = $value;
         return $this;
    }

    /**
     * Get all the details, if any
     *
     * @psalm-return array<string, mixed>
     */
    public function getDetails(): array {
         return $this->details;
    }

    /**
     * 
     * 
     * @param mixed $repository
     * @return DefaultUser
     */
    public function setRepository($repository):static {
        $this->repository = $repository;
        return $this;
    }
 
    /**
     * 
     * 
     * @return mixed
     */
    public function getRepository():mixed {
        return $this->repository;
    }
}
