<!-- Navigacija, ista za svaku stranu -->
    <nav class="navbar navbar-default navbar-custom navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="<?php echo base_url(); ?>">Luke's blog</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                  <!-- Ucitavanje menija koji je prosledjen iz Frontend_Controller -->
                    <?php if(isset($links)): ?>
                    <?php foreach($links as $link): ?>
                      <li>
                          <a href="<?php echo base_url() . $link->link; ?>"><?php echo $link->label; ?></a>
                      </li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
