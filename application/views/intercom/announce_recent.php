 <?php
 defined('BASEPATH') OR exit('No direct script access allowed');
   ?>

 <button type="button" class="btn btn-primary avtt" data-toggle="modal" data-target="#myModal2"><i class="glyphicon glyphicon-plus"></i>&nbsp;New Announcement</button><br><br>
<?php 
//} ?>
 <?php
 $this->load->view('intercom/announceModal');
 ?>
 <?php
 $this->load->view('intercom/askDeleteAnnounce');
?>

<div class="topic-list--header clearfix">
      <span class="topic-list-header--title"><b class="text-center">Recent Announcements</b></span>
    </div>

<h2 class="text-center"></h2>

<br>
<br>
<form action="" method="POST" enctype="multipart/form-data">
<input type="text" class="form-control nav-search" name="srchAnn" value="" id="anouMainViewSearch" placeholder="Search announcement"><br>
</form>

<div class="alert-msgEditAnnSucc"></div>
<div class="ale-AnnPageDel"></div>
<div id="ale-msgAnnErr"></div>
<br>
<div class="node-content">
<hr>
<?php 
    if(isset($announces)!=false && !empty($announces)){
          foreach($announces as $announce) { ?>

<h2 data-ann="<?php echo $announce->title; ?>" id="topDisply"><b><?php echo $announce->title; ?></b></h2>
<?php
  //if(identify_user_role($_SESSION['userID'] == $announnce->annID)){ ?>
  <div class="actions" id="axion">
   <a href="#" class="btn annEdit" value="" id="editingAnn" data-toggle="modal" data-target="#myModalEditAnn" data-annt="<?php echo $announce->title; ?>" data-annid="<?php echo $announce->annID; ?>" data-annb="<?php echo $announce->body; ?>" data-annd="<?php echo $announce->annDate; ?>">
    <i class="glyphicon glyphicon-pencil"></i>
    Edit
  </a>
  <a type="button" href="#"  id="deleteAnnID" class="btn" data-announceid="<?php echo $announce->annID; ?>" class="btn btn-danger" data-toggle="modal" data-target="#annouModel">
   <i class="glyphicon glyphicon-trash"></i>
   Delete
 </a>
</div>
<?php //} ?>
<h4 class="well well-sm"><?php echo $announce->annDate; ?></h4>
<?php
  if(isset($hasAnnounces)){
    $checkBold = 0;
    foreach ($hasAnnounces as $hasAnn) {
      if(($hasAnn->announcementID != $announce->annID) && ($hasAnn->userID != $_SESSION['userID'])){
        $checkBold = 1;
     }
   }
  ?>
      
  <p class="mb20" value="<?php echo $announce->annID; ?>">
    <h5 class="moreContent" data-userread="<?php $_SESSION['userID'] ?>" data-idann="<?php echo $announce->annID; ?>">
<?php if($checkBold == 0){ ?>
    <b><?php echo $announce->body; ?></b>
    <?php }else{ 
        echo $announce->body;
      } ?>
    </h5>
  </p>
  
<?php

} ?>
  <hr>
 <?php
     } 
    
    }else{ ?>
      <h2 class="text-justify"><b>NO ANNOUNCEMENTS AVALILABLE</b></h2>
   <?php
     }
     ?><br>&nbsp;
    <div class="col-md-6 col-xs-12 anouncements" id="announcingDisplay">
    </div>
    </div>
  </div>
</div>
<div class="text-center" style="clear:both;">
    <?php 
    echo $ann_links;
    ?>
</div>
<div id="stop" class="show scrollTop">
   <a href="#" class="myScroll"><i class="glyphicon glyphicon-chevron-up"></i></a><br><br>
</div>
<script>
  $(document).ready(function() {
    var showChar = 150;
    var ellipsestext = "...";
    var moretext = '<span class="btn btn-info more readMoreAnnouncement" class="readingBtn" data-userread="<?php //echo $_SESSION['userID']; ?>" data-idann="<?php //echo $announce->annID; ?>">Read More...</span>';
    var lesstext = '<span class="btn btn-default less readLessAnnouncement">Read Less</span>';
    $('h5.moreContent').each(function(){
      var content = $(this).html();
        if(content.length > showChar){
            var c = content.substr(0,showChar);
            var h = content.substr(showChar,content.length - showChar);
            var body = c + '<span class="moreellipses">'
            + ellipsestext+ '&nbsp;</span><span class="moreContent"><span>'
             + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
           $(this).html(body);
        }else{
          
        }
    });

    $(".morelink").click(function(){
        if($(this).hasClass('less')){
            $(this).removeClass('less');
            $(this).html(moretext);
        }else{
            $(this).addClass('less');
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
      });
    
    $('.moreContent').click(function(e){
      e.preventDefault();
      var readMeAnn = $(this).data('idann');
      var userRead = $(this).data('userread');
      alert(readMeAnn);
      
      $.ajax({
        url:'Intercom/readAnnounce',
        type: 'POST',
        dataType: 'json',
        data:{'readMeAnn':readMeAnn,
              'userRead':userRead},
    })
      .done(function(data){
      })
      .fail(function(){
        console.log('error');
      })
      .always(function(){
        console.log('complete');
      })
  });   
});

  </script>