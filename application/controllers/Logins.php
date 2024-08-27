<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Logins extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->model('AuthModel');
        $this->load->helper('url');
    }

    public function teacher()
    {

        $username = 'Velammalbodhicampus@gmail.com';
        $password = 'orange@2024';
        $res = $this->AuthModel->validate_web($username, $password);
        if (!$res) {
            $status = $this->session->userdata('status');
            $this->session->set_flashdata('error', 'Sorry! Email or Password is incorrect');
            redirect();
        } else {
            $this->session->set_flashdata('login_type', 'auto');
            header("location:" . base_url() . 'dashboard');
        }
    }

    public function student($class)
    {

        $username = "student_$class@orangewebsupport.co.in";
        $password = 'orange@2024';
        $res = $this->AuthModel->validate_web($username, $password);
        if (!$res) {
            $status = $this->session->userdata('status');
            $this->session->set_flashdata('error', 'Sorry! Email or Password is incorrect');
            redirect();
        } else {
            $this->session->set_flashdata('login_type', 'auto');
            header("location:" . base_url() . 'dashboard');
        }
    }

    public function rgve_teacher()
    {

        $username = 'rajagopal@velammal.edu.in';
        $password = 'orange@2024';
        $res = $this->AuthModel->validate_web($username, $password);
        if (!$res) {
            $status = $this->session->userdata('status');
            $this->session->set_flashdata('error', 'Sorry! Email or Password is incorrect');
            redirect();
        } else {
            $this->session->set_flashdata('login_type', 'auto');
            header("location:" . base_url() . 'dashboard');
        }
    }

    public function rgve_student($class)
    {

        $username = "student_$class@velammal.edu.in";
        $password = 'orange@2024';
        $res = $this->AuthModel->validate_web($username, $password);
        if (!$res) {
            $status = $this->session->userdata('status');
            $this->session->set_flashdata('error', 'Sorry! Email or Password is incorrect');
            redirect();
        } else {
            $this->session->set_flashdata('login_type', 'auto');
            header("location:" . base_url() . 'dashboard');
        }
    }
}
