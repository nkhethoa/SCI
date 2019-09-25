<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?> 

  <?php
  $action = isset($inboxID)?'Intercom/replyMsgInbox?editSms=$inboxID':'Intercom/composeMsg';
  echo form_open($action,array('class'=>'form-horizontal'))
  ?>
  <!-- Name input-->
  <p><?php echo form_error('to') ? alertMsgs(FALSE,'',form_error('to')) : '' ;?></p>
  <div class="form-group">
    <label class="col-md-2 control-label" for="name">To</label>
    <div class="col-md-9">
      <input id="to" value="" name="to" type="text" placeholder="Your email" class="form-control">
    </div>
  </div>
  
  <!-- Subject input-->
  <p><?php echo form_error('title') ? alertMsgs(FALSE,'',form_error('title')) : '' ;?></p>
  <div class="form-group">
    <label class="col-md-2 control-label" for="title">Subject</label>
    <div class="col-md-9">
      <input id="title" value="" name="title" type="text" placeholder="Subject" class="form-control">
    </div>
  </div>
 
  <!-- Message body -->
  <p><?php echo form_error('message') ? alertMsgs(FALSE,'',form_error('message')) : '' ;?></p>
  <div class="form-group">
    <label class="col-md-2 control-label" for="message">Your message</label>
    <div class="col-md-9">
      <textarea class="form-control" id="myEditor" name="message" placeholder="Please enter your message here..." rows="4"></textarea>
    </div>
  </div>

  <!-- Form actions -->
  <div class="form-group" style="padding-left:160px;">
    <div class="col-md-3 text-right">
      <button type="button" id="compose" class="btn btn-default">Submit</button>&nbsp;
    </div>
  </div>
  <?php
  echo form_close();
  ?>
