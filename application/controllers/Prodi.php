<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require(FCPATH . 'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Prodi extends CI_Controller {

	protected $page_header = 'Mahasiswa Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Prodi_model'=>'prodi'));
		$this->load->library(array('ion_auth', 'form_validation', 'template'));
		$this->load->helper('bootstrap_helper');
	}

	public function index()
	{  
		
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$data['page_header']   = $this->page_header;
		$data['panel_heading'] = 'Program Studi List';
		$data['page']         = '';
		$data['breadcrumb']         = 'Program Studi';

		$this->template->backend('prodi_v', $data);
	}

	public function get_prodi()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$list = $this->prodi->get_datatables();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) { 
			$id = $field->ProdiID;

			$url_view   = 'view_data('.$id.');';
			$url_update = 'update_data('.$id.');';
			$url_delete = 'delete_data('.$id.');';

			$no++;
			$row = array();
			$row[] = ajax_button($url_view, $url_update, $url_delete);
			$row[] = $no;
			$row[] = $field->NamaProdi;
			$row[] = $field->Jenjang;

			$data[] = $row;
		}
		
		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->prodi->count_rows(),
			"recordsFiltered" => $this->prodi->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function view()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$id = $this->input->post('ProdiID');

		$query = $this->prodi
		->where('ProdiID', $id)
		->get();

		$data = array();
		if($query){
			$data = array('ProdiID' => $query->ProdiID,
				'NamaProdi' => $query->NamaProdi,
				'Jenjang' => $query->Jenjang
			);
		}

		echo json_encode($data);
	}

	public function form_data()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$row = array();
		if($this->input->post('ProdiID')){
			$id      = $this->input->post('ProdiID');
			$query   = $this->prodi->where('ProdiID', $id)->get(); 
			if($query){
				$row = array(
					'ProdiID'       => $query->ProdiID,
					'NamaProdi'     => $query->NamaProdi,
					'Jenjang'      => $query->Jenjang
				);
			}
			$row = (object) $row;
		}

		$data = array('hidden'=> form_hidden('ProdiID', !empty($row->ProdiID) ? $row->ProdiID : ''),
			'NamaProdi' => form_input(array('name' => 'NamaProdi', 'id' => 'NamaProdi', 'class' => 'form-control', 'value' => !empty($row->NamaProdi) ? $row->NamaProdi : '')),
			'Jenjang' => form_input(array('name' => 'Jenjang', 'id' => 'Jenjang', 'class' => 'form-control', 'value' => !empty($row->Jenjang) ? $row->Jenjang : ''))
		);

		echo json_encode($data);
	}

	public function save_prodi()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$rules = array(
			'insert' => array(

				array('field' => 'NamaProdi', 'label' => 'NamaProdi', 'rules' => 'trim|required|max_length[150]'),
				array('field' => 'Jenjang', 'label' => 'Jenjang', 'rules' => 'trim|required|max_length[150]'),
			),
			'update' => array(
				array('field' => 'ProdiID', 'label' => 'ProdiID', 'rules' => 'trim|required|max_length[8]'),
				array('field' => 'NamaProdi', 'label' => 'NamaProdi', 'rules' => 'trim|required|max_length[150]'),
				array('field' => 'Jenjang', 'label' => 'Jenjang', 'rules' => 'trim|required|max_length[150]'),
			)
		);

		$row = array(
			'NamaProdi' => $this->input->post('NamaProdi'),
			'Jenjang' => $this->input->post('Jenjang')
		);


		$code = 0;

		if ($this->input->post('ProdiID') == null) {

			$this->form_validation->set_rules($rules['insert']);

			if ($this->form_validation->run() == true) {

				$this->prodi->insert($row);

				$error =  $this->db->error();
				if ($error['code'] <> 0) {
					$code = 1;
					$title = 'Warning!';
					$notifications = $error['code'] . ' : ' . $error['message'];
				} else {
					$title = 'Insert!';
					$notifications = 'Success Insert Data';
				}
			} else {
				$code = 1;
				$title = 'Warning!';
				$notifications = validation_errors(' ', ' ');
			}
		} else {

			$this->form_validation->set_rules($rules['update']);

			if ($this->form_validation->run() == true) {

				$id = $this->input->post('ProdiID');

				$this->prodi->where('ProdiID', $id)->update($row);

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
		}

		$notif = ($code == 0) ? json_encode(array('icon' => 'success', 'title' => $title, 'message' => $notifications, 'code' => $code)) : json_encode(array('icon' => 'error', 'title' => $title, 'message' => $notifications, 'code' => $code));
		
		echo $notif;
	}

	public function delete()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$code = 0;

		$id = $this->input->post('ProdiID');

		$this->prodi->where('ProdiID', $id)->delete();

		$error =  $this->db->error();
		if($error['code'] <> 0){
			$code = 1;
			$notifications = $error['code'].' : '.$error['message'];
		}
		else{
			$notifications = 'Success Delete Data';
		}
		
		$notif = ($code == 0) ? json_encode(array('icon' => 'success', 'message' => $notifications, 'code' => $code)) : json_encode(array('icon' => 'error', 'message' => $notifications, 'code' => $code));
		
		echo $notif;
	}

}
