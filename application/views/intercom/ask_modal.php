<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Modal -->
  <div class="modal fade" id="askUsModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Ask Us!</h4>
          <div id="alert-FaqError"></div>
        </div>
        <div class="modal-body">
          <?php
            if(isset($questions)){
              foreach ($questions as $question) { ?>
          <input type="hidden" value="<?php echo $question->faqID ?>" class="form form-control" name="faqSelID" id="faqSelID">
          <?php 
        } 
      } ?>
          <div class="form-group">
            <b>Select Category:</b>
            <?php 
            if(isset($categories)){ ?>
          <select class="form-control" id="category_sel" name="category_sel">
          <?php foreach ($categories as $category) { ?>
            <option value="<?php echo $category->faqCatID; ?>"><?php echo $category->faqCategory; ?></option>
            <?php }
              ?>
          </select>
          <?php
          } ?>
        </div>

        <div class="form-group">
        <input class="form-control" value="" name="faq_title" id="faq_title" type="text" placeholder="Title">
        </div>

         <div class="form-group">
          <label for="your_question"><b>Type your question here...</b></label>
          <textarea class="form-control" name="your_question" placeholder="Description" value="" id="your_question" rows="3" cols="4"></textarea>
        </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>&nbsp;<button type="button" class="btn btn-info" id="subm_faq">Submit</button>
        </div>
      </div>
      
    </div>
  </div>
  