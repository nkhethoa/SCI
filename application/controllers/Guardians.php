<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * summary
 */
class Guardians extends CI_Controller{
	public function __construct(){
    parent::__construct();
   //this model is used for anything teacher related
    $this->load->model('Teacher_model','teachers');
    //this model is used for user related data calls
    $this->load->model('Users_model');
    //this model will have guardian related data calls
    $this->load->model('Guardian_model','guardians');
    //for groups and levels related data
    $this->load->model('Level_Group_model','level_group');
    //for learner related data
    $this->load->model('Learner_model','learners');
    $this->load->model('Admin_model','admin');
    $this->load->model('Login_model','login');
    $this->load->model('Files_model','files');
    $this->load->model('Message_model','message_model');
    $this->load->model('Announce_model','announce_model');
    $this->load->model('Profile_model','profile');
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

    }//end constructor
  
  public function guardians()
    {//list of all the guardians
        $data['pageToLoad']='admin/guardianList';
        $data['pageActive']='guardianList';
        $data['all_profiles']=$this->profile->getProfile();
        $data['db']=$this->guardians->getGuardian();
        $data['myChildren']=$this->guardians->getGuardChild();
        //var_dump($data['myChildren']);
        $this->load->view('ini',$data);
    }
   

   // send parent message
public function sendParentsMessage()
{
       $config_validation = array(
         array( 
            'field' => 'gsubj',
            'label' => 'Subject',
            'rules' => 'required|min_length[5]', 
            'errors' => array('required'=>'Subject please.')
            ),
          array( 
            'field' => 'gmsg',
            'label' => 'Message',
            'rules' => 'required|min_length[5]', 
            'errors' => array('required'=>'Please say something, even HELLO will do.')
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
                'title'=>html_escape($this->input->post('gsubj')),
                'body'=>html_escape($this->input->post('gmsg')),
                'to_user_id'=>html_escape($this->input->post('userGuardID'))
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
    }// End Send Parents Messsage
}//end controller
?>