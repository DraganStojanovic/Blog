<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model{

  public $id;
  public $username;
  public $password;
  public $name;
  public $surname;
  public $email;
  public $active;
  public $last_login;
  public $role_id = 2;
  public $registration_date;
  public $table = 'user';
  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  //Funkcija za postavljanje svih polja klase na 'NULL' vrednost
  public function reset()
  {
    unset($this->id);
    unset($this->username);
    unset($this->password);
    unset($this->name);
    unset($this->surname);
    unset($this->email);
    unset($this->active);
    unset($this->last_login);
    unset($this->role_id);
    unset($this->registration_date);
  }

  //Funkcija za pravljenje sesije, pristupa joj login
  public function createSession()
  {
    //Autentifikacija korisnika
    $this->db->select('user.username as username ,user.id as userId,role.name as role');
    $this->db->from('user');
    $this->db->join('role', 'user.role_id=role.id');
    $this->db->where('username', $this->username);
    $this->db->where('password', md5($this->password));
    $result = $this->db->get()->row();


    //Ukoliko korisnik postoji, na osnovu njegovih podataka se pravi sesija
    if(!empty($result))
    {
      //Pravljenje niza podataka na osnovu kog se pravi sesija
      $sessionData = array(
        'role' => $result->role,
        'username' => $result->username,
        'userId' => $result->userId
      );

      //Unistavanje podataka iz sesije za anketu, ukoliko je neko nelogovan glasao, kako bi logovani korisnici mogli da glasaju
      $this->load->model('Survey');
      $surveys = $this->Survey->getSurveys();
      if($surveys)
      {
        foreach($surveys as $sr)
        {
          $this->session->unset_userdata('surveyId_' . $sr->id);
        }
      }
      //Pravljenje sesije na osnovu niza
      $this->session->set_userdata($sessionData);
      return true;
    }
    else return false;

  }

  //Funkcija za pravljenje novog korisnika, vraca id unetog korisnika korisnika, ili false ukoliko unos nije uspeo
  public function newUser()
  {
    //Definisanje kolona cije vrednosti se popunjavaju i njihovih vrednosti
    $data=array(
      'name' => $this->name,
      'surname' => $this->surname,
      'username' => $this->username,
      'email' => $this->email,
      'password' => md5($this->password),
      'registration_date' => $this->registration_date,
      'role_id' => $this->role_id
    );
    //Query builder za unos podataka
    $result = $this->db->insert('user',$data);
    //Ukoliko je izvrsavanje upita uspesno, funkcija vraca ID unetog korisnika, u suprotnom vraca 'false'
    if(!empty($result))
    {
      return $this->db->insert_id();
    }
    else {
      return false;
    }
  }

  public function getUsers()
  {
    $this->db->select('user.id,user.name, user.surname, user.username, user.email, user.active, user.registration_date, user.last_login, role.name as role');
    $this->db->from($this->table);
    $this->db->join('role', $this->table . '.role_id = role.id');
    return $this->db->get()->result();
  }

  public function getUser()
  {
    $this->db->select('user.id,user.name, user.surname, user.username, user.email, user.active, user.registration_date, user.last_login, role.name as role');
    $this->db->from($this->table);
    $this->db->join('role', $this->table . '.role_id = role.id');
    $this->db->where('user.id', $this->id);
    return $this->db->get()->row();
  }

  public function editUser()
  {
    $updateData = array(
      'username' => $this->username,
      'password' => md5($this->password),
      'name' => $this->name,
      'surname' => $this->surname,
      'email' => $this->email,
      'role_id' => $this->role_id
    );

    $this->db->where('user.id', $this->id);
    return $this->db->update('user',$updateData);
  }


  public function delete()
  {
    $this->db->where('id', $this->id);
    return $this->db->delete('user');
  }

}
