 <?php
 defined('BASEPATH') OR exit('No direct script access allowed');

    //associative array to hold details of the main tabs of academy
    //*****************ARRAY GENERATING TABS ***************
    $tabs = array(
        "Assign"=>array("Assignment","glyphicon-tasks"),
        "Progress"=>array("Progress","glyphicon-stats"),
        "Attend"=>array("Attendance","glyphicon-saved"),
        "Discuss"=>array("Discussion","glyphicon-comment"),
        "Study"=>array("Study Material","glyphicon-folder-open"),
        "tt"=>array("TimeTable","glyphicon-time")
    );
 ?>
 <!--****************END ARRAY GENERATING TABS ******************-->
 <div class="container-fluid tab-container">
  <!--visible only on mobile-->
  <div class="dropdown visible-xs visible-sm menuBtn pull-left">
    <button class="btn btn-add dropdown-toggle" type="button" data-toggle="dropdown">
      <i class="glyphicon glyphicon-th-large" title="Main-Menu"></i> Main Menu <span class="caret"></span>
    </button>
    <div class="dropdown-menu mainTab-tab-menu ">
      <div class=" list-group-mobile">
        <?php 
         if (isset($tabs)) { 
          foreach ($tabs as $key => $tab) { 
            //hide discussion tab from the guardian
            if (((strpos(identify_user_role($_SESSION['userID']), 'guardian')!==false) && ($key !== "Discuss")) 
              OR ($choice === "ms")) { ?>
              <a href="#<?php echo $key ?>" 
                  id="<?php echo $key ?>" 
                  class="<?php echo ($key=="Assign")?'active':'' ?>">
                <h4 class="glyphicon <?php echo $tab[1] ?> "></h4>
                <?php echo $tab[0]; ?><br/>
              </a>            
          <?php }elseif($choice !== "mc" OR ($choice === "")) { ?>
            <a href="#<?php echo $key ?>" 
                id="<?php echo $key ?>" 
                class="<?php echo ($key=="Assign")?'active':'' ?>">
              <h4 class="glyphicon <?php echo $tab[1] ?> "></h4>
              <?php echo $tab[0]; ?><br/>
            </a>
          <?php }

        } ?>
      </div>
    </div>
  </div><!--/visible only on mobile-->
    <div class="col-lg-2 col-md-2 hidden-xs hidden-sm mainTab-tab-menu">
      <div class="list-group">
        <?php  
          foreach ($tabs as $key => $tab) { 
            //hide discussion tab from the guardian
          if (((strpos(identify_user_role($_SESSION['userID']), 'guardian')!==false) && ($key !== "Discuss")) 
            OR ($choice === "ms")) { ?>
              <a href="#<?php echo $key; ?>" 
                  id="<?php echo $key; ?>" 
                  class="list-group-item <?php echo ($key == "Assign")?'active':'' ?> text-center mainTab list_group-item">
                <h4 class="glyphicon <?php echo $tab[1] ?> fa-2x"></h4>
                <br/><?php echo $tab[0]; ?>
              </a>
            <?php 
          } elseif($choice !== "mc" OR ($choice === "") && (strpos(identify_user_role($_SESSION['userID']), 'guardian')!==false)) { ?>
            <a href="#<?php echo $key; ?>" 
                  id="<?php echo $key; ?>" 
                  class="list-group-item <?php echo ($key=="Assign")?'active':'' ?> text-center mainTab list_group-item">
              <h4 class="glyphicon <?php echo $tab[1] ?> fa-2x"></h4>
              <br/><?php echo $tab[0]; ?>
            </a>
            <?php 
          }
        }} ?>
      </div>
    </div>
    
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mainTab-tab">
      <!-- Assignmentst section-->
      <?php $this->load->view('academic/assignments'); ?>
      <!-- Progress section -->
      <?php $this->load->view('academic/progress'); ?>
      <!-- Attendance section -->
      <?php $this->load->view('academic/attendance'); ?>
      <!-- Subject Discussion section -->
      <?php
      if (((strpos(identify_user_role($_SESSION['userID']), 'guardian')!==false) && ($key !== "Discuss")) 
        OR ($choice === "ms")) {
        $this->load->view('academic/discussion');    
      }elseif($choice !== "mc" OR ($choice === "") && (strpos(identify_user_role($_SESSION['userID']), 'guardian')!==false)) {
        $this->load->view('academic/discussion'); 
      }
      ?>
      <!-- Subject study material section -->
      <?php $this->load->view('academic/studymaterial'); ?>
        <!-- Subject time table section-->
      <?php $this->load->view('academic/timetable'); ?>
    </div>
  </div>