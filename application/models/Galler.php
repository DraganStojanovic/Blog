<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Galler extends CI_Model{

  public $id;
  public $picture;

  public function insert()
  {
    $insertData = array('picture' => $this->picture);
    $this->db->insert('gallery',$insertData);
  }

  public function get()
  {
    return $this->db->get('gallery')->result();
  }

  public function get_image_src()
  {
    $this->db->select('picture as src');
    $this->db->from('gallery');
    $this->db->where('id', $this->id);
    return $this->db->get()->row();
  }


  public function delete()
  {
    $this->db->where('id', $this->id);
    $this->db->delete("gallery");
  }
}
