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
            <a href="#attend-<?php echo $tabID ; ?>" 
              data-clsid="<?php echo $subject->clsID;?>"  
              data-level="<?php echo $subject->level;?>" 
              data-cg="<?php echo $cg;?>"   
              data-cglid="<?php echo $subject->cglID;?>"  
              data-subname="<?php echo $subject->subjectName; ?>" 
              role="tab" class="attendInnerTab" data-toggle="tab">
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
            <a href="#attend-<?php echo $tabID ; ?>" 
            data-luid="<?php echo $child->lUserID;?>" 
            data-lfname="<?php echo $child->lFName;?>" 
            data-llname="<?php echo $child->lLName;?>" 
            role="tab" class="gAttendLearnInnerTab" data-toggle="tab">
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
     if ((isset($subjects)) 
        && (strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false 
        || identify_user_role($_SESSION['userID'])=='learner')) {
        foreach ($subjects as $subject) { 
          $cg= substr($subject->cgName,(strlen($subject->cgName))-1); 
          $tabID= substr($subject->subjectName,0,4).'-'.$subject->levelID.'-'.$cg
          ?>
          <li role="presentation" class="<?php echo ($index==1)? 'active':'' ?>">
            <a href="#attend-<?php echo $tabID ; ?>" 
              data-clsid="<?php echo $subject->clsID;?>" 
              data-cglid="<?php echo $subject->cglID;?>" 
              data-level="<?php echo $subject->level;?>"  
              data-cg="<?php echo $cg;?>"  
              data-subname="<?php echo $subject->subjectName; ?>" 
              role="tab" class="attendInnerTab" data-toggle="tab">
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
            <a href="#attend-<?php echo $tabID ; ?>" 
            data-luid="<?php echo $child->lUserID;?>" 
            data-lfname="<?php echo $child->lFName;?>" 
            data-llname="<?php echo $child->lLName;?>" 
            role="tab" class="gAttendLearnInnerTab" data-toggle="tab">
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
        <div role="tabpanel" class="tab-pane fade in <?php echo ($index==1)? 'active':'' ?>" id="attend-<?php echo $tabID ;?>">
          <div class="well well-sm">
            <?php 
          if ((strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false)){ ?>           
              <div class="attend-alerts"></div><!--will display all alert for this section actions-->

              <label class="col-md-2 col-xs-12 attend-progress-margin" for="attendRange">Search Attendance:</label> 
                  <div id="attandRange"
                      class="attandDateRange pull-left col-md-4 academic_calendar">
                      <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;&nbsp;
                      <span></span> <b class="caret"></b>
                  </div>
                  <div class="attend-progress-margin">
                    <div class="custom-search-input">
                      <div class="input-group col-md-4 col-xs-12">
                          <input type="text" class="form-control input-md" 
                                id="txtSearch_attend" placeholder="Search..." />
                          <span class="input-group-btn">
                              <button class="btn btn-info btn-md btn-search bigSize" value=""  type="button">
                                  <i class="glyphicon glyphicon-search"></i>
                              </button>
                          </span>
                      </div>
                    </div>
                    </div>
               
                <div class="clearfix"></div>
              
              <div class="attend-progress-margin">
                <select class="form-control vieWhichAttend bigSize" name="vieWhichAttend" id="vieWhichAttend" 
                  data-attend_cls_id="<?php echo $subject->clsID ;?>" 
                  data-cglid="<?php echo $subject->cglID;?>" 
                  data-attend_level="<?php echo $subject->level ;?>"
                  data-attend_cg="<?php echo substr($subject->cgName,(strlen($subject->cgName))-1) ;?>"
                  data-attend_subject="<?php echo $subject->subjectName ;?>">
                  <option selected hidden>What do you want to do?</option>
                  <option value="1">View Attendance</option>
                  <option value="2">Mark Attendance</option>
                </select>
              </div>
          <?php 
          } 
          ?>
          <!--Table responsible for displaying the attendance per subject-->
          <div id="attend<?php echo 
                      str_replace(' ','',$subject->subjectName ).'-'.
                      substr($subject->cgName,(strlen($subject->cgName))-1).'-'.
                      str_replace(' ','',$subject->level); ?>" 
              class="table table-responsive search_results">

            <?php
            //print the table from the controller
             echo isset($attend)? $attend : '' ;
             ?>
          </div>
          <!--Table responsible for displaying the attendance per subject-->            
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
    <div class="attendBuildSubj"><!-- buildLSubj hold results of AJAX-->
      <?php echo isset($guard_child_attend)? $guard_child_attend:'' ;?> 
    </div><!-- /buildLSubj hold results of AJAX-->
     <?php  
   }
      ?> 
    </div><!-- /tab-content-->
  </div><!-- /mainTab-content-->

 