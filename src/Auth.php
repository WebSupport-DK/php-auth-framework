<?php

namespace PHP\Auth;

use PHP\Auth\Models\UserModel as Users;
use PHP\Auth\Models\RoleModel as Roles;
use PHP\Auth\Models\SessionModel as Sessions;

use PHP\HTTP\Session;
use PHP\HTTP\Cookie;

use PHP\Security\Hash;

class Auth 
{
    private $instance;
    protected $user;
    protected $role;
    protected $session;
    protected $sessionName = 'user';
    protected $cookieName = 'hash';
    protected $cookieExpiry = 604800;

    public function __construct()
    {
        $this->user = new Users();
        $this->role = new Roles();
        $this->session = new Sessions();
        
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
        return $this->user->find($_SESSION[$this->sessionName]);
    }

    public function check() 
    {
        if (Session::exists($this->sessionName)) {
            $user = Session::get($this->sessionName);
            return $this->user->find($user);
        }

        if (!Session::exists($this->sessionName) && Cookie::exists($this->cookieName)) {
            $hash = Cookie::get($this->cookieName);
            $hashCheck = $this->session->getHash($hash);
        
            if ($hashCheck->count()) {
                $this->data = $hashCheck->first()->user_id;
                return $this->login();
            }
        }
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
            Session::set($this->sessionName, $this->user->data()->id);
        } else {
            if ($this->user->find($username)) {
                if (password_verify($password, $this->user->data()->password)) {

                    Session::set($this->sessionName, $this->user->data()->id);

                    if ($remember) {
                        $hash = Hash::unique();
                        $hashCheck = $this->session->getUser($this->user->data()->id);

                        if (!$hashCheck->count()) {
                            $this->session->set($hash);
                        } else {
                            $hashCheck = $hashCheck->first()->hash;
                        }

                        Cookie::set($this->cookieName, $hash, $this->cookieExpiry);
                    }

                    return true;
                }
            }
        }

        return false;
    }

    public function logout()
    {
        $this->session->destroy($this->user->data()->id);

        Session::delete($this->sessionName);
        Cookie::delete($this->cookieName);
    }

}