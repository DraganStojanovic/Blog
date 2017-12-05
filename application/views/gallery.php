<style media="screen">
img {
filter: gray; /* IE6-9 */
-webkit-filter: grayscale(1); /* Google Chrome, Safari 6+ & Opera 15+ */
  -webkit-box-shadow: 0px 2px 6px 2px rgba(0,0,0,0.75);
  -moz-box-shadow: 0px 2px 6px 2px rgba(0,0,0,0.75);
  box-shadow: 0px 2px 6px 2px rgba(0,0,0,0.75);
  margin-bottom:20px;
  height:180px !important;
}

img:hover {
filter: none; /* IE6-9 */
-webkit-filter: grayscale(0); /* Google Chrome, Safari 6+ & Opera 15+ */

}
</style>

<div class="container">
	<div class="row">
		<?php if(isset($pictures)): ?>
      <?php foreach($pictures as $pic): ?>
        <div class="col-md-3 col-sm-4 col-xs-6"><img class="img-responsive" src="<?php echo base_url() .'files/uploads/gallery/thumbnails/'. $pic->picture ?>" /></div>
      <?php endforeach; ?>
    <?php endif; ?>

    </div>
</div>
