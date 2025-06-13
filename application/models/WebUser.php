<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WebUser extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  /**
   * Get users with filtering, sorting, searching and pagination
   */
  public function get_users($params = array())
  {
    // Base query
    $this->db->select('*');
    $this->db->from('web_user');

    // Apply filters
    $this->_apply_filters($params);

    // Apply search
    if (!empty($params['search'])) {
      $this->_apply_search($params['search']);
    }

    // Apply sorting
    if (!empty($params['sort_by']) && !empty($params['sort_order'])) {
      $this->db->order_by($params['sort_by'], $params['sort_order']);
    } else {
      $this->db->order_by('dated', 'DESC'); // Default sorting
    }

    // Get total count before pagination
    $total_query = $this->db->get_compiled_select();
    $total_count = $this->db->query($total_query)->num_rows();

    // Reset query for pagination
    $this->db->select('*');
    $this->db->from('web_user');
    $this->_apply_filters($params);

    if (!empty($params['search'])) {
      $this->_apply_search($params['search']);
    }

    if (!empty($params['sort_by']) && !empty($params['sort_order'])) {
      $this->db->order_by($params['sort_by'], $params['sort_order']);
    } else {
      $this->db->order_by('dated', 'DESC');
    }

    // Apply pagination
    if (isset($params['limit']) && isset($params['offset'])) {
      $this->db->limit($params['limit'], $params['offset']);
    }

    $query = $this->db->get();
    $data = $query->result_array();

    return array(
      'data' => $data,
      'total' => $total_count,
      'filtered' => count($data)
    );
  }

  /**
   * Apply filters to query
   */
  private function _apply_filters($params)
  {
    // Status filter
    if (isset($params['status']) && $params['status'] !== '') {
      $this->db->where('status', $params['status']);
    }

    // Active filter
    if (isset($params['active']) && $params['active'] !== '') {
      $this->db->where('active', $params['active']);
    }

    // User type filter
    if (!empty($params['user_type'])) {
      $this->db->where('user_type', $params['user_type']);
    }

    // Country type filter
    if (!empty($params['country_type'])) {
      $this->db->where('country_type', $params['country_type']);
    }

    // Board name filter
    if (!empty($params['board_name'])) {
      $this->db->where('board_name', $params['board_name']);
    }

    // Class filter
    if (!empty($params['classes'])) {
      $this->db->like('classes', $params['classes']);
    }

    // Subject filter
    if (!empty($params['subject'])) {
      $this->db->like('subject', $params['subject']);
    }

    // Date range filters
    if (!empty($params['date_from'])) {
      $this->db->where('DATE(dated) >=', $params['date_from']);
    }

    if (!empty($params['date_to'])) {
      $this->db->where('DATE(dated) <=', $params['date_to']);
    }

    // Session date filters
    if (!empty($params['session_start_from'])) {
      $this->db->where('session_start >=', $params['session_start_from']);
    }

    if (!empty($params['session_start_to'])) {
      $this->db->where('session_start <=', $params['session_start_to']);
    }

    if (!empty($params['session_end_from'])) {
      $this->db->where('session_end >=', $params['session_end_from']);
    }

    if (!empty($params['session_end_to'])) {
      $this->db->where('session_end <=', $params['session_end_to']);
    }
  }

  /**
   * Apply search to multiple fields
   */
  private function _apply_search($search)
  {
    $this->db->group_start();
    $this->db->like('fullname', $search);
    $this->db->or_like('email', $search);
    $this->db->or_like('mobile', $search);
    $this->db->or_like('stu_teacher_id', $search);
    $this->db->or_like('teacher_code', $search);
    $this->db->or_like('school_name', $search);
    $this->db->or_like('city', $search);
    $this->db->or_like('oth_city', $search);
    $this->db->or_like('country', $search);
    $this->db->or_like('board_name', $search);
    $this->db->or_like('principal_name', $search);
    $this->db->or_like('referrel_name', $search);
    $this->db->group_end();
  }

  /**
   * Get user by ID
   */
  public function get_user_by_id($id)
  {
    $this->db->where('id', $id);
    $query = $this->db->get('web_user');
    return $query->row_array();
  }

  /**
   * Create new user
   */
  public function create_user($data)
  {
    $data['dated'] = date('Y-m-d H:i:s');
    return $this->db->insert('web_user', $data);
  }

  /**
   * Update user
   */
  public function update_user($id, $data)
  {
    $this->db->where('id', $id);
    return $this->db->update('web_user', $data);
  }

  /**
   * Delete user (soft delete by setting status to 0)
   */
  public function delete_user($id)
  {
    $this->db->where('id', $id);
    return $this->db->update('web_user', array('status' => 0));
  }

  /**
   * Get filter options for dropdowns
   */
  public function get_filter_options()
  {
    $columns = ['user_type', 'country_type', 'board_name', 'country'];
    $options = [];

    foreach ($columns as $column) {
      $this->db->distinct();
      $this->db->select($column);
      $this->db->from('web_user');
      $this->db->where("$column IS NOT NULL");
      $this->db->where("$column !=", '');
      $query = $this->db->get();
      $options[$column . 's'] = array_column($query->result_array(), $column);
    }

    return $options;
  }


  /**
   * Get user statistics
   */
  public function get_user_stats()
  {
    $stats = array();

    // Total users
    $this->db->from('web_user');
    $stats['total_users'] = $this->db->count_all_results();

    // Active users
    $this->db->from('web_user');
    $this->db->where('active', 1);
    $stats['active_users'] = $this->db->count_all_results();

    // Users by status
    $this->db->select('status, COUNT(*) as count');
    $this->db->from('web_user');
    $this->db->group_by('status');
    $query = $this->db->get();
    $stats['status_breakdown'] = $query->result_array();

    // Users by user type
    $this->db->select('user_type, COUNT(*) as count');
    $this->db->from('web_user');
    $this->db->where('user_type IS NOT NULL');
    $this->db->group_by('user_type');
    $query = $this->db->get();
    $stats['user_type_breakdown'] = $query->result_array();

    return $stats;
  }
}
