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

    /** @var \Illuminate\Cache\CacheManager */
    protected $cache;
    /** @var  array */
    protected $users;
    protected $homeIpStart = false;

    /**
     * UserProvider constructor.
     * @param \Illuminate\Cache\CacheManager $cache
     * @param array $users
     * @param $homeIpStart
     */
    public function __construct($cache, $users, $homeIpStart)
    {
        $this->cache = $cache;
        $this->users = $users;
        $this->homeIpStart = $homeIpStart;
    }


    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        if (!isset($this->users[$identifier])) {
            return null;
        }
        $user = new User();
        $user->setAuthIdentifier($identifier);
        if (isset($this->users[$identifier]['name'])) {
            $user->setName($this->users[$identifier]['name']);
        }
        return $user;
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
        return $this->cache->get('user_' . $identifier . '_token_' . $token . '_' . $_SERVER['REMOTE_ADDR'], NULL);
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
        $time = 60 * 24;
        if ($this->homeIpStart && substr($_SERVER['REMOTE_ADDR'], 0, strlen($this->homeIpStart)) == $this->homeIpStart) {
            $time = 60 * 24 * 1000;
        }
        $this->cache->put('user_' . $user->getAuthIdentifier() . '_token_' . $token . '_' . $_SERVER['REMOTE_ADDR'], $user, $time);
    }

    /**
     * 1 Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $user = new User();
        $user->setAuthIdentifier($credentials['email']);
        if (isset($this->users[$credentials['email']]) && isset($this->users[$credentials['email']]['name'])) {
            $user->setName($this->users[$credentials['email']]['name']);
        }
        return $user;
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
        if (!($user instanceof User)) {
            return false;
        }
        return isset($this->users[$credentials['email']])
            && isset($this->users[$credentials['email']]['password'])
            && $this->users[$credentials['email']]['password'] == $credentials['password'];
    }
}