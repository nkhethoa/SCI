<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * summary
 */
class Users extends CI_Controller{
    public function __construct(){
      parent::__construct();
      //keep the values of the flashdata when going back and forth
      $this->session->keep_flashdata('learner');
      $this->session->keep_flashdata('guardian');
      //this model is used for anything subject related
      $this->load->model('Subject_model','subjects');
      //this model is used for anything teacher related
      $this->load->model('Teacher_model','teachers');
      //this model has users related data
      $this->load->model('Users_model');
      //this model is used for anything OUT of SYSTEM email related
      $this->load->model('Mail_model','mail');
      //this model is used for anything password related
      $this->load->model('Access_Model','access');
      //this is for anything admin related 
      $this->load->model('Admin_model','admin');
      //this model will have guardian related data calls
      $this->load->model('Guardian_model','guardians');
	    $this->load->model('Learner_model','learners');
	    $this->load->model('Login_model','login');
	    $this->load->model('Files_model','files');
      $this->load->model('Profile_model','profile');
	    $this->load->model('Level_Group_model','level_group');
      $this->load->model('Message_model','message_model');
      $this->load->model('Announce_model','announce_model');
	    $this->load->helper(array('form', 'url','security','date','text'));
	    $this->load->library(array('form_validation','email'));
        //check user credentials
        $is_logged_in = ($this->session->userdata('is_logged_in') != NULL) ? $this->session->userdata('is_logged_in') : FALSE;
        //if the user is not logged in
        if (!($is_logged_in)) { 
        //check if cookie exist for login
            if (!$this->login->checkLoginWithCookie()) { 
              //otherwise redirect to login page
              redirect('Guests#login','refresh'); 
            }//end cookie
        }//end logged_in

        //allow any user who is an admin
      if (identify_user_role($_SESSION['userID']) !=='admin' && (strpos(identify_user_role($_SESSION['userID']), 'admin')===false)) {
          redirect('App/');
      }
      
    }
  
  public function addLearner()
    {
      
      $data['pageToLoad']='admin/newLearner';
      $data['pageActive']='newLearner';
      //get relationship types that can exist between learner and guardian
      $data['relationship']=$this->guardians->getHowRelated();
      //get the registered levels 
      $data['levels']=$this->level_group->getclassLevel();
     //$data['groups']=$this->user->getclassGroups();
      $this->load->view('ini',$data);
    }

/**
 * getLevel method builds the dropdown for levels which are registered for the school
 * @return json string encode to the script
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

    /**
     * [add_new_learner method takes the input from the user
     * Do form validations as required
     * Load learner data into session variable for later processing
     */
    public function add_new_learner(){
      $data['pageToLoad']='Admin/newLearner';
      $data['pageActive']='newLearner';
      //get all the classlevels and
      $data['levels']=$this->level_group->getclassLevel();
      //will skip validation if the learner was part of school before
      $l_relation = html_escape($this->input->POST('learner_school_relation'));
      if ($l_relation == 1) {
          $rule1 = array(
             array(
                'field' => 'email',
                'label' => 'Learner Email',
                'rules' => array('required','valid_email','is_unique[user.email]'),
                'errors' => array('required'=>'Please provide valid %s address.',
                                   'is_unique'=>'the email address already exist on the system')
                ),
             array(
              'field' => 'doeLearnNo',
              'label' => 'Learner Number',
              'rules' => 'required|numeric|is_unique[learner.learnerNumber]',
              'errors' => array('required'=>'Please provide %s.')
              )
          );
          //set validation rules for this first set 
          $this->form_validation->set_rules($rule1);
       }

      $rule2 = array(
        array(
            'field'=>'learner_school_relation',
            'label'=>'Learner school',
            'rules'=>'required|numeric',
            'errors'=>array('required'=>'%s previous relationship must be selected')        
        ),
        array(
            'field'=>'firstName',
            'label'=>'Learner First Name',
            'rules'=>array('required',array('validChars',array($this->Users_model,'validateChars'))),
            'errors'=>array('validChars'=>'%s should contain characters and space only')        
        ),
         array(
            'field'=>'middleName',
            'label'=>'Learner Middle Name',
            'rules'=>array(array('validChars',array($this->Users_model,'validateChars'))),
            'errors'=>array('validChars'=>'%s should contain characters and space only')         
        ),
         array(
            'field'=>'lastName',
            'label'=>'Learner Last Name',
            'rules'=>array('required',array('validChars',array($this->Users_model,'validateChars'))),
            'errors'=>array('validChars'=>'%s should contain characters and space only')         
        ),
         array(
          'field' => 'id_type',
          'label' => 'Identity type',
          'rules' => 'required|numeric',
          'errors' => array('required'=>'Please select learner %s.')
        ),
         array(
            'field' => 'phone',
            'label' => 'Learner Phone',
            'rules' => 'required|numeric|exact_length[10]',
            'errors' => array('required'=>'Please provide valid %s number.')
            ),
          array(
            'field'=>'address',
            'label'=>'Physical Address',
            'rules'=>'required|alpha_numeric_spaces',
            'errors'=>array('required'=>'%s is required')        
        ),
          array(
            'field'=>'pcode',
            'label'=>'Learner Postal Code',
            'rules'=>array('required','is_natural','exact_length[4]'),
            'errors'=>array('is_natural'=>'%s can only be numbers')        
        ),
          array(
            'field'=>'city',
            'label'=>'Learner City',
            'rules'=>array('required',array('validChars',array($this->Users_model,'validateChars'))),
            'errors'=>array('validChars'=>'%s should contain characters and space only')     
        ),
        array(
            'field' => 'level',
            'label' => 'Learner class level',
            'rules' => 'required|numeric',
            'errors' => array('required'=>'Please select a %s.')
            ),
        array(
          'field' => 'group',
          'label' => 'Learner class group',
          'rules' => 'required|numeric',
          'errors' => array('required'=>'Please select a %s.')
          )
        
      );

      //get the id type of the user
      $id_type = !empty(html_escape($this->input->POST('id_type'))) ? html_escape($this->input->POST('id_type')) : '';
      //check which type is selected
      if ($id_type == 0 && $id_type != '') {
        $rule4 =array(
          array(
                'field' => 'l_passport',
                'label' => 'Identity Number',
                'rules' => array('required','alpha_numeric',
                                'max_length[9]','min_length[6]',
                                'is_unique[user.identityNumber]',
                                array('is_valid_passport',array($this->Users_model,'validate_passport')),
                                ),
                'errors' => array('required'=>'Please provide valid learner %s.',
                                   'is_unique'=>'User with this %s already exist on the system.',
                                   'is_valid_passport'=>'Learner %s you enterd is invalid'
                                 )
                )
        );
        $this->form_validation->set_rules($rule4);
      //RSA ID is selected
      }elseif ($id_type == 1 && $id_type != '') {
        $rule4 =array(
          array(
              'field' => 'l_idnumber',
              'label' => 'Identity Number',
              'rules' => array('required',
                              'exact_length[13]','is_unique[user.identityNumber]',
                              array('is_valid_id',array($this->Users_model,'verify_ID_number')),
                              ),
              'errors' => array('required'=>'Please provide valid learner %s.',
                                'is_unique'=>'User with this %s already exist on the system.',
                                'is_valid_id'=>'Learner %s you enterd is invalid'
                                )
              )
        );
        $this->form_validation->set_rules($rule4);
      }
    //set form validation rules
    $this->form_validation->set_rules($rule2);
    //check if all went well with the validation
    if($this->form_validation->run()===FALSE){
        //unset the session if there are any errors
        unset($_SESSION['learner']);
        //send all errors to the user
        echo validation_errors();
    }else{
      //assign the user input into session variable for later use
      $_SESSION['learner'] = html_escape($this->input->POST());
      //mark this session as flash data
      $this->session->mark_as_flash('learner');
    }
  }

  /**
   * new_guardian method will accept inputs from either new or existing guardian
   * Do validation and put the correct data into session variable for submission
   * if validation fails, display error messages for user to make immediate corrections
   * @return 
   */
   public function new_guardian(){
      //get the relationship types that can exist between guardian and the learner
      $data['relationship']=$this->guardians->getHowRelated();
        $config1 = array(
          //first guardian relationship with learner
         array(
          'field'=>'fg_related',
          'label'=>'First guardian Relationship',
          'rules' =>'required|numeric',
          'errors'=>array('required'=>'Please indicate %s with learner')        
        ),
         //first guardian relationship with school
         array(
          'field'=>'first_guard_school_relation',
          'label'=>'First guardian',
          'rules' =>'required|numeric',
          'errors'=>array('required'=>'Please indicate %s relationship with school')        
        ),
         array(
          'field'=>'fg_firstName',
          'label'=>'First guardian First Name',
          'rules'=>array('required',array('validChars',array($this->Users_model,'validateChars'))),
          'errors'=>array('validChars'=>'%s should contain characters and space only')        
        ),
         array(
          'field'=>'fg_middleName',
          'label'=>'First guardian Middle Name',
          'rules'=>array(array('validChars',array($this->Users_model,'validateChars'))),
          'errors'=>array('validChars'=>'%s should contain characters and space only')         
        ),
         array(
          'field'=>'fg_lastName',
          'label'=>'First guardian Last Name',
          'rules'=>array('required',array('validChars',array($this->Users_model,'validateChars'))),
          'errors'=>array('validChars'=>'%s should contain characters and space only')         
        ),
         array(
          'field' => 'id_type',
          'label' => 'Identity type',
          'rules' => 'required|numeric',
          'errors' => array('required'=>'Please select first guardian %s')
        ),
        array(
          'field' => 'fg_phone',
          'label' => 'First guardian Phone',
          'rules' => 'required|numeric|exact_length[10]',
          'errors' => array('required'=>'Please provide valid %s number.')
        ),
         //is guardian staying with learner?
         array(
          'field' => 'fg_learner_address',
          'label' => 'guardian address',
          'rules' => 'required|numeric',
          'errors' => array('required'=>'Is %s staying with the Learner? Select Yes or No')
        ),
        //validate if there is the second guardian
        array(
        'field'=>'sg_related',
        'label'=>'Relationship',
        'rules' => 'required|numeric',
        'errors'=>array('required'=>'Please indicate %s between learner and guardian')        
        )

       );
        
      //get relationship type between guardian and school
      $fg_school_relation = !empty(html_escape($this->input->POST('first_guard_school_relation'))) ? html_escape($this->input->POST('first_guard_school_relation')) : 0;
      
      //if not part of school, validate email address entered
      if ($fg_school_relation == 0) {
        $config6 = array( 
           array(
            'field' => 'fg_email',
            'label' => 'Email',
            'rules' => array('required','valid_email','is_unique[user.email]'),
            'errors' => array('required'=>'Please provide valid %s address.',
             'is_unique'=>'%s address already exist on the system')
          ),
        );
        //set specific rules for the this validation
        $this->form_validation->set_rules($config6);
      }

      //get the id type of the user
      $id_type = !empty(html_escape($this->input->POST('id_type'))) ? html_escape($this->input->POST('id_type')) : '';
      //check which type is selected
      if ($id_type == 0 && $id_type != '') {
        $config8 =array(
           array(
              'field' => 'fg_passport',
              'label' => 'Identity Number',
              'rules' => array('required','alpha_numeric',
                                'max_length[9]','min_length[6]',
                                'is_unique[user.identityNumber]',
                                array('is_valid_passport',array($this->Users_model,'validate_passport')),
                                ),
                'errors' => array('required'=>'Please provide valid %s.',
                                   'is_unique'=>'User with this %s already exist on the system.',
                                   'is_valid_passport'=>'First guardian %s you enterd is invalid'
                                 )
              )
        );
        $this->form_validation->set_rules($config8);
      //RSA ID is selected
      }elseif ($id_type == 1 && $id_type != '') {
        $config8 =array(
          array(
              'field' => 'fg_idnumber',
              'label' => 'Identity Number',
              'rules' => array('required',
                              'exact_length[13]','is_unique[user.identityNumber]',
                              array('is_valid_id',array($this->Users_model,'verify_ID_number')),
                              ),
              'errors' => array('required'=>'Please provide first guardian valid %s.',
                                'is_unique'=>'User with this %s already exist on the system.',
                                'is_valid_id'=>'First guardian %s you enterd is invalid'
                                )
              )
        );
        $this->form_validation->set_rules($config8);
      } 

        //if first guardian doesn't stay with the learner
        $fg_address = !empty(html_escape($this->input->POST('fg_learner_address'))) ? html_escape($this->input->POST('fg_learner_address')) : 0;
         if ($fg_address==0) {
          $config2 = array(          
            array(
              'field'=>'fg_address',
              'label'=>'First guardian Address',
              'rules'=>'required|alpha_numeric_spaces',
              'errors'=>array('required'=>'%s cannot be empty.')        
            ),
            array(
              'field'=>'fg_pcode',
              'label'=>'First guardian Postal Code',
              'rules'=>array('required','is_natural','exact_length[4]'),
              'errors'=>array('is_natural'=>'%s can only be numbers')        
            ),
            array(
              'field'=>'fg_city',
              'label'=>'First guardian City',
              'rules'=>array('required',array('validChars',array($this->Users_model,'validateChars'))),
              'errors'=>array('validChars'=>'%s should contain characters and space only')        
            )
          );
          //set specific rules for the this validation
          $this->form_validation->set_rules($config2);
        }
       
      //assign relationship type of the second guardian with learner
      $sg_how_related = !empty(html_escape($this->input->POST('sg_related'))) ? html_escape($this->input->POST('sg_related')) : 0;
      //check if any valid relationship with learner has been selected
      if ($sg_how_related > 0){
         $config3 = array( 
          array(
            'field'=>'second_guard_school_relation',
            'label'=>'Second guardian',
            'rules' => 'required|numeric',
            'errors'=>array('required'=>'Please indicate %s relationship with school')        
          ),
        );
        //set specific rules for the this validation
        $this->form_validation->set_rules($config3);
      }

      //check if the second guardian was/is part of the school
      $sg_school_relationship = !empty(html_escape($this->input->POST('second_guard_school_relation'))) ? html_escape($this->input->POST('second_guard_school_relation')) : 0;
      if ($sg_school_relationship == 1){
        $config5 = array( 
          array(
            'field' => 'sg_username',
            'label' => 'Second guardian',
            'rules' => array('required','valid_email'),
            'errors' => array('required'=>'Please provide %s valid existing username.')
          ),
        );
        //set specific rules for the this validation
         $this->form_validation->set_rules($config5);
      }

       $sg_username = !empty(html_escape($this->input->POST('sg_username'))) ? html_escape($this->input->POST('sg_username')) : '';
      //check if any valid relationship has been selected
      if ($sg_how_related > 0 && $sg_school_relationship == 0){
        $config7 = array(
        array(
          'field'=>'sg_firstName',
          'label'=>'Second guardian First Name',
          'rules'=>array('required',array('validChars',array($this->Users_model,'validateChars'))),
          'errors'=>array('validChars'=>'%s should contain characters and space only')        
        ),
        
        array(
          'field'=>'sg_middleName',
          'label'=>'Second guardian Middle Name',
          'rules'=>array(array('validChars',array($this->Users_model,'validateChars'))),
          'errors'=>array('validChars'=>'%s should contain characters and space only')         
        ),
        array(
          'field'=>'sg_lastName',
          'label'=>'Second guardian Last Name',
          'rules'=>array('required',array('validChars',array($this->Users_model,'validateChars'))),
          'errors'=>array('validChars'=>'%s should contain characters and space only')         
        ),
        array(
          'field' => 'id_type',
          'label' => 'Identity type',
          'rules' => 'required|numeric',
          'errors' => array('required'=>'Please select second guardian %s.')
        ),
        array(
          'field' => 'sg_phone',
          'label' => 'Second guardian Phone',
          'rules' => 'required|numeric|exact_length[10]',
          'errors' => array('required'=>'Please provide valid %s number.')
        ),
        //guardian staying with learner
        array(
          'field' => 'sg_learner_address',
          'label' => 'Second guardian',
          'rules' => 'required|numeric',
          'errors' => array('required'=>'Is %s staying with the Learner? Select Yes or No')
        ),
        array(
          'field' => 'sg_email',
          'label' => 'Second guardian',
          'rules' => array('required','valid_email','is_unique[user.email]'),
          'errors' => array('required'=>'Please provide %s valid email address.',
           'is_unique'=>'the email address already exist on the system')
        )
      );
      //set specific rules for the this validation
      $this->form_validation->set_rules($config7); 
    }
          

    //get the value to determine if the guardian is staying with learner
    $sg_address = !empty(html_escape($this->input->POST('sg_learner_address'))) ? html_escape($this->input->POST('sg_learner_address')) : 0;
    //check if the guardian is staying with the learner
    if ($sg_address == 0 && $sg_how_related > 0 && $sg_school_relationship == 0) {
      $config4 = array( 
        array(
          'field'=>'sg_address',
          'label'=>'Second guardian Address',
          'rules'=>'required|alpha_numeric_spaces',
          'errors'=>array('required'=>'you should insert one %s for the address')        
        ),
        array(
          'field'=>'sg_pcode',
          'label'=>'Second guardian Postal Code',
          'rules'=>array('required','is_natural','exact_length[4]'),
          'errors'=>array('is_natural'=>'%s can only be numbers')        
        ),
        array(
          'field'=>'sg_city',
          'label'=>'Second guardian City',
          'rules'=>array('required',array('validChars',array($this->Users_model,'validateChars'))),
          'errors'=>array('validChars'=>'%s should contain characters and space only')        
        ),
       
      );
      //set specific rules for the this validation
      $this->form_validation->set_rules($config4); 
    }

    //get the id type of the user
    $id_type = !empty(html_escape($this->input->POST('id_type'))) ? html_escape($this->input->POST('id_type')) : '';
    //check which type is selected
    if (($id_type == 0) && ($sg_how_related > 0)) {
      $config8 =array(
        array(
              'field' => 'sg_passport',
              'label' => 'Identity Number',
              'rules' => array('required','alpha_numeric',
                                'max_length[9]','min_length[6]',
                                'is_unique[user.identityNumber]',
                                array('is_valid_passport',array($this->Users_model,'validate_passport')),
                                ),
                'errors' => array('required'=>'Please provide valid %s.',
                                   'is_unique'=>'User with this %s already exist on the system.',
                                   'is_valid_passport'=>'Second guardian %s you enterd is invalid'
                                 )
              )
      );
      $this->form_validation->set_rules($config8);
    //RSA ID is selected
    }elseif (($id_type == 0) && ($sg_how_related > 0)) {
      $config8 =array(
        array(
              'field' => 'sg_idnumber',
              'label' => 'Identity Number',
              'rules' => array('required',
                              'exact_length[13]','is_unique[user.identityNumber]',
                              array('is_valid_id',array($this->Users_model,'verify_ID_number')),
                              ),
              'errors' => array('required'=>'Please provide second guardian valid %s.',
                                'is_unique'=>'User with this %s already exist on the system.',
                                'is_valid_id'=>'Second guardian %s you enterd is invalid'
                                )
              )
      );
      $this->form_validation->set_rules($config8);
    }  
   //general rules for adding a guardian
    $this->form_validation->set_rules($config1);
    //check if all went well with the validation
    if($this->form_validation->run()===FALSE){
        //unset the session if there are any errors
        unset($_SESSION['guardian']);
        //send all errors to the user
        echo validation_errors();
        //$this->load->view('ini',$data);

    }else{
      //assign guardian inputs into seassion guardian
      $_SESSION['guardian'] = html_escape($this->input->POST());
      //mark this session as flash data
      $this->session->mark_as_flash('guardian');
    }
  }

/**
 * [new_learner_guardian method will be responsible for 
 * @return [type] [description]
 */
  public function new_learner_guardian()
  {
    //check if both learner and guardian sessions have been set from previous submits
    if($this->session->flashdata('learner') && $this->session->flashdata('guardian')) {
      $learner = array();
      //assign contents of session into learner array
      $learner = $this->session->flashdata('learner');
      //check again if the array with learner data is not empty
      if (!empty($learner)) {
        //check if both level and group have values before sending them for search
        if(($learner['level'] != '' ) && ($learner['group'] != '')){
            //laod array search with level and groud to find class group level ID
            $search['level'] = $learner['level'];
            $search['group'] = $learner['group'];
            //get class group level ID
            $cgl = $this->level_group->getclassGroupLevel($search);
            //if this combination returns something
              if ($cgl != NULL) {
                //put together address from address fields
                $learner['address'] = $learner['address'].','
                                      .$learner['city'].','
                                      .$learner['pcode'];
                //check if passport was selected
                if ($learner['id_type'] == 0) {
                  //assign passport value as the ID
                  $learner['id_number'] = $learner['l_pass_number'];
                //check if RSA id was selected
                }elseif ($learner['id_type'] == 1) {
                  //assign RSA id number value as the ID
                  $learner['id_number'] = $learner['l_idnumber'];
                }
//*************************check if learner already exist************************************
                if ($learner['l_userID'] > 0) {
                  //assign the userID of the existing learner to search array
                  $search['learner'] = $learner['l_userID'];
                  //get the record of the learner
                  $existing_learner = $this->learners->getLearners($search);
                  //assign the learner number to the learner array
                  $learner['doeLearnNo'] = $existing_learner[0]->learnDoE_ID;
                  //assign the learner userID to the learner array
                  $learner['idUser'] = $learner['l_userID'];
                  //assign the existing username as the learner email address
                  $learner['email'] = $learner['l_username'];
                  //update the record on the database
                  $learnerInsert = $this->Users_model->updateUser($learner);
                  //check if update did not work 
                  if (!$learnerInsert) {
                    //set the insert status to false
                    $statusInsert = FALSE;
                    //need to redirect to another page to notify user when all is done
                    redirect('Users/addLearner?statusInsert=$statusInsert');
                  }
                //if no, create a new record
                }else {
                  //start process of creating new user
                  $l_userID = $this->Users_model->createUser($learner);
                  //did the insert work
                  if ($l_userID > 0) {
                    //assign the userID of the new learner to the learner array
                    $learner['idUser'] = $l_userID;
                    //set the insert status to false
                    $learnerInsert = TRUE;
                  }else{
                    //set the insert status to false
                    $statusInsert = FALSE;
                    //need to redirect to another page to notify user when all is done
                    redirect('Users/addLearner?statusInsert=$statusInsert');
                  }
                  
                }
//************************************************///*****************************************   
                
                if ($learnerInsert) {
                    //load class group level  and user id to learner array
                  $learner['cglID'] = $cgl[0]->cglID;
                  //insert learner
                  $learnerID = $this->learners->addLearner($learner);
                  //check if all went well with adding learner and prepare the messages as necessary
                  if ($learnerID > 0) {
                    //generatee token to be send by email
                    $token = bin2hex(openssl_random_pseudo_bytes(32));
                    //generate temporary [Lock Account] user password which will be changed on activation
                    $this->access->newUser_tempLogins($l_userID,$token,$learner);
                    //when the user is registered, send an email to confirm password
                    $learnerInsert = $this->mail->prepareEmail($token,$learner);
                    //if things didn't go well with insert
                  }else{
                    $statusInsert = FALSE;
                    //need to redirect to another page to notify user when all is done
                    redirect('Users/addLearner?statusInsert=$statusInsert');
                  }             
                }             
                                
              }else{
                $statusInsert = FALSE;
                //need to redirect to another page to notify user when all is done
                redirect('Users/addLearner?statusInsert=$statusInsert');
              }//if cgl not is NULL
              
          }else{
            //set the insert status to false
            $statusInsert = FALSE;
            //need to redirect to another page to notify user when all is done
            redirect('Users/addLearner?statusInsert=$statusInsert');
          }//if level and group are EMPTY
      }else{
        //set the insert status to false
        $statusInsert = FALSE;
        //need to redirect to another page to notify user when all is done
        redirect('Users/addLearner?statusInsert=$statusInsert');
      }//if learner is EMPTY
//*************************************************************************************************************
//*************************************************************************************************************
      //assign guardian session data into guardian variable
      $guardian = array();
      $guardian = $this->session->flashdata('guardian');
      //check first if the guardian is not empty
      if (!empty($guardian) && ($learnerInsert)) {
        //if the guardian is staying with the learner
        if ($guardian['fg_learner_address'] == 1) {
          //assign learner address to the guardian
          $guardian['fg_address'] = $learner['address'];
          //otherwise record guardian address
        }else {
          //put together address from address fields
          $guardian['fg_address'] = $guardian['fg_address'].','
                                    .$guardian['fg_city'].','
                                    .$guardian['fg_pcode'];
        }
        //create array with first guardian data
        $fg = array(
            "firstName"=>$guardian['fg_firstName'],
            "middleName"=>$guardian['fg_middleName'],
            "lastName"=>$guardian['fg_lastName'],
            "phone"=>$guardian['fg_phone'],
            "address"=>$guardian['fg_address']
          );
        
        //check if passport was selected
        if ($guardian['id_type'] == 0) {
          //assign passport value as the ID
          $fg['id_number'] = $guardian['fg_pass_number'];
        //check if RSA id was selected
        }elseif ($guardian['id_type'] == 1) {
          //assign RSA id number value as the ID
          $fg['id_number'] = $guardian['fg_idnumber'];
        }
        //if guardian exist, user the current username for email address
        if ($guardian['fg_userID'] > 0) {
          //assign the guardian existing username to be the email address
          $fg['email'] = $guardian['fg_username'];
          //make the userid of the guardian to be db table field friendly
          $fg['idUser'] = $guardian['fg_userID'];
          //update the record on the database
          $guardian_Update = $this->Users_model->updateUser($fg);
          //assign the userID of the existing guardian to search array
          $search['user'] = $guardian['fg_userID'];
          //get the record of the guardian
          //to check if this user doesn't exist in the guardian table
          $existing_fg = $this->guardians->getGuardian($search);
          //check if this user was a guardian
          if ($existing_fg != NULL) {
            //assign the guardian number
            $guardian_ID = $existing_fg[0]->guardID;
           //if this existing user is not a guardian 
          }else{
            //insert guardian
            $guardian_ID = $this->guardians->addGuardian($guardian['fg_userID']);
          }
          //only if the guardian ID returned something
          if ($guardian_ID > 0) {
              //pair learner with guardian
            $fg_learner_Insert = $this->learners->add_learn_guard($learnerID,$guardian_ID,$guardian['fg_related']);
            //set the insert status to true if all went well
            if ($fg_learner_Insert) {
              $statusInsert = TRUE;
            }else {
              $statusInsert = FALSE;
              //need to redirect to another page to notify user when all is done
              redirect('Users/addLearner?statusInsert=$statusInsert');
            }
          }else{
            $statusInsert = FALSE;
            //need to redirect to another page to notify user when all is done
            redirect('Users/addLearner?statusInsert=$statusInsert');
          }
          

        }else{
          //new email address in the db friendly name
          $fg['email'] = $guardian['fg_email'];
          //start process of creating new user
          $new_fg_userID = $this->Users_model->createUser($fg);
          //insert guardian
          $guardian_ID = $this->guardians->addGuardian($new_fg_userID);
          //check if there is a guardian to pair
          if ($guardian_ID > 0) {
            //pair learner with guardian
            $fg_learner_Insert = $this->learners->add_learn_guard($learnerID,$guardian_ID,$guardian['fg_related']);
            //check if all went well with first guardian insert
            if ($fg_learner_Insert) {
              //generate token to be send by email
              $token = bin2hex(openssl_random_pseudo_bytes(32));
              //generate temporary [Lock Account] user password which will be changed on activation
              $this->access->newUser_tempLogins($new_fg_userID,$token,$fg);
              //when the user is registered, send an email to confirm password
              $statusInsert = $this->mail->prepareEmail($token,$fg);
              //success message for when all went well
              //$_SESSION['guardian1'] = 'First guardian added successfully'; 
            }else{
              $statusInsert = FALSE;
              //need to redirect to another page to notify user when all is done
              redirect('Users/addLearner?statusInsert=$statusInsert');
            }
          }else{
            $statusInsert = FALSE;
            //need to redirect to another page to notify user when all is done
            redirect('Users/addLearner?statusInsert=$statusInsert');
          }//if guardian_ID>0
         

        }
        
        
        
//*************************************************************************************************
//                                        SECOND GUARDIAN
//**************************************************************************************************
        //check if the second guardian was inserted
        if ($guardian['sg_related'] != '' && $guardian['sg_related'] != 0) {
            //if the guardian is staying with the learner
          if ($guardian['sg_learner_address'] == 1) {
            //assign learner address to the guardian
            $guardian['sg_address'] = $learner['address'];
            //otherwise record guardian address
          }else {
            //put together address from address fields
            $guardian['sg_address'] = $guardian['sg_address'].','
                                .$guardian['sg_city'].','
                                .$guardian['sg_pcode'];
          }
          //create array with second guardian data
          $sg = array(
              "firstName"=>$guardian['sg_firstName'],
              "middleName"=>$guardian['sg_middleName'],
              "lastName"=>$guardian['sg_lastName'],
              "phone"=>$guardian['sg_phone'],
              "address"=>$guardian['sg_address']
            );
          //check if passport was selected
          if ($guardian['id_type'] == 0) {
            //assign passport value as the ID
            $sg['id_number'] = $guardian['sg_pass_number'];
          //check if RSA id was selected
          }elseif ($guardian['id_type'] == 1) {
            //assign RSA id number value as the ID
            $sg['id_number'] = $guardian['sg_idnumber'];
          }
          //if guardian exist, user the current username for email address
          if ($guardian['sg_userID'] > 0) {
            //assign the guardian existing username to be the email address
            $sg['email'] = $guardian['sg_username'];
            //make the userid of the guardian to be db table field friendly
            $sg['idUser'] = $guardian['sg_userID'];
            //update the record on the database
            $guardian_Update = $this->Users_model->updateUser($sg);
            //assign the userID of the existing guardian to search array
            $search['user'] = $guardian['sg_userID'];
            //get the record of the guardian
            //to check if this user doesn't exist in the guardian table
            $existing_sg = $this->guardians->getGuardian($search);
            //check if this user was a guardian
            if ($existing_sg != NULL) {
              //assign the guardian number
              $guardian_ID = $existing_sg[0]->guardID;
              //if this existing user is not a guardian 
            }else{
              //insert guardian
              $guardian_ID = $this->guardians->addGuardian($guardian['sg_userID']);
            }
            //check if there is a guadian to pair with learner
            if ($guardian_ID > 0) {
              //pair learner with guardian
              $sg_learner_Insert = $this->learners->add_learn_guard($learnerID,$guardian_ID,$guardian['sg_related']);
              //set the insert status to true if all went well
              if ($sg_learner_Insert) {
                $statusInsert = TRUE;
              }else {
                $statusInsert = FALSE;
                //need to redirect to another page to notify user when all is done
                redirect('Users/addLearner?statusInsert=$statusInsert');
              }
            }
            
          }else{
            //new email address in the db friendly name
            $sg['email'] = $guardian['sg_email'];
            //start process of creating new user
            $new_sg_userID = $this->Users_model->createUser($sg);
            //insert guardian
            $guardian_ID = $this->guardians->addGuardian($new_sg_userID);
              //check if guardian was entered before pairing with learner
            if ($guardian_ID > 0) {
              //pair learner with guardian
              $sg_Insert = $this->learners->add_learn_guard($learnerID,$guardian_ID,$guardian['sg_related']);
              //check if all went well with second guardian insert
              if ($sg_Insert) {
                //generatee token to be send by email
                $token = bin2hex(openssl_random_pseudo_bytes(32));
                //generate temporary [Lock Account] user password which will be changed on activation
                $this->access->newUser_tempLogins($new_sg_userID,$token,$sg);
                //when the user is registered, send an email to confirm password
                $statusInsert = $this->mail->prepareEmail($token,$sg);
                //set message to be sent to user
                //$_SESSION['guardian2'] = 'Second guardian added successfully';
              }else{
                $statusInsert = FALSE;
                //need to redirect to another page to notify user when all is done
                redirect('Users/addLearner?statusInsert=$statusInsert');
              }
            }else{
              $statusInsert = FALSE;
              //need to redirect to another page to notify user when all is done
              redirect('Users/addLearner?statusInsert=$statusInsert');
            }//guardian_ID >0

          }//close else
          

        }//end if second guardian
      }//end if guardian is empty
    
      //need to redirect to another page to notify user when all is done
      redirect('admin/Users?statusInsert=$statusInsert');
    }else{
      //load the current view if the variable are empty for user to try again
      redirect('admin/Users?statusInsert=$statusInsert');
    }

    
    //unset($_SESSION['learner']);
    //unset($_SESSION['guardian']);
    
  }
/**
 * /////////////////////////////////////////////////////////////////////////////
 *    *******************SEARCH FOR EXISTING USERS**************************
 *//////////////////////////////////////////////////////////////////////////////

 /**
  * [searchUsers this will be called by the script
  * to check if the user was part of the system or not
  * @return json encode 
  */
  public function searchUsers()
  {
    //assign the input email to the search array
    $search['usersEmails']=html_escape($this->input->POST('username'));
    //look for the user email and return results
    $user = $this->Users_model->getUsers($search);
    //write those results back to ajax
    echo json_encode($user);
  }
  /**
   * find_user  this will be called from jquery,
   * to check if the user is the learner or guardian
   * @return json
   */
  public function find_user()
  {
    $luserID= !empty(html_escape($this->input->POST('luserID'))) ? html_escape($this->input->POST('luserID')) : 0;
    //check if the learner userID is empty
    if ($luserID!=0) {
      //assign userID ti search array
      $search['learner']=$luserID;
      //get the record of the user 
      $learners = $this->learners->getLearners($search);
      //user is-a learner
      echo json_encode($learners); 
      
    }else{
      //var_dump($luserID);
      //var_dump(html_escape($this->input->POST('userID')));
      $search['learner'] = !empty(html_escape($this->input->POST('userID'))) ? html_escape($this->input->POST('userID')) : 0;
      //get the record of the user 
      $learners = $this->learners->getLearners($search);
      //extract the current year of the learner
      if ($learners!= NULL) {
        //get the current year of the learner
        $currentYear = $learners[0]->academicYear;
        //set the year cycle
        $year_cycle = date('Y').'-01-01';
        //check if the learner is not part of the current year system
        if ($currentYear >= $year_cycle ) {
          echo 'NO';//not a learner
        }else{
          echo 'YES'; //user is-a learner
        }
      }else{
        echo 'NO';//not a learner
      }
    }
  }
  /**
   * *********************************New Teacher********************************************
   */
  /**
   * add_new_teacher method will load add new teacher form
   */
  public function add_new_teacher()
    {  
        $data['pageToLoad'] = 'admin/addTeacher';
        $data['pageActive'] = 'addTeacher';
        //set title of the page
        $data['pageTitle'] = 'Add New Teacher';
        //get school registered levels
        $data['levels'] = $this->level_group->getclassLevel();
        //get schools subjects
        $data['schoolSubjects'] = $this->subjects->getschoolSubjects();
        //load the form with empty fields
        $this->load->view('ini',$data);

    }

/**
 * [add_user_teacher when all the fields are filled
 * this will do all neccessary validations
 * then if all OK, call methods to write data to db
 */
  public function add_user_teacher()
  { // add user to the system
    $data['pageToLoad'] = 'admin/addTeacher';
    $data['pageActive'] = 'addTeacher';
    $data['pageTitle'] = 'Add New Teacher';
    $config1=array(
           array(
              'field' => 't_firstName',
              'label' => 'First name',
              'rules'=>array('required',array('validChars',array($this->Users_model,'validateChars'))),
              'errors'=>array('validChars'=>'%s should contain characters and space only')
              ),           
              array(
              'field' => 't_lastName',
              'label' => 'Last name',
              'rules'=>array('required',array('validChars',array($this->Users_model,'validateChars'))),
              'errors'=>array('validChars'=>'%s should contain characters and space only')
              ),          
            array(
            'field'=>'t_middleName',
            'label'=>'Teacher Middle Name',
            'rules'=>array(array('validChars',array($this->Users_model,'validateChars'))),
            'errors'=>array('validChars'=>'%s should contain characters and space only')         
            ),
         array(
            'field' => 't_phone',
            'label' => 'Phone',
            'rules' => 'required|is_natural|exact_length[10]',
            'errors' => array('required'=>'Please provide valid %s number.')
            ),
           array(
            'field'=>'t_address',
            'label'=>'Physical Address',
            'rules'=>'required|alpha_numeric_spaces',
            'errors'=>array('alpha_numeric_spaces'=>'Separate %s with spaces.')        
          ),
           array(
          'field' => 'id_type',
          'label' => 'Identity type',
          'rules' => 'required|numeric',
          'errors' => array('required'=>'Please select %s')
        ),
          array(
            'field'=>'t_pcode',
            'label'=>'Postal Code',
            'rules'=>array('required','is_natural','exact_length[4]'),
            'errors'=>array('is_natural'=>'%s can only be numbers')        
          ),
         array(
            'field'=>'t_city',
            'label'=>'City',
            'rules'=>array('required',array('validChars',array($this->Users_model,'validateChars'))),
            'errors'=>array('validChars'=>'%s should contain characters and space only')        
          ),
          array(
            'field'=>'subject_y_n',
            'label'=>'Allocate subjects',
            'rules'=>'required',
            'errors'=>array('required'=>'Thuto-sci needs to know if you will %s now.')
          ),
        );
        //get the selected add subject option
        $allocate_subj = !empty(html_escape($this->input->POST('subject_y_n'))) ? html_escape($this->input->POST('subject_y_n')) : 0;
        //selected option is YES 
        if ($allocate_subj == 1) {
          $config2=array(
           array(
              'field' => 'selected_subjects[]',
              'label' => 'Selected subjects',
              'rules'=>array('required',
                       array('validChars',
                            array($this->teachers,'subjects_digitsOnly')
                          )
                      ),
            'errors'=>array('validChars'=>'Error: Please refresh your page, then start again or contact technical staff if error persists.')
              )
          ); 
                      
          //set validation rules for this config
          $this->form_validation->set_rules($config2);
        }
    
        //get the relation type between the school and teacher
        $teacher_school_relation = !empty(html_escape($this->input->POST('t_school_relation'))) ? html_escape($this->input->POST('t_school_relation')) : 0;
        //if teacher is not related to the school
        //Validate email  
        if ($teacher_school_relation == 0) {
          $config3 =array(
            array(
                  'field' => 't_email',
                  'label' => 'Email',
                  'rules' => array('required','valid_email','is_unique[user.email]'),
                  'errors' => array('required'=>'Please provide valid %s address.',
                                     'is_unique'=>'Thuto-sci already have this %s address')
                  )
          );
          $this->form_validation->set_rules($config3);
        }

        //get the id type of the user
        $id_type = !empty(html_escape($this->input->POST('id_type'))) ? html_escape($this->input->POST('id_type')) : '';
        //check which type is selected
        if ($id_type == 0) {
          $config3 =array(
            array(
                  'field' => 't_passport',
                  'label' => 'Passport Number',
                  'rules' => array('required','alpha_numeric',
                                'max_length[9]','min_length[6]',
                                'is_unique[user.identityNumber]',
                                array('is_valid_passport',array($this->Users_model,'validate_passport')),
                                ),
                  'errors' => array('required'=>'Please provide valid %s.',
                                   'is_unique'=>'User with this %s already exist on the system.',
                                   'is_valid_passport'=>'Teacher %s you enterd is invalid'
                                 )
            )
          );
          $this->form_validation->set_rules($config3);
        //RSA ID is selected
        }elseif ($id_type == 1) {
          $config3 =array(
            array(
              'field' => 't_sa_idnumber',
              'label' => 'Identity Number',
              'rules' => array('required',
                              'exact_length[13]','is_unique[user.identityNumber]',
                              array('is_valid_id',array($this->Users_model,'verify_ID_number')),
                              ),
              'errors' => array('required'=>'Please provide teacher valid %s.',
                                'is_unique'=>'User with this %s already exist on the system.',
                                'is_valid_id'=>'%s you enterd is invalid'
                                )
              )
          );
          $this->form_validation->set_rules($config3);
        }
        
    //set validation rules for the general fields
    $this->form_validation->set_rules($config1);
     
    //check if all went well with validation
    if ($this->form_validation->run()==FALSE) {
      //keep the current view so the user can correct errors  
      echo validation_errors();
    //else validation passed
    }else{
      //get teacher form inputs
      $userData = html_escape($this->input->POST());
      //get teacher selected subjects
      $teacher_subjects = html_escape($this->input->POST('selected_subjects'));
      //make data to be db fields friendly
      $teacher_data = array(
                  "firstName"=>$userData['t_firstName'],
                  "middleName"=>$userData['t_middleName'],
                  "lastName"=>$userData['t_lastName'],
                  "email"=>$userData['t_email'],
                  "phone"=>$userData['t_phone']
                );
      //put address in the right format
      $teacher_data['address'] = $userData['t_address'].','
                            .$userData['t_city'].','
                            .$userData['t_pcode'];
      //check if passport was selected
      if ($userData['id_type'] == 0) {
        //assign passport value as the ID
        $teacher_data['id_number'] = $userData['t_passport'];
      //check if RSA id was selected
      }elseif ($userData['id_type'] == 1) {
        //assign RSA id number value as the ID
        $teacher_data['id_number'] = $userData['t_sa_idnumber'];
      }
      //get the user id of existing user
      $userID = !empty($userData['t_userID'])? $userData['t_userID'] : 0;
      //check if the user already exist
      //If yes, do update of details and reset password
      if ($userID > 0) {
        //assign existing username as the user email address
        $teacher_data['email'] = $userData['t_username'];
        //set the user id to correct data field
        $teacher_data['idUser'] = $userID;
        //insert the user into the specific table
        $statusUpdate = $this->Users_model->updateUser($teacher_data);
        //check if update went well before proceeding to adding teacher to table
        if ($statusUpdate) {
          //assign the search array with user ID
          $search['user'] = $userID;
          //get the record of the teacher
          //to check if this user doesn't exist in the teacher table
          $exist_teacher = $this->teachers->getTeacher($search);
          //check if this user is part of teacher table
          if ($exist_teacher != NULL) {
            //delete existing record of this teacher 
            $this->teachers->deleteTeacher($userID);
            //insert teacher
            $teacherID = $this->teachers->addTeacher($userID);
          }else{
            //insert teacher
            $teacherID = $this->teachers->addTeacher($userID);
          }
          //return status to ajax
          $statusInsert = TRUE;
        } //end status update          
      //add new user to the system
      }else{
        //insert the user
        $id_user = $this->Users_model->createUser($teacher_data);
        //insert user as teacher
        $teacherID = $this->teachers->addTeacher($id_user);

        //check if insert of user to respective table went well
        if ($teacherID > 0) {
          //generatee token to be send by email
          $token = bin2hex(openssl_random_pseudo_bytes(32));
          //generate temporary [Lock Account] user password which will be changed on activation
          $this->access->newUser_tempLogins($id_user,$token,$teacher_data);
          //when the user is registered, send an email to confirm password
          $statusInsert = $this->mail->prepareEmail($token,$teacher_data); 
        }//end teacher > 0

      }//end else

      //check if we need to add subjects
      if (($userData['subject_y_n'] != '') && ($userData['subject_y_n'] == 1)) {
         //call method to insert selected teacher subjects 
         $statusInsert = $this->teachers->insert_cls($teacher_subjects,$teacherID);
         //check if all went OK, then send status
        
      }
      if ($statusInsert) {
        echo 'TRUE';
      }
      
    }//else of validation

  }//end add teacher
    /*////////////////////////////////////////////////////////////////////////////////////////////
     * ************************************ADD New ADMIN******************************************
     * ///////////////////////////////////////////////////////////////////////////////////////////
     */
    /**
     * [add_new_admin this will be used to lauch the admin new form
     */
    public function add_new_admin()
    {  
      $data['pageToLoad'] = 'admin/addAdmin';
      $data['pageActive'] = 'addAdmin';
      $data['pageTitle'] = 'Add System Administrator';
      $this->load->view('ini',$data);
    }

    /**
     * [add_admin_user this method will be used to do necessary validations
     * and also insert the admin record to the db
     */
    public function add_admin_user()
    { // add user to the system
        /*$data['pageToLoad'] = 'admin/addAdmin';
        $data['pageActive'] = 'addAdmin';
        $data['pageTitle'] = 'Add System Administrator';*/
//var_dump($this->input->POST());
        $config1=array(
              array(
                'field'=>'lastName',
                'label'=>'Last Name',
                'rules'=>array('required',
                            array('validChars',
                              array($this->Users_model,'validateChars'))),
                'errors'=>array('validChars'=>'%s should contain characters and space only')         
              ),            
              array(
                  'field' => 'firstName',
                  'label' => 'Firstname',
                  'rules'=>array('required',
                              array('validChars',
                                array($this->Users_model,'validateChars'))),
                  'errors'=>array('validChars'=>'%s should contain characters and space only')
                  ),              
              array(
                  'field' => 'middleName',
                  'label' => 'Middlename',
                  'rules'=>array(
                            array('validChars',
                              array($this->Users_model,'validateChars'))),
                  'errors'=>array('validChars'=>'%s should contain characters and space only')
                  ),
              array(
                'field' => 'id_type',
                'label' => 'Identity type',
                'rules' => 'required|numeric',
                'errors' => array('required'=>'Please select %s')
              ),
             array(
                'field' => 'phone',
                'label' => 'Phone',
                'rules' => 'required|numeric|exact_length[10]',
                'errors' => array('required'=>'Please provide valid %s number.')
                ),
               array(
                'field'=>'address',
                'label'=>'Physical Address',
                'rules'=>'required|alpha_numeric_spaces',
                'errors'=>array('required'=>'%s is required')        
              ),
              array(
                'field'=>'pcode',
                'label'=>'Postal Code',
                'rules'=>array('required','is_natural','exact_length[4]'),
                'errors'=>array('is_natural'=>'%s can only be numbers')        
              ),
              array(
                'field'=>'city',
                'label'=>'City',
                'rules'=>array('required',
                          array('validChars',
                          array($this->Users_model,'validateChars'))),
                'errors'=>array('validChars'=>'%s should contain characters and space only')
              ),
        );
        
        //get the relation type between the school and admin
        $admin_school_relation = !empty(html_escape($this->input->POST('admin_school_relation'))) ? html_escape($this->input->POST('admin_school_relation')) : 0;
        //if admin is not related to the school
        //Validate email
        if ($admin_school_relation == 0) {
          $config2 =array(
            array(
                  'field' => 'email',
                  'label' => 'Email',
                  'rules' => array('required','valid_email','is_unique[user.email]'),
                  'errors' => array('required'=>'Please provide valid %s address.',
                                     'is_unique'=>'Thuto-sci already have this %s address')
                  )
          );
          $this->form_validation->set_rules($config2);
        }
        
        //get the id type of the user
        $id_type = !empty(html_escape($this->input->POST('id_type'))) ? html_escape($this->input->POST('id_type')) : '';
        //check which type is selected
        if ($id_type == 0) {
          $config3 =array(
            array(
                  'field' => 'passport',
                  'label' => 'Identity Number',
                  'rules' => array('required','alpha_numeric',
                                'max_length[9]','min_length[6]',
                                'is_unique[user.identityNumber]',
                                array('is_valid_passport',array($this->Users_model,'validate_passport')),
                                ),
                'errors' => array('required'=>'Please provide valid %s.',
                                   'is_unique'=>'User with this %s already exist on the system.',
                                   'is_valid_passport'=>'%s you enterd is invalid'
                                 )
                  )
          );
          $this->form_validation->set_rules($config3);
        //RSA ID is selected
        }elseif ($id_type == 1) {
          $config4 =array(
            array(
              'field' => 'idnumber',
              'label' => 'Identity Number',
              'rules' => array('required',
                              'exact_length[13]','is_unique[user.identityNumber]',
                              array('is_valid_id',array($this->Users_model,'verify_ID_number')),
                              ),
              'errors' => array('required'=>'Please provide second guardian valid %s.',
                                'is_unique'=>'User with this %s already exist on the system.',
                                'is_valid_id'=>'%s you enterd is invalid'
                                )
              )
          );
          $this->form_validation->set_rules($config4);
        }
         $this->form_validation->set_rules($config1);
         //check if all went well with validation
        if ($this->form_validation->run()===FALSE) {
          //keep the current view so the user can correct errors
          //$this->load->view('ini',$data);
          echo validation_errors();
        //else validation passed
        }else{
          
          //$id_user=0;
          //get user data
          $userData = html_escape($this->input->POST());
          //get the userID 
          $userID = !empty(html_escape($this->input->POST('a_userID'))) ? html_escape($this->input->POST('a_userID')) : 0;
          //put address in the right format
          $userData['address'] = $userData['address'].','
                                  .$userData['city'].','
                                  .$userData['pcode'];
          
          //check if passport was selected
          if ($userData['id_type'] == 0) {
            //assign passport value as the ID
            $userData['id_number'] = $userData['passport'];
            //check if RSA id was selected
          }elseif ($userData['id_type'] == 1) {
            //assign RSA id number value as the ID
            $userData['id_number'] = $userData['idnumber'];
          }
          //check if the admin already exist
          if ($userID > 0) {
            //assign existing username as the user email address
            $userData['email'] = $userData['a_username'];
            //set the user id to correct data field
            $userData['idUser'] = $userID;
            //insert the user into the specific table
            $statusUpdate = $this->Users_model->updateUser($userData);
            //check if update went well before proceeding to adding admin to table
            if ($statusUpdate) {
              //assign the search array with user ID
              $search['user'] = $userID;
              //get the record of the guardian
              //to check if this user doesn't exist in the guardian table
              $exist_admin = $this->admin->getAdmin($search);
              //check if this user is part of admin table
              if ($exist_admin != NULL) {
                //delete existing record of this admin 
                $this->admin->deleteAdmin($userID);
                //insert admin
                $statusInsert = $this->admin->addAdmin($userID);
              }else{
                //insert admin
                $statusInsert = $this->admin->addAdmin($userID);
              }
              
            }           
          //add new user to the system
          }else{
            //insert the user into the specific table
            $id_user = $this->Users_model->createUser($userData);
            //insert admin
            $statusInsert = $this->admin->addAdmin($id_user);
            //check if insert of user to respective table went well
            if ($statusInsert) {
              //generatee token to be send by email
              $token = bin2hex(openssl_random_pseudo_bytes(32));
              //generate temporary [Lock Account] user password which will be changed on activation
              $this->access->newUser_tempLogins($id_user,$token,$userData);
              //when the user is registered, send an email to confirm password
              $statusInsert = $this->mail->prepareEmail($token,$userData);
            //if not set statusInsert to false
            }             
          }

          if ($statusInsert) {
            echo 'TRUE';
            //redirect('admin/Users?statusInsert=$statusInsert');
          }
          

      }//else of validation
        
  }//end add admin

  /**
   * add_teacher_subjects method will be used to allocate teacher subjects
   */
  public function add_teacher_subjects()
   {
      $config = array(
       array(
          'field' => 'selected_subjects[]',
          'label' => 'Selected subjects',
          'rules'=>array('required',
                   array('validChars',
                        array($this->teachers,'subjects_digitsOnly')
                      )
                  ),
        'errors'=>array('validChars'=>'Error: Please refresh your page, then start again or contact technical staff if error persists.')
          ),
       array(
          'field' => 'teacherID',
          'label' => 'teacher ID',
          'rules'=>array(array('teacher_exist',array($this->teachers,'teacher_exist')
                  )
        ),
          'errors'=>array('teacher_exist'=>'Error: Please refresh your page, then start again or contact technical staff if error persists.')
          )
      ); 

      $this->form_validation->set_rules($config);
      if ($this->form_validation->run()===FALSE) {
        echo validation_errors();    
      }else{
        //get teacher form inputs
        $teacher_subjects = html_escape($this->input->POST('selected_subjects'));
        $teacherID = html_escape($this->input->POST('teacherID'));
        //call method to insert selected teacher subjects 
        $statusInsert = $this->teachers->insert_cls($teacher_subjects,$teacherID);
        if ($statusInsert) {
          echo 'TRUE';
        }
      } 
  }
  /**
   * [editUser this method will be called when the user clicks on the edit button
   * @param  integer $id this is the ID of the user being edited
   */
  public function edit_user($id = 0)
  {
    $data['pageToLoad'] = 'admin/editUser';
    $data['pageActive'] = 'editUser';
    $data['pageTitle'] = 'Editing User';
    //check if the id being send is numeric
    if($id !=0 && is_numeric($id)){
      $data['id_user'] = $id;
    }else {
      $id = html_escape($this->input->POST('idUser'));
      $data['id_user'] = $id;
    }
    //assign the id of the user to be edited to search array
    $search['id_user'] = $id;
    $search['user'] = $id;
    //get user data
    $data['userData'] = $this->Users_model->getUsers($search);
    //var_dump($data['userData']);
    //loop thru user data and load it into edit variables
    foreach ($data['userData'] as $value) {
      $data['firstNameEdit'] = $value->fName;
      $data['middleNameEdit'] = $value->midName;
      $data['lastNameEdit'] = $value->lName;
      $data['emailEdit'] = $value->email;
      $data['phoneEdit'] = $value->phone;
      //put address according to the address fields
      $address = explode(',', $value->address);
      //assign each into to right address field
      if (isset($address[0]) && !empty($address[0])) {
        $data['addressEdit'] = $address[0];
      }
      if (isset($address[1]) && !empty($address[1])) {
        $data['cityEdit'] = $address[1];
      }
      if (isset($address[2]) && !empty($address[2])) {
        $data['pcodeEdit'] = $address[2];
      }
      
    }

      //identify the current roles of the user
      $admin= $this->admin->countAdmin($search);
      $guardian= $this->guardians->countGuardian($search);
      $learner= $this->learners->countLearners($search);
      $teacher= $this->teachers->countTeachers($search);
      //if it returns admin, then send data to highlight role
      if ($admin > 0) {
        $data['a_role'] = 'admin';
      }
      //if it returns guardian, then send data to highlight role
      if ($guardian > 0) {
        $data['g_role'] = 'guardian';
      }
      //if it returns learner, then send data to highlight role
      if ($learner > 0) {
        $data['l_role'] = 'learner';
      }
      //if it returns teacher, then send data to highlight role
      if ($teacher > 0) {
        $data['t_role'] = 'teacher';
      }

    //start configuration of form validation
    $config = array(
          array(
                'field'=>'lastName',
                'label'=>'Last Name',
                'rules'=>array('required',
                            array('validChars',
                              array($this->Users_model,'validateChars'))),
                'errors'=>array('validChars'=>'%s should contain characters and space only')         
              ),            
              array(
                  'field' => 'firstName',
                  'label' => 'First name',
                  'rules'=>array('required',
                              array('validChars',
                                array($this->Users_model,'validateChars'))),
                  'errors'=>array('validChars'=>'%s should contain characters and space only')
                  ),              
              array(
                  'field' => 'middleName',
                  'label' => 'Middle name',
                  'rules'=>array(
                            array('validChars',
                              array($this->Users_model,'validateChars'))),
                  'errors'=>array('validChars'=>'%s should contain characters and space only')
                  ),
            
             array(
                'field' => 'phone',
                'label' => 'Phone',
                'rules' => 'required|numeric|exact_length[10]',
                'errors' => array('required'=>'Please provide valid %s number.')
                ),
               array(
                'field'=>'address',
                'label'=>'Physical Address',
                'rules'=>'required|alpha_numeric_spaces',
                'errors'=>array('required'=>'%s is required')        
              ),
              array(
                'field'=>'pcode',
                'label'=>'Postal Code',
                'rules'=>array('required','is_natural','exact_length[4]'),
                'errors'=>array('is_natural'=>'%s can only be numbers')        
              ),
              array(
                'field'=>'city',
                'label'=>'City',
                'rules'=>array('required',
                          array('validChars',
                            array($this->Users_model,'validateChars'))),
                'errors'=>array('validChars'=>'%s should contain characters and space only')
              ),
              
      );
      //set validation rules
      $this->form_validation->set_rules($config);
      if ($this->form_validation->run()==FALSE) {
        //load the current view if there are errors on the form
        $this->load->view('ini',$data);
      }else{
        //get the selected roles
        $role = html_escape($this->input->POST('role'));
         //get user data
        $userData = html_escape($this->input->POST());

        //put address in the right format
        $userData['address'] = $userData['address'].','
                              .$userData['city'].','
                              .$userData['pcode'];

        $statusEdit= $this->Users_model->updateUser($userData);
        //if the user has been updated, redirect to another page     
        if ($statusEdit) {
          //redirect to admin page
          redirect('admin/Users?statusEdit=$statusEdit');
        }
        
      }
    

    
  }
  /**
   * [reactivate method will be used to re-activate all the users who have been deleted
   * this are users who needs to come back to the system on the same or different role
   */
  public function activate_deleted_user()
    {
    //loads the manageUsers page
       
        $data['pageToLoad'] = 'admin/reactivateUsers';
        //active on the nav
        $data['pageActive'] = 'reactivateUsers';
        //search the user
        $search['user']=!empty(html_escape($this->input->get('search'))) ? html_escape($this->input->GET('search')) : '';
        $search['id_user']=!empty(html_escape($this->input->get('id_user'))) ? html_escape($this->input->GET('id_user')) : 0;
            //}
        $search['page'] = !empty(html_escape($this->input->get('page'))) ? html_escape($this->input->GET('page')) : 0;
        $data['search']=$search;
        $data['Users']=$this->Users_model->get_deleted_users($search);
        $data['countUsers'] = $this->Users_model->count_deleted_users($search);
            //this is to enable rewrite queries
        $config['enable_query_string'] = TRUE;
        //show the actual page number, ?page =someInt
        $config['page_query_string'] = TRUE;
        //url that use the pagination
        $config['base_url'] = base_url('Users/activate_deleted_user?search='.$search['user']);
        //number of results to be on the pagination
        $config['total_rows'] = $data['countUsers'];
        $this->load->library('pagination');
        //initialize the pagination with our config
        $this->pagination->initialize($config);
        $data['search_pagination']=$this->pagination->create_links();

        $user_to_activate = !empty(html_escape($this->input->post('userid'))) ? html_escape($this->input->post('userid')) : 0;
        if ($user_to_activate != 0  && is_numeric($user_to_activate)) {
          //method to activate deleted user [undelete user]
          $editStatus= $this->Users_model->activateUser($user_to_activate );
            //redirect('admin/manageSubjects');
            if($editStatus)
            {
                  echo 'yes';
              }else {
                  echo 'no';
              }
        }
        
        $this->load->view('ini',$data);
    
    } 

}