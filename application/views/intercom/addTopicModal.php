<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<form method="POST" accept-charset="utf-8" class="form-horizontal">
  <!-- Modal -->
  <div class="modal fade" id="myModal3" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Topic</h4>
            <div class="ale-msgTopic"></div>
            
            <div class="modal-body">         
          </div>
          <div class="modal-body">
          <div class="form-group">
            <label for="topicTitle" class="form-control-label">Title:</label>
            <input type="text" class="form-control" name="topicTitle" id="topicTitle"></input>
            
          </div>
          <div class="form-group">
            <label for="topicDescription" class="form-control-label">Message:</label>
            <textarea class="form-control" rows="10" cols="15" name="topicDescription" id="topicDescription"></textarea>
          </div>
      </div>
        <br>
        <div class="modal-footer text-center">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>&nbsp;&nbsp;&nbsp;<button type="button" id="topic" class="btn btn-primary">Submit</button>
        </div>
        <br>
      </div>
    </div>
  </div>
</div>
</form>