function prikaz_reply(id)
{
    $("#comment_reply_" + id).toggleClass('hidden');
}

function vote(surveyId)
{
  var div = "#anketa" + surveyId;
  var voteButton = "#voteButton" + surveyId;
  var form = "#formVote" + surveyId;
  var error = "#voteError" + surveyId;

  var checked = $("input:radio[name='vote']:checked").val();
  if(checked){
    $.ajax({
      url: baseurl + "anketa/glasaj",
      type: 'POST',
      data: {
        voteId: checked,
        surveyId: surveyId
      },
      success: function(data){

        $(div).html('<i class=" fa fa-spin fa-spinner fa-2x" aria-hidden="true"></i>');
        $(voteButton).addClass('hidden');
        setTimeout(function () {
        $(div).html(data);
      }, 500);
      },
      error: function(request,status,error){
        alert(request.responseText);
      }
    });
  }
  else {
    $(error).html('<p class="text-danger"> Please, choose at least one option.</p>');
  }
}

//Funkcija za generisanje prikaza za edit
function edit_survey_generate(surveyId)
{
  var div = "#anketa" + surveyId;
  var voteButton = "#voteButton" + surveyId;
  $.ajax({
    url: baseurl + 'anketa/edit',
    type: 'POST',
    data: {
      surveyId: surveyId},
    success: function(data)
    {
      $(voteButton).addClass('hidden');
      $(div).html('<i class=" fa fa-spin fa-cog fa-2x" aria-hidden="true"></i>');
      setTimeout(function () {
      $(div).html(data);
    }, 500);
    },
    error: function(request,status,error)
    {

    }
  });
}

//Funkcija za dodavanje polja za edit
function anketa_add_row(surveyId)
{
  div = '#anketa'+surveyId;
  $(div+" > #anket_inputs").append('<i class=" fa fa-spin fa-cog fa-2x" style="margin-bottom:20px;" aria-hidden="true"></i>');
  setTimeout(function () {
  $('.fa-spin').hide();
  $(div+" > #anket_inputs").append('<input type="text" class="form-control anketa_added_'+ surveyId + '"value="" placeholder="Name"/></br>');
}, 300);
}

//Funkcija za izvrsavanje edita i prikaza editovanih podataka
function edit_survey_insert(surveyId)
{
 //Dohvatanje elemenata kojima je potrebno izmeniti naziv
 var toEdit = $('.anketa_edited_' + surveyId);
 //Dohvatanje elemenata koje je potrebno dodati u bazu podataka
 var toInsert = $('.anketa_added_' + surveyId);
 //Pravljenje nizova koje treba napuniti podacima i proslediti contoller-u za izmenu
 var editData = [];
 var insertData = [];
 //Provera da li je neko polje ostavljeno prazno, ako jeste, obavestava se korisnik
 var error = false;
 for (var i = 0; i < toEdit.length; i++) {
  value = toEdit[i].value;
  id = toEdit[i].getAttribute('id');
  //Novi niz u koji ce se smestiti id i value trenutnog elementa
  var edit = [];
  edit.push(id);
  edit.push(value);
  //Dodavanje podataka za edit u niz koji se prosledjuje kontroleru
  editData.push(edit);
  if(!value){
     error = 'Error! Field cant be empty.';
  }
 }
 //Ukoliko nema gresaka medju editovanim, proverava se da li su uneta imena za nove redove
 if(!error)
 {
   $('#edit_error_' + surveyId).html('');
   for (var i = 0; i < toInsert.length; i++) {
    value = toInsert[i].value;
    insertData.push(value);
    if(!value){
       error = 'Error! Field cant be empty.';
    }
   }
   //Ukoliko su i novo dodati redovi uneti bez greske, podaci se prosledjuju kontoleru
   if(!error)
   {
     $.ajax({
       url: baseurl + 'anketa/edit',
       type: 'POST',
       data: {
         edit: true,
         editData: editData,
         insertData: insertData,
         editId: surveyId
       },
       success: function(data)
       {
         var div = "#anketa" + surveyId;
         $(div).html('<i class=" fa fa-spin fa-cog fa-2x" style="margin-bottom:20px;" aria-hidden="true"></i>');
         setTimeout(function () {
        var voteButton = "#voteButton" + surveyId;
        $(voteButton).removeClass('hidden');
        $(div).html(data);
         }, 300);
       },
       error: function(request,status,error)
       {

       }
     });


   }
   else
   {
     $('#edit_error_' + surveyId).html(error);
   }
 }
 else
 {
   $('#edit_error_' + surveyId).html(error);
 }
}

//Funkcija za uklanjanje opcije ankete
function anketa_option_remove(optionId, surveyId)
{
  var div = "#anketa" + surveyId;
  $.ajax({
    url: baseurl + 'anketa/remove',
    type: 'POST',
    data: {
      removeOption: true,
      optionId: optionId,
      surveyId: surveyId
    },
    success: function(data)
    {
      $(div).html('<i class=" fa fa-spin fa-cog fa-2x" aria-hidden="true"></i>');
      setTimeout(function () {
      $(div).html(data);
    }, 500);
    },
    error: function(request,status,error)
    {

    }

});
}
