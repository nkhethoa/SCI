<?php
 defined('BASEPATH') OR exit('No direct script access allowed');

class Guardian_model extends CI_Model{
 public function __construct(){
 	parent:: __construct();
 	$this->load->database();
 }
/**
 * [getHowRelated will bring records of all types of relationships 
 * the learner can have with guardian
 * @return [type] [description]
 */
	public function getHowRelated()
	{
		return $this->db->select("relationship.id as howRelatedID, relationship.description as relationship")   
			        	->from("relationship")
			        	->where('relationship.deleted',0)
			        	->get()->result();
	}

	public function qryGuardian(array $search=array()){

		//assign the user id from array search to a variable
    	$userID = isset($search['user']) ? $search['user'] : FALSE;

		if ($userID) {
			$this->db->where('guardian.userID',$userID);
		}

	    return $this->db->select("user.id as g_uID,user.lastName as gLName, user.middleName as gMName, user.firstName as gFName, user.phone as gPhone, user.address as gAddress, user.email as gEmail, guardian.id as guardID,user.id as userID,profile.filesID as filesID,files.fileName as fileName,fileSize as fileSize,fileType as fileType,filePath as filePath,relationship.id as relID, relationship.description as relDescription, relationship.deleted as relDeleted")  
				        ->from("guardian")
				        ->join('user', 'user.id = guardian.userID')
				        ->join('profile','profile.userID=user.id')
				        ->join('learnerguardian','learnerguardian.guardianID = guardian.id')
				        ->join('relationship','relationship.id = learnerguardian.relationshipID')
                        ->join('files','files.id=profile.filesID')
				        ->where('guardian.deleted',0);
	}

	public function getGuardian(array $search=array())
	{
		$this->qryGuardian($search);
		return $this->db->get()->result();
	}

	public function countGuardian(array $search=array())
	{
		$this->qryGuardian($search);
		return $this->db->count_all_results();
	}


	public function qryGuardChild(array $search=array()){
		
	    //get the learner ID to search for
	    $l_userID = isset($search['learner']) ? $search['learner'] : FALSE;
		 //assign search value user_id
	    $user_id = isset($search['guardian']) ? $search['guardian'] : FALSE ;
	    //search whre the user is search variable
	    if ($user_id) {
	        $this->db->where('gUser.id',$user_id);
	    }

	    //search subjects by learner
	    if ($l_userID) {
	        $this->db->where('learnerguardian.learnerID',$l_userID);
	    }

	    return $this->db->select("learner.id as learnerID, learner.userID as lUserID, lUser.firstName as lFName,lUser.middleName as lMName,lUser.lastName as lLName, lUser.email as lEmail, lUser.phone as lPhone, learnerguardian.guardianID as guardID, learnerguardian.relationshipID as gRelationID, relationship.description as howRelated, guardian.userID as gUserID, gUser.firstName as gFName, gUser.lastName as gLName, gUser.email as gEmail, gUser.phone as gPhone, classgrouplevel.levelID as levelID, level.level as level, classgrouplevel.limit as classLimit , classgrouplevel.classGroupID as cgID, classgroup.className as cgName,filesID as filesID, files.filePath as filePath")  
					    ->from("learnerguardian")
					    ->join('learner','learnerguardian.learnerID=learner.id')
					    ->join("classgrouplevel", "classgrouplevel.id = learner.classGroupLevelID ")
					    ->join("classgroup", "classgroup.id = classgrouplevel.classGroupID")
						->join("level", "level.id = classgrouplevel.levelID")
					    ->join('user as lUser','lUser.id=learner.userID')
					    ->join('relationship','relationship.id=learnerguardian.relationshipID')
					    ->join('guardian','guardian.id=learnerguardian.guardianID')
					    ->join('user as gUser','gUser.id=guardian.userID')
					    ->join('profile','profile.userID = gUser.id' )
					    ->join('files','files.id = profile.filesID' )
					    ->where('guardian.deleted',0);
					    //->get()->result();


	}

	public function getGuardChild(array $search=array())
	{
		$this->qryGuardChild($search);
		return $this->db->get()->result();
	}

	public function countGuardChild(array $search=array())
	{
		$this->qryGuardChild($search);
		return $this->db->count_all_results();
	}


//add parent/guardian to the system
	public function addGuardian($userId)
	{
		$this->db->trans_start();
		$this->db->insert("guardian",array('userID'=>$userId));
        $guardianID = $this->db->insert_id();
        $this->db->trans_complete();
		return $guardianID;
	}

	//delete parent/guardian from the system
	public function deleteGuardian($id_user)
	{   
		$deleted = 1;
	   $this->db->trans_start();
	   $this->db->where('userID',$id_user)
	            ->update('guardian',array('deleted'=>$deleted));
	  return $this->db->trans_complete();

	}
}