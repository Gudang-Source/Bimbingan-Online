<?php

defined('BASEPATH') or exit('No direct script access allowed');

require(FCPATH . 'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Proposaldosen extends CI_Controller
{

	protected $page_header = 'Proposal Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Penelitian_model' => 'penelitian', 'Proposal_model' => 'proposal', 'Mahasiswa_model' => 'mahasiswa', 'Dosen_model' => 'dosen', 'Bimbingan_model' => 'bimbingan'));
		$this->load->library(array('ion_auth', 'form_validation', 'templatekaprodi'));
		$this->load->helper(array('bootstrap_helper','download'));
	}

	public function index()
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		}

		$data['page_header']   = $this->page_header;
		$data['panel_heading'] = 'Proposal List';
		$data['page']         = '';
		$data['breadcrumb']         = 'Proposal';

		$this->templatekaprodi->backend('proposaldosen_v', $data);
	}

	public function get_proposal()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		}

		$list = $this->bimbingan->get_datatables();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) {
			$id = $field->PenelitianID;

			$url_view   = 'view_data(' . $id . ');';
			$url_update = 'update_data(' . $id . ');';
			$url_delete = 'delete_data(' . $id . ');';

			$query   = $this->mahasiswa->where('NIM', $field->NIM)->get();
			$query1  = $this->dosen->where('NPP', $field->NPP)->get();

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->NIM;
			$row[] = $query->Nama;
			$row[] = $query1->Nama;
			$row[] = $field->Jenis;
			$row[] = $field->Judul;
			$row[] = '<button type="button" name="id" class="btn btn-primary btn-sm" onClick="view_data('.$id.');">List Laporan<i class="fas fa-eye ml-2"></i></button>';

			$data[] = $row;
		}

		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->bimbingan->count_rows(),
			"recordsFiltered" => $this->bimbingan->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function download($id)
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		}

		force_download('assets/upload/' . $id . '.pdf', null);
	}


	public function view()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		}

		$id = $this->input->post('PenelitianID');

		$query = $this->proposal->where('PenelitianID', $id)->get_all();

		if ($query) {
			set_table(true);

			$No = array(
				'data' => 'No',
			);
			$NamaBAB = array(
				'data' => 'Nama BAB Laporan'
			);
			$Keterangan = array(
				'data' => 'Keterangan',
			);
			$Status = array(
				'data' => 'Status',
			);
			$File = array(
				'data' => 'File Laporan',
			);

			$this->table->set_heading($No, $NamaBAB, $Keterangan, $Status, $File);

			$no = 1;

			foreach ($query as $row) {
				
				$noo = array(
					'data' => $no++,
					
				);

				$this->table->add_row($noo, $row->NamaBAB, $row->Keterangan, '<p class="btn btn-info btn-sm">' . $row->Status . '</p>', '<a href=' . site_url('proposaldosen/download/' . $row->NamaFile . '') . ' class="btn btn-danger btn-sm">Download</a>');
			}

			$proposal = $this->table->generate();

			$data = array(
				
				'proposal' => $proposal
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

		$where = array (
			'NIM' => $this->session->userdata('username'),
			'Status' => 'On Proses'
		);

		$penelitian   = $this->penelitian->where($where)->get();
		
		$opt_bab = array('BAB-1' => 'BAB-1', 'BAB-2' => 'BAB-2');

		$row = array();
		if ($this->input->post('CripsID')) {
			$id      = $this->input->post('CripsID');
			$query   = $this->proposal->where('CripsID', $id)->get();
			if ($query) {
				$row = array(
					'ProposalID'       => $query->ProposalID,
					'NamaBAB'     => $query->NamaBAB,
					'Keterangan'      => $query->Keterangan,
					'NamaFile'      => $query->NamaFile
				);
			}
			$row = (object) $row;
		}

		$data = array(
			'hidden' => form_hidden('ProposalID', !empty($row->ProposalID) ? $row->ProposalID : ''),
			'Judul' => form_input(array('name' => 'Judul', 'id' => 'Judul', 'class' => 'form-control', 'value' => !empty($penelitian->Judul) ? $penelitian->Judul : '')),
			'Jenis' => form_input(array('name' => 'Jenis', 'id' => 'Jenis', 'class' => 'form-control', 'value' => !empty($penelitian->Jenis) ? $penelitian->Jenis : '')),
			'NamaBAB' => form_dropdown('NamaBAB', $opt_bab, !empty($row->NamaBAB) ? $row->NamaBAB : '', 'class="form-control chosen-select"'),
			'Keterangan' => form_textarea(array('name' => 'Keterangan', 'id' => 'Keterangan', 'class' => 'form-control', 'value' => !empty($row->Keterangan) ? $row->Keterangan : '', 'rows' => 3)),
			'NamaFile' => form_upload(array('name' => 'NamaFile', 'id' => 'NamaFile', 'class' => 'form-control', 'value' => ''))
		);

		echo json_encode($data);
	}

	public function save_proposal()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		}

		$rules = array(
			'insert' => array(

				array('field' => 'NamaBAB', 'label' => 'NamaBAB', 'rules' => 'trim|required|max_length[150]'),
				array('field' => 'Keterangan', 'label' => 'Keterangan', 'rules' => 'trim|required|max_length[150]'),
				array('field' => 'NamaFile', 'label' => 'NamaFile', 'rules' => 'trim|xss_clean')
			)
		);

		$where = array(
			'NIM' => $this->session->userdata('username'),
			'Status' => 'On Proses'
		);

		$penelitian   = $this->penelitian->where($where)->get();

		$config['upload_path'] = "./assets/upload";
		$config['allowed_types'] = 'pdf';
		$config['file_name'] = ''. $penelitian->NIM.'-' . $this->input->post('NamaBAB') . '-' . date('ymdhis') . '';
		// tambah nama file dengan idproposal idpenelitian nim

		$this->load->library('upload', $config);

		$data = array('upload_data' => $this->upload->data());

		$row = array(
			'ProposalID' => $this->input->post('ProposalID'),
			'PenelitianID' => $penelitian->PenelitianID,
			'NamaBAB' => $this->input->post('NamaBAB'),
			'Keterangan' => $this->input->post('Keterangan'),
			'Status' => 'Laporan Baru',
			'NamaFile' => $data['upload_data']['file_name']
		);


		$code = 0;

		if ($this->input->post('ProposalID') == null) {

			$this->form_validation->set_rules($rules['insert']);

			if ($this->form_validation->run() == true) {

				if (!$this->upload->do_upload("NamaFile")) {
					$error = array('error' => $this->upload->display_errors());
					$code = 1;
					$title = 'Warning!';
					$notifications = $this->upload->display_errors(' ', ' ');
				} else {
					$this->proposal->insert($row);
					$this->upload->data("file_name");
					
					$title = 'Insert!';
					$notifications = 'Success Insert Data';
				}
				
				$error =  $this->db->error();
				if ($error['code'] <> 0) {
					$code = 1;
					$title = 'Warning!';
					$notifications = $error['code'] . ' : ' . $error['message'];
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
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		}

		$code = 0;

		$id = $this->input->post('CripsID');

		$this->crips->where('CripsID', $id)->delete();

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
