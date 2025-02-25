<?php
// application/controllers/ResultGenerator.php

defined('BASEPATH') or exit('No direct script access allowed');

class ResultGenerator extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('result_model');
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->library('form_validation');
  }

  public function index()
  {
    // Set validation rules
    $this->form_validation->set_rules('class', 'Class', 'required|numeric');
    $this->form_validation->set_rules('section', 'Section', 'required|numeric');
    $this->form_validation->set_rules('student_code', 'Student Code', 'required|trim');
    $this->form_validation->set_rules('assign_id', 'assign_id', 'required|trim');

    // Initialize variables
    $data = [
      'class' => $this->input->post('class'),
      'section' => $this->input->post('section'),
      'student_code' => $this->input->post('student_code'),
      'assign_id' => $this->input->post('assign_id'),
      'result_message' => '',
      'status' => '',
      'results' => null
    ];

    // Process form if submitted and validated
    if ($this->form_validation->run() == TRUE) {
      $class = $data['class'];
      $section = $data['section'];
      $student_code = $data['student_code'];
      $assign_id = $data['assign_id'];

      // Get student submissions by paper mode
      $submissions = $this->result_model->get_student_submissions($class, $section, $student_code, $assign_id);

      if ($submissions) {
        $student_id = '';
        $result_data = [
          'obj_1' => null,
          'obj_1_dt' => null,
          'obj_2' => null,
          'obj_2_dt' => null,
          'obj_3' => null,
          'obj_3_dt' => null,
          'obj_4' => null,
          'obj_4_dt' => null
        ];

        // Process each paper mode result
        foreach ($submissions as $submission) {
          $student_id = $submission->student_id;
          $obtained_marks = (int)$submission->obtained_marks;
          $total_marks = (int)$submission->total_marks;
          $paper_mode = $submission->paper_mode;
          $paper_date = $submission->paper_date;

          // Set appropriate result data based on paper mode
          switch ($paper_mode) {
            case '11':
              $result_data['obj_1'] = $obtained_marks . '/' . $total_marks;
              $result_data['obj_1_dt'] = $paper_date;
              break;
            case '12':
              $result_data['obj_2'] = $obtained_marks . '/' . $total_marks;
              $result_data['obj_2_dt'] = $paper_date;
              break;
            case '13':
              $result_data['obj_3'] = $obtained_marks . '/' . $total_marks;
              $result_data['obj_3_dt'] = $paper_date;
              break;
            case '14':
              $result_data['obj_4'] = $obtained_marks . '/' . $total_marks;
              $result_data['obj_4_dt'] = $paper_date;
              break;
          }
        }

        // Check if student exists in result table
        $existing_result = $this->result_model->get_student_result($class, $section, $student_code);

        if ($existing_result) {
          // Update existing record, but only for fields that have new data
          $update_data = [];

          // Only add fields to update_data if they have values from submissions
          if ($result_data['obj_1'] !== null) {
            $update_data['obj_1'] = $result_data['obj_1'];
            $update_data['obj_1_dt'] = $result_data['obj_1_dt'];
          }

          if ($result_data['obj_2'] !== null) {
            $update_data['obj_2'] = $result_data['obj_2'];
            $update_data['obj_2_dt'] = $result_data['obj_2_dt'];
          }

          if ($result_data['obj_3'] !== null) {
            $update_data['obj_3'] = $result_data['obj_3'];
            $update_data['obj_3_dt'] = $result_data['obj_3_dt'];
          }

          if ($result_data['obj_4'] !== null) {
            $update_data['obj_4'] = $result_data['obj_4'];
            $update_data['obj_4_dt'] = $result_data['obj_4_dt'];
          }

          // Only update if there are fields to update
          if (!empty($update_data)) {
            if ($this->result_model->update_result($existing_result->id, $update_data)) {
              $data['result_message'] = "Result updated successfully!";
              $data['status'] = 'success';
            } else {
              $data['result_message'] = "Error updating result.";
              $data['status'] = 'error';
            }
          } else {
            $data['result_message'] = "No new results to update.";
            $data['status'] = 'info';
          }
        } else {
          // For new records, only include fields that have values
          $insert_data = [
            'student_id' => $student_id,
            'class' => $class,
            'section' => $section,
            'stu_teacher_code' => $student_code
          ];

          // Only add objective fields that have values
          if ($result_data['obj_1'] !== null) {
            $insert_data['obj_1'] = $result_data['obj_1'];
            $insert_data['obj_1_dt'] = $result_data['obj_1_dt'];
          }

          if ($result_data['obj_2'] !== null) {
            $insert_data['obj_2'] = $result_data['obj_2'];
            $insert_data['obj_2_dt'] = $result_data['obj_2_dt'];
          }

          if ($result_data['obj_3'] !== null) {
            $insert_data['obj_3'] = $result_data['obj_3'];
            $insert_data['obj_3_dt'] = $result_data['obj_3_dt'];
          }

          if ($result_data['obj_4'] !== null) {
            $insert_data['obj_4'] = $result_data['obj_4'];
            $insert_data['obj_4_dt'] = $result_data['obj_4_dt'];
          }

          if ($this->result_model->insert_result($insert_data)) {
            $data['result_message'] = "Result generated and saved successfully!";
            $data['status'] = 'success';
          } else {
            $data['result_message'] = "Error saving result.";
            $data['status'] = 'error';
          }
        }

        // Get the current result data to display
        $data['results'] = $this->result_model->get_student_result($class, $section, $student_code);
      } else {
        $data['result_message'] = "No submissions found for this student.";
        $data['status'] = 'error';
      }
    }

    // Load view with data
    $this->load->view('results/generator_view', $data);
  }
}
