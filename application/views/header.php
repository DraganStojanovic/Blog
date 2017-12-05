<!-- Header koji je drugaciji za svaku stranu  -->
  <style media="screen">
    .small {
      font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }


  </style>
    <header class="intro-header" style="background-image: url('<?php echo base_url(); ?>files/uploads/pages/<?php  if(isset($picture)):echo $picture; endif; ?>'); height:600px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">

                          <?php if(isset($headerTitle)): ?>
                          <h1>  <?php echo $headerTitle; ?>   </h1>
                          <?php endif; ?>

                        <?php if(isset($headerSubtitle)): ?>
                          <hr class="small">
                          <span class="subheading"><?php echo $headerSubtitle; ?></span>
                        <?php endif; ?>
                        <br>

                        <?php if(isset($name)): ?>
                          <span class="small ">
                            <i class="fa fa-calendar"> </i> <?php echo $posting_date; ?> &nbsp&nbsp&nbsp&nbsp&nbsp
                            <a style="text-decoration:none; color:white;"  href="<?php echo base_url() ?>about" target="_blank"><i class="fa fa-user"> </i> <?php echo $name . ' ' . $surname; ?></a>
                          </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>
