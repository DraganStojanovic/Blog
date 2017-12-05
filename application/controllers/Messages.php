<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Activity');
  }

  public function send()
  {
    $this->load->model('Message');
    $this->Message->name = $this->input->post('name');
    $this->Message->email = $this->input->post('email');
    $this->Message->phone = $this->input->post('phone');
    $this->Message->message = $this->input->post('message');

    $result = $this->Message->insertMessage();

    //Dodavanje aktivnosti
    $this->Activity->message = 'Stigla nova poruka!';
    $this->Activity->add();

    if($result)
    {
      echo 'Message sent successfully!';
    }
    else
    {
      echo 'Something went wrong!';
    }


  }

}
