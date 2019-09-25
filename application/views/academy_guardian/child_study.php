<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

if (!empty($lsubjects)) { ?>
       <div class="well well-sm text-center guard-well-bg">
            <h3><?php echo $lFName; ?>'s Subjects with study material Inside.</h3>
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
                    href="#study-<?php echo trim(str_replace(' ','',$childSubj->subjectName ))  .''. $counter.''.$childSubj->learnerID; ?>">
	                <div class="panel-heading">
                    <h4 class="panel-title panel-disc-title">
                      <span class="glyphicon glyphicon-folder-close"></span>
                       <img class="pull-right arrows" 
                          src="<?php echo base_url('assets/images/arrow_down2.png'); ?>" alt="">
                          <span><?php echo $childSubj->subjectName; ?></span>
                    </h4>
                  </div>
              	</a>
            <div id="study-<?php echo trim(str_replace(' ','',$childSubj->subjectName ))  .''. $counter.''.$childSubj->learnerID; ?>"  
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
                      <th>Download</th>
                    </tr>
                  </thead>
            		<tbody>
            			<?php
                //check if the query is not empty
                  if (!empty($all_learner_material)) {
                    //declare new array to hold learner material
                    $learner_study = array();
                    //loop thru material and look for all the matching by learner subjects
                    foreach ($all_learner_material as $all_material) { 
                      if ($childSubj->clsID == $all_material->clsID) { 
                          //if found a matching record, load it into array
                          $learner_study[] = $all_material;
                      }
                    }
                    if (!empty($learner_study)) {
                      foreach ($learner_study as $learnerMaterial) { 
                          //prepare time for post
                          $post_date = strtotime($learnerMaterial->publishDate);
                          $now = time(substr($learnerMaterial->publishDate, 11));
                          $units = 2; ?>
                   		<tr >
                        <td>
                      		<a href="#"
                            data-study_id ="<?php echo $learnerMaterial->studyID;?>" 
                            data-read_title ="<?php echo $learnerMaterial->studyTitle;?>" 
                            data-read_pd ="<?php echo timespan($post_date, $now, $units);?> ago"  
                            data-read_desc ="<?php echo $learnerMaterial->materialDesc;?>" 
                            data-read_path ="<?php echo $learnerMaterial->filePath;?>" 
                            class="viewGuardStudy" data-toggle="modal" data-target=".studyRead">
                            <span class="glyphicon glyphicon-folder-open text-primary glyphicon-green"></span>
                                <?php echo character_limiter($learnerMaterial->studyTitle,5); ?>
                          </a>
                      	</td>
                        <td class="hidden-xs"><?php echo word_limiter($learnerMaterial->materialDesc,5) ;?></td>
                        <td class="hidden-xs"><?php echo timespan($post_date, $now, $units) ;?> ago</td>
                        <td class="text-center">
                          	<?php 
                          //check if file exist
                          if (file_exists($learnerMaterial->filePath)) { ?>
                           	<a href="<?php echo base_url().'Academy/download/'.$learnerMaterial->fileID; ?>">
                                <span class="glyphicon glyphicon-download fa-2x" title="Download File"></span>
                            </a>
                            <?php  
                          }else { ?> 
                    		    <a href="#">
                              <span class="glyphicon glyphicon-minus-sign fa-2x" title="No File"></span>
                            </a>
                        </td>
                            <?php  
                            }
  						              ?>
                      </tr>
                      	<?php  
  	                      }//end foreach learnerMaterial
  	                }else{ //else of EMPTY learner_study
  	                  ?>
                      <tr>
                      	<td colspan="2">No study material</td>
                      </tr>
  	                   <?php  
  	                }//end foreach learner material
  	              }//end IF EMPTY all_learner_material
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
      <!--Table responsible for displaying study material per subject-->
      <?php 
     //if the search returned null
    }else{ ?>
        <span>No subjects</span>
    <?php } ?>
    
