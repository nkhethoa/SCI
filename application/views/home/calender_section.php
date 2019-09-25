<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//link the calender
?>
     
         <div class="col-lg-6">           
               <div class="widget-sidebar">
                 <h2 class="title-widget-sidebar"> RECENT POST</h2>
                   <div class="content-widget-sidebar">
                    <ul>
                          <?php 


                              foreach ($gtopics as $topics) { 
                            ?>
                     <li class="recent-post">
                        <div class="post-img">
                         <img src="<?= $topics->filePath?>">
                         </div>
                          <h4><?= $topics->gtTitle?></h4>
                          <h5><?= $topics->gtDescr?></h5>
                         <p><small><i class="fa fa-calendar" data-original-title="" title=""></i> <?= $topics->topicDate?></small></p>
                        </li>
                        <hr>
                         <?php } ?>                     
                    </ul>
                   </div>
                 </div>
               </div>

  