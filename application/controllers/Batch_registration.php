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
        'forceReg' => $this->input->post('forceReg'),
      ],
      'studentsData' => $previewData,
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
      if (in_array($student_class, $value)) {
        $teacher_book = $key;
      }
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
    $inputFileName = $filePath;
    $inputFileType = 'CSV';
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
    $objWorksheet = $objPHPExcel->getActiveSheet();

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

    // Get forceReg parameter (defaults to false)
    $forceReg = $this->input->post('forceReg') === 'true' || $this->input->post('forceReg') === true;

    $destination_path = $this->upload_directory . $inputData['fileName'];

    // Validate file exists
    if (!file_exists($destination_path)) {
      throw new RuntimeException("CSV file not found: {$destination_path}");
    }

    $studentsData = $this->csvToArray($destination_path);

    if (empty($studentsData)) {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(['success' => false, 'message' => 'No valid student data found in CSV']));
    }

    // Get teacher data once
    $teacherData = $this->getTeacherData($inputData['teacherCode']);
    if (!$teacherData) {
      throw new RuntimeException("Invalid teacher code: {$inputData['teacherCode']}");
    }

    // Use database transaction for data integrity
    $this->db->trans_start();

    try {
      $result = $this->processStudentData($studentsData, $inputData, $teacherData, $forceReg);

      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE) {
        throw new RuntimeException('Transaction failed');
      }

      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($result));
    } catch (Exception $e) {
      $this->db->trans_rollback();
      throw $e;
    }
  }

  private function processStudentData($studentsData, $inputData, $teacherData, $forceReg)
  {
    // Get existing emails and their IDs
    $emails = array_column($studentsData, 'EMAIL');
    $existingUsers = $this->getExistingUsers($emails);

    // Prepare book data once
    $bookData = $this->prepareBookData($teacherData, $inputData['standard']);

    $insertData = [];
    $updateData = [];
    $timestamp = date('Y-m-d H:i:s');

    foreach ($studentsData as $student) {
      // Validate required student fields
      if (empty($student['EMAIL']) || empty($student['NAME'])) {
        continue; // Skip invalid records
      }

      $email = strtolower(trim($student['EMAIL']));
      $studentRecord = [
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
        'email' => $email,
        'pin' => '123456',
        'address' => '',
        'stu_teacher_id' => $inputData['teacherCode'],
        'city' => '',
        'state' => '',
        'class_section' => $inputData['section'],
        'school_name' => $inputData['school'],
        'password' => $student['PASSWORD'] ?? $this->generateDefaultPassword(),
      ];

      // Check if email exists
      if (isset($existingUsers[$email])) {
        if ($forceReg) {
          // Add to update batch
          $updateData[] = [
            'id' => $existingUsers[$email]['id'],
            'data' => $studentRecord
          ];
        }
        // If forceReg is false, skip existing emails (do nothing)
      } else {
        // Add to insert batch
        $insertData[] = $studentRecord;
      }
    }

    $insertedCount = 0;
    $updatedCount = 0;

    // Process insertions
    if (!empty($insertData)) {
      $result = $this->db->insert_batch('web_user', $insertData);
      if ($result) {
        $insertedCount = count($insertData);
      }
    }

    // Process updates
    if (!empty($updateData)) {
      foreach ($updateData as $update) {
        $this->db->where('id', $update['id']);
        $result = $this->db->update('web_user', $update['data']);
        if ($result) {
          $updatedCount++;
        }
      }
    }

    // Prepare response message
    $message = [];
    if ($insertedCount > 0) {
      $message[] = "{$insertedCount} students successfully added";
    }
    if ($updatedCount > 0) {
      $message[] = "{$updatedCount} students successfully updated";
    }
    if (empty($message)) {
      $message[] = "No students processed";
    }

    return [
      'success' => ($insertedCount > 0 || $updatedCount > 0),
      'message' => implode(', ', $message),
      'inserted_count' => $insertedCount,
      'updated_count' => $updatedCount,
      'total_processed' => $insertedCount + $updatedCount
    ];
  }

  private function getTeacherData($teacherCode)
  {
    $this->db->where('teacher_code', $teacherCode);
    $this->db->select('board_name, session_start, session_end, series_classes');
    $query = $this->db->get('web_user');

    return $query->row_array();
  }

  private function getExistingUsers($emails)
  {
    if (empty($emails)) {
      return [];
    }

    $this->db->select('id, email');
    $this->db->where_in('email', array_map('strtolower', array_map('trim', $emails)));
    $query = $this->db->get('web_user');

    $existingUsers = [];
    foreach ($query->result_array() as $user) {
      $existingUsers[$user['email']] = ['id' => $user['id']];
    }

    return $existingUsers;
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
    return bin2hex(random_bytes(4));
  }
}
