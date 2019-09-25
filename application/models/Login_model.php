<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * summary
 */
class Login_model extends CI_MODEL
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('cookie');
	}
    /**
     * [checkPassword description]
     * @param  [type] $password [description]
     * @return [type]           [description]
     */
    public function checkPassword($password)
    {
	   	$username = html_escape($this->input->post('username')); //get username input from the user
	   	$remember = html_escape($this->input->post('remember')); //get the value of checkbox from form
	   	if (empty($username) || empty($password)) { //FIRST STEP to check
	   		return FALSE;
	   	}
	   	//gets hash from the user - db record
	   	$hash = $this->getPasswordfromUser($username);
		//check if empty
	   	if (!empty($hash) && password_verify($password,$hash)) {
			//start user session
			$this->startUserSession($username); //call function start user session
			//go check if the user wants to be remembered
			//if yes set the cookie
			$this->remember_cookie($remember); //
			//if valid
			return TRUE;
		}
		return FALSE;
 	} //end of checkPassword
/**
 * [getPasswordfromUser description]
 * @param  [type] $username [description]
 * @return [type]           [description]
 */
public function getPasswordfromUser($username)
{
 	//get user from db
	$rowData = $this->db->select('userlogin.password')
	->from ('userlogin')
	->where ('userlogin.username',$username)
	->where('deleted',0)
	->get()->row();
	return !empty($rowData->password) ? $rowData->password : null;
}
/**
 * [startUserSession description]
 * @param  [type] $username [description]
 * @return [type]           [description]
 */
public function startUserSession($username)
{
	//get user data and load it into session_data
	$session_data = (array)$this->get_user($username);
	//set user login session to true
	$session_data['is_logged_in'] = TRUE;
	//set the user data into session
	$this->session->set_userdata($session_data);

}//end startUserSession


/**
 * [get_user description]
 * @param  [type] $username [description]
 * @return [type]           [description]
 */
public function get_user($username)
{
	$user_data = $this->db
						->select('userlogin.userID,userlogin.username,files.filePath as filePath')
						->from('userlogin')
						->join('user','userlogin.userID=user.id')
						->join('profile','profile.userID= user.id')
					    ->join('files','files.id=profile.filesID')
						->where ('userlogin.username',$username)
						->where('userlogin.deleted',0)
						->get()->row();
	return !empty($user_data) ? $user_data : null;
}//get_user

public function remember_cookie($remember, $method='login')
{
	if ($remember){ //test if it is TRUE
		if ($method=='login') {
			$this->deleteCookieByToken();
		}
		$expireTime = 3*24*3600; //for 3 days
		$expireDate = date('Y-m-d H:i:s',time()+$expireTime); //format the expiredate
		$token = $this->generateToken(); //generate the token
		//create new cookie and assign it to array
		$newCookieData = array(
			'userID'=>$_SESSION['userID'],
			'token'=>$token,
			'expireDate'=>$expireDate);
		if ($method=='login') {	//if by login, theninsert	
			$this->db->insert('logintoken',$newCookieData); //insert into logintoken table
		}
		//if by cookie, then update the token on the db
		if ($method=='cookie') {
			$tokenOld = isset($_COOKIE[COOKIE_TOKEN]) ? $_COOKIE[COOKIE_TOKEN] : ''; //check if cookie exist
			$this->db->where('token',$tokenOld)
					 ->update('logintoken',$newCookieData);
		}

		set_cookie(COOKIE_TOKEN,$token,$expireTime); // SET THE COOKIE
	}
}//end remember_cookie

public function deleteCookieByToken()
{
	$token = isset($_COOKIE[COOKIE_TOKEN]) ? $_COOKIE[COOKIE_TOKEN] : ''; //check if cookie exist
	if (!empty($token)) { //check if the cookie is not empty
		$this->db->where('token',$token)
				 ->delete('logintoken');
	}
}
/**
 * This will randomly generate the logintoken
 * @return [string] 
 */
public function generateToken()
{
	return bin2hex(openssl_random_pseudo_bytes($length=32));
}//end generateToken


function checkLoginWithCookie()
{

	$token = isset($_COOKIE[COOKIE_TOKEN]) ? $_COOKIE[COOKIE_TOKEN] : ''; //check if cookie exist
	if (!empty($token) && $this->validateCookie($token)) { //check if the cookie is not empty
		$username = $this->getUserFromCookie($token); 
		$this->startUserSession($username);
		$this->remember_cookie(TRUE, 'cookie');		

		return TRUE;
	} 
	return FALSE;
}
//check if the cookie is valid
function validateCookie($token)
{	
	$this->deleteExpiredCookies();
	$resultsDb = (array)$this->db->select('token')
								->from('logintoken')
								->where('token',$token)
								->get()->row();

	if(isset($resultsDb['token']) && $resultsDb['token']==$token){			
		return TRUE;	
	}

	return FALSE; //return FALSE if the user is not the same

}
/**
 * [deleteExpiredCookies description]
 * @return [type] [description]
 */
function deleteExpiredCookies()
{	
	$today = date("Y-m-d H:i:s"); //get todays date
	$this->db->where('expireDate <',$today) 
			->delete("logintoken"); //delete all expired
}
/**
 * [getUserFromCookie description]
 * @param  [type] $token [description]
 * @return [type]        [description]
*/ 
function getUserFromCookie($token)
{

	$username= (array)$this->db->select('t1.username') 
	->from('userlogin as t1') 
	->join('logintoken as t2', 't1.userID = t2.userID','left')
	->where('t2.token',$token)
	->get()->row();
		    			//->LIMIT(1);

	if (isset($username['username'])) {
		return $username['username'];
	} 

	return ""; //return empty string if the user is not the same

}
} //end of class