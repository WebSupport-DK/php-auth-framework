<?php

namespace PHP\Auth\Models;

use PHP\Auth\Models\UserModel as User;

class SessionModel extends User
{
    private $table = 'sessions';

    public function __construct() 
    {
        parent::__construct();
    }

    public function getUser($user_id)
    {
        $this->db->get(array('*'), $this->table, array('user_id', '=', $user_id));
    }

    public function getHash($hash)
    {
        $this->db->get(array('*'), $this->table, array('token', '=', $hash));
    }

    public function set($hash)
    {
        $this->db->insert($this->table, array(
            'user_id' => $this->data()->id,
            'token' => $hash
        ));
    }

    public function destroy($user_id)
    {
        $this->db->delete($this->table, array(array('user_id', '=', $user_id)));
    }

}