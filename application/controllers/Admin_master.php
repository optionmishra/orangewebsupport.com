<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Admin_master extends CI_Controller
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
		if ($_SERVER['HTTP_HOST'] != 'localhost')
			$this->orange_epoch = $this->load->database('orange_epoch', TRUE);
	}

	// classes start
	function message($type, $msg, $data = null)
	{
		$this->data['type'] = $type;
		$this->data['message'] = $msg;
		$this->data['data'] = $data;
		print_json($this->data);
	}

	function retrieve($table, $condCol = null, $id = null)
	{
		$res = $this->AuthModel->retrieveRecord($table, $condCol, $id);
		if (!$res) {
			$this->message('error', '', $this->AuthModel->error);
		} else {
			$this->message('success', '', $res);
		}
	}

	private function upload_file($file_name, $path, $field_name)
	{
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png|jpeg|mp4|3gp|flv|svg|webp|wmv|ico|xlsx|doc|docx|pdf|zip|rar|ini|csv';
		$config['max_size'] = 0;
		$config['file_name'] = $file_name;

		$this->upload->initialize($config);

		if ($this->upload->do_upload($field_name)) {
			return TRUE;
		} else {
			$this->upload_error = $this->upload->display_errors();
			return FALSE;
		}
	}

	// User start 
	function users()
	{
		$res = $this->AuthModel->user();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->action = "<a user_id='" . $value->id . "' class='pr-2 pointer edit-user' href='superadmin/user_update/" . $value->id . "'><i class='fa fa-edit'></i></a>"
				. "<a user_id='" . $value->id . "' class='pointer delete_user'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function add_user()
	{
		if ($this->permission->is_allow('New User')) {
			$ext = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);
			$photo = pathinfo($_FILES['profile_img']['name'], PATHINFO_FILENAME);
			$photogg = time() . $photo . "." . strtolower($ext);
			$photog = str_replace(' ', '_', $photogg);
			$path = 'assets/img/';

			if ($this->upload_file($photog, $path, 'profile_img')) {
				//            $this->message('success', 'file Uploaded');
			} else {
				$this->message('error', $this->upload_error);
			}
			$permissions = implode(',', $this->input->post('permissions'));
			$data = [
				'name' => $this->input->post('name'),
				'mobile' => $this->input->post('mobile'),
				'email' => $this->input->post('email'),
				'level' => $this->input->post('level'),
				'username' => $this->input->post('email'),
				'password' => md5($this->input->post('mobile')),
				'permissions' => $permissions,
				'profile_img' => $photog
			];
			$data = [
				'user' => $this->input->post('email'),
				'password' => $this->input->post('mobile'),
				'fullname' => $this->input->post('name')
			];
			$config = array(
				'charset' => 'utf-8',
				'wordwrap' => TRUE,
				'mailtype' => 'html'
			);

			$this->email->initialize($config);
			$this->email->to($this->input->post('email'));
			$this->email->from('info@touchpadwebsupport.com', 'Orange - Touchpad');
			$this->email->cc('mayank@epochstudio.net, editorial@orangeeducation.in, info@orangeeducation.in');
			$this->email->subject('Your Credentials for Orange - Touchpad Web Support');
			$this->email->message($this->load->view('web/email_template1', $data, true));
			$this->email->send();
			$res = $this->AuthModel->create_user($data);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' User Create Successfully....');
		} else {
			$this->message('error', 'Permission Denied.......');
		}
	}

	function salesman()
	{
		$res = $this->AuthModel->salesman();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->action = "<a salesman_id='" . $value->id . "' class='pr-2 pointer edit-salesman' href='#" . $value->id . "'><i class='fa fa-edit'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}


	function add_salesman()
	{
		if ($this->permission->is_allow('New User')) {

			$data = [
				'name' => $this->input->post('name'),
				'contact' => $this->input->post('mobile'),
				'email' => $this->input->post('email'),
				'address' => $this->input->post('address')
			];

			$config = array(
				'charset' => 'utf-8',
				'wordwrap' => TRUE,
				'mailtype' => 'html'
			);

			$this->email->initialize($config);
			$this->email->to($this->input->post('email'));
			$this->email->from('info@touchpadwebsupport.com', 'Orange - Touchpad');
			$this->email->cc('mayank@epochstudio.net, editorial@orangeeducation.in, info@orangeeducation.in');
			$this->email->subject('Your Credentials for Orange - Touchpad Web Support');
			$this->email->message($this->load->view('web/email_template1', $data, true));
			$this->email->send();
			$res = $this->AuthModel->create_salesman($data);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Salesman Create Successfully....');
		} else {
			$this->message('error', 'Permission Denied.......');
		}
	}

	function add_contact()
	{
		$message = $this->input->post('message') . ' (Mobile Number: ' . $this->input->post('mobile') . ')';
		$this->email->to('info@touchpadwebsupport.com');
		$this->email->from($this->input->post('email'), $this->input->post('name'));
		$this->email->cc('mayank@epochstudio.net, editorial@orangeeducation.in, info@orangeeducation.in');
		$this->email->subject('New Contact Message from Orange - Touchpad Web Support');
		$this->email->message($message);
		$res = $this->email->send();
		if (!$res) {
			$this->message('error', $this->error);
		}
		$this->message('success', 'Message Send Successfully....');
	}

	function update_user()
	{
		if ($this->permission->is_allow('Update User')) {
			$id = $this->input->post('id');
			$permissions = implode(',', $this->input->post('permissions'));
			$details = [
				'name' => $this->input->post('getname'),
				'mobile' => $this->input->post('getmobile'),
				'email' => $this->input->post('getemail'),
				'level' => $this->input->post('getlevel'),
				'permissions' => $permissions
			];
			$res = $this->AuthModel->update_user($details, $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' User Update Successfully....');
		} else {
			$this->message('error', 'Permission Denied.......');
		}
	}


	function update_webteacher()
	{
		if ($this->permission->is_allow('Update User')) {
			$id = $this->input->post('id');
			// $class = implode(',', $this->input->post('class'));
			// $subject = implode(',', $this->input->post('subject'));

			// series - classes
			$sub_arr = $this->input->post('series');
			if (empty($sub_arr)) $this->message('error', 'Please Add at least one Series');
			$series_classes =  array();
			$classes = array();
			foreach ($sub_arr as $sub) {
				$series_classes[$sub] = $this->input->post($sub . 'Classes');
				$classes = array_merge($classes, $this->input->post($sub . 'Classes'));
			}
			// book_name
			$books = array();
			foreach ($series_classes as $key => $value) {
				$this->db->where('sid', $key);
				$this->db->where_in('class', $value);
				$this->db->select('name');
				$subjects = $this->db->get('subject')->result();
				foreach ($subjects as $subject) {
					$books[] = $subject->name;
				}
			}
			$book_name = implode(',', $books);
			// 
			$serialized_series_classes = serialize($series_classes);
			$classes = array_unique($classes);
			$subject = implode(',', $this->input->post('series'));

			$classes = implode(',', $classes);
			$details = [
				'fullname' => $this->input->post('name'),
				'mobile' => $this->input->post('mobile'),
				'email' => $this->input->post('email'),
				'pin' => $this->input->post('pin'),
				'board_name' => $this->input->post('board'),
				'classes' => $classes,
				'subject' => $subject,
				'series_classes' => $serialized_series_classes,
				'book_name' => $book_name,
			];
			$res = $this->AuthModel->update_webu($details, $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' User Update Successfully....');
		} else {
			$this->message('error', 'Permission Denied.......');
		}
	}

	function update_salesman()
	{
		if ($this->permission->is_allow('Update User')) {
			$id = $this->input->post('id');
			$permissions = implode(',', $this->input->post('permissions'));
			$details = [
				'name' => $this->input->post('getname'),
				'contact' => $this->input->post('getmobile'),
				'email' => $this->input->post('getemail'),
				'address' => $this->input->post('getaddress'),
				'permissions' => $permissions
			];
			$res = $this->AuthModel->update_user($details, $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' User Update Successfully....');
		} else {
			$this->message('error', 'Permission Denied.......');
		}
	}

	function delete_user()
	{
		if ($this->permission->is_allow('Delete User')) {
			$id = $this->input->post('id');
			$res = $this->AuthModel->delete_record('user', 'id', $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', 'User Deleted Successfully....');
		} else {
			$this->message('error', 'Permission Denied.......');
		}
	}

	function delete_salesman()
	{
		if ($this->permission->is_allow('Delete User')) {
			$id = $this->input->post('id');
			$res = $this->AuthModel->delete_record('salesman', 'id', $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', 'User Deleted Successfully....');
		} else {
			$this->message('error', 'Permission Denied.......');
		}
	}

	function editSchoolLogo()
	{
		$ext = pathinfo($_FILES['logo_img']['name'], PATHINFO_EXTENSION);
		$photo = pathinfo($_FILES['logo_img']['name'], PATHINFO_FILENAME);
		$photogg = time() . $photo . "." . strtolower($ext);
		$photog = str_replace(' ', '_', $photogg);
		$path = 'assets/img/';

		if ($this->upload_file($photog, $path, 'logo_img')) {
			//            $this->message('success', 'file Uploaded');
		} else {
			$this->message('error', $this->upload_error);
		}
		$username = $this->session->userdata('ausername');
		$data = [
			'profile_img' => $photog
		];
		$res = $this->AuthModel->update_school_logo($data, $username);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', 'Profile Image Successfully Updated....');
	}

	function editProfileLogo()
	{
		$ext = pathinfo($_FILES['logo_img']['name'], PATHINFO_EXTENSION);
		$photo = pathinfo($_FILES['logo_img']['name'], PATHINFO_FILENAME);
		$photogg = time() . $photo . "." . strtolower($ext);
		$photog = str_replace(' ', '_', $photogg);
		$path = 'assets/img/';

		if ($this->upload_file($photog, $path, 'logo_img')) {
			//            $this->message('success', 'file Uploaded');
		} else {
			$this->message('error', $this->upload_error);
		}
		$username = $this->session->userdata('username');
		$data = [
			'dp' => $photog
		];
		$res = $this->AuthModel->update_profile_logo($data, $username);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', 'Profile Image Successfully Updated....');
	}

	function update_profile()
	{
		$username = $this->session->userdata('ausername');
		$data = [
			'name' => $this->input->post('name'),
			'mobile' => $this->input->post('mobile'),
			'email' => $this->input->post('email'),
			'secret_word' => $this->input->post('secret_word')
		];
		$res = $this->AuthModel->update_user_profile($data, $username);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', $this->input->post('name') . ' Your Profile Updated Successfully....');
	}

	function wupdate_profile()
	{
		$username = $this->session->userdata('username');
		$data = [
			'fullname' => $this->input->post('name'),
			'mobile' => $this->input->post('mobile'),
			'pin' => $this->input->post('pin'),
			'address' => $this->input->post('address'),
			'city' => $this->input->post('city'),
			'dob' => $this->input->post('dob'),
			'addresss' => $this->input->post('addresss')
		];
		$res = $this->AuthModel->update_web_profile($data, $username);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', $this->input->post('name') . ' Your Profile Updated Successfully....');
	}

	function update_profile_account()
	{
		$username = $this->session->userdata('ausername');
		$data = [
			'username' => $this->input->post('username'),
			'password' => md5($this->input->post('password'))
		];
		$res = $this->AuthModel->update_profile_account($data, $username);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', $this->input->post('username') . ' Your Profile Updated Successfully....');
	}

	function wupdate_profile_account()
	{
		$username = $this->session->userdata('username');
		$data = [
			'email' => $this->input->post('email'),
			'password' => $this->input->post('password')
		];
		$res = $this->AuthModel->update_web_account($data, $username);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', $this->input->post('username') . ' Your Profile Updated Successfully....');
	}

	// End User
	// User start 
	function conts()
	{
		$res = $this->AuthModel->cont();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->action = "<a cont_id='" . $value->id . "' class='pr-2 pointer edit-cont' data-toggle='modal' data-target='#edit-cont'><i class='fa fa-edit'></i></a>"
				. "<a cont_id='" . $value->id . "' class='pointer delete_cont'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function add_cont()
	{
		if ($this->permission->is_allow('New Content')) {
			$data = [
				'name' => $this->input->post('name'),
			];
			$res = $this->AuthModel->create_con($data);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Content Create Successfully....');
		} else {
			$this->message('error', 'Permission Denied.......');
		}
	}

	function update_cont()
	{
		if ($this->permission->is_allow('Update Content')) {
			$id = $this->input->post('id');
			if ($_FILES['file_name']['size'] == 0) {
				$details = [
					'name' => $this->input->post('name'),
					'value' => $this->input->post('value')
				];
			} else {
				$ext = pathinfo($_FILES['file_name']['name'], PATHINFO_EXTENSION);
				$photo = pathinfo($_FILES['file_name']['name'], PATHINFO_FILENAME);
				$photogg = time() . $photo . "." . strtolower($ext);
				$photog = str_replace(' ', '_', $photogg);
				$path = 'assets/img/';

				if ($this->upload_file($photog, $path, 'file_name')) {
					//            $this->message('success', 'file Uploaded');
				} else {
					$this->message('error', $this->upload_error);
				}
				$details = [
					'name' => $this->input->post('name'),
					'value' => $this->input->post('value'),
					'file_name' => $photog
				];
			}
			$res = $this->AuthModel->update_con($details, $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Content Update Successfully....');
		} else {
			$this->message('error', 'Permission Denied.......');
		}
	}

	function delete_cont()
	{
		if ($this->permission->is_allow('Delete Content')) {
			$id = $this->input->post('id');
			$res = $this->AuthModel->delete_record('web_content', 'id', $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', 'Content Deleted Successfully....');
		} else {
			$this->message('error', 'Permission Denied.......');
		}
	}

	// End Content
	// Permission start 
	function permissions()
	{
		$res = $this->AuthModel->permission();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->action = "<a permission_id='" . $value->id . "' class='pr-2 pointer edit-permission' data-toggle='modal' data-target='#edit-permission'><i class='fa fa-edit'></i></a>"
				. "<a permission_id='" . $value->id . "' class='pointer delete_permission'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function add_permission()
	{
		$data = [
			'name' => $this->input->post('name'),
		];
		$res = $this->AuthModel->create_permission($data);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', $this->input->post('name') . ' Permission Create Successfully....');
	}

	function update_permission()
	{
		$id = $this->input->post('id');
		$details = [
			'name' => $this->input->post('name')
		];
		$res = $this->AuthModel->update_permission($details, $id);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', $this->input->post('name') . ' Permission Update Successfully....');
	}

	function delete_permission()
	{
		$id = $this->input->post('id');
		$res = $this->AuthModel->delete_record('permission', 'id', $id);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', 'Permission Deleted Successfully....');
	}

	// End Permission
	// State start 
	function states()
	{
		$res = $this->AuthModel->stated();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->action = "<a state_id='" . $value->StateID . "' class='pr-2 pointer edit-state' data-toggle='modal' data-target='#edit-state'><i class='fa fa-edit'></i></a>"
				. "<a state_id='" . $value->StateID . "' class='pointer delete_state'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function add_state()
	{
		$data = [
			'StateName' => $this->input->post('name'),
			'zone' => $this->input->post('zone')
		];
		$res = $this->AuthModel->create_state($data);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', $this->input->post('name') . ' state Create Successfully....');
	}

	function update_state()
	{
		$id = $this->input->post('id');
		$details = [
			'StateName' => $this->input->post('name'),
			'zone' => $this->input->post('zone')
		];
		$res = $this->AuthModel->update_state($details, $id);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', $this->input->post('name') . ' state Update Successfully....');
	}

	function delete_state()
	{
		$id = $this->input->post('id');
		$res = $this->AuthModel->delete_record('state', 'StateID', $id);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', 'state Deleted Successfully....');
	}

	// End state
	// Web User start 
	function webu()
	{
		$res = $this->AuthModel->webu();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			if ($value->status == '1') {
				$active = "Active";
			} else {
				$active = "Inactive";
			}

			$value->status = $active;
			$value->action = "<a webu_id='" . $value->id . "' class='pr-2 pointer edit-webu' data-toggle='modal' data-target='#edit-webu'><i class='fa fa-edit'></i></a>"
				. "<a webu_id='" . $value->id . "' title='Block' class='pr-2 pointer status_webu'><i class='fa fa-times text-danger'></i></a>"
				. "<a webu_id='" . $value->id . "' title='Unblock' class='pr-2 pointer statuss_webu'><i class='fa fa-check text-success'></i></a>"
				. "<a webu_id='" . $value->id . "' class='pointer delete_webu'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function webu_teacher()
	{
		$res = $this->AuthModel->webu_teacher();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$sub = $this->AuthModel->get_msubject($value->subject);

			$subject = $sub['name'];
			if ($value->status == '1') {
				$active = "Active";
			} else {
				$active = "Inactive";
			}

			$value->status = $active;
			$value->subject = $subject;
			$value->action = "<a user_id='" . $value->id . "' class='pr-2 pointer edit-user' href='superadmin/teacher_update/" . $value->id . "'><i class='fa fa-edit'></i></a>"
				. "<a webu_id='" . $value->id . "' title='Block' class='pr-2 pointer status_webu'><i class='fa fa-times text-danger'></i></a>"
				. "<a webu_id='" . $value->id . "' title='Unblock' class='pr-2 pointer statuss_webu'><i class='fa fa-check text-success'></i></a>"
				. "<a webu_id='" . $value->id . "' class='pointer delete_webu'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function reject_emp()
	{
		$id = $this->input->post('id');
		$this->db->where('id', $id);
		$this->db->set('status', '0');
		$res = $this->db->update('web_user');
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', 'User Block Successfully....');
	}

	function select_emp()
	{
		$id = $this->input->post('id');
		$this->db->where('id', $id);
		$this->db->set('status', '1');
		$res = $this->db->update('web_user');
		$this->db->where('id', $id);
		$ress = $this->db->get('web_user')->result();
		$data = [
			'user' => $ress[0]->email,
			'password' => $ress[0]->password,
			'fullname' => $ress[0]->fullname
		];
		$config = array(
			'charset' => 'utf-8',
			'wordwrap' => TRUE,
			'mailtype' => 'html'
		);



		$this->email->initialize($config);
		$this->email->to($ress[0]->email);
		$this->email->from('info@touchpadwebsupport.com', 'Orange - Touchpad');
		$this->email->cc('mayank@epochstudio.net, editorial@orangeeducation.in, info@orangeeducation.in');
		$this->email->subject('Your Credentials for Orange - Touchpad Web Support');
		$this->email->message($this->load->view('web/cemail_template', $data, true));
		$this->email->send();
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', 'User UnBlock Successfully....');
	}

	function update_webu()
	{
		if ($this->permission->is_allow('Update Web Support User')) {
			$id = $this->input->post('id');
			$details = [
				'fullname' => $this->input->post('name'),
				'pin' => $this->input->post('pin'),
				'mobile' => $this->input->post('mobile'),
				'email' => $this->input->post('email'),
				'address' => $this->input->post('address'),
				'city' => $this->input->post('city')
			];
			$res = $this->AuthModel->update_webu($details, $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' User Update Successfully....');
		} else {
			$this->message('error', 'Permission Denied.......');
		}
	}

	function delete_webu()
	{
		if ($this->permission->is_allow('Delete Web Support User')) {
			$id = $this->input->post('id');
			$res = $this->AuthModel->delete_record('web_user', 'id', $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', 'Web Support User Deleted Successfully....');
		} else {
			$this->message('error', 'Permission Denied.......');
		}
	}

	// End Web Support User
	// Board start 

	// start cities
	function cities()
	{
		$res = $this->AuthModel->citiesd();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->action = "<a state_id='" . $value->StateID . "' class='pr-2 pointer edit-state' data-toggle='modal' data-target='#edit-state'><i class='fa fa-edit'></i></a>"
				. "<a state_id='" . $value->StateID . "' class='pointer delete_state'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function boards()
	{
		$res = $this->AuthModel->board();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->action = "<a board_id='" . $value->id . "' class='pr-2 pointer edit-board' data-toggle='modal' data-target='#edit-board'><i class='fa fa-edit'></i></a>"
				. "<a board_id='" . $value->id . "' class='pointer delete_board'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function add_board()
	{
		if ($this->permission->is_allow('New Board')) {
			$data = [
				'name' => $this->input->post('name'),
			];
			$res = $this->AuthModel->create_board($data);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Board Create Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	function update_board()
	{
		if ($this->permission->is_allow('Update Board')) {
			$id = $this->input->post('id');
			$details = [
				'name' => $this->input->post('name')
			];
			$res = $this->AuthModel->update_board($details, $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Board Update Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	function delete_board()
	{
		if ($this->permission->is_allow('Delete Board')) {
			$id = $this->input->post('id');
			$res = $this->AuthModel->delete_record('board', 'id', $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', 'Board Deleted Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	// End Board
	// Publication start 
	function publications()
	{
		$res = $this->AuthModel->publication();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->action = "<a publication_id='" . $value->id . "' class='pr-2 pointer edit-publication' data-toggle='modal' data-target='#edit-publication'><i class='fa fa-edit'></i></a>"
				. "<a publication_id='" . $value->id . "' class='pointer delete_publication'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function add_publication()
	{
		if ($this->permission->is_allow('New Publication')) {
			$data = [
				'name' => $this->input->post('name'),
			];
			$res = $this->AuthModel->create_publication($data);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Publication Create Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	function update_publication()
	{
		if ($this->permission->is_allow('Update Publication')) {
			$id = $this->input->post('id');
			$details = [
				'name' => $this->input->post('name')
			];
			$res = $this->AuthModel->update_publication($details, $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Publication Update Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	function delete_publication()
	{
		if ($this->permission->is_allow('Delete Publication')) {
			$id = $this->input->post('id');
			$res = $this->AuthModel->delete_record('publication', 'id', $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', 'Publication Deleted Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}



	function ret_city()
	{
		$postData = $this->input->post();
		$data = $this->AuthModel->ret_city($postData);
		//pr($data);
		//foreach()

		//echo json_encode($data);
	}

	// End Publication
	// Category start 
	function categorys()
	{
		$res = $this->AuthModel->category();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->orders = "<select category_id='" . $value->id . "' class='edit-categoryss' name='cat'><option value='" . $value->orderb . "' selected disabled>" . $value->orderb . "</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option><option value='7'>7</option><option value='8'>8</option><option value='9'>9</option><option value='10'>10</option><option value='11'>11</option><option value='12'>12</option><option value='13'>13</option><option value='14'>14</option><option value='15'>15</option></select>";
			$value->sr_no = $i;
			$value->action = "<a category_id='" . $value->id . "' class='pr-2 pointer edit-category' data-toggle='modal' data-target='#edit-category'><i class='fa fa-edit'></i></a>"
				. "<a category_id='" . $value->id . "' class='pointer delete_category'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function add_category()
	{
		if ($this->permission->is_allow('New Category')) {
			$data = [
				'name' => $this->input->post('name'),
			];
			$res = $this->AuthModel->create_category($data);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Category Create Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	function update_category()
	{
		if ($this->permission->is_allow('Update Category')) {
			$id = $this->input->post('id');
			$details = [
				'name' => $this->input->post('name')
			];
			$res = $this->AuthModel->update_category($details, $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Category Update Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	function chg()
	{
		$id = $this->input->post('id');
		$val = $this->input->post('val');
		$this->db->where('id', $id);
		$this->db->set('orderb', $val);
		$res = $this->db->update('category');
		if (!$res) {
			$this->message('error', $this->error);
		} else {
			$this->message('success', 'Category Order Successfully Changed');
		}
	}

	function delete_category()
	{
		if ($this->permission->is_allow('Delete Category')) {
			$id = $this->input->post('id');
			$res = $this->AuthModel->delete_record('category', 'id', $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', 'Category Deleted Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	// End Category

	// Summative Question Start

	function summativeQuestion()
	{
		$res = $this->AuthModel->summativeQuestion();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->class = 'Class ' . $value->class;

			$value->action = "<a summativeQuestion_id='" . $value->id . "' class='pr-2 pointer summative-QuestionEdit' data-toggle='modal' data-target='#summative-QuestionEdit'><i class='fa fa-edit'></i></a>"
				. "<a summativeQuestion_id='" . $value->id . "' class='pointer summativeQuestionDelete'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}


	function add_summativeQuestion()
	{
		if ($this->permission->is_allow('New Subject')) {

			$cur_time = date("l jS \of F Y h:i:s A");
			$data = [
				'question_type' => 1,
				'series' => $this->input->post('series'),
				'class' => $this->input->post('class'),
				'name' => $this->input->post('name'),
				'marks' => $this->input->post('marks'),
				'status' => 1,
				'created_by' => $this->session->userdata('ausername'),
				'created_dt' => $cur_time
			];

			$res = $this->AuthModel->create_summativeQuestion($data);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Subject Create Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}


	function mupdate_summativeQuestion()
	{
		if ($this->permission->is_allow('Update Subject')) {
			$id = $this->input->post('id');
			$cur_time = date("l jS \of F Y h:i:s A");
			$details = [
				'question_type' => 1,
				'series' => $this->input->post('series'),
				'class' => $this->input->post('class'),
				'name' => $this->input->post('name'),
				'marks' => $this->input->post('marks'),
				'status' => 1,
				'updated_by' => $this->session->userdata('ausername'),
				'updated_dt' => $cur_time
			];
			$res = $this->AuthModel->update_summativeQuestion($details, $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Question Update Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}


	function summativeQuestionDelete()
	{
		if ($this->permission->is_allow('Delete Subject')) {
			$id = $this->input->post('id');
			$res = $this->AuthModel->delete_record('touch_question', 'id', $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', 'Question Deleted Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	// Summative Question End


	// Objective Question Start

	function objectiveQuestion()
	{
		$res = $this->AuthModel->objectiveQuestion();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->class = 'Class ' . $value->class;
			$value->action = "<a objectiveQuestion_id='" . $value->id . "' class='pr-2 pointer objective-QuestionEdit' data-toggle='modal' data-target='#objective-QuestionEdit'><i class='fa fa-edit'></i></a>"
				. "<a objectiveQuestion_id='" . $value->id . "' class='pointer objectiveQuestionDelete'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}


	function add_objectiveQuestion()
	{
		if ($this->permission->is_allow('New Subject')) {

			$cur_time = date("l jS \of F Y h:i:s A");
			$data = [
				'question_type' => 2,
				'series' => $this->input->post('series'),
				'class' => $this->input->post('class'),
				'name' => $this->input->post('name'),
				'option_a' => $this->input->post('option_a'),
				'option_b' => $this->input->post('option_b'),
				'option_c' => $this->input->post('option_c'),
				'option_d' => $this->input->post('option_d'),
				'answer' => $this->input->post('answer'),
				'marks' => $this->input->post('marks'),
				'status' => 1,
				'created_by' => $this->session->userdata('ausername'),
				'created_dt' => $cur_time
			];

			$res = $this->AuthModel->create_objectiveQuestion($data);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Subject Create Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}


	function update_objectiveQuestion()
	{

		if ($this->permission->is_allow('Update Subject')) {
			$id = $this->input->post('id');
			$cur_time = date("l jS \of F Y h:i:s A");
			$details = [
				'series' => $this->input->post('series'),
				'class' => $this->input->post('class'),
				'name' => $this->input->post('name'),
				'option_a' => $this->input->post('option_a'),
				'option_b' => $this->input->post('option_b'),
				'option_c' => $this->input->post('option_c'),
				'option_d' => $this->input->post('option_d'),
				'answer' => $this->input->post('answer'),
				'marks' => $this->input->post('marks'),
				'updated_by' => $this->session->userdata('ausername'),
				'updated_dt' => $cur_time
			];
			$res = $this->AuthModel->update_objectiveQuestion($details, $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Question Update Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}


	function objectiveQuestionDelete()
	{
		if ($this->permission->is_allow('Delete Subject')) {
			$id = $this->input->post('id');
			$res = $this->AuthModel->delete_record('touch_question', 'id', $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', 'Question Deleted Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	// Objective Question End



	// Main Subject start 
	function msubjects()
	{
		$res = $this->AuthModel->msubject();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->action = "<a msubject_id='" . $value->id . "' class='pr-2 pointer medit-subject' data-toggle='modal' data-target='#medit-subject'><i class='fa fa-edit'></i></a>"
				. "<a msubject_id='" . $value->id . "' class='pointer mdelete_subject'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function madd_subject()
	{
		if ($this->permission->is_allow('New Subject')) {
			$data = [
				'board' => $this->input->post('board'),
				'serial' => $this->input->post('serial'),
				'name' => $this->input->post('name')

			];
			$res = $this->AuthModel->mcreate_subject($data);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Subject Create Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	function mupdate_subject()
	{
		if ($this->permission->is_allow('Update Subject')) {
			$id = $this->input->post('id');
			$details = [
				'board' => $this->input->post('board'),
				'serial' => $this->input->post('serial'),
				'name' => $this->input->post('name')
			];
			$res = $this->AuthModel->mupdate_subject($details, $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Subject Update Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	function mdelete_subject()
	{
		if ($this->permission->is_allow('Delete Subject')) {
			$id = $this->input->post('id');
			$res = $this->AuthModel->delete_record('main_subject', 'id', $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', 'Subject Deleted Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	function mdelete_assign_paper()
	{
		//if ($this->permission->is_allow('Delete Subject')) {
		$id = $this->input->post('id');

		$this->db->where('id', $id);
		$test_data = $this->db->get('paper_assign')->row_array();
		$this->make_result($test_data);

		$res = $this->AuthModel->delete_record('paper_assign', 'id', $id);
		$res = $this->AuthModel->delete_record('paper_submision', 'assign_id', $id);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', 'Subject Deleted Successfully....');
		/*} else {
            $this->message('error', 'permission Denied.......');
        }*/
	}

	// End Main Subject
	// Subject start 
	function subjects()
	{
		$res = $this->AuthModel->subjectr();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->action = "<a subject_id='" . $value->id . "' class='pr-2 pointer edit-subject' data-toggle='modal' data-target='#edit-subject'><i class='fa fa-edit'></i></a>"
				. "<a subject_id='" . $value->id . "' class='pointer delete_subject'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function add_subject()
	{
		if ($this->permission->is_allow('New Subject')) {
			$data = [
				'name' => $this->input->post('name'),
				'sid' => $this->input->post('sid'),
				'class' => $this->input->post('class'),
				'categories' => implode(',', $this->input->post('categories')),
			];
			$res = $this->AuthModel->create_subject($data);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Subject Create Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	function update_subject()
	{
		if ($this->permission->is_allow('Update Subject')) {
			$id = $this->input->post('id');
			$details = [
				'name' => $this->input->post('name'),
				'sid' => $this->input->post('sid'),
				'class' => $this->input->post('class'),
				'categories' => implode(',', $this->input->post('categories')),
			];
			$res = $this->AuthModel->update_subject($details, $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Subject Update Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	function delete_subject()
	{
		if ($this->permission->is_allow('Delete Subject')) {
			$id = $this->input->post('id');
			$res = $this->AuthModel->delete_record('subject', 'id', $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', 'Subject Deleted Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	// End Subject

	// Assign Test Start

	function assigntest()
	{
		$res = $this->AuthModel->assigntest();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->action = "<button type='button' class='btn btn-lg btn-toggle' data-toggle='button' aria-pressed='false' autocomplete='off" > ''
				. "<a assigntest_id='" . $value->id . "' class='pointer delete_assigntest'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function add_assigntest()
	{

		$teacher_code = $this->session->userdata('teacher_code');
		$class = $this->input->post('class_name');
		$section = $this->input->post('section_name');
		$paper_code = $this->input->post('paper_type');
		$res = $this->AuthModel->check_assign_paper($teacher_code,  $class, $section, $paper_code);
		if ($res) {
			$cur_time = date("Y-m-d h:i:sa");
			$data = [
				'teacher_id' => $this->session->userdata('username'),
				'teacher_code' => $this->session->userdata('teacher_code'),
				'class_name' => $this->input->post('class_name'),
				'section_name' => $this->input->post('section_name'),
				'paper_mode' => $this->input->post('paper_type'),
				'date_start' => $this->input->post('start_date'),
				'date_end' => $this->input->post('end_date'),
				'status' => 0,
				'created_by' => $this->session->userdata('username'),
				'created_date' => $cur_time
			];
			$res = $this->AuthModel->create_assigntest($data);
			if (!$res) {
				$this->session->set_flashdata('error', 'Some thing wrong! Plese try again.');
				redirect('web/test_assign');
			}
			$this->session->set_flashdata('success', $this->input->post('name') . ' Test Assigned Successfully.');
			redirect('web/test_assign');
		} else {

			$this->session->set_flashdata('error', 'Assigned test already exists.');
			redirect('web/test_assign');
		}
	}

	function add_assigntest_active()
	{
		$result_data = array();
		if ($this->session->userdata('type') == 'Teacher') {

			$id = $_GET['assigntest'];

			$details = [
				'status' => 1
			];
			$res = $this->AuthModel->update_assigntest_status($details, $id);
			// if (!$res) {
			// 	redirect('web/test_assign');
			// } else {
			// 	redirect('web/test_assign');
			// }

			// Setting Intial Result of that test with N/A
			$this->db->where('id', $id);
			$active_test_data = $this->db->get('paper_assign')->row_array();
			$this->make_result($active_test_data);
		}
	}
	function make_result($test_data)
	{
		switch ($test_data['paper_mode']) {
			case '11':
				$result_data = [
					'obj_1' => 'N/A',
					'obj_1_dt' => $test_data['created_date'],
					'stu_teacher_code' => $this->session->userdata('teacher_code'),
				];
				break;
			case '12':
				$result_data = [
					'obj_2' => 'N/A',
					'obj_2_dt' => $test_data['created_date'],
					'stu_teacher_code' => $this->session->userdata('teacher_code'),
				];
				break;
			case '13':
				$result_data = [
					'obj_3' => 'N/A',
					'obj_3_dt' => $test_data['created_date'],
					'stu_teacher_code' => $this->session->userdata('teacher_code'),
				];
				break;
			case '14':
				$result_data = [
					'obj_4' => 'N/A',
					'obj_4_dt' => $test_data['created_date'],
					'stu_teacher_code' => $this->session->userdata('teacher_code'),
				];
				break;
			case '21':
				$result_data = [
					'sub_1' => 'N/A',
					'sub_1_dt' => $test_data['created_date'],
					'stu_teacher_code' => $this->session->userdata('teacher_code'),
				];
				break;
			case '22':
				$result_data = [
					'sub_2' => 'N/A',
					'sub_2_dt' => $test_data['created_date'],
					'stu_teacher_code' => $this->session->userdata('teacher_code'),
				];
				break;
		}
		// get all students of that teacher
		$this->db->where('stu_teacher_id', $this->session->userdata('teacher_code'));
		$students = $this->db->get('web_user')->result();
		// if ($students) {
		// 	echo 'found students';
		// }

		foreach ($students as $student) {
			$this->db->where('student_id', $student->id);
			$has_result = $this->db->get('result')->row_array();
			if ($has_result) {
				// update that test result with NA
				$this->db->set(array_merge($has_result, $result_data));
				$this->db->where('student_id', $student->id);
				$result_updated = $this->db->update('result');
				/*
				if (!$has_result[array_keys($result_data)[0]]) {
					// only update with NA if it is null
					$this->db->set($result_data);
					$this->db->where('student_id', $student->id);
					$result_updated = $this->db->update('result');
					// if ($result_updated) {
					// 	echo 'result updated of' . $student->id;
					// }
				}
				*/
			} else {
				// make row for every student with NA result
				$result_data2 = $result_data + [
					'student_id' => $student->id,
					'class' => $student->classes,
					'section' => $student->class_section,
				];
				$result_inserted = $this->db->insert('result', $result_data2);
				// if ($result_inserted) {
				// 	echo 'result inserted';
				// }
			}
		}
	}


	function add_assigntest_inactive()
	{
		if ($this->session->userdata('type') == 'Teacher') {

			$id = $_GET['assigntest'];

			$details = [
				'status' => 0
			];
			$res = $this->AuthModel->update_assigntest_status($details, $id);
			// if (!$res) {
			// 	redirect('web/test_assign');
			// } else {
			// 	redirect('web/test_assign');
			// }
		}
	}

	// Assign Test End

	// Paper Submission Start

	function paper_submision()
	{

		$cur_time = date("Y-m-d h:i:s");
		$summative = 0;
		$objective = 0;
		$paper_mode = $this->input->post('paper_type');
		if ($paper_mode == '21' || $paper_mode == '22') {
			$summative = 1;
		} else {
			$objective = 1;
		}

		$count = count($this->input->post('question'));

		// show result immediately in case of objective exam
		if ($objective) {
			$obtained_marks = 0;
			$total_marks = 0;
			for ($i = 0; $i < $count; $i++) {
				$this->db->where('id', $this->input->post('question')[$i]);
				$objective_question = $this->db->get('touch_question')->row();

				$data = [
					'student_id' => $this->session->userdata('user_id'),
					'student_code' => $this->session->userdata('stu_teacher_code'),
					'student_class' => $this->session->userdata('classes'),
					'student_section' => $this->session->userdata('section'),
					'assign_id' => $this->input->post('assignid'),
					'paper_mode' => $paper_mode,
					'question_id' => $this->input->post('question')[$i],
					'answer' => $this->input->post('answer' . $i),
					'qus_marks' => $this->input->post('marks')[$i],
					'status' => 1,
					'summative_status' => $summative,
					'objective_status' => $objective,
					'paper_date' => $cur_time,
					'created_dt' => $this->input->post('created_date'),
				];
				// checking objective paper
				if ($objective_question->answer == $data['answer']) {
					$data += [
						'ans_marks' => $objective_question->marks,
						'ques_type' => 'Right',
					];
				} else {
					$data += [
						'ans_marks' => '0',
						'ques_type' => 'Wrong',
					];
				}
				$res = $this->AuthModel->paper_submision($data);
				// 
				$obtained_marks += (int)$data['ans_marks'];
				$total_marks += $objective_question->marks;
			}
			switch ($paper_mode) {
				case '11':
					$result_data = [
						'obj_1' => '' . $obtained_marks . '/' . $total_marks,
					];
					break;
				case '12':
					$result_data = [
						'obj_2' => '' . $obtained_marks . '/' . $total_marks,
					];
					break;
				case '13':
					$result_data = [
						'obj_3' => '' . $obtained_marks . '/' . $total_marks,
					];
					break;
				case '14':
					$result_data = [
						'obj_4' => '' . $obtained_marks . '/' . $total_marks,
					];
					break;
			}
			$this->db->set($result_data);
			$this->db->where('student_id', $this->session->userdata('user_id'));
			$this->db->update('result');
			// redirect('/web/student_panel');
			redirect('web/view_objective_paper/' . $data['student_id'] . '/' . $paper_mode);
			// echo 'success';
			// exit();
		}
		if ($summative) {
			for ($i = 0; $i < $count; $i++) {

				$data = [
					'student_id' => $this->session->userdata('user_id'),
					'student_code' => $this->session->userdata('stu_teacher_code'),
					'student_class' => $this->session->userdata('classes'),
					'student_section' => $this->session->userdata('section'),
					'assign_id' => $this->input->post('assignid'),
					'paper_mode' => $paper_mode,
					'question_id' => $this->input->post('question')[$i],
					'answer' => $this->input->post('answer' . $i),
					'qus_marks' => $this->input->post('marks')[$i],
					'status' => 1,
					'summative_status' => $summative,
					'objective_status' => $objective,
					'paper_date' => $cur_time,
					'created_dt' => $this->input->post('created_date'),
				];
				//echo $this->session->userdata('user_id');
				//die();
				$res = $this->AuthModel->paper_submision($data);
			}
			switch ($paper_mode) {
				case '21':
					$result_data = [
						'sub_1' => 'Submitted',
					];
					break;
				case '22':
					$result_data = [
						'sub_2' => 'Submitted',
					];
					break;
			}
			$this->db->set($result_data);
			$this->db->where('student_id', $this->session->userdata('user_id'));
			$this->db->update('result');

			if (!$res) {
				// $this->message('error', $this->AuthModel->error);
				// redirect('web/student_panel');	
				redirect('studentPanel/student_panel');
			}
			// $this->message('success', $this->input->post('name') . ' Test Assign Create Successfully....');
			redirect('studentPanel/student_panel');
		}
	}


	// Paper Submission End


	function teacher_check_paper_summ()
	{

		$count = count($this->input->post('paper_id'));
		$paper_mode = $this->input->post('paper_mode');
		$student_id = $this->input->post('student_id');

		$created_at = date('Y-m-d');
		$obtained_marks = 0;
		$total_marks = $this->input->post('total_marks');
		for ($i = 0; $i < $count; $i++) {

			$id = $this->input->post('paper_id')[$i];

			$data = [
				'ques_type' => $this->input->post('type' . $i),
				'ans_marks' => $this->input->post('teacher_marks')[$i],
				'teacher_remarks' => 'checked',
				'paper_date' => $created_at
			];
			$res = $this->AuthModel->update_paper_submision_marks($data, $id);
			$obtained_marks += $data['ans_marks'];
			// $total_marks += 10;
		}
		switch ($paper_mode) {
			case '21':
				$result_data = [
					'sub_1' => '' . $obtained_marks . '/' . $total_marks,
				];
				break;
			case '22':
				$result_data = [
					'sub_2' => '' . $obtained_marks . '/' . $total_marks,
				];
				break;
		}
		$this->db->set($result_data);
		$this->db->where('student_id', $student_id);
		$res = $this->db->update('result');

		if ($res) {
			$this->session->set_flashdata('success', 'Marks Submitted Successfully');
			redirect('web/result');
		} else {
			$this->session->set_flashdata('error', 'Marks Submision Failed');
			redirect('web/result');
		}
	}


	// Class Section start

	function classesSection()
	{
		$res = $this->AuthModel->classesSection();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->action = "<a classesSection_id='" . $value->id . "' class='pr-2 pointer edit-classesSection' data-toggle='modal' data-target='#edit-classesSection'><i class='fa fa-edit'></i></a>"
				. "<a classesSection_id='" . $value->id . "' class='pointer delete_classesSection'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function add_classesSection()
	{
		if ($this->permission->is_allow('New Classes')) {
			$data = [
				'class_id' => $this->input->post('class_id'),
				'name' => $this->input->post('name'),
				'status' => 1
			];
			$res = $this->AuthModel->create_classesSection($data);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Class Section Create Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	function update_classesSection()
	{
		if ($this->permission->is_allow('Update Classes')) {
			$id = $this->input->post('id');
			$details = [
				'class_id' => $this->input->post('class_id'),
				'name' => $this->input->post('name')

			];
			$res = $this->AuthModel->update_classesSection($details, $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Class Section Update Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	function delete_classesSection()
	{
		if ($this->permission->is_allow('Delete Classes')) {
			$id = $this->input->post('id');
			$res = $this->AuthModel->delete_record('class_section', 'id', $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', 'Class Section Deleted Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	// class Section End

	// Classes start 
	function classess()
	{
		$res = $this->AuthModel->classes();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->action = "<a classes_id='" . $value->id . "' class='pr-2 pointer edit-classes' data-toggle='modal' data-target='#edit-classes'><i class='fa fa-edit'></i></a>"
				. "<a classes_id='" . $value->id . "' class='pointer delete_classes'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function add_classes()
	{
		if ($this->permission->is_allow('New Classes')) {
			$data = [
				'name' => $this->input->post('name'),
				'class_position' => $this->input->post('position'),
			];
			$res = $this->AuthModel->create_classes($data);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Classes Create Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	function update_classes()
	{
		if ($this->permission->is_allow('Update Classes')) {
			$id = $this->input->post('id');
			$details = [
				'name' => $this->input->post('name'),
				'class_position' => $this->input->post('getposition')

			];
			$res = $this->AuthModel->update_classes($details, $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', $this->input->post('name') . ' Classes Update Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	function delete_classes()
	{
		if ($this->permission->is_allow('Delete Classes')) {
			$id = $this->input->post('id');
			$res = $this->AuthModel->delete_record('classes', 'id', $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', 'Classes Deleted Successfully....');
		} else {
			$this->message('error', 'permission Denied.......');
		}
	}

	// End Classes
	// web support

	function support()
	{
		$id = $this->input->post('id');
		$res = $this->AuthModel->support($id);
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->check = '<input type="checkbox" class="form-control-custom" name="checkDelete" value="' . $value->id . '">';
			$value->sr_no = $i;
			$value->action = "<a support_id='" . $value->id . "' class='pr-2 pointer edit-support' href='superadmin/support_update/" . $value->id . "'><i class='fa fa-edit'></i></a>"
				. "<a support_id='{$value->id}' file1='{$value->book_image}' file2='{$value->file_name}' class='pointer delete_support'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}

		$this->message('success', '', $res);
	}

	function addSupport()
	{
		$time = $this->input->post('time');
		$ext = pathinfo($_FILES['support_image']['name'], PATHINFO_EXTENSION);
		$photo = pathinfo($_FILES['support_image']['name'], PATHINFO_FILENAME);
		$photogg = $time . $photo . "." . strtolower($ext);
		$photog = str_replace(' ', '_', $photogg);
		$path = 'assets/files/';

		$ext2 = pathinfo($_FILES['book_image']['name'], PATHINFO_EXTENSION);
		$photo2 = pathinfo($_FILES['book_image']['name'], PATHINFO_FILENAME);
		$photogg2 = $time . $photo2 . "." . strtolower($ext2);
		$photog2 = str_replace(' ', '_', $photogg2);
		$path2 = 'assets/bookicon/';

		// if ($this->upload_file($photog, $path, 'support_image') && $this->upload_file($photog2, $path2, 'book_image')) {
		// 	// $this->message('success', 'file Uploaded');
		// } else {
		// 	$this->message('error', $this->upload_error);
		// }
		// $this->upload_file($photog, $path, 'support_image');
		// $this->upload_file($photog2, $path2, 'book_image');


		/* if ($this->upload_file($photog, $path, 'support_image')) {
//            $this->message('success', 'file Uploaded');
        } else {
            $this->message('error', $this->upload_error);
        }
		if ($this->upload_file($photog2, $path2, 'book_image')) {
//            $this->message('success', 'file Uploaded');
        } else {
            $this->message('error', $this->upload_error);
        }*/


		// modified for url upload feature
		$up_type = $this->input->post('up_type');
		if ($up_type === 'url') {
			$photog = '';
			if ($this->upload_file($photog2, $path2, 'book_image')) {
				// $this->message('success', 'file Uploaded');
			} else {
				$this->message('error', $this->upload_error);
			}
		} else {
			$ext = pathinfo($_FILES['support_image']['name'], PATHINFO_EXTENSION);
			$photo = pathinfo($_FILES['support_image']['name'], PATHINFO_FILENAME);
			$photogg = time() . $photo . "." . strtolower($ext);
			$photog = str_replace(' ', '_', $photogg);
			$path = 'assets/files/';

			if ($this->upload_file($photog, $path, 'support_image') && $this->upload_file($photog2, $path2, 'book_image')) {
				// $this->message('success', 'file Uploaded');
			} else {
				$this->message('error', $this->upload_error);
			}
		}

		$states = implode(',', $this->input->post('states'));
		$data = [
			'title' => $this->input->post('title'),
			'type' => $this->input->post('type'),
			'board' => $this->input->post('board'),
			'publication' => $this->input->post('publication'),
			'subject' => $this->input->post('subject'),
			'msubject' => $this->input->post('msubject'),
			'classes' => $this->input->post('classes'),
			'description' => $this->input->post('description'),
			'edition' => $this->input->post('edition'),
			'year' => $this->input->post('year'),
			'states' => $states,
			'file_name' => $photog,
			'file_url' => $this->input->post('support_url'),
			'book_image' => $photog2
		];

		$res = $this->AuthModel->addSupport($data);

		if ($this->input->get_request_header('Origin') == 'https://www.touchpadwebsupport.com') {
			return $this->output
				->set_content_type('application/json')
				->set_status_header(200)->set_output(json_encode(array('type' => 'success', 'message' => 'Files uploaded successfully')));
		}

		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', 'Support Successfully Uploaded....');
	}

	function update_support()
	{
		$id = $this->input->post('id');
		$file_url = $this->input->post('upload_type') == 'file' ? '' : $this->input->post('support_url');
		if (!$_FILES['support_image']['size'] && !$_FILES['support_icon']['size']) {
			$states = implode(',', $this->input->post('states'));
			$data = [
				'title' => $this->input->post('title'),
				'board' => $this->input->post('board'),
				'publication' => $this->input->post('publication'),
				'subject' => $this->input->post('subject'),
				'msubject' => $this->input->post('msubject'),
				'classes' => $this->input->post('classes'),
				'description' => $this->input->post('description'),
				'edition' => $this->input->post('edition'),
				'year' => $this->input->post('year'),
				'states' => $states,
				'file_url' => $file_url,
				'file_name' => ''
			];
			$res = $this->AuthModel->update_support($data, $id);
		} elseif (!$_FILES['support_image']['size'] && $_FILES['support_icon']['size']) {
			$ext2 = pathinfo($_FILES['support_icon']['name'], PATHINFO_EXTENSION);
			$photo2 = pathinfo($_FILES['support_icon']['name'], PATHINFO_FILENAME);
			$photogg2 = time() . $photo2 . "." . strtolower($ext2);
			$photog2 = str_replace(' ', '_', $photogg2);
			$path2 = 'assets/bookicon/';

			if ($this->upload_file($photog2, $path2, 'support_icon')) {
				// $this->message('success', 'file Uploaded');
			} else {
				$this->message('error', $this->upload_error);
			}
			$states = implode(',', $this->input->post('states'));
			$data = [
				'title' => $this->input->post('title'),
				'board' => $this->input->post('board'),
				'publication' => $this->input->post('publication'),
				'subject' => $this->input->post('subject'),
				'msubject' => $this->input->post('msubject'),
				'classes' => $this->input->post('classes'),
				'description' => $this->input->post('description'),
				'states' => $states,
				'book_image' => $photog2,
				'file_url' => $file_url,
				'file_name' => ''
			];
			$res = $this->AuthModel->update_support($data, $id);
		} else {
			$ext = pathinfo($_FILES['support_image']['name'], PATHINFO_EXTENSION);
			$photo = pathinfo($_FILES['support_image']['name'], PATHINFO_FILENAME);
			$photogg = time() . $photo . "." . strtolower($ext);
			$photog = str_replace(' ', '_', $photogg);
			$path = 'assets/files/';

			$ext2 = pathinfo($_FILES['support_icon']['name'], PATHINFO_EXTENSION);
			$photo2 = pathinfo($_FILES['support_icon']['name'], PATHINFO_FILENAME);
			$photogg2 = time() . $photo2 . "." . strtolower($ext2);
			$photog2 = str_replace(' ', '_', $photogg2);
			$path2 = 'assets/bookicon/';

			if ($this->upload_file($photog, $path, 'support_image')) {
				//  $this->message('success', 'file Uploaded');
			} else {
				$this->message('error', $this->upload_error);
			}
			if ($this->upload_file($photog2, $path2, 'support_icon')) {
				// $this->message('success', 'file Uploaded');
			} else {
				$this->message('error', $this->upload_error);
			}
			$states = implode(',', $this->input->post('states'));
			$data = [
				'title' => $this->input->post('title'),
				'board' => $this->input->post('board'),
				'publication' => $this->input->post('publication'),
				'subject' => $this->input->post('subject'),
				'msubject' => $this->input->post('msubject'),
				'classes' => $this->input->post('classes'),
				'description' => $this->input->post('description'),
				'states' => $states,
				'file_name' => $photog,
				'book_image' => $photog2,
				'file_url' => $file_url,
			];
			$res = $this->AuthModel->update_support($data, $id);
		}
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', 'Support Successfully Updated....');
	}

	function delete_support()
	{
		$id = $this->input->post('id');
		$res = $this->AuthModel->delete_record('websupport', 'id', $id);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', 'Record Deleted Successfully....');
	}

	function delete_support_all()
	{
		$id = $this->input->post('id');
		foreach ($id as $i) {
			$res = $this->AuthModel->delete_record('websupport', 'id', $i);
		}
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', 'Record Deleted Successfully....');
	}

	// end web support

	function upload_video($file_name)
	{
		$this->upload->initialize([
			'upload_path' => "files/",
			'allowed_types' => "gif|jpg|png|jpeg|mp4|3gp|flv|svg|webp|wmv|ico|docx|xlsx|doc|docs|pdf|zip|rar",
			'max_size' => 0,
			'file_name' => $file_name
		]);
		return $this->upload->do_upload('feed_fil');
	}

	// Web Controller Start
	function validate_email()
	{
		$id = $this->input->post('id');
		$res = $this->WebModel->validate_email($id);
		if (!empty($res)) {
			$this->message('error', 'Email ID in already Use.');
		} else {
			$this->message('success', 'Email ID is Avilable.');
		}
	}
	/*
   
    function add_student() {
        $check = $this->WebModel->validate_email($this->input->post('email'));

        if (!empty($check)) {
            $this->message('error', 'Email ID in already Use.');
        } else {
			
			$teacher_code = $this->input->post('stu_teacher_id');
			$check_tu = $this->WebModel->validate_student($teacher_code);
			if (empty($check_tu)) {
            $this->message('error', 'Invalid Teacher Code');
			} else {

                $teacher_book = $check_tu['subject'];
                
            $res = $this->db->insert('web_user', [
                'fullname' => $this->input->post('name'),
                'mobile' => $this->input->post('mobile'),
                'email' => $this->input->post('email'),
                'pin' => $this->input->post('pin'),
                'address' => $this->input->post('address'),
                'stu_teacher_id' => $this->input->post('stu_teacher_id'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'classes' => $this->input->post('class'),
                'board_name' => $this->input->post('board'),
                'school_name' => $this->input->post('school_name'),
                'subject' => $teacher_book,
                'user_type' => 'Student',
                'status' => 1,
                'password' => $this->input->post('password')
            ]);
            $data = [
                'user' => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'fullname' => $this->input->post('name')
            ];
            $config = array(
                'charset' => 'utf-8',
                'wordwrap' => TRUE,
                'mailtype' => 'html'
            );



            $this->email->initialize($config);
            $this->email->to($this->input->post('email'));
            $this->email->from('info@touchpadwebsupport.com', 'Orange - Touchpad');
            $this->email->cc('mayank@epochstudio.net, editorial@orangeeducation.in, info@orangeeducation.in');
            $this->email->subject('Thank you for registering with Orange - Touchpad Web Support');
            $this->email->message($this->load->view('web/email_template', $data, true));
            $this->email->send();
            if (!$res) {
                $this->message('error', $this->error);
            } else {
                $username = $this->input->post('email');
                $password = $this->input->post('password');
                $rest = $this->AuthModel->validate_web($username, $password);
                if ($rest) {
                    $this->message('success', 'You are Successfully registerd with us, Please Check your registerd email for your account credentials...');
                }
            }
        }
    
      }
    }
    */

	function add_student_custom()
	{
		$check = $this->WebModel->validate_email($this->input->post('email'));

		if (!empty($check)) {
			$this->session->set_flashdata('error', 'Email ID is already in Use.');
			redirect('' . '/student-registration');
		} else {

			$teacher_code = $this->input->post('stu_teacher_id');
			$check_tu = $this->WebModel->validate_student($teacher_code);
			if (empty($check_tu)) {
				$this->session->set_flashdata('error', 'Teacher Code is Not Valid!!');
				redirect('' . '/student-registration');
			} else {

				// $teacher_book = $check_tu['subject'];

				$board = $check_tu['board_name'];
				$start_session = $check_tu['session_start'];
				$end_session = $check_tu['session_end'];

				// Book Name for student
				$student_class = $this->input->post('class');
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

				$reg_data = [
					'fullname' => $this->input->post('name'),
					'mobile' => $this->input->post('mobile'),
					'email' => $this->input->post('email'),
					'pin' => $this->input->post('pin'),
					'session_start' => $start_session,
					'session_end' => $end_session,
					'address' => $this->input->post('address'),
					'stu_teacher_id' => $this->input->post('stu_teacher_id'),
					'city' => $this->input->post('city'),
					'state' => $this->input->post('state'),
					'classes' => $student_class,
					'class_section' => $this->input->post('class_section_name'),
					'board_name' => $board,
					'school_name' => $this->input->post('school_name'),
					'subject' => $teacher_book,
					'user_type' => 'Student',
					'status' => 1,
					'activeBooks' => 1,
					'password' => $this->input->post('password'),
					'book_name' => $book_name,
				];

				$res = $this->db->insert('web_user', $reg_data);
				$this->orange_epoch->insert('web_user', $reg_data);

				if ($this->input->get_request_header('Origin') == 'https://www.touchpadwebsupport.com') {
					return $this->output
						->set_content_type('application/json')
						->set_status_header(200);
				}

				$data = [
					'user' => $this->input->post('email'),
					'password' => $this->input->post('password'),
					'fullname' => $this->input->post('name')
				];
				$config = array(
					'charset' => 'utf-8',
					'wordwrap' => TRUE,
					'mailtype' => 'html'
				);



				$this->email->initialize($config);
				$this->email->to($this->input->post('email'));
				$this->email->from('info@touchpadwebsupport.com', 'Orange - Touchpad');
				$this->email->cc('mayank@epochstudio.net, editorial@orangeeducation.in, info@orangeeducation.in');
				$this->email->subject('Thank you for registering with Orange - Touchpad Web Support');
				$this->email->message($this->load->view('web/email_template', $data, true));
				$this->email->send();
				if (!$res) {
					$this->session->set_flashdata('error', 'Email ID is already in Use.');
					redirect('' . '/student-registration');
				} else {

					$this->session->set_flashdata('success', 'You are Successfully registerd with us, Please Check your registerd email for your account credentials...');
					redirect('' . '/student-registration');
				}
			}
		}
	}
	/*
    function add_teacher() {
        $check = $this->WebModel->validate_email($this->input->post('email'));
        if (!empty($check)) {
            $this->message('error', 'Email ID in already Use.');
        } else {
			$teacher_code = $this->randomPass(10);
            $class = implode(',', $this->input->post('class'));
            $subject = implode(',', $this->input->post('subject'));
            $res = $this->db->insert('web_user', [
                'fullname' => $this->input->post('name'),
                'mobile' => $this->input->post('mobile'),
                'teacher_code' => $teacher_code,
                'email' => $this->input->post('email'),
                'pin' => $this->input->post('pin'),
                'address' => $this->input->post('address'),
                //'session_start' => $this->input->post('session_start'),
                'session_end' => $this->input->post('session_end'),
                'principal_name' => $this->input->post('principal_name'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'addresss' => $this->input->post('addresss'),
				'board_name' => $this->input->post('board'),
                'dob' => $this->input->post('dob'),
                'emails' => $this->input->post('emails'),
                'classes' => $class,
                'subject' => $subject,
                'user_type' => 'Teacher',
				'school_name' => $this->input->post('school_name'),
                'password' => $this->input->post('password'),
				'status' => 1,
            ]);

            $data = [
                'user' => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'fullname' => $this->input->post('name')
            ];
            $config = array(
                'charset' => 'utf-8',
                'wordwrap' => TRUE,
                'mailtype' => 'html'
            );



            $this->email->initialize($config);
            $this->email->to($this->input->post('email'));
            $this->email->from('info@touchpadwebsupport.com', 'Orange - Touchpad');
            $this->email->cc('mayank@epochstudio.net, editorial@orangeeducation.in, info@orangeeducation.in');
            $this->email->subject('Your Credentials for Orange - Touchpad Web Support');
            $this->email->message($this->load->view('web/email_template', $data, true));
            $this->email->send();
            if (!$res) {
                $this->message('error', $this->error);
				redirect('');
            } else {
                $username = $this->input->post('email');
                $password = $this->input->post('password');
                $rest = $this->AuthModel->validate_web($username, $password);
                if ($rest) {
                    $this->message('success', 'You are Successfully registerd with us, Please Check your registerd email for your account credentials...');
					//redirect('');
                }
            }
        }
    } 
    */

	function add_teacher_custom()
	{
		$check = $this->WebModel->validate_email($this->input->post('email'));
		if (!empty($check)) {
			$this->session->set_flashdata('error', 'Email ID in already Use.');
			redirect('' . '/teacher-registration');
		} else {

			$month = $this->input->post('session_start');


			$courrentYr = date('Y');
			$pastYr = date('Y') + 1;

			if ($month == '1') {
				$pastMonth = 12;
			} else {
				$pastMonth = $month - 1;
			}

			$start_session = $courrentYr . '/' . $month . '/1';

			$lastcheckdate = $pastYr . '/' . $pastMonth . '/1';

			$lastdate = date("t", strtotime($lastcheckdate));

			$end_session = $pastYr . '/' . $pastMonth . '/' . $lastdate;

			if ($this->input->post('country_type') > 0) {
				$country = $this->input->post('country_type');
			} else {
				$country = $this->input->post('oth_country');
			}

			// series - classes
			$sub_arr = $this->input->post('series');
			$series_classes =  array();
			$classes = array();
			foreach ($sub_arr as $sub) {
				$series_classes[$sub] = $this->input->post($sub . 'Classes');
				$classes = array_merge($classes, $this->input->post($sub . 'Classes'));
			}
			// echo '<pre>', var_dump($series_classes), '</pre>';
			// echo '<pre>', var_dump(serialize($series_classes)), '</pre>';
			// exit();
			// book_name
			$books = array();
			foreach ($series_classes as $key => $value) {
				$this->db->where('sid', $key);
				$this->db->where_in('class', $value);
				$this->db->select('name');
				$subjects = $this->db->get('subject')->result();
				foreach ($subjects as $subject) {
					$books[] = $subject->name;
				}
			}
			$book_name = implode(',', $books);
			// 
			$serialized_series_classes = serialize($series_classes);
			$classes = array_unique($classes);
			if (empty($this->input->post('series'))) {
				$this->session->set_flashdata('error', 'Please select at least one series! Registration Failed.');
				redirect('' . '/teacher-registration');
			}
			$subject = implode(',', $this->input->post('series'));

			//activeBooks
			// $count = count($this->input->post('class'));
			$count = count($classes);
			$sub = array();
			for ($i = 0; $i < $count; $i++) {
				array_push($sub, '1');
			}
			$activeBooks = implode(',', $sub);
			$classes = implode(',', $classes);
			$teacher_code = $this->randomPass(10);
			//activeBooks
			// $count = count($this->input->post('class'));
			// for ($i = 1; $i <= $count; $i++) {
			// $sub[] = 1;
			// }
			// $activeBooks = implode(',', $sub);
			// $teacher_code = $this->randomPass(10);
			// $class = implode(',', $this->input->post('class'));
			// $subject = implode(',', $this->input->post('subject'));
			$reg_data = [
				'fullname' => $this->input->post('name'),
				'mobile' => $this->input->post('mobile'),
				'teacher_code' => $teacher_code,
				'email' => $this->input->post('email'),
				'pin' => $this->input->post('pin'),
				'address' => $this->input->post('address'),
				'session_start' => $start_session,
				'session_end' => $end_session,
				'principal_name' => $this->input->post('principal_name'),
				'country' => $country,
				'city' => $this->input->post('city'),
				'state' => $this->input->post('state'),
				'addresss' => $this->input->post('addresss'),
				'board_name' => $this->input->post('board'),
				'dob' => $this->input->post('dob'),
				'emails' => $this->input->post('emails'),
				// 'classes' => $class,
				'classes' => $classes,
				'subject' => $subject,
				'user_type' => 'Teacher',
				'school_name' => $this->input->post('school_name'),
				'password' => $this->input->post('password'),
				'activeBooks' => $activeBooks,
				'status' => 0,
				'referrel_name' => $this->input->post('referrel_name'),
				'referrel_mobile' => $this->input->post('referrel_mobile'),
				'series_classes' => $serialized_series_classes,
				'book_name' => $book_name,
			];

			$res = $this->db->insert('web_user', $reg_data);
			$this->orange_epoch->insert('web_user', $reg_data);

			if ($this->input->get_request_header('Origin') == 'https://www.touchpadwebsupport.com') {
				return $this->output
					->set_content_type('application/json')
					->set_status_header(200);
			}

			$data = [
				'user' => $this->input->post('email'),
				'password' => $this->input->post('password'),
				'fullname' => $this->input->post('name')
			];
			$config = array(
				'charset' => 'utf-8',
				'wordwrap' => TRUE,
				'mailtype' => 'html'
			);

			$this->email->initialize($config);
			$this->email->to($this->input->post('email'));
			$this->email->from('info@touchpadwebsupport.com', 'Orange - Touchpad');
			$this->email->cc('mayank@epochstudio.net, editorial@orangeeducation.in, info@orangeeducation.in');
			$this->email->subject('Your Credentials for Orange - Touchpad Web Support');
			$this->email->message($this->load->view('web/email_template', $data, true));
			$this->email->send();
			if (!$res) {
				$this->session->set_flashdata('error', 'Something went wrong! Your are not registered');
				redirect('' . '/teacher-registration');
			} else {
				$this->session->set_flashdata('success', 'You are Successfully registerd with us, Please Check your registerd email for your account credentials...');
				redirect('' . '/teacher-registration');
			}
		}
	}

	public function process()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$res = $this->AuthModel->validate_web($username, $password);
		if (!$res) {
			$this->message('error', 'Email or Password is incorrect');
		} else {
			$this->message('success', 'Login Successfull');
		}
	}

	function default_product()
	{
		$selectedBoard = $this->input->post('select_board');
		$selectedPublication = $this->input->post('select_publication');
		$selectedClasses = $this->input->post('select_classes');
		$selectedMSubject = $this->input->post('select_msubject');

		$this->session->set_userdata('board', $selectedBoard);
		$this->session->set_userdata('publication', $selectedPublication);
		$this->session->set_userdata('publication_name', $this->session->userdata('publication_name'));
		$this->session->set_userdata('classes', $selectedClasses);
		$this->session->set_userdata('msubject', $selectedMSubject);

		$bookCategoriesArr = $this->AuthModel->book_categories_arr();
		$currentCategoryId = $this->session->userdata('category');

		if (!in_array($currentCategoryId, $bookCategoriesArr)) {
			$this->session->set_userdata('category', reset($bookCategoriesArr));
		}

		$defaultProduct = $this->AuthModel->default_product();
		$this->message('success', 'Items Found', $defaultProduct);
	}

	function default_product_old()
	{
		//$bid = $this->input->post('select_board');
		//$pid = $this->input->post('select_publication');
		// $board = $this->AuthModel->check_boardName($bid);
		//$msubsubject = $this->WebModel->msubjects($this->input->post('select_msubject'));
		// $publication = $this->AuthModel->check_pubName($pid);
		$this->session->set_userdata('board', $this->input->post('select_board'));
		$this->session->set_userdata('publication', $this->input->post('select_publication'));
		//$this->session->set_userdata('board_name', $board[0]->name);
		//$this->session->set_userdata('msubjectname', $msubsubject[0]->name);
		$this->session->set_userdata('publication_name', $this->session->userdata('publication_name'));
		$this->session->set_userdata('classes', $this->input->post('select_classes'));
		$this->session->set_userdata('msubject', $this->input->post('select_msubject'));
		//$this->session->set_userdata('subject', $this->input->post('select_subject'));
		$res = $this->AuthModel->default_product();
		$this->message('success', 'Items Find', $res);
	}

	function change_board()
	{
		$cid = $this->input->post('bid');
		$this->session->set_userdata('board', $this->input->post('bid'));
		$this->message('success', 'Items Find', $cid);
	}

	function change_product()
	{
		$cid = $this->input->post('id');
		$category = $this->AuthModel->check_catName($cid);
		$this->session->set_userdata('category', $this->input->post('id'));
		$this->session->set_userdata('category_name', $category[0]->name);
		$res = $this->AuthModel->default_product();
		$this->message('success', 'Items Find', $res);
	}

	function get_sub()
	{
		$postData = $this->input->post();
		$data = $this->AuthModel->get_sub($postData);
		echo json_encode($data);
	}

	function get_sectionName()
	{
		$postData = $this->input->post('bid');
		$data = $this->AuthModel->get_section_name($postData);
		echo json_encode($data);
	}
	// modified function for js fetch
	function get_section_name($class_id)
	{
		$data = $this->AuthModel->get_section_name($class_id);
		echo json_encode($data);
		// console.log();
	}
	// get all paper set based on class,series
	function get_paper_set($class_id, $series)
	{
		$this->db->where('class', $class_id);
		$this->db->where('series', $series);
		$this->db->order_by('type', 'ASC');
		$data = $this->db->get('paper_set')->result_array();
		echo json_encode($data);
	}

	function get_sectionName_reg()
	{
		$postData = $this->input->post('bid');
		$data = $this->AuthModel->get_section_name($postData);
		$row = '<option>--Select--</option>';
		foreach ($data as $value) {
			$row .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
			// $section_arr[] = array("id" => $value['id'], "name" => $value['name']);

		}
		echo $row;
		//echo json_encode($section_arr);
	}

	function get_states()
	{
		$postData = $this->input->post('bid');
		//echo 'alert("'.$postData.'")';
		$data = $this->AuthModel->get_statess($postData);
		$row = '<option>--Select State--</option>';
		foreach ($data as $value) {
			$row .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
		}
		echo $row;
	}

	function get_cities()
	{
		$postData = $this->input->post('bid');
		$data = $this->AuthModel->get_cities($postData);
		$row = '<option>--Select State--</option>';
		foreach ($data as $value) {
			$row .= '<option value="' . $value['city'] . '">' . $value['city'] . '</option>';
		}
		echo $row;
		//echo json_encode($section_arr);
	}

	function get_series()
	{
		$postData = $this->input->post('bid');
		$data = $this->AuthModel->subject_name($postData);

		foreach ($data as $sub) {
			$dd = '<div class="col-lg-4">
				<div class="form-check">
					<input type="radio" class="form-control-custom ss" id="student_subject_';
			$dd .= $sub->id;

			$dd .= '" name="subject[]" value="' . $sub->id . '"> 
					<label class="form-check-label" for="student_subject_';
			$dd .= $sub->id;
			$dd .= '">';
			$dd .= $sub->name;
			$dd .= '</label>
				</div>
			</div>';
			echo $dd;
		}


		//echo json_encode($section_arr);
	}

	function get_series_mod($board)
	{
		// $postData = $this->input->post('bid');
		$data = $this->AuthModel->subject_name($board);
		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}
	// get classes of a series
	function get_series_classes($series)
	{
		$this->db->where('sid', $series);
		$this->db->order_by('class', 'asc');
		$books = $this->db->get('subject')->result();
		$classesArr = array();
		foreach ($books as $book) {
			array_push($classesArr, $book->class);
		}
		$this->db->or_where_in('id', $classesArr);
		$classes = $this->db->get('classes')->result();
		return $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode($classes));
	}

	function get_series_update()
	{
		$postData = $this->input->post('bid');
		$postData2 = $this->input->post('webid');
		$data = $this->AuthModel->subject_name($postData);
		$data2 = $this->AuthModel->retrive_teacher_update_row($postData2);
		// modified for change from radio to checkbox
		$subjectArray = explode(',', $data2->subject);

		foreach ($data as $key => $sub) {
			$dd = '<div class="col-lg-4">
				<div class="form-check">
					<input type="checkbox" ';
			foreach ($subjectArray as $subArr) {
				if ($subArr == $sub->id) {
					$dd .= 'checked';
				} else {
					$dd .= '';
				}
			}
			$dd .= ' class="form-control-custom ss" id="student_subject_';
			$dd .= $sub->id;

			$dd .= '" name="subject[]" value="' . $sub->id . '"> 
					<label class="form-check-label" for="student_subject_';
			$dd .= $sub->id;
			$dd .= '">';
			$dd .= $sub->name;
			$dd .= '</label>
				</div>
			</div>';
			echo $dd;
		}
		//echo json_encode($section_arr);
	}


	function get_serieschange_update()
	{
		$postData = $this->input->post('bid');
		$postData2 = $this->input->post('webid');
		$data = $this->AuthModel->subject_name($postData);
		$data2 = $this->AuthModel->retrive_teacher_update_row($postData2);

		foreach ($data as $sub) {
			$dd = '<div class="col-lg-4">
				<div class="form-check">
					<input type="radio" ';
			if ($data2->subject == $sub->id) {
				$dd .= 'checked';
			} else {
				$dd .= '';
			}
			$dd .= ' class="form-control-custom ss" id="student_subject_';
			$dd .= $sub->id;

			$dd .= '" name="subject[]" value="' . $sub->id . '"> 
					<label class="form-check-label" for="student_subject_';
			$dd .= $sub->id;
			$dd .= '">';
			$dd .= $sub->name;
			$dd .= '</label>
				</div>
			</div>';
			echo $dd;
		}
	}
	function get_sectionclass()
	{

		$class_id = $this->input->post('cid');
		$section_id = $this->input->post('sid');
		$teacher_code = $this->session->userdata('teacher_code');
		$class_section_array = $this->AuthModel->class_section_array();

		$this->db->where('class', $class_id);
		$this->db->where('section', $section_id);
		$this->db->where('stu_teacher_code', $teacher_code);
		$this->db->select('*');
		$this->db->from('result');
		$this->db->select('web_user.fullname');
		$this->db->join('web_user', 'web_user.id=result.student_id');
		$results = $this->db->get()->result();
		$row = '';
		foreach ($results as $key => $result) {
			$row .= '<tr><td>' . ++$key . '</td>';
			$row .= '<td>' . $result->fullname . '</td>';
			$row .= '<td>Class ' . $result->class . '' . $class_section_array[$result->section] . '</td>';
			// objective tests
			$row .= '<td><div class="d-flex flex-column justify-content-center">';
			if ($result->obj_1 && $result->obj_1 != 'N/A') {
				$row .= '<div class="my-1"><span>Objective Test 1</span>';
				$row .= '<a class="mx-2 btn btn-sm btn-success" href="' . base_url() . 'web/view_objective_paper/' . $result->student_id . '/' . '11' . '" target="_blank">View</a><span class="gray-box">' . $result->obj_1;
				$row .= '</span></div>';
			} else if ($result->obj_1 == 'N/A') {
				$row .=
					'<div class="my-1"><span>Objective Test 1</span>';
				$row .= '<span class="mx-2 btn btn-sm btn-danger" title="Test Not Attempted">N/A</span>';
				$row .= '</span></div>';
			}
			if ($result->obj_2 && $result->obj_2 != 'N/A') {
				$row .= '<div class="my-1"><span>Objective Test 2</span>';
				$row .= '<a class="mx-2 btn btn-sm btn-success" href="' . base_url() . 'web/view_objective_paper/' . $result->student_id . '/' . '12' . '" target="_blank">View</a><span class="gray-box">' . $result->obj_2;
				$row
					.= '</span></div>';
			} else if ($result->obj_2 == 'N/A') {
				$row .=
					'<div class="my-1"><span>Objective Test 2</span>';
				$row .= '<span class="mx-2 btn btn-sm btn-danger" title="Test Not Attempted">N/A</span>';
				$row .= '</span></div>';
			}

			if ($result->obj_3 && $result->obj_3 != 'N/A') {
				$row .= '<div class="my-1"><span>Objective Test 3</span>';
				$row .= '<a class="mx-2 btn btn-sm btn-success" href="' . base_url() . 'web/view_objective_paper/' . $result->student_id . '/' . '13' . '" target="_blank">View</a><span class="gray-box">' . $result->obj_3;
				$row .= '</span></div>';
			} else if ($result->obj_3 == 'N/A') {
				$row .= '<div class="my-1"><span>Objective Test 3</span>';
				$row .= '<span class="mx-2 btn btn-sm btn-danger" title="Test Not Attempted">N/A</span>';
				$row .= '</span></div>';
			}
			if ($result->obj_4 && $result->obj_4 != 'N/A') {
				$row .= '<div class="my-1"><span>Objective Test 4</span>';
				$row .= '<a class="mx-2 btn btn-sm btn-success" href="' . base_url() . 'web/view_objective_paper/' . $result->student_id . '/' . '14' . '" target="_blank">View</a><span class="gray-box">' . $result->obj_4;
				$row .= '</span></div>';
			} else if ($result->obj_4 == 'N/A') {
				$row .=
					'<div class="my-1"><span>Objective Test 4</span>';
				$row .= '<span class="mx-2 btn btn-sm btn-danger" title="Test Not Attempted">N/A</span>';
				$row .= '</span></div>';
			}
			$row .= '</div></td>';
			// objective test dates
			$row .= '<td><div class="d-flex flex-column justify-content-center">';
			if ($result->obj_1_dt) {
				$row .= '<div class="my-1"><span class="gray-box">' . $result->obj_1_dt . '</div>';
			}
			if ($result->obj_2_dt) {
				$row .= '<div class="my-1"><span class="gray-box">' . $result->obj_2_dt . '</div>';
			}
			if ($result->obj_3_dt) {
				$row .= '<div class="my-1"><span class="gray-box">' . $result->obj_3_dt . '</div>';
			}
			if ($result->obj_4_dt) {
				$row .= '<div class="my-1"><span class="gray-box">' . $result->obj_4_dt . '</div>';
			}
			$row .= '</div></td>';
			// subjective tests
			$row .= '<td><div class="d-flex flex-column justify-content-center">';
			if ($result->sub_1 && $result->sub_1 != 'N/A' && $result->sub_1 != 'Submitted') {
				$row .= '<div class="my-1"><span>Subjective Test 1</span>';
				$row .= '<a class="mx-2 btn btn-sm btn-success" href="' . base_url() . 'web/view_subjective_paper/' . $result->student_id . '/' . '21' . '" target="_blank">View</a><span class="gray-box">' . $result->sub_1;
				$row .= '</span></div>';
				// web/view_subjective_paper/' . $value['student_id'] . '/' . $test_assign_id . '/21"
			} else if ($result->sub_1 == 'N/A') {
				$row .= '<div class="my-1"><span>Subjective Test 1</span>';
				$row .= '<span class="mx-2 btn btn-sm btn-danger" title="Test Not Attempted">N/A</span>';
				$row .= '</span></div>';
			} else if ($result->sub_1 == 'Submitted') {
				$row .= '<div class="my-1"><span>Subjective Test 1</span>';
				$row .= '<a class="mx-2 btn btn-sm btn-success" href="' . base_url() . 'web/view_subjective_paper/' . $result->student_id . '/' . '21' . '" target="_blank">Evaluate</a>';
				$row .= '</div>';
			}
			if ($result->sub_2 && $result->sub_2 != 'N/A' && $result->sub_2 != 'Submitted') {
				$row .= '<div class="my-1"><span>Subjective Test 2</span>';
				$row .= '<a class="mx-2 btn btn-sm btn-success" href="' . base_url() . 'web/ view_subjective_paper/' . $result->student_id . '/' . '22' . '" target="_blank">View</a><span class="gray-box">' . $result->sub_2;
				$row
					.= '</span></div>';
			} else if ($result->sub_2 == 'N/A') {
				$row .=
					'<div class="my-1"><span>Subjective Test 2</span>';
				$row .= '<span class="mx-2 btn btn-sm btn-danger" title="Test Not Attempted">N/A</span>';
				$row .= '</span></div>';
			} else if ($result->sub_2 == 'Submitted') {
				$row .= '<div class="my-1"><span>Subjective Test 2</span>';
				$row .= '<a class="mx-2 btn btn-sm btn-success" href="' . base_url() . 'web/view_subjective_paper/' . $result->student_id . '/' . '22' . '" target="_blank">Evaluate</a>';
				$row .= '</div>';
			}
			$row .= '</div></td>';
			// subjective test dates

			$row .= '<td><div class="d-flex flex-column justify-content-center">';
			if ($result->sub_1_dt) {
				$row .= '<div class="my-1"><span class="gray-box">' . $result->sub_1_dt . '</div>';
			}
			if ($result->sub_2_dt) {
				$row .= '<div class="my-1"><span class="gray-box">' . $result->sub_2_dt . '</div>';
			}
			$row .= '</div></td></tr>';
		}
		echo $row;
		// echo var_dump($response);
	}

	// function get_sectionclass_old()
	// {
	// 	$section_id = $this->input->post('sid');
	// 	$class_id = $this->input->post('cid');
	// 	$data = $this->AuthModel->get_section_class_paper($section_id, $class_id);
	// 	$summative_count = $this->AuthModel->count_summative($section_id, $class_id);
	// 	$objective_count = $this->AuthModel->count_objective($section_id, $class_id);
	// 	$paper_mode = $this->AuthModel->get_paper_mode($section_id, $class_id);
	// 	$papermarks = $this->AuthModel->get_paper_marks($class_id);


	// 	$sam_marks = 0;
	// 	$obj_marks = 0;

	// 	foreach ($paper_mode as $value) {
	// 		switch ($value['paper_mode']) {
	// 			case '11':
	// 				$objective1 = '11';
	// 				break;
	// 			case '12':
	// 				$objective2 = '12';
	// 				break;
	// 			case '13':
	// 				$objective3 = '13';
	// 				break;
	// 			case '14':
	// 				$objective4 = '14';
	// 				break;
	// 			case '21':
	// 				$subjective1 = '21';
	// 				break;
	// 			case '22':
	// 				$subjective2 = '22';
	// 				break;
	// 		}
	// 		// if ($value['paper_mode'] == '21' || $value['paper_mode'] == '22') {
	// 		// 	$summative = $value['paper_mode'];
	// 		// }
	// 		// if ($value['paper_mode'] == '11' || $value['paper_mode'] == '12' | $value['paper_mode'] == '13' | $value['paper_mode'] == '14') {
	// 		// 	$objective = $value['paper_mode'];
	// 		// }
	// 	}
	// 	$sam_marks1 = 0;
	// 	$sam_marks2 = 0;
	// 	$obj_marks1 = 0;
	// 	$obj_marks2 = 0;
	// 	$obj_marks3 = 0;
	// 	$obj_marks4 = 0;

	// 	foreach ($papermarks as $valued) {
	// 		if ($valued['question_type'] == '21' || $valued['question_type'] == '22') {
	// 			$sam_marks1 += $valued['marks'];
	// 		}
	// 		if ($valued['question_type'] == '22') {
	// 			$sam_marks2 += $valued['marks'];
	// 		}

	// 		if ($valued['question_type'] == '11') {
	// 			$obj_marks1 += $valued['marks'];
	// 		}
	// 		if ($valued['question_type'] == '12') {
	// 			$obj_marks2 += $valued['marks'];
	// 		}
	// 		if ($valued['question_type'] == '13') {
	// 			$obj_marks3 += $valued['marks'];
	// 		}
	// 		if ($valued['question_type'] == '14') {
	// 			$obj_marks4 +=  $valued['marks'];
	// 		}
	// 	}

	// 	$a = 1;
	// 	// $tests = array_unique(array_map(function ($test) {
	// 	// 	return $test['assign_id'];
	// 	// }, $data));
	// 	$all_submission = $this->db->get('paper_submision')->result_array();
	// 	$total_students = array_unique(
	// 		array_map(
	// 			function ($test) {
	// 				return $test['student_id'];
	// 			},
	// 			$all_submission
	// 		)
	// 	);
	// 	$total_tests = array_unique(
	// 		array_map(
	// 			function ($test) {
	// 				return $test['assign_id'];
	// 			},
	// 			$all_submission
	// 		)
	// 	);
	// 	$res_data = array();
	// 	$res_test_type = array();
	// 	foreach ($total_tests as $test) {
	// 		$res_test_type += [
	// 			$test => array()
	// 		];
	// 		foreach ($all_submission as $submision) {
	// 			if ($submision['assign_id'] == $test) {
	// 				$res_test_type[$test] = $submision['paper_mode'];
	// 				break;
	// 			}
	// 		}
	// 	}
	// 	// get all assigned test to student


	// 	foreach ($all_submission as $submision) {
	// 		foreach ($total_students as $student) {
	// 			$res_data += [
	// 				$student => array()
	// 			];
	// 			foreach ($total_tests as $key => $test) {
	// 				if ($submision['student_id'] == $student && $submision['assign_id'] == $test) {
	// 					array_push($res_data[$student], $test);
	// 					// $res_data[$student] = [$test];
	// 					// $res_data[$student][$test] = [$submision['paper_mode']];

	// 				}
	// 			}
	// 			$res_data[$student] = array_unique($res_data[$student]);
	// 		}
	// 	}

	// 	foreach ($data as $value) {

	// 		$row = '<tr id="stu_' . $value['student_id'] . '">
	//             <td style="width:30px">' . $a . '</td>
	//             <td style="width:150px;">' . $value['name'] . '</td>
	//             <td>Class ' . $value['student_class'] . '' . $value['sectionName'] . '</td>';
	// 		// <td>';
	// 		// if ($value['status'] == 1) {
	// 		// 	$row .= '<a class="btn btn-primary bg-active w-100">Active</a>';
	// 		// } else {
	// 		// 	$row .= '<a class="btn btn-primary bg-active w-100">Active</a>';
	// 		// }
	// 		// $row .= '</td>	
	// 		$row .= '<td class="d-flex flex-column">';

	// 		foreach ($res_data[$value['student_id']] as $test_assign_id) {
	// 			if ($objective1 == '11') {

	// 				$student_papermarks_obj = $this->AuthModel->get_paper_obtn_marks($value['student_id'], '11');
	// 				if ($res_test_type[$test_assign_id] == '11') {
	// 					$row .= '<div class="m-1"><span>Objective Test 1</span>';
	// 					$row .= '<a class="mx-2 btn btn-sm btn-success" href="' . base_url() . 'web/view_objective_paper/' . $value['student_id'] . '/' . '11' . '" target="_blank">View</a>';
	// 					$row .= '<span class="sp-box">' . $this->AuthModel->get_paper_obtn_marks_for_view($value['student_id'], $test_assign_id, '11')->ans_marks . '/' . $obj_marks1 . '</span></div>';
	// 					// continue;
	// 				}
	// 			}
	// 			//  else {
	// 			// 	$row .= '<span class="btn-text btn-text-attmp" title="Test Not Attempted">N/A</span>';
	// 			// 	// $row .= '<span class="sp-box">N/A</span>';
	// 			// 	continue;
	// 			// }
	// 			if ($objective2 == '12') {
	// 				if ($res_test_type[$test_assign_id] == '12') {
	// 					$row .=
	// 						'<div class="m-1"><span>Objective Test 2</span>';
	// 					$row .= '<a class="mx-2 btn btn-sm btn-success" href="' . base_url() . 'web/view_objective_paper/' . $value['student_id'] . '/' . '12' . '" target="_blank">View</a>';
	// 					$row .= '<span class="sp-box">' . $this->AuthModel->get_paper_obtn_marks_for_view($value['student_id'], $test_assign_id, '12')->ans_marks . '/' . $obj_marks2 . '</span></div>';
	// 					// continue;
	// 				}
	// 			}
	// 			//  else {
	// 			// 	$row .= '<span class="btn-text btn-text-attmp" title="Test Not Attempted">N/A</span>';
	// 			// 	// $row .= '<span class="sp-box">N/A</span>';
	// 			// 	continue;
	// 			// }
	// 			if ($objective3 == '13') {
	// 				if ($res_test_type[$test_assign_id] == '13') {
	// 					$row .=
	// 						'<div class="m-1"><span>Objective Test 3</span>';
	// 					$row .= '<a class="btn btn-sm btn-success" href="' . base_url() . 'web/view_objective_paper/' . $value['student_id'] . '/' . '13' . '" target="_blank">View</a>';
	// 					$row .= '<span class="sp-box">' . $this->AuthModel->get_paper_obtn_marks_for_view($value['student_id'], $test_assign_id, '13')->ans_marks . '/' . $obj_marks3 . '</span></div>';
	// 					// continue;
	// 				}
	// 			}
	// 			//  else {
	// 			// 	$row .= '<span class="btn-text btn-text-attmp" title="Test Not Attempted">N/A</span>';
	// 			// 	continue;
	// 			// 	// $row .= '<span class="sp-box">N/A</span>';
	// 			// }
	// 			if ($objective4 == '14') {
	// 				if ($res_test_type[$test_assign_id] == '14') {
	// 					$row .= '<div class="m-1"><span>Objective Test 4</span>';
	// 					$row .= '<a class="mx-2 btn btn-sm btn-success" href="' . base_url() . 'web/view_objective_paper/' . $value['student_id'] . '/' . '14' . '" target="_blank">View</a>';
	// 					$row .= '<span class="sp-box">' . $this->AuthModel->get_paper_obtn_marks_for_view($value['student_id'], $test_assign_id, '14')->ans_marks . '/' . $obj_marks4 . '</span></div>';
	// 					// continue;
	// 				}
	// 			}
	// 			//  else {
	// 			// 	$row .= '<span class="btn-text btn-text-attmp" title="Test Not Attempted">N/A</span>';
	// 			// 	// $row .= '<span class="sp-box">N/A</span>';
	// 			// 	continue;
	// 			// }
	// 		}
	// 		// $row .= '<span class="btn-text btn-text-pend">Done <a href="' . base_url() . 'web/view_objective_paper/' . $value['student_id'] . '" target="_blank"><i class="text-right fa fa-eye"></i></a></span>';
	// 		// } else {
	// 		// 	$row .= '<span class="btn-text btn-text-attmp" title="Test Not Attempted">N/A</span>';
	// 		// 	// $row .= '<span class="sp-box">N/A</span>';
	// 		// }
	// 		$row .= '</td><td><span class="sp-box marks-numb" style="width: 100%;">' . $value['created_dt'] . '</span></td>';
	// 		$row .= '<td class="d-flex flex-column">';

	// 		if ($subjective1 == '21') {
	// 			foreach ($res_data[$value['student_id']] as $test_assign_id) {
	// 				// $student_papermarks_summ = $this->AuthModel->get_paper_obtn_marks($value['student_id'], 'subjective');
	// 				if ($res_test_type[$test_assign_id] == '21') {
	// 					$row .= '<div class="m-1"><span>Subjective Test 1</span>';
	// 					$row .= '<a class="mx-2 btn btn-sm btn-success" href="' . base_url() . 'web/view_subjective_paper/' . $value['student_id'] . '/' . $test_assign_id . '/21" target="_blank">View / Evaluate</a>';
	// 					$row .= '<span class="sp-box">' . $this->AuthModel->get_paper_obtn_marks_for_view($value['student_id'], $test_assign_id, '21')->ans_marks . '/' . $sam_marks1 . '</span></div>';
	// 				}
	// 			}
	// 		}
	// 		//  else {
	// 		// 	$row .= '<span class="btn-text btn-text-attmp" title="Test Not Attempted">N/A</span>';
	// 		// 	// $row .= '<span class="sp-box">N/A</span>';
	// 		// }
	// 		if ($subjective2 == '22') {
	// 			foreach ($res_data[$value['student_id']] as $test_assign_id) {

	// 				if ($res_test_type[$test_assign_id] == '22') {
	// 					$row .= '<div class="m-1"><span>Subjective Test 2</span>';
	// 					$row .= '<a class="mx-2 btn btn-sm btn-success" href="' . base_url() . 'web/view_subjective_paper/' . $value['student_id'] . '/' . $test_assign_id . '/22" target="_blank">View / Evaluate</a>';
	// 					$row .= '<span class="sp-box">' . $this->AuthModel->get_paper_obtn_marks_for_view($value['student_id'], $test_assign_id, '22')->ans_marks . '/' . $sam_marks2 . '</span></div>';
	// 				}
	// 			}
	// 		}
	// 		//  else {
	// 		// 	$row .= '<span class="btn-text btn-text-attmp" title="Test Not Attempted">N/A</span>';
	// 		// 	// $row .= '<span class="sp-box">N/A</span>';
	// 		// }

	// 		// $row .= '<span class="btn-text btn-text-pend">Done <a href="' . base_url() . 'web/view_subjective_paper/' . $value['student_id'] . '" target="_blank"><i class="text-right fa fa-eye"></i></a></span>';
	// 		// }
	// 		//  else {
	// 		// 	$row .= '<span class="btn-text btn-text-attmp" title="Test Not Attempted">N/A</span>';
	// 		// 	// $row .= '<span class="sp-box">N/A</span>';
	// 		// }

	// 		$row .= '</td>	
	//             <td>';
	// 		$row .= '<span class="sp-box marks-numb" style="width: 100%;">' . $value['created_dt'] . '</span>
	//             </td>';

	// 		// $row .= '<td><a href="' . base_url() . '/web/printPaperpdf/' . $value['student_id'] . '" target="_blank">Print <i class="fa fa-print"></i></a></td>';

	// 		//  $row .='<a href="#" class="act-sp btn-chk"><i class="fa fa-check"></i></a>';
	// 		//  $row .='<a href="'.base_url().'web/marks_submit" target="_blank" class="act-sp btn-edit"><i class="fa fa-edit"></i></a>';
	// 		// $row .= '<td><a data-id="' . $value['student_id'] . '" class="rm_result act-sp btn-trash"><i style="color:white" class="fa fa-trash"></i></a></td>';
	// 		$row .= '</tr>';
	// 		echo $row;
	// 		// echo var_dump($all_submission[0]);
	// 		// echo var_dump($res_data);
	// 		// echo var_dump($res_test_type);
	// 		$a++;
	// 	}
	// }

	function get_subr()
	{
		$postData = $this->input->post();
		$data = $this->AuthModel->get_subr($postData);
		echo json_encode($data);
	}

	function get_subm()
	{
		$postData = $this->input->post();
		$data = $this->AuthModel->get_subm($postData);
		echo json_encode($data);
	}

	function get_subs()
	{
		$postData = $this->input->post();
		$data = $this->AuthModel->get_subs($postData);
		echo json_encode($data);
	}

	function get_techrefsubs()
	{
		$postData = $this->input->post();
		$data = $this->AuthModel->get_techrefsubs($postData);
		echo json_encode($data);
	}

	function sub_ch()
	{
		$data = $this->AuthModel->sub_ch();
		print_r($data);
	}

	function check_forgot()
	{
		$email = $this->input->post('email');
		$res = $this->AuthModel->val_email($email);
		if (!$res) {
			$this->message('error', 'Email is not registerd with us, Please Retry!');
		} else {
			$data = [
				'email' => $email,
				'password' => $res[0]->password,
			];
			$config = array(
				'charset' => 'utf-8',
				'wordwrap' => TRUE,
				'mailtype' => 'html'
			);



			$this->email->initialize($config);
			$this->email->to($this->input->post('email'));
			$this->email->from('info@touchpadwebsupport.com', 'Orange - Touchpad');
			$this->email->subject('Your Password for Orange - Touchpad Web Support');
			$this->email->message($this->load->view('web/email_template2', $data, true));
			$this->email->send();
			$this->message('success', 'Your Password Sent on your registerd email, Please check your email inbox. Thank You....');
		}
	}

	function ret_r()
	{
		$postData = $this->input->post();
		$res = $this->AuthModel->ret_r($postData);
		echo json_encode($res);
	}


	function teacher_reference()
	{
		if (!$this->session->userdata('username')) {
			header("location:" . base_url());
		} else {
			$res = $this->db->insert('teacher_query_question', [
				'user_id' => $this->session->userdata('username'),
				'board' => $this->input->post('board'),
				'publication' => $this->input->post('publication'),
				'class' => $this->input->post('class'),
				'book' => $this->input->post('book'),
				'question_type' => $this->input->post('question_type'),
				'question' => $this->input->post('question'),
				'option_answer' => $this->input->post('option_answer')
			]);

			if ($res) {
				$this->message('success', 'You are Successfully submit your question with us!Thanks.');
			}
			redirect('dashboard');
		}
	}


	function teacher_update_student_prof()
	{
		$id = $this->input->post('stu_id');
		$data = [
			'fullname' => $this->input->post('stud_name'),
			'mobile' => $this->input->post('stud_mobile'),
			'email' => $this->input->post('stud_email'),
			'addresss' => $this->input->post('stud_school_address'),
			'emails' => $this->input->post('stud_school_email'),
			'principal_name' => $this->input->post('pri_name'),
			'referrel_name' => $this->input->post('ref_name'),
			'referrel_mobile' => $this->input->post('ref_contact')
		];
		$res = $this->AuthModel->teacher_update_web_profile($data, $id);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', $this->input->post('stud_name') . 'Profile Updated Successfully....');
	}

	function teacher_remove_student()
	{
		$id = $this->input->post('id');
		$data = [
			'stu_teacher_id' => ''
		];
		$res = $this->AuthModel->teacher_remove_student($data, $id);
		if (!$res) {
			$this->message('error', $this->AuthModel->error);
		}
		$this->message('success', 'Profile Successfully Removed....');
	}


	function randomPass($numchar)

	{

		$word = "a,b,c,d,e,f,g,h,i,j,k,l,m,1,2,3,4,5,6,7,8,9,0";

		$array = explode(",", $word);

		shuffle($array);

		$newstring = implode($array);

		return substr($newstring, 0, $numchar);
	}
	// End Web Controller
	// demo id
	public function gen_id()
	{
		// if (!$this->input->is_cli_request()) {
		// 	echo "This script can only be accessed via the command line" . PHP_EOL;
		// 	return;
		// }
		// $id = date('dmY') . '@orangeeducation.in';
		$date = new DateTime("now", new DateTimeZone('Asia/Kolkata'));
		$id = $date->format('dmY') . '@orangeeducation.in';
		$data = [
			'id' => 999999,
			'user_type' => 'Teacher',
			'stu_teacher_id' => '',
			'teacher_code' => 'tech3438',
			'session_start' => date('Y-m-d'),
			'session_end' => date('Y-m-d'),
			'fullname' => 'Demo ID',
			'email' => $id,
			'password' => '12345678',
			'mobile' => '011-43776600',
			'address' => '9,Daryaganj, New Delhi-110002',
			'pin' => '110002',
			'country_type' => '',
			'city' => 'new delhi',
			'state' => '60',
			'country' => '',
			'oth_state' => '',
			'oth_city' => '',
			'classes' => '1,2,3,4,5,6,7,8',
			'class_section' => '',
			'subject' => '27',
			'school_name' => '',
			'board_name' => 'CBSE',
			'dp' => '',
			'emails' => '',
			'addresss' => '9,Daryaganj, New Delhi-110002',
			'dob' => '',
			'referrel_name' => '',
			'principal_name' => '',
			'referrel_mobile' => '',
			'status' => '1',
			'active' => '1',
			'activeBooks' => '0,1,1,1,1,1,1,1',
			// 'dated' => '',
		];
		$generation = $this->AuthModel->gen_id($data);
	}
	function delete_student_result($id)
	{
		$this->db->where('student_id', $id);
		$res = $this->db->delete('paper_submision');
		if ($res) {
			return TRUE;
		}
		return false;
	}

	// Bulk Question Upload
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

	function upload_questions()
	{
		$file_name = date('ymd-hisa'); /// $this->WebModel->Webuser()[0]->teacher_code . '_' . 
		$config['upload_path'] = 'assets/question_files';
		$config['allowed_types'] = 'csv';
		$config['max_size'] = 10240;
		$config['file_name'] = $file_name;

		$this->upload->initialize($config);
		if (!$this->upload->do_upload('questions_file')) {
			$error = array('error' => $this->upload->display_errors());
			$this->session->set_flashdata('error', $error['error']);
			redirect('' . 'superadmin/test_question');
			// echo var_dump($error);
		} else {
			$data = array('upload_data' => $this->upload->data());
			// echo '<pre>', var_dump($this->csvToArray('assets/reg_files/' . $file_name . '.csv')), '<pre>';
			$questions_data = $this->csvToArray('assets/question_files/' . $file_name . '.csv');
			// for ($i = 0; $i < count($initial_reg_data); $i++) {
			// 	'fullname' => $reg_data[$i]['fullname'],
			// 	'email' => $reg_data[$i]['email'],
			// 	'classes' => $reg_data[$i]['class'],
			// 	'mobile' => $reg_data[$i]['mobile'],
			// $this->session->set_userdata('upload_success', $reg_data);
			// redirect('' . '/multiple-student-registration');
			$series = $this->input->post('msubject');
			$book = explode(',', $this->input->post('book'));
			$class = $book[0];
			$book_id = $book[1];
			$set = $this->input->post('set');
			$desc = $this->input->post('desc');

			// set name
			switch ($set) {
				case '11':
					$set_name = 'Objective Test 1';
					break;
				case '12':
					$set_name = 'Objective Test 2';
					break;
				case '13':
					$set_name = 'Objective Test 3';
					break;
				case '14':
					$set_name = 'Objective Test 4';
					break;
				case '21':
					$set_name = 'Subjective Test 1';
					break;
				case '22':
					$set_name = 'Subjective Test 2';
					break;
			}
			// check for paper set
			$this->db->where('series', $series);
			$this->db->where('class', $class);
			$this->db->where('type', $set);
			$set_exist = $this->db->get('paper_set')->row_array();
			// insert
			if (!$set_exist) {
				$paper_set_data = [
					'class' => $class,
					'series' => $series,
					'book' => $book_id,
					'name' => $set_name,
					'type' => $set,
					'description' => $desc,
				];
				$this->db->insert('paper_set', $paper_set_data);
			}

			if ($set > 20 && $set < 30) {
				// It is subjective type
				$option_a = '';
				$option_b = '';
				$option_c = '';
				$option_d = '';
				$answer = '';
			}

			foreach ($questions_data as $key => $question_data) {
				if ($set > 10 && $set < 20) {
					// It is objective type
					$option_a = $question_data['option_a'];
					$option_b = $question_data['option_b'];
					$option_c = $question_data['option_c'];
					$option_d = $question_data['option_d'];
					$answer = $question_data['answer'];
				}

				// for ($i = 0; $i < count($question_data); $i++) {
				// $marks = (int)$question_data['marks'];
				// echo var_dump($marks);
				// exit();
				$data = [
					'question_type' => $set, // paper set name
					'series' => $series,
					'class' => $class,
					'book_id' => $book_id,
					'name' => $question_data['question'], //question
					'option_a' => $option_a,
					'option_b' => $option_b,
					'option_c' => $option_c,
					'option_d' => $option_d,
					'option_e' => '',
					'marks' => (int)$question_data['marks'],
					// 'marks' => $marks, //assign 10 for subjective 2 for objective
					'answer' => $answer, //answer of objective question
					// 'desc' => $desc,
					'status' => 1,
					'created_by' => '',
					'created_dt' => '',
					'updated_by' => '',
					'updated_dt' => '',
				];
				$res = $this->db->insert('touch_question', $data);
			}
			if ($res) {
				// echo 'upload success';
				$this->session->set_flashdata('success', 'Upload Successful !');
				// to prevent repeating upload
				redirect('' . '/superadmin/test_question');
			} else {
				// echo 'failed';
				$this->session->set_flashdata('error', 'Upload Failed !');
				// to prevent repeating upload
				redirect('' . '/superadmin/test_question');
			}
		}
	}
	// get main subject of selected board via js fetch
	function get_main_subjects($board)
	{
		$this->db->where('board', $board);
		$res = $this->db->get('main_subject')->result();
		echo json_encode($res);
	}
	// get books of selected subject via js fetch
	function get_books($msubject)
	{
		$this->db->where('sid', $msubject);
		$res = $this->db->get('subject')->result();
		echo json_encode($res);
	}
	// delete a questions set
	function delete_questions_set($book_id, $question_type)
	{
		$this->db->where('book_id', $book_id);
		$this->db->where('question_type', $question_type);
		$deletion1 = $this->db->delete('touch_question');
		$this->db->where('book', $book_id);
		$this->db->where('type', $question_type);
		$deletion2 = $this->db->delete('paper_set');
		if ($deletion1 && $deletion2) {
			$res = array('success'  => 'Deletion Successful!');
		} else {
			$res = array('error'  => 'Deletion Failed!');
		}
		echo json_encode($res);
	}
	// preview uploaded questions
	function preview_uploaded_questions($book_id, $question_type)
	{
		$this->db->where('book_id', $book_id);
		$this->db->where('question_type', $question_type);
		$res = $this->db->get('touch_question')->result();
		echo json_encode($res);
	}

	// Get classes based on selected main subject
	function getClasses($mainSubjectID)
	{
		// function modified for getting teacher classes of a particular main subject
		$user_id = $this->session->userdata('user_id');
		$this->db->where('id', $user_id);
		$user_row = $this->db->get('web_user')->row();
		if (!($user_row->series_classes)) {
			// $mainSubject = $this->AuthModel->msubject($mainSubjectID);
			// $classesArr = explode(',', $mainSubject[0]->classes);
			// show all classes of that user
			$classesArr = explode(',', $user_row->classes);
		} else {
			// show only classes that a user has selected
			$data =  unserialize($user_row->series_classes);
			$classesArr = $data[$mainSubjectID];
		}
		$this->db->where_in('id', $classesArr);
		$classes = $this->db->get('classes')->result();
		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($classes));
	}
	function delete_websupport_files()
	{
		$file1 = $this->input->post('file1');
		$file2 = $this->input->post('file2');

		$thumbnail_file = "assets/bookicon/{$file1}";
		$content_file = "assets/files/{$file2}";

		is_file($thumbnail_file) && unlink($thumbnail_file);
		is_file($content_file) && unlink($content_file);
	}


	function add_notification()
	{
		// Skip permission check
		if ($this->permission->is_allow('New Notification')) {

			$data = [
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
			];

			log_message('debug', 'Notification Data: ' . print_r($data, true));

			$res = $this->AuthModel->create_notification($data);

			if (!$res) {
				log_message('error', 'Error creating notification: ' . $this->AuthModel->error);
				$this->message('error', $this->AuthModel->error);
			} else {
				$this->message('success', 'Notification created successfully.');
				redirect('admin_master/add_notification');
			}
		} else {
			$this->message('error', 'Permission Denied.');
		}
	}
	// Update Notification
	public function update_notification()
	{
		// Uncomment this line if you want to check for permissions
		if ($this->permission->is_allow('Update Notification')) {

			// Get the ID of the notification to update
			$id = $this->input->post('id');

			// Prepare the data to update
			$details = [
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description')
			];

			// Log the data for debugging
			log_message('debug', 'Updating notification ID: ' . $id . ' with data: ' . print_r($details, true));

			// Call the model method to update the notification
			$res = $this->AuthModel->update_notification($details, $id);

			// Check if the update was successful
			if (!$res) {
				// Set an error message if the update failed
				$this->message('error', $this->AuthModel->error);
			} else {
				// Set a success message if the update was successful
				$this->message('success', 'Notification updated successfully.');
			}
		} else {
			$this->message('error', 'Permission Denied.');
		}
	}
	// Delete Notification
	function delete_notification()
	{
		if ($this->permission->is_allow('Delete Notification')) {
			$id = $this->input->post('id');
			$res = $this->AuthModel->delete_record('notifications', 'id', $id);
			if (!$res) {
				$this->message('error', $this->AuthModel->error);
			}
			$this->message('success', 'Notification deleted successfully.');
		} else {
			$this->message('error', 'Permission Denied.');
		}
	}

	// Retrieve Notifications
	function notifications()
	{

		$res = $this->AuthModel->get_notifications();
		$i = 1;
		foreach ($res as $key => &$value) {
			$value->sr_no = $i;
			$value->action = "<a notification_id='" . $value->id . "' class='pr-2 pointer edit-notification' data-toggle='modal' data-target='#edit-notification' data-title='" . htmlspecialchars($value->title) . "' data-description='" . htmlspecialchars($value->description) . "'><i class='fa fa-edit'></i></a>"
				. "<a notification_id='" . $value->id . "' class='pointer delete_notification'><i class='fa fa-trash text-danger'></i></a>";
			$i++;
		}
		$this->message('success', '', $res);
	}
}
