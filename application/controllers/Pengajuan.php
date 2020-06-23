<?php

defined('BASEPATH') or exit('No direct script access allowed');

require(FCPATH . 'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Pengajuan extends CI_Controller
{

	protected $page_header = 'Pengajuan Penelitian';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Pengajuan_model' => 'pengajuan', 'Mahasiswa_model' => 'mahasiswa', 'Penelitian_model' => 'penelitian', 'Tahunakademik_model'=>'tahun'));
		$this->load->library(array('ion_auth', 'form_validation', 'templatemahasiswa'));
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
		$data['panel_heading'] = 'Pengajuan List';
		$data['page']         = '';
		$data['breadcrumb']         = 'Pengajuan';

		$this->templatemahasiswa->backend('pengajuan_v', $data);
	}

	public function get_pengajuan()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
			redirect('auth/login', 'refresh');
		}

		$list = $this->pengajuan->get_datatables();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) {
			$id = $field->PengajuanID;

			$url_view   = 'view_data(' . $id . ');';
			$url_update = 'update_data(' . $id . ');';
			$url_delete = 'delete_data(' . $id . ');';

			$no++;
			$row = array();
			$row[] = ajax_button($url_view, $url_update, $url_delete);
			$row[] = $no;
			$row[] = $field->Nama;
			$row[] = $field->JenisPengajuan;
			$row[] = $field->JudulPenelitian;
			if ($field->Status == 'On Proses') {
				$row[] = "<p class='btn btn-warning btn-sm text-white'>" . $field->Status . "</p>";
			} elseif ($field->Status == 'Ditolak') {
				$row[] = "<p class='btn btn-danger btn-sm'>" . $field->Status . "</p>";
			} else {
				$row[] = "<p class='btn btn-success btn-sm'>" . $field->Status . "</p>";
			}


			$data[] = $row;
		}

		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->pengajuan->count_rows(),
			"recordsFiltered" => $this->pengajuan->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function view()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
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
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
			redirect('auth/login', 'refresh');
		}

		//   $opt_KodeKriteria     = $this->kriteria->as_dropdown('KodeKriteria')->get_all();
		//   $opt_NamaKriteria     = $this->kriteria->as_dropdown('NamaKriteria')->get_all();
		//   $opt = $this->kriteria->as_dropdown('NamaKriteria')->get_all();

		$opt_jenis = array('Kerja Praktek' => 'Kerja Praktek', 'Skripsi' => 'Skripsi');

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

		$data = array(
			'hidden' => form_hidden('PengajuanID', !empty($row->PengajuanID) ? $row->PengajuanID : ''),
			'JenisPengajuan' => form_dropdown('JenisPengajuan', $opt_jenis, !empty($row->JenisPengajuan) ? $row->JenisPengajuan : '', 'class="form-control chosen-select"'),
			'JudulPenelitian' => form_input(array('name' => 'JudulPenelitian', 'id' => 'JudulPenelitian', 'class' => 'form-control', 'value' => !empty($row->JudulPenelitian) ? $row->JudulPenelitian : '')),
		);

		echo json_encode($data);
	}

	public function save_pengajuan()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
			redirect('auth/login', 'refresh');
		}

		$rules = array(
			'insert' => array(
				array('field' => 'JenisPengajuan', 'label' => 'JenisPengajuan', 'rules' => 'trim|required|max_length[20]'),
				array('field' => 'JudulPenelitian', 'label' => 'JudulPenelitian', 'rules' => 'trim|required|max_length[150]')
			),
			'update' => array(
				array('field' => 'PengajuanID', 'label' => 'PengajuanID', 'rules' => 'trim|required|max_length[20]'),
				array('field' => 'JenisPengajuan', 'label' => 'JenisPengajuan', 'rules' => 'trim|required|max_length[20]'),
				array('field' => 'JudulPenelitian', 'label' => 'JudulPenelitian', 'rules' => 'trim|required|max_length[150]')
			)
		);

		// $mahasiswa   = $this->mahasiswa->where('Nama', $this->session->userdata('first_name'))->get();

		$where = array(
			'NIM' => $this->session->userdata('username'), 
			'JenisPengajuan' => $this->input->post('JenisPengajuan')
		);

		$pengajuan   = $this->pengajuan->where($where)->get();
		$penelitian   = $this->penelitian->where('NIM', $this->session->userdata('username'))->get();

		$pengajuan1   = $this->pengajuan->where('NIM', $this->session->userdata('username'))->get();

		$tahunakademik   = $this->tahun->where('Status', 1)->get();

		$row = array(
			'NIM' => $this->session->userdata('username'),
			'JenisPengajuan' => $this->input->post('JenisPengajuan'),
			'JudulPenelitian' => $this->input->post('JudulPenelitian'),
			'TahunAkademikID' => $tahunakademik->TahunAkademikID,
			'Status' => 'On Proses',
			'Info' => '1'
		);

		$code = 0;

		if ($this->input->post('PengajuanID') == null) {

			$this->form_validation->set_rules($rules['insert']);

			if ($this->form_validation->run() == true) {

				if(empty($pengajuan1))
				{
					if($this->input->post('JenisPengajuan') == 'Kerja Praktek')
					{
						$this->pengajuan->insert($row);
						$title = 'Insert!';
						$notifications = 'Success Insert Data';
					}
					elseif($this->input->post('JenisPengajuan') == 'Skripsi')
					{
						$code = 2;
						$title = 'Warning!';
						$notifications = 'Maaf Anda belum bisa melakukan penelitian karna anda belum mengerjakan Kerja Praktek';
					}
				}
				elseif ($pengajuan == null) {
					if($pengajuan1->Status == 'On Proses')
					{
						$code = 2;
						$title = 'Warning!';
						$notifications = 'Maaf tidak bisa menambahkan penelitian karna penelitian yang lama masih di proses';
					} 
					elseif($pengajuan1->Status == 'Ditolak')
					{
						$code = 2;
						$title = 'Warning!';
						$notifications = 'Maaf tidak bisa menambahkan penelitian karna penelitian yang lama Ditolak silahkan edit data penelitian yang Ditolak';
					} 
					elseif($pengajuan1->Status == 'Disetujui')
					{
						if($penelitian->Status == 'On Proses')
						{
							$code = 2;
							$title = 'Warning!';
							$notifications = 'Maaf tidak bisa menambahkan penelitian karna penelitian yang lama masih di proses';
						}
						elseif ($penelitian->Status == 'Selesai') 
						{
							$this->pengajuan->insert($row);
							$title = 'Insert!';
							$notifications = 'Success Insert Data';
						}

					}
					else {
						$this->pengajuan->insert($row);
						$title = 'Insert!';
						$notifications = 'Success Insert Data';
					}
					
				} else {

					$error =  $this->db->error();
					if ($error['code'] <> 0) {
						$code = 1;
						$title = 'Warning!';
						$notifications = $error['code'] . ' : ' . $error['message'];
					} else {
						$code = 2;
						$title = 'Warning!';
						$notifications = 'Maaf Anda sudah Mengisinya';
					}
				}
				
			} else {
				$code = 1;
				$title = 'Warning!';
				$notifications = validation_errors(' ', ' ');
			}
		} else {

			$this->form_validation->set_rules($rules['update']);

			if ($this->form_validation->run() == true) {

				$id = $this->input->post('PengajuanID');

				$pengajuan1 = $this->pengajuan->where('PengajuanID', $id)->get();

				if($pengajuan1->Status == 'On Proses')
				{
					$code = 2;
					$title = 'Warning!';
					$notifications = 'Maaf Anda tidak bisa mengupdate data ini, karna data sedang di proses !';
				}
				elseif($pengajuan1->Status == 'Disetujui')
				{
					$code = 2;
					$title = 'Warning!';
					$notifications = 'Maaf Anda tidak bisa mengupdate data ini, karna data sudah Disetujui !';
				}
				elseif($pengajuan1->Status == 'Ditolak')
				{
					$this->pengajuan->where('PengajuanID', $id)->update($row);
					$title = 'Update!';
					$notifications = 'Success Update Data';
				} else {
					$error =  $this->db->error();
					if ($error['code'] <> 0) {
						$code = 1;
						$title = 'Warning!';
						$notifications = $error['code'] . ' : ' . $error['message'];
					}
				}
			} else {
				$code = 1;
				$title = 'Warning!';
				$notifications = validation_errors(' ', ' ');
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
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
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

		$notif = ($code == 0) ? json_encode(array('icon' => 'success', 'message' => $notifications, 'code' => $code)) : json_encode(array('icon' => 'error', 'message' => $notifications, 'code' => $code));

		echo $notif;
	}

	public function export()
	{
		$filename = urlencode("Data" . date('ymdhis') . ".xls");

		$getdosen = $this->dosen->get_all();
		$getkelas = $this->kelas->get_all();
		$getakademik = $this->akademik->get_all();
		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();

		// Set document properties

		// Add some data



		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('A1', 'Kelas ID')
		->setCellValue('B1', 'NPP')
		->setCellValue('C1', 'Tahun Akademik ID')
		->setCellValue('E1', 'Data dosen')
		->setCellValue('E2', 'NPP')
		->setCellValue('F2', 'Nama dosen')
		->setCellValue('H1', 'Data Kelas')
		->setCellValue('H2', 'Kelas ID')
		->setCellValue('I2', 'Nama Kelas')
		->setCellValue('K1', 'Data Tahun Akademik')
		->setCellValue('K2', 'Tahun Akademik ID')
		->setCellValue('L2', 'Tahun Akademik')
		->setCellValue('M2', 'Status')
		->mergeCells('H1:I1')
		->mergeCells('E1:F1');

		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);

		$spreadsheet->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal('left');
		$spreadsheet->getActiveSheet()->getStyle('E')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal('left');
		$spreadsheet->getActiveSheet()->getStyle('H')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('H2')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal('left');
		$spreadsheet->getActiveSheet()->getStyle('K')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('K2')->getFont()->setBold(true);


		$x = 3;

		foreach ($getdosen as $get) {
			$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('E' . $x, $get->npp)
			->setCellValue('F' . $x, $get->nama);
			$x++;
		}

		$y = 3;

		foreach ($getkelas as $get) {
			$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('H' . $y, $get->kelasID)
			->setCellValue('I' . $y, $get->nama_kelas);
			$y++;
		}

		$z = 3;

		foreach ($getakademik as $get) {
			$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('K' . $z, $get->thnAkademikID)
			->setCellValue('L' . $z, $get->thnAkademik)
			->setCellValue('M' . $z, $get->status);
			$z++;
		}



		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('Simple');

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$spreadsheet->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Xlsx)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	public function upload()
	{
		$data = array();
		$data['title'] = 'Import Excel Sheet | TechArise';
		$data['breadcrumbs'] = array('Home' => '#');
		date_default_timezone_set('asia/jakarta');
		$code = 0;

		$this->form_validation->set_rules('fileURL', 'Upload File', 'callback_checkFileValidation');

		if ($this->form_validation->run() == false) {
			$code = 1;
			$notifications = validation_errors('<p>', '</p>');
		} else {
			if (!empty($_FILES['fileURL']['name'])) {
				$extension = pathinfo($_FILES['fileURL']['name'], PATHINFO_EXTENSION);
				if ($extension == 'csv') {
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
				} elseif ($extension == 'xlsx') {
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
				} else {
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
				}


				$spreadsheet = $reader->load($_FILES['fileURL']['tmp_name']);
				$allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

				$flag = true;
				$i = 0;
				$updatedata = array();
				//$primarydata = array();
				foreach ($allDataInSheet as $value) {
					if ($flag) {
						$flag = false;
						continue;
					}


					//$primarydata[$i]= $value['A'];
					$query = $this->dsnkelas->where('npp', $value['B'])->get();
					if (!empty($query)) {
						$updatedata[$i]['kelasID'] = $value['A'];
						$updatedata[$i]['npp'] = $value['B'];
						$updatedata[$i]['thnAkademikID'] = $value['C'];
						$updatedata[$i]['updated_at'] = date('Y-m-d H:i:s');
					} else {
						$insertdata[$i]['kelasID'] = $value['A'];
						$insertdata[$i]['npp'] = $value['B'];
						$insertdata[$i]['thnAkademikID'] = $value['C'];
						$insertdata[$i]['created_at'] = date('Y-m-d H:i:s');
					}


					//$data = array('hidden'=> form_hidden('aksi', !empty($row->mhsKelasID) ? 'update' : 'create'));

					$i++;
				}

				if (!empty($insertdata)) {
					$this->imp->setBatchImport($insertdata);
					$this->imp->importData('dosen_kelas');
				}

				if (!empty($updatedata)) {
					$this->imp->setBatchUpdate($updatedata);
					$this->imp->updateData('dosen_kelas', 'npp');
				}
			}
			$error =  $this->db->error();
			if ($error['code'] <> 0) {
				$code = 1;
				$notifications = $error['code'] . ' : ' . $error['message'];
			} else {
				$notifications = 'Success Import Data';
			}
		}
		$notifications = ($code == 0) ? notifications('success', $notifications) : notifications('error', $notifications);

		echo json_encode(array('message' => $notifications, 'code' => $code));
		//redirect('Import/import', 'refresh');
	}

	public function checkFileValidation($string)
	{
		$file_mimes = array(
			'text/x-comma-separated-values',
			'text/comma-separated-values',
			'application/octet-stream',
			'application/vnd.ms-excel',
			'application/x-csv',
			'text/x-csv',
			'text/csv',
			'application/csv',
			'application/excel',
			'application/vnd.msexcel',
			'text/plain',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		);
		if (isset($_FILES['fileURL']['name'])) {
			$arr_file = explode('.', $_FILES['fileURL']['name']);
			$extension = end($arr_file);
			if ($extension == 'xlsx' || $extension == 'xls' || $extension == 'csv' && in_array($_FILES['fileURL']['type'], $file_mimes)) {
				return true;
			} else {
				$this->form_validation->set_message('checkFileValidation', 'Please choose correct file');
				return false;
			}
		} else {
			$this->form_validation->set_message('checkFileValidation', 'Please choose a file');
			return false;
		}
	}
}
