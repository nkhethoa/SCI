<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    //check if anything was retuned
      if (!empty($learnerList)) {
        //loop through the content from the table based on the query
        foreach ($learnerList as $list) {  ?>         
            <tr>
              <td><input type="checkbox" 
                            name="submitCheckbox" 
                            class="checkbox_learner" 
                            value="<?php echo $list->learnerID; ?>" 
                            data-lid="<?php echo $list->learnerID; ?>" 
                            data-assignid="<?php echo $assignID; ?>">
              </td>
              <td><?php echo $list->learnerID; ?></td>
              <td><?php echo $list->lName.' '.$list->fName; ?></td>
                <?php 
              //check if this learner submitted 
              if (!empty($submissions)) {
                foreach ($submissions as $submission) {
                  //if () {
                    if ($submission->submitStatus == 1 && $list->learnerID == $submission->lID) { ?>
                      <td class="hidden-xs"><?php echo $submission->submittedDate; ?></td>
                      <!-- //download button -->
                      <td class="hidden-xs">
                        <?php 
                       //if the file exist
                       //then display download icon
                      if (file_exists($submission->filePath)) { ?>
                        <a href="<?php  echo base_url().'Academy/download/'.$submission->fileID; ?>"> 
                            <span class="glyphicon glyphicon-floppy-save fa-2x" title="Download File"></span>
                        </a>
                        <?php 
                        //if not display another icon
                      }else { ?>
                        <a href="#"> 
                          <span class="glyphicon glyphicon-minus-sign fa-2x" title="No File"></span>
                        </a>
                        <?php 
                      }
                      ?>
                      </td>
                         <!-- //to display reset button -->
                      <td>
                        <?php 
                        //check if the assignment is not overdue
                        //if not, display reset button
                        if (strtotime($submission->dueDate) > strtotime(date('Y-m-d H:i:s'))) { ?>         
                          <a href="#" 
                             data-assignid="<?php echo $submission->assignID; ?>" 
                             data-lid="<?php echo $submission->lID; ?>" 
                             data-cglid="<?php echo $list->cglID; ?>" 
                             class="resetSubmissions">
                             <span class="glyphicon glyphicon-floppy-remove fa-2x glyphicon-green" title="Reset Assignment"></span>
                          </a>
                          <?php 
                        }else{ ?>     
                          <a href="#">
                            <span class="glyphicon glyphicon-ban-circle fa-2x glyphicon-red" title="No Reset"></span>
                          </a>
                          <?php 
                        }
                         ?>
                      </td>
                       <?php 
                    }else { ?>
                        <td> Not submitted</td>
                        <td>
                          <a href="#"> 
                            <span class="glyphicon glyphicon-minus-sign fa-2x" title="No File"></span>
                          </a>
                        </td> 
                        <td>        
                          <a href="#">
                            <span class="glyphicon glyphicon-ban-circle fa-2x glyphicon-red" title="No Reset"></span>
                          </a>
                        </td>
                         <?php 
                  }//end submission status=1
                } //end submissions foreach
              }else{ ?>
                <td> Not submitted </td>
                <td>
                   <a href="#"> 
                       <span class="glyphicon glyphicon-minus-sign fa-2x" title="No File"></span>
                   </a>
                </td>
                <td>        
                  <a href="#">
                     <span class="glyphicon glyphicon-ban-circle fa-2x glyphicon-red" title="No Reset"></span>
                  </a>
                </td>
                  <?php 
              }//end if submitted else
              ?>
            </tr>
            <?php 
        }//end main foreach
      }//end !empty($learnerList)
    ?>
    
