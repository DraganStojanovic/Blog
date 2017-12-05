<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class posts extends Frontend_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->library('user_agent');
    $this->load->model('Post');
    $this->load->model('Comment');
    $this->load->model('Reply');
    $this->load->model('Activity');
  }

  //Funkcija za ucitavanje blog post-a
  function post($link=null)
  {

    //Ispitivanje da li je prosledjen blog post, ukoliko nije, korisnik se redirektuje na pocetnu stranu
    if(!empty($link))
    {
    //Dohvatanje podataka iz baze o specificnom postu, na osnovu njegovog linka

    $this->Post->link=$link;
    $result = $this->Post->getSinglePost();

    //Ukoliko je baza vratila podatke za zeljeni post (post postoji), podaci o postu se izvlace i prosledjuju odgovarajucem view-u
    //U suprotnom, korisnik se redirektuje na pocetnu stranu
    if($result)
    {
      $data['title'] = $result->title;
      $data['picture'] = $result->picture;
      $data['headerTitle'] = $result->title;
      $data['headerSubtitle'] = $result->subtitle;
      $data['content'] = $result->content;
      $data['name'] = $result->name;
      $data['surname'] = $result->surname;
      $data['posting_date'] = $result->posting_date;
      $data['id'] = $result->postId;
      $data['link'] = $result->link;

      //Ucitavanje modela za komentare i potkomentare


      //Postavljanje polja modela reply na id trenutnog posta
      $this->Comment->post_id=$data['id'];
      //Ucitavanje svih komentara za trenutni post
      $data['comments']=$this->Comment->getPostComments();
      //Dohvatanje reply-a za svaki komentar
      //Za svaki komentar pravi se novi niz koji sadrzi trenutni komentar i sve odgovore na njega
      $i=0;
      foreach($data['comments'] as $comment){
        //Postavljanje 'comment_id' polja za komentar u modelu reply
        $this->Reply->comment_id = $comment->id;
        //Dohvatanje svih potkomentara za zadati komentar
        $replies=$this->Reply->getReplies();
        //Niz koji sadrzi komentar, i sve odgovore na taj komentar
        $data['comment_replies'][$i++]=array(
          'comment' => $comment,
          'replies' => $replies
        );
      }

      //Odabir view-a koji se prosledjuje natklasi
      $data['pages']=array('post','comments');
      $this->load_views($data);
    }
    else
    {
      redirect(base_url());
    }

  }
  else redirect(base_url());
  }

  //Funkcija za dodavanje komentara
  public function addComment()
  {
    //Ukoliko korisnik pokusava da pristupi funkciji, a da to nije preko forme, biva redirektovan na pocetnu stranu
    if($this->input->post('submit_comment')){
        //Proverava se da li je korisnik upisao nesto u polje za komentar, ukoliko nije, biva obavesten o gresci i vracen na stranu gde je pokusao da upise komentar
        if($this->input->post('comment')!="")
        {
          //Postavljanje polja u modelu, na osnovu kojih se komentar upisuje u bazu
          $this->Comment->user_id = $this->input->post("user_id");
          $this->Comment->post_id = $this->input->post("post_id");
          $this->Comment->comment = $this->input->post('comment');
          $this->Comment->date_posted = date('Y-m-d H:i:s');
          $this->Comment->addComment();
          //Dodavanje aktivnosti
          $this->Activity->message = 'Dodat komentar na post.';
          $this->Activity->add();
          //Vracanje korisnika na stranu na kojoj je unosio komentar
          redirect($this->agent->referrer());
        }
        else
        {
          $this->session->set_flashdata('error',"Comment should not be empty.");
          redirect($this->agent->referrer());
        }
    }
    else {
      redirect(base_url());
    }
  }

  //Funkcija za postavljanje potkomentara na komentar i nije prazan, ukoliko nije, korisnik se vraca na prethodnu stranu i obavestava o gresci
  public function addReply($commentId = null){
    //Provera da li je potkomentar poslat formom
    if($this->input->post('reply_' . $commentId))
    {

      //Postavljanje odgovarajucih polja u klasi, na osnovu kojih se potkomentar preko funkcije upisuje u bazu
      $this->Reply->comment_id=$commentId;
      $this->Reply->reply_content = $this->input->post('reply_' . $commentId);
      $this->Reply->user_id = $this->session->userdata('userId');
      $this->Reply->reply_date = date('Y-m-d H:i:s');
      //Funkcija za unos komentara u bazu
      $this->Reply->addReply();
      //Dodavanje aktivnosti
      $this->Activity->message = 'Dodat komentar na post!';
      $this->Activity->add();
      //Vracanje korisnika na stranu na kojoj je uneo komentar
      redirect($this->agent->referrer());
    }
    else
    {
      $this->session->set_flashdata('error', 'Comment should not be empty');
      redirect($this->agent->referrer());
    }
  }

  //Funkcija za uklananjanje postojeceg komentara, prosledjuje joj se id komentara za brisanje
  public function deleteComment($comment_id=null)
{
    //Provera da li je korisnik koji je pokusao da obrise komentar autor komentara pomocu funkcije u modelu 'comment', funkcija vraca true ili false
    $this->Comment->id = $comment_id;
    $provera_authora = $this->Comment->isAuthor();

    //Ukoliko je trenutno ulogovani korisnik 'Admin' ili autor komentara, dozvoljeno mu je brisanje
    //U suprotnom, korisnik biva vracen na pocetnu stranu
    if($this->session->userdata('role')=='Admin' || $provera_authora)
    {
      //Prvo se poziva funkcija za brisanje komentara, nakon toga se ucitava model za potkomentare
      //Kako bi se obrisali svi potkomentari tog komentara
      $this->Comment->deleteComment();
      //Dodavanje aktivnosti
      $this->Activity->message = 'Obrisan komentar!';
      $this->Activity->add();
      $this->Reply->comment_id = $comment_id;
      $this->Reply->deleteAllReplies($comment);
      redirect($this->agent->referrer());
    }
    else
    {
      redirect(base_url());
    }
}

//Funkcija za brisanje potkomentara
public function deleteReply($reply_id=null)
  {
    //Provera da li je korisnik koji je pokusao da obrise komentar autor komentara pomocu funkcije u modelu 'reply', funkcija vraca true ili false
    $this->Reply->id = $reply_id;
    $provera_authora = $this->Reply->isAuthor();
    //Ukoliko je trenutno ulogovani korisnik 'Admin' ili autor komentara, dozvoljeno mu je brisanje
    //U suprotnom, korisnik biva vracen na pocetnu stranu
    if($this->session->userdata('role')=='Admin' || $provera_authora)
    {
      //Dodavanje aktivnosti
      $this->Activity->message = 'Obrisan komentar!';
      $this->Activity->add();
      $this->Reply->deleteReply();
      redirect($this->agent->referrer());
    }
    else
    {
      redirect(base_url());
    }
  }


}
