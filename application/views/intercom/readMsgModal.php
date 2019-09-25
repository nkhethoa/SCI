<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>  
<!-- /.modal compose message -->
    <div class="modal fade" id="myModalRead" role="dialog">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <span><i class="glyphicon glyphicon-user"></i></span><h4 class="modal-title" name="receiver" id="receiver"></h4>&nbsp;&nbsp;<span class="pull-left" id="sentDate" name="sentDate" value="" class="pull-left"></span>
            <div id="alert-msg"></div>
          </div>
          <div class="modal-body">
            <form role="form" class="form-horizontal">
              <input type="hidden"  value="" class='form-control' name="outboxID" id="outboxID">
               
                <div class="form-group font-italic">
                  <label class="col-sm-2 " for="to"><i class="glyphicon glyphicon-envelope"></i>Email:</label>
                  <div class="col-sm-10">
                  <em><span name="to" id="to"></span></em>
                  </div>
                </div>
                <div class="form-group font-weight-bold">
                  <label class="col-sm-2" for="sentTitle"><i class="glyphicon glyphicon-bookmark"></i>Title:</label>
                  <span name="sentTitle" id="sentTitle"></span>
                </div>
                <div class="form-group">
                  <label class="col-sm-2" for="sentBody"><i class="glyphicon glyphicon-th-list"></i>Body:</label>
                  <span name="sentBody" id="sentBody"></span>
                </div>
            </form> 
          </div>
          <div class="modal-footer">
            
          <button type="button" class="btn btn-default close_read" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /-->



