<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <a  href="<?php echo base_url('Intercom')?>">Intercom &nbsp;
          <div class="noti_Counter"></div>
            <?php
              //check if messages and announcements arrays are set
              if(isset($messages) && isset($announces) ){
              
                //initialise the variable that will count messages inbox
                $totalInboxCount = 0;
                //initialise the variable that will count total messages and annoucements
                $totalNotific = 0;
                //initialise the variable that will count announcements
                $totalAnnCount = 0;
                //go thru messages and sift specific ones for this user who is logged in
                if(!empty($messages)){
                foreach ($messages as $message) {
                  if($message->read == 0 && $message->receiverID == $_SESSION['userID']){
                    //keep count of messages
                     $totalInboxCount++; 
                  }
                 }
                } 
                //go thru announcements and keep count of those within 7 days
                if(!empty($announces)){
                foreach ($announces as $countAnn) {
                  if (strtotime($countAnn->annDate) > strtotime('-7 day')) {
                    //keep count of the announcements
                    $totalAnnCount++; 
                  }
                 }
                }
                //tally total of message and announcements  
                $totalNotific = $totalInboxCount + $totalAnnCount; 
                if ($totalNotific > 0) { ?>
                  <div id="noti_Button" style="color:white;text-align:center;">
                    <span class="unread">
                      <?php echo $totalNotific; ?>
                    </span>
                  </div>
                  <?php
                }
              }
              ?>
          </a>
          <div class="col-sm-3" id="notifications">
            <h4 style="color:black; text-align: center;">
              <strong>Notifications</strong>
            </h4>
            <div style="height:100px">
                <ul class="list-group">
                  <li class="list-group-item justify-content align-items-center" style="color:black;">&nbsp;
                    <strong>Messages</strong>
                    <span class="badge badge-primary badge-pill">
                      <?php echo $totalInboxCount; ?>
                    </span>
                  </li>
                  <li class="list-group-item justify-content align-items-center" style="color:black;">&nbsp;
                    <strong>Announcements</strong>
                    <span class="badge badge-primary badge-pill">
                      <?php echo $totalAnnCount; ?>
                    </span>
                  </li>
                </ul>
          </div>
      </div>
    
