<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="topic--body">
	<div class="topic--list">
		<div class="topic-list--header clearfix">
			<span class="topic-list-header--title"><i class="fa fa-info-circle"></i><b>Frequently Asked Questions</b></span>
		</div><br>
		<div class="form-group">
			<label for="usr"><h4><b>Search Forum</b></h4></label><button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#askUsModal">
				Question&nbsp;<i class="glyphicon glyphicon-question-sign"></i>
			</button>

			<input type="text" name="srchFAQ" value=""  placeholder="Search Forum" class="form-control" id="faqMainViewSearch">
		</div>
		<div class="modal-faqsucc"></div>
		<div class="modal-msgFaqx"></div>
		<div class="alert-msgEditFAQx"></div>
		<div class="alert-msgEditFAQtrr"></div>
		<div class="alert-FaqErrorx"></div>
		<div class="alert-faqPage"></div>
	</div>

<div id="faqlinks" class="clearfix">
	<?php 
	//var_dump($questions);
	if(isset($categories)!=false && !empty($categories)){
		$category_count = 0;
	 foreach ($categories as $category) { ?>
	<div class="column1 col-md-6">

		<dl class="faq--body">
			<dt class="faq--title"><h2><b><?php echo $category->faqCategory; ?></b></h2></dt>
			<?php
			 if(isset($questions)){
			 	$topic_count = 0;
			 	foreach ($questions as $question) {
			 	if($question->frequentquestionscategoryID == $category->faqCatID){ ?>
			<dd><a href="#f<?php echo $category_count; ?>r<?php echo $topic_count; ?>" class="prevent"><?php echo $question->faqTitle; ?></a></dd>
		<?php 
		$topic_count++;
		}
	  } 
     } ?>
		</dl>
	</div>
	<?php
	$category_count++;
  }
} ?>	
</div>

<?php 
	if(isset($categories)!=false && !empty($categories)){
		$category_count = 0;
	 foreach ($categories as $category) { ?>
	<div class="topic--list">
		<div class="topic-list--header clearfix">
			<span class="topic-list-header--title"><i class="fa fa-info-circle"></i><b><?php echo $category->faqCategory; ?></b>
				</span><a class="collapsed faq_links" data-toggle="collapse" href="#<?php echo $category->faqCatID; ?>" aria-expanded="false">
			<span class="topic-list-header--toggle-btn"><i class="fa fa-minus"></i></span></a>
		</div>
		<div class="topic-list--content" id="<?php echo $category->faqCatID; ?>">
					<div class="faq--content">
						<?php
						if(isset($questions)){
							$topic_count = 0;
						foreach ($questions as $question) {
						if($question->frequentquestionscategoryID == $category->faqCatID){
							?>
						<dl class="faq">
							<?php
       						//if(identify_user_role($_SESSION['userID']) == 'admin'){ ?>
							<dt id="f<?php echo $category_count; ?>r<?php echo $topic_count; ?>" class="faq--title"><h4><u><b><?php echo $question->faqTitle; ?></b>
								<div class="pull-right"><a href="#" class="faqPencil"
								 data-faqidcat="<?php echo $category->faqCatID; ?>"
								 data-faqcat="<?php echo $category->faqCategory; ?>"
								 data-toggle="modal" data-target="#myModalEditFaq" 
								 data-faid="<?php echo $question->faqID; ?>" 
								 data-faqtitle="<?php echo $question->faqTitle; ?>" 
								 data-faqdescr="<?php echo $question->faqDescription; ?>">
          						<span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
       							<a href="#" id="delFAQBtn" class="delThisQuest" 
       							data-delfaqid="<?php echo $question->faqID; ?>"  
       							data-toggle="modal" 
       							data-target="#FAQDelModal">
       							<span class="glyphicon glyphicon-trash" style="color:red;"></span></a></div></u></h4>
       						</dt>
       						<?php //} ?>
							<dd class="description_faq"><?php echo $question->faqDescription; ?></dd>
						</dl>	
						<hr><?php
						 $topic_count++;
					   }
					  }
					 }
					?>
			 </div>
		</div>
	</div>
	 <?php
     } 
    
    }else{ ?>
      <h2 class="text-justify"><b>NO FAQ's AVALILABLE</b></h2>
   <?php

   $category++;
     }
?>
</div><br>
<div id="stop" class="show scrollTop">
    <a href="#" class="myScroll"><i class="glyphicon glyphicon-chevron-up"></i></a><br><br>
</div>
<?php
	$this->load->view('intercom/ask_modal');
?>
<?php
	$this->load->view('intercom/edit_ask_modal');
?>
<?php
	$this->load->view('intercom/askDeleteFAQ');
?>