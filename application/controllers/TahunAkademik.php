<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require(FCPATH . 'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class TahunAkademik extends CI_Controller {

	protected $page_header = 'Tahun Akademik Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Tahunakademik_model'=>'tahun'));
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
		$data['panel_heading'] = 'Tahun Akademik List';
		$data['page']         = '';
		$data['breadcrumb']         = 'Tahun Akademik';

		$this->template->backend('tahunakademik_v', $data);
	}

	public function get_tahunakademik()
	{
		if (!$this->ion_auth->logged_in()){            
			redirect('auth/login', 'refresh');
		}
		elseif(!$this->ion_auth->is_admin()) 
		{
			redirect('auth/login', 'refresh');
		}

		$list = $this->tahun->get_datatables();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) { 
			$id = $field->TahunAkademikID;

			$url_view   = 'view_data('.$id.');';
			$url_update = 'update_data('.$id.');';
			$url_delete = 'delete_data('.$id.');';

			if($field->Status == 0)
			{
				$Status = 'Tidak Aktif';
			}
			if($field->Status == 1)
			{
				$Status = 'Aktif';
			}

			$no++;
			$row = array();
			$row[] = ajax_button($url_view, $url_update, $url_delete);
			$row[] = $no;
			$row[] = $field->TahunAkademik;
			$row[] = $Status;

			$data[] = $row;
		}
		
		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->tahun->count_rows(),
			"recordsFiltered" => $this->tahun->count_filtered(),
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

		$id = $this->input->post('TahunAkademikID');

		$query = $this->tahun
		->where('TahunAkademikID', $id)
		->get();

		$data = array();
		if($query){
			$data = array('TahunAkademikID' => $query->TahunAkademikID,
				'TahunAkademik' => $query->TahunAkademik,
				'Status' => $query->Status
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

		$opt_Status = array('0' => 'Tidak Aktif', '1' => 'Aktif');

		$row = array();
		if($this->input->post('TahunAkademikID')){
			$id      = $this->input->post('TahunAkademikID');
			$query   = $this->tahun->where('TahunAkademikID', $id)->get(); 
			if($query){
				$row = array(
					'TahunAkademikID'       => $query->TahunAkademikID,
					'TahunAkademik'     => $query->TahunAkademik,
					'Status'      => $query->Status
				);
			}
			$row = (object) $row;
		}

		$data = array('hidden'=> form_hidden('TahunAkademikID', !empty($row->TahunAkademikID) ? $row->TahunAkademikID : ''),
			'TahunAkademik' => form_input(array('name' => 'TahunAkademik', 'id' => 'TahunAkademik', 'class' => 'form-control', 'value' => !empty($row->TahunAkademik) ? $row->TahunAkademik : '')),
			'Status' => form_dropdown('Status', $opt_Status, !empty($row->Status) ? $row->Status : '', 'class="form-control"')
		);

		echo json_encode($data);
	}

	public function save_tahunakademik()
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

				array('field' => 'TahunAkademik', 'label' => 'TahunAkademik', 'rules' => 'trim|required|is_unique[tahun_akademik.TahunAkademik]|max_length[150]'),
				array('field' => 'Status', 'label' => 'Status', 'rules' => 'trim|required|max_length[150]'),
			),
			'update' => array(
				array('field' => 'TahunAkademikID', 'label' => 'TahunAkademikID', 'rules' => 'trim|required|max_length[8]'),
				array('field' => 'TahunAkademik', 'label' => 'TahunAkademik', 'rules' => 'trim|required|max_length[150]'),
				array('field' => 'Status', 'label' => 'Status', 'rules' => 'trim|required|max_length[150]'),
			)
		);

		$row = array(
			'TahunAkademik' => $this->input->post('TahunAkademik'),
			'Status' => $this->input->post('Status')
		);


		$code = 0;

		if ($this->input->post('TahunAkademikID') == null) {

			$this->form_validation->set_rules($rules['insert']);

			if ($this->form_validation->run() == true) {

				if($row['Status'] == 1){
					$this->tahun->update(array('Status' => 0));
				}

				$this->tahun->insert($row);

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

				if($row['Status'] == 1){
					$this->tahun->update(array('Status' => 0));
				}

				$id = $this->input->post('TahunAkademikID');

				$this->tahun->where('TahunAkademikID', $id)->update($row);

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

		$id = $this->input->post('TahunAkademikID');

		$this->tahun->where('TahunAkademikID', $id)->delete();

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
