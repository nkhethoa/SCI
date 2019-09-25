<?php
 defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends CI_Model{
 public function __construct(){
 	parent:: __construct();
 	$this->load->database();
 }
 public function getProfile(array $search=array())
    {
    	//Array search to a variable
    	$profileID = isset($search['profile']) ? $search['profile'] : FALSE;

		if ($profileID) {
			$this->db->where('profile.userID',$profileID);
		}
        return $this->db->select("profile.id as proID,profile.lastName as lName,profile.middleName as midName, profile.firstName as fName, profile.phone as phone, profile.email as email,profile.bio as bio, profile.address as address, profile.userID as userID,profile.filesID as filesID,files.fileName as fileName,fileSize as fileSize,fileType as fileType,filePath as filePath, tempName as tempName")  
        ->from("profile")
        ->join('user','profile.userID=user.id')
        ->join('files','files.id=profile.filesID')
//        ->where('user.id',$profileID)
        ->get()->result();

}


public function addProfile($data,$filesID,$id_user)
	{
         $add = array(
                "firstName"=>$data['firstName'],
				"middleName"=>$data['middleName'],
				"lastName"=>$data['lastName'],
				"email"=>$data['email'],
				"phone"=>$data['phone'],
				"address"=>$data['address'],
                "filesID"=>$filesID,
                "userID"=>$id_user,
         );

         $this->db->trans_start();
         $this->db->insert("profile",$add);
         return $this->db->trans_complete();
	}
public function updateProfile($data,$uploadFile)
	{
			$filesID = $data['fileID'];
			if($uploadFile){
				$fileStatus=$this->files->updateFile($uploadFile,$filesID);
			}
		   	

          	$profile = array(
				"firstName"=>$data['firstName'],
				"middleName"=>$data['middleName'],
				"lastName"=>$data['lastName'],
				"email"=>$data['email'],
				"phone"=>$data['phone'],
				"address"=>$data['address'],
				"bio"=>$data['bio'],
				"filesID"=>$filesID
			);
        $this->db->trans_start();
		$this->db->where('profile.userID',$_SESSION['userID'])
				 ->update('profile',$profile);
		return $this->db->trans_complete();

	}

	
}