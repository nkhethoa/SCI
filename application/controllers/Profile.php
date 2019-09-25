<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * summary
 */
class Profile extends CI_Controller{
    public function __construct(){
    parent::__construct();
    $this->load->model('Teacher_model','teachers');
    //this model is used for user related data calls
    $this->load->model('Users_model');
    //this model will have guardian related data calls
    $this->load->model('Guardian_model','guardians');
    //for groups and levels related data
    $this->load->model('Level_Group_model','level_group');
    //for learner related data
    $this->load->model('Learner_model','learners');
     //this model is used for anything subject related
    $this->load->model('Subject_model','subjects');
    $this->load->model('Admin_model','admin');
    $this->load->model('Login_model','login');
    $this->load->model('Files_model','files');
    $this->load->model('Profile_model','profile');
    $this->load->model('Message_model','message_model');
    $this->load->model('Announce_model','announce_model');
    $this->load->helper('form');
    $this->load->library('form_validation');
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

    }
    public function index()
    {
   
         if(null != $this->input->get('statusEdit')){
            $data['statusEdit'] = $this->input->get('statusEdit');
        }

        $data['pageToLoad'] = 'profile/userProfile';
        //active on the nav
        $data['pageActive'] = 'userProfile';
        $search['search']=!empty($this->input->get('profile') ) ? $this->input->get('profile') : '';
        $search['profile']= $_SESSION['userID']; 
        $data['userProfile']=$this->profile->getProfile($search);

        if(identify_user_role($_SESSION['userID']) == 'learner')
        {

            $data['learner']=$this->learners->getLearners();
        }
        
         $this->load->view('ini',$data);
    }
    public function editProfile()
    {
        //var_export($_FILES['fileUpload']['type']);
        $variable = html_escape($this->input->POST('form_data'));
      
        $id = $_SESSION['userID'];

        $data['pageToLoad'] = 'profile/userProfile';
        $data['pageActive'] = 'userProfile';
        $search['profile']= $_SESSION['userID']; 
        $data['userProfile'] = $this->profile->getProfile($search);
        $this->load->helper('form');
        $this->load->library('form_validation');

        $config_validation=array(
            array(
                'field' => 'firstName',
                'label' => 'Firstname',
                'rules' => array('required','alpha'),
                'errors' => array('required'=>'Please provide your %s.')
                ),
            array(
                'field' => 'lastName',
                'label' => 'Lastname',
                'rules' => array('required','alpha'),
                'errors' => array('required'=>'Please provide your %s.')
                ),
            array(
                'field' => 'middleName',
                'label' => 'Middlename',
                'rules' => array('required','alpha'),
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
                'rules' => array('required','numeric'),
                'errors' => array('required'=>'Please provide valid %s number.')
                ),
             array(
                'field' => 'address',
                'label' => 'Address',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide street %s.')
                ),
              array(
                'field' => 'fileID',
                'label' => 'fileID',
                'rules' => 'required',
                'errors' => array('required'=>'Please Choose an Image.')
                ),

              array(
                'field' => 'bio',
                'label' => 'bio',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide biography %s.')
                )
                
        );
    
          
         $this->form_validation->set_rules($config_validation);
         if ($this->form_validation->run()==FALSE) {
           echo validation_errors();
            //$this->load->view('ini',$data);
           
       }else{
            //check if we have a file to upload
           if($_FILES['fileUpload']['name']){
                //check if the file being uploaded is the image
                if (strpos($_FILES['fileUpload']['type'], 'image/') !== FALSE) {
                    //upload file
                    $uploadFile=$this->files->uploadFile();
                    //check if the image was uploaded 
                    if ($uploadFile['error'] == 0) {
                        //insert the image
                        $insertStatus= $this->profile->updateProfile(html_escape($this->input->post()),$uploadFile);
                        //check if insert went all OK
                        if($insertStatus)
                        {
                            echo 'YES';
                        }else{
                            echo 'NO';
                        }
                    }else{
                        echo 'Error: Please try again.';
                    }
                }else{
                    echo 'Profile picture can only be an image';
                }

            }else{
                echo 'No file uploaded.';
            }
            //check if the file was uploaded
            
           /* $username=$_SESSION['username'];
           session_destroy();
           $this->Login_model->startUserSession($username);
           redirect('profile?statusEdit='.$insertStatus);*/
       }

   }

    
}
?>