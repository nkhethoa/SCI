<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="container-fluid backgroundColour1">

 <section class="form-box" >
  <div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1  form-wizard">
    <?php
        $action ="Users/add_admin_user";
        echo form_open($action,array('id'=>'admin_form', 'role'=>'form'));

        ?>
      <h3><?php echo $pageTitle;?></h3>
      <fieldset>

      <h4 class="text-center">Admin Information</h4>
        <p><small><em><span style="color: red">* </span>= required fields</em></small> </p>           
       <div class="clearfix"></div>
       <div class="form-group col-md-6" id="admin_school_relation">
       <label>Is Admin Part of School (Previously or Now):<span>*</span></label>
       <select class="form-control" name="admin_school_relation" id="asr">
          <option hidden value="">Admin part of school?</option>
          <option value="0">No</option>
          <option value="1">Yes</option>
       </select>
       </div>
       <div class="form-group col-md-12 none" id="a_username_div">
         <label>Username: <span>*</span></label>
         <input type="hidden" value="" id="a_userID" name="a_userID">
         <div class="custom-search-input">
           <div class="input-group col-md-12">
               <input type="text" 
               value="" id="a_username" 
               name="a_username" 
               placeholder="Admin User Name or ID number" 
               class="form-control">
               <span class="input-group-btn">
                   <button class="btn btn-info btn-md btn-search" value="" id="a_search" type="button" style="min-width: 0px;">
                       <i class="glyphicon glyphicon-search"></i>
                   </button>
               </span>
           </div>
         </div>
         <div class="alert" id="a_username_alert"></div>
       </div>
       <div class="clearfix"></div>
      <div class="Admin_div none">
        <div class="form-group col-md-6 ">
          <label>First Name: <span>*</span></label>
          <input type="text" name="firstName" placeholder="First Name" id="a_firstName" class="form-control"  
          value="<?=set_value('firstName');?>">
          <span><?php echo form_error('firstName')? alertMsgs(false,'',form_error('firstName')):''?></span>
        </div>
        <div class="form-group col-md-6">
          <label>Middle Name:</label>
          <input type="text" name="middleName" placeholder="Middle Name" id="a_middleName" value="<?=set_value('middleName');?>" class="form-control">
          <span><?php echo form_error('middleName')? alertMsgs(false,'',form_error('middleName')):''?></span>
        </div>
        <div class="form-group col-md-12">
          <label>Last Name: <span>*</span></label>
          <input type="text" name="lastName" placeholder="Last Name" id="a_lastName" value="<?=set_value('lastName');?>" class="form-control ">
          <span><?php echo form_error('lastName')? alertMsgs(false,'',form_error('lastName')):''?></span>
        </div>
        <div class="col-md-5">
            <label class="col-dmd-3">Type of Identity: <span>*</span></label>
            <label class="radio-inline col-mdd-3" for="id_type_pass">
              &nbsp;&nbsp;&nbsp;<input type="radio" class="id_type" id="id_type_pass" value="0" name="id_type">Passport Number
            </label>
            <label class="radio-inline col-mdd-3" for="id_type_rsa">
             <input type="radio" class="id_type" id="id_type_rsa" value="1" name="id_type">RSA ID Number
            </label>
            <span><?php echo form_error('id_type')? alertMsgs(false,'',form_error('id_type')):''?></span>
        </div>
        <div class="form-group col-md-7 none" id="rsaid">
          <input type="text" name="idnumber" placeholder="Enter Identity number" id="a_idnumber" value="<?=set_value('idnumber');?>" class="form-control numeric_only" maxlength="13">
          <span><?php echo form_error('idnumber')? alertMsgs(false,'',form_error('idnumber')):''?></span>
        </div>
        <div class="form-group col-md-7 none" id="passport">
          <input type="text" name="passport" placeholder="Enter Passport number" id="a_pass_number" maxlength="9" value="<?=set_value('passport');?>" class="form-control">
          <span><?php echo form_error('passport')? alertMsgs(false,'',form_error('passport')):''?></span>
        </div>
        <div class="clearfix"></div>
        <div class="form-group col-md-12 ">
          <label>Email: <span>*</span></label>
          <input type="email" name="email" placeholder="Email Address" id="a_email" value="<?=set_value('email');?>" class="form-control ">
          <span><?php echo form_error('email')? alertMsgs(false,'',form_error('email')):''?></span>
        </div>
        <div class="form-group col-md-12">
          <label>Phone: <span>*</span></label>
          <input type="text" name="phone" placeholder="Phone number" id="a_phone" value="<?=set_value('firstName');?>" class="form-control numeric_only">
          <span><?php echo form_error('phone')? alertMsgs(false,'',form_error('phone')):''?></span>
        </div>
        <div class="form-group col-md-12">
          <label>Physical Address: <span>*</span></label>
          <input type="text" name="address" value="<?=set_value('address');?>" id="a_address" placeholder="House number, street/zone/section, complex/flat (physical address)" class="form-control ">
          <span><?php echo form_error('address')? alertMsgs(false,'',form_error('address')):''?></span>
        </div>
        <div class="form-group col-md-6">
          <label>City: <span>*</span></label>
          <input type="text" id="a_city" name="city" placeholder="City" value="<?=set_value('city');?>" class="form-control ">
          <span><?php echo form_error('city')? alertMsgs(false,'',form_error('city')):''?></span>
        </div>
        <div class="form-group col-md-6">
          <label>Postal Code: <span>*</span></label>
          <input type="text" id="a_pcode" value="<?=set_value('pcode');?>" name="pcode" placeholder="Postal Code" class="form-control numeric_only">
          <span><?php echo form_error('pcode')? alertMsgs(false,'',form_error('pcode')):''?></span>
        </div>
        <div class="form-wiszard-buttons col-md-12 text-center">
          <button type="submit" id="submit_admin" class="btn btn-primary">Submit</button>
          <a type="reset" href="<?php echo base_url('Admin')?>" class="btn btn-cancel">Cancel</a>
        </div>
      </div>
        <?php echo form_close(); ?>
        </fieldset>
        
      </div>
      <div class="clearfix"></div>
    </section>
  </div>


