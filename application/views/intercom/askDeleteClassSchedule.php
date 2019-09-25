<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Modal for confirm delete-->
    <div id="myTblTimeModal" class="modal in delSchCl" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Delete Class Schedule</h4>
          </div>
          <div class="modal-body">
            <p class="myP"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                   <input type="hidden" name="annID"  value="">
                    <button class="btn btn-success btn-md deleteYes" id="deleteClsSchedID" value="">YES</button>
                    &nbsp;<button title="No Delete" class="btn btn-danger btn-md deleteNo" data-dismiss="modal" value="Close">NO</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

 <!-- Modal success -->
<div id="tblT-success" class="modal in delSchedule" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Success!</h4>
          </div>
          <div class="modal-body">
            <p class="modal-tblT"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-info btn-md successOK close_sche" data-dismiss="modal" value="OK">OK</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->