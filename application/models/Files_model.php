<?php
 defined('BASEPATH') OR exit('No direct script access allowed');

class Files_model extends CI_Model{
 public function __construct(){
 	parent:: __construct();
 	$this->load->database();
 }
 public function getFile(array $search=array())
{
	//assign the value of the temporary profile pic
	$tempPic = isset($search['profile']) ? $search['profile'] : false;
	//assign the value for the pic to be downloaded
	$fileToDown = isset($search['download']) ? $search['download'] : false;
	//get the picture for the profile
	if ($tempPic) {
		$this->db->where('files.id',$tempPic);
	}

	//get the file the user wants to download
	if ($fileToDown) {
		$this->db->where('files.id',$fileToDown);
	}
	//return the query results
	return $this->db->select("files.id as filesID, files.fileName as fileName,files.fileSize as fileSize,files.fileType as fileType,files.filePath as filePath, files.tempName as tempName")  
        ->from("files")
        //->where('files.id',1)
        ->get()->result();

}

/**
 * [updateFile description]
 * @param  [type] $fileData [description]
 * @param  [type] $fileID   [description]
 * @return [type]           [description]
 */
public function updateFile($fileData,$fileID)
{
	$fileData=array(
		'fileName'=>$fileData['fileName'],
		'fileSize'=>$fileData['fileSize'],
		'fileType'=>$fileData['fileType'],
		'filePath'=>$fileData['filePath'],
		'tempName'=>$fileData['tempName'],
				);
	return $this->db->where('files.id',$fileID)
             ->update("files",$fileData);

}
/**
 * [insertFile description]
 * @param  [type] $fileData [description]
 * @return [type]           [description]
 */
public function insertFile($fileData)
{
	$fileData=array(
		'fileName'=>$fileData['fileName'],
		'fileSize'=>$fileData['fileSize'],
		'fileType'=>$fileData['fileType'],
		'filePath'=>$fileData['filePath'],
		'tempName'=>$fileData['tempName'],
				);
    $this->db->insert("files",$fileData);
    $fileID = $this->db->insert_id();
    return $fileID;
}

/**
 * [uploadFile description]
 * @return [type] [description]
 */
public function uploadFile()	{
	//get the file properties
	$fileName = $_FILES['fileUpload']['name'];
	$fileType = $_FILES['fileUpload']['type'];
	$fileSize = $_FILES['fileUpload']['size'];
	$newName='';

	$uploads_dir= './assets/files';   
	$info = pathinfo($_FILES['fileUpload']['name']); 
   //returns the file extension 
	if(isset($info['extension'] ) && $info['extension']){
		$ext = $info['extension']; 
	}else{
		$ext = "";
	}
	 //generates a random name with the uniquid function
	$new_random_file_name = uniqid(); 
    //writes the new name an the file extension
	$newName=$new_random_file_name.'.'.$ext; 
	move_uploaded_file($_FILES['fileUpload']['tmp_name'],"$uploads_dir/$newName");
	$uploads = array(
				'tempName'=> $newName,
				'fileSize'=> $fileSize,
				'fileName'=> $fileName,
				'fileType'=> $fileType,
				'filePath'=> $uploads_dir.'/'.$newName,
				'error'=>$_FILES['fileUpload']['error']
			);
			
	return $uploads;
	
}//end upload file


}//end of files model