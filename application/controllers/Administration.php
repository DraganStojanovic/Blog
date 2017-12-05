<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administration extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
        $this->load->model('Role');
        $this->load->model('User');
        $this->load->model('Post');
        $this->load->model('Menu');
        $this->load->model('Message');
        $this->load->model('Survey_options');
        $this->load->model('Survey');
        $this->load->model('Activity');
        $this->load->model('Galler');
  }


  function index()
  {
      if($this->input->post('upload'))
      {
        $config['upload_path'] = 'files/uploads/gallery/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 0;
                $config['max_width']            = 0;
                $config['max_height']           = 0;

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload('picture'))
                {
                        $data['error'] = array('error' => $this->upload->display_errors());
                }
                else
                {
                        $data['uploaded'] = array('upload_data' => $this->upload->data());
                        $this->load->model('Galler');
                        $this->Galler->picture = $data['uploaded']['upload_data']['file_name'];
                        $this->Galler->insert();

                      $filename =$data['uploaded']['upload_data']['file_name'];
                      $source_path = "files/uploads/gallery/" . $filename;
                      $target_path = "files/uploads/gallery/thumbnails/";
                      $config = array(
                        'image_library' => 'gd2',
                        'source_image' => $source_path,
                        'new_image' => $target_path,
                        'maintain_ratio' => FALSE,
                        'create_thumb' => TRUE,
                        'thumb_marker' => '',
                        'width' => 400,
                        'height' => 300
                      );
                      $this->load->library('image_lib', $config);
                      if (!$this->image_lib->resize())
                      {
                        var_dump($this->image_lib->display_errors());
                      }
              }
      }
      $data['pictures'] = $this->Galler->get();
      $data['links'] = $this->Menu->getNavigation();
      $data['title'] = 'Administration';
      $data['messages'] = $this->Message->getMessages();
      $data['blog_posts'] = $this->Post->postsCount();
      $data['reg_users'] =count($this->User->getUsers());
      $data['surveys'] = count($this->Survey->getSurveys());
      $data['msg'] = count($this->Message->getMessages());
      $this->load->view('head',$data);
      $data['activities'] = $this->Activity->get();
      $this->load->view('/admin/administration',$data);
      $this->load->view('footer');
  }

  //Funkcija za baratanje postovima na admin panelu, pristupa joj se ajaxom
  public function posts($tip=null)
  {
      switch($tip)
      {
          case 'get':
            $data['posts'] = $this->Post->getPostsForAdmin();
            $this->load->view('admin/posts_reload',$data);
            break;
          case 'insert':
              $target_dir = "files/uploads/pages/";
              $target_file = $target_dir . basename($_FILES["postPicture"]["name"]);
              $uploadOk = 1;
              $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

              // Check if file already exists
              if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
              }
              // Check file size
              if ($_FILES["postPicture"]["size"] > 50000000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
              }
              // Allow certain file formats
              if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
              && $imageFileType != "gif" ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
              }
              // Check if $uploadOk is set to 0 by an error
              if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
              // if everything is ok, try to upload file
              } else {
                if (move_uploaded_file($_FILES["postPicture"]["tmp_name"], $target_file)) {
                    $this->Post->picture = basename( $_FILES["postPicture"]["name"]);
                    $this->Post->title = $this->input->post('postTitle');
                    $this->Post->subtitle = $this->input->post('postSubtitle');
                    $this->Post->content = $this->input->post('postContent');
                    $this->Post->link = $this->input->post('postLink');
                    $this->Post->author_id = $this->session->userdata('userId');
                    $this->Post->posting_date = date('Y-m-d H:i:s');

                    $upis = $this->Post->insert();
                    $data['posts'] = $this->Post->getPostsForAdmin();

                    //Dodavanje aktivnosti
                    $this->Activity->message = 'Added new post!';
                    $this->Activity->add();

                    $this->load->view('admin/posts_reload',$data);

                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
              }
              break;
              case 'edit':
              $target_dir = "files/uploads/pages/";
              $target_file = $target_dir . basename($_FILES["postPicture"]["name"]);
              $uploadOk = 1;
              $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

              // Check if file already exists
              if (file_exists($target_file)) {
                $this->Post->id = $this->input->post('postId');
                $updateData = array(
                "title" => $this->input->post('postTitle'),
                "subtitle" => $this->input->post('postSubtitle'),
                "content" => $this->input->post('postContent'),
                "link" => $this->input->post('postLink'),
                "author" => $this->session->userdata('userId'),
                "posting_date" => date('Y-m-d H:i:s')
                );
                $update = $this->Post->update($updateData);
                //Dodavanje aktivnosti
                $this->Activity->message = 'Post updated!';
                $this->Activity->add();
                 $data['posts'] = $this->Post->getPostsForAdmin();
                 $this->load->view('admin/posts_reload',$data);
              }
            else
            {
              if ($_FILES["postPicture"]["size"] > 50000000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
              }
              // Allow certain file formats
              if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
              && $imageFileType != "gif" ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
              }
              // Check if $uploadOk is set to 0 by an error
              if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
              // if everything is ok, try to upload file
              } else {
                if (move_uploaded_file($_FILES["postPicture"]["tmp_name"], $target_file)) {
                    $this->Post->id = $this->input->post('postId');
                    $updateData = array(
                    "picture" => basename( $_FILES["postPicture"]["name"]),
                    "title" => $this->input->post('postTitle'),
                    "subtitle" => $this->input->post('postSubtitle'),
                    "content" => $this->input->post('postContent'),
                    "link" => $this->input->post('postLink'),
                    "author" => $this->session->userdata('userId'),
                    "posting_date" => date('Y-m-d H:i:s')
                    );
                    $update = $this->Post->update($updateData);
                    //Dodavanje aktivnosti
                    $this->Activity->message = 'Post updated!';
                    $this->Activity->add();
                    $data['posts'] = $this->Post->getPostsForAdmin();
                    $this->load->view('admin/posts_reload',$data);
                }
            }
      }

  }
  }

  //Funkciji se pristupa preko ajaxa
  public function navigation($tip=null)
  {
      //Ucitavanje biblioteke i postavljanje parametara za tabelu, na osnovu kojih ce se raditi refresh na svaki dogadjaj pri radu
      //Na admin panelu sa navigacijom (svaki put kada se izvrsi insert, update ili delete, radi se refresh)
      $this->load->library('table');
      $template = array('table_open' => '<table class="text-center small table table-hover table-bordered">');
      $this->table->set_template($template);
      $this->table->set_heading('#','Label','Link','Admin only','Logged','Picture','Edit','Delete');

      //Odabir tipa dogadjaja, prosledjuje se kao parametar funkciji, preko ajaxa
      if($tip)
      {
        switch($tip)
        {
              case 'insert':
                $this->Menu->label = $this->input->post('label');
                $this->Menu->link = $this->input->post('link');
                $this->Menu->admin_only = $this->input->post('admin_only');
                $this->Menu->logged = $this->input->post('logged');
                $inserted = $this->Menu->insert();
                //Dodavanje aktivnosti
                $this->Activity->message = 'Added new link!';
                $this->Activity->add();
              break;
              //U ovom koraku se dohvataju podaci iz baze, i prikazuju i view-u, u formi za izmenu
              //Nakon klika na dugme edit, ulazi se u slucaj edited gde se izmenjeni podaci obradjuju
              case 'edit':
                $this->Menu->id = $this->input->post('id');
                $link = $this->Menu->getLink();
                if($link)
                {
                  $data['id'] = $link->id;
                  $data['admin'] = $link->admin_only;
                  $data['logged'] = $link->logged;
                  $data['link'] = $link->link;
                  $data['label'] = $link->label;
                }
              break;
              case 'edited':
                  $this->Menu->id = $this->input->post('id');
                  $updateData = array(
                  "label" => $this->input->post('label'),
                  "link" => $this->input->post('link'),
                  "admin_only" => $this->input->post('admin_only'),
                  "logged" => $this->input->post('logged'),
                  );
                  $edited = $this->Menu->edit($updateData);
                  //Dodavanje aktivnosti
                  $this->Activity->message = 'Edited link!';
                  $this->Activity->add();
              break;
              case 'delete':
                $this->Menu->id = $this->input->post('id');
                $deleted = $this->Menu->delete();
                //Dodavanje aktivnosti
                $this->Activity->message = 'Deleted link!';
                $this->Activity->add();
              break;
        }
      }
      //Dohvatanje linkova i postavljanje podataka za tabelu na osnovu tih podataka
      $links = $this->Menu->getMenu();
      $i = 1;
      $tableData = array();
      foreach($links as $link)
      {
        array_push($tableData, array(
          $i++,
          $link->label,
          $link->link,
          $link->admin_only,
          $link->logged,
          $link->picture,
          "<i style='padding:0px;' onclick=admin_navigation('edit'," . $link->id . ") class='btn fa fa-pencil-square-o' aria-hidden='true'></i>",
          "<i style='padding:0px;' onclick=admin_navigation('delete'," . $link->id . ") class='btn fa fa-trash-o' aria-hidden='true'></i>",
        ));
      }
      //Generisanje linkova za tabelu, koji se prosledjuje view-u za prikaz
      $data['tabela'] = $this->table->generate($tableData);
      //Ucitavanje view-a koji se loaduje u div-u na admin panelu po odradjenom dogadjaju
      $this->load->view('admin/navigation_reload',$data);
  }

  public function users($tip=null)
  {
    //Ukoliko je na panelu kliknuto za unos novog korisnika
    if($this->input->post('insert'))
    {
      //Postavljanje vrednosti polja korisnika za unos
      $this->User->username = $this->input->post('username');
      $this->User->password = $this->input->post('password');
      $this->User->name = $this->input->post('firstname');
      $this->User->surname = $this->input->post('lastname');
      $this->User->email = $this->input->post('email');
      $this->User->role_id = $this->input->post('role');
      $this->User->registration_date = date("Y-m-d H:i:s");

      //Dodavanje aktivnosti
      $this->Activity->message = 'Dodao novog korisnika!';
      $this->Activity->add();

      //Unos korisnika
      $insertResult = $this->User->newUser();
    }
    else
    {
      if($this->input->post('edit'))
      {
        $this->User->id = $this->input->post('id');
        $this->User->username = $this->input->post('username');
        $this->User->password = $this->input->post('password');
        $this->User->name = $this->input->post('firstname');
        $this->User->surname = $this->input->post('lastname');
        $this->User->email = $this->input->post('email');
        $this->User->role_id = $this->input->post('role');
        //Edit korisnika
        //Dodavanje aktivnosti
        $this->Activity->message = 'Izmenio korisnika!';
        $this->Activity->add();
        $insertResult = $this->User->editUser();
      }
    }

    //Ucitavanje view-a u kome je tabela i forma za korisnike
    $data['users'] = $this->User->getUsers();
    $data['roles'] = $this->Role->getRoles();
    $this->load->view('admin/users_reload',$data);
   }



  public function messages($tip=null)
  {
      //Ucitavanje biblioteke i postavljanje parametara za tabelu, na osnovu kojih ce se izvrsiti prikaz svih podataka iz tabele
      //I brisanje ukoliko se klikne na ikonicu za brisanje
      $this->load->library('table');
      $template = array('table_open' => '<table class="text-center small table table-hover table-bordered">');
      $this->table->set_template($template);
      $this->table->set_heading('#','First Name','Email','Phone','Message','Delete');

      //Ukoliko je prosledjen dogadjaj, vrsi se obrada dogadjaja na osnovu tipa, podaci se prosledjuju post-om, preko ajaxa
      if($tip)
      {
        switch($tip)
        {
          case 'delete':
            $this->Message->id = $this->input->post('id');
            $deleted = $this->Message->delete();
            //Dodavanje aktivnosti
            $this->Activity->message = 'Obrisana poruka!';
            $this->Activity->add();
          break;
        }
      }

      //Ucitavanje svih podataka i prosledjivanje view-u na prikaz
      $messages = $this->Message->getMessages();
      $tableData = array();
      $i = 1;
      foreach($messages as $m)
      {
        array_push($tableData, array(
          $i++,
          $m->name,
          $m->email,
          $m->phone,
          $m->message,
          '<i style="padding:0px;" onclick=admin_messages("delete",'. $m->id .') class="btn fa fa-trash-o" aria-hidden="true"></i>',
        ));
      }
      $data['tabela'] =  $this->table->generate($tableData);
      $this->load->view('admin/messages_reload',$data);


  }

  public function surveys($tip=null)
  {
      //Ucitavanje biblioteke i postavljanje parametara za tabelu, na osnovu kojih ce se raditi refresh na svaki dogadjaj pri radu
      //Na admin panelu sa navigacijom (svaki put kada se izvrsi insert, update ili delete, radi se refresh)
      $this->load->library('table');
      $template = array('table_open' => '<table  class="text-center small table table-hover table-bordered">');
      $this->table->set_template($template);
      $this->table->set_heading('#','Option Name','Votes','Edit','Delete');

      //Ucitavanje svih opcija za prikaz u dropdown-u
      //Na osnovu kog ce se ucitavati tabela za opcije
      $ankete = $this->Survey->getSurveys();
      $options = array();
      foreach($ankete as $a)
      {
        array_push($options, array(
          $a->id => $a->name
        ));
      }

      $data['surveys'] = form_dropdown('surveys', $options, $options[0],'id=survey_dropdown onchange=admin_survey_refresh_options()');
      $this->Survey_options->survey_id = $this->Survey->getLast()->id;
      $this->Survey->id = $this->Survey->getLast()->id;
      $survey_name = $this->Survey->getSurvey()->name;
      $survey_id = $this->Survey->getSurvey()->id;
      $data['survey_name'] = array(
        array(
          'type' => 'text',
          'placeholder' => 'Survey name',
          'class' => 'form-control',
          "id" => "anketa_name",
          'value' => $survey_name
        ),
        array(
          'type' => 'hidden',
          "id" => "anketa_name_id",
          'value' => $survey_id
        )
      );

      //Postavljanje parametara za prikaz forme ispod tabele, za unos nove opcije
      $data['form_title'] = 'Add new option';
      $data['form_button_title'] = "Add";
      $data['form_button_onclick'] = "admin_survey_option('insert'," . $this->Survey_options->survey_id . ")";
      //Odabir tipa dogadjaja, prosledjuje se kao parametar funkciji, preko ajaxa
      if($tip)
      {
        switch($tip)
        {
              case 'insert':
                $this->Survey_options->survey_id = $this->input->post('survey_id');
                $this->Survey_options->name = $this->input->post('option_name');
                $this->Survey_options->insertOption();
                //Dodavanje aktivnosti
                $this->Activity->message = 'Dodata nova opcija za anketu!';
                $this->Activity->add();
                $this->Survey_options->survey_id = $this->input->post('survey_id');
                $data['samo_tabela'] = true;
              break;
              //U ovom koraku se dohvataju podaci iz baze, i prikazuju i view-u, u formi za izmenu
              //Nakon klika na dugme edit, ulazi se u slucaj edited gde se izmenjeni podaci obradjuju
              case 'edit':
                $this->Survey_options->id = $this->input->post('option_id');
                $data['form_title'] = 'Edit option';
                $data['form_button_title'] = 'Edit';
                $data['form_button_onclick'] = "admin_survey_option('edited'," . $this->Survey_options->id . ")";
                $data['form_input_value'] = $this->Survey_options->getName();
                $this->Survey_options->survey_id = $this->input->post('survey_id');
              break;
              case 'change_name':
              $this->Survey->id = $this->input->post('survey_id');
              $this->Survey->name = $this->input->post('name');
              $this->Survey->update_name();

              $ankete = $this->Survey->getSurveys();
              $options = array();
              foreach($ankete as $a)
              {
                array_push($options, array(
                  $a->id => $a->name
                ));
                $survey_name = $this->Survey->getSurvey()->name;
                $survey_id = $this->Survey->getSurvey()->id;
                $data['survey_name'] = array(
                  array(
                    'type' => 'text',
                    'placeholder' => 'Survey name',
                    'class' => 'form-control',
                    "id" => "anketa_name",
                    'value' => $survey_name
                  ),
                  array(
                    'type' => 'hidden',
                    "id" => "anketa_name_id",
                    'value' => $survey_id
                  )
                );
              }

              $data['surveys'] = form_dropdown('surveys', $options, $options[0],'id=survey_dropdown onchange=admin_survey_refresh_options()');
              $this->Survey_options->survey_id = $this->Survey->getLast()->id;
              break;
              case 'edited':
                $this->Survey_options->id = $this->input->post('option_id');
                $this->Survey_options->name = $this->input->post('option_name');
                $this->Survey_options->updateOption();
                //Dodavanje aktivnosti
                $this->Activity->message = 'Izmenjena opcija za anketu!';
                $this->Activity->add();
                $this->Survey_options->survey_id = $this->input->post('survey_id');
                $data['form_title'] = 'Add new option';
                $data['form_button_title'] = "Add";
                $data['form_button_onclick'] = "admin_survey_option('insert'," . $this->Survey_options->survey_id . ")";
                //$data['surveys'] = form_dropdown('surveys', $options, $this->input->post('survey_id'),'id=survey_dropdown onchange=admin_survey_refresh_options()');
              break;

              case 'delete':
              $this->Survey_options->id = $this->input->post('option_id');
              $this->Survey_options->survey_id = $this->input->post('survey_id');
              $this->Survey_options->delete();
              //Dodavanje aktivnosti
              $this->Activity->message = 'Obrisana opcija za anketu!';
              $this->Activity->add();
              $data['samo_tabela']=true;
              break;
              case 'refresh_table':
              $this->Survey_options->survey_id = $this->input->post('survey_id');


              $data['surveys'] = form_dropdown('surveys', $options, $this->Survey_options->survey_id,'id=survey_dropdown onchange=admin_survey_refresh_options()');
              $this->Survey->id = $this->input->post('survey_id');

              $survey_name = $this->Survey->getSurvey()->name;
              $survey_id = $this->Survey->getSurvey()->id;
              $data['survey_name'] = array(
                array(
                  'type' => 'text',
                  'placeholder' => 'Survey name',
                  'class' => 'form-control',
                  "id" => "anketa_name",
                  'value' => $survey_name
                ),
                array(
                  'type' => 'hidden',
                  "id" => "anketa_name_id",
                  'value' => $survey_id
                )
              );
              break;
        }
      }
      //Dohvatanje linkova i postavljanje podataka za tabelu na osnovu tih podataka
      $options = $this->Survey_options->getSurveyOptions();
      $i = 1;
      $tableData = array();
      foreach($options as $o)
      {
        array_push($tableData, array(
          $i++,
          $o->name,
          $o->votes,
          "<i style='padding:0px;' onclick=admin_survey_option('edit'," . $o->id . ") class='btn fa fa-pencil-square-o' aria-hidden='true'></i>",
          "<i style='padding:0px;' onclick=admin_survey_option('delete'," . $o->id . ") class='btn fa fa-trash-o' aria-hidden='true'></i>",
        ));
      }
      //Generisanje linkova za tabelu, koji se prosledjuje view-u za prikaz
      $data['tabela'] = $this->table->generate($tableData);
      //Ucitavanje view-a koji se loaduje u div-u na admin panelu po odradjenom dogadjaju
      $this->load->view('admin/survey_reload',$data);
  }

  //Funkcija za brisanje, parametar je tip brisanja, moze da bude user,message...
  //Kada se uradi brisanje, potrebno je ucitati view sa novonastalim podacima
  public function delete($type=null)
  {
    switch($type)
    {
      case 'user':
      {
        $this->User->id = $this->input->post('user_id');
        $this->User->delete();
        //Dodavanje aktivnosti
        $this->Activity->message = 'Obrisan korisnik!';
        $this->Activity->add();
        $data['users'] = $this->User->getUsers();
        $data['roles'] = $this->Role->getRoles();
        $this->load->view('admin/users_reload',$data);
      }
      break;
      case 'navigation':
      {

      }
      break;
      case 'survey':
      {

      }
      break;
      case 'message':
      {

      }
      break;
      case 'post':
      {
        $this->Post->id = $this->input->post('post_id');
        $this->Post->delete();
        //Dodavanje aktivnosti
        $this->Activity->message = 'Obrisan post!';
        $this->Activity->add();
        $data['posts'] = $this->Post->getPostsForAdmin();
        $this->load->view('admin/posts_reload',$data);
      }
      break;
    }
  }

  public function edit($type=null)
  {
    switch($type)
    {
      case 'user':
      {
        $this->User->id = $this->input->post('user_id');
        $data['users'] = $this->User->getUsers();
        $data['edit_user'] = $this->User->getUser();
        $data['roles'] = $this->Role->getRoles();
        $this->load->view('admin/users_reload',$data);
      }
      break;
      case 'navigation':
      {

      }
      break;
      case 'survey':
      {

      }
      break;
      case 'message':
      {

      }
      break;
      case 'post':
        $this->Post->id = $this->input->post('post_id');
        $data['edit_post'] = $this->Post->getPost();
         $data['posts'] = $this->Post->getPostsForAdmin();
         $this->load->view('admin/posts_reload',$data);
      break;
    }
  }


	function download() {
	ini_set("allow_url_fopen", 1);
    // load download helder
    $this->load->helper('download');
    // read file contents
    $data = file_get_contents(base_url('files/uploads/documentation.pdf'));
    force_download('documentation.pdf', $data);
}

public function delete_img($id)
{
    $this->Galler->id = $id;
    $src = $this->Galler->get_image_src()->src;
    unlink("files/uploads/gallery/thumbnails/" . $src);
    unlink("files/uploads/gallery/" . $src);
    $this->Galler->delete();
    redirect(base_url() . 'administration');
}

}
