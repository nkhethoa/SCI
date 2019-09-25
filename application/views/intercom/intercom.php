 <?php
 defined('BASEPATH') OR exit('No direct script access allowed');
 ?>
 <div class="container-fluid tab-container">
  <!--visible on mobile device-->
    <div class="dropdown visible-xs visible-sm menuBtn pull-left">
      <button class="btn menu-color dropdown-toggle" type="button" data-toggle="dropdown">
      Side Menu
      <span class="caret"></span>
    </button>
    <div class="dropdown-menu mainTab-tab-menu">
      <div class="list-group-mobile">
        <a href="#msgMainView" class="active">
          <h4 class="glyphicon glyphicon-envelope"></h4> Messages
        </a><br>
        <a href="#anouMainView" class="">
          <h4 class="glyphicon glyphicon-bullhorn"></h4> Announcements
        </a><br>
        <a href="#discusMainView" class="">
          <h4 class="glyphicon glyphicon-comment"></h4> Discussions
        </a><br>
        <a href="#calMainView" class="">
          <h4 class="glyphicon glyphicon-calendar"></h4> Calendar
        </a><br>
        <a href="#faqMainView" class="">
          <h4 class="glyphicon glyphicon-question-sign"></h4> FAQs
        </a><br>
        <a href="#trashMainView" class="">
          <h4 class="glyphicon glyphicon-trash"></h4> Trash
        </a>
      </div>  
    </div>
  </div>
  <!--visible on mobile device end-->

<!--normal device start-->
<div class="col-lg-2 col-md-2 hidden-xs hidden-sm mainTab-tab-menu">
      <div class="list-group">
        <a href="#msgMainView" class="list-group-item active text-center mainTab list_group-item">
          <h4 class="glyphicon glyphicon-envelope fa-2x"></h4><br/> Messages
        </a>
        <a href="#anouMainView" class="list-group-item text-center mainTab list_group-item">
          <h4 class="glyphicon glyphicon-bullhorn fa-2x"></h4><br/> Announcements
        </a>
        <a href="#discusMainView" class="list-group-item text-center mainTab list_group-item">
          <h4 class="glyphicon glyphicon-comment fa-2x"></h4><br/> Discussions
        </a>
        <a href="#calMainView" class="list-group-item text-center mainTab list_group-item">
          <h4 class="glyphicon glyphicon-calendar fa-2x"></h4><br/> Calendar
        </a>
        <a href="#faqMainView" class="list-group-item text-center list_group-item">
          <h4 class="glyphicon glyphicon-question-sign fa-2x"></h4><br/> FAQs
        </a>
        <a href="#trashMainView" class="list-group-item text-center list_group-item">
          <h4 class="glyphicon glyphicon-trash fa-2x"></h4><br/> Trash
        </a>
      </div>  
</div>
<!--end normal device-->

    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mainTab-tab">
      <!-- messages section -->
      <div id="msgMainView" class="mainTab-tab-content active"></div>
    
      <!-- announcements section-->
       <div id="anouMainView" class="mainTab-tab-content"></div>
    
      <!-- Discussion section -->
      <div id="discusMainView" class="mainTab-tab-content"></div>
    
    <div id="calMainView" class="mainTab-tab-content">
      <!--Delete event button-->
      <div class="row col-xs-12"><div class="text-center btn-danger" id="trashArea"><span id="calendarTrash" class="glyphicon glyphicon-trash fa-3x"></span></div>
    </div>
    <!--end delete event button-->
      <br>
      <br>
      <!--calendar start-->
      <div class="row col-xs-12">
        <div class="cal-eventPage"></div>
        <div id="calendar">
      </div>
      </div>
      <!--calendar end-->
      <br>
      <div class="row col-xs-12">
      <div id="event_buttons">
        <?php if(isset($colors)){
          foreach ($colors as $color) { ?>
      <a href="#" class="btn trigger" data-id="<?php echo $color->colorID; ?>" style="color:black; background-color: <?php echo $color->eventColor; ?>" value=""><i class="icon-2x glyphicon glyphicon-<?php echo $color->glyphicon; ?>"></i>&nbsp;<?php echo $color->eventCategory; ?></a>
      <?php
    }
  } ?>
      </div>
      </div>
        <br>
        <input type="hidden" value="" class="form form-control" name="groupevent" value="" id="groupevent"> 
        <div class="form-inline event_colors_display">
            <div class="form-group">
            </div>
        </div>
        <br>
      <div id="calendarSection">
      <?php
        $this->load->view('intercom/calendar_modal');
      ?>
    </div>
    </div>
   <br>
    <div id="faqMainView" class="mainTab-tab-content">
      <?php $this->load->view('intercom/FAQ'); ?>
    </div>
    <br>
    <div id="trashMainView" class="mainTab-tab-content"></div>
    
  </div>
</div>