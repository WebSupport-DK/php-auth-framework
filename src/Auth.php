<?php

namespace WebSupportDK\PHPAuthFramework;

use WebSupportDK\PHPScrud\DB,
    WebSupportDK\PHPSecurity\Session,
    WebSupportDK\PHPSecurity\Cookie,
    WebSupportDK\PHPSecurity\Hash,
    WebSupportDK\PHPSecurity\Token,
    WebSupportDK\PHPSecurity\Password;

class Auth
{

    // object instance
    private static
            $_instance = null;
    protected
            $db,
            $data,
            $users,
            $roles,
            $userAttributes,
            $status,
            $token,
            $active,
            $sessionName,
            $sessionRole,
            $sessions,
            $cookieName,
            $cookieExpiry,
            $timeout,
            $isLoggedIn;

    public
            function __construct($db)
    {
        // db connection
        $this->db             = $db;
        $this->token          = '5XDPLPAmat';
        $this->isLoggedIn     = false;
        // db tables
        $this->users          = 'Users';
        $this->roles          = 'Roles';
        $this->sessions       = 'Sessions';
        $this->status         = 'Status';
        $this->userAttributes = array('ID, Created, Username, Firstname, Lastname, Password, Email, Role_ID, Status_ID, Auth_token, Reset_token, Last_login');
        // default values
        $this->active         = 2;
        // session and cookie names
        $this->sessionName    = 'User';
        $this->sessionRole    = 'Role';
        $this->cookieName     = 'User';
        // life of cookie before exipry
        $this->cookieExpiry   = 1800;
        $this->timeout        = 1800;
    }

    /*
     * Instantiate object
     */

    public static
            function load($params = null)
    {
        if (!isset(self::$_instance))
        {
            self::$_instance = new Auth($params);
        }
        return self::$_instance;
    }

    public
            function setAttribute($attribute, $name)
    {
        $this->$attribute = $name;
    }

    //Find users
    public
            function search($user = null)
    {

        if ($user)
        {

            $field = (is_numeric($user)) ? 'ID' : 'Username';
            if (filter_var($user, FILTER_VALIDATE_EMAIL))
            {
                $field = 'Email';
            }
            $data = $this->db->select($this->userAttributes, $this->users, null, array(array($field, '=', $user)));

            if ($data->count())
            {
                $this->data = $data->first();
                return true;
            }
        }
        return false;
    }

    //check if user exists
    public
            function exists()
    {
        return (!empty($this->data)) ? true : false;
    }

    //Log users in
    public
            function login($usernameOrEmail = null, $password = null, $remember = false)
    {
        if (!$this->isLoggedIn())
        {

            $user = $this->search($usernameOrEmail);
            if ($user)
            {

                if (Password::verify($password, $this->data()->Password))
                {
                    // password is correct start a user session
                    $this->startSession();

                    if ($remember)
                    {
                        $hash = Token::create(46);

                        $hashCheck = $this->db->select(array('User_ID'), $this->sessions, null, array(array('User_ID', '=', $this->data()->ID)), array('LIMIT' => 1));

                        if (!$hashCheck->count())
                        {
                            $this->db->insert($this->sessions, array(
                                'User_ID' => $this->data()->ID,
                                'Token'   => $hash
                            ));
                        }
                        Cookie::set($this->cookieName, $hash, $this->cookieExpiry);
                    }
                    return true;
                }
            }
        }
        return false;
    }

    private
            function updateTimeout()
    {
        $this->db->update($this->users, 'ID', $this->data()->ID, array(
            'Timeout' => time()
        ));
    }

    private
            function updateLastLogin()
    {
        $this->db->update($this->users, 'ID', $this->data()->ID, array(
            'Last_login' => time()
        ));
    }

    private
            function startSession()
    {
        Session::addKey($this->sessionName, 'ID', $this->data()->ID);
        Session::addKey($this->sessionName, 'Username', $this->data()->Username);
        Session::addKey($this->sessionName, 'Last_login', $this->data()->Last_login);
        Session::addKey($this->sessionName, 'Timeout', time());
        $role = $this->db->select(array('ID,Role'), $this->roles, null, array(array('ID', '=', $this->data()->Role_ID)));
        Session::addKey($this->sessionName, 'Role', Hash::encrypt($role->first()->Role, $this->token));
        // update last login
        $this->updateLastLogin();
        $this->updateTimeout();
    }

    private
            function checkCookie()
    {
        /*
         * Check for cookies on client
         * only if no session user-session (login-session) exists
         */
        if (Cookie::exists($this->cookieName) && !Session::exists($this->sessionName))
        {
            // Get hashed name from cookie on client
            // and check if hashed name exists in database 'Sessions'
            $hashCheck = $this->db->select(array('User_ID'), $this->sessions, null, array(array('Token', '=', Cookie::get($this->cookieName))), array('LIMIT' => 1));
            // Only if the query returns results then login the client
            if ($hashCheck->count())
            {
                // Get the user ID from the $hashCheck query and login the client/user
                $this->search($hashCheck->first()->User_ID);

                // star the user session
                $this->startSession();
                $this->isLoggedIn = true;
                return true;
            }
            else
            {
                $this->logout();
                return false;
            }
        }
        return false;
    }

    private
            function checkSession()
    {
        if (Session::exists($this->sessionName))
        {

            $user_timeout = Session::getKey($this->sessionName, 'Timeout');
            if (time() - $user_timeout > $this->timeout)
            {
                if ($this->search(Session::getKey($this->sessionName, 'ID')))
                {
                    $this->updateTimeout();
                    $this->isLoggedIn = true;
                    return true;
                }
                else
                {
                    $this->logout();
                    return false;
                }
            }
            $this->isLoggedIn = true;
            return true;
        }
    }

    public
            function check()
    {
        if($this->checkSession() || $this->checkCookie()){
            return true;
        }
        return false;
    }

    //User roles
    public
            function role($key)
    {
        if ($this->isLoggedIn() == true)
        {
            $role = Session::getKey($this->sessionName, $this->sessionRole);

            if ($role)
            {
                $permissions = json_decode(Hash::decrypt($role, $this->token), true);

                if ($permissions[$key] == true)
                {
                    return true;
                }
            }
        }
        return false;
    }

    public
            function authToken($user_id)
    {
        $token = Token::create(46);
        $this->search($user_id);
        $this->db->update($this->users, 'ID', $this->data()->ID, array(
            'Auth_token' => $token
        ));
        return $token;
    }

    public
            function auth($key)
    {
        $token = $this->db->select(array('ID, Auth_token'), $this->users, null, array(array('Auth_token', '=', $key)));

        if ($token->results())
        {
            $this->search($token->first()->ID);
            $this->db->update($this->users, 'ID', $this->data()->ID, array(
                'Status_ID' => $this->active
            ));
            return true;
        }
        else
        {
            return false;
        }
    }

    public
            function resetToken($user_id)
    {
        $token = Token::create(46);
        $this->search($user_id);
        $this->db->update($this->users, 'ID', $user_id, array(
            'Reset_token' => $token
        ));
        return $token;
    }

    public
            function reset($key)
    {
        $token = $this->db->select(array('ID, Reset_token'), $this->users, null, array(array('Reset_token', '=', $key)));

        if ($token->results())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public
            function logout()
    {

        if (Cookie::exists($this->cookieName))
        {
            $this->db->delete($this->sessions, array(array('Token', '=', Cookie::get($this->sessionName))));
        }
        Session::delete($this->sessionName);
        Cookie::delete($this->cookieName);
    }

    protected
            function data()
    {
        return $this->data;
    }

    public
            function isLoggedIn()
    {
        $this->check();
        return $this->isLoggedIn;
    }

}
