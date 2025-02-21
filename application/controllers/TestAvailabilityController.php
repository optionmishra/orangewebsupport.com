<?php

/**
 * TestAvailabilityController
 * Handles the test availability tracking functionality for teachers
 */
class TestAvailabilityController extends CI_Controller
{
  private $test_types = [
    // 'all' => 'All Tests',
    // 'subjective' => [
    '21' => 'Subjective Test 1',
    '22' => 'Subjective Test 2',
    // ],
    // 'objective' => [
    '11' => 'Objective Test 1',
    '12' => 'Objective Test 2',
    '13' => 'Objective Test 3',
    '14' => 'Objective Test 4'
    // ]
  ];

  public function __construct()
  {
    parent::__construct();
    $this->load->model('TestAvailabilityModel');
    $this->load->library('session');
    $this->load->model('AuthModel');
    $this->load->helper('url');
    // $this->checkTeacherAuth();
  }

  /**
   * Main view for test availability
   */
  public function index()
  {
    // Get filter parameters
    $filters = $this->getFilters();

    // Get test availability data
    $availability_data = $this->TestAvailabilityModel->getStudentTestAvailability($filters);

    $data = [
      'title' => 'Test Availability Dashboard',
      'test_types' => $this->test_types,
      // 'classes' => $this->TestAvailabilityModel->getTeacherClasses($this->session->userdata('teacher_code')),
      // 'classes' => $this->AuthModel->classes_teacher_new($this->session->userdata('teacher_classess'), $this->session->userdata('teacher_code')),
      'sections' => $this->TestAvailabilityModel->getClassSections($this->session->userdata('teacher_code'), $filters['class']),
      'filters' => $filters,
      'availability_data' => $availability_data
    ];


    // $this->load->view('teacher/header', $data);
    $this->load->view('web/test_availability', $data);
    // $this->load->view('teacher/footer');
  }

  /**
   * Get and validate filter parameters
   */
  private function getFilters()
  {
    return [
      'class' => $this->input->get('class', TRUE) ?: 'all',
      'section' => $this->input->get('section', TRUE) ?: 'all',
      'test_type' => $this->input->get('test_type', TRUE) ?: 'all',
      'date_start' => $this->input->get('date_start', TRUE) ?: date('Y-m-d'),
      'date_end' => $this->input->get('date_end', TRUE) ?: date('Y-m-d', strtotime('+7 days')),
      'sort_by' => $this->input->get('sort_by', TRUE) ?: 'student_name',
      'sort_dir' => $this->input->get('sort_dir', TRUE) ?: 'asc'
    ];
  }

  private function checkTeacherAuth()
  {
    if (!$this->session->userdata('is_teacher')) {
      redirect('auth/login');
    }
  }
}
