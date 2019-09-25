    <?php
    defined('BASEPATH') OR exit('No direct script access allowed'); 
    ?>

    <div class="inbox-head">
      <h3><b>Sent</b></h3>&nbsp;
    </div>
    <br>
    <input type="text" class="form-control pull-right nav-search" name="outboxMsg" value="" id="sentMainViewSearch" placeholder="Search message">
    <br>
    <br><br>
    
    <span data-toggle="buttons">
      <label class="btn btn-info">
        <input type="checkbox" id="select_all_sent"  />All
        <span class="glyphicon glyphicon-check" title="Select All Sent"></span>
      </label>
      <!--<i class="glyphicon glyphicon-cog fa-2x"></i>-->
    </span>
    <a href="#" name="btn_delete_sent" class="btn btn-warning" id="delSelSent">Delete Marked</a><br>

    <div class="alert-msgPage"></div>
    <div id="alert-msg"></div>
    <div id="alert-msgrepl"></div>
    <div class="modal-sent"></div>
    <div id="sent-success"></div>
    <br>

    <table class="table table-striped sizweTd">
      <tr>
        <th>Mark:</th>
        <th>To:</th>
        <th>Email:</th>
        <th>Date:</th>
        <th>Subject:</th>
        <th>Message:</th>
        <th>Priview:</th>
        <th>Delete:</th>
      </tr>
      <?php
      if($outboxes && $direct_messages){
        //create an array with sent messages
        $delivered = array();   
        foreach ($direct_messages as $direct_message) { 
          foreach ($outboxes as $sent) {
            //find all matching emails and store them in an array
            if($direct_message->dimID == $sent->sentID){
              $delivered[] = $sent;
            }
          } 
        }
        ?>

        <tbody class="sentMs">
          <?php 
          $multi_msg = 0; //variable to keep count of multiple messages
          foreach ($outboxes as $outbox) {
            //print the first email of many 
            if ($multi_msg < 1  ) { 
              //variable to hold the names of the receivers
                $names = '';
                if (!empty($delivered)) {
                  foreach ($delivered as $value) {
                    if ($value->sentID ==  $outbox->sentID) {
                      $names .= $value->firstname. " " .$value->lastname.', ';
                      $multi_msg++; //keep count of multiple messages
                    }
                  }
                }
                ?>
            <tr class="portlet-body readSentMsg" data-target="#mySentMsgModal" data-toggle="modal" id="preview" data-email="<?php echo $outbox->email; ?>"
                data-name="<?php echo $outbox->firstname. " ".$outbox->lastname; ?>"
                data-subject="<?php echo $outbox->title; ?>"
                data-date="<?php echo $outbox->date; ?>"
                data-body="<?php echo $outbox->body; ?>"
                data-sntid="<?php echo $outbox->sentID; ?>"
                data-placement="top">
            <td class="view-message">
              <input type="checkbox" name="sentID[]" class="del_sent_message" value="<?php echo $outbox->sentID; ?>">
            </td>
            <td>
              <i><?php echo rtrim($names,', '); ?></i>
            </td>
            <td>
              <i><?php echo $outbox->email; ?></i>
            </td>
            <td>
              <i><?php echo $outbox->date ?></i>
            </td>
            <td>
              <i><?php echo $outbox->title ?></i>
            </td>
            <td>
              <i><?php echo $outbox->body ?></i>
            </td>
            <td>
            <a href="<?php echo $outbox->sentID; ?>" type="button" 
              data-toggle="modal" data-target="#myModalRead" 
              class="glyphicon glyphicon-eye-open fa-2x previewSent" 
              style="color:#00688b;"
              data-sentemail="<?php echo $outbox->email?>"
              data-sentname="<?php echo $outbox->firstname. " " .$outbox->lastname ?>"
              data-senttitle="<?php echo $outbox->title?>"
              data-sentdate="<?php echo $outbox->date ?>"
              data-sentbody="<?php echo $outbox->body ?>"
              data-sentid="<?php echo $outbox->sentID ?>">
            </a>
          </td>
          <td>
            <a href="#" 
            data-sentMsgID="<?php echo $outbox->sentID ?>" 
            class="delSentMsgs" name="trashingSent" 
            title="delete" data-toggle="modal" 
            data-target="#myModalSent">
            <i class="fa fa-trash fa-2x" style="color:red;" aria-hidden="true"></i>
          </a><br>
        </td>
      </tr>
      <?php 
       
     }
     //slowly reset count of multiple to zero 
     $multi_msg--;
    } ?>
    </tbody>
    <?php
    //}
    }
    ?>
    </table>
    <br>
    <br>
    <div class="text-center">
      <h4>Messages Sent:&nbsp;&nbsp;<?php echo ($outCount>0)? $outCount:"<span style='color:navy'>No Results</span>";?></h4>
      <div id="pagination_link">
        <?php 
        echo $out_links;
        ?>
      </div>
    </div>
    <?php
    $this->load->view('intercom/askDeleteSentMsg');
    ?>
    <?php
    $this->load->view('intercom/readMsgModal');
    ?>