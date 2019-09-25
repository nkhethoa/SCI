<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal fade editDiscuss" id="myModalEditDisc" data-backdrop="static" data-keyboard="FALSE" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>        
        <h4 class="modal-titlee"></h4><span class="pull-right gtTdate"></span>
        <div class="alert-msgEditx"></div>
        </div>
        <form role="form" method="POST" enctype="multiform/formdata" class="formEditComm">
        <input type="hidden" value="" class='form-control' name="topicCreatorID" id="topicCreatorID">
        <input type="hidden" value="" class='form-control' name="editDiscHiddnID" id="editDiscHiddnFiel">        
        <div class="modal-body">
           <div class="form-group text-warning">
           <span class="date"><span class="fa fa-paper-clip"></span></span>
        </div>
          <div class="form-group">
          <label for="editT">Edit Title</label>&nbsp;&nbsp;<i class="fa fa-pencil fa-2x" aria-hidden="true"></i>:
          <input class="form-control" id="editT" name="edtitle" value="" rows="5" cols="5">
          </div>

          <div class="form-group">
          <label for="editDis">Edit Description</label>&nbsp;&nbsp;<i class="fa fa-pencil fa-2x" aria-hidden="true"></i>:
          <textarea class="form-control" name="descrDis" id="editDis" value="" rows="5" cols="5"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <div class="row">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-default" id="clickEditDiscuss" data-dismiss="modal">Save</button>&nbsp;&nbsp;
        </div>
      </form>
        </div>
      </div>
      
    </div>
  </div>