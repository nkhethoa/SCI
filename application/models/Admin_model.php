<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * summary
 */
class Admin_model extends CI_MODEL
{
	public function __construct()
	{
		parent::__construct();
       
	}
	 public function qryAdmin(array $search=array()){
    //assign the user id from array search to a variable
    $userID = isset($search['user']) ? $search['user'] : FALSE;
    //search for the user by date
    $user_by_date = isset($search['user_by_date']) ? $search['user_by_date'] : FALSE;
    if ($user_by_date) {
      $this->db->like('admin.startDate',$user_by_date);
    }
    if ($userID) {
      $this->db->where('admin.userID',$userID);
    }
    
    return $this->db->select("user.id as a_uID, user.lastName as aLName, user.middleName as aMidName, user.firstName as aFName, user.phone as aPhone, user.email as aEmail,admin.id as adminID, admin.startDate as startDate, admin.endDate as endDate,user.id as userID,profile.filesID as filesID,files.fileName as fileName,fileSize as fileSize,fileType as fileType,filePath as filePath, tempName as tempName")  
          ->from("admin")
          ->join('user', 'user.id = admin.userID')
          ->join('profile','profile.userID=user.id')
          ->join('files','files.id=profile.filesID')
          ->where('admin.deleted',0)
          ->where('user.deleted',0)
          ->order_by('admin.id','DESC');
          //->get()->result();

    }

    public function getAdmin(array $search=array())
  {
    $this->qryAdmin($search);
    return $this->db->get()->result();
  }

  public function countAdmin(array $search=array())
  {
    $this->qryAdmin($search);
    return $this->db->count_all_results();
  }

  //add Admin to the system
  public function addAdmin($userId)
  {
    
    $admin = array(
               "startDate"=> date('Y-m-d'),
               "userID"=> $userId

       );
    $this->db->trans_start();
    $this->db->insert("admin",$admin);
    return $this->db->trans_complete();
  }
  
  //delete Administrator from the system
  public function deleteAdmin( $id_user)
  {  
    //data array to be updated
    $admin = array('deleted'=>1,
                    'endDate'=>date('Y-m-d')
                  );
     $this->db->trans_start();
     $this->db->where('userID',$id_user)
              ->update('admin',$admin);
    return $this->db->trans_complete();

  }

 public function getschoolQuarters(){

    return  $this->db->select("schoolquarter.id as quarterID, schoolquarter.description as quarterName")
        ->from("schoolquarter")
        ->where('schoolquarter.deleted',0)
        //->join("classGroupLevel ", "classGroupLevel.id = teacherResponsibleClass.classGroupLevelID")
        ->get()->result();
 }

 //Get Assessment Types
 public function getAssessmentTypes(){

    return  $this->db->select("assessmentype.id as assessID, assessmentype.description as assessName")
        ->from("assessmentype")
        ->where('assessmentype.deleted',0)
        //->join("classGroupLevel ", "classGroupLevel.id = teacherResponsibleClass.classGroupLevelID")
        ->get()->result();
 }

  public function queryTask(array $search = array()){

    //assign search variable for active todo list
    $user_todo= isset($search['user_todo']) ? $search['user_todo'] : FALSE;
    //assign search variables for all deleted todos
    $todo_history= isset($search['deleted']) ? $search['deleted'] : FALSE;

    if($user_todo)
    {
        $this->db->where('todolist.userID',$user_todo);
        $this->db->where('todolist.deleted',0);
    }
    if ($todo_history) {
      $this->db->where('todolist.userID',$todo_history);
        $this->db->where('todolist.deleted',1);
        $this->db->where('todolist.complete',1);
        $this->db->where('todolist.historyRemove',0);
    }
    return  $this->db->select("todolist.id as todolistID, todolist.description as descriptionName, todolist.userID as userID,todolist.complete as completed,todolist.completedDate")
        ->from("todolist")
        ->join('user', 'user.id = todolist.userID')
        ->join('admin', 'user.id = admin.userID')
        //->where('todolist.deleted',0)
        ->where('user.deleted',0)
        ->where('admin.deleted',0)
        ->order_by('todolist.id', 'DESC')
        ->order_by('todolist.userID');
        
 }
  public function countTasks(array $search = array()){
        $this->queryTask($search);
        return $this->db->count_all_results();
    }
     public function getTasks(array $search = array()){
        $this->queryTask($search);
        return $this->db->get()->result();
    }

	 public function cleanHistory($todoID){
    $this->db->trans_start();
    $trashHistory = explode(',',$todoID);
      echo '<pre>';var_dump($trashHistory); echo '<pre>';
    for ($i = 0; $i < count($trashHistory) ; $i++){

      $this->db->where('todolist.id',$trashHistory[$i])
               //->where('toUserID',$_SESSION['userID'])
               ->update("todolist",array("CleanHistory"=>1));
                //->insert("todolist",array("CleanHistory"=>1));
              
              echo '<pre>';var_dump($trashHistory[$i]); echo '<pre>';
        }

   return $this->db->trans_complete();
     //return $this->db->get()->result();
 }
	
		//create Quarter
	public function createQuarter($data)
	{
         $addQuarter = array(
            "description"=>$data['quarterName'], 
         );

         $this->db->trans_start();
         $this->db->insert("schoolquarter",$addQuarter);
         return $this->db->trans_complete();
	}
	//edit/update Quarter name on the system
	public function updateQuarter($data)
	{
		
          	$EditQuarter = array(
            "description"=>$data['quarterName'], 
         );

         $this->db->trans_start();
         $this->db->where('schoolquarter.id',$data['Quartid'])
         ->update("schoolquarter",$EditQuarter);
         return $this->db->trans_complete();

	}

	  //create Assessment Type
	public function createAssessType($data)
	{
         $addAssessType = array(
            "description"=>$data['assessName'], 
         );

         $this->db->trans_start();
         $this->db->insert("assessmentype",$addAssessType);
         return $this->db->trans_complete();
	}
	//edit/update Assessment type on the system
	public function updateAssessType($data)
	{
		
          	$editAssessType = array(
            "description"=>$data['assessName'], 
         );

         $this->db->trans_start();
         $this->db->where('assessmentype.id',$data['assessID'])
         ->update("assessmentype",$editAssessType);
         return $this->db->trans_complete();

	}

	
	
      //create todoList
    public function createTodoList($data)
    {
         $addTodoList = array(
            "description"=>$data['sTaskDescription'], 
            "userID"=>$_SESSION['userID'], 
         );

         $this->db->trans_start();
         $this->db->insert("todolist",$addTodoList);
         return $this->db->trans_complete();
    }
  

	
	
	
	//delete user from the system
	
	//delete School Quarter from the system
	public function deleteQuarter($id_user)
	{  
		$deleted = 1;
	   $this->db->trans_start();
	   $this->db->where('id',$id_user)
	            ->update('schoolquarter',array('deleted'=>$deleted));
	  return $this->db->trans_complete();

	}

	//delete Assessment Type from the system
	public function deleteAssessmentType($id_user)
	{  
		$deleted = 1;
	   $this->db->trans_start();
	   $this->db->where('id',$id_user)
	            ->update('assessmentype',array('deleted'=>$deleted));
	  return $this->db->trans_complete();

	}
	
    //delete todo from the system
    public function deleteTodo( $id_todo)
    {  
      $deleted = 1;
      $this->db->trans_start();
      $this->db->where('id',$id_todo)
                ->update('todolist',array('deleted'=>$deleted));
      return $this->db->trans_complete();

    }
     //complete todo from the system
    public function completeTodo( $id_todo)
    {  
        $completed = array(
             'complete'=>1,   
             'completedDate'=>date('Y-m-d H:m:s')   
        );

       $this->db->trans_start();
       $this->db->where('id',$id_todo)
                ->update('todolist',$completed);
      return $this->db->trans_complete();

    }


    /************Delete Class Group Level****************/
    //delete Administrator from the system
    public function deleteClassGroupLevel( $cglID)
    {  
      //data array to be updated
      $cgl = array(
                      
                      'deleted'=>1,
                      
                  );
       $this->db->trans_start();
       $this->db->where('id',$cglID)
                ->update('classgrouplevel',$cgl);
      return $this->db->trans_complete();

    }
    /************End of delete class group level****************/



/****************Topics****************/
 /*public function getGeneraltopics(){

    return  $this->db->select("generaltopic.id as gtID, generaltopic.title as gtitle,generaltopic.description as gtDesc,generaltopic.topicDate as gtdate")
        ->from("generaltopic")
        ->where('generaltopic.deleted',0)
        //->join("classGroupLevel ", "classGroupLevel.id = teacherResponsibleClass.classGroupLevelID")
        ->get()->result();
 }*/

 public function getTopics($search=array()){
  $topicMainID = isset($search['topicMain']) ? $search['topicMain'] : FALSE;
  if($topicMainID){
    $this->db->where('generaltopic.id',$topicMainID);
  }

  return  $this->db->select("generaltopic.id as gtID,generaltopic.title as gtTitle,generaltopic.description as gtDescr,generaltopic.topicDate,generaltopic.creatorID as topicCreator,user.firstName as creatorName,user.lastName as creatorSurname,generaltopic.deleted as deleted,profile.id as prflUserID, profile.firstName as prflFName,profile.lastName as prflLName, profile.filesID as prflCreatorPic,profile.deleted as prflDeleted,files.id as fileID,files.fileName,files.filePath,files.deleted as fileDeleted")
    ->from('generaltopic')
    ->join('admin','admin.id = generaltopic.creatorID')
    ->join('user','user.id = admin.userID')
    ->join('profile','profile.userID = user.id')
    ->join('files','files.id = profile.filesID')
    ->where('generaltopic.deleted',0);
  }

public function getTopiyycsQuery($params = array(),array $search=array())
{
  $this->getTopics($search);
  //to establish the limits for the pagination
  if(array_key_exists("start", $params) && array_key_exists("limit", $params)){
    $this->db->limit($params['limit'],$params['start']);
  }elseif(!array_key_exists("start", $params) && array_key_exists("limit", $params)){
    $this->db->limit($params['limit']);
  }
  $out = $this->db->get()->result();
  return (!empty($out))?$out:FALSE;
}


/****************End of topics****************/

 //Remove todo from todo history 
  public function removeTodo($id_todo)
  {  
    //data array to be updated
    $history = array('historyRemove'=>1,
                  );
     $this->db->trans_start();
     $this->db->where('id',$id_todo)
              ->update('todolist',$history);
    return $this->db->trans_complete();

  }

} //end of class 