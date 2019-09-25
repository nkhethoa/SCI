<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comments_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}

public function getTopicComments(array $search = array()){
   $tableTopic = isset($search['searchTopic']) ? $search['searchTopic'] : FALSE;
    if(isset($tableTopic)){
    $where = '(generaltopic.title LIKE "%'.$tableTopic.'%" || generaltopic.description LIKE "%'.$tableTopic.'%")';
    	$this->db->where($where);
    }
   return  $this->db->select('count(generaltopiccomments.generaltopicID) as userCount, generaltopiccomments.generaltopicID as gtID, generaltopiccomments.commentMessage as Msg, generaltopiccomments.userID as userID,generaltopic.topicDate, generaltopic.title as gtTitle, generaltopic.creatorID as topicCreator,user.firstName as creatorName,user.lastName as creatorSurname, generaltopic.description as gtDescr')
                    ->from('generaltopiccomments')
                    ->join('generaltopic','generaltopic.id = generaltopiccomments.generalTopicID')
                    ->join('user','user.id = generaltopiccomments.userID')
                    ->where('generaltopiccomments.deleted',0)
                    ->order_by('generaltopiccomments.userID','DESC')
                    ->group_by('generaltopiccomments.userID')
                    ->group_by('generaltopiccomments.generaltopicID');
}

public function getTableTopics($params = array(),array $search = array()){
	$offset = !empty($search['page']) ? $search['page'] : 0;
	$this->getTopicComments($search);
 	 //to establish the limits for the pagination
 	if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
     $this->db->limit($params['limit'],$params['start']);

		}elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }

        $out = $this->db->get()->result();
        return (!empty($out)) ? $out : FALSE;
}

public function getLikes($search=array()){
	$cmlid = isset($search['commentID']) ? $search['commentID'] : FALSE;
	$userlid = isset($search['userlike']) ? $search['userlike'] : FALSE;

	if($cmlid){
		$this->db->where('hascommentslikes.commentLikeID',$cmlid);
	}

	if($userlid){
		$this->db->where('hascommentslikes.userCommentLikeID',$userlid);
	}

	return  $this->db->select("hascommentslikes.commentLikeID as commentLiked, hascommentslikes.userCommentLikeID as userIDLike,hascommentslikes.likes as liked")
		->from('hascommentslikes')
		->join('generaltopiccomments','generaltopiccomments.id = hascommentslikes.commentLikeID')
		->join('user','user.id = hascommentslikes.userCommentLikeID')
		->get()->result();
}

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

public function getTopicsQuery($params = array(),array $search=array())
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

//select box query
public function getSelectBoxTopics($search=array()){
	$topicMainID = isset($search['topicMain']) ? $search['topicMain'] : FALSE;
	if($topicMainID){
		$this->db->where('generaltopic.id',$topicMainID);
	}
	return  $this->db->select("generaltopic.id as gtID,generaltopic.title as gtTitle,generaltopic.description as gtDescr,generaltopic.topicDate,generaltopic.creatorID as topicCreator,user.firstName as creatorName,user.lastName as creatorSurname,generaltopic.deleted as deleted")
		->from('generaltopic')
		->join('admin','admin.id = generaltopic.creatorID')
		->join('user','user.id = admin.userID')
		->where('generaltopic.deleted',0)
		->get()->result();
}
//select box query end

public function commentQuery(array $search = array()){
		$c = isset($search['searchTopic']) ? $search['searchTopic'] : FALSE;
		$t = isset($search['topicName']) ? $search['topicName'] : FALSE;
		$topicID = isset($search['topicID']) ? $search['topicID'] : FALSE;
	
	if(isset($c)){
		$where = '(generaltopic.title LIKE "%'.$c.'%" || generaltopic.description LIKE "%'.$c.'%")';
		$this->db->where($where);
	}
	if($topicID){
		$this->db->where('generaltopic.id',$topicID);
	}
	if($t){
		$this->db->where('generaltopiccomments.generaltopicID',$t);
	}
		return $this->db->select("generaltopiccomments.id AS totalComm,generaltopiccomments.id as gtcID,generaltopiccomments.generalTopicID as gtID,generaltopiccomments.userID AS totalUser,generaltopiccomments.userID,generaltopiccomments.commentMessage,generaltopiccomments.commentDate,generaltopiccomments.deleted,user.firstName as commentorFName,user.lastName as commentorLName,generaltopic.title as gtTitle,generaltopic.topicDate,files.filePath,guardian.id as gID,guardian.userID as gUserID,guardian.deleted as gDeleted,relationship.id as relID, relationship.description as relDescription")
						->from('generaltopiccomments')
						->join('generaltopic','generaltopic.id = generaltopiccomments.generalTopicID')
						->join('user','user.id = generaltopiccomments.userID')
						->join('guardian','guardian.userID = user.id')
						->join('learnerguardian','learnerguardian.guardianID = guardian.id')
						->join('relationship','relationship.id = learnerguardian.relationshipID')
						->join('profile','profile.userID = user.id')
						->join('files','files.id = profile.filesID')
						->where('generaltopiccomments.deleted',0)
						->group_by('generaltopiccomments.id')
						->order_by('generaltopiccomments.id','DESC');
		}

	public function getComment(array $search = array(),$params = array()){
		$offset = !empty($search['page']) ? $search['page'] : 0;
 		$this->commentQuery($search);
 			 //to establish the limits for the pagination
 			if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        
 		$out= $this->db->get()->result();
 		return (!empty($out)) ? $out : FALSE;
		}

		/*delete comment*/
	public function deleteComment($commID){
		$this->db->trans_start();
		$this->db->where('generaltopiccomments.id',$commID)
				 ->update('generaltopiccomments',array('deleted'=>1));
		return $this->db->trans_complete();
	}
		/*delete discussion*/
	public function deleteDiscussion($discuID){
		$this->db->trans_start();
		$this->db->where('generaltopic.id',$discuID)
				 ->update('generaltopic',array('deleted'=>1));
		return $this->db->trans_complete();
	}

	public function updateComment($data){
			$this->db->trans_start();
			$this->db->where('generaltopiccomments.id',$data['gtcid'])
					 ->update('generaltopiccomments',array('commentMessage'=>$data['cm']));
					 return $this->db->trans_complete();
				//'creator'=>$data[''])
	}

	public function updateDiscuss($dataa){
		//var_dump($dataa);
			$this->db->trans_start();
			$this->db->where('generaltopic.id',$dataa['editDiscHiddnID'])
					 ->update('generaltopic',array('title'=>$dataa['edtitle'],'description'=>$dataa['descrDis']));
					 return $this->db->trans_complete();
	}

	public function countComment(array $search = array()){
		$this->commentQuery($search);
		return $this->db->count_all_results();
	}

	public function addComment($data){
		$commDate = date('Y-m-d H:i:s');
		$commt = array( 
				'generalTopicID'=>$data['gtide'],
				'userID'=>$_SESSION['userID'],
				'commentMessage'=>$data['cm']);
		$this->db->trans_start();
		$this->db->insert('generaltopiccomments',$commt);
		return $this->db->trans_complete();
	}

	public function addDiscuss($dataa){
		$discuDate = date('Y-m-d H:i:s');
		$commtD = array(
				'creatorID'=>$_SESSION['userID'],
				'title'=>$dataa['edtitle'],
				'description'=>$dataa['descrDis']);
		$this->db->trans_start();
		$this->db->insert('generaltopic',$commtD);
		return $this->db->trans_complete();
	}

	//comment likes
	public function likeComment($like){
		$this->db->trans_start();
		$liking = array(
				 "userCommentLikeID"=>$_SESSION['userID'],
				 "commentLikeID"=>$like['commentID'],
				 "likes"=>$like['like']
				);
		$this->db->insert('hascommentslikes',$liking);
		return $this->db->trans_complete();

	}

	public function updateLike($like){
		$this->db->trans_start();
		$this->db->where('hascommentslikes.userCommentLikeID',$_SESSION['userID'])
				 ->where('hascommentslikes.commentLikeID',$like['commentID'])
					 ->update('hascommentslikes',array('likes'=>$like['like']));
					 return $this->db->trans_complete();
	}

	public function comment_exist($cmmID){
	    $qryComment= (array)$this->db->select('id')
	                                ->from('generaltopiccomments')
	                                ->where('id',$cmmID)
	                                ->get()->row();

	    if(($qryComment!=NULL) && ($qryComment['id']==$cmmID)){
	        return TRUE;
	    }else{
	        return FALSE;
	    }
	}

	public function discussion_exist($discID){
	    $qryDiscu = (array)$this->db->select('id')
	                                ->from('generaltopic')
	                                ->where('id',$discID)
	                                ->get()->row();

	    if(($qryDiscu!=NULL) && ($qryDiscu['id']==$discID)){
	        return TRUE;
	    }else{
	        return FALSE;
	    }
	}

	public function qryRating(array $search=array()){
		$user_rate_id = isset($search['user_rating']) ? $search['user_rating'] : FALSE;
		$rate_id = isset($search['ratingID']) ? $search['ratingID'] : FALSE;
		$comm_star = isset($search['comment_star']) ? $search['comment_star'] : FALSE;

		if($user_rate_id){
			$this->db->where('commentrating.userRateID',$user_rate_id);
		}

		if($rate_id){
			$this->db->where('commentrating.ratingID',$rate_id);
		}

		if($comm_star){
			$this->db->where('commentrating.generaltopiccommentID',$comm_star);
		}

		return $this->db->select("commentrating.id as postID,commentrating.userRateID,commentrating.generaltopiccommentID,commentrating.ratingID")
						->from('commentrating')
						->join('rating','rating.id = commentrating.ratingID')
						->join('generaltopiccomments','generaltopiccomments.id = commentrating.generaltopiccommentID')
						->join('user','user.id = commentrating.userRateID');
		}

		public function getRatings(array $search = array()){
			$this->qryRating($search);
			return $this->db->get()->result();
		}

		public function giveStarPost($data){
			$star = array(
					'userRateID'=>$_SESSION['userID'],
					'generaltopiccommentID'=>$data['comment_rate'],
					'ratingID'=>$data['star'] );
					$this->db->insert('commentrating',$star);//inserts into commentrating table
					return $this->db->trans_complete();
		}

		public function updateRate($rate){
		$this->db->trans_start();
		$this->db->where('commentrating.userRateID',$_SESSION['userID'])
				 ->where('commentrating.generaltopiccommentID',$rate['commentID'])
				 ->update('commentrating',array('rating'=>$rate['star']));
					 return $this->db->trans_complete();
	}

	public function hasrating_exist($starID){
	    $qryRating= (array)$this->db->select('id')
	                                ->from('rating')
	                                ->where('id',$starID)
	                                ->get()->row();

	    if(($qryRating!=NULL) && ($qryRating['id']==$starID)){
	        return TRUE;
	    }else{
	        return FALSE;
	    }
	}
 }
?>