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
            <a href="#disc-<?php echo $tabID ; ?>" 
              data-clsid="<?php echo $subject->clsID;?>" 
              data-subname="<?php echo $subject->subjectName; ?>" 
              role="tab" class="discInnerTab" data-toggle="tab">
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
          $tabID= substr($child->lFName,0,4).'-'.$child->lLName
          ?>
          <li role="presentation" class="<?php echo ($index==1)? 'active':'' ?>">
            <a href="#disc-<?php echo $tabID ; ?>" 
            data-luid="<?php echo $child->lUserID;?>" 
            data-lfname="<?php echo $child->lFName;?>" 
            data-llname="<?php echo $child->lLName;?>" 
            role="tab" class="gDiscLearnInnerTab" data-toggle="tab">
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
            <a href="#disc-<?php echo $tabID ; ?>" 
              data-clsid="<?php echo $subject->clsID;?>" 
              data-subname="<?php echo $subject->subjectName; ?>" 
              role="tab" class="discInnerTab" data-toggle="tab">
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
            <a href="#disc-<?php echo $tabID ; ?>" 
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
        } //end foreach
        
      }//end if KIDS
        ?>

      </ul>
    </div>
    
    <div class="tab-content"> 
      <?php 
      $index=1;
      if ((isset($subjects)) && (strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false || identify_user_role($_SESSION['userID'])=='learner')) {
      foreach ($subjects as $subject) { 
        $cg= substr($subject->cgName,(strlen($subject->cgName))-1); 
        $tabID= substr($subject->subjectName,0,4).'-'.$subject->levelID.'-'.$cg
        ?> 
        <div role="tabpanel" class="tab-pane fade in <?php echo ($index==1)? 'active':'' ?>" id="disc-<?php echo $tabID ;?>">
          <div class="well well-sm text-center">
            <div class="discuss-alerts"></div>
            <h3 class="">
              <?php 
              if (strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false) { ?>
                <button 
                  type="button"
                  data-subname="<?php echo $subject->subjectName ;?>" 
                  value="<?php echo $subject->clsID ;?>" 
                  class="btn btn-success btn-md addDisc btn-float-right" 
                  data-toggle="modal" 
                  data-target="#discModal" 
                  data-placement="center">
                  <i class="glyphicon glyphicon-plus"></i>
                </button>
            <?php 
              }
            ?>
              Discussion Group Categories
            </h3>

          </div>
          <!--Table responsible for displaying the disc files per subject-->
          <div class="panel-group disc-<?php echo trim(str_replace(' ','',$subject->subjectName ));?>">
            <?php echo isset($discuss)? $discuss:'' ;?> 
          </div>
          <!--Table responsible for displaying the disc files per subject-->
        </div>

          <?php 
          $index++; 
        } 
      }//end if teacher or learner
      ?> 
    </div><!-- /tab content -->
  </div><!-- /main tab -->

  <!-- Modal start for discussion group -->
  <div id="discModal" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close modalClose" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Category</h4>
          <div class="disc-alert"></div>
        </div>
        <form action="" method="POST" class=frmDisc enctype="multipart/form-data">
          <!-- Modal body-->
          <div class="modal-body">
            <input type="hidden" name="cls" id="disc_cls" value="">
            <input type="hidden" name="subname" id="disc_subname" value="">
            <input type="hidden" name="discID" id="discID" value="">
            <div class="form-group">
              <label for="disc_title">Title</label>
              <input class="form-control" id="disc_title" name="disc_title" placeholder="Category Title " type="text" value="" />
            </div>
            <div class="form-group">
              <label for="disc_body">Description</label>
              <textarea class="form-control" id="disc_body" rows="4" style="width: 100%;" name="disc_body" placeholder="Category Description here" type="textarea" value=""></textarea>
            </div>
          </div>
          <!-- Modal footer-->
          <div class="modal-footer">
            <input class="btn btn-primary saveCategory" id="addCat" name="submit" type="submit" value="Add" />
            <input class="btn btn-danger modalClose" type="button" data-dismiss="modal" value="Close" />
          </div>
        </div>
      </form>
    </div>
  </div>

<!-- Modal start for topic -->
  <div id="topicModal" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close modalClose" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Topic</h4>
          <div class="topic-alert"></div>
        </div>
        <form action="" method="POST" class='frmTopic' enctype="multipart/form-data">
          <!-- Modal body-->
          <div class="modal-body">
            <input type="hidden" name="topic_ID" id="topic_ID" value="">
            <input type="hidden" name="topic_discID" id="topic_discID" value="">
            <div class="form-group">
              <label for="topic_title">Title</label>
              <input class="form-control" id="topic_title" name="topic_title" placeholder="Topic Title " type="text" value="" />
            </div>
            <div class="form-group">
              <label for="topic_body">Description</label>
              <textarea class="form-control" id="topic_body" rows="3" style="width: 100%;" name="topic_body" placeholder="Topic Description..." type="textarea" value=""></textarea>
            </div>
          </div>
          <!-- Modal footer-->
          <div class="modal-footer">
            <input class="btn btn-primary saveTopic" id="addTopic" name="submit" type="submit" value="Add" />
            <input class="btn btn-danger modalClose" type="button" data-dismiss="modal" value="Close" />
          </div>
        </div>
      </form>
    </div>
  </div><!-- /Modal for topic-->


<!-- Modal start for comments -->
  <div id="commentsModal" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close modalClose" data-dismiss="modal"><span class="xCloseBtn">&times;</span></button>
          <h4 class="modal-title">Topic Comments</h4>
          <div class="comment-alert"></div>
        </div>
             
        
          <!-- Modal body-->
          <div class="modal-body"> 
            <!-- display comments-->
            <div class="commentsDiv"></div>
              <!-- /display comments-->
            <div class="clearfix"></div> 
          <form action="" method="POST" class='frmComment' enctype="multipart/form-data">  
            <input type="hidden" name="replyToUserID" id="replyToUserID" value="">
            <input type="hidden" name="comment_creatorID" id="comment_creatorID" value="">
            <input type="hidden" name="commentReload" id="commentReload" value="">
            <input type="hidden" name="commentID" id="commentID" value="">
            <input type="hidden" name="comment_topicID" id="comment_topicID" value="">
            <div class="form-group">
              <label for="comments">Post your comment</label>
              <textarea class="form-control" id="comments" rows="4" name="comments" placeholder="Your comment..." type="textarea" value=""></textarea>
            </div>
          </div>
          <!-- Modal footer-->
          <div class="modal-footer">
            <input class="btn btn-primary saveComment" id="addComment" name="submit" type="submit" value="Send" />
            <input class="btn btn-danger modalClose" type="button" data-dismiss="modal" value="Close" />
          </div>
        </div>
      </form>
    </div>
  </div><!-- /Modal for comments-->

 <!-- Modal for confirm delete topic-->
  <div id="topic-confirm" class="modal in none" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <p class="myP"></p><!--display user message here-->
          <div class="row">
            <div class="col-xs-12 text-center">
              <input type="hidden" name="del_topic_discID" id="del_topic_discID" value="">
              <button class="btn btn-success btn-md deleteTopic-Yes" id="deleteTopicID" value="">Yes</button>
              <button class="btn btn-danger btn-md deleteTopic-No modalClose" data-dismiss="modal" value="Close">No</button>
           </div>
         </div>
       </div>
       
     </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
 </div><!-- /.modal -->

  <!-- Modal for confirm delete-->
  <div id="disc-confirm" class="modal in none" data-backdrop="static" data-keyboard="false"">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <p class="myP"></p><!--display user message here-->
          <div class="row">
            <input type="hidden" name="cls" id="del_disc_cls" value="">
            <input type="hidden" name="subname" id="del_disc_subname" value="">
            <div class="col-12-xs text-center">
             <button class="btn btn-success btn-md deleteDisc-Yes" id="deleteDiscID" value="">Yes</button>
             <button class="btn btn-danger btn-md deleteDisc-No modalClose" data-dismiss="modal" value="Close">No</button>
           </div>
         </div>
       </div>
       
     </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
 </div><!-- /.modal -->

