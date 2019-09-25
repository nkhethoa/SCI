<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
  <input type="hidden" name="id_user" value="<?php echo isset($id_user)? $id_user:0 ;?>" placeholder="">
  <div class="text-center container-fluid">
   <div class="panel-success platform">
    <div class="panel-heading">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-3">
          <h2 class="text-center pull-left mTitle"> <span><img src="<?php echo base_url('assets/images/refresh.png')?>" class="mov"></span></span>&nbsp;Reactivate Users</h2>
        </div>
        <div class="col-xs-9 col-sm-9 col-md-9 mini-jambo ">
          <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-12 col-md-4">
             <form action="activate_deleted_user" method="get">
              <div class="form-group">
                <div class="input-group">
                  <input type="text" class="form-control input-md" id="search" name="search">
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-md btn-btnColors bigSize"> <i class="fa fa-search" aria-hidden="true"></i></button>
                  </div>
                </div>

              </div>
            </form>

          </div>
        </div> 
        <div class="col-md-12 col-lg-12">

         <a role="button"  href="<?php echo base_url('admin/Users')?>" class="btn btn-progress1 pull-right hidden-sm hidden-xs"><i class="fa fa-step-backward"></i></a></div>
       </div>

     </div>
     <span class="alert-actives"></span>
   </div>
   <div class="panel-body table-responsive">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
                <!--<th class="text-center"> Id </th>
                <th class="text-center"> First Name </th>
                <th class="text-center"> Middle Name</th>-->
                <th class="text-center hidden-sm hidden-xs">Identity</th>
                <th class="text-center"> Full Name </th>
                 <th class="text-center hidden-sm hidden-xs"> Phone </th> 
                <th class="text-center hidden-sm hidden-xs"> Username </th>
                <th class="text-center hidden-sm hidden-xs"> Address</th> 
                <th class="text-center"> Action</th>
              </tr>
            </thead>

            <tbody><!--Displays users from the database in a table-->
              <?php
              foreach($Users as $user){ 

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
                  <a  data-activateid="<?=$user->userID?>"
                      data-activatename="<?=$user->fName.' '.$user->lName?>" 
                      class="btn btn-default btn-xs activate" title="Activate"
                    ><i class="fa fa-recycle"></i></a>
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
      <div id="user_activation" class="modal in displahy" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
              <p class="modal-msg"></p>
              <div class="row">
                <div class="col-12-xs text-center">
                  <button class="btn btn-success btn-md activation-yes" data-dismiss="modal" value="">Yes</button>
                  <button class="btn btn-danger btn-md activation-no modalClose" value="No">No</button>
                </div>
              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->