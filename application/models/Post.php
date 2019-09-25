<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Post extends CI_Model{
public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model("users_model");
    }
/**
 * [getReceivedmsg gets the messages received by the user id and prepares the data for the ajax pagination]
 * @param  array  $params [contains the pagination parameters]
 * @return array         [with the data selected from the database]
 */
    function getReceivedmsg($params = array())
    {
        $this->db->select('messages.id,messages.message,messages.date,uhm.id_sender as sender,uhm.readed as readed,messages.subject as subject')
       ->from('messages')
		->join('user_has_message as uhm','uhm.id_message = messages.id')
		->join('users','uhm.id_receiver = users.id')
		->where('users.id',$_SESSION['id'])
		->where('uhm.deleted_by_receiver',0);
        //checks the existence of the pagination parameters
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        $this->db->order_by('messages.date','desc');
        //prepares the data that will be written on the view
        $rows=$this->db->get()->result();
        foreach ($rows as $msg) {
            $sender= (array)$this->users_model->get_user_by_id($msg->sender);
            $out[]=array('id'=>$msg->id,'message'=>$msg->message,'subject'=>$msg->subject,'senderMail'=>$sender['email'],'sender'=>$sender['name'],'sender_id'=>$sender['id'],'senderAvatar'=>$sender['avatar'],'date'=>$msg->date,'readed'=>$msg->readed);
        }
        return (!empty($out))?$out:FALSE;
    }
/**
 * [getSentmsg gets the messages sent by a the user id and prepares the data for the pagination]
 * @param  array  $params [contains the pagination parameters]
 * @return array         [with the data selected from the database]
 */
 function getSentmsg($params = array())
    {
        $this->db->select('messages.id,messages.message,messages.subject as subject,messages.date,uhm.id_receiver as receiver')
        ->from('messages')
        ->join('user_has_message as uhm','uhm.id_message = messages.id')
        ->join('users','uhm.id_sender = users.id')
        ->where('users.id',$_SESSION['id'])
        ->where('uhm.deleted_by_sender',0);
        //checks the existence of the pagination parameters
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        
        $this->db->order_by('messages.date','desc');
        $rows=$this->db->get()->result();
        //prepares the data that will be written on the view
        foreach ($rows as $msg) {
            $receiver= (array)$this->users_model->get_user_by_id($msg->receiver);
            $out[]=array('id'=>$msg->id,'message'=>$msg->message,'subject'=>$msg->subject,'receiver_id'=>$receiver['id'],'receiverMail'=>$receiver['email'],'receiver'=>$receiver['name'],'receiverAvatar'=>$receiver['avatar'],'date'=>$msg->date);
        }
        return (!empty($out))?$out:FALSE;
    }

public function deleteMessage($msgUser){
    if($msgUser['who']=='receiver'){
        //receiver
        $delete = array(
            'deleted_by_receiver'           => 1
            );
        }elseif($msgUser['who']=='sender'){
        //sender
        $delete = array(
            'deleted_by_sender'         => 1
            );
        }else{
            $delete = array();
        }
    //begin the transaction
    $this->db->trans_start();
    //delete the book
    $this->db->where('user_has_message.id_message',$msgUser['idMsg'])
    ->where('user_has_message.id_sender',$msgUser['idSender'])
    ->where('user_has_message.id_receiver',$msgUser['idReceiver'])
    ->update('user_has_message',$delete);
    //close the transaction
    return $this->db->trans_complete();
}
public function updateReaded($msgId,$idReceiver){
    $prevState = $this->db->select('readed')
    ->from('user_has_message')
    ->where('id_message',$msgId)
    ->where('id_receiver',$idReceiver)
    ->get()->row();

        $readed = array(
            'readed'            => 1
            );
    //begin the transaction
    $this->db->trans_start();
    //delete the book
    $this->db->where('user_has_message.id_message',$msgId)
    ->update('user_has_message',$readed);
    //close the transaction
    $this->db->trans_complete();
    return $prevState->readed ??null;
}
 function saveSentMsg($data = array())
    {
        if(!empty($data)){
              return FALSE;
          }
    $receiverId = get_user_id($data['name']);
    if($receiverId){
    $newMsg = array(
        'message'=>$data['msg'],
        'subject'=>$data['subject'],
        );
//begin the transaction
    $this->db->trans_start();
    $this->db->insert("messages",$newMsg);
    $msg_id = $this->db->insert_id();
   
$this->saveUserHasMsg($msg_id,$receiverId);
    $this->db->trans_complete();
     return $this->db->trans_status();
}else{
    return FALSE;
}
    }
 function saveUserHasMsg($msg_id,$receiverId)
    {

    $user_has_message = array(
        'id_sender'=>$_SESSION['id'],
        'id_receiver'=>$receiverId,
        'id_message'=>$msg_id,
        );
    $this->db->insert("user_has_message",$user_has_message);

    }
       
}