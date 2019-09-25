<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Send_email extends CI_Controller {

	/**
	 * [__construct description]
	 */
	
	public function __construct(){
		parent::__construct();
		$this->load->library('email');
		$this->load->model('Postoffice_model');
	}

/**
 *send an email with the template on views\email\new_user_email_template
 *
 **/
public function index(){
	//email
	
	$name = 'name';
	$email ='blaqueish@gmail.com';
	$token = bin2hex(openssl_random_pseudo_bytes(32));				
	$url = 'App/'.$token;
	//url to be sent by the email
	$url_to_activate = base_url($url);
	//prepare the template new user to be sent by email
	$this->Postoffice_model->setTemplate($this->load->view("email/new_user_email_template",array(),TRUE));
	//prepare the data neaded for the email
	$templateData = array('user_name'	=> $name,
		'url_to_activate' 		=> $url_to_activate
		 );
	$this->Postoffice_model->setDataToTemplate($templateData);
	$subject ='Registration' ;
	//send email to the new user
	$a=$this->Postoffice_model->sendEmail($subject,$email);
	if($a){
		echo"email sent..";
	}else{
		echo"error";
	}

				//end email
}//end index methode
}