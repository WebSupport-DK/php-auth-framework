<?php

namespace PHP\Auth\Models;

use Exception;
use PHP\CRUD\Database as DB;

class UserModel {

    private $table = 'users';
    protected $db;
    protected $data;

    public function __construct() 
    {
        $this->db = DB::singleton();
    }

    public function update($fields = array(), $id = null) 
    {
        if (!$this->db->update($this->table, $id, $fields)) {
            throw new Exception('There was a problem updating user.');
        }
    }

    public function create($fields) 
    {
        if (!$this->db->insert($this->table, $fields)) {
            throw new Exception('There was a problem creating user.');
        }
    }

    public function find($user = null) 
    {
        if ($user) {
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->db->get(array('*'), $this->table, array(array($field, '=', $user)));
            if ($data->count()) {
                $this->data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function exists() {
        return (!empty($this->data)) ? true : false;
    }

    public function data() {
        return $this->data;
    }

}