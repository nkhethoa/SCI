<?php
 defined('BASEPATH') OR exit('No direct script access allowed');

class Assignment_model extends CI_Model{
 public function __construct(){
 	parent:: __construct();
 	$this->load->database();
 }
public function getAssignSubmission(array $search=array()){
	
    //assign subject value to subject
	$subjectName = isset($search['subject']) ? $search['subject'] : FALSE;
	//get the assignment of the subject for user
	$assignID = isset($search['assign']) ? $search['assign'] : FALSE;
    //get the teacher ID to search for
    $tUser_id = isset($search['teacher']) ? $search['teacher'] : FALSE;
    //get the learner ID to search for
    $lUser_id = isset($search['learner']) ? $search['learner'] : FALSE;
    //get the value of learner only
    $lID = isset($search['learnOnly']) ? $search['learnOnly'] : FALSE;
    //assign the value of class group level
    $cglID = isset($search['cglID']) ? $search['cglID'] : FALSE;

    //search assignment by ID
	if ($assignID) {
		$this->db->where('assignsubmission.assignmentID',$assignID);
	}
	//search for the learner who submitted the assignment
	if ($lID) {
        $this->db->where('assignsubmission.learnerID',$lID);
		$this->db->group_by('assignsubmission.assignmentID');
	}
    //search subjects by learner
    if ($lUser_id) {
        $this->db->where('user.id',$lUser_id);
    }

    //search subjects by learner
    if ($cglID) {
        $this->db->where('cgl.id',$cglID);
    } 
    //get the current year submissions only
        $this->db->where('assignsubmission.date >',date('Y').'-01-01');

 	return	$this->db->select("assignsubmission.id as submissionID, assignsubmission.date as submittedDate, assignsubmission.learnerID as lID, learner.userID as lUserID, assignsubmission.assignmentID as assignID, assignsubmission.submitted as submitStatus, assignsubmission.deleted as submitDeleted, user.firstName as lFName, user.lastName as lLName, assignsubmission.filesID as fileID, files.fileName as fileName, files.fileSize as fileSize, files.fileType as fileType, files.filePath as filePath, learner.classGroupLevelID as cglID, learner.startdate as learn_startDate, cgl.levelID as levelID, cgl.classGroupID as cgID, cgl.limit as limit, classgroup.className as cgName, assignment.title as assignTitle, assignment.description as assignDesc, assignment.dueDate as dueDate, assignment.publishDate as publishDate, assignment.classLevelSubjectsID as clsID, classlevelsubjects.subjectID as subjectID, subject.name as subjectName ")
 					->from("assignsubmission")
 					->join("assignment","assignment.id = assignsubmission.assignmentID")
 					->join("files", "files.id = assignsubmission.filesID")
 					->join("learner", "learner.id = assignsubmission.learnerID")
 					->join("classgrouplevel as cgl", "cgl.id = learner.classGroupLevelID")
 					->join("level", "level.id = cgl.levelID")
 					->join("classgroup", "classgroup.id = cgl.classGroupID")
 					->join("classlevelsubjects", "classlevelsubjects.id = assignment.classLevelSubjectsID")
 					->join("subject", "subject.id = classlevelsubjects.subjectID")
 					->join("user", "user.id = learner.userID")
                    ->where('assignsubmission.deleted',0)
                    //->where('assignsubmission.submitted',1)
				 	->get()->result();
 
 }

/**
 * [resetSubmission reset submission of learner assignment
 * @param  int $assignID specific assignment to be reset
 * @param  int $lID learner with reset request      
 * @return bool
 */
public function resetSubmission($assignID,$lID)
{
    $this->db->trans_start();//start transaction
        $this->db->where('assignsubmission.assignmentID',$assignID)
                    ->where('assignsubmission.learnerID',$lID)
                    ->update("assignsubmission",array('submitted'=>0));
    return $this->db->trans_complete(); //close transacion
}

/**
 * [submitAssignment learner submit assignment here
 * @param  [array] $data     learner assignment data to be written
 * @param  [array] $fileData file to be inserted 
 * @return [bool]   
 */
public function submitAssignment($data,$fileData)
{
    $this->db->trans_start();//start transaction
    //call the method to insert user file record
    $filesID=$this->files->insertFile($fileData);
    //assign userID and search among learners
    $search['learner'] = $_SESSION['userID'];
    //get learnerID from learners table
    $learnerID=$this->learners->getLearners($search);
    //populate array with contents
    $assignment=array(
        'assignmentID'=>$data['aid'],
        'learnerID'=>$learnerID[0]->learnerID,
        'filesID'=>$filesID,
        );
    //write file to db
    $this->db->insert("assignsubmission",$assignment);
    return $this->db->trans_complete(); //close transacion

}

public function updateSubmission($data,$fileData)
{
    $this->db->trans_start();//start transaction
    //update the previous file the user uploaded
    $fID = $data['f'];
    //update file contents before writing file
    $fileUpdate = $this->files->updateFile($fileData,$fID);
    //populate array with contents
    $assignment=array(
        'assignmentID'=>$data['aid'],
        'learnerID'=>$_SESSION['userID'],
        'filesID'=>$fID,
        'submitted'=>1
        );

    $this->db->where('assignsubmission.assignID',$data['assignID'])
    		->where('assignsubmission.learnerID',$_SESSION['userID'])
            ->update("assignsubmission",$assignment);
    return $this->db->trans_complete(); //close transacion
}

/**
 * [assignExist method checks if the assignment exist on the db
 * @param  [int] $assignID [the assignment being sought]
 * @return [bool]           [true or false depending on the outcome]
 */
public function assignExist($assignID)
{
    $qryAssign= (array)$this->db->select('id')
                                ->from('assignment')
                                ->where('id',$assignID)
                                ->get()->row();

    if(($qryAssign!=NULL) && ($qryAssign['id']==$assignID)){
        return True;
    }else{
        return FALSE;
    }
}


public function queryAssignments(array $search=array()){
	//assign subject value to subject
	$subjectName = isset($search['subject']) ? $search['subject'] : FALSE;
	//get the assignment of the subject for user
	$assignID = isset($search['assign']) ? $search['assign'] : FALSE;
	//get the cls of the subject for user
    $clsID = isset($search['clsID']) ? $search['clsID'] : FALSE;
    //get the teacher ID to search for
    $tUser_id = isset($search['teacher']) ? $search['teacher'] : FALSE;
    //get the learner ID to search for
    $lUser_id = isset($search['learner']) ? $search['learner'] : FALSE;
    //search subjects by subject
    if ($subjectName) {
        //$this->db->where('subject.name',$subjectName);
    }
    //search subjects by teacher
    if ($tUser_id) {
        $this->db->where('ut.id',$tUser_id);
    }
    //search assignment by ID
	if ($assignID) {
		$this->db->where('assignment.id',$assignID);
	}
    //search subjects by learner
    if ($lUser_id) {
        $this->db->where('ul.id',$lUser_id);
    }
    //search subjects by classLevelSubjects
    if ($clsID){
        $this->db->where('assignment.classLevelSubjectsID',$clsID);
    }
    //get the current year assignmrnts only
    $this->db->where('assignment.publishDate >',date('Y').'-01-01');

    return  $this->db->select("assignment.id as assignID, assignment.title as assignTitle, assignment.description as assignDesc, assignment.publishDate as publishDate, assignment.dueDate as dueDate, assignment.filesID as fileID, files.fileName as fileName,files.fileSize as fileSize, files.fileType as fileType, files.filePath as filePath, assignment.classLevelSubjectsID as clsID, classlevelsubjects.subjectID as subjectID,classlevelsubjects.teacherID as teacherID, classlevelsubjects.classGroupLevelID as cglID, classgrouplevel.levelID as levelID,level.level as levelDesc,classgrouplevel.limit as classLimit,classgrouplevel.classGroupID as cgID, classgroup.className as cgClassName, subject.name as subjectName,teacher.userID as tUserID, ut.firstName as tFirstName, ut.lastName as tLastName, learner.id as lID, learner.userID as lUserID, ul.firstName as lFirstName, ul.lastName as lFastName")
                    ->from("assignment")
                    ->join("files", "files.id = assignment.filesID")
                    ->join("classlevelsubjects", "classlevelsubjects.id = assignment.classLevelSubjectsID")
                    ->join("classgrouplevel", "classgrouplevel.id = classlevelsubjects.classGroupLevelID")
                    ->join("subject", "subject.id = classlevelsubjects.subjectID")
                    ->join("classgroup", "classgroup.id = classgrouplevel.classGroupID")
                    ->join("learner", "learner.classGroupLevelID = classgrouplevel.id")
            		->join("teacher", "teacher.id = classlevelsubjects.teacherID")
                    ->join("level", "level.id = classgrouplevel.levelID")
                    ->join("user as ut", "ut.id = teacher.UserID")
                    ->join("user as ul", "ul.id = learner.UserID")
                    ->where('assignment.deleted',0)
                    ->group_by('assignment.id');
                    //->order_by("assignment.classLevelSubjectsID");

 }
public function getAssignments(array $search=array())
{
	$this->queryAssignments($search);
	return $this->db->get()->result();
}

public function countAssignments(array $search=array())
{
	$this->queryAssignments($search);
	return $this->db->count_all_results();
}

/**
 * deleteAssigment will be used to delete study material based on the id
 * supplied
 * @param  [int] $studyID holds the id of the record the user wants to delete
 * @return [bool]          [true if success, false for failure]
 */
public function deleteAssigment($assignID)
{
	return $this->db->where('assignment.id',$assignID)
            	->update("assignment",array('deleted'=>1));

}
/**
 * [updateMaterial description]
 * @param  [type] $data     [description]
 * @param  [type] $fileData [description]
 * @return [type]           [description]
 */
public function updateAssignment($data,$fileData)
{
	$this->db->trans_start();//start transaction
	//update the previous file the user uploaded
	$fID = $data['fileID'];
	//update file contents before writing file
	$fileUpdate = $this->files->updateFile($fileData,$fID);
	//populate array with contents
	$assignment=array(
		'title'=>$data['title'],
		'description'=>$data['description'],
		'dueDate'=>$data['dueDate'],
		'classLevelSubjectsID'=>$data['cls'],
		'filesID'=>$data['fileID'],
		);

	$this->db->where('assignment.id',$data['assignID'])
        	->update("assignment",$assignment);
	return $this->db->trans_complete(); //close transacion
}
/**
 * [addMaterial description]
 * @param [type] $data     [description]
 * @param [type] $fileData [description]
 */
public function addAssignment($data,$fileData)
{
	$this->db->trans_start();//start transaction
	//call the method to insert user file record
	$filesID=$this->files->insertFile($fileData);
	//populate array with contents
	$assignment=array(
		'title'=>$data['title'],
		'description'=>$data['description'],
		'dueDate'=>$data['dueDate'],
		'classLevelSubjectsID'=>$data['cls'],
		'filesID'=>$filesID,
		);
	//write file to db
    $this->db->insert("assignment",$assignment);
    return $this->db->trans_complete(); //close transacion

}



}//end of model Assignment


