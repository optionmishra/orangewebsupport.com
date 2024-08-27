<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WebModel extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }

    public function validate_email($id)
    {
        if (!empty($id)) {
            $this->db->where('email', $id);
        }
        $res = $this->db->get('web_user')->result();
        return $res;
    }

    public function validate_student($id)
    {
        if (!empty($id)) {
            $this->db->where('teacher_code', $id);
            $res = $this->db->get('web_user')->row_array();
            if (!$res) {
                $this->error = $this->db->error();
                return FALSE;
            } else {
                return $res;
            }
        } else {
            return FALSE;
        }
    }

    function Webuser()
    {
        $username = $this->session->userdata('username');
        if (!empty($username)) {
            $this->db->where('email', $username);
        }
        $result = $this->db->get('web_user')->row();
        if (!$result) {
            $this->error = $this->db->error();
            return FALSE;
        } else {
            return $result;
        }
    }

    function subjects()
    {
        $sub = $this->session->userdata('subject');
        $this->db->where('id', $sub);
        $result = $this->db->get('subject')->result();
        if (!$result) {
            $this->error = $this->db->error();
            return FALSE;
        } else {
            return $result;
        }
    }

    function msubjects()
    {
        $sub = $this->session->userdata('msubject');
        $this->db->where('id', $sub);
        $result = $this->db->get('main_subject')->result();
        if (!$result) {
            $this->error = $this->db->error();
            return FALSE;
        } else {
            return $result;
        }
    }
}
