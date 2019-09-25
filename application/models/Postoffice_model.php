<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
* Model to manage the email messages to send
*/
class Postoffice_model extends CI_Model {

	private $template;
	private $email_body;


	public function __constructor(){
		parent::__constructor();
	}

	/**
	 * Sets the template.
	 *
	 * @param      string  $template  The template
	 */
	public function setTemplate(string $template){
		$this->template = $template;
	}

/**
* Sets the data to template with an array. Containing key values as the variables name to be replaced
*
* @param      array  $data   The data
*/
public function setDataToTemplate(array $data){

	$aux = $this->template;
	foreach ($data as $key => $value) {
		$aux = str_replace("{".$key."}",$value, $aux);
	}
	$this->email_body = $aux;
}

/**
 * Sends an email.
 *
 * @param      string  $subject   The subject
 * @param      string  $email_to  The email to
 *
 * @return     <type>  ( description_of_the_return_value )
 */
public function sendEmail(string $subject,string $email_to){

	$email_from = $this->config->item('email_from');

	//************************************************************
	$this->email->set_newline("\r\n");
	// Sender email address
	$this->email->from($email_from);
	// Receiver email address
	$this->email->to($email_to);
	// Subject of email
	$this->email->subject($subject);
	$this->email->set_mailtype("html");
	// Message in email
	$this->email->message($this->email_body);
	return $this->email->send();
}

}
