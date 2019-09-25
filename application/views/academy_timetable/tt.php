<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="alert-msgTime"></div>
    <!-- Mobile version of timetable-->
    <div class="well well-sm visible-xs">
      <div class="table table-responsive ">
        <table class="table table-responsive">
          <!--Main loop -->          
          <thead>
            <tr class="success">
              <th>
                <b>Day/Time</b>
              </th>
              <th>
                <b>Subject Name</b>
              </th>
            </tr>
            <?php            
          if(isset($weekDays)){
            foreach ($weekDays as $day){ ?>
            <tr>
              <th colspan="2"><?php echo $day->wdName ?></th>
            </tr>
          </thead>
          <tbody>
            <?php 
              if(isset($timeDays)){
                foreach ($timeDays as $time) { 
                  if(isset($active_tt_schedule)){
                    foreach ($active_tt_schedule as $classes) {
                      if(($classes->what_timeID == $time->what_timeID) && 
                        ($classes->wdID == $day->wdID)){ ?>
                          <tr>
                            <td><?php echo substr($time->class_startTime,0,5);?></td>
                            <td class="del_btn"
                                data-what_timeid ="<?php echo $time->what_timeID ?>"
                                data-what_wdid ="<?php echo $day->wdID ?>"
                                data-what_clsid ="<?php echo $classes->clsID ?>" 
                              >
                              <?php echo substr($classes->subjectName,0,3).' '.
                                  substr($classes->cgName,-1,1).'-'.substr($classes->levelDesc,-1,1); ?>
                              <span 
                                class="glyphicon glyphicon-trash none delete_tt_subj" 
                                title="Remove subject"
                                data-toggle="modal" 
                                data-target="#tt-confirm" 
                                data-placement="top">
                              </span>
                            </td>
                          </tr>
                    <?php 
                      }
                    }
                  }
                }
              }
              ?>
          </tbody>
          <?php
          }
        } ?>
        </table>
    </div>
  </div><!--/ Mobile version of time table-->
    <table class="table table-responsive assignTable tblslim hidden-xs">
      <thead class="">
        <tr class="row_content">
          <th class="head_content">Time</th>
          <?php
          //echo var_dump($active_tt_schedule);
          if(isset($weekDays)){
            foreach ($weekDays as $day){ ?>
              <th class="head_content">
                <?php echo $day->wdName ?>
              </th>
              <?php
            }
          } ?>
        </tr>
      </thead>
      <tbody class="body_content">
        <?php
        if(isset($timeDays)){
          foreach ($timeDays as $time) { ?>
          <tr class="row_content">
            <td class="box_content"><?php echo substr($time->class_startTime,0,5);?></td>
            <?php 
            if(isset($weekDays)){
              foreach ($weekDays as $day) {
                  //initialise the array to hold found data for the time table
                  $array_td = array(); 
                if(isset($active_tt_schedule)){
                  foreach ($active_tt_schedule as $classes) {
                    if(($classes->what_timeID == $time->what_timeID) && 
                      ($classes->wdID == $day->wdID)) { 
                        //build an array with the identified class
                        $array_td[] = $classes;   
                    }//if statement with && end here 
                  }//foreach loop schedule end
                }//schedule if isset end
                //loop thru the array created to hold the subjects of the user [learner or teacher]
                if (!empty($array_td)) {
                    foreach ($array_td as $class) { 
                      if (($class->wdID == $day->wdID)){ ?>
                        <td class="box_content del_btn text-center"
                            data-what_timeid ="<?php echo $time->what_timeID ?>"
                            data-what_wdid ="<?php echo $day->wdID ?>"
                            data-what_clsid ="<?php echo $class->clsID ?>" 
                          >
                          <?php echo substr($class->subjectName,0,3).' '.
                              substr($class->cgName,-1,1).'-'.substr($class->levelDesc,-1,1); ?>
                          <span 
                            class="glyphicon glyphicon-trash none delete_tt_subj" 
                            title="Remove subject"
                            data-toggle="modal" 
                            data-target="#tt-confirm" 
                            data-placement="top">
                          </span>
                        </td>
                        <?php
                      }else{ //if array_td ?>
                    <td class="box_content addClass" 
                          data-what_timeid ="<?php echo $time->what_timeID ?>"
                          data-what_wdid ="<?php echo $day->wdID ?>"
                          data-toggle="modal" 
                          data-target="#classDayModal" 
                          data-placement="top"
                          >
                    </td>
                  <?php 
                  }
                    }//end foreach array_td
                  }else{ //if array_td ?>
                    <td class="box_content addClass" 
                          data-what_timeid ="<?php echo $time->what_timeID ?>"
                          data-what_wdid ="<?php echo $day->wdID ?>"
                          data-toggle="modal" 
                          data-target="#classDayModal" 
                          data-placement="top"
                          >
                    </td>
                  <?php 
                  }
              }//foreach end weekdays
            } //weekdays isset end
            ?>
            </tr>

            <?php
          }//time days end
        }//if isset timedays end 
        ?>
      </tbody>
    </table>