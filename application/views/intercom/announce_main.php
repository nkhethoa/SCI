 <?php
 defined('BASEPATH') OR exit('No direct script access allowed');
 ?>

  <div class="tab" role="tabpanel">
    
  </div>
  <div class="tab-content">
    <!--create announcement-->
    
    <!--recent announcement-->
    <div role="tabpanel" class="tab-pane fade in active" id="Ann2">
     <?php $this->load->view('intercom/announce_recent'); ?>
     <?php $this->load->view('intercom/announceEditModal'); ?>
   </div>
 </div>