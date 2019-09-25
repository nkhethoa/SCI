<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="container-fluid backgroundColour1">

 <section class="form-box" >
  <div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1  form-wizard">


    <?php
    $action ="Users/add_user_teacher";
    echo form_open($action,array('id'=>'teacher_form','role'=>'form'));

    ?>
    <h3><?php echo $pageTitle;?></h3>
    <fieldset> 
      <div class="alert teacher-alert"></div>
      <h4 class="text-center">Teacher Information</h4>
        <p>
          <small><em>
            <span style="color: red">*</span>= required fields
          </em></small>
        </p> 

      <div class="form-group col-md-6" id="teacher_school_relation">
       <label>Is Teacher Part of School (Previously or Now):<span>*</span></label>
       <select class="form-control" name="teacher_school_relation" id="tsr">
          <option hidden value="">Teacher part of school?</option>
          <option value="0">No</option>
          <option value="1">Yes</option>
       </select>
       </div>
       <div class="form-group col-md-12 none" id="t_username_div">
         <label>Username: <span>*</span></label>
         <input type="hidden" value="" id="t_userID" name="t_userID">
         <div class="custom-search-input">
           <div class="input-group col-md-12">
               <input type="text" 
               value="" id="t_username" 
               name="t_username" 
               placeholder="Teacher User Name or ID number" 
               class="form-control">
               <span class="input-group-btn">
                   <button class="btn btn-info btn-md btn-search" value="" id="t_search" type="button" style="min-width: 0px;">
                       <i class="glyphicon glyphicon-search"></i>
                   </button>
               </span>
           </div>
         </div>
         <div class="alert" id="t_username_alert"></div>
       </div>
      <div class="clearfix"></div>
      <div class="none teacher_div">
        <div class="form-group col-md-4 ">
          <label>First Name: <span>*</span></label>
          <input type="text" name="firstName" placeholder="First Name" id="t_firstName" class="form-control"  
          value="<?=set_value('firstName');?>">
        </div>
        <div class="form-group col-md-4">
          <label>Middle Name:</label>
          <input type="text" name="middleName" placeholder="Middle Name" id="t_middleName" value="<?=set_value('middleName');?>" class="form-control">
        </div>
        <div class="form-group col-md-4">
          <label>Last Name: <span>*</span></label>
          <input type="text" name="lastName" placeholder="Last Name" id="t_lastName" value="<?=set_value('lastName');?>" class="form-control ">
        </div>
        <div class="col-md-12">
          <label class="col-md-2" style="padding-left: 0px;">Type of Identity: <span>*</span></label>
          <div data-toggle="buttons" class="col-md-6 side-margins">
            <label class="btn btn-primary">
             <input type="radio" class="id_type" id="id_type_pass" value="0" name="id_type">Passport
             <span class="glyphicon glyphicon-check" style="color: white;" title="Passport"></span>
            </label>          
            <label class="btn btn-primary">
             <input type="radio" class="id_type" id="id_type_rsa" value="1" name="id_type">ID Number
             <span class="glyphicon glyphicon-check" style="color: white;" title="Identity Number"></span>
            </label>
          </div>
        <div class="col-md-4 side-margins">
          <div class="form-group col-md-12 none side-margins" id="rsaid">
          <input type="text" name="idnumber" placeholder="Enter Identity number" id="t_idnumber" value="<?=set_value('idnumber');?>" class="form-control numeric_only">
        </div>
        <div class="form-group col-md-12 none side-margins" id="passport">
          <input type="text" name="passport" placeholder="Enter Passport number" id="t_pass_number" value="<?=set_value('passport');?>" class="form-control">
        </div>
        </div>
        
        </div>
        <div class="clearfix"></div>
        <div class="form-group col-md-6">
          <label>Email: <span>*</span></label>
          <input type="email" name="email" placeholder="Email Address" id="t_email" value="<?=set_value('email');?>" class="form-control ">
        </div>
        <div class="form-group col-md-6">
          <label>Phone: <span>*</span></label>
          <input type="text" name="phone" placeholder="Phone number" id="t_phone" value="<?=set_value('firstName');?>" class="form-control numeric_only">
        </div>
        <div class="form-group col-md-12">
          <label>Physical Address: <span>*</span></label>
          <input type="text" name="address" value="<?=set_value('address');?>" id="t_address" placeholder="House number, street/zone/section, complex/flat (physical address)" class="form-control ">
        </div>
        <div class="form-group col-md-6">
          <label>City: <span>*</span></label>
          <input type="text" id="t_city" name="city" placeholder="City" value="<?=set_value('city');?>" class="form-control ">
        </div>
        <div class="form-group col-md-6">
          <label>Postal Code: <span>*</span></label>
          <input type="text" id="t_pcode" value="<?=set_value('pcode');?>" name="pcode" placeholder="Postal Code" class="form-control numeric_only">
        </div>
        <div class="col-md-4">
          <label>Want to assign teacher subject? <span>*</span></label>
        </div>
        <div class="col-msd-8 " data-toggle="buttons">
          <label class="btn btn-success">
            <input name="subject_y_n" value="1" class="subject_y_n" type="radio">Yes
            <span class="glyphicon glyphicon-check" style="color: white;"></span>
          </label>
          <label class="btn btn-danger">
            <input name="subject_y_n" value="0" type="radio" class="subject_y_n">No
            <span class="glyphicon glyphicon-remove-sign" style="color: white;"></span>
          </label>
        </div>
      <div id="t_subject_div" class="none">
       <div class="col-md-5">
        <div class="form-group col-md-12 fluid-card">
          <label>Select Subjects <span>*</span></label>
          <select class="form-control add_from" id="t_school_subjects" name="school_subjects">
           <option hidden value="">Select Subject</option>
           <?php
           foreach($schoolSubjects as $sub){ ?>  
           <option value="<?=$sub->subjID?>"><?=$sub->subjectName?></option>
           <?php
         }
         ?> 
       </select>
     </div>
     <div class="form-group col-md-12 fluid-card none" id="level_div">
      <label>Class Level <span>*</span></label>
      <select class="level_id form-control add_from" id="t_level" name="level">
       <option hidden value="">Select Grade/Level</option>
       <?php
       foreach($levels as $cgLevels){ ?>  
       <option value="<?=$cgLevels->levelID?>"><?=$cgLevels->levelName?></option>
       <?php
     }
     ?> 
   </select>
   </div>
   <div class="form-group col-md-12 fluid-card none" id="group_div">
    <label>Class Group <span>*</span></label>
    <select class="group_id form-control add_from" id="t_group" name="group">
      <option hidden value>Select Class Group</option>  
    </select>
  </div>
  </div>

  <div class="col-md-2">
    <!--push add button down -->
    <div class="col-md-12"><label></label></div>
    <div class="col-md-12"><label></label></div>
    <div class="col-md-12 none" id="add_subj_div">
      <button type="button" id="add_selected" class="btn btn-success col-md-8">Add &gt;</button>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 none" id="btn_remove_div">
      <button type="button" id="remove_selected" class="btn btn-warning col-md-8">Remove</button>
    </div>
  </div>

  <div class="form-group col-md-5 none" id="my_subjecs_div">
    <input type="hidden" name="list_count" id="list_count">
    <label>Selected Subjects </label>
    <select size="10" multiple class="form-control" id="selected_subjects" name="selected_subjects[]">  
    </select>
  </div>
</div>
  <div class="col-md-12"><label></label></div>
  <div class="form-wiszard-buttons col-md-12 ">
    <button type="submit" id="submit_teacher" class="btn btn-primary">Submit</button>
    <a type="reset" href="<?php echo base_url('Admin')?>" class="btn btn-cancel">Cancel</a>
  </div>
  </div><!--/toggle fields-->
  <?php echo form_close(); ?>
  </fieldset>

  </div>
  <div class="clearfix"></div>
  </section>
  </div>

