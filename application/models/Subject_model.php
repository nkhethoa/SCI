<?php
 defined('BASEPATH') OR exit('No direct script access allowed');

class Subject_model extends CI_Model{
 public function __construct(){
 	parent:: __construct();
 	$this->load->database();
 }

public function qrySubject(array $search = array()){
    //get the cls of the subject for user
    $clsID = isset($search['clsID']) ? $search['clsID'] : FALSE;
    //
    $subjectName = isset($search['subject']) ? $search['subject'] : FALSE;
    $subj_id = isset($search['subject_id']) ? $search['subject_id'] : FALSE;
    //get the teacher ID to search for
    $tUser_id = isset($search['teacher']) ? $search['teacher'] : FALSE;
    //get the learner ID to search for
    $lUser_id = isset($search['learner']) ? $search['learner'] : FALSE;
    //search subjects by tearcher
    if ($tUser_id) {
        $this->db->where('ut.id',$tUser_id);
    }
    //search subjects by learner
    if ($lUser_id) {
        $this->db->where('ul.id',$lUser_id);
    }
    //search subjects by classLevelSubjects
    if ($clsID){
        $this->db->where('classlevelsubjects.id',$clsID);
    }
    //search subjects by classLevelSubjects
    if ($subjectName){
        $this->db->where('subject.name',$subjectName);
    }
     //search subjects by classLevelSubjects
    if ($subj_id){
        $this->db->where('subject.id',$subj_id);
    }
    return  $this->db->select("subject.id, classlevelsubjects.subjectID as subjectID, subject.name as subjectName, classlevelsubjects.classGroupLevelID as cglID, classlevelsubjects.id as clsID, classgrouplevel.levelID as levelID, level.level as level, classgrouplevel.limit as classLimit , classgrouplevel.classGroupID as cgID, classgroup.className as cgName, classlevelsubjects.teacherID as teacherID, teacher.userID as tUserID, ut.firstName as tFName, ut.lastName as tLName, learner.userID as lUserID, ul.firstName as lFName, ul.lastName as lLName, learner.id as learnerID")
                    ->from("subject")
                    ->join("classlevelsubjects", "classlevelsubjects.subjectID = subject.id")
                    ->join("classgrouplevel", "classgrouplevel.id = classlevelsubjects.classGroupLevelID")
                    ->join("classgroup", "classgroup.id = classgrouplevel.classGroupID")
                    ->join("learner", "learner.classGroupLevelID = classgrouplevel.id")
                    ->join("teacher", "teacher.id = classlevelsubjects.teacherID")
                    ->join("level", "level.id = classgrouplevel.levelID")
                    ->join("user as ut", "ut.id = teacher.UserID")
                    ->join("user as ul", "ul.id = learner.UserID")
                    ->where('subject.deleted',0)
                    ->where('teacher.deleted',0)
                    ->where('learner.deleted',0)
                    ->where('classlevelsubjects.deleted',0)
                    //->order_by("subject.name")
                    //->order_by("classLevelSubjects.id")
                    ->group_by('subject.id')
                    ->group_by('level.id')
                    ->group_by('classgroup.id');

 }

    public function getSubject(array $search=array())
    {
            $this->qrySubject($search);
            return $this->db->get()->result();
    }

    public function countSubject(array $search=array())
    {
            $this->qrySubject($search);
            return $this->db->count_all_results();
    }



     public function qryschoolSubjects(array $search=array()){

        return  $this->db->select("subject.id as subjID, subject.name as subjectName")
            ->from("subject")
            ->where('subject.deleted',0)
            ->order_by("subject.name")
            ->group_by('subject.id');
     }

    public function getschoolSubjects(array $search=array())
    {
            $this->qryschoolSubjects($search);
            return $this->db->get()->result();
    }

    public function countSchoolSubjects(array $search=array())
    {
            $this->qryschoolSubjects($search);
            return $this->db->count_all_results();
    }

    //create Subject
    public function createSubject($data)
    {
         $addSubject = array(
            "name"=>$data['subjectName'], 
         );

         $this->db->trans_start();
         $this->db->insert("subject",$addSubject);
         return $this->db->trans_complete();
    }
    
    //edit/update Subject name on the system
    public function updateSubject($data)
    {
        
            $EditSubject = array(
            "name"=>$data['subjectName'], 
         );

         $this->db->trans_start();
         $this->db->where('subject.id',$data['subjectId'])
         ->update("subject",$EditSubject);
         return $this->db->trans_complete();

    }
    
    public function deleteSubject($id_user)
    {  
        $deleted = 1;
       $this->db->trans_start();
       $this->db->where('id',$id_user)
                ->update('subject',array('deleted'=>$deleted));
      return $this->db->trans_complete();

    }

    /**
     * [subject_exist method will check if the provided subjects already exist 
     * on the subject table
     * @param  int    $s_ID    it has the subject id
     * @return bool       boolean
     */
    public function subject_exist($s_ID)
    {
        //load search array with subject iD
      $search['subject_id'] = $s_ID;
      //call method to check the subject
      $s_found = $this->getSubject($search);
      //check if method returned anything
      if ($s_found != NULL) {
        return TRUE;
      }else {
        return FALSE;
      }
    }
}//close teacher model

