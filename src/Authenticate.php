<?php

namespace PHP\Auth;

class Authenticate 
{
    protected $db;
    protected $table;
    protected $sessionName = 'user';

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function user() 
    {
        return User::find($_SESSION[$this->sessionName]);
    }

    public function check() 
    {
        return isset($_SESSION[$this->sessionName]);
    }

    public function attempt($email, $password) 
    {

        $user = User::where('email', $email)->first();

        if (!$user) {
            return false;
        }

        if (password_verify($password, $user->password)) {
            $_SESSION[$this->sessionName] = $user->id;
            return true;
        }

        return false;
    }

    public function logout()
    {
        unset($_SESSION[$this->sessionName]);
    }

}