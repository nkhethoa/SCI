<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Level_Group_model extends CI_Model{
 public function __construct(){
 	parent:: __construct();
 	$this->load->database();
 }
 


     //Get ClassLevels
    public function getLevels(array $search =array()){
        $levelID = isset($search['levelID']) ? $search['levelID'] : FALSE;
        //check if this level exist
        if ($levelID) {
            $this->db->where('level.id',$levelID);
        }
        return  $this->db->select("level.id as levelID, level.level as levelName")
            ->from("level")
            ->where('level.deleted',0)
            ->get()->result();
    }
 
 //create Class Level
    public function createLevel($data)
    {
         $addLevel = array(
            "level"=>$data['levelName'], 
         );

         $this->db->trans_start();
         $this->db->insert("level",$addLevel);
         return $this->db->trans_complete();
    }

        //edit/update Level name on the system
    public function updateLevel($data)
    {
        
            $editLevel = array(
            "level"=>$data['levelName'], 
         );

         $this->db->trans_start();
         $this->db->where('level.id',$data['levID'])
         ->update("level",$editLevel);
         return $this->db->trans_complete();

    }

        //delete Class Level from the system
    public function deleteLevel( $id_user)
    {  
        $deleted = 1;
       $this->db->trans_start();
       $this->db->where('id',$id_user)
                ->update('level',array('deleted'=>$deleted));
      return $this->db->trans_complete();

    }
    /**
     * [getLevelSbj on which level is the subject
     * @param  array  $search [description]
     * @return [type]         [description]
     */
    public function qryClassLevel(array $search=array()){

        return  $this->db->select("classgrouplevel.id as cglID, classgrouplevel.levelID as levelID, classgrouplevel.limit as classLimit, level.level as levelName")
            ->from("classgrouplevel")
            ->join('level','level.id =  classgrouplevel.levelID')
            ->where('level.deleted',0)
            ->group_by('classgrouplevel.levelID');
     }
    public function getclassLevel(array $search=array())
    {
        $this->qryClassLevel($search);
        return $this->db->get()->result();
    }

    public function countClassLevel(array $search=array())
    {
        $this->qryClassLevel($search);
        return $this->db->count_all_results();
    }

    /**
     * [getGroupSbj each level group
     * @param  array  $search [description]
     * @return [type]         [description]
     */
     public function qryGroup(array $search=array()){

     	$sbjID = isset($search['group']) ? $search['group'] : FALSE;
     	if ($sbjID){
            $this->db->where('classgroup.id',$sbjID);
        }

         return  $this->db->select("classgroup.id as groupID, classgroup.className as classGroupName")
            ->from("classgroup")
            ->where('classgroup.deleted',0);
    }
    public function getGroup(array $search=array())
    {
        $this->qryGroup($search);
        return $this->db->get()->result();
    }

    public function countGroup(array $search=array())
    {
        $this->qryGroup($search);
        return $this->db->count_all_results();
    }

    public function createClassGroup($data)
    {
         $addClassGroup = array(
            "className"=>$data['classGroupName'], 
         );

         $this->db->trans_start();
         $this->db->insert("classgroup",$addClassGroup);
         return $this->db->trans_complete();
    }
    //edit/update Assessment type on the system
    public function updateClassGroup(array $data)
    {
        
            $editClassGroup = array(
            "className"=>$data['classGroupName'], 
         );

         $this->db->trans_start();
         $this->db->where('classgroup.id',$data['groupID'])
         ->update("classgroup",$editClassGroup);
         return $this->db->trans_complete();

    }
    //delete Assessment Type from the system
    public function deleteClassGroup($id_user)
    {  
        $deleted = 1;
       $this->db->trans_start();
       $this->db->where('id',$id_user)
                ->update('classgroup',array('deleted'=>$deleted));
      return $this->db->trans_complete();

    }
    public function getclassGroupLevel(array $search = array()){
        $levelID= isset($search['level']) ?$search['level'] : FALSE;
        $cgID= isset($search['group']) ?$search['group'] : FALSE;
        if($levelID)
        {
            $this->db->where('classgrouplevel.levelID',$levelID);
        }
        if($cgID)
        {
            $this->db->where('classgrouplevel.classGroupID',$cgID);
         }    
               return  $this->db->select("classgrouplevel.id as cglID,classgrouplevel.levelID as levelID, classgrouplevel.classGroupID as cgID, classgrouplevel.limit as classLimit, classgroup.className as classGroupName,level.level as level")
            ->from("classgrouplevel")
            ->join("classgroup", "classgroup.id = classgrouplevel.classGroupID")
            ->join("level", "level.id = classgrouplevel.levelID")
            ->where('classgrouplevel.deleted',0)
            ->get()->result();
    }

   //Get Class Groups
 public function getclassGroups(){

    return  $this->db->select("classgroup.id as groupID, classgroup.className as classGroupName")
        ->from("classgroup")
        ->join("classgrouplevel", "classgroup.id = classgrouplevel.classGroupID")
        ->group_by('classgrouplevel.classGroupID')
        ->where('classgroup.deleted',0)
        ->get()->result();
 }

    public function qryClasslevelsubjects(array $search=array()){
        $clsID= isset($search['cls']) ? $search['cls'] : FALSE;
        $teacherID= isset($search['teacher']) ? $search['teacher'] : FALSE;
        if($clsID)
        {
            $this->db->where('classlevelsubjects.id',$clsID);
        }
        if($teacherID)
        {
            $this->db->where('classlevelsubjects.teacherID',$teacherID);
        }


        return  $this->db->select("classlevelsubjects.id as clsID, classlevelsubjects.subjectID, classlevelsubjects.classgrouplevelID, classlevelsubjects.beginDate, classlevelsubjects.endDate")
            ->from("classlevelsubjects")
            ->join('subject','subject.id =  classlevelsubjects.subjectID')
            ->join('teacher','teacher.id =  classlevelsubjects.teacherID')
            ->join('classgrouplevel','classgrouplevel.id =  classlevelsubjects.classgrouplevelID')
            ->where('classlevelsubjects.deleted',0)
            ->order_by('classlevelsubjects.id','DESC');
     }
    public function getClasslevelsubjects(array $search=array())
    {
        $this->qryClasslevelsubjects($search);
        return $this->db->get()->result();
    }

    public function countClasslevelsubjects(array $search=array())
    {
        $this->qryClasslevelsubjects($search);
        return $this->db->count_all_results();
    }

    /**
     * [addClassGroupLevel method will create a new class group level
     * 
     * @param [array] $data [data needed for adding classgrouplevel]
     */
    public function addClassGroupLevel($data)
    {
         $addClassGroup = array(
            "levelID"=>$data['levelID'], 
            "classGroupID"=>$data['groupID'], 
            "limit"=>$data['limit'], 
         );

         $this->db->trans_start();
         $this->db->insert("classgrouplevel",$addClassGroup);
         return $this->db->trans_complete();
    }
    public function editClassGroupLevel($data)
    {
        $editClassGroup = array(
            "levelID"=>$data['levelID'], 
            "classGroupID"=>$data['groupID'], 
            "limit"=>$data['limit'], 
         );

         $this->db->trans_start();
         $this->db->where('classgrouplevel.id',$data['cglid']);
         $this->db->update("classgrouplevel",$editClassGroup);
         return $this->db->trans_complete();
    }
    public function verify_level($levelID)
    {
        $search['levelID'] = $levelID;
        $lvl = (array)$this->level_group->getLevels($search);
        if (isset($lvl) && $lvl['levelID'] == $levelID) {
           return TRUE; 
        }
        return FALSE;
    }

    /**
 * [cls_exist method checks if the class level subject exist on the db
 * @param  [int] $clsID [the assignment being sought]
 * @return [bool]           [true or false depending on the outcome]
 */
public function cls_exist($clsID)
{
    $qryCls= (array)$this->db->select('id')
                                ->from('classlevelsubjects')
                                ->where('id',$clsID)
                                ->get()->row();

    if(($qryCls!=NULL) && ($qryCls['id']==$clsID)){
        return True;
    }else{
        return FALSE;
    }
}


}
