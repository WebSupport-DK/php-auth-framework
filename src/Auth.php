<?php

namespace PHP\Auth;

use PHP\Auth\User;

use PHP\HTTP\Session;
use PHP\HTTP\Cookie;

use PHP\Security\Hash;
use PHP\Security\Password;

class Auth 
{
    protected static $instance = null;
    
    protected $user;
    protected $sessionName = 'user';
    protected $cookieName = 'hash';
    protected $cookieExpiry = 604800;

    public function __construct()
    {
        $this->user = new User();
    }

    public static function singleton()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Auth();
        }

        return self::$instance;
    }

    public function user() 
    {
        return $this->user->find(Session::get($this->sessionName));
    }

    public function check() 
    {
        if (Session::exists($this->sessionName)) {
            $user = Session::get($this->sessionName);
            return $this->user->find($user);
        }

        if (!Session::exists($this->sessionName) && Cookie::exists($this->cookieName)) {
            $hash = Cookie::get($this->cookieName);
            $user = $this->user->getSession($hash);
        
            if ($user) {
                $this->data = $user->getId();
                return $this->login();
            }
        }

        return false;
    }

    public function attempt() 
    {

        if ($this->login()) {
            return true;
        }

        return false;
    }

    public function login($username = null, $password = null, $remember = false) {

        if (!$username && !$password && $this->user->exists()) {
            Session::set($this->sessionName, $this->user->getId());
        } else {
            if ($this->user->find($username)) {
                if (Password::verify($password, $this->user->getPassword())) {

                    Session::set($this->sessionName, $this->user->getId());

                    if ($remember) {
                        $hash = Hash::unique();

                        if(!$this->user->getSession()) {
                            $hash = $this->user->setSession($hash);
                        } else {
                            $hash = $this->user->getSession();
                        }

                        Cookie::set($this->cookieName, $hash, $this->cookieExpiry);
                    }

                    return true;
                }
            }
        }

        return false;
    }

    public function role($key)
    {
        $role = json_decode($this->user->getRole(), true);
        
        if($role[$key] == true) {
            return true;
        }

        return false;
    }

    public function logout()
    {
        $this->session->destroy($this->user->getUserId());

        $this->user->resetSession();

        Session::delete($this->sessionName);
        Cookie::delete($this->cookieName);
    }

}