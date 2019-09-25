<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

//check if  request didn't returne NULL
      if (!empty($topics)) {
        foreach ($topics as $topic) {
          if ($dgID == $topic->dgID) { ?>
          <tr>
            <td>
              <span class="glyphicon glyphicon-pencil text-primary"></span>
              <a href="#" 
                  data-toggle="modal" 
                  data-target="#commentsModal"
                  data-id="<?php echo $topic->topicID; ?>" 
                  class="viewTopicComments"><?php echo $topic->topicTitle; ?> 
              </a>
            </td>
            <?php 
            //set the time for when the topic was posted
            $tpost_date = strtotime($topic->tPostDate);
            $now = time(substr($topic->tPostDate, 11));
            $units = 2; ?>
            <td>&nbsp;
              <span><strong>Posted:</strong>&nbsp;<?php echo timespan($tpost_date, $now, $units); ?> ago</span>
            </td>
            <?php 
            //get comments count
            $commentCount= 0;
            if (isset($topic_comments) && !empty($topic_comments)) {
              foreach ($topic_comments as $comments){
                if($topic->topicID == $comments->topicID ){
                  $commentCount++;
                }
              }
            }
            //check if there are any results 
            if ($commentCount > 0) { ?> 
              <td>
                <span>This topic has 
                  <span class="badge badgeColor">
                    <?php echo $commentCount; ?>
                  </span>&nbsp;comments
                </span>
              </td>
              <?php 
            }else{ ?>
              <td>
                <span>Be the first to comment...</span>
              </td>
              <?php 
            }
            ?>
           <!--  //user buttons for topic -->
            <td>
                <a href="#" 
                    data-id="<?php echo $topic->topicID; ?>" 
                    data-discid="<?php echo $topic->dgID; ?>" 
                    data-title="<?php echo $topic->topicTitle; ?>" 
                    data-body="<?php echo $topic->tDescription; ?>"
                    class="viewTopicComments" 
                    data-toggle="modal" 
                    data-target="#commentsModal">
                    <span class="glyphicon glyphicon-comment glyphicon-green" title="Join discussion"></span>
                </a>
                <?php 
                //only the topic creator can delete or edit topic
                if ($_SESSION['userID'] == $topic->topicAuthorID 
                    OR strpos(identify_user_role($_SESSION['userID']), 'teacher')  !== false) { ?>
                  <a href="#" 
                      data-id="<?php echo $topic->topicID; ?>" 
                      data-discid="<?php echo $topic->dgID; ?>" 
                      data-title="<?php echo $topic->topicTitle; ?>" 
                      data-body="<?php echo $topic->tDescription; ?>" 
                      class="editTopic" 
                      data-toggle="modal" 
                      data-target="#topicModal">
                    <span class="glyphicon glyphicon-edit glyphicon-green" title="Edit Topic"></span>
                  </a>
                  <a href="#" 
                      data-id="<?php echo $topic->topicID; ?>" 
                      data-title="<?php echo $topic->topicTitle; ?>" 
                      data-discid="<?php echo $topic->dgID; ?>" 
                      class="deleteTopic">
                    <span class="glyphicon glyphicon-trash glyphicon-red" title="Delete Topic"></span>
                  </a>
                  <?php 
                }
                ?>
            </td>
          </tr>
          <?php 
        }}
      }else { ?>
        <tr>
          <td class="glyphicon-green">
            <strong>Be the first one to add Topic to this discussion category.</strong>
          </td>
        </tr>
        <?php  
      }

    ?>