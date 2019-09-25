<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="mainTab-tab-content">
   <!--visible only on mobile-->
  <div class="dropdown visible-xs visible-sm">
    <button class="btn menu-color dropdown-toggle" type="button" data-toggle="dropdown">
      <i class="glyphicon glyphicon-th-list" title="Sub-Menu"></i> Sub Menu <span class="caret"></span>
    </button>
    <div class="dropdown-menu">
      <ul class="nav nav-mobile" role="tablist">    
      <?php 
      $index=1;
      if ((isset($subjects)) && (strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false || identify_user_role($_SESSION['userID'])=='learner')) { 
        foreach ($subjects as $subject) { 
          $cg= substr($subject->cgName,(strlen($subject->cgName))-1); 
          $tabID= substr($subject->subjectName,0,4).'-'.$subject->levelID.'-'.$cg
          ?>
          <li role="presentation" class="<?php echo ($index==1)? 'active':'' ?>">
            <a href="#progress-<?php echo $tabID ; ?>" 
              data-clsid="<?php echo $subject->clsID;?>" 
              data-level="<?php echo $subject->level;?>" 
              data-cg="<?php echo $cg;?>"   
              data-cglid="<?php echo $subject->cglID;?>"   
              data-subname="<?php echo $subject->subjectName; ?>" 
              role="tab" class="progressInnerTab" data-toggle="tab">
              <i class="fa fa-book"></i>&nbsp;&nbsp;<?php echo $subject->subjectName .' - Class '. $subject->levelID.''.$cg;?> 
            </a>
          </li>
          <?php
          $index++;   
        }
      //children of the guardian
      }elseif ((isset($kids)) && strpos(identify_user_role($_SESSION['userID']), 'guardian')!==false) {
         $index=1;
         foreach ($kids as $child) {
          //$cg= substr($child->cgName,(strlen($child->cgName))-1); 
          $tabID= substr($child->lFName,0,4).'-'.$child->lLName
          ?>
          <li role="presentation" class="<?php echo ($index==1)? 'active':'' ?>">
            <a href="#progress-<?php echo $tabID ; ?>" 
            data-luid="<?php echo $child->lUserID;?>" 
            data-lfname="<?php echo $child->lFName;?>" 
            data-llname="<?php echo $child->lLName;?>" 
            role="tab" class="gProgressLearnInnerTab" data-toggle="tab">
           <?= file_exists($child->filePath) ? '<img src="'.base_url($child->filePath).'" class="tabImg">':'<i class="fa fa-user"></i>';?>
              &nbsp;&nbsp;
            <?php
            $cg= substr($child->cgName,(strlen($child->cgName))-1);
            echo $child->lFName .' - '. $child->lLName .' '. $child->levelID.''.$cg;?>
          </a>
          </li>
          <?php
          $index++;   
        } 
        
      }
        ?>

      </ul>
    </div>
  </div><!--/visible only on mobile-->

  <!--visible only on desktop and larger-->
  <div class="tab hidden-xs hidden-sm" role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs " role="tablist" >    
      <?php 
      $index=1;
     if ((isset($subjects)) && (strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false || identify_user_role($_SESSION['userID'])=='learner')) {
        foreach ($subjects as $subject) { 
          $cg= substr($subject->cgName,(strlen($subject->cgName))-1); 
          $tabID= substr($subject->subjectName,0,4).'-'.$subject->levelID.'-'.$cg
          ?>
          <li role="presentation" class="<?php echo ($index==1)? 'active':'' ?>">
            <a href="#progress-<?php echo $tabID ; ?>" 
              data-clsid="<?php echo $subject->clsID;?>" 
              data-level="<?php echo $subject->level;?>" 
              data-cg="<?php echo $cg;?>"   
              data-cglid="<?php echo $subject->cglID;?>"  
              data-subname="<?php echo $subject->subjectName; ?>" 
              role="tab" class="progressInnerTab" data-toggle="tab">
              <i class="fa fa-book"></i>&nbsp;&nbsp;<?php echo $subject->subjectName .' - Class '. $subject->levelID.''.$cg;?> 
            </a>
          </li>
          <?php
          $index++;           
        }
      //children of the guardian
      }elseif ((isset($kids)) && strpos(identify_user_role($_SESSION['userID']), 'guardian')!==false) {
         $index=1;
         foreach ($kids as $child) {
          //$cg= substr($child->cgName,(strlen($child->cgName))-1); 
          $tabID= substr($child->lFName,0,4).'-'.$child->lLName
          ?>
          <li role="presentation" class="<?php echo ($index==1)? 'active':'' ?>">
            <a href="#progress-<?php echo $tabID ; ?>" 
            data-luid="<?php echo $child->lUserID;?>" 
            data-lfname="<?php echo $child->lFName;?>" 
            data-llname="<?php echo $child->lLName;?>" 
            role="tab" class="gProgressLearnInnerTab" data-toggle="tab">
           <?= file_exists($child->filePath) ? '<img src="'.base_url($child->filePath).'" class="tabImg">':'<i class="fa fa-user"></i>';?>
              &nbsp;&nbsp;
            <?php
            $cg= substr($child->cgName,(strlen($child->cgName))-1);
            echo $child->lFName .' - '. $child->lLName .' '. $child->levelID.''.$cg;?>
          </a>
          </li>
          <?php
          $index++;   
        } //end forloop
        
      }//end if KIDS
        ?>

      </ul>
    </div>
    
     <div class="tab-content">
       <?php 
       $index=1;
       if ((isset($subjects)) && ((strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false) || (identify_user_role($_SESSION['userID'])=='learner'))) {
       foreach ($subjects as $subject) { 
         $cg= substr($subject->cgName,(strlen($subject->cgName))-1); 
         $tabID= substr($subject->subjectName,0,4).'-'.$subject->levelID.'-'.$cg
         ?> 
         <div role="tabpanel" class="tab-pane fade in <?php echo ($index==1)? 'active':'' ?>" id="progress-<?php echo $tabID ;?>">
           <div class="well well-sm">
             <?php 
            if ((strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false)){ ?>           
               <div class="progress-alerts"></div><!--will display all alert for this section actions-->
               <label class="col-md-2 col-xs-12 attend-progress-margin" for="progressRange">Search Progress:</label> 
                   <div id="progressRange" class="progressDateRange pull-left col-md-4 academic_calendar">
                       <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;&nbsp;
                       <span></span> <b class="caret"></b>
                   </div>
                   <div class="attend-progress-margin">
                     <div class="custom-search-input">
                       <div class="input-group col-md-4 col-xs-12">
                           <input type="text" class="form-control input-md" placeholder="Search..." />
                           <span class="input-group-btn">
                               <button class="btn btn-info btn-md btn-search bigSize" value=""  type="button">
                                   <i class="glyphicon glyphicon-search"></i>
                               </button>
                           </span>
                       </div>
                     </div>
                     </div>
                
                 <div class="clearfix"></div>
               
               <div class="attend-progress-margin col-md-4">
                 <select class="form-control vieWhat bigSize" name="vieWhat" id="vieWhat" 
                   data-progress_cls_id="<?php echo $subject->clsID ;?>" 
                   data-cglid="<?php echo $subject->cglID;?>" 
                   data-progress_level="<?php echo $subject->level ;?>"
                   data-progress_cg="<?php echo substr($subject->cgName,(strlen($subject->cgName))-1) ;?>"
                   data-progress_subject="<?php echo $subject->subjectName ;?>">
                  <option selected hidden value="0">What do you want to do?</option>
                  <option value="1">View Specific Assessment Marks</option>
                  <option value="2">Add Assessment Marks</option>
                  <option value="3">Update Specific Assessment Marks</option>
                 </select>
               </div>
               <!--this hidden input will hold the option selected above -->
               <input type="hidden" id="progress_reason" value="" name="progress_reason">
               <div class="form-group attend-progress-margin div_which_assess none col-md-4">
                 <select class="form-control which_sub_assess bigSize" 
                      name="which_sub_assess" id="which_sub_assess" 
                      data-progress_cls_id="<?php echo $subject->clsID ;?>" 
                      data-cglid="<?php echo $subject->cglID;?>" 
                      data-progress_level="<?php echo $subject->level ;?>"
                      data-progress_cg="<?php echo substr($subject->cgName,(strlen($subject->cgName))-1) ;?>"
                      data-progress_subject="<?php echo $subject->subjectName ;?>">
                    <option selected hidden value="0">Select Assessment</option>
                   
                 </select>
              </div>
              <div class="clearfix"></div>
              
           <?php 
           } 
           ?>
           <!--Table responsible for displaying the attendance per subject-->
          <div class="table table-responsive tbl_results items">
              <table class="table table-responsive export_table">
              <thead>
                <tr>
                  <?php 
                  if (identify_user_role($_SESSION['userID']) !== 'learner') { ?>
                  <th>L#</th>
                  <th>Learner</th>
                  <?php } ?>
                  <th>Assessment</th>
                  <?php 
                  if (identify_user_role($_SESSION['userID']) === 'learner') { ?>
                  <th>Weight</th>
                  <?php } ?>
                  <th>Mark %</th>
                </tr>
              </thead>
              <tbody id='progress<?php echo 
                      str_replace(' ','',$subject->subjectName ).'-'.
                      substr($subject->cgName,(strlen($subject->cgName))-1).'-'.
                      str_replace(' ','',$subject->level); ?>'>

                 <?php echo isset($progress)? $progress:'' ;?>  
              </tbody>
            </table>

          </div>
           <script>
             $(function(){
                 $(".progress_btn_export").click(function(){
                     $("#export_table").tableToCSV();
                 });
             });
           </script>     
           </div>
         </div>  
         <?php 
         $index++; 
       }//end foreach subjects 
     }elseif (strpos(identify_user_role($_SESSION['userID']), 'guardian')!==false) { 
     //to count the subject as they come
     $counter=0;
     ?>
     <br>
     <div class="progressBuildSubj"><!-- buildLSubj hold results of AJAX-->
       <?php echo isset($guard_child_progress)? $guard_child_progress:'' ;?> 
     </div><!-- /buildLSubj hold results of AJAX-->
      <?php  
    }
       ?> 
     </div><!-- /tab-content-->
  </div>

 <div id="addProgressModal" 
            class="modal fade" 
            data-backdrop="static" 
            data-keyboard="false" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close modalClose" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Set up Assessment</h4>
          <div class="progress-alert"></div>
        </div>
        <!-- Modal body-->
        <div class="modal-body well well-sm">
          <form action="" method="POST" id="frmAssess_setup" accept-charset="utf-8">
            <input type="hidden" id="clsID_assess_number" value="" name="clsID_assess_number">
            <input type="hidden" id="subname_assess" value="" name="subname_assess">
            <input type="hidden" id="cglid_assess" value="" name="cglid_assess">
            <input type="hidden" id="level_assess" value="" name="level_assess">
            <input type="hidden" id="cg_assess" value="" name="cg_assess">
            <div class="form-group">
              <select class="form-control whichAssess bigSize" name="whichAssess" id="whichAssess">
                <option selected hidden>Which Assessement?</option>
                <?php 
                  if (isset($activeAssessType)) { 
                    foreach ($activeAssessType as $type) { ?>
                      <option value="<?php echo $type->id; ?>">
                        <?php echo $type->description; ?>
                      </option>
                  <?php 
                    } 
                  } 
                ?>
              </select>
            </div>
            <div class="form-group">
              <label>Enter Assessment Number:</label>
              <input type="text" class="numeric_only form-control" id="assess_number" name="assess_number">
            </div>
            <div class="form-group">
              <label>How much does it count?</label>
              <input type="text" class="numeric_only form-control" id="assess_weight" name="assess_weight">
            </div>
        </div>
        </form>
          <!-- Modal footer-->
          <div class="modal-footer">
            <input class="btn btn-primary" id="submit_assess_setup" name="submit" type="submit" value="Apply">
            <input class="btn btn-danger modalClose" type="button" data-dismiss="modal" value="Close" />
          </div>
        </div>
      </form>
      </div>
    </div>
