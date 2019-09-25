<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
  <h3 class="well well-md"><img src="assets/images/intercomRecycle.png" style="height:90px;width:90px;" title="Recycle" alt="recyle" ></h3>&nbsp;
  <input type="text" class="form-control pull-right nav-search" name="srchMgs" value="" id="trashMainViewSearch" placeholder="Search message"><br><br><br>
  <div class="modal-restore"></div>
  <div id="event-success"></div>
  <div class="restore-trashPage"></div>
  <div class="modal-restoreErr"></div>
  <div class="sent-trashPage"></div>
  <div class="modal-sentErr"></div>
  <div class="restore-trashAnnPage"></div>
  <div class="modal-annRestoreErr"></div>
  <div class="modal-event"></div>
  <div class="modal-eventErr"></div>
  <br>
  <div class="accordion">
  	<div class="accordion-group">
  		<div class="accordion-heading country">
  			<a class="accordion-toggle" data-toggle="collapse" href="#trashed_messages"><h3 class="well well-sm"><b>Deleted Messages</b><i class="glyphicon glyphicon-envelope pull-right"></i></h3></a>
  		</div>
  		<div id="trashed_messages" class="accordion-body collapse table-responsive">
  			<div class="accordion-inner col-md-12">
  				<span data-toggle="buttons">
        	   	<label class="btn btn-info">
        		<input type="checkbox" id="select_all_inbox"  />All
        		<span class="glyphicon glyphicon-check" title="Select All Inbox"></span>&nbsp;
        </label>
      </span>
      <a href="#" name="btn_restore" class="btn btn-warning" id="restoreSelInboxes">Restore Marked</a>
      <br>
      <h4><b><u>Inbox Messages</u></b></h4>
      	<table class="table">
  					<thead class="mytHead">
  						<tr>
  							<th>Mark</th>
  							<th>Email</th>
  							<th>Subject</th>
  							<th>Date</th>
  							<th>Recycle</th>
  						</tr>
  					</thead>
  					<tbody class="myTbodwy">
  						<?php
  						if(isset($trashedMsg)!=false && !empty($trashedMsg)){
  							foreach ($trashedMsg as $trashMsgDisply) { ?>
  							<tr class="myTr">
  								<td><input type="checkbox" name="ibxID[]" class="restr_inboxes" value="<?php echo $trashMsgDisply->dmID; ?>"></td>
  								<td><?php echo $trashMsgDisply->email; ?></td>
  								<td><?php echo $trashMsgDisply->title; ?></td>
  								<td><?php echo $trashMsgDisply->date; ?></td>
  								<td><a type="button" href="#"  id="restoreInboxID" class="btn" data-inboxid="<?php echo $trashMsgDisply->dmID; ?>" class="btn btn-default" data-toggle="modal" data-target="#myModalRestore"><i class="fa fa-recycle fa-2x"></i></a></td>
                  <?php }
                    }else{ ?>
                    <td colspan="5"><h3><b>NO DELETED INBOX MESSAGES</b></h3></td>
  								</tr>
  					   <?php
  					   } ?>
  				</tbody>
  			</table>
       <br><br>
       <span data-toggle="buttons">
        <label class="btn btn-info">
            <input type="checkbox" id="select_all_sent"  />All
            <span class="glyphicon glyphicon-check" title="Select All Sent"></span>&nbsp;
        </label>
      </span>
      <a href="#" name="btn_restore_sent" class="btn btn-warning" id="restoreSelSent">Restore Marked</a>
        <h4><b><u>Sent Messages</u></b></h4>
        <table class="table">
          <thead class="mytHead">
            <tr>
                <th>Mar</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Recycle</th>
            </tr>
          </thead>
          <tbody class="myTboqdy">
            <?php
             //var_dump($trashedSent);
             if(isset($trashedSent)!=false && !empty($trashedSent)){
              foreach ($trashedSent as $trashSentDisply){ ?>
                <tr class="myTr">
                  <td><input type="checkbox" name="outgoID[]" class="restr_outbox" value="<?php echo $trashSentDisply->sentID; ?>"></td>
                  <td><?php echo $trashSentDisply->email; ?></td>
                  <td><?php echo $trashSentDisply->title; ?></td>
                  <td><?php echo $trashSentDisply->date; ?></td>
                  <td><a type="button" href="#" class="btn" id="restoreSentID" data-outid="<?php echo $trashSentDisply->sentID; ?>" class="btn btn-default" data-toggle="modal" data-target="#myModalRstoreSent"><i class="fa fa-recycle fa-2x"></i></a></td>
                  <?php }
                  }else{ ?>
                  <td colspan="5"><h3><b>NO DELETED SENT MESSAGES</b></h3></td>
                <?php } ?>
          </tbody>
        </table>
  		</div>
  	</div>		
  </div>
  <div class="clearfix">
  </div>
  <div class="accordion-group">
  	<div class="accordion-heading country">
  		<a class="accordion-toggle" data-toggle="collapse" href="#trashed_announcements"><h3 class="well well-sm"><b>Deleted Announcements</b><i class="glyphicon glyphicon-bullhorn pull-right"></i></h3></a>
  	</div>
  	<div id="trashed_announcements" class="accordion-body collapse">
  		<div class="accordion-inner col-md-12">
  			<span data-toggle="buttons">
        <label class="btn btn-info">
        <input type="checkbox" id="select_all_announcement"  />All
        <span class="glyphicon glyphicon-check" title="Select All Announcements"></span>&nbsp;
        </label>
      </span>
      <a href="#" name="btn_restore" class="btn btn-warning" id="restoreSelAnnouncement">Restore Marked</a>
  			<table class="table col-md-12">
  				<thead class="mytHead">
  					<tr>
  						<th>Mark</th>
  						<th>Title</th>
  						<th>Description</th>
  						<th>Start-End Date</th>
  						<th>Recylce</th>
  					</tr>
  				</thead>
  				<tbody class="myTboqdy">
  					<?php
            //var_dump($trashedAnn);
  					if(isset($trashedAnn)!=false && !empty($trashedAnn)){
  						foreach ($trashedAnn as $trashAnnDisply) { ?>
  						<tr class="myTr">
  							<td><input type="checkbox" name="annoID[]" class="restr_announcement" value="<?php echo $trashAnnDisply->annID; ?>"></td>
  							<td><?php echo $trashAnnDisply->title; ?></td>
  							<td><?php echo $trashAnnDisply->body; ?></td>
  							<td><?php echo $trashAnnDisply->annDate; ?></td>
  							<td><a type="button" href="#"  id="restoreAnnID" class="btn" data-announcementid="<?php echo $trashAnnDisply->annID; ?>" class="btn btn-default" data-toggle="modal" data-target="#myModalRestoreAnn">
                                    <i class="fa fa-recycle fa-2x"></i>
                                  </a></td>
                  <?php }
                }else{ ?>
              <td colspan="4"><h3><b>NO DELETED ANNOUNCEMENTS</b></h3></td>
  						</tr>
  				
  					   <?php
  					   } ?>
  			</tbody>
  		</table>
  	</div>
  </div>		
</div>
<div class="clearfix">
</div>
<div class="accordion-group">
	<div class="accordion-heading country">
		<a class="accordion-toggle" data-toggle="collapse" href="#trashed_events"><h3 class="well well-sm"><b>Deleted Events</b><i class="glyphicon glyphicon-calendar pull-right"></i></h3></a>

	</div>
	
	<div id="trashed_events" class="accordion-body collapse">
		<div class="accordion-inner ">

	  <span data-toggle="buttons">
        <label class="btn btn-info">
        <input type="checkbox" id="select_all_appoint"  />All
        <span class="glyphicon glyphicon-check" title="Select All Events"></span>&nbsp;
        </label>
      </span>
      <a href="#" name="btn_restore" class="btn btn-warning" id="restoreSelAppointment">Restore Marked</a>
			<table class="table">
				<thead class="mytHead col-md-12">
					<tr>
						<th>Mark</th>
						<th>Title</th>
						<th>Description</th>
						<th>Start-End Date</th>
						<th>Recycle</th>
					</tr>
				</thead>
				<tbody class="myTbqody">
					<?php
					if(isset($trashedEvent)!=false && !empty($trashedEvent)){
						foreach ($trashedEvent as $trashEventDisply) { ?>
						<tr class="myTr">
							<td><input type="checkbox" name="trashID[]" class="restr_appointment" value="<?php echo $trashEventDisply->id; ?>">
                            </td>
							<td><?php echo $trashEventDisply->title; ?></td>
							<td><?php echo $trashEventDisply->descr; ?></td>
							<td><?php echo $trashEventDisply->start.' '.$trashEventDisply->end ?></td>
							<td><a type="button" href="#" id="restoreEventtrshID" class="btn" data-eventcycleid="<?php echo $trashEventDisply->id; ?>" class="btn btn-default" data-toggle="modal" data-target="#myModalRestoreEvent">
                                    <i class="fa fa-recycle fa-2x"></i>
                                  </a></td>
                 <?php }
               }else{ ?>
              <td colspan="4"><h3><b>NO DELETED EVENTS</b></h3></td>
  				</tr>
  					<?php
  				} ?>
			</tbody>
		</table>
	</div>
</div>		
</div>
</div>
<?php 
	$this->load->view('intercom/askRestoreMsg');
?>
<?php 
	$this->load->view('intercom/askRestoreAnn');
?>
<?php
	$this->load->view('intercom/askRestoreEvent');
?>
<?php
  $this->load->view('intercom/askRestoreSentMsg');
  ?>
  <div id="stop" class="show scrollTop">
    <a href="#" class="myScroll"><i class="glyphicon glyphicon-chevron-up"></i></a><br><br>
</div>