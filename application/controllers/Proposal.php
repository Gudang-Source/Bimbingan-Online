<?php

defined('BASEPATH') or exit('No direct script access allowed');

require(FCPATH . 'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Proposal extends CI_Controller
{

	protected $page_header = 'Proposal Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Penelitian_model' => 'penelitian', 'Proposal_model' => 'proposal', 'Grouppesan_model' => 'grouppesan', 'Pesan_model' => 'pesan', 'Mahasiswa_model' => 'mahasiswa', 'Dosen_model' => 'dosen'));
		$this->load->library(array('ion_auth', 'form_validation', 'templatemahasiswa', 'pdf', 'pdf2'));
		$this->load->helper(array('bootstrap_helper','download'));
	}

	public function index()
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
			redirect('auth/login', 'refresh');
		}

		$where = array(
			'NIM' => $this->session->userdata('username'), 
			'Status' => 'On Proses'
		);

		$penelitian   = $this->penelitian->where($where)->get();

		$data['page_header']   = $this->page_header;
		$data['panel_heading'] = 'Proposal List';
		$data['page']         = '';
		$data['breadcrumb']         = 'Proposal';
		$data['penelitian']         = $penelitian;
		$data['input_cetak'] = '<input type="hidden" name="PenelitianID" value='.$penelitian->PenelitianID.'>';

		$this->templatemahasiswa->backend('proposal_v', $data);
	}

	public function get_proposal()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
			redirect('auth/login', 'refresh');
		}

		$list = $this->proposal->get_datatables();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) {
			$id = $field->ProposalID;

			$url_view   = 'view_data(' . $id . ');';
			$url_update = 'update_data(' . $id . ');';
			$url_delete = 'delete_data(' . $id . ');';

			// $url_download = force_download('assets/upload/'. $field->NamaFile.'.pdf', null);

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->NamaBAB;
			$row[] = '<p class="btn btn-info btn-sm">' . $field->Status . '</p>';
			$row[] = '<a href='. site_url('proposal/download/'. $field->NamaFile.'').' class="btn btn-danger btn-sm">Download<i class="fas fa-file-pdf ml-2"></i></a>';

			$data[] = $row;
		}

		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->proposal->count_rows(),
			"recordsFiltered" => $this->proposal->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function download($id)
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
			redirect('auth/login', 'refresh');
		}

		force_download('assets/upload/' . $id . '', null);
	}


	public function view()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
			redirect('auth/login', 'refresh');
		}

		$id = $this->input->post('CripsID');

		$query = $this->crips
			->with_kriteria('fields:NamaKriteria,KodeKriteria')
			->where('CripsID', $id)
			->get();

		$data = array();
		if ($query) {
			$data = array(
				'CripsID' => $query->CripsID,
				'KriteriaID' => $query->kriteria->KodeKriteria,
				'KriteriaID1' => $query->kriteria->NamaKriteria,
				'Crips' => $query->Crips,
				'Nilai' => $query->Nilai
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

		$where = array (
			'NIM' => $this->session->userdata('username'),
			'Status' => 'On Proses'
		);

		$penelitian   = $this->penelitian->where($where)->get();
		
		$opt_bab = array('BAB-1' => 'BAB-1', 'BAB-2' => 'BAB-2');

		$data = array(
			'Judul' => form_input(array('name' => 'Judul', 'id' => 'Judul', 'class' => 'form-control', 'value' => !empty($penelitian->Judul) ? $penelitian->Judul : '')),
			'Jenis' => form_input(array('name' => 'Jenis', 'id' => 'Jenis', 'class' => 'form-control', 'value' => !empty($penelitian->Jenis) ? $penelitian->Jenis : '')),
			'NamaBAB' => form_dropdown('NamaBAB', $opt_bab, '', 'class="form-control chosen-select"'),
			'NamaFile' => form_upload(array('name' => 'NamaFile', 'id' => 'NamaFile', 'class' => 'form-control', 'value' => ''))
		);

		echo json_encode($data);
	}

	public function save_proposal()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
			redirect('auth/login', 'refresh');
		}

		$rules = array(
			'insert' => array(

				array('field' => 'NamaBAB', 'label' => 'NamaBAB', 'rules' => 'trim|required|max_length[150]'),
				array('field' => 'NamaFile', 'label' => 'NamaFile', 'rules' => 'trim|xss_clean')
			)
		);

		$where = array(
			'NIM' => $this->session->userdata('username'),
			'Status' => 'On Proses'
		);

		$penelitian   = $this->penelitian->where($where)->get();

		$dosen = $penelitian->NPP;
		$mhs   = $this->session->userdata('username');
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
		$config['file_name']     = ''. $penelitian->NIM.'-' . $this->input->post('NamaBAB') . '-' . date('ymdhis') . '';
		// tambah nama file dengan idproposal idpenelitian nim

		$this->load->library('upload', $config);

		$data = array('upload_data' => $this->upload->data());
		
		$data1 = $this->upload->do_upload("NamaFile");

		$row = array(
			'ProposalID' => $this->input->post('ProposalID'),
			'PenelitianID' => $penelitian->PenelitianID,
			'NamaBAB' => $this->input->post('NamaBAB'),
			'Status' => 'Laporan Baru',
			'NamaFile' => $this->upload->data('orig_name'),
			'Info' => '1'
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
					
					$proposal   = $this->proposal->where('PenelitianID', $penelitian->PenelitianID)->get_all();

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

	public function delete()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
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

	public function laporan_pdf()
    {

    	if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		}

        $where = array(
			'NIM' => $this->session->userdata('username'), 
			'Status' => 'On Proses'
		);

		$query   = $this->penelitian->where($where)->get();
        $mahasiswa = $this->mahasiswa->where('NIM', $query->NIM)->get();
        $dosen = $this->dosen->where('NPP', $query->NPP)->get();
        $query1 = $this->proposal->where('PenelitianID', $query->PenelitianID)->get_all();

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
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
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
