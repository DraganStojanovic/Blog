<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends Frontend_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Activity');
  }

  function index()
  {
    //Odabir strana za ucitavanje
    $data['pages']=array('contact');
    $data['title']="Contact";
    $data['headerTitle']="Contact";
    $data['headerSubtitle']="Send us a message";

    //Dohvatanje pozadinske slike iz baze podataka
    $this->load->model('Menu');
    $this->Menu->label='contact';
    $data['picture']=$this->Menu->getBacground();

    //Prosledjivanje metodu u natklasi
    $this->load_views($data);
  }

}
