

$(document).ready(function(){
    //load the inner content [table inside timetable tab] of the timetable
    $("#tt_outputs").load('Academy/get_time_table', function() { });
    //$("#tt_outputs").load('Academy/get_time_table', function() { });




//function to get todays date and time
function getTodayDate() {
    var m = new Date().getMinutes()       // Get the minutes (0-59)
    var h =  new Date().getHours()         // Get the hour (0-23)
    var s = new Date().getSeconds()       // Get the seconds (0-59)
   var tdate = new Date();
   var DD = tdate.getDate(); //yields day
   var MM = tdate.getMonth(); //yields month
   var yyyy = tdate.getFullYear(); //yields year
   
   var currentDate = yyyy + "-" +( MM+1) + "-" + DD;
   //var currentDate= yyyy + "-" +( MM+1) + "-" + dd + ' ' +h+':'+m+':'+s;

   return currentDate;
}

//this will be triggered when the user click on close button
$(document).on('click','.modalClose',function(e){
    e.preventDefault();
    //$(':input[type="text"]').prop('disabled', false);
    $("input[type=text],textarea[type=textarea]").removeAttr('disabled');
    $('input[type=text],textarea').css('cursor','');
    $(this).closest('form').find("input[type=date],input[type=file],input[type=text], input[type=submit],textarea").val("");

});

/**=============================================================================================
* ///////////////////// time table Script ////////////////
* =============================================================================================
*/

/*
* This script will fetch subjects related to the user (learner or teacher) logged in 
* subjects will be added into the dropdown list of the modal which will be triggered 
* 
*/
$(document).on('click','.addClass',function(e){
    e.preventDefault();
    //give hidden inputs values of day and time
    $('#what_wdid').val($(this).data('what_wdid'));
    $('#what_timeid').val($(this).data('what_timeid')); 
});
/*
*show delete button when the user hover over the subject
*/
$(document).on('mouseover', '.del_btn', function(e) {
    e.preventDefault();
    $(this).find('span').show();
});

/*
*remove delete button on mouse is moved out of the subject
*/
$(document).on('mouseout', '.del_btn', function(e) {
    e.preventDefault();
    $(this).find('span').hide();
});

/*
* this will run when the clicks YES button on confirm modal
* values of the hidden inputs will be taken and send them to the controller
*/
$(document).on('click','#del_btn_yes',function(e){
    e.preventDefault();
    var weekdayID = $('#del_wcs_wdID').val();
    var timeID = $('#del_wcs_ssID').val();
    var clsID= $('#del_wcs_clsID').val();
    //ajax call to get subjects
    $.ajax({
        url: 'Academy/remove_timetable_subjects',
        type: 'POST',
        data: {
                'weekdayID':weekdayID,
                'timeID':timeID,
                'clsID':clsID,
            },
    })
    .done(function(data) {
        $("#tt_outputs").load('Academy/get_time_table', function() { });
    })
    .fail(function() {
        console.log("error");
    })
    
});

/*
* This script execute when the user click on delete button of the subject
* the hidden inputs will be populated with values to be used for deleting subject 
*/
$(document).on('click','.del_btn',function(e){
    e.preventDefault();
    $('#tt_del_heading').text("Remove Subject!");
    $('#tt_myP').text('Are you sure you want to remove this subject?');
    //if the user clicks YES, take the ID for delete
    $('#del_wcs_wdID').val($(this).data('what_wdid'));
    $('#del_wcs_ssID').val($(this).data('what_timeid'));
    $('#del_wcs_clsID').val($(this).data('what_clsid'));
});
/*
* This script will submit the selected subject to the database 
*/
$(document).on('click','#add_my_class',function(e){
    e.preventDefault();
    //get form inputs
    var form = $('#add_my_tt_subjects')[0];
    var form_data = new FormData(form);
    //ajax call to get subjects
    $.ajax({
        url: 'Academy/add_timetable_subjects',
        type: 'POST',
        data: form_data,
        processData:false,
        contentType:false,
    })
    .done(function(data) {
        //refresh the page
        $("#tt_outputs").load('Academy/get_time_table', function() { });
    })
    .fail(function() {
        console.log("error");
    })
    
});

/**=============================================================================================
* ///////////////////// Progress Script ////////////////
* =============================================================================================
*/

//datepicker calendar for progress
    $(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();
    
    function cb(start, end) {
        var valueX = $('.progressDateRange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        //console.log(start.format('YYYY-MM-DD'));
        //console.log(end.format('YYYY-MM-DD'));
        //call function to send ajax request and search for this range
    }

    $('.progressDateRange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
//
    cb(start, end);
    
});
  
/**
 * [description]
 * @param  {[type]} e){               e.preventDefault();    var form [description]
 * @return {[type]}      [description]
 */
$(document).on('click','.saveProgress',function(e){
    e.preventDefault();
    //get the hidden input progress ID 
    var lID = $('.progress_lID').map(function() {
        return $(this).val()
    }).get();
    //get the hidden input progress clsID
    var clsID = $('.progress_clsID').map(function() {
        return $(this).val()
    }).get();
    //get the hidden input progressID [record ID]
    var pID = $('.progressID').map(function() {
        return $(this).val()
    }).get();
    //get the hidden input progress marks
    var marks = $('.progress_mark').map(function() {
        return $(this).val()
    }).get();

    //declare and build an array to store all the value
    //to be send to the controller
    var form_data = [];
    form_data[0]=pID;
    form_data[1]=clsID;
    form_data[2]=lID;
    form_data[3]=marks;
    //send AJAX request to the controller
    $.ajax({
        url: 'Academy/updateProgress',
        type: 'POST',
        data: {'form_data':form_data},
        //processData:false,
    })
    .done(function(progress) {
        $('.progress-alerts').fadeIn();
        if (progress == 0) {
            $('.progress-alerts').html('<div class="alert alert-success text-center" alert-dismissable> \
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                'All marks saved successfully.</div>');
        }else {
            $('.progress-alerts').html('<div class="alert alert-danger text-center" alert-dismissable> \
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                progress +' marks were not updated. Please confirm them.</div>');            
        }              
    })
    .fail(function() {
        alert("Something went wrong, Please try again.");
    })
});

//
$(document).on('change','.which_sub_assess',function(e){
    e.preventDefault();
    var whichAssess = $(this).val();
    var subjectName = $(this).data('progress_subject');
    var clsID = $(this).data('progress_cls_id');
    var cglID = $(this).data('cglid');
    var level = $(this).data('progress_level');
    var cg = $(this).data('progress_cg');
    $.ajax({
        url: 'Academy/getLearnersProgress',
        type: 'POST',
         data: {'cglID':cglID,
                'clsID':clsID,
                'reason':$('#progress_reason').val(),
                'whichAssess':whichAssess,
            },
        dataType:'json',
    })
    .done(function(data) {
        //$('.tbl_results').fadeIn();
        var id= '';
        var subject = subjectName.replace(/\s+/g, "");
        var lvl = level.replace(/\s+/g, "");
        var id= '#progress'+subject+'-'+cg+'-'+lvl;
        $(id).html(data);                               
    })
    .fail(function() {
        alert("error");
    })
});

//this will submit the assessment setup before the teacher can enter marks
$(document).on('click','#submit_assess_setup',function(e){
    e.preventDefault();

    var clsID = $('#clsID_assess_number').val();
    var subjectName = $('#subname_assess').val();
    var cglID = $('#cglid_assess').val();
    var level = $('#level_assess').val();
    var cg = $('#cg_assess').val();
    //get form inputs
    var form = $('#frmAssess_setup')[0];
    var form_data = new FormData(form);
    $.ajax({
        url: 'Academy/setupNewAssessment',
        type: 'POST',
        data: form_data,
        cache:false,
        processData:false,
        contentType:false,
    })
    .done(function(msg){
        if(!isNaN(msg) && msg > 0 ){
            //call method to insert a new list of learners
            $('#addProgressModal').hide('800', function() {
                $(this).closest('form').find("input[type=hidden],input[type=text]").val("");
                //modal-open class is added on body so it has to be removed
                $('body').removeClass('modal-open'); 
                //need to remove div with modal-backdrop class
                $('.modal-backdrop').remove();
            });
            get_learners(subjectName,level,cg,cglID,clsID,msg);
        }else{
            $('.progress-alerts').fadeIn();
            $('.progress-alerts').html('<div class="alert alert-danger text-center" alert-dismissable> \
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                        msg +'</div>');
        }                             
    })
});

//this method will be called to create a new list of learners
function get_learners(subjectName,level,cg,cglID,clsID,whichAssess){
    $.ajax({
        url: 'Academy/getLearnersProgress',
        type: 'POST',
        data: {'cglID':cglID,
                'clsID':clsID,
                'reason':$('#progress_reason').val(),
                'whichAssess':whichAssess,
            },
        dataType:'json',
    })
    .done(function(data) {
        //$('.tbl_results').fadeIn();
        var id= '';
        var subject = subjectName.replace(/\s+/g, "");
        var lvl = level.replace(/\s+/g, "");
        var id= '#progress'+subject+'-'+cg+'-'+lvl;
        $(id).html(data);
                                     
    })
}

//display the progress when the user select option to view
$(document).on('change','.vieWhat',function(e){
    e.preventDefault();
    var wannaDo = $(this).val();
    var cglID = $(this).data('cglid');
    var clsID = $(this).data('progress_cls_id');
    var level = $(this).data('progress_level');
    var cg = $(this).data('progress_cg');
    var subjectName= $(this).data('progress_subject');
    //attach option selected to hidden input
    $('#progress_reason').val(wannaDo);
    //if the user wants to view
    if(wannaDo == 1){
        //$('.tbl_results').fadeOut();
        //populate the assessment list
        loadAssessments_by_cls(clsID);
        $('.which_sub_assess').prop('selectedIndex', 0);
        $('.div_which_assess').fadeIn();
        
    //if the user wants to add new mark
    }else if(wannaDo == 2){
        $('.div_which_assess').fadeOut();
        //load assessment setup modal
        $('#addProgressModal').modal('show');
        //populate hidden inputs in the form with values
        $('#clsID_assess_number').val(clsID);
        $('#subname_assess').val(subjectName);
        $('#cglid_assess').val(cglID);
        $('#level_assess').val(level);
        $('#cg_assess').val(cg);
    //if the user want to update certain marks
    }else if(wannaDo == 3){
        //$('.tbl_results').fadeOut();
        //populate the assessment list 
        loadAssessments_by_cls(clsID);
        $('.which_sub_assess').prop('selectedIndex', 0);
        $('.div_which_assess').fadeIn();
    }      
});

//this method will be triggered when the user wants to view or update marks
//method is used to populate the assessement dropdown with the assessments related to the subject
function loadAssessments_by_cls(clsID){
    $.ajax({
        url: 'Academy/getAssessments_by_cls',
        type: 'POST',
        data: {'clsID':clsID},
        dataType: 'JSON',
    })
    .done(function(data) {
        $('.which_sub_assess').html(data);                                
    })
    .fail(function() {
        console.log("error");
    })
}

$(document).on('click','.progressInnerTab',function(e){
    e.preventDefault();
    var subjectName = $(this).data('subname');
    var level = $(this).data('level');
    var cg = $(this).data('cg');
    if (subjectName!='') {
        $.ajax({
            url: 'Academy/getLearnersProgress',
            type: 'POST',
            data: {'subjectName':subjectName,
                    'cglID':$(this).data('cglid'),
                    'clsID':$(this).data('clsid')
                    },
            dataType: 'JSON',
        })
        .done(function(data) {
            $('.vieWhat').prop('selectedIndex', 0);
            $('.form_new_assessment').fadeOut();
            //$('.tbl_results').fadeIn();
            $('.div_which_assess').fadeOut();
            $('.progress-alerts').fadeOut();
            var id= '';
            var subject = subjectName.replace(/\s+/g, "");
            var lvl = level.replace(/\s+/g, "");
            var id= '#progress'+subject+'-'+cg+'-'+lvl;
            $(id).html(data);                                
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    }
});  
/**
 * when the user clicks on the edit button, 
 * modal will popup with values ready to be edited,
 * but all text inputs will be disabled to avoid changing them
 * @param  {[type]} e){                 e.preventDefault();    var form [description]
 * @return {[type]}      [description]
 */
$(document).on('click','.editProgress',function(e){
    e.preventDefault();
    $('#progress_clsID').val($(this).attr('data-marks_clsID'));
    $('#progressID').val($(this).attr('data-marksID'));
    $('#progress_lID').text($(this).attr('data-marksLID'));
    $('#progress_lFName').text($(this).attr('data-marksFName'));
    $('#progress_lLName').text($(this).attr('data-marksLName'));
    $('#progress_mark').val($(this).attr('data-marksActual'));
    $('#progress_mark').focus();
    $('#updateProgress').val('Update');
    $('.modal-title').text('Edit Learner Marks');
});


/**
 * ********************************START GUARDIAN  Progress SECTION****************************
 */
//this will get progress for parent view
$(document).on('click','.gProgressLearnInnerTab',function(e){
    e.preventDefault();
    var fname = $(this).data('lfname');
    var lname = $(this).data('llname');
    var childID = $(this).data('luid');
    if (childID) {
        $.ajax({
            url: 'Academy/getGuardLearnSubject',
            type: 'POST',
            data: {'childID': childID,
                    'fname':fname,
                    'progress':1,
                    },
            dataType:'json',
        })
        .done(function(data) {
            $('.progressBuildSubj').html(data);                              
        })
        .fail(function() {
           alert("Error: Please contact admin if this persits...");
        })

    }

});   
/**=============================================================================================
* ///////////////////// Discussion Script ////////////////
* =============================================================================================
*/

//hide success message after few seconds
setTimeout(function() {
    //$('.discuss-alerts').show();
    //$('.discuss-alerts').fadeOut('slow');
}, 20000); //hide after 20sec


/**this triggers when the user click on the save(submit) button**
*/
$(document).on('click','.saveComment',function(e){
    e.preventDefault();
    var topicID= $('#commentReload').val();
    var form = $('.frmComment')[0];
    var form_data = new FormData(form);
    $.ajax({
        url: 'Academy/addComment',
        type: 'POST',
        cache:false,
        data: form_data, //send this to php
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
    })
    .done(function(msg) {
            //alert(msg);
            if (msg == "Added"){
                //clear textarea
                $("textarea[type=textarea]").val('')
                //method to get data after comment add
                loadComments(topicID);
            }
        })
    .fail(function() {
        console.log("error");
    })

});

//use this method to refresh the comments
function loadComments (topicID) {
     $.ajax({
            url: 'Academy/getTopicComments',
            type: 'POST',
            data: {'topicID': topicID},
            dataType:'json',
        })
        .done(function(data) {
            $('.saveComment').val('Send');
            $('.commentsDiv').html(data);                          
        })
        .fail(function() {
            alert("error");
        })
}//end loadComments

//this will get the user to view topic and its comments
$(document).on('click','.viewTopicComments',function(e){
    e.preventDefault();
    var topicID = $(this).data('id');
    $('.modal-title').html("<strong>Topic and Comments</strong>");
    $('#comment_topicID').val(topicID);
    if (topicID != '') {
        $.ajax({
            url: 'Academy/getTopicComments',
            type: 'POST',
            data: {'topicID': topicID},
            dataType:'json',
        })
        .done(function(data) {
            $('#commentReload').val(topicID);
            $('.saveComment').val('Send');
            $('.commentsDiv').html(data);                          
        })
        .fail(function() {
            alert("error");
        })

    }

}); 


/**
 *===============================================================================
 *"""""""""""""""""""""""""""""END of COMMENTS SCRIPT"""""""""""""""""""""""""
 *===============================================================================
 */
/**this triggers when the user click on the save(submit) button**
*/
$(document).on('click','.saveTopic',function(e){
    e.preventDefault();
    var form = $('.frmTopic')[0];
    var form_data = new FormData(form);
    $.ajax({
        url: 'Academy/addTopic',
        type: 'POST',
        cache:false,
        data: form_data, //send this to php
        contentType: false,
        processData: false,
    })
    .done(function(msg) {
            //alert(msg);
            if (msg == "Added"){
                //close modal
                $('#topicModal').hide('800', function() {
                    //clear text inputs
                    $("input[type=text],textarea[type=textarea]").val('');
                    //modal-open class is added on body so it has to be removed
                    $('body').removeClass('modal-open'); 
                    //need to remove div with modal-backdrop class
                    $('.modal-backdrop').remove();
                    //display this to the page
                    $('.discuss-alerts').show();
                    $('.discuss-alerts').html('<div class="alert alert-success text-center" alert-dismissable> \
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                        Topic added successfully!</div>');
                    //to refresh topics after adding new
                    loadTopicsDisc($('#topic_discID').val());
                });
                
            }
            else if (msg == "updated"){
               //close modal
                $('#topicModal').hide('800', function() {
                    //clear text inputs
                    $("input[type=text],textarea[type=textarea]").val('');
                    //modal-open class is added on body so it has to be removed
                    $('body').removeClass('modal-open'); 
                    //need to remove div with modal-backdrop class
                    $('.modal-backdrop').remove();
                    //display this to the page
                    $('.discuss-alerts').show();
                    $('.discuss-alerts').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                        Topic updated successfully!</div>');
                    //to refresh topics after adding new
                    loadTopicsDisc($('#topic_discID').val());
                });    
            }
            else if (msg == "NO"){
                //display this to the page
                $('.discuss-alerts').show();
                $('.discuss-alerts').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Error! Topic not recorded, please try again.</div>');
            }
            else{
                //display this to the page
                $('.discuss-alerts').show();
                $('.discuss-alerts').html('<div class="alert alert-danger text-center" alert-dismissable> \
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> '+ msg + ' </div>');
            }
    })
    .fail(function() {
        console.log("error");
    })

}); 

function loadTopicsDisc (discID) {
     $.ajax({
            url: 'Academy/getTopicsByDiscussion',
            type: 'POST',
            data: {'discID': discID},
            dataType:'json',
        })
        .done(function(data) {
            var id= '#topicscomments'+discID;
            $(id).html(data);                     
        })
        .fail(function() {
            alert("error");
        })
}

//crab the value of cls from the button that launch modal
$(document).on('click','.addTopic',function(e){
    var discID = $(this).data('id');
    $('#topic_discID').val(discID);
    //change the button name for when the user does edit
    $('#addTopic').val("Add");
    $('.modal-title').text("Add Topic");
  });
   
//will be triggered when the user clicks on editTopic
$(document).on('click','.editTopic',function(e){
    e.preventDefault();
    $('#topic_ID').val($(this).data('id'));
    $('#topic_discID').val($(this).data('discid'));
    $('#topic_title').val($(this).data('title'));
    $('#topic_body').val($(this).data('body'));
    $('#addTopic').val("Update");
    $('.modal-title').text("Update Topic");
    
});

//delete discussion topic when the user clicks YES
$(document).on('click','.deleteTopic-Yes',function(e){
    e.preventDefault();
    //get discussion ID to be deleted
    var topicID = $(this).val();
    //hide modal after user select yes on confirm delete
    $('#topic-confirm').hide();
    //check if there an id to delete
    if (topicID!=0) {
        $.ajax({
            url: 'Academy/deleteTopic',
            type: 'POST',
            data: {'topicID':topicID},
        })
        .done(function(msg) {
            //if yes, it means deleted
            if (msg=="YES") {
                //display this to the page
                $('.discuss-alerts').show();
                $('.discuss-alerts').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Topic deleted successfully!</div>');
                //to refresh topics after adding new
                loadTopicsDisc($('#del_topic_discID').val());
            //else something went wrong 
            }else{
                //display this to the page
                $('.discuss-alerts').show();
                $('.discuss-alerts').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Record not deleted.</div>');
            }

        })
        .fail(function() {
            console.log("error");
        })
    }
   
});
$(document).on('click','.deleteTopic-No',function(e){
    e.preventDefault();
    $('#topic-confirm').hide();
});

//close modal when the user clicks OK to success
$(document).on('click','.disc-successOK',function(e){
    e.preventDefault();
    $('#disc-success').hide();
});
//load data when the user clicks on delete
//check if there are comments for that topic before deleting
//if yes, prevent the user from deleting
$(document).on('click','.deleteTopic',function(e){
    e.preventDefault();
    var delTopicID = $(this).data('id');
    var delDiscID = $(this).data('discid');
    var title=$(this).data('title');
    var title = title.toUpperCase();
    $.ajax({
        url: 'Academy/getTopicComments',
        type: 'POST',
        data: {'delTopicID': delTopicID},
    })
    .done(function(msg) {
        if (msg!="NO") {
            $('.modal-title').text("Delete!");
            //user message to confirm
            $('.myP').text('Are you sure you want to delete '+title+'?');
            //if the user clicks YES, take the ID for delete
            $('#deleteTopicID').val(delTopicID);
            $('#del_topic_discID').val(delDiscID);
            //then show modal to confirm delete
            $('#topic-confirm').show();
        }else {
            $('.modal-title').text("Delete!");
            $('.modal-msg').text('This topic: ' +title+ ' has comments. It cannot be deleted!!!');
            //then show modal to confirm delete
            $('#disc-success').show();
        }
        
    })
    .fail(function() {
        console.log("error");
    })

    
    
});

/**
 *===============================================================================
 *"""""""""""""""""""""""""""""END of TOPIC SCRIPT"""""""""""""""""""""""""
 *===============================================================================
 */

//click on subject name tab, will trigger this
$(document).on('click','.discInnerTab',function(e){
    e.preventDefault();
    var subjectName = $(this).data('subname');
    var cls = $(this).data('clsid');
    $('.discuss-alerts').hide();
    if (subjectName != '') {
        $.ajax({
            url: 'Academy/getCategoryBySubject',
            type: 'POST',
            data: {'subjectName': subjectName,
                    'cls':cls},
            dataType:'json',
        })
        .done(function(data) {
            var id= '';
            var subject = subjectName.replace(/\s+/g, "");
            var id= '.disc-'+subject;
            $(id).html(data);                                
        })
        .fail(function() {
            alert("Error: Contact admin if this persists..");
        })

    }

});


//when the user clicks on VIEW (eye-ICON),
//populate data form data attr on the button 
$(document).on('click','.viewCategory',function(e){
    e.preventDefault();   
    //assign db data to the input controls on the modal
    $('#disc_cls').val($(this).data('cls'));
    $('#discID').val($(this).data('id'));
    $('#disc_title').val($(this).data('title'));
    $('#disc_body').val($(this).data('body'));
    $("input[type=text],textarea[type=textarea]").attr('disabled','disabled');
    $('input[type=text],textarea').css('cursor','pointer');
    $('#addCat').hide();
    $('.modal-title').text("View Discussion Category");
       
});   

//when the user click on EDIT, get values from the button clicked
//then populate the fields on the form
$(document).on('click','.editCategory',function(e){
    e.preventDefault();
    //assign db data to the input controls on the modal
    $('#disc_cls').val($(this).data('cls'));
    $('#discID').val($(this).data('id'));
    $('#disc_title').val($(this).data('title'));
    $('#disc_body').val($(this).data('body'));
    $('#disc_subname').val($(this).data('subname'));
    $('#addCat').show();
    $('#addCat').val("Update");
    $('.modal-title').text("Update Discussion Category");
    
});

//delete discussion topic when the user clicks YES
$(document).on('click','.deleteDisc-Yes',function(e){
    e.preventDefault();
    //get discussion ID to be deleted
    var discID = $(this).val();
    //hide modal after user select yes on confirm delete
    $('#disc-confirm').hide();
    //check if there an id to delete
    if (discID!=0) {
        $.ajax({
            url: 'Academy/deleteCategory',
            type: 'POST',
            data: {'discID':discID},
        })
        .done(function(msg) {
            //if yes, it means deleted
            if (msg=="YES") {
                var subjectName = $('#del_disc_subname').val();
                var cls = $('#del_disc_cls').val();
                //method to refresh results
                loadCategories(subjectName,cls);
               //display this to the page
                $('.discuss-alerts').show();
                $('.discuss-alerts').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Discussion Category deleted successfully!</div>');               
            //else something went wrong 
        }else{
            //display this to the page
            $('.discuss-alerts').show();
            $('.discuss-alerts').html('<div class="alert alert-danger text-center" alert-dismissable>\
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Discussion Category not deleted!</div>');
        }

    })
        .fail(function() {
            console.log("error");
        })
    }
   
});


//load data when the user clicks on delete
$(document).on('click','.deleteCategory',function(e){
    e.preventDefault();
    var cls = $(this).data('cls');
    var subjectName = $(this).data('subname');
    var discID = $(this).data('id');
    var title=$(this).data('title');
    var title = title.toUpperCase();
    $('.modal-title').text("Delete!");
    $('.myP').text('Are you sure you want to delete '+title+'?');
    //if the user clicks YES, take the ID for delete
    $('#deleteDiscID').val(discID);
    $('#del_disc_subname').val(subjectName);
    $('#del_disc_cls').val(cls);
    //then show modal to confirm delete
    $('#disc-confirm').show();
});
/**this triggers when the user click on the save(submit) button**
*/
$(document).on('click','.saveCategory',function(e){
    e.preventDefault();
    var form = $('.frmDisc')[0];
    var form_data = new FormData(form);
    $.ajax({
        url: 'Academy/addDiscCategory',
        type: 'POST',
        cache:false,
        data: form_data, //send this to php
        contentType: false,
        processData: false,
    })
    .done(function(msg) {
            var subject = $('#disc_subname').val();
            var cls = $('#disc_cls').val();
           
            //check if added
            if (msg == "Added"){
                //close modal
                $('#discModal').hide('800', function() {
                     //clear control mode variable
                    $('#discID').val('');
                    //clear text inputs
                    $("input[type=text],textarea[type=textarea],input[type=submit]").val('');
                    //modal-open class is added on body so it has to be removed
                    $('body').removeClass('modal-open'); 
                    //need to remove div with modal-backdrop class
                    $('.modal-backdrop').remove();
                    //display this to the page
                    $('.discuss-alerts').show();
                    $('.discuss-alerts').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                        Discussion Category added successfully!</div>');
                    //to refresh topics after adding new
                    loadCategories(subject,cls);
                });
              //check if updated  
            }else if (msg == "updated"){
                //close modal if update went well
                $('#discModal').hide('800', function() {
                    //clear control mode variable
                    $('#discID').val('');
                    //clear text inputs
                    $("input[type=text],textarea[type=textarea],input[type=submit]").val('');
                    //modal-open class is added on body so it has to be removed
                    $('body').removeClass('modal-open'); 
                    //need to remove div with modal-backdrop class
                    $('.modal-backdrop').remove();
                    //display this to the page
                    $('.discuss-alerts').show();
                    $('.discuss-alerts').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                        Discussion Category updated successfully!</div>');
                    //to refresh topics after adding new
                    loadCategories(subject,cls);
                });
            }
            else if (msg == "NO"){
                //display this to the page
                $('.discuss-alerts').show();
                $('.discuss-alerts').html('<div class="alert alert-success text-center" alert-dismissable> \
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Error! Discussion Category not recorded, please try again.</div>');
            }
            else{
                //display this to the page
                $('.discuss-alerts').show();
                $('.discuss-alerts').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + msg + '</div>');
            }
        })
    .fail(function() {
        console.log("error");
    })
}); 

//crab the value of cls from the button that launch modal
$(document).on('click','.addDisc',function(e){
    $('#disc_subname').val($(this).data('subname'));
    $('#disc_cls').val($(this).val());
    //change the button name for when the user does edit
    $('#addCat').val("Add");
    $('.modal-title').text("Add Discussion Category");
  });

//refresh categories after add, delete
function loadCategories(subjectName,cls) {
    $.ajax({
        url: 'Academy/getCategoryBySubject',
        type: 'POST',
        data: {'subjectName': subjectName,
                'cls':cls},
        dataType:'json',
    })
    .done(function(data) {
        var id= '';
        var subject = subjectName.replace(/\s+/g, "");
        var id= '.disc-'+subject;
        $(id).html(data);                                
    })
    .fail(function() {
        alert("Error: Contact admin if this persists..");
    })

}//end loadCategories

/**=============================================================================================
* ///////////////////// Attandence Script ////////////////
* =============================================================================================
*/
    //datepicker for attendance
    $(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        var valueX = $('.attandDateRange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        //console.log(start.format('YYYY-MM-DD'));
        var startDate = start.format('YYYY-MM-DD');
        //console.log(end.format('YYYY-MM-DD'));
        var endDate = end.format('YYYY-MM-DD');
        //call function to send ajax request and search for this range
        //search_attendance_by_date(startDate,endDate);
    }

    $('.attandDateRange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
    
});

//declare the function that take the date ranges, send to controller to search for values on that
function search_attendance_by_date(startDate,endDate){
    $.ajax({
        url: 'Academy/getLearnersForAttend',
        type: 'POST',
        data: {'startDate': startDate,
                'endDate':endDate
                },
        dataType:'json',
    })
    .done(function(data){
        console.log(data);
    })
}

//when the user clicks the top innerTab, then fetch the results and display them
$(document).on('click','.attendInnerTab',function(e){
    e.preventDefault();
    var subjectName = $(this).data('subname');
    var clsID = $(this).data('clsid');
    var cglID = $(this).data('cglid');
    var level = $(this).data('level');
    var cg = $(this).data('cg');
    
    if (clsID != '' && cglID != '' && level != '' && subjectName != '') {
       // alert(subjectName);
        $.ajax({
            url: 'Academy/getLearnersForAttend',
            type: 'POST',
            data: {'subjectName': subjectName,
                    'clsID':clsID,
                    'cglID':cglID
                    },
            dataType:'json',
        })
        .done(function(data) {
            //reset the select box
            $('.vieWhichAttend').prop('selectedIndex', 0);
            var id= '';
            var subject = subjectName.replace(/\s+/g, "");
            var lvl = level.replace(/\s+/g, "");
            var id= '#attend'+subject+'-'+cg+'-'+lvl;
            $(id).html(data);                                
        })
        .fail(function() {
            console.log("error");
        })
    }
}); 
//display the progress when the user select option to view
$(document).on('change','.vieWhichAttend',function(e){
    e.preventDefault();
    var wannaDo = $(this).val();
    var cglID = $(this).data('cglid');
    var clsID = $(this).data('attend_cls_id');
    var subjectName= $(this).data('attend_subject');
    var level = $(this).data('attend_level');
    var cg = $(this).data('attend_cg');
    if (wannaDo!=0) {
        $.ajax({
            url: 'Academy/getLearnersForAttend',
            type: 'POST',
            data: {'reason':wannaDo,
                    'clsID':clsID,
                    'cglID':cglID,
                    'subjectName':subjectName
                   },
            dataType: 'JSON',
        })
        .done(function(data) {
            var id= '';
            var subject = subjectName.replace(/\s+/g, "");
            var lvl = level.replace(/\s+/g, "");
            var id= '#attend'+subject+'-'+cg+'-'+lvl;
            $(id).html(data);                                
        })
        .fail(function() {
            console.log("error");
        })        
    }
}); 

// clicking on the toggle button (red or green) 
// will trigger this function and update attandance
// with 1 or 0 depending on the button clicked
$(document).on('click','.markAttend',function(e){
  e.preventDefault();
    var status = $(this).val();
    var lID = $(this).attr('data-lID');
    var clsID = $(this).attr('data-clsID');
    var rowID = $(this).attr('data-rowID');
    if ((lID != 0) && (clsID != 0)){
        $.ajax({
            url: 'Academy/markAttendance',
            type: 'POST',
            data: {'lID':lID,
                    'rowID':rowID,
                    'clsID':clsID,
                    'status':status,
                    },
        })
        .done(function(msg) {
               if (msg=="Updated") {
                    console.log("Updated");
                }
                                          
        })
        .fail(function() {
            alert("Error: Please contact admin if this persits...");
        })
    }

});

/**
 * ********************************START ATTENDANCE SECTION****************************
 */
//this will get assignments for teacher or learner, even for parent view
$(document).on('click','.gAttendLearnInnerTab',function(e){
    e.preventDefault();
    var fname = $(this).data('lfname');
    var lname = $(this).data('llname');
    var childID = $(this).data('luid');
    if (childID) {
        $.ajax({
            url: 'Academy/getGuardLearnSubject',
            type: 'POST',
            data: {'childID': childID,
                    'fname':fname,
                    'attend':1,
                    },
            dataType:'json',
        })
        .done(function(data) {
            $('.attendBuildSubj').html(data);                              
        })
        .fail(function() {
           alert("Error: Please contact admin if this persits...");
        })

    }

});    

//view assignment 
//for learner guardian
$(document).on('click','.viewGuardStudy',function(e){
    e.preventDefault();

    $("#gStudy_title").text($(this).data('read_title'));
    $("#gStudy_desc").text($(this).data('read_desc'));
    $("#gStudy_pubDate").text($(this).data('read_pd'));
    $("#gStudy_down").attr('href',$(this).data('read_path'));    
});


/**=============================================================================================
* /////////////////////Study material Script////////////////
* =============================================================================================
*/

//this will get study material for teacher or learner, even for parent view
//$('.studyInnerTab').on('click',function(e){
$(document).on('click','.studyInnerTab',function(e){
    e.preventDefault();
    var subjectName = $(this).data('subname');
    var clsID= $(this).data('clsid');
    $('.study-alerts').hide();
    if (subjectName != '') {
        $.ajax({
            url: 'Academy/getMaterial', 
            type: 'POST',
            data: {'subjectName': subjectName,
                    'clsID':clsID,
                    },
            dataType:'json',
        })
        .done(function(data) {
            var tbody= '';
            var subject = subjectName.replace(/\s+/g, "");
            var tbody= '.study'+subject;
            $(tbody).html(data);
                                           
        })
        .fail(function() {
            alert("Error: Please contact admin if this persits...");
        })

    }

});

//refresh categories after add, delete
function refreshMaterial(subjectName,cls) {
    $.ajax({
        url: 'Academy/getMaterial', 
        type: 'POST',
        data: {'subjectName': subjectName,
                'clsID':cls,
                },
        dataType:'json',
    })
    .done(function(data) {
        var tbody= '';
        var subject = subjectName.replace(/\s+/g, "");
        var tbody= '.study'+subject;
        $(tbody).html(data);                             
    })
    .fail(function() {
        alert("Error: Please contact your school admin if this persits...");
    })

}//end loadCategories

/**this triggers when the user click on the edit button**
    then it displays a modal with form fields populated with
    database data for the specific record
    */
$(document).on('click','.editMaterial',function(e){
    e.preventDefault();
    //assign db data to the input controls on the modal
    $('#sm_cls').val($(this).data('study_cls'));
    $('#sm_subname').val($(this).data('study_subname'));
    $('#sm_studyID').val($(this).data('study_id'));
    $('#study_file').text($(this).data('study_path'));
    $('#sm_fileID').val($(this).data('study_fileid'));
    $('#sm_title').val($(this).data('study_title'));
    $('#sm_description').val($(this).data('study_desc'));
    $('.study_UPSERT').text("Edit Study Material");
    $('#study_adding').val("Update");
});

//delete study material
$(document).on('click','.study_delete_Yes',function(e){
    e.preventDefault();
    var studyID = $(this).val();
    //hide modal after user select yes on confirm delete
    $('#study-confirm').hide();
    //check if there an id to delete
    if (studyID!=0) {
        $.ajax({
            url: 'Academy/deleteMaterial',
            type: 'POST',
            data: {'studyID':studyID},
        })
        .done(function(msg) {
            var cls = $('#del_study_cls').val();
            var subjectName = $('#del_study_subname').val();
            //if yes, it means deleted
            if (msg=="YES") {
                //display this to the page
                $('.study-alerts').show();
                $('.study-alerts').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Study material deleted successfully!</div>');
                //refresh contents immediately after adding new content
                refreshMaterial(subjectName,cls);
            //else something went wrong 
        }else{
            //display this to the page
            $('.study-alerts').show();
            $('.study-alerts').html('<div class="alert alert-danger text-center" alert-dismissable>\
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                Study material not deleted!</div>');
        }


    })
        .fail(function() {
            console.log("error");
        })
    }
   
});

//close modal when the user clicks no to delete
$('.study_delete_No').on('click',function(e) {
    e.preventDefault();
    $('#study-confirm').hide();
});

//close modal when the user clicks OK to success
$('.study_successOK').on('click',function(e) {
    e.preventDefault();
    $('#study-success').hide();
});
//load data when the user clicks on delete
$(document).on('click','.deleteMaterial',function(e){
    e.preventDefault();
    $('#del_study_cls').val($(this).data('study_cls'));
    $('#del_study_subname').val($(this).data('study_subname'));
    var studyID = $(this).data('study_id');
    var title=$(this).data('study_title');
    var title = title.toUpperCase();
    $('.modal-title').text("Delete!");
    $('.myP').text('Are you sure you want to delete '+title+'?');
    $('#deleteStudyID').val(studyID);
    $('#study-confirm').show();
});

/**this triggers when the user click on the save(submit) button**
*/
$(document).on('click','.saveMaterial',function(e){
    e.preventDefault();
    var form = $('.frmStudy')[0];
    var form_data = new FormData(form);
    //var form_data = $('.frmStudy').serialize();
    $.ajax({
        url: 'Academy/addMaterial',
        type: 'POST',
        cache:false,
        data: form_data, //send this to php
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
    })
    .done(function(msg) {
        var cls = $('#sm_cls').val();
        var subjectName = $('#sm_subname').val();
        //alert(msg);
        if (msg == "Added"){
             //close modal
            $('#studyModal').hide('800', function() {
                 //clear control mode variable
                //$('#studyID').val('');
                //clear text inputs
                clear_modal();
                //display this to the page
                $('.study-alerts').show();
                $('.study-alerts').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Study material added successfully!</div>');
                //refresh contents immediately after adding new content
                refreshMaterial(subjectName,cls);
            });
        }else if (msg == "updated"){
            $('#studyModal').hide('800', function() {
                 //clear control mode variable
                $('#studyID').val('');
                //clear text inputs
                clear_modal();
                //display this to the page
                $('.study-alerts').show();
                $('.study-alerts').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Study material updated successfully!</div>');
                //refresh contents immediately after adding new content
                refreshMaterial(subjectName,cls)
            });
        }
        else if (msg == "NO"){
            $('.study-alert').html('<div class="alert alert-danger text-center">Error! Material was not recorded, please try again.</div>');
        }
        else{
            $('.study-alert').html('<div class="alert alert-danger">' + msg + '</div>');
        }
    })
    .fail(function() {
        console.log("error");
    })
}); 

//crab the value of cls from the button that launch modal
$(document).on('click','.addStudy',function(e){
     e.preventDefault();
      $('#sm_cls').val($(this).data('clsid'));
      $('#sm_subname').val($(this).data('subname'));
      $('#study_file').text('');
      $('#study_adding').val("Add");
      $('.study_UPSERT').text("Add Study Material");
  });


/**
 * ********************************START GUARDIAN study material SECTION****************************
 */
//this will get study material for teacher or learner, even for parent view
$(document).on('click','.gStudyLearnInnerTab',function(e){
    e.preventDefault();
    var fname = $(this).data('lfname');
    var lname = $(this).data('llname');
    var childID = $(this).data('luid');
    if (childID) {
        $.ajax({
            url: 'Academy/getGuardLearnSubject',
            type: 'POST',
            data: {'childID': childID,
                    'fname':fname,
                    'study':1,
                    },
            dataType:'json',
        })
        .done(function(data) {
            $('.studyBuildSubj').html(data);                              
        })
        .fail(function() {
           alert("Error: Please contact admin if this persits...");
        })

    }

});    

//view assignment for learner guardian
$(document).on('click','.viewGuardStudy',function(e){
    e.preventDefault();
    $("#gStudy_title").text($(this).data('read_title'));
    $("#gStudy_desc").text($(this).data('read_desc'));
    $("#gStudy_pubDate").text($(this).data('read_pd'));
    $("#gStudy_down").attr('href',$(this).data('read_path'));    
});
/**
 * ********************************END GUARDIAN study material SECTION*******************************
 */

/**=============================================================================================
* /////////////////////Assignment Script////////////////
* =============================================================================================
*/

//this method is common to most modal close that needs to be executed on this scripts file
function clear_modal() {
    $("input[type=text],textarea[type=textarea]").removeAttr('disabled');
    $('input[type=text],textarea').css('cursor','');
    $(this).closest('form').find("input[type=file],input[type=hidden],input[type=text], input[type=submit],textarea").val("");
    //modal-open class is added on body so it has to be removed
    $('body').removeClass('modal-open'); 
    //need to remove div with modal-backdrop class
    $('.modal-backdrop').remove();
}

//to be used for 
//put this as the field on the html [create assignment]  
//<input type="text" name="daterange" value="01/01/2015 - 01/31/2015" />
$(function() {
    //$("#assi_dueDate").daterangepicker("setDate", currentDate);
    var currentDate = getTodayDate();
    $('input[name="dueDate"]').daterangepicker({
        timePicker: true,
        timePickerIncrement: 10,
        timePicker24Hour: true,
        singleDatePicker: true,
        autoUpdateInput: true,
        minDate: currentDate,
        dateLimit: {
            days: 7
        },
        locale: {
            applyLabel: "Apply Date",
            cancelLabel: "Clear",
            format: 'YYYY-MM-DD hh:mm'
        }
        //
    });
    //$('#assi_dueDate').html(picker.startDate.format('YYYY-MM-DD hh:mm'));
    
});

$('#assi_dueDate').on('apply.daterangepicker', function(ev, picker) {
  //listen to the apply button
  console.log(picker.endDate.format('YYYY-MM-DD hh:mm'));
});

$('#assi_dueDate').on('cancel.daterangepicker', function(ev, picker) {
  //do something, like clearing an input
  $('#assi_dueDate').val('');
});

//refresh assignments after add, delete, update
function reloadAssignments(subjectName,clsID) {
    $.ajax({
        url: 'Academy/getAssignments',
        type: 'POST',
        data: {'subjectName': subjectName,
                'clsID':clsID},
        dataType:'json',
    })
    .done(function(data) {
        var subject = subjectName.replace(/\s+/g, "");
        var tbody= '.assign'+subject;
        $(tbody).html(data);                                
    })
    .fail(function() {
        alert("Error: Contact admin if this persists..");
    })

}//end reloadAssignments

//this will get assignments for teacher or learner, even for parent view
$(document).on('click','.assignInnerTab',function(e){
    e.preventDefault();
    var subjectName = $(this).data('subname');
    var clsID = $(this).data('clsid');
    $('.assignment-alerts').text('');
    if (subjectName != '') {
        $.ajax({
            url: 'Academy/getAssignments',
            type: 'POST',
            data: {'subjectName': subjectName,
                    'clsID':clsID},
            dataType:'json',
        })
        .done(function(data) {
            //var tbody= '';
            var subject = subjectName.replace(/\s+/g, "");
            var tbody= '.assign'+subject;
            $(tbody).html(data);                                
        })
        .fail(function() {
            alert("Error: Please contact admin if this persits...");
        })

    }

});

/**this triggers when the user click on the edit button**
then it displays a modal with form fields populated with
database data for the specific record
*/
$(document).on('click','.editAssignment',function(e){
    e.preventDefault();
    //assign db data to the input controls on the modal
    $('input[type=file],textarea,input[type=text]').val('');
    $('#assi_cls').val($(this).data('clsid'));
    $('#assi_subname').val($(this).data('subname'));
    $('#assignID').val($(this).data('assignid'));
    $('#assi_fileID').val($(this).data('assign_fileid'));
    $('#assign_file').val($(this).data('assign_path'));
    $('#assi_dueDate').val($(this).data('assign_duedate'));
    $('#assi_title').val($(this).data('assign_title'));
    $('#assi_description').val($(this).data('assign_desc'));
    $('#addAssign').val("Update");
    $('.modal-assignment-title').text("Update Assignment");
  
});

//delete study material
$(document).on('click','.deleteAssign_Yes',function(e){
    e.preventDefault();
    var assignID = $(this).val();
    //hide modal after user select yes on confirm delete
    $('#assign-confirm').hide();
    //check if there an id to delete
    if (assignID!=0) {
        $.ajax({
            url: 'Academy/deleteAssignment',
            type: 'POST',
            data: {'assignID':assignID},
        })
        .done(function(msg) {
            var cls = $('#del_assi_cls').val();
            var subjectName = $('#del_assi_subname').val();
            //if yes, it means deleted
            if (msg=="YES") {
                //display this to the page
                //$('.assignment-alerts').show();
                $('.assignment-alerts').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Assignment deleted successfully!</div>');
                //refresh contents immediately after adding new content
                reloadAssignments(subjectName,cls)
            //else something went wrong 
        }else{
            $('.modal-msg').text('Assignment not deleted.');
            $('#assign-success').show();
        }

    })
    .fail(function() {
        console.log("error");
    })

}
   
});

//close modal when the user clicks no to delete
$(document).on('click','.deleteAssign_No',function(e){
    e.preventDefault();
    $('#assign-confirm').hide();
});

    //close modal when the user clicks OK to success
$(document).on('click','.assignSuccessOK',function(e){
    e.preventDefault();
    $('#assign-success').hide();
});

//load data when the user clicks on delete
$(document).on('click','.deleteAssigment',function(e){
    e.preventDefault();
    $('#del_assi_cls').val($(this).data('clsid')); //for refreshing results by classlevel subject
    $('#del_assi_subname').val($(this).data('subname'));//for refreshing results by subject
    //var assignID = $(this).data('assignid');
    var title=$(this).data('title');
    var title = title.toUpperCase();
    $('.modal-title-del').text("Delete!");
    $('.myP').text('Are you sure you want to delete '+title+'?');
    $('#deleteAssignID').val($(this).data('assignid'));
    $('#assign-confirm').show();
});

/**this triggers when the user click on the save(submit) button**
*/
$(document).on('click','.saveAssignment',function(e){
    e.preventDefault();
    var form = $('.frmAssign')[0];
    var form_data = new FormData(form);
    $.ajax({
        url: 'Academy/addAssignments',
        type: 'POST',
        cache:false,
        data: form_data, //send this to php controller
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
    })
    .done(function(msg) {
        var cls = $('#assi_cls').val();
        var subjectName = $('#assi_subname').val();
        if (msg == "Added"){
            //close modal
            $('#assignModal').hide('800', function() {
                //call method to clear modal
                clear_modal();
                //div to display message
                //$('.assignment-alerts').show();
                $('.assignment-alerts').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Assignment added successfully!</div>');
                //refresh contents immediately after adding new content
                reloadAssignments(subjectName,cls)
            });
        }else if (msg == "updated"){
              //close modal
            $('#assignModal').hide('800', function() {
                //clear text inputs
                clear_modal();
                //div to display message
                //$('.assignment-alerts').show();
                $('.assignment-alerts').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Assignment updated successfully!</div>');
                //refresh contents immediately after adding new content
                reloadAssignments(subjectName,cls)
            });
        }
        else if (msg == "NO"){
            $('.assign-alert').html('<div class="alert alert-danger text-center">Error! Assignment was not recorded, please try again.</div>');
        }
        else{
            $('.assign-alert').html('<div class="alert alert-danger">' + msg + '</div>');
        }
    })
    .fail(function() {
        console.log("error");
    })
}); 

//crab the value of cls from the button that launch modal
$(document).on('click','.addAssign',function(e){
    e.preventDefault();
    //clear text inputs
    $("input[type=text],textarea[type=textarea]").text('');
    $('input[type=file],textarea,input[type=text]').val('');
    //$(this).closest('form').find("input[type=file],input[type=text], input[type=submit],textarea").val("");
    $('#assignID').val('');
    $('#assi_fileID').val('');
    $('#assi_cls').val($(this).data('clsid'));
    $('#assi_subname').val($(this).data('subname'));
    $('#addAssign').val("Add");
    $('.modal-assignment-title').text("Add Assignment");
});

//when the teacher wants to view learners submissions
$(document).on('click','.viewSubmissions',function(e){
    e.preventDefault();
    var assignID = $(this).data('assignid');
    var cglID = $(this).data('assign_cglid');
    $('#download_assign').val(assignID);
    $('#submit_assign_title').text($(this).data('assign_title'));
    $('#submit_assignDue').text($(this).data('assign_duedate'));
    $.ajax({
        url: 'Academy/getLearnersSubmissions',
        type: 'POST',
        data: {'assignID':assignID,
                'cglID':cglID},
        dataType:'json',
    })
    .done(function(data) {
        $('.learnerSubmissions').html(data); 
    })
    .fail(function() {
        console.log("error");
    })
});

//teacher will be able to reset the assignment submitted by the learner
//
$(document).on('click','.resetSubmissions',function(e){
    e.preventDefault();
    var assignID = $(this).data('assignid') 
    var cglID = $(this).data('cglid') 
    var lID = $(this).data('lid') 
    $.ajax({
        url: 'Academy/resetSubmission',
        type: 'POST',
        data: {'lID':lID,
                'assignID':assignID},
    })
    .done(function(msg) {
         if (msg=="DONE") {
            $('#reset-msg').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Assignment reset successfully!</div>');
            refreshSubmissions(assignID,cglID);
         }else {
             $('#reset-msg').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Assignment not reset!</div>');
         }
    })
    .fail(function() {
        console.log("error");
    })
})

//this method will execute when the user clicks on Download Multiple
//method will get selected learners and assignment ID, then send them to the controller
//when files downloaded, status message will be returned
$(document).on('click','#download_assign',function(e){
    e.preventDefault();
    //get a learner numbers of all selected learner
    var lID = $('input[type=checkbox]:checked').map(function() {
        return $(this).data('lid')
    }).get();
    //check if learners were selected
    if (lID.length > 0) {
        //load progress bar here
        $.ajax({
            url: 'Academy/get_assignment_submissions',
            type: 'POST',
            data: {'lID':lID,
                    'assignID':$(this).val()
                    },
        })
        .done(function(msg) {
            $('#learnSubmissions').modal('hide');

            if (msg == 'Yes') {
                //close progress bar
                
                //load success msg
            }else
            {
                //error
            }
        })
        .fail(function() {
            console.log("error");
        })
    }else {
        //if no learners selected
        alert('Please select atleast one learner.');
    }
})
//refresh submissions after reset of assignment submission
function refreshSubmissions(assignID,cglID) {
    $.ajax({
        url: 'Academy/getLearnersSubmissions',
        type: 'POST',
        data: {'assignID':assignID,
                'cglID':cglID},
        dataType:'json',
    })
    .done(function(data) {
        $('.learnerSubmissions').html(data); 
    })
    .fail(function() {
        console.log("error");
    })
}


//teacher will search for the learner when viewing the submissions
$(document).on('click','#searchLearner',function(e){
    e.preventDefault();
    //alert($('#searchSubmissions').val());
    $.ajax({
        url: 'Academy/searchLearnerSubmit',
        type: 'POST',
        data: {'lsearch':$('#searchSubmissions').val()},
        dataType:'json',
    })
    .done(function(data) {
        //console.log(data);
        $('.learnerSubmissions').html(data); 
    })
    .fail(function() {
        console.log("error");
    })
});
//this will select all the checkboxes when the user clicks on the button
    $(document).on('change','#select_all_assign',function(e){
        e.preventDefault();
        $(".checkbox_learner").prop('checked', $(this).prop('checked'));
    });
    $(document).on('change','.checkbox_learner',function(){
        if (!$(this).prop("checked")){
            $("#select_all_assign").prop("checked",false);
        }
    });


/*$('input[type=checkbox]:checked').map(function(_, el) {
    return $(el).val();
}).get();

var lID = $('.checkbox_learner').map(function() {
        return $(this).val()
    }).get();
*/

//when the learner wants to submit the assignment 
//this will be called
$(document).on('click','.readAssignment',function(e){
    e.preventDefault();
    var assignID = $(this).data('ass_id');
    $('.nofile').text('');
    $.ajax({
        url: 'Academy/getAssignments',
        type: 'POST',
        data: {'assignID':assignID},
        dataType:'json',
    })
    .done(function(data) {
        var submitStatus = 0;
        var submitDeleted = 0; 
        //check if the object has values and not null/undefined
        if (data['learnerSubmit'][0]) {
            //get submission status of learner submission
            submitStatus = data['learnerSubmit'][0]['submitStatus'];
            //what if the submission has been reset
            submitDeleted = data['learnerSubmit'][0]['submitDeleted'];
            //assign the data of when the assignment was submitted
            $('#submitDate').text(data['learnerSubmit'][0]['submittedDate']);    
        }

        if (data['assignEdit'][0]) {
            //assignment due date from the datebase
            var dueDate = new Date(data['assignEdit'][0]['dueDate']);
            //todays date
            var today = new Date();
        }
        //check if the assignment is not overdue,
        //if yes hide submission link && 
        if (dueDate > today) {
            //check if the assignment has already been submitted
            if(submitStatus == 0) { 
                $('#no-submit').css('display','inline');
                $('.yes-submit').css('display','none');
            }else {
                $('#no-submit').css('display','none');
                $('.yes-submit').css('display','inline');
            }
        }else { //the assignment is over-due
            $('#no-submit').css('display','none');
            $('.yes-submit').css('display','none');
        }
        //check if we hav values to display for assignment
        if (data['assignEdit'][0]) {
            //assign the values from the database to the html   
            $('#assign_title').text(data['assignEdit'][0]['assignTitle']);
            $('#aid').val(data['assignEdit'][0]['assignID']);
            $('#assign_pubDate').text(data['assignEdit'][0]['publishDate']);
            $('#dueDate').text(data['assignEdit'][0]['dueDate']);
            $('#assign_desc').text(data['assignEdit'][0]['assignDesc']);
            $('#assign_down').attr('href', 'Academy/download/'+data['assignEdit'][0]['fileID']);
            $('#submit_assign_title').text(data['assignEdit'][0]['assignTitle']);
        }  
    })
});

//this will be triggered when the learner submit the assignment
$(document).on('click','.uploadSubmission',function(e){
    e.preventDefault();
    var file = ($('#fileUpload').val());
    var form = $('.frmSubmission')[0];
    var form_data = new FormData(form);
    if (file) {
        $.ajax({
            url: 'Academy/submitAssignment',
            type: 'POST',
            cache:false,
            data: form_data, //send this to php controller
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
        })
        .done(function(msg) {
                var assignid = $('#aid').val();
            if (msg == "Added"){
                //close modal
                $('#assignSubmit').hide('800', function() {
                    //call method to clear the form
                    clear_modal();
                    /////div to display message
                    //$('.assignment-alerts').show();
                    $('.assignment-alerts').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                        Assignment uploaded successfully!</div>');
                    //refresh contents immediately after adding new content
                    //refreshSubmissions(assignid);
                });
            }else
                $('.assign-alert').html('<div class="alert alert-danger">' + msg + '</div>');
                })
        }else {
            $('.nofile').html('<div class="alert alert-danger text-center">Please upload file before you submit.</div>');
        }
});

/**
 * ********************************START GUARDIAN ASSIGNMENT SECTION****************************
 */
//this will get assignments for teacher or learner, even for parent view
$(document).on('click','.gAssignLearnInnerTab',function(e){
    e.preventDefault();
    var fname = $(this).data('lfname');
    var lname = $(this).data('llname');
    var childID = $(this).data('luid');
    if (childID) {
        $.ajax({
            url: 'Academy/getGuardLearnSubject',
            type: 'POST',
            data: {'childID': childID,
                    'fname':fname,
                    'assign':1
                },
            dataType:'json',
        })
        .done(function(data) {
            $('.buildLSubjAssign').html(data);                              
        })
        .fail(function() {
            alert("Error: Please contact admin if this persits...");
        })
    }

});    


//view assignment for learner guardian
$(document).on('click','.viewAssignment',function(e){
    e.preventDefault();
    $('#assign_title').text($(this).data('assign_title'));
    $('#assign_pubDate').text($(this).data('assign_pd'));
    $('#dueDate').text($(this).data('assign_duedate'));
    $('#assign_desc').text($(this).data('assign_desc'));
    $('#submitDate').text($(this).data('submit_date'));
    $('#assign_down').attr('href',$(this).data('assign_path'));    
});

/**
 * ********************************END GUARDIAN ASSIGNMENT SECTION*******************************
 */

 $(document).on('click', '.switch_arrow', function(e){
     e.preventDefault();
     var c = $(this).hasClass("collapsed");
     if (!c) {
        $(this).find("h4 .arrows").attr('src',"assets/images/arrow_up2.png");
     }else {
        $(this).find("h4 .arrows").attr('src',"assets/images/arrow_down2.png");
     }
        
 });


 /*Pagination Script*/
    pageSize = 1;

    var pageCount =  $(".items").length / pageSize;
     for(var i = 0 ; i<pageCount;i++){
       var pagination =$("#pagn").append('<li><a href="#">'+(i+1)+'</a></li> ');
     }
        $("#pagin li").first().find("a").addClass("current")
        showPage = function(page) {
        $(".items").hide();
        $(".items").each(function(n) {
            if (n >= pageSize * (page - 1) && n < pageSize * page)
                $(this).show();
        });        
    }
    
    showPage(1);

    $("#pagin li a").click(function(e) {
          e.preventDefault();
        $("#pagin li a").removeClass("current");
        $(this).addClass("current");
        showPage(parseInt($(this).text())) 
    });
});//close document ready
