<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * controller responsible for 
 */
class Academy extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Schedule_model','timetable');
        $this->load->model('Login_model','Login');
        $this->load->model('Material_model','material');
        $this->load->model('Teacher_model','teachers');
        $this->load->model('Users_model');
        $this->load->model('Admin_model','admin');
        $this->load->model('Subject_model','subject');
        $this->load->model('Guardian_model','guardians');
        $this->load->model('Assignment_model','assignment');
        $this->load->model('Discussion_model','discussion');
        $this->load->model('Files_model','files'); 
        $this->load->model('Learner_model','learners');
        $this->load->model('Attendance_model','attendance');
        $this->load->model('Progress_model','progress');
        $this->load->model('Level_Group_model','level_group');
        $this->load->model('Message_model','message_model');
        $this->load->model('Announce_model','announce_model');
        $this->load->library(array('form_validation','upload'));
        $this->load->helper(array('form', 'url','security','download','date','text'));
        //check user credentials
        $is_logged_in = ($this->session->userdata('is_logged_in')) ? $this->session->userdata('is_logged_in') : FALSE;
        //if the user is not logged in
        if (!($is_logged_in)) { 
        //check if cookie exist for login
            if (!$this->Login->checkLoginWithCookie()) { 
                //otherwise redirect to login page
                redirect('Guests#login','refresh');  
            }//end cookie
        }//end logged_in

        //prohibit any user who is just an admin
        if (identify_user_role($_SESSION['userID'])==='admin') {
            redirect('App');
        }
    }//end constructor

    public function index()
    {
        
        $data['pageToLoad'] = 'academic/academic';
        $data['pageActive'] = 'academic'; 
        //when teacher_guadian is logged in, 
        //make both children and subjects options on the main links to be available
        $choice = !empty(html_escape($this->input->GET('c'))) ? html_escape($this->input->GET('c')) : ''; 
        //this will be used when the teacher_guardian is logged in
        //the choice will be either [subjects or children]
        $data['choice'] = $choice;
        //check who is logged in and display the subjects
        if (isset($_SESSION['userID']) && (identify_user_role($_SESSION['userID']) == 'learner')) {
            //assign learner USERID to search array
            $search['learner'] = $_SESSION['userID'];
           // $data['tt_subjects'] = $this->subject->getSubject($search);
            //initiate the data that will be needed by the user
            $data = $this->getContent($data,$search);
            //Do we have subjects for this teacher?
        }elseif (isset($_SESSION['userID']) 
            && strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false 
            && ($choice == '' OR $choice == 'ms')) {
            //assign teacher USERID to search array
            $search['teacher'] = $_SESSION['userID'];
            //$data['tt_subjects'] = $this->subject->getSubject($search);
            //initiate the data that will be needed by the user
            $data = $this->getContent($data,$search);
        //parent child/ren
        }elseif (isset($_SESSION['userID']) 
            && strpos(identify_user_role($_SESSION['userID']), 'guardian')!==false
            && ($choice == '' OR $choice == 'mc')) {
            //control variable to check from where the request was made
            $from_index = 1;
            //assign guardian USERID to search array
            $search['guardian'] = $_SESSION['userID'];
            //get details of guardian child/ren
            $myKids = $this->guardians->getGuardChild($search);
            //check if this is not empty
            if ($myKids != NULL) {
                //array to hold kids to be loaded first time page is opened
                $data['kids'] = $myKids;
                //array with guardian child details [first child]
                $guard_child = array( 
                                    'fname'=>$myKids[0]->lFName,
                                    'lUserID'=>$myKids[0]->lUserID
                                );
                //get all the content from this method with assignments and study material
                $back_to_index = $this->getGuardLearnSubject($from_index, $guard_child);
                //data to write for assignments
                $data['guard_child_assign']= $back_to_index['assign'];
                //data to write for study material
                $data['guard_child_study']= $back_to_index['study'];
                //data to write for attendance
                $data['guard_child_attend']= $back_to_index['attend'];
                //data to write for progress
                $data['guard_child_progress']= $back_to_index['progress'];
            }
        }

        //load the view
        $this->load->view('ini',$data);
 
    }//end index

    /**
     * [getContent method prepares the content for learner and teacher
     * @param  [int] $userID  [the id of the user logged in]
     * @return [array]         [data array with the details to be send to the view ]
     */
    public function getContent(array $data = array(),array $search=array())
    {   
        $from_index = 1;
        //array to hold subjects to be loaded first time page is opened
        $activeTabs = $this->subject->getSubject($search);
        /*//get timetable data based on the user logged in
        $data['active_tt_schedule'] = $this->timetable->getSchedule($search);*/
        //check if this is not empty
        if (!empty($activeTabs)) {
            //array to hold subjects to be loaded first time page is opened
            $data['subjects'] = $activeTabs; 
            //var_dump($data['subjects']);             
            //array to hold details of the first subject of teacher
            $user_subjects = array( 
                            'clsID'=>$activeTabs[0]->clsID,
                            'cglID'=>$activeTabs[0]->cglID,
                            'subjectName'=>$activeTabs[0]->subjectName
                            );
            //use this methods to populate the study material tab on page load
            $data['study'] = $this->getMaterial($from_index,$user_subjects);
            //use this methods to populate Discussion tab on page load
            $data['discuss'] = $this->getCategoryBySubject($from_index,$user_subjects);
            //use this methods to populate the Assignment tab on page load
            $data['assign'] = $this->getAssignments($from_index,$user_subjects);
            //use this methods to populate Attendance tab on page load
            $data['attend'] = $this->getLearnersForAttend($from_index,$user_subjects);
            //get assessment type [group] for progress
            $data['activeAssessType']= $this->progress->getAssessmentType();
            //get list of specific assessments as created under each type [group]
            $data['activeAssessment']= $this->progress->getAssessment();
            //use this methods to populate Progress tab on page load
            $data['progress'] = $this->getLearnersProgress($from_index,$user_subjects);

        }

        return $data;
    }
    
    /**
     * [download method will handle all things download related]
     * @param  [int] $fileid [is the id of the file to be downloaded]
     */
    public function download($fileid=0){
        //load the zip library
        $this->load->library('zip');
        //check if the file is 
        if(!empty($fileid) && is_numeric($fileid) && $fileid!=0){
            //search for this file id on the file table
            $search['download'] = $fileid;
            //get file info from database
            $fileInfo = $this->files->getFile($search);
            if (!empty($fileInfo)) {
                //
                $this->zip->add_dir('my-download');
                //set file path and name
                $path = $fileInfo[0]->filePath;
                $name = $fileInfo[0]->fileName;
                 // add data own data into the folder created
                $this->zip->add_data('my-download/'.$name,file_get_contents($path));
                //download file from directory
                //force_download($file, NULL);
                $this->zip->download('download.zip');
            }
        }              
    }//end download

/**==========================================================================================================
 **         //////////////////////////////STUDY MATERIAL section /////////////////////////////
 **==========================================================================================================
 **/

/**
 * [addMaterial method to add study material]
 */
public function addMaterial(){
    //get the error number from FILES
    $error = $_FILES['fileUpload']['error'];
    //get the fileSize of the fileUpload
    $filesize = $_FILES['fileUpload']['size'];
    //assign maximum size to a variable
    $maxSize = 50*1024*1024;
    //check if there is a file
    if($error==4) {
        echo "Please select a file";
        //return;
    }else if($filesize > $maxSize){
        echo "File size should be less than or equal to 50MB";
        //return;
    }else {

        //validate this id if we on update mode
        if (html_escape($this->input->POST('studyID')) != '') {  
            $config1 = array(          
                array(
                    'field' => 'studyID',
                    'label' => 'Study material ID',
                    'rules'=>array('required',array('material_exist',array($this->material,'material_exist'))),
                    'errors' => array('material_exist'=>'No such study material exist in the system.')
                )
            );
            $this->form_validation->set_rules($config1);
        }
        
        $config_validation = array(
             array(
                'field' => 'title',
                'label' => 'title',
                'rules' => 'required|max_length[100]|min_length[5]', 
                'errors' => array('required'=>'You have not provided %s for the material.')
                ),
             array( 
                'field' => 'description',
                'label' => 'description',
                'rules' => 'required|max_length[255]|min_length[5]', 
                'errors' => array('required'=>'You have not provided %s for the material.')
                )
        );
    }
    //set validation rules
    $this->form_validation->set_rules($config_validation);
    //check if all went well
    if ($this->form_validation->run()===FALSE) {
        //send errors to the modal
        echo validation_errors();

    }else {
       
        //call upload file method here
        $fileData = $this->files->uploadFile();
        //check if the file was uploaded
        if ($fileData['error']==0) {
            //check if we on add or edit mode
            if(html_escape($this->input->POST('studyID')) == ''){
                //add study material
                $insertStatus = $this->material->addMaterial(html_escape($this->input->post()),$fileData);
                //send feedback based on insert
                 if ($insertStatus) {
                    echo "Added";
                }else {
                    echo "NO";
                }
            }else {
                //update study material
                $updateStatus = $this->material->updateMaterial(html_escape($this->input->post()),$fileData);
                //send feedback based on update
                 if ($updateStatus) {
                    echo "updated";
                }else {
                    echo "NO";
                }
            }
        //else for testing file upload
        } else {
            echo 'File was not uploaded, please try again';
        }

        //send the status to jquery for message display to the user.
    }
    
}//end addMaterial
/**
 * [deleteMaterial based on the studyID]
 * @return [type] [description]
 */
public function deleteMaterial()
{
     //this is to validate the assignment ID of assignment being submitted
        $config_validation = array(
             array(
                'field' => 'studyID',
                'label' => 'Study material ID',
                'rules'=>array('required',array('material_exist',array($this->material,'material_exist'))),
                'errors' => array('material_exist'=>'No such study material exist in the system.')
                ),
             
        );
    //set validation rules
    $this->form_validation->set_rules($config_validation);
    //check if all went well
    if ($this->form_validation->run()===FALSE) {
        //send errors to the modal
        echo validation_errors();
    }else {
        $studyID = !empty(html_escape($this->input->POST('studyID'))) ? html_escape($this->input->POST('studyID')) : 0;
        //$editStudy=$this->material->getStudyMaterial($search);
        $deleteStatus = $this->material->deleteMaterial($studyID);
        //did we get success or failer
         if ($deleteStatus) {
            echo "YES";
        }else {
            echo "NO";
        }
    }

}//end deleteMaterial

/**
 * [getMaterial description]
 * @param  integer $from_index    [description]
 * @param  array   $user_subjects [description]
 * @return [type]                 [description]
 */
public function getMaterial($from_index = 0, array $user_subjects = array())
{
    //check if the request is send frm the index or ajax
    if ($from_index == 1) {
        //class level subject id of the first subject on the list
        $clsID = $user_subjects['clsID'];
        //the subject name of the first subject on the list of many
        $subName = $user_subjects['subjectName'];
    }else{
        //subject name to search for
        $clsID = !empty(html_escape($this->input->POST('clsID'))) ? html_escape($this->input->POST('clsID')) : 0;
        //subject name to search for
        $subName = !empty(html_escape($this->input->POST('subjectName'))) ? html_escape($this->input->POST('subjectName')) : '';
        //study material id to search for
        $studyID = !empty(html_escape($this->input->POST('studyID'))) ? html_escape($this->input->POST('studyID')) : 0;  
    }
    
    //load subject inside search
    $search['subject'] = $subName;
    //load clsID inside search
    $search['clsID'] = $clsID;
    //check who is logged in
    if (strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false) {
        //the user_id of the teacher who is logged in
        $search['teacher'] = $_SESSION['userID'];

    }elseif (identify_user_role($_SESSION['userID'])== 'learner') {
        //the user_id of the learner who is logged in
        $search['learner'] = $_SESSION['userID'];

     }
    //send request for material
    $notes = $this->material->getStudyMaterial($search);
    //check which mode to execute
    if ($clsID != 0) {
        //call method to buid table for view
        $material = $this->viewMaterial($notes);
        if ($from_index == 1) {
            return $material;
        }
        //send data back to JSON requester
        echo json_encode($material);
        //if this ID has value other than ZERo, then get data for edit
    }else if ($studyID != 0) {
        //load studyID to search
        $search['study'] = $studyID;
        //get data that you going to edit
        $editStudy = $this->material->getStudyMaterial($search);
        //send data back to JSON request
        echo json_encode($editStudy);
    }

}//end getMaterial
/**
 * viewMaterial method is used to build the view for study material
 * the array with all study material is passed as the argument
 * and the view is returned back to the caller
 * @return [string] view which will be printed to the page
 */
public function viewMaterial($notes)
{   
    //the array with the study material data needed for the view 
    $data['notes'] = $notes;
    //assign the view to the variable
    $view = $this->load->view('academy_study/viewMaterial',$data,TRUE);
    //return results back to the caller
    return $view;

}//end view Material

/**==========================================================================================================
 ** //////////////////////////////Attendance section /////////////////////////////
 **==========================================================================================================
 **/
/**
 * displayAttendance method will get the data which is needed to build the attendance
 * then load the view into the variable to be returned back to the caller
 * @return [string] string build with html code for JSON return
 */
public function displayAttendance($learnerList,$reason=0,$clsID=0,array $attend = array())
{ 
    //assign all the variables to data array
    $data['reason'] = $reason;
    $data['attend'] = $attend;
    $data['clsID'] = $clsID;
    $data['learnerList'] = $learnerList;
    $search['clsID'] = $clsID;
    //get the total count of the expected attendance for the subject
    $data['attend_count'] = count($this->attendance->getAttendance($search));
    //get attendance data based on the class level subject id
    $data['existing_attend'] = $this->attendance->getAttendance();
    //assign the attendance view into the variable
    $view = $this->load->view('academy_attendance/displayAttendance',$data,TRUE);

    return $view;

}//end displayAttendace

/**
 * [getLearnersForAttend description]
 * @return [type] [description]
 */
public function getLearnersForAttend($from_index = 0, array $user_subjects = array())
{
    //check if the request is send frm the index or ajax
    if ($from_index == 1) {
        //class level subject id of the first subject on the list
        $clsID = $user_subjects['clsID'];
        //get the class group level to identify which class learners are
        $cglID = $user_subjects['cglID'];
        //the subject name of the first subject on the list of many
        $subName = $user_subjects['subjectName'];
        //the option of what the user wants to do [1=view]
        $reason = 1;
    }else{
        //the option of what the user wants to do
        $reason = !empty(html_escape($this->input->POST('reason'))) ? html_escape($this->input->POST('reason')) : 0;
        //get class level subject to know which subject is
        $clsID = !empty(html_escape($this->input->POST('clsID'))) ? html_escape($this->input->POST('clsID')) : 0;
        //get the class group level to identify which class learners are
        $cglID = !empty(html_escape($this->input->POST('cglID'))) ? html_escape($this->input->POST('cglID')) : 0;
        //subject name to search for
        $subName = !empty(html_escape($this->input->POST('subjectName'))) ? html_escape($this->input->POST('subjectName')) : '';
    }
    //getting the start date
    $startDate = !empty(html_escape($this->input->POST('startDate'))) ? html_escape($this->input->POST('startDate')) : '';
    //get the end date
    $endDate = !empty(html_escape($this->input->POST('endDate'))) ? html_escape($this->input->POST('startDate')) : '';
    //check who is logged in
    if (strpos(identify_user_role($_SESSION['userID']), 'teacher') !== false) {
        //the user_id of the teacher who is logged in
        $search['end'] = $endDate;
        $search['start'] = $startDate;
        $search['cglID'] = $cglID;

    }elseif (identify_user_role($_SESSION['userID']) == 'learner') {
        //the user_id of the learner who is logged in
        $search['learner'] = $_SESSION['userID'];
        $search['clsID'] = $clsID;
     }
    //get the list of learners registered for the subject
    $subjLearners = $this->learners->getLearners($search);
    //VIEW MODE
    if ($reason == 1 || $reason == 0) {
        //display the list of students for attendance
        $learnerList = $this->displayAttendance($subjLearners,$reason,$clsID); 
        if ($from_index == 1) {
            return $learnerList;
        }
        //return request to script        
        echo json_encode($learnerList);
    }else
    //if user wants to mark attendance, then display buttons [red/green]
    //MARK ATTENDANCE MODE
    if (($reason == 2)) {
        //use today's date and clsID to search for records
        $search['today'] = date('Y-m-d');
        $search['clsID'] = $clsID;
        //send a data request to RETRIEVE OLD LIST of attendance by today's date
        $attends = $this->attendance->getAttendance($search);
        //check if the request returned NULL
        if ($attends == NULL) {
            //create new records for the students then display list
            $insertStatus = $this->attendance->insertLearners($subjLearners,$clsID);
            //if all went 'a' OK with insert
            if ($insertStatus) {
                //then retrieve NEW list based on today's date 
                $attend = $this->attendance->getAttendance($search);
                //call display attendance to create a table with learners
                $learnerList = $this->displayAttendance($subjLearners,$reason,$clsID,$attend); 
            } 
            
        }else {
           //call display attendance to create a table with learners
            $learnerList = $this->displayAttendance($subjLearners,$reason,$clsID,$attends);  
        }
        //return request to script        
        echo json_encode($learnerList);
    }

}//getLearnersForAttend ends

/**
 * markAttendance method is resposible for the accepting attendance status of the learners
 * Method will receive as inputs [learnerID, class level subjects, and status(present-1 or absent-0)]
 * Method to update table in the database will be called 
 */
public function markAttendance()
{ 
    //the record being updated
    $attend['rowID']=!empty(html_escape($this->input->POST('rowID'))) ? html_escape($this->input->POST('rowID')) : 0; //check if exist   
    //the learner being marked
    $attend['lID']=!empty(html_escape($this->input->POST('lID'))) ? html_escape($this->input->POST('lID')) : 0; //check if exist
    //class level subject ID for selected subject
    $attend['clsID']=!empty(html_escape($this->input->POST('clsID'))) ? html_escape($this->input->POST('clsID')) : 0;//check if exist
    //status being send (present/absent)
    $attend['status']=html_escape($this->input->POST('status')); //check if status is 0 or 1
    //call method to update attendance   
    $updateStatus = $this->attendance->markAttendance($attend);
    //check if all went well
    if ($updateStatus) {
        echo "Updated";
    }

}//markAttendance ends
/**
 * ==============================================================================================
 *     //////////////////////////////Assignment section /////////////////////////////
 *===============================================================================================
 **/
/**
 * addAssignments method will be used to add new assignment and update assignment details
 * Each assignment will have file with instructions, so hence file input 
 */
public function addAssignments(){
    //get the error number from FILES
    $error = $_FILES['fileUpload']['error'];
    //get the fileSize of the fileUpload
    $filesize = $_FILES['fileUpload']['size'];
    //assign maximum size to a variable
    $maxSize = 50*1024*1024;
    //if no file selected
    if($error==4) {
        echo "Please select a file";
        return;
    }else if($filesize > $maxSize){
        echo "File size should be less than or equal to 50MB";
        return;
    }else {

         //validate this id if we on update mode
        if (html_escape($this->input->POST('assignID')) != '') {  
            $config1 = array(          
                array(
                    'field' => 'assignID',
                    'label' => 'Assignment ID',
                    'rules'=>array('required',array('assignExist',array($this->assignment,'assignExist'))),
                    'errors' => array('assignExist'=>'No such assignment exist in the system.')
                )
            );
            $this->form_validation->set_rules($config1);
        }
        $config_validation = array(
             array(
                'field' => 'title',
                'label' => 'title',
                'rules' => 'required|max_length[100]|min_length[5]|xss_clean', 
                'errors' => array('required'=>'You have not provided %s for the assignment.')
                ),
             array( 
                'field' => 'description',
                'label' => 'description',
                'rules' => 'required|max_length[255]|min_length[5]', 
                'errors' => array('required'=>'You have not provided %s for the assignment.',
                                    'xss_clean'=>'text not clean')
                ),
              array(
                'field' => 'dueDate',
                'label' => 'Due Date',
                'rules' => 'required',
                'errors' => array('required'=>'You have not provided valid %s for the assignment.')
                    )
        );
    }
    //set validation rules
    $this->form_validation->set_rules($config_validation);
    //check if all went well
    if ($this->form_validation->run()===FALSE) {
        //send errors to the modal
        echo validation_errors();
    }else {
        //sanitize user input
        $data =$this->security->xss_clean($this->input->post());
        //call upload file method here
        $fileData = $this->files->uploadFile();
        //check if the file was uploaded
        if ($fileData['error']==0) {
            //check if we on add or edit mode
            if(html_escape($this->input->post('assignID'))==''){
                //insert assignment
                $insertStatus = $this->assignment->addAssignment($data,$fileData);
                //send feedback based on insert
                 if ($insertStatus) {
                    echo "Added";
                }else {
                    echo "NO";
                }
            }else {
                //update assignment
                $updateStatus = $this->assignment->updateAssignment($data,$fileData);
                //send feedback based on update
                 if ($updateStatus) {
                    echo "updated";
                }else {
                    echo "NO";
                }
            }
        //else for testing file upload
        } else {
            echo 'File was not uploaded, please try again';
        }

        //send the status to jquery for message display to the user.
    }
    
}//end addAssignments

/**
 * deleteAssignment method will be used to delete existing assignment
 * method receive assignment ID of assignment to be deleted as the input
 * then returns Yes/No for success
 * 
 */
public function deleteAssignment()
{   
    
    $config_validation = array(
             array(
                'field' => 'assignID',
                'label' => 'Assignment ID',
                'rules'=>array('required',array('assignExist',array($this->assignment,'assignExist'))),
                'errors' => array('assignExist'=>'No such assignment exist in the system.')
                ),
        );
     //set validation rules
    $this->form_validation->set_rules($config_validation);
    //check if all went well
    if ($this->form_validation->run()===FALSE) {
        //send errors to the modal
        echo validation_errors();
    }else {    
        //get the ID of the assignment to be deleted
        $assignID = !empty(html_escape($this->input->POST('assignID'))) ? html_escape($this->input->POST('assignID')) : 0;
        if ($assignID > 0) {
            //call method to delete the assignmnet from the database
            $deleteStatus = $this->assignment->deleteAssigment($assignID);
             if ($deleteStatus) {
                echo "YES";
            }else {
                echo "NO";
            }
        }else{
            echo "NO";
        }
    }



}//end deleteAssignment

/**
 * getAssignments method is reponsible for retrieving existing assignments on the db
 * this will be retrieved based on the user who is logged in
 * several inputs are received in order to pull the correct data from the database
 * @param  integer $from_index    tells if the request is from first time load [Index]
 * @param  array   $user_subjects has details needed to pull data from db on pageLoad
 * @return json_encoded string                 
 */
public function getAssignments($from_index = 0, array $user_subjects = array())
{
    //check if the request is send frm the index or ajax
    if ($from_index == 1) {
        //class level subject id of the first subject on the list
        $clsID = $user_subjects['clsID'];
        //the subject name of the first subject on the list of many
        $subName = $user_subjects['subjectName'];
    }else{
        //clsID name to search for
        $clsID = !empty(html_escape($this->input->POST('clsID'))) ? html_escape($this->input->POST('clsID')) : 0;
        //subject name to search for
        $subName = !empty(html_escape($this->input->POST('subjectName'))) ? html_escape($this->input->POST('subjectName')) : '';
    }
    //assignment id to search for
    $assignID = !empty(html_escape($this->input->POST('assignID'))) ? html_escape($this->input->POST('assignID')) : 0;
    //check who is logged in
    if (strpos(identify_user_role($_SESSION['userID']), 'teacher') !== false) {
        //assignment ID teacher clicked
        $search['assign'] = $assignID;
        //the user_id of the teacher who is logged in
        $search['teacher'] = $_SESSION['userID'];
    //check if is learner logged in
    }elseif (identify_user_role($_SESSION['userID'])== 'learner') {
        //the user_id of the learner who is logged in
        $search['learner'] = $_SESSION['userID'];
        $search['assign'] = $assignID;
        $learnerSubmit=$this->assignment->getAssignSubmission($search);
    }
    //use this variable to search for record
    $search['subject'] = $subName;
    //use this variable to search for record
    $search['clsID'] = $clsID;
    //send request for material
    $home_work=$this->assignment->getAssignments($search);
    //use this variable to search for record
    $search['assign'] = $assignID;
    //check if subject was sent
    if($clsID!= 0) {
        //view assignments 
        $assignment=$this->viewAssignments($home_work);
        if ($from_index == 1) {
            return $assignment;
        }
        //return to AJAX
        echo json_encode($assignment);
    //if not subject, then they want to edit assignment
    }elseif ($assignID!=0) {
        //get contents of assignment to be editted
        $editAssign=$this->assignment->getAssignments($search);
        //create associative array to send back to AJAX
        $assignData= array(
                    'assignEdit'=>$editAssign,
                    'learnerSubmit'=>$learnerSubmit
                    );
        //return to AJAX
        echo json_encode($assignData);
    }

}//end of getAssignments

/**
 * viewAssignments method is used to build the results that the user will see when pulled from the db
 * Method receives array of all assignments based on the supplied ID for the user who is logged in
 * @param  [array] $home_work has the data needed for building the view
 * @return [string]        this variable holds the view to be printed on the page
 */
public function viewAssignments($home_work)
{   
    //assign the assignment data to data array so it can be send with the view
    $data['home_work'] = $home_work;
    //assign the view to the variable
    $view = $this->load->view('academy_assignments/view_assignments',$data,TRUE);
    //return the results to the caller
    return $view;
    
}//end view viewAssignments

/**
 * submitAssignment method will be used to accept the assignment during submission
 * method accepts file and other inputs 
 * then returns success or failure back to the caller
 */
public function submitAssignment()
{   
    //get the error number from FILES
    $error = $_FILES['fileUpload']['error'];
    //get the fileSize of the fileUpload
    $filesize = $_FILES['fileUpload']['size'];
    //assign maximum size to a variable
    $maxSize = 50*1024*1024;
    //if no file selected
    if($error==4) {
        echo "Please select a file";
        //return;
    }else if($filesize > $maxSize){
        echo "File size should be less than or equal to 50MB";
        //return;
    }else {
        //this is to validate the assignment ID of assignment being submitted
        $config_validation = array(
             array(
                'field' => 'aid',
                'label' => 'Assignment ID',
                'rules'=>array('required',array('assignExist',array($this->assignment,'assignExist'))),
                'errors' => array('assignExist'=>'No such assignment exist in the system.')
                ),
             
        );
    }
    //set validation rules
    $this->form_validation->set_rules($config_validation);
    //check if all went well
    if ($this->form_validation->run()===FALSE) {
        //send errors to the modal
        echo validation_errors();
    }else {
       $data =$this->security->xss_clean($this->input->post());
        //call upload file method here
        $fileData = $this->files->uploadFile();
        //check if the file was uploaded
        if ($fileData['error']==0) {
            //insert assignment
            $insertStatus = $this->assignment->submitAssignment($data,$fileData);
            //send feedback based on insert
             if ($insertStatus) {
                echo "Added";
            }else {
                echo "NO";
            }
        }else {
                echo "NO";
            }
    }

}//end of submitAssignment

/**
 * resetSubmission method will be used when the teacher wants to reset the assignment submission of the learner
 * method will accept assignment ID and the learner ID 
 * then return success or failure based on reset results
 */
public function resetSubmission()
{   

    //this is to validate the assignment ID of assignment being submitted
        $config_validation = array(
             array(
                'field' => 'lID',
                'label' => 'Learner ID',
                'rules'=>array('required',array('learnerExist',array($this->learners,'learner_exist'))),
                'errors' => array('learnerExist'=>'No such learner exist in the system.')
                ),
             array(
                'field' => 'assignID',
                'label' => 'Assignment ID',
                'rules'=>array('required',array('assignExist',array($this->assignment,'assignExist'))),
                'errors' => array('assignExist'=>'No such assignment exist in the system.')
                ),
        );
    //set validation rules
    $this->form_validation->set_rules($config_validation);
    //check if all went well
    if ($this->form_validation->run()===FALSE) {
        //send errors to the modal
        echo validation_errors();
    }else {
        //get the ID of the assignment to be reset
        $assignID = !empty(html_escape($this->input->POST('assignID'))) ? html_escape($this->input->POST('assignID')) : 0;
        //get the ID of the learner assignment to be reset
        $learnerID = !empty(html_escape($this->input->POST('lID'))) ? html_escape($this->input->POST('lID')) : 0;
        //check if values are not default
        if ($assignID > 0 && $learnerID > 0) {
            //call method to reset assignment submission
            $done = $this->assignment->resetSubmission($assignID,$learnerID);
            //check if all went well and send response as such
            if ($done) {
                echo "DONE";
            }else {
                echo "NO";
            }
        }
     
    }
}

/**
 * searchLearnerSubmit method will be used to search the learner from the submission list
 * this will be used by the teacher to find specific learner
 * @return json_encoded string
 */
public function searchLearnerSubmit()
{   
    //value to be searched
    $searchValue = !empty(html_escape($this->input->POST('lsearch'))) ? html_escape($this->input->POST('lsearch')) : '';
    //assign search array with search value
    $search['searchLearner'] = $searchValue;
    //get results of learners based on search value
    $learnerList = $this->learners->getLearners($search);
    //call method to build the results in the correct format
    $searchResults = $this->buildSubmissions($learnerList);
    //return results back to the caller
    echo json_encode($searchResults);
}

/**
 * get_assignment_submissions method will be used to get all the assignments submitted by the learner
 * method will receive array of learnerID's and assignmentID
 * then download method will be called to handle file downloads
 */
public function get_assignment_submissions()
{   
    //get learner list from the form/modal
    $learner_list =html_escape($this->input->POST('lID'));
    //get the assignment id forwhich the assignments are to be downloaded
    $assignID = html_escape($this->input->POST('assignID'));
    //check if the arrays are not empty
    if (!empty($assignID) && !empty($learner_list)) {
        //assign assingment ID to search array
        $search['assign'] = $assignID;
        //get submission of all learners
        $submissions = $this->assignment->getAssignSubmission($search);
        //call method to download all the assignments of selected learners
        $status = $this->download_all_assignments($submissions,$learner_list);
        //return results to ajax
        echo $status; 
    }else{
        echo 'NO';
    }
    
}

/**
 * [download_all_assignments method is responsible for downloading the assignmnets of the learner
 * method will accept a list of learner, and learner submission
 * then it will return YES or NO status
 * @param  [array] $submissions  [learners who submitted the assignmnent on question]
 * @param  [array] $learner_list [selected list of learners by user/teacher]
 * @return [string]               [YES/NO] denotes success or failure
 */
public function download_all_assignments($submissions,$learner_list)
{
    //check if there were submissions found
    if (!empty($submissions)) {
        //load the 'zip' library
        $this->load->library('zip');
        //get the name of the assignment
        $assign_name = 'assignment - '.$submissions[0]->assignTitle;
        //create a folder using the name of the assignment
        $this->zip->add_dir($assign_name);
        //loop thru the submissions 
        foreach ($submissions as $submission) {
            //loop thru the list of learners
            for($i = 0; $i < count($learner_list); $i++){
                //check if the learner on list match learner on submission list
                if ($submission->lID == $learner_list[$i]) {
                    //if there is a match, check if there is a file
                    if (file_exists($submission->filePath)) {
                        //if file found, add it to the folder with its original name
                        $this->zip->add_data($assign_name.'/'.$submission->fileName,
                                    file_get_contents($submission->filePath));
                    }
                }
            }
        }
        //create a zip folder with the assignment name
        $this->zip->download($assign_name.'.zip');
        //return YES for success
        return 'YES';    
    }else{
        //no submissions were found for this assignment
        return 'NO';
    }

}//end download_all_assignments

/**
 * getLearnersSubmissions method is used when the user [teacher] wants to view learner submissions
 * method accept assignmentID and the class group level of the learners
 * @return [json_encoded string] 
 */
public function getLearnersSubmissions()
{
    //assignment id to search for
    $assignID = !empty(html_escape($this->input->POST('assignID'))) ? html_escape($this->input->POST('assignID')) :0;
    //class group to search for
    $cglID = !empty(html_escape($this->input->POST('cglID'))) ? html_escape($this->input->POST('cglID')) : 0;
    //assign the class group of the learner to search array
    $search['cglID'] = $cglID;
    //get list of learners based on the class group
    $learnerList = $this->learners->getLearners($search);
    //build the results in acceptable format
    $learnerSubmit = $this->buildSubmissions($learnerList,$assignID);
    //return to caller
    echo json_encode($learnerSubmit);       
        
}//end getLearnersSubmissions
/**
 * buildSubmissions method is used to build the the view for the assignmnet submissions
 * @param  [array]  $learnerList [list of learners in the class group level]
 * @param  integer $assignID    [the assignment ID where the view is requested]
 * @return [string]               [string of the view]
 */
public function buildSubmissions($learnerList = array(),$assignID=0)
{
    //assign the learner and submission data to data array so it can be send with the view
    $data['learnerList'] = $learnerList;
    $data['assignID'] = $assignID;
    $search['assign'] = $assignID;
    //get submission per learner
    $data['submissions'] = $this->assignment->getAssignSubmission($search);
    //assign the view to the variable
    $view = $this->load->view('academy_assignments/build_submission',$data,TRUE);
    //return the results to the caller
    return $view;
    
}
/**==========================================================================================
 *      ////////////////////////////// Discussion section /////////////////////////////
 *===========================================================================================
 **/

/**
 * [addDiscCategory this will be used to add or update discussion categories]
 */
public function addDiscCategory(){

    //validate this id if we on update mode
    if (html_escape($this->input->POST('discID')) != '') {  
        $config1 = array(          
            array(
                'field' => 'discID',
                'label' => 'Discussion category ID',
                'rules'=>array('required',array('category_exist',array($this->discussion,'dsicuss_category_exist'))),
                'errors' => array('category_exist'=>'No such dsicuss category exist in the system.')
            ),
        );
        $this->form_validation->set_rules($config1);
    }
    $config_validation = array(
        array(
            'field' => 'disc_title',
            'label' => 'title',
            'rules' => 'required|max_length[100]|min_length[5]', 
            'errors' => array('required'=>'You have not provided %s for discussion category.')
            ),
        array( 
            'field' => 'disc_body',
            'label' => 'description',
            'rules' => 'required|max_length[500]|min_length[5]', 
            'errors' => array('required'=>'You have not provided %s for discussion category.')
            )
    );
    //set validation rules
    $this->form_validation->set_rules($config_validation);
    if ($this->form_validation->run()===FALSE) {
        //send errors to the modal
        echo validation_errors();

    }else {
       
        //check if we on add or edit mode
        if($discID == ''){
            $insertStatus = $this->discussion->addCategory(html_escape($this->input->post()));
            //send feedback based on insert
             if ($insertStatus) {
                echo "Added";
            }else {
                echo "NO";
            }
        }else {
            $updateStatus = $this->discussion->updateCategory(html_escape($this->input->post()));
            //send feedback based on update
             if ($updateStatus) {
                echo "updated";
            }else {
                echo "NO";
            }
        }

        //send the status to jquery for message display to the user.
    }
    
}//end addDiscCategory

/**
 * [deleteCategory method will be used to delete category from the table
 * @return [string] [this will be used to indicate to the user if the delete worked or not]
 */
public function deleteCategory()
{
    $config_validation = array(

        array(
            'field' => 'discID',
            'label' => 'Discussion category ID',
            'rules'=>array('required',array('category_exist',array($this->discussion,'dsicuss_category_exist'))),
            'errors' => array('category_exist'=>'No such dsicuss category exist in the system.')
        ),
    );
    //set validation rules
    $this->form_validation->set_rules($config_validation);
    if ($this->form_validation->run()===FALSE) {
        //send errors to the modal
        echo validation_errors();

    }else {

        $discID = !empty(html_escape($this->input->POST('discID'))) ? html_escape($this->input->POST('discID')) :0;
        if ($discID > 0) {
            //call the method which will delete discussion category
            $deleteStatus = $this->discussion->deleteCategory($discID);
            //send feedback based on delete
             if ($deleteStatus) {
                echo "YES";
            }else {
                echo "NO";
            }
        }else {
                echo "NO";
        }
    }

}//end deleteCategory


/**
 * [viewDiscussions description]
 * @param  [array] $categories [array holding all discussion categories]
 * @param  [string] $subName    [subject name to be used for id creation on the panel group]
 * @return [string]             [the view to be send back to the caller]
 */
public function viewDiscussions($categories,$subName)
{       
    $data['categories'] = $categories;
    $data['subName'] = $subName;
    $data['topics'] = $this->discussion->getTopicsByDiscGroup();
    //$data['dgID'] = $dgID;
    $data['topic_comments'] = $this->discussion->getTopicCommentsByTopic();
    $disc_view = $this->load->view('academy_discussion/viewDiscussions',$data,TRUE);
    return $disc_view;

}//end viewCategories

/**
 * [getTopicsByDiscussion will trigger after the new topic has been added to the list
 * of discussion category
 * @return [json text] return results will be send back to AJAX
 */
public function getTopicsByDiscussion()
{
    //get categoryID to be editted
    $catID = html_escape($this->input->POST('discID')) ? html_escape($this->input->POST('discID')) : 0;
    //search array to hold the category ID to search for
    $search['catID'] = $catID;
    //get topics
    $topics=$this->discussion->getTopicsByDiscGroup($search);
    //call method to display the topics
    $disc = $this->displayTopics($topics,$catID);
    //return request to AJAX
    echo json_encode($disc);
}
/**
 * displayTopics method prepare the topic and return them to the caller method
 * this topics will be sift and ensure they are all under the correct discussion groups
 * @param  [array] $topics [this array has all the topics from the databse]
 * @return [string]         [it has the viiew as needed]
 */
public function displayTopics($topics,$catID)
 {  
    $data['topics'] = $topics;
    $data['dgID'] = $catID;
    //get all topic comments and assign them to data array to send to the view
    $data['topic_comments'] = $this->discussion->getTopicCommentsByTopic();
    //get the view and assign it to the variable
    $disc_view = $this->load->view('academy_discussion/displayTopics',$data,TRUE);
    //return the view back to the caller
    return $disc_view;

 }//end displayTopics 


public function getCategoryBySubject($from_index = 0, array $user_subjects = array())
{
    //check if the request is send frm the index or ajax
    if ($from_index == 1) {
        //class level subjects id of the first subject on the list of many
        $clsID = $user_subjects['clsID'];
        //subject name of the first subject on the list of many
        $subName = $user_subjects['subjectName'];

    }else{
        //subject of tab clicked
        $subName = !empty(html_escape($this->input->POST('subjectName'))) ? html_escape($this->input->POST('subjectName')) : '';
        //cls of tab clicked
        $clsID = !empty(html_escape($this->input->POST('cls'))) ? html_escape($this->input->POST('cls')) : 0;
        //get categoryID to be editted
        $catID = !empty(html_escape($this->input->POST('catID'))) ? html_escape($this->input->POST('catID')) : 0;
    }

    //use this for searching subject to get students
    $search['subject'] = $subName;
    //use this for searching subject to get students
    $search['clsID'] = $clsID;
    //check who is logged in
    if (strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false) {
        //the user_id of the teacher who is logged in
        $search['teacher'] = $_SESSION['userID'];
        //
    }elseif (identify_user_role($_SESSION['userID'])== 'learner') {
        //the user_id of the learner who is logged in
        $search['learner'] = $_SESSION['userID'];
    }
    //get categories/discussion group 
    $categories=$this->discussion->getCategoryBySubject($search);
    //check if subject was sent 
    if ($clsID != 0) {
        //get for 
        $catList = $this->viewDiscussions($categories,$subName);
        if ($from_index == 1) {
            return $catList;
        }
        //return results to caller
        echo json_encode($catList);
    //if not subject, then they want to edit discussion
    }elseif ($catID!=0) {
        //
        $search['catID'] = $catID;
        //get contents of discussion to be editted
        $editCategory=$this->discussion->getCategoryBySubject($search);
        //return results to requester
        echo json_encode($editCategory);
    }
}

/**
 * addTopic method will be used to add new discussion topic
 * this method will also be used when editing the details of the topic
 */
public function addTopic()
{   

    //validate this id if we on update mode
    if (html_escape($this->input->POST('topicID')) != '') {  
        $config1 = array(          
            array(
                'field' => 'topicID',
                'label' => 'Topic ID',
                'rules'=>array('required',array('topic_exist',array($this->discussion,'topic_exist'))),
                'errors' => array('topic_exist'=>'No such topic exist in the system.')
            ), 
        );
        $this->form_validation->set_rules($config1);
    }
    //set the validation rules for the topic input fields
    $config_validation = array(
         array(
            'field' => 'topic_title',
            'label' => 'title',
            'rules' => 'required|max_length[100]|min_length[5]', 
            'errors' => array('required'=>'You have not provided %s for the topic.')
            ),
         array( 
            'field' => 'topic_body',
            'label' => 'description',
            'rules' => 'required|max_length[500]|min_length[5]', 
            'errors' => array('required'=>'You have not provided %s for the topic.')
            )
    );
    //set validation rules
    $this->form_validation->set_rules($config_validation);
    if ($this->form_validation->run()===FALSE) {
        //send errors to the modal
        echo validation_errors();

    }else {
       
        //check if we on add or edit mode
        if(html_escape($this->input->post('topic_ID'))==''){
            $insertStatus = $this->discussion->addTopic(html_escape($this->input->post()));
            //send feedback based on insert
             if ($insertStatus) {
                echo "Added";
            }else {
                echo "NO";
            }
        }else {
            $updateStatus = $this->discussion->updateTopic(html_escape($this->input->post()));
            //send feedback based on update
             if ($updateStatus) {
                echo "updated";
            }else {
                echo "NO";
            }
        }
        //send the status to jquery for message display to the user.
    }
}
/**
 * [deleteTopic will be used to delete topic as specified by the topic ID
 * @return [type] [description]
 */
public function deleteTopic()
{   
    $config_validation = array(

        array(
            'field' => 'topicID',
            'label' => 'Topic ID',
            'rules'=>array('required',array('topic_exist',array($this->discussion,'topic_exist'))),
            'errors' => array('topic_exist'=>'No such topic exist in the system.')
        ),
    );
    //set validation rules
    $this->form_validation->set_rules($config_validation);
    if ($this->form_validation->run()===FALSE) {
        //send errors to the modal
        echo validation_errors();
    }else {
        //get the topic ID of the topic  to be deleted
        $topicID = !empty(html_escape($this->input->POST('topicID'))) ? html_escape($this->input->POST('topicID')) : 0;
        if ($topicID > 0) {
            //call method to delete topic
            $deleteStatus = $this->discussion->deleteTopic($topicID);
            //send feedback based on delete
             if ($deleteStatus) {
                echo "YES";
            }else {
                echo "NO";
            }
        }else {
            echo "NO";
        }
        
    }

}//end deleteTopic

/**
 * getTopic method will get list of topics
 * @return [type] [description]
 */
public function getTopic()
{
    //the topic id of clicked topic
    $topicID = !empty(html_escape($this->input->POST('topicID'))) ? html_escape($this->input->POST('topicID')) : 0;
    //check if topic has useful value
    if ($topicID!=0) {
        $search['topic'] = $topicID;
        //get topic based on the iD of topic clicked
        $topics=$this->discussion->getTopicsByDiscGroup($search);
        //return results to JSON
        echo json_encode($topics); 
    } 
}
/**
 * addComment method will be used when the user post a comment in the discussion
 */
public function addComment()
{
     $config_validation = array(
         array(
            'field' => 'comment_topicID',
            'label' => 'Topic ID',
            'rules'=>array('required',array('topic_exist',array($this->discussion,'topic_exist'))),
            'errors' => array('topic_exist'=>'No such topic exist in the system.')
        ),
         array( 
            'field' => 'comments',
            'label' => 'comment',
            'rules' => 'required|max_length[500]|min_length[5]', 
            'errors' => array('required'=>'Please say something, even HELLO will do.')
        )
    );
    //set validation rules
    $this->form_validation->set_rules($config_validation);
    if ($this->form_validation->run()===FALSE) {
        //send errors to the modal
        echo validation_errors();

    }else {
       
        //check if we on add or edit mode
        //if(html_escape($this->input->post('comment_topicID'))!=''){
            $insertStatus = $this->discussion->addComment(html_escape($this->input->post()));
            //send feedback based on insert
            if ($insertStatus) {
                echo "Added";
            }else {
                echo "NO";
            }
        /*}else {
            $updateStatus = $this->discussion->updateComment(html_escape($this->input->post()));
            //send feedback based on update
            if ($updateStatus) {
                echo "updated";
            }else {
                echo "NO";
            }
        }*/
        //send the status to jquery for message display to the user.
    }
}

/**
 * [viewTopicONLY if there are no comments to discplay
 * display this topic only so that the user can comment
 * @param  [type] $topics [description]
 * @return [string]         build html view
 */
public function viewTopicWithComments($topics,$topicID)
{
    $data['topicID'] = $topicID;
    $data['topics'] = $topics; 
    $search['topic']= $topicID;
    //get comments  
    $data['comments'] = $this->discussion->getTopicCommentsByTopic($search);
    $topic_view = $this->load->view('academy_discussion/viewTopicWithComments',$data,TRUE);
    return $topic_view;

}//end viewTopicWithComments

/**
 * getTopicComments method will be responsible for retrieving the comments based on the topic the user selected
 */
public function getTopicComments()
{
    //the topic id of clicked topic
    $topicID = !empty(html_escape($this->input->POST('topicID'))) ? html_escape($this->input->POST('topicID')) : 0;
    //ID of the topic to be deleted
    $delTopicID = !empty(html_escape($this->input->POST('delTopicID'))) ? html_escape($this->input->POST('delTopicID')) : 0;
    //check if topic has useful value
    if ($topicID!=0) {
        //search based on the topic clicked
        $search['topic']= $topicID;
        //get topic based on the iD of topic clicked
        $topics=$this->discussion->getTopicsByDiscGroup($search);
        //call this to build the view
        $tc = $this->viewTopicWithComments($topics,$topicID);
        //set search variable to false when the search is complete
        $search['topic']= FALSE;
        //return results to AJAX
        echo json_encode($tc); 
    //check if the user is deleting 
    }elseif ($delTopicID != 0) {
         //search based on the topic clicked
       $search['delTopicID']= $delTopicID;
       //get topic based on the iD of topic clicked
       $topics=$this->discussion->getTopicCommentsByTopic($search);
       //check if the search return anything than NULL
       if (count($topics) > 0) {
           //return results to AJAX
            echo "NO";
       }
       
    }
}//end getTopicComments

/**==============================================================================================
 ** //////////////////////////////Progress section /////////////////////////////
 **==============================================================================================
 **/
/**
 * displayProgress method is responsible for building the view for progress
 * method accepts array of learners, option selected [reason], class level subject id, and specific assessment
 * @param  array   $learner_list   [list of learners in this group]
 * @param  integer $reason         [selected option, being VIEW]
 * @param  integer $clsID          [class level subjects id especially for teachers subjects]
 * @param  integer $new_assessment [specific assessment selected]
 * @return [string]                [output of view after build]
 */
public function displayProgress($learner_list = array(),$reason = 0,$clsID=0,$new_assessment=0)
{ 
    $data['learner_list'] = $learner_list;
    $data['new_assessment'] = $new_assessment;
    $data['reason'] = $reason;
    $search['clsID'] = $clsID;
    $data['learner_progress'] = $this->progress->getProgress($search);
    $progress_view = $this->load->view('academy_progress/displayProgress',$data,TRUE);
    return $progress_view;
}//end displayProgress

/**
 * capture_marks method is responsible for building the view so that teacher can be able to update marks
 * the method is also used for normal view of specific assessments
 * @param  [integer] $reason         [whether you want to view[1], update[3] or add-2]
 * @param  [integer] $new_assessment [the specific assessment being needed for VIEW, or UPDATE]
 * @param  [integer] $clsID          [it has the class level subjects id]
 * @return [string]                 [description]
 */
public function capture_marks($reason=0, $new_assessment=0, $clsID=0){
    //load search array with cls and new assessment number
    $search['clsID'] = $clsID;
    $search['assessment'] = $new_assessment;
    $data['reason'] = $reason;
    $data['new_assessment'] = $new_assessment;
    //get learners from progress table based on these two variables
    $data['learner_progress'] = $this->progress->getProgress($search);
    $progress_view = $this->load->view('academy_progress/capture_marks',$data,TRUE);
    return $progress_view;
}//end of capture_marks

/**
 * getLearnersProgress method is used to get progress of all learners
 * method is used for both learner(VIEW) and teacher (view, update of progress)
 * @param  integer $from_index    [1-tell that the request comes from the index (on pageLoad)]
 * @param  array   $user_subjects [has the class and subject details for the progress]
 */
public function getLearnersProgress($from_index = 0, array $user_subjects = array())
{
    //check if the request is send frm the index or ajax
    if ($from_index == 1) {
        //class level subject id of the first subject on the list
        $clsID = $user_subjects['clsID'];
        //the class group level that learners belong and teacher assigned to
        $cglID = $user_subjects['cglID'];
        //the subject name of the first subject on the list of many
        $subName = $user_subjects['subjectName'];
    }else{
        //subject name of clicked tab
        $subName = !empty(html_escape($this->input->POST('subjectName'))) ? html_escape($this->input->POST('subjectName')) : '';
        //classLevelSubjectsID of clicked tab
        $clsID = !empty(html_escape($this->input->POST('clsID'))) ? html_escape($this->input->POST('clsID')) : 0;
        //get the class group level subject
        $cglID = !empty(html_escape($this->input->POST('cglID'))) ? html_escape($this->input->POST('cglID')) : 0;
    }
     
    //get the option that the user selected
    $reason = !empty(html_escape($this->input->POST('reason'))) ? html_escape($this->input->POST('reason')) : 0;   
    //get the assessment type
    $new_assessment = !empty(html_escape($this->input->POST('whichAssess'))) ? html_escape($this->input->POST('whichAssess')) : 0;
    //check who is logged in
    if (strpos(identify_user_role($_SESSION['userID']), 'teacher') !== false) {
        //the user_id of the teacher who is logged in
        $search['cglID'] = $cglID;

    }elseif (identify_user_role($_SESSION['userID']) == 'learner') {
        //the user_id of the learner who is logged in
        $search['learner'] = $_SESSION['userID'];
        $search['clsID'] = $clsID;
     }

    //send a data request to retrieve LIST of learners for progress
    $learner_list = $this->learners->getLearners($search);
    //if the request comes from the top tab only
    if ($reason == 1 OR $reason == 0) {
        //this method willbe used to display assessment specific marks
        if($new_assessment > 0){
            //display the list of students for progress
            $display_results = $this->capture_marks($reason, $new_assessment, $clsID);
        }else{
            //display the list of students for progress
            $display_results = $this->displayProgress($learner_list,$reason,$clsID);
        }
        
        //check if request is from index
        if ($from_index == 1) {
            return $display_results;
        }
        //return request to script        
        echo json_encode($display_results);
    //if option selected is ADD
    }elseif ($reason == 2) {
        //create a new list of learners 
        $insertStatus = $this->progress->insertLearners($learner_list,$new_assessment,$clsID);
        if($insertStatus){
            //display the list of students for progress
            $learnerList = $this->capture_marks($reason, $new_assessment, $clsID);
            //return request to script        
            echo json_encode($learnerList);
        }
    //if the option selected is UPDATE  
    }elseif ($reason == 3 ) {
        //display the list of students for progress
        $learnerList = $this->capture_marks($reason, $new_assessment, $clsID);
        echo json_encode($learnerList); 
    }

}//end getLearnersForProgress

/**
 * updateProgress method is used to get all the marks from the input boxes 
 * method accepts array with learnerID, and the marks as entered
 * @return [integer] to keep count of errors during the process of saving marks into db
 */
public function updateProgress()
{
    //get input values from the form [from script]
    $dataMarks = html_escape($this->input->POST('form_data'));
    //loop thru the array 
    for($i = 0; $i < count($dataMarks); $i++){
       for($ii = 0; $ii < count($dataMarks[0]); $ii++){
            //load associated values to marks array
            $marks[$ii][] = $dataMarks[$i][$ii];
        }//end inner loop
    }//end outer loop

    //declare array to hold transaction status
    $updateStatus=[];
    $error=0;
    //use loop to send data to UPDATE function
    for ($i = 0; $i < count($marks); $i++) {
        //validate each mark before update
        $status = $this->progress->validateMarks($marks[$i]);
        if ($status) {
            $updateStatus[$i] = $this->progress->updateProgress($marks[$i]);
            if ($updateStatus[$i]==FALSE) {
                $error++;
            }
        }else{
            $error++;
        }
    }
    //send this to AJAX and print the relevant message to the user
    //based on the number of transactions 
    echo $error;
   
}//end of updateProgress

/**
 * [setup_new_assessment method will accept the values of the new assessment being created before the marks can be entered
 * @return [type] [description]
 */
public function setupNewAssessment(){
    //var_dump($this->input->POST());
    $rules = array(
        array(
            'field' => 'whichAssess',
            'label' => 'Assessment Type',
            'rules'=>array('required','numeric',array('assessment_type_exist',array($this->progress,'assessment_type_exist'))),
            'errors' => array('required'=>'%s is a required field.',
                                'assessment_type_exist'=>'No such assessment type exist in the system',
                                'numeric'=>'Assessment type must be numeric',
                            )
            ),
        array(
            'field' => 'assess_number',
            'label' => 'Assessment number',
            'rules' => 'required|numeric', 
            'errors' => array('required'=>'Please enter %s.')
            ),
        array(
            'field' => 'assess_weight',
            'label' => 'Assessment weight',
            'rules' => 'required|numeric', 
            'errors' => array('required'=>'Please enter how much it will count.')
            ),
        array(
            'field' => 'clsID_assess_number',
            'label' => 'class level subject ID',
            'rules'=>array('required','numeric',array('cls_exist',array($this->level_group,'cls_exist'))),
            'errors' => array('required'=>'%s is a required field.',
                                'cls_exist'=>'No such class level subjects exist in the system',
                                'numeric'=>'Class level subjects must be numeric',
                            )
            ),
    );
    $this->form_validation->set_rules($rules);
    if ($this->form_validation->run()===false){
        echo validation_errors();
    }else{
        $assess_data = html_escape($this->input->POST());
        $assessID = $this->progress->new_assess_details($assess_data);
        if($assessID > 0){
            echo ($assessID);
        }
    }
}

/**
 * getAssessments_by_cls method will get the assessments as added for the specific subject
 * the method will accept the clsid of the subject as the input 
 * then return json_encoded string
 */
public function getAssessments_by_cls()
    {
      //get the value of the class level subject to be used for searching assessments
        $clsID = !empty(html_escape($this->input->post('clsID'))) ? html_escape($this->input->post('clsID')) : 0;
        //assign class level subjects into search array
        $search['clsID'] = $clsID;
        $data['clsID'] = $clsID;
        //use the search array to look specifi values
        $data['assessment'] = $this->progress->getAssessment($search);
        $select = $this->load->view('academy_progress/getAssessments_by_cls',$data,TRUE);
        //return to the caller  
        echo json_encode($select);

    }

/**
 * ///////////////////////////////////////////////////////////////////////////////////////////////
    ********************************START LEARNER GUARDIAN SECTION*******************************
 * ///////////////////////////////////////////////////////////////////////////////////////////////
 */

/**
 * [getGuardLearnSubject get all the subjects of the learner 
 * assignment/study material per subject
 * this will only be called when the guardian is logged in 
 * @return [type] [description]
 */
public function getGuardLearnSubject($from_index=0, $guard_child= array())
{   
    $view_mode = 0;
    //check if the request is send frm the index or ajax
    if ($from_index == 1) {
        //get the learner ID to use for finding subjects
        $luID = $guard_child['lUserID'];
        //get the learner name to use as part of the heading
        $lFName = $guard_child['fname'];
    //if request comes from script
    }else{
        //get the learner ID to use for finding subjects
        $luID = !empty(html_escape($this->input->POST('childID'))) ? html_escape($this->input->POST('childID')) : 0;
        //id to tell if the request comes from study material [1=yes]
        $onStudy = !empty(html_escape($this->input->POST('study'))) ? html_escape($this->input->POST('study')) : 0;
        //id to tell if the request comes from assignments [1=yes]
        $onAssign = !empty(html_escape($this->input->POST('assign'))) ? html_escape($this->input->POST('assign')) : 0;
        //id to tell if the request comes from attendance [1=yes]
        $onAttend = !empty(html_escape($this->input->POST('attend'))) ? html_escape($this->input->POST('attend')) : 0;
        //id to tell if the request comes from progress [1=yes]
        $onProgress = !empty(html_escape($this->input->POST('progress'))) ? html_escape($this->input->POST('progress')) : 0;
        //get the learner name to use as part of the heading
        $lFName = !empty(html_escape($this->input->POST('fname'))) ? html_escape($this->input->POST('fname')) : '';
    }
    //assign the learner to search array
    $search['learner'] = $luID;
    //get learner subjects
    $lsubjects = $this->subject->getSubject($search);
    //check if the request comes from index
    //then send the results back as such 
    if ($from_index == 1) {
        //array to hold all the results requested from index as ONE
        $back_to_index = [
            'study' => $this->viewLearnSubjStudy($lsubjects,$lFName,$luID),
            'assign' => $this->viewLearnSubjAssign($lsubjects,$lFName,$luID),
            'attend' => $this->viewLearnSubjAttend($lsubjects,$lFName,$luID),
            'progress' => $this->viewLearnSubjProgress($lsubjects,$lFName,$luID)
        ];
        //return to index page
        return $back_to_index;
    }
    //check where request comes from 
    if ($onStudy==1) {
        //method to assemble the learner subject study material view
        $learnSubjStudy = $this->viewLearnSubjStudy($lsubjects,$lFName,$luID);
        //return to caller
        echo json_encode($learnSubjStudy);
        //the user_id of the learner who is logged in
    }elseif ($onAssign==1) {
        //method to assemble the learner subject assignments view
        $learnSubjAssign = $this->viewLearnSubjAssign($lsubjects,$lFName,$luID);
        //return to caller
        echo json_encode($learnSubjAssign);
    }elseif ($onAttend==1) {
        //method to assemble the learner subject attendance view
        $learnSubjAttend = $this->viewLearnSubjAttend($lsubjects,$lFName,$luID);
        //return to caller
        echo json_encode($learnSubjAttend);
    }elseif ($onProgress==1) {
        //method to assemble the learner subject progress view
        $learnSubjProgress = $this->viewLearnSubjProgress($lsubjects,$lFName,$luID);
        //return to caller
        echo json_encode($learnSubjProgress);
    }

}//end getGuardLearnAssign


/**
 * [viewLearnSubjStudy create a view to be returned to AJAX call
 * @param  [array] $lsubjects has all the subjects the learner is doing
 * @return [string] view [this has the whole view]
 */
public function viewLearnSubjStudy($lsubjects,$lFName,$luID)
{
    //method to assemble the learner subject attacndance view
    $data['lFName'] = $lFName;
    $data['lsubjects'] = $lsubjects;
    $search['learner'] = $luID;
    //get this guardian child assignments
    $data['all_learner_material'] = $this->material->getStudyMaterial($search);
    //assign the view to the variable
    $view = $this->load->view('academy_guardian/child_study',$data,TRUE);

    return $view;

}//viewLearnSubjStudy


/**
 * [viewLearnSubjAssign create a view to be returned to AJAX call
 * @param  [array] $lsubjects has all the subjects the learner is doing
 * @return [string] view [this has the whole view]
 */
public function viewLearnSubjAssign($lsubjects,$lFName,$luID)
{
    
    //method to assemble the learner subject attacndance view
    $data['lFName'] = $lFName;
    $data['lsubjects'] = $lsubjects;
    $search['learner'] = $luID;
    //get this guardian child assignments
    $data['all_learner_assignments'] = $this->assignment->getAssignments($search);
    //get all assignments submissions of the learners
    $data['learnerSubmission'] = $this->assignment->getAssignSubmission();
    //assign the view to the variable
    $view = $this->load->view('academy_guardian/child_assignment',$data,TRUE);

    return $view;

}//createLearnSubjAssign


/**
 * [viewLearnSubjAttend create a view to be returned to AJAX call
 * @param  [array] $lsubjects has all the subjects the learner is doing
 * @return [string] view [this has the whole view]
 */
public function viewLearnSubjAttend($lsubjects,$lFName,$luID)
{
    //method to assemble the learner subject attacndance view
    $data['lFName'] = $lFName;
    $data['lsubjects'] = $lsubjects;
    $search['learner'] = $lsubjects[0]->learnerID;

    $data['learnerAttend']= $this->attendance->getAttendance($search);

    $view = $this->load->view('academy_guardian/child_attendance',$data,TRUE);

    return $view;

}// end createLearnSubjAssign

/**
 * viewLearnSubjProgress method is responsible for building the view for the guardian child/ren progress
 * @param  [array] $lsubjects [subjects learner os registered for]
 * @param  [string] $lFName    [guardian child name]
 * @param  [int] $luID      [guardian child learnerID]
 * @return [string] view [this has the whole view]
 */
public function viewLearnSubjProgress($lsubjects,$lFName,$luID)
{
    //method to assemble the learner subject progress view
    $data['lFName'] = $lFName;
    $data['lsubjects'] = $lsubjects;
    $search['learner'] = $lsubjects[0]->learnerID;

    $data['learner_progress']= $this->progress->getProgress($search);

    $view = $this->load->view('academy_guardian/child_progress',$data,TRUE);

    return $view;
}
/**
 * ////////////////////////////////////////////////////////////////////////////////////////////
 *         ****************************Timetable Sections*****************************
 * ////////////////////////////////////////////////////////////////////////////////////////////
 */

    
    /**
     * remove_timetable_subjects method will be used to remove the subject added on the timetable
     */
    public function remove_timetable_subjects()
    {

        $del_data = html_escape($this->input->POST());
        //get timetable data based on the user logged in
        $statusDelete = $this->timetable->remove_subjects($del_data);
        if ($statusDelete) {
            echo 'DONE';
        }
    }
    /**
     * add_timetable_subjects method will validate the selected subject and call the method that will add it to database
     */
    public function add_timetable_subjects()
    {
        $subject = html_escape($this->input->POST());
        //this will be used when the user wants to add subjects using mobile
        if ($subject['timeSelector'] != 0 && $subject['daySelector'] != 0) {
            $subject['what_timeid'] = $subject['timeSelector'];
            $subject['what_wdid'] = $subject['daySelector'];
        }
        if (!empty($subject)) {
            $statusInsert = $this->timetable->addClassSchedule($subject); 
            if ($statusInsert) {
                echo 'DONE';
            }
        }
        
        
    }
    /**
     * get_time_table method will get data needed for the time table for logged in user
     * Then echo the view back for output
     * @return string description
     */
    public function get_time_table()
    {
        //check if is teacher logged in
        if (strpos(identify_user_role($_SESSION['userID']), 'teacher')!==false) {
            //the user_id of the teacher who is logged in
            $search['teacher'] = $_SESSION['userID'];
            //check if is learner logged in
        }elseif (identify_user_role($_SESSION['userID'])== 'learner') {
            //the user_id of the learner who is logged in
            $search['learner'] = $_SESSION['userID'];
        }
        //get days of the week
        $data['weekDays'] = $this->timetable->getWeekDays();
        //get normal schoold time
        $data['timeDays'] = $this->timetable->getWeektime();
        //get timetable data based on the user logged in
        $data['active_tt_schedule'] = $this->timetable->getSchedule($search);
        //load the view into the variable
        $view = $this->load->view('academy_timetable/tt',$data,TRUE);
        //echo the view back to script
        echo $view;

    }

}//end academy contoller