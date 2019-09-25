<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trash_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}
/**
 * Message Trash
 * @param  array  $search [description]
 * @return [type]         [description]
 */
	public function msgTrashQuery(array $search=array()){
		//var_dump($search);
        $inboxIDtrash = isset($search['deletedMessage']) ? $search['deletedMessage']:FALSE;
        $user_id_inboxtrsh = isset($search['user_id_del']) ? $search['user_id_del'] : FALSE;
        $direct_msg = isset($search['direct_msg_title']) ? $search['direct_msg_title'] : FALSE;
        $user_email = isset($search['user_inbox_srch']) ? $search['user_inbox_srch'] : FALSE;
        $msg_id = isset($search['msg_id']) ? $search['msg_id'] : FALSE;
        $sendr_id = isset($search['user_id_del']) ? $search['user_id_del'] : FALSE;
        //var_dump($sender_id);

        if($direct_msg){
        	$where = '(directmessage.title LIKE "%'.$direct_msg.'%" || directmessage.body LIKE "%'.$direct_msg.'%")';
            $this->db->where($where);
        }

        if($sendr_id){
        	$this->db->where('inbox.fromUserID',$sendr_id);
        }

        if($inboxIDtrash){
      	$this->db->where('inbox.id',$inboxIDtrash);
      	}

      	if($user_id_inboxtrsh){
      		$this->db->where('incoming.id',$user_id_inboxtrsh);
      	}

      	return	$this->db->select('directmessage.id as dmID,directmessage.title as title,directmessage.body,directmessage.date as date,directmessage.deleted as msgDeleted,sender.firstName as firstname,sender.lastName as lastname,sender.email as email,inbox.toUserID as receiverID,inbox.fromUserID as senderID, inbox.id as inboxID,inbox.read as read,files.filePath')
      	->from('inbox')
      	->join('directmessage','directmessage.id = inbox.directMsgID')
      	->join('user as incoming','incoming.id = inbox.toUserID')
      	->join('user as sender','sender.id = inbox.fromUserID')
      	->join('profile','profile.userID = sender.id')
      	->join('files','files.id = profile.filesID')
      	->where('inbox.deletedReceiver',1)
      	->order_by('directmessage.id','DESC');

 		}

 		public function getTrashedMsgs($params = array(),array $search = array()){
 			$offset = !empty($search['page']) ? $search['page'] : 0;
 			$this->msgTrashQuery($search);
 			//to establish the limits for the pagination
 			if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        	}elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        	}
 			$out= $this->db->get()->result();
 			return (!empty($out)) ? $out : FALSE;
 		}

 		public function restoreToMsgs($msg_restrID){
 			//var_dump($msg_restrID);
    		$this->db->trans_start();
    		$recycleMsg = explode(',',$msg_restrID);
    		for($i = 0; $i < count($recycleMsg); $i++){
	 	 	$this->db->where('inbox.directMsgID',$recycleMsg[$i])
               		 ->where('inbox.fromUserID',$_SESSION['userID'])
	 				 ->update('inbox',array('deletedReceiver'=>0));
        }
	 	return $this->db->trans_complete();
 		}

		 public function restore_msg_exists($ibxID)
		 {
		 	  $inboxRestr = explode(',',$ibxID);
		      $array_inbx_restr = array();
		      $error_inbx_restr = [];
		      for($n = 0; $n < count($inboxRestr);$n++){
		        $qryIbxRestr = (array)$this->db->select('directMsgID')
		                                  ->from('inbox')
		                                  ->where('inbox.directMsgID',$inboxRestr[$n])
		                                  ->get()->row();
		        
		        if (!empty($qryIbxRestr)) {
		          $error_inbx_restr[] = FALSE;
		        }else{
		          $error_inbx_restr[] = TRUE;
		        }
		      }

		      if(!empty($error_inbx_restr)){
		          for($n = 0; $n < count($error_inbx_restr);$n++){
		            if($error_inbx_restr[$n] === TRUE){
		                return FALSE;
		             }
		          }
		          return TRUE;
		      }
		      return FALSE;
		 }

/***************************************SENT MESSAGES********************************************************/
		public function msgSentTrashQuery(array $search=array()){
		//var_dump($search);
 		$outsnt = isset($search['sent_trash']) ? $search['sent_trash'] : FALSE;
        $outboxIDsnt = isset($search['outboxID_trash']) ? $search['outboxID_trash'] : FALSE;
        $user_id_sent_srch = isset($search['user_id_del']) ? $search['user_id_del'] : FALSE;
        $restr_id_sent = isset($search['snt_rmoved']) ? $search['snt_rmoved'] : FALSE;


        if (isset($outsnt)){
            $where = '(receiver.firstName LIKE "%'.$outsnt.'%" || receiver.lastName LIKE "%'.$outsnt.'%")';
            $this->db->where($where);
        }

      	if($outboxIDsnt){
      	 $this->db->where('inbox.id',$outboxIDsnt);
      	}

        if($user_id_sent_srch){
      	$this->db->where('inbox.toUserID',$user_id_sent_srch);
      	}

	return $this->db->select('directmessage.id as sentID,directmessage.title as title,directmessage.body,directmessage.date as date,directmessage.deleted as msgDeleted,receiver.firstName as firstname,receiver.lastName as lastname,receiver.email as email,inbox.toUserID as toUser,inbox.fromUserID as fromUser,inbox.id as inboxID,inbox.read as read,files.filePath')
 		->from('inbox')
 		->join('directmessage','directmessage.id = inbox.directMsgID')
 		->join('user as sent','sent.id = inbox.fromUserID')
    	->join('user as receiver','receiver.id = inbox.toUserID')
    	->join('profile','profile.userID = receiver.id')
    	->join('files','files.id = profile.filesID')
 		->where('inbox.deletedSender',1)
 		->order_by('directmessage.id','DESC');
 	 }
				
		 public function getTrashedSent($params = array(),array $search = array()){
 			$offset = !empty($search['page']) ? $search['page'] : 0;
 			$this->msgSentTrashQuery($search);
 			//to establish the limits for the pagination
 			if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        	}elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        	}
 			$out= $this->db->get()->result();
 			return (!empty($out)) ? $out : FALSE;
 		}

		 public function restoreToSent($outbxID){
		 	$this->db->trans_start();
		 	$recycleSnt = explode(',',$outbxID);
		 	for($y = 0; $y < count($recycleSnt); $y++){
		 		$this->db->where('inbox.directMsgID',$recycleSnt[$y])
		 				 ->where('inbox.toUserID',$_SESSION['userID'])
		 				 ->update('inbox',array('deletedSender'=>0));
		 	}
		 	return $this->db->trans_complete();
		 }

  		 public function restore_sent_exists($outID)
  		 {
  		 	$trashSentRestr = explode(',', $outID);
		    $array_sent_restr = array();
		    $error_sent_restor = [];
		    for($m = 0; $m < count($trashSentRestr);$m++){
		    $qrySentRestr = (array)$this->db->select('directMsgID')
		                      ->from('inbox')
		                      ->where('inbox.directMsgID',$trashSentRestr[$m])
		                      ->get()->result();
		  if (!empty($qrySentRestr)) {
		          $error_sent_restor[] = FALSE;
		        }else{
		          $error_sent_restor[] = TRUE;
		        }
		      }

		      if(!empty($error_sent_restor)){
		        for($m = 0; $m < count($error_sent_restor); $m++){
		          if($error_sent_restor[$m] === TRUE){
		            return FALSE;
		          }
		        }
		        return TRUE;
		      }
		      return FALSE;
		    }

 		/**
 * [countTrashMsg description]
 * @param  array  $search [description]
 * @return [type]         [description]
 */
 		public function countTrashMsg(array $search = array()){
 			$this->msgTrashQuery($search);
 			return $this->db->count_all_results();
		 }

		 /************************Message Trash End***********************************/
		 /**
		  *
		 Announcement Trash * 
		  */
        public function announceTrashQuery(array $search = array()){
        //var_dump($search);
		//assign title to search
		$titleAnnounce = isset($search['announce_title']) ? $search['announce_title'] : FALSE;
		//var_dump($titleAnnounce);
		//assign announce ID value to announcement search
		$announceRecentID = isset($search['ann_recent']) ? $search['ann_recent'] : FALSE;
		//assign user SESSION to announcement
		$user_recentannouncement = isset($search['user_ann']) ? $search['user_ann'] : FALSE;
		$user_ann_srch = isset($search['user_id_del']) ? $search['user_id_del'] : FALSE;
		//search assigned by the title
		if (isset($titleAnnounce)){
            $where = '(announcement.title LIKE "%'.$titleAnnounce.'%" || announcement.body LIKE "%'.$titleAnnounce.'%")';
            $this->db->where($where);
        }

        if($user_ann_srch){

		 	$this->db->where('announcement.userID',$user_ann_srch);

		 }
       
		return $this->db->select('announcement.id as annID,announcement.title as title,announcement.body as body,announcement.userID as userID,user.lastName as lastName,user.firstName as firstName,announcement.date as annDate')
		->from('announcement')
		->join('user','user.id = announcement.id')
		->where('announcement.deleted',1)
		->order_by('announcement.id','DESC');
	}

	public function getAnnounceTrash($params = array(),array $search = array()){
		$offset = !empty($search['page']) ? $search['page'] : 0;
		$this->announceTrashQuery($search);
		//to establish the limits for the pagination
		if(array_key_exists("start",$params) && array_key_exists("limit", $params)){
			$this->db->limit($params['limit'],$params['start']);
		}
		elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }//end pagination 
 		$out = $this->db->get()->result();
 		return (!empty($out)) ? $out : FALSE;
	}

	public function countTrashAnnounce(array $search = array()){
			$this->announceTrashQuery($search);
		return $this->db->count_all_results();
	}

	public function restoreToAnnouncements($annaID){
    		$this->db->trans_start();
    		$restrAnn = explode(',',$annaID);
    		for($g = 0; $g < count($restrAnn);$g++){
	 	 	$this->db->where('announcement.id',$restrAnn[$g])
	 	 			 ->where('announcement.userID',$_SESSION['userID'])
	 				 ->update("announcement",array("deleted"=>0));
        }
	 	return $this->db->trans_complete();
	 }

	//announcement exist validation
	public function announce_exist($annrID)
	  {
	  	 $restoreAnnvar = explode(',',$annrID);
	  	 $array_ann = array();
	  	 $error_ann = [];
	  	 for($v = 0; $v < count($restoreAnnvar);$v++){
	     $qryAnnounce = (array)$this->db->select('id')
	                                ->from('announcement')
	                                ->where('announcement.id',$restoreAnnvar[$v])
	                                ->get()->row();
	  	if (!empty($qryAnnounce)) {
          $error_ann[] = FALSE;
        }else{
          $error_ann[] = TRUE;
        }
      }
      if(!empty($error_ann)){
          for($v = 0; $v < count($error_ann);$v++){
            if($error_ann[$v] === TRUE){
                return FALSE;
            }
          }
          return TRUE;
      }
      return FALSE;
	}

	/*******************************************Announcement Trash End*******************************/

	/**
	 Event Trash Start*
	 * 
	 */
	public function getEventTrashQuery(array $search = array()){
		 //var_dump($search);
		 //$eventID = $search['eventID'] ?? FALSE;
		 $userID_del = isset($search['user_id_del']) ? $search['user_id_del'] : FALSE;
		 $category_id_del = isset($search['categories_del']) ? $search['categories_del'] : FALSE;
		 $event_title = isset($search['event_del_title']) ? $search['event_del_title'] : FALSE;
		  $category_id_del = isset($search['event_rmoved']) ? $search['event_rmoved'] : FALSE;
		 $user_event_title = isset($search['user_id_event']) ? $search['user_id_event'] : FALSE;
					
		 if (isset($event_title)){
            $where = '(appointment.title LIKE "%'.$event_title.'%" || appointment.body LIKE "%'.$event_title.'%")';
            $this->db->where($where);
         }

		 if($category_id_del){
			$this->db->where('appointment.colorID',$category_id_del);
		 }

		 if($userID_del){

		 	$this->db->where('appointment.userID',$userID_del);

		 }

		 $out = $this->db->select("appointment.id,appointment.userID as userID,appointment.startDate as start,appointment.endDate as end,appointment.title as title,appointment.body as descr,appointment.editable as editable,color.id as colorID,color.eventColor,color.colorName as color,color.eventCategory,color.glyphiconCategory as glyphicon")
                ->from('appointment')
                ->join('user','user.id = appointment.userID')
                ->join('color','color.id = appointment.colorID')
                ->where('appointment.deleted',1)
                ->order_by('appointment.id','DESC');
               
	}

 	 public function getTrasheEvent($params = array(),array $search = array()){
 	 	$offset = !empty($search['page']) ? $search['page'] : 0;
 	 	$this->getEventTrashQuery($search);
 	 	//to establish the limits for the pagination
 			if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        	}elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
 		$out= $this->db->get()->result();

 	return (!empty($out)) ? $out : FALSE;
 	 }

	 public function restoreToEvents($evtID){
    		$this->db->trans_start();
    		$restrEvent = explode(',',$evtID);
    		for($y = 0; $y < count($restrEvent); $y++){
	 	 	$this->db->where('appointment.id',$restrEvent[$y])
	 	 			 ->where('appointment.userID',$_SESSION['userID'])
	 				 ->update('appointment',array('deleted'=>0));
        	}
	 	return $this->db->trans_complete();
	 }

	 //appointment exist validation 
	 public function event_exist($evntID){
	 	$restoreEvnt = explode(',',$evntID);
	 	$array_evnt = array();
	 	$error_evnt = [];
	 	for($b = 0; $b < count($restoreEvnt);$b++){
		$qryEvent = (array)$this->db->select('appointment.id')
									->from('appointment')
									->where('appointment.id',$restoreEvnt[$b])
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