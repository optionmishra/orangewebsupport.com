<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AuthModel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->model('WebModel');
    } 

    public function validate() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $admin = $this->db->query("SELECT * FROM user WHERE username = ? AND password = ? LIMIT 1", [$username, $password]);
        $adm = $admin->result();
        if ($admin->num_rows() == 1) {
            $this->session->set_userdata('ausername', $username);
            //$this->session->set_userdata('password', $password);
            $this->session->set_userdata('level', $adm[0]->level);
			$this->session->set_userdata('userType', $adm[0]->user_type);
            $cur_time = date("l jS \of F Y h:i:s A");
         //   $last_login = $this->db->query("UPDATE user SET last_login = $cur_time' WHERE username = ? AND password = ? ", [$username, $password]);
            return true;
        }
 	
        return false;
    }

    public function val_email($email) {
        if (!empty($email)) {
            $this->db->where('email', $email);
        }
        $res = $this->db->get('web_user')->result();
        return $res;
    } 
    
    public function check_student_paper($id) {
        if (!empty($id)) {
            $this->db->where('student_id', $id);        
            $res = $this->db->get('paper_submision')->result();
            return $res;            
        }
    }

    public function navbar() {
        $level = $this->session->userdata('level');
        if ($level == "Super Admin") {
            $admin = $this->load->view('globals/navbar');
            return $admin;
        } else if ($level == "Admin") {
            $admin = $this->load->view('globals/navbar');
            return $admin;
        } else {
            return false;
        }
    }

    function retrieveRecord($table, $condCol = null, $id = null) {
        if (!empty($id)) {
            $this->db->where($condCol, $id);
        }
        $res = $this->db->get($table)->result();
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return $res;
        }
    }

    function boardx() {
        $res = $this->db->get('board', 1)->result();
        return $res;
    }

    function publicationx() {
        $res = $this->db->get('publication', 1)->result();
        return $res;
    }

    function categoryx() {
        $this->db->order_by('orderb', 'asc');
        $res = $this->db->get('category', 1)->result();
        return $res;
    }

    function classesx() {
        $id= $this->session->userdata('teacher_classess');
        $class_id = explode(',',$id);
		$fst_class = $class_id[0];
        $this->db->where('id', $fst_class);
	    $res = $this->db->get('classes')->result();
        return $res;
    }

    function subjectx() {
        $res = $this->db->get('subject', 1)->result();
        return $res;
    }
    
    function asubjectx($ss) {
        $this->db->where('sid',$ss);
        $res = $this->db->get('subject', 1)->result();
        return $res;
    }
    
    function rsubjectx($rr) {
        $this->db->where('sid',$rr);
        $res = $this->db->get('subject', 1)->result();
        return $res;
    }
    
    function ssubjectx($rs) {
        $this->db->where('sid',$rr);
        $res = $this->db->get('subject')->result();
        return $res;
    }
    
    function msubjectx() {
        $res = $this->db->get('main_subject', 1)->result();
        return $res;
    }
    
    function summativeQues() {
        $this->db->where('question_type','1');
        $res = $this->db->get('touch_question')->result();
        return $res;
    }

    function view_student_summativeQues($id) {
        $this->db->where('paper_mode','Summative');
        $this->db->where('student_id',$id);
        $res = $this->db->get('paper_submision')->result();
        return $res;
    }

    function summativeQuestion_solved($id) {
        $this->db->select('touch_question.name');
        $this->db->select('paper_submision.*');
        $this->db->from('paper_submision as paper_submision');
        $this->db->join('touch_question as touch_question', 'paper_submision.question_id=touch_question.id', 'INNER');
        $this->db->where('paper_mode','Summative');
        $this->db->where('student_id',$id);
        $res = $this->db->get()->result();
        return $res;
    }

   
    function objectiveQuestion_solved($id) {
        $this->db->select('touch_question.name, touch_question.option_a, touch_question.option_b, touch_question.option_c, touch_question.option_d');
        $this->db->select('paper_submision.*');
        $this->db->from('paper_submision as paper_submision');
        $this->db->join('touch_question as touch_question', 'paper_submision.question_id=touch_question.id', 'INNER');
        $this->db->where('paper_mode','Objective');
        $this->db->where('student_id',$id);
        $res = $this->db->get()->result();
        return $res;
    }
    
    function objectiveQues() {
        $this->db->where('question_type','2');
        $res = $this->db->get('touch_question')->result();
        return $res;
    } 
    
    function summativeQuestion() {
        $this->db->select('main_subject.name as subsName');
        $this->db->select('touch_question.*');
        $this->db->from('touch_question as touch_question');
        $this->db->join('main_subject as main_subject', 'touch_question.series=main_subject.id', 'INNER');
        $this->db->where('question_type', '1');
        $res = $this->db->get()->result();
        return $res;
    }

    function create_summativeQuestion($data) {
        $res = $this->db->insert('touch_question', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_summativeQuestion($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('touch_question');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_paper_submision_marks($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('paper_submision');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }
	
    function objectiveQuestion() {
        $this->db->select('main_subject.name as subsName');
        $this->db->select('touch_question.*');
        $this->db->from('touch_question as touch_question');
        $this->db->join('main_subject as main_subject', 'touch_question.series=main_subject.id', 'INNER');
        $this->db->where('question_type', '2');
        $res = $this->db->get()->result();
        return $res;
    }

    function create_objectiveQuestion($data) {
        $res = $this->db->insert('touch_question', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_objectiveQuestion($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('touch_question');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

	function categorydemo() {
        $this->db->where('id',11);
		$this->db->order_by('orderb', 'asc');
        $res = $this->db->get('category')->result();
        return $res;
    }
	
	function msubjectxdemo() {
        $this->db->where('id',25);
        $res = $this->db->get('main_subject')->result();
        return $res;
    }

    function validate_web($username, $password) {
        
        $publication = $this->AuthModel->publicationx();
        $category = $this->AuthModel->categoryx();
        $admin = $this->db->query("SELECT * FROM web_user WHERE email = ? AND password = ? AND status = ? LIMIT 1", [$username, $password, '1']);
       // $admin = $this->db->query("SELECT * FROM web_user WHERE email = ? AND password = ? LIMIT 1", [$username, $password]);
        $adm = $admin->result();
        if ($admin->num_rows() == 1) {
            $this->session->set_userdata('username', $username);
			$this->session->set_userdata('user_id', $adm[0]->id);
            $this->session->set_userdata('password', $password);
            $this->session->set_userdata('type', $adm[0]->user_type);
            if ($adm[0]->user_type == 'Student') {
                $this->session->set_userdata('publication', $publication[0]->id);
                $this->session->set_userdata('classes', $adm[0]->classes);
                $this->session->set_userdata('section', $adm[0]->class_section);
				$this->session->set_userdata('status', $adm->status); 
                $this->session->set_userdata('stu_teacher_code', $adm[0]->stu_teacher_id);
				$this->session->set_userdata('main_subject', $adm[0]->subject);
                $this->session->set_userdata('board_name', $adm[0]->board_name);
                $this->session->set_userdata('publication_name', $publication[0]->name);
                $this->session->set_userdata('category', $category[0]->id);
                $this->session->set_userdata('category_name', $category[0]->name); 
                $this->session->set_userdata('school_name', $category[0]->school_name);
            } elseif ($adm[0]->user_type == 'Teacher'){
                $this->session->set_userdata('publication', $publication[0]->id);				
                $this->session->set_userdata('teacher_classess', $adm[0]->classes);
                $this->session->set_userdata('teacher_code', $adm[0]->teacher_code);
				$this->session->set_userdata('status', $adm->status); 
				$this->session->set_userdata('main_subject', $adm[0]->subject);
                $this->session->set_userdata('board_name', $adm[0]->board_name);
                $this->session->set_userdata('publication_name', $publication[0]->name);
                $this->session->set_userdata('category', $category[0]->id);
                $this->session->set_userdata('category_name', $category[0]->name);
                $this->session->set_userdata('school_name', $category[0]->school_name); 
                $classes = $this->AuthModel->classesx();
                $this->session->set_userdata('classes', $classes[0]->id);
            }
            return true;
        }  
    }

    function delete_record($table, $condColumn, $id) {
        $this->db->where($condColumn, $id);
        $res = $this->db->delete($table);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function user_profile() {
        $username = $this->session->userdata('ausername');
        if (!empty($username)) {
            $this->db->where('username', $username);
        }
        $result = $this->db->get('user')->result();
        if (!$result) {
            $this->error = $this->db->error();
            return FALSE;
        } else {
            return $result;
        }
    }
	
	public function salesman_profile() {

        $result = $this->db->get('salesman')->result(); 
        if (!$result) {
            $this->error = $this->db->error();
            return FALSE;
        } else {
            return $result;
        }
    }

    function update_school_logo($data, $username) {
        $this->db->where('username', $username);
        $this->db->set($data);
        $res = $this->db->update('user');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_profile_logo($data, $username) {
        $this->db->where('email', $username);
        $this->db->set($data);
        $res = $this->db->update('web_user');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_user_profile($data, $username) {
        $this->db->where('username', $username);
        $this->db->set($data);
        $res = $this->db->update('user');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_web_profile($data, $username) {
        $this->db->where('email', $username);
        $this->db->set($data);
        $res = $this->db->update('web_user');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function teacher_update_web_profile($data, $id) {
        $this->db->where('id', $id);
        $this->db->set($data);
        $res = $this->db->update('web_user');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function teacher_remove_student($data, $id) {
        $this->db->where('id', $id);
        $this->db->set($data);
        $res = $this->db->update('web_user');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_profile_account($data, $username) {
        $this->db->where('username', $username);
        $this->db->set($data);
        $res = $this->db->update('user');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_web_account($data, $username) {
        $this->db->where('email', $username);
        $this->db->set($data);
        $res = $this->db->update('web_user');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

// User Start  

    function user($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('user')->result();
        return $res;
    }
	
    function salesman($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('salesman')->result();
        return $res;
    }

    function content($name = null) {
        if (!empty($name)) {
            $this->db->where('name', $name);
        }
        $res = $this->db->get('web_content')->result();
        return $res;
    }
	
	function content_row($name = null) {
        if (!empty($name)) {
            $this->db->where('name', $name);
        }
        $res = $this->db->get('web_content')->row_array();
        return $res;
    }

    function check_boardName($bid) {
        if (!empty($bid)) {
            $this->db->where('id', $bid);
        }
        $res = $this->db->get('board')->result();
        return $res;
    }

    function check_pubName($pid) {
        if (!empty($pid)) {
            $this->db->where('id', $pid);
        }
        $res = $this->db->get('publication')->result();
        return $res;
    }

	public function dropdown_list($query)
		{
		$query = $this->db->query($query);		
		return $query->result_array();
		}

    function check_catName($cid) {
        if (!empty($cid)) {
            $this->db->where('id', $cid);
        }
        $res = $this->db->get('category')->result();
        return $res;
    }

    function state($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('state')->result();
        return $res;
    }

	function ret_city($postData) { 
        $response = array();
        $this->db->select('id,city_name');
        $this->db->where('state_id', $postData['id']);
        $q = $this->db->get('city');
        $response = $q->result_array();     
		return $response;   
    }

    function staten() {
        $this->db->where('zone', 'North');
        $res = $this->db->get('state')->result();
        return $res;
    }

    function statee() {
        $this->db->where('zone', 'East');
        $res = $this->db->get('state')->result();
        return $res;
    }

    function statew() {
        $this->db->where('zone', 'West');
        $res = $this->db->get('state')->result();
        return $res;
    }

    function states() {
        $this->db->where('zone', 'South');
        $res = $this->db->get('state')->result();
        return $res;
    }

    function create_user($data) {
        $res = $this->db->insert('user', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }
	
	function create_salesman($data) {
        $res = $this->db->insert('salesman', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_user($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('user');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }
	
	function update_salesman($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('salesman');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function retrive_user_update($id) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('user')->result();
        return $res;
    }

    function retrive_teacher_update($id) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('web_user')->result();
        return $res;
    }

    function retrive_teacher_update_row($id) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('web_user')->row();
        return $res;
    }
	
	function retrive_salesman_update($id) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('salesman')->result();
        return $res;
    }

// user End
// Content Start
    function cont($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('web_content')->result();
        return $res;
    }

    function create_con($data) {
        $res = $this->db->insert('web_content', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_con($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('web_content');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

// Permission End
// Permission Start
    function permission($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('permission')->result();
        return $res;
    }

    function create_permission($data) {
        $res = $this->db->insert('permission', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_permission($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('permission');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

// Permission End

// state Start
    function country($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('country')->result();
        return $res;
    }
	
	
	function stated($id = null) {
        if (!empty($id)) {
            $this->db->where('StateID', $id);
        }
        $res = $this->db->get('state')->result();
        return $res;
    }
	
	function get_statess($id = null) {
		//echo 'alert("'.$id.'")';
        if (!empty($id)) {
            $this->db->where('country_id', $id);
        }
        $res = $this->db->get('states')->result_array();
        return $res;
    }

    function create_state($data) {
        $res = $this->db->insert('state', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_state($details, $id) {
        $this->db->where('StateID', $id);
        $this->db->set($details);
        $res = $this->db->update('state');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

// state End

// state Start
    function citiesd($id = null) {
        $this->db->select('state.StateName as state_name');
        $this->db->select('city.*');
        $this->db->from('city as city');
        $this->db->join('state as state', 'city.state_id = state.StateID', 'INNER');
        $res = $this->db->get()->result();
        return $res;
    }

	function cities($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('city')->result();
        return $res;
    }
	
	function get_cities($id = null) {
        if (!empty($id)) {
            $this->db->where('state_id', $id);
        }
        $res = $this->db->get('cities')->result_array();
        return $res;
    }

    function create_city($data) {
        $res = $this->db->insert('city', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_city($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('city');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

// state End

// Web User Start  

    function webu($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $this->db->where('user_type', 'Student');
        $res = $this->db->get('web_user')->result();
        return $res;
    }

    function webu_teacher($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $this->db->where('user_type', 'Teacher');
        $res = $this->db->get('web_user')->result();
        return $res;
    }

    function update_webu($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('web_user');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

// WEBU End
// Board Start
    function board($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('board')->result();
        return $res;
    }
	
	function board_name($name = null) {
        if (!empty($name)) {
            $this->db->where('name', $name);
        }
        $res = $this->db->get('board')->result();
        return $res;
    }

    function create_board($data) {
        $res = $this->db->insert('board', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_board($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('board');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

// Board End
// Publication Start
    function publication($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('publication')->result();
        return $res;
    }

    function create_publication($data) {
        $res = $this->db->insert('publication', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_publication($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('publication');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE; 
        } else {
            return TRUE;
        }
    }

// Publication End
// Category Start
    function category($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
	//$this->db->where('allow','Teacher');
	//$this->db->where('allow','Both');

        $this->db->order_by('orderb', 'asc');
        $res = $this->db->get('category')->result();
        return $res;
    }

     function categoryx_student() {
	//$this->db->where('allow','Student');
	$this->db->where('allow','Both');
        $this->db->order_by('orderb', 'asc');
        $res = $this->db->get('category')->result();
        return $res;
    }

    function create_category($data) {
        $res = $this->db->insert('category', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_category($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('category');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

// Category End
// Main Subject Start
    function msubject($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('serial', asc);
        $res = $this->db->get('main_subject')->result();
        return $res;
    }

    function get_msubject($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('main_subject')->row_array();
        return $res;
    }

    function mcreate_subject($data) {
        $res = $this->db->insert('main_subject', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function mupdate_subject($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('main_subject');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

// Subject Start
    function subject($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('subject')->result();
        return $res;
    } 

	function subject_name($id = null) {
        if (!empty($id)) {
            $this->db->where('board', $id);
        }
        $res = $this->db->get('main_subject')->result();
        return $res;
    }

    function subjectr() {
        $this->db->select('main_subject.name as subsName');
        $this->db->select('subject.*');
        $this->db->from('subject as subject');
        $this->db->join('main_subject as main_subject', 'subject.sid=main_subject.id', 'INNER');
        $res = $this->db->get()->result();
        return $res;
    }

    function create_subject($data) {
        $res = $this->db->insert('subject', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_subject($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('subject');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

// Subject End
// Classes Start
    function classes($id = null) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
	    $this->db->order_by('class_position', asc);
        $res = $this->db->get('classes')->result();
        return $res;
    }
    
    function classes_teacher($id = null) {
        $class_id = explode(',',$id);
        $this->db->where_in('id', $class_id);
        
	    $this->db->order_by('class_position', asc);
        $res = $this->db->get('classes')->result();

        $this->db->last_query();
        return $res;
    }
    
    function classes_array() {

        $id= $this->session->userdata('teacher_classess');

        $class_id = explode(',',$id);
        $this->db->where_in('id', $class_id);

	    $this->db->order_by('class_position', asc);
        $res = $this->db->get('classes')->result();

        return $res;
    } 
    
    function classes_arraystudent() {
                
        $stud_cla_id= $this->session->userdata('classes');               
        $this->db->where('id', $stud_cla_id);

	    $this->db->order_by('class_position', asc);
        $res = $this->db->get('classes')->result();

       // $this->db->last_query();
        return $res;
    }
    
    
    function get_section_name($id) {        
        $this->db->where('class_id', $id);
        $response = $this->db->get('class_section')->result_array();
        return $response;
    }
    
    function get_paper_mode($sid,$cid) {        
        $this->db->select('paper_mode');
        $this->db->where('student_section', $sid);
        $this->db->where('student_class', $cid);
        $this->db->group_by(array('student_id', 'paper_mode'));
        $response = $this->db->get('paper_submision')->result_array();
        return $response;
    }

    function get_paper_marks($cid) {        
        $this->db->select('question_type,marks');
        $this->db->where('class', $cid);
        $response = $this->db->get('touch_question')->result_array();
        return $response;
    }

    function get_paper_obtn_marks($sid, $mode) {        
        $this->db->select_sum('ans_marks');
        $this->db->where('student_id', $sid);
        $this->db->where('paper_mode', $mode);
        $response = $this->db->get('paper_submision')->row(); 
        return $response;
    }
    
    function get_section_class_paper($sid,$cid) {        

        $this->db->select('web_user.fullname as name');
        $this->db->select('class_section.name as sectionName');
        $this->db->select('paper_submision.*');
        $this->db->from('paper_submision as paper_submision');
        $this->db->join('web_user as web_user', 'paper_submision.student_id=web_user.id', 'INNER');
        $this->db->join('class_section as class_section', 'paper_submision.student_section=class_section.id', 'INNER');
        $this->db->where('student_section', $sid);
        $this->db->where('student_class', $cid);
        $this->db->where('student_code', $this->session->userdata('teacher_code'));
        $this->db->group_by('student_id');
        $response = $this->db->get()->result_array();
        return $response;
    }
    
    function count_summative($sid,$cid) {        
        $this->db->where('student_section', $sid);
        $this->db->where('student_class', $cid);
        $this->db->where('paper_mode', 'Summative');
        $response = $this->db->get('paper_submision')->num_rows();;
        return $response;
    }
    
    function count_objective($sid,$cid) {        
        $this->db->where('student_section', $sid);
        $this->db->where('student_class', $cid);
        $this->db->where('paper_mode', 'Objective');
        $response = $this->db->get('paper_submision')->num_rows();;
        return $response;
    }

    function sta_user() {
        $query = $this->db->query("SELECT * FROM web_user WHERE user_type = 'Student'");
        return $query->num_rows();
    }

    function tea_user() {
        $query = $this->db->query("SELECT * FROM web_user WHERE user_type = 'Teacher'");
        return $query->num_rows();
    }

    function sta_boards() {
        $this->db->select('COUNT(websupport.board) as total');
        $this->db->select('board.*');
        $this->db->from('board as board');
        $this->db->join('websupport as websupport', 'board.id=websupport.board', 'INNER');
        $this->db->group_by('board.id');
        $res = $this->db->get()->result();
        return $res;
    }

    function sta_pub() {
        $this->db->select('COUNT(websupport.publication) as total');
        $this->db->select('publication.*');
        $this->db->from('publication as publication');
        $this->db->join('websupport as websupport', 'publication.id=websupport.publication', 'INNER');
        $this->db->group_by('publication.id');
        $res = $this->db->get()->result();
        return $res;
    }

    function ret_r($postData) {
        $response = array();
        $this->db->select('COUNT(websupport.type) as total');
        $this->db->select('category.*');
        $this->db->from('category as category');
        $this->db->join('websupport as websupport', 'category.id=websupport.type', 'INNER');
        $this->db->group_by('category.id');
        $this->db->where('websupport.subject', $postData['id']);
        $response = $this->db->get()->result_array();
        return $response;
    }

    function sta_subject() {
        $query = $this->db->query('SELECT * FROM subject');
        return $query->num_rows();
    }

    function create_classes($data) {
        $res = $this->db->insert('classes', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function assigntest($id = null) {
        if (!empty($id)) {
            $this->db->where('teacher_code', $id);
        }
        $res = $this->db->get('paper_assign')->result();
        return $res;
    }
    
    function create_assigntest($data) {
		
        $res = $this->db->insert('paper_assign', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_assigntest_status($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('paper_assign');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function paper_submision($data) {
        $res = $this->db->insert('paper_submision', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function classesSection(){
        $this->db->select('classes.name as className');
        $this->db->select('class_section.*');
        $this->db->from('class_section as class_section');
        $this->db->join('classes as classes', 'class_section.class_id=classes.id', 'INNER');
        $res = $this->db->get()->result();
        return $res;
    }


    function create_classesSection($data) {
        $res = $this->db->insert('class_section', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_classes($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('classes');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function update_classesSection($details, $id) {
        $this->db->where('id', $id);
        $this->db->set($details);
        $res = $this->db->update('class_section');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

// Classes End
// Support Start
    function support($id = null) {
        if (!empty($id)) {
            $this->db->select('board.name as boardName');
            $this->db->select('publication.name as publicationName');
            $this->db->select('subject.name as subjectName');
            $this->db->select('classes.name as className');
            $this->db->select('websupport.*');
            $this->db->from('websupport as websupport');
            $this->db->join('board as board', 'websupport.board=board.id', 'INNER');
            $this->db->join('publication as publication', 'websupport.publication=publication.id', 'INNER');
            $this->db->join('subject as subject', 'websupport.subject=subject.id', 'INNER');
            $this->db->join('classes as classes', 'websupport.classes=classes.id', 'INNER');
            $this->db->where('websupport.type', $id);
        }
        $res = $this->db->get()->result();
        return $res;
    }

    function supportt($id = null) {
        if (!empty($id)) {
            $this->db->select('board.name as boardName');
            $this->db->select('publication.name as publicationName');
            $this->db->select('subject.name as subjectName');
            $this->db->select('msubject.name as msubjectName');
            $this->db->select('classes.name as className');
            $this->db->select('websupport.*');
            $this->db->from('websupport as websupport');
            $this->db->join('board as board', 'websupport.board=board.id', 'INNER');
            $this->db->join('publication as publication', 'websupport.publication=publication.id', 'INNER');
            $this->db->join('subject as subject', 'websupport.subject=subject.id', 'INNER');
            $this->db->join('main_subject as msubject', 'websupport.msubject=msubject.id', 'INNER');
            $this->db->join('classes as classes', 'websupport.classes=classes.id', 'INNER');
            $this->db->where('websupport.id', $id);
        }
        $res = $this->db->get()->result();
        return $res;
    }

    function addSupport($data) {
        $res = $this->db->insert('websupport', $data);
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function retrive_support_update($id) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('websupport')->result();
        return $res;
    }

    function update_support($data, $id) {
        $this->db->where('id', $id);
        $this->db->set($data);
        $res = $this->db->update('websupport');
        if (!$res) {
            $this->error = $this->db->error()['message'];
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function default_product() {
        if($this->session->userdata('board_name')=='CBSE'){
			$board ='1';
		}else{
			$board ='6';
		}		
        $this->db->where('board',$board);
        $this->db->where('publication', $this->session->userdata('publication'));
        $this->db->where('classes', $this->session->userdata('classes'));
        $this->db->where('msubject', $this->session->userdata('main_subject'));
        $this->db->where('type', $this->session->userdata('category'));
        $query = $this->db->get('websupport')->result();

        //echo $this->db->last_query();
        return $query;
    }
 
    function get_sub($postData) {
        $response = array();
        $this->db->distinct();
        $this->db->select('main_subject.name as subjectName');
        $this->db->select('websupport.msubject as subjectId');
        $this->db->from('websupport as websupport');
        $this->db->join('main_subject as main_subject', 'websupport.msubject=main_subject.id', 'INNER');
        $this->db->where('websupport.board', $postData['bid']);
        $this->db->where('websupport.publication', $postData['pid']);
        $this->db->where('websupport.classes', $postData['cid']);
		$this->db->where('websupport.msubject', $postData['mid']);
        $response = $this->db->get()->result_array();
        return $response;
    }
    
    function get_subr($postData) {
        $response = array();
        $this->db->distinct();
        $this->db->select('main_subject.name as subjectName');
        $this->db->select('websupport.msubject as subjectId');
        $this->db->from('websupport as websupport');
        $this->db->join('main_subject as main_subject', 'websupport.msubject=main_subject.id', 'INNER');
        $this->db->where('websupport.board', $postData['bid']);
        $this->db->where('websupport.publication', $postData['pid']);        
        $this->db->where('websupport.classes', $postData['cid']);
		$this->db->where('websupport.msubject', $postData['sid']);
        $response = $this->db->get()->result_array();
        return $response;
    }

    function get_subm($postData) {
        $response = array();
        $this->db->where('sid', $postData['bid']);
        $res = $this->db->get('subject')->result();
        return $res;
    }
	
	function get_techrefsubs($postData) {
        $response = array();
        $this->db->where('board_id', $postData['id']);
        $res = $this->db->get('main_subject')->result();
        return $res;
    }

    function get_subs($postData) {
        $response = array();
        $this->db->distinct();
        $this->db->select('subject.name as subjectName');
        $this->db->select('websupport.subject as subjectId');
        $this->db->from('websupport as websupport');
        $this->db->join('subject as subject', 'websupport.subject=subject.id', 'INNER');
        $this->db->where('websupport.classes', $postData['id']);
        $response = $this->db->get()->result_array();
        return $response;
    }

    function sub_ch() {
        $this->db->select('COUNT(websupport.subject) as total');
        $this->db->select('category.name as catName');
        $this->db->select('subject.name as subName');
        $this->db->from('websupport as websupport');
        $this->db->join('subject as subject', 'subject.id=websupport.subject', 'INNER');
        $this->db->join('category as category', 'category.id=websupport.type', 'INNER');
        $this->db->group_by('subject.id,category.id');
        $res = $this->db->get()->result();
        return $res;
    }
	
	function get_all_teacherdata() {
        $this->db->select('state.StateName as statename');
        $this->db->select('subject.name as subName');
        $this->db->select('web_user.*');
        $this->db->from('web_user as web_user');
        $this->db->join('state as state', 'state.StateID = web_user.state', 'LEFT');
        $this->db->join('subject as subject', 'subject.sid = web_user.subject', 'LEFT');
		$this->db->where('web_user.user_type', 'Teacher');
		$this->db->group_by('web_user.id');
		$this->db->order_by('web_user.id', DESC);
        $res = $this->db->get()->result();
		
        return $res;
    }
	
	function get_all_studentdata() {
		$this->db->distinct();
        $this->db->select('state.StateName as statename');
        $this->db->select('subject.name as subName');
        $this->db->select('web_user.*');
        $this->db->from('web_user as web_user');
        $this->db->join('state as state', 'state.StateID = web_user.state', 'LEFT');
        $this->db->join('subject as subject', 'subject.sid = web_user.subject', 'LEFT');
		$this->db->where('web_user.user_type', 'Student');
		$this->db->group_by('web_user.id');
		$this->db->order_by('web_user.id', DESC);		
        $res = $this->db->get()->result(); 

       return $res;
    }
	
	function teacher_student(){ //stu_teacher_id  //teacher_code
		$this->db->where('stu_teacher_id',$this->session->userdata('teacher_code'));
        $res = $this->db->get('web_user')->result();
        return $res;
	}
	
	function student_teacher(){ //stu_teacher_id  //teacher_code
		$this->db->where('teacher_code',$this->session->userdata('stu_teacher_id'));
        $res = $this->db->get('web_user')->result();
        return $res;
	}

// Support End
}
