<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Model{

  public $id;
  public $role;
  public $table = 'role';

  public function getRoles()
  {
    return $this->db->get($this->table)->result();
  }
}
