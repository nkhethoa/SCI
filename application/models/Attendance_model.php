<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * summary
 */
class Attendance_model extends CI_MODEL
{
public function __construct()
{
        parent::__construct();
}

public function queryAttendance(array $search=array())
{
        //learners attendance by subject
        $subjName = isset($search['subject'])  ? $search['subject'] :  FALSE;
        $user_id = isset($search['user'])  ? $search['user'] :  FALSE;
        $cls = isset($search['clsID'])  ? $search['clsID'] :  FALSE;
        $today = isset($search['today'])  ? $search['today'] :  FALSE;
        $lID = isset($search['learner']) ?  $search['learner'] : FALSE;
        $t_uID = isset($search['teacher']) ?  $search['teacher'] : FALSE;
        $endDate = isset($search['end']) ?  $search['end'] : FALSE;
        $startDate = isset($search['start']) ?  $search['start'] : FALSE;

        if($startDate AND $endDate){
            $this->db->where('startDate BETWEEN',$startDate AND $endDate);
        }

        if ($subjName) {
            $this->db->where('subject.name',$subjName);
        }
        if ($user_id) {
            $this->db->where('ut.id',$user_id);
        }
        if ($cls) {
            $this->db->where('attendance.classLevelSubjectsID',$cls);
            $this->db->group_by('attendance.date');
        }
        if ($today) {//

            $this->db->like('attendance.date',$today , 'after'); 
            //$this->db->where('',);
            $this->db->group_by('attendance.learnerID');
        }

        if($t_uID){
            $this->db->where('teacher.userID',$t_uID);
        }
        if($lID){
            $this->db->where('attendance.learnerID',$lID);
        }
       $this->db->where('attendance.date >',date('Y').'-01-01 00:00:00');

        return  $this->db->select(" attendance.id as attendID, attendance.date as attendDate,  attendance.status as attendStatus, attendance.learnerID as learnerID, attendance.classLevelSubjectsID as clsID, classlevelsubjects.subjectID as subjectID, subject.name as subjectName,learner.userID as lUserID, user.firstName as lFName, user.lastName as lLName,  classlevelsubjects.classGroupLevelID as cglID, classlevelsubjects.beginDate as cglBeginDate, classgrouplevel.levelID as levelID, classgrouplevel.classGroupID as cgID, classgrouplevel.limit as classLimit, classgroup.className cgName , level.level as level,classlevelsubjects.teacherID as teacherID, ut.id as tUserID, ut.firstName as tFName, ut.lastName as tLName")
                        ->from("attendance")
                        ->join('learner','learner.id=attendance.learnerID')
                        ->join('classlevelsubjects','classlevelsubjects.id=attendance.classLevelSubjectsID')
                        ->join("subject", "subject.id = classlevelsubjects.subjectID")
                        ->join("classlevelsubjects as cls", "cls.id = subject.id")
                        ->join("classgrouplevel", "classgrouplevel.id = classlevelsubjects.classGroupLevelID")
                        ->join("classgroup", "classgroup.id = classgrouplevel.classGroupID")
                        ->join("user", "user.id = learner.UserID")
                        ->join('teacher','teacher.id=classlevelsubjects.teacherID')
                        ->join('user as ut','ut.id=teacher.userID')
                        ->join("level", "level.id = classgrouplevel.levelID")
                        ->where('attendance.deleted',0)
                        ->where('user.deleted',0);
                        //->group_by('learnerID')
 }

 public function getAttendance(array $search=array())
{
        $this->queryAttendance($search);
        return $this->db->get()->result();
}

public function countAttendance(array $search=array())
{
        $this->queryAttendance($search);
        return $this->db->count_all_results();
}
        
/**
 * [insertLearners description]
 * @param  [type] $subjLearners [description]
 * @return [type]               [description]
 */
public function insertLearners($subjLearners,$clsID)
{
        //start transaction
        $this->db->trans_start();
        //loop thru list of learner for the subject to create new record
        foreach ($subjLearners as $learner) {
                $learnData=array(
                        'learnerID'=>$learner->learnerID,
                        'classLevelSubjectsID'=>$clsID,
                        'date'=>date('Y-m-d H:i:s'),
                                );
                $this->db->insert("attendance",$learnData);
        }
        return $this->db->trans_complete(); //close transacion
}//insertLearners ends

/**
 * [markAttendance method will receive the status of the attendance per learner
 * Look for that record on the DB and update it
 * @param  [array] $attend [details of attendance per learner]
 * @return [type]         [description]
 */
public function markAttendance($attend)
{   
    //start transaction
    $this->db->trans_start();
    //start update query
    $this->db->where('attendance.learnerID',$attend['lID'])
                    ->where('attendance.id',$attend['rowID'])
                    ->where('attendance.classLevelSubjectsID',$attend['clsID'])
                    ->update("attendance",array('status'=>$attend['status']));
    return $this->db->trans_complete(); //close transacion
}

} //end of class
