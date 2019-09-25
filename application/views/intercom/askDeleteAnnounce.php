<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
 ?>
  
 <!-- Modal for confirm delete-->
    <div id="myModalAnnoun" class="modal in delAnnou" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Delete Announcement</h4>
          </div>
          <div class="modal-body">
            <p class="myP"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                   <input type="hidden" name="annID"  value="">
                    <button class="btn btn-success btn-md deleteYes" id="deleteAnnID" value="">YES</button>
                    &nbsp;<button title="No Delete" class="btn btn-danger btn-md deleteNo" data-dismiss="modal" value="Close">NO</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal success -->
<div id="ann-success" class="modal in" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Success!</h4>
          </div>
          <div class="modal-body">
            <p class="modal-ann"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-info btn-md successOK" data-dismiss="modal" value="OK">OK</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->