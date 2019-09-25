<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">                
  <ul class="list-group listBottom">
    <li class="list-group-item side-margins top-margins backgroundColour">
    
       <!-- Material sidebar -->
       <aside id="sidebar" class="sidebar side-margins open col-xs-12 col-md-3 hidden-sm hidden-xs " role="navigation">
        <!-- Sidebar header -->
        <div class="sidebar-header header-cover">
          <!-- Top bar -->
          <div class="top-bar"></div>
          <!-- Sidebar toggle button -->
          <!-- Sidebar brand image -->
          <div class="sidebar-image profile_pic"><!--Checks the user session and then displays the current logged in user profile picture-->
            <?= (isset($_SESSION['filePath'])) ? '<img src='.$_SESSION['filePath'].'>': '<span class="glyphicon glyphicon-user fa-5x" style=color:gray></span>';?>

            <!--  <img src="<?php //echo base_url(); ?>files/<?php //echo .$_SESSION['filePath'].'>'; ?>">-->
          </div>
          <!-- Sidebar brand name -->
          <a data-toggle="dropdown" class="sidebar-brand " href="#settings-dropdown">
            <?= (isset($_SESSION['username'])) ? $_SESSION['username'] : '<span class="glyphicon glyphicon-email fa-5x" style=color:white></span>';?> 
            <b class="caret"></b>
          </a>
        </div>

        <!-- Sidebar navigation -->
        <ul class="nav sidebar-nav">
          <li class="dropdown">
            <ul id="settings-dropdown" class="dropdown-menu">
             <!--  <li>
                <a href="#" tabindex="-1">Profile</a>
              </li>
              <li>
                <a href="#" tabindex="-1">Settings</a>
              </li>
              <li>
                <a href="#" tabindex="-1">Help</a>
              </li>
              <li>
                <a href="#" tabindex="-1">Exit</a>
              </li> -->
            </ul>
          </li>
          <li>
            <a  class="achors"  href="<?php echo base_url("Profile")?>"><span><i class="fa fa-user fa-2x"></i></span><b class="shift2Right">Profile</b></a>
          </li>
          <li>
            <a  class="achors" href="#"><span><i class="fa fa-users fa-2x"></i></span><b class="shift2RightUsers">Users&nbsp;:&nbsp;<?php echo $countUsers ?></b></a>
          </li>
          <li>
            <a  class="achors" href="<?php echo base_url("App/Logout")?>"><span><i class="fa fa-power-off fa-2x"></i></span><b class="shift2Right">Logout</b></a>
          </li>

        </aside>

      <div class=" hidden-sm hidden-xs">

        <div class="col-xs-6 col-sm-6 col-md-2">
          <a href="<?php echo base_url('admin/users')?>"  class="btn btn-sq-md btn-sq-lg  hov btnColours">
           <br><i class="fa fa-users fa-5x"></i><br/>
           <br>Manage <br>Users
         </a>

         <a href="<?php echo base_url('Users/addLearner')?>"  class="btn btn-sq-md  btn-sq-lg  hov btnColours">
          <br><img src="assets/images/learner-icon.png" class="adminIcons"><br/>
          <br>Add<br>Learner
        </a>

        <a href="<?php echo base_url('Teachers/teachers')?>"  class="btn btn-sq-md btn-sq-lg hov btnColours">
          <br><img src="assets/images/teaching-icon.png" class="adminIcons"><br/>
          <br>Teacher <br>List
        </a>
      </div>

      <div class="col-xs-6 col-sm-6 col-md-2" >
        <a href="<?php echo base_url('Users/add_new_teacher')?>" class="btn btn-sq-md btn-sq-lg  hov btnColours">
          <br><img src="assets/images/Teacher-Icon.png" class="adminIcons"><br/>
          <br>Add<br>Teacher
        </a>

        <a href="<?php echo base_url('Admin/manageAcademy')?>"  class="btn btn-sq-md btn-sq-lg hov btnColours">
          <br><i class="fa fa-graduation-cap fa-5x"></i><br/>
          <br>Manage<br>Academy
        </a>

        <a href="<?php echo base_url('Learners/learners')?>"   class="btn btn-sq-md btn-sq-lg  hov btnColours">
         <br><img src="assets/images/students.png" class="adminIcons"><br/>
         <br>Learner <br> List
       </a>

     </div>
     <div class="col-xs-6 col-sm-6 col-md-2"> 
      <a href="<?php echo base_url('admin/adminList')?>"  class="btn btn-sq-md btn-sq-lg hov btnColours"> 
        <br><img src="assets/images/admin.png" class="adminIcons"><br/>
        <br>Admin <br>List
      </a>
      <a href="<?php echo base_url('Users/add_new_admin')?>"  class="btn btn-sq-md btn-sq-lg  hov btnColours">
       <br><img src="assets/images/admin-icon.png" class="adminIcons"><br/>
       <br>Add <br>System Admin
     </a>

     <a href="<?php echo base_url('Guardians/guardians')?>"  class="btn btn-sq-md btn-sq-lg hov btnColours">
      <br><img src="assets/images/guardin-icon.png" class="adminIcons"><br/>
      <br>Guardian <br>List
    </a>

  </div>
</div>
<div class="col-sm-12 col-md-6 visible-sm visible-xs">
            <div class="panel-group" id="accordion">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-tasks">&nbsp;
                            </span>Menu</a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse ">
                        <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-user text-success"></span>&nbsp;&nbsp;<a href="<?php echo base_url('admin/users')?>">Manage Users</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-book text-success"></span>&nbsp;&nbsp;<a href="<?php echo base_url('Admin/manageAcademy')?>">Manage Academy</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-file text-success"></span>&nbsp;&nbsp;<a href="<?php echo base_url('Users/add_new_teacher')?>">Add Teacher</a>
                                    </td>
                                </tr>
                                 <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-file text-success"></span>&nbsp;&nbsp;<a href="<?php echo base_url('Users/addLearner')?>">Add Learner</a>
                                    </td>
                                </tr>
                                 <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-file text-success"></span>&nbsp;&nbsp;<a href="<?php echo base_url('Users/addLearner')?>">Add Guardian</a>
                                    </td>
                                </tr>
                                 <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-file text-success"></span>&nbsp;&nbsp;<a href="<?php echo base_url('Users/add_new_admin')?>">Add Admin</a>
                                    </td>
                                </tr>
                                 <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-list-alt text-success"></span>&nbsp;&nbsp;<a href="<?php echo base_url('Teachers/teachers')?>">Teacher List</a>
                                    </td>
                                </tr>
                                 <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-list-alt text-success"></span>&nbsp;&nbsp;<a href="<?php echo base_url('Learners/learners')?>">Learner List</a>
                                    </td>
                                </tr>
                                 <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-list-alt text-success"></span>&nbsp;&nbsp;<a href="<?php echo base_url('Guardians/guardians')?>">Guardian List</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-list-alt text-success"></span>&nbsp;&nbsp;<a href="<?php echo base_url('admin/adminList')?>">Admin List</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>





  <div class="panel1 pull-right col-md-6 col-sm-12 col-xs-12 buildTodo">
    <!--On page refresh-->
    <?php echo isset($todoList)? $todoList:'' ;?>
  </div>



        
          <div id="history" class="modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title text-center">Todo History</h4>
              </div>
              <div class="modal-body scroll">
                <input id="myInput" type="text" class="form-control" placeholder="Search..">       
                <div id="preview"></div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal-dialog 
        <div class="col-sm-12 visible-sm visible-xs">
            <div class="panel1_Mobile buildTodo" id="accordion">
             <?php //echo isset($todoList)? $todoList:'' ;?>   
           </div>
        </div>-->
<div class="clearfix"></div>
</li>

</ul>
</div>


