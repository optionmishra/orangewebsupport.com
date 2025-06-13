<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Web_user extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('WebUser');
    $this->load->library('form_validation');

    // Set JSON header for all responses
    header('Content-Type: application/json');

    // Enable CORS if needed
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    // Handle preflight requests
    if ($this->input->method() === 'options') {
      exit();
    }
  }

  /**
   * Get all users with filtering, sorting, searching and pagination
   * GET /api/web_users
   */
  public function index()
  {
    try {
      // Get query parameters
      $params = array(
        // Pagination
        'limit' => $this->input->get('limit') ?: 10,
        'offset' => $this->input->get('offset') ?: 0,
        'page' => $this->input->get('page') ?: 1,

        // Sorting
        'sort_by' => $this->input->get('sort_by'),
        'sort_order' => $this->input->get('sort_order') ?: 'ASC',

        // Search
        'search' => $this->input->get('search'),

        // Filters
        'status' => $this->input->get('status'),
        'active' => $this->input->get('active'),
        'user_type' => $this->input->get('user_type'),
        'country_type' => $this->input->get('country_type'),
        'board_name' => $this->input->get('board_name'),
        'classes' => $this->input->get('classes'),
        'subject' => $this->input->get('subject'),

        // Date filters
        'date_from' => $this->input->get('date_from'),
        'date_to' => $this->input->get('date_to'),
        'session_start_from' => $this->input->get('session_start_from'),
        'session_start_to' => $this->input->get('session_start_to'),
        'session_end_from' => $this->input->get('session_end_from'),
        'session_end_to' => $this->input->get('session_end_to'),
      );

      // Calculate offset from page if provided
      if ($params['page'] > 1) {
        $params['offset'] = ($params['page'] - 1) * $params['limit'];
      }

      // Validate sort column
      $allowed_sort_columns = array(
        'id',
        'user_type',
        'stu_teacher_id',
        'teacher_code',
        'session_start',
        'session_end',
        'fullname',
        'email',
        'mobile',
        'city',
        'state',
        'country',
        'school_name',
        'board_name',
        'status',
        'active',
        'dated'
      );

      if ($params['sort_by'] && !in_array($params['sort_by'], $allowed_sort_columns)) {
        $this->_send_response(false, 'Invalid sort column', null, 400);
        return;
      }

      // Validate sort order
      if (!in_array(strtoupper($params['sort_order']), array('ASC', 'DESC'))) {
        $params['sort_order'] = 'ASC';
      }

      // Get data from model
      $result = $this->WebUser->get_users($params);

      // Prepare response
      $response = array(
        'data' => $result['data'],
        'pagination' => array(
          'total' => $result['total'],
          'page' => (int)$params['page'],
          'limit' => (int)$params['limit'],
          'offset' => (int)$params['offset'],
          'pages' => ceil($result['total'] / $params['limit'])
        ),
        'filters_applied' => $this->_get_applied_filters($params)
      );

      $this->_send_response(true, 'Users retrieved successfully', $response);
    } catch (Exception $e) {
      $this->_send_response(false, 'Error retrieving users: ' . $e->getMessage(), null, 500);
    }
  }

  /**
   * Get single user by ID
   * GET /api/web_users/{id}
   */
  public function show($id)
  {
    try {
      if (!is_numeric($id)) {
        $this->_send_response(false, 'Invalid user ID', null, 400);
        return;
      }

      $user = $this->WebUser->get_user_by_id($id);

      if ($user) {
        $this->_send_response(true, 'User retrieved successfully', $user);
      } else {
        $this->_send_response(false, 'User not found', null, 404);
      }
    } catch (Exception $e) {
      $this->_send_response(false, 'Error retrieving user: ' . $e->getMessage(), null, 500);
    }
  }

  /**
   * Create new user
   * POST /api/web_users
   */
  public function create()
  {
    try {
      $input = json_decode($this->input->raw_input_stream, true);

      if (!$input) {
        $this->_send_response(false, 'Invalid JSON data', null, 400);
        return;
      }

      // Validation rules
      $this->form_validation->set_data($input);
      $this->form_validation->set_rules('stu_teacher_id', 'Student/Teacher ID', 'required|max_length[255]');
      $this->form_validation->set_rules('teacher_code', 'Teacher Code', 'required|max_length[255]');
      $this->form_validation->set_rules('session_start', 'Session Start', 'required|valid_date');
      $this->form_validation->set_rules('session_end', 'Session End', 'required|valid_date');
      $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[60]');
      $this->form_validation->set_rules('fullname', 'Full Name', 'max_length[128]');
      $this->form_validation->set_rules('mobile', 'Mobile', 'max_length[20]');

      if ($this->form_validation->run() === FALSE) {
        $this->_send_response(false, 'Validation failed', validation_errors(), 400);
        return;
      }

      // Set default values
      if (!isset($input['status'])) $input['status'] = 1;
      if (!isset($input['active'])) $input['active'] = 1;
      if (!isset($input['dp'])) $input['dp'] = '3.png';
      if (!isset($input['classes'])) $input['classes'] = '3';
      if (!isset($input['subject'])) $input['subject'] = '3';

      $result = $this->WebUser->create_user($input);

      if ($result) {
        $user_id = $this->db->insert_id();
        $new_user = $this->WebUser->get_user_by_id($user_id);
        $this->_send_response(true, 'User created successfully', $new_user, 201);
      } else {
        $this->_send_response(false, 'Failed to create user', null, 500);
      }
    } catch (Exception $e) {
      $this->_send_response(false, 'Error creating user: ' . $e->getMessage(), null, 500);
    }
  }

  /**
   * Update user
   * PUT /api/web_users/{id}
   */
  public function update($id)
  {
    try {
      if (!is_numeric($id)) {
        $this->_send_response(false, 'Invalid user ID', null, 400);
        return;
      }

      // Check if user exists
      $existing_user = $this->WebUser->get_user_by_id($id);
      if (!$existing_user) {
        $this->_send_response(false, 'User not found', null, 404);
        return;
      }

      $input = json_decode($this->input->raw_input_stream, true);

      if (!$input) {
        $this->_send_response(false, 'Invalid JSON data', null, 400);
        return;
      }

      // Remove id from input to prevent updating primary key
      unset($input['id']);
      unset($input['dated']); // Don't allow updating creation date

      $result = $this->WebUser->update_user($id, $input);

      if ($result) {
        $updated_user = $this->WebUser->get_user_by_id($id);
        $this->_send_response(true, 'User updated successfully', $updated_user);
      } else {
        $this->_send_response(false, 'Failed to update user', null, 500);
      }
    } catch (Exception $e) {
      $this->_send_response(false, 'Error updating user: ' . $e->getMessage(), null, 500);
    }
  }

  /**
   * Delete user (soft delete)
   * DELETE /api/web_users/{id}
   */
  public function delete($id)
  {
    try {
      if (!is_numeric($id)) {
        $this->_send_response(false, 'Invalid user ID', null, 400);
        return;
      }

      // Check if user exists
      $existing_user = $this->WebUser->get_user_by_id($id);
      if (!$existing_user) {
        $this->_send_response(false, 'User not found', null, 404);
        return;
      }

      $result = $this->WebUser->delete_user($id);

      if ($result) {
        $this->_send_response(true, 'User deleted successfully');
      } else {
        $this->_send_response(false, 'Failed to delete user', null, 500);
      }
    } catch (Exception $e) {
      $this->_send_response(false, 'Error deleting user: ' . $e->getMessage(), null, 500);
    }
  }

  /**
   * Get filter options for dropdowns
   * GET /api/web_users/filter_options
   */
  public function filter_options()
  {
    try {
      $options = $this->WebUser->get_filter_options();
      $this->_send_response(true, 'Filter options retrieved successfully', $options);
    } catch (Exception $e) {
      $this->_send_response(false, 'Error retrieving filter options: ' . $e->getMessage(), null, 500);
    }
  }

  /**
   * Get user statistics
   * GET /api/web_users/stats
   */
  public function stats()
  {
    try {
      $stats = $this->WebUser->get_user_stats();
      $this->_send_response(true, 'Statistics retrieved successfully', $stats);
    } catch (Exception $e) {
      $this->_send_response(false, 'Error retrieving statistics: ' . $e->getMessage(), null, 500);
    }
  }

  /**
   * Bulk operations
   * POST /api/web_users/bulk
   */
  public function bulk()
  {
    try {
      $input = json_decode($this->input->raw_input_stream, true);

      if (!$input || !isset($input['action']) || !isset($input['ids'])) {
        $this->_send_response(false, 'Invalid request data. Required: action, ids', null, 400);
        return;
      }

      $action = $input['action'];
      $ids = $input['ids'];
      $data = isset($input['data']) ? $input['data'] : array();

      if (!is_array($ids) || empty($ids)) {
        $this->_send_response(false, 'IDs must be a non-empty array', null, 400);
        return;
      }

      $success_count = 0;
      $errors = array();

      foreach ($ids as $id) {
        if (!is_numeric($id)) {
          $errors[] = "Invalid ID: $id";
          continue;
        }

        switch ($action) {
          case 'delete':
            $result = $this->WebUser->delete_user($id);
            break;
          case 'activate':
            $result = $this->WebUser->update_user($id, array('active' => 1));
            break;
          case 'deactivate':
            $result = $this->WebUser->update_user($id, array('active' => 0));
            break;
          case 'update':
            if (empty($data)) {
              $errors[] = "No data provided for update operation";
              continue 2;
            }
            $result = $this->WebUser->update_user($id, $data);
            break;
          default:
            $errors[] = "Invalid action: $action";
            continue 2;
        }

        if ($result) {
          $success_count++;
        } else {
          $errors[] = "Failed to $action user ID: $id";
        }
      }

      $response = array(
        'success_count' => $success_count,
        'total_count' => count($ids),
        'errors' => $errors
      );

      if ($success_count > 0) {
        $this->_send_response(true, "Bulk operation completed. $success_count out of " . count($ids) . " operations successful.", $response);
      } else {
        $this->_send_response(false, 'All bulk operations failed', $response, 400);
      }
    } catch (Exception $e) {
      $this->_send_response(false, 'Error in bulk operation: ' . $e->getMessage(), null, 500);
    }
  }

  /**
   * Export users data
   * GET /api/web_users/export
   */
  public function export()
  {
    try {
      $format = $this->input->get('format') ?: 'json';
      $params = array(
        'status' => $this->input->get('status'),
        'active' => $this->input->get('active'),
        'user_type' => $this->input->get('user_type'),
        'date_from' => $this->input->get('date_from'),
        'date_to' => $this->input->get('date_to'),
      );

      // Remove pagination for export
      $result = $this->WebUser->get_users($params);

      switch (strtolower($format)) {
        case 'csv':
          $this->_export_csv($result['data']);
          break;
        case 'json':
        default:
          $this->_send_response(true, 'Data exported successfully', $result['data']);
          break;
      }
    } catch (Exception $e) {
      $this->_send_response(false, 'Error exporting data: ' . $e->getMessage(), null, 500);
    }
  }

  /**
   * Send JSON response
   */
  private function _send_response($success, $message, $data = null, $http_code = 200)
  {
    $response = array(
      'success' => $success,
      'message' => $message,
      'timestamp' => date('Y-m-d H:i:s')
    );

    if ($data !== null) {
      $response['data'] = $data;
    }

    http_response_code($http_code);
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit();
  }

  /**
   * Get applied filters for response
   */
  private function _get_applied_filters($params)
  {
    $applied = array();

    $filter_keys = array(
      'status',
      'active',
      'user_type',
      'country_type',
      'board_name',
      'classes',
      'subject',
      'date_from',
      'date_to',
      'session_start_from',
      'session_start_to',
      'session_end_from',
      'session_end_to'
    );

    foreach ($filter_keys as $key) {
      if (!empty($params[$key])) {
        $applied[$key] = $params[$key];
      }
    }

    if (!empty($params['search'])) {
      $applied['search'] = $params['search'];
    }

    return $applied;
  }

  /**
   * Export data as CSV
   */
  private function _export_csv($data)
  {
    if (empty($data)) {
      $this->_send_response(false, 'No data to export', null, 400);
      return;
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="web_users_' . date('Y-m-d_H-i-s') . '.csv"');

    $output = fopen('php://output', 'w');

    // Write header
    fputcsv($output, array_keys($data[0]));

    // Write data
    foreach ($data as $row) {
      fputcsv($output, $row);
    }

    fclose($output);
    exit();
  }
}
