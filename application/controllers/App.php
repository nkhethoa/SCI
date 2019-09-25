<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * summary
 */
class App extends CI_Controller{
	public function __construct(){
    parent::__construct();
    $this->load->helper(array('form','url','date'));
    $this->load->library('form_validation');
    $this->load->model('Login_model','Login');
    $this->load->model('Users_model');
    $this->load->model('Admin_model','admin');
    $this->load->model('Comments_model','comments_model');
    $this->load->model('Guardian_model','guardians');
    $this->load->model('Learner_model','learners');
    //this model is used for anything teacher related
    $this->load->model('Teacher_model','teachers');
    $this->load->model('Message_model','message_model');
    $this->load->model('Announce_model','announce_model');
    $this->load->model('Home_calendar_model','home_calendar');
     
     //check user credentials
        $is_logged_in = ($this->session->userdata('is_logged_in')) ? $this->session->userdata('is_logged_in') : FALSE;

        //if the user is not logged in
        if (!($is_logged_in)) { 
            //check if cookie exist for login
            if (!($this->Login->checkLoginWithCookie())) {
                //otherwise redirect to login page
                redirect('Guests#login','refresh');  
            }//end cookie
        }//end logged_in

}   


    public function index($year=null,$month=null)
    {
        //var_export($this->session->userdata('is_logged_in'));
        //(strpos($a, 'are')
        $data['pageToLoad'] = 'home/home';
        $data['pageActive'] = 'home';
        //get announcements to be displayed on the home page
        $data['announces'] = $this->announce_model->getAnnounce();
        $data['gtopics'] = $this->comments_model->getTopicsQuery();
        //if the year has value
        if (!$year) {
            //get the year
            $year = date('Y');
        }
        //if the month has the value
        if(!$month)
        {
            //get the month
            $month = date('m');
        }
        //generate calendar and send to the view
        $data['calendar'] = $this->home_calendar->generateCal($year,$month);
        //to load the view
        $this->load->view('ini',$data);
    }

    public function teacher()
    {
        $data['pageToLoad'] = 'teacher/teacherList';
        $data['pageActive'] = 'teacherList';
        $this->load->view('ini',$data);
    }

    public function learner()
    {
       
        $this->load->view('admin/register');
    }
    
    public function parent()
    {
        $data['pageToLoad'] = 'parent/myChild';
        $data['pageActive'] = 'myChild';
        $this->load->view('ini',$data);
    }
    public function profile()
    {
        $data['pageToLoad'] = 'profile/userProfile';
        $data['pageActive'] = 'userProfile';
        $this->load->view('ini',$data); 

    }
    public function editProfile()
    {
        //$data['userData'] = $this->Users_model->updateProfile();

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
                )
        );

         $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()==FALSE) {
            $this->load->view('ini',$data);
        }else{
            $this->Users_model->updateProfile($this->input->post());
            $this->load->view('ini',$data);
        }
        
    }
    
    public function logout()
    {
        $this->Login->deleteCookieByToken(); //delete the cookie that exist in db
        delete_cookie(COOKIE_TOKEN); //delete cookie
        $this->session->sess_destroy(); //destroy session
        redirect('Guests'); //redirect to publiczone
    }

}// end of app controller

