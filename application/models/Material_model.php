<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material_model extends CI_Model{
	public function __construct(){
		$this->load->database();
		
	}

	public function queryStudyMaterial(array $search=array()){
		//assign subject value to subject
		$subjectName = isset($search['subject']) ? $search['subject'] : FALSE;
		//assign search array value to studyID
		$studyID = isset($search['study']) ? $search['study'] : FALSE;
		//get the cls of the subject for user
	    $clsID = isset($search['clsID']) ? $search['clsID'] : FALSE;
		//get the teacher ID to search for
	    $tUser_id = isset($search['teacher']) ? $search['teacher'] : FALSE;
	    //get the learner ID to search for
	    $lUser_id = isset($search['learner']) ? $search['learner'] : FALSE;
	    //search progress by tearcher
	    if ($tUser_id) {
	        $this->db->where('ut.id',$tUser_id);
	    }
	    //search progress by learner
	    if ($lUser_id) {
	        $this->db->where('ul.id',$lUser_id);
	    }
	    //search for study material by ID
		if ($studyID) {
			$this->db->where('studymaterial.id',$studyID);
		}
	    //search for study material by class level subjects
	 	if($clsID){
	 		$this->db->where('studymaterial.classLevelSubjectsID',$clsID);
	 	}

	   return  $this->db->select("studymaterial.id as studyID, studymaterial.description as materialDesc, studymaterial.title as studyTitle, studymaterial.date as publishDate, studymaterial.filesID as fileID, files.fileName as fileName, files.fileSize as fileSize, files.fileType as fileType, files.filePath as filePath,studymaterial.classLevelSubjectsID as clsID, classlevelsubjects.subjectID as subjectID, subject.name as subjectName, classlevelsubjects.classGroupLevelID as cglID, classgrouplevel.levelID as levelID, level.level as levelDesc, classgrouplevel.limit as classLimit, classgrouplevel.classGroupID as cgID, classgroup.className as cgName," )
	            ->from("studymaterial")
	            ->join("files", "files.id = studymaterial.filesID")
	            ->join("classlevelsubjects", "classlevelsubjects.id = studymaterial.classLevelSubjectsID")
	            ->join("classgrouplevel", "classgrouplevel.id = classlevelsubjects.classGroupLevelID")
	            ->join("subject", "subject.id = classlevelsubjects.subjectID")
	            ->join("classgroup", "classgroup.id = classgrouplevel.classGroupID")
	            ->join("learner", "learner.classGroupLevelID = classgrouplevel.id")
	            ->join("teacher", "teacher.id = classlevelsubjects.teacherID")
	            ->join("level", "level.id = classgrouplevel.levelID")
	            ->join("user as ut", "ut.id = teacher.UserID")
	            ->join("user as ul", "ul.id = learner.UserID")
	            ->where('studymaterial.deleted',0)
	            ->order_by("studymaterial.id")
	            ->group_by("studymaterial.id");

	 }

	public function getStudyMaterial(array $search=array())
	{
		$this->queryStudyMaterial($search);
		return $this->db->get()->result();
	}

	public function countMaterial(array $search=array())
	{
		$this->queryStudyMaterial($search);
		return $this->db->count_all_results();
	}
	/**
	 * deleteMaterial will be used to delete study material based on the id
	 * supplied
	 * @param  [int] $studyID holds the id of the record the user wants to delete
	 * @return [bool]          [true if success, false for failure]
	 */
	public function deleteMaterial($studyID)
	{
		return $this->db->where('studymaterial.id',$studyID)
	            	->update("studymaterial",array('deleted'=>1));
	}
	/**
	 * [updateMaterial description]
	 * @param  [array] $data     [description]
	 * @param  [array] $fileData [description]
	 * @return [type]           [description]
	 */
	public function updateMaterial($data,$fileData)
	{
		$this->db->trans_start();//start transaction
		//update the previous file the user uploaded
		$fID = $data['fileID'];
		$fileUpdate = $this->files->updateFile($fileData,$fID);
		//populate array with contents
		$material=array(
			'title'=>$data['title'],
			'description'=>$data['description'],
			'classLevelSubjectsID'=>$data['cls'],
			'filesID'=>$data['fileID'],
			);

		$this->db->where('studymaterial.id',$data['studyID'])
	        	->update("studymaterial",$material);
		return $this->db->trans_complete(); //close transacion
	}
	/**
	 * [addMaterial description]
	 * @param [type] $data     [description]
	 * @param [type] $fileData [description]
	 */
	public function addMaterial($data,$fileData)
	{
		$this->db->trans_start();//start transaction
		//call the method to insert user file record
		$filesID=$this->files->insertFile($fileData);
		//populate array with contents
		$material=array(
			'title'=>$data['title'],
			'description'=>$data['description'],
			'classLevelSubjectsID'=>$data['cls'],
			'filesID'=>$filesID,
					);
		//write file to db
	    $this->db->insert("studymaterial",$material);
	    return $this->db->trans_complete(); //close transacion

	}

	/**
	 * [material_exist method will be used to check if the study material exist on the db
	 * @param  [type] $studyID [number or id of the study material being confirmed]
	 * @return [bool]            [boolean to give indication]
	 */
	public function material_exist($studyID)
	{
	    $qryStudy= (array)$this->db->select('id')
	                                ->from('studymaterial')
	                                ->where('id',$studyID)
	                                ->get()->row();

	    if(($qryStudy!=NULL) && ($qryStudy['id']==$studyID)){
	        return TRUE;
	    }else{
	        return FALSE;
	    }
	}


}//end of class