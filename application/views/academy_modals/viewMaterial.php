<?php
defined('BASEPATH') OR exit('No direct script access allowed');

        //check if anything was retuned
        if (!empty($notes)) {
          //loop through the content from the table based on the query
          foreach ($notes as $note) {
            //show how long the file has been online
            $post_date = strtotime($note->publishDate);
            $now = time(substr($note->publishDate, 11));
            $units = 2; 
            //start table row that will be appended to the html table ?>          
            <tr>
              <td><?php echo character_limiter($note->studyTitle,2); ?></td>
              <td class="hidden-xs"><?php echo word_limiter($note->materialDesc,5); ?></td>
              <td class="hidden-xs"><?php echo timespan($post_date, $now, $units); ?> ago</td>
              <td class="text-center hidden-xs">
              <?php 
            //check if file exists
            if (file_exists($note->filePath)) { ?>
              <a href="<?php echo base_url().'Academy/download/'.$note->fileID; ?>" 
                data-download="<?php echo $note->fileID; ?>"
                class="downldoad">
                <span class="glyphicon glyphicon-download fa-2x"></span>
              </a>
                <?php 
            }else {//if not, display alternative icon
              ?>
              <a href="#">
                <span class="glyphicon glyphicon-minus-sign fa-2x" title="No file"></span>
              </a>
            <?php 
            } ?>
             
              </td>         
              <td>
                <a href="#" 
                    data-read_id="<?php echo $note->studyID; ?>" 
                    data-read_title="<?php echo $note->studyTitle; ?>" 
                    data-read_desc="<?php echo $note->materialDesc; ?>" 
                    data-read_pd="<?php echo timespan($post_date, $now, $units); ?> ago" 
                    data-read_path="<?php echo $note->filePath; ?>" 
                    class="viewGuardStudy" data-toggle="modal" 
                    data-target=".studyRead">
                    <span class="glyphicon glyphicon-eye-open fa-2x glyphicon-green" title="Read more..."></span>
                </a>
                  <?php 
                if (strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false) { ?>
                  <a href="#" 
                    data-study_cls="<?php echo $note->clsID; ?>" 
                    data-study_id="<?php echo $note->studyID; ?>" 
                    data-study_subname="<?php echo $note->subjectName; ?>" 
                    data-study_fileid="<?php echo $note->fileID; ?>" 
                    data-study_title="<?php echo $note->studyTitle; ?>" 
                    data-study_desc="<?php echo $note->materialDesc; ?>" 
                    data-study_pd="<?php echo $note->publishDate; ?>" 
                    data-study_path="Upload '<?php echo $note->fileName; ?> again to keep it or just replace it." 
                    class="editMaterial" data-toggle="modal" 
                    data-target="#studyModal">
                    <span class="glyphicon glyphicon-edit fa-2x glyphicon-green" title="Edit"></span>
                  </a>
                  <a href="#"  
                      data-study_id="<?php echo $note->studyID; ?>"  
                      data-study_cls="<?php echo $note->clsID; ?>" 
                      data-study_title="<?php echo $note->studyTitle; ?>" 
                      data-study_subname="<?php echo $note->subjectName; ?>" 
                      class="deleteMaterial">
                      <span class="glyphicon glyphicon-trash fa-2x glyphicon-red" title="Delete"></span>
                  </a>
                  <?php 
                } 
                  ?>
              </td>      
            </tr>
            <?php 
          }
        //if nothing brought back, print this   
        }else{ ?>
          <tr>
            <td colspan="4" class="hidden-xs glyphicon-red">
              <b>No study material for this subject.</b>
            </td>
            <td colspan="2" class="visible-xs glyphicon-red">
              <b>No study material.</b>
            </td>
          </tr>
          <?php 
        } 
        ?>
    
