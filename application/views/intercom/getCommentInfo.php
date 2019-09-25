<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php 
if($comments!= NULL){
  $counter = 1;
  foreach ($comments as $comment) { 
    if($counter == 1){ ?>
      <div class="category-header row row-md-center bgColor"><h4 class="forumTitle"><b><?php echo $comment->gtTitle; ?></b>
      </h4></div>
      <?php }
          $counter++; ?>
          
          <br>
          <br>
             <!--new comments section start-->
             <div class="well">
               <div class="media">
                <div class="pull-left">
                    <img src="<?php echo base_url($comment->filePath); ?>" alt="pro_pic" class="img-thumbnail"><br>
                    <b><i class="text-center"><?php echo $comment->relDescription; ?></i></b>
                  </div>
                  <div class="media-body">

                    
                    <h4 class="media-heading"><b>Posted by</b>:&nbsp;&nbsp;<div class="pull-right">

                      <!--actions start-->
                      <a href="#" class="btn btn-default btn-sm disc_actioon" id="editingComm" 
                      data-toggle="modal" 
                      data-target="#myModalEdit" 
                      data-subd="<?php echo $comment->topicDate; ?>" 
                      data-subt="<?php echo $comment->gtTitle; ?>" 
                      data-gentpc="<?php $comment->gtID; ?>" 
                      data-subs="<?php echo $comment->gtcID; ?>" 
                      data-sub="<?php echo $comment->commentMessage; ?>"><i class="glyphicon glyphicon-pencil">
                      </i>
                    </a>
                    <a href="#" data-commID="<?php echo $comment->gtcID; ?>" type="button" class="btn btn-default btn-sm delCommentt disc_actiion" name="trashing" title="deletecomment" data-toggle="modal" data-target="#myModalComment">
                      <i class="glyphicon glyphicon-trash"></i>
                    </a></div><p class="text-left"><?php echo $comment->commentorFName.' '.$comment->commentorLName; ?> 
                    </p></h4> 
                    <!--actions end-->
                    <p><?php echo $comment->commentMessage; ?></p>
                    <ul class="list-inline list-unstyled">
                      <li><span><i class="glyphicon glyphicon-calendar"></i> <?php echo $comment->commentDate; ?></span></li>
                      <li>|</li>
                      <span><i class="glyphicon glyphicon-comment"></i></span>
                      <li>|</li>
                      <li>
                        <!--stars-->
                        <div class="star-rating">
                          <span class="fa fa-star-o rate" data-comment="<?php echo $comment->gtcID; ?>" data-rating="1"></span>
                          <span class="fa fa-star-o rate" data-comment="<?php echo $comment->gtcID; ?>" data-rating="2"></span>
                          <span class="fa fa-star-o rate" data-comment="<?php echo $comment->gtcID; ?>" data-rating="3"></span>
                          <span class="fa fa-star-o rate" data-comment="<?php echo $comment->gtcID; ?>" data-rating="4"></span>
                          <span class="fa fa-star-o rate" data-comment="<?php echo $comment->gtcID; ?>" data-rating="5"></span>
                          <?php 
                           $rate = 0;//initial value of counter like variable
                           $average_rate = 0;
                           $total_rating = 0;
                           if(isset($ratings) && !empty($ratings)){//testing the the likes variable is not null
                            //var_dump($ratings);
                              foreach ($ratings as $rating) {//foreach start
                                if ($comment->gtcID == $rating->generaltopiccommentID) { 
                                    $total_rating += $rating->ratingID;                   
                                    $rate++;//increament number of likes if test pass == 1
                                  }
                              }//end foreach
                              if($total_rating > 0){
                              $average_rate = round($total_rating/$rate,0);
                            }
                           }//end if statement
                          ?>
                          <input type="hidden" name="rating" class="rating-value" id="<?php //echo  $comment->gtcID; ?>" value="<?php echo $average_rate; ?>">
                        </div>                  
                    <!--stars end-->
                     </li>
                  </ul>
                  <!--likes/dislikes counter start-->
                   <?php 
                     $thumbs_up = 0;//initial value of counter like variable
                     $thumbs_down = 0;//initial value of counter dislike variable
                     if(isset($likes) && !empty($likes)){//testing the the likes variable is not null
                        foreach ($likes as $like) {//foreach start
                          if ($comment->gtcID == $like->commentLiked) { 
                            if($like->liked == 1){
                              //                           
                              $thumbs_up++;//increament number of likes if test pass == 1
                            }elseif($like->liked == 0){
                              $thumbs_down++;//else increment number of dislikes if test otherwise
                            }
                          }
                        }//end foreach
                     }//end if statement
                     ?>
                     <!--likes/dislikes counter end-->
                  <!--thumbs start-->
                  <div class="ipsLikeBar right clearfix" id="rep_post_251538">
                    <span class="glyphicon glyphicon-thumbs-up" value=""></span></a><span>&nbsp;(<?php echo $thumbs_up; ?>)</span>&nbsp;<span><b>likes</b></span>&nbsp;&nbsp;
                    <a href="#"
                    data-commenttopic="<?php echo $comment->gtID; ?>"
                    data-likecommentid="<?php echo $comment->gtcID; ?>" 
                    data-userlike="<?php echo $_SESSION['userID']; ?>" 
                    data-likes = "0"
                    class="likeCommenta" id="dislikes" name="dislikes" title="dislikecomment">
                    <span class="glyphicon glyphicon-thumbs-down" value="">
                    </span></a><span>&nbsp;(<?php echo $thumbs_down; ?>)</span>&nbsp;<span><b>dislikes</b></span>&nbsp;
                  </div>
                </div></div>
                </div>
                <div id="alert-msgLike"></div>
                <div class="modal-comm"></div>
                 <?php 
               } ?>
               <!--thumbs end-->
                </div>
              </div>
            </div>
            <!--new comments section end-->
              <br>
              <div id="stop" class="show scrollTop">
               <a href="#" class="myScroll"><i class="glyphicon glyphicon-chevron-up"></i></a><br><br>
             </div>
             <?php }else { ?>
             <h2><b>NO COMMENTS</b></h2>
             <?php 
           }
           ?>
           <script>
            var $star_rating = $('.star-rating .fa');
            var SetRatingStar = function() {
              return $star_rating.each(function() {
                if (parseInt($star_rating.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
                  return $(this).removeClass('fa-star-o').addClass('fa-star');
                } else {
                  return $(this).removeClass('fa-star').addClass('fa-star-o');
                }
              });
            };

            $star_rating.on('click', function() {
              $star_rating.siblings('input.rating-value').val($(this).data('rating'));
              return SetRatingStar();
            });

            SetRatingStar();
          </script>