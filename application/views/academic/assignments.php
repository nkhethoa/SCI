<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  ?>
  <div class="mainTab-tab-content active"><!-- mainTab-content-->
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
            <a href="#assign-<?php echo $tabID ; ?>" 
              data-clsid="<?php echo $subject->clsID;?>" 
              data-subname="<?php echo $subject->subjectName; ?>" 
              role="tab" class="assignInnerTab" data-toggle="tab">
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
            <a href="#assign-<?php echo $tabID ; ?>" 
            data-luid="<?php echo $child->lUserID;?>" 
            data-lfname="<?php echo $child->lFName;?>" 
            data-llname="<?php echo $child->lLName;?>" 
            role="tab" class="gAssignLearnInnerTab" data-toggle="tab">
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
            <a href="#assign-<?php echo $tabID ; ?>" 
              data-clsid="<?php echo $subject->clsID;?>" 
              data-subname="<?php echo $subject->subjectName; ?>" 
              role="tab" class="assignInnerTab" data-toggle="tab">
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
            <a href="#assign-<?php echo $tabID ; ?>" 
            data-luid="<?php echo $child->lUserID;?>" 
            data-lfname="<?php echo $child->lFName;?>" 
            data-llname="<?php echo $child->lLName;?>" 
            role="tab" class="gAssignLearnInnerTab" data-toggle="tab">
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
    <!-- tab-content-->
    <div class="tab-content">
      <?php 
      $index=1;
      if ((isset($subjects)) && (strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false || identify_user_role($_SESSION['userID'])=='learner')) {
      foreach ($subjects as $subject) { 
        $cg= substr($subject->cgName,(strlen($subject->cgName))-1); 
        $tabID= substr($subject->subjectName,0,4).'-'.$subject->levelID.'-'.$cg
        ?>
        <!--The tabpanel start--> 
        <div role="tabpanel" class="tab-pane fade in <?php echo ($index==1)? 'active':'' ?>" id="assign-<?php echo $tabID ;?>">
          <div class="well well-sm">
            <div class="assignment-alerts"></div>
            <span class="pull-left"><h3><strong><u><?php echo $subject->subjectName ;?> Assignments</u></strong> </h3></span>
            <?php 
          if (strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false){ ?>
            <!--The button to launch assign material add form-->
            <span class="pull-right">
              <button type="button" 
                data-clsid="<?php echo $subject->clsID;?>" 
                data-subname="<?php echo $subject->subjectName; ?>"
                class="btn btn-success btn-md addAssign btn-add" 
                data-toggle="modal" 
                data-target="#assignModal" 
                data-placement="top" style="float: right;"><i class="glyphicon glyphicon-plus"></i>
              </button>
            </span>
            <?php 
          }
            ?>
            <!--Table responsible for displaying the assign files per subject-->
            <div class="table table-responsive">
            <table class="table table-responsive assignTable tblslim">
              <thead>
                <tr>
                  <th>Title</th>
                  <th class="hidden-xs">Description</th>
                  <th class="hidden-xs">Published</th>
                  <th class="hidden-xs">Due Date</th>
                  <th class="hidden-xs">Download Link</th>
                  <?php 
                  if (identify_user_role($_SESSION['userID'])=='learner') { ?>
                    <th>View <br>Assignment</th>
                  <?php }else{ ?>
                    <th>Options</th>
                   <?php 
                  }
                  ?>
                </tr>
              </thead>
              <tbody class='assign<?php echo trim(str_replace(' ','',$subject->subjectName )) ;?>'>
                <!--contents of the table will be shown here-->
                <?php echo isset($assign)? $assign:'' ;?>
              </tbody>
            </table>
          </div>
          </div>
          
        </div>

        <?php 
        $index++; 
      }//end foreach
    }elseif (strpos(identify_user_role($_SESSION['userID']), 'guardian')!==false) { ?>
    <br>
    <div class="buildLSubjAssign"><!-- buildLSubj hold results of AJAX-->
      <?php echo isset($guard_child_assign)? $guard_child_assign:'' ;?> 
    </div><!-- /buildLSubj hold results of AJAX-->
     <?php  
   }
      ?> 
    </div><!-- /tab-content-->
  </div><!-- /mainTab-content-->

  <!-- Modal start-->
  <div id="assignModal" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close modalClose" data-dismiss="modal">&times;</button>
          <h4 class="modal-title assignment-title"></h4>
          <div class="assign-alert"></div>
        </div>
       <form action="" method="POST" class=frmAssign enctype="multipart/form-data">
        <!-- Modal body-->
          <div class="modal-body">
              <input type="hidden" name="cls" id="assi_cls" value="">
              <input type="hidden" name="assi_subname" id="assi_subname" value="">
              <input type="hidden" name="assignID" id="assignID" value="">
              <input type="hidden" name="fileID" id="assi_fileID" value="">
            <div class="form-group">
              <label for="title">Title</label>
              <input class="form-control" id="assi_title" name="title" placeholder="Assignment title " type="text" value="" />
            </div>
            <div class="form-group">
              <label for="fileUpload">File</label>
              <input type="file" name="fileUpload" id="assi_fileUpload" value="">
              <label id="assign_file"></label><br>
              <label style="padding-top: 10px;"><b>Note:</b><em>&nbsp;Maximum file size is 50MB.</em> </label>
            </div>
            <div class="form-group has-feedback">
                <label class="control-label" for="dueDate">Due Date</label>
                <input type="text" name="dueDate" id="assi_dueDate" 
                  class="form-control date-picker" placeholder="Select Due Date and Time" value="" /> 
                <i class="glyphicon glyphicon-calendar fa fa-calendar  form-control-feedback" style="font-size:34px"></i>
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <textarea class="form-control" id="assi_description" rows="7" style="width: 100%;" name="description" placeholder="Assignment Description here" type="textarea" value=""></textarea>
            </div>

        </div>
          <!-- Modal footer-->
          <div class="modal-footer">
            <input class="btn btn-primary saveAssignment" id="addAssign" name="submit" type="submit" value="Add"  />
            <input class="btn btn-danger modalClose" type="button" data-dismiss="modal" value="Close" />
          </div>
        </div>
      </form>
      </div>
    </div>


  <!-- Modal for confirm delete-->
    <div id="assign-confirm" class="modal in none" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title-del"></h4>
          </div>
          <div class="modal-body">
            <p class="myP"></p>
            <div class="row">
              <div class="col-12-xs text-center">
                <input type="hidden" name="assignID"  value="">
                <input type="hidden" name="cls" id="del_assi_cls" value="">
                <input type="hidden" name="assi_subname" id="del_assi_subname" value="">
                <button class="btn btn-success btn-md deleteAssign_Yes" id="deleteAssignID" value="" data-dismiss="modal" >Yes</button>
                <button class="btn btn-danger btn-md deleteAssign_No modalClose" data-dismiss="modal" value="Close">No</button>
              </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

  <!-- submit assignment modal -->
  <div class="modal fade submitAssignment" data-backdrop="static" data-keyboard="false" id="assignSubmit" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header modal_head_color">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&nbsp;<span class="xCloseBtn">×</span></button>
          <h4 class="modal-title"><strong>Assignment Title:</strong> &nbsp;
            <span  class="modal-title " name="assign_title" id="assign_title"></span><br>
            <span class="pull-rigdht"><strong>Published:</strong>&nbsp;
              <span class="pull-rdight" id="assign_pubDate" name="assign_pubDate"></span>
            </span>
          </h4>
          <div id="alert-msg"></div>
        </div>
        <div class="modal-body">
          <div class="form-group font-italic">
            <label class="col-sfm-2 " for="dueDate"><b>Due Date:</b>&nbsp;&nbsp;</label>
            <em><span id="dueDate" class="glyphicon-red" ></span></em>
          </div>
          <div class="form-group font-weight-bold">
            <label class="col-sfm-2" for="assign_desc"><b>Description</b></label>
            <br><span id="assign_desc"></span>
          </div>
          <div class="form-group">
            <label class="col-sfm-2" for="assign_down"><b>Download:</b>&nbsp;&nbsp;</label>
            <a id="assign_down" href="#" download>
              <span class="glyphicon glyphicon-download fa-2x" title="Download File"></span>
            </a>
            <span ></span>
          </div>
          <div class="form-group font-italic yes-submit">
            <label class="col-sdm-4 " for="submitDate"><b>Submitted On:</b>&nbsp;&nbsp;</label>
            <em><span id="submitDate"></span></em>
          </div>
          <div class="nofile" ></div>
          <!--close button if submitted -->
          <button type="button" class="btn btn-danger modalClose btndmarg-left yes-submit" data-dismiss="modal">Close</button>
          <?php if (identify_user_role($_SESSION['userID'])=='learner') { ?>
          <form action="" method="POST" class="frmSubmission" accept-charset="utf-8" id="no-submit">
            <input type="hidden" name="aid" id="aid" value="">
            <input type="hidden" name="ss" id="ss" value="">
            <input type="hidden" name="f" id="f" value="">
            <input type="hidden" name="dued" id="dued" value="">
            <div class="form-group files">
              <label class="col-mdd-4">Upload Your assignment</label><br>
              <div class="col-sdm-12">
                <input type="file" class="form-control" name="fileUpload" id="fileUpload" value="">
              </div>
            </div>                
            <br>
          <button type="submit" class="btn btn-primary uploadSubmission" title="Submit File">
              <i class="fa fa-upload" style="font-size:18px"></i>
          </button>
          <button type="button" class="btn btn-danger modalClose btndmarg-left" data-dismiss="modal">Close</button>
        </form>
          <?php } ?>                    
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal submit assignmtn -->



<div class="modal fade learnSubmissions" id="learnSubmissions">
  <form role="form" class="form-horizontal">
<div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header modal_head_color">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;&nbsp;<span class="xCloseBtn">×</span></button>
          <h4 class="modal-title modal-submission-title"><strong>Assignment Title:</strong> &nbsp;
            <span id="submit_assign_title"></span><br>
            <span class=><strong>Due Date:</strong>&nbsp;
              <span class="pull-rdight" id="submit_assignDue"></span>
            </span>
          </h4>
        </div>

        <div class="modal-body list">
          <div class="btn-group">
            <div class="input-group stylish-input-group col-md-6 col-xs-12">
            <input type="text" class="form-control" name="searchSubmissions" id="searchSubmissions" placeholder="Search learner" >
            <span class="input-group-addon">
              <button type="submit" id="searchLearner">
                <span class="glyphicon glyphicon-search"></span>
              </button>  
            </span>
          </div>
          </div>
          <div id="reset-msg"></div>
           <table class="table table-striped table-responsive">
            <thead id="tblHead">
              <tr>
                <th>
                  <span data-toggle="buttons">
                    <label class="btn btn-primary">
                     <input type="checkbox" id="select_all_assign"  />All
                     <span class="glyphicon glyphicon-check" title="Select All"></span>
                    </label>
                  </span>
                </th>
                <th>L#</th>
                <th>Name</th>
                <th class="hidden-xs">Submitted</th>
                <th class="hidden-xs">
                  <span class="glyphicon glyphicon-inbox" title="Download"></span>
                </th>
                <th>Reset</th>
                
              </tr>
            </thead>
            <tbody class="learnerSubmissions">
              <!-- content of learners -->
            </tbody>
            
          </table>
    </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" name= "download_assign" 
                    id="download_assign" value="">Download
          </button>
          <button type="button" class="btn btn-danger modalClose" data-dismiss="modal">Close</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </form>
  </div><!-- /.modal -->