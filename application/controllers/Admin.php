<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * summary
 */
class Admin extends CI_Controller{
	public function __construct(){
    parent::__construct();
    //this model is used for anything password related
    //$this->load->model('Access_model','access');
    //this model is used for anything teacher related
    $this->load->model('Teacher_model','teachers');
    //this model is used for user related data calls
    $this->load->model('Users_model');
    //this model is used for anything subject related
    $this->load->model('Subject_model','subjects');
    //this model will have guardian related data calls
    $this->load->model('Guardian_model','guardians');
    //for groups and levels related data
    $this->load->model('Level_Group_model','level_group');
    //for learner related data
    $this->load->model('Learner_model','learners');
    $this->load->model('Admin_model','admin');
    $this->load->model('Login_model','login');
    $this->load->model('Files_model','files');
    $this->load->model('Profile_model','profile');
    $this->load->model('Message_model','message_model');
    $this->load->model('Announce_model','announce_model');
    $this->load->helper(array('form','text','url','date'));
    $this->load->library(array('form_validation','email'));
   //check user credentials
        $is_logged_in = ($this->session->userdata('is_logged_in')) ? $this->session->userdata('is_logged_in') : FALSE;
        //if the user is not logged in
        if (!($is_logged_in)) { 
        //check if cookie exist for login
            if (!$this->login->checkLoginWithCookie()) { 
            //otherwise redirect to login page
                redirect('Guests#login','refresh'); 
            }//end cookie
        }//end logged_in

        //disallow any user who is an admin
        if (identify_user_role($_SESSION['userID']) !=='admin' && (strpos(identify_user_role($_SESSION['userID']), 'admin')===false)) {
            redirect('App/');
        }

    }
    public function index()
    {
    // loads admin main page
         $data['pageToLoad'] = 'admin/administration';
        //active on the nav
        $data['pageActive'] = 'administration';
        $search['profile']= $_SESSION['userID'];
        $data['userProfile'] = $this->profile->getProfile($search);
         //search the user
        $search['search']=!empty($this->input->get('search') ) ? $this->input->get('search') : '';
        $search['id_user']=!empty($this->input->get('id_user')) ? $this->input->get('id_user') : 0;
            //}
        $search['page'] = !empty($this->input->get('page')) ? $this->input->get('page') : 0;
        $data['search']=$search;
        $data['db']=$this->Users_model->getUsers($search);
        $data['countUsers'] = $this->Users_model->countUsers($search);
            //this is to enable rewrite queries
        $config['enable_query_string'] = TRUE;
        //show the actual page number, ?page =someInt
        $config['page_query_string'] = TRUE;
        //url that use the pagination
        $config['base_url'] = base_url('admin/users?search='.$search['search']);
        //number of results to be on the pagination
        $config['total_rows'] = $data['countUsers'];
        $this->load->library('pagination');
        //initialize the pagination with our config
        $this->pagination->initialize($config);
        $data['search_pagination']=$this->pagination->create_links();

        //control variable to tell from which page getTodolist was loaded
        $index=1;
        //take returned list to the view
        $data['todoList'] = $this->getTodolist($index);
        
        $this->load->view('ini',$data);
    } 

    /**********************
     public function learners()
    {
        // add learners
        $data['pageToLoad'] = 'admin/LearnerList';
        $data['pageActive'] = 'LearnerList';
        $data['learners']=$this->learners->getLearners();
        $this->load->view('ini',$data);
    }
     public function guardians()
    {//list of all the guardians
        $data['pageToLoad']='admin/guardianList';
        $data['pageActive']='guardianList';
        $data['userProfile']=$this->profile->getProfile($_SESSION['userID']);
        $data['db']=$this->guardians->getGuardian();
        $data['myChildren']=$this->guardians->getGuardChild();
        //var_dump($data['myChildren']);
        $this->load->view('ini',$data);
    }
 public function teachers()
    {//bring the list of all the teachers
        $data['pageToLoad'] = 'admin/teacherList'; 
        $data['pageActive'] = 'teacherList';
        $data['userProfile']=$this->profile->getProfile($_SESSION['userID']);
        //var_dump($data['userProfile']);
        $data['teachers']=$this->teachers->getTeacher();
        //$data['teacherSubjects']=$this->subjects->getSubject();
        $data['levels']=$this->level_group->getclassLevel();
        $data['classTeacher']=$this->teachers->getClassTeacherClass();
        $data['schoolSubjects']=$this->subjects->getschoolSubjects();
        //var_dump($data['classTeacher']);
        $this->load->view('ini',$data); 
    }
    /***********************/
/***Send Admin Message****/
public function sendAdminMessage()
{
       $config_validation = array(
         array( 
            'field' => 'asubj',
            'label' => 'subject',
            'rules' => 'required|min_length[5]', 
            'errors' => array('required'=>'Subject please.')
            ),
          array( 
            'field' => 'amsg',
            'label' => 'message',
            'rules' => 'required|min_length[5]', 
            'errors' => array('required'=>'Message cannot be empty, please type something.')
            )
    );
    //set validation rules
    $this->form_validation->set_rules($config_validation);
    if ($this->form_validation->run()===FALSE) {
        //send errors to the modal
        echo validation_errors();

    }else {
       
        //check if we on add message
        if($this->input->post('mess_ID')==''){
          //assign message variables into db friendly variables
            $msg = array(
                'title'=>html_escape($this->input->post('asubj')),
                'body'=>html_escape($this->input->post('amsg')),
                'to_user_id'=>html_escape($this->input->post('userAdminID'))
              );
            $insertStatus = $this->message_model->sendMsg($msg);

            //send feedback based on insert
             if ($insertStatus) {
                echo "Added";
            }else {
                echo "NO";
            }
        }
      }
    }

/***End of send Admin Message****/
       
    public function users()
    {
    //loads the manageUsers page
         $data['pageToLoad'] = 'admin/manageUsers';
        //active on the nav
        $data['pageActive'] = 'manageUsers';
        $search['profile']= $_SESSION['userID'];
        $data['userProfile']=$this->profile->getProfile($search);
        if($this->input->post('id_user') !=0 && is_numeric(html_escape($this->input->post('id_user')) )){
            $statusRemoved = $this->Users_model->deleteUser(html_escape($this->input->post('id_user')) );
             if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'no';
           }
        }//delete Guardian/Parent
        if($this->input->post('id_userGuardian') !=0 && is_numeric(html_escape($this->input->post('id_userGuardian')))){
            $statusRemoved = $this->guardians->deleteGuardian(html_escape($this->input->post('id_userGuardian')));
             if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'no';
           }
        }// delete teacher
         if($this->input->post('id_userTeacher') !=0 && is_numeric(html_escape($this->input->post('id_userTeacher')))){
            $statusRemoved = $this->teachers->deleteTeacher(html_escape($this->input->post('id_userTeacher')));
            if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'no';
           }
        }
        // delete Learner
         if($this->input->post('learnid') !=0 && is_numeric(html_escape($this->input->post('learnid')))){
            $statusRemoved = $this->learners->deleteLearner(html_escape($this->input->post('learnid')));
            if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'no';
           }
        }
        // delete Subject
         if($this->input->post('subid') !=0 && is_numeric(html_escape($this->input->post('subid')))){
            $statusRemoved = $this->subjects->deleteSubject(html_escape($this->input->post('subid')));  
            //redirect('admin/manageSubjects');
         if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'no';
           }
        }
         // delete Quarter
         if($this->input->post('quarterId') !=0 && is_numeric(html_escape($this->input->post('quarterId')))){
            $statusRemoved = $this->admin->deleteQuarter(html_escape($this->input->post('quarterId')));         
           if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'no';
           }
        }
        // delete Level
         if($this->input->post('levID') !=0 && is_numeric(html_escape($this->input->post('levID')))){
            $statusRemoved = $this->level_group->deleteLevel(html_escape($this->input->post('levID')));         
           if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'no';
           }
        }

        // delete Assessment Type
         if($this->input->post('assessID') !=0 && is_numeric(html_escape($this->input->post('assessID')))){
            $statusRemoved = $this->admin->deleteAssessmentType(html_escape($this->input->post('assessID')));         
           if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'no';
           }
        }
          // delete Class Group
         if($this->input->post('groupID') !=0 && is_numeric(html_escape($this->input->post('groupID')))){
            $statusRemoved = $this->level_group->deleteClassGroup(html_escape($this->input->post('groupID')));         
           if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'no';
           }
        }
       
         //display suucess/ error messages  
        if(null != $this->input->get('statusEdit')){
            $data['statusEdit'] = $this->input->get('statusEdit');
            //var_dump($data['statusEdit']);
        }//display suucess/ error messages

        if(null != $this->input->get('statusInsert')){
            $data['statusInsert'] = $this->input->get('statusInsert');
        }
       //search the user
        $search['user']=!empty($this->input->get('search')) ? $this->input->get('search') : '';
        $search['id_user']=!empty($this->input->get('id_user')) ? $this->input->get('id_user') : 0;
            //}
        $search['page'] = !empty($this->input->get('page')) ? $this->input->get('page') : 0;
        $data['search']=$search;
        $data['db']=$this->Users_model->getUsers($search);
        $data['countUsers'] = $this->Users_model->countUsers($search);
            //this is to enable rewrite queries
        $config['enable_query_string'] = TRUE;
        //show the actual page number, ?page =someInt
        $config['page_query_string'] = TRUE;
        //url that use the pagination
        $config['base_url'] = base_url('admin/users?search='.$search['user']);
        //number of results to be on the pagination
        $config['total_rows'] = $data['countUsers'];
        $this->load->library('pagination');
        //initialize the pagination with our config
        $this->pagination->initialize($config);
        $data['search_pagination']=$this->pagination->create_links();
         $this->load->view('ini',$data);
    }
    
    public function addClassGroups()
    {
       $data['pageToLoad']='admin/class_group_level';
        $data['pageActive']='class_group_level';
        $data['classGroup_level']=$this->level_group->getclassGroupLevel();
        $data['levels']=$this->level_group->getLevels();
        $data['groups']=$this->level_group->getGroup();
        $this->load->view('ini',$data);
    }

     public function add_class_group_level()
    { // add subject to the system

        //var_dump($this->input->post());
        $config_validation=array(
            array(
                'field' => 'levelID',
                'label' => 'Level',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide  %s.')
                ),
             array(
                'field' => 'groupID',
                'label' => 'Group',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide  %s.')
                ),
              array(
                'field' => 'limit',
                'label' => 'Limit',
                'rules' => 'numeric',
                'errors' => array('numeric'=>'%s should be a number.')
                ),
           
        );

         $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()==FALSE) {
            //$this->load->view('ini',$data);
            echo validation_errors();
        }else{
         
            $cgl_data = html_escape($this->input->POST());
            $search['level'] = $cgl_data['levelID'];
            $search['group'] = $cgl_data['groupID'];
            //get the class group level based on selected level and group
            $cgl = $this->level_group->getclassGroupLevel($search);
            //if ($cgl == NULL) {
              if ($cgl_data['cglid']=='') {
                $insertStatus= $this->level_group->addClassGroupLevel($cgl_data);
                if ($insertStatus) {
                  echo 'Add';
                }else{
                  echo 'NO';
                }
            
              }else{
                 $EditStatus= $this->level_group->editClassGroupLevel($cgl_data);
                 if ($EditStatus) {
                  echo 'Edit';

                  }else{
                    echo 'NO';
                  }

              }
              //}
        }//end main else
        
    }


    public function addSubject()
    { // add subject to the system

        //var_dump($this->input->post());
        $config_validation=array(
            array(
                'field' => 'subjectName',
                'label' => 'subjectName',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide  %s.')
                ),
           
        );

         $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()==FALSE) {
            //$this->load->view('ini',$data);
            echo validation_errors();
        }else{
            $insertStatus= $this->subjects->createSubject(html_escape($this->input->post()));
            //redirect('admin/manageSubjects');
            echo 'yes';
        }
        
    }
    public function editSubject()
    {//edit subject
       
            $config_validation=array(
            array(
                'field' => 'subjectName',
                'label' => 'subjectName',
                'rules' => 'required',
                'errors' => array('required'=>'Please Edit  %s.')
                ),
           
        );

         $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()==FALSE) {
            //$this->load->view('ini',$data);
            echo validation_errors();
        }else{
            $updateStatus= $this->subjects->updateSubject(html_escape($this->input->post()));
            //redirect('admin/manageSubjects');
            if($updateStatus)
            {
                echo 'yes';
            }else{
                echo 'no';
            }
            
        }
    }
     public function addQuarter()
    { // add Quarter to the system

        //var_dump($this->input->post());
        $config_validation=array(
            array(
                'field' => 'quarterName',
                'label' => 'quarterName',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide  %s.')
                ),
           
        );

         $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()==FALSE) {
            //$this->load->view('ini',$data);
            echo validation_errors();
        }else{
            $insertStatus= $this->admin->createQuarter(html_escape($this->input->post()));
            //redirect('admin/manageSubjects');
            if($insertStatus)
            {
                  echo 'yes';
              }else {
                  echo 'no';
              }
          
        }
        
    }
    public function editQuarter()
    {//edit Quarter
        
            $config_validation=array(
            array(
                'field' => 'quarterName',
                'label' => 'quarterName',
                'rules' => 'required',
                'errors' => array('required'=>'Please Edit  %s.')
                ),
           
        );

         $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()==FALSE) {
            //$this->load->view('ini',$data);
            echo validation_errors();
        }else{
            $insertStatus= $this->admin->updateQuarter(html_escape($this->input->post()));
            //redirect('admin/manageSubjects');
            if($insertStatus)
            {
                echo 'yes';
            }else{
                echo 'no';
            }
            
        }
    }


     public function addLevel()
    { // add level to the system

        //var_dump($this->input->post());
        $config_validation=array(
            array(
                'field' => 'levelName',
                'label' => 'levelName',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide  %s.')
                ),
           
        );

         $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()==FALSE) {
            //$this->load->view('ini',$data);
            echo validation_errors();
        }else{
            $insertStatus= $this->level_group->createLevel(html_escape($this->input->post()));
            //redirect('admin/manageSubjects');
            if($insertStatus)
            {
                  echo 'yes';
              }else {
                  echo 'no';
              }
          
        }
        
    }
    public function editLevel()
    {//edit Level
        
            $config_validation=array(
            array(
                'field' => 'levelName',
                'label' => 'levelName',
                'rules' => 'required',
                'errors' => array('required'=>'Please Edit  %s.')
                ),
           
        );

         $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()==FALSE) {
            //$this->load->view('ini',$data);
            echo validation_errors();
        }else{
            $insertStatus= $this->level_group->updateLevel(html_escape($this->input->post()));
            //redirect('admin/manageSubjects');
            if($insertStatus)
            {
                echo 'yes';
            }else{
                echo 'no';
            }
            
        }
    }



 public function addAssessType()
    { // add level to the system

        //var_dump($this->input->post());
        $config_validation=array(
            array(
                'field' => 'assessName',
                'label' => 'assessName',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide  %s.')
                ),
           
        );

         $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()==FALSE) {
            //$this->load->view('ini',$data);
            echo validation_errors();
        }else{
            $insertStatus= $this->admin->createAssessType(html_escape($this->input->post()));
            //redirect('admin/manageSubjects');
            if($insertStatus)
            {
                  echo 'yes';
              }else {
                  echo 'no';
              }
          
        }
        
    }
    public function editAssessType()
    {//edit Assessment Type
        
            $config_validation=array(
            array(
                'field' => 'assessName',
                'label' => 'assessName',
                'rules' => 'required',
                'errors' => array('required'=>'Please Edit  %s.')
                ),
           
        );

         $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()==FALSE) {
            //$this->load->view('ini',$data);
            echo validation_errors();
        }else{
            $insertStatus= $this->admin->updateAssessType(html_escape($this->input->post()));
            //redirect('admin/manageSubjects');
            if($insertStatus)
            {
                echo 'yes';
            }else{
                echo 'no';
            }
            
        }
    }

    public function addClassGroup()
    { // add level to the system

        //var_dump($this->input->post());
        $config_validation=array(
            array(
                'field' => 'classGroupName',
                'label' => 'classGroupName',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide  %s.')
                ),
           
        );

         $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()==FALSE) {
            //$this->load->view('ini',$data);
            echo validation_errors();
        }else{
            $insertStatus= $this->level_group->createClassGroup(html_escape($this->input->post()));
            //redirect('admin/manageSubjects');
            if($insertStatus)
            {
                  echo 'yes';
              }else {
                  echo 'no';
              }
          
        }
        
    }
   
    public function editClassGroup()
    {//edit Assessment Type
        
            $config_validation=array(
            array(
                'field' => 'classGroupName',
                'label' => 'classGroupName',
                'rules' => 'required',
                'errors' => array('required'=>'Please Edit  %s.')
                ),
           
        );

         $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()==FALSE) {
            //$this->load->view('ini',$data);
            echo validation_errors();
        }else{
            $insertStatus= $this->level_group->updateClassGroup(html_escape($this->input->post()));
            //redirect('admin/manageSubjects');
            if($insertStatus)
            {
                echo 'yes';
            }else{
                echo 'no';
            }
            
        }
    }

    public function askDelete($id_remove=0)
    {   //check delete
        if($id_remove !=0 && is_numeric($id_remove)){
        $data['id_user'] = $id_remove;

        }else{
            redirect("admin/user");
        }
        $data['pageToLoad'] = 'admin/askDelete';
        $data['pageActive'] = 'askDelete';
         $this->load->helper('form');
        $this->load->library('form_validation');
        //launch to check delete
        $this->load->view('ini',$data);

    }
    public function askDeleteParent($id_remove=0)
    {   //check delete
        if($id_remove !=0 && is_numeric($id_remove)){
        $data['id_user'] = $id_remove;
        }else{
            redirect("admin/user");
        }
        $data['pageToLoad'] = 'admin/askDeleteParent';
        $data['pageActive'] = 'askDeleteParent';
         $this->load->helper('form');
        $this->load->library('form_validation');
        //launch to check delete
        $this->load->view('ini',$data);

    }
    public function askDeleteTeacher($id_remove=0)
    {   //check delete
        if($id_remove !=0 && is_numeric($id_remove)){
        $data['id_user'] = $id_remove;
        }else{
            redirect("admin/user");
        }
        $data['pageToLoad'] = 'admin/askDeleteTeacher';
        $data['pageActive'] = 'askDeleteTeacher';
         $this->load->helper('form');
        $this->load->library('form_validation');
        //launch to check delete
        $this->load->view('ini',$data);

    }
    /*public function askDeleteSubject($id_remove=0)
    {   //check delete
        if($id_remove !=0 && is_numeric($id_remove)){
        $data['id_user'] = $id_remove;
        }else{
            redirect("admin/user");
        }
        $data['pageToLoad'] = 'admin/askDeleteSubject';
        $data['pageActive'] = 'askDeleteSubject';
         $this->load->helper('form');
        $this->load->library('form_validation');
        //launch to check delete
        $this->load->view('ini',$data);

    }*/

    public function askDeleteSubject(){
        $subjID = html_escape($this->input->POST('trashSubject'));
        if($subjID!=0 && is_numeric($subjID)){
            $deletingSubj = $this->subjects->deleteSubject($subjID);
            //$data['commID']=$commID;
           }
        if($deletingSubj){
                echo 'YES';
            }
            else{
                echo 'NO';
            }
        }

    public function askDeleteQuarter($id_remove=0)
    {   //check delete
        if($id_remove !=0 && is_numeric($id_remove)){
        $data['id_user'] = $id_remove;
        }else{
            redirect("admin/user");
        }
        $data['pageToLoad'] = 'admin/askDeleteQuarter';
        $data['pageActive'] = 'askDeleteQuarter';
         $this->load->helper('form');
        $this->load->library('form_validation');
        //launch to check delete
        $this->load->view('ini',$data);

    }
    /*********
    public function teacherList()
    {//bring the list of all the teachers
        $data['pageToLoad'] = 'admin/teacherList';
        $data['pageActive'] = 'teacherList';
        $data['userProfile']=$this->profile->getProfile($_SESSION['userID']);
        //var_dump($data['userProfile']);
        $data['teachers']=$this->teachers->getTeacher();
        //$data['teacherSubjects']=$this->subjects->getSubject();
        $data['levels']=$this->level_group->getclassLevel();
        $data['classTeacher']=$this->teachers->getClassTeacherClass();
        $data['schoolSubjects']=$this->subjects->getschoolSubjects();
        //var_dump($data['classTeacher']);
        $this->load->view('ini',$data); 
    }*/

    

    /*public function guardianList()
    {//list of all the guardians
        $data['pageToLoad']='admin/guardianList';
        $data['pageActive']='guardianList';
        $data['userProfile']=$this->profile->getProfile($_SESSION['userID']);
        $data['db']=$this->guardians->getGuardian();
        $data['myChildren']=$this->guardians->getGuardChild();
        //var_dump($data['myChildren']);
        $this->load->view('ini',$data);
    }*/
    public function manageAcademy()
    {//load manage Academy page
        $data['pageToLoad']='admin/manageAcademy';
        $data['pageActive']='manageAcademy';
        $this->load->view('ini',$data);
    }


    public function manageSubjects()
    {//load manage subjects page
        $data['pageToLoad']='admin/Subjects';
        $data['pageActive']='subjects';
        $data['schoolSubjects']=$this->subjects->getschoolSubjects();
        //var_dump($data['schoolSubjects']);
        $this->load->view('ini',$data);
    }
  
    public function manageSchoolQuarters()
    {
        //loads manage school quarters page
        $data['pageToLoad']='admin/schoolQuarters';
        $data['pageActive']='schoolQuarters';
        $data['Quarters']=$this->admin->getschoolQuarters();
        $this->load->view('ini',$data);
    }
    public function manageClassLevels()
    {
        //loads manage school levels page
        $data['pageToLoad']='admin/classLevels';
        $data['pageActive']='classLevels';
        $data['levels']=$this->level_group->getLevels();
        $this->load->view('ini',$data);
    }
     public function manageAssessmentTypes()
    {
        //loads manage school assessment types page
        $data['pageToLoad']='admin/assessmentTypes';
        $data['pageActive']='assessmentTypes';
        $data['assessments']=$this->admin->getAssessmentTypes();
        $this->load->view('ini',$data);
    }
    public function manageClassGroups()
    {
        //loads manage school assessment types page
        $data['pageToLoad']='admin/classGroups';
        $data['pageActive']='classGroups';
        $data['groups']=$this->level_group->getGroup();
        $this->load->view('ini',$data);
    }
   
   
    public function adminList()
    {//bring the list of all the teachers
        $data['pageToLoad'] = 'admin/adminList';
        $data['pageActive'] = 'adminList';
        $search['profile']= $_SESSION['userID'];
        $data['userProfile']=$this->profile->getProfile($search);
        $data['admins']=$this->admin->getAdmin();
        $this->load->view('ini',$data); 
    }

   /* public function LearnerList()
    {// add learners
        $data['pageToLoad'] = 'admin/LearnerList';
        $data['pageActive'] = 'LearnerList';
        $data['learners']=$this->learners->getLearners();
        //$data['groups']=$this->admin->getclassGroups();
       //$data['levels']=$this->admin->getclassLevels();
       
        $this->load->view('ini',$data);
    }*/
//todo list
  public function addTodoList()
    { // add todo to the system
      
      //var_dump($this->input->POST('sTaskDescription'));
       $data['pageToLoad'] = 'admin/administration';
        $data['pageActive'] = 'administration';
        //var_dump($this->input->post());
        $config_validation=array(
            array(
                'field' => 'sTaskDescription',
                'label' => 'sTaskDescription',
                'rules' => 'required|trim',
                'errors'=>array('required'=>'this field is a must')
                ),          
        );

         $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()==FALSE) {
            //$this->load->view('ini',$data);
            echo validation_errors();
        }else{
            $insertStatus= $this->admin->createTodoList(html_escape($this->input->post()));
            //redirect('admin/manageSubjects');
            if($insertStatus)
            {
                  echo 'yes';
              }else {
                  echo 'no';
              }
          }
        
    }
/**
 * [getTodolist method will be used by admin to keep list of things that 
 * @return [string] [the view to be printed]
 */
public function getTodolist($index=0)
{
    //assign search array with the admin logged in
    $search['user_todo'] = $_SESSION['userID'];
    //count tasks available
    $data['taskCount'] = $this->admin->countTasks($search);
    //get the list for this logged in admin
    $tasks = $this->admin->getTasks($search);
    $data['tasks'] = $tasks;
    $ct = 0;
    //loop thru all tasks
    foreach($tasks as $task){ 
        //check how many ones are incomplete
        if($task->completed == 0){
            $ct++;//keep count
        }
    }
    $data['ct'] = $ct;
    //assign the view to the the variable to be returned to the caller
    $todo_view = $this->load->view('admin_todo/getTodolist',$data,TRUE);
    //check if call comes from index or ajax
    if($index==1){
      return $todo_view;
    }else{
      echo json_encode($todo_view);
    }
         
}//end getTodolist

/**
 * getTodoHistory method will be used to generate the list of all the completed task
 * @return [string] [the string to print out the view]
 */
public function getTodoHistory()
{
    //assign search array with the admin logged in
    $search['deleted'] = $_SESSION['userID'];
    //count tasks available
    $data['taskCount'] = $this->admin->countTasks($search);
    //get the list for this logged in user
    $tasks = $this->admin->getTasks($search);
    $data['tasks'] = $tasks;
    
    //initialise count todo
    $ct = 0;
    //loop thru all tasks
    foreach($tasks as $task){ 
      //check how many ones are incomplete
      if($task->completed ==1){
        $ct++;//keep count
      }
    }
    $data['ct'] = $ct;
    //assign the view to the the variable to be returned to the caller
    $todo_view = $this->load->view('admin_todo/getTodoHistory',$data,TRUE);
    //return to the caller for output
    echo json_encode($todo_view);
  
}//end getTodoHistory


public function deleteTodo()
{
      // delete todo
          $statusRemoved = $this->admin->deleteTodo(html_escape($this->input->post('todoDel')));        
           if($statusRemoved)
           {
             echo 'YES';
           }else
           {
              echo 'NO';
           }
       
}
public function deleteTodoHistory()
{
      // delete todo History
          $statusRemoved = $this->admin->removeTodo(html_escape($this->input->post('todoDelHistory')));        
           if($statusRemoved)
           {
             echo 'YES';
           }else
           {
              echo 'NO';
           }
       
}







public function wipeTodoHistory()
{
      // delete todo 
      
          $statusRemoved = $this->admin->cleanHistory(html_escape($this->input->post('trashHistory')));
  echo '<pre>'; var_dump($statusRemoved);  echo'<pre>';
           if($statusRemoved)
           {
             echo 'YES';
           }else
           {
              echo 'NO';
           }
       
}
public function finishTodo()
{
      // delete todo
          $statusRemoved = $this->admin->completeTodo(html_escape($this->input->post('todofinish')));        
           if($statusRemoved)
           {
             echo 'YES';
           }else
           {
              echo 'NO';
           }
       
}
//end of todo list



    //Remove assigned class
  public function removeClassTeacher()
{
      // 
          $statusRemoved = $this->teachers->deleteClassTeacher(html_escape($this->input->post('removeTeacherClass')));       
           if($statusRemoved)
           {
             echo 'YES';
           }else
           {
              echo 'NO';
           }
       
}

//End assign Class

/*********Delete Admin**************/
    //Remove assigned class
    public function removeAdmin()
{
      // 
          $statusRemoved = $this->admin->deleteAdmin(html_escape($this->input->post('id_admin')));        
           if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'NO';
           }
       
}

/*********End of delete Admin**************/

/*********Delete Admin**************/
    //Remove the class group level
  public function removeclass_group_level()
{
      // 
          $statusRemoved = $this->admin->deleteClassGroupLevel(html_escape($this->input->post('cglid')));        
           if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'NO';
           }
       
}

/*********End of delete Admin**************/

}//end Admin controller
?>