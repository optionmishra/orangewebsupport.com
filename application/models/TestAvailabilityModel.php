<?php
class TestAvailabilityModel extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  /**
   * Get all students and their test availability for a teacher
   */
  public function getStudentTestAvailability($filters = [])
  {
    // Get teacher information
    $teacher_code = $this->session->userdata('teacher_code');
    $teacher_email = $this->session->userdata('username');
    // echo var_dump($this->session->userdata());

    // First get all students for this teacher
    $this->db->select('wu.id, wu.stu_teacher_id, wu.fullname as student_name, 
    c.name as class_name, cs.name as section_name, wu.email, wu.classes as class_id, wu.class_section as section_id');
    $this->db->from('web_user wu');
    $this->db->join('classes c', 'c.id = wu.classes', 'left');
    $this->db->join('class_section cs', 'cs.id = wu.class_section', 'left');
    $this->db->where('wu.stu_teacher_id', $teacher_code);
    $this->db->where('wu.user_type', 'student');
    // $this->db->where('wu.status', 1);

    // Apply class/section filters if provided
    // if (!empty($filters['class'])) {
    //   $this->db->where('classes', $filters['class']);
    // }
    // if (!empty($filters['section'])) {
    //   $this->db->where('class_section', $filters['section']);
    // }

    $students = $this->db->get()->result_array();

    // For each student, get their test information
    foreach ($students as &$student) {
      $student['tests'] = $this->getStudentTests(
        $student,
        $teacher_email,
        $filters
      );
    }

    return $students;
  }

  /**
   * Get tests assigned to a specific student
   */
  private function getStudentTests($student, $teacher_email, $filters = null)
  {
    $this->db->select('id, paper_mode, date_start, date_end');
    $this->db->from('paper_assign');
    $this->db->where('class_name', $student['class_id']);
    $this->db->where('section_name', $student['section_id']);
    $this->db->where('teacher_id', $teacher_email);
    $this->db->where('status', 1);

    // // Apply test type filter if provided
    // if (!empty($filters['test_type'])) {
    //   $this->db->where('paper_mode', $filters['test_type']);
    // }

    // // Apply date range filters if provided
    // if (!empty($filters['date_start'])) {
    //   $this->db->where('date_start >=', $filters['date_start']);
    // }
    // if (!empty($filters['date_end'])) {
    //   $this->db->where('date_end <=', $filters['date_end']);
    // }

    $tests = $this->db->get()->result_array();

    // Check submission status for each test
    foreach ($tests as &$test) {
      $test['status'] = $this->getTestStatus($student, $test);
    }

    return $tests;
  }

  /**
   * Get test status and reason for a specific student and test
   */
  private function getTestStatus($student, $test)
  {
    // Check if test has been submitted
    $this->db->where('student_id', $student['id']);
    $this->db->where('student_code', $student['stu_teacher_id']);
    $this->db->where('assign_id', $test['id']);
    $submission = $this->db->get('paper_submision')->row_array();

    $current_date = date('Y-m-d');

    if ($submission) {
      return [
        'can_attempt' => false,
        'reason' => 'Already submitted on ' . $submission['paper_date']
      ];
    }

    if ($current_date < $test['date_start']) {
      return [
        'can_attempt' => false,
        'reason' => 'Test starts on ' . $test['date_start']
      ];
    }

    if ($current_date > $test['date_end']) {
      return [
        'can_attempt' => false,
        'reason' => 'Test ended on ' . $test['date_end']
      ];
    }

    return [
      'can_attempt' => true,
      'reason' => 'Can be attempted until ' . $test['date_end']
    ];
  }

  /**
   * Get available classes for the teacher
   */
  public function getTeacherClasses($teacher_code)
  {
    // $this->db->select('DISTINCT(classes) as class');
    // $this->db->from('web_user');
    // $this->db->where('stu_teacher_id', $teacher_code);
    // $this->db->where('user_type', 'student');
    // $this->db->where('status', 1);
    // return $this->db->get()->result_array();

    $this->db->select('classes');
    $this->db->from('web_user');
    $this->db->where('teacher_code', $teacher_code);
    return $this->db->get()->result();
  }

  /**
   * Get sections for a specific class
   */
  public function getClassSections($teacher_code, $class)
  {
    $this->db->select('DISTINCT(class_section) as section');
    $this->db->from('web_user');
    $this->db->where('stu_teacher_id', $teacher_code);
    $this->db->where('classes', $class);
    $this->db->where('user_type', 'student');
    $this->db->where('status', 1);
    return $this->db->get()->result_array();
  }

  public function getStudentPanelTests($studentEmail)
  {
    $this->db->select('id, stu_teacher_id, fullname as student_name, classes as class_name, class_section as section_name');
    $this->db->from('web_user');
    $this->db->where('email', $studentEmail);
    $this->db->where('status', 1);

    $student = $this->db->get()->row_array();
    $teacher_email = $this->db->where('teacher_code', $student['stu_teacher_id'])->get('web_user')->row_array()['email'];

    return $this->getStudentTests($student, $teacher_email);
  }
}
