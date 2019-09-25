<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
    <a  href="#" class="dropdown-toggle" data-toggle="dropdown">
     <?php echo (isset($_SESSION["username"])) ? $_SESSION["username"]:''; ?>&nbsp;<span class="caret"></span>
   </a>
   <ul class="dropdown-menu">
     <li class="dropdown-plus-title">
      &nbsp;Select
      <b class="pull-right glyphicon glyphicon-chevron-up"></b>
    </li>
    <li class="message">
      <a href="<?php echo base_url("Profile")?>"><?= (isset($_SESSION['filePath']) && file_exists($_SESSION['filePath'])) ? '<img src="'.base_url($_SESSION['filePath']).'">':'<span class="glyphicon glyphicon-user"></span>';?>
        &nbsp;Profile
        
      </a>
    </li>
    <li>
      <a href="<?php echo base_url('App/Logout')?>">&nbsp;&nbsp;<span class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;Logout</a>
    </li>
    <!-- <li class="divider"></li> -->
            