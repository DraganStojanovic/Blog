<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Model{

  public $id;
  public $title;
  public $author_id;
  public $posting_date;
  public $content;
  public $picture;
  public $link;
  public $subtitle;
  public $table = 'post';

  //Funkcija za postavljanje svih polja klase na 'NULL' vrednost
  public function reset()
  {
    unset($this->id);
    unset($this->title);
    unset($this->author_id);
    unset($this->posting_date);
    unset($this->content);
    unset($this->picture);
    unset($this->link);
    unset($this->subtitle);
  }

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  //Dohvatanje podataka svih postojecih postova
  public function getPosts($offset)
  {
    $this->db->select('post.id as postId, post.title, post.posting_date, post.content, post.picture, post.link, post.subtitle, user.username, user.name, user.surname');
    $this->db->from($this->table);
    $this->db->join('user', 'post.author=user.id');
    $this->db->limit(3,$offset);
    $result = $this->db->get()->result();

    //Ukoliko baza vrati podatke, funkcija ih prosledjuje kontroleru, u suprotnom vraca false
    if(!empty($result))
    return $result;
    else return false;
  }

  public function getPostsForAdmin()
  {
    $this->db->select('post.id as postId, post.title, post.posting_date, post.content, post.picture, post.link, post.subtitle, user.username, user.name, user.surname');
    $this->db->from($this->table);
    $this->db->join('user', 'post.author=user.id');
    $result = $this->db->get()->result();

    //Ukoliko baza vrati podatke, funkcija ih prosledjuje kontroleru, u suprotnom vraca false
    if(!empty($result))
    return $result;
    else return false;
  }

  //Dohvatanje posta na osnovu linka
  public function getSinglePost()
  {
    $this->db->select('post.id as postId, post.title, post.posting_date, post.content, post.picture, post.link, post.subtitle, user.name,user.surname');
    $this->db->from($this->table);
    $this->db->join('user', 'post.author=user.id');
    $this->db->where('post.link', $this->link);
    $result = $this->db->get()->row();

    //Ukoliko baza vrati podatke, funkcija ih prosledjuje kontroleru, u suprotnom vraca false
    if(!empty($result))
    return $result;
    else return false;

  }

  //Dohvatanje posta na osnovu linka
  public function getPost()
  {
    $this->db->select('post.id as postId, post.title, post.posting_date, post.content, post.picture, post.link, post.subtitle, user.name,user.surname');
    $this->db->from($this->table);
    $this->db->join('user', 'post.author=user.id');
    $this->db->where('post.id', $this->id);
    $result = $this->db->get()->row();

    //Ukoliko baza vrati podatke, funkcija ih prosledjuje kontroleru, u suprotnom vraca false
    if(!empty($result))
    return $result;
    else return false;

  }

  public function postsCount()
  {
    return $this->db->count_all($this->table);
  }

  public function insert()
  {
    $insertData = array(
      'title' => $this->title,
      'author' => $this->author_id,
      'posting_date' => $this->posting_date,
      'content' => $this->content,
      'picture' => $this->picture,
      'link' => $this->link,
      'subtitle' => $this->subtitle
    );
    return $this->db->insert($this->table,$insertData);
  }

  public function delete()
  {
    $this->db->delete($this->table, array('id' => $this->id));
  }

  public function update($updateData)
  {
    $this->db->where('post.id', $this->id);
    return $this->db->update('post',$updateData);
  }
}
