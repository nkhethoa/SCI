<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frequently_Asked_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}

	public function askedFrequentlyQuery(array $search = array()){
		$questionID = isset($search['faq']) ? $search['faq'] : FALSE;
		//$questionCategory = isset($search['faqCategory']) ? $search['faqCategory'] : FALSE;

		if (isset($questionID)){
            $where = '(frequentlyasked.faqTitle LIKE "%'.$questionID.'%")';
            $this->db->where($where);
        }

        /*if (isset($questionCategory)){
        	$where = '(frequentquestionscategory.faqCategory LIKE "%'.$questionCategory.'%")';
        	$this->db->where($where);
        }*/

		return $this->db->select('frequentlyasked.id as faqID,frequentlyasked.faqTitle,frequentlyasked.faqDescription,frequentlyasked.frequentquestionscategoryID,frequentquestionscategory.id,frequentquestionscategory.faqCategory,frequentquestionscategory.deleted')
		->from('frequentlyasked')
		->join('frequentquestionscategory','frequentquestionscategory.id = frequentlyasked.frequentquestionscategoryID ')
		->where('frequentlyasked.deleted',0)
 		->get()->result();
		}

	public function getFAQSearch($params = array(),array $search = array()){
		$offset = !empty($search['page']) ? $search['page'] : 0;
		$this->askedFrequentlyQuery($search);
		//to establish the limits for the pagination
		if(array_key_exists("start",$params) && array_key_exists("limit", $params)){
			$this->db->limit($params['limit'],$params['start']);
		}
		elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }//end pagination
 		return (!empty($out)) ? $out : FALSE;
	}

	public function frequentAskedCategory(array $search = array()){
		$categoryFAQID = isset($search['faqCategory']) ? $search['faqCategory'] : FALSE;
		//$categoryFAQuestion = isset($search['faqQuestion']) ? $search['faqQuestion'] : FALSE;

		if(isset($categoryFAQID)){
			$where = '(frequentquestionscategory.faqCategory LIKE "%'.$categoryFAQID.'%")';
			$this->db->where($where);
		}

		/*if(isset($categoryFAQuestion)){
			$where = '(frequentlyasked.faqTitle LIKE "%'.$categoryFAQuestion.'%")';
			$this->db->where($where);
		}*/

		return $this->db->select('frequentquestionscategory.id as faqCatID,frequentquestionscategory.faqCategory,frequentquestionscategory.deleted')
		->from('frequentquestionscategory')
		//->join('frequentlyasked','frequentlyasked.frequentquestionscategoryID = frequentquestionscategory.id')
		->where('frequentquestionscategory.deleted',0)
		->get()->result();
	}

	public function getFAQCategorySearch($params = array(), array $search = array()){
		$offset = !empty($search['page']) ? $search['page'] : 0;
		$this->frequentAskedCategory($search);
		//to establish the limits for the pagination
		if(array_key_exists("start",$params) && array_key_exists("limit", $params)){
			$this->db->limit($params['limit'],$params['start']);
		}
		elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }//end pagination
        return (!empty($out)) ? $out : FALSE;
	}

	public function addQuestion($data){
		$questions = array( 
				'faqTitle'=>$data['faq_title'],
				'faqDescription'=>$data['your_question'],
				'frequentquestionscategoryID'=>$data['category_sel']);
		$this->db->trans_start();
		$this->db->insert('frequentlyasked',$questions);
		return $this->db->trans_complete();
	}
	//update FAQ function
	public function updateFAQuestion($data){
		$qstns = array(
				"frequentquestionscategoryID"=>$data['category_sel'],
				"faqTitle"=> $data['faq_title'],
				"faqDescription"=>$data['your_question']); 
				$this->db->trans_start();//start transaction
				$this->db->where('frequentlyasked.id',$data['hiddnedtfaqSelIDt'])
					 ->update('frequentlyasked',$qstns);
					 return $this->db->trans_complete();
	}
	//end update function

	//delete FAQ function
	public function deleteFAQs($askID){
		$this->db->trans_start();
		$this->db->where('frequentlyasked.id',$askID)
				 ->update('frequentlyasked',array('deleted'=>1));
		return $this->db->trans_complete();//end transaction
	}
	//end delete FAQ function
	
	public function faq_exist($faqID)
	{
	    $qryFrequent= (array)$this->db->select('id')
	                                ->from('frequentlyasked')
	                                ->where('id',$faqID)
	                                ->get()->row();
	    if(($qryFrequent!=NULL) && ($qryFrequent['id']==$faqID)){
	        return TRUE;
	    }else{
	        return FALSE;
	    }
	}

}