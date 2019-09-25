<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

if (!empty($lsubjects)) { ?>
       <div class="well well-sm text-center guard-well-bg">
            <h3><?php echo $lFName; ?>'s Subjects with assignments Inside.</h3>
        </div>
        <?php 
        $counter=0;
        foreach ($lsubjects as $childSubj) { ?> 
        <!--Table responsible for displaying the subjects for learner assignments-->
          <!-- panel group-->
          <div class="panel-group panelGroup" id="<?php echo trim(str_replace(' ','',$childSubj->subjectName )) ?>">
            <!-- panel-->
            <div class="panel no_underline">
              <!-- panel heading-->
                  <a data-toggle="collapse" class="switch_arrow" 
                        data-parent="#<?php echo trim(str_replace(' ','',$childSubj->subjectName )) ?>"  
                        href="#assign-<?php echo trim(str_replace(' ','',$childSubj->subjectName ))  .''. 
                              $counter.''.$childSubj->learnerID; ?>">
                    <div class="panel-heading">
                      <h4 class="panel-title panel-disc-title">
                        <span class="glyphicon glyphicon-folder-close"></span>
                         <img class="pull-right arrows" 
                            src="<?php echo base_url('assets/images/arrow_down2.png'); ?>" alt="">
                            <span><?php echo $childSubj->subjectName; ?></span>
                      </h4>
                    </div>
                  </a>
            <div id="assign-<?php echo trim(str_replace(' ','',$childSubj->subjectName ))  
                    .''. $counter.''.$childSubj->learnerID; ?>"  
                    class="panel-collapse collapse">
              <!-- panel body-->
              <div class="panel-body">
                <!-- panel body table-->
            	<table class="table table-responsive">
                  <thead>
                    <tr>
                      <th>Title</th>
                      <th class="hidden-xs">Description</th>
                      <th class="hidden-xs">Published Date</th>
                      <th class="hidden-xs">Due Date</th>
                      <th>Download</th>
                    </tr>
                  </thead>
            		<tbody>
                  <?php 
                //check if the query is not empty
                if (!empty($all_learner_assignments)) {
                  //declare new array to hold learner assignments
                  $learnerAssigns = array();
                  //loop thru assignments and look for all the matching by learner subjects
                  foreach ($all_learner_assignments as $all_assignments) { 
                    if ($childSubj->clsID == $all_assignments->clsID) { 
                        //if found a matching record, load it into array
                        $learnerAssigns [] = $all_assignments;
                    }
                  }
                  //check if there where any assignment found
                  //if yes continue printing them
                  if (!empty($learnerAssigns)) {
                    foreach ($learnerAssigns as $learnerAssign) { 
                      //if ($childSubj->clsID == $learnerAssign->clsID) { 
                        //prepare time for post
                      $post_date = strtotime($learnerAssign->publishDate);
                      $now = time(substr($learnerAssign->publishDate, 11));
                      $units = 2; ?>
                      <tr>
                        <td>
                          <a href="#" 
                              data-ass_id ="<?php echo $learnerAssign->assignID;?>" 
                              data-assign_title ="<?php echo $learnerAssign->assignTitle;?>" 
                              data-assign_pd ="<?php echo timespan($post_date, $now, $units);?> ago"  
                              data-assign_desc ="<?php echo $learnerAssign->assignDesc;?>" 
                              data-assign_path ="<?php echo $learnerAssign->filePath;?>" 
                              data-assign_duedate ="<?php echo substr($learnerAssign->dueDate,0,16);?>"
                              <?php 
                              if ($learnerSubmission!=NULL) {
                                foreach ($learnerSubmission as $learnerSubmit) {                                  
                                  if (($childSubj->learnerID == $learnerAssign->lID) 
                                    && ($learnerSubmit->submitStatus== 1) 
                                    && ($learnerSubmit->assignID== $learnerAssign->assignID)) { ?>
                                    data-submit_date ="<?php echo $learnerSubmit->submittedDate;?>"
                                    <?php                            
                                  }else { ?>
                                    data-submit_date ="Not submitted.."
                                    <?php 
                                  }
                                }//end foreach
                              }else { ?>
                                  data-submit_date ="Not submitted.."
                                  <?php 
                              }//end learnersubmission
                              ?>
                              class="viewAssignment" data-toggle="modal" data-target=".submitAssignment">
                              <span class="glyphicon glyphicon-tasks text-primary glyphicon-green"></span>
                                  <?php echo $learnerAssign->assignTitle; ?>
                            </a>
                        </td>
                          <td class="hidden-xs"><?php echo word_limiter($learnerAssign->assignDesc,5); ?> </td>
                          <td class="hidden-xs"><?php echo timespan($post_date, $now, $units); ?> ago</td>
                          <td class="hidden-xs"><?php echo substr($learnerAssign->dueDate,0,16); ?> </td>
                          <td class="text-center">
                            <?php 
                              //check if file exists
                              if (file_exists($learnerAssign->filePath)) { ?>
                                  <a href="<?php echo  base_url().'Academy/download/'.$learnerAssign->fileID; ?>">
                                    <span class="glyphicon glyphicon-download fa-2x" title="Download File"></span>
                                  </a>
                                    <?php 
                              }else { ?> 
                              <a href="#">
                                <span class="glyphicon glyphicon-minus-sign fa-2x" title="No File"></span>
                              </a>
                          </td>
                        <?php 
                        }//end file_exists
                        ?>
                      </tr>
                      <?php 
                      //}//end assignment IF
                    }//end foreach learner assignments
                  }else{//end ISSET IF
                    ?>
                    <tr>   
                      <td colspan="2">No assignments</td>
                    </tr>
                    <?php 
                 }//end IF learnerAssigns EMPTY    
                }//end all_learner_assignments IF
                ?>    
            		</tbody>
                  
            </table><!-- /panel body table-->
            </div><!-- /panel body-->
            </div>
        </div> <!-- /panel-->       
      </div><!-- /panel group-->
      <?php 
      $counter++;
      }
      ?>
      <!--Table responsible for displaying assignments per subject-->
      <?php 
     //if the search returned null
    }else{ ?>
        <span>No subjects</span>
    <?php } ?>
    
