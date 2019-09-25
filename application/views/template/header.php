<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('template/jumbotron.php');
?>
<nav class="navbar navbar-inverse">
  <div class="contdainer-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#"></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="nav-item" <?php echo setMenuActiveItem($pageActive == "home") ?> >
          <a href="<?php echo base_url('App')?>">Home
          </a>
        </li>
        <li class="nav-item refresh_notify" <?php echo setMenuActiveItem($pageActive == "intercom") ?> >
          <?php 
            //get all the messages
            $data['messages']= getMessageNotification();
            //get all announcements
            $data['announces'] = getAnnouncesNotification();
            //load the view which displays notifications
            $this->load->view('notifications/notifications',$data);
           ?>
        </li>

        <?php
        if((identify_user_role($_SESSION['userID']) == 'learner') || (identify_user_role($_SESSION['userID']) == 'teacher') 
          || (identify_user_role($_SESSION['userID']) == 'guardian') || (identify_user_role($_SESSION['userID']) == 'guardian_admin')
          ) { ?>   
        <li class="nav-item" <?php echo setMenuActiveItem($pageActive=="academic") ?> >
          <a  href="<?php echo base_url('Academy')?>">Academic
          </a>
        </li>
          <?php
        }
        if(strpos(identify_user_role($_SESSION['userID']), 'teacher_guardian') !== false){ ?>
          <li class="nav-item" <?php echo setMenuActiveItem($pageActive == "academic") ?> >
            <a href="#Academic" class="dropdown-toggle" data-toggle="dropdown">
              Academic&nbsp;<span class="caret"></span>
            </a>
              <ul class="dropdown-menu">
                <li class="dropdown-plus-title">
                    &nbsp;Options
                    <b class="pull-right glyphicon glyphicon-chevron-up"></b>
                </li>
                <li class="nav-item" <?php echo setMenuActiveItem($pageActive == "academic") ?> >
                  <a href="<?php echo base_url("Academy?c=ms")?>">My Subjects</a>
                </li>
                <li class="nav-item" <?php echo setMenuActiveItem($pageActive == "academic") ?> >
                  <a href="<?php echo base_url("Academy?c=mc")?>">My Children</a>
                </li>
                <!-- <li class="divider"></li> -->
              </ul>
          </li>
          <?php
        } 
        if(strpos(identify_user_role($_SESSION['userID']), 'admin') !== false){ ?>
          <li class="nav-item" <?php echo setMenuActiveItem($pageActive == "administration") ?> >
            <a  href="<?php echo base_url("admin")?>">Administration
            </a>  
          </li>
          <?php 
        } 
        if(strpos(identify_user_role($_SESSION['userID']), 'teacher') !== false && (strpos(identify_user_role($_SESSION['userID']), 'admin') === false)){ ?>
          <li class="nav-item" <?php echo setMenuActiveItem($pageActive == "guardianList") ?> >
            <a  href="<?php echo base_url("Guardians/guardians")?>">Guardians</a>
          </li>
          <li class="nav-item" <?php echo setMenuActiveItem($pageActive == "learnerList") ?> >
            <a  href="<?php echo base_url("Learners/learners")?>">Learners</a>
          </li>
          <?php 
        }
        if(strpos(identify_user_role($_SESSION['userID']), 'admin') === false) 
        { ?>
          <li class="nav-item" <?php echo setMenuActiveItem($pageActive == "teacherList") ?> >
            <a  href="<?php echo base_url("Teachers/teachers")?>">Teachers
            </a>
          </li>
          <?php
          }
        ?>
      </ul>
       
      <ul class="nav navbar-nav navbar-right">
      
      <?php
      //display on full screen but not on admin page
      if($this->uri->segment(1,0) != 'admin') { ?>
        <li class="nav-item libackground">
          <?php $this->load->view('template/nav-login'); ?> 
        </li>
        <?php
      }
       //display this when on mobile mode
      if($this->uri->segment(1,0) == 'admin') { ?>
        <li class="nav-item libackground visible-sm visible-xs">
          <?php $this->load->view('template/nav-login'); ?> 
        </li>
        <?php
      }
      ?>
              
      </ul>
    </div>
  </div>
</nav>