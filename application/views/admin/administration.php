<!-- Admin panel CSS -->
 <link href="<?php echo base_url() ?>files/css/sb-admin.css" rel="stylesheet">
<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.html">Hello <?php if($this->session->userdata('username')) echo $this->session->userdata('username'); ?></a>
        </div>
        <!-- Top Menu Items -->
        <ul class="nav navbar-left top-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
                <ul class="dropdown-menu message-dropdown">
                    <li class="message-preview">
                    <?php if(isset($messages)): ?>
                    <?php foreach($messages as $message): ?>
                        <a href="#">
                            <div class="media">
                                <div class="media-body">
                                    <h5 class="media-heading"><strong><?php echo $message->name; ?></strong>
                                    </h5>
                                    <p class="small text-muted"><i class="fa fa-clock-o"></i><?php echo $message->time; ?></p>
                                    <p><?php echo $message->message; ?></p>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                    <?php endif;?>
                    </li>
                    <li class="message-footer">
                        <a href="#" onclick="admin_messages();">Read All New Messages</a>
                    </li>
                </ul>
            </li>

        </ul>
      <ul class="nav navbar-nav navbar-right" style="margin-right:30px;">
      <?php if(isset($links)): ?>
        <?php foreach($links as $link): ?>
          <li><a href="<?php echo $link->link; ?>"><?php echo $link->label; ?></a></li>
        <?php endforeach; ?>
      <?php endif; ?>

    </ul>

        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li class="">
                    <a href="<?php echo base_url()?>/administration"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                </li>
                <li onclick="admin_posts('overview');">
                    <a href="#"><i class="fa fa-fw fa-pencil"></i> Posts</a>
                </li>
                <li onclick="admin_navigation('overview');">
                    <a href="#"><i class="fa fa-fw fa-list"></i> Navigation</a>
                </li>
                <li onclick="admin_users('overview');">
                    <a href="#"><i class="fa fa-fw fa-user"></i> Users</a>
                </li>
                <li onclick="admin_surveys();">
                    <a href="#"><i class="fa fa-fw fa-question"></i> Surveys</a>
                </li>
                <li onclick="admin_messages();">
                    <a href="#"><i class="fa fa-fw fa-envelope"></i> Messages</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>

    <div id="admin-page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Admin panel <small>Statistics Overview</small>
                    </h1>

                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-pencil fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if(isset($blog_posts))echo $blog_posts;?></div>
                                    <div>Blog posts!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if(isset($reg_users))echo $reg_users;?></div>
                                    <div>Registered users!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-question fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if(isset($surveys))echo $surveys;?></div>
                                    <div>Available surveys!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-envelope fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if(isset($msg))echo $msg; ?></div>
                                    <div>New <br>messages!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-md-offset-3">
              <?php if(isset($activities)): ?>
                <table class="table table-bordered table-hover">
                  <tr><th>#</th><th>User</th><th>Activity</th><th>Time</th></tr>
                  <?php $i=1; ?>
                  <?php foreach($activities as $activity): ?>
                    <tr><td><?php echo $i++; ?></td><td><?php echo $activity->user_id;?></td><td><?php echo $activity->message; ?></td><td><?php echo $activity->time; ?></td></tr>
                  <?php endforeach; ?>
                </table>
              <?php endif; ?>
            </div>

            <!-- /.row -->
		<div id="upload_slike" class="col-md-4 col-md-offset-4 text-center">
            <p class="lead">GALLERY</p>
              <?php if(isset($pictures)): $rb=1;?>
                <table class="table table-striped table-hover">
                  <tr><th>#</th><th>Thumbnail</th><th>Delete</th></tr>
                  <?php foreach($pictures as $pic): ?>
                    <tr>
                      <td><?php echo $rb++; ?></td>
                      <td><img src="<?php echo base_url() ?>images/thumbnails/<?php echo $pic->picture;?>" class="img-responsive img-thumbnail" style="height:60; width:100px;" alt=""></td>
                      <td><a href="<?php echo base_url()?>administration/delete_img/<?php echo $pic->id;?>"><input type="button" name="" value="Delete"></a></td>
                    </tr>


                  <?php endforeach; ?>
                </table>
              <?php endif; ?>
              <p class="lead">Upload picture to gallery!</p>
              <form class="" action="<?php echo base_url()?>administration" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <input type="file" name="picture" class="form-control" value="">
                </div>
                <div class="form-group">
                  <input type="submit" name="upload" class="btn btn-danger" value="Upload">
                </div>

              </form>
                <div id="feedback">
                <?php if(isset($error)) echo "<p class='text-danger'>" . $error['error'] . "</p>"; ?>
                <?php if(isset($uploaded)) echo '<p class="small">Picture ' . $uploaded['upload_data']['file_name'] . ' successfully uploaded.</p>'; ?>
              </div>
            </div>
            </div>
        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>
