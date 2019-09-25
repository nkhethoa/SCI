<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="container-fluid">
  <div class="win-alert"></div>
   <input type="hidden" name="id_user" value="<?php echo isset($id_user)? $id_user:0 ;?>" placeholder="">
    <h2 class="titles">Guardian List</h2>
    <div class="col-md-12 col-lg-12">

     <a role="button"  href="<?php echo base_url('admin')?>" class="btn btn-progress1 pull-right" title="Go Back"><i class="fa fa-step-backward"></i></a></div>
        <div class="col-lg-12">
            <input type="search" class="form-control" id="input-search" placeholder="Search Parents..." >
        </div>

        <div class="searchable-container">
       <!--Dislplay Guardian list using a foreach loop-->
            <?php 
            
            foreach ($db as $value) { 
  ?>
            <div class="items col-xs-12 col-sm-12 col-md-6 col-lg-6 clearfix">
               <div class="info-block block-info clearfix">
                <?php
                    if(strpos(identify_user_role($_SESSION['userID']),'admin')!==false){ ?>
                  <a href="<?php //echo base_url('admin/askDeleteParent/'.$value->g_uID)?>
                   " role="button" 
                    data-gname="<?=$value->gFName?>&nbsp;<?=$value->gMName?>&nbsp;<?=$value->gLName?> " 
                    class="btn btn-sq-xs btn-default askDeleteGuardian pull-right"
                    data-guardid="<?=$value->g_uID?>">

              <i class="fa fa-trash fa-1x"></i><br/>
            </a> 
             <?php } ?> 
               <a href="#myModal" role="button" 
               data-lname="<?=$value->gFName?>&nbsp;<?=$value->gMName?>&nbsp;<?=$value->gLName?> "
                data-guardid="<?=$value->g_uID?>" 
                data-email="<?=$value->gEmail?>" 
                data-toggle="modal" class="btn btn-sq-xs btn-default gcontact pull-right">
                <i class="fa fa-envelope-o fa-1x"></i><br/>
                </a> 
                    <div class="square-box pull-left">
                         <img src="<?php echo base_url($value->filePath)?>" class="listImg">
                    </div>
                    <h4><?=$value->gFName?>&nbsp;<?=$value->gMName?>&nbsp;<?=$value->gLName?></h4>
                    <em>Email: <?=$value->gEmail?></em>
                    <br>
                    <em>Phone: <?=$value->gPhone?></em>
                    <br>
                    <em>Relationship:<b><?=$value->relDescription?></b></em>
                    <br>
                    <span><b class="presentation2">Child/ren:</b>
                    <?php 
                    if (isset($myChildren)) {
                      
                   
                    foreach ($myChildren as $child) {
                         if ($value->guardID == $child->guardID) {?>

                              &nbsp;&nbsp; <span class="glyphicon glyphicon-user learnerGlyph"></span>
                               &nbsp;&nbsp;
                               <a  role="button"
                                  data-cid="<?=$child->learnerID?>" 
                                  data-cname="<?=$child->level?>" 
                                  class="viewp"
                                  data-cgnam="<?=$child->cgName?>"
                                  data-mabitso="<?=$child->lFName?>&nbsp;<?=$child->lMName?>&nbsp;<?=$child->lLName?> "
                                  <?php 
                                  if (isset($all_profiles)) {
                                  foreach ($all_profiles as $profiles) {
                                       if ($profiles->userID == $child->lUserID) {?>
                                          data-bio="<?=$profiles->bio?>" 
                                          data-pic="<?= base_url().$profiles->filePath?>"
                                       <?php }}} ?>
                                  data-toggle="modal" class="btn btn-sq-xs btn-default gcontact pull-right">
    
                                                        <?=$child->lFName?></a>

                         <?php
                       }
                     }
                    } ?>
                    
                </div> 
                <hr>
            </div>

            <?php } ?>
            
</div>
<div class="clearfix"></div>
 <ul id="pagin" class="text-center">
         
</ul>
</div>

<div class="container">

</div>
<!--pops up a modal when the admin clicks the message button to send a message-->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"> 
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel" class="text-center">Contact Parent</h3>
      </div> 
      <form action="" method="POST" class='messageForm' enctype="multipart/form-data">
        <div class="modal-body">
         <input type="hidden" name="userGuardID" id="userGuardID" value="">
         <div class="form-group col-md-6"><label>Name</label>
          <input class="form-control required" id="gname" value="" name="gname" placeholder="Your name" data-placement="top" data-trigger="manual" data-content="Must be at least 3 characters long, and must only contain letters." type="text"  disabled>
        </div>
        <div class="form-group col-md-6"><label>E-Mail</label>
          <input class="form-control email" id="gemail" name="gemail" value="" placeholder="email@you.com " data-placement="top" data-trigger="manual" data-content="Must be a valid e-mail address (user@gmail.com)" type="email" disabled>
        </div>
        <div class="form-group"><label>Subject</label>
          <input class="form-control" id="subject" name="gsubj" value="" placeholder="Message Title" data-placement="top" data-trigger="manual" type="text">
        </div>
        <div class="form-group"><label>Message</label>
          <textarea class="form-control" name="gmsg" id="message" rows="7" placeholder="Your message here.." data-placement="top" data-trigger="manual" required="required"></textarea>
        </div>
        <div class="form-group"> 
          <p class="help-block pull-left text-danger hide" id="form-error">  The form is not valid. </p>
        </div>
        
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger pull-right" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button type="submit"  class="btn btn-primary pull-left g_send-Message">Send !</button>
        
      </div>
    </form>
  </div>
</div>
</div>

<!-- modal for success message start -->
<div id="askDeleteGuardian" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-center"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-success btn-md askDeleteGuardian-yes modalClose" value="">Yes</button>
                    <button class="btn btn-danger btn-md askDeleteGuardian-no modalClose" value="No">No</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- modal for success message start -->
<div id="askDeleteGuardian-success" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-info btn-md askDeleteGuardian-ok modalClose" value="">Ok</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<!--Child profile-->
<div id="viewProfile" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><img id="profile_pic" src="" class="center"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg2 text-center presentation1"></p>
            <p class="modal-msg text-center presentation1"></p>
            <p class="modal-msg3 text-center presentation1"></p>
           
          </div>
          <div class="modal-footer">
             <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-success btn-md viewProfile-no modalClose pull-right" value="No">Close</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->