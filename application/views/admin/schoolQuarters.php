<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="container-fluid backgroundColour1">
  <div class="ale-quarter"></div>
  <div class="pull-right btn-style">
                    <button type="button" class="btn btn-sm btn-success btn-create subjectButton pull-right" data-toggle="modal" data-target="#myModal1">Add New</button>
                  </div>
 <table id="example" class="table table-striped table-hover table-bordered tableBackground" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Description</th>
                <th>Action</th>
              
            </tr>
        </thead>
        <tfoot>
        </tfoot>
        <tbody>
           <?php
                   foreach($Quarters as $Quarter){ 

                    ?>
            <tr>
                <td><?=$Quarter->quarterID?></td>
                <td id="quarteer"><?=$Quarter->quarterName?></td>
                <td align="center">
                              <a class="btn btn-default quarterEdit" 
                                data-qname="<?=$Quarter->quarterName?>"  
                                data-qid="<?=$Quarter->quarterID?>" 
                                 data-toggle="modal" data-target="#myModal"><em class="fa fa-pencil"></em></a>
                              <a class="btn btn-default askDeleteQuarter"
                                data-qname="<?=$Quarter->quarterName?>"  
                                data-Qid="<?=$Quarter->quarterID?>" 
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
        <h4 class="modal-title text-center">Edit Quarter</h4>
      </div>
      <div class="ale-errorQ"></div>
      <div class="modal-body">
        <from>
            
            <!-- Select Basic -->
                <div class="form-group">
                 <div class="form-group mx-sm-3 mb-2">
                  <label for="editQuarter" class="sr-only">Quarter name</label>
                  <input type="text" class="form-control" name="QuaterDescription" id="editQuarter" value="">
                  <input type="hidden" class="form-control" name="QuarterId" id="Quartid" value="">
                </div>
              </div>
            
         
        </from>    
        </div> 


    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button id="btnAgregar" name="btnAgregar" class="btn btn-default updateQuarter">Update</button>
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
        <h4 class="modal-title text-center">Add Quarter</h4>
      </div>
      <div class="ale-quarterAdd"></div>
      <div class="modal-body">
        <form>
            
            <!-- Select Basic -->
              <div class="form-group">
                 <div class="form-group mx-sm-3 mb-2">
                  <label for="newQuarter" class="sr-only">Quarter name</label>
                  <input type="text" class="form-control" name="QuarterName" id="newQuarter" placeholder="Quarter Description" value="">
                </div>
              </div>   
         
        </div> 


    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button id="add" name="add" type="submit" class="btn btn-default addQuarter">Insert</button>
    </div> 
    </div>
</form> 
  </div>
</div>

<!-- modal for success message start -->
<div id="askDelete" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-center"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg text-center"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-success btn-md askDelete-yes modalClose" value="">Yes</button>
                    <button class="btn btn-danger btn-md askDelete-no modalClose" value="No">No</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- modal for success message start -->
<div id="askDelete-success" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-center"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg text-center"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-info btn-md askDelete-ok modalClose" value="">Ok</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->