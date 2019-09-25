<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    //check if anything was retuned
    if (!empty($home_work)) {
        //loop through the content from the table based on the query
        foreach ($home_work as $work) {
            //show how long the file has been online
            $post_date = strtotime($work->publishDate);
            $now = time(substr($work->publishDate, 11));
            $units = 2; ?>
            <!-- start table row that will be appended to the html table   -->         
            <tr>
                <td>
                    <?php 
                    if ((strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false) && ($_SESSION['userID']==$work->tUserID)){ ?>
                        <a href="#" 
                            data-assignid="<?php echo $work->assignID; ?>" 
                            data-assign_title="<?php echo $work->assignTitle; ?>"
                            data-assign_duedate="<?php echo substr($work->dueDate,0,16); ?>"  
                            data-assign_cglid="<?php echo $work->cglID; ?>"  
                            class="viewSubmissions" data-toggle="modal" data-target="#learnSubmissions">                      
                            <?php echo $work->assignTitle; ?>
                        </a>
                        <?php 
                    }
                    //display this title if its the learner logged in
                    if (identify_user_role($_SESSION['userID'])==='learner') { 
                       echo word_limiter($work->assignTitle);
                    } 
                    ?>
                </td>
                <td class="hidden-xs"><?php echo word_limiter($work->assignDesc,5); ?></td>
                <td class="hidden-xs"><?php echo timespan($post_date, $now, $units); ?> ago</td>
                <td class="hidden-xs"><?php echo substr($work->dueDate,0,16); ?></td>
                <td class="text-center hidden-xs">
                    <?php 
                    //check if there is a file
                    if (file_exists($work->filePath)) { ?>
                        <a href="<?php echo base_url().'Academy/download/'.$work->fileID; ?>"> 
                            <span class="glyphicon glyphicon-download fa-2x" title="Download File"></span>
                        </a>
                        <?php 
                    //if not display another icon
                    }else { ?>
                        <a href="#"> 
                            <span class="glyphicon glyphicon-minus-sign fa-2x" title="No File"></span>
                        </a>
                        <?php 
                    }
                    ?>
                </td>
                <?php 
                //check who is teacher who is logged in
                //then display actions/options
                if ((strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false) && ($_SESSION['userID']==$work->tUserID)) { ?>
                    <td>
                        <a href="#" 
                            data-assign_duedate="<?php echo substr($work->dueDate,0,16); ?>" 
                            data-assign_cls="<?php echo $work->clsID; ?>" 
                            data-assign_cglid="<?php echo $work->cglID; ?>" 
                            data-assignid="<?php echo $work->assignID; ?>" 
                            data-assign_title="<?php echo $work->assignTitle; ?>" 
                            class = "viewSubmissions" 
                            data-toggle="modal" data-target="#learnSubmissions">
                            <span class="glyphicon glyphicon-eye-open fa-2x glyphicon-green" title="View Submissions"></span>
                        </a>
                        <a href="#" 
                            data-subname="<?php echo $work->subjectName; ?>" 
                            data-clsid="<?php echo $work->clsID; ?>" 
                            data-assignid="<?php echo $work->assignID; ?>" 
                            data-assign_title="<?php echo $work->assignTitle; ?>" 
                            data-assign_desc="<?php echo $work->assignDesc; ?>" 
                            data-assign_duedate="<?php echo $work->dueDate; ?>" 
                            data-assign_fileid="<?php echo $work->fileID; ?>" 
                            data-assign_path="<?php echo $work->filePath; ?>" 
                            class = "editAssignment" 
                            data-toggle="modal" data-target="#assignModal">
                            <span class="glyphicon glyphicon-edit fa-2x glyphicon-green" title="Edit Assignment"></span>
                        </a>
                        <a href="#"  
                            data-assignid="<?php echo $work->assignID; ?>" 
                            data-subname="<?php echo $work->subjectName; ?>" 
                            data-title="<?php echo $work->assignTitle; ?>" 
                            data-clsid="<?php echo $work->clsID; ?>" 
                            class="deleteAssigment">
                            <span class="glyphicon glyphicon-trash fa-2x glyphicon-red" title="Delete Assignment"></span>
                        </a>
                    </td>
                    <?php 
                    //check if learner is logged in
                    //for learner options only
                    }
                    if (identify_user_role($_SESSION['userID'])=='learner'){ ?>
                        <td>
                            <a href="#"
                                data-ass_id ="<?php echo $work->assignID; ?>" 
                                class="readAssignment" 
                                data-toggle="modal" data-target="#assignSubmit">
                                <span class="glyphicon glyphicon-eye-open fa-2x glyphicon-green"></span>
                            </a>
                        </td>
                        <?php  
                    } ?>
            </tr>
            <?php 
        }
    //if nothing brought back, print this    
    }else{ ?>
        <tr>
            <td colspan="4" class="hidden-xs glyphicon-red">
                <b>Its lonely in here, but lets hope this status changes sooner.</b>
            </td>
            <td colspan="2" class="visible-xs glyphicon-red">
                <b>No assignments.</b>
            </td>
        </tr>
    <?php
    }
     ?>
  
