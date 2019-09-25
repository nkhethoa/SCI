<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 


        if (isset($topics)) { 
          $changeColor=1;
          foreach ($topics as $topic) { ?>
            <ul class="list-group">
              <li class="list-group-item">
                <span class="glyphicon glyphicon-pencil text-primary"></span>
                <span>
                  <strong>
                    <u>
                      <em><?php echo $topic->topicTitle; ?></em>
                    </u>
                  </strong>
                </span>
                <br><br>
                <span>
                  <strong>Brief description:&nbsp;&nbsp;</strong><?php echo $topic->tDescription; ?>
                </span>
              </li>
              <li>
                <ul class="list-group comment-list">
                  <?php 
                    //loop thru the comments based on the topic
                  foreach ($comments as $comment) {
                    //set the time for when the comment was posted
                    $cpost_date = strtotime($comment->tcPostDate);
                    $now = time(substr($comment->tcPostDate, 11));
                    $units = 2;
                    //prnt the comment details
                    if ($changeColor % 2) { ?> 
                      <li class="list-group-item list_group-item">
                          <span class="glyphicon glyphicon-comment text-success"></span>
                          &nbsp;<?php echo $comment->tcFName; ?> said...
                          <span class="pull-right">
                            <strong>Posted:</strong>&nbsp;<?php echo timespan($cpost_date, $now, $units); ?> ago
                          </span><br>
                          <span class="comment-list">&nbsp;<?php echo $comment->tcAuthorMsg; ?></span>
                      </li>
                      <?php     
                    }else { ?>  
                      <li class="list-group-item list_group-item" style=background-color:#d3d3d31f;>
                          <span class="glyphicon glyphicon-comment text-success"></span>
                          &nbsp;<?php echo $comment->tcFName; ?> said...
                          <span class="pull-right">
                            <strong>Posted:</strong>&nbsp;<?php echo timespan($cpost_date, $now, $units); ?> ago
                          </span>
                          <br><span class="comment-list">&nbsp;<?php echo $comment->tcAuthorMsg; ?></span>
                      </li>
                      <?php  
                    }
                  } 
                    ?>   
                </ul>    
              </li>
            </ul>
            <?php  
            $changeColor++;
          }
      }
?>
    
