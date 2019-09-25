<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>        
      <!--<div class="col-md-12 margin-bottom-30">
        <!-- BEGIN Portlet PORTLET-->
        <!-- END Portlet PORTLET-->
         <input type="hidden"  value="" class='form-control' name="inboxID" id="inboxID">
          <div class="inbox-body">
                          <a href="" data-toggle="modal" data-target=".myReply" data-placement="top" title="Compose" class="btn btn-compose" title="write">
                              Compose&nbsp;<i class="glyphicon glyphicon-envelope"></i>
                          </a>
          </div>
          <br>
              </aside>
                  <aside class="lg-side">
                  <?php
                  if($messages != FALSE && !empty($messages)){
                    foreach ($messages as $msg) { ?>
                      <div class="inbox-body">
                          <table class="table table-inbox table-hover">
                            <tbody>
                              <tr style="background-color:#F8F8F8;" id="read" name="read"
                              >
                                  <td class="view-message">
                                       <input type="checkbox" name="inboxID[]" class="del_message" value="<?php echo $msg->dmID; ?>">
                                  </td>
                                  <td class="view-message">
                                    <span><img src="<?php echo base_url($msg->filePath) ?>" alt="pro_pic" class="avatar image-responsive userImg"></span>
                                  </td>
                                   <?php if($msg->read == 0){ ?>
                                  <td class="view-message"><b><?php echo $msg->firstname.' '.$msg->lastname; ?></b></td>
                                   <?php }else { ?>
                                   <td class="view-message"><i><?php echo $msg->firstname.' '.$msg->lastname; ?></i></td>
                                   <?php } ?>
                                   <?php if($msg->read == 0){ ?>
                                   <td class="view-message"><b><?php echo $msg->email; ?></b></td>
                                   <?php }else{ ?>
                                   <td class="view-message"><i><?php echo $msg->email; ?></i></td>
                                   <?php } ?>
                                   <?php if($msg->read == 0){ ?>
                                  <td class="view-message"><b><a href="#<?php echo $msg->dmID; ?>" data-toggle="collapse" aria-expanded="false" 
                                  data-email="<?php echo $msg->email?>"
                                  data-name="<?php echo $msg->firstname. " " .$msg->lastname ?>"
                                  data-subject="<?php echo $msg->title?>"
                                  data-date="<?php echo $msg->date ?>"
                                  data-body="<?php echo $msg->body ?>"
                                  data-msgid="<?php echo $msg->inboxID ?>" id="read" name="read" class="readModal">
                                  <?php echo word_limiter($msg->title,10); ?></a></b></td>
                                  <?php }else{ ?>
                                  <td class="view-message"><i><a href="#<?php echo $msg->dmID; ?>" data-toggle="collapse" aria-expanded="false"><?php echo word_limiter($msg->title,10); ?></a></i></td>
                                  <?php } ?>
                                  <td class="view-message"></td>
                                  <?php if($msg->read == 0){ ?>
                                  <td class="view-message"><b><?php echo $msg->date; ?></b></td>
                                  <?php }else{ ?>
                                  <td class="view-message"><i><?php echo $msg->date; ?></i></td>
                                  <?php } ?>

                                  <td class="view-message"><div class="actions">
                                    <a href="#" name="fward" class="btn btn-red btn-circle replyModal"
                                   data-mail ="<?php echo $msg->email; ?>"
                                   data-sender="<?php echo $msg->senderID;?>"
                                   data-sub="<?php echo $msg->title; ?>"
                                   data-toggle="modal" 
                                   data-target="composeModal" 
                                   data-placement="top">
                                  <i class="fa fa-mail-reply"></i>
                                  </a>
                                    <a href="#" 
                                    data-messageID="<?php echo $msg->inboxID ?>"
                                    class="btn btn-red btn-circle" id="delMsgs" name="trashing" title="delete"
                                    data-toggle="modal" 
                                    data-target="#myModalMsg">
                                 <i class="glyphicon glyphicon-trash"></i>
                                 </a></div>
                                </td>
                              </tr>
                              <!--hidden row-->
                              <tr>
                                <td colspan="9">
                                    <div class="col-md-12 margin-bottom-30 accordian-body collapse" id="<?php echo $msg->dmID; ?>">
                                      <!-- BEGIN Portlet PORTLET-->
                                      <div class="portlet">
                                              <div class="portlet-body">
                                                <h4><?php echo $msg->title ;?></h4>
                                                <p><?php echo $msg->body; ?></p>
                                              </div>
                                      </div>
                                            <!-- END Portlet PORTLET-->
                                    </div>
                                </td>
                            </tr>
                            <?php
                          }
                        }else{ ?>
                        <tr>
                            <td colspan="8"><h3><b>NO MESSAGES</b></h3></td>
                        </tr>
                        <?php } ?>     
                          </tbody>
                          </table>
                      </div>
                      <?php
                    //}
                    //}
                   ?>
     



