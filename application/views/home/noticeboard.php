<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//link the carousel 
?>

<div class="col-lg-6">
 <div class="widget-sidebar">
  <h2 class="title-widget-sidebar"> ANNOUNCEMENTS</h2>
  <?php if(isset($announces)){
    $count=1;
    foreach($announces as $announce){ 
      if ($count < 5){ 
        $post_date = strtotime($announce->annDate);
        $now = time(substr($announce->annDate, 11));
        $units = 2;
        ?>
        <div class="last-post">
          <button class="accordion">  <small><b>Posted:</b>&nbsp;&nbsp;&nbsp;<?php echo timespan($post_date, $now, $units).' ago'; ?></small></button>
          <div class="panelz"  id="accordion<?php echo $announce->annID; ?>">
           <li class="recent-post">
             <h5><b><?= $announce->title;?></b></h5>
             <p><small><i class="fa fa-calendar" data-original-title="" title=""></i>&nbsp;&nbsp;<?= $announce->annDate;?></small></p>
           </li>
           <p><?= $announce->body;?></p>
         </div>
       </div>                 
       <hr>
       <?php
     }
     $count++;
   }
 }
 ?>
</div>
</div>