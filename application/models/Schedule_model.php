<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_model extends CI_Model{

    public function __construct(){
     	parent:: __construct();
     	$this->load->database();
    }

    public function scheduleQuery(array $search=array()){
     	$daysID = isset($search['days']) ? $search['days'] :  FALSE;
     	//get the teacher ID to search for
        $tUser_id = isset($search['teacher']) ? $search['teacher'] : FALSE;
        //get the learner ID to search for
        $lUser_id = isset($search['learner']) ? $search['learner'] : FALSE;
        //search subjects by teacher
        if ($tUser_id) {
            $this->db->where('weeklyclassschedule.userID',$tUser_id);
        }
        //search subjects by learner
        if ($lUser_id) {
            $this->db->where('weeklyclassschedule.userID',$lUser_id);
        }

     	if ($daysID){
            $this->db->where('weekdays.id',$daysID);
        }

        return $this->db->select("weeklyclassschedule.id as wcsID,weeklyclassschedule.weekdaysID as wdID,	weekdays.name as wdName, weekdays.deleted as wdDeleted, schoolschedule.id as what_timeID,schoolschedule.start_time as class_startTime,schoolschedule.end_time as class_endTime, weeklyclassschedule.classLevelSubjectsID as clsID, classlevelsubjects.subjectID as subjectID, subject.name as subjectName, classlevelsubjects.classGroupLevelID as cglID, classgrouplevel.levelID as levelID, level.level as levelDesc, learner.userID as l_userID, classgrouplevel.limit as classLimit, classgrouplevel.classGroupID as cgID, classgroup.className as cgName, classlevelsubjects.teacherID as teacherID, teacher.userID as tUserID")
        				->from('weeklyclassschedule')
        				->join('weekdays','weekdays.id = weeklyclassschedule.weekdaysID')
        				->join('schoolschedule', 'schoolschedule.id = weeklyclassschedule.schoolScheduleID')
        				->join("classlevelsubjects", "classlevelsubjects.id = weeklyclassschedule.classLevelSubjectsID")
    		            ->join("classgrouplevel", "classgrouplevel.id = classlevelsubjects.classGroupLevelID")
    		            ->join("subject", "subject.id = classlevelsubjects.subjectID")
    		            ->join("classgroup", "classgroup.id = classgrouplevel.classGroupID")
    		            ->join("learner", "learner.classGroupLevelID = classgrouplevel.id")
    		            ->join("teacher", "teacher.id = classlevelsubjects.teacherID")
    		            ->join("level", "level.id = classgrouplevel.levelID")
    		            ->join("user as ut", "ut.id = teacher.UserID")
    		            ->join("user as ul", "ul.id = learner.UserID")
        				->where('weeklyclassschedule.deleted',0)
        				->group_by('weeklyclassschedule.id');
     }

    public function getSchedule(array $search=array()){
            $this->scheduleQuery($search);
            return $this->db->get()->result();
    }

    /**
     * [getWeekDays days of the week based on school schedule
     */
    public function getWeekDays(array $search=array()){
     	$daysID = isset($search['days']) ? $search['days'] : FALSE;
     	if ($daysID){
            $this->db->where('weekdays.id',$daysID);
        }

        return $this->db->select("weekdays.id as wdID,weekdays.name as wdName,weekdays.deleted as wdDeleted")
        	->from('weekdays')
        	->where('weekdays.deleted',0)
        	->group_by('weekdays.id')
        	->get()->result();
     }

    /**
     * [getWeektime daily time for the school from morning to school out
     */
    public function getWeektime(array $search=array()){
     	$timesID = isset($search['days']) ? $search['days'] : FALSE;
     	if ($timesID){
            $this->db->where('schoolschedule.id',$timesID);
        }

        return $this->db->select("schoolschedule.id as what_timeID,schoolschedule.start_time as class_startTime,schoolschedule.end_time as class_endTime")
        	->from('schoolschedule')
        	//->where('schoolschedule.deleted',0)
        	->group_by('schoolschedule.id')
        	->get()->result();
     }
     /**
      * addClassSchedule method will be used to add subject to the user timetable
      * 
      */
    public function addClassSchedule($data){
    		$class_time= array(
    				'userID' => $_SESSION['userID'],
    				'weekdaysID' =>$data['what_wdid'],
    				'schoolScheduleID' =>$data['what_timeid'],
    				'classLevelSubjectsID' =>$data['tt_sbj_cls_id']);
    	$this->db->trans_start();
    	$this->db->insert('weeklyclassschedule',$class_time);
    	$class_timeID = $this->db->insert_id();
    	if($this->db->trans_complete()){
    		return $class_timeID;
    	}else{
    		return FALSE;
    	}
    }

    /**
     * delete_class_time method will be used to update deleted field to 1 
     * the process will have been initiated by the user to remove the time on school schedule
     */
    public function delete_class_time($class_timeID){
        $this->db->trans_start();
        $this->db->where('weeklyclassschedule.id',$class_timeID)
                 ->update('weeklyclassschedule',array('deleted'=>1));
        return $this->db->trans_complete();
    }

    /**
     * remove_subjects method will UPDATE the deleted field to 1
     * this will happen when the user wants to remove the subject from the timetable
     */
    public function remove_subjects($del_data){
		$this->db->trans_start();
		$this->db->where('weeklyclassschedule.userID',$_SESSION['userID'])
                 ->where('weeklyclassschedule.weekdaysID',$del_data['weekdayID'])
                 ->where('weeklyclassschedule.schoolScheduleID',$del_data['timeID'])
                 ->where('weeklyclassschedule.classLevelSubjectsID',$del_data['clsID'])
				 ->update('weeklyclassschedule',array('deleted'=>1));
		return $this->db->trans_complete();
	}



}//end model
