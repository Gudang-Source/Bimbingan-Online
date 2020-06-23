<?php

defined('BASEPATH') or exit('No direct script access allowed');

require(FCPATH . 'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Dosen extends CI_Controller
{

	protected $page_header = 'Dosen Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Dosen_model' => 'dosen'));
		$this->load->library(array('ion_auth', 'form_validation', 'template'));
		$this->load->helper('bootstrap_helper');
	}

	public function index()
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}

		$data['page_header']   = $this->page_header;
		$data['panel_heading'] = 'Dosen List';
		$data['page']         = '';
		$data['breadcrumb']         = 'Dosen';

		$this->template->backend('dosen_v', $data);
	}

	public function get_dosen()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}

		$list = $this->dosen->get_datatables();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) {
			$id = $field->NPP;

			$url_view   = 'view_data(' . $id . ');';
			$url_update = 'update_data(' . $id . ');';
			$url_delete = 'delete_data(' . $id . ');';

			$no++;
			$row = array();
			$row[] = ajax_button($url_view, $url_update, $url_delete);
			$row[] = $no;
			$row[] = $field->NPP;
			$row[] = $field->Nama;
			$row[] = $field->Jabatan;

			$data[] = $row;
		}

		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->dosen->count_rows(),
			"recordsFiltered" => $this->dosen->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function view()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}

		$id = $this->input->post('NPP');

		$query = $this->dosen
			->where('NPP', $id)
			->get();

		$data = array();
		if ($query) {
			$data = array(
				'NPP' => $query->NPP,
				'Nama' => $query->Nama,
				'Jabatan' => $query->Jabatan
			);
		}

		echo json_encode($data);
	}

	public function form_data()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}

		//   $opt_KodeKriteria     = $this->kriteria->as_dropdown('KodeKriteria')->get_all();
		//   $opt_NamaKriteria     = $this->kriteria->as_dropdown('NamaKriteria')->get_all();
		//   $opt = $this->kriteria->as_dropdown('NamaKriteria')->get_all();

		$opt_jabatan = array('Dosen' => 'Dosen', 'Kaprodi Sistem Informasi' => 'Kaprodi Sistem Informasi', 'Kaprodi Teknik Informatika' => 'Kaprodi Teknik Informatika');

		$row = array();
		if ($this->input->post('NPP')) {
			$id      = $this->input->post('NPP');
			$query   = $this->dosen->where('NPP', $id)->get();
			if ($query) {
				$row = array(
					'NPP'       => $query->NPP,
					'Nama'     => $query->Nama,
					'Jabatan'      => $query->Jabatan
				);
			}
			$row = (object) $row;
		}

		$data = array(
			'hidden' => form_hidden('aksi', !empty($row->NPP) ? 'update' : 'create'),
			'NPP' => form_input(array('name' => 'NPP', 'id' => 'NPP', 'class' => 'form-control', 'value' => !empty($row->NPP) ? $row->NPP : '')),
			'Nama' => form_input(array('name' => 'Nama', 'id' => 'Nama', 'class' => 'form-control', 'value' => !empty($row->Nama) ? $row->Nama : '')),
			'Jabatan' => form_dropdown('Jabatan', $opt_jabatan, !empty($row->Jabatan) ? $row->Jabatan : '', 'class="form-control chosen-select"')
		);

		echo json_encode($data);
	}

	public function save_dosen()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}

		$rules = array(
			'insert' => array(

				array('field' => 'NPP', 'label' => 'NPP', 'rules' => 'trim|required|is_unique[dosen.NPP]|max_length[20]'),
				array('field' => 'Nama', 'label' => 'Nama', 'rules' => 'trim|required|max_length[150]'),
				array('field' => 'Jabatan', 'label' => 'Jabatan', 'rules' => 'trim|required|max_length[150]'),
			),
			'update' => array(
				array('field' => 'NPP', 'label' => 'NPP', 'rules' => 'trim|required|max_length[8]'),
				array('field' => 'Nama', 'label' => 'Nama', 'rules' => 'trim|required|max_length[150]'),
				array('field' => 'Jabatan', 'label' => 'Jabatan', 'rules' => 'trim|required|max_length[150]'),
			)
		);

		$row = array(
			'NPP' => $this->input->post('NPP'),
			'Nama' => $this->input->post('Nama'),
			'Jabatan' => $this->input->post('Jabatan')
		);


		$code = 0;

		if ($this->input->post('aksi') == 'create') {

			$this->form_validation->set_rules($rules['insert']);

			if ($this->form_validation->run() == true) {

				$this->dosen->insert($row);

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

				$id = $this->input->post('NPP');

				$this->dosen->where('NPP', $id)->update($row);

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
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}

		$code = 0;

		$id = $this->input->post('NPP');

		$this->dosen->where('NPP', $id)->delete();

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
