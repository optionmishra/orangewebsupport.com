<?php

/**
 * Student Panel Controller
 * Handles student test panel functionality including checking and displaying
 * available subjective and objective tests
 */
class StudentPanel extends CI_Controller
{
  // Test type constants
  const SUBJECTIVE_TEST_1 = '21';
  const SUBJECTIVE_TEST_2 = '22';
  const OBJECTIVE_TESTS = ['11', '12', '13', '14'];

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->model('AuthModel');
    $this->load->model('WebModel');
    $this->load->helper('url');
    $this->load->model('TestAvailabilityModel');
  }

  /**
   * Main student panel function
   * Displays available tests and their status
   */
  public function student_panel()
  {
    // Check if user is logged in
    if (!$this->session->userdata('username')) {
      redirect(base_url('web/logout'));
      return;
    }

    // // Get student information from session
    // $student_data = [
    //   'id' => $this->session->userdata('user_id'),
    //   'code' => $this->session->userdata('stu_teacher_code'),
    //   'class' => $this->session->userdata('classes'),
    //   'section' => $this->session->userdata('section'),
    // ];

    // // Get date range for tests
    // $date_range = $this->getDateRange();

    // // Initialize test data
    // $test_data = $this->initializeTestData();

    // // Check if any papers are assigned
    // // $has_papers = $this->AuthModel->check_class_paper(
    // //   $student_data['class'],
    // //   $student_data['section'],
    // //   $student_data['code'],
    // //   $date_range['start'],
    // //   $date_range['end']
    // // );

    // // if ($has_papers) {
    // $test_data = $this->processTestData($student_data, $date_range);
    // // }

    // Prepare view data
    // $view_data = $this->prepareViewData($test_data);
    $view_data = $this->prepareViewData();

    // Load views
    $this->loadViews($view_data);
  }

  /**
   * Get current date and next day for test range
   */
  private function getDateRange()
  {
    $current_date = date("Y-m-d");
    return [
      'start' => $current_date,
      'end' => date('Y-m-d', strtotime($current_date . " + 1 day"))
    ];
  }

  /**
   * Initialize test data structure
   */
  private function initializeTestData()
  {
    return [
      'subjective' => [
        'test1' => ['data' => '', 'date' => ''],
        'test2' => ['data' => '', 'date' => '']
      ],
      'objective' => [
        'test1' => ['data' => '', 'date' => ''],
        'test2' => ['data' => '', 'date' => ''],
        'test3' => ['data' => '', 'date' => ''],
        'test4' => ['data' => '', 'date' => '']
      ],
      'msg' => 'No test has been assigned yet'
    ];
  }

  /**
   * Process test data for both subjective and objective tests
   */
  private function processTestData($student_data, $date_range)
  {
    $test_data = $this->initializeTestData();

    // Process subjective tests
    $subjective_tests = [
      ['mode' => self::SUBJECTIVE_TEST_1, 'key' => 'test1'],
      ['mode' => self::SUBJECTIVE_TEST_2, 'key' => 'test2']
    ];

    foreach ($subjective_tests as $test) {
      $result = $this->processSubjectiveTest(
        $student_data,
        $date_range,
        $test['mode'],
        $test['key']
      );
      $test_data['subjective'][$test['key']] = $result;
    }

    // Process objective tests
    foreach (self::OBJECTIVE_TESTS as $index => $mode) {
      $key = 'test' . ($index + 1);
      $result = $this->processObjectiveTest(
        $student_data,
        $date_range,
        $mode,
        $key
      );
      $test_data['objective'][$key] = $result;
    }

    $test_data['msg'] = 'You are already Done! Thank You.';
    return $test_data;
  }

  /**
   * Process individual subjective test
   */
  private function processSubjectiveTest($student_data, $date_range, $mode, $key)
  {
    $test = $this->AuthModel->checkAssignedTest(
      $student_data['class'],
      $student_data['section'],
      $student_data['code'],
      $date_range['start'],
      $date_range['end'],
      $mode
    );

    if (!$test) {
      return ['data' => '', 'date' => ''];
    }

    $submission = '';
    if ($this->AuthModel->checkTestSubmitted(
      // $student_data['class'],
      // $student_data['section'],
      // $student_data['code'],
      $test['id'],
      // $mode,
      $student_data['id']
    ) === FALSE) {
      $submission = $test;
    }

    return [
      'data' => $submission,
      'date' => $test['date_end']
    ];
  }

  /**
   * Process individual objective test
   */
  private function processObjectiveTest($student_data, $date_range, $mode, $key)
  {
    $test = $this->AuthModel->checkAssignedTest(
      $student_data['class'],
      $student_data['section'],
      $student_data['code'],
      $date_range['start'],
      $date_range['end'],
      $mode
    );

    if (!$test) {
      return ['data' => '', 'date' => ''];
    }

    $submission = '';
    if ($this->AuthModel->checkTestSubmitted(
      // $student_data['class'],
      // $student_data['section'],
      // $student_data['code'],
      $test['id'],
      // $mode,
      $student_data['id']
    ) === FALSE) {
      $submission = $test;
    }

    return [
      'data' => $submission,
      'date' => $test['date_end']
    ];
  }

  /**
   * Prepare data for view
   */
  private function prepareViewData()
  {
    return [
      'title' => 'Student Panel',
      'page' => 'Student panel',
      'logo' => $this->AuthModel->content_row('Logo'),
      'logo1' => $this->AuthModel->content_row('Logo_index'),
      'mobile1' => $this->AuthModel->content('Mobile1'),
      'mobile2' => $this->AuthModel->content('Mobile2'),
      'email1' => $this->AuthModel->content('Email1'),
      'email2' => $this->AuthModel->content('Email2'),
      'address' => $this->AuthModel->content('Address'),
      'copyright' => $this->AuthModel->content('Copyright'),
      'user' => $this->WebModel->Webuser(),
      'classes' => $this->AuthModel->classes_teacher($this->session->userdata('classes')),
      // 'subjective_test1' => $test_data['subjective']['test1']['data'],
      // 'subjective_test2' => $test_data['subjective']['test2']['data'],
      // 'objective_test1' => $test_data['objective']['test1']['data'],
      // 'objective_test2' => $test_data['objective']['test2']['data'],
      // 'objective_test3' => $test_data['objective']['test3']['data'],
      // 'objective_test4' => $test_data['objective']['test4']['data'],
      // 'msg' => $test_data['msg'],
      // 'sub1_date' => $test_data['subjective']['test1']['date'],
      // 'sub2_date' => $test_data['subjective']['test2']['date'],
      // 'ob1_date' => $test_data['objective']['test1']['date'],
      // 'ob2_date' => $test_data['objective']['test2']['date'],
      // 'ob3_date' => $test_data['objective']['test3']['date'],
      // 'ob4_date' => $test_data['objective']['test4']['date'],
      'tests' => $this->TestAvailabilityModel->getStudentPanelTests($this->session->userdata('username'))
    ];
  }

  /**
   * Load required views
   */
  private function loadViews($view_data)
  {
    $this->load->view('globals/web/header', $view_data);
    $this->load->view('web/student_panel');
    $this->load->view('globals/web/footer', $view_data);
  }
}
