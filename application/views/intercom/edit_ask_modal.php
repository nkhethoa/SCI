<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Modal -->
  <div class="modal fade" id="myModalEditFaq" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><b>Ask Us Edit!</b></h4>
          <div id="alert-msgEditFAQtx"></div>
        </div>
        <div class="modal-body">
          <?php
            if(isset($questions)){
              foreach ($questions as $question) { ?>
          <input type="hidden" value="<?php echo $question->faqID; ?>" class="form form-control" name="edtfaqSelID" id="edtfaqSelID">
          <?php }
        } ?>
          <div class="form-group">
            <b>Edit Category:</b>
            <?php
            if(isset($categories)){ ?>
          <select class="form-control" id="edtcategory_sel" name="edtcategory_sel">
          <?php foreach ($categories as $category) { ?>
            <option value="<?php echo $category->faqCatID; ?>"><?php echo $category->faqCategory; ?></option>
            <?php }
              ?>
          </select>
          <?php
          } ?>
        </div>
        <div class="form-group">
          <b>Edit Title:</b>
        <input class="form-control" value="" name="edtfaq_title" id="edtfaq_title" type="text" placeholder="Title">
        </div>
         <div class="form-group">
          <label for="your_question"><b>Edit your question here...</b></label>
          <textarea class="form-control" value="" name="edtyour_question" placeholder="Description" id="edtyour_question" rows="3" cols="4"></textarea>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>&nbsp;<button type="button" class="btn btn-info" id="apply_subm_faq">Apply</button>
        </div>
      </div>
      
    </div>
  </div>
  