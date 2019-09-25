<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Announce_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}

	public function announceQuery(array $search = array()){
		//assign announce ID value to announcement search
		$announceID = isset($search['announce']) ? $search['announce'] : FALSE;
		//assign title to serch
		$title = isset($search['title']) ? $search['title'] : FALSE;
		//search assigned by the title
		if (isset($title)){
            $where = '(announcement.title LIKE "%'.$title.'%")';
            $this->db->where($where);
        }
       
		return $this->db->select('announcement.id as annID,announcement.title as title,announcement.body as body,announcement.userID as userID,user.lastName as lastName,user.firstName as firstName,announcement.date as annDate')
		->from('announcement')
		//->join('announcement','announcement.id = hasannouncement.announcementID')
		->join('user','user.id = announcement.id')
		->where('announcement.deleted',0)
		->order_by('announcement.id','DESC');
	}
	
	public function getAnnounce($params = array(),array $search = array()){
		$offset = !empty($search['page']) ? $search['page'] : 0;
		$this->announceQuery($search);
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

	public function hasAnnounceQuery(array $search = array()){
		//assign announce ID value to announcement search
		$announceID = isset($search['announce_id']) ? $search['announce_id'] : FALSE;
		//assign user SESSION to announcement
		$user_hasannouncement = isset($search['user_id_ann']) ? $search['user_id_ann'] : FALSE;
		if($announceID){
			$this->db->where('hasannouncement.announcementID',$announceID);
		}
		if($user_hasannouncement){
			$this->db->where('hasannouncement.userID',$user_hasannouncement);
		}
		return $this->db->select('hasannouncement.announcementID, hasannouncement.userID, hasannouncement.read as hasRead')
		->from('hasannouncement')
		//->where('hasannouncement.read',1)
		->order_by('hasannouncement.announcementID','DESC')
		->get()->result();
	}

	public function announceMore($data){
  		//var_dump($data);
  		//begin transaction
  		$this->db->trans_start();
  		$announcementv = array(
  			'announcementID' =>$data['readMeAnn'],
  			'userID' =>$_SESSION['userID']);
  		$this->db->insert('hasannouncement',$announcementv);
  		return $this->db->trans_complete();
  		//end of transaction
  	}

	public function createAnnounceM($data){
		$annDate = date('Y-m-d H:i:s');
		$ann = array(
				"title"=>$data['annTitled'],
				//"date"=> $annDate,
				"userID"=>$_SESSION['userID'],
				"body"=>$data['annBdy']);
				$this->db->trans_start();//start transaction
				$this->db->insert("announcement",$ann);//insert data into database
				$directAnnID = $this->db->insert_id();
				$this->db->insert("announcement",$ann);
				//calls annouceMore and inserts into hasannouncement table
				return $this->db->trans_complete();//end of transaction
	}

	public function countAnnounce(array $search = array()){
			$this->announceQuery($search);
		return $this->db->count_all_results();
	}

	public function deleteAnnounce($announceID){
		$this->db->trans_start();
		$this->db->where('announcement.id',$announceID)
				 ->update('announcement',array('deleted'=>1));
		return $this->db->trans_complete();//end transaction
	}

	public function updateAnnounce($data){
		$annDt = date('Y-m-d H:i:s');
		$ann = array(
				"title"=>$data['annTitled'],
				"date"=> $annDt,
				"userID"=>$_SESSION['userID'],
				"body"=>$data['annBdy']); 
				$this->db->trans_start();//start transaction
				$this->db->where('announcement.id',$data['annIDe'])
					 ->update('announcement',$ann);
					 return $this->db->trans_complete();
	}

	public function hasannounce_exist($hasAnnnID)
	{
		$hasAnnnID = explode(',',$hasAnnnID);
		$array_ann = array();
		$error_ann = [];

		for($f = 0; $f < count($hasAnnnID);$f++){
		$qryHasAnn = (array)$this->db->select('id')
									 ->from('announcement')
									 ->where('id',$hasAnnnID[$f])
									 ->get()->row();
		if (!empty($qryHasAnn)) {
          $erro_ann[] = FALSE;
        }else{
          $error_ann[] = TRUE;
        }
      }

	if(!empty($erro_ann)){
          for($f = 0; $f < count($erro_ann);$f++){
            if($erro_ann[$f] === TRUE){
                return FALSE;
            }
          }
          return TRUE;
      }
      return FALSE;
    }
 }
?>