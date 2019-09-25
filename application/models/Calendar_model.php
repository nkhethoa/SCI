<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
 
class Calendar_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}
	//code to test the calendar model
	/**
	 * [get_apps brings the users data ]
	 * @return [obj]           [with the users data]
	 */
	public function getEventQuery(array $search = array()){
		 //$eventID = $search['eventID'] ?? FALSE;
		 $userID = isset($search['user_id']) ? $search['user_id'] : FALSE;
		 $category_id = isset($search['categories']) ? $search['categories'] : FALSE;
		if($category_id){
			$this->db->where('appointment.colorID',$category_id);
		}

		if($userID){
		$this->db->where('appointment.userID',$userID);
		}

		$out = $this->db->select("appointment.id as id,appointment.userID as userID,appointment.startDate as start,appointment.endDate as end,appointment.title as title,appointment.body as descr,appointment.editable as editable,color.id as colorID,color.eventColor,color.colorName as color,color.eventCategory,color.glyphiconCategory as glyphicon")
                ->from('appointment')
                ->join('user','user.id = appointment.userID')
                ->join('color','color.id = appointment.colorID')
                ->where('appointment.deleted',0)
                ->order_by('appointment.id','DESC')
                ->group_by('appointment.id')
                ->get()->result();
            return $out;  
	}

	public function getColorQuery(array $search = array()){
		return $this->db->select("color.id as colorID,color.eventColor,color.colorName as color,color.eventCategory,color.glyphiconCategory as glyphicon")
			 	->from('color')
			 	->get()->result();
	}

	public function getTimeSchedule(array $search = array()){
		return $this->db->select('schoolschedule.id as scheduleID,schoolschedule.start_time,schoolschedule.end_time')
			->from('schoolschedule')
			->get()->result();
	}

	public function addEventAppoint($event){
		$eventDate = date('Y-m-d H:i:s');
		$data = array(
				'userID' => $_SESSION['userID'],
				'title'=>$event['title'],
				'startDate'=>$event['start'],
				'endDate'=>$event['end'],
				'allDay'=>$event['allDay'],
				'body'=>$event['descr'],
				'colorID'=>$event['selColor']);
		$this->db->trans_start();
		$this->db->insert('appointment',$data);
		$eventID = $this->db->insert_id();
		if($this->db->trans_complete()){
			return $eventID;
		}else{
			return FALSE;
		}
	}

	public function eventUpdate($event,$userID){
		//var_dump($event);
		$data = array(
				'userID' => $_SESSION['userID'],
				'title'=>$event['title'],
				'startDate'=>$event['start'],
				'endDate'=>$event['end'],
				'allDay'=>$event['allDay'],
				'body'=>$event['descr'],
				'colorID'=>$event['selColor']);
		$this->db->trans_start();
		$this->db->where('id',$event['id']);
		$this->db->update('appointment',$data);
		return $this->db->trans_complete();
	}

	public function delete_event($eventID){
		//var_dump($eventID);
		$this->db->trans_start();
		$event_rmove = explode(',',$eventID);
		for($t = 0; $t < count($event_rmove); $t++){
		$this->db->where('appointment.id',$event_rmove[$t])
			 	 ->where('appointment.userID',$_SESSION['userID'])
				 ->update('appointment',array('deleted'=>1));
			}
		return $this->db->trans_complete();
	}

	public function event_exist_del($evntID){
	 	$restoreEvnt = explode(',',$evntID);
	 	$array_evnt = array();
	 	$error_evnt = [];
	 	for($b = 0; $b < count($restoreEvnt);$b++){
		$qryEvent = (array)$this->db->select('id')
									->from('appointment')
									->where('id',$restoreEvnt[$b])
									->get()->row();
		if (!empty($qryEvent)) {
          $error_evnt[] = FALSE;
        }else{
          $error_evnt[] = TRUE;
        }
      }

      if(!empty($error_evnt)){
          for($b = 0; $b < count($error_evnt);$b++){
            if($error_evnt[$b] === TRUE){
                return FALSE;
            }
          }
          return TRUE;
      }
      return FALSE;
  }//end appointment exist validation
}
?>