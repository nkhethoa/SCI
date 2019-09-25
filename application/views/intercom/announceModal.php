<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
 ?>

  <form method="POST" accept-charset="utf-8" class="form-horizontal">
  <!-- Modal -->
  <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Announcement</h4>
            <div class="ale-AnnPage"></div>
            <div id="ale-msgAnn"></div>
        <div class="modal-body">         
          </div>
          <div class="modal-body">
          <div class="form-group">
            <label for="annTitle" class="form-control-label">Title:</label>
            <input type="text" class="form-control" value="" name="annTitled" id="annTitled" placeholder="Your Title"></input>
            
          </div>
          <div class="form-group">
            <label for="body" class="form-control-label">Message:</label>
            <textarea class="form-control" value="" name="annBdy" rows="10" cols="15" id="annBdy" placeholder="Please enter your announcement here..."></textarea>
          </div>
      </div>
        <br>
        <div class="modal-footer text-center">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>&nbsp;&nbsp;&nbsp;<button type="button" id="announce" class="btn btn-primary">Submit</button>
        </div>
        <br>
      </div>
    </div>
  </div>
</div>
</form>