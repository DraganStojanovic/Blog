<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends Frontend_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Activity');
  }

  function index($page=null)
  {
    //Odabir strana za ucitavanje
    $data['pages']=array('gallery');

    //Postavljanje imena strane
    $data['title']="Gallery";
    $data['headerTitle']="Gallery";
    $data['headerSubtitle']="Browse Marvel's pictures";

    $this->load->model("Galler");
    $data['pictures'] = $this->Galler->get();
    //Dohvatanje pozadinske slike iz baze podataka
    $this->load->model('Menu');
    $this->Menu->label='gallery';
    $data['picture']=$this->Menu->getBacground();



    //Prosledjivanje metodu u natklasi
    $this->load_views($data);
  }
  
  function download()
  {
    if($this->session->userdata('role')=='admin')
    {
    	$this->load->helper('download');
	$name = base_url() . 'files/uploads/documentation.pdf';

	force_download($name); 
    }
    else
    {
    	redirect(base_url());
    }
  }
  

}
