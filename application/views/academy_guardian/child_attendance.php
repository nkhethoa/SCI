<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

if (!empty($lsubjects)) { ?>
       <div class="well well-sm text-center guard-well-bg">
            <h3><?php echo $lFName; ?>'s Subjects with attendance Inside.</h3>
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
                    href="#attend-<?php echo trim(str_replace(' ','',$childSubj->subjectName ))  .''. $counter.''.$childSubj->learnerID; ?>">
	                <div class="panel-heading">
                    <h4 class="panel-title panel-disc-title">
                      <span class="glyphicon glyphicon-folder-close"></span>
                       <img class="pull-right arrows" 
                          src="<?php echo base_url('assets/images/arrow_down2.png'); ?>" alt="">
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
                //to hold total expected count
                $attend_count = 0;
                //array to hold date where the learner was absent
                $array_absent = array();
                $countPresent = 0;
                $countAbsent = 0;
                $totalAttend = 0;
                if (!empty($learnerAttend)) {
                  //count how many times was the learner present and absent
                  foreach ($learnerAttend as $attend) {
                    if ($childSubj->clsID == $attend->clsID) {
                      //tally the expected attendance total for the specific subject
                      $attend_count++;
                      if ($attend->attendStatus == 1) {
                          $countPresent++;
                      }else {
                          $countAbsent++; //=+ $value->attendStatus;
                          //keep records where the learner was absent
                          $array_absent[] = $attend;
                      }
                    } 
                  }
                }
                //avoid division by zero before printing
                if ($attend_count > 0) {
                    //calculate the percentage of attandance
                    $totalAttend = round(($countPresent/$attend_count)*100,0);
                    if (!empty($array_absent)) {
                        foreach ($array_absent as $absent) { ?>
                          <tr>
                            <td>
                              <b><?php echo $lFName ?></b>, was absent on <?php echo substr($absent->attendDate,0,10); ?> 
                            </td>
                            <td> round about this time:
                              <b> <?php echo substr($absent->attendDate,11); ?></b>
                            </td>       
                          </tr>
                            <?php 
                        } 
                    }else{ ?>
                    <tr></tr>
                    <tr>  
                      <td colspan="2">Average attendance: 
                        <b> <?php echo $totalAttend; ?> %</b>
                      </td>
                    </tr>
                    <?php 
                  }
                }else { ?>
                    <tr>   
                      <td colspan="2">Not yet marked.</td>
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
    <?php 
    } 
    ?> 
    
