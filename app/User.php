<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;

class User implements Authenticatable
{

    public $name = null;
    private $password = null;
    private $token = null;

    /**
     * @param null $authIdentifier
     */
    public function setAuthIdentifier($authIdentifier)
    {
        $this->name = $authIdentifier;
    }

    /**
     * @param null $password
     */
    public function setAuthPassword($password)
    {
        $this->password = $password;
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
        return $this->name;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->token = $value;
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
