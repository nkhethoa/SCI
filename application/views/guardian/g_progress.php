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
                    href="#progress-<?php echo trim(str_replace(' ','',$childSubj->subjectName ))  .''. $counter.''.$childSubj->learnerID; ?>">
	                <div class="panel-heading">
	                  <h4 class="panel-title panel-disc-title">
	                    <span class="glyphicon glyphicon-folder-open"></span>
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
           		//assign the learner ID and the subject in question to the search array
                $search['learner'] = $childSubj->learnerID;
                $search['clsID'] = $childSubj->clsID;
                $learner_progress = $this->progress->getProgress($search);
                //to hold how the test will count
                $weight = 0;
                //to hold total progress mark per learner
                $progress_mark = 0;
                if(!empty($learner_progress)){
                   foreach($learner_progress as $progress){ 
                    //create a list of assessments ?>
                       <tr>
                                    <td>'.$progress->assessProgressDescription.</td>
                                    '<td>'.$progress->weight.</td>
                                    '<td>'.$progress->marks.</td>
                                '</tr>
                        <?php 
                        //tally the assessment weights
                        $weight += $progress->weight;
                        //get a percentage out of marks based on the weight
                        $progress_mark += ($progress->marks*$progress->weight)/100;
                   }
                   //perform calculations if there were marks found
                   //this will help avoid division by zero
                   if($progress_mark > 0){ ?>
                      <tr><td><td style="color:green;font-weight: bold;" colspan=2>
                                Overall progress: '.ROUND(($progress_mark/$weight)*100,0);
                    <?php  
                   }else{ ?>
                        $lsa .= '<td style="color:green;font-weight: bold;">'.$progress_mark;</td>
                        <?php 
                   }
                }else{ ?>
                    $lsa .= '<td colspan="2">No progress yet.'; </td>
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
    
