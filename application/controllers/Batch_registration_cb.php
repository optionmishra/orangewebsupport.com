<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Batch_registration_cb extends CI_Controller
{

  public $upload_error;
  public $error;

  function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
    $this->load->helper('string');
    $this->load->library('session');
    $this->load->library('form_validation');
    $this->load->library('Permission');
    $this->load->helper('form');
    $this->load->model('AuthModel');
    $this->load->model('WebModel');
    $this->load->library('upload');
    $this->load->library('email');
    $this->load->library('excel');
  }


  function csvToArray($filePath, $delimiter = ',', $enclosure = '"', $lineEnding = "\r\n", $sheetIndex = 0, $header = true)
  {
    //Create excel reader after determining the file type
    $inputFileName = $filePath;
    /**  Identify the type of $inputFileName  **/
    $inputFileType = 'CSV';
    /**  Create a new Reader of the type that has been identified  **/
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    // $objReader->setDelimiter($delimiter);
    // $objReader->setEnclosure($enclosure);
    // $objReader->setLineEnding($lineEnding);
    // $objReader->setSheetIndex($sheetIndex);
    // /** Set read type to read cell data onl **/
    // $objReader->setReadDataOnly(true);
    /**  Load $inputFileName to a PHPExcel Object  **/
    $objPHPExcel = $objReader->load($inputFileName);
    //Get worksheet and built array with first row as header
    $objWorksheet = $objPHPExcel->getActiveSheet();

    //excel with first row header, use header as key
    if ($header) {
      $highestRow    = $objWorksheet->getHighestRow();
      $highestColumn = $objWorksheet->getHighestColumn();
      $headingsArray = $objWorksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true);
      $headingsArray = $headingsArray[1];

      $r = -1;
      $namedDataArray = array();
      for ($row = 2; $row <= $highestRow; ++$row) {
        $dataRow = $objWorksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true, true);
        if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
          ++$r;
          foreach ($headingsArray as $columnKey => $columnHeading) {
            $namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
          }
        }
      }
    } else {
      //excel sheet with no header
      $namedDataArray = $objWorksheet->toArray(null, true, true, true);
    }

    return $namedDataArray;
  }

  function show_data()
  {
    $sections = $this->db->get('class_section')->result();
    $sectionsArr = $this->groupSectionsByLetter($sections);

    // foreach ($sectionsArr as $letter => $sectionIds) {
    // echo '<pre>', var_dump($sectionsArr['A'][$class]), '</pre>';
    // }
    // exit;

    $classes = [
      1 => $this->csvToArray('assets/CB1.csv'),
      2 => $this->csvToArray('assets/CB2.csv'),
      4 => $this->csvToArray('assets/CB4.csv'),
      5 => $this->csvToArray('assets/CB5.csv'),
    ];

    foreach ($classes as $classId => $classData) {
      // echo '<pre>', var_dump($sectionsArr['A'][$classId]), '</pre>';
      // echo '<pre>', var_dump($classData), '</pre>';
      $this->check_register($classData, $classId, $sectionsArr);
    }
    exit;
  }

  private function groupSectionsByLetter($sections)
  {
    $sectionsArr = [];
    foreach ($sections as $section) {
      $sectionsArr[$section->name][$section->class_id] = $section->id;
    }
    return $sectionsArr;
  }

  function check_register($data, $class, $sectionsArr)
  {
    echo "<h1>Already Registered Emails from Class $class: </h1>";
    $student_data =  array();
    $unregistered_students = array();
    foreach ($data as $row) {
      $student_data['name'] = $row['NAME'];
      $student_data['mobile'] = $row['MOBILE'];
      $student_data['email'] = $row['EMAIL'];
      $student_data['password'] = 'stemrobo';
      $student_data['pin'] = '123456';
      $student_data['school_name'] = "CHRYSALIS BLOCK";
      $student_data['address'] = ' ';
      $student_data['stu_teacher_id'] = $class == 1 ? '2CJD690M81' : 'C2E60KI9L3';
      $student_data['city'] = ' ';
      $student_data['state'] = ' ';

      $student_data['class'] = $class;
      switch (trim($row['SECTION'])) {
        case 'A':
          $student_data['class_section_name'] = $sectionsArr['A'][$class];
          break;
        case 'B':
          $student_data['class_section_name'] = $sectionsArr['B'][$class];
          break;
        case 'C':
          $student_data['class_section_name'] = $sectionsArr['C'][$class];
          break;
        case 'D':
          $student_data['class_section_name'] = $sectionsArr['D'][$class];
          break;
        case 'E':
          $student_data['class_section_name'] = $sectionsArr['E'][$class];
          break;
        case 'F':
          $student_data['class_section_name'] = $sectionsArr['F'][$class];
          break;
      }

      // $this->db->where('email', $row['EMAIL']);
      // $this->db->delete('web_user');

      $check = $this->WebModel->validate_email($row['EMAIL']);
      if (!empty($check)) {
        // array_push($unregistered_students, $student_data);
        echo '<pre>', $row['EMAIL'], '</pre>';
      } else {
        $res = $this->add_student_custom($student_data);
        if ($res) {
          echo '<pre>Registered :', $row['EMAIL'], '</pre>';
        }
      }
    }
    // return $unregistered_students;
  }

  function add_student_custom($student_data)
  {
    $teacher_code = $student_data['stu_teacher_id'];
    $check_tu = $this->WebModel->validate_student($teacher_code);
    // $teacher_book = $check_tu['subject'];
    $board = $check_tu['board_name'];
    $start_session = $check_tu['session_start'];
    $end_session = $check_tu['session_end'];

    // Book Name for student
    $student_class = $student_data['class'];
    $series_classes = unserialize($check_tu['series_classes']);
    $books = array();
    foreach ($series_classes as $key => $value) {
      $this->db->where('sid', $key);
      $this->db->where_in('class', $student_class);
      $this->db->select('name');
      $subjects = $this->db->get('subject')->result();
      foreach ($subjects as $subject) {
        $books[] = $subject->name;
      }
      // select 1 subject for student based on his class
      if (in_array($student_class, $value)) {
        $teacher_book = $key;
      }
      // end mod
    }
    $book_name = implode(',', $books);

    $res = $this->db->insert('web_user', [
      // $res = [
      'session_start' => $start_session,
      'session_end' => $end_session,
      'classes' => $student_class,
      'board_name' => $board,
      'subject' => $teacher_book,
      'user_type' => 'Student',
      'status' => 1,
      'activeBooks' => 1,
      'book_name' => $book_name,
      'fullname' => $student_data['name'],
      'mobile' => $student_data['mobile'],
      'email' => $student_data['email'],
      'pin' => $student_data['pin'],
      'address' => $student_data['address'],
      'stu_teacher_id' => $student_data['stu_teacher_id'],
      'city' => $student_data['city'],
      'state' => $student_data['state'],
      'class_section' => $student_data['class_section_name'],
      'school_name' => $student_data['school_name'],
      'password' => $student_data['password'],
    ]);
    // ];
    return $res;
  }
}
