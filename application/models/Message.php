<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CI_Model{

  public $id;
  public $name;
  public $email;
  public $phone;
  public $message;
  public $table = 'message';

  public function __construct()
  {
    parent::__construct();
  }


  public function insertMessage()
  {
    $insertData = array(
      'name' => $this->name,
      'email' => $this->email,
      'phone' => $this->phone,
      'message' => $this->message,
      'time' => date('Y-m-d H:i:s')
    );

    $this->db->insert($this->table, $insertData);

    if($this->db->insert_id())
    {
      return true;
    }
    else {
      return false;
    }
  }

  public function getMessages()
  {
    return $this->db->get($this->table)->result();
  }

  public function delete()
  {
    $this->db->where('id', $this->id);
    $this->db->delete($this->table);
  }

}
