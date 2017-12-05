<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity extends CI_Model{

  public $id;
  public $user_id;
  public $message;

  public function add()
  {

    if(isset($_SESSION['userId']))
    {
      $this->user_id = $_SESSION['userId'];
    }
    else $this->user_id = null;

    $insertData = array(
      'message' => $this->message,
      'user_id' => $this->user_id,
      'time' => date('Y-m-d H:i:s')
    );
    $this->db->insert('activity', $insertData);
  }

  public function get()
  {
  	$upit = "SELECT user.username as user_id, activity.message, activity.time FROM activity LEFT OUTER JOIN user ON activity.user_id = user.id ORDER BY activity.id 	desc LIMIT 15";
  	return $this->db->query($upit)->result();
  }

}
