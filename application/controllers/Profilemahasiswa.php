<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Profilemahasiswa extends CI_Controller
{

	protected $page_header = 'Profile';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Mahasiswa_model' => 'mahasiswa', 'Penelitian_model' => 'penelitian', 'Dosen_model' => 'dosen', 'Proposal_model' => 'proposal', 'Users_model' => 'users'));
		$this->load->library(array('ion_auth', 'form_validation', 'templatemahasiswa', 'pdf'));
		$this->load->helper('bootstrap_helper');
	}

	public function index()
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
			redirect('auth/login', 'refresh');
		}

		$data['page_header']   = $this->page_header;
		$data['panel_heading'] = 'Daftar Penelitian';
		$data['page']         = '';
		$data['breadcrumb']         = 'Profile';

		$this->templatemahasiswa->backend('profile_v', $data);
	}

	public function get_profile()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
			redirect('auth/login', 'refresh');
		}

		$id = $this->session->userdata('username');

		$mahasiswa = $this->mahasiswa->where('NIM', $id)->get();
		$users = $this->users->where('username', $id)->get();

		if(!empty($mahasiswa) && !empty($users))
		{
			$data = array(
				'Nama' => $mahasiswa->Nama,
				'NIM' => $mahasiswa->NIM,
				'Prodi' => $mahasiswa->Prodi,
				'Angkatan' => $mahasiswa->Angkatan
			);
		}

		if(!empty($users->email))
		{
			$data['Email'] = $users->email;
		} else {
			$data['Email'] = 'xxxx@mail.com';
		}

		if(!empty($users->phone))
		{
			$data['Phone'] = $users->phone;
		} else {
			$data['Phone'] = '+62 8xx xxxx xxxx';
		}

		// echo "<pre>";
		// print_r($data);
		// exit();

		echo json_encode($data);
	}

	public function form_data()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
			redirect('auth/login', 'refresh');
		}

		$data = array(
			'check' => form_password(array('name' => 'password1', 'id' => 'password1', 'class' => 'form-control', 'value' => ''))
		);

		echo json_encode($data);
	}

	public function form_edit()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
			redirect('auth/login', 'refresh');
		}

		$id = $this->session->userdata('username');

		$query   = $this->users->where('username', $id)->get(); 
		if($query){
			$row = array(
				'Email'       => $query->email,
				'Whatsapp'     => $query->phone
			);
		}
		$row = (object) $row;

		$data = array(
			'email1' => form_input(array('name' => 'Email', 'id' => 'Email', 'class' => 'form-control', 'value' => !empty($row->Email) ? $row->Email : '')),
			'Whatsapp' => form_input(array('name' => 'Whatsapp', 'id' => 'Whatsapp', 'class' => 'form-control', 'value' => !empty($row->Whatsapp) ? $row->Whatsapp : '')),
			'password' => form_password(array('name' => 'password', 'id' => 'password', 'class' => 'form-control', 'value' => '')),
			'repassword' => form_password(array('name' => 'repassword', 'id' => 'repassword', 'class' => 'form-control', 'value' => ''))
		);

		echo json_encode($data);
	}

	public function save_edit($id = null)
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
			redirect('auth/login', 'refresh');
		}

		$rule = array(array('field' => 'Whatsapp', 'label' => 'Whatsapp', 'rules' => 'trim|required|min_length[10]|max_length[13]'),
			array('field' => 'Email', 'label' => 'Email', 'rules' => 'required|valid_email'));

		if (!empty($_POST['password'])) {
			$rule[] = array('field' => 'password', 'label' => 'Password', 'rules' => 'alpha_numeric|min_length[5]|max_length[20]');
			$rule[] = array('field' => 'repassword', 'label' => 'Retype Password', 'rules' => 'alpha_numeric|required|min_length[5]|max_length[20]|matches[password]');
		}

		$code = 0;

		$this->form_validation->set_rules($rule);

		if ($this->form_validation->run() == true)
		{
			$row = array(
				'email' => $this->input->post('Email'),
				'phone' => $this->input->post('Whatsapp')
			);

			if (!empty($this->input->post('password'))) {
				$row['password'] = $this->input->post('password');
			}

			$this->ion_auth->update($id, $row);

			$error =  $this->db->error();
			if ($error['code'] <> 0) {
				$code = 1;
				$title = 'Warning!';
				$notifications = $error['code'] . ' : ' . $error['message'];
			} else {
				$title = 'Update!';
				$notifications = 'Success Update Data';
			}

		} else {
			$code = 1;
			$title = 'Warning!';
			$notifications = validation_errors(' ', ' ');
		}

		$notif = ($code == 0) ? json_encode(array('icon' => 'success', 'title' => $title, 'message' => $notifications, 'code' => $code)) : json_encode(array('icon' => 'error', 'title' => $title, 'message' => $notifications, 'code' => $code));
		
		echo $notif;

	}

	public function check_password()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
			redirect('auth/login', 'refresh');
		}

		$identity = $this->session->userdata('username');
		$password = $this->input->post('password1');
		$remember = false;

		$code = 0;

		if($this->ion_auth->login($identity, $password, $remember))
    	{
    		$title = 'Insert!';
			$notifications = 'Success Insert Data';
    	} else {
    		$code = 1;
    		$title = 'Warning!';
    		$notifications = 'Password yang anda masukan salah';
    	}

		$notif = ($code == 0) ? json_encode(array('icon' => 'success', 'title' => $title, 'message' => $notifications, 'code' => $code)) : json_encode(array('icon' => 'error', 'title' => $title, 'message' => $notifications, 'code' => $code));
		
		echo $notif;
	}
	
}
