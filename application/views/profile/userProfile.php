<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid pro">
  <div class="rocw">
   <div class="col-md-12c ">
    <div class="rosw">
     <div class="col-mdd-12 ">
      <div class=" panel-success">
        <div class="panel-heading"><button type="button" class="btn btn-btnColors btn-md" data-toggle="modal" data-target="#updateProfile" ">
          <i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit</button><a role="button" href="<?php echo base_url('Access/resetPassword')?> " class="btn btn-btnColors btn-md pull-right">
            <i class="fa fa-key" aria-hidden="true"></i>&nbsp;Change password</a></div>
            <div class="panel-body">

              <div class="box box-info">

                <div class="box-body">
                 <div class="col-sm-4">
                  <?php if (isset($userProfile)) { 

                   if(isset($userProfile)){
                    foreach ($userProfile as $userProfile) { 

                    }
                    ?>
                    <!-- tumelo pic-->
                    <div  align="center">
                      <div id="profile-container">
                        <img id="proPic" src="<?= base_url($userProfile->filePath);?>" />
                      </div>

                      <!--Upload Image Js And Css-->
                    </div>

                    <!-- /input-group -->
                  </div>
                  <div class="col-sm-8">

                    <h4 class="profileNames"><b><span id="label_8"><?= $userProfile->fName;?></span>&nbsp;<span id="label_9"><?= $userProfile->midName;?></span>&nbsp;<span id="label_10"><?= $userProfile->lName;?></span></h4></span></b>
                    <span><p id="label_7"><?= $userProfile->bio;?></p>
                      <div id="ale-AnnPage"></div>
                      <?php   if(isset($statusEdit)){
                        echo alertMsgs($statusEdit,'Password Updated',' Password Not Updated');
                      } ?>
                      <?php
                      if(isset($learner)){
                        foreach ($learner as $value) { 

                        }?>

                        <p>Class: <b><?=$value->cgName?></b>&nbsp; &nbsp; Level:<b> <?=$value->level?></b></p>
                        <?php 
                      } ?>
                    </span>            
                  </div>
                  <div class="clearfix"></div>
                  <hr style="margin:5px 0 5px 0;">


                  <table class="table table-hover table-striped table-responsive-md">
                    <thead class="success">
                      <tr  class="success"><th>Fields</th>
                        <th>Details</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                       <td>First Name</td>
                       <td><label id="label_1"><?= $userProfile->fName;?></label></td>
                     </tr>
                     <tr>
                       <td>Middle Name</td>
                       <td><label id="label_2"><?= $userProfile->midName;?></label></td>
                     </tr>
                     <tr>
                       <td>LastName</td>
                       <td><label id="label_3"><?= $userProfile->lName;?></label></td>
                     </tr>
                     <tr>
                       <td>Email</td>
                       <td><label id="label_4"><?= $userProfile->email;?></label></td>
                     </tr>
                     <tr>
                       <td>Phone</td>
                       <td><label id="label_5"><?= $userProfile->phone;?></label></td>
                     </tr>
                     <tr>
                       <td>Address</td>
                       <td><label id="label_6"><?= $userProfile->address;?></label></td>
                     </tr>
                   </tbody>

                 </table>
                 <!-- /.box-body -->
               </div>
               <!-- /.box -->

             </div>

   <?php } }?>     
           
           </div> 
         </div>
       </div>  
     </div>
   </div>
   <input type="hidden" name="id_user" value="<?php echo isset($id_user)? $id_user:0 ;?>" placeholder="">
   <!-- line modal -->
   <div class="modal fade" id="updateProfile" tabindex="-1" role="dialog"  data-backdrop="static" data-keyboard="false" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
          <h3 class="modal-title text-center" id="lineModalLabel">EDIT PROFILE</h3>
        </div>

        <div class="modal-body">
          <?php //var_dump($profile) ;?>
          <!-- content goes here -->
          <form class="frmEditProfile" action="profile/editProfile" id="proForm" method="POST" enctype="multipart/form-data">

           <div class="form-group moveCenter">
            <div id="profile-container" class="mobile-center">
             <image id="profileImage" src="<?= base_url($userProfile->filePath);?>" />
              <input type="hidden" id="fileID" name="fileID" value="<?= $userProfile->filesID;?>">
              <input id="imageUpload" name="fileUpload"  type="file" 
              placeholder="Photo" capture></div>
            </div>
            <div id="ale-msg"></div>
            <div class="form-group col-md-4">
              <label for="firstName">First name</label>
              <input type="text" class="form-control" id="firstName" name="firstName" value="<?= $userProfile->fName;?>" placeholder="First Name">
            </div>
            <div class="form-group col-md-4">
              <label for="middleName">Middle name</label>
              <input type="text" class="form-control" id="middleName" name="middleName" value="<?= $userProfile->midName;?>" placeholder="Middle Name">
            </div>
            <div class="form-group col-md-4">
             <label for="lastName">Last name</label>
             <input type="text" class="form-control" id="lastName" name="lastName" value="<?= $userProfile->lName;?>" placeholder="Last Name">

           </div>
           <div class="form-group col-md-12">
             <label for="email">Email</label>
             <input type="email" class="form-control" id="email" name="email" value="<?= $userProfile->email;?>" placeholder="Email">

           </div>
           <div class="form-group col-md-12">
             <label for="phone">Phone </label>
             <input type="text" class="form-control" id="phone" name="phone" value="<?= $userProfile->phone;?>" placeholder="Phone">

           </div>
           <div class="form-group col-md-12">
             <label for="address">Address</label>
             <input type="text" class="form-control" id="address" name="address" value="<?= $userProfile->address;?>" placeholder="Address">

           </div>
           <div class="form-group col-md-12">
             <label for="bio">Biography</label>
             <textarea rows="5" class="form-control" id="bio" name="bio" value="" placeholder="Biography"><?= $userProfile->bio;?></textarea>

           </div>






         </div>
         <div class="clearfix"></div>
         <div class="modal-footer">
          <div class="btn-group btn-group-justified" role="group" aria-label="group button">
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-default" data-dismiss="modal"  role="button">Close</button>
            </div>
            <div class="btn-group btn-delete hidden" role="group">
              <button type="button" id="delImage" class="btn btn-default btn-hover-red" data-dismiss="modal"  role="button">Delete</button>
            </div>
            <div class="btn-group" role="group">
              <button type="submit" id="saveImage" class="btn btn-default btn-hover-green" role="button">Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>







