<?php

defined('BASEPATH') or exit('No direct script access allowed');

require(FCPATH . 'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Bimbingandosen extends CI_Controller
{

	protected $page_header = 'Daftar Bimbingan Mahasiswa';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Mahasiswa_model' => 'mahasiswa', 'Bimbingan_model' => 'penelitian', 'Dosen_model' => 'dosen', 'Proposal_model' => 'proposal', 'Grouppesan_model' => 'grouppesan', 'Penelitian_model' => 'penelitian', 'Pesan_model' => 'pesan', 'Tahunakademik_model'=>'tahun'));
		$this->load->library(array('ion_auth', 'form_validation', 'templatedosen', 'pdf', 'pdf2'));
		$this->load->helper(array('bootstrap_helper','download'));
	}

	public function index()
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

		$query = $this->tahun->as_object()->get_all();

		foreach($query as $row)
		{
			$data1[$row->TahunAkademikID] = $row->TahunAkademik;
		}

		arsort($data1);

		$tahunakademik = $this->tahun->where('Status', 1)->get();

		$penelitian = $this->penelitian->where('NPP', $this->session->userdata('username'))->get_all();

		$data['page_header']   = $this->page_header;
		$data['panel_heading'] = 'Daftar Bimbingan';
		$data['page']         = '';
		$data['breadcrumb']         = 'Bimbingan';
		$data['bimbingan']         = $penelitian;
		$data['tahunakademik']         = $tahunakademik;
		$data['opt_tahun']         = $data1;

		$this->templatedosen->backend('bimbingandosen_v', $data);
	}

	public function download($id)
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

		force_download('assets/upload/' . $id . '', null);
	}

	public function get_penelitian()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

		$list = $this->penelitian->get_datatables();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) {
			$id = $field->PenelitianID;

			$url_view   = 'view_data(' . $id . ');';
			
			$query   = $this->mahasiswa->where('NIM', $field->NIM)->get();
			$query1  = $this->dosen->where('NPP', $field->NPP)->get();

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->NIM;
			$row[] = $query->Nama;
			$row[] = $field->Jenis;
			$row[] = $field->Judul;
			if ($field->Status == 'On Proses') {
				$row[] = '<button id="view" class="btn btn-warning text-white btn-sm btn-circle" onClick="' . $url_view . '" title="View Data" alt="View Data">' . $field->Status . '<i class="fas fa-eye ml-2"></i></button>';
			} else {
				$row[] = '<button id="view" class="btn btn-success text-white btn-sm btn-circle" onClick="' . $url_view . '" title="View Data" alt="View Data">' . $field->Status . '<i class="fas fa-eye ml-2"></i></button>';
			}
			$row[] = '<button type="button" name="id" class="btn btn-primary btn-sm" onClick="view_data1(' . $id . ');">List Laporan<i class="fas fa-ellipsis-v ml-2"></i></button>';

			$data[] = $row;
		}

		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->penelitian->count_rows(),
			"recordsFiltered" => $this->penelitian->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function view()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

		$id = $this->input->post('PenelitianID');

		$query = $this->penelitian
			->with_mahasiswa('fields:Nama')
			->with_dosen('fields:Nama')
			->where('PenelitianID', $id)
			->get();

		$data = array();
		if ($query) {
			$data = array(
				'NIM_BIM' => $query->NIM,
				'NIM1_BIM' => $query->mahasiswa->Nama,
				'NPP_BIM' => $query->dosen->Nama,
				'Jenis_BIM' => $query->Jenis,
				'Judul_BIM' => $query->Judul,
				'Status_BIM' => $query->Status
			);
		}

		echo json_encode($data);
	}

	public function form_upload()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

		$id = $this->input->post('PenelitianID');

		$query = $this->penelitian
			->with_mahasiswa('fields:Nama')
			->where('PenelitianID', $id)
			->get();

		$data = array();
		if ($query) {
			$data = array(
				'Nama' => $query->mahasiswa->Nama,
				'Jen' => $query->Jenis,
				'Jud' => $query->Judul,
			);
		}

		$data = array(
			'hidden' => form_hidden('PenelitianID', !empty($id) ? $id : ''),
			'Nama' => form_input(array('name' => 'Nama', 'id' => 'Nama', 'class' => 'form-control', 'value' => !empty($query->mahasiswa->Nama) ? $query->mahasiswa->Nama : '')),
			'Judul' => form_input(array('name' => 'Judul', 'id' => 'Judul', 'class' => 'form-control', 'value' => !empty($query->Judul) ? $query->Judul : '')),
			'Jenis' => form_input(array('name' => 'Jenis', 'id' => 'Jenis', 'class' => 'form-control', 'value' => !empty($query->Jenis) ? $query->Jenis : '')),
			'NamaFile' => form_upload(array('name' => 'NamaFile', 'id' => 'NamaFile', 'class' => 'form-control', 'value' => ''))
		);

		echo json_encode($data);
	}

	public function save_proposal()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

		$rules = array(
			'insert' => array(

				array('field' => 'NamaFile', 'label' => 'NamaFile', 'rules' => 'trim|xss_clean')
			)
		);

		$id = $this->input->post('PenelitianID');

		$penelitian   = $this->penelitian->where('PenelitianID', $id)->get();

		$dosen = $penelitian->NPP;
		$mhs   = $penelitian->NIM;
		$query = $this->grouppesan->as_object()->get_all();

		if(!empty($query))
		{
			foreach($query as $value)
			{
				if ($value->Name1 == $dosen && $value->Name2 == $mhs) 
				{
					$where1 = array(
						'Name1' => $dosen,
						'Name2' => $mhs
					);
					$group = $this->grouppesan->where($where1)->get();
					// $d = 'aku';
					break;

				}
				elseif ($value->Name1 == $mhs && $value->Name2 == $dosen) 
				{
					$where1 = array(
						'Name1' => $mhs,
						'Name2' => $dosen
					);
					$group = $this->grouppesan->where($where1)->get();
					break;
				} else {
					$group = 0;
				}
			}
		} else {
			$group = 0;
		}

		

		if ($group == null) 
		{
			$ins = array(
				'Name1' => $mhs,
				'Name2' => $dosen
			);

			$this->grouppesan->insert($ins);

			$where1 = array(
				'Name1' => $mhs,
				'Name2' => $dosen
			);
			$group = $this->grouppesan->where($where1)->get();
		}

		$config['upload_path']   = "./assets/upload";
		$config['allowed_types'] = 'pdf|docx';
		$config['file_name']     = ''. $penelitian->NPP.'-Revisi-' . date('ymdhis') . '';
		// tambah nama file dengan idproposal idpenelitian nim

		$this->load->library('upload', $config);

		$data = array('upload_data' => $this->upload->data());

		$data1 = $this->upload->do_upload("NamaFile");
		
		$row = array(
			'PenelitianID' => $id,
			'NamaBAB' => 'Revisi',
			'Status' => 'Revisi',
			'NamaFile' => $this->upload->data('orig_name')
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
					
					$proposal   = $this->proposal->where('PenelitianID', $id)->get_all();

					foreach ($proposal as $key)
					{
						$d = $key;
					}

					$row1 = array(
						'GroupPesanID' => $group->GroupPesanID,
						'PesanID' => $this->input->post('PesanID'),
						'Name' => $this->session->userdata('username'),
						'ProposalID' => $d->ProposalID
					);

					$this->pesan->insert($row1);
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

	public function selesai()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

		$id = $this->input->post('PenelitianID');

		$query = $this->penelitian->where('PenelitianID', $id)->get();
		$query1 = $this->proposal->where('PenelitianID', $id)->get_all();

		$row = array(
			'Status' => 'Selesai'
		);

		$jumlah = count((array)$query1);

		$code = 0;

		if($jumlah >= 12)
		{
			if(!empty($this->input->post('PenelitianID')))
			{
				$this->penelitian->where('PenelitianID', $id)->update($row);
				$title = 'Insert!';
				$notifications = 'Success Insert Data';
			} 
			else 
			{
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
			$notifications = 'Bimbingan penelitian minimal 12 kali bimbingan';
		}

		

		if ($code == 0) {
			$notif = json_encode(array('icon' => 'success', 'title' => $title, 'message' => $notifications, 'code' => $code));
		} else {
			$notif = json_encode(array('icon' => 'warning', 'title' => $title, 'message' => $notifications, 'code' => $code));
		}

		echo $notif;


	}

	public function form()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

		$data = array();

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

				$this->table->add_row($noo, $row->NamaBAB, $row->Keterangan, '<p class="btn btn-info btn-sm">' . $row->Status . '</p>', '<a href=' . site_url('kaprodi/bimbingan/download/' . $row->NamaFile . '') . ' class="btn btn-danger btn-sm">Download<i class="fas fa-file-pdf ml-2"></i></a>');
			}

			$proposal = $this->table->generate();

			$data['proposal'] = $proposal;

		} else {

			$data['proposal'] = '<h2 class="text-center">Data Proposal Belum Ada</h2>';
		}

		$query2 = $this->penelitian
				->with_mahasiswa('fields:Nama, Prodi')
				->with_dosen('fields:Nama')
				->where('PenelitianID', $id)
				->get();

		$id1 = $query2->PenelitianID;

		$url_view = 'action_selesai(' . $id1 . ');';

		if ($query2) {
			$data['NIM'] = $query2->NIM;
			$data['NIM1'] = $query2->mahasiswa->Nama;
			$data['NIM2'] = $query2->mahasiswa->Prodi;
			$data['NPP'] = $query2->dosen->Nama;
			$data['Jenis'] = $query2->Jenis;
			$data['Judul'] = $query2->Judul;
			if($query2->Status == 'On Proses')
			{
				$data['Status'] = '<p class="btn btn-warning btn-sm text-white">'.$query2->Status.'</p>';
			} 
			elseif($query2->Status == 'Selesai')
			{
				$data['Status'] = '<p class="btn btn-success btn-sm text-white">'.$query2->Status.'</p>';
			}
			$data['button'] = '<button type="button" id="selesai" class="btn btn-info btn-sm btn-circle" onClick="' . $url_view . '">Bimbingan Selesai<i class="fas fa-check ml-2"></i></button>';
			$data['input'] = '<input type="hidden" name="PenelitianID" value='.$query2->PenelitianID.'>';
			$data['input_cetak'] = '<input type="hidden" name="PenelitianID" value='.$query2->PenelitianID.'>';
			$data['upload'] = '<button type="button" name="upload" class="btn btn-primary btn-sm" onClick="form1(' . $id . ');">Upload Revisi<i class="fas fa-upload ml-2"></i></button>';
		}

		echo json_encode($data);
	}

	public function laporan_pdf()
    {

    	if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

        $id = $this->input->post('PenelitianID');
        $query = $this->penelitian->where('PenelitianID', $id)->get();
        $mahasiswa = $this->mahasiswa->where('NIM', $query->NIM)->get();
        $dosen = $this->dosen->where('NPP', $query->NPP)->get();
        $query1 = $this->proposal->where('PenelitianID', $id)->get_all();

        // echo "<pre>";
        // print_r($query);
        // exit();

        $data = array(
            'penelitian' => $query,
            'proposal' => $query1,
            'mahasiswa' =>$mahasiswa,
            'dosen' =>$dosen,
        );

        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "Laporan Bimbingan '$mahasiswa->Nama'.pdf";
        $this->pdf->load_view('laporan_pdf', $data);

    }

    public function cetak()
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

		$id = $this->input->post('PenelitianID');
        $query = $this->penelitian->where('PenelitianID', $id)->get();
        $mahasiswa = $this->mahasiswa->where('NIM', $query->NIM)->get();
        $dosen = $this->dosen->where('NPP', $query->NPP)->get();
        $query1 = $this->proposal->where('PenelitianID', $id)->get_all();

		$pdf = new FPDF('P', 'mm', 'A4'); //L = lanscape P= potrait
		// membuat halaman baru
		$pdf->AddPage();
		
		$pdf->Image('assets/img/kop.png',10,10,190);
		$pdf->SetFont('Arial', 'B', 24);
		$pdf->Cell(15, 7, '', 0, 1);
		$pdf->SetFont('Arial', '', 10);
		$pdf->Cell(15, 7, '', 0, 1);
		$pdf->SetFont('Arial', '', 10);
		$pdf->Cell(15, 7, '', 0, 1);
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10, 7, '', 0, 1);

		$pdf->SetFont('Arial', 'B', 14);
		// $pdf->SetMargins(30, 30, 30);
		$pdf->Cell(190, 3, '', 0, 1);
		$pdf->Cell(190, 6.5, 'Laporan Bimbingan Penelitian', 0, 1, 'C');
		$pdf->Cell(190, 3, '', 0, 1);
		
		$pdf->SetFont('Arial', '', 11);

		$pdf->SetX(15);
		$pdf->Cell(15, 8, 'Nama Mahasiswa', 0, 0);

		$pdf->SetX(50);
		$pdf->Cell(0, 8, ':', 0, 0);

		$pdf->SetX(53);
		$pdf->Cell(0, 8, $mahasiswa->Nama, 0, 1);

		$pdf->Cell(190, 3, '', 0, 1);

		$pdf->SetX(15);
		$pdf->Cell(15, 8, 'NIM', 0, 0);

		$pdf->SetX(50);
		$pdf->Cell(0, 8, ':', 0, 0);

		$pdf->SetX(53);
		$pdf->Cell(0, 8, $query->NIM, 0, 1);

		$pdf->Cell(190, 3, '', 0, 1);

		$pdf->SetX(15);
		$pdf->Cell(15, 8, 'Judul Penelitian', 0, 0);

		$pdf->SetX(50);
		$pdf->Cell(0, 8, ':', 0, 0);

		$teks = $query->Judul;

		$panjang = strlen($teks);

		if($panjang <= 76)
		{
			$pdf->SetX(53);
			$pdf->Cell(0, 8, $teks, 0, 1);
		} else {
			$pisah1 = explode(" ", $teks);

			$potong = substr($teks, 0, 76);

			$pisah = explode(" ", $potong);

			$jumlah = count($pisah) - 2;

			$jml = count($pisah1) - 1;

			if($panjang <= 152)
			{
				for($i= 0; $i <= $jumlah; $i++)
				{
					$data[] = " ".$pisah[$i]."";
				}

				for($i= $jumlah + 1; $i <= $jml; $i++)
				{
					$data1[] = " ".$pisah1[$i]."";
				}

				$pdf->SetX(53);
				$pdf->Cell(0, 8, implode(" ", $data), 0, 1);

				$pdf->SetX(53);
				$pdf->Cell(0, 8, implode(" ", $data1), 0, 1);

			} else {
				$potong1 = substr($teks, 0, 152);

				$pisah2 = explode(" ", $potong1);

				$jumlah1 = count($pisah2) - 2;

				for($i= 0; $i <= $jumlah; $i++)
				{
					$data[] = " ".$pisah1[$i]."";
				}

				for($i= $jumlah + 1; $i <= $jumlah1; $i++)
				{
					$data1[] = " ".$pisah1[$i]."";
				}

				for($i= $jumlah1 + 1; $i <= $jml; $i++)
				{
					$data2[] = " ".$pisah1[$i]."";
				}

				$pdf->SetX(53);
				$pdf->Cell(0, 8, implode(" ", $data), 0, 1);

				$pdf->SetX(53);
				$pdf->Cell(0, 8, implode(" ", $data1), 0, 1);

				$pdf->SetX(53);
				$pdf->Cell(0, 8, implode(" ", $data2), 0, 1);
			}   
		}

		$pdf->Cell(190, 3, '', 0, 1);

		$pdf->SetX(15);
		$pdf->Cell(15, 8, 'Jenis Penelitian', 0, 0);

		$pdf->SetX(50);
		$pdf->Cell(0, 8, ':', 0, 0);

		$pdf->SetX(53);
		$pdf->Cell(0, 8, $query->Jenis, 0, 1);

		$pdf->Cell(190, 3, '', 0, 1);

		$pdf->SetX(15);
		$pdf->Cell(15, 8, 'Dosen Pembimbing', 0, 0);

		$pdf->SetX(50);
		$pdf->Cell(0, 8, ':', 0, 0);

		$pdf->SetX(53);
		$pdf->Cell(0, 8, $dosen->Nama, 0, 1);

		$pdf->Cell(120, 8, '', 0, 1);

		$pdf->SetFont('Arial', 'B', 11);

		$pdf->SetWidths(array(15,50,35,90));

		$pdf->Row(array('No', 'Tanggal', 'BAB Laporan', 'Catatan'));

		$pdf->SetFont('Arial', '', 11);

		// $pdf->Row(array('1', 'Jumat, 19 Juni 2020 06:38', 'Revisi', 'Catatan'));

		if(!empty($query1))
		{
			$no = 1;
			foreach($query1 as $row)
			{
				$pdf->Row(array($no++, format_indo($row->created_at), $row->NamaBAB, $row->Keterangan));
			}
		}


		$pdf->Output("Laporan Bimbingan-".$mahasiswa->NIM.".pdf","D");

	}
	
}
