<?php
 defined('BASEPATH') OR exit('No direct script access allowed');

class Learner_model extends CI_Model{
 public function __construct(){
    parent:: __construct();
    $this->load->database();
 }


//get learner from the database
    public function qryLearners(array $search=array())
    {

        //assign the user id from array search to a variable
        $userID= isset($search['user']) ? $search['user'] : FALSE;
        $lID = isset($search['learner']) ? $search['learner'] : FALSE;
        $cgl = isset($search['cglID']) ? $search['cglID'] : FALSE;

        if ($cgl) {
            $this->db->where('learner.classGroupLevelID',$cgl);
        }

        if ($userID) {
            $this->db->where('learner.userID',$userID);
        }

        if($lID){
            $this->db->where('learner.userID',$lID);
        }

        //get the current year learners only
        $this->db->where('learner.startdate >',date('Y').'-01-01');

         return $this->db->select("user.id as l_uID,user.lastName as lName,user.middleName as midName,user.firstName as fName, user.phone as phone, user.address as address,user.email as email, learner.id as learnerID, learner.learnerNumber as learnDoE_ID, learner.startdate as academicYear, classgroup.id as cgID, classgroup.className as cgName, classgrouplevel.id as cglID,level.level as level, user.id as userID, profile.filesID as filesID,files.fileName as fileName,fileSize as fileSize,fileType as fileType,filePath as filePath ")   
                        ->from("learner")
                        ->join('user', 'user.id=learner.userID')
                        ->join('profile','profile.userID=user.id')
                        ->join('files','files.id=profile.filesID')
                        ->join("classgrouplevel", "classgrouplevel.id = learner.classGroupLevelID")
                        ->join('level', 'level.id=classgrouplevel.levelID')
                        ->join("classgroup", "classgroup.id = classgrouplevel.classGroupID")
                        ->where('learner.deleted',0)
                        ->where('user.deleted',0)
                        //->order_by('learner.id')
                        ->order_by('learner.startdate','DESC');

    }

     public function getLearners(array $search=array())
    {
            $this->qryLearners($search);
            return $this->db->get()->result();
    }

    public function countLearners(array $search=array())
    {
            $this->qryLearners($search);
            return $this->db->count_all_results();
    }

    //add learner to the system
    public function addLearner(array $data)
    {
        $learner = array(
            "userID"=>$data['idUser'], 
            "classGroupLevelID"=>$data['cglID'],
            "startDate"=> date('Y-m-d'),
            "learnerNumber"=> $data['doeLearnNo'],
        );
        $this->db->trans_start();
        $this->db->insert("learner",$learner);
        $learnerID = $this->db->insert_id();
        $this->db->trans_complete();
        return $learnerID;
    }


    //delete Learner from the system
    public function deleteLearner($id_user)
    {   
        $deleted = 1;
        $this->db->trans_start();
        $this->db->where('userID',$id_user)
                ->update('learner',array('deleted'=>$deleted));
        return $this->db->trans_complete();

    }
    
    public function add_learn_guard(int $id_luser, int $guardianID, int $howRelated)
    {
        $lg = array(
            "guardianID"=>$guardianID, 
            "learnerID"=>$id_luser,
            "relationshipID"=>$howRelated
        );
     $this->db->trans_start();
     $this->db->insert("learnerGuardian",$lg);
     return $this->db->trans_complete(); 
    }

    /**
     * [learner_exist method will be used to check if the learner exist on the db
     * @param  [type] $learnerID [number or id of the learner being confirmed]
     * @return [bool]            [boolean to give indication]
     */
    public function learner_exist($learnerID)
    {
        $qrylearner= (array)$this->db->select('id')
                                    ->from('learner')
                                    ->where('id',$learnerID)
                                    ->get()->row();

        if(($qrylearner!=NULL) && ($qrylearner['id']==$learnerID)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
}//end of model