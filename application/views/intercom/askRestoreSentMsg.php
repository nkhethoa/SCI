<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
 ?>
  
 <!-- Modal for confirm delete-->
    <div id="myModalRstoreSent" class="modal in restoreOut" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Restore Sent Message</h4>
          </div>
          <div class="modal-body">
            <p class="mySt"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                   <input type="hidden" name="restrID"  value="">
                    <button class="btn btn-success btn-md restoreYes" id="restoreSentMsgID" value="">YES</button>
                    &nbsp;<button title="No Restore" class="btn btn-danger btn-md restoreNo" data-dismiss="modal" value="Close">NO</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal success -->
    <div id="sent-success" class="modal in" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Success!</h4>
          </div>
          <div class="modal-body">
            <p class="modal-sent"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-info btn-md successOK close_sent_restore" data-dismiss="modal" value="OK">OK</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->