<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">
            Admin panel <small>Statistics Overview</small>
          </h1>
        </div>
     </div>
     <?php if(isset($tabela))echo $tabela; ?>
    <div class='row'>
      <div class="container">
        <div class="col-md-3 col-md-offset-4">
             <div class="form-group">
               <input type="text" class="form-control" value="<?php if(isset($label))echo $label;?>" id="navigation_label" placeholder="Link label">
             </div>
             <div class="form-group">
               <input type="text" class="form-control" value="<?php if(isset($link))echo $link;?>" id="navigation_link" placeholder="Link address, relative to base_url">
             </div>
             <div class="checkbox">
               <label class="small"><input <?php if(isset($admin))echo 'checked';?> id="navigation_admin_only" type="checkbox"> Admin only</label>
             </div>
             <div class="checkbox">
               <label class="small"><input <?php if(isset($logged))echo 'checked';?> id="navigation_logged" type="checkbox"> For logged users</label>
             </div>
             <?php if(isset($id)): ?>
               <button  onclick="admin_navigation('edited',<?php echo $id; ?>)" class="btn btn-default">Edit link</button>
             <?php else: ?>
               <button  onclick="admin_navigation('insert')" class="btn btn-default">Add link</button>
             <?php endif; ?>
             <div id="feedback">

             </div>
        </div>
      </div>
    </div>
