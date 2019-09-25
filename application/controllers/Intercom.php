<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/** 
 * summary
 */ 
class Intercom extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(array('form','text','smiley','email','date','url'));
        //$this->load->helper('ckeditor');
        $this->load->library('form_validation');
        //$this->load->database();
        $this->load->library('pagination');
        $this->load->library('table');
        $this->load->model('Login_model','login');
        $this->load->model('Announce_model','announce_model');
        $this->load->model('Message_model','message_model');
        $this->load->model('Comments_model','comments_model');
        $this->load->model('Topic_model','topic_model');
        $this->load->model('Users_model');
        $this->load->model('Calendar_model','calendar_model');
        $this->load->model('Learner_model','learners');
        $this->load->model('Teacher_model','teachers');
        $this->load->model('Guardian_model','guardians');
        $this->load->model('Admin_model','admin');
        $this->load->model('Subject_model','subject_model');
        $this->load->model('Schedule_model','schedule_model');
        $this->load->model('Frequently_Asked_model','questions_model');
        $this->load->model('Trash_model','trash_model');
        $this->load->library('Ajax_pagination');//items per page on pagination
        $this->perPage = 4;
        //check user credentials
        $is_logged_in = ($this->session->userdata('is_logged_in')) ? $this->session->userdata('is_logged_in') : FALSE;
        //if the user is not logged in
        if (!($is_logged_in)) {
        //check if cookie exist for login
            if (!$this->login->checkLoginWithCookie()) { 
            //otherwise redirect to login page
                redirect('Guests#login','refresh'); 
            }//end cookie
        }//end logged_in
    }

    /**
     * refreshNotifications method will reload notifications view with the latest count
     * this will only update the count of messages as they are specific to a user who is logged in
     * announcements will stay in the notification for 7 days even after they have been read
     * @return [string json_encoded] [this is the view that will display notifications]
     */
    public function refreshNotifications()
    {
        //get all the messages
        $data['messages'] = getMessageNotification();
        //get all announcements
        $data['announces'] = getAnnouncesNotification();
        //load the view which displays notifications
        $view = $this->load->view('notifications/notifications',$data,TRUE);
        echo json_encode($view);
    }
    public function getEventColorCategory(){
        $search['categories'] = html_escape($this->input->POST('category'));
        $data['groups'] = $this->calendar_model->getEventQuery($search);
        $eventDisply = $this->load->view('intercom/getEventColorCategory',$data,TRUE);
             echo json_encode($eventDisply);
    }
      
        //search phone book list
    public function getSearchMail(){
        $search['usersEmails'] = html_escape($this->input->POST('srchMgs'));
        $data['mailList'] = $this->Users_model->getUsers($search);
        $mails = $this->load->view('intercom/getSearchMail',$data,TRUE);
        echo json_encode($mails);
    }//end search book list

        //gets discussion comments
    public function getCommentInfo(){
        $topicName = html_escape($this->input->POST('topicName'));
        $search['topicName'] = $topicName;
        $data['comments'] = $this->comments_model->getComment($search);
        $data['likes'] = $this->comments_model->getLikes($search);
        $data['ratings'] = $this->comments_model->getRatings();
        $viewComment = $this->load->view('intercom/getCommentInfo',$data,TRUE);
        echo json_encode($viewComment);
    }//end gets discussion comments
    
   public function index()
    {
       //var_dump(identify_user_role($_SESSION['userID']));
       $data['pageToLoad'] = 'intercom/intercom';
       $data['pageActive'] = 'intercom';
       $data['colors'] = $this->calendar_model->getColorQuery();
       $this->load->view('ini',$data);
   }

   public function manageMsg(){
    $page = html_escape($this->input->POST('page'));
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }

        $image_array_msg = get_clickable_smileys(base_url().'assets/images/smileys','compose_messagea');
        $col_array = $this->table->make_columns($image_array_msg,20);
        $data['smiley_table'] = $this->table->generate($col_array);

        $search['user_id'] = $_SESSION['userID'];
        $search['msg'] = html_escape($this->input->POST('srchMgs')) ? html_escape($this->input->POST('srchMgs')) : '';
        $search['direct_inbox'] = !empty(html_escape($this->input->POST('srchMgs'))) ? html_escape($this->input->POST('srchMgs')) : '';
        $idRemove = html_escape($this->input->POST('inboxID'));
        $idRemoveSnt = html_escape($this->input->POST('outboxID'));
        $data['search'] = $search;
        $this->load->helper('form','url');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('checkMail[]','User Email','required');
        //total rows count
        $totalRec = count($this->message_model->getMessage(array(),$search));
        $data['msgCount']= $totalRec;
        //pagination configuration
        $config['target']      = '#msgMainView';
        $config['targetSearch']= '#msgMainViewSearch';
        $config['base_url']    = 'Intercom/manageMsg';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'msgMainView';
        $this->ajax_pagination->initialize($config);
        //get the post data
        $data['messages'] = $this->message_model->getMessage(array('start'=>$offset,'limit'=>$this->perPage),$search);
        $data['usersEmails'] = $this->Users_model->getUsers();
        $data['mail_links'] = $this->ajax_pagination->create_links();
        $data['msg_links'] = $this->ajax_pagination->create_links();
        $this->load->view('intercom/messages_main',$data);
        }

        public function onBlurGetUser(){
        $usersEmails = [];
        $receiver = [];
        $receiver = html_escape($this->input->POST('to'));
        //var_dump($receiver);
        $rmail = explode(';', $receiver);
        //var_dump($rmail);
        for ($i = 0; $i < count($rmail) ; $i++){
            $search['usersEmails'] = $rmail[$i];
            $usersEmails[$i] = $this->Users_model->getUsers($search);
            if ($usersEmails[$i] == NULL) {
                
            }
        }
        //var_dump($usersEmails);
         echo json_encode($usersEmails);
        }

        public function getUser(){
        $usersEmails = [];
        $receiver = html_escape($this->input->POST('selectedMail'));
        //var_dump($receiver);
        for ($i = 0; $i < count($receiver) ; $i++){
            $search['usersEmails'] = $receiver[$i];
            $usersEmails[$i] = $this->Users_model->getUsers($search);
        }
        
         echo json_encode($usersEmails);
        $page = html_escape($this->input->POST('page'));
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        $search['mail'] = html_escape($this->input->POST('srchMgs')) ? html_escape($this->input->POST('srchMgs')) : '';
        $data['search'] = $search;
        $this->load->helper('form','url');
        $this->load->library('form_validation');
        $totalRec = count($this->Users_model->getUsers($search));
        $data['mailCount']= $totalRec;
         //$search['user_id'] =$_SESSION['userID'];
        $email = html_escape($this->input->POST('to'));
        $emailr = html_escape($this->input->POST('rto'));
        $search['userEmail'] = $email;
        $search['userEmailr'] = $emailr;
        //pagination configuration
        $config['target']      = '#msgMainView';
        $config['targetSearch']= '#mailMainViewSearch';
        $config['base_url']    = 'Intercom/getUser';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'msgMainView';
        
    }

    public function outBoxMsgPagination(){
            $page = html_escape($this->input->POST('page'));
            if(!$page){
                $offset = 0;
            }else{
                $offset = $page;
            }
            $search['user_id'] = $_SESSION['userID'];
            $search['sent'] = !empty(html_escape($this->input->POST('srchMgs'))) ? html_escape($this->input->POST('srchMgs')) : '';
            $totalRec = $this->message_model->countSentMsg($search);
            $data['outCount'] = $totalRec;
            $config['target'] = '#msg3';
            $config['targetSearch'] = '#sentMainViewSearch';
            $config['base_url'] = 'Intercom/outBoxMsgPagination';
            $config['total_rows'] = $totalRec;
            $config['per_page'] = $this->perPage;
            $config['link_func'] = 'msgMainViewOut';
            $this->ajax_pagination->initialize($config);
            $data['outboxes'] = $this->message_model->getSentMessage(array('start'=>$offset,'limit'=>$this->perPage),$search);
            $data['direct_messages'] = $this->message_model->getDirectMsg();
            //var_dump($data['direct_messages']);
            $data['out_links'] = $this->ajax_pagination->create_links();
            echo $this->load->view('intercom/messages_sent',$data,TRUE);
        }
/**
 * [composeMsg description]
 * @return [type] [description]
 */

public function composeMsg(){
    //var_dump($this->input->post('receiverID'));
    $data['pageTitle'] = 'Create Message';
    $data['messageW'] = $this->message_model->getMessage();
    $config_validation = array(
        array(
            'field'=>'subjectMsgs',
            'label'=>'Subject',
            'rules'=>'required|max_length[100]|min_length[5]',
            'errors'=>array('required'=>'you should insert one %s for the description')        
        ),
        array(
            'field'=>'messagea',
            'label'=>'Message',
            'rules'=>'required|max_length[400]|min_length[10]',
            'errors'=>array('required'=>'you should insert one %s for the receiver.')        
        ),
    );

    $this->load->library('form_validation');
    $this->form_validation->set_rules($config_validation,'checkMail[]','User Email','required');
    if($this->form_validation->run()===FALSE){
        echo validation_errors();
    }else{
        //unifying direct message variables to database fields
        $msg = array(
                'title'=>html_escape($this->input->POST('subjectMsgs')),
                'body'=>html_escape($this->input->POST('messagea')),
                'to_user_id'=>html_escape($this->input->POST('receiverID'))
              );
        $msgInsert = $this->message_model->sendMsg($msg);
        if($msgInsert){
            echo 'YES';
        }
        else{
            echo 'NO';
        }
    }
}

public function replyMsg(){
    $data['pageTitle'] = 'Create Message';
    $data['messageW'] = $this->message_model->getMessage();
    $config_validation = array(
       array(
            'field'=>'subjectMsgs',
            'label'=>'Subject',
            'rules'=>'required|max_length[100]|min_length[5]',
            'errors'=>array('required'=>'you should insert one %s for the description')      
        ),
        array(
            'field'=>'messagea',
            'label'=>'Message',
            'rules'=>'required|max_length[400]|min_length[10]',
            'errors'=>array('required'=>'you should insert %s for the receiver.') 
        ),
    );

    $this->load->library('form_validation');
    $this->form_validation->set_rules($config_validation,'checkMail','User Email','required');
    if($this->form_validation->run()===FALSE){ 
        echo validation_errors();
    }else{

        $msg = array(
                'title'=>html_escape($this->input->POST('subjectMsgs')),
                'body'=>html_escape($this->input->POST('messagea')),
                'to_user_id'=>html_escape($this->input->POST('receiverID'))
              );
             
        $msgInsert = html_escape($this->message_model->sendMsg($msg));
        if($msgInsert){
            echo 'YES';
        }
        else{
            echo 'NO';
        }
    }
}
    //delete from inbox    
    public function askDelete(){
        //var_dump($this->input->POST('trashhMsg'));
        $inboxRule = array(
            array(
                    'field' => 'trashhMsg',
                    'label' => 'Inbox ID',
                    'rules'=>array('required',array('message_inbox_exist',array($this->message_model,'message_inbox_exist'))),
                    'errors' =>array('message_inbox_exist'=>'No such inbox exist in the system.')
                )
        );
        $this->form_validation->set_rules($inboxRule);
        if($this->form_validation->run()===FALSE){
        echo validation_errors();
    }else{
        $search['user_id_msg'] = $_SESSION['userID'];
        $search['ibx_id'] = !empty(html_escape($this->input->POST('trashhMsg'))) ? $this->input->POST('trashhMsg') : '';
        $idRemoveMsg = html_escape($this->input->POST('trashhMsg'));
        $deletingMsg = $this->message_model->removeFromMsgs($idRemoveMsg);
        if($deletingMsg){
                echo 'DELETED';
            }
            else{
               echo 'NO';
       } 
     }
   }//end delete inbox

        //ask delete sent messages
        public function askDeleteSent(){
        //var_dump($this->input->POST());
        $sentRule = array(
                array(
                            'field' => 'trashSent',
                            'label' => 'Sent Message ID',
                            'rules' =>array('required',array('message_sent_exist',array($this->message_model,'message_sent_exist'))),
                            'errors'=>array('message_sent_exist' => 'No such message exists in the system.')
                             )
                        );
            $this->form_validation->set_rules($sentRule);
            if($this->form_validation->run()===FALSE){
            echo validation_errors();
            }else{
                $search['user_snt_msg'] = $_SESSION['userID'];
                $search['snt_id'] = !empty(html_escape($this->input->POST('trashSent'))) ? $this->input->POST('trashSent') : '';
                $idRemoveMsgSent = html_escape($this->input->POST('trashSent'));
                $deletingMsgSent = $this->message_model->removeFromSent($idRemoveMsgSent);
                if($deletingMsgSent){
                echo 'DELETED';
            }
            else{
                echo 'NO';
            }
        }   
    }//end ask delete sent messages

    public function createAnnounce(){
         $config_validation = array(
            array(
                'field'=>'annTitled',
                'label'=>'Title',
                'rules'=>'required|max_length[100]|min_length[5]',
                'errors'=>array('required'=>'you should insert one %s for the Title')
            ),
            array(
                'field'=>'annBdy',
                'label'=>'Body',
                'rules'=>'required|max_length[1000]|min_length[10]',
                'errors'=>array('required'=>'insert %s for the Body')
            )
        );
            //set validation rules
            $this->form_validation->set_rules($config_validation);
            if ($this->form_validation->run()===FALSE) {
                //send errors to the modal
                echo validation_errors();
            }else {
                if($this->input->POST('annIDe') == ''){
                $announceInsert = $this->announce_model->createAnnounceM(html_escape($this->input->POST()));
                if ($announceInsert) {
                echo "ADDED";
            }else {
                echo "NO";
            }
        }else{
            //var_dump($this->input->POST());
            $updateAnnounce = $this->announce_model->updateAnnounce(html_escape($this->input->POST()));
            if($updateAnnounce){
                echo 'UPDATED';
            }else{
                echo 'NO';
            }
        }
      }
    }

    public function askDeleteAnnounce(){
        $rule = array(
            array(
                    'field' => 'trashAnn',
                    'label' => 'Announcement Trash ID',
                    'rules'=>array('required',array('announce_exist',array($this->trash_model,'announce_exist'))),
                    'errors' =>array('announce_exist'=>'No such Announcement exist in the system.')
                )
        );
         $this->form_validation->set_rules($rule);
        if($this->form_validation->run()===FALSE){
        echo validation_errors();
        }else{
        $idRemAnn = html_escape($this->input->POST('trashAnn'));
        if($idRemAnn !=0 && is_numeric($idRemAnn)){
            $deletingAnnoun = $this->announce_model->deleteAnnounce($idRemAnn);
        }
        if($deletingAnnoun){
            echo 'DELETED';
        }else{
            echo 'NO';
        }
      }
    }

    public function readAnnounce(){
            //var_dump($this->input->POST());
            $hasAnnounceRule = array(
                array(
                        'field' => 'readMeAnn',
                        'label' => 'Announcement ID',
                        'rules' =>array('required',array('hasannounce_exist',array($this->announce_model,'hasannounce_exist'))),
                        'errors' =>array('hasannounce_exist'=>'No such announcement exist in the system.')
                       )
                );
            $this->form_validation->set_rules($hasAnnounceRule);
            if($this->form_validation->run()===FALSE){
                echo validation_errors();
            }else{
           
            $search['announce_id'] = !empty(html_escape($this->input->POST('readMeAnn'))) ? $this->input->POST('readMeAnn') : '';
            $search['user_id_ann'] = $_SESSION['userID'];
            $readVariable = html_escape($this->input->POST());
            $announceResults = $this->announce_model->hasAnnounceQuery($search);
            if(empty($announceResults)){
            $readAnnounceq = $this->announce_model->announceMore($readVariable);
             if($readAnnounceq){
                echo 'READ';
            }
         }
      }
   }
    
    public function manageAnnounce(){
        $page = $this->input->POST('page');
         if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }

        $search['title'] = !empty(html_escape($this->input->POST('srchMgs'))) ? $this->input->POST('srchMgs') : '';
        $data['search'] = $search;
        $this->load->helper('form','url');
        $this->load->library('form_validation');
        //total rows count
        $totalRec = count($this->announce_model->getAnnounce(array(),$search));
        $data['annCount']= $totalRec;
        //pagination configuration
        $config['target']      = '#anouMainView';
        $config['targetSearch']= '#anouMainViewSearch';
        $config['base_url']    = 'Intercom/manageAnnounce';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'anouMainView';
        $this->ajax_pagination->initialize($config);
        //get the posts data
        $data['announces'] = $this->announce_model->getAnnounce(array('start'=>$offset,'limit'=>$this->perPage),$search);
        //var_dump($data['announces']);
        $data['hasAnnounces'] = $this->announce_model->hasAnnounceQuery();
        //var_dump($data['hasAnnounces']);
        $data['ann_links'] = $this->ajax_pagination->create_links();
        //load the view
        $this->load->view('intercom/announce_main',$data);
    }

    public function manageComment(){
        $page = html_escape($this->input->POST('page'));
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        $image_array_com = get_clickable_smileys(base_url().'assets/images/smileys','cm');
        $col_array = $this->table->make_columns($image_array_com,21);
        $data['smiley_table'] = $this->table->generate($col_array);
        $search['user_id'] = $_SESSION['userID'];

        $search['searchTopic'] = !empty(html_escape($this->input->POST('srchMgs'))) ? $this->input->POST('srchMgs') :  '';
        $search['topicMain'] = !empty(html_escape($this->input->POST('srchMgs'))) ? $this->input->POST('srchMgs') : '';
        $data['search'] = $search;
        $this->load->helper('form','url');
        $this->load->library('form_validation');
        //total rows count
        $totalRec = count($this->comments_model->getComment(array(),$search));
        $data['topicCount']= $totalRec;
        //pagination configuration
        $config['target']      = '#discusMainView';
        $config['targetSearch'] = '#discusMainViewSearch';
        $config['base_url']    = 'Intercom/manageComment';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'discusMainView';
        $this->ajax_pagination->initialize($config);
        //get the posts data
        $data['comments'] = $this->comments_model->getComment(array('start'=>$offset,'limit'=>$this->perPage),$search);
        $data['topics'] = $this->comments_model->getTableTopics(array('start'=>$offset,'limit'=>$this->perPage),$search);
        $data['selectTopics'] = $this->comments_model->getTableTopics();
        //build the initial table with topics, comments and user count
        $data['ct_table'] = $this->comments_model->getTableTopics(array('start'=>$offset,'limit'=>$this->perPage),$search);
        $data['topic_links'] = $this->ajax_pagination->create_links();
        //load the view
        $this->load->view('intercom/discuss_main',$data);
    }

    public function ratePost(){
            var_dump($this->input->POST());
            $hasRatedRule = array(
                array(
                        'field' => 'star',
                        'label' => 'Rating ID',
                        'rules' =>array('required',array('hasrating_exist',array($this->comments_model,'hasrating_exist'))),
                        'errors' =>array('hasrating_exist'=>'No such comment exist in the system.')
                       ),
                array(
                    'field' => 'comment_rate',
                    'label' => 'Comment ID',
                    'rules'=>array('required',array('comment_exist',array($this->comments_model,'comment_exist'))),
                    'errors' => array('comment_exist'=>'No such comment exist in the system.')
                 )
                );
            $this->form_validation->set_rules($hasRatedRule);
            if($this->form_validation->run()===FALSE){
                echo validation_errors();
            }else{
            $search['comment_star'] = !empty(html_escape($this->input->POST('comment_rate'))) ? $this->input->POST('comment_rate') : '';
            $search['rating_id'] = !empty(html_escape($this->input->POST('star'))) ? $this->input->POST('star') : '';
            $search['user_rate_id'] = $_SESSION['userID'];
            $rateVariable = html_escape($this->input->POST());
            $ratingResults = $this->comments_model->getRatings($search);
            if(empty($ratingResults)){
            $rateThisPost = $this->comments_model->giveStarPost($rateVariable);
             if($rateThisPost){
                echo 'RATED';
            }
         }
      }
   }

     public function writeComment(){
        $config_validation = array(
                    array(
                        'field'=>'cm',
                        'label'=>'Comment',
                        'rules'=>'required|max_length[1000]|min_length[10]',
                        'errors'=>array('required'=>'You have not provided %s for the comment')
                            ),
                        );
        $this->form_validation->set_rules($config_validation);
        if($this->form_validation->run()===FALSE){
            echo validation_errors();
        }else{
            if($this->input->POST('gtcid') == ''){
            $commentInsert = $this->comments_model->addComment(html_escape($this->input->POST()));
            if($commentInsert){
                echo 'ADDED';
            }
            else{
                echo 'NO';
            }
        }else{

            $updateStatus = $this->comments_model->updateComment(html_escape($this->input->POST()));
            if($updateStatus){
                echo 'UPDATED';
            }
            else{
                    echo 'NO';
                }
            }
        }
    }

        public function createTopic(){
        $data['pageToLoad'] = 'intercom/discussion_main';
        $data['pageActive'] = 'discussion_main';

        $config_validation = array(
            array(
                'field'=>'topicTitle',
                'label'=>'Title',
                'rules'=>'required|max_length[100]|min_length[5]',
                'errors'=>array('required'=>'you should insert one %s for the title')        
            ),
            array(
                'field'=>'topicDescription',
                'label'=>'Description',
                'rules'=>'required|max_length[1000]|min_length[10]',
                'errors'=>array('required'=>'you should insert one %s for the description')        
            )
        );
        $this->form_validation->set_rules($config_validation);
        if($this->form_validation->run()===FALSE){
            echo validation_errors();

        }else{

            $topicInsert = $this->topic_model->createTopic(html_escape($this->input->POST()));
                if($topicInsert){
                    echo 'ADDED';
                }
                else{
                    echo 'NO';
                }
            }
        }

        public function writeDiscuss(){
        $config_validation = array(
                    array(
                        'field'=>'edtitle',
                        'label'=>'Title',
                        'rules'=>'required|max_length[100]|min_length[5]',
                        'errors'=>array('required'=>'You have not provided %s for the inputbox')
                            ),
                    array(
                        'field'=>'descrDis',
                        'label'=>'Description',
                        'rules'=>'required|max_length[400]|min_length[10]',
                        'errors'=>array('required'=>'You have not provided %s for the textarea')
                            )
                        );
                    $this->form_validation->set_rules($config_validation);
                    if($this->form_validation->run()===FALSE){
                        echo validation_errors();
                    }else{
                        //var_dump($this->input->post());
                        if(html_escape($this->input->POST('editDiscHiddnID')) == ''){
                        $discussInsert = $this->comments_model->addDiscuss(html_escape($this->input->POST()));
                        if($discussInsert){
                            echo 'ADDED';
                        }
                        else{
                            echo 'NO';
                        }
                    }else{

                        $updateStatus = $this->comments_model->updateDiscuss(html_escape($this->input->POST()));
                        if($updateStatus){
                            echo 'UPDATED';
                        }
                        else{
                        echo 'NO';
                    }
                }
            }
        }

        public function askDeleteComm(){
        $commentRule = array(
            array(
                    'field' => 'trashCom',
                    'label' => 'Comment ID',
                    'rules'=>array('required',array('comment_exist',array($this->comments_model,'comment_exist'))),
                    'errors' => array('comment_exist'=>'No such comment exist in the system.')
                )
        );
         $this->form_validation->set_rules($commentRule);
        if($this->form_validation->run()===FALSE){
        echo validation_errors();

        }else{
        $commID = html_escape($this->input->POST('trashCom'));
        if($commID!=0 && is_numeric($commID)){
            $deletingComm = $this->comments_model->deleteComment($commID);
           }
        if($deletingComm){
                echo 'DELETED';
            }
            else{
                echo 'NO';
            }
          }
        }

        public function askDeleteDiscussion(){
            $discussionRule = array(
                array(
                    'field' => 'trashDisc',
                    'label' => 'Discussion ID',
                    'rules'=>array('required',array('discussion_exist',array($this->comments_model,'discussion_exist'))),
                    'errors' =>array('discussion_exist'=>'No such Topic exist in the system.')
                )
            );
            $this->form_validation->set_rules($discussionRule);
            if($this->form_validation->run()===FALSE){
                echo validation_errors();

            }else{
        $discID = html_escape($this->input->POST('trashDisc'));
        if($discID!=0 && is_numeric($discID)){
            $deletingDisc = $this->comments_model->deleteDiscussion($discID);
           }
        if($deletingDisc){
                echo 'DELETED';
            }
            else{
                echo 'NO';
            }
        }
    }

        public function manageCalendar(){
        $page = html_escape($this->input->POST('page'));
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        $search['user_id'] = $_SESSION['userID'];
        $this->load->helper('form','url');
        $this->load->library('form_validation');
        
        $data['colors'] = $this->calendar_model->getColorQuery();
        $data['schedules'] = $this->calendar_model->getTimeSchedule();
        $data['appointments'] = $this->calendar_model->getEventQuery();
        $this->load->view('intercom/calendar_modal',$data);
    }

        public function createEvent(){
          $search['user_id'] = $_SESSION['userID'];
          $config_validationw = array(
            array(
                'field'=>'selColor',
                'label'=>'Color',
                'rules'=>'required',
                'errors'=>array('required'=>'you should select one %s for the Color')        
            )
              );
            //set validation rules
            $this->form_validation->set_rules($config_validationw);
            if ($this->form_validation->run()===FALSE) {
                //send errors to the modal
                echo validation_errors();
            }else {
            $eventInsert = $this->calendar_model->addEventAppoint(html_escape($this->input->POST()));
            if ($eventInsert) {
                echo "ADDED";
            }else {
                echo "NO";
            }
          }
        }

        public function getEvents(){
            $search['user_id'] = $_SESSION['userID'];
            echo json_encode($this->calendar_model->getEventQuery($search));
        }

        public function event_update(){
            $search['user_id'] = $_SESSION['userID'];
            var_dump($this->input->POST());
            echo json_encode($this->calendar_model->eventUpdate(html_escape($this->input->POST()),$search));
        }

        public function eventDelete(){
            //var_dump($this->input->POST('eventid'));
            $eventDelRule = array(
                        array(
                               'field'=>'eventid',
                               'label'=>'Event Delete ID',
                               'rules'=>array('required',array('event_exist_del',array($this->calendar_model,'event_exist_del'))),
                               'errors'=>array('event_exist_del'=>'No such event in the system.')
                           )
                    );
            $this->form_validation->set_rules($eventDelRule);
            if($this->form_validation->run()===FALSE){
                echo validation_errors();
            }else{
            
            $idDelEvent = html_escape($this->input->POST('eventid'));
            $delEvnt = $this->calendar_model->delete_event($idDelEvent);
            if($delEvnt){
                echo 'DELETED';
            }else{
                echo 'NO';
            }
         }
      }//event validation end

        public function readMsg(){
            $search['user_id'] = $_SESSION['userID'];
            $read = $this->message_model->updateRead(html_escape($this->input->POST('readMe')));
            if($read){
                echo $read;
            }
        }

        public function manageLikes(){
            $search['commentID'] = html_escape($this->input->POST('commentID'));
            $search['userlike'] = html_escape($this->input->POST('userlike'));
            $likeExist = $this->comments_model->getLikes($search);
            if($likeExist != NULL){
                $like = $this->comments_model->updateLike(html_escape($this->input->POST()));
            }else{
               $like = $this->comments_model->likeComment(html_escape($this->input->POST()));
            }
            if($like){
                echo 'LIKED'; //json_encode($this->input->POST());
                }else{
                    echo 'NO';
                }
        }

        public function manageFAQs(){
            $page = $this->input->POST('page');
            if(!$page){
                $offset = 0;
            }else{
                $offset = $page;
            }
            
            $search['faq'] = !empty(html_escape($this->input->POST('srchMgs'))) ? html_escape($this->input->POST('srchMgs')) : '';
            $search['faqCategory'] = !empty(html_escape($this->input->POST('srchMgs'))) ? html_escape($this->input->POST('srchMgs')) : '';
            $faqTitle = html_escape($this->input->POST('form_faq'));
                
            $search['user_id'] = $_SESSION['userID'];
            $this->load->helper('form','url');
            $this->load->library('form_validation');
             //pagination configuration
            $config['target']      = '#FAQsMainView';
            $config['targetSearch'] = '#faqMainViewSearch';
            $config['base_url']    = 'Intercom/manageFAQs';
            //$config['total_rows']  = $totalRec;
            $config['per_page']    = $this->perPage;
            $config['link_func']   = 'FAQsMainView';
            $data['questions'] = $this->questions_model->askedFrequentlyQuery($search);
            $data['categories'] = $this->questions_model->frequentAskedCategory($search);
            $this->load->view('intercom/FAQ',$data);
        }

        public function writeFAQ(){
           $faqTitle = html_escape($this->input->POST('form_faq'));
           $config_validation = array(
            array(
                'field'=>'category_sel',
                'label'=>'FAQ Category',
                'rules'=>'required',
                'errors'=>array('required'=>'you should select one %s for the category')
            ),
            array(
                'field'=>'faq_title',
                'label'=>'Title',
                'rules'=>'required|max_length[100]|min_length[5]',
                'errors'=>array('required'=>'you should insert %s for the title input box')
            ),            
            array(
                 'field'=>'your_question',
                 'label'=>'Your Question',
                 'rules'=>'required|max_length[1000]|min_length[10]',
                 'errors'=>array('required'=>'You have not entered %s for the empty textarea')
                    )
              );
        //set validation rules
        $this->form_validation->set_rules($config_validation);
        if($this->form_validation->run()===FALSE){
            echo validation_errors();
        }else{
            
            $faqInsert = $this->questions_model->addQuestion(html_escape($this->input->POST()));
                echo 'ADDED';
           }
        }

        public function editFAQuestions(){
            $faqEdt = html_escape($this->input->POST());
            $config_validation = array(
            array(
                'field'=>'category_sel',
                'label'=>'FAQ Category',
                'rules'=>'required',
                'errors'=>array('required'=>'you should select one %s for the category')        
            ),
            array(
                'field'=>'faq_title',
                'label'=>'Title',
                'rules'=>'required|max_length[100]|min_length[5]',
                'errors'=>array('required'=>'you should edit %s for the input box')        
            ),            
            array(
                 'field'=>'your_question',
                 'label'=>'Your Question',
                 'rules'=>'required|max_length[1000]|min_length[10]',
                 'errors'=>array('required'=>'You have not edited %s for the textarea')
                    )
              );

            $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()===FALSE) {
            //$this->load->view('ini',$data);
            echo validation_errors();
        }else{
            $updateFaq = $this->questions_model->updateFAQuestion(html_escape($this->input->POST()));
            if($updateFaq){

                echo 'UPDATED';
            }
            else{
                echo 'NO';
            }
        }
    }

        //delete frequently asked question
        public function askDeleteFAQs(){
         $idRemFAQ = html_escape($this->input->POST('trashFAQ'));
         
         $faqRule = array(
            array(
                'field'=>'trashFAQ',
                'label'=>'FAQ ID',
                'rules'=>array('required',array('faq_exist',array($this->questions_model,'faq_exist'))),
                'errors'=>array('faq_exist'=>'No such question exist in the system.')
            )
        );
         $this->form_validation->set_rules($faqRule);
         if($this->form_validation->run()===FALSE){
            echo validation_errors();
            }else{
                $deletingQuestions = $this->questions_model->deleteFAQs($idRemFAQ);
             if($deletingQuestions){
               echo 'DELETED';
            }else{
               echo 'NO';
         }
      }
    }//end delete frequently asked question

    public function manageTrashedItems(){
        $page = $this->input->POST('page');
        if(!$page){
            $offset = 0;
         }else{
            $offset = $page;
        }
        
        $search['outboxID_trash'] = !empty(html_escape($this->input->POST('srchMgs'))) ? html_escape($this->input->POST('srchMgs')) : '';
        $search['sent_trash'] = !empty(html_escape($this->input->POST('srchMgs'))) ? html_escape($this->input->POST('srchMgs')) : '';
        $search['user_inbox_srch'] = !empty(html_escape($this->input->POST('srchMgs'))) ? html_escape($this->input->POST('srchMgs')) : '';
        $search['direct_msg_title'] = !empty(html_escape($this->input->POST('srchMgs'))) ? html_escape($this->input->POST('srchMgs')) : '';
        $search['announce_title'] = !empty(html_escape($this->input->POST('srchMgs'))) ? html_escape($this->input->POST('srchMgs')) : '';
        $search['event_del_title'] = !empty(html_escape($this->input->POST('srchMgs'))) ? html_escape($this->input->POST('srchMgs')) : '';
        $search['user_id_del'] = $_SESSION['userID'];
        $this->load->helper('form','url');
        $this->load->library('form_validation');
        //pagination configuration
        $config['target']      = '#trashMainView';
        $config['targetSearch'] = '#trashMainViewSearch';
        $config['base_url']    = 'Intercom/manageTrashedItems';
        //$config['total_rows'] = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'trashMainView';
        $data['trashedMsg'] = $this->trash_model->getTrashedMsgs(array('start'=>$offset,'limit'=>$this->perPage),$search);
        $data['trashedAnn'] = $this->trash_model->getAnnounceTrash(array('start'=>$offset,'limit'=>$this->perPage),$search);
        $data['trashedEvent'] = $this->trash_model->getTrasheEvent(array('start'=>$offset,'limit'=>$this->perPage),$search);
        $data['trashedSent'] = $this->trash_model->getTrashedSent(array('start'=>$offset,'limit'=>$this->perPage),$search);
        $this->load->view('intercom/myTrash',$data);
    }

    //restore to inbox    
        public function recycleMsg(){
        //var_dump($this->input->POST('restoreInbx'));
        $trashMsgRule = array(
                    array(
                        'field'=>'restoreInbx',
                        'label'=>'Inbox Trash ID',
                        'rules'=>array('required',array('restore_msg_exists',array($this->trash_model,'restore_msg_exists'))),
                        'errors'=>array('restore_msg_exists'=>'No such message exist in the system.')
                        )
                    );
        $this->form_validation->set_rules($trashMsgRule);
        if($this->form_validation->run()===FALSE){
        echo validation_errors();
        }else{
        $search['msg_id'] = !empty(html_escape($this->input->POST('restoreInbx'))) ? $this->input->POST('restoreInbx') : '';
        $search['sendr_id'] = $_SESSION['userID'];
        $idRestoreMsgin = html_escape($this->input->POST('restoreInbx'));
        $recylingMsg = $this->trash_model->restoreToMsgs($idRestoreMsgin);
        if($recylingMsg){
                echo 'RESTORED';
            }
            else{
                echo 'NO';
            }
        }
    }//end restore inbox

          //recycle sent messages
          public function recycleSentMsg(){
            //var_dump($this->input->POST('restoreOutbx'));
            $trashSentRule = array(
                        array(
                             'field'=>'restoreOutbx',
                             'label'=>'Sent Trash ID',
                             'rules'=>array('required',array('restore_sent_exists',array($this->trash_model,'restore_sent_exists'))),
                             'errors'=>array('restore_sent_exists'=>'No such sent message exist in the system.')
                         )
                    );
            $this->form_validation->set_rules($trashSentRule);
            if($this->form_validation->run()===FALSE){
                echo validation_errors();
            }else{
            $search['user_id_snt'] = $_SESSION['userID'];
            $search['snt_rmoved'] = !empty(html_escape($this->input->POST('restoreOutbx'))) ? $this->input->POST('restoreOutbx') : '';
            $idRestoreSent = html_escape($this->input->POST('restoreOutbx'));
            $recyclingSent = $this->trash_model->restoreToSent($idRestoreSent);
            if($recyclingSent){
                echo 'RESTORED';
            }else{
                echo 'NO';
             }
          }
        }//end recycle sent message

        //restore to announcements  
        public function recycleAnnouncement(){
        //var_dump($this->input->POST('restoreAnnTrshh'));
        $trashAnnRule = array(
                          array(
                                'field'=>'restoreAnnTrshh',
                                'label'=>'Announcement Restore',
                                'rules'=>array('required',array('announce_exist',array($this->trash_model,'announce_exist'))),
                                'errors'=>array('announce_exist'=>'No such announcement exist in the system.')
                              )
                          );
        $this->form_validation->set_rules($trashAnnRule);
        if($this->form_validation->run()===FALSE){
            echo validation_errors();
        }else{
        $search['user_ann'] = $_SESSION['userID'];
        $search['ann_recent'] = !empty(html_escape($this->input->POST('restoreAnnTrshh'))) ? $this->input->POST('restoreAnnTrshh') : ''; 
        $idRestoreAnn = html_escape($this->input->POST('restoreAnnTrshh'));
        $recylingAnn = $this->trash_model->restoreToAnnouncements($idRestoreAnn);
        if($recylingAnn){
                echo 'RESTORED';
            }
            else{
                echo 'NO';
        }
      }
    }//end restore announcements

        //restore to events    
        public function recycleEvent(){
        //var_dump($this->input->POST('cycleEvent'));
        $restoreEventRule = array(
                        array(
                            'field'=>'cycleEvent',
                            'label'=>'Event Restore ID',
                            'rules'=>array('required',array('event_exist',array($this->trash_model,'event_exist'))),
                            'errors'=>array('event_exist'=>'No such event exist in the system.')
                         )
                    );
        $this->form_validation->set_rules($restoreEventRule);
        if($this->form_validation->run()===FALSE){
            echo validation_errors();
        }else{
        $search['user_id_event'] = $_SESSION['userID'];
        $search['event_rmoved'] = !empty(html_escape($this->input->POST('cycleEvent'))) ? $this->input->POST('cycleEvent') : '';
        $idRestoreEvent = html_escape($this->input->POST('cycleEvent'));
        $recylingEvent = $this->trash_model->restoreToEvents($idRestoreEvent);
        if($recylingEvent){
                echo 'RESTORED';
            }
            else{
                echo 'NO';
            }
        }
     }//end restore to events
}
?>
