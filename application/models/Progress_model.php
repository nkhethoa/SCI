<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * summary
 */
    class Progress_model extends CI_MODEL
    {
    public function __construct()
    {
            parent::__construct();
            $this->load->database();
    }
    /**
     * [getAssessmentType description]
     * @return [type] [description]
     */
    public function getAssessmentType(array $search=array())
    {
        $type = isset($search['type']) ? $search['type'] : FALSE;
        if($type){
            $this->db->where('assessmentype.id',$type);
        }
        return $this->db->where('assessmentype.deleted',0)
                    ->get('assessmentype')
                    ->result();
    }

    public function getAssessment(array $search=array())
    {
        $clsID = isset($search['clsID']) ? $search['clsID'] : FALSE;
        //search progress based on classLevelSubjectsID
        if ($clsID) {
            $this->db->where('assesstypeprogress.classLevelSubjectsID',$clsID);
        }
        return $this->db->where('assesstypeprogress.deleted',0)
                    ->get('assesstypeprogress')
                    ->result();
    }
    /**
     * [queryProgress description]
     * @param  array  $search [description]
     * @return [type]         [description]
     */
    public function queryProgress(array $search=array())
    {//learners progress by subject
        $subjName = isset($search['subject']) ? $search['subject'] : FALSE;
        //get the teacher ID to search for
        $tUser_id = isset($search['teacher']) ? $search['teacher'] : FALSE;
        //get the learner ID to search for
        $learnerID = isset($search['learner']) ? $search['learner'] : FALSE;
        //progress by clsID
        $cls = isset($search['clsID']) ? $search['clsID'] : FALSE;
        $cglID = isset($search['cglID']) ? $search['cglID'] : FALSE;
        //get marks by term
        $term = isset($search['date']) ? $search['date'] : FALSE;
        //get learner record to edit
        $record = isset($search['record']) ? $search['record'] :FALSE;
        //get learner record to edit
        $assessType = isset($search['assessment']) ? $search['assessment'] :FALSE;

        //search progress by subject
        if ($subjName) {
                $this->db->where('subject.name',$subjName);
        }
        //search progress by userID
        if ($tUser_id) {
                $this->db->where('ut.id',$tUser_id);
        }
        //search progress by userID
        if ($learnerID) {
            $this->db->where('progress.learnerID',$learnerID);
        }
        //search progress based on classLevelSubjectsID
        if ($cls) {
            $this->db->where('progress.classLevelSubjectsID',$cls);
            //s$this->db->group_by('progress.learnerID');
        }
        //search by quarter
        if ($term) {
                $this->db->where('progress.schoolQuarterID',$term); 
        }
        //search by id
       if ($record) {
                $this->db->where('progress.id',$record); 
        }
         //search by assessmenTypeID
       if ($assessType) {
                $this->db->where('progress.assessTypeProgressID',$assessType); 
        }
        $this->db->where('progress.date >',date('Y').'-01-01 00:00:00');
        return  $this->db->select(" progress.id as progressID, progress.date as progressDate, progress.progress as marks, progress.schoolQuarterID as quarterID, schoolquarter.description as quater, progress.learnerID as learnerID, progress.classLevelSubjectsID as clsID, progress.assessTypeProgressID, classlevelsubjects.subjectID as subjectID, subject.name as subjectName,learner.userID as lUserID, ul.firstName as lFName, ul.lastName as lLName, classlevelsubjects.classGroupLevelID as cglID, classlevelsubjects.beginDate as cglBeginDate, classgrouplevel.levelID as levelID, classgrouplevel.classGroupID as cgID, classgrouplevel.limit as classLimit, classgroup.className cgName ,level.level as level,classlevelsubjects.teacherID as teacherID, ut.firstName as tFName, ut.lastName as tLName, assessmentype.description as assessmenType, assesstypeprogress.weight, assesstypeprogress.description as assessProgressDescription")
                    ->from("progress")
                    ->join("classlevelsubjects", "classlevelsubjects.id = progress.classLevelSubjectsID")
                    ->join("learner", "learner.id = progress.learnerID")
                    ->join("assesstypeprogress", "assesstypeprogress.id = progress.assessTypeProgressID")
                    ->join("assessmentype", "assessmentype.id = assesstypeprogress.assessmentTypeID")
                    ->join("subject", "subject.id = classlevelsubjects.subjectID")
                    ->join("classlevelsubjects as cgl", "cgl.id = subject.id")
                    ->join("schoolquarter", "schoolquarter.id = progress.schoolQuarterID")
                    ->join("classgrouplevel", "classgrouplevel.id = classlevelsubjects.classGroupLevelID")
                    ->join("classgroup", "classgroup.id = classgrouplevel.classGroupID")
                    ->join("user as ul", "ul.id = learner.UserID")
                    ->join('teacher','teacher.id=classlevelsubjects.teacherID')
                    ->join('user as ut','ut.id=teacher.userID')
                    ->join("level", "level.id = classgrouplevel.levelID")
                    ->where('progress.deleted',0);
                    //->order_by('learnerID','ASC');
                    //->group_by('learnerID');
     }

     public function getProgress(array $search=array())
    {
            $this->queryProgress($search);
            return $this->db->get()->result();
    }

    public function countProgress(array $search=array())
    {
            $this->queryProgress($search);
            return $this->db->count_all_results();
    }
      
    /**
     * [insertLearners description]
     * @param  [type] $subjLearners [description]
     * @return [type]               [description]
     */
    public function insertLearners($subjLearners,$whichAssess,$clsID)
    {
        //start transaction
        $this->db->trans_start();
        //determine which quarter we in
        $term = $this->whichQuarter();
        //loop thru list of learner for the subject to create new record
        foreach ($subjLearners as $learner) {
            //load data to be written
            $learnData=array(
                    'schoolQuarterID'=>$term,
                    'date'=>date('Y-m-d'),
                    'learnerID'=>$learner->learnerID,
                    'progress'=>0,
                    'assessTypeProgressID'=>$whichAssess,
                    'classLevelSubjectsID'=>$clsID
                            );
            $this->db->insert("progress",$learnData);
        }
            return $this->db->trans_complete(); //close transacion
    }//insertLearners ends

    function whichQuarter()
    {
        //extract month from date
        $month = date('n');
        //check if we in the first quater
        if ($month > 0 && $month < 4) {
            return 1;
        }
        //check if we in the second quater 
        if (($month > 3) && ($month < 7)) {
            return 2;
        }
        //check if we in the third quater
        if (($month > 6) && ($month < 10)) {
            return 3;
        }
        //otherwise we in the last fourth quater
        return 4;

    }
    /**
     * validateMarks method responsible for checking if the marks are with acceptable limits
     * @param  [array] $marks [it has the mark as entered into input field]
     * @return [bool]        [tell if all went well or not]
     */
    public function validateMarks($marks)
    {  
        if ((is_numeric($marks[3])) && ($marks[3] >= 0) && ($marks[3] < 101)) {
            return true;  
        }else { 
            return false;
        }
    }//end validateMarks

    /**
     * [updateProgress description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function updateProgress($marks)
    {
        return $this->db->where('progress.id',$marks[0]) //index [0] progressID
            ->where('progress.classlevelsubjectsID',$marks[1]) //index [1] clsID
            ->where('progress.learnerID',$marks[2]) //index [2] LearnerID
            ->update('progress',array('progress'=>$marks[3])); ////index [3] marks to be updated   

    }//end of updateProgress
    /**
     * [new_assess_details method will create new assessment before the marks can be entered into system
     * The assessment ID as entered will be returned back to the caller
     * 
     * @param  [array] $data [details or data needed to create new assessment]
     * @return [int]  $assessID     [assessment ID]
     */
    public function new_assess_details($data){
        //assign assessment type to the search array
        $search['type'] = $data['whichAssess'];
        //get assessment based on the type selected
        $assessments = $this->getAssessmentType($search);
        //populate array with data to insert into the table
        $newAssess = array(
            'classLevelSubjectsID' =>$data['clsID_assess_number'],
            'assessmentTypeID' =>$data['whichAssess'],
            'weight' =>$data['assess_weight'],
            'description' =>$assessments[0]->description.' '.$data['assess_number']
        );
        //start transaction
        $this->db->trans_start();
        //insert data into table
        $this->db->insert('assesstypeprogress',$newAssess);
        $assessID = $this->db->insert_id();
        $this->db->trans_complete();
        //return transaction status
        return $assessID;
    }

    public function assessment_type_exist($assessment_typeID)
    {
        $qryAssessmentType= (array)$this->db->select('id')
                                ->from('classlevelsubjects')
                                ->where('id',$assessment_typeID)
                                ->get()->row();

        if(($qryAssessmentType!=NULL) && ($qryAssessmentType['id']==$assessment_typeID)){
            return True;
        }else{
            return FALSE;
        }
    }
} //end of Progress Model
