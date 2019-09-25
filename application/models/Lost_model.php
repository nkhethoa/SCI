<?php
 defined('BASEPATH') OR exit('No direct script access allowed');

class Lost_model extends CI_Model{
 public function __construct(){
    parent:: __construct();
    $this->load->database();
 }

 public function getAssignments(){

    return  $this->db->select("assignment.id as assignID, assignment.title as assignTitle,assignment.description as assignDesc, assignment.publishDate as publishDate, assignment.dueDate as dueDate,assignment.filesID as fileID,files.fileName as fileName,files.fileSize as fileSize,files.fileType as fileType,files.filePath as filePath, assignment.classLevelSubjectsID as clsID,classLevelSubjects.subjectID as subjectID,classLevelSubjects.teacherID as clsTeacherID,classLevelSubjects.classGroupLevelID as cglID,classGroupLevel.levelID as levelID,level.level as levelDesc,classGroupLevel.limit as cglLimit ,classGroupLevel.classGroupID as cgID,classGroup.className as cgClassName,subject.name as subjectName,teacher.userID as tUserID, user.firstName as firstName, user.lastName as lastName")
                    ->from("assignment")
                    ->join("files", "files.id = assignment.filesID")
                    ->join("classlevelsubjects", "classlevelsubjects.id = assignment.classLevelSubjectsID")
                    ->join("classgrouplevel", "classgrouplevel.id = classLevelSubjects.classGroupLevelID")
                    ->join("subject", "subject.id = classLevelSubjects.subjectID")
                    ->join("classgroup", "classgroup.id = classGroupLevel.classGroupID")
                    ->join("teacher", "teacher.id = classLevelSubjects.teacherID")
                    ->join("level", "level.id = classGroupLevel.levelID")
                    ->join("user", "user.id = teacher.UserID")
                    ->order_by("assignment.id","DESC")
                    ->get()->result();
 
 }

public function getAssignSubmission(){

    return  $this->db->select("assignsubmission.id as submitID, assignsubmission.date as submitDate, assignsubmission.learnerID as lID, learner.userID as lUserID, assignsubmission.assignmentID as assignID, assignsubmission.submitted as submitStatus, user.firstName as firstName, user.lastName as lastName, assignsubmission.filesID as fileID, files.fileName as fileName,files.fileSize as fileSize, files.fileType as fileType, files.filePath as filePath, learner.classGroupLevelID as cglID, learner.startdate as LstartDate, cgl.levelID as levelID, cgl.classGroupID as cgID, cgl.limit as limit, classgroup.className as cgName, assignment.title as assignTitle, assignment.description as assignDesc, assignment.dueDate as dueDate, assignment.publishDate as publishDate, assignment.classLevelSubjectsID as clsID, classLevelSubjects.subjectID as subjectID, subject.name as subjectName ")
                    ->from("assignsubmission")
                    ->from("assignment")
                    ->join("files", "files.id = assignSubmission.filesID")
                    ->join("learner", "learner.id = assignSubmission.learnerID")
                    ->join("classGroupLevel as cgl", "cgl.id = learner.classGroupLevelID")
                    ->join("level", "level.id = cgl.levelID")
                    ->join("classgroup", "classgroup.id = cgl.classGroupID")
                    ->join("classlevelsubjects", "classlevelsubjects.id = assignment.classLevelSubjectsID")
                    ->join("subject", "subject.id = classLevelSubjects.subjectID")
                    ->join("user", "user.id = learner.userID")
                    ->get()->result();
 
 }

public function getAssignGrades()
{
    return $this->db->select("assignmentassignsubmissiongrades.id assignGradeID, assignmentassignsubmissiongrades.assignmentID, assignmentassignsubmissiongrades.grade as assignGrade,assignment.id as assignID,assignment.title as assignTitle,assignment.description as assignDesc,assignment.dueDate as dueDate,assignment.publishDate as publishDate,assignment.classlevelsubjectsID as clsID,assignment.filesID as filesID,classlevelsubjects.subjectID, classlevelsubjects.teacherID, classlevelsubjects.classGroupLevelID as cglID, subject.name as subjectName, classgrouplevel.limit, classgrouplevel.levelID as levelID, classgrouplevel.classGroupID, classgroup.className as cgName, level.level as level,learner.id as learnerID,user.lastName as lastName, user.firstName as firstName  ")
        ->from("assignmentassignsubmissiongrades")
        ->join('assignment','assignment.id = assignmentassignsubmissiongrades.assignmentID')
        ->join("classlevelsubjects", "classlevelsubjects.id = assignment.classLevelSubjectsID")
        ->join("subject", "subject.id = classLevelSubjects.subjectID")
        ->join("classgrouplevel", "classgrouplevel.id = classlevelsubjects.classGroupLevelID")
        ->join("level", "level.id = classgrouplevel.levelID")
        ->join("classgroup", "classgroup.id = classgrouplevel.classGroupID")
        ->join("assignSubmission", "assignSubmission.id = assignmentassignsubmissiongrades.assignSubmissionID")
        ->join("learner", "learner.id = assignSubmission.learnerID")
        ->join("classgrouplevel as cgl", "cgl.id = learner.classGroupLevelID")
        ->join("user", "user.id = learner.userID")
        
        //->where('teacher.id',3)
        ->get()->result();
}
 
public function getLearnerAttendance(){
 
    return  $this->db->select(" attendance.id as attendID, attendance.date as attendDate, avg(attendance.status) as attendStatus, attendance.learnerID as learnerID, attendance.classLevelSubjectsID as clsID,classLevelSubjects.subjectID as subjectID, subject.name as subjectName,learner.userID as lUserID, learner.id as learnerID, user.firstName as firstName, user.lastName as lastName, classLevelSubjects.classGroupLevelID as cglID, classLevelSubjects.beginDate as cglBeginDate, classGroupLevel.levelID, classGroupLevel.classGroupID, classGroupLevel.limit as classLimit, classGroup.className,level.level")
                    ->from("attendance")
                    ->join("classLevelSubjects", "classLevelSubjects.id = attendance.classLevelSubjectsID")
                    ->join("learner", "learner.id = attendance.learnerID")
                    ->join("subject", "subject.id = classLevelSubjects.subjectID")
                    ->join("classlevelsubjects as cgl", "classLevelSubjects.id = subject.id")
                    ->join("classGroupLevel", "classGroupLevel.id = classLevelSubjects.classGroupLevelID")
                    ->join("classgroup", "classgroup.id = classGroupLevel.classGroupID")
                    ->join("user", "user.id = learner.UserID")
                    ->join("level", "level.id = classGroupLevel.levelID")
                    //->join("user", "user.id = learner.UserID")
                    ->get()->result();
 }
 
 public function getLearnerProgress(){
 
    return  $this->db->select(" progress.id as attendID, progress.date as attendDate, progress.progress as learnerProgress, progress.learnerID as learnerID, progress.classLevelSubjectsID as clsID,classLevelSubjects.subjectID as subjectID, subject.name as subjectName,learner.userID as lUserID, learner.id as learnerID, user.firstName as firstName, user.lastName as lastName, classLevelSubjects.classGroupLevelID as cglID, classLevelSubjects.beginDate as cglBeginDate, classGroupLevel.levelID, classGroupLevel.classGroupID, classGroupLevel.limit as classLimit, classGroup.className,level.level")
                    ->from("progress")
                    ->join("classLevelSubjects", "classLevelSubjects.id = progress.classLevelSubjectsID")
                    ->join("learner", "learner.id = progress.learnerID")
                    ->join("subject", "subject.id = classLevelSubjects.subjectID")
                    ->join("classlevelsubjects as cgl", "classLevelSubjects.id = subject.id")
                    ->join("classGroupLevel", "classGroupLevel.id = classLevelSubjects.classGroupLevelID")
                    ->join("classgroup", "classgroup.id = classGroupLevel.classGroupID")
                    ->join("user", "user.id = learner.UserID")
                    ->join("level", "level.id = classGroupLevel.levelID")
                    //->join("user", "user.id = learner.UserID")
                    ->get()->result();
 }
 
 public function getStudyMaterial(){
 
    return  $this->db->select("studyMaterial.id as studyID,studyMaterial.title as materialTitle, studyMaterial.description as materialDesc, studyMaterial.date as publishDate,studyMaterial.filesID as fileID,files.fileName as fileName,files.fileSize as fileSize,files.fileType as fileType,files.filePath as filePath, studyMaterial.classLevelSubjectsID as clsID,classLevelSubjects.subjectID as subjectID, classLevelSubjects.teacherID as tearcherID,classLevelSubjects.classGroupLevelID as cglID,classGroupLevel.levelID as levelID,level.level as levelDesc, classGroupLevel.limit as classLimit ,classGroupLevel.classGroupID as cgID,classGroup.className as cgClassName,subject.name as subjectName,teacher.userID as tUserID, user.firstName as firstName, user.lastName as lastName")
                    ->from("studyMaterial")
                    ->join("files", "files.id = studyMaterial.filesID")
                    ->join("classlevelsubjects", "classlevelsubjects.id = studyMaterial.classLevelSubjectsID")
                    ->join("classgrouplevel", "classgrouplevel.id = classLevelSubjects.classGroupLevelID")
                    ->join("subject", "subject.id = classLevelSubjects.subjectID")
                    ->join("classgroup", "classgroup.id = classGroupLevel.classGroupID")
                    ->join("teacher", "teacher.id = classLevelSubjects.teacherID")
                    ->join("level", "level.id = classGroupLevel.levelID")
                    ->join("user", "user.id = teacher.UserID")
                    ->order_by("studyMaterial.id","DESC")
                    ->get()->result();
 }
 
public function getTeacherSubject(){
 
    return  $this->db->select("subject.id as subjID, subject.name as subjectName,teacher.userID as tUserID, user.firstName as firstName, user.lastName as lastName,classLevelSubjects.teacherID as teacherID, classLevelSubjects.classGroupLevelID as cglID, classLevelSubjects.beginDate as cglBeginDate, classGroupLevel.levelID, classGroupLevel.classGroupID, classGroupLevel.limit as classLimit,classGroup.className,level.level")
        ->from("subject")
        ->join("classlevelsubjects", "classLevelSubjects.id = subject.id")
        ->join("teacher", "teacher.id = classLevelSubjects.teacherID")
        ->join("classGroupLevel", "classGroupLevel.id = classLevelSubjects.classGroupLevelID")
        ->join("classgroup", "classgroup.id = classGroupLevel.classGroupID")
        ->join("user", "user.id = teacher.UserID")
        //->join("classGroupLevel as cgl ", "classGroupLevel.id = teacherResponsibleClass.classGroupLevelID")
        ->join("level", "level.id = classGroupLevel.levelID")
        ->where('teacher.id',1)
        ->where('teacher.deleted',0)
        ->get()->result();
 }
 
 public function getClassTeacher()
 {
    return $this->db->select('teacherresponsibleclass.teacherID as teacherID, teacherresponsibleclass.classGroupLevelID as cglID, teacherresponsibleclass.date as startDate, teacher.userID as tUserID,user.firstName as tFName, user.lastName as tLName, classGroupLevel.levelID as levelID, classGroupLevel.classGroupID, classGroupLevel.limit as classLimit, classGroup.className,level.level ')
                ->from('teacherresponsibleclass')
                ->join('teacher','teacher.id = teacherResponsibleClass.teacherID')
                ->join('classGroupLevel','classGroupLevel.id = teacherResponsibleClass.classGroupLevelID')
                ->join('user','user.id = teacher.userID')
                //->join("classGroupLevel", "classGroupLevel.id = classLevelSubjects.classGroupLevelID")
                ->join('level','level.id = classGroupLevel.levelID')
                ->join("classgroup", "classgroup.id = classGroupLevel.classGroupID")
                ->where('teacher.deleted',0)
                ->get()->result();
     
 }
 
public function getLearnerSubject(){
 
    return  $this->db->select("subject.id as subjID, subject.name as subjectName,learner.userID as lUserID, user.firstName as firstName, user.lastName as lastName, classLevelSubjects.classGroupLevelID as cglID, classLevelSubjects.beginDate as cglBeginDate, classGroupLevel.levelID, classGroupLevel.classGroupID, classGroupLevel.limit as classLimit, classGroup.className,level.level")
        ->from("subject")
        ->from("learner")
        ->join("classlevelsubjects", "classLevelSubjects.id = subject.id")
        ->join("classGroupLevel", "classGroupLevel.id = classLevelSubjects.classGroupLevelID")
        ->join("classgroup", "classgroup.id = classGroupLevel.classGroupID")
        ->join("user", "user.id = learner.UserID")
        //->join("classGroupLevel ", "classGroupLevel.id = teacherResponsibleClass.classGroupLevelID")
        ->join("level", "level.id = classGroupLevel.levelID")
        ->where('learner.id',1)
        ->where('learner.deleted',0)
        ->get()->result();
 }
 

//=======================================/////
//other latest statements
//=======================================/////
public function getTopics()
{
    
   return  $this->db->select("generaltopic.id as gtid, generaltopic.title as title")
                    ->from('generaltopic')
                    ->join('admin','admin.id = generaltopic.creatorID')
                    ->where('generaltopic.deleted',0)
                    ->order_by('generaltopic.id','DESC')
                    ->get()->result();

}
public function getLikes($search=array()){
    $like = $search['gtCommentID'] ?? FALSE;
    if($like){
        $this->db->where('hascommentslikes.commentLikeID',$like);
    }
    return  $this->db->select("COUNT(hascommentslikes.commentLikeID) AS totalComm,COUNT(hascommentslikes.userCommentLikeID) AS totalmm,hascommentslikes.likes as liked")
        ->from('hascommentslikes')
        ->join('generaltopiccomments','generaltopiccomments.id = hascommentslikes.commentLikeID')
        ->join('user','user.id = hascommentslikes.userCommentLikeID')
        ->group_by('hascommentslikes.commentLikeID')
        ->get()->result();
}

public function myQry()
{
/*    $sub = '(select COUNT(generaltopiccomments.id) as commCount 
                        from (generaltopiccomments)
                        where(generaltopiccomments.generalTopicID = 111))';

    $this->db->select("$sub,generaltopiccomments.id as gtcid,generaltopiccomments.generaltopicID as gtID, generaltopiccomments.commentMessage as Msg");
            ->from('generaltopiccomments')
                    ->join('generaltopic','generaltopic.id = generaltopiccomments.generalTopicID')
                    ->join('user','user.id = generaltopiccomments.userID')
                    ->join('profile','profile.userID = user.id')
                    ->join('files','files.id = profile.filesID')
                    ->where('generaltopiccomments.deleted',0)
                    ->where('generaltopiccomments.generalTopicID',1)
                    ->order_by('generaltopiccomments.id','DESC');
    return $this->db->get();
$this->db->select('generaltopiccomments.id as gtcid,generaltopiccomments.generaltopicID as gtID, generaltopiccomments.commentMessage as Msg, 

    (SELECT COUNT(*) FROM generaltopiccomments where generaltopiccomments.generaltopicID=111) as count', FALSE)*/

}

public function getTopicComments()
{
    
   return  $this->db->select('count(generaltopiccomments.generaltopicID) as userCount, generaltopiccomments.generaltopicID as gtID, generaltopiccomments.commentMessage as Msg, generaltopiccomments.userID as userID, generaltopic.title as topicTitle, generaltopic.creatorID, generaltopic.description as gtDescr')
                    ->from('generaltopiccomments')
                    ->join('generaltopic','generaltopic.id = generaltopiccomments.generalTopicID')
                    ->join('user','user.id = generaltopiccomments.userID')
                    ->where('generaltopiccomments.deleted',0)
                    ->order_by('generaltopiccomments.userID','DESC')
                    ->group_by('generaltopiccomments.userID')
                    ->group_by('generaltopiccomments.generaltopicID')
                    ->get()->result();

}

 public function getAssignmentsByTeach(){

    return  $this->db->select("assignment.id as assignID, assignment.title as assignTitle,assignment.description as assignDesc, assignment.publishDate as publishDate, assignment.dueDate as dueDate,assignment.filesID as fileID,files.fileName as fileName,files.fileSize as fileSize,files.fileType as fileType,files.filePath as filePath, assignment.classLevelSubjectsID as clsID,classLevelSubjects.subjectID as subjectID,classLevelSubjects.teacherID as tearcherID,classLevelSubjects.classGroupLevelID as cglID,classGroupLevel.levelID as levelID,level.level as levelDesc,classGroupLevel.limit as classLimit,classGroupLevel.classGroupID as cgID,classGroup.className as cgClassName,subject.name as subjectName,teacher.userID as tUserID, user.firstName as firstName, user.lastName as lastName")
                    ->from("assignment")
                    ->join("files", "files.id = assignment.filesID")
                    ->join("classlevelsubjects", "classlevelsubjects.id = assignment.classLevelSubjectsID")
                    ->join("classgrouplevel", "classgrouplevel.id = classLevelSubjects.classGroupLevelID")
                    ->join("subject", "subject.id = classLevelSubjects.subjectID")
                    ->join("classgroup", "classgroup.id = classGroupLevel.classGroupID")
                    ->join("teacher", "teacher.id = classLevelSubjects.teacherID")
                    ->join("level", "level.id = classGroupLevel.levelID")
                    ->join("user", "user.id = teacher.UserID")
                    //->where('teacher.id',4)
                    ->order_by("assignment.id","DESC")
                    ->get()->result();
 }

public function getLearnerAttendanceByUser()
{//learners attendance by subject

    return  $this->db->select(" attendance.id as attendID, attendance.date as attendDate,  attendance.status as attendStatus, attendance.learnerID as learnerID,ROUND(AVG((attendance.status)*100),0) AS attendAVG, attendance.classLevelSubjectsID as clsID, classLevelSubjects.subjectID as subjectID, subject.name as subjectName,learner.userID as lUserID, user.firstName as lFName, user.lastName as lLName, classLevelSubjects.classGroupLevelID as cglID, classLevelSubjects.beginDate as cglBeginDate, classGroupLevel.levelID as levelID, classGroupLevel.classGroupID as cgID, classGroupLevel.limit as classLimit, classGroup.className cgName ,level.level as level,classLevelSubjects.teacherID as teacherID, ut.firstName as tFName, ut.lastName as tLName")
                    ->from("attendance")
                    ->join('learner','learner.id=attendance.learnerID')
                    ->join('classLevelSubjects','classLevelSubjects.id=attendance.classLevelSubjectsID')
                    ->join("subject", "subject.id = classLevelSubjects.subjectID")
                    ->join("classlevelsubjects as cls", "cls.id = subject.id")
                    ->join("classGroupLevel", "classGroupLevel.id = classLevelSubjects.classGroupLevelID")
                    ->join("classgroup", "classgroup.id = classGroupLevel.classGroupID")
                    ->join("user", "user.id = learner.UserID")
                    ->join('teacher','teacher.id=classLevelSubjects.teacherID')
                    ->join('user as ut','ut.id=teacher.userID')
                    ->join("level", "level.id = classGroupLevel.levelID")
                    ->where('user.id',117)
                    ->where('user.deleted',0)
                    ->group_by('subjectID')
                    ->get()->result();
 }

 public function getLearnerProgressByUser()
 {//learner progress

    return  $this->db->select(" progress.id as progressID, progress.date as progresDate, progress.progress as Marks, progress.learnerID as learnerID, progress.classLevelSubjectsID as clsID,classLevelSubjects.subjectID as subjectID, subject.name as subjectName,learner.userID as lUserID, user.firstName as lFName, user.lastName as lLName, classLevelSubjects.classGroupLevelID as cglID, classLevelSubjects.beginDate as cglBeginDate, classGroupLevel.levelID as levelID, classGroupLevel.classGroupID as cgID, classGroupLevel.limit as classLimit, classGroup.className cgName ,level.level as level,classLevelSubjects.teacherID as teacherID, ut.firstName as tFName, ut.lastName as tLName")
                    ->from("progress")
                    ->join("classLevelSubjects", "classLevelSubjects.id = progress.classLevelSubjectsID")
                    ->join("learner", "learner.id = progress.learnerID")
                    ->join("subject", "subject.id = classLevelSubjects.subjectID")
                    ->join("classlevelsubjects as cgl", "cgl.id = subject.id")
                    ->join("classGroupLevel", "classGroupLevel.id = classLevelSubjects.classGroupLevelID")
                    ->join("classgroup", "classgroup.id = classGroupLevel.classGroupID")
                    ->join("user", "user.id = learner.UserID")
                    ->join('teacher','teacher.id=classLevelSubjects.teacherID')
                    ->join('user as ut','ut.id=teacher.userID')
                    ->join("level", "level.id = classGroupLevel.levelID")
                    ->where('user.id',200)
                    ->where('user.deleted',0)
                    ->get()->result();
 }

 public function getStudyMaterialbyTeacher(){

    return  $this->db->select("studyMaterial.id as studyID, studyMaterial.description as materialDesc, studyMaterial.date as publishDate,studyMaterial.filesID as fileID,files.fileName as fileName,files.fileSize as fileSize,files.fileType as fileType,files.filePath as filePath, studyMaterial.classLevelSubjectsID as clsID,classLevelSubjects.subjectID as subjectID, classLevelSubjects.teacherID as tearcherID,classLevelSubjects.classGroupLevelID as cglID,classGroupLevel.levelID as levelID,level.level as levelDesc, classGroupLevel.limit as classLimit ,classGroupLevel.classGroupID as cgID,classGroup.className as cgClassName,subject.name as subjectName,teacher.userID as tUserID, user.firstName as firstName, user.lastName as lastName")
                    ->from("studyMaterial")
                    ->join("files", "files.id = studyMaterial.filesID")
                    ->join("classlevelsubjects", "classlevelsubjects.id = studyMaterial.classLevelSubjectsID")
                    ->join("classgrouplevel", "classgrouplevel.id = classLevelSubjects.classGroupLevelID")
                    ->join("subject", "subject.id = classLevelSubjects.subjectID")
                    ->join("classgroup", "classgroup.id = classGroupLevel.classGroupID")
                    ->join("teacher", "teacher.id = classLevelSubjects.teacherID")
                    ->join("level", "level.id = classGroupLevel.levelID")
                    ->join("user", "user.id = teacher.UserID")
                    ->where('teacher.id',16)
                    ->order_by("studyMaterial.id","DESC")
                    ->get()->result();
 }

public function getTeacherSubjectByTeacher(){

    return  $this->db->select("subject.id as subjID, subject.name as subjectName,teacher.userID as tUserID, user.firstName as firstName, user.lastName as lastName,classLevelSubjects.teacherID as teacherID, classLevelSubjects.classGroupLevelID as cglID, classLevelSubjects.beginDate as cglBeginDate, classGroupLevel.levelID, classGroupLevel.classGroupID, classGroupLevel.limit as classLimit,classGroup.className,level.level")
        ->from("subject")
        ->join("classlevelsubjects", "classLevelSubjects.id = subject.id")
        ->join("teacher", "teacher.id = classLevelSubjects.teacherID")
        ->join("classGroupLevel", "classGroupLevel.id = classLevelSubjects.classGroupLevelID")
        ->join("classgroup", "classgroup.id = classGroupLevel.classGroupID")
        ->join("user", "user.id = teacher.UserID")
        //->join("classGroupLevel as cgl ", "classGroupLevel.id = teacherResponsibleClass.classGroupLevelID")
        ->join("level", "level.id = classGroupLevel.levelID")
        ->where('teacher.id',1)
        ->where('teacher.deleted',0)
        ->get()->result();
 }

 public function getClassTeacherByClass()
 {
    return $this->db->select('teacherresponsibleclass.teacherID as teacherID, teacherresponsibleclass.classGroupLevelID as cglID, teacherresponsibleclass.date as startDate, teacher.userID as tUserID,user.firstName as tFName, user.lastName as tLName, classGroupLevel.levelID as levelID, classGroupLevel.classGroupID, classGroupLevel.limit as classLimit, classGroup.className,level.level ')
                ->from('teacherresponsibleclass')
                ->join('teacher','teacher.id = teacherResponsibleClass.teacherID')
                ->join('classGroupLevel','classGroupLevel.id = teacherResponsibleClass.classGroupLevelID')
                ->join('user','user.id = teacher.userID')
                //->join("classGroupLevel", "classGroupLevel.id = classLevelSubjects.classGroupLevelID")
                ->join('level','level.id = classGroupLevel.levelID')
                ->join("classgroup", "classgroup.id = classGroupLevel.classGroupID")
                ->where('teacher.deleted',0)
                ->get()->result();
    
 }

public function getLearnerSubjectByLearn(){

    return  $this->db->select("subject.id as subjID, subject.name as subjectName,learner.userID as lUserID, user.firstName as firstName, user.lastName as lastName, classLevelSubjects.classGroupLevelID as cglID, classLevelSubjects.beginDate as cglBeginDate, classGroupLevel.levelID, classGroupLevel.classGroupID, classGroupLevel.limit as classLimit, classGroup.className,level.level")
        ->from("subject")
        ->from("learner")
        ->join("classlevelsubjects", "classLevelSubjects.subjectID = subject.id")
        ->join("classGroupLevel", "classGroupLevel.id = classLevelSubjects.classGroupLevelID")
        ->join("classgroup", "classgroup.id = classGroupLevel.classGroupID")
        ->join("user", "user.id = learner.UserID")
        //->join("classGroupLevel ", "classGroupLevel.id = teacherResponsibleClass.classGroupLevelID")
        ->join("level", "level.id = classGroupLevel.levelID")
        ->where('learner.id',1)
        ->where('learner.deleted',0)
        ->get()->result();
 }

public function getLearner()
    {
        return $this->db->select("user.id as l_uID,user.lastName as lName,user.middleName as midName,user.firstName as fName, user.phone as phone, user.address as address,user.email as email,learner.id as learnerID,learner.learnerNumber as learnDoE_ID, classgroup.id as cgID, classgroup.className as cgName, classGroupLevel.id as cglID, ")   
        ->from("learner")
        ->join('user', 'user.id=learner.userID')
        ->join("classGroupLevel", "classGroupLevel.id = learner.classGroupLevelID")
        ->join("classgroup", "classgroup.id = classGroupLevel.classGroupID")
        ->where('learner.id',80)
        ->get()->row();

    }

    public function getUser()
    {
        return $this->db->select("user.id as userID,user.lastName as lName,user.middleName as midName, user.firstName as fName, user.phone as phone, user.email as email, userLogin.username as userName")  
        ->from("user")
        ->join('userLogin','userLogin.userID=user.id')
        ->where('user.id',1)
        ->get()->row();

    }
public function getTeacher()
    {
        return $this->db->select("user.id as t_uID,user.lastName as tLName,user.middleName as tMidName, user.firstName as tFName, user.phone as tPhone, user.email as tEmail,teacher.id as teacherID,") 
        ->from("user")
        ->join('teacher', 'teacher.userID=user.id')
        ->where('user.id',3)
        ->get()->row();

    }
    public function getAdmin()
    {
        return $this->db->select("user.id as a_uID,user.lastName as aLName,user.middleName as aMidName,user.firstName as aFName, user.phone as aPhone, user.email as aEmail,admin.id as adminID,")  
        ->from("user")
        ->join('admin', 'admin.userID=user.id')
        ->where('user.id',1)
        ->get()->row();

    }
    public function getGuardian()
    {
        return $this->db->select("user.id as g_uID,user.lastName as gLName,user.firstName as gFName, user.phone as gPhone, user.address as gAddress,user.email as gEmail,guardian.id as guardID,")  
        ->from("user")
        ->join('guardian', 'guardian.userID=user.id')
        ->where('user.id',100)
        ->get()->row();

    }
    public function getGuardianChild()
    {//Tell me who is/are the child/ren of Guardian 8
        return $this->db->select("learner.id as learnerID,learner.userID as lUserID, user.firstName as lFName, user.lastName as lLName, user.email as lEmail, user.phone as lPhone, learnerGuardian.guardianID as guardID ,learnerGuardian.relationshipID as gRelationID, relationship.description as howRelated")  
        ->from("learner")
        ->join('user','user.id=learner.userID')
        ->join('learnerGuardian','learnerGuardian.learnerID=learner.id')
        ->join('relationship','relationship.id=learnerGuardian.relationshipID')
        ->join('guardian','guardian.id=learnerGuardian.guardianID')
        //->join('user as g','user.id=guardian.userID')
        ->where('guardian.id',1)
        ->get()->result();

    }

        public function getLearnParents()
    {//who are the parents of learner 10
        return $this->db->select("guardian.id as guardID,guardian.userID as gUserID, user.firstName as gFName, user.lastName as gLName, user.email as gEmail, user.phone as gPhone, learnerGuardian.learnerID as lID,   learnerGuardian.relationshipID as lRelationID, relationship.description as howRelated") 
        ->from("guardian")
        ->join('user','user.id=guardian.userID')
        ->join('learnerGuardian','learnerGuardian.guardianID=guardian.id')
        ->join('learner','learner.id=learnerGuardian.learnerID')
        //->join('user as lu','user.id=learner.userID')
        ->join('relationship','relationship.id=learnerGuardian.relationshipID')
        ->where('learner.id',100)
        ->get()->result();

    }
}