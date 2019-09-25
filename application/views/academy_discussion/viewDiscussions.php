<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

    $counter=1;
    //check if anything was retuned
    if (!empty($categories)) {
        //loop through the content from the table based on the query
        foreach ($categories as $category) {
            //show how long the file has been online
            $post_date = strtotime($category->dgPostDate);
            $now = time(substr($category->dgPostDate, 11));
            $units = 2; ?>

              <!--  //start table row that will be appended to the html table -->           
              <div class="panel panel-default ">
                <div class="panel-heading">
                  <h4 class="panel-title panel-disc-title">
                    <span class="pull-right">
                      <!-- //user option buttons for the discussion group -->
                      <a href="#" 
                          data-id="<?php echo $category->dgID; ?>" 
                          data-title="<?php echo word_limiter($category->dgTitle,10); ?>" 
                          data-body="<?php echo $category->dgBody; ?>"
                          class="viewCategory" 
                          data-toggle="modal" 
                          data-target="#discModal">
                          <span class="glyphicon glyphicon-eye-open fa-2x glyphicon-grey"></span>
                      </a>
                      <?php 
                      //only the teacher can edit or delete his own topic
                      if ((strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false) 
                          && ($_SESSION['userID']==$category->tUserID)) { ?>
                        <a 
                            href="#" 
                            data-id="<?php echo $category->dgID; ?>" 
                            data-cls="<?php echo $category->clsID; ?>" 
                            data-title="<?php echo $category->dgTitle; ?>" 
                            data-body="<?php echo $category->dgBody; ?>" 
                            data-subname="<?php echo $category->subjectName; ?>" 
                            class="editCategory" 
                            data-toggle="modal" 
                            data-target="#discModal">
                            <span class="glyphicon glyphicon-edit fa-2x glyphicon-green"></span>
                        </a>
                        <a href="#"  
                            data-subname="<?php echo $category->subjectName; ?>" 
                            data-cls="<?php echo $category->clsID; ?>" 
                            data-id="<?php echo $category->dgID; ?>" 
                            data-title="<?php echo $category->dgTitle; ?>" 
                            class="deleteCategory">
                            <span class="glyphicon glyphicon-trash fa-2x glyphicon-red"></span>
                        </a>
                        <?php 
                      }
                      ?>
                    </span>

                    <a data-toggle="collapse" 
                      data-parent="#<?php echo trim(str_replace(' ','',$subName )); ?>" 
                      href="#<?php echo trim(str_replace(' ','',$subName )).''
                              .character_limiter(rtrim(str_replace(' ','',$category->dgTitle ),','),15) .''
                              .$counter; ?>">
                      <span class="glyphicon glyphicon-folder-close"></span>
                      <span><?php echo $category->dgTitle; ?></span><br>
                      <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <strong>Posted:</strong>&nbsp;<?php echo timespan($post_date, $now, $units); ?> ago</span>
                    </a>
                  </h4>
                </div>
                <div id="<?php echo trim(str_replace(' ','',$subName )).''
                            .character_limiter(rtrim(str_replace(' ','',$category->dgTitle ),','),15).''
                            .$counter; ?>" class="panel-collapse collapse">
                  <div class="panel-body">
                    <!-- //display add button -->
                    <table class="table table-responsive">
                      <tr>
                        <td>
                          <a href="#" 
                              data-id="<?php echo $category->dgID; ?>" 
                              class="addTopic" data-toggle="modal" 
                              data-target="#topicModal">
                            <span class="glyphicon glyphicon-plus-sign fa-2x glyphicon-green" title="Add Topic"></span>
                          </a>
                        </td>
                      </tr>
                      <tbody id="topicscomments <?php echo $category->dgID; ?>">
                        <?php
                        //send category id so that topics can matched to each by dgID
                        $data['dgID'] = $category->dgID;
                        if (!empty($topics)) {
                          foreach ($topics as $topic) {
                            if ($category->dgID == $topic->dgID) {
                              //branch out to display topics
                              $this->load->view('academy_discussion/displayTopics',$data);
                            }
                          }
                        } 
                       ?>
                      </tbody>
                    </table><!-- /panel body table-->
                  </div><!-- /panel body-->
                </div>
              </div> <!-- /panel-->
              <?php  
              $counter++; 
        }//end of main foreach
        
      //if nothing brought back, print this    
      }else{
          if(identify_user_role($_SESSION['userID']) === 'learner'){ ?>
            <span class="glyphicon-red"><b>Nothing at the moment.</b></span>
            <?php 
          }else{ ?>
            <span class="glyphicon-red"><b>Please add category so that learners can also add topics.</b></span>
            <?php 
          }  
      }

    ?>