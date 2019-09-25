<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<p><?php echo form_error('to') ? alertMsgs(FALSE,'',form_error('to')) : '' ;?></p>
<!-- /.modal compose message -->
    <div class="modal fade composeModal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Reply Message</h4>
            <div id="alert-msgreply"></div>
          </div>
          <div class="modal-body">            
            <form role="form" class="form-horizontal">
              <input type="hidden"  value="" class='form-control' name="inboxID_reply" id="inboxID_reply">
              <input type="hidden"  value="" class='form-control' name="receiverIDReply" id="receiverIDReply">
              <input type="hidden"  value="" class='form-control' name="directMsgID_reply" id="directMsgID_reply">
             
              <input type="hidden"  value=""  class='form-control' id="replyID_reply" name="replyID_reply">
              <span class="glyphicons glyphicons-address-book"></span>
                <div class="form-group">
                  <label class="col-sm-2" for="to">To</label>
                  <div class="col-sm-10">
                    <input type="email" class="form-control" name="rto" id="reply_to" placeholder="To"></div>

                </div> <span class="alert-userrEmail" style="display: none;"></span>
                <p><?php echo form_error('subjectMsg') ? alertMsgs(FALSE,'',form_error('subjectMsg')) : '' ;?></p>
                <div class="form-group">
                  <label class="col-sm-2" for="subjectMsg">Subject</label>
                  <div class="col-sm-10"><input type="text" class="form-control" name="rsubjectMsgs" id="reply_subjectMsgs" placeholder="subject"></div>
                </div>
                <p><?php echo form_error('messagea') ? alertMsgs(FALSE,'',form_error('messagea')) : '' ;?></p>
                <div class="form-group">
                  <label class="col-sm-12" for="messagea">Message</label>
                  <div class="col-sm-12"><textarea class="form-control" id="reply_messagea" placeholder="Enter your meassage" name="rmessagea" rows="18"></textarea></div>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button> 
            
            <button type="button" id="reply_msg" data-dismiss="modal" class="btn btn-primary ">Send <i class="fa fa-arrow-circle-right fa-lg"></i></button>
            
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal compose message -->



