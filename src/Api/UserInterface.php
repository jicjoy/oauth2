<?php

namespace Wolf\Authentication\Oauth2\Api;

interface UserInterface
{

     /**
     * Get the unique user identity (id, username, email address or ...)
     */
    public function getIdentity(): string;

    /**
     * Get all user roles
     *
     * @psalm-return iterable<int|string, string>
     */
    public function getRoles(): iterable;

    /**
     * Get a detail $name if present, $default otherwise
     *
     * @param null|mixed $default
     * @return mixed
     */
    public function getDetail(string $name, $default = null);

    /**
     * Get all the details, if any
     *
     * @psalm-return array<string, mixed>
     */
    public function getDetails(): array;

    public function setDetail($name,$value):static;

    public function setRepository($repository):static;

    public function getRepository():mixed;
    
}
