<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment extends CI_Model{

  public $id;
  public $user_id;
  public $post_id;
  public $comment;
  public $date_posted;
  public $table = 'comment';
  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  //Funkcija za upis novog komentara u bazu
  public function addComment()
  {
    $insertData = array(
      'user_id' => $this->user_id,
      'post_id' => $this->post_id,
      'comment' => $this->comment,
      'date_posted' => $this->date_posted
    );
    return $this->db->insert($this->table, $insertData);
  }

  //Funkcija za iscitavanje svih komentara za zadati post
  public function getPostComments()
  {
    $this->db->select('comment.id as id, user.id as user_id ,user.picture as picture,user.username as username, comment.date_posted as date_posted, comment.comment as comment');
    $this->db->from('comment');
    $this->db->join('user', 'comment.user_id=user.id');
    $this->db->where('comment.post_id',$this->post_id);
    $this->db->order_by('comment.id','desc');
    return $this->db->get()->result();
  }

  //Funkcija za brisanje odredjenog komentara na postu
  public function deleteComment()
  {
    $this->db->where('id', $this->id);
    $this->db->delete('comment');
  }

  //Funkcija za proveru da li je trenutni korisnik autor komentara
  public function isAuthor()
  {
    $this->db->select('count(*) as broj');
    $this->db->from('comment');
    $this->db->where('comment.id',$this->id);
    $this->db->where('user_id',$this->session->userdata('userId'));
    $result = $this->db->get()->row()->broj;

    if($result>0){
      return true;
    }
    else return false;
  }

  //Funkcija za postavljanje svih polja klase na 'NULL' vrednost
  public function reset()
  {
    unset($this->id);
    unset($this->user_id);
    unset($this->post_id);
    unset($this->comment);
    unset($this->date_posted);
  }



}
