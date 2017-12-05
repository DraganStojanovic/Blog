<!-- Deo za komentare -->

     <div class="container">
         <div class="row">
             <div class="col-md-8 col-lg-">
                 <h2 class="page-header">Comments</h2>
                       <div class="well">
                         <!-- Ukoliko je korisnik logovan, dozvoljeno mu je da ostavi komentar -->
                           <?php if($this->session->userdata('username')):?>
                             <h4>Leave a Comment:</h4>
                             <form role="form" method="post" action="<?=base_url();?>posts/addComment">
                                 <div class="form-group">
                                     <textarea name="comment" class="form-control" rows="3"></textarea>
                                     <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('userId');?>"/>
                                     <input type="hidden" name="post_id" value="<?php echo $id;?>" />
                                     <input type="hidden" name="link" value="<?php echo $link;?>" />
                                 </div>
                                 <button type="submit" name="submit_comment" value="1" class="btn btn-primary">Submit</button>
                                 <?php if($this->session->flashdata('error')) echo $this->session->flashdata('error'); ?>
                             </form>
                            <!-- Ukoliko korisnik nije logovan, dobija login link i obavestenje da je potrebno da se uloguje -->
                           <?php else:?>
                             <h4>To post a comment, you must <a href="<?php echo base_url() . 'login/login' ?>">log in</a> first.<h4>
                           <?php endif; ?>
                       </div>
               <hr>
                 <section class="comment-list">
                   <!-- First Comment -->
           <?php if(isset($comment_replies)):
               //var_dump($comment_replies);
                   foreach($comment_replies as $cr):
                      ?>
                   <article id="comment_<?php echo $cr['comment']->id; ?>" class="row">
                     <div class="col-md-2 col-sm-2 hidden-xs">
                       <figure class="thumbnail">
                         <img class="img-responsive" src="<?php echo base_url() . "files/uploads/users/". $cr['comment']->picture; ?>" />
                         <figcaption class="text-center"><?php echo $cr['comment']->username; ?></figcaption>
                       </figure>
                     </div>
                     <div class="col-md-10 col-sm-10">
                       <div class="panel panel-default arrow left">
                         <div class="panel-body">
                           <header class="text-left">
                             <div class="comment-user"><i class="fa fa-user"></i> <?php echo $cr['comment']->username; ?>

                               <?php if($this->session->userdata('role')=='Admin' || $this->session->userdata('userId')==$cr['comment']->user_id): ?>
                                   <a href="<?php echo base_url() . 'posts/deleteComment/' . $cr['comment']->id ?>" style="color:black;">
                                   <i class="fa fa-times pull-right" aria-hidden="true"></i></a>
                               <?php endif; ?>

                             </div>
                             <time class="comment-date" datetime="16-12-2014 01:05"><i class="fa fa-clock-o"></i> <?php echo $cr['comment']->date_posted; ?></time>
                           </header>
                           <div class="comment-post">
                             <p>
                                <?php echo $cr['comment']->comment; ?>
                           </div>
                           <button onclick="prikaz_reply(<?php echo $cr['comment']->id ?>)"  class="btn btn-default btn-sm pull-right"><i class="fa fa-reply"></i> reply</button>
                         </div>
                       </div>
                       <!-- Forma za odgovor je inicijalno skrivena -->
                       <div class=" col-md-offset-2 col-md-10">
                             <div id="comment_reply_<?php echo $cr['comment']->id; ?>" class="panel-body hidden">
                             <?php if($this->session->userdata('username')):?>

                                 <form method="post" role="form container-fluid" action="<?=base_url();?>posts/addReply/<?php echo $cr['comment']->id;?>">
                                         <div class="form-group">
                                              <input type="hidden" name="reply_comment_id" value="">
                                             <textarea class="form-control" name="reply_<?php echo $cr['comment']->id; ?>" rows="3" placeholder="Add your comment" autofocus=""></textarea>
                                         </div>
                                         <button type="submit" class=" btn btn-success pull-right" >Post reply</button>
                                 </form>
                             </div>
                             <?php else:?>

                                 <h4>To post a reply, you must <a href="<?php echo base_url() . 'login'; ?>">log in</a> first.<h4>

                             <?php endif; ?>
                       </div> <!-- Zavrseno forma za reply -->
                     </div>
                   </article>
                   <?php if(isset($cr['replies'])):
                         foreach($cr['replies'] as $reply): ?>
                         <article class="row">
                           <div class="col-md-2 col-sm-2 col-md-offset-1 col-sm-offset-0 hidden-xs">
                             <figure class="thumbnail">
                               <img class="img-responsive" src="<?php echo base_url() . "files/uploads/users/". $reply->user_picture; ?>" />
                               <figcaption class="text-center"><?php echo $reply->username; ?></figcaption>
                             </figure>
                           </div>
                           <div class=" col-md-9 col-sm-9">
                             <div class="panel  panel-default  arrow left" style="background:#e8eaf6;">

                               <div class="panel-heading right">Reply</div>
                               <div class="panel-body ">
                                 <header class="text-left">
                                   <div class="comment-user"><i class="fa fa-user"> </i><?php echo ' '.$reply->username; ?></div>
                                   <?php if($this->session->userdata('role')=='Admin' || $this->session->userdata('userId')==$reply->reply_user_id): ?>
                                       <a href="<?php echo base_url() . 'posts/deleteReply/' . $reply->reply_id; ?>" style="color:black;">
                                       <i class="fa fa-times pull-right" aria-hidden="true"></i></a>
                                   <?php endif; ?>
                                   <time class="comment-date" datetime="16-12-2014 01:05"><i class="fa fa-clock-o"></i> <?php echo $reply->reply_date ?></time>
                                 </header>
                                 <div class="comment-post">
                                   <p>
                                     <?php echo $reply->reply_content; ?>
                                   </p>
                                 </div>

                               </div>
                             </div>
                           </div>
                         </article>
                   <?php endforeach; endif; ?>
                 <?php endforeach; endif; ?>
                   <!-- Second Comment Reply -->
                 </section>
             </div>
         </div>
   </div>
<hr>
