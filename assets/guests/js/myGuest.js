$(document).ready(function() {

//contact script
//will trigger when the user submit the contact us form
$(".contactUs").click(function(e){
    e.preventDefault();
    
    var name = $('#name').val();
    var subject=$('#subject').val();
    var email=$('#email').val();
    var message=$('#message').val();

    if (name == '')
      $('.name-alert').text("Name field is required.");
    if(email == '') 
      $('.email-alert').text("Please provide valid email address.");
    if (message == '')
      $('.message-alert').text("Message is required, even Hello will do.");
    if (subject == '')
      $('.subject-alert').text("Subject field is required.");

    if ((name!='') || (email!='') || (message!='') || (subject!='')) {
      //clear messages before sending request
      $('.name-alert').text('');
      $('.email-alert').text('');
      $('.message-alert').text('');
      $('.subject-alert').text('');
      //send ajax request
      $.ajax({
        url: 'Guests/contactUs',
        type: 'POST',
        //dataType: 'json',
        data: {'name':name,
                'email':email,
                'subject':subject,
                'message':message
              },
      })
      .done(function(msg) {
        if (msg!='done') {
          //display form validation errors
          $('.contact-alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + msg + '</div>');
          //
        }else{
          //when all is done, close contact modal and 
          $('#contact').hide('fast', function() {
            //modal-open class is added on body so it has to be removed
            $('body').removeClass('modal-open'); 
            //need to remove div with modal-backdrop class
            $('.modal-backdrop').remove();
            //redirect to another page
          });

          //open success modal
          $('#guest-success').show('800', function() {
            $('.modal-title').text('Contact Us');
            $('.modal-msg').text('Your email has been sent successfully. \nThuto-sci team will be in touch.');
          });
            
        }
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      
    }else {
      $('.contact-alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+ 
               'All fields are required.</div>');
    }
  });


//login scrpt
//will trigger when the user submit the login form
$("#userLogin").click(function(e){
    e.preventDefault();
    var username = $('#username').val();
    var password=$('#password').val();
    var me=$('#remember').val();

    if ((username!='') || (password!='')) {
      $.ajax({
        url: 'Logins/validateUser',
        type: 'POST',
        //dataType: 'json',
        data: {'password':password,
                'username':username,
                'remember':me
              },
      })
      .done(function(msg) {
        if (msg!='done') {
          //display form validation errors
          $('.login-alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + msg + '</div>');
          //
        }else{
          //when all is done, close login modal and 
          $('#login').hide('fast', function() {
            //modal-open class is added on body so it has to be removed
            $('body').removeClass('modal-open'); 
            //need to remove div with modal-backdrop class
            $('.modal-backdrop').remove();
            //redirect to another page
            var app_url = window.location.href;
            app_url = app_url.substr(0, app_url.lastIndexOf("/"));
            window.location.replace(app_url+'/App');
          });
            
        }
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      
    }else {
       $('.login-alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> '+
                'Both fields are required!</div>');
    }
  });

//forgot password
//this will only trigger when the user submit email/username for password reset
$(".forgotPasswo").click(function(e){
    e.preventDefault();
    var email = $('#forgotUser').val();
    if (email!='') {
      $.ajax({
        url: 'Access/validateEmail',
        type: 'POST',
        //dataType: 'json',
        data: {'email':email },
      })
      .done(function(msg) {
        if (msg=='Yes') {
           $('#forgotPassword').hide();   
           $('#guest-success').show('800', function() {
              $('.modal-title').text('Forgot password')     
              $('.modal-msg').text('Thuto-sci has sent you an email with the reset password instructions.');
           });     
        }else{
           $('.alert-msg').html('<div class="alert alert-danger text-center" alert-dismissable>\
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + msg + '</div>');  
        }
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      
    }else {
       $('.alert-msg').html('<div class="alert alert-danger text-center" alert-dismissable>\
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> '+
                'Please provide your username. \nIt must be in the format of email address.</div>');
    }
  }); 

//this will close reset password modal
 $(".modalClose").click(function(e){
    e.preventDefault();
    $("#forgotPassword").hide('slow', function() {
      //clear form values
      $('#forgotUser').val("");
      $('.alert-msg').text('');
      //modal-open class is added on body so it has to be removed
      $('body').removeClass('modal-open'); 
      //need to remove div with modal-backdrop class
      $('.modal-backdrop').remove();
      //refresh window when the user is done
      var app_url = window.location.href;
            app_url = app_url.substr(0, app_url.lastIndexOf("/"));
            window.location.replace(app_url+'/Guests');
    });

  });

//this will close contact form login
 $(".modalClose").click(function(e){
    e.preventDefault();
    $("#contact").hide('slow', function() {
      //clear form values
      $('#name').val("");
      $('#email').val("");
      $('#message').val("");
      $('#subject').val("");
      $('.contact-alert').text('');
      //modal-open class is added on body so it has to be removed
      $('body').removeClass('modal-open'); 
      //need to remove div with modal-backdrop class
      $('.modal-backdrop').remove();
      //refresh window when the user is done
    var app_url = window.location.href;
            app_url = app_url.substr(0, app_url.lastIndexOf("/"));
            window.location.replace(app_url+'/Guests');
    });

  });

//this will close login form modal
 $(".modalClose").click(function(e){
    e.preventDefault();
    $("#login").hide('slow', function() {
      //clear the values
      $('#username').val("");
      $('#password').val("");
      $('.login-alert').text('');
      $('#remember').removeAttr('unchecked');
      //modal-open class is added on body so it has to be removed
      $('body').removeClass('modal-open'); 
      //need to remove div with modal-backdrop class
      $('.modal-backdrop').remove();
      //refresh window when the user is done
    var app_url = window.location.href;
            app_url = app_url.substr(0, app_url.lastIndexOf("/"));
            window.location.replace(app_url+'/Guests');
    });

  });
//close success modal when the user click of OK
$('.guest-successOK').click(function(e) {
  e.preventDefault();
  $('#guest-success').hide('800', function() {
    //refresh window when the user is done
    var app_url = window.location.href;
            app_url = app_url.substr(0, app_url.lastIndexOf("/"));
            window.location.replace(app_url+'/Guests');
  });
});

});//end of document ready