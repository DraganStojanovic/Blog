//Funkcija za ucitavanje sadrzaja za admin posts
function admin_posts(type)
{
  switch(type)
  {
    case 'overview':

    $.ajax({
      url: baseurl +'administration/posts/get',
      type: 'POST',
      success: function(data)
      {
        div = "#admin-page-wrapper";
        $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
        setTimeout(function () {
        $(div).html(data);
      }, 700);
      },
      error: function(request,status,error){
        if(error)
        {
          alert(error);
        }
      }
    });
    break;

    case 'insert':
      title = $("#postTitle").val();
      subtitle = $("#postSubtitle").val();
      link = $("#postLink").val();
      slika = $("#postPicture");
      formData = new FormData($("form#postForma")[0]);
      content = $("#postContent").val();
      var errors = [];

      var reTitle = /^[A-Za-z0-9\s]{3,30}$/;
      var reSubtitle = /^[A-Za-z0-9\s]{3,50}$/;
      var reLink = /^[a-z0-9\_-]{3,20}$/;

      if(!reTitle.test(title))
      {
        errors.push("Invalid title format!");
      }

      if(!reLink.test(link))
      {
        errors.push("Invalid link format!");
      }

      if(!reSubtitle.test(subtitle))
      {
        errors.push("Invalid subtitle format!");
      }

      if(!slika.val())
      {
        errors.push("You must select picture!");
      }
      if(!content)
      {
        errors.push("Content should not be empty!");
      }
      if(errors.length>0)
      {
        $("#feedback").html(errors);
      }
      else
      {
          $.ajax({
                url: baseurl + 'administration/posts/insert',
                type: 'POST',
                data : formData,
                 cache: false,
                 contentType: false,
                 processData: false,

                success: function(data)
                {
                  div = "#admin-page-wrapper";
                  $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position:fixed; top:40%; left:50%;"aria-hidden="true"></i>');
                  setTimeout(function () {
                  $(div).html(data);
                }, 700);
                },
                error: function(request,status,error){
                  if(error)
                  {
                    alert(error);
                  }
                }
              });
      }
    break;
    case 'edit':
          title = $("#postTitle").val();
          subtitle = $("#postSubtitle").val();
          link = $("#postLink").val();
          slika = $("#postPicture").attr('value');
          formData = new FormData($("form#postForma")[0]);
          content = $("#postContent").val();
          var errors = [];

          var reTitle = /^[A-Za-z0-9\s]{3,30}$/;
          var reSubtitle = /^[A-Za-z0-9\s]{3,50}$/;
          var reLink = /^[a-z0-9\_-]{3,20}$/;

          if(!reTitle.test(title))
          {
            errors.push("Invalid title format!");
          }

          if(!reLink.test(link))
          {
            errors.push("Invalid link format!");
          }

          if(!reSubtitle.test(subtitle))
          {
            errors.push("Invalid subtitle format!");
          }

          if(!slika)
          {
            errors.push("You must select picture!");
          }
          if(!content)
          {
            errors.push("Content should not be empty!");
          }
          if(errors.length>0)
          {
            $("#feedback").html(errors);
          }
          else
          {
              $.ajax({
                    url: baseurl + 'administration/posts/edit',
                    type: 'POST',
                    data : formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                    success: function(data)
                    {
                      div = "#admin-page-wrapper";
                      $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position:fixed; top:40%; left:50%;"aria-hidden="true"></i>');
                      setTimeout(function () {
                      $(div).html(data);
                    }, 700);
                    },
                    error: function(request,status,error){
                      if(error)
                      {
                        alert(error);
                      }
                    }
                  });
          }
    break;
  }
}
//Funkcija za upravljanje navigacijom na admin panelu, ocekuje se tip dogadjaja
//Na osnovu tipa dogadjaja, kontroleru se prosledjuju razliciti parametri na osnovu kojih se vrsi obrada podataka
function admin_navigation(type,id)
{
  switch(type)
  {
    //U slucaju da je tip overview, kontroleru se stavlja do znanja da je potrebno samo ucitati sav sadrzaj
    case 'overview':
        $.ajax({
        url: baseurl + 'administration/navigation',
        type: 'POST',
        success: function(data)
        {
          div = "#admin-page-wrapper";
          $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
          setTimeout(function () {
          $(div).html(data);
        }, 700);
        },
        error: function(request,status,error){
          if(error)
          {
            alert(error);
          }
        }
      });
    break;
    //Ukoliko je kliknuto na edit, prosledjen je i id linka kome je potreban edit
    //Na osnovu tog id-a kontroler treba da dohvati ceo link preko modela
    //I da njegove podatke prosledi view-u na prikaz, radi izmene
    case 'edit':
          $.ajax({
            url: baseurl + 'administration/navigation/edit',
            type: 'POST',
            data: {
              id:id
            },
            success: function(data)
            {
              div = "#admin-page-wrapper";
              $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
              setTimeout(function () {
              $(div).html(data);
            }, 700);
            },
            error: function(request,status,error){
              if(error)
              {
                alert(error);
              }
            }
          });
    break;

    //Ukoliko je kliknuto na edit, izmenjeni podaci se prosledjuju kontroleru, koji ih salje modelu
    //Vrsi se izmena i novi podaci se ucitavaju u glavni div
    case 'edited':
      var label = $("#navigation_label").val();
      var link = $("#navigation_link").val();
      var admin_only = $("#navigation_admin_only").is(":checked");
      var logged = $("#navigation_logged").is(":checked");
      var errors = [];

      if(logged)logged=1;else logged = 0;
      if(admin_only)admin_only=1; else admin_only = 0;
      var reLabel = /^[A-z]{3,15}$/;
      var reLink = /^[a-z\_\-\\]{3,40}$/;

      if(!reLabel.test(label))
      {
        errors.push('Invalid link label format');
      }
      if(!reLink.test(link))
      {
        errors.push("Invalid link format");
      }
      //Ukoliko je doslo do greske prilikom provere podataka iz forme, u divu #feedback se ispisuju greske
      if(errors.length>0)
      {
        $("#feedback").html(errors);
      }
      else
      {
        $.ajax({
        url: baseurl +'administration/navigation/edited',
        type: 'POST',
        data: {
          id : id,
          label : label,
          link : link,
          admin_only : admin_only,
          logged : logged
        },
        success: function(data)
        {
          div = "#admin-page-wrapper";
          $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
          setTimeout(function () {
          $(div).html(data);
        }, 700);
        },
        error: function(request,status,error){
          if(error)
          {
            alert(error);
          }
        }
      });
      }
    break;
    //U slucaju da je tip insert, ucitavaju se svi podaci iz forme za unos novog linka
    //I prosledjuju kontroleru na obradu, kontroler po zavrsenoj obradi treba da ucita overview
    //Na osnovu kog ce se izvrsiti refresh sajta sa novoobradjenim podacima
    case 'insert':
      var label = $("#navigation_label").val();
      var link = $("#navigation_link").val();
      var admin_only = $("#navigation_admin_only").is(":checked");
      var logged = $("#navigation_logged").is(":checked");
      var errors = [];

      if(logged)logged=1;else logged = 0;
      if(admin_only)admin_only=1; else admin_only = 0;
      var reLabel = /^[A-z]{3,15}$/;
      var reLink = /^[a-z\_\-\\]{3,40}$/;

      if(!reLabel.test(label))
      {
        errors.push('Invalid link label format');
      }
      if(!reLink.test(link))
      {
        errors.push("Invalid link format");
      }
      //Ukoliko je doslo do greske prilikom provere podataka iz forme, u divu #feedback se ispisuju greske
      if(errors.length>0)
      {
        $("#feedback").html(errors);
      }
      else
      {
        $.ajax({
        url: baseurl + 'administration/navigation/insert',
        type: 'POST',
        data: {
          label : label,
          link : link,
          admin_only : admin_only,
          logged : logged
        },
        success: function(data)
        {
          div = "#admin-page-wrapper";
          $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
          setTimeout(function () {
          $(div).html(data);
        }, 700);
        },
        error: function(request,status,error){
          if(error)
          {
            alert(error);
          }
        }
      });
      }
    break;
    //Ukoliko je kliknuto na ikonicu za brisanje, ajaxom se salje id kontroleru administration, funkciji za rad sa navigacijom parametar
    //Da se zna da je brisanje
    case 'delete':
          $.ajax({
            url: baseurl + 'administration/navigation/delete',
            type: 'POST',
            data: {
              id:id
            },
            success: function(data)
            {
              div = "#admin-page-wrapper";
              $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
              setTimeout(function () {
              $(div).html(data);
            }, 700);
            },
            error: function(request,status,error){
              if(error)
              {
                alert(error);
              }
            }
          });
    break;
  }
}

function admin_users(type)
{
  switch(type)
  {
    case 'overview':
        $.ajax({
          url: baseurl + 'administration/users/',
          type: 'POST',
          success: function(data)
          {
            div = "#admin-page-wrapper";
            $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
            setTimeout(function () {
            $(div).html(data);
          }, 700);
          },
          error: function(xhr,request,error,status)
        {
        alert(xhr.responseText);
        }
        });
        break;
      case 'insert':
            var username = $('#regUsername');
            var password = $('#regPassword');
            var firstname = $('#regFirstName');
            var lastname = $('#regLastName');
            var email = $('#regEmail');
            var confirm = $('#regConfirm');
            var role = $('#regRole');
            var errors = [];

            var reUsername = /^[a-zA-Z0-9.\-_$@*!]{3,30}$/
            var rePassword = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;
            var reEmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var reName = /^[A-z]{2,14}$/;

            if(!reUsername.test(username.val()))
            {
              errors.push("Invalid username format!</br>");
            }
            if(!rePassword.test(password.val()))
            {
              errors.push("Invalid password format!</br>");
            }
            if(!reEmail.test(email.val()))
            {
              errors.push("Invalid email format!</br>");
            }
            if(!reName.test(firstname.val()))
            {
              errors.push("Invalid firstname format!</br>");
            }
            if(!reName.test(lastname.val()))
            {
              errors.push("Invalid lastname format!</br>");
            }
            if(password.val()!==confirm.val())
            {
              errors.push("Passwords do not match!</br>");
            }

            if(errors.length!=0)
            {
              password.val("");
              confirm.val("");
              $("#register_errors").html(errors);
            }
            else
            {
              $.ajax({
                url: baseurl + 'administration/users/',
                type: 'POST',
                data :
                {
                  insert :true,
                  username : username.val(),
                  password : password.val(),
                  firstname : firstname.val(),
                  lastname : lastname.val(),
                  email : email.val(),
                  role : role.val()
                },
                success: function(data)
                {
                  div = "#admin-page-wrapper";
                  $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position:fixed; top:40%; left:50%;"aria-hidden="true"></i>');
                  setTimeout(function () {
                  $(div).html(data);
                }, 700);
                },
                error: function(request,status,error){
                  if(error)
                  {
                    alert(error);
                  }
                }
              });
            }

            break;
            case'update':
            var username = $('#regUsername');
            var password = $('#regPassword');
            var firstname = $('#regFirstName');
            var lastname = $('#regLastName');
            var email = $('#regEmail');
            var confirm = $('#regConfirm');
            var role = $('#regRole');
            var id = $('#regUserId');
            var errors = [];

            var reUsername = /^[a-zA-Z0-9.\-_$@*!]{3,30}$/
            var rePassword = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;
            var reEmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var reName = /^[A-z]{2,14}$/;

            if(!reUsername.test(username.val()))
            {
              errors.push("Invalid username format!</br>");
            }
            if(!rePassword.test(password.val()))
            {
              errors.push("Invalid password format!</br>");
            }
            if(!reEmail.test(email.val()))
            {
              errors.push("Invalid email format!</br>");
            }
            if(!reName.test(firstname.val()))
            {
              errors.push("Invalid firstname format!</br>");
            }
            if(!reName.test(lastname.val()))
            {
              errors.push("Invalid lastname format!</br>");
            }
            if(password.val()!==confirm.val())
            {
              errors.push("Passwords do not match!</br>");
            }

            if(errors.length!=0)
            {
              password.val("");
              confirm.val("");
              $("#register_errors").html(errors);
            }
            else
            {
              $.ajax({
                url: baseurl + 'administration/users',
                type: 'POST',
                data :
                {
                  edit:true,
                  id: id.val(),
                  username : username.val(),
                  password : password.val(),
                  firstname : firstname.val(),
                  lastname : lastname.val(),
                  email : email.val(),
                  role : role.val()
                },
                success: function(data)
                {
                  div = "#admin-page-wrapper";
                  $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position:fixed; top:40%; left:50%;"aria-hidden="true"></i>');
                  setTimeout(function () {

                  $(div).html(data);
                }, 700);
                },
                error: function(request,status,error){
                  if(error)
                  {
                    alert(error);
                  }
                }
              });
            }
            break;
  }
}

function admin_messages(tip,id)
{
  switch(tip)
  {
    //Overview
    case 'overview':
    break;

    //Delete
    case 'delete':
        $.ajax({
        url: baseurl + 'administration/messages/delete',
        type: 'POST',
        data: {
           id:id
        },
        success: function(data)
        {
          div = "#admin-page-wrapper";
          $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
          setTimeout(function () {
          $(div).html(data);
        }, 700);
        },
        error: function(request,status,error){
          if(error)
          {
            alert(error);
          }
        }
      });
    break;

    //Default
    default:
        $.ajax({
        url: baseurl + 'administration/messages',
        type: 'POST',
        success: function(data)
        {
          div = "#admin-page-wrapper";
          $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
          setTimeout(function () {
          $(div).html(data);
        }, 700);
        },
        error: function(request,status,error){
          if(error)
          {
            alert(error);
          }
        }
      });
    break;
  }
} // end of admin_messages()

function admin_surveys(tip,id)
{
  switch(tip)
  {
    //Overview
    case 'overview':
    break;

    //Delete
    case 'delete':
        $.ajax({
        url: baseurl + 'administration/messages/delete',
        type: 'POST',
        data: {
           id:id
        },
        success: function(data)
        {
          div = "#admin-page-wrapper";
          $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
          setTimeout(function () {
          $(div).html(data);
        }, 700);
        },
        error: function(request,status,error){
          if(error)
          {
            alert(error);
          }
        }
      });
    break;

    //Default
    default:
        $.ajax({
        url: baseurl + 'administration/surveys',
        type: 'POST',
        success: function(data)
        {
          div = "#admin-page-wrapper";
          $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
          setTimeout(function () {
          $(div).html(data);
        }, 700);
        },
        error: function(request,status,error){
          if(error)
          {
            alert(error);
          }
        }
      });
    break;
  }
} // end of admin_surveys()

function admin_delete_user(user_id)
{
  $.ajax({
    url: baseurl + 'administration/delete/user',
    type: 'POST',
    data: {
      user_id: user_id
    },
    success: function(data)
    {
      div = "#admin-page-wrapper";
      $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
      setTimeout(function () {
      $(div).html(data);
    }, 700);
    },
    error: function(xhr,request,status,error){
      if(error)
      {
        alert(error.responseText);
      }
    }
  });
}

function admin_edit_user(user_id)
{
  $.ajax({
    url: baseurl + 'administration/edit/user',
    type: 'POST',
    data: {
      user_id: user_id
    },
    success: function(data)
    {
      div = "#admin-page-wrapper";
      $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
      setTimeout(function () {
      $(div).html(data);
    }, 700);
    },
    error: function(xhr,request,status,error){
      if(error)
      {
        alert(error.responseText);
      }
    }
  });
}

function admin_edit_post(post_id)
{
  $.ajax({
    url: baseurl + 'administration/edit/post',
    type: 'POST',
    data: {
      post_id: post_id
    },
    success: function(data)
    {
      div = "#admin-page-wrapper";
      $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
      setTimeout(function () {
      $(div).html(data);
    }, 700);
    },
    error: function(xhr,request,status,error){
      if(error)
      {
        alert(error.responseText);
      }
    }
  });
}

function admin_delete_post(post_id)
{
  alert(post_id);
  $.ajax({
    url: baseurl + 'administration/delete/post',
    type: 'POST',
    data: {
      post_id: post_id
    },
    success: function(data)
    {
      div = "#admin-page-wrapper";
      $(div).html('<i class="fa fa-refresh fa-spin fa-5x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
      setTimeout(function () {
      $(div).html(data);
    }, 700);
    },
    error: function(xhr,request,status,error){
      if(error)
      {
        alert(error.responseText);
      }
    }
  });
}

function admin_survey_refresh_options()
{
  survey_id = $("#survey_dropdown").val();
  $.ajax({
    url: baseurl + 'administration/surveys/refresh_table',
    type: 'POST',
    data: {
      survey_id: survey_id
    },
    success: function(data)
    {
      div = "#admin-page-wrapper";
      $(div).html('<i class="fa fa-cog fa-spin fa-3x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
      setTimeout(function () {
      $(div).html(data);
    }, 700);
    },
    error: function(xhr,request,status,error){
      if(error)
      {
        alert(error.responseText);
      }
    }
  });
}

function admin_survey_option(type,id)
{
  switch(type)
  {
    case 'delete':
    var survey_id = $("#survey_dropdown").val();
        $.ajax({
        url: baseurl + 'administration/surveys/delete',
        type: 'POST',
        data: {
          option_id: id,
          survey_id: survey_id
        },
        success: function(data)
        {
          div = "#admin-page-tabela";
          $(div).html('<i class="fa fa-cog fa-spin fa-3x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
          setTimeout(function () {
          $(div).html(data);
        }, 700);
        },
        error: function(xhr,request,status,error){
          if(error)
          {
            alert(error.responseText);
          }
        }
      });
    break;
    case 'insert':
      var optionName = $("#admin_option_name");
      var reOptionName = /^[A-z0-9\s]{3,30}$/;
      var survey_id = $("#survey_dropdown").val();
      if(!reOptionName.test(optionName))
      {
        $("#feedback").html('Ime opcije nije u dobrom formatu');
      }
      else
      {
        $.ajax({
        url: baseurl + 'administration/surveys/insert',
        type: 'POST',
        data: {
          option_name: optionName.val(),
          survey_id : survey_id
        },
        success: function(data)
        {
          div = "#admin-page-tabela";
          $(div).html('<i class="fa fa-cog fa-spin fa-3x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
          optionName.val('');
          setTimeout(function () {
          $(div).html(data);
        }, 700);
        },
        error: function(xhr,request,status,error){
          if(error)
          {
            alert(error.responseText);
          }
        }
      });
      }
    break;
    case 'edit':
    var survey_id = $("#survey_dropdown").val();
      $.ajax({
        url: baseurl + 'administration/surveys/edit',
        type: 'POST',
        data: {
          option_id: id,
          survey_id : survey_id
        },
        success: function(data)
        {
          div = "#admin-page-wrapper";
          $(div).html('<i class="fa fa-cog fa-spin fa-3x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
          setTimeout(function () {
          $(div).html(data);
        }, 700);
        },
        error: function(xhr,request,status,error){
          if(error)
          {
            alert(error.responseText);
          }
        }
      });
    break;

    case 'edited':
      var optionName = $("#admin_option_name");
      var reOptionName = /^[A-z0-9\s]{3,30}$/;
      var survey_id = $("#survey_dropdown").val();
      if(!reOptionName.test(optionName))
      {
        $("#feedback").html('Ime opcije nije u dobrom formatu');
      }
      else
      {
        $.ajax({
        url: baseurl + 'administration/surveys/edited',
        type: 'POST',
        data: {
          option_id: id,
          option_name: optionName.val(),
          survey_id : survey_id
        },
        success: function(data)
        {
          div = "#admin-page-wrapper";
          $(div).html('<i class="fa fa-cog fa-spin fa-3x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
          optionName.val('');
          setTimeout(function () {
          $(div).html(data);
        }, 700);
        },
        error: function(xhr,request,status,error){
          if(error)
          {
            alert(error.responseText);
          }
        }
      });
      }
    break;
  }
}

function anketa_change_name()
{
  var name = $("#anketa_name").val();
  var id = $("#anketa_name_id").val();

  if(name=="")
  {
    alert("Survey name can't be empty!");
  }
  else
  {
    $.ajax({
      url: baseurl + 'administration/surveys/change_name',
      type: 'POST',
      data: {
        name: name,
        survey_id : id
      },
      success: function(data)
      {
        div = "#admin-page-wrapper";
        $(div).html('<i class="fa fa-cog fa-spin fa-3x" style="position: absolute; left: 50%; top: 45%;" aria-hidden="true"></i>');
        setTimeout(function () {
        $(div).html(data);
      }, 700);
      },
      error: function(xhr,request,status,error){
        if(error)
        {
          alert(error.responseText);
        }
      }
    });
  }


}
