<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * summary
 */
class App extends CI_Controller{
	public function __construct(){
    parent::__construct();
     $this->load->helper(array('form','url'));
     $this->load->library(array('form_validation','email'));
     $this->load->model('Login_model','login');
     $this->load->model('Users_model','user');
     $this->load->model('announce_model');
     
     $is_logged_in = $this->session->userdata('is_logged_in')?? FALSE;
        if (!$is_logged_in) { //if the user is not logged in
            if (!$this->login->checkLoginWithCookie()) { //check if cookie exist for login
                redirect('Guests'); //otherwise redirect to login page
            }
            
        }

}   
   public function index()
    {
        $data['pageToLoad'] = 'home/home';
        $data['pageActive'] = 'home';
        $data['announces'] = $this->announce_model->getAnnounce();
        $this->load->view('ini',$data);

    }

    public function teacher()
    {
        $data['pageToLoad'] = 'teacher/teacherList';
        $data['pageActive'] = 'teacherList';
        $this->load->view('ini',$data);
    }
	public function validateUser()
    {
        $data['pageToLoad'] = 'register/registration';
        $data['pageActive'] = 'registration';

        $config=array(
                    /*array(
                        'field'=>'username',
                        'label'=>'username',
                        'rules'=>'required|valid_email|trim|min_length[5]|is_unique[userlogin.username]|matches[user.email]',
                        'errors'=>'required',
                    ),
                    array(
                        'field'=>'userID',
                        'label'=>'userID',
                        'rules'=>'required|is_numeric|is_unique[user.id]|matches[user.id]',
                        'errors'=>'required',
                    ),
                    array(
                        'field'=>'email',
                        'label'=>'email',
                        'rules'=>'required|valid_email|is_unique[user.email]',
                        'errors'=>'required',
                    ),*/
                    array(
                        'field'=>'password',
                        'label'=>'password',
                        'rules'=>'required|trim|min_length[6]',
                        'errors'=>array('required'=>'Really!, we need to know your key',
                                        'min_length'=>'Please dont be a lazy typer, six alphanumeric with special characters is minimum.'),
                    ),
                    
                    array(
                        'field'=>'confirm_password',
                        'label'=>'confirm password',
                        'rules'=>'required|trim|min_length[6]|matches[password]',
                        'errors'=>array('required'=>'This is as important as password, enter it',
                                        'min_length'=>'Adhere to the same minimum length as stated in the password tog',
                                        'matches'=>'Confirm means it must be the same as password above'),
                    ),
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run()==FALSE) {
            $this->load->view('ini',$data);
        }else{
            $hash=password_hash($this->input->POST('password'),PASSWORD_DEFAULT);
            var_dump($hash);
            //set email subject
            $this->prepareEmail();
            //insert record
            //$status=$this->user->registerUserLogin($this->input->POST(),$hash);
            $this->load->view('ini',$data);
            //redirect('Guests#login');
        }
        //$this->load->view('ini',$data);
    }
public function prepareEmail()
{
            $name = 'Nkhethoa';
            $email ='thutosci@gmail.com';
            $token = bin2hex(openssl_random_pseudo_bytes(32));              
            $url = 'App/learner/'.$token;
            //url to be sent by the email
            $url_to_activate = base_url($url);
            $message = '';
            $message .= '<p>You have been successfully registered to Thuto-sci<br>Please click on the button below to activate your account.</p>';
            $message .= '<a type="button" href="'.$url_to_activate.'" class="btn btn-success">Activate Account</a> ';
            $subject='Activate your Thuto-sci account for school communication';
            //send email
            $send=$this->user->sendEmail($subject,$email,$message);
            if ($this->email->send()) {
                echo 'Done';
            }else {
                echo 'error';
            }

}
    public function learner()
    {
        $data['pageToLoad'] = 'register/registration';
        $data['pageActive'] = 'registration';
        $this->load->view('ini',$data);
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
        $this->Login_model->deleteCookieByToken(); //delete the cookie that exist in db
        delete_cookie(COOKIE_TOKEN); //delete cookie
        $this->session->sess_destroy(); //destroy session
        redirect('Guests'); //redirect to publiczone
    }

}// end of app controller

