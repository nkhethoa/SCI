=<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * summary
 */
class Admin extends CI_Controller{
	public function __construct(){
    parent::__construct();
    $this->load->model('Users_model');
    $this->load->model('admin_model','user');
    $this->load->model('Login_model');
    $this->load->model('Files_model','files');
    $this->load->model('Profile_model','profile');
    $this->load->helper('form');
    $this->load->library(array('form_validation','email'));
   //check user credentials
        $is_logged_in = $this->session->userdata('is_logged_in')?? FALSE;
        //if the user is not logged in
        if (empty($is_logged_in)) { 
        //check if cookie exist for login
            if (!$this->login->checkLoginWithCookie()) { 
            //otherwise redirect to login page
                redirect('Guests#login','refresh'); 
            }//end cookie
        }//end logged_in
    }
    public function index()
    {// loads admin main page
         $data['pageToLoad'] = 'admin/administration';
        //active on the nav
        $data['pageActive'] = 'administration';
        $data['userProfile']=$this->profile->getProfile($_SESSION['userID']);
       /*$id_remove = $this->input->post('id_user');
        if($id_remove !=0 && is_numeric($id_remove)){
            $data['statusRemoved'] = $this->user->deleteUser($id_remove);
        }
        if(null != $this->input->get('statusEdit')){
            $data['statusEdit'] = $this->input->get('statusEdit');
        }

        if(null != $this->input->get('statusInsert')){
            $data['statusInsert'] = $this->input->get('statusInsert');
        }
       
        $search['search']=$this->input->get('search') ?? '';
        $search['id_user']=$this->input->get('id_user') ?? 0;
            //}
        $search['page'] = $this->input->get('page') ?? 0;
        $data['search']=$search;
        $data['db']=$this->user->getUser($search);
        $data['countUsers'] = $this->user->countUsers($search);
            //this is to enable rewrite queries
        $config['enable_query_string'] = TRUE;
        //show the actual page number, ?page =someInt
        $config['page_query_string'] = TRUE;
        //url that use the pagination
        $config['base_url'] = base_url('Admin?search='.$search['search']);
        //number of results to be on the pagination
        $config['total_rows'] = $data['countUsers'];
        $this->load->library('pagination');
        //initialize the pagination with our config
        $this->pagination->initialize($config);
        $data['search_pagination']=$this->pagination->create_links();*/
         $this->load->view('ini',$data);
    } 
    public function users()
    {//loads the manageUsers page
       
         $data['pageToLoad'] = 'admin/manageUsers';
        //active on the nav
        $data['pageActive'] = 'manageUsers';
        $data['userProfile']=$this->profile->getProfile($_SESSION['userID']);
        if($this->input->post('id_user') !=0 && is_numeric($this->input->post('id_user') )){
            $data['statusRemoved'] = $this->user->deleteUser($this->input->post('id_user') );
        }//delete Guardian/Parent
        if($this->input->post('id_userGuardian') !=0 && is_numeric($this->input->post('id_userGuardian'))){
            $statusRemoved = $this->user->deleteParent($this->input->post('id_userGuardian'));
             if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'no';
           }
        }// delete teacher
         if($this->input->post('id_userTeacher') !=0 && is_numeric($this->input->post('id_userTeacher'))){
            $statusRemoved = $this->user->deleteTeacher($this->input->post('id_userTeacher'));
            if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'no';
           }
        }
        // delete Subject
         if($this->input->post('subid') !=0 && is_numeric($this->input->post('subid'))){
            $statusRemoved = $this->user->deleteSubject($this->input->post('subid'));  
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
         if($this->input->post('quarterId') !=0 && is_numeric($this->input->post('quarterId'))){
            $statusRemoved = $this->user->deleteQuarter($this->input->post('quarterId'));         
           if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'no';
           }
        }
        // delete Level
         if($this->input->post('levID') !=0 && is_numeric($this->input->post('levID'))){
            $statusRemoved = $this->user->deleteLevel($this->input->post('levID'));         
           if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'no';
           }
        }

        // delete Assessment Type
         if($this->input->post('assessID') !=0 && is_numeric($this->input->post('assessID'))){
            $statusRemoved = $this->user->deleteAssessmentType($this->input->post('assessID'));         
           if($statusRemoved)
           {
             echo 'yes';
           }else
           {
              echo 'no';
           }
        }
          // delete Class Group
         if($this->input->post('groupID') !=0 && is_numeric($this->input->post('groupID'))){
            $statusRemoved = $this->user->deleteClassGroup($this->input->post('groupID'));         
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
        }//display suucess/ error messages

        if(null != $this->input->get('statusInsert')){
            $data['statusInsert'] = $this->input->get('statusInsert');
        }
       //search the user
        $search['search']=$this->input->get('search') ?? '';
        $search['id_user']=$this->input->get('id_user') ?? 0;
            //}
        $search['page'] = $this->input->get('page') ?? 0;
        $data['search']=$search;
        $data['db']=$this->user->getUser($search);
        $data['countUsers'] = $this->user->countUsers($search);
            //this is to enable rewrite queries
        $config['enable_query_string'] = TRUE;
        //show the actual page number, ?page =someInt
        $config['page_query_string'] = TRUE;
        //url that use the pagination
        $config['base_url'] = base_url('Admin/users?search='.$search['search']);
        //number of results to be on the pagination
        $config['total_rows'] = $data['countUsers'];
        $this->load->library('pagination');
        //initialize the pagination with our config
        $this->pagination->initialize($config);
        $data['search_pagination']=$this->pagination->create_links();
         $this->load->view('ini',$data);
    } 
    public function addUser()
    { // add user to the system
        $data['pageToLoad'] = 'admin/addUser';
        $data['pageActive'] = 'addUser';
        $data['pageTitle'] = 'Add User';
        $data['userData'] = $this->Users_model->getUsers();
        $data['levels']=$this->user->getclassLevels();
        $this->load->helper('form');
        $this->load->library('form_validation');

        $config_validation=array(
            array(
                'field' => 'firstName',
                'label' => 'Firstname',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide your %s.')
                ),
            array(
                'field' => 'lastName',
                'label' => 'Lastname',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide your %s.')
                ),
            array(
                'field' => 'middleName',
                'label' => 'Middlename',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide your %s.')
                ),
             array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => array('required','valid_email','is_unique[user.email]'),
                'errors' => array('required'=>'Please provide valid %s address.',
                                   'is_unique'=>'the email address already exist on the system')
                ),
             array(
                'field' => 'phone',
                'label' => 'Phone',
                'rules' => 'required|exact_length[10]',
                'errors' => array('required'=>'Please provide valid %s number.')
                ),
             array(
                'field' => 'address',
                'label' => 'Address',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide street %s.')
                ),
              array(
                'field' => 'role[]',
                'rules' => 'required',
                'errors' => array('required'=>'Please select role.')
                ),
        );
        //get input role
        $role = html_escape($this->input->POST('role'));
        //configure rules if the new user is a learner
        for ($i = 0; $i < count($role) ; $i++) {
          if ($role[$i]=='learner') {
            $config_validation=array(
                array(
                      'field' => 'level',
                      'label' => 'level',
                      'rules' => 'required|numeric',
                      'errors' => array('required'=>'Please select a %s.')
                      ),
                array(
                  'field' => 'group',
                  'label' => 'group',
                  'rules' => 'required|numeric',
                  'errors' => array('required'=>'Please select a %s.')
                  ),
                array(
                  'field' => 'doeLearnNo',
                  'label' => 'Learner Number',
                  'rules' => 'required|numeric|is_unique[learner.learnerNumber]',
                  'errors' => array('required'=>'Please provide %s.')
                  )
            );//close config_validation array
        }//close IF
      }//close for loop
          //set the rules 
         $this->form_validation->set_rules($config_validation);
         //check if all went well with validation
        if ($this->form_validation->run()==FALSE) {
          //keep the current view so the user can correct errors
            $this->load->view('ini',$data);

        //else validation passed
        }else{

          $id_user=0;
          //get user data
          $userData = html_escape($this->input->POST());
          //get input level
          $level = html_escape($this->input->POST('level'));
          //get input group
          $group = html_escape($this->input->POST('group'));
          //get input learner number
          $doeLearnNo = html_escape($this->input->POST('doeLearnNo'));
          //loop thru the roles selected
          
          for ($i = 0; $i < count($role); $i++) {
            //ONLY if role is not learner
            if(($role[$i] != 'learner') && ($level == '' ) && ($group == '')){
              //if user is created, don't call again
              if ($id_user == 0) {
                //insert the user into the specific table
                $id_user= $this->user->createUser($userData);
              }
              //if role is teacher
              if ($role[$i]=='teacher') {
                //call method to add teacher
                $statusInsert = $this->user->addTeacher($id_user);    
                 //call method to add guardian
              }elseif($role[$i]=='guardian'){
                //insert guardian
                $statusInsert = $this->user->addGuardian($id_user);
                 //call method to add admin
              }elseif($role[$i]=='admin'){
                //insert admin
                $statusInsert = $this->user->addAdmin($id_user);
              }
              //call method to add learner
            }elseif(($role[$i] == 'learner') && ($level != '' ) && ($group != '')){
                //this values will be used to search for
                $search['level'] = $level;
                $search['group'] = $group;
                //get class group level ID
                $cgl = $this->user->getclassGroupLevel($search);
                //if this combination returns something
                  if ($cgl != NULL) {
                    //start process of creating new user
                    $id_user= $this->user->createUser($userData);
                    //insert learner
                    $statusInsert = $this->user->addLearner($id_user,$cgl[0]->cglID,$doeLearnNo);
                  }
                  
              }
          }

          //check if insert of user to respective table went well
          if ($statusInsert) {
            //generatee token to be send by email
            $token = bin2hex(openssl_random_pseudo_bytes(32));
            //generate temporary [Lock Account] user password which will be changed on activation
            $this->user->tempLogins($id_user,$token,$userData);
            //when the user is registered, send an email to confirm password
            $statusInsert = $this->user->prepareEmail($token,$userData);
          //if not set statusInsert to false
          }             
            
            //???
            redirect('admin/Users?statusInsert=$statusInsert');

      }//else of validation
        
}//end addUser

    public function editUser($id=0)
    {// edit existing users

        //var_dump($id);
        //check edit
        if($id !=0 && is_numeric($id)){
        $data['id_user'] = $id;
        }else{
           // redirect("admin/user");
        }
        $data['pageToLoad'] = 'admin/addUser';
        $data['pageActive'] = 'addUser';
        $data['pageTitle'] = 'Edit User';
        $search['id_user'] = $id;
        $data['db'] = $this->user->getUser($search);
        foreach ($data['db'] as $value) {
            $data['firstNameEdit'] = $value->fName;
            $data['middleNameEdit'] = $value->midName;
            $data['lastNameEdit'] = $value->lName;
            $data['emailEdit'] = $value->email;
            $data['phoneEdit'] = $value->phone;
            $data['addressEdit'] = $value->address;
        }

        $this->load->helper('form');
        $this->load->library('form_validation');

        $config_validation=array(
            array(
                'field' => 'firstName',
                'label' => 'Firstname',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide your %s.')
                ),
            array(
                'field' => 'lastName',
                'label' => 'Lastname',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide your %s.')
                ),
            array(
                'field' => 'middleName',
                'label' => 'Middlename',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide your %s.')
                ),
             array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => array('required','valid_email'),
                'errors' => array('required'=>'Please provide valid %s address.')
                ),
             array(
                'field' => 'phone',
                'label' => 'Phone',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide valid %s number.')
                ),
             array(
                'field' => 'address',
                'label' => 'Address',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide street %s.')
                ),

              array(
                'field' => 'role',
                'label' => 'role',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide a role %s.')
                )
                
        );

         $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()==FALSE) {
            //var_dump($this->input->post());
            $this->load->view('ini',$data);
        }else{
            
           $insertStatus= $this->user->updateUser($this->input->post());
            redirect('admin/Users?statusEdit = $statusEdit');
        }
        
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
            $insertStatus= $this->user->createSubject($this->input->post());
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
            $insertStatus= $this->user->updateSubject($this->input->post());
            //redirect('admin/manageSubjects');
            if($insertStatus)
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
            $insertStatus= $this->user->createQuarter($this->input->post());
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
            $insertStatus= $this->user->updateQuarter($this->input->post());
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
            $insertStatus= $this->user->createLevel($this->input->post());
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
            $insertStatus= $this->user->updateLevel($this->input->post());
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
            $insertStatus= $this->user->createAssessType($this->input->post());
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
            $insertStatus= $this->user->updateAssessType($this->input->post());
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
            $insertStatus= $this->user->createClassGroup($this->input->post());
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
     * [getLevel called when the user select level when adding a learner]
     * 
     */
    public function getLevel()
    {
      //assign selected level into search array
      $search['level']=html_escape($this->input->post('levelId'));
      //use the search array to look specifi values
      $groupLevel= $this->user->getclassGroupLevel($search);
      //start with select
      $select = '';
      $select .= '<option hidden=hidden >Select Class Group';
      //check if there are values to display
      if ($groupLevel != null) {
          foreach($groupLevel as $cgLevels){ 
            $select .= '<option value='.$cgLevels->cgID.'>'.$cgLevels->classGroupName;
          }
        }else{
            //if no values found
            $select .= '<option> No Class groups for this level';
        }
            
        echo json_encode($select);

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
            $insertStatus= $this->user->updateClassGroup($this->input->post());
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
            redirect("Admin/user");
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
            redirect("Admin/user");
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
            redirect("Admin/user");
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
            redirect("Admin/user");
        }
        $data['pageToLoad'] = 'admin/askDeleteSubject';
        $data['pageActive'] = 'askDeleteSubject';
         $this->load->helper('form');
        $this->load->library('form_validation');
        //launch to check delete
        $this->load->view('ini',$data);

    }*/

    public function askDeleteSubject(){
        $subjID = $this->input->POST('trashSubject');
        if($subjID!=0 && is_numeric($subjID)){
            $deletingSubj = $this->user->deleteSubjects($subjID);
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
            redirect("Admin/user");
        }
        $data['pageToLoad'] = 'admin/askDeleteQuarter';
        $data['pageActive'] = 'askDeleteQuarter';
         $this->load->helper('form');
        $this->load->library('form_validation');
        //launch to check delete
        $this->load->view('ini',$data);

    }
    public function teacherList()
    {//bring the list of all the teachers
        $data['pageToLoad'] = 'admin/teacherList';
        $data['pageActive'] = 'teacherList';
        $data['userProfile']=$this->profile->getProfile($_SESSION['userID']);
        //var_dump($data['userProfile']);
        $data['db']=$this->user->getTeachers();
        $data['classTeacher']=$this->user->getClassTeacherByClass();
        //var_dump($data['classTeacher']);
        $this->load->view('ini',$data); 
    }
    public function guardianList()
    {//list of all the guardians
        $data['pageToLoad']='admin/guardianList';
        $data['pageActive']='guardianList';
        $data['userProfile']=$this->profile->getProfile($_SESSION['userID']);
        $data['db']=$this->user->getGuardian();
        $data['myChildren']=$this->user->getGuardianChild();
        //var_dump($data['myChildren']);
        $this->load->view('ini',$data);
    }
    public function resetPassword()
    {//redirects to register page for password reset 
        $data['pageToLoad']='admin/resetPassword';
        $data['pageActive']='resetPassword';
        $this->load->view('ini',$data);
    }
    public function manageAcademy()
    {//load manage Academy page
        $data['pageToLoad']='admin/manageAcademy';
        $data['pageActive']='manageAcademy';
        $this->load->view('ini',$data);
    }
    public function manageSubjects()
    {//load manage subjects page
        $data['pageToLoad']='admin/subjects';
        $data['pageActive']='subjects';
        $data['schoolSubjects']=$this->user->getschoolSubjects();
        //var_dump($data['schoolSubjects']);
        $this->load->view('ini',$data);
    }
    public function manageSchoolQuarters()
    {
        //loads manage school quarters page
        $data['pageToLoad']='admin/schoolQuarters';
        $data['pageActive']='schoolQuarters';
        $data['Quarters']=$this->user->getschoolQuarters();
        $this->load->view('ini',$data);
    }
    public function manageClassLevels()
    {
        //loads manage school quarters page
        $data['pageToLoad']='admin/classLevels';
        $data['pageActive']='classLevels';
        $data['levels']=$this->user->getclassLevels();
        $this->load->view('ini',$data);
    }
     public function manageAssessmentTypes()
    {
        //loads manage school assessment types page
        $data['pageToLoad']='admin/assessmentTypes';
        $data['pageActive']='assessmentTypes';
        $data['assessments']=$this->user->getAssessmentTypes();
        $this->load->view('ini',$data);
    }
    public function manageClassGroups()
    {
        //loads manage school assessment types page
        $data['pageToLoad']='admin/classGroups';
        $data['pageActive']='classGroups';
        $data['groups']=$this->user->getclassGroups();
        $this->load->view('ini',$data);
    }
    public function validateUser()
    {
        $data['pageToLoad']='admin/resetPassword';
        $data['pageActive']='resetPassword';
        $config=array(
      
            array(
                'field'=>'password',
                'label'=>'password',
                'rules'=>array('required|trim|min_length[7]',array('userPass',array($this->Users_model,'passwordCheck'))),
                'errors'=>array('min_length'=>'Please enter minimum of seven alphanumeric with special characters.',
                                'required'=>'This field is required.',
                                'trim'=>'Avoid leading spaces.',
                                'min_length'=>'Password should be seven characters long',
                                'userPass'=>'Minimum password requirements are [e.g. number, Upper/lower case, special characters like @#$, and atleast seven characters long]')
            ), 

            array(
                'field'=>'confirm_password',
                'label'=>'confirm password',
                'rules'=>array('required','trim','min_length[7]'),
                'errors'=>array('min_length'=>'Password should be seven characters long',
                                'trim'=>'Avoid leading spaces.',
                                ),
            )
        );

        $this->form_validation->set_rules($config);

        if ($this->form_validation->run()===FALSE) {

            $this->load->view('ini',$data);
            
        }else{
            
            $status = $this->user->updateUserLogin(html_escape($this->input->POST()));
            if ($status) {
                redirect('profile?statusEdit = $statusEdit');
            }else {
            
            }
            
            
        }
        
    }//end ValidateUser
    

    public function addLearner()
    {// add learners
        $data['pageToLoad'] = 'admin/addLearner';
        $data['pageActive'] = 'addLearner';
        $data['groups']=$this->user->getclassGroups();
       $data['levels']=$this->user->getclassLevels();
       $data['schoolSubjects']=$this->user->getschoolSubjects();
        $this->load->view('ini',$data);
    }

}//end Admin controller
?>