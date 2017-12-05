<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frontend_Controller extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function load_views($data){
    //Ucitavanje head-a
    $this->load->view('head',$data);

    //Dohvatanje svih linkova za navigaciju iz baze i ucitavanje view-a na osnovu njih
    $this->load->model('Menu');
    $data['links']=$this->Menu->getNavigation();
    $this->load->view('navigation',$data);


    $this->load->view('header');

    //Ucitavanje ostalih strana, dobijenih iz potklase
    foreach($data['pages'] as $page)
    {
      $this->load->view($page);
    }

    //Ucitavanje footer-a
    $this->load->view('footer');

  }



}
