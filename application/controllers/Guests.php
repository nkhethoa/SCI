<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * summary
 */
class Guests extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library("user_agent");
        $this->load->library("email");
        $this->load->model('Login_model');
    }

    public function index()
    {
           
        $this->load->helper('form');
        $this->load->view('guest/guest');
        
    }//end index
    
    public function contactUs()
    {
        $this->load->helper('form');
        $this->load->library("form_validation");

        $config_validation = array(
             array(
                'field' => 'name',
                'label' => 'name',
                'rules' => 'required|min_length[2]',
                'errors' => array('required'=>'Please provide your %s.')
                ),
             array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => array('required','valid_email'),
                'errors' => array('required'=>'Please provide valid %s address.')
                ),
             array(
                'field' => 'subject',
                'label' => 'Subject',
                'rules' => array('required','min_length[5]','max_length[100]'),
                'errors' => array('required'=>'Please provide subject for your contact.')
                ),
             array(
                'field' => 'message',
                'label' => 'Message',
                'rules' => array('required','min_length[5]','max_length[500]'),
                'errors' => array('required'=>'Message is required, even Hello will do.')
                ),
                
        );

        $this->form_validation->set_rules($config_validation);
        //check if the form is ok
        if ($this->form_validation->run()==FALSE) {
            //write errors back to the user
            echo validation_errors();
        } else {
            //get the name of the sendeer
            $name=html_escape($this->input->POST('name'));
            //ge tthe send email
            $sender_email=html_escape($this->input->POST('email'));
            //get subject
            $subject=html_escape($this->input->POST('subject'));
            //get the message
            $message=html_escape($this->input->POST('message'));
            //send email
            $send= $this->sendEmail($name,$subject,$sender_email,$message);
            //check if the email was send 
            if ($send) {
                echo 'done';
            }else{
                echo 'no';
            }
            
        }

    }//end of contactUs 


public function sendEmail(string $name_from, string $subject, string $email_from, string $message_body){

    $this->email->set_newline("\r\n");
    // Sender email address
    $this->email->from($email_from,$name_from);
    // Receiver email address
    $this->email->to('thutosci@gmail.com');
    // Subject of email
    $this->email->subject($subject);
    //set message type 
    $this->email->set_mailtype("html");
    // Message in email
    $this->email->message($message_body);
     //return $this->email->print_debugger();
    return $this->email->send();

}//end send email


}//end of controller Guests
