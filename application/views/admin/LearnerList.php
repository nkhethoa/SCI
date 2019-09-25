<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="container-fluid">
  <div id="learn-alert"></div>
    <h2 class="titles">Learner List</h2>
      <div class="row">
          
    <div class="col-md-12 col-lg-12">
     <a role="button"  href="<?php echo base_url('admin')?>" class="btn btn-progress pull-right" title="Go Back"><i class="fa fa-step-backward"></i></a></div>
        <div class="col-lg-12">
            <input type="search" class="form-control" id="input-search" placeholder="Search Learners..." >
        </div>
        <div class="searchable-container">
         <!--Dislplay teacher list using a foreach loop-->
            <?php 
            foreach ($learners as $learner) { 
  ?>
            <div class="items col-xs-12 col-sm-12 col-md-6 col-lg-6 clearfix">
               <div class="info-block block-info clearfix">
                <?php
                    if(strpos(identify_user_role($_SESSION['userID']),'admin') !== false){ ?>
                <a  href="<?php //echo base_url('admin/askDeleteTeacher/'.$value->t_uID)?> " 
                  role="button"
                  title="Delete Learner" 
                   data-learnname="<?=$learner->fName?>&nbsp;<?=$learner->midName?>&nbsp;<?=$learner->lName?> " 
                  data-learnid="<?=$learner->l_uID?>" 
                  class="btn btn-sq-xs btn-default askDeleteLearner  pull-right">
              <i class="fa fa-trash fa-1x styler"></i>
              <?php } ?> 
              <br/>
                   <a href="#myModal" role="button" 
                   title="Contact Learner"
                  data-learnname="<?=$learner->fName?>&nbsp;<?=$learner->midName?>&nbsp;<?=$learner->lName?> " 
                   data-learnemail="<?=$learner->email?>"  
                   data-learnid="<?=$learner->l_uID?>" 
                   data-toggle="modal" class="btn btn-sq-xs btn-default learncontact  pull-right">
              <i class="fa fa-envelope-o fa-1x"></i><br/>
            </a> 
                    <div class="square-box pull-left">
                        <img src="<?php echo base_url($learner->filePath)?>" class="listImg">
                    </div>
                    <h4><?=$learner->fName?>&nbsp;<?=$learner->midName?>&nbsp; <?=$learner->lName?></h4>
                    <p>Email: <?=$learner->email?></p>
                    <span>Phone: <?=$learner->phone?></span>
                    <br>
                    <em title="From Department of Education">Learner Number(DOE):<b class="presentation1"> <?=$learner->learnDoE_ID?></b></em>
                    <br>
                    <p>Class: <b class="presentation1"><?=$learner->cgName?></b>&nbsp; &nbsp; Level:<b class="presentation1"> <?=$learner->level?></b></p>                       
                </div> 
                <hr>
            </div>

            <?php } ?>

            
</div>
</div>

<div class="container">

</div>
   <!--pops up a modal when the admin clicks the message button to send a message-->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div id="lea-alert"></div>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel" class="text-center">Contact Learner</h3>
      </div>
       <form action="" method="POST" class='l_messageForm' enctype="multipart/form-data">
      <div class="modal-body">
       
          <input type="hidden" name="userlearnerID" id="userlearnerID" value="">
          <div class="form-group col-md-6">
            <label>Name</label>
            <input class="form-control required" name="lName" id="learnName" value="" placeholder="Your name" data-placement="top" data-trigger="manual" data-content="Must be at least 3 characters long, and must only contain letters." type="text" disabled>
          </div>
          <div class="form-group col-md-6">
            <label>E-Mail</label>
            <input class="form-control email"  id="learnEmail" name="lEmail" value=""  data-placement="top" data-trigger="manual" data-content="Must be a valid e-mail address (user@gmail.com)" type="text" disabled>
          </div>
          <div class="form-group">
            <label>Subject</label>
          <input class="form-control" id="lsubj" name="lsubj" value="" placeholder="Enter Subject" data-placement="top" data-trigger="manual" type="text">
        </div>
          <div class="form-group">
            <label>Message</label>
            <textarea class="form-control" name="lmsg" value="" rows="7" id="lmsg" placeholder="Your message here.." data-placement="top" data-trigger="manual" required="required"></textarea>
          </div>
         
        
      </div>
      <div class="modal-footer">
       <div class="form-group">
            <button type="submit" class="btn btn-primary Send-Messages">Send</button>  
            <button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button> 
            <p class="help-block pull-left text-danger hide" id="form-error">  The form is not valid. 
            </p>
          </div>
      </div>
    </form>
    </div>
  </div>
</div>

<!-- modal for success message start -->
<div id="askDeleteLearner" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-center"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-success btn-md askDeleteLearner-yes modalClose" value="">Yes</button>
                    <button class="btn btn-danger btn-md askDeleteLearner-no modalClose" value="No">No</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- modal for success message start -->
<div id="askDeleteLearner-success" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-info btn-md askDeleteLearner-ok modalClose" value="">Ok</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
 <div class="clearfix"></div>
<ul id="pagin" class="text-center">
         
</ul>




