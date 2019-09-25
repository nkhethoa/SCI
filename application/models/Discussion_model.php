<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * summary
 */
class Discussion_model extends CI_MODEL
{
public function __construct()
{
	parent::__construct();

}

public function queryCategoryBySubject(array $search=array())
{
	//search for subject when the user click the tab
	$subjName = isset($search['subject']) ? $search['subject'] : FALSE;
	//search for discussion group based on ID for editing purpose
	$dgID = isset($search['catID']) ? $search['catID'] : FALSE;
	//get the learner ID to search for
	$tUser_id = isset($search['teacher']) ? $search['teacher'] : FALSE;
    //get the learner ID to search for
    $lUser_id = isset($search['learner']) ? $search['learner'] : FALSE;
    //get the cls of the subject for user
    $clsID = isset($search['clsID']) ? $search['clsID'] : FALSE;
    //search by class level subjects 
	if ($clsID){
        $this->db->where('discussiongroup.classLevelSubjectsID',$clsID);
    }
    //search subjects by tearcher
    if ($tUser_id) {
        $this->db->where('ut.id',$tUser_id);
    }
    //search subjects by learner
    if ($lUser_id) {
        //$this->db->where('ul.id',$lUser_id);
    }
    //search by subject
	if ($subjName) {
		$this->db->where('subject.name',$subjName);
	}
	//search disscussion group ID
	if ($dgID) {
		$this->db->where('discussiongroup.id',$dgID); 
	} 

		return  $this->db->select("discussiongroup.id as dgID, discussiongroup.title as dgTitle, discussiongroup.body as dgBody,discussiongroup.date as dgPostDate, discussiongroup.classLevelSubjectsID as clsID, classlevelsubjects.subjectID as subjectID, subject.name as subjectName, classlevelsubjects.classGroupLevelID as cglID, classlevelsubjects.beginDate as cglBeginDate, classgrouplevel.levelID as levelID, classgrouplevel.classGroupID as cgID, classgrouplevel.limit as classLimit, classgroup.className cgName ,level.level as level, classlevelsubjects.teacherID as teacherID, teacher.userID as tUserID, ut.firstName as tFName, ut.lastName as tLName, learner.userID as lUserID, ul.firstName as lFName, ul.lastName as lLName, learner.id as learnerID")
					->from("discussiongroup")
					->join('classlevelsubjects','classlevelsubjects.id=discussiongroup.classLevelSubjectsID')
					->join("subject", "subject.id = classlevelsubjects.subjectID")
					->join("classgrouplevel", "classgrouplevel.id = classlevelsubjects.classGroupLevelID")
					->join("classgroup", "classgroup.id = classgrouplevel.classGroupID")
					->join("learner", "learner.classGroupLevelID = classgrouplevel.id")
                    ->join("teacher", "teacher.id = classlevelsubjects.teacherID")
                    ->join("level", "level.id = classgrouplevel.levelID")
                    ->join("user as ut", "ut.id = teacher.UserID")
                    ->join("user as ul", "ul.id = learner.UserID")
					->where('discussiongroup.deleted',0)
					->order_by('discussiongroup.id')
					->group_by('discussiongroup.id');

}
/**
 * [getCategoryBySubject will call queryCategoryBySubject()
 * then send results to caller
 * @param  array  $search it has the values to search for
 * @return [sql results]         
 */
public function getCategoryBySubject(array $search=array())
{
	$this->queryCategoryBySubject($search);
	return $this->db->get()->result();
}

public function countCategoryBySubject(array $search=array())
{
	$this->queryCategoryBySubject($search);
	return $this->db->count_all_results();
}

/**
 * [dsicuss_category_exist method will be used to check if the categoryID exist on the db
 * @param  [type] $categoryID [number or id of the categoryID being confirmed]
 * @return [bool]            [boolean to give indication]
 */
public function dsicuss_category_exist($categoryID)
{
    $qryDiscuss= (array)$this->db->select('id')
                                ->from('discussiongroup')
                                ->where('id',$categoryID)
                                ->get()->row();

    if(($qryDiscuss!=NULL) && ($qryDiscuss['id']==$categoryID)){
        return TRUE;
    }else{
        return FALSE;
    }
}

public function deleteCategory($discID)
{
	return $this->db->where('discussiongroup.id',$discID)
				->update("discussiongroup",array('deleted'=>1));

}
/**
 * [updateCategory description]
 * @param  [type] $data     [description]
 * @return [bool]           status of the transaction
 */
public function updateCategory($data)
{
	$this->db->trans_start();//start transaction
	//populate array with contents
	$category=array(
		'title'=>$data['disc_title'],
		'body'=>$data['disc_body'],
		'classLevelSubjectsID'=>$data['cls'],
	);

	$this->db->where('discussiongroup.id',$data['discID'])
			->update("discussiongroup",$category);
	return $this->db->trans_complete(); //close transacion
}
/**
 * [addCategory description]
 * @param [type] $data  has user inputs from the form
 */
public function addCategory($data)
{
	$this->db->trans_start();//start transaction
	$category=array(
		'title'=>$data['disc_title'],
		'body'=>$data['disc_body'],
		'classLevelSubjectsID'=>$data['cls'],
	);
	//write file to db
	$this->db->insert("discussiongroup",$category);
    return $this->db->trans_complete(); //close transacion

}

/**
 * [getSubjectTopicComments to get all the comments related to the topic for specific cls
 */
public function queryTopicsCommentsByTopic(array $search=array())
{
	//search for discussion group based on ID for editing purpose
	$topicID = isset($search['topic']) ? $search['topic'] : FALSE;
	//id of topic to be deleted
	$delTopicID = isset($search['delTopicID']) ? $search['delTopicID'] : FALSE;
	//general search
	if ($topicID) {
		$this->db->where('topiccomments.topicID',$topicID);
	}
	//look for comments of topic to be deleted
	if ($delTopicID) {
		$this->db->where('topiccomments.topicID',$delTopicID);
	}

	return  $this->db->select("topiccomments.id as tcID, topiccomments.userID as tcAuthorID, commentSender.firstName as tcFName, commentSender.lastName as tcLName , topiccomments.commentMessage as tcAuthorMsg, topiccomments.commentDate as tcPostDate,topiccomments.topicID as topicID, topic.title as tTitle, topic.description as tDescription, topic.date as tPostDate, topic.discussionGroupID as dgID, topic.creatorID as topicAuthorID, topicAuthor.firstName as tAuthorFName, topicAuthor.lastName as tAuthorLName, discussiongroup.title as dgTitle, discussiongroup.body as dgBody,discussiongroup.date as dgPostDate, discussiongroup.classLevelSubjectsID as clsID")
			->from("topiccomments")
			->join("topic", "topic.id = topiccomments.topicID")
			->join("user as topicAuthor", "topicAuthor.id = topic.creatorID")
			->join("user as commentSender", "commentSender.id = topiccomments.UserID")
			->join("discussiongroup", "discussiongroup.id = topic.discussionGroupID")
			->join('classlevelsubjects','classlevelsubjects.id=discussiongroup.classLevelSubjectsID')
			->where('topiccomments.deleted',0);
			//->group_by('topicID');

}

public function getTopicCommentsByTopic(array $search=array())
{
	$this->queryTopicsCommentsByTopic($search);
	return $this->db->get()->result();
}

public function countTopicCommentsByTopic(array $search=array())
{
	$this->queryTopicsCommentsByTopic($search);
	return $this->db->count_all_results();
}
/**
 * [queryTopicsBySubject description]
 * @param  array  $search [description]
 * @return [type]         [description]
 */
public function queryTopicsByDiscGroup(array $search=array())
{
	$topicID = isset($search['topic']) ? $search['topic'] : FALSE;
	if ($topicID) {
		$this->db->where('topic.id',$topicID);
	} 

	$dgID = isset($search['catID']) ? $search['catID'] : FALSE;
	if ($dgID) {
		$this->db->where('topic.discussionGroupID',$dgID);
	} 

		return  $this->db->select("topic.id as topicID, topic.title as topicTitle, topic.description as tDescription, topic.date as tPostDate, topic.creatorID as topicAuthorID, topicAuthor.firstName as tAuthorFName, topicAuthor.lastName as tAuthorLName, topic.discussionGroupID as dgID, discussiongroup.title as dgTitle, discussiongroup.body as dgBody, discussiongroup.date as dgPostDate, discussiongroup.classLevelSubjectsID as clsID")
				->from("topic")
				->join("user as topicAuthor", "topicAuthor.id = topic.creatorID")
				->join("discussiongroup", "discussiongroup.id = topic.discussionGroupID")
				->where('topic.deleted',0);
}

public function getTopicsByDiscGroup(array $search=array())
{
	$this->queryTopicsByDiscGroup($search);
	return $this->db->get()->result();
}

public function countTopicsByDiscGroup(array $search=array()){
	$this->queryTopicsByDiscGroup($search);
	return $this->db->count_all_results();
}

/**
 * [topic_exist method will be used to check if the topic exist on the db
 * @param  [type] $topic [number or id of the topic being confirmed]
 * @return [bool]            [boolean to give indication]
 */
public function topic_exist($topicID)
{
    $qryTopic= (array)$this->db->select('id')
                                ->from('topic')
                                ->where('id',$topicID)
                                ->get()->row();

    if(($qryTopic!=NULL) && ($qryTopic['id']==$topicID)){
        return TRUE;
    }else{
        return FALSE;
    }
}

public function deleteTopic($topicID)
{
	return $this->db->where('topic.id',$topicID)
				->update("topic",array('deleted'=>1));

}
/**
 * [updateCategory description]
 * @param  [type] $data     [description]
 * @return [bool]           status of the transaction
 */
public function updateTopic($data)
{
	$this->db->trans_start();//start transaction
	//populate array with contents
	$topic=array(
		'title'=>$data['topic_title'],
		'description'=>$data['topic_body'],
		'creatorID'=>$_SESSION['userID'],
		'discussionGroupID'=>$data['topic_discID'],
	);

	$this->db->where('topic.id',$data['topic_ID'])
			->update("topic",$topic);
	return $this->db->trans_complete(); //close transacion
}
/**
 * [addCategory description]
 * @param [type] $data  has user inputs from the form
 */
public function addTopic($data)
{

	$this->db->trans_start();//start transaction
	$topic=array(
		'title'=>$data['topic_title'],
		'description'=>$data['topic_body'],
		'creatorID'=>$_SESSION['userID'],
		'discussionGroupID'=>$data['topic_discID'],
	);
	//write file to db
	$this->db->insert("topic",$topic);
    return $this->db->trans_complete(); //close transacion

}

public function deleteComment($commentID)
{
	return $this->db->where('topiccomments.id',$commentID)
				->update("topiccomments",array('deleted'=>1));

}
/**
 * [updateCategory description]
 * @param  [type] $data     [description]
 * @return [bool]           status of the transaction
 */
public function updateComment($data)
{
	$this->db->trans_start();//start transaction
	//populate array with contents
	$comment=array(
		'topicID'=>$data['comment_topicID'],
		'id'=>$data['commentID'],
		'userID'=>$_SESSION['userID'],
		'commentMessage'=>$data['comments'],
	);

	$this->db->where('topiccomments.id',$data['commentID'])
			->update("topiccomments",$comment);
	return $this->db->trans_complete(); //close transacion
}
/**
 * [addCategory description]
 * @param [type] $data  has user inputs from the form
 */
public function addComment($data)
{
	$this->db->trans_start();//start transaction
	$comment=array(
		'topicID'=>$data['comment_topicID'],
		'userID'=>$_SESSION['userID'],
		'commentMessage'=>$data['comments'],
	);
	//write file to db
	$this->db->insert("topiccomments",$comment);
    return $this->db->trans_complete(); //close transacion

}//end addComment

} //end of class