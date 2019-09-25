<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="container-fluid backgroundColour">
  <section class="form-box" >
      <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2  form-wizard">
        <!-- Form Wizard -->
          <h3>Add Learner Form</h3>
          <p>Fill all form field to go next step</p>
          <div class="alert wizard-errors"></div>
          <?php                 
            if(isset($statusInsert)){
              echo alertMsgs($statusInsert,'User Inserted','Error: Please refresh your page and try again. Contact technical staff if this error persist.');
            }
          ?>
          <!-- Form progress -->
          <div class="form-wizard-steps form-wizard-tolal-steps-3">
            <div class="form-wizard-progress">
              <div class="form-wizard-progress-line wiz" data-now-value="12.25" data-number-of-steps="3"></div>
            </div>
            <!-- Step 1 -->
            <div class="form-wizard-step active">
              <div class="form-wizard-step-icon"><i class="fa fa-child" aria-hidden="true"></i></div>
              <p>Learner</p>
            </div><!-- Step 1 --> 
            <!-- Step 2 -->
            <div class="form-wizard-step">
              <div class="form-wizard-step-icon"><i class="fa fa-user" aria-hidden="true"></i></div>
              <p>Guardian</p>
            </div><!-- Step 2 -->
            <!-- Step 3  -->
            <div class="form-wizard-step">
              <div class="form-wizard-step-icon"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
              <p>Confirm Details</p>
            </div><!--Step 3-->
          </div><!-- Form progress -->

          <!-- Form Step 1 -->
          <fieldset>

            <h4>Learner Information: <span>Step 1 - 3</span></h4>
            <p><small><em><span style="color: red">* </span>= required fields</em></small> </p>
             <?php
              $action = "Users/new_learn_guard";
              echo form_open($action,array('id'=>'learn_guard','role'=>'form'));
              ?>
            
            <div class="form-group col-md-6" id="learner_school_relation">
            <label>Learner first time in the school?:<span>*</span></label>
            <select class="form-control" name="learner_school_relation" id="lsr">
               <option hidden value="">Is learner first time in this school?</option>
               <option value="0">No</option>
               <option value="1">Yes</option>
            </select>
            </div>
            <div class="clearfix"></div>
            <div class="form-group col-md-12 none" id="learner_username">
              <label>Username: <span>*</span></label>
              <input type="hidden" value="0" id="l_userID" name="l_userID">
              <div class="custom-search-input">
                <div class="input-group col-md-12">
                    <input type="text" 
                    value="" id="l_username" 
                    name="l_username" 
                    placeholder="Learner User Name or ID number" 
                    class="form-control">
                    <span class="input-group-btn">
                        <button class="btn btn-info btn-md btn-search" value="" id="l_search"  type="button" style="min-width: 0px;">
                          <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </span>
                </div>
              </div>
              <div class="alert" id="l_username_alert"></div>
            </div>
            <div class="clearfix"></div>
            <div class="learner_div none">
              <div class="form-group col-md-4 ">
                <label>First Name: <span>*</span></label>
                <input type="text" name="firstName" placeholder="First Name" id="firstName" class="form-control"  
                  value="<?=set_value('firstName');?>">
              </div>
              <div class="form-group col-md-4">
                <label>Middle Name:</label>
                <input type="text" name="middleName" placeholder="Middle Name" id="middleName" value="<?=set_value('middleName');?>" class="form-control">
              </div>
              <div class="form-group col-md-4">
                <label>Last Name: <span>*</span></label>
                <input type="text" name="lastName" placeholder="Last Name" id="lastName" value="<?=set_value('lastName');?>" class="form-control ">
              </div>
              <div class="col-md-5">
                  <label class="col-dmd-3">Type of Identity: <span>*</span></label>
                  <label class="radio-inline col-mdd-3" for="id_type_pass">
                    &nbsp;&nbsp;&nbsp;<input type="radio" class="l_id_type" id="id_type_pass" value="0" name="id_type">Passport Number
                  </label>
                  <label class="radio-inline col-mdd-3" for="id_type_rsa">
                   <input type="radio" class="l_id_type" id="id_type_rsa" value="1" name="id_type">RSA ID Number
                  </label>
              </div>
              <div class="form-group col-md-7 none" id="l_rsaid">
                <input type="text" name="l_idnumber" placeholder="Enter Identity number" id="l_idnumber" value="<?=set_value('idnumber');?>" class="form-control numeric_only">
              </div>
              <div class="form-group col-md-7 none" id="l_passport">
                <input type="text" name="l_passport" placeholder="Enter Passport number" id="l_pass_number" value="<?=set_value('passport');?>" class="form-control">
              </div>
              <div class="clearfix"></div>
              <div class="form-group col-md-6 ">
                <label>Email: <span>*</span></label>
                <input type="email" name="email" placeholder="Email Address" id="email" value="<?=set_value('email');?>" class="form-control ">
              </div>
              <div class="form-group col-md-6">
                <label>Phone: <span>*</span></label>
                <input type="text" name="phone" placeholder="Phone number" id="phone" value="<?=set_value('firstName');?>" class="form-control numeric_only">
              </div>
              <div class="form-group col-md-12">
                <label>Physical Address: <span>*</span></label>
                <input type="text" name="address" value="<?=set_value('address');?>" id="address" placeholder="House number, street/zone/section, complex/flat (physical address)" class="form-control ">
              </div>
              <div class="form-group col-md-6">
                <label>City: <span>*</span></label>
                <input type="text" id="city" name="city" placeholder="City" value="<?=set_value('city');?>" class="form-control ">
              </div>
              <div class="form-group col-md-6">
                <label>Postal Code: <span>*</span></label>
                <input type="text" id="pcode" value="<?=set_value('pcode');?>" name="pcode" placeholder="Postal Code" class="form-control numeric_only">
              </div>
              
              <div class="form-group col-md-12">
                <label>Learner Number: <span>*</span></label>
                <input type="text" class="form-control numeric_only" id="doe_Learner_No" value="" name="doeLearnNo" placeholder="Enter unique DoE Learner Number" >
                <span style="color: red"><em><small>Number is supplied by Department of Education</small></em></span>
                <span><?php echo form_error('doeLearnNo')? alertMsgs(false,'',form_error('doeLearnNo')):''?></span>
              </div>
              <div class="form-group col-md-6">
                <label>Class Level </label>
                <select class="level_id form-control " id="level" name="level">
                   <option hidden value="">Select Grade/Level</option>
                   <?php
                   foreach($levels as $cgLevels){ ?>  
                     <option value="<?=$cgLevels->levelID?>"><?=$cgLevels->levelName?></option>
                     <?php
                    }
                      ?> 
                </select>
              </div>
            <div class="form-group col-md-6">
              <label>Class Group </label>
              <select class="group_id form-control " id="group" name="group">
                <option hidden value>Select Class Group</option>  
              </select>
            </div>
          </div>
            <?php echo form_close(); ?>
      <div class="form-wizard-buttons col-md-12">
        <button type="submit" class="btn btn-next" id="submit_learn_guard" disabled>Next</button>
      </div>
    
    </fieldset>
    <!-- Form Step 1 -->

    <!-- Form Step 2 -->
    <fieldset> 
      <p><small><em><span style="color: red">* </span>= required fields</em></small> </p>
      <h4>First Guardian Information: <span>Step 2 - 3</span></h4>
        <?php
        $action = "Users/new_learn_guard";
        echo form_open($action,array('id'=>'f_s_guard','role'=>'form'));
        ?>
        <div class="form-group col-md-6">
          <label>Relationship to learner: <span>*</span></label>
          <select class="form-control " name="fg_related" id="fg_related">
           <option hidden value="">How are they related?</option>
           <?php
             foreach($relationship as $howRelated){ ?>  
               <option value="<?=$howRelated->howRelatedID?>"><?=$howRelated->relationship?></option>
               <!-- other options -->
               <?php
            }
           ?> 
          </select>
          </div>
          <div class="form-group col-md-6 none" id="guardian_school_relation">
          <label>Is Guardian Part of School (Previously or Now):<span>*</span></label>
          <select class="form-control" name="first_guard_school_relation" id="gsr">
             <option hidden value="">Is Guardian part of school?</option>
             <option value="0">No</option>
             <option value="1">Yes</option>
          </select>
          </div>
          <div class="form-group col-md-12 none" id="guardian_username">
            <label>Username: <span>*</span></label>
            <input type="hidden" value="" id="fg_userID" name="fg_userID">
            <div class="custom-search-input">
              <div class="input-group col-md-12">
                  <input type="text" 
                  value="" id="fg_username" 
                  name="fg_username" 
                  placeholder="Guardian User Name or ID number" 
                  class="form-control">
                  <span class="input-group-btn">
                      <button class="btn btn-info btn-md btn-search" value="" id="fg_search" type="button" style="min-width: 0px;">
                          <i class="glyphicon glyphicon-search"></i>
                      </button>
                  </span>
              </div>
            </div>
            <div class="alert" id="f_username_alert"></div>
          </div>
        <div class="clearfix"></div>
        <div class="first_guardian none">
          <div class="form-group col-md-4">
            <label>First Name: <span>*</span></label>
            <input type="text" value="" id="fg_firstName" name="fg_firstName" placeholder="First Name" class="form-control ">
          </div>
          <div class="form-group col-md-4">
            <label>Middle Name:</label>
            <input type="text" value="" id="fg_middleName" name="fg_middleName" placeholder="Middle Name" class="form-control">
          </div>
          <div class="form-group col-md-4">
            <label>Last Name: <span>*</span></label>
            <input type="text" value="" id="fg_lastName" name="fg_lastName" placeholder="Last Name" class="form-control ">
          </div>
          <div class="col-md-5">
              <label class="col-dmd-3">Type of Identity: <span>*</span></label>
              <label class="radio-inline col-mdd-3" for="fg_type_pass">
                &nbsp;&nbsp;&nbsp;<input type="radio" class="fg_id_type" id="fg_type_pass" value="0" name="id_type">Passport Number
              </label>
              <label class="radio-inline col-mdd-3" for="fg_type_rsa">
               <input type="radio" class="fg_id_type" id="fg_type_rsa" value="1" name="id_type">RSA ID Number
              </label>
          </div>
          <div class="form-group col-md-7 none" id="fg_rsaid">
            <input type="text" name="fg_idnumber" placeholder="Enter Identity number" id="fg_idnumber" value="<?=set_value('idnumber');?>" class="form-control numeric_only">
          </div>
          <div class="form-group col-md-7 none" id="fg_passport">
            <input type="text" name="fg_passport" placeholder="Enter Passport number" id="fg_pass_number" value="<?=set_value('passport');?>" class="form-control">
          </div>
          <div class="clearfix"></div>
          <div class="form-group col-md-6">
            <label>Email: <span>*</span></label>
            <input type="email" id="fg_email" value="" name="fg_email" placeholder="Email Address" class="form-control ">
          </div>
          <div class="form-group col-md-6">
            <label>Phone: <span>*</span></label>
            <input type="text" id="fg_phone" value="" name="fg_phone" placeholder="Phone number" class="form-control numeric_only">
          </div>
          <div class="col-md-3">
            <label>Stay with learner? <span>*</span></label>
          </div>
         <div class="col-md-9" data-toggle="buttons">
            <label class="btn btn-primary">
              <input name="fg_learner_address" value="1" class="active fg_learner_address" type="radio">Yes
            </label>
            <label class="btn btn-danger">
              <input name="fg_learner_address" value="0" type="radio" class="fg_learner_address">No
            </label>
          </div>
          <!-- Mother's Profession input-->
          <div id="fg_address_div" class="none">
              <div class="form-group col-md-12">
              <label>Physical Address: <span>*</span></label>
              <input type="text" name="fg_address" value="" id="fg_address" placeholder="Physical address - House number, street/zone/section, complex/flat" class="form-control ">
            </div>
            <div class="form-group col-md-6">
              <label>City: <span>*</span></label>
              <input type="text" id="fg_city" name="fg_city" placeholder="City" value="" class="form-control ">
            </div>
            <div class="form-group col-md-6">
              <label>Postal Code: <span>*</span></label>
              <input type="text" id="fg_pcode" value="" name="fg_pcode" placeholder="Postal Code" class="form-control numeric_only">
            </div>
          </div>
        </div>
      <!-- second guardian-->
        <h4>Second Guardian Information</h4>
          <div class="form-group col-md-6">
          <label>Relationship to learner: <span>*</span></label>
          <select class="form-control" name="sg_related" id="sg_related">
             <option hidden value="">How are they related?</option>
             <option value="0">Not Applicable</option>
             <?php
             foreach($relationship as $howRelated){ ?>  
               <option value="<?=$howRelated->howRelatedID?>"><?=$howRelated->relationship?></option>
               <!-- other options -->
               <?php
           }
           ?> 
          </select>
          </div>
          <div class="form-group col-md-6 none" id="second_guardian_school_relation">
          <label>Is a Guardian Part of School (Previously or Now):<span>*</span></label>
          <select class="form-control" name="second_guard_school_relation" id="second_guard_school_relation">
             <option hidden value="">Guardian part of school?</option>
             <option value="0">No</option>
             <option value="1">Yes</option>
          </select>
          </div>
          <div class="form-group col-md-12 none" id="s_guardian_username">
            <label>Username: <span>*</span></label>
            <input type="hidden" value="" id="sg_userID" name="sg_userID">
            <div class="custom-search-input">
              <div class="input-group col-md-12">
                  <input type="text" 
                  value="" id="sg_username" 
                  name="sg_username" 
                  placeholder="Guardian User Name or ID number" 
                  class="form-control">
                  <span class="input-group-btn">
                      <button class="btn btn-info btn-md btn-search" value="" id="sg_search" type="button" style="min-width: 0px;">
                          <i class="glyphicon glyphicon-search"></i>
                      </button>
                  </span>
              </div>
            </div>
            <div class="alert" id="s_username_alert"></div>
          </div>
          <div class="clearfix"></div>
          <div class="second_guardian_div none">
            <div class="form-group col-md-4">
              <label>First Name: <span>*</span></label>
              <input type="text" value="" id="sg_firstName" name="sg_firstName" placeholder="First Name" class="form-control ">
            </div>
            <div class="form-group col-md-4">
              <label>Middle Name:</label>
              <input type="text" value="" id="sg_middleName" name="sg_middleName" placeholder="Middle Name" class="form-control">
            </div>
            <div class="form-group col-md-4">
              <label>Last Name: <span>*</span></label>
              <input type="text" value="" id="sg_lastName" name="sg_lastName" placeholder="Last Name" class="form-control ">
            </div>
            <div class="col-md-5">
                <label class="col-dmd-3">Type of Identity: <span>*</span></label>
                <label class="radio-inline col-mdd-3" for="sg_type_pass">
                  &nbsp;&nbsp;&nbsp;<input type="radio" class="sg_id_type" id="sg_type_pass" value="0" name="id_type">Passport Number
                </label>
                <label class="radio-inline col-mdd-3" for="sg_type_rsa">
                 <input type="radio" class="sg_id_type" id="sg_type_rsa" value="1" name="id_type">RSA ID Number
                </label>
            </div>
            <div class="form-group col-md-7 none" id="sg_rsaid">
              <input type="text" name="idnumber" placeholder="Enter Identity number" id="sg_idnumber" value="<?=set_value('idnumber');?>" class="form-control numeric_only">
            </div>
            <div class="form-group col-md-7 none" id="sg_passport">
              <input type="text" name="passport" placeholder="Enter Passport number" id="sg_pass_number" value="<?=set_value('passport');?>" class="form-control">
            </div>
            <div class="clearfix"></div>
            <div class="form-group col-md-6">
              <label>Email: <span>*</span></label>
              <input type="email" value="" id="sg_email" name="sg_email" placeholder="Email Address" class="form-control ">
            </div>
            <div class="form-group col-md-6">
              <label>Phone: <span>*</span></label>
              <input type="text" id="sg_phone" value="" name="sg_phone" placeholder="Phone number" class="form-control numeric_only">
            </div>
            <div class="col-md-3">
              <label>Stay with learner?: <span>*</span></label>
            </div>
            <div class="col-md-9" data-toggle="buttons">
              <label class="btn btn-primary">
                <input name="sg_learner_address" value="1" class="active sg_learner_address" type="radio">Yes
              </label>
              <label class="btn btn-danger">
                <input name="sg_learner_address" value="0" type="radio" class="sg_learner_address">No
              </label>
            </div>
            <div class="clearfix"></div>
          <div id="sg_address_div" class="none">
            <div class="form-group col-md-12">
              <label>Physical Address: <span>*</span></label>
              <input type="text" name="sg_address" value="" id="sg_address" placeholder="Physical address - House number, street/zone/section, complex/flat" class="form-control ">
            </div>
            <div class="form-group col-md-6">
              <label>City: <span>*</span></label>
              <input type="text" id="sg_city" name="sg_city" placeholder="City" value="" class="form-control ">
            </div>
            <div class="form-group col-md-6">
              <label>Postal Code: <span>*</span></label>
              <input type="text" id="sg_pcode" value="" name="sg_pcode" placeholder="Postal Code" class="form-control numeric_only">
            </div>
        </div>
        </div>
        <div class="clearfix"></div>
        <?php echo form_close(); ?>
      <div class="form-wizard-buttons">
        <button type="button" class="btn btn-previous">Previous</button>
        <?php //if (isset($_SESSION['learner']) && !empty($_SESSION['learner'])) { ?>
          <button type="submit" class="btn btn-next learn_guard_submit" id="final-next">Next</button>
        <?php //} ?>
      </div>
    
    </fieldset>
    <!-- Form Step 2 -->
    <!-- Form Step 3 -->
   <fieldset>
      <h4 class="text-center">Confirm Information: <span>Step 3 - 3</span></h4>
      <br>
      <br>
      <h3 class="text-center">Please confirm all details below and submit the form.</h3>
      <p class="text-center">Use Previous button to go back and make correction were necessary</p>
      <br>
      <br>
      <div>
        <?php 
            $learner = $this->session->flashdata('learner');
            $guardian = $this->session->flashdata('guardian');
        ?>
        <h3 class="text-center">Learner</h3>
      <div class="col-md-12">
        <label class="col-md-2"><b>Names</b>:</label>
        <label class="col-md-4"><?=$learner['firstName'] ;?></label>
        <label class="col-md-4"><?=$learner['middleName'] ;?></label>
        <label class="col-md-2"><?=$learner['lastName'] ;?></label>
      
      </div>
        <div class="col-md-12">
        <label class="col-md-2"><b>Contact</b>:</label>
        <label class="col-md-4"><?= ($learner['learner_school_relation'] == 0) ? $learner['l_username'] : $learner['email'] ;?></label>
        <label class="col-md-6"><?=$learner['phone'] ;?></label>
      </div>
      <div class="col-md-12">
        <label class="col-md-2"><b>Address</b>:</label>
        <label class="col-md-4"><?=$learner['address'] ;?></label>
        <label class="col-md-4"><?=$learner['city'] ;?></label>
        <label class="col-md-2"><?=$learner['pcode'] ;?></label>
      </div>
      </div>
      <div>
        <h3 class="text-center">First Guardian</h3>
      <div class="col-md-12">
        <label class="col-md-2"><b>Names</b>:</label>
        <label class="col-md-4"><?=$guardian['fg_firstName'] ;?></label>
        <label class="col-md-4"><?=$guardian['fg_middleName'] ;?></label>
        <label class="col-md-2"><?=$guardian['fg_lastName'] ;?></label>
      
      </div>
        <div class="col-md-12">
        <label class="col-md-2"><b>Contact</b>:</label>
        <label class="col-md-4"><?= ($guardian['first_guard_school_relation'] == 1) ? $guardian['fg_username'] : $guardian['fg_email'] ;?></label>
        <label class="col-md-6"><?=$guardian['fg_phone'] ;?></label>
      </div>
      <div class="col-md-12">
            <?php
        if ($guardian['fg_learner_address'] == 0) {?>
          <label class="col-md-2"><b>Address</b>:</label>
          <label class="col-md-4"><?=$guardian['fg_address'] ;?></label>
          <label class="col-md-4"><?=$guardian['fg_city'] ;?></label>
          <label class="col-md-2"><?=$guardian['fg_pcode'] ;?></label>
        <?php
      }else {?>
          <label class="col-md-2"><b>Address</b>:</label>
          <label class="col-md-4"><?=$learner['address'] ;?></label>
          <label class="col-md-4"><?=$learner['city'] ;?></label>
          <label class="col-md-2"><?=$learner['pcode'] ;?></label>
      <?php
    }
      ?>
      </div>
      </div>
      <div>
        <h3 class="text-center">Second Guardian</h3>
        <?php
        if ($guardian['sg_related'] > 0) {?>
      <div class="col-md-12">
        <label class="col-md-2"><b>Names</b>:</label>
        <label class="col-md-4"><?=$guardian['sg_firstName'] ;?></label>
        <label class="col-md-4"><?=$guardian['sg_middleName'] ;?></label>
        <label class="col-md-2"><?=$guardian['sg_lastName'] ;?></label>
      
      </div>
        <div class="col-md-12">
        <label class="col-md-2"><b>Contact</b>:</label>
        <label class="col-md-4"><?= ($guardian['second_guard_school_relation'] == 1) ? $guardian['sg_username'] : $guardian['sg_email'] ;?></label>
        <label class="col-md-6"><?=$guardian['sg_phone'] ;?></label>
      </div>
      <div class="col-md-12">
        <?php
        if ($guardian['sg_learner_address'] == 0) {?>
          <label class="col-md-2"><b>Address</b>:</label>
          <label class="col-md-4"><?=$guardian['sg_address'] ;?></label>
          <label class="col-md-4"><?=$guardian['sg_city'] ;?></label>
          <label class="col-md-2"><?=$guardian['sg_pcode'] ;?></label>
        <?php
      }else {?>
          <label class="col-md-2"><b>Address</b>:</label>
          <label class="col-md-4"><?=$learner['address'] ;?></label>
          <label class="col-md-4"><?=$learner['city'] ;?></label>
          <label class="col-md-2"><?=$learner['pcode'] ;?></label>
      <?php
    }
      ?>
      </div>
      <?php
    }else {?>
           <p>Not Applicable</p>
    <?php
  }
      ?>
      </div>
      <div class="form-wizard-buttons">
        <button type="button" class="btn btn-previous">Previous</button>
        <?php/*
        if (isset($_SESSION['learner']) && !empty($_SESSION['learner'])) { 
          if (isset($_SESSION['guardian']) && !empty($_SESSION['guardian'])) {*/?>
        <a role="button" href="new_learner_guardian" class="btn btn-primary" id="final-submit" >Submit</a>
        <?php //}} ?>
      </div>
    </fieldset>
  <!-- Form Wizard -->
</div>
<div class="clearfix"></div>
</section>
</div><!--/container-fluid-->