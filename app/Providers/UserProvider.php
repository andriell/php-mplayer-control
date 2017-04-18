<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 18.04.2017
 * Time: 9:38
 */

namespace App\Providers;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as IlluminateUserProvider;

class UserProvider implements IlluminateUserProvider
{

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        var_dump($identifier);
        echo 'retrieveById';
        exit();
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed $identifier
     * @param  string $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        var_dump($identifier);
        var_dump($token);
        echo 'retrieveByToken';
        exit();
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        var_dump($user);
        var_dump($token);
        echo 'updateRememberToken';
        exit();
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        echo 'retrieveByCredentials';
        var_dump($credentials);
        //exit();
        return new User();
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        echo 'validateCredentials';
        var_dump($user);
        var_dump($credentials);
        return true;
    }
}