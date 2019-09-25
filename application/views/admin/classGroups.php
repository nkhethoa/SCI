<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="container-fluid backgroundColour1">
  <div class="aler-group"></div>
  <div class="pull-right btn-style">
                    <button type="button" class="btn btn-sm btn-success btn-create subjectButton pull-right" data-toggle="modal" data-target="#myModal1">Add New</button>
                  </div>
 <table id="example" class="table table-striped table-hover table-bordered tableBackground" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Class Name</th>
                <th>Action</th>
              
            </tr>
        </thead>
        <tfoot>
        </tfoot>
        <tbody>
           <?php
                   foreach($groups as $group){ 

                    ?>
            <tr>
                <td><?=$group->groupID?></td>
                <td><?=$group->classGroupName?></td>
                <td align="center">
                              <a class="btn btn-default groupEdit" 
                                data-gname="<?=$group->classGroupName?>"  
                                data-gid="<?=$group->groupID?>" 
                                 data-toggle="modal" data-target="#myModal"><em class="fa fa-pencil"></em></a>
                              <a class="btn btn-default askDeleteGroup"
                                data-gcname="<?=$group->classGroupName?>"
                                data-gid="<?=$group->groupID?>" 
                                href="<?php //echo base_url('admin/askDeleteQuarter/'.$Quarter->quarterID)?> "><em class="fa fa-trash"></em></a>
                            </td>
            </tr>
                     <?php
                  }
              ?>
        </tbody>
    </table>
</div>
<!-- Edit Subject Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title text-center">Edit Group</h4>
      </div>
      <div class="aler-groupError"></div>
      <div class="modal-body">
        <from>
            
            <!-- Select Basic -->
                <div class="form-group">
                 <div class="form-group mx-sm-3 mb-2">
                  <label for="editGroups" class="sr-only">Group name</label>
                  <input type="text" class="form-control" name="levelDescription" id="editGroups" value="">
                  <input type="hidden" class="form-control" name="classGroupId" id="groupID" value="">
                </div>
              </div>
            
         
        </from>    
        </div> 


    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button id="btnAgregar" name="btnAgregar" class="btn btn-default updateGroup">Update</button>
    </div>
    </div>

  </div>
</div>

<!-- Add Subject Modal -->
<div id="myModal1" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title text-center">Add Group</h4>
      </div>
      <div class="aler-groupAdd"></div>
      <div class="modal-body">
        <form>
            
            <!-- Select Basic -->
              <div class="form-group">
                 <div class="form-group mx-sm-3 mb-2">
                  <label for="newGroup" class="sr-only">Group name</label>
                  <input type="text" class="form-control" name="groupName" id="newGroup" placeholder="Group Name" value="">
                </div>
              </div>   
         
        </div> 


    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button id="add" name="add" type="submit" class="btn btn-default addGroup">Insert</button>
    </div> 
    </div>
</form> 
  </div>
</div>

<!-- modal for success message start -->
<div id="askDeleteGroup" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-center"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg text-center"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-success btn-md askDeleteGroup-yes modalClose" value="">Yes</button>
                    <button class="btn btn-danger btn-md askDeleteGroup-no modalClose" value="No">No</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- modal for success message start -->
<div id="askDeleteGroup-success" class="modal in display" data-backdrop="static" data-keyboard="false" >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-center"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg text-center"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-info btn-md askDeleteGroup-ok modalClose" value="">Ok</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->