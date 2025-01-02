<?php

declare(strict_types=1);

namespace Wolf\Authentication\Oauth2\Repository\DB;
use ArrayAccess;
use Psr\Container\ContainerInterface;
class ModelEntity  implements \Wolf\Authentication\Oauth2\Api\ModelEntityInterface
{

    /**
     * Undocumented variable
     *
     * @var string
     */
    private string $model;

    private array $_data = [];

 
    
    /**
     * 构造函数，用于初始化 ModelEntity 类。
     *
     * @param ArrayAccess $model 一个实现了 ArrayAccess 接口的模型对象。
     */
    public function __construct(ArrayAccess $model)
    {
       $this->model = $model;
    }

    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, mixed $value)
    {
        return $this->setAttribute($key, $value);
    }

    public function __isset(string $key)
    {
        return isset($this->_data[$key]);
    }
 
    // 实现接口中的所有方法
    public function find(int|string $id): static
    {
          return $this->setRawAttributes( $this->model->newQuery()->where($this->model->getKeyName(),$id)->first()->toArray() );
    }

    public function getAttribute(string $key)
    {
        // 实现获取属性逻辑
        return $this->_data[$key] ?? null;
    }

    public function getAttributes()
    {
        // 实现获取所有属性逻辑
        return $this->_data;
    }

    public function getId()
    {
        // 实现获取ID逻辑
        return $this->_data[$this->model->getKeyName()] ?? null;
    }

    public function getRevoked(): int|null
    {
        // 实现获取撤销状态逻辑
        return $this->_data['revoked'] ?? null;
    }

    public function save()
    {
        // 实现保存逻辑
        $this->model->setRwAttributes($this->_data)->save();
    }

    public function setAttribute(string $key, mixed $value)
    {
        // 实现设置属性逻辑
        $this->_data[$key] = $value;
        return $this;
    }

    public function setRawAttributes(array $attributes, bool $sync = false): static
    {
        // 实现设置原始属性逻辑
        $this->_data = $attributes;
        return $this;
    }

    public function update()
    {
        // 实现更新逻辑
        foreach ($this->_data as $key => $value) {
            $this->model->setAttribute($key, $value);
        }
        $this->model->save();
    }

   
}
