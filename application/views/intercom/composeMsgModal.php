<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- /.modal compose message -->
    <div class="modal fade myReply" id="modalCompose" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header modal-header-info">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title"><span class="glyphicon glyphicon-envelope"></span> Compose Message</h4> 
            <div class="alert-msgw"></div>
          </div>
          <div class="modal-body">
            <form role="form" class="form-horizontal" id="msg_comp">
              <input type="hidden" value="" class='form-control' name="inboxID" id="inboxID">
              <input type="hidden" value="" class='form-control' name="receiverID" id="receiverID">
              <input type="hidden" value="" class='form-control' name="directMsgID" id="directMsgID">
              <input type="hidden" value="" class='form-control' id="replyID" name="replyID">
                <div class="form-group">
                  <label class="col-sm-2" for="to"><span class="glyphicon glyphicon-user"></span>To</label>
                  <div class="col-sm-10"><input type="email" class="form-control" name="compose_to" id="compose_to" value="" placeholder="To" data-role="tagsinput"></div>
                </div><span class="alert-userEmail" style="display: none;"></span>
                <div class="form-group">
                  <label class="col-sm-2" for="subjectMsgs"><span class="glyphicon glyphicon-list-alt"></span>Subject</label>
                  <div class="col-sm-10"><input type="text" class="form-control" name="subjectMsgs" id="compose_subjectMsgs" placeholder="subject"></div>
                </div>
                
               <button type="button" class="btn btn-primary glyphicon glyphicon-list-alt pull-right showing" data-toggle="modal" data-target="#contactList">
                    Contacts
                    </button>
         
                <div class="form-group">
                  <label class="col-sm-12" for="messagea"><span class="glyphicon glyphicon-list"></span>Message</label>
                    <br>
                  <div class="col-sm-12">
                    <textarea class="form-control" id="compose_messagea" name="messagea" rows="8" placeholder="Type message"></textarea>
                  </div>

                </div>
                <button type="button" class="btn btn-success" id="msgEmoji"><i class="fa fa-smile-o"></i></button><div class="pull-right msgsmiles" style="display: none;">
                    <?php echo $smiley_table; ?>
                    <?php echo smiley_js(); ?>
             </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left reset" data-dismiss="modal">Cancel</button><!--&nbsp;<button type="button" class="btn btn-warning pull-left">Save Draft</button>-->
            <button type="button" id="compose" class="btn btn-primary ">Send <i class="fa fa-arrow-circle-right fa-lg"></i></button>
            
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal compose message -->

    


