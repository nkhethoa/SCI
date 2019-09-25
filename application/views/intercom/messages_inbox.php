<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php
//var_dump($messages);
if(isset($statusRemoved)){
  echo alertMsgs($statusRemoved,'Message Deleted');
}
if(isset($statusEdit)){
  echo alertMsgs($statusEdit,'Message Edited');
}
?>
<div class="alert-msgPage"></div>
<div class="alert-msgrepl"></div>
<div class="msg-successPage"></div>

<form action="" method="POST" enctype="multipart/form-data">
<div class="inbox-head">
  <h3><b>Inbox</b></h3>&nbsp;
</div>
<br>
<input type="text" class="form-control pull-right nav-search" name="srchMgs" value="" id="msgMainViewSearch" placeholder="Search message"><br><br><br>
      
      <span data-toggle="buttons">
        <label class="btn btn-info">
        <input type="checkbox" id="select_all"  />All
        <span class="glyphicon glyphicon-check" title="Select All"></span>
        </label>
      </span>
      <a href="#" name="btn_delete" class="btn btn-warning" id="delSel">Delete Marked</a>
</form>
<br>
<div class="modal-msg"></div>
<div id="msg-success"></div>
 
<!-- Modal -->
<p><?php echo form_error('to') ? alertMsgs(FALSE,'',form_error('to')) : '' ;?></p>
<?php
    $this->load->view('intercom/composeMsgModal');
    ?>
    <?php
    $this->load->view('intercom/inboxMsg');
    ?>
  <!-- Modal -->
    <?php
    $this->load->view('intercom/askDeleteMsg');
    ?>
     
    <?php
    $this->load->view('intercom/replyMsgModal');
    ?>
    <?php
    $this->load->view('intercom/phoneBook');
    ?>
    <br>
  </table>
  <div class="text-center">
  <h4>Messages Inbox:&nbsp;&nbsp;<?php echo ($msgCount>0)? $msgCount:"<span style='color:beige'>No Results</span>";?></h4>
  <div id="pagination_link">
    <?php 
    echo $msg_links;
    ?>
  </div>

  </div>