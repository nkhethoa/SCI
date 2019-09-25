<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="container-fluid backgroundColour1">
  <div class="ale-sub"></div>
  <div class="pull-right btn-style">
                    <button type="button" class="btn btn-sm btn-success  subjectButton" data-toggle="modal" data-target="#myModal1">Add New</button>
                  </div>
 <table id="example" class="table table-striped table-hover table-bordered tableBackground" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Subject Name</th>
                <th>Action</th>
              
            </tr>
        </thead>
        <tfoot>
        </tfoot>
        <tbody>
           <?php
            //var_dump($schoolSubjects);
                   foreach($schoolSubjects as $subject){ 

                    ?>
            <tr>
                <td><?=$subject->subjID?></td>
                <td id="new_subject"><?=$subject->subjectName?></td>
                <td align="center">
                               <a class="btn btn-default subjectEdit" 
                                data-subname="<?=$subject->subjectName?>"  
                                data-subid="<?=$subject->subjID?>" 
                                 data-toggle="modal" data-target="#myModal"><em class="fa fa-pencil"></em></a>
                              <a class="btn btn-default askDeleteSubject"
                               data-subname="<?=$subject->subjectName?>"  
                               data-subid="<?=$subject->subjID?>" 
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
        <h4 class="modal-title text-center">Edit Subject</h4>
      </div>
 
      
      <div class="modal-body">
      <div class="ale-Editerror"></div>
        <from>
            
            <!-- Select Basic -->
                <div class="form-group">
                 <div class="form-group mx-sm-3 mb-2">
                  <label for="editSubject" class="sr-only">Subject name</label>
                  <input type="text" class="form-control" name="SubjectName" id="editSubject" value="">
                  <input type="hidden" class="form-control" name="subjectId" id="subid" value="">
                </div>
              </div>
            
         
        </from>    
        </div> 


    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button id="btnAgregar" name="btnAgregar" class="btn btn-default updateSubject">Update</button>
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
        <h4 class="modal-title text-center">Add Subject</h4>
      </div>
      <div class="ale-subAdd"></div>
      <div class="modal-body">
        <form>
            
            <!-- Select Basic -->
              <div class="form-group">
                 <div class="form-group mx-sm-3 mb-2">
                  <label for="newSubject" class="sr-only">Subject name</label>
                  <input type="text" class="form-control" name="subjectName" id="newSubject" placeholder="subject Name" value="">
                </div>
              </div>   
         
        </div> 


    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button id="add" name="add" type="submit" class="btn btn-default addSubject">Insert</button>
    </div> 
    </div>
</form> 
  </div>
</div>


    

<!-- modal for success message start -->
<div id="askDeleteSubject" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-center"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg text-center"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-success btn-md askDeleteSubject-yes modalClose" value="">Yes</button>
                    <button class="btn btn-danger btn-md askDeleteSubject-no modalClose" value="No">No</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- modal for success message start -->
<div id="askDeleteSubject-success" class="modal in display" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-center"></h4>
          </div>
          <div class="modal-body">
            <p class="modal-msg text-center"></p>
            <div class="row">
                <div class="col-12-xs text-center">
                    <button class="btn btn-info btn-md askDeleteSubject-ok modalClose" value="">Ok</button>
                </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
