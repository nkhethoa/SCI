<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

     <!--  //start with select -->
      <option hidden="hidden">Select Class Group</option>
      <!-- //check if there are values to display -->
      <?php 
      if ($groupLevel != null) {
          foreach($groupLevel as $cgLevels){  ?>
            <option value='<?php echo $cgLevels->cgID; ?>'><?php echo $cgLevels->classGroupName; ?></option>
            <?php  
          }
        }else{ ?>
          <!-- //if no values found -->
          <option> No Class groups for this level</option>
          <?php 
        }
        ?>
    
