<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of blog_post
 *
 * @author lukee
 */
class Reply extends CI_Model{

    public $id;
    public $comment_id;
    public $reply_content;
    public $user_id;
    public $reply_date;

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    //Funkcija za postavljanje svih polja klase na 'NULL' vrednost
    public function reset()
    {
      unset($this->id);
      unset($this->comment_id);
      unset($this->reply_content);
      unset($this->user_id);
      unset($this->reply_date);
    }

    //Funkcija za dodavanje komentara na komentar
    public function addReply()
    {
      $insertData = array(
        'comment_id' => $this->comment_id,
        'reply_content' => $this->reply_content,
        'user_id' => $this->user_id,
        'reply_date' => $this->reply_date
      );
      return  $this->db->insert('comment_reply',$insertData);
    }

    //Funkcija za dohvatanje svih potkomentara odredjenog komentara
    public function getReplies()
    {
       $this->db->select('comment_reply.id as reply_id,user.id as reply_user_id,reply_content, reply_date, user.username as username, user.picture as user_picture');
       $this->db->from('comment_reply');
       $this->db->join('user', 'user.id=comment_reply.user_id');
       $this->db->where('comment_id', $this->comment_id);
       return $this->db->get()->result();

    }

    //Funkcija za proveru da li je trenutni korisnik autor potkomentara
    public function isAuthor($reply)
    {
      $this->db->select('count(*) as broj');
      $this->db->from('comment_reply');
      $this->db->where('comment_reply.id',$reply);
      $this->db->where('user_id',$this->session->userdata('userId'));
      $result = $this->db->get()->row()->broj;

      if($result>0){
        return true;
      }
      else return false;
    }


    //Funkcija za brisanje potkomentara
    public function deleteReply($reply_id)
    {
      $this->db->where('id', $this->id);
      $this->db->delete('comment_reply');
    }

    //Funkcija za brisanje svih potkomentara
    public function deleteAllReplies($comment_id){
      $this->db->where('comment_id', $comment_id);
      $this->db->delete('comment_reply');
    }

}
