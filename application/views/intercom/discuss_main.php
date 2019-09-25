<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$filters = array('');
?> 

<div class="tab" role="tabpanel">
  
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active" id="tab1"><a href="#disc1" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-book"></i><b>Topic List</b></a></li>
      
      <li role="presentation" style="display: none;" id="tab2"><a href="#disc2" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-book" ></i><b>Posts</b></a></li>

    </ul>
  </div> 
  <div class="tab-content">
    <!--create messages-->
    <div role="tabpanel" class="tab-pane fade in active" id="disc1">
      <?php
        $this->load->view('intercom/topic_list');
      ?>
    </div>
    <!--recent messages-->
    <div role="tabpanel" class="tab-pane fade" id="disc2">
      <h3></h3>
     <div class="form-group">
      <!---Modal for commenting-->
      <!--END-->
    <label for="filter"><b>Select Topic</b></label>
    
    <select class="form-control" name="topicName" id="filters">
      <?php 
        if(isset($selectTopics)){
        foreach ($selectTopics as $comment){ ?>
          <option <?php
            if(isset($gtID)){
           echo ($comment->gtTitle) ? 'selected' : '';
         }else{
            if(set_value('filters')){
              echo (set_value('filters') == $comment->gtID) ? 'selected' : '';
            }
          }
            ?>
            value="<?php echo $comment->gtID ?>"><?php
            echo $comment->gtTitle; ?></option>
        <?php
        } }
        ?>
    </select>
    <br>
    <div class="ale-msgcomdelno"></div>
    <div id="alert-msgLike"></div>
    <div class="alert-msgEditx"></div>
    <div class="alert-subComm"></div>
    <div class='alert-subErrComm'></div>
    <br>
    <div class="modal-commdel"></div>
    <div id="comm-success"></div>
    <form role="form" id="comm_comp">
     <div class="form-group">
      <div class="commentarea">
      <label for="comment"><b>Question/Comment:</b></label>
       <input type="hidden" value="" class="form form-control" name="gtide" id="gtide">
      <textarea class="form-control" value="" cols="5" rows="10" name="cm" id="cm"></textarea>
      <br>
      <button type="button" class="btn btn-info subComm">Comment</button>&nbsp;<button type="button" class="btn btn-success" id="emoji"><i class="fa fa-smile-o"></i></button> <div class="pull-right smiles" style="display: none;"> 
      <?php echo $smiley_table; ?>
      <?php echo smiley_js(); ?>
    </div>
    <br>
    </div>
   </div>
   <br>
   <div id="alert-msgEdit"></div>
 </form>
       <br>
      <div class="form-group" value="" id="ajaxMsg"  name="ajaxMsg">
      </div>
    <br>
    <br>
   </div>
   
 </div>
</div>
<?php
      $this->load->view('intercom/askDeleteComment');
 ?>
 <?php
      $this->load->view('intercom/editCommentModal');
 ?>
 <?php
      $this->load->view('intercom/askDeleteDiscussion');
 ?>
 <?php
      $this->load->view('intercom/editDiscussModal');
 ?>














