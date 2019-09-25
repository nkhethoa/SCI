<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="container-fluid backgroundColour1">
  <div class="alert-addcgl"></div>
  <div class="pull-right btn-style">
      <button type="button" class="btn btn-sm btn-success btn-create add_new_cgl " data-toggle="modal" data-target="#add_class_group_level">Add New</button>    
  </div>
         
              
 <table id="example" class="table table-striped table-hover table-bordered tableBackground" cellspacing="0" width="100%">

        <thead>
            <tr>
                <th>Level</th>
                <th>Group</th>
                <th>Limit</th>
                <th>Action</th>

            </tr>
        </thead>
        <tfoot>
        </tfoot>
        <tbody>

           <?php
                   foreach($classGroup_level as $value){ 

                    ?>
            <tr>
                <td><?=$value->level?></td>
                <td><?=$value->classGroupName?></td>
                <td><?=$value->classLimit?></td>
                <td align="center">
                              <a class="btn btn-default cglEdit" 
                                data-cglname="<?=$value->classGroupName?>"  
                                data-cglid="<?=$value->cglID?>" 
                                data-cgl_level="<?=$value->levelID?>"  
                                data-cgl_group="<?=$value->cgID?>"  
                                data-cgl_limit="<?=$value->classLimit?>" 
                                 data-toggle="modal" data-target="#add_class_group_level">
                                 <em class="fa fa-pencil"></em>
                              </a>
                              <a class="btn btn-default askDeleteCgLevel"
                                data-cglname="<?=$value->classGroupName?>" 
                                data-cgl_limit="<?=$value->classLimit?>"  
                                data-cgl_level="<?=$value->level?>"  
                                data-cglid="<?=$value->cglID?>" 
                                href="<?php //echo base_url('admin/askDeleteCgLevel/'.$Quarter->quarterID)?> "><em class="fa fa-trash"></em></a>
                            </td>

                     <?php
                  }
              ?>
            </tr>
        </tbody>
    </table>
</div>
<!-- Add Class Group Level Modal -->
<!-- line modal -->
<div class="modal fade" id="add_class_group_level" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title text-center" id="lineModalLabel">Add Class Group Level</h3>
      </div>
      <div class="modal-body">
        <div class="alert-cglError"></div>
        <!-- content goes here -->
        <form method="POST">
         <div class="form-group">
          <input type="hidden" name="cglID" id="cglID" value="">
          <label>Class Limit</label>
          <input type="number" name="limit" id="cgl_limit" class="form-control" value="">

        </div>
        <div class="col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px">
         <div class="form-group col-md-6 removePadding"><label>Level</label>
           <select class="form-control" id="cgl_level" name="level" >
             <option hidden value="">Select Grade/Level</option>
             <?php
             if (isset($levels)) {
               foreach($levels as $cgLevels){ ?>  
               <option value="<?=$cgLevels->levelID?>"><?=$cgLevels->levelName?></option>
               <!-- other options -->
               <?php
             }
           }
           ?>
           <!-- other options -->
         </select>
         <?php echo form_error('level')? alertMsgs(false,'',form_error('level')):''?></span>
       </div>
       <div class="form-group col-md-6 removePaddingRight"><label>Level Group</label>
         <select class="form-control" id="cgl_group" name="group" >
           <option hidden value="">Select Class Group Level</option>
           <?php
             if (isset($groups)) {
               foreach($groups as $group){ ?>  
               <option value="<?=$group->groupID?>"><?=$group->classGroupName?></option>
               <!-- other options -->
               <?php
             }
           }
           ?>
         </select>
         <?php echo (form_error('group') && form_error('level')=='')? alertMsgs(false,'',form_error('group')):''?></span>
       </div>

     </form>
   </div>
 </div>
 <div class="btn-group btn-group-justified" role="group" aria-label="group button">
  <div class="btn-group" role="group">
    <button type="button" class="btn btn-warning" data-dismiss="modal"  role="button">Cancel</button>
  </div>
  <div class="btn-group" role="group">
    <button type="button" id="addclassglevel" class="btn btn-success btn-hover-green addclassglevel"  role="button">Add</button>
  </div>
</div>
</div>
</div>
</div>

<!-- modal for success message start -->
<div id="askDeleteCgLevel" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-center"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg text-center"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-success btn-md askDeleteCgLevel-yes modalClose" value="">Yes</button>
                    <button class="btn btn-danger btn-md askDeleteCgLevel-no modalClose" value="No">No</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- modal for success message start -->
<div id="askDeleteCgLevel-success" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-center"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg text-center"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-info btn-md askDeleteCgLevel-ok modalClose" value="">Ok</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


