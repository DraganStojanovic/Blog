<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Frontend_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Activity');
  }

  function index($page=null)
  {
    //Odabir strana za ucitavanje
    $data['pages']=array('allblogposts','surveys');

    //Dohvatanje svih dostupnih anketa iz baze za prikaz na home strani
    $this->load->model('Survey');
    //Dohvatanje id-a svakog survey-a, kako bi se dohvatile opcije za zeljeni survey
    $surveys = $this->Survey->getSurveys();

    //Dohvatanje opcija za svaki prosledjeni survay i postavljanja istih u asocijativni niz
    $data['surveys'] = array(); //Podaci koji ce se proslediti view-u survey
    $this->load->model('Survey_options'); //Model za rad sa survey opcijama



    //Za svaki postojeci survey u bazi, dohvataju se njegove opcije
    foreach($surveys as $survey)
    {
      $this->Survey_options->survey_id = $survey->id; //Postavljanje vrednosti polja u modelu survey_options, na osnovu kog se dohvataju opcije
      $surveyOptions = $this->Survey_options->getSurveyOptions(); //Dohvatanje svih opcija
      array_push($data['surveys'], array('survey' => $survey,'surveyOptions' => $surveyOptions)); //Unos kompletnih podataka u asocijatnvni niz, za prikaz
    }

    //Dohvatanje svih dostupnih postova iz baze
    $this->load->model('Post');
    $data['posts']=$this->Post->getPosts($page);
    $data['title']="Home";
    $data['headerTitle']="Welcome";
    $data['headerSubtitle']="To Luke's blog";

    //Dohvatanje pozadinske slike iz baze podataka
    $this->load->model('Menu');
    $this->Menu->label='home';
    $data['picture']=$this->Menu->getBacground();

    //Paginacija na pocetnoj strani
    $this->load->library('pagination');
    $config['base_url'] = base_url() . 'home/index';
    $config['total_rows'] = $this->Post->postsCount();
    $config['per_page'] = 3;
    $this->pagination->initialize($config);
    $data['pagination'] = $this->pagination->create_links();


    //Prosledjivanje metodu u natklasi
    $this->load_views($data);
  }

}
