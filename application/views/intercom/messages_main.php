<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
  <div class="dropdown visible-xs visible-sm">
    <button class="btn menu-color dropdown-toggle" type="button" data-toggle="dropdown">
      Select Tab
      <span class="caret"></span>
    </button>
    <!-- Nav tabs -->
     <div class="dropdown-menu">
    <ul class="nav nav-mobile" role="tablist">
      <li role="presentation" class="active"><a href="#msg1" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-book">&nbsp;</i><b>Inbox</b></a></li>
      
      <li role="presentation"><a href="#msg3" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-book">&nbsp;</i><b>Sent</b></a>
      </li>

    </ul>
  </div>
  </div>

  <div class="tab hidden-xs hidden-sm" role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#msg1" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-book"></i><b>Inbox</b></a></li>
      
      <li role="presentation"><a href="#msg3" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-book"></i><b>Sent</b></a></li>

    </ul>
  </div>
  
  <div class="tab-content">
    <!--create messages-->
    <div role="tabpanel" class="tab-pane fade in active" id="msg1">
      <?php $this->load->view('intercom/messages_inbox')?>
    </div>
    <!--recent messages-->
    <div role="tabpanel" class="tab-pane fade" id="msg3">
     <?php //$this->load->view('intercom/messages_sent')?>
   </div>
   <!--sent messages-->
   <div role="tabpanel" class="tab-pane fade" id="msg4">
    <?php ?>
  </div>
 </div>
 
 <script>$('#msg3').load("intercom/outBoxMsgPagination");</script>