<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="alert-topicPage"></div>
<div class="alert-msgEdit"></div>

<?php
              //if($_SESSION['userWho'] == 'admin'){?>
              <!--<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal3">Create&nbsp;<i class="glyphicon glyphicon-comment"  ></i></button>-->

              <button type="button" class="btn btn-primary avt" data-toggle="modal" data-target="#myModal3"><i class="glyphicon glyphicon-plus"></i>&nbsp;Start New Topic</button>
              <?php
//} ?>
<?php $this->load->view('intercom/addTopicModal') ?>

<h1 class="text-center"><b>Discussion</b></h1>
<br>
<div class="alert-topicedtsucc"></div>
<div class="alert-topicerr"></div>
<div class="alert-topicPagex"></div>
<div class="ale-msgTopicx"></div>
<br>
<?php
$action = 'Intercom/manageComment';
echo form_open($action,array('class'=>'form-horizdontal'));
?>
<input type="text" class="form-control nav-search" name="tpcSearch" value="" id="discusMainViewSearch" placeholder="Search Topic"><br>
<?php echo form_close(); ?>
<div id="alert-topicPage"></div>
<div class="modal-discuss"></div>
<div id="discus-success"></div>
  <!-- main -->
  <div class="forufm-main">
    <div class="col">
      <div class="category-header row row-md-center bgColor">
        <div class="col-md-6 title"><b>Discussion</b></div>
        <div class="col-md-1"><i class="glyphicon glyphicon-user"></i></div>
        <div class="col-md-1"><i class="glyphicon glyphicon-comment"></i></div>
        <div class="col-md-2 hidden-xs-down"><b>Posted</b></div>
        <div class="col-md-2 hidden-xs-down pull-right"><b>Actions</b></div>
      </div>
      <?php
      //var_dump($topics);
      if(isset($topics) && ($topics != FALSE)){
        foreach ($topics as $topic) { ?>
      <!--create a div here-->
      <div class="forum-list rsow colours topicResults">
        <div class="row forum-list-spacer">
          <div class="col-md-1"><i class="fa fa-comments fa-2x icons"></i></div>
          <div class="col-md-5 d-flex flex-column forum-title">
            <div class="title "><a href="#" class="topicBtn" id="<?php echo $topic->gtID; ?>"><b><?php echo $topic->gtTitle; ?></b></a></div>
            <div class="descritiption "><em><?php echo $topic->gtDescr; ?></em></div>
          </div>
          <?php
          if(isset($ct_table) && ($ct_table != NULL)){
            $totalComm = 0;
            $totalUser = 0;
            foreach ($ct_table as $comment) {
              if($topic->gtID == $comment->gtID){
                $totalComm += $comment->userCount;
                $totalUser += 1;
              }
            }
            ?>
          <div class="col-md-1 topics "><?php echo $totalComm; ?></div>
          <div class="col-md-1 posts "><?php echo $totalUser; ?></div>
          
           <div class="d-flex flex-row forum-last-post col-md-4">
            <div class="info d-flex flex-column forum-title hidden-xs-down">
              <div class="title "><strong><u><b>Posted By</b></u></strong>
                <div class="pull-right">
                <a href="#" type="button" id="editingDiscuss" data-toggle="modal" data-target="#myModalEditDisc" data-discr="<?php echo $topic->gtDescr?>" data-gtname="<?php echo $topic->gtTitle ?>" data-creat="<?php echo $topic->topicCreator ?>" data-gtident="<?php echo $topic->gtID ?>" data-time="<?php echo $topic->topicDate ?>"><i class="glyphicon glyphicon-pencil"></i></a>

                <a href="#" data-discussID="<?php echo $topic->gtID; ?>" type="button" class="btn btn delDiscussion" name="trashingT" title="deleteT" data-toggle="modal" data-target="#myModalDiscussion"><i class="glyphicon glyphicon-trash" style="color:red;"></i></a>
              </div>
            </div>
              <div class="author "><?php echo $topic->creatorName.' '.$topic->creatorSurname; ?></div>
              <div class="time "><?php echo $topic->topicDate; ?></div>
            </div>
          </div>
          <?php
          } ?>
        </div>
      </div>
      <?php
      }
    } ?>
       </div>
  </div>
  <br>
  <div class="text-center" style="clear:both;">
  <?php
  //echo $topicCount;
  ?><?php
    echo $topic_links;
    ?>
  </div>
  <br>
  <br>
  