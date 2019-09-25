<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 
    <table class="table table-responsive">
        <thead>
        <tr>
          <?php 
          //check if the user is a learner
          //Then print the correct headings
          if (identify_user_role($_SESSION['userID']) == 'learner') { ?>
            <th class="visible-xs">Attend Date</th>
            <th class="hidden-xs">Date Absent (Y-M-D)</th>
            <th class="visible-xs">Time</th>
            <th class="hidden-xs">Time Absent (H:m)</th>
              <?php  
          }else { ?>
            <th class="visible-xs">ID</th>
            <th class="hidden-xs">Learner ID</th>
            <th class="hidden-xs">First Name</th>
            <th class="hidden-xs">Last Name</th>
            <th class="visible-xs">Name</th>
            <?php 
              if ($reason == 2) { ?>
                <th class="hidden-xs">Attendance Date</th>
                <th>
                  Attendance:<br>
                  <small>(<em>Present by Default</em>)</small>
                </th>
                <?php 
              }elseif($reason == 1 || $reason == 0){ ?>
                <th class="hidden-xs">Average Attendance</th>
                <th class="visible-xs">AVG. %</th>
                <?php 
              }
          }  
          ?>
        </tr>
        </thead>
      <tbody>
        <?php
      if (!empty($learnerList)) {
        $counter = 1;
        //loop thru learner list and print their names
        foreach ($learnerList as $list) {
            //print this section for teacher subjects
          if (strpos(identify_user_role($_SESSION['userID']), 'teacher') !== false) { ?>
              <!-- write table details -->
              <tr>   
                <td><?php echo $list->learnerID; ?></td>
                <td class="hidden-xs"><?php echo $list->fName; ?></td>
                <td class="hidden-xs"><?php echo $list->lName; ?></td>
                <td class="visible-xs"><?php echo $list->lName.', '.$list->fName; ?></td>
            <?php 
          }
            //check which view option is selected
            //VIEW MODE
            if ($reason == 1 || $reason == 0) {
                //$attend_count = 0;//get accumulative attandance for this subject
                $countPresent = 0;// to hold of count number of times present
                $countAbsent = 0; // to hold count of times absent
                $totalAttend = 0; //to hold number of expected total attendance
                //array to keep record of which the learner waas absent
                $array_absent = array();
                //count how many times was the learner present and absent
                foreach ($existing_attend as $value) {
                //getting learner attendance for specific subject
                if ($clsID == $value->clsID) {
                  //if clsid match the current subject
                  //check if the learner from the learner list match the learner on the attendance of the subject
                  if ($list->learnerID == $value->learnerID) {
                    //if present, keep count
                    if ($value->attendStatus == 1) {
                      $countPresent++;
                    }else {
                      $countAbsent++; //=+ $value->attendStatus;
                      //take that record absent and keep it in array
                      $array_absent = $value;
                    }
                  }//end if clsID
                  }//end if learnerID
                }//end foreach existing_attend
                //echo var_dump($existing_attend);
                //avoid division by zero before printing
                if ($attend_count > 0) {
                  //calculate the percentage of overall attendance
                  $totalAttend = round(($countPresent/$attend_count)*100,0); ?>
                  <td>
                    <?php echo $totalAttend; ?> %
                  </td>
                  <?php 
                  if(identify_user_role($_SESSION['userID']) == 'learner') {
                    if (!empty($array_absent)) {
                      foreach ($array_absent as $absent) { ?>
                        <tr>
                          <td>
                            <b>You were absent on <?php echo substr($absent->attendDate,0,10); ?></b>
                          </td>       
                          <td>
                            Attendance was marked round about this time:
                            <b> <?php echo substr($absent->attendDate,11); ?></b>
                          </td>      
                        </tr>
                        <?php 
                      } 
                    }
                      ?> 
                    <tr></tr>
                    <tr>   
                      <td colspan="2" class="hidden-xs">
                        Average attendance is 
                          <b><?php echo $totalAttend; ?> %</b>
                      </td>   
                      <td colspan="2" class="visible-xs">
                        Attendance average: 
                        <b> <?php echo $totalAttend; ?> %</b>
                      </td>   
                    </tr>
                    <?php 
                  }
                }else {
                    //print attendance percentage
                    //$printAttend .= '<td>'.$totalAttend.' %';
                    if(identify_user_role($_SESSION['userID']) == 'learner') { ?>
                      <tr>   
                        <td colspan="2">Not yet marked.</td>   
                      </tr>
                      <?php 
                    }else{ ?>
                        <td>
                          <?php echo $totalAttend; ?> %
                        </td>   
                      </tr>
                      <?php 
                    }
                }
            }//end reason = 1
            //UPDATE ATTENDANCE
            elseif ($reason==2){
                foreach ($attend as $status) {
                    //print each button if it matches with the learner
                    if ($list->learnerID == $status->learnerID) { ?>
                      <td class="hidden-xs">
                        <?php echo $status->attendDate; ?>
                      </td>
                      <td>
                        <div class="btn-group" data-toggle="buttons">
                          <button type="button" id="present" value="1" title="Present" 
                                  class="btn btn-success    
                                  <?php  echo ($status->attendStatus == 1 
                                          && substr($status->attendDate,0,10) == date('Y-m-d')) ? ' active ' :'' ;?> markAttend"
                                  data-lID="<?php echo $list->learnerID;?>" 
                                  data-clsID="<?php echo $clsID;?>"
                                  data-rowID="<?php echo $status->attendID;?>">
                                <input type="radio"  name="attend" autocomplete="off">
                                <span class="glyphicon glyphicon-ok"></span>
                          </button>
                          <button type="button" id="absent" value="0" title="Absent" 
                                  class="btn btn-danger 
                                  <?php echo ($status->attendStatus == 0 
                                      && substr($status->attendDate,0,10) == date('Y-m-d')) ? ' active ' :'' ;?> markAttend" 
                                  data-lID="<?php echo $list->learnerID; ?>"   
                                  data-clsID="<?php echo $clsID; ?>"
                                  data-rowID="<?php echo $status->attendID; ?>">
                                <input type="radio"  name="attend" autocomplete="off">
                                <span class="glyphicon glyphicon-remove"></span>
                          </button>
                        </div>
                      </td>
                    </tr>
                    <?php 
                    }  
                }//end foreach reason 2
            }//end reason 2
        $counter++;
        }//end main foreach
        ?>
        </tbody>
        </table>
        <?php 
    }else { ?>
      <tr>
        <td colspan="4" class="hidden-xs glyphicon-red ">
          <b>We cannot find any students for this class. Please check with your admin.</b>
        </td>
        <td colspan="3" class="visible-xs glyphicon-red ">
          <b>No learners found.</b>
        </td>
      </tr>
    </tbody>
    </table>
  <?php 
    }
  ?>