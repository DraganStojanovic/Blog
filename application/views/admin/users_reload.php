
<div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <h1 class="page-header">
              Admin panel <small>Statistics Overview</small>
            </h1>
          </div>
        </div>
<?php
 $this->load->library('table');
 $template = array('table_open' => '<table class="text-center small table table-hover table-bordered">');
 $this->table->set_template($template);
 $this->table->set_heading('#','First Name','Last Name','Username','Email','Active','Registered','Last Login','Role','Edit','Delete');
$tableData = array();
$i = 0;
foreach($users as $user)
{
  array_push($tableData, array(
    $i++,
    $user->name,
    $user->surname,
    $user->username,
    $user->email,
    $user->active,
    $user->registration_date,
    $user->last_login,
    $user->role,
    '<i style="padding:0px;" onclick="admin_edit_user('. $user->id .')" class="btn fa fa-pencil-square-o" aria-hidden="true"></i>',
    '<i style="padding:0px;" onclick="admin_delete_user('. $user->id .')" class="btn fa fa-trash-o" aria-hidden="true"></i>',
  ));
}
echo $this->table->generate($tableData);

//Ucitavanje polja za registraciju ili edit korisnika
//Ukoliko je izabran edit, dugmetu dati drugu vrednost za Edit
//U suprotnom, vrednost za insert
//Ukoliko je kliknuto na edit, podaci u formi se postavljaju na
//Podatke korisnika, u suprotnom, polja ostaju prazna


if(isset($edit_user))
{
  $username = $edit_user->username;
  $surname = $edit_user->surname;
  $name = $edit_user->name;
  $id = $edit_user->id;
  $email = $edit_user->email;

  $submit = array(
      'class' => 'login loginmodal-submit',
      'name' => 'regSubmit',
      'id' => 'add_user_submit',
      'value' => 'Edit user',
      'type' => 'button',
      'class' => 'col-md-offset-2 btn btn-info',
      "onClick"=>"admin_users('update')"
    );
}
else
{
  $username = "";
  $surname = "";
  $name = "";
  $id = "";
  $email = "";

  $submit = array(
      'class' => 'login loginmodal-submit',
      'name' => 'regSubmit',
      'id' => 'add_user_submit',
      'value' => 'Add user',
      'type' => 'button',
      'class' => 'col-md-offset-2 btn btn-info',
      "onClick"=>"admin_users('insert')"
    );
}

$inputs=array(
  0 =>array(
    'name' => 'regUsername',
    'id' => 'regUsername',
    'value' => $username,
    'type' => 'text',
    'placeholder' => 'Username',
    'required' => '',
    'class' => 'form-control',
  ),
  1 =>array(
    'name' => 'regFirstName',
    'id' => 'regFirstName',
    'value' => $name,
    'type' => 'text',
    'placeholder' => 'First name',
    'required' => '',
    'class' => 'form-control'
  ),
  2 =>array(
    'name' => 'regLastName',
    'id' => 'regLastName',
    'value' => $surname,
    'type' => 'text',
    'placeholder' => 'Last name',
    'required' => '',
    'class' => 'form-control'
  ),
  3 =>array(
    'name' => 'regEmail',
    'id' => 'regEmail',
    'value' => $email,
    'type' => 'text',
    'placeholder' => 'Email',
    'required' => '',
    'class' => 'form-control'
  ),

  4 =>array(
    'name' => 'regPassword',
    'id' => 'regPassword',
    'value' => '',
    'type' => 'password',
    'placeholder' => 'Password',
    'required' => '',
    'class' => 'form-control'
  ),

  5 =>array(
    'name' => 'regConfirm',
    'id' => 'regConfirm',
    'value' => '',
    'type' => 'password',
    'placeholder' => 'Confirm password',
    'required' => '',
    'class' => 'form-control'
  ),
  6 =>array(
    'name' => 'regUserId',
    'id' => 'regUserId',
    'value' => $id,
    'type' => 'hidden',
  )
);

$parametar = 'insert';


echo "<div id='admin_add_user' class='row'><div class='container-fluid'><div class='col-md-3 col-md-offset-4'>";
echo '<div class="text-center bottom-buffer-m">Add new user </div>';
foreach($inputs as $i)
{
  echo  '<div class="form-group">' . form_input($i) . '</div>';
}
echo "<div class='form-group'><select id='regRole' class='form-control'>";
foreach($roles as $r)
{
  echo '<option value="' . $r->id . '">' . $r->name . "</option>";
}
echo "</select></div>";
echo form_input($submit);
echo "</div><div class='col-md-4 col-md-offset-4 text-danger' id='register_errors'></div></div></div>";
?>
