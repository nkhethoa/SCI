<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="container-fluid backgroundColour1">

 <section class="form-box" >
  <div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1  form-wizard">
    <?php
        $action ="Users/edit_user";
        echo form_open($action,array('id'=>'edit_user_form', 'role'=>'form'));
        ?>
      <h3>
        <?php echo isset($id_user)? $pageTitle.': '.$firstNameEdit. ' '.$lastNameEdit : '';?>
      </h3>
      <fieldset>

        <p><small><em><span style="color: red">* </span>= required fields</em></small> </p>           
       <div class="clearfix"></div>
         <div class="form-group col-md-12">
          <label for="role">Role: <span>*</span>
            <span style="color: red" class="hidden-sm hidden-xs">
              <em><small>&nbsp;[Press and Hold CTRL key to select multiple Roles.]</small></em>
            </span>
          </label>
            <select name="role[]" size="5" multiple class="form-control" id="role">
              <option value="" disabled>Please Select User Role</option>
              <option disabled value="admin" <?=(isset($a_role) && $a_role=='admin')? 'selected' : ''; ?> >Admin</option>
              <option  disabled value="learner" <?=(isset($l_role) && $l_role=='learner')? 'selected' : ''; ?> >Learner</option>
              <option disabled value="teacher" <?=(isset($t_role) && $t_role=='teacher')? 'selected' : ''; ?> >Teacher</option>
              <option  disabled value="guardian" <?=(isset($g_role) && $g_role=='guardian')? 'selected' : ''; ?> >Guardian</option>
            </select><!--displays form errors-->
            <span><?php echo form_error('role')? alertMsgs(false,'',form_error('role')):''?></span>
        </div>
      <div id="user_div">
        <input <?php echo isset($id_user)? "value='$id_user'": "value = 0"; ?> id ='idUser' type='hidden' name='idUser'>
        <div class="form-group col-md-6 ">
          <label>First Name: <span>*</span></label>
          <input type="text" class="form-control" id="e_firstName" value="<?php echo isset($id_user)? $firstNameEdit:set_value('firstName');?>" name="firstName" placeholder="First Name" >
          <span><?php echo form_error('firstName')? alertMsgs(false,'',form_error('firstName')):''?></span>
        </div>
        <div class="form-group col-md-6">
          <label>Middle Name:</label>
          <input type="text" class="form-control" name="middleName" value="<?php echo isset($id_user)? $middleNameEdit:set_value('middleName');?>" id="middleName" placeholder="Middle Name" >
          <span><?php echo form_error('middleName')? alertMsgs(false,'',form_error('middleName')):''?></span>
        </div>
        <div class="form-group col-md-12">
          <label>Last Name: <span>*</span></label>
          <input type="text" class="form-control" name="lastName" id="e_lastName" value="<?php echo isset($id_user)? $lastNameEdit:set_value('lastName');?>" placeholder="Last Name" >
          <span><?php echo form_error('lastName')? alertMsgs(false,'',form_error('lastName')):''?></span>
        </div>
        <div class="form-group col-md-12 ">
          <label>Email: <span>*</span></label>
          <input type="email" class="form-control" id="e_email" value="<?php echo isset($id_user)? $emailEdit:set_value('email');?>" name="email" placeholder="Email address" disabled >
          <span><?php echo form_error('email')? alertMsgs(false,'',form_error('email')):''?></span>
        </div>
        <div class="form-group col-md-12">
          <label>Phone: <span>*</span></label>
          <input type="text" class="form-control" size="10" id="e_phone" value="<?php echo isset($id_user)? $phoneEdit:set_value('phone');?>" name="phone" placeholder="Phone Number" >
          <span><?php echo form_error('phone')? alertMsgs(false,'',form_error('phone')):''?></span>
        </div>
        <div class="form-group col-md-12">
          <label>Physical Address: <span>*</span></label>
          <input type="text" name="address" value="<?php echo isset($addressEdit) && isset($id_user)? $addressEdit:set_value('address');?>" id="e_address" placeholder="House number, street/zone/section, complex/flat (physical address)" class="form-control ">
          <span><?php echo form_error('address')? alertMsgs(false,'',form_error('address')):''?></span>
        </div>
        <div class="form-group col-md-6">
          <label>City: <span>*</span></label>
          <input type="text" id="e_city" name="city" placeholder="City" value="<?php echo isset($cityEdit) && isset($id_user)? $cityEdit:set_value('city');?>" class="form-control ">
          <span><?php echo form_error('city')? alertMsgs(false,'',form_error('city')):''?></span>
        </div>
        <div class="form-group col-md-6">
          <label>Postal Code: <span>*</span></label>
          <input type="text" id="e_pcode" value="<?php echo isset($pcodeEdit) && isset($id_user)? $pcodeEdit:set_value('pcode');?>" name="pcode" placeholder="Postal Code" class="form-control ">
          <span><?php echo form_error('pcode')? alertMsgs(false,'',form_error('pcode')):''?></span>
        </div>
      </div>
        <div class="form-wiszard-buttons col-md-12 text-center">
          <button type="submit" id="submit_user_edit" class="btn btn-primary">Submit</button>
          <a type="reset" href="<?php echo base_url('Admin/Users')?>" class="btn btn-cancel">Cancel</a>
        </div>
        <?php echo form_close(); ?>
        </fieldset>
        
      </div>
      <div class="clearfix"></div>
    </section>
  </div>

<!-- modal for success message start -->
<div id="change_role" class="modal in none" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center">Change Role</h4>
      </div>
      <div class="modal-body">
        <p class="text-center"><b><em>To change user role.<br>
            Please use <a href="<?php echo base_url('admin'); ?> ">admin buttons</a> to add this user to that specific role.</em></b></p>
        <div class="row">
          <div class="col-12-xs text-center">
            <button class="btn btn-info btn-md role_close" data-dismiss="modal">Ok</button>
          </div>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
