<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logins extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Login_model","login");
		//users related data 
		$this->load->model("Users_model");
      	//this is for anything admin related 
		$this->load->model('Admin_model','admin');
      	//this model will have guardian related data calls
		$this->load->model('Guardian_model','guardians');
		//model has learners data
		$this->load->model('Learner_model','learners');
		//this model is used for anything teacher related
      	$this->load->model('Teacher_model','teachers');
	}
	/**
	 * [validateuser description]
	 */
	public function validateUser()
	{
		$this->load->helper('form');
		$this->load->library("form_validation");

		$config_validation = array( 
			 array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => array('required','valid_email'),
                'errors' => array('required'=>'You have provided invalid %s.',
            						'valid_email'=>'Please provide valid email address for username')
        						),
			 array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => array('required',
            					array('checkPassword',array($this->login,'checkPassword'))),	      						
				'errors' => array('required'=>'You have provided invalid %s.',
								'checkPassword'=>'Invalid %s or Password incorrect')
					),
			 
			 array( 'field' => 'remember',
					'label' => 'Remember')
				
		);

		$this->form_validation->set_rules($config_validation);
		//check if the form is ok
		if ($this->form_validation->run()===FALSE) {
			//display the error messages if the fields are not valid
			if ($this->form_validation->error_array()>0) {
				echo ('Thuto-sci cannot find either your username or password.');
			}
			
		} else {
			//if all went well, send results back to ajax
			echo 'done';
		}

}
	
}