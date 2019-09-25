$(document).ready(function(){
//function to get todays date and time
function getTodayDate() {
    var m = new Date().getMinutes()       // Get the minutes (0-59)
    var h =  new Date().getHours()         // Get the hour (0-23)
    var s = new Date().getSeconds()       // Get the seconds (0-59)
   var tdate = new Date();
   var DD = tdate.getDate(); //yields day
   var MM = tdate.getMonth(); //yields month
   var yyyy = tdate.getFullYear(); //yields year
   
   var currentDate= yyyy + "-" +( MM+1) + "-" + DD;
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
* ///////////////////// Progress Script ////////////////
* =============================================================================================
*/
//datepicker calendar for progress
    $(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        var valueX = $('.progressDateRange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        //alert($('#progressDateRange span').text());
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
            if (progress == 0) {
                $('#editProgressModal').hide('slow');
                $('#progress-success').show('slow', function() {
                    $('.modal-title').text('Updated!');
                    $('.modal-msg').text('Marks updated successfully.');
                });
            }else {
                $('#editProgressModal').hide('slow');
                $('#progress-success').show('slow', function() {
                    $('.modal-title').text('Error!');
                    $('.modal-msg').text(progress+' marks were not updated. Please confirm them');
                }); 
            }
                     
    })
    .fail(function() {
        alert("Something went wrong, Please try again.");
    })
});
/**
 * [description]
 * @param  {[type]} e)
 * @return {[type]}      [description]
 */
$(document).on('click','.progress_successOK',function(e){
    e.preventDefault();
    //hide the modal
     $('#progress-success').hide('slow', function() {
        //modal-open class is added on body so it has to be removed
        $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove();           
    });
   
});

//display the progress when the user clicks the tab
$(document).on('click','.addProgress',function(e){
    e.preventDefault();
    var clsID = $(this).val('id');
    if (clsID != '') {
        $.ajax({
            url: 'Academy/getLearnersForProgress',
            type: 'POST',
            data: {'clsID': clsID},
            dataType:'json',
        })
        .done(function(data) {
           // console.log(data);                               
        })
        .fail(function() {
            alert("error");
        })

    }
});

//display the progress when the user select option to view
$(document).on('change','.vieWhat',function(e){
    e.preventDefault();
    var wannaDo = $(this).val();
    var clsID = $(this).data('progress_cls_id');
    var subjectName= $(this).data('progress_subject');
    $('.whichAssess').show();
    if (wannaDo!=0) {
        $.ajax({
            url: 'Academy/getLearnersProgress',
            type: 'POST',
            data: {'reason': wannaDo,
                    'clsID':clsID,
                    'subjectName':subjectName
                   },
            dataType: 'JSON',
        })
        .done(function(data) {
            var id= '';
            var subject = subjectName.replace(/\s+/g, "");
            var id= '.progress'+subject;
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

//display the progress when the user select option to view
$(document).on('change','.whichAssess',function(e){
    e.preventDefault();
    var assessType = $(this).val();
    var clsID = $(this).data('progress_cls_id');
    var subjectName= $(this).data('progress_subject');

    if (wannaDo!=0) {
        $.ajax({
            url: 'Academy/getLearnersProgress',
            type: 'POST',
            data: { 'wannaDo':wannaDo,
                    'assessType': assessType,
                    'clsID':clsID,
                    'subjectName':subjectName
                   },
            dataType: 'JSON',
        })
        .done(function(data) {
            var id= '';
            var subject = subjectName.replace(/\s+/g, "");
            var id= '.progress'+subject;
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

$(document).on('click','.progressInnerTab',function(e){
    e.preventDefault();
    var subjectName = $(this).text();
    if (subjectName!='') {
        $.ajax({
            url: 'Academy/getLearnersProgress',
            type: 'POST',
            data: {'subjectName':subjectName},
            dataType: 'JSON',
        })
        .done(function(data) {
            var id= '';
            var subject = subjectName.replace(/\s+/g, "");
            var id= '.progress'+subject;
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
/**=============================================================================================
* ///////////////////// Discussion Script ////////////////
* =============================================================================================
*/
/**this triggers when the user click on the save(submit) button**
*/
$(document).on('click','.saveComment',function(e){
    e.preventDefault();
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
            if (msg == "Added")
                $('.comment-alert').html('<div class="alert alert-success text-center">Topic added successfully!</div>');
            else if (msg == "updated")
                $('.comment-alert').html('<div class="alert alert-success text-center">Topic updated successfully!</div>');
            else if (msg == "NO")
                $('.comment-alert').html('<div class="alert alert-danger text-center">Error! Topic not recorded, please try again.</div>');
            else
                $('.comment-alert').html('<div class="alert alert-danger">' + msg + '</div>');
        })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete"); 
    })
});

//this will get the user to view topic and its comments
$(document).on('click','.viewTopicComments',function(e){
    e.preventDefault();
    var topicID = $(this).attr('id');
    $('.modal-title').text("Topic and Comments");
    $('#comment_topicID').val(topicID);
    if (topicID != '') {
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
    })
    .done(function(msg) {
            //alert(msg);
            if (msg == "Added")
                $('.topic-alert').html('<div class="alert alert-success text-center">Topic added successfully!</div>');
            else if (msg == "updated")
                $('.topic-alert').html('<div class="alert alert-success text-center">Topic updated successfully!</div>');
            else if (msg == "NO")
                $('.topic-alert').html('<div class="alert alert-danger text-center">Error! Topic not recorded, please try again.</div>');
            else
                $('.topic-alert').html('<div class="alert alert-danger">' + msg + '</div>');
        })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete"); 
    })
}); 
//crab the value of cls from the button that launch modal
$(document).on('click','.addTopic',function(e){
    var discID = $(this).attr('id');
    alert (discID);
    //var className=$(this).data('className');
    $('#topic_discID').val(discID);
    //change the button name for when the user does edit
    $('#addTopic').val("Add");
    $('.modal-title').text("Add Topic");
  });
   
//will be triggered when the user clicks on editTopic
$(document).on('click','.editTopic',function(e){
    e.preventDefault();
    var topicID = $(this).attr('id');
    if (topicID!=0) {
        $.ajax({
            url: 'Academy/getTopic',
            type: 'POST',
            data: {'topicID':topicID},
            dataType:'json',
        })
        .done(function(data) {
        //assign db data to the input controls on the modal
        $('#topic_creatorID').val(data[0]['topicAuthorID']);
        $('#topic_ID').val(data[0]['topicID']);
        $('#topic_discID').val(data[0]['dgID']);
        $('#topic_title').val(data[0]['topicTitle']);
        $('#topic_body').val(data[0]['tDescription']);
        $('#addTopic').val("Update");
        $('.modal-title').text("Update Topic");
    })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });  
    }
    
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
                $('.modal-msg').text('Record deleted successfully.');
                $('.modal-title').text("Success!");
                $('#disc-success').show();
                $('discInnerTab').refresh();
            //else something went wrong 
            }else{
                $('.modal-msg').text('Record not deleted.');
                $('#disc-success').show();
            }

    })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
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
    var delTopicID = $(this).attr('id');
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
            //then show modal to confirm delete
            $('#topic-confirm').show();
        }else {
            $('.modal-title').text("Delete!");
            $('.modal-msg').text('This title:' +title+' has comments. It cannot be deleted!!!');
            //then show modal to confirm delete
            $('#disc-success').show();
        }
        
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
    
});
/**
 *===============================================================================
 *"""""""""""""""""""""""""""""END of TOPIC SCRIPT"""""""""""""""""""""""""
 *===============================================================================
 */

//click on subject name tab, will trigger this
$(document).on('click','.discInnerTab',function(e){
    e.preventDefault();
    var subjectName = $(this).text();
    //var cls=$(this).data('attend_cls');
    if (subjectName != '') {
        $.ajax({
            url: 'Academy/getCategoryBySubject',
            type: 'POST',
            data: {'subjectName': subjectName},
            dataType:'json',
        })
        .done(function(data) {
            console.log(data);
            var id= '';
            var subject = subjectName.replace(/\s+/g, "");
            var id= '.disc-'+subject;
            $(id).html(data);                                
        })
        .fail(function() {
            alert("error");
        })

    }

});
//when the user clicks on VIEW (eye-ICON),
//send AJAX data request and populate data 
$(document).on('click','.viewCategory',function(e){
    e.preventDefault();
    var catID = $(this).attr('id');
    if (catID!=0) {
        $.ajax({
            url: 'Academy/getCategoryBySubject',
            type: 'POST',
            data: {'catID':catID},
            dataType:'json',
        })
        .done(function(data) {
            //assign db data to the input controls on the modal
            $('#disc_cls').val(data[0]['clsID']);
            $('#discID').val(data[0]['dgID']);
            $('#disc_title').val(data[0]['dgTitle']);
            $('#disc_body').val(data[0]['dgBody']);
            $("input[type=text],textarea[type=textarea]").attr('disabled','disabled');
            $('input[type=text],textarea').css('cursor','pointer');
            $('#addCat').hide();
            $('.modal-title').text("View Discussion Category");
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });  
    }
    
});   
//when the user click on EDIT,
//send AJAX data request and populate form fields
$(document).on('click','.editCategory',function(e){
    e.preventDefault();
    var catID = $(this).attr('id');
    if (catID!=0) {
        $.ajax({
            url: 'Academy/getCategoryBySubject',
            type: 'POST',
            data: {'catID':catID},
            dataType:'json',
        })
        .done(function(data) {
        //assign db data to the input controls on the modal
        $('#disc_cls').val(data[0]['clsID']);
        $('#discID').val(data[0]['dgID']);
        $('#disc_title').val(data[0]['dgTitle']);
        $('#disc_body').val(data[0]['dgBody']);
        $('#addCat').show();
        $('#addCat').val("Update");
        $('.modal-title').text("Update Discussion Category");
    })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });  
    }
    
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
                $('.modal-msg').text('Record deleted successfully.');
                $('.modal-title').text("Success!");
                $('#disc-success').show();
                $('discTable').load('academic.php');
            //else something went wrong 
        }else{
            $('.modal-msg').text('Record not deleted.');
            $('#disc-success').show();
        }

    })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        })
    }
   
});

//close modal when the user clicks NO to delete
$(document).on('click','.deleteDisc-No',function(e){
    e.preventDefault();
    $('#disc-confirm').hide();
});

//close modal when the user clicks OK to success
$(document).on('click','.disc-successOK',function(e){
    e.preventDefault();
    $('#disc-success').hide();
});
//load data when the user clicks on delete
$(document).on('click','.deleteCategory',function(e){
    e.preventDefault();
    var discID = $(this).attr('id');
    var title=$(this).data('title');
    var title = title.toUpperCase();
    $('.modal-title').text("Delete!");
    $('.myP').text('Are you sure you want to delete '+title+'?');
    //if the user clicks YES, take the ID for delete
    $('#deleteDiscID').val(discID);
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
        enctype: 'multipart/form-data',
        processData: false,
    })
    .done(function(msg) {
            //alert(msg);
            if (msg == "Added")
                $('.disc-alert').html('<div class="alert alert-success text-center">Discussion Category added successfully!</div>');
            else if (msg == "updated")
                $('.disc-alert').html('<div class="alert alert-success text-center">Discussion Category updated successfully!</div>');
            else if (msg == "NO")
                $('.disc-alert').html('<div class="alert alert-danger text-center">Error! Discussion Category not recorded, please try again.</div>');
            else
                $('.disc-alert').html('<div class="alert alert-danger">' + msg + '</div>');
        })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    })
}); 

//crab the value of cls from the button that launch modal
$(document).on('click','.addDisc',function(e){
  var cls = $(this).val();
      //var className=$(this).data('className');
      $('#disc_cls').val(cls);
      //change the button name for when the user does edit
      $('#addCat').val("Add");
      $('.modal-title').text("Add Discussion Category");
  });
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
        //alert($('.attandDateRange span').text());
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

//when the user clicks the top innerTab, then fetch the results and display them
$(document).on('click','.attendInnerTab',function(e){
    e.preventDefault();
    var subjectName = $(this).text();
    var clsID = $(this).data('cls');
    var level = $(this).data('level');
    var cg = $(this).data('cg');
    if (subjectName != '') {
        $.ajax({
            url: 'Academy/getLearnersForAttend',
            type: 'POST',
            data: {'subjectName': subjectName,
                    'clsID':clsID
                    },
            dataType:'json',
        })
        .done(function(data) {
            var id= '';
            var subject = subjectName.replace(/\s+/g, "");
            var lvl = level.replace(/\s+/g, "");
            var id= '.attend'+subject+'-'+cg+'-'+lvl;
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
    var clsID = $(this).data('attend_cls_id');
    var subjectName= $(this).data('attend_subject');
    var level = $(this).data('attend_level');
    var cg = $(this).data('attend_cg');
    $(".loading").ajaxStart(function () {
       $(this).show();
    });
    if (wannaDo!=0) {
        $.ajax({
            url: 'Academy/getLearnersForAttend',
            type: 'POST',
            data: {'reason': wannaDo,
                    'clsID':clsID,
                    'subjectName':subjectName
                   },
            dataType: 'JSON',
        })
        .done(function(data) {
            $(".loading").ajaxStop(function () {
               $(this).hide();
            });
            var id= '';
            var subject = subjectName.replace(/\s+/g, "");
            var lvl = level.replace(/\s+/g, "");
            var id= '.attend'+subject+'-'+cg+'-'+lvl;
            console.log(id);
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
            alert("Something went wrong, Please try again.");
        })
    }

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
                //var tbody= '';
                var subject = subjectName.replace(/\s+/g, "");
                var tbody= '.study'+subject;
                $(tbody).html(data);                                
            })
        .fail(function() {
            alert("Something went wrong, Please try again.");
        })

    }

});

/**this triggers when the user click on the edit button**
    then it displays a modal with form fields populated with
    database data for the specific record
    */
$(document).on('click','.editMaterial',function(e){
    e.preventDefault();
    var studyID = $(this).attr('id');
    if (studyID!=0) {
        $.ajax({
            url: 'Academy/getMaterial',
            type: 'POST',
            data: {'studyID':studyID},
            dataType:'json',
        })
        .done(function(data) {
        //assign db data to the input controls on the modal
        $('#sm_cls').val(data[0]['clsID']);
        $('#sm_studyID').val(data[0]['studyID']);
        $('#sm_fileID').val(data[0]['fileID']);
        $('#sm_title').val(data[0]['studyTitle']);
        $('#sm_description').val(data[0]['materialDesc']);
        $('#add').val("Update");
        $('.modal-title').text("Update Study Material");
    })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });  
    }

});

//delete study material
$(document).on('click','.deleteYes',function(e){
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
            //if yes, it means deleted
            if (msg=="YES") {
                $('.modal-msg').text('Record deleted successfully.');
                $('.modal-title').text("Success!");
                $('#study-success').show();
                $('studyTable').load('academic.php');
            //else something went wrong 
        }else{
            $('.modal-msg').text('Record not deleted.');
            $('#study-success').show();
        }

    })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        })
    }
   
});

//close modal when the user clicks no to delete
$('.deleteNo').on('click',function(e) {
    e.preventDefault();
    $('#study-confirm').hide();
});

//close modal when the user clicks OK to success
$('.successOK').on('click',function(e) {
    e.preventDefault();
    $('#study-success').hide();
});
//load data when the user clicks on delete
$(document).on('click','.deleteMaterial',function(e){
    e.preventDefault();
    var studyID = $(this).attr('id');
    var title=$(this).data('title');
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
            //alert(msg);
            if (msg == "Added")
                $('.study-alert').html('<div class="alert alert-success text-center">Study material added successfully!</div>');
            else if (msg == "updated")
                $('.study-alert').html('<div class="alert alert-success text-center">Study material updated successfully!</div>');
            else if (msg == "NO")
                $('.study-alert').html('<div class="alert alert-danger text-center">Error! Material was not recorded, please try again.</div>');
            else
                $('.study-alert').html('<div class="alert alert-danger">' + msg + '</div>');
        })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    })
}); 

//crab the value of cls from the button that launch modal
$(document).on('click','.addStudy',function(e){
  var cls = $(this).val();
      //var className=$(this).data('className');
      $('#sm_cls').val(cls);
      $('#add').val("Add");
      $('.modal-title').text("Add Study Material");
  });

 
/**=============================================================================================
* /////////////////////Assignment Script////////////////
* =============================================================================================
*/

//this will get assignments for teacher or learner, even for parent view
$(document).on('click','.assignInnerTab',function(e){
    e.preventDefault();
    var subjectName = $(this).data('subname');
    var clsID = $(this).data('clsid');
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
            alert("Something went wrong, Please try again.");
        })

    }

});

/**this triggers when the user click on the edit button**
then it displays a modal with form fields populated with
database data for the specific record
*/
$(document).on('click','.editAssignment',function(e){
    e.preventDefault();
    var assignID = $(this).attr('id');
    if (assignID!=0) {
        $.ajax({
            url: 'Academy/getAssignments',
            type: 'POST',
            data: {'assignID':assignID},
            dataType:'json',
        })
        .done(function(data) {
            //assign db data to the input controls on the modal
            $('#assi_cls').val(data['assignEdit'][0]['clsID']);
            $('#assignID').val(data['assignEdit'][0]['assignID']);
            $('#assi_fileID').val(data['assignEdit'][0]['fileID']);
            $('#assi_dueDate').val(data['assignEdit'][0]['dueDate']);
            $('#assi_title').val(data['assignEdit'][0]['assignTitle']);
            $('#assi_description').val(data['assignEdit'][0]['assignDesc']);
            $('#addAssign').val("Update");
            $('.modal-title').text("Update Assignment");
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });  
    }
    
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
        //if yes, it means deleted
        if (msg=="YES") {
            $('.modal-msg').text('Assignment deleted successfully.');
            $('.modal-title').text("Success!");
            $('#assign-success').show();
        //else something went wrong 
    }else{
        $('.modal-msg').text('Assignment not deleted.');
        $('#assign-success').show();
    }

})
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
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
    var assignID = $(this).attr('id');
    var title=$(this).data('title');
    var title = title.toUpperCase();
    $('.modal-title').text("Delete!");
    $('.myP').text('Are you sure you want to delete '+title+'?');
    $('#deleteAssignID').val(assignID);
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
            //alert(msg);
            if (msg == "Added")
                $('.assign-alert').html('<div class="alert alert-success text-center">Assignment added successfully!</div>');
            else if (msg == "updated")
                $('.assign-alert').html('<div class="alert alert-success text-center">Assignment updated successfully!</div>');
            else if (msg == "NO")
                $('.assign-alert').html('<div class="alert alert-danger text-center">Error! Assignment was not recorded, please try again.</div>');
            else
                $('.assign-alert').html('<div class="alert alert-danger">' + msg + '</div>');
        })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    })
}); 

//crab the value of cls from the button that launch modal
$(document).on('click','.addAssign',function(e){
    var cls = $(this).data('ass_id');
  //var className=$(this).data('className');
  $('#assi_cls').val(cls);
  $('#addAssign').val("Add");
  $('.modal-title').text("Add Assignment");
});



//Read assignment
$(document).on('click','.readAssignment',function(e){
    e.preventDefault();
    var assignID = $(this).data('ass_id');
    $.ajax({
        url: 'Academy/getAssignments',
        type: 'POST',
        data: {'assignID':assignID},
        dataType:'json',
        })
    .done(function(data) {
        //check if the object has values and not null/undefined
        if (data['learnerSubmit'][0]) {
            //get submission status of learner submission
            var submitStatus = data['learnerSubmit'][0]['submitStatus'];
            //what if the submission has been reset
            var submitDeleted = data['learnerSubmit'][0]['submitDeleted'];
            //assign the data of when the assignment was submitted
            $('#submitDate').text(data['learnerSubmit'][0]['submittedDate']);
            
        }
        //due date from the datebase
        var dueDate = new Date(data['assignEdit'][0]['dueDate']);
        //todays date
        var today = new Date(getTodayDate());
        //check if the assignment is not overdue or submitted already,
        //if yes hide submission link
        if ((dueDate > today)) {
            $('#ss').val(submitStatus);
            $('#no-submit').css('display','inline');
            $('.yes-submit').css('display','none');
        }else {
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
            $('#assign_down').attr('href', data['assignEdit'][0]['filePath']);
        }
        
    })

});
//this will be triggered when the learner submit the assignment
$(document).on('click','.uploadSubmission',function(e){
    e.preventDefault();
    alert ($('#fileUpload').val());
    var form = $('.frmSubmission')[0];
    var form_data = new FormData(form);
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
            //alert(msg);
            if (msg == "Added")
                $('.assign-alert').html('<div class="alert alert-success text-center">Assignment added successfully!</div>');
            else if (msg == "updated")
                $('.assign-alert').html('<div class="alert alert-success text-center">Assignment updated successfully!</div>');
            else if (msg == "NO")
                $('.assign-alert').html('<div class="alert alert-danger text-center">Error! Assignment was not recorded, please try again.</div>');
            else
                $('.assign-alert').html('<div class="alert alert-danger">' + msg + '</div>');
        })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    })
});
/*
**************************************************************************************************************************
*Download a file
* /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/
$(document).on('click','.download',function(e){
    e.preventDefault();
    var file = $(this).data('download');
    alert (file);
    if (file != 0) {
        $.ajax({
            url: 'Academy/download',
            type: 'POST',
            dataType: 'json',
            data: {'file': file},
        })
        .done(function(data) {
            console.log("success");
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    }
});

});//close document ready
