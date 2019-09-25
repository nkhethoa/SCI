<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="container-fluid backgroundColour1">
  <div class="ale-asses"></div>
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
                   foreach($assessments as $assessment){ 

                    ?>
            <tr>
                <td><?=$assessment->assessID?></td>
                <td><?=$assessment->assessName?></td>
                <td align="center">
                              <a class="btn btn-default assessEdit" 
                                data-assessname="<?=$assessment->assessName?>"  
                                data-assessid="<?=$assessment->assessID?>" 
                                 data-toggle="modal" data-target="#myModal"><em class="fa fa-pencil"></em></a>
                              <a class="btn btn-default askDeleteAssess" 
                               data-assessname="<?=$assessment->assessName?>" 
                               data-Lid="<?=$assessment->assessID?>" 
                                href="<?php //echo base_url('admin/askDeleteQuarter/'.$Quarter->quarterID)?> "><em class="fa fa-trash"></em></a>
                            </td>
            </tr>
                     <?php
                  }
              ?>
        </tbody>
    </table>
</div>
<!-- Edit Assessment type Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title text-center">Edit Assessment</h4>
      </div>
      <div class="ale-assesError"></div>
      <div class="modal-body">
        <from>
            
            <!-- Select Basic -->
                <div class="form-group">
                 <div class="form-group mx-sm-3 mb-2">
                  <label for="editAssessment" class="sr-only">Assessment name</label>
                  <input type="text" class="form-control" name="assessDescription" id="editAssessment" value="">
                  <input type="hidden" class="form-control" name="assessmentTypeID" id="assessID" value="">
                </div>
              </div>
            
         
        </from>    
        </div> 


    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button id="btnAgregar" name="btnAgregar" class="btn btn-default updateAssessment">Update</button>
    </div>
    </div>

  </div>
</div>

<!-- Add Assessment type Modal -->
<div id="myModal1" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title text-center">Add Assement Type</h4>
      </div>
      <div class="ale-assesAdd"></div>
      <div class="modal-body">
        <form>
            
            <!-- Select Basic -->
              <div class="form-group">
                 <div class="form-group mx-sm-3 mb-2">
                  <label for="newType" class="sr-only">Assessment Type</label>
                  <input type="text" class="form-control" name="AssessName" id="newType" placeholder="Assessment Type" value="">
                </div>
              </div>   
         
        </div> 


    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button id="add" name="add" type="submit" class="btn btn-default addAsseType">Insert</button>
    </div> 
    </div>
</form> 
  </div>
</div>

<!-- modal for success message start -->
<div id="askDeleteAssess" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-center"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg text-center"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-success btn-md askDeleteAssess-yes modalClose" value="">Yes</button>
                    <button class="btn btn-danger btn-md askDeleteAssess-no modalClose" value="No">No</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- modal for success message start -->
<div id="askDeleteAssess-success" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-center"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg text-center"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-info btn-md askDeleteAssess-ok modalClose" value="">Ok</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->