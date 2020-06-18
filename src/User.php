<?php

namespace PHP\Auth;

use Exception;
use PHP\CRUD\Database as DB;

class User 
{

    private $table = 'users';
    protected $db;
    protected $data;
    private $fields = array(
        'ID' => 'id',
        'USERNAME' => 'username',
        'SESSION' => 'session'
    );

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
            $field = (is_numeric($user)) ? $this->fields['ID'] : $this->fields['USERNAME'] ;
            $data = $this->db->get(array('*'), $this->table, array(array($field, '=', $user)));
            if ($data->count()) {
                $this->data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function getId()
    {
        return $this->data()->id;
    }

    public function getPassword()
    {
        return $this->data()->password;
    }

    public function resetSession()
    {
        $this->db->update($this->table, $this->getId(), array(
            $this->fields['SESSION'] => ''
        ));
    }

    public function getSession($hash = null)
    {
        if($this->data()->session) {
            return $this->data()->session;
        } else {
            return $this->db->get(array('*'), $this->table, array(array(
                $this->fields['SESSION'], '=', $hash
            )));
        }
    }

    public function getRole()
    {
        return $this->data()->role;
    }

    public function setSession($hash)
    {
        $this->db->update($this->table, $this->getId(), array(
            $this->fields['SESSION'] => $hash
        ));
    }

    public function exists() {
        return (!empty($this->data)) ? true : false;
    }

    public function data() {
        return $this->data;
    }

}