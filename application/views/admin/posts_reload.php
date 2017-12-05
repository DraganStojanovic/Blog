<div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <h1 class="page-header">
              Admin panel <small>Statistics Overview</small>
            </h1>
          </div>
        </div>'
<?php
$template = array('table_open' => '<table class="text-center small table table-hover table-bordered">');
$this->table->set_template($template);
$this->table->set_heading('#','Title','Author','Posting Date','Link','Last Modified','Edit','Delete');
$tableData = array();
$i = 0;
foreach($posts as $post)
{
  array_push($tableData, array(
    $i++,
    $post->title,
    $post->username,
    $post->posting_date,
    $post->link,
    $post->posting_date,
    '<i style="padding:0px;" onclick="admin_edit_post('. $post->postId .')" class="btn fa fa-pencil-square-o" aria-hidden="true"></i>',
    '<i style="padding:0px;" onclick="admin_delete_post('. $post->postId .')" class="btn fa fa-trash-o" aria-hidden="true"></i>',
  ));
}
echo $this->table->generate($tableData);
?>
  <style media="screen">
  .btn-file {
  position: relative;
  overflow: hidden;
}
.btn-file input[type=file] {
  position: absolute;
  top: 0;
  right: 0;
  min-width: 100%;
  min-height: 100%;
  font-size: 100px;
  text-align: right;
  filter: alpha(opacity=0);
  opacity: 0;
  outline: none;
  background: white;
  cursor: inherit;
  display: block;
}
  </style>
<div class="row">
  <div class="container">
    <div class="col-md-8 col-md-offset-2">
       <form id="postForma" action="" method="POST" enctype="multipart/form-data">
      <?php
      if(isset($edit_post))
      {
        $id=$edit_post->postId;
        $title = $edit_post->title;
        $content =$edit_post->content;
        $picture = $edit_post->picture;
        $link = $edit_post->link;
        $subtitle = $edit_post->subtitle;
        $dugme = array(
          'type' => 'button',
          'value' => 'Edit post',
          'class' => 'btn btn-primary col-md-offset-4',
          'id' => 'addPost',
          "onclick" => "admin_posts('edit')"
        );
      }
      else
      {
        $id="";
        $title = "";
        $author ="";
        $content ="";
        $picture = "";
        $link = "";
        $subtitle = "";
        $dugme = array(
          'type' => 'button',
          'value' => 'Unesi post',
          'class' => 'btn btn-primary col-md-offset-4',
          'id' => 'addPost',
          "onclick" => "admin_posts('insert')"
        );
      }
      $inputs=array(
        0 =>array(
          'name' => 'postTitle',
          'id' => 'postTitle',
          'value' => $title,
          'type' => 'text',
          'placeholder' => 'Post title',
          'required' => 'true',
          'class' => 'form-control'
        ),
        1 =>array(
          'name' => 'postSubtitle',
          'id' => 'postSubtitle',
          'value' => $subtitle,
          'type' => 'text',
          'placeholder' => 'Post subtitle',
          'required' => 'true',
          'class' => 'form-control'
        ),
          2 =>array(
            'name' => 'postLink',
            'id' => 'postLink',
            'value' => $link,
            'type' => 'text',
            'placeholder' => 'Post link',
            'required' => 'true',
            'class' => 'form-control'
          ),
          3 =>array(
            'name' => 'postPicture',
            'id' => 'postPicture',
            'value' => $picture,
            'type' => 'file',
            'class' => 'form-control'
          ),
          4 =>array(
            'name' => 'postId',
            'id' => 'postId',
            'value' => $id,
            'type' => 'hidden',
            'class' => 'form-control'
          ),
          );

          foreach($inputs as $input)
          {
            echo form_input($input) . '</br>';
          }
       ?>
       <textarea class="form-control" name="postContent" id="postContent" rows="8" cols="80"><?php echo $content; ?></textarea><br>
       <?php echo form_input($dugme); ?>
       </form>
       <div id="feedback">

       </div>
    </div>

  </div>

</div>
