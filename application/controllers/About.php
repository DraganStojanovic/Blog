<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends Frontend_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Activity');
  }

  function index()
  {
    //Odabir strana za ucitavanje
    $data['pages']=array('about');

    //Postavljanje imena strane
    $data['title']="About";
    $data['headerTitle']="About";
    $data['headerSubtitle']="Hi, im Luka Lukić";

    //Dohvatanje pozadinske slike iz baze podataka
    $this->load->model('Menu');
    $this->Menu->label='about';
    $data['picture']=$this->Menu->getBacground();

    //Prosledjivanje metodu u natklasi
    $this->load_views($data);
  }

}
