$(document).ready(function(){

$("#msgMainView").load(window.location.href+'/manageMsg', function() {

});

$("#anouMainView").load(window.location.href+'/manageAnnounce', function() {
 
});

$("#discusMainView").load(window.location.href+'/manageComment', function() {
  
});

$("#calendarSection").load(window.location.href+'/manageCalendar', function() {

});

$("#faqMainView").load(window.location.href+'/manageFAQs', function() {
  
});
$("#trashMainView").load(window.location.href+'/manageTrashedItems',function(){

});




 /*$( "#trashArea" ).click(function( event ) {
  var pageCoords = "( " + event.pageX + ", " + event.pageY + " )";
  var clientCoords = "( " + event.clientX + ", " + event.clientY + " )";
  $( "span:first" ).text( "( event.pageX, event.pageY ) : " + pageCoords );
  $( "span:last" ).text( "( event.clientX, event.clientY ) : " + clientCoords );

  console.log(pageCoords);
  console.log(clientCoords);
  });*/


/***********************************************ANNOUNCEMENTS START**************************************************/
    //creating announcement
    $(document).on('click','#announce',function(e){
     e.preventDefault();
    var formAnn = {
        annTitled:$('#annTitled').val(),
        annBdy: $('#annBdy').val()
    };
    $.ajax({
        url: "Intercom/createAnnounce",
        type: 'POST',
        data: formAnn,
        success: function(msg) {
            if (msg == "ADDED"){
                $('#myModal2').hide('fast', function() {
                    $('#annTitled').val('');
                    $('#annBdy').val('');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    });
                $("#anouMainView").load(window.location.href+'/manageAnnounce', function() {
                     $('.ale-AnnPage').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your announcement has been made successfully!</div></div>');
                });
            }
            else if (msg == "NO"){
                //$('#ale-msgAnnErr').html('<div class="alert alert-danger text-center">Error in sending your announcement! Please try again later.</div>');
                 $('#ale-msgAnnErr').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Error in sending your announcement! Please try again later.</div></div>');
            }
            else
                $('#ale-msgAnn').html('<div class="alert alert-danger">' + msg + '</div>');
        }
    });
    
});//end creating announcement

/**ask delete announcement**/
    $(document).on('click','#deleteAnnID',function(e){
        e.preventDefault();
        var trashAnn = $(this).data('announceid');
        //alert(trashAnn);
        $('#myModalAnnoun').hide();
        if(trashAnn!=0){
            $.ajax({
        url: "Intercom/askDeleteAnnounce",
        type: 'POST',
        data: {'trashAnn':trashAnn},
        })
            .done(function(msg){
                if(msg=='DELETED'){
                    $("#anouMainView").load(window.location.href+'/manageAnnounce', function() {
                        $('.ale-AnnPageDel').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Announcement deleted successfully!</div>');
                    });
                }else{
                    $('#ale-msgAnnDel').html('<div class="alert alert-danger">' + msg + '</div>');
                }
            })
            .fail(function(){
                console.log('error');
            })
            .always(function(){
            console.log('complete');
        })
     }  
});

$(document).on('click','.delAnnou',function(e){
    e.preventDefault();
    $('#myModalAnnoun').hide();
});

$(document).on('click','.successOK',function(e){
    e.preventDefault();
    $('#ann-success').hide();
});

 $(document).on('click','#delAnnouncemet',function(e){
    e.preventDefault();
    var anouID = $(this).attr('data-announceID');
    $('.myP').text('Are you sure?');
    $('#deleteAnnID').val(anouID);
    $('#myModalAnnoun').show();
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});
 /**end delete announcement**/

//edit announcement
$(document).on('click','#editingAnn',function(e){
     e.preventDefault();
    var annID = $(this).data('annid');
    var annTitl = $(this).data('annt');
    var annBdy = $(this).data('annb');
    var annDt = $(this).data('annd');
    
    $('#annIDe').val(annID);
    $('#annTitledEdit').val(annTitl);
    $('#annBdyEdit').text(annBdy);    
    $('#annDt').val(annDt);
});
    $(document).on('click','.clickApplyAnn',function(e){
        e.preventDefault();
         var form_ann = {
        annIDe: $('#annIDe').val(),
        annTitled: $('#annTitledEdit').val(),
        annBdy: $('#annBdyEdit').val(),
        annDt: $('#annDt').val(),
    };
    //console.log(form_ann);
        $.ajax({
            url:"Intercom/createAnnounce",
            type:"POST",
            data:form_ann,
            processData:true,
        })
            .done(function(msg)
            {
                if (msg == "UPDATED"){
                     $("#anouMainView").load(window.location.href+'/manageAnnounce', function() {
                     $('body').removeClass('modal-open');
                     $('.modal-backdrop').remove();
                        $('.alert-msgEditAnnSucc').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your comment has been editted successfully!</div></div>');
                    });
                                        
                }else if (msg == "NO")
                $('.alert-msgEditAnnSucc').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Error in editing your comment! Please try again later!</div></div>');
                else
                $('#alert-msgEditAnn').html('<div class="alert alert-danger">' + msg + '</div>');
            })
            .fail(function(){
            console.log('error editable');
        })
        .always(function(){
            console.log('complete');
        }); 
    });
//end edit announcement

//search announcements
    $(document).on('keyup','#anouMainViewSearch',function(e){
         e.preventDefault();
          $('#anouMainViewSearch').focus();
        if($('#anouMainViewSearch').val().length>=3){
            var search_ann = $('#anouMainViewSearch').val().toLowerCase();
            /*$('#topDisply b').filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(search_ann) > -1)
            });*/
            $.ajax({
                method:"POST",
                url:"Intercom/manageAnnounce",
                data:{srchMgs:search_ann},
                success:function(msg){
                    $('#anouMainView').html(msg);
                    $('#anouMainViewSearch').val(search_ann);
                    $('#anouMainViewSearch').focus();                    
      } 
   });
 }
});//end search announcements    
/**********************************************END ANNOUNCEMENTS***************************************************/
/*************************************************MESSAGES START************************************************/
    //compose modal
    $(document).on('click','#compose',function(e){
         e.preventDefault();
    var form_data = {
        to: $('#compose_to').val(),
        receiverID: $('#receiverID').val(),
        subjectMsgs: $('#compose_subjectMsgs').val(),
        messagea: $('#compose_messagea').val(),
        senderID:$('#senderID').val(),
        //input fields ids and names that are gonna be passed in the data variable 
    };
    $.ajax({
        url: "Intercom/composeMsg",
        type: 'POST',
        data: form_data,
        success: function(msg) {
            if (msg == "YES"){
            $('.myReply').hide('fast', function(){
                $('#compose_to').val('');
                $('#compose_subjectMsgs').val('');
                $('#compose_messagea').val('');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            });
            $("#msgMainView").load(window.location.href+'/manageMsg', function() {
               $('.alert-msgPage').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your mail has been sent successfully!</div>');
            });
            }else if (msg == "NO"){
                $('.alert-msgw').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Error in sending your message!</div>');
            }        
            else
               $('.alert-msgw').html('<div class="alert alert-danger">' + msg + '</div>');
        }
    });
});//end compose message
$(document).on('click','.reset',function(e){
    e.preventDefault();
    $('#msg_comp')[0].reset();
});
    //checking if the emails exists in the phonebook
    $(document).on('blur','#compose_to',function(e){
         e.preventDefault();
        var to = $('#compose_to').val();
        $.ajax({
            url: "Intercom/onBlurGetUser",
            type: 'POST',
            data: {'to':to},
            dataType: 'json',
            success: function(data) {
                    if (data[0].length > 0) {
                        var rid = '';
                        for (var i = 0; i < data.length; i++) {
                             rid += data[i][0]['userID']+';';
                        }
                        var uid = rid.slice(0,-1);
                        $('#receiverID').val(uid);
                    }else {
                        $('.alert-userEmail').show();
                        $('.alert-userEmail').html('<div class="alert alert-danger">' + msg + '</div>');
                    }
               }
        });
        //to.initialize();
    });//end checking email
 
    //check reply email exists
     $(document).on('blur','#reply_to',function(e){
         e.preventDefault();
        var rto = $('#reply_to').val();
        $.ajax({
            url: "Intercom/onBlurGetUser",
            type: 'POST',
            data: {'to':rto},
            dataType: 'json',
            success: function(data) {
              if (data[0].length > 0) {
                    var rrid = '';
                    for (var i = 0; i < data.length; i++) {
                         rrid += data[i][0]['userID']+';';
                    }
                    var uid = rrid.slice(0,-1);
                    $('#receiverIDReply').val(uid);
                }else {
                    $('.alert-msgrepl').show();
                    $('.alert-msgrepl').html('<div class="alert alert-danger">' + msg + '</div>');
                 }
            }
        });
    });//end check reply email exists

    //search/input box for emails 
    $(document).on('blur','#result',function(e){
         e.preventDefault();
        var result = $('#result').val();
        $.ajax({
            url: "Intercom/getUser",
            type: 'POST',
            data: {'result':result},
            dataType: 'json',
            success: function(data) {
              
                if(data == 'NO'){
                    $('.alert-userrEmail').show();
                    $('.alert-userrEmail').html('<div class="alert alert-danger text-center">Error: email does not exist.</div>');
                }else{
                     $('#result').val(data[0]['lName']);
                     $('#receiverID').val(data[0]['userID']);
                     $('.alert-userrEmail').hide();
                     $('.alert-userrEmail').html('<div class="alert alert-danger">' + msg + '</div>');
                }
            }
        });
    });//end input box
    //confirms the email
    $(document).on('change','.checkMail',function(e){
         //e.preventDefault();
        var email = $(this).val();
        $('#compose_to').val(email);
        //$('#to').tagsinput('')
    }); //end confirm email
    //closes the phonebook and sends data of celected checkboxes to the getUser function
    $(document).on('click','.closeBook',function(e){
         e.preventDefault();
        var selectedMail = [];
        $('input[type=checkbox]:checked').map(function(i){
            selectedMail[i] = $(this).val();
            //$(this).tagsinput(selectedMail[i]);
        });

        if(selectedMail.length >= 0){
           //alert('Please select atleast one mail.');
                $.ajax({
                    url: 'Intercom/getUser',
                    type: 'POST',
                    data: {'selectedMail':selectedMail},
                    dataType: 'json',
                    success: function(data){
                        //console.log(compose);
                        var e = '';
                        var rid = '';
                        for (var i = 0; i < data.length; i++) {
                             e += data[i][0]['email']+';';
                             rid += data[i][0]['userID']+';';
                        }
                        var str = e.slice(0,-1);
                        var uid = rid.slice(0,-1);
                        
                        $('#compose_to').val(str);
                        $('#receiverID').val(uid);
                        
                        //add selected messages
                        $('#contactList').hide('100', function() {
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        $('.myReply').modal('show');
                        });
                     }
                });
            }
        });  //end closes the phonebook and sends data of celected checkboxes to the getUser function

    /*****************reply message holds the value of the fields***********************/
$(document).on('click','.replyModal',function(e){
     e.preventDefault();
    var replyID = $(this).data('mail');
    var title = $(this).data('sub');
    var receiverID = $(this).data('sender');

    $('#reply_to').val(replyID);
    $('#reply_subjectMsgs').val(title);
    $('#receiverIDReply').val(receiverID);
});

$(document).on('click','#reply_msg',function(e){
    e.preventDefault();
    var form_data = {
    receiverID:$('#receiverIDReply').val(),
    to:$('#reply_to').val(),
    subjectMsgs:$('#reply_subjectMsgs').val(),
    messagea:$('#reply_messagea').val(),
    senderID:$('#senderID_reply').val(),
};

$.ajax({
    url:"Intercom/replyMsg",
    type:'POST',
    data:form_data,
    dataType:'json',
    success:function(msg){
        if(msg == 'DELETED'){
            $('.composeModal').hide('fast',function(){
                $('#reply_to').val('');
                $('#reply_subjectMsgs').val('');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            });
            $("#msgMainView").load(window.location.href+'/manageMsg', function() {
                $('#alert-msgrepl').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your mail has been sent successfully!</div>');
            });
            }else if(msg == 'NO'){
                 $('.alert-msgrepl').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Error in sending your mail! Please try again later!</div>');
            }
            else
                 $('.alert-msgreply').html('<div class="alert alert-danger">' + msg + '</div>');
        }
    });
});
/**ask delete message **/
    $(document).on('click','#deleteMsgID-y',function(e){
        e.preventDefault();
        var trashhMsg = $(this).val();
        //alert(trashhMsg);
        $('#myModalMsg').hide();
        if(trashhMsg!=0){
            $.ajax({
        url: "Intercom/askDelete",
        type: 'POST',
        data: {'trashhMsg':trashhMsg},
        })
            .done(function(msg){
                if(msg=='DELETED'){
                    $("#msgMainView").load(window.location.href+'/manageMsg', function() {
                         $('.modal-msg').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Message deleted successfully!</div>');
                    });
                }else{
                     $('.msg-successPage').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Message not deleted successfully!</div>');
                    
                    }
                })
            .fail(function(){
                console.log('error');
            })
            .always(function(){
                console.log('complete');
        })
     }    
});
$(document).on('click','.deleMsg',function(e){
    e.preventDefault();
    $('#myModalMsg').hide();
});
$(document).on('click','.close_modal_del',function(e){
    e.preventDefault();
    $('#msg-success').hide();
});

 $(document).on('click','#delMsgs',function(e){
    e.preventDefault();
    var msgID = $(this).attr('data-messageID');
    //alert(msgID);
    $('.myP').text('Are you sure?');
    $('#deleteMsgID-y').val(msgID);
    $('#myModalMsg').show();
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});
 /**end delete message**/

 //hide compose message modal and shows phonebook
    $(document).on('click','.showing',function(e){
        e.preventDefault();
         $('.myReply').modal('hide');
         $('#contactList').modal('show');
    });
    //end hide compose message modal and shows phonebook
    $(document).on('click','.goBack',function(e){
        e.preventDefault();
        $('.myReply').modal('show');
    });

    /*****Search Contacts******/
$(document).on('blur','#mailMainViewSearch',function(e){
     e.preventDefault();
     $('#mailMainViewSearch').focus();
      if($('#mailMainViewSearch').val().length>=3){  
        var search = $('#mailMainViewSearch').val();
            $.ajax({
                method: "POST",
                url: "Intercom/getSearchMail",
                data: {srchMgs:search},
                dataType: 'json',
                success: function(data){
                     $('.contactBook').html(data);
                     $('#mailMainViewSearch').val(search);
                     $('#mailMainViewSearch').focus();   
          }
      });
    }
  });//end search contacts

    //select all checkboxes
    $(document).on('change','#select_all',function(e){
        e.preventDefault();
        alert('all select');
        if($(this).is(":checked",true)){
            $(".del_message").prop('checked',true);
        }
        else{
            $(".del_message").prop('checked',false);
        }
        //select all checked checkboxes count
        $("#select_count").html($("input.value:checked").length+" Select");
    });
    //end selected checkboxes

    //delete selected records
    $(document).on('click','#delSel',function(e){
        e.preventDefault();
        //delete selected records
        var messaging = [];
        $('.del_message:checked').each(function(){
            messaging.push($(this).val());
        });
        //console.log(messaging);
        if(messaging.length <= 0){
            alert('Please select atleast one Message.');
        }
        else{

            WRN_MESSAGE_DELETE = 'Are you sure you want to Delete '+(messaging.length>1?"these":"this")+" ";
            var checked = confirm(WRN_MESSAGE_DELETE);
            if(checked == true){
                var selected_values = messaging.join(",");
                //console.log(selected_values);
                $.ajax({
                    url: 'Intercom/askDelete',
                    type: 'POST',
                    data: {'trashhMsg':selected_values},
                    dataType: 'json',
                    success: function(response){
                        //remove selected messages
                        $("#msgMainView").load(window.location.href+'/manageMsg', function() {
                        });
                        var trashhMsg = response.split(",");
                        for(var i=0; i < trashhMsg.length; i++){
                            $("#"+trashhMsg[i]).remove();
                       }
                    }
               });
           }
        }
      });//end delete selected messages


/*******************************SENT MESSAGES MULTIPLE****************************************/
 //select all checkboxes
    $(document).on('change','#select_all_sent',function(e){
        e.preventDefault();
        alert('all select');
        if($(this).is(":checked",true)){
            $(".del_sent_message").prop('checked',true);
        }
        else{
            $(".del_sent_message").prop('checked',false);
        }
        //select all checked checkboxes count
        $("#select_count").html($("input.value:checked").length+" Select");
    });
    //end selected checkboxes

    //delete selected records
    $(document).on('click','#delSelSent',function(e){
        e.preventDefault();
        //delete selected records
        var messaging_sent = [];
        $('.del_sent_message:checked').each(function(){
            messaging_sent.push($(this).val());
        });
        //console.log(messaging);
        if(messaging_sent.length <= 0){
            alert('Please select atleast one Sent Message.');
        }
        else{

            WRN_MESSAGE_DELETE = 'Are you sure you want to Delete '+(messaging_sent.length>1?"these":"this")+" ";
            var checked_sent = confirm(WRN_MESSAGE_DELETE);
            if(checked_sent == true){
                var selected_values_sent = messaging_sent.join(",");
                //console.log(selected_values);
                $.ajax({
                    url: 'Intercom/askDeleteSent',
                    type: 'POST',
                    data: {'trashSent':selected_values_sent},
                    dataType: 'json',
                    success: function(response){
                        //remove selected messages
                        $('#msgMainView').load("intercom/manageMsg");
                        var trashSentt = response.split(",");
                        for(var i=0; i < trashSentt.length; i++){
                            $("#"+trashSentt[i]).remove();
                       }
                    }
               });
            }
        }
      });//end delete selected messages

/*****************************END SENT MESSAGES MUTLIPLE****************************************/

    //reading inbox message modal
 $(document).on('click','.readModal',function(e){
     e.preventDefault();   
    var readID = $(this).data('email');
    var readTitle = $(this).data('subject');
    var readName = $(this).data('name');
    var readDate = $(this).data('date');
    var readMessage = $(this).data('body');
    var readMe = $(this).data('msgid');
    $('#from').text(readID);
    $('#msgTitle').text(readTitle);
    $('#sender').text(readName);
    $('#msgDate').text(readDate);
    $('#msgBody').text(readMessage);
    $.ajax({
        url: 'Intercom/readMsg',
        type: 'POST',
        dataType: 'json',
        data: {'readMe': readMe},
    })
    .done(function(data) {
        //refresh messages when being read
        $("#msgMainView").load(window.location.href+'/manageMsg', function() { 
            //call this method which will update the count of the notifications when the user has read a message
            refreshNotifications();
        });
    })
    .fail(function() {
        console.log("error");
    })
    
});//end reading inbox message modal

 //preview sent messages
 $(document).on('click','.previewSent',function(e){
    e.preventDefault();
    var prevID = $(this).data('sentemail');
    var prevTitle= $(this).data('senttitle');
    var prevName = $(this).data('sentname');
    var prevDate = $(this).data('sentdate');
    var prevBody = $(this).data('sentbody');
    var prevSent = $(this).data('sentid');

    $('#to').text(prevID);
    $('#sentTitle').text(prevTitle);
    $('#sentDate').text(prevDate);
    $('#sentBody').text(prevBody);
    $('#receiver').text(prevName);
 });
 
//end preview sent messages

/**///////////////////////////////////////*********************///////////////////////
/***********this will refresh the notifications when messages are being read*********/
/**//////////////////////////////////////*********************///////////////////////
function refreshNotifications() {
    $.ajax({
        url: 'Intercom/refreshNotifications',
        type: 'POST',
        dataType: 'json',
    })
    .done(function(data) {
        $(".refresh_notify").html(data);
    })
    .fail(function() {
        console.log("error");
    })
 } 
/**************************************************************************************/

/*search messages*/
$(document).on('keyup','#msgMainViewSearch',function(e){
     e.preventDefault();
     $('#msgMainViewSearch').focus();
      if($('#msgMainViewSearch').val().length>=3){  
        var search = $('#msgMainViewSearch').val();
        //var filterItems = $('')
       
            $.ajax({
                method: "POST",
                url: "Intercom/manageMsg",
                data: {srchMgs:search},
                //dataType: 'json',
                success: function(data){
                     $('#msgMainView').html(data);
                     $('#msgMainViewSearch').val(search);
                     $('#msgMainViewSearch').focus();
          }
      });
     }
    });//end search messages
    function allResults(search){
     $('#msgMainViewSearch').focus();
     $('#msgMainViewSearch').val(search);
        $.ajax({
            method: "POST",
            url: "Intercom/manageMsg",
            success: function(data){
                 $('#msgMainView').html(data);
                 $('#msgMainViewSearch').focus();
                 $('#msgMainViewSearch').val(search);
            }
        });
    }
//read messages

//end read messages

/*****SEARCH SENT MESSAGES******/
$(document).on('keyup','#sentMainViewSearch',function(e){
     e.preventDefault();
     $('#sentMainViewSearch').focus();
      if($('#sentMainViewSearch').val().length>=3){  
        var search_sent = $('#sentMainViewSearch').val();
            $.ajax({
                method: "POST",
                url: "Intercom/outBoxMsgPagination",
                data: {'srchMgs':search_sent},
                success: function(snt){
                     $('#msg3').html(snt);
                     $('#sentMainViewSearch').val(search_sent);
                     $('#sentMainViewSearch').focus();
                 }
           });
        }
    });//end search sent messages

/**Delete sent messages**/
$(document).on('click','#deleteSentID',function(e){
        e.preventDefault();
        var trashSent = $(this).val();
        $('#myModalSent').hide();
        if(trashSent!=0){
            $.ajax({
        url: "Intercom/askDeleteSent",
        type: 'POST',
        data: {'trashSent':trashSent},
        })
            .done(function(snt){
                if(snt=='DELETED'){
                    $("#msgMainView").load(window.location.href+'/manageMsg', function() {
                         $('.modal-sent').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Message deleted!</div>');
                    });
                   
                }else{
                     $('.modal-sent').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Message not deleted!</div>');
                }
            })
            .fail(function(){
                console.log('error');
            })
            .always(function(){
             console.log('complete');
      })
    }   
});
$(document).on('click','.deleSent',function(e){
    e.preventDefault();
    $('#myModalSent').hide();
});

$(document).on('click','.close_sent_msg',function(e){
    e.preventDefault();
    $('#sent-success').hide();
});

 $(document).on('click','.delSentMsgs',function(e){
    e.preventDefault();
    var dmID = $(this).attr('data-sentMsgID');
    $('.myP').text('Are you sure?');
    $('#deleteSentID').val(dmID);
    $('#myModalSent').show();
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});
/*end delete sent message end*/
/********************************************END MESSAGES**************************************************/

/********************************************DISCUSSION START************************************************/
/*star ratings start*/
$(document).on('click','.rate',function(e){
    e.preventDefault();
    var star = $(this).data('rating');
    var comment_rate = $(this).data('comment');
    var userrate = $(this).data('userrate');
    $.ajax({
        url:'Intercom/ratePost',
        type: 'POST',
        dataType: 'json',
        data:{'comment_rate':comment_rate,
              'star':star,
              'userrate':userrate},
    })
    .done(function(data){
        if(data == 'RATED'){
            refreshComments(topicID);
        }
      })
      .fail(function(){
        console.log('error');
      })
      .always(function(){
        console.log('complete');
      })
});
/*star rating end*/

/**ask delete comment **/
    $(document).on('click','#deleteCommID',function(e){
        e.preventDefault();
        var trashCom = $(this).val();
        $('#myModalComment').hide();
        if(trashCom!=0){
            $.ajax({
        url: "Intercom/askDeleteComm",
        type: 'POST',
        data: {'trashCom':trashCom},
        })
            .done(function(msg){
                if(msg=='ADDED'){
                     refreshComments($('#commentgtcid').val());
                     $("#discusMainView").load(window.location.href+'/manageComment', function() {
                         $('.modal-commdel').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Comment deleted!</div>');
                    });
                   
                    }else{
                    $('.ale-msgcomdelno').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Comment Not Deleted Successfully!</div>');
                  }
                })
            .fail(function(){
                console.log('error');
            })
            .always(function(){
                console.log('complete');
        });
       }
    });

$(document).on('click','.delComm',function(e){
//$('.delComm').on('click',function(e){
    e.preventDefault();
    $('#myModalComment').hide();
});

$(document).on('click','.successOK',function(e){
//$('.successOK').on('click',function(e){
    e.preventDefault();
    $('#comm-success').hide();
});

 $(document).on('click','.delCommentt',function(e){
    e.preventDefault();
    var cmID = $(this).attr('data-commID');
    var gtid = $(this).data('commenttopic');
    $('.myP').text('Are you sure?');
    $('#deleteCommID').val(cmID);
    $('#commentgtcid').val(gtid);
    $('#myModalComment').show();
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});
 /**end delete comment**/
function refreshComments(topicName) {
    $.ajax({
            url: 'Intercom/getCommentInfo',
            type:'POST',
            data: {'topicName':topicName},
            dataType: 'json',
        })
        .done(function(data){
            $('#ajaxMsg').html(data);
       
        })
        .fail(function(){
            alert("error topic");
        }) 
}
/**ask delete discussion **/
    $(document).on('click','#deleteDiscussID',function(e){
        e.preventDefault();
        var trashDisc= $(this).val();
        //alert(trashCom);
        $('#myModalDiscussion').hide();
        if(trashDisc!=0){
    
            $.ajax({
        url: "Intercom/askDeleteDiscussion",
        type: 'POST',
        data: {'trashDisc':trashDisc},
        })
            .done(function(msg){
                if(msg=='DELETED'){
                     $("#discusMainView").load(window.location.href+'/manageComment', function() {
                        $('.modal-discuss').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Discussion Topic Deleted!</div>');
                     });
                      
                }else{
                      $('.modal-discussErr').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Discussion Topic Not Deleted!</div>');
                }
            })
            .fail(function(){
                console.log('error');
            })
            .always(function(){
             console.log('complete');
        })
    }
});
//delete discussion modal
$(document).on('click','.delDiscuss',function(e){
    e.preventDefault();
    $('#myModalDiscussion').hide();
});

$(document).on('click','.close_discuss',function(e){
    e.preventDefault();
    $('#discuss-success').hide();
});

 $(document).on('click','.delDiscussion',function(e){
    e.preventDefault();
    var discID = $(this).attr('data-discussID');
    //alert(discID);
    $('.myP').text('Are you sure?');
    $('#deleteDiscussID').val(discID);
    $('#myModalDiscussion').show();
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});
 /**end delete discussion**/

    //create topic script
   $(document).on('click','#topic',function(e){
         e.preventDefault();
        var form_data = {
        //gtID: $('#gtID').val(),
        topicTitle: $('#topicTitle').val(),
        topicDescription: $('#topicDescription').val(),
    };
    //console.log(form_data);
    $.ajax({
        url: "intercom/createTopic",
        type: 'POST',
        data: form_data,
        success: function(msg) {
            //console.log(msg);
            if (msg == "ADDED"){
                    $('#myModal3').hide('fast', function() {
                    $("#discusMainView").load(window.location.href+'/manageComment', function() {
                         $('.alert-topicPagex').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your topic has been made successfully!</div></div>');
                    });
                        $('#topicTitle').val('');
                        $('#topicDescription').val('');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                });
                               
            }else if (msg == "NO"){
                 $('.ale-msgTopicxErr').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Discussion not added successfully!!</div>');
            }
            else
               $('.ale-msgTopic').html('<div class="alert alert-danger">' + msg + '</div>');
            }
    });
    
});//end create topic

function fetchTopics(topic){
    $.ajax({
            url: "Intercom/manageComment",
            type: 'POST',
            data: {'topic':topic},
            dataType: 'json',
            success: function(data) {
                $('.topicResults').html(data);
         }
   });  
}

/****************Discussin Topics and comments**********************/
    $(document).on('click','.topicBtn',function(e){
    e.preventDefault();
    $("#tab2").show();

    $("#tab1").removeClass('active');
    $("#disc1").removeClass('active in');
   
    $("#tab2").addClass('active');
    $("#disc2").addClass('active in');
        var topicName = $(this).attr('id');
    $('#gtide').val(topicName);

    if(topicName != ''){
        $.ajax({
            url: 'Intercom/getCommentInfo',
            type:'POST',
            data: {'topicName':topicName},
            dataType: 'json',
        })
        .done(function(data){
            $('#ajaxMsg').html(data);
    
        })
        .fail(function(){
            alert("error topic");
        })
    }    
});//end Discussin Topics and comments

//select dropdown of topics to select
    $(document).on('change','#filters',function(e){
    e.preventDefault();
        var topicName = $(this).val();
        //console.log(topicName);
        $('#gtide').val(topicName);
        if(topicName != ''){
            
            $.ajax({
                url:'Intercom/getCommentInfo',
                type:'POST',
                data:{'topicName':topicName},
                dataType:'json',
                success:function(msg){ 
                //console.log(msg);      
                    $('#ajaxMsg').html(msg);
                },
                error: function(){
                    alert('Error occur filter...');
               }
           })
        }
    });//select dropdown of topics to select
 
//submit comment to the selected discussion
$(document).on('click','.subComm',function(e){
     e.preventDefault();
    //var cm = CKEDITOR.instances.cm.getData();
    var form_comm = {
        gtide: $('#gtide').val(),
        uid: $('#uid').val(),
        cm: $('#cm').val(),
    };
    //console.log(form_comm);
    $.ajax({
        url: "Intercom/writeComment",
        type: 'POST',
        //cache:false,
        data: form_comm,
        //dataType: 'json',
        success: function(msg) {
            $('#cm').val('');
            if (msg == "ADDED"){

                fetchComm($('#gtide').val());
                $('.alert-subComm').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your comment was added successfully!</div></div>');
            
        }else if(msg == "NO"){
             $('.alert-subErrComm').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your comment was not successfully!</div></div>');
        }else
               $('.alert-subErrComm').html('<div class="alert alert-danger">' + msg + '</div>');
      }
   });
});

//submit comment to the selected discussion
function fetchComm(commentTpc){
    //alert(commentTpc);
    $.ajax({
            url: "Intercom/getCommentInfo",
            type: 'POST',
            data: {'topicName':commentTpc},
            dataType: 'json',
            success: function(data) {
                //console.log(data);
                $('#ajaxMsg').html(data);
         }
   });  
}

/*search discussion*/
    $(document).on('keyup','#discusMainViewSearch',function(e){
         e.preventDefault();
         $('#discusMainViewSearch').focus();
        if($('#discusMainViewSearch').val().length>=3){
            var search_disc = $('#discusMainViewSearch').val();
            console.log(search_disc);
            $.ajax({
                method:"POST",
                url:"Intercom/manageComment",
                data:{srchMgs:search_disc},
                success:function(data){
                    $('#discusMainView').html(data);
                    $('#discusMainViewSearch').val(search_disc);
                    $('#discusMainViewSearch').focus();
                }
           });
        }
    });//end search discussion

//editing for comments
$(document).on('click','#editingComm',function(e){
     e.preventDefault();
    var gtcID = $(this).data('subs');
    var gtitle = $(this).data('subt');
    var gtdate = $(this).data('subd');
    var commBody = $(this).data('sub');
    var genT = $(this).data('gentpc');
    $('#egtcid').val(gtcID);
    $('.modal-title').text(gtitle);
    $('.gtdate').text(gtdate);
    $('#ecm').text(commBody);
    $('#egtid').val(genT);
});
    $(document).on('click','.clickEditComm',function(e){
        e.preventDefault();
         var form_data = {
        gtid: $('#egtid').val(),
        uid: $('#euid').val(),
        cm: $('#ecm').val(),
        gtcid: $('#egtcid').val(),
    };
        $.ajax({
            url:"Intercom/writeComment",
            type:"POST",
            data:form_data,
            processData:true,
        })
            .done(function(msg)
            {
               if (msg == "UPDATED")
                $('#ecm').val('');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                   $("#discusMainView").load(window.location.href+'/manageComment', function() {
                     $('.alert-msgEditx').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your comment was editted successfully!</div></div>');
                });
            })
            .fail(function(){
            console.log('error editable');
        })
        .always(function(){
            console.log('complete');
        });
    });
    //end edit comment
    
    //editing for discussion
$(document).on('click','#editingDiscuss',function(e){
     e.preventDefault();
    var egtTitle = $(this).data('gtname');
    var egBody = $(this).data('discr');
    var editID = $(this).data('gtident');
    var creator = $(this).data('creat');
    //var editDat = $(this).data('time');
    $('#editDiscHiddnFiel').val(editID);
    $('#editT').val(egtTitle);
    $('#editDis').text(egBody);
    $('#topicCreatorID').text(creator);
});

    //edit discussion
    $(document).on('click','#clickEditDiscuss',function(e){
        e.preventDefault();
         var form_data = {
        editDiscHiddnID: $('#editDiscHiddnFiel').val(),
        edtitle: $('#editT').val(),
        descrDis: $('#editDis').val(),
    };
        topicCreatorID = $('#topicCreatorID').val();
        discuUserID = $('#discuUserID').val();
        $.ajax({
            url:"Intercom/writeDiscuss",
            type:"POST",
            data:form_data,
            //processData:true,
        })
            .done(function(msg)
            {
                if (msg == "UPDATED"){
                $("#discusMainView").load(window.location.href+'/manageComment', function() {
                    $('.alert-topicedtsucc').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your topic was editted successfully!</div></div>');
                });
                $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }else if (msg == "NO")
                $('.alert-topicerr').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your topic was not editted successfully!</div></div>');
                else
                $('.alert-msgEditx').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your topic was not editted successfully!</div></div>');
            })
            .fail(function(){
            console.log('error editable');
        })
        .always(function(){
            console.log('complete');
        });
    });
    //end edit discussion
/***************************************************END DISCUSSION**********************************************/

    $(document).on('click','.likeCommenta',function(e){
        e.preventDefault();
        var commentID = $(this).data('likecommentid');
        var like = $(this).data('likes');
        var userlike = $(this).data('userlike');
        var topicID = $(this).data('commenttopic');
       
        if(userlike != '' && commentID != ''){
            $.ajax({
                url:'Intercom/manageLikes',
                type:'POST',
                data:{'commentID':commentID,
                       'userlike':userlike,
                       'like':like
                    },
            })
            .done(function(msg){
                console.log(msg);
                if(msg == 'LIKED'){
                   refreshComments(topicID); 
                }
            })
            .fail(function(){
                console.log('error like');
            })
        }
    });

    //add comment smileys
    $(document).on('click','#emoji',function(e){
        e.preventDefault();
        $('.smiles').toggle();
        
    });
    //end add comment smileys

    //compose message modal smileys
    $(document).on('click','#msgEmoji',function(e){
        e.preventDefault();
        $('.msgsmiles').toggle();
    });//end compose message modal smileys

    //event group start buttons
    $(document).on('click','.trigger',function(e){
    e.preventDefault();
          var category = $(this).data('id');       
        if(category != ''){
            $.ajax({
                url:'Intercom/getEventColorCategory',
                type:'POST',
                data:{'category':category},
                dataType:'json',
                success:function(msg){
                //console.log(msg);      
                    $('.event_colors_display').html(msg);
                },
                error: function(){
                    alert('Error occur filter...');
                }
            })
        }
    });//select event group button


/*search frequently asked questions*/
    $(document).on('keyup','#faqMainViewSearch',function(e){
         e.preventDefault();
          $('#faqMainViewSearch').focus();
        if($('#faqMainViewSearch').val().length>=3){
            var searchFAQ = $('#faqMainViewSearch').val();
            var searchFAQCategory = $('#faqMainViewSearch').val();
            
            $.ajax({
                method:"POST",
                url:"Intercom/manageFAQs",
                data:{srchMgs:searchFAQ,
                      srchMsg:searchFAQCategory},
                success:function(data){
                    console.log(data);
                    $('#faqMainView').html(data);
                    $('#faqMainViewSearch').val(searchFAQ);
                    $('#faqMainViewSearch').focus();            
      }
   });
 }
});/*end search frequently asked questions*/

    //back to top button frequently asked questions section
    if($('.myScroll').length){
        var scrollTrigger = 100;
       function backToTop(){
            var scrollTop = $(window).scrollTop();
            if(scrollTop > scrollTrigger){
                $('.myScroll').addClass('show');
            }else{
                $('.myScroll').removeClass('show');
            }
        };
        backToTop();
        $(window).on('scroll',function(){
            backToTop();
        });
    }
    $(document).on('click','.myScroll',function(e){
        //alert('top Clicked');
        e.preventDefault();
        $('html,.topic--body').animate({
            scrollTop: 0 },
            700);
        });
    //end back to top button frequently asked questions section
    //toggle group categories plus/minus accordion
        $(document).on('click','.faq_links',function(e){
            e.preventDefault();
            var collapsed = $(this).find('i').hasClass('fa-minus');
            $('a.faq_links').find('i').removeClass('fa-plus');
            $('a.faq_links').find('i').addClass('fa-minus');
            if(collapsed){
                $(this).find('i').toggleClass('fa-minus fa-plus');
            }else {
          }
        });//end toggle categories accordion

    //submit quetsions
    $(document).on('click','#subm_faq',function(e){
        e.preventDefault();
        var form_faq = {
        hiddenFaqID: $('#faqSelID').val(),
        category_sel: $('#category_sel').val(),
        faq_title: $('#faq_title').val(),
        your_question: $('#your_question').val(),
    };
    //console.log(form_faq);
    $.ajax({
        url: 'Intercom/writeFAQ',
        type: 'POST',
        data: form_faq,
        success: function(msg) {
            if (msg == "ADDED"){
                $('#askUsModal').hide('fast', function() {
                    $('#category_sel').val('');
                    $('#faq_title').val('');
                    $('#your_question').val('');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                });
                $("#faqMainView").load(window.location.href+'/manageFAQs', function() {
                    $('.alert-faqPage').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your question added successfully!</div></div>');
                });
             }
        else if(msg == "NO"){
             $('.alert-FaqErrorx').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your question was not successfully!</div></div>');
        }else
               $('#alert-FaqError').html('<div class="alert alert-danger">' + msg + '</div>');
        }
    });
 });

//edit faq question
$(document).on('click','.faqPencil',function(e){
     e.preventDefault();

    var edtfaqcat = $(this).data('faqidcat');
    var edtfaq = $(this).data('faid');
    var edtTitle = $(this).data('faqtitle');
    var edtBody = $(this).data('faqdescr');
    
    $('#edtfaqSelID').val(edtfaq);
    $('#edtcategory_sel').val(edtfaqcat);
    $('#edtfaq_title').val(edtTitle);
    $('#edtyour_question').text(edtBody);
    
});
    $(document).on('click','#apply_subm_faq',function(e){
        e.preventDefault();
         var form_editFaq = {
        hiddnedtfaqSelIDt: $('#edtfaqSelID').val(),
        category_sel: $('#edtcategory_sel').val(),
        faq_title: $('#edtfaq_title').val(),
        your_question: $('#edtyour_question').val(),
    };
    //console.log(form_editFaq);
        $.ajax({
            url:"Intercom/editFAQuestions",
            type:"POST",
            data:form_editFaq,
            //processData:true,
        })
            .done(function(msg)
            {
                if (msg == "UPDATED"){
                    $("#faqMainView").load(window.location.href+'/manageFAQs', function() {
                        $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    $('.alert-msgEditFAQx').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your question has been editted successfully!</div></div>');
                    });
                    
                }else if (msg == "NO")
                $('.alert-msgEditFAQtrr').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Error in editing your question! Please try again later!</div></div>');
                else
                     $('#alert-msgEditFAQtx').html('<div class="alert alert-danger">' + msg + '</div>');
            })
            .fail(function(){
            console.log('error editable');
        })
        .always(function(){
            console.log('complete');
        });
    });
//end edit question

/*ask delete FAQ*/
    $(document).on('click','#deleteFAQID',function(e){
        e.preventDefault();
        var trashFAQ = $(this).val();
        $('#mFAQDelModal').hide();
        if(trashFAQ!=0){
            $.ajax({
        url: "Intercom/askDeleteFAQs",
        type: 'POST',
        data: {'trashFAQ':trashFAQ},
        })
            .done(function(msg){
                if(msg=='DELETED'){
                    $("#faqMainView").load(window.location.href+'/manageFAQs', function() {
                         $('.modal-faqsucc').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Question deleted successfully!</div>');
                    });
                   
                }else{
                     $('.modal-msgFaqx').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Question not deleted!</div>');
                }
            })
            .fail(function(){
                console.log('error');
            })
            .always(function(){
            console.log('complete');
        })
     }
});

$(document).on('click','.delFquestion',function(e){
    e.preventDefault();
    $('#FAQDelModal').hide();
});

$(document).on('click','.close_faq',function(e){
    e.preventDefault();
    $('#faq-success').hide();
});

$(document).on('click','.delThisQuest',function(e){
    e.preventDefault();
    //alert('delete modal');
    var thisQid = $(this).data('delfaqid');
    
    $('.myMQ').text('Are You Sure?');
    $('#deleteFAQID').val(thisQid);
    $('#FAQDelModal').show();
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});
 /**end delete FAQ**/
/*****************************************************************************END FAQ SECTION******************************************************************************/
/**notification counter**/
        $('#noti_Counter')
            .css({ opacity: 0 })
            .text('7')
            .css({ top: '-10px' })
            .animate({ top: '-2px', opacity: 1 }, 500);

        $('#noti_Button').click(function () {

            //toggle notification window
            $('#notifications').fadeToggle('fast', 'linear', function () {
                if ($('#notifications').is(':hidden')) {
                    $('#noti_Button').css('background-color', '#2E467C');
                }
                else $('#noti_Button').css('background-color', '#FFF');//change the background color of the button
            });
            $('#noti_Counter').fadeOut('slow');//hide counter

            return false;
        });

        //hide notifications when clicked anywhere on the page
        $(document).click(function () {
            $('#notifications').hide();

            //check if the notification is hidden
            if ($('#noti_Counter').is(':hidden')) {
                //change background color of the button
                $('#noti_Button').css('background-color', '#2E467C');
            }
        });

        $('#notifications').click(function () {
            return false; //do nothing when the container is clicked
        });
/**end notification counter**/

/*search announcements trash*/
    $(document).on('keyup','#trashMainViewSearch',function(e){
         e.preventDefault();
          $('#trashMainViewSearch').focus();
        if($('#trashMainViewSearch').val().length>=3){
            var search = $('#trashMainViewSearch').val();
            console.log(search);
            $.ajax({
                method:"POST",
                url:"Intercom/manageTrashedItems",
                data:{srchMgs:search},
                success:function(msg){
                    $('#trashMainView').html(msg);
                    $('#trashMainViewSearch').val(search);
                    $('#trashMainViewSearch').focus();                    
      }
   });
  }
});/*end search announcements*/

    /**ask restore message **/
    $(document).on('click','#restoreMsgID',function(e){
        e.preventDefault();
        var restoreInbx = $(this).val();
        $('#myModalRestore').hide();
        if(restoreInbx!=0){
            $.ajax({
        url: "Intercom/recycleMsg",
        type: 'POST',
        data: {'restoreInbx':restoreInbx},
        })
            .done(function(msg){
                if(msg=='RESTORED'){
                     $("#trashMainView").load(window.location.href+'/manageTrashedItems',function(){
                         $('.restore-trashPage').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Message Restored Successfully!</div>');
                     });
                   
                    }else{
                    $('.modal-restoreErr').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Message not restored!</div>');
                    }
                })
            .fail(function(){
                console.log('error');
            })
            .always(function(){
            console.log('complete');
        })
     }  
});

$(document).on('click','.restoreMsg',function(e){
//$('.delAnnou').on('click',function(e){
    e.preventDefault();
    $('#myModalRestore').hide();
});

$(document).on('click','.close_modal_restore',function(e){
//$('.successOK').on('click',function(e){
    e.preventDefault();
    $('#restore-success').hide();
});

 $(document).on('click','#restoreInboxID',function(e){
    e.preventDefault();
    var inbxID = $(this).attr('data-inboxid');
    $('.myR').text('Are you sure?');
    $('#restoreMsgID').val(inbxID);
    $('#myModalRestore').show();
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});
 /**end restore message**/

/*ask restore sent messages*/
$(document).on('click','#restoreSentMsgID',function(e){
    e.preventDefault();
    var restoreOutbx = $(this).val();
    $('#myModalRstoreSent').hide();
    if(restoreOutbx != 0){
        $.ajax({
            url:'Intercom/recycleSentMsg',
            type:'POST',
            data:{'restoreOutbx':restoreOutbx},
        })
        .done(function(snt){
            if(snt == 'RESTORED'){
                  $("#trashMainView").load(window.location.href+'/manageTrashedItems',function(){
                    $('.sent-trashPage').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Message Restored Successfully!</div>');
                    });
                   }else{
                     $('.modal-sentErr').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Message not restored!</div>');
                    $('.modal-sent').text('Message not restored');
                }
        })
        .fail(function(){
            console.log('error');
        })
        .always(function(){
            console.log('complete');
        })
    }
});

$(document).on('click','.restoreOut',function(e){
//$('.delAnnou').on('click',function(e){
    e.preventDefault();
    $('#myModalRstoreSent').hide();
});

$(document).on('click','.close_sent_restore',function(e){
    e.preventDefault();
    $('#sent-success').hide();
});

 $(document).on('click','#restoreSentID',function(e){
    e.preventDefault();
    var outboxID = $(this).attr('data-outid');
    $('.mySt').text('Are you sure?');
    $('#restoreSentMsgID').val(outboxID);
    $('#myModalRstoreSent').show();
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});
/*end restore sent messages*/

  /**ask restore announcements **/
    $(document).on('click','#restoreAnnTrshID',function(e){
        e.preventDefault();
        var restoreAnnTrshh = $(this).val();
        //alert(restoreAnnTrshh);
        $('#myModalRestoreAnn').hide();
        if(restoreAnnTrshh!=0){
            $.ajax({
        url: "Intercom/recycleAnnouncement",
        type: 'POST',
        data: {'restoreAnnTrshh':restoreAnnTrshh},
        })
            .done(function(msg){
                if(msg=='RESTORED'){
                     $("#trashMainView").load(window.location.href+'/manageTrashedItems',function(){
                    $('.restore-trashAnnPage').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Announcement Restored Successfully!</div>');
                });
                   }else{
                     $('.modal-annRestoreErr').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Announcement not deleted!</div>');
                }
            })
            .fail(function(){
                console.log('error');
            })
            .always(function(){
            console.log('complete');
        })
     }  
});

$(document).on('click','.restoreAnn',function(e){
//$('.delAnnou').on('click',function(e){
    e.preventDefault();
    $('#myModalRestoreAnn').hide();
});

$(document).on('click','.close_modal_restoreann',function(e){
//$('.successOK').on('click',function(e){
    e.preventDefault();
    $('#restoreann-success').hide();
});

 $(document).on('click','#restoreAnnID',function(e){
    e.preventDefault();
    var anntrashID = $(this).attr('data-announcementid');
    $('.myRA').text('Are you sure?');
    $('#restoreAnnTrshID').val(anntrashID);
    $('#myModalRestoreAnn').show();
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});
 /**end restore announcements**/

 /**restore deleted event **/
    $(document).on('click','#restoreEventID',function(e){
        e.preventDefault();
        var cycleEvent = $(this).val();
        $('#myModalMsg').hide();
        if(cycleEvent!=0){
            $.ajax({
        url: "Intercom/recycleEvent",
        type: 'POST',
        data: {'cycleEvent':cycleEvent},
        })
            .done(function(msg){
                if(msg=='RESTORED'){
                     $("#trashMainView").load(window.location.href+'/manageTrashedItems',function(){
                     $('.modal-event').html('<div class="alert alert-success text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Event deleted!</div>');
                 });

                }else{
                     $('.modal-eventErr').html('<div class="alert alert-danger text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Event not deleted!</div>');
                }
            })
            .fail(function(){
                console.log('error');
            })
            .always(function(){
                console.log('complete');
        })
    }    
});
$(document).on('click','.restoreEvent',function(e){
//$('.deleMsg').on('click',function(e){
    e.preventDefault();
    $('#myModalRestoreEvent').hide();
});
$(document).on('click','.close_modal_restoreevent',function(e){
    e.preventDefault();
    $('#restoreevent-success').hide();
});

 $(document).on('click','#restoreEventtrshID',function(e){
    e.preventDefault();
    var trseventID = $(this).attr('data-eventcycleid');
    //alert(msgID);
    $('.myPE').text('Are you sure?');
    $('#restoreEventID').val(trseventID);
    $('#myModalRestoreEvent').show();
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});
 /**end restore deleted event**/

/****************************Multiple restore Events****************************************/
 //select all events
    $(document).on('change','#select_all_appoint',function(e){
        e.preventDefault();
        if($(this).is(":checked",true)){
            $(".restr_appointment").prop('checked',true);
        }
        else{
            $(".restr_appointment").prop('checked',false);
        }
        //select all checked checkboxes count
        $("#select_count").html($("input.value:checked").length+" Select");
    });
    //end selected checkboxes

    //restore selected appointments
    $(document).on('click','#restoreSelAppointment',function(e){
        e.preventDefault();
        //restore selected records
        var appoint = [];
        $('.restr_appointment:checked').each(function(){
            appoint.push($(this).val());
        });
        //console.log(appoint);
        if(appoint.length <= 0){
            alert('Please select atleast one record.');
        }
        else{

            WRN_APPOINTMENT_RESTORE = 'Are you sure you want to Restore '+(appoint.length>1?"these":"this")+" ";
            var checked = confirm(WRN_APPOINTMENT_RESTORE);
            if(checked == true){
                var selected_values_app = appoint.join(",");
                //console.log(selected_values);
                $.ajax({
                    url: 'Intercom/recycleEvent',
                    type: 'POST',
                    data: {'cycleEvent':selected_values_app},
                    dataType: 'json',
                    success: function(response){
                        //remove selected messages
                        $("#trashMainView").load(window.location.href+'/manageTrashedItems',function(){
                        });
                        var cycleEventt = response.split(",");
                        for(var i=0; i < cycleEventt.length; i++){
                            $("#"+cycleEventt[i]).remove();
                       }
                    }
               });
            }
         }
       });//end restore selected events
/****************************End Multiple restore Events****************************************/
/****************************Multiple restore Announcements****************************************/
//select all announcements
    $(document).on('change','#select_all_announcement',function(e){
        e.preventDefault();
        if($(this).is(":checked",true)){
            $(".restr_announcement").prop('checked',true);
        }
        else{
            $(".restr_announcement").prop('checked',false);
        }
        //select all checked checkboxes count
        $("#select_count").html($("input.value:checked").length+" Select");
    });
    //end selected checkboxes

    //restore selected announcement
    $(document).on('click','#restoreSelAnnouncement',function(e){
        e.preventDefault();
        //restore selected records
        var announce = [];
        $('.restr_announcement:checked').each(function(){
            announce.push($(this).val());
        });
        console.log(announce);
        if(announce.length <= 0){
            alert('Please select atleast one record.');
        }
        else{

            WRN_ANNOUNCEMENT_RESTORE = 'Are you sure you want to Restore '+(announce.length>1?"these":"this")+" ";
            var checked = confirm(WRN_ANNOUNCEMENT_RESTORE);
            if(checked == true){
                var selected_values_annc = announce.join(",");
                //console.log(selected_values);
                $.ajax({
                    url: 'Intercom/recycleAnnouncement',
                    type: 'POST',
                    data: {'restoreAnnTrshh':selected_values_annc},
                    dataType: 'json',
                    success: function(response){
                        //remove selected messages
                        $("#trashMainView").load(window.location.href+'/manageTrashedItems',function(){
                        });
                        var restore_ann = response.split(",");
                        for(var i=0; i < restore_ann.length; i++){
                            $("#"+restore_ann[i]).remove();
                       }
                   }
              });
           }
         }
       });//end restore selected announcements
/**********************************END RESTORE MULTIPLE ANNOUNCEMENT******************************************/
/****************************MULTIPLE RESTORE MESSAGES****************************************/
//select all messages
    $(document).on('change','#select_all_inbox',function(e){
        e.preventDefault();
        if($(this).is(":checked",true)){
            $(".restr_inboxes").prop('checked',true);
        }
        else{
            $(".restr_inboxes").prop('checked',false);
        }
        //select all checked checkboxes count
        $("#select_count").html($("input.value:checked").length+" Select");
    });
    //end selected checkboxes

    //restore selected messages
    $(document).on('click','#restoreSelInboxes',function(e){
        e.preventDefault();
        //restore selected records
        var inbox = [];
        $('.restr_inboxes:checked').each(function(){
            inbox.push($(this).val());
        });
        console.log(inbox);
        if(inbox.length <= 0){
            alert('Please select atleast one record.');
        }
        else{

            WRN_INBOXES_RESTORE = 'Are you sure you want to Restore '+(inbox.length>1?"these":"this")+" ";
            var checked = confirm(WRN_INBOXES_RESTORE);
            if(checked == true){
                var selected_values_inbx = inbox.join(",");
                //console.log(selected_values);
                $.ajax({
                    url: 'Intercom/recycleMsg',
                    type: 'POST',
                    data: {'restoreInbx':selected_values_inbx},
                    dataType: 'json',
                    success: function(response){
                        //remove selected messages
                        $("#trashMainView").load(window.location.href+'/manageTrashedItems',function(){
                        });
                        var restoreInbxTrsh = response.split(",");
                        for(var i=0; i < restoreInbxTrsh.length; i++){
                            $("#"+restoreInbxTrsh[i]).remove();
                       }
                    }
              });
           }
         }
       });//end restore selected messages
/************************************************************END RESTORE MULTIPLE MESSAGES*****************************************************/

/*********************************************RESTORE MULTIPLE SENT MESSAGES****************************************************************/
//select all sent messages
    $(document).on('change','#select_all_sent',function(e){
        e.preventDefault();
        if($(this).is(":checked",true)){
            $(".restr_outbox").prop('checked',true);
        }
        else{
            $(".restr_outbox").prop('checked',false);
        }
        //select all checked checkboxes count
        $("#select_count").html($("input.value:checked").length+" Select");
    }); //end selected checkboxes
   
   //restore selected sent messages
    $(document).on('click','#restoreSelSent',function(e){
        e.preventDefault();
        //restore selected records
        var sent = [];
        $('.restr_outbox:checked').each(function(){
            sent.push($(this).val());
        });
        console.log(sent);
        if(sent.length <= 0){
            alert('Please select atleast one record.');
        }
        else{

            WRN_INBOXES_RESTORE = 'Are you sure you want to Restore '+(sent.length>1?"these":"this")+" ";
            var checked = confirm(WRN_INBOXES_RESTORE);
            if(checked == true){
                var selected_sent = sent.join(",");
                $.ajax({
                    url: 'Intercom/recycleSentMsg',
                    type: 'POST',
                    data: {'restoreOutbx':selected_sent},
                    dataType: 'json',
                    success: function(response){
                        //remove selected messages
                        $("#trashMainView").load(window.location.href+'/manageTrashedItems',function(){
                        });
                        var restoreSentTrsh = response.split(",");
                        for(var i=0; i < restoreSentTrsh.length; i++){
                            $("#"+restoreSentTrsh[i]).remove();
                       }
                    }
              });
           }
         }
       });//end restore selected sent messages
/*********************************************END RESTORE MULTIPLE SENT MESSAGES****************************************************************/


 //back to top button calendar section
    if($('#back-to-cale').length){
        var scrollCalendar = 100;
       function backToTopCal(){
            var scrollTopCal = $(window).scrollTop();
            if(scrollTopCal > scrollCalendar){
                $('#back-to-cale').addClass('show');
            }else{
                $('#back-to-cale').removeClass('show');
            }
        };
        backToTopCal();
        $(window).on('scroll',function(){
            backToTopCal();
        });
    }
    $(document).on('click','#back-to-cale',function(e){
        alert('topEvents');
        e.preventDefault();
        $('html,.cal-body').animate({
            scrollTopCal: 0 },
            700);
        });
    //end back to top button calendar section
    
    //back to top button comments section
    if($('#back-to-comm').length){
        var scrollComments = 100;
       function backToTopComm(){
            var scrollTopCom = $(window).scrollTop();
            if(scrollTopCom > scrollComments){
                $('#back-to-comm').addClass('show');
            }else{
                $('#back-to-comm').removeClass('show');
            }
        };
        backToTopComm();
        $(window).on('scroll',function(){
            backToTopComm();
        });
    }
    $(document).on('click','#back-to-comm',function(e){
        //alert('topComments');
        e.preventDefault();
        $('html,.com-body').animate({
            scrollTopCom: 0 },
            700);
        });
    //end back to top button comments section
    
});
