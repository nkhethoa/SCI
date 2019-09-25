<?php 
 defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends CI_Model{
 public function __construct(){
 	parent:: __construct();
 	$this->load->database();
 }
 
 public function messageQuery(array $search=array()){

 		$m = isset($search['msg']) ? $search['msg'] : FALSE;
 	 		//var_dump($search);
        $inboxID = isset($search['inboxID']) ? $search['inboxID']:FALSE;
        $outbox = isset($search['outboxes']) ? $search['outboxes'] : FALSE;
        $user_id_sent = isset($search['user_id']) ? $search['user_id'] : FALSE;
        $user_id_inbox = isset($search['user_id_msg']) ? $search['user_id_msg'] : FALSE;
        $ibx_id = isset($search['ibx_id']) ? $search['ibx_id'] : FALSE;
        
    		if (isset($m)){
                $where = '(incoming.firstName LIKE "%'.$m.'%" || incoming.LastName LIKE "%'.$m.'%" || directmessage.title LIKE "%'.$m.'%")';
                $this->db->where($where);
            }
 
      	if($inboxID){
      	$this->db->where('inbox.id',$inboxID);
      	}

      	if($user_id_inbox){
      		$this->db->where('incoming.id',$user_id_inbox);
      	}

        if($user_id_sent){
      	}

	return	$this->db->select('directmessage.id as dmID,directmessage.title,directmessage.body,directmessage.date as date,directmessage.deleted as msgDeleted,sender.firstName as firstname,sender.lastName as lastname,sender.email as email,inbox.toUserID as receiverID,inbox.fromUserID as senderID, inbox.id as inboxID,inbox.read as read,files.filePath')
 		->from('inbox')
 		->join('directmessage','directmessage.id = inbox.directMsgID')
    ->join('user as incoming','incoming.id = inbox.toUserID')
 		->join('user as sender','sender.id = inbox.fromUserID')
    ->join('profile','profile.userID = sender.id')
    ->join('files','files.id = profile.filesID')
 		->where('inbox.deletedReceiver',0);
 }

  public function getMessage($params = array(),array $search = array()){
		$offset = !empty($search['page']) ? $search['page'] : 0;
 		$this->messageQuery($search);
 		//to establish the limits for the pagination
 			if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        	}elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
 		$out= $this->db->get()->result();

 return (!empty($out)) ? $out : FALSE;
 }

 public function directMessageQuery(array $search = array()){
       return $this->db->select('directmessage.id as dimID,directmessage.title as directTitle,directmessage.body as directBody,directmessage.date as directDate,directmessage.deleted as directDeleted')
      ->from('directmessage')
      ->where('directmessage.deleted',0);
 }

 public function getDirectMsg($params = array(), array $search = array()){
  $offset = !empty($search['page']) ? $search['page'] : 0;
    $this->directMessageQuery($search);
    //to establish the limits for the pagination
      if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
          }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
    $out= $this->db->get()->result();

 return (!empty($out)) ? $out : FALSE;
 }

 /**
 * [countMsg description]
 * @param  array  $search [description]
 * @return [type]         [description]
 */
 public function countMsg(array $search = array()){
 	$this->messageQuery($search);
 	return $this->db->count_all_results();
 }
/**
 * [sendMsg description]
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
public function sendMsg($data){
  //var_dump($data);
 		$msg = array(
				//"id"=>$data['to'],
				"title"=>$data['title'],
				"body"=>$data['body']);
				$this->db->trans_start();
				$this->db->insert("directmessage",$msg);//inserts into direct message table
				$directMsgID = $this->db->insert_id();
        $this->inboxMsg($directMsgID,$data);//calls inboxMsg and inserts into inbox table
				return $this->db->trans_complete();
 	}

 public function inboxMsg($directMsgID,$data){
    $receiver = [];
    $receiver = $data['to_user_id'];
    $r = explode(';', $receiver);
    $this->db->trans_start();
    //entering multiple message ids at the same time on transaction start
    for ($i = 0; $i < count($r); $i++) {
     $msg = array(
        "directMsgID"=>$directMsgID,
        "fromUserID"=>$_SESSION['userID'],
        "toUserID"=>$r[$i]);//user id of each receiver
        
        $this->db->insert("inbox",$msg);
        }
        return $this->db->trans_complete();//end of trasaction
  }

 	public function removeFromMsgs($msgID){
    $this->db->trans_start();
    $trashhMsgvar = explode(',',$msgID);
    for ($k = 0; $k < count($trashhMsgvar) ; $k++){
	 	 	$this->db->where('inbox.directMsgID',$trashhMsgvar[$k])
               ->where('toUserID',$_SESSION['userID'])
	 				     ->update("inbox",array("deletedReceiver"=>1));
        }
	 	 return $this->db->trans_complete();
    }

  public function updateMsg($data){
 	$msgInbox = array(
 				"id"=>$data['to'],
				"title"=>$data['subjectMsg'],
				"date"=> $msgDate,
				"body"=>$data['messagea']);
 			$this->db->trans_start();
 			$this->db->where('inbox.id',$data['msgID'])
 					->update('inbox',$msgInbox);
 			return $this->db->trans_complete();
 }

 public function message_inbox_exist($ibID)
  {
      $trashhMsh = explode(',',$ibID);
      $array = array();
      $error = [];
      for($r = 0; $r < count($trashhMsh);$r++){
        $qryIbx = (array)$this->db->select('directMsgID')
                                  ->from('inbox')
                                  ->where('inbox.directMsgID',$trashhMsh[$r])
                                  ->get()->row();
        if (!empty($qryIbx)) {
          $error[] = FALSE;
        }else{
          $error[] = TRUE;
        }
      }

      if(!empty($error)){
          for($r = 0; $r < count($error);$r++){
            if($error[$r] === TRUE){
                return FALSE;
            }
          }
          return TRUE;
      }
      return FALSE;
  }

/********************************************************************Sent Messages************************************************************************/
 public function sentQuery(array $search=array()){
 		    $out = isset($search['sent']) ? $search['sent'] : FALSE;
        $outboxID = isset($search['outboxID']) ? $search['outboxID'] : FALSE;
        $user_id_sent = isset($search['user_id']) ? $search['user_id'] : FALSE;
        $user_snt_msg = isset($search['user_snt_msg']) ? $search['user_snt_msg'] : FALSE;
        $snt_id = isset($search['snt_id']) ? $search['snt_id'] : FALSE;
        $countSent = isset($search['countSent']) ? $search['countSent'] : FALSE;
        if($countSent){
           $this->db->group_by('directmessage.id');
        }

        if (isset($out)){
            $where = '(receiver.firstName LIKE "%'.$out.'%" || receiver.lastName LIKE "%'.$out.'%")';
            $this->db->where($where);
        }

      	if($outboxID){
      	 $this->db->where('inbox.id',$outboxID);
      	}

        if($user_id_sent){
      	$this->db->where('sent.id',$user_id_sent);
      	}

	return $this->db->select('directmessage.id as sentID,directmessage.title as title,directmessage.body,directmessage.date as date,directmessage.deleted as msgDeleted,receiver.firstName as firstname,receiver.lastName as lastname,receiver.email as email,inbox.toUserID as toUser,inbox.fromUserID as fromUser,inbox.id as inboxID,inbox.read as read,files.filePath')
 		->from('inbox')
 		->join('directmessage','directmessage.id = inbox.directMsgID')
 		->join('user as sent','sent.id = inbox.fromUserID')
    ->join('user as receiver','receiver.id = inbox.toUserID')
    ->join('profile','profile.userID = receiver.id')
    ->join('files','files.id = profile.filesID')
 		->where('inbox.deletedSender',0)
 		->order_by('directmessage.id','DESC');
 	 }

  public function getSentMessage($params = array(),array $search = array()){
		$offset = !empty($search['page']) ? $search['page'] : 0;
 		$this->sentQuery($search);
 		//to establish the limits for the pagination
 			if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        	}elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
 		$out = $this->db->get()->result();

 return (!empty($out)) ? $out : FALSE;
 }

public function removeFromSent($msgIDs){
    $this->db->trans_start();
    $trashSent = explode(',', $msgIDs);
    for($z = 0; $z < count($trashSent); $z++){
    $this->db->where('inbox.directMsgID',$trashSent[$z])
               ->where('fromUserID',$_SESSION['userID'])
               ->update("inbox",array("deletedSender"=>1));
        }
    return $this->db->trans_complete();
}
 /**
 * [countMsg description]
 * @param  array  $search [description]
 * @return [type]         [description]
 */
public function countSentMsg(array $search = array()){
  $search['countSent'] =TRUE;
 	$this->sentQuery($search);
 	return $this->db->count_all_results();
 }

public function updateRead($inboxID){
      $this->db->trans_start();
      $this->db->where("inbox.id",$inboxID)
           ->update("inbox",array("read" =>1));
      return $this->db->trans_complete();
 }

public function message_sent_exist($sntID){
    $trashSent = explode(',', $sntID);
    $array_sent = array();
    $error_sent = [];
    for($x = 0; $x < count($trashSent);$x++){
    $qrySent = (array)$this->db->select('inbox.directMsgID')
                      ->from('inbox')
                      ->where('inbox.fromUserID',$trashSent[$x])
                      ->get()->row();
    if (!empty($qrySent)) {
          $error_sent[] = FALSE;
        }else{
          $error_sent[] = TRUE;
        }
      }

      if(!empty($error_sent)){
        for($x = 0; $x < count($error_sent); $x++){
          if($error_sent[$x] === TRUE){
            return FALSE;
          }
        }
        return TRUE;
      }
      return FALSE;
    }
}

?>