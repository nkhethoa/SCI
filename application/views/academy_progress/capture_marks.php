<?php
defined('BASEPATH') OR exit('No direct script access allowed');


    if(!empty($learner_progress)){
        foreach($learner_progress as $progress){ ?>
            <!-- //print learner details -->
          <tr>
            <td><?php echo $progress->learnerID; ?></td>
            <td><?php echo $progress->lLName.', '.$progress->lFName; ?></td>
            <!-- //assessment name -->
            <td><?php echo $progress->assessProgressDescription; ?></td>
            <!-- //use this only when on ADD or UPDATE MODE -->
            <?php 
            if($reason != 1 && $new_assessment != 0){ ?>
                <!-- //hidden input fields required for the entering of marks --> 
              <input type="hidden" name="progress_clsID[]" 
                    class="progress_clsID" 
                    value="<?php echo $clsID; ?>">
              <input type="hidden" name="progressID[]" 
                    class="progressID" 
                    value="<?php echo $progress->progressID; ?>">
              <input type="hidden" name="progress_lID[]" 
                    class="progress_lID" 
                    value="<?php echo $progress->learnerID; ?>">
              <!-- //print recorded marks before update -->
              <td class="">
                <input type="text" 
                      class="form-control progress_mark numeric_only"  
                      name="progress_mark"  min="0" max="100" 
                      value="<?php echo $progress->marks; ?>">
              </td>
                <?php 
            }else{ ?>
              <td><?php echo $progress->marks; ?></td>
              <?php  
            }
        }
    }
    //add buttons at bottom
    if($reason != 1 && $new_assessment != 0){ ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td> 
          <input class="btn btn-primary saveProgress" 
                  id="updateProgress" name="submit" 
                  type="submit" value="Save">
        </td>
      </tr>
      <?php 
    }   
    ?>