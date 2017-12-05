<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anketa extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Survey_options');
    $this->load->model('Survey');
    $this->load->model('Activity');
  }

  function index()
  {

  }

  public function glasaj()
  {
    if($this->input->post('voteId')){
      $this->Survey_options->id = $this->input->post('voteId');

      if($this->session->userdata('surveyId_' . $this->input->post('surveyId')))
      {
        echo 'You can vote only one time!';
        $voted = false;
      }
      else
      {
        $voted = $this->Survey_options->vote();
      }
      //Ukoliko je glasanje odradjeno uspesno, treba ucitati tabelu sa azuriranim glasovima
      if($voted)
      {

        //Postavi se polje survey_id, da bi se dohvatile samo opcije za trenutnu anketu
        $this->Survey_options->survey_id=$this->input->post('surveyId');

        //U sesiju se belezi da je dodat glas, tacnije belezi se id ankete za koju je dodat glas i postavlja true
        $this->session->set_userdata('surveyId_' . $this->input->post('surveyId'), true);
        //Dohvataju se sve opcije trenune ankete, koje treba prikazati korisniku
        $data['surveyOptions'] = $this->Survey_options->getSurveyOptions();
        //Dohvatanje ukupnog broja glasova ankete radi procentualnog prikaza

        $this->Survey->id = $this->input->post('surveyId');
        $data['votesNumber'] = $this->Survey->getSurvey()->votesNumber;
        $data['success'] = "Thanks four your vote!";

        //Dodavanje aktivnosti
        $this->Activity->message = 'Dodat glas na anketu!';
        $this->Activity->add();

        //Ucitavanje view-a na osnovu podataka
        $this->load->view('anketa_tabela',$data);
      }
    }
    else
    {
      echo '<p class="text-danger">Ups.. something got wrong.</p>';
    }

  }

  public function edit()
  {
    if($this->input->post('surveyId'))
    {
      $surveyId = $this->input->post('surveyId');
      $this->Survey_options->survey_id = $surveyId;
      $surveyOptions = $this->Survey_options->getSurveyOptions();

      $data['inputs'] = array();
      echo '<div id="anket_inputs"><table>';
      foreach($surveyOptions as $s)
      {
        $input = array(
          'type' => 'text',
          'id' => $s->id,
          'value' => $s->name,
          'required', '',
          'class' => 'form-control anketa_edited_' . $surveyId,
          'style' => 'margin-top:10px; margin-bottom:10px;'
        );
        echo '<tr><td>';
        echo form_input($input) . '</td><td>';
        echo anchor('#anketa', '<i onclick="anketa_option_remove('. $s->id .', '. $surveyId .')" class=" fa fa-close"></i>') . '</td>';
        echo '</tr>';
      }

      $editButton = array(
        'type' => 'button',
        'onclick' => 'edit_survey_insert(' . $surveyId . ')',
        'class' => 'btn btn-info',
        'value' => 'edit'
      );

      $addRow = array(
        'type' => 'button',
        'class' => 'btn btn-warning btn-xs',
        'value' => 'Add row',
        'onclick' => 'anketa_add_row(' . $surveyId . ')'
      );
      echo "</table></div>";
      echo "<div class='text-danger small' id='edit_error_" .$surveyId . "'></div>";
      echo form_input($addRow);
      echo form_input($editButton);
    }

    else
    {
      //Ukoliko su prosledjeni podaci za edit, vrsi se izmena ankete
      //Moguca je izmena trenutnih podataka ili dodavanje novih
      if($this->input->post('edit'))
      {
        //Dohvatanje podataka za izmenu
        $insertData = $this->input->post('insertData');
        $editData = $this->input->post('editData');
        $editId = $this->input->post('editId');
        //Ucitavanje modela za rad sa opcijama ankete
        $this->Survey_options->survey_id=$editId;

        //Ukoliko podaci postoje, vrsi se insert i update
        //Podaci za insert i update se prosledjuju u obliku niza, tako da se radi bach insert i batch update
        if($insertData)
        {
          $insert = $this->Survey_options->insertOptions($insertData);
        }
        if($editData)
        {
          $update = $this->Survey_options->updateOptions($editData);
        }

        //Dohvataju se sve opcije trenune ankete, koje treba prikazati korisniku
        $data['surveyOptions'] = $this->Survey_options->getSurveyOptions();
        //Dohvatanje ukupnog broja glasova ankete radi procentualnog prikaza
        $this->Survey->id = $editId;
        $data['votesNumber'] = $this->Survey->getSurvey()->votesNumber;
        //Ucitavanje view-a na osnovu podataka
        $this->load->view('anketa_tabela',$data);
      }

    }
  }


  public function remove()
  {
    if($this->input->post('removeOption'))
    {
      $this->Survey_options->id = $this->input->post('optionId');
      $this->Survey_options->survey_id = $this->input->post('surveyId');
      $deleteResult = $this->Survey_options->delete();

      //Dodavanje aktivnosti
      $this->Activity->message = 'Uklonjena opcija za anketu!';
      $this->Activity->add();

       //Dohvataju se sve opcije trenune ankete, koje treba prikazati korisniku
        $data['surveyOptions'] = $this->Survey_options->getSurveyOptions();
        //Dohvatanje ukupnog broja glasova ankete radi procentualnog prikaza
        $this->Survey->id = $this->input->post('surveyId');
        $data['votesNumber'] = $this->Survey->getSurvey()->votesNumber;
        //Ucitavanje view-a na osnovu podataka
        $this->load->view('anketa_tabela',$data);

    }
  }
}
