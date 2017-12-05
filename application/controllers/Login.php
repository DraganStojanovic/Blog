<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Frontend_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Activity');
  }

  function index()
  {
    redirect(base_url() . 'login/login');
  }

  function login($username = null)
  {
    //Ukoliko je korisnik vec ulogovan, a pokusao je da pristupi ovoj strani, biva vracen na pocetnu
    if($this->session->userdata('userId'))
    {
      redirect(base_url());
    }
    //U suprotnom, generise se forma za logovanje i loguje korisnik
    else
    {

      //Postavljanje naslova stranice i teksta u header-u
      $data['title']="Log in";
      $data['headerTitle']="Welcome";
      $data['headerSubtitle']="Please log in";

      //Dohvatanje pozadinske slike iz baze podataka
      $this->load->model('Menu');
      $this->Menu->label='login';
      $data['picture']=$this->Menu->getBacground();

      //Odabir strana za ucitavanje
      $data['pages']=array('login');

      //Generisanje svojstava za pravljenje forme preko funkcije form_open()
      $data['action']= base_url () . 'login/login';
      $data['attributes']=array(
        'method' => 'post'
      );

      $data['inputs']=array(
        0 =>array(
          'name' => 'loginUsername',
          'id' => 'loginUsername',
          'value' => $username,
          'type' => 'text',
          'placeholder' => 'Username',
          'required' => ''
        ),
        1 =>array(
          'name' => 'loginPassword',
          'id' => 'loginPassword',
          'type' => 'password',
          'placeholder' => 'Password',
          'required' => ''
        ),

        2 =>array(
          'class' => 'login loginmodal-submit',
          'name' => 'loginSubmit',
          'id' => 'loginUsername',
          'value' => 'Login',
          'type' => 'submit',
        ),
      );

      //Tek kada je pritisnut button 'Login', proveravaju se podaci korisnika i na osnovu njih pravi sesija
      if($this->input->post('loginSubmit'))
      {
        //Ucitavanje vrednosti iz polja koja je popunio korisnik
        $username = $this->input->post('loginUsername');
        $password = $this->input->post('loginPassword');

        //Ucitavanje modela za rad sa korisnicima
        $this->load->model('User');

        //Postavljanje vrednosti polja objekta modela, na osnovu kojih ce se izvrsiti upit
        $this->User->password=$password;
        $this->User->username=$username;


        //Poziva se metod iz modela za pravljenje sesije na osnovu korisnika, ukoliko autorizacija nije prosla, metod vraca false
        $result = $this->User->createSession();
        
        //Ukoliko je uspelo pravljenje sessije, na osnovu podataka sesije, korisnik se redirektuje na odredjenu stranu
        //U suprotnom, korisnik se ponovo vraca na stranu za logovanje, sa sacuvanim username-om (line 86)
        if($result)
        {
          //Dodavanje aktivnosti
          $this->Activity->message = 'Korisnik logovan!';
          $this->Activity->add();

          switch($this->session->userdata('role'))
          {
            case 'Admin':
              redirect(base_url() . 'administration');
              break;
            case 'User':
              redirect(base_url());
              break;
          }
        }
        else
        {
          $this->session->set_flashdata('error', 'Please check your username or password.');
          redirect(base_url() . 'login/login/' . $username);
        }
      }
    }



    //Prosledjivanje metodu u natklasi
    $this->load_views($data);
  }//Kraj login funkcije

  //Funkcija za reigstraciju korisnika. U okviru nje se, kao i u funkciji za logovanje, generise ceo sadrzaj forme.
  public function register($username = null, $firstname = null, $lastname = null)
  {

    //Zabrana pristupa kontroleru za korisnika koji je ulogovan
    if($this->session->userdata('userId'))
    {
      redirect(base_url());
    }
    else
    {
        //Postavljanje naslova stranice i teksta u header-u
        $data['title']="Register";
        $data['headerTitle']="Welcome";
        $data['headerSubtitle']="Please register";

        //Dohvatanje pozadinske slike iz baze podataka
        $this->load->model('Menu');
        $this->Menu->label='login';
        $data['picture']=$this->Menu->getBacground();

        //Odabir strana za ucitavanje
        $data['pages']=array('login');

        //Generisanje svojstava za pravljenje forme za registraciju, pomocu funkcije form_open i view-u
        $data['action']= base_url () . 'login/register';
        $data['attributes']=array(
          'method' => 'post'
        );

        //Definisanje svih input polja, njihovih atributa i inicijalnih vrednosti ako postoje
        $data['inputs']=array(
          0 =>array(
            'name' => 'regUsername',
            'id' => 'regUsername',
            'value' => $username,
            'type' => 'text',
            'placeholder' => 'Username',
            'required' => ''
          ),
          1 =>array(
            'name' => 'regFirstName',
            'id' => 'regFirstName',
            'value' => $firstname,
            'type' => 'text',
            'placeholder' => 'First name',
            'required' => ''
          ),
          2 =>array(
            'name' => 'regLastName',
            'id' => 'regLastname',
            'value' => $lastname,
            'type' => 'text',
            'placeholder' => 'Last name',
            'required' => ''
          ),
          3 =>array(
            'name' => 'regEmail',
            'id' => 'regEmail',
            'value' => '',
            'type' => 'text',
            'placeholder' => 'Email',
            'required' => ''
          ),

          4 =>array(
            'name' => 'regPassword',
            'id' => 'regPassword',
            'value' => '',
            'type' => 'password',
            'placeholder' => 'Password',
            'required' => ''
          ),

          5 =>array(
            'name' => 'regConfirm',
            'id' => 'regConfirm',
            'value' => '',
            'type' => 'password',
            'placeholder' => 'Confirm password',
            'required' => ''
          ),


          6 =>array(
            'class' => 'login loginmodal-submit',
            'name' => 'regSubmit',
            'id' => 'regSubmit',
            'value' => 'Register',
            'type' => 'submit',
          ),
        );

        //Ukoliko je korisnik kliknuo na submit dugme za registraciju, ulece se ovde
        if($this->input->post('regSubmit'))
        {
          //Ucitavanje biblioteke za validaciju forme
          $this->load->library('form_validation');

          //Postavljanje pravila za validaciju
          //Field - name atribut polja
          //Label - ime koje ce polje imati ukoliko se budu ispisivale greske
          //Rules - set pravila koja korisnik mora da zadovolji pri unosu podataka u Postavljanje
          //errors - niz gresaka koje su nastale ukoliko korisnik nije ispostovao pravila, ispisuju se korisniku
          $rules = array(
            array(
              'field' => 'regUsername',
              'label' => 'Username',
              'rules' => 'required|min_length[3]|max_length[15]|is_unique[user.username]',
              'errors' => array(
                'required' => 'You have not provided $s.',
                'is_unique' => 'This %s already exists.',
                'min_length[3]' => '%s should not cointain less than 3 characters.',
                'max_length[15]' => '$s should not cointan more than 15 characters.'
              )
            ),
          array(
            'field' => 'regFirstName',
            'label' => 'Last name',
            'rules' => 'required|min_length[2]|max_length[15]',
            'errors' => array(
              'required' => 'You have not provided $s.',
              'min_length[2]' => '%s should not cointain less than 2 characters.',
              'max_length[15]' => '$s should not cointan more than 15 characters.'
            )
          ),
          array(
            'field' => 'regLastName',
            'label' => 'Last name',
            'rules' => 'required|min_length[2]|max_length[15]',
            'errors' => array(
              'required' => 'You have not provided $s.',
              'min_length[2]' => '%s should not cointain less than 2 characters.',
              'max_length[15]' => '$s should not cointan more than 15 characters.'
            )
          ),
          array(
            'field' => 'regEmail',
            'label' => 'email',
            'rules' => 'required|valid_email|is_unique[user.email]',
            'errors' => array(
              'required' => 'You have not provided $s.',
              'valid_email' => 'You have not provided valid %s format.',
              'is_unique' => 'This %s already exists.'
            )
          ),
          array(
            'field' => 'regPassword',
            'label' => 'Password',
            'rules' => 'required|min_length[5]',
            'errors' => array(
              'required' => 'You have not provided $s.',
              'min_length[5]' => '%s should not cointain less than 5 characters.',
            )
          ),
          array(
            'field' => 'regConfirm',
            'label' => 'Confirm password',
            'rules' => 'required|matches[regPassword]',
            'errors' => array(
              'required' => 'You have not provided $s.',
              'matches' => 'Passwords do not match.',
            )
          ),
          );

          //Postavljanje definisanih pravila
          $this->form_validation->set_rules($rules);

          //Ispitivanje da li je korisnik zadovoljio gore navedene kriterijume
          if($this->form_validation->run())
          {
            //Ucitavanje modela za upravljanje korisnicima
            $this->load->model('User');
            //Postavljanje vrednosti polja korisnika na ona koja je korisnik uneo u formu
            $this->User->username = $this->input->post('regUsername');
            $this->User->name = $this->input->post('regFirstName');
            $this->User->surname = $this->input->post('regLastName');
            $this->User->email = $this->input->post('regEmail');
            $this->User->password = $this->input->post('regPassword');
            $this->User->registration_date = $date = date('Y-m-d H:i:s');

            //Poziv funkcije za pravljenje novog korisnika na osnovu unetih podataka, funkcija vraca ID novonapravljenog korisnika
            $newUserID = $this->User->newUser();

            //Obavestavanje korisnika o uspesnosti registracije
            if($newUserID)
            {
              //Dodavanje aktivnosti
              $this->Activity->message = 'Registrovan novi korisnik!';
              $this->Activity->add();

              $data['success']='Registration successfull! Please check ' . $this->User->email . ' to verify your account.';
            }
            else
            {
              $data['error']="Something went wrong!";
            }
          }
          //Ukoliko validacija forme nije prosla, korisnik se redirektuje na formu za registraciju, sa odredjenim podacima vec popunjenenim u formu
          else
          {
            $username = $this->input->post('regUsername');
            $firstname = $this->input->post('regFirstName');
            $lastname = $this->input->post('regLastName');
            $this->session->set_flashdata('error',validation_errors());
            redirect(base_url().'login/register/' . $username . '/' . $firstname . '/' . $lastname . '/' . $email);
          }
        }
        //Ucitavanje view-a ('login' sa potrebnim podacima)
        $this->load_views($data);
    }
  }





  public function logout()
  {
    $this->session->sess_destroy();
    redirect(base_url());
  }

}
