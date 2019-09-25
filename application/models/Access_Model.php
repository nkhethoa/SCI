<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access_model extends CI_MODEL
{
	public function __construct()
	{
		parent::__construct();

	}
	public function check_user()
	{
		
	}
//this will create temporary login record for the user
    /**
     * tempLogins generate temporary loggins while the user has not activated the account
     * @param  int    $userID the userID of the user to be registered
     * @param  string $token  32bit token string
     * @param  array  $data   array with user data
     * @return bool       a boolean to tell if all went OK with insert
     */
	public function newUser_tempLogins($userID,$token,$data)
	{
		$bytes = openssl_random_pseudo_bytes(32);
		$pass = bin2hex($bytes);
        //assign db fields with data from the user
		$tempLogin = array(
					'password'=>$pass,
					'username'=>$data['email'],
					'userID'=>$userID,
					'activated'=>$token
					);
        $this->db->trans_start();
		$this->db->insert('userlogin',$tempLogin);
        return $this->db->trans_complete();
	}
	

    /**
     * [updateUserLogin when the user reset his/her password under
     * manage profile
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function updateUserLogin($data)
    {
        //only the following fields will be updated
        $updateLogin = array(
                        'password' => password_hash($data['password'],PASSWORD_DEFAULT),
                        );
        //start transaction
        $this->db->trans_start();
        //this update will be checked against existing token on the database
        $this->db->where('userlogin.username',$_SESSION['username'])
                ->update("userlogin",$updateLogin);
        return $this->db->trans_complete(); //close transacion
    }



/**
 * tempLogins this will be called when the user has forgotten the password
 * @param  string $token 32bit string to be sent to the user
 * @param  array   $data  array with user data 
 * @return bool        boolean based on the transaction
 */
public function reset_tempLogins($token,$data)
{
	//the following fields will be updated
	$updateLogin =array(
				'password' =>bin2hex(openssl_random_pseudo_bytes(32)),
				'resetkey'=>$token,
				'deleted'=>0
 			);
 	//start transaction
	$this->db->trans_start();
	//this update will be checked against existing token on the database
    $this->db->where('userlogin.username',$data['email'])
    		->update("userlogin",$updateLogin);
    return $this->db->trans_complete(); 
    //close transacion

}

 //the user will create a new password and it will be updated on the database
public function updateNewUserLogin($data)
{
	//only the following fields will be updated
	$updateLogin =array(
					'password' => password_hash($data['password'],PASSWORD_DEFAULT),
					'activated'=>'Active'
	 			);
		$this->db->trans_start();//start transaction
		//this update will be checked against existing token on the database
	    $this->db->where('userlogin.activated',$data['nekot'])
	    		->update("userlogin",$updateLogin);
	    return $this->db->trans_complete(); //close transacion
}

//the user will create a new password and it will be updated on the database
public function updatePassReset($data)
{
	//only the following fields will be updated
	$updateLogin =array(
					'password' => password_hash($data['password'],PASSWORD_DEFAULT),
					'resetkey'=>null
	 			);
		$this->db->trans_start();//start transaction
		//this update will be checked against existing token on the database
	    $this->db->where('userlogin.resetkey',$data['reset'])
	    		->update("userlogin",$updateLogin);
	    return $this->db->trans_complete(); //close transacion
}


/**
 * passwordCheck is the method that checks if the user password
 * adheres to the minimum character requirements 
 * @param  [type] $password the string entered by the user
 */
public function passwordCheck($password)
    {
        $password = trim($password);
        $regex_case = '/[a-zA-Z]/';
        $regex_number = '/[0-9]/';
        $regex_special = '/[!@#$%^&*()=+{}:]/';

        if (preg_match_all($regex_case, $password) < 1)
        {
            return FALSE;
        }
        if (preg_match_all($regex_number, $password) < 1)
        {
            return FALSE;
        }
        if (preg_match_all($regex_special,$password) < 1)
        {
            return FALSE;
        }

        return TRUE;
	}
/**
 * [verifyEmail verifies if the email the user submitted matches the one on database]
 * @param  [type] $username [description]
 * @return [type]           [description]
 */
public function verifyEmail($email)
{
	
	$qryUser= (array)$this->db->select('email')
								->from('user')
								->where('email',$email)
								->get()->row();

	if(isset($qryUser['email']) && $qryUser['email']==$email){
		return TRUE;
	}else{
		return FALSE;
	}
}
	/**
 * [verifyToken verifies the token that was sent to user for 
 * account activation]
 * @param  [type] $token [description]
 * @return [type]        [description]
 */
public function verifyToken($token)
{
	$qryToken= (array)$this->db->select('activated')
							->from('userlogin')
							->where('activated',$token)
							->get()->row();

	if(isset($qryToken['activated']) && $qryToken['activated']==$token){
		return TRUE;
	}else{
		return FALSE;
	}
}

/**
 * [verifyResetKey the reset key sent to user 
 * needs to be confirmed if it matches on the db
 * @param  [type] $token [description]
 * @return [type]        [description]
 */
public function verifyResetKey($token)
{
	$qryToken= (array)$this->db->select('resetKey')
								->from('userlogin')
								->where('resetKey',$token)
								->get()->row();

	if(isset($qryToken['resetKey']) && $qryToken['resetKey']==$token){
		return TRUE;
	}else{
		return FALSE;
	}
}

} //end of class
