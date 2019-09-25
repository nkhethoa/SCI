<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

if (!empty($lsubjects)) { ?>
       <div class="well well-sm text-center" style="background-color: #7fa27f2e;">
            <h3><?php echo $lFName; ?>s Subjects with assignments Inside.</h3>
        </div>
        <?php 
        $counter=0;
        foreach ($lsubjects as $childSubj) { ?> 
        <!--Table responsible for displaying the subjects for learner assignments-->
          <!-- panel group-->
          <div class="panel-group panelGroup" id="<?php echo trim(str_replace(' ','',$childSubj->subjectName )) ?>">
            <!-- panel-->
            <div class="panel panel-default">
              <!-- panel heading-->
                  <a data-toggle="collapse" 
                        data-parent="#<?php echo trim(str_replace(' ','',$childSubj->subjectName )) ?>"  
                        href="#assign-<?php echo trim(str_replace(' ','',$childSubj->subjectName ))  .''. $counter.''.$childSubj->learnerID; ?>">
                    <div class="panel-heading">
                      <h4 class="panel-title panel-disc-title">
                        <span class="glyphicon glyphicon-folder-open"></span>
                        <span><?php echo $childSubj->subjectName; ?></span>
                      </h4>
                    </div>
                  </a>
            <div id="assign-<?php echo trim(str_replace(' ','',$childSubj->subjectName ))  .''. $counter.''.$childSubj->learnerID; ?>"  
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
            			//reset search variables
                $search['assign']=FALSE;
                $search['learnOnly']=FALSE;
                //assign learnerID to search variable
                $search['learner']=$luID;
                //get the assignments of the selected learner
                $all_learner_assignments=$this->assignment->getAssignments($search);
                //assign learnerID to search variable
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
                        <tr >
                            <td>
                         <?php 
                            $search['assign']=$learnerAssign->assignID;
                            $search['learnOnly']=$learnerAssign->lID;
                            //$search['learner']=FALSE;
                            $learnerSubmission=$this->assignment->getAssignSubmission($search);
                            ?>
                        <a href="#" 
                            data-ass_id ="'.$learnerAssign->assignID.'" 
                            data-assign_title ="'.$learnerAssign->assignTitle.'" 
                            data-assign_pd ="'.timespan($post_date, $now, $units).' ago"  
                            data-assign_desc ="'.$learnerAssign->assignDesc.'" 
                            data-assign_path ="'.$learnerAssign->filePath.'" 
                            data-assign_duedate ="'.substr($learnerAssign->dueDate,0,16).'"
                            <?php 
                            if ($learnerSubmission!=NULL) {
                                foreach ($learnerSubmission as $learnerSubmit) {                                  
                                    if (($childSubj->learnerID == $learnerAssign->lID) && 
                                        ($learnerSubmit->submitStatus== 1) &&
                                        ($learnerSubmit->assignID== $learnerAssign->assignID)) { ?>
                                data-submit_date ="'.$learnerSubmit->submittedDate.'"
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
                            <span class="glyphicon glyphicon-tasks text-primary" style="color:green"></span>
                                '.$learnerAssign->assignTitle.'
                            </a>
                                

                            </td>
                            <td class="hidden-xs">'.word_limiter($learnerAssign->assignDesc,5).'</td>
                            <td class="hidden-xs">'.timespan($post_date, $now, $units).' ago</td>
                            <td class="hidden-xs">'.substr($learnerAssign->dueDate,0,16).'</td>
                            <td class="text-center">
                              <?php 
                            //check if file exists
                            if (file_exists($learnerAssign->filePath)) { ?>
                                <a href="'.base_url().'Academy/download/'.$learnerAssign->fileID.'">
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
                    <td colspan="2">No assignments
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
    }
    
