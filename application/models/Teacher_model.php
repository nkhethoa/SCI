<?php
 defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher_model extends CI_Model{
 public function __construct(){
 	parent:: __construct();
 	$this->load->database();
 }

    public function queryTeacher(array $search=array())
    {
        //assign the id of teacher to variable
        $user_id = isset($search['user']) ? $search['user']: FALSE;
        //assign the teacher search value to variable find 
        $find = isset($search['teachers']) ? $search['teachers']: FALSE;
        //to search by teacher ID
        $t_ID = isset($search['teachID']) ? $search['teachID']: FALSE;
        //search teacher by names
        if($find){
            $where = '(user.firstname LIKE "%'.$find.'%" || 
            user.middlename LIKE "%'.$find.'%" ||
            user.id LIKE "%'.$find.'%" ||
            user.lastname LIKE "%'.$find.'%")';
            
            $this->db->where($where);
          }
        //search teacher by USERID  
        if ($user_id) {
            $this->db->where('teacher.userID',$user_id);
        }
        //search teacher by teacher id  
        if ($t_ID) {
            $this->db->where('teacher.id',$t_ID);
        }
        //get the current year teachers only
        //$this->db->where('teacher.startDate >',date('Y').'-01-01');

        return $this->db->select("teacher.id as teacherID, teacher.userID as t_userID, user.lastName as tLName, user.middleName as tMidName, user.firstName as tFName, user.phone as tPhone, user.email as tEmail,user.id as userID,profile.filesID as filesID,files.fileName as fileName,fileSize as fileSize,fileType as fileType,filePath as filePath, tempName as tempName") 
                    ->from("teacher")
                    ->join('user', 'user.id = teacher.userID')
                    ->join('profile','profile.userID=user.id')
                    ->join('files','files.id=profile.filesID')
                    ->where('user.deleted',0)
                    ->where('teacher.deleted',0)
                    ->order_by('teacher.id','DESC');
                    //->get()->result();
    }


     public function getTeacher(array $search=array())
    {
            $this->queryTeacher($search);
            return $this->db->get()->result();
    }

    public function countTeachers(array $search=array())
    {
            $this->queryTeacher($search);
            return $this->db->count_all_results();
    }


    //add teacher to the system
    public function addTeacher($userId)
    {
        $teacher = array(
               "startDate"=> date('Y-m-d'),
               "userID"=> $userId
           );
        $this->db->trans_start();
        //write user data
        $this->db->insert("teacher",$teacher);
        //the last insert teacher ID
        $teacherID = $this->db->insert_id();
        $this->db->trans_complete();
        //return the ID of the new teacher
        return $teacherID;
    }

    //delete teacher from the system
    public function deleteTeacher($id_user)
    {   
      //array with data
        $teacher = array('deleted'=>1,
                    'endDate'=>date('Y-m-d')
                  );
      $this->db->trans_start();
      //assign the search array with user ID
      $search['user'] = $id_user;
      //call method to check the teacher id
      $exist_teacher = $this->getTeacher($search);

      $this->db->where('userID',$id_user)
                ->update('teacher',$teacher);

      $search['teacher'] = $exist_teacher[0]->teacherID;
      //get subjects which were assigned to the teacher
      $cls = $this->level_group->countClassLevelSubjects($search);
      if ($cls > 0) {
        //use the ID to call delete teacher subject method
        $this->deleteTeacherSubjects($exist_teacher[0]->teacherID);
      }
      
      return $this->db->trans_complete();

    }

    /**
     * [deleteTeacherSubjects method is called when the teacher is being deleted
     * @param  int    $id_teacher is the ID of the teacher to delete
     * @return bool    for True ot False
     */
    public function deleteTeacherSubjects($id_teacher)
    {   
      //array with data
        $teacher = array('deleted'=>1,
                    'endDate'=>date('Y-m-d')
                  );
       $this->db->trans_start();
       $this->db->where('teacherID',$id_teacher)
                ->update('classlevelsubjects',$teacher);
      return $this->db->trans_complete();

    }
      //unassign class Teacher 
    public function deleteClassTeacher( $teacherID)
    {  
        $teacher = array(
               "endDate"=> date('Y-m-d'),
               "deleted"=> 1
           );

        $this->db->trans_start();
        $this->db->where('teacherresponsibleclass.teacherID',$teacherID)
                ->update('teacherresponsibleclass',$teacher);
        return $this->db->trans_complete();

    }

    //update assign teacher class
    public function updateAssignedClass($data, $cglID)
    {  
         $updateClass = array(
            "teacherID"=>$data['teacherID'], 
            "classGroupLevelID"=>$cglID, 
            "startDate"=>$data['date'], 
            "deleted"=> 0, 
         );
        $this->db->trans_start();
        $this->db->where('teacherresponsibleclass.teacherID',$data['teacherID'])
                ->update('teacherresponsibleclass',$updateClass);
      return $this->db->trans_complete();

    }
            //create assignedClass
    public function assignedClass($data,$cglID)
    {
        $insert_class = array(
            "teacherID"=>$data['teacherID'], 
            "classGroupLevelID"=>$cglID, 
            "startDate"=>$data['date'], 
         );
        $this->db->trans_start();
        $this->db->insert('teacherresponsibleclass',$insert_class);
      return $this->db->trans_complete();
    } 

    public function getClassTeacherClass(array $search=array())
    {
       $tID = isset($search['teacherID']) ? $search['teacherID'] : FALSE;
       if($tID){
           $this->db->where('teacherresponsibleclass.teacherID',$tID);
       }
       //Tell me the classes the teacher is responsible of and their levels
       return $this->db->select('teacherresponsibleclass.teacherID as teacherID, teacherresponsibleclass.classGroupLevelID as cglID, teacherresponsibleclass.startDate as startDate, teacher.userID as tUserID,user.firstName as tFName, user.lastName as tLName, classgrouplevel.levelID as levelID, classgrouplevel.classGroupID, classgrouplevel.limit as classLimit, classgroup.className,level.level as level')
                   ->from('teacherresponsibleclass')
                   ->join('teacher','teacher.id = teacherresponsibleclass.teacherID')
                   ->join('classgrouplevel','classgrouplevel.id = teacherresponsibleclass.classGroupLevelID')
                   ->join('user','user.id = teacher.userID')
                   ->join('level','level.id = classgrouplevel.levelID')
                   ->join("classgroup", "classgroup.id = classgrouplevel.classGroupID")
                   ->where('teacherresponsibleclass.deleted',0)
                   ->where('user.deleted',0)
                   ->order_by('teacherresponsibleclass.id','DESC')
                   ->get()->result();
       
    }

    /**
     * [insert_cls this method is responsible for inserting teacher subjects into class level subjects
     * @param  string $ts         comma separated string holding the values of subject, level and group
     * @param  int    $teacherID  the new teacher who has just been inserted
     * @return bool               boolean based on insert
     */
    public function insert_cls($ts,$teacherID)
    { 
        //start with the transaction
        $this->db->trans_start();

        //loop thru teacher subjects
        foreach ($ts as $value) {
          //array to hold teacher subjects after explode
          $teacher_subjects = array();
          //explode each selected subject to extract subject ID, level and group
          $teacher_subjects = explode(',', $value); 
          //load array search with level and groud to find class group level ID
          $search['level'] = $teacher_subjects[1];
          $search['group'] = $teacher_subjects[2];
          //get class group level ID
          $cgl = $this->level_group->getclassGroupLevel($search);
            //put data to be written inside an array
          if ($cgl!= NULL) {
            $cls_data = array(
                "teacherID"=>$teacherID,
                "subjectID"=>$teacher_subjects[0],
                "classGroupLevelID"=>$cgl[0]->cglID, 
                "beginDate"=>date('Y-m-d'),  
             );
            //insert into the table class level subjects
            $this->db->insert('classlevelsubjects',$cls_data);
          }
             
        }
        //return transaction status when all is done
        return $this->db->trans_complete();
    }

    public function teacher_exist($t_ID)
    {
      //assign search arrya with the value of the teacher
      $search['teachID'] = $t_ID;
      //call method to go check if the teacher exist
      $t_found = $this->getTeacher($search);
      //check if the teacher was found
      if ($t_found != NULL) {
        //return true if found
        return TRUE;
      }else {
        //otherwise return false
        return FALSE;
      }
    }
    /**
     * [subjects_digitsOnly a call_back to check if someone didn't fiddle with selected subject
     * if yes, reject the whole list and request page refresh
     * @param  array  $teacher_subjects an array with numbers representing selected options
     * @return boolean True if nothing what fakely changed, false otherwise
     */
    public function subjects_digitsOnly($teacher_subjects)
    { 
        //break options into arrays
        $subj = explode(',', $teacher_subjects);
        //go thru array after explode
        for ($i = 0; $i < count($subj); $i++) {
          //check if elements of array are numbers only
          //and bigger than 0
          if (!is_numeric($subj[$i]) && $subj[$i] < 1) {
            //return false
            return FALSE;
          }//end if natural
        }//end for loop

        //otherwise all is OK
        return TRUE;
    }

}//close teacher model

