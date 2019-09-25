<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="modal fade modali" id="contactList">
  <form role="form" class="form-horizontal" id="book_add">
<div class="modal-dialog book" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 class="modal-title">ContactList</h3>
           <div class="input-group stylish-input-group"><input type="hidden"  value="" class='form-control' name="receiverID[]" id="receiverID">
                    <input type="text" class="form-control" id="mailMainViewSearch" name="result" id="result" placeholder="Search" >
                    <span class="input-group-addon">
                        
                    </span>
                </div>
        </div>
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;<div class="btn-group">
        <br>
      </div>
 <div class="ale-msg"></div>
 <div id="alert-userrEmail"></div>
        <div class="modal-body list">
          <table class="table table-striped" id="tblGrid">
            <thead id="tblHead">
              <tr>
                <th>Name</th>
                <th>E-mail</th>
                <th class="text-right">Mark</th>
              </tr>
            </thead>
                            
            <tbody class="contactBook">
              <?php
              if(isset($usersEmails) && $usersEmails != null){
            foreach ($usersEmails as $contact) {?>
              <tr>
                <td><?php echo $contact->lName.' '.$contact->fName?></td>
                <td><?php echo $contact->email ?></td>
                <td class="text-right"><input type="checkbox" name="checkMail[]" class="checkMail" value="<?php echo $contact->email ?>"></td>
              </tr>
               <?php
            }
          }?>
            </tbody>
            
          </table>
    </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left goBack" data-dismiss="modal">Cancel</button> 
          <div class="text-center"></div>
          <button type="button" class="btn btn-primary closeBook" data-dismiss="modal" value="Save">Apply</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </form>
  </div><!-- /.modal -->