<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="panel-header"></div>
         <div class="stats">
            <p id="statDetails">Your todo History has
              <?php 
                if ($taskCount == 1) { ?>
                  <?php echo $taskCount; ?> item. <br>
                  <?php 
                }else if ($taskCount > 1) { ?>
                  <?php echo $taskCount; ?> items. <br>
                  <?php
                }
               ?>
              <br>
            </p>
         </div>     
         
        <div class="todoList">
    <!-- <button type="button" id="buttonDelete" class="btn btn-danger buttonDelete" title="Delete All">X</button> -->
          <div class="todo_History_alert"></div>

          <!--  <input type="checkbox" name="" id="select_all_cc">Check All</input> -->
          <?php 
          if($tasks!=NULL){
            //loop thru todos, and build the list with small containers
            foreach($tasks as $task){ 
              $post_date = strtotime($task->completedDate);
              $now = time(substr($task->completedDate, 11));
              $units = 2;
              ?>

              <div class="todoItem"><!-- <input type="checkbox" class="checkBoxClass" id="<?=$task->todolistID?>"> --><button type="button" data-hid="<?=$task->todolistID?>" class="btn btn-danger btn-circle pull-right remHistory" title="Remove from History"><i class="glyphicon glyphicon-remove-circle"></i></button>
                 <!--  //check if the task has been completed -->
                 <?php  
                  if($task->completed == 1){ ?>
                    <div class="todoItemText">
                      <?php echo character_limiter($task->descriptionName,23); ?>
                    </div> 
                    <?php     
                  } ?>
                  <div class="itemControls">
                    <div id="<?php echo $task->todolistID; ?>" class="complete">
                      Completed: &nbsp;&nbsp;
                      <?php echo timespan($post_date, $now, $units); ?> ago
                    </div>
                    <!-- //display delete button if the task is marked as completed -->
                  </div>
              </div>
              <?php 
            }//close foreach
            //echo json_encode($myTodos);
          }else{ ?>
           <span>List is empty</span>
           <?php   
          } 
          ?>
        <!-- <ul id="pagin" class="text-center"></ul> -->

    
