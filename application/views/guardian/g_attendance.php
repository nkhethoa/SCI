<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

if (!empty($lsubjects)) { ?>
       <div class="well well-sm text-center" style="background-color: #7fa27f2e;">
            <h3><?php echo $lFName; ?>s Subjects with progress Inside.</h3>
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
                    href="#attend-<?php echo trim(str_replace(' ','',$childSubj->subjectName ))  .''. $counter.''.$childSubj->learnerID; ?>">
	                <div class="panel-heading">
	                  <h4 class="panel-title panel-disc-title">
	                    <span class="glyphicon glyphicon-folder-open"></span>
	                    <span><?php echo $childSubj->subjectName; ?></span>
	                  </h4>
	                </div>
              	</a>
            <div id="attend-<?php echo trim(str_replace(' ','',$childSubj->subjectName ))  .''. $counter.''.$childSubj->learnerID; ?>"  
                    class="panel-collapse collapse">
              <!-- panel body-->
              <div class="panel-body">
                <!-- panel body table-->
            	<table class="table table-responsive">
                  <thead>
                    <tr>
                      <th class="hidden-xs">Date Absent (Y-M-D)</th>
                      <th class="visible-xs">Date (Y-M-D)</th>
                      <th class="hidden-xs">Time Absent (H:m:s)</th>
                      <th class="visible-xs">Time (H:m)</th>
                    </tr>
                  </thead>
            		<tbody>
            			<?php
           		//assign the learner ID and the subject in question to the search array
                $search['learner'] = $childSubj->learnerID;
                $search['clsID'] = $childSubj->clsID;
                //to hold total expected count
                $attend_count = 0;
                //get records specific for this learner
                $learnerAttend = $this->attendance->getAttendance($search);
                //count how many records were returned
                $attend_count= count($learnerAttend);
                //array to hold date where the learner was absent
                $array_absent = array();
                $countPresent = 0;
                $countAbsent = 0;
                $totalAttend = 0;
                if ($learnerAttend != NULL) {
                    //count how many times was the learner present and absent
                    foreach ($learnerAttend as $value) {
                        if ($value->attendStatus == 1) {
                            $countPresent++;
                        }else {
                            $countAbsent++; //=+ $value->attendStatus;
                            //keep records where the learner was absent
                            $array_absent[] = $value;
                        }
                        //$totalAttend++;
                    }
                }
                //avoid division by zero before printing
                if ($attend_count > 0) {
                    $totalAttend = round(($countPresent/$attend_count)*100,0);

                    if (!empty($array_absent)) {
                        foreach ($array_absent as $absent) { ?>
                            <tr>
                            <td><b>.$lFName.'</b>, was absent on '.substr($absent->attendDate,0,10); </td>
                            <td> round about this time:<b> ' .substr($absent->attendDate,11);</b></td>       
                            </tr>
                            <?php 
                        } 
                    }
                    ?>
                    <tr></tr>
                            <tr>  
                        <td colspan="2">Average attendance: <b> '.$totalAttend.' %</b></td>
                    </tr>
                    <?php 
                }else { ?>
                    <tr>   
                    <td colspan="2">Not yet marked.
                    </tr>
                    <?php 
                }    
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
     //if the search returned null
    }else{ ?>
        <span>No subjects</span>
    }
    
