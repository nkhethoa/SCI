<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

if (!empty($lsubjects)) { ?>
       <div class="well well-sm text-center guard-well-bg">
            <h3><?php echo $lFName; ?>'s Subjects with progress Inside.</h3>
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
                    href="#progress-<?php echo trim(str_replace(' ','',$childSubj->subjectName ))  .''. $counter.''.$childSubj->learnerID; ?>">
	                <div class="panel-heading">
                    <h4 class="panel-title panel-disc-title">
                      <span class="glyphicon glyphicon-folder-close"></span>
                       <img class="pull-right arrows" 
                          src="<?php echo base_url('assets/images/arrow_down2.png'); ?>" alt="">
                          <span><?php echo $childSubj->subjectName; ?></span>
                    </h4>
                  </div>
              	</a>
            <div id="progress-<?php echo trim(str_replace(' ','',$childSubj->subjectName ))  .''. $counter.''.$childSubj->learnerID; ?>"  
                    class="panel-collapse collapse">
              <!-- panel body-->
              <div class="panel-body">
                <!-- panel body table-->
            	<table class="table table-responsive">
                  <thead>
                    <tr>
                      <th>Assessment</th>
                      <th>Weight</th>
                      <th>Mark %</th>
                    </tr>
                  </thead>
            		<tbody>
            			<?php
                //to hold how the test will count
                $weight = 0;
                //to hold total progress mark per learner
                $progress_mark = 0;
                if(!empty($learner_progress)){
                  foreach($learner_progress as $progress){ 
                    if ($childSubj->clsID == $progress->clsID) { ?>
                      <!--create a list of assessments-->
                      <tr>
                          <td><?php echo $progress->assessProgressDescription; ?></td>
                          <td><?php echo $progress->weight; ?></td>
                          <td><?php echo $progress->marks; ?></td>
                      </tr>
                      <?php 
                      //tally the assessment weights
                      $weight += $progress->weight;
                      //get a percentage out of marks based on the weight
                      $progress_mark += ($progress->marks*$progress->weight)/100;
                    }
                  }
                   //perform calculations if there were marks found
                   //this will help avoid division by zero
                   if($progress_mark > 0){ ?>
                      <tr>
                        <td></td>
                        <td colspan="2" class="marks" >
                            Overall progress: <?php  echo ROUND(($progress_mark/$weight)*100,0);?>
                        </td>
                    <?php  
                   }else{ ?>
                     <!--  <td class="marks"><?php echo $progress_mark; ?> </td> -->
                      <td colspan="2">No progress yet.</td>
                  <?php 
                   }
                }else{ ?>
                    <td colspan="2">No progress yet.</td>
                    <?php 
                }
              //}
                ?>
                </tr>
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
        <span class="marks">No subjects</span>
    <?php
    } ?>
    
