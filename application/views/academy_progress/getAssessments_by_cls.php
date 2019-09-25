<?php
defined('BASEPATH') OR exit('No direct script access allowed');

        if ($clsID > 0) {
          //check if there are values to display
          if (!empty($assessment)) { ?>
              <!-- //start with select -->
            <option value="0" hidden="hidden">Select Assessment</option>
              <!-- //loop thru evaluations -->
            <?php  
            foreach($assessment as $evaluation){ ?>
              <option value="<?php echo $evaluation->id; ?>"><?php echo $evaluation->description; ?></option>
              <?php  
            }
          }else{ ?>
              <!-- //if no values found -->
            <option> No assessments found</option>
            <?php 
          }
        }else{ ?>
          <!-- //if no values found -->
          <option> No assessments found</option>
          <?php 
        }
        
    ?>