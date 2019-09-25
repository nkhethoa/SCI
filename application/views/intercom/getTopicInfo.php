<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php
if(!empty($table)){
                foreach ($table as $tbl) { ?>
                    <div class="forum-list rsow colours" class="topicResults">
                        <div class="row forum-list-spacer">
                            <div class="col-md-1"><i class="fa fa-comments fa-2x icons"></i></div>
                            <div class="col-md-5 d-flex flex-column forum-title">
                        <div class="title "><a href="#" class="topicBtn" id="<?php echo $tbl->gtID; ?>"><?php echo $tbl->gtTitle; ?></a></div>
                    <div class="descritiption"><em><?php echo $tbl->gtDescr; ?></em></div>
                                </div>
                  <?php
                if(isset($built_table) && ($built_table != NULL)){
                    $totalComm=0;
                    $totalUser=0;
                    foreach ($built_table as $built) { 
                    if($topic->gtID == $built->gtID){ 
                        $totalComm += $built->userCount;
                        $totalUser += 1;
                      }
                    }
                    ?>
                        <div class="col-md-1 topics "><?php echo $totalComm; ?></div>
                            <div class="col-md-1 posts "><?php echo $totalUser; ?></div>
                            <div class="d-flex flex-row forum-last-post col-md-4">
                            <div class="info d-flex flex-column forum-title hidden-xs-down">
                        <div class="title "><strong><u>Posted By</u></strong>
                        <div class="pull-right">
                        <a href="#" type="button" id="editingDiscuss" data-toggle="modal" data-target="#myModalEditDisc" data-discr="<?php echo $topic->gtDescr?>" data-gtname="<?php echo $topic->gtTitle; ?>" data-creat="<?php echo $topic->topicCreator; ?>" data-gtident="<?php echo $topic->gtID; ?>" data-time="<?php echo $topic->topicDate; ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                            <a href="#" data-discussID="<?php echo $topic->gtID; ?>" type="button" class="btn btn delDiscussion" name="trashingT" title="deleteT" data-toggle="modal" data-target="#myModalDiscussion"><i class="glyphicon glyphicon-trash"></i></a>
                                </div>
                                </div>
                            <div class="author "><?php echo $topic->creatorName; ?></div>
                            <div class="time "><?php echo $topic->topicDate; ?></div>
                        </div>
                    </div>
                   <?php } ?>
                    </div>
                    </div>
             <?php    }
            }else{ ?>
                <h2 class="text-center"><b>NO TOPICS</b></strong></h2>          
          <?php  } ?>

          ?>
