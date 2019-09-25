<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="container-fluid">
    <div class="win-alert"></div>
  <h2 class="titles">Teacher List</h2>
  <input <?php echo isset($cglID)? "value='$cglID'": "value = 0"; ?> id='cglID' type='hidden' id="cglID" name="cglID">
  <div class="row"> 
    <div class="class_Teacher_alert"></div>
    <div class="col-md-12 col-lg-12">
      <div class="alert-assignPages"></div>
     
      <!--hidden input id
        <input <?php //echo isset($id_user)? //"value='$id_user'": "value = 0"; ?> id ='idUser' type='hidden' name='idUser'>-->

        <a role="button"  href="<?php echo base_url('admin')?>" class="btn btn-progress pull-right" title="Go Back"><i class="fa fa-step-backward"></i></a></div>
        <div class="col-lg-12">
          <input type="search" class="form-control" id="input-search" placeholder="Search Teachers..." >
        </div>
        <div class="searchable-container">
         <!--Dislplay teacher list using a foreach loop-->
         <?php 

         foreach ($teachers as $value) { 
          ?>
          <div class="items col-xs-12 col-sm-12 col-md-6 col-lg-6 clearfix">
           <div class="info-block block-info clearfix">
            <?php
            if(strpos(identify_user_role($_SESSION['userID']),'admin')!==false){ ?>
            <a  href="#" 
                role="button" 
                data-tname="<?=$value->tFName?>&nbsp;<?=$value->tMidName?>&nbsp;<?=$value->tLName?> "
                data-techid="<?=$value->t_userID?>"
                title="Delete Teacher" 
                class="btn btn-sq-xs btn-default askDeleteTeacher  pull-right"
                data-teachid="<?=$value->t_userID?>">
                <i class="fa fa-trash fa-1x"></i>
               <a  href="#" 
                  role="button" 
                  title="Assign Class Teacher"
                  data-lname="<?=$value->tFName?>&nbsp;<?=$value->tMidName?>&nbsp;<?=$value->tLName?> "  
                  data-toggle="modal" 
                  data-target="#assignClass" 
                  data-teachid="<?=$value->teacherID?>" 
                  data-teacher_userid="<?=$value->t_userID?>" 
                  class="btn btn-sq-xs btn-default assignClass pull-right">
                  <i class="fa fa-plus-square fa-1x"></i>
              <?php } ?>  
              <br/>
              <a href="#" role="button" 
                  data-lname="<?=$value->tFName?>&nbsp;<?=$value->tMidName?>&nbsp;<?=$value->tLName?> " 
                  data-teachid="<?=$value->t_userID?>"
                  data-email="<?=$value->tEmail?>" 
                  data-toggle="modal" data-target="#myModal" title="Contact Teacher" class="btn btn-sq-xs btn-default gcontact  pull-right">
                  <i class="fa fa-envelope-o fa-1x"></i><br/>

            </a> 
            <a href="#" 
              role="button"  
              data-teachid="<?=$value->t_userID?>" 
              data-toggle="modal" data-target="#moreDetails" title="View Classes" class="btn btn-sq-xs viewDetails btn-default pull-right">
              <i class="fa fa-info-circle fa-1x"></i><br/>
          </a> 
          <div class="square-box pull-left ">
            <img src="<?php echo base_url($value->filePath)?>" class="listImg">
          </div>
          <h4><?=$value->tFName?>&nbsp;<?=$value->tMidName?>&nbsp; <?=$value->tLName?></h4>
          <p>Email: <?=$value->tEmail?></p>
          <span>Phone: <?=$value->tPhone?></span><br>
          <?php
          if(strpos(identify_user_role($_SESSION['userID']),'admin')!==false){ ?>
          <a href="#" 
            role="button"
            data-lname="<?=$value->tFName?> <?=$value->tMidName?> <?=$value->tLName?>" 
            data-teahersid= "<?=$value->teacherID?>"
            data-toggle="modal" data-target="#allocate_subject" 
            class="btn  col-sm-12 col-lg-2 pull-right Class_Assign " 
            title="Allocate Subjects">Allocate</a>
          <?php 
            }
            //an empty array to hold class teachers  
            $class_teachers = array();
            //loop to find who is the class teacher
           foreach ($classTeacher as $class) {
            //compare from list of teacher and list of class teacher
            //if find a match, then that person is class teacher
             if ($value->teacherID == $class->teacherID) { 
                //load the teacher found in the class teacher array
                $class_teachers[] = $class;
              }//end teacher compare
            }//close foreach
            //check if the class teacher array has something or not empty
            if (isset($class_teachers) && !empty($class_teachers)) {
              //loop thru array of found class teacher
              foreach ($class_teachers as $value) { 
                //prind the class the teacher is responsible for
                ?>
                <span>
                  Class Teacher: 
                  <b class="presentation1">
                    <label class="<?=$value->tUserID?>"><?=$value->level?></label>
                  </b>&nbsp;
                  <b class="presentation1">
                    <label class="<?=$value->teacherID?>"><?=$value->className?></label>
                  </b>
                   <?php
                    if(strpos(identify_user_role($_SESSION['userID']),'admin')!==false){ ?>
                  <a href="#" class="btn-sm btn-hover RemoveClassTeacher btn-danger" data-teahersid= "<?=$value->teacherID?>" title="remove as class teacher"><i class="fa fa-trash"></i></a>
                  <?php 
                }?>
                </span>
               
                  <?php 
              }//end foreach 
            //if class teacher array is empty
            }else{
             ?>
            <span>
              Class Teacher: 
              <b class="presentation1">
                <label class="<?=$value->t_userID?>"><b class="presentation2">Not a Class Teacher</b></label>
              </b>&nbsp; &nbsp;
              <b class="presentation1">
                <label class="<?=$value->teacherID?>"></label>
              </b>
            </span>

            <?php 
            }//end else not a class teacher

       ?>
     </div> 

     <hr>
   </div>

   <?php } ?>


 </div>

</div>

<div class="container">

</div>

<!--pops up a modal when the admin clicks the info button to view more details-->
<div id="moreDetails" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header modStyle">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel" class="text-center">More Details</h3>
      </div>
      <div class="modal-body modBstyle">
        <div class="form-group" id="mySubjects">

        </div>
      </div>
      <div class="modal-footer modStyle">
       <div class="form-group"> 
        <button class="btn btn-default pull-right" data-dismiss="modal" aria-hidden="true">Close</button> 
        <p class="help-block pull-left text-danger hide" id="form-error">  The form is not valid. </p>
      </div>
    </div> 
  </div>

</div>
</div>

<!--pops up a modal when the admin clicks the message button to send a message-->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel" class="text-center">Contact Teacher</h3>
      </div>
      <div class="comment-alert"></div> 
      <form  action="" method="POST" class="t_messageForm">
        <div class="modal-body">

         <input type="hidden" name="userTeachID" id="userTeachID" value="">
         <div class="form-group col-md-6"><label>Name</label>
          <input class="form-control required " id="tName" name="tName" value="" placeholder="Your name" data-placement="top" data-trigger="manual" data-content="Must be at least 3 characters long, and must only contain letters." type="text" disabled>
        </div>
        <div class="form-group col-md-6"><label>E-Mail</label>
          <input class="form-control email "  id="tEmail" name="tEmail" value="" placeholder="email@you.com (so that we can contact you)" data-placement="top" data-trigger="manual" data-content="Must be a valid e-mail address (user@gmail.com)" type="text" disabled></div>
          <div class="form-group"><label>Subject</label>
            <input class="form-control" id="subject" name="tsubj" value="" placeholder="Enter Subject" data-placement="top" data-trigger="manual" type="text">
          </div>
          <div class="form-group"><label>Message</label>
            <textarea class="form-control"  name="tmsg" id="message" rows="7" placeholder="Your message here.." data-placement="top" data-trigger="manual" required="required"></textarea>
          </div>


        </div>
        <div class="modal-footer">
         <div class="form-group">
          <button type="submit" class="btn pull-left t_send-Message">Send</button>  
          <button class="btn  pull-right" data-dismiss="modal" aria-hidden="true">Cancel</button> 
          <p class="help-block pull-left text-danger hide" id="form-error">  The form is not valid. </p>
        </div>
      </div> 
    </form>
  </div>

</div>
</div>


<!-- modal for success message start -->
<div id="askDeleteTeacher" class="modal in display" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center"></h4>
      </div>
      <div class="modal-body">
        <p class="modal-msg"></p>
        <div class="row">
          <div class="col-12-xs text-center">
            <button class="btn btn-success btn-md askDeleteTeacher-yes modalClose" value="">Yes</button>
            <button class="btn btn-danger btn-md askDeleteTeacher-no modalClose" value="No">No</button>
          </div>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- modal for success message start -->
<div id="askDeleteTeacher-success" class="modal in display" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <p class="modal-msg"></p>
        <div class="row">
          <div class="col-12-xs text-center">
            <button class="btn btn-info btn-md askDeleteTeacher-ok modalClose" value="">Ok</button>
          </div>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--pops up a modal when the admin clicks the message button to send a message-->
<div id="allocate_subject" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"> 
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel" class="text-center">Allocate Teacher subjects</h3>
      </div> 
      <div class="teach_subj-alert"></div>
      <form action="" method="POST" class='messagveForm'>
        <div class="modal-body">
          <input type="hidden" name="userTeachID" id="userTedachID" value=""> 
          <div class="col-md-12">
            <div class="form-group"><label>Teacher Names</label>
            <input class="form-control required" id="teach" name="teach" value="" placeholder="Your name" data-placement="top" data-trigger="manual" type="text" disabled="disabled">
          </div>
          </div>
          <div class="col-md-5">
            <div class="form-group">
              <label>Select Subjects <span>*</span></label>
              <select class="level_id form-control add_from" id="t_school_subjects" name="school_subjects">
               <option hidden value="">Select Subject</option>
               <?php
               foreach($schoolSubjects as $sub){ ?>  
                <option value="<?=$sub->subjID?>"><?=$sub->subjectName?></option>
               <?php
                }
             ?> 
           </select>
           <span><?php echo form_error('school_subjects')? alertMsgs(false,'',form_error('school_subjects')):''?></span>
         </div>
         <div class="form-group none" id="level_div">
          <label>Class Level <span>*</span></label>
          <select class="level_id form-control add_from" id="t_level" name="level">
           <option hidden value="">Select Grade/Level</option>
           <?php
           foreach($levels as $cgLevels){ ?>  
           <option value="<?=$cgLevels->levelID?>"><?=$cgLevels->levelName?></option>
           <?php
         }
         ?> 
       </select>
       <span><?php echo form_error('level')? alertMsgs(false,'',form_error('level')):''?></span>
     </div>
     <div class="form-group none" id="group_div">
      <label>Class Group <span>*</span></label>
      <select class="group_id form-control add_from" id="t_group" name="group">
        <option hidden value>Select Class Group</option>  
      </select>
      <span><?php echo form_error('group')? alertMsgs(false,'',form_error('group')):''?></span>
    </div>
  </div>
  <div class="col-md-3">
    <!--push add button down -->
    <div class="col-mdd-12"><label></label></div>
    <div class="col-mdd-12"><label></label></div>
    <div class="col-md-12 none" id="add_subj_div">
      <button type="button" id="add_selected" class="btn btn-success col-md-8">Add &gt;</button>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 none" id="btn_remove_div">
      <button type="button" id="remove_selected" class="btn btn-warning col-md-8">Remove</button>
    </div>
  </div>
  <div class="form-group col-md-4 none" id="my_subjecs_div">
    <label>Selected Subjects </label>
    <select size="10" multiple class="select form-control" id="selected_subjects" name="selected_subjects[]">  
    </select>
  </div>
  
</div>
</form>
<div class="clearfix"></div>
<div class="modal-footer">
  <button class="btn btn-danger pull-right" data-dismiss="modal" aria-hidden="true">Close</button>
  <button type="button" id="mt_allocate_subjects" class="btn btn-success pull-left">Assign</button>

</div>

</div>
</div>
</div>
<div class="clearfix"></div>
<ul id="pagin" class="text-center">

</ul>






<!-- Assign Class Teacher Subject Modal -->
<!-- line modal -->
<div class="modal fade" id="assignClass" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title text-center" id="lineModalLabel">Assign Class Teacher</h3>
      </div>
      <div class="modal-body">
        <div class="alert-assignError"></div>
        <!-- content goes here -->
        <form method="POST">
          <div class="form-group">
           <input type="hidden" name="teacherID" id="teacherID" value=""> 
           <input type="hidden" name="teacher_userID" id="teacher_userID" value=""> 
           <input <?php echo isset($cglID)? "value='$cglID'": "value = 0"; ?> id='cglID' type='hidden' id="cglID" name="cglID">
           <label for="assignClasses">Full Names</label>
           <input type="text"  name="assignClasses" class="form-control" value="" id="assignClasses" disabled>
         </div>
         <div class="form-group">
          <input type="date" name="date" id="date" class="form-control" value="">

        </div>
        <div class="col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px">
         <div class="form-group col-md-6 removePadding"><label>Assign Level</label>
           <select class="level_id form-control" id="teacherLevel" name="level" >
             <option hidden value="">Select Grade/Level</option>
             <?php
             if (isset($levels)) {
               foreach($levels as $cgLevels){ ?>  
               <option value="<?=$cgLevels->levelID?>"><?=$cgLevels->levelName?></option>
               <!-- other options -->
               <?php
             }
           }
           ?>
           <!-- other options -->
         </select>
         <?php echo form_error('level')? alertMsgs(false,'',form_error('level')):''?></span>
       </div>
       <div class="form-group col-md-6 removePaddingRight"><label>Assign Level Group</label>
         <select class="group_id form-control" id="teacherGroup" name="group" >
           <option hidden value="">Select Class Group Level</option>
           

           <!-- other options -->
         </select>
         <?php echo (form_error('group') && form_error('level')=='')? alertMsgs(false,'',form_error('group')):''?></span>
       </div>

     </form>
   </div>
 </div>
 <div class="btn-group btn-group-justified" role="group" aria-label="group button">
  <div class="btn-group" role="group">
    <button type="button" class="btn btn-warning" data-dismiss="modal"  role="button">Cancel</button>
  </div>
  <div class="btn-group" role="group">
    <button type="button" id="assign" class="btn btn-success btn-hover-green assign"  role="button">Assign</button>
  </div>
</div>
</div>
</div>
</div>