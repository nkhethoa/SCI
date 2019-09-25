<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Model to manage the email messages to send
*/
class Mail_model extends CI_Model {
	public function __constructor(){
		parent::__constructor();
	}

	/**
	  * [sendEmail send email to the user]
	  * @param  string $name_from    [the sender of the email]
	  * @param  string $subject      [email subject]
	  * @param  string $email_to     [the receiver of the email]
	  * @param  string $message_body [message to be send]
	  * @return [boolean]               [send status]
	  */
	public function sendEmail($name_from, $subject, $email_to, $message_body){

		$email_from = $this->config->item('email_from');

		$this->email->set_newline("\r\n");
		// Sender email address
		$this->email->from($email_from,$name_from);
		// Receiver email address
		$this->email->to($email_to);
		// Subject of email
		$this->email->subject($subject);
		//set message type 
		$this->email->set_mailtype("html");
		// Message in email
		$this->email->message($message_body);
		 //return $this->email->print_debugger();
		return $this->email->send();
	}

	/**
	 * prepareEmail to prepare the email that will be send to the user 
	 * as part of the registration process
	 * @param  string $token random generated token to verify the user
	 * @param  array  $data  is an array with user data
	 * @return bool    to tell if the message was send after preparation        
	 */
	public function prepareEmail($token, $data)
    {
        $name = 'Thuto-sci';
        $email =$data['email'];
        //
        $url = 'Access/register/'.$token;
        //url to be sent by the email
        $url_to_activate = base_url($url);
        $message = '';
        $message .= '<p>Hi ' .$data['firstName'].' '.$data['lastName'].'<br>'; 
        $message .= 'You have been successfully registered to Thuto-sci<br>Please follow the link below to activate your account.</p>';
        $message .= '<a type="button" href="'.$url_to_activate.'" class="btn btn-danger">Activate Account</a> ';
        $subject='Thuto-sci Account Activation';
        //send email
        $send=$this->mail->sendEmail($name,$subject,$email,$message);
        //check if the message was successfully sent
        if ($send) {
            return TRUE;
        }else {
            return FALSE;
        }

    }

    /**
 * [prepareEmail for when the user forgot password]
 * @param  [type] $token [description]
 * @param  [type] $data  [description]
 * @return [type]        [description]
 */
public function prepareForgotPassEmail($data, $token)
    {
    	//specify the name of the sender
        $from = 'Thuto-sci';
        //who the email is being send to
        $email_to =$data['email'];
        //assign the token and the URL to the variable
        $url = 'Access/forgotPassword/'.$token;
        //url to be sent by the email
        $url_to_activate = base_url($url);
        //build the message body to send to user
        $message_body = '';
        $message_body .= '<p>Hi ' .$data['email'].'<br>'; 
        $message_body .= 'You or someone have requested password reset for your Thuto-sci account. <br>If it was not you, please notify the admin at school or click on the link below to reset your password.</p>';
        $message_body .= '<a role="button" href="'.$url_to_activate.'" class="btn btn-danger">Activate Account</a> ';
        //subject for the message
        $subject='Thuto-sci Password Reset';
        //send email
        $send=$this->mail->sendEmail($from,$subject,$email_to,$message_body);
        //check if the message was successfully sent
        if ($send) {
            return TRUE;
        }else {
            return FALSE;
        }

    }

}
