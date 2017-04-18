<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;

class User implements Authenticatable
{

    private $authIdentifier = null;
    private $authPassword = null;
    private $rememberToken = null;

    /**
     * @param null $authIdentifier
     */
    public function setAuthIdentifier($authIdentifier)
    {
        $this->authIdentifier = $authIdentifier;
    }

    /**
     * @param null $authPassword
     */
    public function setAuthPassword($authPassword)
    {
        $this->authPassword = $authPassword;
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
