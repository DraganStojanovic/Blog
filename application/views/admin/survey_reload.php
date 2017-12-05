<?php if(!isset($samo_tabela)): ?>
  <div class="container-fluid">
      <div class="row">
          <div class="col-lg-12">
            <h1 class="page-header">
              Admin panel <small>Statistics Overview</small>
            </h1>
          </div>
       </div>
      <div class="col-md-4 col-md-offset-4">
         <?php if(isset($surveys))echo $surveys; ?>
       </br>
     </br>
       <?php if(isset($survey_name)): ?>
         <div class="form-group text-center col-md-5">
           <?php foreach($survey_name as $s): ?>
             <?php echo form_input($s);?>
           <?php endforeach; ?>
         </div>
         <div class="form-group">
            <input type="button"  class="btn-primary  btn-xs" value="Change name" onclick="anketa_change_name()" value="">
         </div>
       <?php endif; ?>
     </br>
      </div>
<?php endif; ?>
    <div id="admin-page-tabela" style="min-height:200px;">
      <?php if(isset($tabela))echo $tabela; ?>
    </div>

    <?php if(isset($form_title) && !isset($samo_tabela)): ?>
      <div class="col-md-3 col-md-offset-4 text-center">
        <p class="lead"><?php echo $form_title; ?></p>
        <input type="hidden"  name="" value="">
        <div class="form-group">
          <input type="text" class="form-control" id="admin_option_name" value="<?php if(isset($form_input_value))echo $form_input_value; ?>" placeholder="Option name">
        </div>
        <div class="form-group">
          <input type="button" onclick="<?php echo $form_button_onclick; ?>" class="btn-xs btn" name="" value="<?php echo $form_button_title; ?>">
        </div>
      </div>
    <?php endif; ?>

    <div class="col-md-3 col-md-offset-4 clearfix" id="feedback">

    </div>
