<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="container-fluid"> 
 <div class="yes-alert"></div>
  <h2 class="titles">Admin List</h2>
  <input <?php echo isset($cglID)? "value='$cglID'": "value = 0"; ?> id='cglID' type='hidden' id="cglID" name="cglID">
  <div class="row">
    <div class="col-md-12 col-lg-12">
      <!--hidden input id
        <input <?php //echo isset($id_user)? //"value='$id_user'": "value = 0"; ?> id ='idUser' type='hidden' name='idUser'>-->

        <a role="button"  href="<?php echo base_url('admin')?>" class="btn btn-progress pull-right" title="Go Back"><i class="fa fa-step-backward"></i></a></div>
        <div class="col-lg-12">
          <input type="search" class="form-control" id="input-search" placeholder="Search Administrators..." >
        </div>
        <div class="searchable-container">
         <!--Dislplay teacher list using a foreach loop-->
         <?php 

         foreach ($admins as $value) { 
          ?>
          <div class="items col-xs-12 col-sm-12 col-md-6 col-lg-6 clearfix">
           <div class="info-block block-info clearfix">
            <?php
            if(strpos(identify_user_role($_SESSION['userID']),'admin') !== false){ ?>
            <a  href="<?php //echo base_url('admin/askDeleteTeacher/'.$value->t_uID)?> " 
              role="button" 
              data-aname="<?=$value->aFName?>&nbsp;<?=$value->aMidName?>&nbsp;<?=$value->aLName?> "
              data-adminid="<?=$value->a_uID?>" 
              class="btn btn-sq-xs btn-default askDeleteAdmin  pull-right"
              >
              <i class="fa fa-trash fa-1x"></i>
              <?php } ?> 
              <br/>
              <a href="#" role="button" 
              data-aname="<?=$value->aFName?>&nbsp;<?=$value->aMidName?>&nbsp;<?=$value->aLName?> " 
              data-adminid="<?=$value->a_uID?>"
              data-adminemail="<?=$value->aEmail?>" 
              data-toggle="modal" data-target="#textAdmin" class="btn btn-sq-xs btn-default acontact  pull-right">
              <i class="fa fa-envelope-o fa-1x"></i><br/>

            </a> 
          <div class="square-box pull-left">
            <img src="<?php echo base_url($value->filePath)?>" class="listImg">
          </div>
          <h4><?=$value->aFName?>&nbsp;<?=$value->aMidName?>&nbsp; <?=$value->aLName?></h4>
          <p>Email: <?=$value->aEmail?></p>
          <span>Phone: <?=$value->aPhone?></span><br>
          <em>Start Date: <b><?=$value->startDate?></b></em><br>
     </div> 

     <hr>
   </div>

   <?php } ?>


 </div>

</div>

<div class="container">

</div>
<!--pops up a modal when the admin clicks the message button to send a message-->
<div id="textAdmin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel" class="text-center">Contact Admin</h3>
      </div>
      <div class="a-alert"></div> 
      <form  action="" method="POST" class="a_messageForm">
        <div class="modal-body">

         <input type="hidden" name="userAdminID" id="userAdminID" value="">
         <div class="form-group col-md-6"><label>Name</label>
          <input class="form-control required" id="aName" name="aName" value="" placeholder="Your name" data-placement="top" data-trigger="manual" data-content="Must be at least 3 characters long, and must only contain letters." type="text" disabled>
        </div>
        <div class="form-group col-md-6"><label>E-Mail</label>
          <input class="form-control email"  id="aEmail" name="aEmail" value="" placeholder="email@you.com (so that we can contact you)" data-placement="top" data-trigger="manual" data-content="Must be a valid e-mail address (user@gmail.com)" type="text" disabled></div>
          <div class="form-group"><label>Subject</label>
            <input class="form-control" id="subject" name="asubj" value="" placeholder="Enter Subject" data-placement="top" data-trigger="manual" type="text">
          </div>
          <div class="form-group"><label>Message</label>
            <textarea class="form-control"  name="amsg" id="message" rows="7" placeholder="Your message here.." data-placement="top" data-trigger="manual" required="required"></textarea>
          </div>


        </div>
        <div class="modal-footer">
         <div class="form-group">
          <button type="submit" class="btn pull-left Send-aMessage">Send</button>  
          <button class="btn  pull-right" data-dismiss="modal" aria-hidden="true">Cancel</button> 
          <p class="help-block pull-left text-danger hide" id="form-error">  The form is not valid. </p>
        </div>
      </div> 
    </form>
  </div>

</div>
</div>

<!-- modal for success message start -->
<div id="askDeleteAdmin" class="modal in display" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center"></h4>
      </div>
      <div class="modal-body">
        <p class="modal-msg"></p>
        <div class="row">
          <div class="col-12-xs text-center">
            <button class="btn btn-success btn-md askDeleteAdmin-yes modalClose" value="">Yes</button>
            <button class="btn btn-danger btn-md askDeleteAdmin-no modalClose" value="No">No</button>
          </div>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- modal for success message start -->
<div id="askDeleteAdmin-success" class="modal in display" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <p class="modal-msg"></p>
        <div class="row">
          <div class="col-12-xs text-center">
            <button class="btn btn-info btn-md askDeleteAdmin-ok modalClose" value="">Ok</button>
          </div>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="clearfix"></div>
<ul id="pagin" class="text-center">

</ul>