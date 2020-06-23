<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pengajuandosen extends CI_Controller
{

	protected $page_header = 'Daftar Pengajuan Penelitian';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Pengajuan_model' => 'pengajuan', 'Mahasiswa_model' => 'mahasiswa', 'Dosen_model' => 'dosen', 'Bimbingan_model' => 'bimbingan', 'Penelitian_model' => 'penelitian', 'Grouppesan_model' => 'grouppesan', 'Pesan_model' => 'pesan', 'Proposal_model' => 'proposal', 'Tahunakademik_model'=>'tahun'));
		$this->load->library(array('ion_auth', 'form_validation', 'templatekaprodi'));
		$this->load->helper('bootstrap_helper');
	}

	public function index()
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$query = $this->tahun->as_object()->get_all();

		foreach($query as $row)
		{
			$data1[$row->TahunAkademikID] = $row->TahunAkademik;
		}

		arsort($data1);

		$tahunakademik = $this->tahun->where('Status', 1)->get();

		$data['page_header']   = $this->page_header;
		$data['panel_heading'] = 'Pengajuan List';
		$data['page']         = '';
		$data['breadcrumb']         = 'Pengajuan';
		$data['tahunakademik']         = $tahunakademik;
		$data['opt_tahun']         = $data1;

		$this->templatekaprodi->backend('pengajuandosen_v', $data);
	}

	public function get_pengajuan()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$list = $this->pengajuan->get_datatables();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) {
			$id = $field->PengajuanID;

			$url_tolak   = 'save_tolak(' . $id . ');';
			$url_setuju = 'save_setuju(' . $id . ');';
			$url_view = 'view_data(' . $id . ');';

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->Nama;
			$row[] = $field->JenisPengajuan;
			$row[] = $field->JudulPenelitian;
			if ($field->Status == 'On Proses') {
				$row[] = '<button id="view" class="btn btn-warning btn-sm btn-circle text-white" onClick="' . $url_view . '" title="View Data" alt="View Data">' . $field->Status . '<i class="fas fa-eye ml-1"></i></button>';
			} elseif ($field->Status == 'Ditolak') {
				$row[] = '<button id="view" class="btn btn-danger btn-sm btn-circle" onClick="' . $url_view . '" title="View Data" alt="View Data">' . $field->Status . '<i class="fas fa-eye ml-3"></i></button>';
			} else {
				$row[] = '<button id="view" class="btn btn-success btn-sm btn-circle" onClick="' . $url_view . '" title="View Data" alt="View Data">' . $field->Status . '<i class="fas fa-eye ml-2"></i></button>';
			}
			$row[] = tombol_button($url_tolak, $url_setuju);


			$data[] = $row;
		}

		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->pengajuan->count_rows(),
			"recordsFiltered" => $this->pengajuan->count_filtered(),
			"data" => $data
		);
		echo json_encode($output);
	}


	public function view()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$id = $this->input->post('PengajuanID');

		$query = $this->pengajuan
		->with_mahasiswa('fields:Nama')
		->where('PengajuanID', $id)
		->get();

		$data = array();
		if ($query) {
			$data = array(
				'PengajuanID' => $query->PengajuanID,
				'NIM' => $query->NIM,
				'NIM1' => $query->mahasiswa->Nama,
				'JenisPengajuan' => $query->JenisPengajuan,
				'JudulPenelitian' => $query->JudulPenelitian,
				'Status' => $query->Status
			);
		}

		echo json_encode($data);
	}

	public function form_data()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$opt_dosen = $this->dosen->as_dropdown('Nama')->get_all();

		$row = array();
		if ($this->input->post('PengajuanID')) {
			$id      = $this->input->post('PengajuanID');
			$query   = $this->pengajuan->where('PengajuanID', $id)->get();
			if ($query) {
				$row = array(
					'PengajuanID'       => $query->PengajuanID,
					'NIM'       => $query->NIM,
					'JenisPengajuan'     => $query->JenisPengajuan,
					'JudulPenelitian'      => $query->JudulPenelitian
				);
			}
			$row = (object) $row;
		}

		$mahasiswa = $this->mahasiswa->where('NIM', $query->NIM)->get();

		$data = array(
			'hidden' => form_hidden('PengajuanID', !empty($row->PengajuanID) ? $row->PengajuanID : ''),
			'NIM' => form_input(array('name' => 'NIM', 'id' => 'NIM', 'class' => 'form-control', 'value' => !empty($row->NIM) ? $row->NIM : '')),
			'Nama' => form_input(array('name' => 'Nama', 'id' => 'Nama', 'class' => 'form-control', 'value' => !empty($mahasiswa->Nama) ? $mahasiswa->Nama : '')),
			'JenisPengajuan' => form_input(array('name' => 'JenisPengajuan', 'id' => 'JenisPengajuan', 'class' => 'form-control', 'value' => !empty($row->JenisPengajuan) ? $row->JenisPengajuan : '')),
			'JudulPenelitian' => form_input(array('name' => 'JudulPenelitian', 'id' => 'JudulPenelitian', 'class' => 'form-control', 'value' => !empty($row->JudulPenelitian) ? $row->JudulPenelitian : '')),
			'NPP' => form_dropdown('NPP', $opt_dosen, !empty($row->Dosen) ? $row->Dosen : '', 'class="form-control chosen-select"')
		);

		echo json_encode($data);
	}

	public function save_pengajuan()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$tahunakademik   = $this->tahun->where('Status', 1)->get();

		$cek = array(
			'NIM' => $this->input->post('NIM'),
			'Jenis' => $this->input->post('JenisPengajuan'),
			'Judul' => $this->input->post('JudulPenelitian')
		);

		$row = array(
			'NIM' => $this->input->post('NIM'),
			'NPP' => $this->input->post('NPP'),
			'Jenis' => $this->input->post('JenisPengajuan'),
			'Judul' => $this->input->post('JudulPenelitian'),
			'TahunAkademikID' => $tahunakademik->TahunAkademikID,
			'Status' => 'On Proses',
			'Info' => '1'
		);

		$row1= array(
			'Status' => 'Ditolak'
		);

		$code = 0;

		if ($this->input->post('PenelitianID') == null)
		{
			$penelitian = $this->penelitian->where($cek)->get();

			if(empty($penelitian))
			{
				$this->penelitian->insert($row);

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
				$id = $this->input->post('PengajuanID');

				$this->pengajuan->where('PengajuanID', $id)->update($row1);

				$code = 2;
				$title = 'Warning!';
				$notifications = 'Maaf anda sudah mengisi data ini';
			}
		}

		if ($code == 0) {
			$notif = json_encode(array('icon' => 'success', 'title' => $title, 'message' => $notifications, 'code' => $code));
		} elseif ($code == 1) {
			$notif = json_encode(array('icon' => 'error', 'title' => $title, 'message' => $notifications, 'code' => $code));
		} else {
			$notif = json_encode(array('icon' => 'warning', 'title' => $title, 'message' => $notifications, 'code' => $code));
		}

		echo $notif;
	}

	public function save_tolak()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$row = array(
			'Status' => 'Ditolak'
		);

		$code = 0;

		$id = $this->input->post('PengajuanID');

		$pengajuan = $this->pengajuan->where('PengajuanID', $id)->get();

		if($pengajuan->Status == 'On Proses')
		{
			$this->pengajuan->where('PengajuanID', $id)->update($row);
			$code = 2;
			$title = 'Update!';
			$notifications = 'Success Update Data';
		}
		elseif($pengajuan->Status == 'Ditolak')
		{
			$code = 2;
			$title = 'Warning!';
			$notifications = 'Data sudah Ditolak';
		}
		elseif($pengajuan->Status == 'Disetujui')
		{
			$code = 2;
			$title = 'Warning!';
			$notifications = 'Data sudah Disetujui';
		}

			// $this->pengajuan->where('PengajuanID', $id)->update($row);

		else{
			$error =  $this->db->error();
			if ($error['code'] <> 0) {
				$code = 1;
				$title = 'Warning!';
				$notifications = $error['code'] . ' : ' . $error['message'];
			}
		}

		if($code == 0)
		{
			$notif = json_encode(array('icon' => 'success', 'title' => $title, 'message' => $notifications, 'code' => $code));
		}
		elseif($code == 1) {
			$notif = json_encode(array('icon' => 'error', 'title' => $title, 'message' => $notifications, 'code' => $code));
		} 
		else 
		{
			$notif = json_encode(array('icon' => 'warning', 'title' => $title, 'message' => $notifications, 'code' => $code));
		}

		echo $notif;
	}

	public function save_setuju()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$row = array(
			'Status' => 'Disetujui',
			'Info' => '1'
		);

		$code = 0;

		$id = $this->input->post('PengajuanID');

		$pengajuan = $this->pengajuan->where('PengajuanID', $id)->get();

		if($pengajuan->Status == 'On Proses')
		{
			$this->pengajuan->where('PengajuanID', $id)->update($row);
			$title = 'Update!';
			$notifications = 'Success Update Data';
		}
		elseif($pengajuan->Status == 'Ditolak')
		{
			$code = 2;
			$title = 'Warning!';
			$notifications = 'Data sudah Ditolak';
		}
		elseif($pengajuan->Status == 'Disetujui')
		{
			$code = 2;
			$title = 'Warning!';
			$notifications = 'Data sudah Disetujui';
		}

			// $this->pengajuan->where('PengajuanID', $id)->update($row);

		else{
			$error =  $this->db->error();
			if ($error['code'] <> 0) {
				$code = 1;
				$title = 'Warning!';
				$notifications = $error['code'] . ' : ' . $error['message'];
			}
		}

		if($code == 0)
		{
			$notif = json_encode(array('icon' => 'success', 'title' => $title, 'message' => $notifications, 'code' => $code));
		}
		elseif($code == 1) {
			$notif = json_encode(array('icon' => 'error', 'title' => $title, 'message' => $notifications, 'code' => $code));
		} 
		else 
		{
			$notif = json_encode(array('icon' => 'warning', 'title' => $title, 'message' => $notifications, 'code' => $code));
		}

		echo $notif;
	}

	public function delete()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$code = 0;

		$id = $this->input->post('PengajuanID');

		$this->pengajuan->where('PengajuanID', $id)->delete();

		$error =  $this->db->error();
		if ($error['code'] <> 0) {
			$code = 1;
			$notifications = $error['code'] . ' : ' . $error['message'];
		} else {
			$notifications = 'Success Delete Data';
		}

		$notifications = ($code == 0) ? notifications('success', $notifications) : notifications('error', $notifications);

		echo json_encode(array('message' => $notifications, 'code' => $code));
	}

}
