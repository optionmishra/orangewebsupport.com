<?php
// application/models/Result_model.php

defined('BASEPATH') or exit('No direct script access allowed');

class Result_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  /**
   * Get student submissions grouped by student_id and paper mode.
   * This ensures that results for each student are calculated separately.
   */
  public function get_student_submissions($class, $section, $student_code, $assign_id)
  {
    $this->db->select('student_id, paper_mode, SUM(ans_marks) as obtained_marks, SUM(qus_marks) as total_marks, MAX(paper_date) as paper_date');
    $this->db->from('paper_submision');
    $this->db->where('student_class', $class);
    $this->db->where('student_section', $section);
    $this->db->where('student_code', $student_code);
    $this->db->where('assign_id', $assign_id);
    $this->db->group_by(['student_id', 'paper_mode']);

    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      return $query->result();
    }
    return false;
  }

  /**
   * Get a student result record by student_id.
   */
  public function get_student_result_by_id($student_id)
  {
    $this->db->where('student_id', $student_id);
    $query = $this->db->get('result');

    if ($query->num_rows() > 0) {
      return $query->row();
    }
    return false;
  }

  /**
   * Get results for all students in a given class, section, and teacher code.
   */
  public function get_results_by_class_section_teachercode($class, $section, $teacher_code)
  {
    $this->db->where('class', $class);
    $this->db->where('section', $section);
    $this->db->where('stu_teacher_code', $teacher_code);
    $query = $this->db->get('result');

    if ($query->num_rows() > 0) {
      return $query->result();
    }
    return false;
  }

  /**
   * Update an existing result record.
   */
  public function update_result($result_id, $data)
  {
    $this->db->where('id', $result_id);
    return $this->db->update('result', $data);
  }

  /**
   * Insert a new result record.
   */
  public function insert_result($data)
  {
    return $this->db->insert('result', $data);
  }
}
