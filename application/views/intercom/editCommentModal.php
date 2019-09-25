<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal fade editComm" id="myModalEdit" data-backdrop="static" data-keyboard="FALSE" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>        
        <h4 class="modal-title"></h4><span class="pull-right gtdate"></span>
        <div id="alert-msgEdit"></div> 
        </div>
        <form role="form" method="POST" enctype="multiform/formdata" class="form-horizontal formEditComm">
        <input type="hidden" value="" class='form-control' name="gtid" id="egtid">
        <input type="hidden" value="" class='form-control' name="gtcid" id="egtcid">
        <div class="modal-body">
          
          <div class="forppm-group">
          <label for="cm">Edit Comment</label>&nbsp;&nbsp;<i class="fa fa-pencil fa-2x" aria-hidden="true"></i>:
          <textarea class="form-control" id="ecm" name="cm" value="" rows="5" cols="5"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <div class="row">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-default clickEditComm">Save</button>&nbsp;&nbsp;
        </div>
      </form>
        </div>
      </div>
    </div>
  </div>
