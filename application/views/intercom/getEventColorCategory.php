<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php
if(!empty($groups)){

    foreach ($groups as $grp) { ?>

    <div class="category-header row row-md-center bgColor" style="background-color:#F6F9FB;">
        <div class="col-md-6 title style="color:#F6F9FB;">rfrfrff</div></div>
        <div class="form-inline event_colors_display row">
            <div class="form-group">

                <article class="events-preview">
                    <div class="events-preview-date" style="background-color:<?php echo $grp->eventColor; ?>">
                        <div class="events-preview-date-inner" style="color:white;">
                           <?php echo substr(date("F",strtotime($grp->start)),0,3).'<br>'.substr($grp->start,8,2); ?>
                             
                        </div>
                       </div>
                       <div class="events-preview-content">
                          <h4 class="Adelle"><?php echo $grp->title; ?></h4>
                          <p><em><b></b></em>
                              <br>
                              <?php echo $grp->descr; ?>
                          </p>
                      </div>
                  </article>
              </div>
          </div>
          <div class="cal-eventPage"></div>
          <div class="cal-eventAdd"></div>
          <div class="cal-eventUpdt"></div>
          <br>
          <?php } ?>
          
<div id="stop" class="show scrollTop">
   <a href="#" class="myScroll"><i class="glyphicon glyphicon-chevron-up"></i></a><br><br>
</div> <br>
      <?php }else { ?>
          <h2><b>NO APPOINTMENTS</b></strong></h2>          
          <?php  } ?>
