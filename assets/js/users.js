$(document).ready(function(){

    //this will be used to enforce numbers only into text inputs
    $(document).on('keypress keyup blur','.numeric_only',function(e){   
       $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((e.which < 48 || e.which > 57)) {
            e.preventDefault();            
        }
    });


//show hide the ID fields
$(document).on('change','.id_type',function(e){
   e.preventDefault();
   //if yes the guardian is part of the school
   if ($(this).val() == 1){
        $('#passport').hide('normal');
        $('#rsaid').fadeIn();
   }else if ($(this).val() == 0) {
        $('#passport').fadeIn();
        $('#rsaid').hide('fast');
   }
});

//when admin selects level
$(document).on('change','.level_id',function(e){
    e.preventDefault();
    var levelId = $(this).val();
    $(".group_id").show();
    if (levelId != 0) {
        $.ajax({
            url: 'getLevel',
            type: 'POST',
            data: {'levelId': levelId},
            dataType:'json',
        })
        .done(function(data) {
        $(".group_id").html(data);
                      
        })
        .fail(function() {
            alert("error");
        })

    }
});

$(document).on('mouseover', '#role', function(event) {
    event.preventDefault();
    $('#change_role').fadeIn('slow');
});

$(document).on('click', '.role_close', function(event) {
    event.preventDefault();
    $('#change_role').fadeOut('slow', function() {
        //modal-open class is added on body so it has to be removed
        $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
    });
});
/**
 * ***************************************************************************************
 *       ///////////////////First Guardian Section form wizard/////////////////////////
 * ***************************************************************************************
 */
//show hide the ID fields
$(document).on('change','.fg_id_type',function(e){
   e.preventDefault();
   //if yes the guardian is part of the school
   if ($(this).val() == 1){
        $('#fg_passport').hide('fast');
        $('#fg_rsaid').fadeIn();
   }else if ($(this).val() == 0) {
        $('#fg_passport').fadeIn();
        $('#fg_rsaid').hide('fast');
   }
});

//hide and display address depending on whether stays with learner
$(document).on('change','.fg_learner_address',function(e){
   e.preventDefault();
   if ($(this).val()==1) {
    $('#fg_address_div').hide();
   }else {
        //if the learner is not residing with the guardian
        //$('#fg_address').val('');
       //$('#fg_city').val('');
        //$('#fg_pcode').val('');
        //$('#fg_province').val('');
       $('#fg_address_div').fadeIn();
   }
});

//how the guardian is related to school
$(document).on('change','#fg_related',function(e){
   e.preventDefault();
   if ($(this).val() =='') {
    $('#guardian_school_relation').hide();
   }else {
      $('#guardian_school_relation').fadeIn(); 
   }
});

/**
 * Use to toggle if the guardian is already part of school or not
 */
$(document).on('change','#gsr',function(e){
   e.preventDefault();
   //if nothing is selected the first time
    if ($(this).val() =='') {
        //hide input fields of the first guardian
        $('.first_guardian').hide();
   }
   //if NO is selected, then hide username input and show all the fields
   if ($(this).val() == 0) {
        $('#fg_firstName').val('');
        $('#fg_middleName').val('');
        $('#fg_lastName').val('');
        $('#fg_email').val('');
        $('#fg_phone').val('');
        $('#fg_userID').val('');
        $('#fg_address').val('');
        $('#fg_city').val('');
        $('#fg_pcode').val('');
        $('#fg_idnumber').val('');
        $('#fg_pass_number').val('');
        $('input[type=text],input[type=email]').removeAttr('disabled');
        $('#guardian_username').hide();
        $('.first_guardian').fadeIn(); 
   }
   //if yes the guardian is part of the school
   if ($(this).val() == 1){
       $('.first_guardian').hide();
       //show the input field to input username
       $('#guardian_username').fadeIn();
   }
});

//type the username of the guardian and check if already on the system
$(document).on('click','#fg_search',function(e){
    e.preventDefault();
    if ($('#fg_username').val()!='') {
        $.ajax({
            url: 'searchUsers',
            type: 'POST',
            data: {'username':$('#fg_username').val()},
            dataType:'json'
        })
        .done(function(data) {
            console.log(data);
            //check if data variable has anything
            if (data!='') {
                //check if this user is not a learner
                find_fg_user(data);
            }else {
                $('#f_username_alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Thuto-sci cannot find the username you specified!</div>');
                //$('#final-next').attr('disabled', true);
                $('.first_guardian').hide();
            }
            
        })
        .fail(function(){
            alert("error");
        })
    }
});

//to check if the user is not one of the current learners
function find_fg_user(data) {
    $.ajax({
            url: 'find_user',
            type: 'POST',
            data: {'userID':data[0]['userID']},
        })
        .done(function(msg) {
            if (msg=='YES'){
                $('#f_username_alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    User you entered is a learner. A learner cannot have guardian role!</div>');
                $('#final-next').attr('disabled', true);
                $('.first_guardian').hide();
            }
            else {
                $('#final-next').removeAttr('disabled');
                $('#f_username_alert').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Thuto-sci found the user as specified. You can proceed!</div>');
                $('#fg_userID').val(data[0]['userID']);
                $('#fg_firstName').val(data[0]['fName']);
                $('#fg_middleName').val(data[0]['midName']);
                $('#fg_lastName').val(data[0]['lName']);
                //check if the db identity value is RSA id or passport
                if (data[0]['identityNumber'].length > 10) {
                    $('#fg_idnumber').val(data[0]['identityNumber']).attr('disabled', true);
                    $('#rsaid').fadeIn();
                    $("#fg_type_rsa").prop("checked", true);
                }else{
                    $('#fg_pass_number').val(data[0]['identityNumber']);
                    $('#passport').fadeIn();
                    $("#fg_type_pass").prop("checked", true)
                }
                $('#fg_email').val(data[0]['email']).attr('disabled', true);
                $('#fg_phone').val(data[0]['phone']);
                var address = data[0]['address'].split(',');
                $('#fg_address').val(address[0]);
                $('#fg_city').val(address[1]);
                $('#fg_pcode').val(address[2]);
                $('.first_guardian').fadeIn();
            }
        })
        .fail(function(){
            console.log("error");
    })    
}
/**
 * ***************************************************************************************
 *          //////////////////// Learner Section form wizard ////////////////////
 * ***************************************************************************************
 */

//show hide the ID fields
$(document).on('change','.l_id_type',function(e){
   e.preventDefault();
   //if yes the guardian is part of the school
   if ($(this).val() == 1){
        $('#l_passport').hide('fast');
        $('#l_rsaid').fadeIn();
   }else if ($(this).val() == 0) {
        $('#l_passport').fadeIn();
        $('#l_rsaid').hide('fast');
   }
});
/**
 * Use to toggle if the learner was part of school or not
 */
$(document).on('change','#lsr',function(e){
   e.preventDefault();
   //if nothing is selected the first time
    if ($(this).val() =='') {
        //hide input fields of the first guardian
        $('.learner_div').hide();
   }
   //if NO is selected, then hide username input and show all the fields
   if ($(this).val() ==0) {
      $('#submit_learn_guard').removeAttr('disabled');
      $('#learner_username').fadeIn();
      $('.learner_div').hide(); 
   }
   //if yes the guardian is part of the school
   if ($(this).val() == 1){
        $('#firstName').val('');
        $('#doe_Learner_No').val('');
        $('#middleName').val('');
        $('#lastName').val('');
        $('#email').val('');
        $('#phone').val('');
        $('#l_userID').val('');
        $('#address').val('');
        $('#city').val('');
        $('#pcode').val('');
        $('#l_idnumber').val('');
        $('#l_pass_number').val('');
        $('#submit_learn_guard').removeAttr('disabled');
        $('input[type=text],input[type=email]').removeAttr('disabled');
        $('.learner_div').fadeIn();
        //show the input field to input username
        $('#learner_username').hide();
   }
});

//type the username of the guardian and check if already on the system
$(document).on('click','#l_search',function(e){
    e.preventDefault();
    if ($('#l_username').val()!='') {
        $.ajax({
            url: 'searchUsers',
            type: 'POST',
            data: {'username':$('#l_username').val()},
            dataType:'json'
        })
        .done(function(data) {
            //check if data variable has anything
            if (data!='') {
                //check if this user is not a learner
                find_learner(data);
            }else {
                $('#l_username_alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Thuto-sci cannot find the username you specified!</div>');
                $('#submit_learn_guard').attr('disabled', true);
                $('.learner_div').hide();
            }
            
        })
        .fail(function(){
            alert("error");
        })
    }
});

//to check if the user is not one of the current learners
function find_learner(data) {
    $.ajax({
            url: 'find_user',
            type: 'POST',
            data: {'luserID':data[0]['userID']},
            dataType: 'json'
        })
        .done(function(learner) {
            //check if the learner is not empty
            if (learner!=''){
                //get the current calendar year
                var calendar_year = new Date().getFullYear();
                //get the last date the learner registered on the system
                var currentYear =  learner[0]['academicYear'];
                //get the year ONLY from the date
                var academic_year = currentYear.substr(0,4);
                //check if the learner is part of the current academic year
                if (calendar_year > academic_year) {
                    $('#submit_learn_guard').removeAttr('disabled');
                    $('#l_username_alert').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                        Thuto-sci found the learner as specified.<br> Confirm and complete all missing fields!</div>');
                    $('#l_userID').val(data[0]['userID']);
                    $('#firstName').val(data[0]['fName']);
                    $('#middleName').val(data[0]['midName']);
                    $('#lastName').val(data[0]['lName']);
                    //check if the db identity value is RSA id or passport
                    if (data[0]['identityNumber'].length > 10) {
                        $('#l_idnumber').val(data[0]['identityNumber']).attr('disabled', true);
                        $('#rsaid').fadeIn();
                        $("#id_type_rsa").prop("checked", true);
                    }else{
                        $('#l_pass_number').val(data[0]['identityNumber']);
                        $('#passport').fadeIn();
                        $("#id_type_pass").prop("checked", true)
                    }
                    $('#email').val(data[0]['email']).attr('disabled', true);
                    $('#phone').val(data[0]['phone']);
                    var address = data[0]['address'].split(',');
                    $('#address').val(address[0]);
                    $('#city').val(address[1]);
                    $('#pcode').val(address[2]);
                    $('#doe_Learner_No').val(learner[0]['learnDoE_ID']).attr('disabled', true);
                    $('.learner_div').fadeIn();
                }else {
                    $('#l_username_alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    This learner already exist for this current academic year!</div>');
                }
            }else {
                $('#l_username_alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Thuto-sci cannot find the learner you specified!</div>');
            }
        })
        .fail(function(){
            alert("error");
    })    
}
/**
 * ***************************************************************************************
 *   //////////////////////////Second Guardian Form Wizard///////////////////////////
 * ***************************************************************************************
 */

//show hide the ID fields
$(document).on('change','.sg_id_type',function(e){
   e.preventDefault();
   //if yes the guardian is part of the school
   if ($(this).val() == 1){
        $('#sg_passport').hide('fast');
        $('#sg_rsaid').fadeIn();
   }else if ($(this).val() == 0) {
        $('#sg_passport').fadeIn();
        $('#sg_rsaid').hide('fast');
   }
});

/**
 * Use this to hide or show the input fields of the second guardian depending on the selected value
 */
$(document).on('change','#sg_related',function(e){
   e.preventDefault();
   if ($(this).val() =='' || $(this).val() == 0) {
    $('.second_guardian_div').hide();
    $('#final-next').removeAttr('disabled');
    $('#second_guardian_school_relation').hide();
    $('#s_guardian_username').hide();
   }else {
        $('#final-next').attr('disabled', true);
        //$('.second_guardian_div').fadeIn(); 
        $('#second_guardian_school_relation').fadeIn(); 
   }
});

//hide and display address depending on whether stays with learner
$(document).on('change','.sg_learner_address',function(e){
   e.preventDefault();
   $('#final-next').removeAttr('disabled');
   //if the options yes, 
   //hide address fields
   if ($(this).val()==1) {
    $('#sg_address_div').hide();
   }else {
        //clear and display all the fields
        /*$('#sg_address').val('');
        $('#sg_city').val('');
        $('#sg_pcode').val('');*/
        //unhide the address container
       $('#sg_address_div').fadeIn();
   }
});

/**
 * Use to toggle if the second guardian is already part of school or not
 */
$(document).on('change','#second_guard_school_relation',function(e){
   e.preventDefault();
   //if nothing is selected the first time
    if ($(this).val() == '') {
        //hide input fields of the first guardian
        $('.second_guardian_div').hide();
   }
   //if NO is selected, then hide username input and show all the fields
   if ($(this).val() == 0) {
        $('#sg_firstName').val('');
        $('#sg_middleName').val('');
        $('#sg_lastName').val('');
        $('#sg_email').val('');
        $('#sg_phone').val('');
        $('#sg_userID').val('');
        $('#sg_address').val('');
        $('#sg_city').val('');
        $('#sg_pcode').val('');
        $('#sg_idnumber').val('');
        $('#sg_pass_number').val('');
        $('input[type=text],input[type=email]').removeAttr('disabled');
        $('#s_guardian_username').hide();
        $('.second_guardian_div').fadeIn(); 
   }
   //if yes the guardian is part of the school
   if ($(this).val() == 1){
       $('.second_guardian_div').hide();
       //show the input field to input username
       $('#s_guardian_username').fadeIn();
   }
});

//type the username of the guardian and check if already on the system
$(document).on('click','#sg_search',function(e){
    e.preventDefault();
    if ($('#sg_username').val()!='') {
        $.ajax({
            url: 'searchUsers',
            type: 'POST',
            data: {'username':$('#sg_username').val()},
            dataType:'json'
        })
        .done(function(data) {
            console.log(data);
            //check if data variable has anything
            if (data!='') {
                //check if this user is not a learner
                find_second_user(data);
            }else {
                $('#s_username_alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Thuto-sci cannot find the username or ID number you specified!</div>');
                $('#final-next').attr('disabled', true);
                $('.second_guardian_div').hide();
            }
            
        })
        .fail(function(){
            alert("error");
        })
    }
});

//to check if the user is not one of the current learners
function find_second_user(data) {
    $.ajax({
            url: 'find_user',
            type: 'POST',
            data: {'userID':data[0]['userID']},
        })
        .done(function(msg) {
            if (msg=='YES'){
                $('#s_username_alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    User you entered is a learner. A learner cannot have guardian role!</div>');
                $('#final-next').attr('disabled', true);
                $('.second_guardian_div').hide();
            }
            else {
                $('#final-next').removeAttr('disabled');
                $('#s_username_alert').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Thuto-sci found the user as specified. You can proceed!</div>');
                $('#sg_userID').val(data[0]['userID']);
                $('#sg_firstName').val(data[0]['fName']);
                $('#sg_middleName').val(data[0]['midName']);
                $('#sg_lastName').val(data[0]['lName']);
                //check if the db identity value is RSA id or passport
                if (data[0]['identityNumber'].length > 10) {
                    $('#sg_idnumber').val(data[0]['identityNumber']).attr('disabled', true);
                    $('#rsaid').fadeIn();
                    $("#sg_type_rsa").prop("checked", true);
                }else{
                    $('#sg_pass_number').val(data[0]['identityNumber']);
                    $('#passport').fadeIn();
                    $("#sg_type_pass").prop("checked", true)
                }
                $('#sg_email').val(data[0]['email']).attr('disabled', true);
                $('#sg_phone').val(data[0]['phone']);
                var address = data[0]['address'].split(',');
                $('#sg_address').val(address[0]);
                $('#sg_city').val(address[1]);
                $('#sg_pcode').val(address[2]);
                $('.second_guardian_div').fadeIn();

            }
        })
        .fail(function(){
            alert("error");
    })    
}

/**
 * ***************************************************************************************
 *         ////////////////////Form Wizard Submit buttons ///////////////////////////
 * ***************************************************************************************
 */

/**
 * This will only execute when the validation for all inputs is TRUE
 */
$(document).on('click','.confirmed',function(e){
   e.preventDefault();
    $.ajax({
        url: 'new_learner_guardian',
        type: 'POST',
    })
    .done(function(msg) {
        if (msg != "Added")
            $('.wizard-errors').html('<div class="alert alert-danger text-center" alert-dismissable>'+
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + msg + '</div>');       
    })
    .fail(function() {
        alert("error");
    })
});
//submit learner guardian details by clicking on next
$(document).on('click','#submit_learn_guard',function(e){
   e.preventDefault();
    var form = $('#learn_guard')[0];
    var form_data = new FormData(form);

    $.ajax({
        url: 'add_new_learner',
        type: 'POST',
        cache:false,
        data: form_data, //send this to php controller
        contentType: false,
        processData: false,
    })
    .done(function(msg) {
            if (msg != ""){
                $('.wizard-errors').html('<div class="alert alert-danger text-center" alert-dismissable>'+
                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + msg + '</div>');
                //disable next button on guardian form
                $('#final-next').attr('disabled', true);
                //disable selection of first guardian relationhip with learner
                $('#fg_related').attr('disabled', true);
                //disable selection of second guardian relationhip with learner
                $('#sg_related').attr('disabled', true);
            }else if (msg==""){
                //clear errors
                $('.wizard-errors').html('');
                //enable next button and select boxes for 
                $('#final-next').attr('disabled',true);
                $('#fg_related').removeAttr('disabled',true);
                $('#sg_related').removeAttr('disabled',true);
            }
                      
        })
        .fail(function() {
            alert("error");
        })
});

//submit first and second guardian details by clicking on next
$(document).on('click','.learn_guard_submit',function(e){
    e.preventDefault();
    var form = $('#f_s_guard')[0]; //first and second guardian form inputs
    var form_data = new FormData(form);
    $.ajax({
        url: 'new_guardian',
        type: 'POST',
        //cache:false,
        data: form_data, //send this to php controller
        contentType: false,
        processData: false,
    })
    .done(function(msg) {
        //alert(msg);
        if (msg != ""){
            $('.wizard-errors').html('<div class="alert alert-danger text-center" alert-dismissable>'+
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + msg + '</div>');
            $('#final-submit').attr('disabled', true);
        }else {
            $('.wizard-errors').html('');
            $('#final-submit').removeAttr('disabled');
        }
                      
    })
    .fail(function() {
        alert("error");
    })
});

});//close document ready

/**
 * ********************************************************************************************************
 * ////////////////////////////////////////////////////////////////////////////////////////////////////////
 */
"use strict";
function scroll_to_class(element_class, removed_height) {
    var scroll_to = $(element_class).offset().top - removed_height;
    if($(window).scrollTop() != scroll_to) {
        $('.form-wizard').stop().animate({scrollTop: scroll_to}, 0);
    }
}

function bar_progress(progress_line_object, direction) {
    var number_of_steps = progress_line_object.data('number-of-steps');
    var now_value = progress_line_object.data('now-value');
    var new_value = 0;
    if(direction == 'right') {
        new_value = now_value + ( 100 / number_of_steps );
    }
    else if(direction == 'left') {
        new_value = now_value - ( 100 / number_of_steps );
    }
    progress_line_object.attr('style', 'width: ' + new_value + '%;').data('now-value', new_value);
}

jQuery(document).ready(function() {
    
    /*
        Form
    */
    $('.form-wizard fieldset:first').fadeIn('slow');
    
    $('.form-wizard .required').on('focus', function() {
        $(this).removeClass('input-error');
    });
    
    // next step
    $('.form-wizard .btn-next').on('click', function() {
        var parent_fieldset = $(this).parents('fieldset');
        var next_step = true;
        // navigation steps / progress steps
        var current_active_step = $(this).parents('.form-wizard').find('.form-wizard-step.active');
        var progress_line = $(this).parents('.form-wizard').find('.form-wizard-progress-line');
        
        // fields validation
        parent_fieldset.find('.required').each(function() {
            if( $(this).val() == "" ) {
                $(this).addClass('input-error');
                next_step = false;
            }
            else {
                $(this).removeClass('input-error');
            }
        });
        // fields validation
        
        if( next_step ) {
            parent_fieldset.fadeOut(400, function() {
                // change icons
                current_active_step.removeClass('active').addClass('activated').next().addClass('active');
                // progress bar
                bar_progress(progress_line, 'right');
                // show next step
                $(this).next().fadeIn();
                // scroll window to beginning of the form
                scroll_to_class( $('.form-wizard'), 20 );
            });
        }
        
    });
    
    // previous step
    $('.form-wizard .btn-previous').on('click', function() {
        // navigation steps / progress steps
        var current_active_step = $(this).parents('.form-wizard').find('.form-wizard-step.active');
        var progress_line = $(this).parents('.form-wizard').find('.form-wizard-progress-line');
        
        $(this).parents('fieldset').fadeOut(400, function() {
            // change icons
            current_active_step.removeClass('active').prev().removeClass('activated').addClass('active');
            // progress bar
            bar_progress(progress_line, 'left');
            // show previous step
            $(this).prev().fadeIn();
            // scroll window to beginning of the form
            scroll_to_class( $('.form-wizard'), 20 );
        });
    });
    
    // submit
    $('.form-wizard').on('submit', function(e) {
        
        // fields validation
        $(this).find('.required').each(function() {
            if( $(this).val() == "" ) {
                e.preventDefault();
                $(this).addClass('input-error');
            }
            else {
                $(this).removeClass('input-error');
            }
        });
        // fields validation
        
    });
    
    
});
/**//////////////////////////////////////////////////////////
/*   ****************Admin section*************************
/**//////////////////////////////////////////////////////////

$(document).on('change','#asr',function(e){
   e.preventDefault();
   //if NO is selected, then hide username input and show all the fields
   if ($(this).val() == 0) {
        $('#a_firstName').val('');
        $('#a_middleName').val('');
        $('#a_lastName').val('');
        $('#a_email').val('');
        $('#a_phone').val('');
        $('#a_userID').val('');
        $('#a_address').val('');
        $('#a_city').val('');
        $('#a_pcode').val('');
        $('input[type=text],input[type=email]').removeAttr('disabled');
        $('#a_username_div').hide();
        $('.Admin_div').fadeIn(); 
   }
   //if yes admin is part of the school
   if ($(this).val() == 1){
       $('.Admin_div').hide();
       //show the input field to input username
       $('#a_username_div').fadeIn();
   }
});

//type the username of admin and check if already on the system
$(document).on('click','#a_search',function(e){
    e.preventDefault();
    if ($('#a_username').val()!='') {
        $.ajax({
            url: 'searchUsers',
            type: 'POST',
            data: {'username':$('#a_username').val()},
            dataType:'json'
        })
        .done(function(data) {
            //check if data variable has anything
            if (data!='') {
                //check if this user is not a learner
                find_admin_user(data);
            }else {
                $('#a_username_alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Thuto-sci cannot find the username or ID number you specified!</div>');
                $('.Admin_div').hide();
            }
            
        })
        .fail(function(){
            alert("error");
        })
    }
});

//to check if the user is not one of the current learners
function find_admin_user(data) {
    $.ajax({
            url: 'find_user',
            type: 'POST',
            data: {'userID':data[0]['userID']},
        })
        .done(function(msg) {
            if (msg=='YES'){
                $('#a_username_alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Admin you entered is a learner. A learner cannot have admin role!</div>');
                $('.Admin_div').hide();
            }
            else {
                $('#a_username_alert').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Thuto-sci found the user as specified. You can proceed!</div>');
                $('#a_userID').val(data[0]['userID']);
                $('#a_firstName').val(data[0]['fName']);
                $('#a_middleName').val(data[0]['midName']);
                $('#a_lastName').val(data[0]['lName']);
                //check if the db identity value is RSA id or passport
                if (data[0]['identityNumber'].length > 10) {
                    $('#a_idnumber').val(data[0]['identityNumber']).attr('disabled', true);
                    $('#rsaid').fadeIn();
                    $("#id_type_rsa").prop("checked", true);
                }else{
                    $('#a_pass_number').val(data[0]['identityNumber']);
                    $('#passport').fadeIn();
                    $("#id_type_pass").prop("checked", true)
                }
                
                $('#a_email').val(data[0]['email']).attr('disabled', true);
                $('#a_phone').val(data[0]['phone']);
                //get the value of address and split it for other address fields
                var address = data[0]['address'].split(',');
                $('#a_address').val(address[0]);
                $('#a_city').val(address[1]);
                $('#a_pcode').val(address[2]);
                $('.Admin_div').fadeIn();

            }
        })
        .fail(function(){
            alert("error");
    })    
}

$(document).on('click','#submit_admin',function(e){
    e.preventDefault();
    var form = $('#admin_form')[0];
    var form_data = new FormData(form);
    //if ($('#t_username').val()!='') {
        $.ajax({
            url: 'add_admin_user',
            type: 'POST',
            data: form_data,
            contentType:false,
            processData:false,
            //dataType:'json'
        })
        .done(function(msg) {
            //console.log(window.location.href);
            if (msg != "TRUE"){
               $('.admin-alert').html('<div class="alert alert-danger text-center" alert-dismissable>'+
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + msg + '</div>');
           }else{
            //get the current url
            var app_url = window.location.href;
            //take off the last part of the url after /
            app_url = app_url.substr(0, app_url.lastIndexOf("/"));
            var redirect_url = app_url.substr(0, app_url.lastIndexOf("/"));
            //redirect to manage users page
            window.location.replace(redirect_url+"/admin/Users?statusInsert=$statusInsert");
           }
            
        })
        .fail(function(){
            console.log("Error: Please try again or contact school admin if it persist");
        })
    //}
});
/**//////////////////////////////////////////////////////////
/*   ****************Teacher section*************************
/**//////////////////////////////////////////////////////////

/**
 * Use to toggle if the second guardian is already part of school or not
 */
$(document).on('change','#tsr',function(e){
   e.preventDefault();
   //if NO is selected, then hide username input and show all the fields
   if ($(this).val() == 0) {
        $('#t_firstName').val('');
        $('#t_middleName').val('');
        $('#t_lastName').val('');
        $('#t_email').val('');
        $('#t_phone').val('');
        $('#t_userID').val('');
        $('#t_address').val('');
        $('#t_city').val('');
        $('#t_pcode').val('');
        $('input[type=text],input[type=email]').removeAttr('disabled');
        $('#t_username_div').hide();
        $('.teacher_div').fadeIn(); 
   }
   //if yes the guardian is part of the school
   if ($(this).val() == 1){
       $('.teacher_div').hide();
       //show the input field to input username
       $('#t_username_div').fadeIn();
   }
});

//type the username of the guardian and check if already on the system
$(document).on('click','#t_search',function(e){
    e.preventDefault();
    if ($('#t_username').val()!='') {
        $.ajax({
            url: 'searchUsers',
            type: 'POST',
            data: {'username':$('#t_username').val()},
            dataType:'json'
        })
        .done(function(data) {
            //check if data variable has anything
            if (data!='') {
                //check if this user is not a learner
                find_teacher_user(data);
            }else {
                $('#t_username_alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Thuto-sci cannot find the username or ID number you specified!</div>');
                $('.teacher_div').hide();
            }
            
        })
        .fail(function(){
            alert("error");
        })
    }
});

//to check if the user is not one of the current learners
function find_teacher_user(data) {
    $.ajax({
            url: 'find_user',
            type: 'POST',
            data: {'userID':data[0]['userID']},
        })
        .done(function(msg) {
            if (msg=='YES'){
                $('#t_username_alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Teacher you entered is a learner. A learner cannot have teacher role!</div>');
                $('.teacher_div').hide();
            }
            else {
                $('#t_username_alert').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Thuto-sci found the user as specified. You can proceed!</div>');
                $('#t_userID').val(data[0]['userID']);
                $('#t_firstName').val(data[0]['fName']);
                $('#t_middleName').val(data[0]['midName']);
                $('#t_lastName').val(data[0]['lName']);
                //check if the db identity value is RSA id or passport
                if (data[0]['identityNumber'].length > 10) {
                    $('#t_idnumber').val(data[0]['identityNumber']).attr('disabled', true);
                    $('#rsaid').fadeIn();
                    $("#id_type_rsa").prop("checked", true);
                }else{
                    $('#t_pass_number').val(data[0]['identityNumber']);
                    $('#passport').fadeIn();
                    $("#id_type_pass").prop("checked", true)
                }
                $('#t_email').val(data[0]['email']).attr('disabled', true);
                $('#t_phone').val(data[0]['phone']);
                //get the value of address and split it for other address fields
                var address = data[0]['address'].split(',');
                $('#t_address').val(address[0]);
                $('#t_city').val(address[1]);
                $('#t_pcode').val(address[2]);
                $('.teacher_div').fadeIn();

            }
        })
        .fail(function(){
            alert("error");
    })    
}

$(document).ready(function() {
    //
    $(document).on('click','#add_selected',function(e){
        e.preventDefault();
        //get selected values of each select
        var option_values = $('.add_from').map(function() {
            return $(this).val()
        }).get();
        //get selected text of each select
        var option_text = $('.add_from option:selected').map(function() {
            return $(this).text()
        }).get();

        //loop thru selected values and present them as string
        var key='';
        for (i = 0; i < option_values.length; i++)
        { 
            //build option values as string from selected options
            key += option_values[i]+',';
        }

        //declare variable to hold selected options
        //then loop thru selected subjects
        var txt='';
        for (i = 0; i < option_text.length; i++)
        { 
            //build string with all the options from select boxes
            txt += option_text[i]+',';
        }

        //remove separator at end of value and text
        var value = key.slice(0,-1);
        var subject = txt.slice(0,-1);

        //add each option to the multi-select list
        //each time user clicks on add
        $('#selected_subjects').append(
            $('<option>',
                {
                    value: value,
                    text : subject 
                }
            )
        );
        //disable add button after adding to the list
        $('#add_selected').attr('disabled', 'true');
        
    });//end of add subjects to the list

    //when the user select an item from the list and click on remove
    $(document).on('click','#remove_selected',function(e){
        e.preventDefault(); 
        //remove selected subject
        $('#selected_subjects').find('option:selected').remove();
        //get the number of subjects on the list
        var list_count = $("#selected_subjects").children().length;
        //check if the list is empty
        //if yes, disable form submit button and other related elements
        //$('#list_count').val(list_count);
        if (list_count == 0) {
            //disable form submit button
           $('#submit_teacher').attr('disabled', 'true');
           //assign button from modal [allocate teacher subjects]
            $('#mt_allocate_subjects').attr('disabled', 'true');
           //hide remove button
           $('#btn_remove_div').hide(); 
           //hide subjects list box
           $('#my_subjecs_div').hide(); 
            
        }else {
            //enable the form submit button
            $('#submit_teacher').removeAttr('disabled', 'true');
            //assign button from modal [allocate teacher subjects]
            $('#mt_allocate_subjects').removeAttr('disabled', 'true');
            //hide remove button
            $('#btn_remove_div').hide(); 
        }
    });

/**
 * ***************************HIDE and SEEK Teacher FORM********************
 */
    //hide and display address depending on whether
    //admin wants to allocate subjects to the teacher or not
    $(document).on('change','.subject_y_n',function(e){
       e.preventDefault();
       if ($(this).val()==1) {
        //display subject div
        $('#t_subject_div').fadeIn();
        //disable submit button
        $('#submit_teacher').attr('disabled', 'true');
       }else {
        //hide subjects container
        $('#t_subject_div').hide();
        $('#submit_teacher').removeAttr('disabled', 'true');
       }
    });

    /**
     * after the user selects a subject
     * show level select
     */
    $(document).on('change','#t_school_subjects',function(e){
       e.preventDefault();
       if ($(this).val() !='') {
        //clear all select boxes after adding to list box
        $("#t_level").prop('selectedIndex', 0);
        $('#level_div').fadeIn();
       }else {
        $('#level_div').hide();
       }
    });
    /**
     * after the user selects level
     * show group select
     */
    $(document).on('change','#t_level',function(e){
       e.preventDefault();
       if ($(this).val() !='') {
        $("#t_group").prop('selectedIndex', 0);
        $('#group_div').fadeIn();
       }else {
        $('#group_div').hide();
       }
    });
     /**
     * after the user selects group
     * show add subject to list button
     */
    $(document).on('change','#t_group',function(e){
       e.preventDefault();
       if ($(this).val() !='') {
        $('#add_subj_div').fadeIn();
        $('#add_selected').removeAttr('disabled', 'true');
       }else {
        $('#add_subj_div').hide();
       }
    });
    /**
     * after the user selects group
     * show add subject to list button
     */
    $(document).on('click','#add_selected',function(e){
       e.preventDefault();
       //display subject list
        $('#my_subjecs_div').fadeIn();
        //hide level select box
        $('#level_div').hide();
        //hide group select box
        $('#group_div').hide();
        //hide add subjects button 
        $('#add_subj_div').hide();
        //enable submit button
        $('#submit_teacher').removeAttr('disabled', 'true');
        //assign button from modal [allocate teacher subjects]
        $('#mt_allocate_subjects').removeAttr('disabled', 'true');
        //hide remove button if vissible
        $('#btn_remove_div').hide(); 
        $("#t_school_subjects").prop('selectedIndex', 0);
    });
    /**
     * when the user selects click inside the subject list
     * show remove subject button
     */
    $(document).on('focus','#selected_subjects',function(e){
       e.preventDefault();
        $('#btn_remove_div').fadeIn();     
    });

/**
 * **********************Teacher FORM SUBMISSION***************************
 */
    //this will trigger when the user submit the add teacher form
    $(document).on('click','#submit_teacher',function(e){
        e.preventDefault();
        //get the values of the selected subjects
        var subjects_onList = $('#selected_subjects option').map(function() {
            return $(this).val()
        }).get();
        //collect form inputs
        var form_data = {
            t_userID: $('#t_userID').val(),
            t_username: $('#t_username').val(),
            t_firstName: $('#t_firstName').val(),
            t_middleName: $('#t_middleName').val(),
            t_lastName: $('#t_lastName').val(),
            t_email: $('#t_email').val(),
            t_phone: $('#t_phone').val(),
            t_address: $('#t_address').val(),
            t_city: $('#t_city').val(),
            t_pcode: $('#t_pcode').val(),
            subject_y_n: $("input[name='subject_y_n']:checked").val(),
            id_type: $("input[name='id_type']:checked").val(),
            t_school_subjects: $('#t_school_subjects').val(),
            t_school_relation: $('#tsr').val(),
            t_level: $('#t_level').val(),
            t_group: $('#t_group').val(),
            t_sa_idnumber: $('#t_idnumber').val(),
            t_passport: $('#t_pass_number').val(),
            selected_subjects: subjects_onList,
        } 

        $.ajax({
            url: 'add_user_teacher',
            type: 'POST',
            //dataType: 'json',
            data: form_data,                
        })
        .done(function(msg) {
           if (msg != "TRUE"){
               $('.teacher-alert').html('<div class="alert alert-danger text-center" alert-dismissable>'+
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + msg + '</div>');
           }else{
           //get the current url
            var app_url = window.location.href;
            //take off the last part of the url after /
            app_url = app_url.substr(0, app_url.lastIndexOf("/"));
            var redirect_url = app_url.substr(0, app_url.lastIndexOf("/"));
            //redirect to manage users page
            window.location.replace(redirect_url+"/admin/Users?statusInsert=$statusInsert");
           }
        })
        .fail(function() {
            console.log("error");
        })      

    });

/**
 * this is for adding subjects to an already existing teacher
 * the initial process starts from 'allocate' in the teacher list
 */
 //this will trigger when the user submit button on the modal
    $(document).on('click','#mt_allocate_subjects',function(e){
        e.preventDefault();
        //get the values of the selected subjects
        var subjects_onList = $('#selected_subjects option').map(function() {
            return $(this).val()
        }).get();
        //collect form inputs
        var form_data = {
            teacherID: $('#userTedachID').val(),
            selected_subjects: subjects_onList,
        } 
        $.ajax({
            url: '../Users/add_teacher_subjects',
            type: 'POST',
            //dataType: 'json',
            data: form_data,                
        })
        .done(function(msg) {
           if (msg != "TRUE"){
               $('.teach_subj-alert').html('<div class="alert alert-danger text-center" alert-dismissable>'+
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + msg + '</div>');
           }else{
            //hide modal
            $('#allocate_subject').hide('800', function() {
              $('body').removeClass('modal-open'); 
               //need to remove div with modal-backdrop class
               $('.modal-backdrop').remove(); 
                
            });
            //display success message
            $('.win-alert').html('<div class="alert alert-success text-center" alert-dismissable>'+
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                'Subjects added Successfully.</div>');
           }
        })
        .fail(function() {
            console.log("error");
        })      

    });

 
//****************************** Re-Activate Deleted Users *************************************************//

$(document).on('click','.activate',function(e){
    e.preventDefault();
    var active = $(this).data('activateid');
     console.log(active);
    var activename = $(this).data('activatename'); 
    console.log(activename);
    $('.activation-yes').val(active);
     $('.modal-title').text('Activate User');
     $('.modal-msg').text('Are you sure you want to Re-Activate' + ' ' +  activename.toUpperCase()  + ' ?')
     $('#user_activation').show();

    });
$(document).on('click','.activation-no',function(e){
    e.preventDefault();
     $('#user_activation').hide('800', function() {
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
         
     });

    });

$(document).on('click','.activation-yes',function(e){
    e.preventDefault();
    var active =$(this).val();
    if (active != '') {

        $.ajax({
            url: 'activate_deleted_user',
            type: 'POST',
            data: {'userid': active}
            //dataType:'json',
        })
        .done(function(msg) {
            //get string YES from html
            var str = msg.substr(0,3);
            if(str == 'yes'){
                $('#user_activation').hide();
                refreshActivation(str);    
                //$('.alert-actives').html('<div class="alert alert-success text-center">The User has been Reactivated Successfully</div>');
            }

        })
        .fail(function() {
            alert("error");
        })

    }
});

function refreshActivation(str)
{
     $.ajax({
            url: 'activate_deleted_user',
            type: 'POST',
            //data: {'userid': active}*/
            //dataType:'json',
        })
        .done(function() {
            if(str == 'yes'){   
                $('.alert-actives').html('<div class="alert alert-actives text-center">'+
                    'The User has been Re-activated Successfully</div>');
            }

        })
}
//****************************** End Activate Users *************************************************//
});//document ready
