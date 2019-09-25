<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="panel-header">
          <h2 class="text-centerr">
            <b>Todo List<b>
          </h2>
        </div>
        <div class="stats">
          <p id="statDetails">Your todo list has
            <?php 
            if ($taskCount == 1) { ?>
              <?php echo $taskCount; ?> item. <br>
              <?php 
            }else if ($taskCount > 1) { ?>
              <?php echo $taskCount; ?> items. <br>
              <?php  
            }
            ?>
            You have <span class="badge badgeColor">
              <?php 
              if ($ct == 1) { ?>
                <?php echo $ct; ?></span> item to complete!
                <?php 
              }else if ($ct > 1) { ?>
                <?php echo $ct; ?></span> items to complete!
                <?php
              } 
              ?>
              <br>
              <span class="text-center">
                <a  href="#" class="history" data-toggle="modal" data-target="#history">View History</a>
              </span>
          </p>
        </div>
        <div class="todoList">
          <?php 
          if($tasks!=NULL){
            //loop thru todos, and build the list with small containers
            foreach($tasks as $task){ ?>
              <div class="todoItem">
                <!-- //check if the task has been completed -->
                <?php 
                if($task->completed == 0){ ?>
                  <div class="todoItemText">
                    <?php echo character_limiter($task->descriptionName,23); ?>
                  </div>
                  <?php     
                }else{ ?> 
                  <!-- //strike thru completed and ready to be deleted task -->
                  <div class="todoItemText">
                      <del class="colorStrike">
                        <span style="color:black;" >
                          <?php echo character_limiter($task->descriptionName,23); ?>
                        </span>
                      </del>
                  </div>
                  <?php  
                } ?>
                <div class="itemControls">
                  <div id="<?php echo $task->todolistID; ?>" class="complete">
                    <i class="fa fa-check fa-lg" title="Mark as Completed"></i>
                  </div>
                  <!-- //display delete button if the task is marked as completed -->
                  <?php 
                  if($task->completed == 1){ ?>
                    <div id="<?php echo $task->todolistID; ?>" class="delete todoDelete">
                      <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                    </div>
                    <?php 
                  } ?>
                </div>
              </div>
              <?php 
            }//close foreach
          }else{ ?>
            <span>List is empty</span>
            <?php  
          } ?>
        </div>
        <?php 
        if(isset($taskCount) && $taskCount < 8){ ?>
          <div class="controls"> 
            <form name="frmTodoItem" id="frmTodoItem" method="POST">
              <input type="text" 
                  name="sTaskDescriptioen" value="" 
                  id="sTaskDescriptioen" 
                  class="form-control tboxTodoItem"/>
              <span>
                <small>
                  <em>Be short, but descriptive.</em>
                </small>
              </span>
            </form>
            <div id="submit_todo" class="submit">Add</div>
          </div>
          <?php  
        }else{ ?>
          <div class="todoItemText">
            <em>You have reached your limit. <br>Please remove completed items first.</em>
          </div>
          <?php 
        }
        ?>
    
