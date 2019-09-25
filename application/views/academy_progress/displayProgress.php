<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    if (!empty($learner_list)) {
        $counter = 1;
        foreach ($learner_list as $list) { ?>
            <!-- //start building the table -->
          <tr>
            <?php 
            //hide this columns if the user is the learner
            if (identify_user_role($_SESSION['userID']) !=='learner') { ?>
              <td><?php echo $list->learnerID; ?></td>
              <td><?php echo $list->lName.', '.$list->fName; ?></td>
              <?php 
            }
            //if the user wants to view list of students only [VIEW ONLY]
            if ($reason == 1 OR $reason == 0) {
                /*$search['clsID'] = $clsID;
                $search['learner'] = $list->learnerID;
                $learner_progress = $this->progress->getProgress($search);*/
                //to hold how the test will count
                $weight = 0;
                //to hold total progress mark per learner
                $progress_mark = 0;
                if(!empty($learner_progress)){
                  foreach($learner_progress as $progress){
                    if ($list->learnerID == $progress->learnerID) {
                      //print list of assessments if user is learner
                      if(identify_user_role($_SESSION['userID'])==='learner'){ ?>
                        <tr>
                          <td><?php echo $progress->assessProgressDescription; ?></td>
                          <td><?php echo $progress->weight; ?></td>
                          <td><?php echo $progress->marks; ?></td>
                        </tr>
                        <?php 
                      }//end if is learner logged in
                      //tally the assessment weights
                      $weight += $progress->weight;
                      //get a percentage out of marks based on the weight
                      $progress_mark += ($progress->marks*$progress->weight)/100;
                    }
                  }//end foreach learner_progress
                   
                   //perform calculations if there were marks found
                   //this will help avoid division by zero
                   if($progress_mark > 0){ 
                        if(identify_user_role($_SESSION['userID'])==='learner'){ ?>
                          <tr>
                            <td></td>
                            <td class="marks" colspan="2">
                                Overall progress: <?php echo ROUND(($progress_mark/$weight)*100,0); ?> 
                            </td>
                                <?php 
                        }else{ ?>
                            <!-- //type of assessment on the first view -->
                          <td>Progress</td>
                          <td class="marks">
                              <?php echo ROUND(($progress_mark/$weight)*100,0); ?>
                          </td>
                          
                          <?php 
                        }
                         
                   }else{ ?>
                      <td class="marks"><?php echo $progress_mark; ?></td>
                        <?php 
                   }
                }else{ ?>
                  <td colspan="2">No progress yet.</td>
                  <?php 
                }
            }
            //if user wants to add new marks
        }//end foreach
    }           
    ?>
    
