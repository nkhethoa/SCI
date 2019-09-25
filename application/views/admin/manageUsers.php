<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<input type="hidden" name="id_user" value="<?php echo isset($id_user)? $id_user:0 ;?>" placeholder="">
<div class="text-center container-fluid">
 <div class="panel-success platform">
  <?php if(isset($statusEdit)){
    echo alertMsgs($statusEdit,'User Updated','User Not Updated');
  }?></span><?php

  if(isset($statusInsert)){
    echo alertMsgs($statusInsert,'User Inserted','User Not Inserted');
  }
  if(isset($statusRemoved)){
    echo alertMsgs($statusRemoved,'User Deleted','User Not Inserted');
  }

  ?>
  <div class="panel-heading ">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-3">
        <h2 class="text-center pull-left mTitle"><span><i class="fa fa-users"></i></span>&nbsp;Manage Users</h2>
        <li class=" nav-item libackground visible-xs visible-sm">
          <button type="button" href="#" class="nav-item dropdown-toggle btn text-center btn btn-md btn-btnColors pull-right pull_down" data-toggle="dropdown">
           More..&nbsp;<span class="caret"></span>
         </button>
         <ul class="dropdown-menu" role="menu">
           <li class="dropdown-plus-title">
            &nbsp;Choose below
            <b class="pull-right glyphicon glyphicon-chevron-up"></b>
          </li>
          <li><a href="<?php echo base_url('Users/activate_deleted_user')?>"><span><i class="fa fa-recycle"></i></span>&nbsp;Re-activate Users</a></li>
          <li><!--   --></li>
          <!-- <li class="divider"></li> -->
        </ul>
      </li>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 mini-jambo ">
      <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="col-xs-12 col-md-4">
         <form action="users" method="get">
          <div class="form-group">
            <div class="input-group">
              <input type="text" class="form-control input-md" id="search" name="search">
              <div class="input-group-btn">
                <button type="submit" class="btn btn-md  bigSize btn-btnColors"> <i class="fa fa-search" aria-hidden="true"></i></button>
              </div>
            </div>

          </div>
        </form>

      </div>
    </div> <div class="error_msgs pull-right">
             <?php /*if(isset($statusRemoved)){
          echo alertMsg($statusRemoved,'User Deleted','User Not Deleted');
        }*//*--Displays success and error messages*/?>
        <span class="alert-succes">
        </div>
        <div class="input-group-btn">
          <a type="button" href="<?php echo base_url('Users/activate_deleted_user')?>" class="btn btn-md btn-btnColors btn-recycle"><i class="fa fa-recycle" aria-hidden="true"></i>&nbsp;Re-activate</a>
        </div>
        <div class="dropdown input-group-btn pull-right  btn-drop">
          <button class="btn  dropdown-toggle btn-btnColors" type="button" data-toggle="dropdown">
            <i class="fa fa-user-plus fa-1x"></i><span class="caret"></span>
          </button>
          <div class="dropdown-menu dropdownPadding">
            <a href="<?php echo base_url('Users/addLearner')?>" class="btn btn-btnColors">Learner</a>
            <a href="<?php echo base_url('Users/add_new_teacher')?>" class="btn btn-btnColors">Teacher</a>
            <a href="<?php echo base_url('Users/addLearner')?>" class="btn btn-btnColors">Guardian</a>
            <a href="<?php echo base_url('Users/add_new_admin')?>" class="btn btn-btnColors">Admin</a>
          </div>
        </div>


        <div class="col-md-12 col-lg-12">  
         <a role="button"  href="<?php echo base_url('admin')?>" class="btn btn-progress1 pull-right hidden-sm hidden-xs" title="Go Back">
          <i class="fa fa-step-backward"></i>
        </a>
      </div>
    </div>
  </div>
</div>
<div class="panel-body table-responsive">
  <table class="table table-striped table-hover table-responsive">
    <thead>
      <tr>
              <!--<th class="text-center"> Id </th>
              <th class="text-center"> First Name </th>
              <th class="text-center"> Middle Name</th>-->
              <th class="text-center hidden-sm hidden-xs">Pictures</th>
              <th class="text-center"> Full Name </th>
              <th class="text-center hidden-sm hidden-xs"> Phone </th> 
              <th class="text-center hidden-sm hidden-xs"> Username</th>
              <th class="text-center hidden-sm hidden-xs"> Address</th> 
              <th class="text-center"> Action</th>
            </tr>
          </thead>
          <tbody><!--Displays users from the database in a table-->
            <?php
            foreach($db as $user){ 

              ?>

              <tr class="edit" id="detail">
              <!--<td id="no" class="text-center"><?php echo $user->userID?></td>
              <td id="name" class="text-center"><?php echo $user->fName ?></td>
              <td id="namef" class="text-center"><?php echo $user->midName?></td>-->
              <td class="text-center hidden-sm hidden-xs"><img src="<?php echo base_url($user->filePath)?>" class="userImg"></td>
              <td id="namle" class="text-center"><?php echo $user->fName.' '.$user->lName?></td>
              <td id="mobile" class="text-center hidden-sm hidden-xs"><?php echo $user->phone?></td> 
              <td id="mail" class="text-center hidden-sm hidden-xs"><?php echo $user->email?></td>
              <td id="ciity" class="text-center hidden-sm hidden-xs"><?php echo $user->address?></td> 
              <td id="city" class="text-center"> 
                <a href="<?php echo base_url('Users/edit_user/'.$user->userID)?> " class="btn btn-default btn-xs" title="edit"
                  ><i class="fa fa-edit" aria-hidden="true"></i></a>
                  <a href="#"
                   class="btn btn-default btn-xs askDeleteUser"
                   data-username="<?=$user->fName.' '.$user->lName?>"
                   data-usernameid="<?=$user->userID?>"
                    title="delete"
                    ><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                  </td>
                </tr>
                <?php
              }
              ?><!--Counts users from the database-->
              <tr><td colspan="6">Total:<?php echo $countUsers ?></td></tr>
            </tbody>
          </table>
          <br>
          <div class="text-center" >
            <!--pagination-->
            <?php
            echo $search_pagination;
            ?>
          </div>
        </div>

        <div class="panel-footer">
          <div class="row">
            <div class="col-lg-12">
              <div class="col-md-8">
              </div>
              <div class="col-md-4">

              </div>
            </div>
          </div>
        </div>
      </div>





      <!-- modal for success message start -->
<div id="askDeleteUser" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button data-username="<?=$user->fName.' '.$user->lName?>"  data-usernameid="<?=$user->userID?>" class="btn btn-success btn-md askDeleteUser-yes modalClose" value="">Yes</button>
                    <button class="btn btn-danger btn-md askDeleteUser-no modalClose" value="No">No</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- modal for success message start -->
<div id="askDeleteUser-success" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-info btn-md askDeleteUser-ok modalClose" value="">Ok</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->