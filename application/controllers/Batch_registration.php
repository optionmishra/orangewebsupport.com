<?php

class Batch_registration extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
    $this->load->library('upload');
    $this->load->library('excel');
    $this->load->model('WebModel');
  }

  public $upload_directory = FCPATH . 'assets/batch-registrations/';

  public function index()
  {
    $this->load->view('web/batch_registration');
  }

  public function getStandards()
  {
    $standards = $this->db
      ->order_by('class_position', 'ASC')
      ->get('classes')
      ->result();

    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($standards));
  }

  public function getSections($standardId)
  {
    $sections = $this->db
      ->where('class_id', $standardId)
      ->order_by('name', 'ASC')
      ->get('class_section')
      ->result();

    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($sections));
  }

  // public function store()
  // {
  //   $school = $this->input->post('school');
  //   $teacherCode = $this->input->post('teacherCode');
  //   $standard = $this->input->post('standard');
  //   $section = $this->input->post('section');
  //   $fileName = $this->input->post('fileName');

  //   $destination_path = $this->upload_directory . $fileName;
  //   $studentsData = $this->csvToArray($destination_path);

  //   foreach ($studentsData as $student) {
  //     $check = $this->checkEmailExists($student['EMAIL']);
  //     if (!$check) {
  //       $student_data['name'] = $student['NAME'];
  //       $student_data['mobile'] = $student['MOBILE'];
  //       $student_data['email'] = $student['EMAIL'];
  //       $student_data['password'] = $student['PASSWORD'];
  //       $student_data['pin'] = '123456';
  //       $student_data['school_name'] = $school;
  //       $student_data['address'] = ' ';
  //       $student_data['stu_teacher_id'] = $teacherCode;
  //       $student_data['city'] = ' ';
  //       $student_data['state'] = ' ';
  //       $student_data['class'] = $standard;
  //       $student_data['class_section'] = $section;
  //       $this->add_student_custom($student_data);
  //     }
  //   }
  //   return true;
  // }

  public function preview()
  {
    $school = $this->input->post('school');
    $teacherCode = $this->input->post('teacherCode');
    $standard = $this->input->post('standard');
    $section = $this->input->post('section');

    // Validate Teacher Code
    $teacherData = $this->getTeacherData($teacherCode);
    if (!$teacherData) {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode(['error' => 'Invalid teacher code']));
    }

    // Check if file was uploaded
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode(['error' => 'File upload error']));
    }

    // Validate file type
    $file_info = $_FILES['file'];
    $file_extension = strtolower(pathinfo($file_info['name'], PATHINFO_EXTENSION));

    if ($file_extension !== 'csv') {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode(['error' => 'Only CSV files are allowed']));
    }

    // Validate file size (2MB max)
    if ($file_info['size'] > 2048 * 1024) {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode(['error' => 'File size too large. Maximum 2MB allowed']));
    }

    $fileName = date('ymd-hisa') . '.csv';

    // Create directory if it doesn't exist
    if (!is_dir($this->upload_directory)) {
      if (!mkdir($this->upload_directory, 0755, true)) {
        return $this->output
          ->set_content_type('application/json')
          ->set_status_header(500)
          ->set_output(json_encode(['error' => 'Failed to create upload directory']));
      }
    }

    $destination_path = $this->upload_directory . $fileName;

    // Move uploaded file manually
    if (!move_uploaded_file($file_info['tmp_name'], $destination_path)) {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(500)
        ->set_output(json_encode(['error' => 'Failed to move uploaded file']));
    }

    // Verify file was uploaded successfully
    if (!file_exists($destination_path)) {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(500)
        ->set_output(json_encode(['error' => 'File upload verification failed']));
    }

    // Process the CSV file
    $studentsData = $this->csvToArray($destination_path);
    $previewData = $this->getPreviewData($studentsData);

    $data = [
      'basicData' => [
        'school' => $school,
        'teacherCode' => $teacherCode,
        'standard' => $standard,
        'section' => $section,
        'fileName' => $fileName,
      ],
      'studentsData' => $previewData,
      // 'file_path' => $destination_path
    ];

    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($data));
  }

  private function getPreviewData($studentsData)
  {
    $previewData = array();
    foreach ($studentsData as $student) {
      $check = $this->checkEmailExists($student['EMAIL']);
      array_push($previewData, ['name' => $student['NAME'], 'email' => $student['EMAIL'], 'status' => (bool)$check]);
    }
    return $previewData;
  }

  private function checkEmailExists($email)
  {
    return $this->db
      ->where('email', $email)
      ->get('web_user')
      ->first_row();
  }

  private function add_student_custom($student_data)
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
      'class_section' => $student_data['class_section'],
      'school_name' => $student_data['school_name'],
      'password' => $student_data['password'],
    ]);
    return $res;
  }

  private function csvToArray($filePath, $delimiter = ',', $enclosure = '"', $lineEnding = "\r\n", $sheetIndex = 0, $header = true)
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

  public function store()
  {
    // Input validation and sanitization
    $requiredFields = ['school', 'teacherCode', 'standard', 'section', 'fileName'];
    $inputData = [];

    foreach ($requiredFields as $field) {
      $inputData[$field] = $this->input->post($field);
      if (empty($inputData[$field])) {
        throw new InvalidArgumentException("Required field '{$field}' is missing or empty");
      }
    }

    $destination_path = $this->upload_directory . $inputData['fileName'];

    // Validate file exists
    if (!file_exists($destination_path)) {
      throw new RuntimeException("CSV file not found: {$destination_path}");
    }

    $studentsData = $this->csvToArray($destination_path);

    if (empty($studentsData)) {
      return ['success' => false, 'message' => 'No valid student data found in CSV'];
    }

    // Get teacher data once (avoid repeated queries)
    $teacherData = $this->getTeacherData($inputData['teacherCode']);
    if (!$teacherData) {
      throw new RuntimeException("Invalid teacher code: {$inputData['teacherCode']}");
    }

    // Prepare batch data
    $batchData = $this->prepareBatchStudentData($studentsData, $inputData, $teacherData);

    if (empty($batchData)) {
      return ['success' => false, 'message' => 'No new students to insert (all emails already exist)'];
    }

    // Use database transaction for data integrity
    $this->db->trans_start();

    try {
      // Batch insert for better performance
      $result = $this->db->insert_batch('web_user', $batchData);

      if (!$result) {
        throw new RuntimeException('Failed to insert student data');
      }

      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE) {
        throw new RuntimeException('Transaction failed');
      }

      $data = [
        'success' => true,
        'message' => count($batchData) . ' students successfully added',
        'inserted_count' => count($batchData)
      ];

      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($data));
    } catch (Exception $e) {
      $this->db->trans_rollback();
      throw $e;
    }
  }

  private function prepareBatchStudentData($studentsData, $inputData, $teacherData)
  {
    // Get existing emails in one query to avoid N+1 problem
    $emails = array_column($studentsData, 'EMAIL');
    $existingEmails = $this->getExistingEmails($emails);

    // Prepare book data once
    $bookData = $this->prepareBookData($teacherData, $inputData['standard']);

    $batchData = [];
    $timestamp = date('Y-m-d H:i:s');

    foreach ($studentsData as $student) {
      // Skip if email already exists
      if (in_array(strtolower(trim($student['EMAIL'])), $existingEmails)) {
        continue;
      }

      // Validate required student fields
      if (empty($student['EMAIL']) || empty($student['NAME'])) {
        continue; // Skip invalid records
      }

      $batchData[] = [
        'session_start' => $teacherData['session_start'],
        'session_end' => $teacherData['session_end'],
        'classes' => $inputData['standard'],
        'board_name' => $teacherData['board_name'],
        'subject' => $bookData['teacher_book'],
        'user_type' => 'Student',
        'status' => 1,
        'activeBooks' => 1,
        'book_name' => $bookData['book_names'],
        'fullname' => trim($student['NAME']),
        'mobile' => $student['MOBILE'] ?? '',
        'email' => strtolower(trim($student['EMAIL'])),
        'pin' => '123456', // Consider making this configurable
        'address' => '', // Use empty string instead of space
        'stu_teacher_id' => $inputData['teacherCode'],
        'city' => '',
        'state' => '',
        'class_section' => $inputData['section'],
        'school_name' => $inputData['school'],
        'password' => $student['PASSWORD'] ?? $this->generateDefaultPassword(),
        // 'created_at' => $timestamp, // Add timestamp if your table supports it
        // 'updated_at' => $timestamp
      ];
    }

    return $batchData;
  }

  private function getTeacherData($teacherCode)
  {
    $this->db->where('teacher_code', $teacherCode); // Adjust field name as needed
    $this->db->select('board_name, session_start, session_end, series_classes');
    $query = $this->db->get('web_user'); // Adjust table name as needed

    return $query->row_array();
  }

  private function getExistingEmails($emails)
  {
    if (empty($emails)) {
      return [];
    }

    $this->db->select('email');
    $this->db->where_in('email', array_map('strtolower', array_map('trim', $emails)));
    $query = $this->db->get('web_user');

    return array_column($query->result_array(), 'email');
  }

  private function prepareBookData($teacherData, $studentClass)
  {
    $series_classes = unserialize($teacherData['series_classes']);
    $books = [];
    $teacher_book = null;

    foreach ($series_classes as $key => $value) {
      $this->db->where('sid', $key);
      $this->db->where_in('class', $studentClass);
      $this->db->select('name');
      $subjects = $this->db->get('subject')->result();

      foreach ($subjects as $subject) {
        $books[] = $subject->name;
      }

      // Select 1 subject for student based on class
      if (in_array($studentClass, $value)) {
        $teacher_book = $key;
      }
    }

    return [
      'book_names' => implode(',', $books),
      'teacher_book' => $teacher_book
    ];
  }

  private function generateDefaultPassword()
  {
    // Generate a secure default password or return a default value
    return bin2hex(random_bytes(4)); // 8 character random password
  }
}
