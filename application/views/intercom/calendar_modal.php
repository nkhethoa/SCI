<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <!-- calendar modal -->
    <div id="CalenderModalNew" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="myModalLabel">New Calendar Entry</h4>
          </div>
          <div class="modal-body">
            <div id="testmodal" style="padding: 5px 20px;">
              <form id="antoform" class="form-horizontal calender" role="form">
                <div class="form-group">
                    <div class="col-xs-6">
                   </div>
                  </div>       
                <div class="form-group">
                  <label class="col-sm-3 control-label">Title</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" value="" id="title" name="title">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Description</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" style="height:55px;" value="" id="descr" name="descr"></textarea>
                  </div>
                </div>                 
                <br>
                <div class="form-group col-md-12">
                  <!--start time-->
                  <div class="col-md-6">
                  <label class="col-sm-3 control-label">Start</label>
                  <div class="col-sm-9">
                    <?php
                  if(isset($schedules)){ ?>

                    <select class="form-control" id="selTime">
                      <?php
                    foreach ($schedules as $begin) { ?>
                        <option value="<?php echo $begin->scheduleID; ?>"><?php echo substr($begin->start_time,0,-9); ?></option>
                        <?php
                         }
                        ?>
                    </select>
                    <?php
                    } ?>
                  </div>
                </div>
                <!--start time-->
                <!--end time-->
                <div class="col-md-6">
                  <label class="col-sm-2 control-label">End</label>
                  <div class="col-sm-9">
                    <?php
                  if(isset($schedules)){ ?>
                    <select class="form-control" id="selTime">
                      <?php
                    foreach ($schedules as $ending) { ?>
                        <option value="<?php echo $ending->scheduleID; ?>"><?php echo substr($ending->end_time,0,-9); ?></option>
                        <?php
                         }
                        ?>
                    </select>
                    <?php
                    } ?>
                  </div>
                </div>
                <!--end time-->
                </div>
                <br>                        
                <div class="form-group">
                  <label class="col-sm-2 control-label">Event</label>
                  <div class="col-sm-9">
                    <?php
                    //var_dump($colors);
                  if(isset($colors)){ ?>
                    <select class="form-control" id="selColor">
                      <?php
                    foreach ($colors as $color) { ?>
                        <option value="<?php echo $color->colorID; ?>" style="color:<?php echo $color->eventColor; ?>;">&#9724;<?php echo $color->eventCategory; ?></option>
                        <?php
                         }
                        ?>
                    </select>
                    <?php
                    } ?>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary antosubmit">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    <div id="CalenderModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="myModalLabel2">Edit Calendar Entry</h4>
          </div>
          <div class="modal-body">

            <div id="testmodal2" style="padding: 5px 20px;">
              <form id="antoform2" class="form-horizontal calender" role="form">
               <input type="hidden" value="" id="hiddenEdtEvent" name="hiddenEdtEvent" class="form-control">               
                <div class="form-group">
                  <label class="col-sm-3 control-label">Title</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="title2" name="title2">
                  </div>
                </div>
                <div class="form-group">
                 <label class="col-sm-3 control-label">Description</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" style="height:55px;" id="descr2" name="descr2"></textarea>
                  </div>
                </div> 
                <br>
                <div class="form-group col-md-12">
                  <!--start time edit-->
                  <div class="col-md-6">
                    <label class="col-sm-3 control-label">Start</label>
                  <div class="col-sm-9">
                    <?php
                  if(isset($schedules)){ ?>

                    <select class="form-control" id="selColor">
                      <?php
                    foreach ($schedules as $begin) { ?>
                        <option value="<?php echo $begin->scheduleID; ?>"><?php echo substr($begin->start_time,0,-9); ?></option>
                        <?php
                         }
                        ?>
                    </select>
                    <?php
                    } ?>
                  </div>
                  </div>
                  <!--start time edit end-->
                  <!--end time-->
                <div class="col-md-6">
                  <label class="col-sm-2 control-label">End</label>
                  <div class="col-sm-9">
                    <?php
                  if(isset($schedules)){ ?>
                    <select class="form-control" id="selColor">
                      <?php
                    foreach ($schedules as $ending) { ?>
                        <option value="<?php echo $ending->scheduleID; ?>"><?php echo substr($ending->end_time,0,-9); ?></option>
                        <?php
                         }
                        ?>
                    </select>
                    <?php
                    } ?>
                  </div>
                </div>
                <!--end time edit-->
                </div>
                <br>          
                <div class="form-group">
                  <label class="col-sm-3 control-label">Event</label>
                  <div class="col-sm-9">
                    <?php
                  if(isset($colors)){ ?>
                    <select class="form-control" id="selColorEdt">
                      <?php
                    foreach ($colors as $color) { ?>
                        <option value="<?php echo $color->colorID; ?>" style="color:<?php echo $color->eventColor; ?>;">&#9724;<?php echo $color->eventCategory; ?></option>
                        <?php
                         }
                        ?>
                    </select>
                    <?php
                    } ?>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default antoclose2" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary antosubmit2">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    <div id="fc_create" data-toggle="modal" data-target="#CalenderModalNew"></div>
    <div id="fc_edit" data-toggle="modal" data-target="#CalenderModalEdit"></div>
    <!-- /calendar modal -->