<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Topic_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}

	public function topicQuery(array $search = array()){
		$topicID = isset($search['searchTopicMain']) ? $search['searchTopicMain'] : FALSE;
		//$user_id = isset($search['searchTopicMain']) ? $search['searchTopicMain'] : FALSE;
		if(isset($topicID)){
      	$this->db->where('generaltopic.id',$topicID);
      	}
      	/*if($user_id){
      	$this->db->where('generaltopic.id',$user_id);
      	}*/
		return $this->db->select('generaltopic.id as topicID,generaltopic.title as topicTitle,generaltopic.description as topicDscr,generaltopic.topicDate,generaltopic.creatorID as adminID,admin.userID,generaltopic.deleted as deleted,user.firstName as firstName')
				->from('generaltopic')
				->join('admin','admin.id = generaltopic.creatorID')
				->join('user','user.id = admin.userID')
				->group_by('topicID')
				->order_by('generaltopic.id','DESC')
 				->get()->result();
	}

	public function getTopic($params = array(),array $search = array()){
		$offset = !empty($search['page']) ? $search['page'] : 0;
		$this->topicQuery($search);
		//to establish the limits for the pagination
		if(array_key_exists("start",$params) && array_key_exists("limit", $params)){
			$this->db->limit($params['limit'],$params['start']);
		}
		elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }//end pagination
 		return (!empty($out)) ? $out : FALSE;
	}
	public function countTopic(array $search = array()){
		$this->topicQuery($search);
		return $this->db->count_all_results();
	}

	public function createTopic($data){
		//$userID = 1;
		$msgDate = date('Y-m-d H:i:s');
		$tpc = array(
				'title'=>$data['topicTitle'],
				 'description'=>$data['topicDescription'],
				'creatorID'=>$_SESSION['userID']);
		$this->db->trans_start();
		$this->db->insert('generaltopic',$tpc);
		return $this->db->trans_complete();

	}

	 public function deleteTopic($topicID){
	 	$this->db->trans_start();
	 	$this->db->delete('generaltopic',array('id'=>$topicID));
	 	return $this->db->trans_complete();
	 }

	public function updateTopic($data){
		$msgDate = date('Y-m-d H:i:s');
	 	$gntpc = array(
	 				'title'=>$data['title'],
	 				'description'=>$data['topicDescription'],
	 				'creatorID'=>$_SESSION['userID']);
	 	$this->db->trans_start();
	 	$this->db->where('generaltopic.id',$data['gtID'])
	 			->update('generaltopic',$gntpc);
	 			return $this->db->trans_complete();
	 }

}
?>