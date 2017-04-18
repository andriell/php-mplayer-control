<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;

class User implements Authenticatable
{

    public $name = null;
    private $authIdentifier = null;
    private $authPassword = null;
    private $rememberToken = null;

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param null $authIdentifier
     */
    public function setAuthIdentifier($authIdentifier)
    {
        $this->authIdentifier = $authIdentifier;
    }

    /**
     * @param null $password
     */
    public function setAuthPassword($password)
    {
        $this->authPassword = $password;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'aid';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->authIdentifier;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->authPassword;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->rememberToken = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'token';
    }
}
