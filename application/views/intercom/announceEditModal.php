<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
 ?> 
 <!-- Modal -->
<div class="modal fade edtAnn" id="myModalEditAnn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="editingAnouncement"><b>Edit Announcement</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="alert-msgEditAnn"></div>
      <div class="modal-body">
        <input type="hidden"  value="" class='form-control' id="annIDe" name="annIDe">
          <input type="hidden"  value="" class='form-control' id="annDt" name="annDt">
        <div class="form-group">
      <label for="head">Title:</label><i class="glyphicon glyphicon-pencil pull-right"></i>
      <input type="text" value="" class="form-control" name="annTitled" id="annTitledEdit">
    </div>
    <div class="form-group">
      <label for="comment">Announcement Body:</label><i class="glyphicon glyphicon-pencil pull-right"></i>
      <textarea class="form-control" value="" rows="5" name="annBdy" id="annBdyEdit"></textarea>
    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary clickApplyAnn">Apply</button>
      </div>
    </div>
  </div>
</div>