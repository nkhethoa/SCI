<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * summary
 */
class Users_model extends CI_MODEL
{
	public function __construct()
	{
		parent::__construct();
		
	}


	//search for users on the system
	public function qryUser(array $search =array())
	{
		$m = isset($search['user']) ? $search['user'] : FALSE;
		$id_user = isset($search['id_user']) ? $search['id_user'] : FALSE;
        $userEmail = isset($search['usersEmails']) ? $search['usersEmails'] : FALSE;
		$userEmailr = isset($search['userEmailr']) ? $search['userEmailr'] : FALSE;

		if($m){
			$where = '(user.firstname LIKE "%'.$m.'%" || 
			            user.middlename LIKE "%'.$m.'%" ||
						user.id LIKE "%'.$m.'%" || 
						user.identityNumber LIKE "%'.$m.'%" ||
					    user.lastname LIKE "%'.$m.'%" ||
					    user.email LIKE "%'.$m.'%")';
			$this->db->where($where);
		}
		if($id_user){
			$this->db->where('user.id',$id_user);
		}
		if($userEmail){
			$this->db->where('user.email =', $userEmail);
			$this->db->or_where('user.identityNumber =', $userEmail);
		}

		if($userEmailr){
			$this->db->where('user.email',$userEmailr);
		}
          return $this->db->select("user.id as userID,user.lastName as lName,user.middleName as midName, user.firstName as fName, user.phone as phone, user.identityNumber, user.email as email, user.address as address,profile.filesID as filesID,files.fileName as fileName,fileSize as fileSize,fileType as fileType,filePath as filePath, tempName as tempName")  
        ->from("user")
        ->join('profile','profile.userID=user.id')
        ->join('files','files.id=profile.filesID')
        ->where('user.deleted',0)
        ->group_by('user.id');

	}
	//get users from the database
	public function getUsers($search = array(),$limit = ITEMS_PER_PAGE)
    {
       $offset = !empty($search['page']) ? $search['page'] : 0;
		$this->qryUser($search)
			->limit($limit,$offset);
		return $this->db->get()->result();

    }
    // count users
    public function countUsers($search = array()){
		$this->qryUser($search);
		return $this->db->count_all_results();
	}


	public function createUser($data)
	{
		
		$user = array(
           "firstName"=>$data['firstName'],
           "middleName"=>$data['middleName'],
           "lastName"=>$data['lastName'],
           "email"=>$data['email'],
           "phone"=>$data['phone'],
           "address"=>$data['address'],
           "identityNumber"=>$data['id_number']
		);
		//start transaction
		$this->db->trans_start();
		//WRITE INTO THE TABLE USER
		$this->db->insert("user",$user);
		//the last insert userID
		$id_user = $this->db->insert_id();
		//value of the default profile pic 
		$search['profile']= 1;
		//get the temporary image to user for the user profile
		$fileData=$this->files->getFile($search);
		//assign file db response into file array
		$file['fileName'] = $fileData[0]->fileName;
		$file['fileSize'] = $fileData[0]->fileSize;
		$file['fileType'] = $fileData[0]->fileType;
		$file['filePath'] = $fileData[0]->filePath;
		$file['tempName'] = $fileData[0]->tempName;
		//insert avator [temporary profile pic]
		$filesID = $this->files->insertFile($file);
		//create user profile 
		$statusInsert = $this->profile->addProfile($data,$filesID,$id_user);
		$this->db->trans_complete();
		return $id_user;
	}

    /**
     * [updateUser method will be called when user record need editing
     * @param  array $data has all the needed data from the form
     * @return bool   return transaction status
     */
	public function updateUser($data)
	{
	    $user = array(
	        "firstName"=>$data['firstName'],
	        "middleName"=>$data['middleName'],
	        "lastName"=>$data['lastName'],
	        //"email"=>$data['email'], //cannot edit email as is unique username
	        "phone"=>$data['phone'],
	        "address"=>$data['address'],
	        //can only edit passport as it changes with every application
	        "identityNumber"=>$data['id_number'] 
	    );
	    $this->db->trans_start();
	    $this->db->where('user.id',$data['idUser'])
	             ->update('user',$user);
	    return $this->db->trans_complete();

	}

	//delete user from the system
	public function deleteUser($id_user)
	{  
		$deleted = 1;
	   	$this->db->trans_start();
	   	$this->db->where('id',$id_user)
	            ->update('user',array('deleted'=>$deleted));
	   //delete user login account
	   $this->deleteLogin($id_user);
	  return $this->db->trans_complete();

	}
	/**
	 * [deleteLogin whent the user is deleted
	 * delete the login accout relating to that user
	 * @param  int    $id_user id of the user being deleted
	 * @return bool          boolean of transaction
	 */
	public function deleteLogin($id_user)
	{  
		$deleted = 1;
	   	$this->db->trans_start();
	   	$this->db->where('id',$id_user)
	            ->update('userlogin',array('deleted'=>$deleted));
	  return $this->db->trans_complete();

	}

	/**
	 * [validateChars method will check the input characters
	 * Return false if disallowed characters are found on the string
	 * @param  string $str string input from the user
	 * @return [boolean]      [bool]
	 */
	public function validateChars($str)
	{	
		//take out trailling spaces
		$str = trim($str);
		//preg match to validate string
		$allowed = "/^[a-zA-Z ]*$/";
		//check if the characters are within this preg match
		if (!preg_match($allowed,$str)) {
	  		return FALSE; 
		}
		return TRUE;
	}

	/**
	 * [validate_passport method will check whether the passport number is within the format
	 * Return false if it does not match 
	 * @param  string $str [passport number supplied]
	 * @return [bool]      [Boolean]
	 */
	public function validate_passport($str)
	{	
		//take out trailling or leading spaces
		$str = trim($str);
		//preg match to validate string
		$f_allowed = "/[A-Z]{1}[0-9]\d{5,7}/";
		$s_allowed = "/[0-9]\d{5,8}/";
		//check if the characters are within this preg match
		if (!preg_match($s_allowed,$str) OR !preg_match($f_allowed,$str)) {
	  		return FALSE; 
		}
		return TRUE;
	}

/***************************Reactivate Users***********************************/

	//search for users on the system
	public function qry_deleted_users($search =array())
	{
		$m = isset($search['user']) ? $search['user'] : FALSE;
		$id_user = isset($search['id_user']) ? $search['id_user'] : FALSE;

		if($m){
			$where = '(user.firstname LIKE "%'.$m.'%" || 
            user.middlename LIKE "%'.$m.'%" ||
			user.id LIKE "%'.$m.'%" ||
			user.identityNumber LIKE "%'.$m.'%" ||
		    user.lastname LIKE "%'.$m.'%"||
		    user.email LIKE "%'.$m.'%")';
			$this->db->where($where);
		}
		if($id_user){
			
			$this->db->where('user.id',$id_user);
		}

        return $this->db->select("user.id as userID, user.lastName as lName, user.middleName as midName, user.firstName as fName, user.phone as phone, user.email as email, user.address as address,profile.filesID as filesID,files.fileName as fileName,fileSize as fileSize,fileType as fileType,filePath as filePath")  
			        ->from("user")
			        ->join('profile','profile.userID=user.id')
                    ->join('files','files.id=profile.filesID')
			        ->where('user.deleted',1)
			        ->group_by('user.id');

	}
	//get users from the database
	public function get_deleted_users($search = array(),$limit = ITEMS_PER_PAGE)
    {
       $offset = isset($search['page']) ? $search['page'] : 0;
		$this->qry_deleted_users($search)
			->limit($limit,$offset);
		return $this->db->get()->result();

    }
    // count users
    public function count_deleted_users($search = array()){
		$this->qry_deleted_users($search);
		return $this->db->count_all_results();
	}

	//Activate User on the system
	public function activateUser($userid)
	  {  
	    $deleted = 0;
	      $this->db->trans_start();
	      $this->db->where('id',$userid)
	              ->update('user',array('deleted'=>$deleted));
	         //activate login on undelete
	        $this->activate_user_login($userid);
	    return $this->db->trans_complete();

	}

	/**
	 * [activate_user_login method will undelete the login account of the user being activated
	 * @param  int    $userid [id of the user being activated]
	 * @return [bool]         [boolean]
	 */
  	public function activate_user_login($userid)
  	{  
    	$deleted = 0;
      	$this->db->trans_start();
      	$this->db->where('id',$userid)
              ->update('userlogin',array('deleted'=>$deleted));
    	return $this->db->trans_complete();
  }

/***************************End of Reactivate Users***********************************/

/**
 * [verify_ID_number this method accept the SA ID NUMBER and verifies it
 * The method accept the ID numbers string]
 * @param  [string] $id_number string ID number
 * @return [bool]            [True or false depending on the string supplied]
 */
	public function verify_ID_number($id_number)
	{
		//split the id into tokens
		$tokenize_id_num = str_split($id_number);
		//id control variable, to hold 13th[last] digit of id
		//then add zer0 to cast to integer
		$id_control_value = substr($id_number, -1,1) + 0;
		//variable to hold even numbers
		$group_even = '';
		//to hold total of odd numbers
		$add_odd = 0;
		//set the loop counter limit 
		//and exclude the last digit of the id number
		$max_limit = count($tokenize_id_num) - 1;
		//loop thru the array of tokens
		for ($i = 0; $i < $max_limit; $i++) {
			//user mod operator to separate odd and even numbers 
			if ($i % 2) {
				//concatenate even numbers
				$group_even .= $tokenize_id_num[$i];
			}else {
				//sum odd numbers
				$add_odd += $tokenize_id_num[$i]; 
			}
		}//end loop

		//double grouped even numbers
		$dbl_group = $group_even * 2;	
		//after doubling, tokenize the numbers
		$split_dbl = str_split($dbl_group);
		//to hold total of split group numbers
		$add_after_split = 0;
		//loop thru the numbers and add them
		for ($i = 0; $i < count($split_dbl); $i++) {
			//add numbers from the array of split
			$add_after_split += $split_dbl[$i];
		}

		//sum total of odd numbers and add after split
		$add_even_final_odd = $add_odd + $add_after_split;
		//subtract the second character of the answer above from 10
		$calculated_control_value = 10 - substr($add_even_final_odd, 1,1);
		//check if two digits were returned
		if (strlen($calculated_control_value) > 1) {
			//take the second digit and compare with the control value
			$calculated_control_value = substr($add_even_final_odd, 1,1);
		}
		//check if the value match with the given control value
		if ($calculated_control_value == $id_control_value) {
			//ID IS CORRECT
			return TRUE;
		}else {
			//incorrect id supplied
			return FALSE;
		}
	}//end verify_ID_number


} //end of model