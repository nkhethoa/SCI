<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//link the carousel 

$this->load->view('carousel/carousel.php');

?>
  <div class="container-fluid fluid-card">

  <?php
  $this->load->view('home/calender_section.php');
  $this->load->view('home/noticeboard.php');
  ?>

</div>