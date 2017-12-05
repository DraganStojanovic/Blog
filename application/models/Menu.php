<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Model{

  public $id;
  public $label;
  public $link;
  public $admin_only;
  public $logged;
  public $table = 'menu';

  //Funkcija za postavljanje svih polja klase na 'NULL' vrednost
  public function reset()
  {
    unset($this->id);
    unset($this->label);
    unset($this->link);
    unset($this->admin_only);
    unset($this->logged);
  }

  //Vraca sve linkove menija, na osnovu korisnika koji je, ili nije ulogovan, podatke daje u vidu objekta
  public function getNavigation()
  {

    $navigation = $this->db->get('menu')->result();       //Dohvatanje svih linkova iz baze

    //Ukolliko je baza vratila linkove
    if($navigation)
    {
        $links=array();

         //Ukoliko je korisnik logovan, proverava se da li je administrator, da bi mu se prikazao deo za administraciju
        if($this->session->userdata('role'))
        {
          //Ako je logovani korisnik admin, prikazuju mu se svi linkovi
          if($this->session->userdata('role')=='Admin')
          {
            foreach($navigation as $link)
            {
              //Ukoliko je korisnik ulogovan, ne ucitava mu se link za logovanje
              if($link->label!='Login')
              array_push($links,$link);
            }
          }
          //U suprotnom, filtriraju se linkovi koji su namenjeni iskljucivo za administratore
          else
          {
            foreach($navigation as $link){
              if(!$link->admin_only)
              {
                //Ukoliko je korisnik ulogovan, ne ucitava mu se link za logovanje
                if($link->label!='Login')
                array_push($links,$link);
              }
            }
          }

          return $links;
        }

        //Ukoliko trenutno korisnik nije logovan, prikazuju mu se standardni linkovi
        else
        {

          foreach($navigation as $link)
          {
            if(!$link->logged)
            {
              array_push($links,$link);
            }
          }
          return $links;
        }
    }

    else
    {
    return 'Došlo je do greske sa čitanjem podataka iz baze.';
    }

  }//Kraj funkcije getNavigation

  //Funkcija za dohvatanje pozadinske slike za stranicu na osnovu imena kontrolera
 /* public function getBacground()
  {
    $this->db->select('picture');
    $this->db->from('menu');
    $this->db->where('label', $this->label);
    return $this->db->get()->row()->picture;
  }*/

  public function getMenu()
  {
    return $this->db->get($this->table)->result();
  }

  public function insert()
  {
    $insertData = array(
      'label' => $this->label,
      'link' => $this->link,
      'admin_only' => $this->admin_only,
      'logged' => $this->logged
    );
    return $this->db->insert('menu', $insertData);
  }

  public function delete()
  {
    return $this->db->delete('menu', array('id'=>$this->id));
  }

  //funkcija za dohvatanje jednog linka na osnovu id-a
  public function getLink()
  {
    $this->db->where('menu.id', $this->id);
    return $this->db->get('menu')->row();
  }

  public function edit($updateData)
  {
    $this->db->where('menu.id', $this->id);
    return $this->db->update('menu',$updateData);
  }

}
