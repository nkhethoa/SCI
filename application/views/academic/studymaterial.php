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
            <a href="#study-<?php echo $tabID ; ?>" 
              data-clsid="<?php echo $subject->clsID;?>" 
              data-subname="<?php echo $subject->subjectName; ?>" 
              role="tab" class="studyInnerTab" data-toggle="tab">
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
            <a href="#study-<?php echo $tabID ; ?>" 
            data-luid="<?php echo $child->lUserID;?>" 
            data-lfname="<?php echo $child->lFName;?>" 
            data-llname="<?php echo $child->lLName;?>" 
            role="tab" class="gStudyLearnInnerTab" data-toggle="tab">
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
            <a href="#study-<?php echo $tabID ; ?>" 
              data-clsid="<?php echo $subject->clsID;?>" 
              data-subname="<?php echo $subject->subjectName; ?>" 
              role="tab" class="studyInnerTab" data-toggle="tab">
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
            <a href="#study-<?php echo $tabID ; ?>" 
            data-luid="<?php echo $child->lUserID;?>" 
            data-lfname="<?php echo $child->lFName;?>" 
            data-llname="<?php echo $child->lLName;?>" 
            role="tab" class="gStudyLearnInnerTab" data-toggle="tab">
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
        
      }//end elseif KIDS
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
        <div role="tabpanel" class="tab-pane fade in <?php echo ($index==1)? 'active':'' ?>" id="study-<?php echo $tabID ;?>">
          <div class="well well-sm">
            <div class="study-alerts"></div><!--will display all alert for this section actions-->

            <span class="pull-left"><h3><strong><u><?php echo $subject->subjectName ;?> Study Material</u></strong> </h3></span>
            <?php 
          if (strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false){ ?>
          <!--The button to launch study material add form-->
            <span class="pull-right">
              <button type="button" 
                data-clsid="<?php echo $subject->clsID;?>" 
                data-subname="<?php echo $subject->subjectName; ?>"
                class="btn btn-success btn-md addStudy btn-add" 
                data-toggle="modal" 
                data-target="#studyModal" title="Add" 
                data-placement="top"><i class="glyphicon glyphicon-plus" ></i>
              </button>
            </span>
          <?php 
          }
          ?>
            
            <!--Table responsible for displaying the study files per subject-->
            <div class="table table-responsive">
            <table class="table table-responsive studyTable">
              <thead>
                <tr>
                  <th>Title</th>
                  <th class="hidden-xs">Description</th>
                  <th class="hidden-xs">Published</th>
                  <th class="hidden-xs">Download Link</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody class='study<?php echo trim(str_replace(' ','',$subject->subjectName )) ;?>'>
                  <?php echo isset($study)? $study:'' ;?> 
              </tbody>
            </table>
          </div>
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
    <div class="studyBuildSubj"><!-- buildLSubj hold results of AJAX-->
      <?php echo isset($guard_child_study)? $guard_child_study:'' ;?> 
    </div><!-- /buildLSubj hold results of AJAX-->
     <?php  
   }
      ?> 
    </div><!-- /tab-content-->
  </div><!-- /mainTab-content-->

  <!-- Modal start-->
  <div id="studyModal" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close modalClose" data-dismiss="modal">&times;</button>
          <h4 class="modal-title study_UPSERT">Add Study Material</h4>
          <div class="study-alert"></div>
        </div>
       <form action="" method="POST" class=frmStudy enctype="multipart/form-data">
        <!-- Modal body-->
            <div class="modal-body">
                <input type="hidden" name="cls" id="sm_cls" value="">
                <input type="hidden" name="subname" id="sm_subname" value="">
                <input type="hidden" name="studyID" id="sm_studyID" value="">
                <input type="hidden" name="fileID" id="sm_fileID" value="">
            <div class="form-group">
              <label for="title">Title</label>
              <input class="form-control" id="sm_title" name="title" placeholder="Study material title " type="text" value="" />
            </div>
            <div class="form-group">
              <label for="fileUpload">File</label>
              <input type="file" name="fileUpload" id="sm_fileUpload" value="">
              <label id="study_file"></label><br>
              <label style="padding-top: 10px;" class="file_label"><b>Note:</b><em>&nbsp;Maximum file size is 50MB.</em> </label>
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <textarea class="form-control" id="sm_description" rows="7" style="width: 100%;" name="description" placeholder="Material Description here" type="textarea" value=""></textarea>
            </div>
          </div>
          <!-- Modal footer-->
          <div class="modal-footer">
            <input class="btn btn-primary saveMaterial" id="study_adding" name="submit" type="submit" value="Add">
            <input class="btn btn-danger modalClose" type="button" data-dismiss="modal" value="Close" />
          </div>
        </div>
      </form>
      </div>
    </div>


 <!-- Modal for confirm delete-->
    <div id="study-confirm" class="modal in none" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <p class="myP"></p>
            <div class="row">
              <div class="col-12-xs text-center">
                <input type="hidden" name="studyID"  value="">
                <input type="hidden" name="cls" id="del_study_cls" value="">
                <input type="hidden" name="subname" id="del_study_subname" value="">
                <button class="btn btn-success btn-md study_delete_Yes" id="deleteStudyID" value="" data-dismiss="modal" >Yes</button>
                <button class="btn btn-danger btn-md study_delete_No modalClose" data-dismiss="modal" value="Close">No</button>
              </div>
            </div>
          </div>
       
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<!-- read study material modal -->
 <div class="modal fade studyRead" data-backdrop="static" data-keyboard="false" id="viewGuardStudy" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header modal_head_color">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&nbsp;<span class="xCloseBtn">×</span></button>
          <h4 class="modal-title"><strong>Material Title:</strong> &nbsp;
            <span  class="modal-title" name="gStudy_title" id="gStudy_title"></span><br>
            <span class="pull-rigdht"><strong>Published:</strong>&nbsp;
              <span class="pull-rdight" id="gStudy_pubDate" name="gStudy_pubDate"></span>
            </span>
          </h4>
          <div id="alert-study"></div>
        </div>
        <div class="modal-body">
          <div class="form-group font-weight-bold">
            <label class="col-sdm-2" for="gStudy_desc"><b>Description</b></label>
            <br><span id="gStudy_desc"></span>
          </div>
          <div class="form-group">
            <label class="col-ssm-2" for="gStudy_down"><b>Download:</b>&nbsp;&nbsp;</</label>
            <a id="gStudy_down" href="#" download>
                <span class="glyphicon glyphicon-download fa-2x" title="Download File"></span>
            </a>
            <span ></span>
          </div>
            <button type="button" class="btn btn-danger modalClose btnmarg-left" data-dismiss="modal">Close</button>                    
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal read material -->