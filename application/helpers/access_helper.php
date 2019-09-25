<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('identify_user_role')){

	/**
	 * [identify_user_role helper method will be used to identify the user logged in
	 * USer ID will be used to identify this user
	 * All the roles that the user is playing will be returned to the caller
	 * @param  integer $userID  [the unique identifier of the user logged in]
	 * @return [string]          [user role]
	 */
	function identify_user_role($userID = 0){
		if ($userID > 0){
			$ci =& get_instance();
			//declare empty array to hold the role of person logged in
			$user = array();
			//assign the user ID to array
			$search['user'] = $userID;
			//identify the current role of the user LOGGED IN
			$admin= $ci->admin->countAdmin($search);
			$guardian= $ci->guardians->countGuardian($search);
			$learner= $ci->learners->countLearners($search);
			$teacher= $ci->teachers->countTeachers($search);
			//if it returns learner, then send data to highlight role
		    if ($learner > 0) {
		      return 'learner';
		    }
		    //if it returns teacher, then send data to highlight role
		    if ($teacher > 0) {
		      $user[] = 'teacher';
		    }
		    //if it returns guardian, then send data to highlight role
		    if ($guardian > 0) {
		      $user[] = 'guardian';
		    }
		    //if it returns admin, then send data to highlight role
		    if ($admin > 0) {
		      $user[] = 'admin';
		    }
		    //variable to hold the string user logged in
		    $who = '';
		    //loop thru found user roles and build a string
		    for ($i = 0; $i < count($user); $i++) {
		    	$who .= $user[$i].'_';
		    }
		    //remove the last charecter of the string user who
		    $userWho = substr($who, 0,-1);
		    //return the string
		    return $userWho;
		}
		return '';
	}
}//end identify_user_role

?>