<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
 ?>
  
 <!-- Modal for confirm delete-->
    <div id="FAQDelModal" class="modal in delFquestion" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Delete FAQs</h4>
          </div>
          <div class="modal-body">
            <p class="myMQ"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                   <input type="hidden" name="fquestionID"  value="">
                    <button class="btn btn-success btn-md deleteYes" id="deleteFAQID" value="">YES</button>
                    &nbsp;<button title="No Delete" class="btn btn-danger btn-md deleteNo" data-dismiss="modal" value="Close">NO</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

 <!-- Modal success -->
<div id="faq-success" class="modal in" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Success!</h4>
          </div>
          <div class="modal-body">
            <p class="modal-faq"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-info btn-md successOK close_faq" data-dismiss="modal" value="OK">OK</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->