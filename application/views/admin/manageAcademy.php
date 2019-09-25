<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="container-fluid backgroundColour">
   
  <div>
  <div>
    <div class="col-md-12">
    
     <div class="panelStyle text-center well">Manage Academy</div>
   
    </div>
  </div>
     
  <div>
    <div class="col-md-4 col-sm-6">
        <a href="<?php echo base_url('Admin/manageSubjects')?>"> 
      <div class="tile purples btn">
        <br><i class="fa fa-book fa-5x"></i><br/>
        <h3 class="title">School Subjects</h3>
        <p class="hidden-sm hidden-xs"> Click here to add/edit/delete Subjects.</p>
      </div>
  </a>
    </div>
    <div class="col-md-4 col-sm-6">
       <a href="<?php echo base_url('Admin/manageClassLevels')?>"> 
      <div class="tile reds btn">
        <br><i class="fa fa-list-ol fa-5x"></i><br/>
        <h3 class="title">Class Levels</h3>
        <p class="hidden-sm hidden-xs">Click here to add/edit/delete Levels.</p>
      </div>
    </a>
    </div>
    <div class="col-md-4 col-sm-6">
      <a href="<?php echo base_url('Admin/manageAssessmentTypes')?>"> 
      <div class="tile oranges btn">
        <br><i class="fa fa-file-text fa-5x"></i><br/>
        <h3 class="title">Assessment Type</h3>
        <p class="hidden-sm hidden-xs">Click here to add/delete Assessments.</p>
      </div>
      </a>
    </div>
  </div>
  <div>
    <div class="col-md-4 col-sm-6">
      <a href="<?php echo base_url('Admin/manageSchoolQuarters')?>"> 
      <div class="tile greens btn">
        <br><i class="fa fa-calendar fa-5x"></i><br/>
        <h3 class="title">School Quarters</h3>
        <p class="hidden-sm hidden-xs">Click here to add/edit/delete Quarters.</p>
      </div>
      </a>
    </div>
    <div class="col-md-4 col-sm-6">
       <a href="<?php echo base_url('Admin/manageClassGroups')?>"> 
      <div class="tile blues btn">
        <br><i class="fa fa-th fa-5x"></i><br/>
        <h3 class="title">Class Groups</h3>
        <p class="hidden-sm hidden-xs">Click here to add/edit/delete Groups.</p>
      </div>
            </a>
    </div>
    <div class="col-md-4 col-sm-6">
      <a href="<?php echo base_url('Admin/addClassGroups')?>"> 
      <div class="tile pinks btn">
        <br><i class="fa fa-university fa-5x"></i><br/>
        <h3 class="title">Add Class Group Level</h3>
        <p class="hidden-sm hidden-xs"> Click here to add/edit/delete Class Group Level.</p>
      </div>
    </a>
    </div>     
  </div>
</div>
</div>