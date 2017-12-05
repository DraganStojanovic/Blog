<!--Sadrzaj index strane, ucitani svi blog postovi iz baze -->
<div class="container">
    <div class="row">
        <div class="col-lg-9 col-md-9">
            <?php if(isset($posts)): ?>
              <?php foreach($posts as $post): ?>
                <div class="post-preview">
                    <a href="<?php echo base_url(); ?>posts/post/<?php echo $post->link; ?>">
                        <h2 class="post-title">
                          <?php echo $post->title; ?>
                        </h2>
                        <h3 class="post-subtitle">
                            <?php echo $post->subtitle; ?>
                        </h3>
                    </a>
                    <p class="post-meta">Posted by <a href="#"><?php echo $post->name . ' ' . $post->surname; ?></a> on <?php echo $post->posting_date; ?></p>
                </div>
                <hr>
              <?php endforeach; ?>
            <?php endif; ?>

            <!-- Pager -->
            <style media="screen">
              .pagination a {
                color:black !important;
              }
              .pagination .active a {
                color:white !important;
              }
            </style>
              <?php if(isset($pagination)): ?>
                <?php echo $pagination; ?>
              <?php endif; ?>

        </div>
