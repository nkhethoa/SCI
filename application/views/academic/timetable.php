<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="mainTab-tab-content">
  <h2 class="text-center">Time-Table
    <?php 
    //if (strpos($_SESSION['userWho'], 'guardian')===false){ ?>
      <span class="pull-right visible-xs">
        <button type="button" 
          class="btn btn-success btn-md addClass btn-add" 
          data-toggle="modal" 
          data-target="#classDayModal" 
          data-placement="top" style="float: right;" >
          <i title="Add subjects" class="glyphicon glyphicon-plus"></i>
        </button>
      </span>

      <?php
    //var_dump($tt_subjects);
    ?>
  </h2>
  <!-- tab-content start here-->
  <div class="tab-content container-fluid" id="tt_outputs">
    <!--
    ***********************************************************
         this-content comes from tt.php
    ***********************************************************
    -->
  </div><!-- /tab-content-->

</div><!-- /mainTab-content-->


<!-- Modal -->
<div class="modal fade" id="classDayModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <form action="" method="POST" id="add_my_tt_subjects" accept-charset="utf-8">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Class</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="what_timeid" name="what_timeid" value="">
        <input type="hidden" id="what_wdid" name="what_wdid" value="">
        <div class="form-group col-md-12">
          <label>List of your subjects</label>
          <?php 
          if(isset($subjects)){ ?>
            <select class="form-control col-md-6" id="tt_sbj_cls_id" name="tt_sbj_cls_id">
              <option value="0" hidden>Select Subject</option>
              <?php foreach ($subjects as $subject) {?>
              <option value="<?php echo $subject->clsID; ?>"><?php echo $subject->subjectName; ?></option>
              <?php }
              ?>
            </select>
            <?php
          } ?>
       </div>
      <div class="col-md-6 visible-xs">
        <label for="timeSelector">Start - End</label>
        <?php
        if(isset($timeDays)){ ?>
          <select class="form-control col-md-6" id="timeSelector" name="timeSelector">
            <option value="0" hidden>Select Class Time</option>
            <?php 
            foreach ($timeDays as $time) {?>
              <option value="<?php echo $time->what_timeID; ?>">
                <?php echo substr($time->class_startTime,0,5);?>
              </option>
              <?php 
            }
            ?>
          </select>
          <?php
        } ?>
      </div>
      <div class="col-md-6 visible-xs">
        <label for="daySelector">Week Day</label>
        <?php
        if(isset($weekDays)){ ?>
          <select class="form-control col-md-6" id="daySelector" name="daySelector">
            <option value="0" hidden>Select Class Day</option>
            <?php  
            foreach ($weekDays as $day) {?>
              <option value="<?php echo $day->wdID; ?>">
                <?php echo $day->wdName; ?>
              </option>
              <?php 
            }
            ?>
          </select>
          <?php
        } ?>
      </div> 
     </div>
     <div class="clearfix"></div>
     <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-info" id="add_my_class" data-dismiss="modal" >Submit</button>
    </div>
    </form>
  </div>

</div>
</div>

<!-- Modal for confirm delete-->
    <div id="tt-confirm" class="modal none" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="tt_del_heading"></h4>
          </div>
          <div class="modal-body text-center">
            <p id="tt_myP"></p>
            <div class="row">
              <div class="col-xs-12 ">
                <input type="hidden" name="wcsID" id="wcsID" value="">
                <input type="hidden" name="clsID" id="del_wcs_clsID" value="">
                <input type="hidden" name="ssID" id="del_wcs_ssID" value="">
                <input type="hidden" name="wdID" id="del_wcs_wdID" value="">
                <button class="btn btn-success btn-md" id="del_btn_yes" value="" data-dismiss="modal">Yes</button>
                <button class="btn btn-danger btn-md" data-dismiss="modal">No</button>
              </div>
            </div>
          </div>
       
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
