<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * summary
 */
class Teachers extends CI_Controller{
	public function __construct(){
    parent::__construct();
    //this model is used for anything teacher related
    $this->load->model('Teacher_model','teachers');
    //this model is used for user related data calls
    $this->load->model('Users_model');
    //this model will have guardian related data calls
    $this->load->model('Guardian_model','guardians');
    //for groups and levels related data
    $this->load->model('Level_Group_model','level_group');
    //for learner related data
    $this->load->model('Learner_model','learners');
     //this model is used for anything subject related
    $this->load->model('Subject_model','subjects');
    $this->load->model('Admin_model','admin');
    $this->load->model('Login_model','login');
    $this->load->model('Files_model','files');
    $this->load->model('Message_model','message_model');
    $this->load->model('Announce_model','announce_model');
    $this->load->model('Profile_model','profile');
    $this->load->helper(array('form','text','url','date'));
    $this->load->library(array('form_validation','email'));
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

    }//end constructor
  
    public function teachers()
    {//bring the list of all the teachers
        $data['pageToLoad'] = 'admin/teacherList';
        $data['pageActive'] = 'teacherList';
        $search['profile']= $_SESSION['userID'];
        $data['userProfile']=$this->profile->getProfile($search);
        //var_dump($data['userProfile']);
        $data['teachers']=$this->teachers->getTeacher();
        //$data['teacherSubjects']=$this->subjects->getSubject();
        $data['levels']=$this->level_group->getclassLevel();
        $data['classTeacher']=$this->teachers->getClassTeacherClass();
        $data['schoolSubjects']=$this->subjects->getschoolSubjects();
        //var_dump($data['classTeacher']);
        $this->load->view('ini',$data); 
    }

     /**
     * [getLevel called when the user select level when adding a learner]
     * 
     */
    public function getLevel()
    {
        //assign selected level into search array
        $search['level'] = !empty(html_escape($this->input->post('levelId'))) ? html_escape($this->input->post('levelId')) : 0;
        //use the search array to look specifi values
        $data['groupLevel'] = $this->level_group->getclassGroupLevel($search);
        //assign the view to the the variable to be returned to the caller
        $select = $this->load->view('subject_level/getLevel',$data,TRUE);
        //return the results to the caller   
        echo json_encode($select);

    }
  
    //assign Class

   public function assignClass()
    { // assign class to the teacher
        $config_validation=array(
            array(
                'field' => 'teacherID',
                'label' => 'teacherID',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide  %s.')
                ),
             array(
                'field' => 'level',
                'label' => 'teacherLevel',
                'rules' => 'required',
                'errors' => array('required'=>'Please select  %s.')
                ), 
             array(
                'field' => 'group',
                'label' => 'teacherGroup',
                'rules' => 'required',
                'errors' => array('required'=>'Please select  %s.')
                ),
              array(
                'field' => 'date',
                'label' => 'date',
                'rules' => 'required',
                'errors' => array('required'=>'Please provide  %s.')
                ),
           
            

              );
         $this->form_validation->set_rules($config_validation);
        if ($this->form_validation->run()==FALSE) {
            //$this->load->view('ini',$data);
            echo validation_errors();
        }else{
                //array class teacher data
                $ctd = html_escape($this->input->POST());
                //assign search array variables
                $search['teacherID'] = $ctd['teacherID'];
                //get class group level ID
                $teacherUpdate = $this->teachers->getClassTeacherClass($search);
                //assign search variables for level and group
                $search['level'] = $ctd['level'];
                $search['group'] = $ctd['group'];
                //get the class group level based on selected level and group
                $cgl = $this->level_group->getclassGroupLevel($search);
                //if this combination returns something
                  if ($cgl != NULL) {
                    //if the teacher already exist with another class
                    if (count($teacherUpdate) > 0) {
                      //delete existing class teacher role 
                      $deleteStatus= $this->teachers->deleteClassTeacher($ctd['teacherID']);
                      //check if the role has been removed
                      if ($deleteStatus) {
                        //assign new class teacher role
                        $insertStatus= $this->teachers->assignedClass($ctd,$cgl[0]->cglID);
                      }
                      //if all went well
                     if($insertStatus){
                        echo 'YES';
                      }else
                      {
                        echo 'NO';
                      }
                      
                    }else{
                      //insert
                      $insertStatus= $this->teachers->assignedClass($ctd,$cgl[0]->cglID);
                      //if all went well
                      if($insertStatus){
                        echo 'YES';
                      }else
                      {
                        echo 'NO';
                      }


                    }

                   
                  }else{
                     echo 'NO';
                  }
         }
    }

    public function getTeacherSubjects()
    {
       // //assign selected level into search array
      $search['teacher']=!empty(html_escape($this->input->post('teachId'))) ? html_escape($this->input->post('teachId')) : 0;
      //use the search array to look specifi values
      $subjects= $this->subjects->getSubject($search);
      
      //start with select
      $lbl  = '';
      //check if there are values to display
      if ($subjects != null) {
        $lbl .='<label>Teaches &nbsp;</label><br>';
          foreach($subjects as $teacherSubjects){ 
            $lbl .= "<label class='todoItem'><span><i class='fa fa-book'></i></span>&nbsp;$teacherSubjects->subjectName
           <span>In &nbsp;</span>$teacherSubjects->cgName
            $teacherSubjects->level</label><br>";
          }
        }else{
            //if no values found
            $lbl  .= "<label>No Classes assigned yet...check later</label><span>&nbsp;&nbsp;<i class='fa fa-exclamation-triangle fa-2x' style='color:yellow; background-color:black;'></i></span>";
        }
            
        echo json_encode($lbl);
    }

            // send parent message
public function sendTeacherMessage()
{ 
       $config_validation = array(
         array( 
            'field' => 'tsubj',
            'label' => 'Subject',
            'rules' => 'required|min_length[5]', 
            'errors' => array('required'=>'Subject please.')
            ),
          array( 
            'field' => 'tmsg',
            'label' => 'Message',
            'rules' => 'required|min_length[5]|alpha_numeric_spaces', 
            'errors' => array('required'=>'Please say something, even HELLO will do.')
            )
    );
    //set validation rules
    $this->form_validation->set_rules($config_validation);
    if ($this->form_validation->run()===FALSE) {
        //send errors to the modal
        echo validation_errors();

    }else {
       
        //check if we on add message
        if($this->input->post('mess_ID')==''){
          //assign message variables into db friendly variables
            $msg = array(
                'title'=>html_escape($this->input->post('tsubj')),
                'body'=>html_escape($this->input->post('tmsg')),
                'to_user_id'=>html_escape($this->input->post('userTeachID'))
              );
            $insertStatus = $this->message_model->sendMsg($msg);
            //send feedback based on insert
             if ($insertStatus) {
                echo "Added";
            }else {
                echo "NO";
            }
        }
      }
    }
}
?>