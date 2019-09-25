<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * summary
 */
class Access extends CI_Controller{
    public function __construct(){
        parent::__construct();
        //this model is used for anything OUT of SYSTEM email related
        $this->load->model('Mail_model','mail');
        //this model is used for anything password related
        $this->load->model('Access_model','access');
        $this->load->helper(array('form','url','security'));
        $this->load->library(array('form_validation','email'));
        $this->load->model('Login_model','login');
        $this->load->model('Users_model');
        $this->load->model('Admin_model','admin');
        $this->load->model('Guardian_model','guardians');
        $this->load->model('Teacher_model','teachers');
        $this->load->model('Learner_model','learners');       
        $this->load->model('Message_model','message_model');       
        $this->load->model('Announce_model','announce_model');       
    }
//this will open the form when the user clicks on the link from email
public function register()
{
    $data['nekot'] = html_escape($this->uri->segment(3,0));
    $this->load->view('admin/register',$data);

}//end register

//this will open the form when the user clicks on the link from email
public function forgotPassword()
{
    $data['reset'] = html_escape($this->uri->segment(3,0));
    $this->load->view('admin/forgotPassword',$data);

}//end forgotPassword
/**
 * [registerComplete when the password is created, open this message to inform the user
 * @return [type] [description]
 */
public function registerComplete()
{
    $this->load->view('admin/activationDone');
}

/**
 * [validateEmail the user wants to reset password, 
 * verify email/username first
 * @return [type] [description]
 */
public function validateEmail()
{
    $config=array(
        array(
            'field'=>'email',
            'label'=>'email',
            'rules'=>array('xss_clean','required','valid_email','trim',array('existMatch',array($this->access,'verifyEmail'))),
            'errors'=>array('xss_clean'=>'text not clean',
                            'required'=>'We can only identify you by your username',
                            'valid_email'=>'Username must be a valid email address',
                            'existMatch'=>'Thuto-sci cannot find this username from the records. Please verify it.'
                        ),
            )
    );

    $this->form_validation->set_rules($config);
    //check if the validation went OK
    if ($this->form_validation->run()===FALSE) {
        //send errors to the form
        echo validation_errors();
    }else {
        //generate token to send to user
        $token = bin2hex(openssl_random_pseudo_bytes(32));
        //generate temporary user password which will be changed on activation
        $tempPassStatus=$this->access->reset_tempLogins($token,html_escape($this->input->POST()));
        //if temp password went OK, send an email
        if ($tempPassStatus) {
            //prepare and send email
            $emailSend = $this->mail->prepareForgotPassEmail(html_escape($this->input->POST()),$token);
            //if the message was send
            if ($emailSend) {
                echo 'Yes';
            }else{
                echo 'No';
            }
        }
        
    }


}//end validateEmail


/**
 * [validateUser description]
 * @return [type] [description]
 */
public function validateUser()
{
    $config=array(
        array(
            'field'=>'nekot',
            'label'=>'nekot',
            'rules'=>array('required',array('passMatch',array($this->access,'verifyToken'))),
            'errors'=>array('passMatch'=>'Please click on the link again. Contact admin if the problem persists'),
            ),

        array(
            'field'=>'password',
            'label'=>'password',
            'rules'=>array('required','trim','min_length[7]',array('userPass',array($this->access,'passwordCheck'))),
            'errors'=>array('min_length'=>'Please enter minimum of seven alphanumeric with special characters.',
                            'required'=>'This field is required.',
                            'min_length'=>'Password should be seven characters long',
                            'userPass'=>'Minimum password requirements are [e.g. a number, Upper and lower case , special characters like @#$, and atleast seven characters long]')
        ), 

        array(
            'field'=>'confirm_password',
            'label'=>'confirm password',
            'rules'=>array('required','trim','min_length[7]'),
            'errors'=>array('min_length'=>'Password should be seven characters long'),
        )
    );
    
     //load the rules into the validation form
    $this->form_validation->set_rules($config);
    //check if all went well validation
    if ($this->form_validation->run()===FALSE) {
        //reload the token to data variable
         $data['nekot'] = html_escape($this->input->POST('nekot'));
        //load the view if the validation fails
        $this->load->view('admin/register',$data);
    }else{
        
        $status = $this->access->updateNewUserLogin(html_escape($this->input->POST()));
        if ($status) {
            redirect('Access/registerComplete');
        }else {
            $this->load->view('admin/register');
        }
        
        
    }
        
}//end ValidateUser

/**
 * [validateUserResetKey this will work when the user has forgotten the password]
 *
 */
public function validateUserResetKey()
{
    
    $config=array(
        array(
            'field'=>'reset',
            'label'=>'reset',
            'rules'=>array('required',array('passMatch',array($this->access,'verifyResetKey'))),
            'errors'=>array('passMatch'=>'Please click on the link again. Contact admin if the problem persists'),
            ),

        array(
            'field'=>'password',
            'label'=>'password',
            'rules'=>array('required','trim','min_length[7]',array('userPass',array($this->access,'passwordCheck'))),
            'errors'=>array('min_length'=>'Please enter minimum of seven alphanumeric with special characters.',
                            'required'=>'This field is required.',
                            'min_length'=>'Password should be seven characters long',
                            'userPass'=>'Minimum password requirements are [e.g. number, Upper/lower case, special characters like @#$, and atleast seven characters long]')
        ), 

        array(
            'field'=>'confirm_password',
            'label'=>'confirm password',
            'rules'=>'required|trim|min_length[7]',
            'errors'=>array('min_length'=>'Password should be seven characters long'),
        )
    );
   

    $this->form_validation->set_rules($config);

    if ($this->form_validation->run()===FALSE) {
         //get the value of the uri 
        $data['reset'] = html_escape($this->input->POST('reset'));
        //reload the view with the same token
        $this->load->view('admin/forgotPassword',$data);
    }else{
        
        $status = $this->access->updatePassReset(html_escape($this->input->POST()));
        if ($status) {
            redirect('Access/registerComplete');
        }else {
            $this->load->view('Access/forgotPassword');
        }
        
        
    }
        
}//end ValidateUser

    public function resetPassword()
    {//redirects to register page for password reset 
        $data['pageToLoad']='admin/resetPassword';
        $data['pageActive']='resetPassword';
        $this->load->view('ini',$data);
    }

    public function change_my_password()
    {
        $data['pageToLoad']='admin/resetPassword';
        $data['pageActive']='resetPassword';
        $config=array(
      
            array(
                'field'=>'password',
                'label'=>'password',
                'rules'=>array('required','trim','min_length[7]',array('userPass',array($this->access,'passwordCheck'))),
                'errors'=>array('min_length'=>'Please enter minimum of seven alphanumeric with at least one special character.',
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
            
            $status = $this->access->updateUserLogin(html_escape($this->input->POST()));
            if ($status) {
                redirect('profile?statusEdit=$statusEdit');
            }
            
            
        }
        
    }//end ValidateUser

 
}// end of app controller

