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
    $this->form_validation->set_rules('assign_id', 'Assign ID', 'required|trim');

    // Initialize variables
    $data = [
      'class'         => $this->input->post('class'),
      'section'       => $this->input->post('section'),
      'student_code'  => $this->input->post('student_code'),
      'assign_id'     => $this->input->post('assign_id'),
      'result_message' => '',
      'status'        => '',
      'results'       => null
    ];

    // Process form if submitted and validated
    if ($this->form_validation->run() == TRUE) {
      $class        = $data['class'];
      $section      = $data['section'];
      $student_code = $data['student_code'];
      $assign_id    = $data['assign_id'];

      // Get student submissions grouped by student_id and paper mode
      $submissions = $this->result_model->get_student_submissions($class, $section, $student_code, $assign_id);

      if ($submissions) {
        // Group submissions by student_id
        $students_results = [];
        foreach ($submissions as $submission) {
          $student_id = $submission->student_id;
          if (!isset($students_results[$student_id])) {
            $students_results[$student_id] = [
              'student_id'      => $student_id,
              'class'           => $class,
              'section'         => $section,
              'stu_teacher_code' => $student_code,
              'obj_1'         => null,
              'obj_1_dt'      => null,
              'obj_2'         => null,
              'obj_2_dt'      => null,
              'obj_3'         => null,
              'obj_3_dt'      => null,
              'obj_4'         => null,
              'obj_4_dt'      => null
            ];
          }

          // Set the appropriate result field based on paper mode
          switch ($submission->paper_mode) {
            case '11':
              $students_results[$student_id]['obj_1']   = $submission->obtained_marks . '/' . $submission->total_marks;
              $students_results[$student_id]['obj_1_dt'] = $submission->paper_date;
              break;
            case '12':
              $students_results[$student_id]['obj_2']   = $submission->obtained_marks . '/' . $submission->total_marks;
              $students_results[$student_id]['obj_2_dt'] = $submission->paper_date;
              break;
            case '13':
              $students_results[$student_id]['obj_3']   = $submission->obtained_marks . '/' . $submission->total_marks;
              $students_results[$student_id]['obj_3_dt'] = $submission->paper_date;
              break;
            case '14':
              $students_results[$student_id]['obj_4']   = $submission->obtained_marks . '/' . $submission->total_marks;
              $students_results[$student_id]['obj_4_dt'] = $submission->paper_date;
              break;
          }
        }

        // Process each student's result record individually
        $successMessages = [];
        $errorMessages   = [];
        foreach ($students_results as $student_id => $result_data) {
          // Check if the student result already exists using student_id
          $existing_result = $this->result_model->get_student_result_by_id($student_id);
          if ($existing_result) {
            // Prepare update data (only include fields that have new data)
            $update_data = [];
            if ($result_data['obj_1'] !== null) {
              $update_data['obj_1']    = $result_data['obj_1'];
              $update_data['obj_1_dt'] = $result_data['obj_1_dt'];
            }
            if ($result_data['obj_2'] !== null) {
              $update_data['obj_2']    = $result_data['obj_2'];
              $update_data['obj_2_dt'] = $result_data['obj_2_dt'];
            }
            if ($result_data['obj_3'] !== null) {
              $update_data['obj_3']    = $result_data['obj_3'];
              $update_data['obj_3_dt'] = $result_data['obj_3_dt'];
            }
            if ($result_data['obj_4'] !== null) {
              $update_data['obj_4']    = $result_data['obj_4'];
              $update_data['obj_4_dt'] = $result_data['obj_4_dt'];
            }

            if (!empty($update_data)) {
              if ($this->result_model->update_result($existing_result->id, $update_data)) {
                $successMessages[] = "Result for student ID {$student_id} updated successfully!";
              } else {
                $errorMessages[] = "Error updating result for student ID {$student_id}.";
              }
            } else {
              $successMessages[] = "No new results to update for student ID {$student_id}.";
            }
          } else {
            // Insert new result record
            if ($this->result_model->insert_result($result_data)) {
              $successMessages[] = "Result for student ID {$student_id} generated and saved successfully!";
            } else {
              $errorMessages[] = "Error saving result for student ID {$student_id}.";
            }
          }
        }

        // Combine messages for display
        if (!empty($errorMessages)) {
          $data['result_message'] = implode("<br>", $errorMessages);
          $data['status'] = 'error';
        } else {
          $data['result_message'] = implode("<br>", $successMessages);
          $data['status'] = 'success';
        }

        // Optionally, retrieve all results for display (this method returns an array of result records)
        $data['results'] = $this->result_model->get_results_by_class_section_teachercode($class, $section, $student_code);
      } else {
        $data['result_message'] = "No submissions found for the specified criteria.";
        $data['status'] = 'error';
      }
    }

    // Load view with data
    $this->load->view('results/generator_view', $data);
  }
}
