<?php

namespace PHP\Auth\Models;

use PHP\Auth\Models\UserModel as User;

class RoleModel extends User
{
    private $table = 'roles';

    public function __construct() 
    {
        parent::__construct();
    }

    public function get($key) {
        $group = $this->db->get(array('*'), $this->table, array(array('id', '=', $this->data()->role)));
        if ($group->count()) {
            $permissions = json_decode($group->first()->permissions, true);
            if ($permissions[$key] == true) {
                return true;
            }
        }
        return false;
    }

}