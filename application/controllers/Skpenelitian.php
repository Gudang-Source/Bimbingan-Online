<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Skpenelitian extends CI_Controller
{

	protected $page_header = 'Form Sk Penelitian';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Skpenelitian_model' => 'skpenelitian', 'Mahasiswa_model' => 'mahasiswa', 'Dosen_model' => 'dosen', 'Bimbingan_model' => 'bimbingan', 'Penelitian_model' => 'penelitian', 'Tahunakademik_model'=>'tahun'));
		$this->load->library(array('ion_auth', 'form_validation', 'templatekaprodi', 'pdf', 'pdf2'));
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
		$query1 = $this->skpenelitian->as_object()->get_all();

		foreach($query as $row)
		{
			$data1[$row->TahunAkademikID] = $row->TahunAkademik;
		}

		arsort($data1);

		$tahunakademik = $this->tahun->where('Status', 1)->get();

		if(!empty($query1))
		{
			foreach($query1 as $row)
			{
				if($this->ion_auth->in_group('kaprodi_TI'))
				{
					$where = array(
						'Jenis' => $row->Jenis,
						'Prodi' => 'Teknik Informatika');
				}
				if($this->ion_auth->in_group('kaprodi_SI'))
				{
					$where = array(
						'Jenis' => $row->Jenis,
						'Prodi' => 'Sistem Informasi');
				}
			}
		}	

		
		if(!empty($where))
		{
			$jenis = $this->skpenelitian->where($where)->get();	
		} else {
			$jenis = '';
		}
		

		$data['page_header']   = $this->page_header;
		$data['panel_heading'] = 'Pengajuan List';
		$data['page']          = '';
		$data['breadcrumb']    = 'Sk Penelitian';
		$data['tahunakademik'] = $tahunakademik;
		$data['opt_tahun']     = $data1;
		$data['Jenis']     = $jenis;

		$this->templatekaprodi->backend('skpenelitian_v', $data);
	}

	public function get_sk()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$list = $this->skpenelitian->get_datatables();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) { 
			$query = $this->mahasiswa->where('NIM', $field->NIM)->get();
			$query1 = $this->dosen->where('NPP', $field->NPP)->get();

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->NomerSK;
			$row[] = $query->Nama;
			$row[] = $query1->Nama;

			$data[] = $row;
		}
		
		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->skpenelitian->count_rows(),
			"recordsFiltered" => $this->skpenelitian->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function filter()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$query1 = $this->skpenelitian->as_object()->get_all();

		if(!empty($query1))
		{
			foreach($query1 as $row)
			{
				if($this->ion_auth->in_group('kaprodi_TI'))
				{
					$where = array(
						'Jenis' => $row->Jenis,
						'Prodi' => 'Teknik Informatika');
				}
				if($this->ion_auth->in_group('kaprodi_SI'))
				{
					$where = array(
						'Jenis' => $row->Jenis,
						'Prodi' => 'Sistem Informasi');
				}
			}

			if(!empty($where))
			{
				$code = 0;
				echo json_encode(array('code' => $code));
			} else {
				$code = 1;
				$title = 'Warning!';
				$notifications = 'Maaf Data Sk Penelitian Belum Ada .';

				echo json_encode(array('icon' => 'error', 'title' => $title, 'message' => $notifications, 'code' => $code));
			}
		} else {
			$code = 1;
			$title = 'Warning!';
			$notifications = 'Maaf Data Sk Penelitian Belum Ada .';

			echo json_encode(array('icon' => 'error', 'title' => $title, 'message' => $notifications, 'code' => $code));
		}
	}

	public function form_kp()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$where = array(
			'Status' => 'On Proses',
			'Jenis' => 'Kerja Praktek'
		);

		$query   = $this->penelitian->where($where)->get_all();

		if(!empty($query))
		{
			if($this->ion_auth->in_group('kaprodi_TI'))
			{
				foreach($query as $value)
				{
					$where1 = array(
						'NIM' => $value->NIM,
						'Prodi' => 'Teknik Informatika'
					);

					$query1 = $this->mahasiswa->where($where1)->get();
					
					if(!empty($query1))
					{
						$query2 = $this->penelitian->where('NIM', $query1->NIM)->get();
						// $data['PenelitianID'] = $query2->PenelitianID;
						// $data['NomerSK'] = $id;
						$data1[] = '
						<div class="card px-2 py-2 mx-4 mt-2">
						<p class="h6 font-weight-bold">'.$query2->NIM.' - '.$query1->Nama.'</p>
						<p class="card-text">'.$query2->Judul.'</p>
						</div>
						';
						$NomerSK = form_input(array('name' => 'NomerSK', 'id' => 'NomerSK', 'class' => 'form-control', 'value' => ''));
					}
					
				}
				if(empty($data1))
				{
					$data1 = '';
					$NomerSK = '';
					$kosong = '
					<div class="card-body col-12 text-center" style="height:40vh;">
					<h5>SK Penelitian Belum Bisa Dibuat</h5>
					<button type="button" name="back" class="btn btn-primary btn-sm mt-3" onClick="table_data();">Back Button</button>
					</div>';
				}
			}
			elseif ($this->ion_auth->in_group('kaprodi_SI')) 
			{
				foreach($query as $value)
				{
					$where1 = array(
						'NIM' => $value->NIM,
						'Prodi' => 'Sistem Informasi'
					);

					$query1 = $this->mahasiswa->where($where1)->get();

					if(!empty($query1))
					{
						$query2 = $this->penelitian->where('NIM', $query1->NIM)->get();
						// $data['PenelitianID'] = $query2->PenelitianID;
						// $data['NomerSK'] = $id;
						$data1[] = '
						<div class="card px-2 py-2 mx-4 mt-2">
						<p class="h6 font-weight-bold">'.$query2->NIM.' - '.$query1->Nama.'</p>
						<p class="card-text">'.$query2->Judul.'</p>
						</div>
						';
						$NomerSK = form_input(array('name' => 'NomerSK', 'id' => 'NomerSK', 'class' => 'form-control', 'value' => ''));
					}
					
				}

				if(empty($data1))
				{
					$data1 = '';
					$NomerSK = '';
					$kosong = '
					<div class="card-body col-12 text-center" style="height:40vh;">
					<h5>SK Penelitian Belum Bisa Dibuat</h5>
					<button type="button" name="back" class="btn btn-primary btn-sm mt-3" onClick="table_data();">Back Button</button>
					</div>';
				}
			}

			if(empty($kosong))
			{
				$data = array(
					'NomerSK' => $NomerSK,
					'daftar' => $data1
					
				);
			} else {
				$data = array(
					'NomerSK' => $NomerSK,
					'daftar' => $data1,
					'kosong' => $kosong
				);
			}
			

		} else {
			$data = array(
				'kosong' => '
				<div class="card-body col-12 text-center" style="height:40vh;">
				<h5>SK Penelitian Belum Bisa Dibuat</h5>
				<button type="button" name="back" class="btn btn-primary btn-sm mt-3" onClick="table_data();">Back Button</button>
				</div>'
			);
		}	

		echo json_encode($data);
	}

	public function form_skripsi()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$where = array(
			'Status' => 'On Proses',
			'Jenis' => 'Skripsi'
		);

		$query   = $this->penelitian->where($where)->get_all();

		if(!empty($query))
		{
			if($this->ion_auth->in_group('kaprodi_TI'))
			{
				foreach($query as $value)
				{
					$where1 = array(
						'NIM' => $value->NIM,
						'Prodi' => 'Teknik Informatika'
					);

					$query1 = $this->mahasiswa->where($where1)->get();
					
					if(!empty($query1))
					{
						$query2 = $this->penelitian->where('NIM', $query1->NIM)->get();
						// $data['PenelitianID'] = $query2->PenelitianID;
						// $data['NomerSK'] = $id;
						$data1[] = '
						<div class="card px-2 py-2 mx-4 mt-2">
						<p class="h6 font-weight-bold">'.$query2->NIM.' - '.$query1->Nama.'</p>
						<p class="card-text">'.$query2->Judul.'</p>
						</div>
						';
						$NomerSK = form_input(array('name' => 'NomerSK', 'id' => 'NomerSK', 'class' => 'form-control', 'value' => ''));
					}
					
				}
				if(empty($data1))
				{
					$data1 = '';
					$NomerSK = '';
					$kosong = '
					<div class="card-body col-12 text-center" style="height:40vh;">
					<h5>SK Penelitian Belum Bisa Dibuat</h5>
					<button type="button" name="back" class="btn btn-primary btn-sm mt-3" onClick="table_data();">Back Button</button>
					</div>';
				}
			}
			elseif ($this->ion_auth->in_group('kaprodi_SI')) 
			{
				foreach($query as $value)
				{
					$where1 = array(
						'NIM' => $value->NIM,
						'Prodi' => 'Sistem Informasi'
					);

					$query1 = $this->mahasiswa->where($where1)->get();
					if(!empty($query1))
					{
						$query2 = $this->penelitian->where('NIM', $query1->NIM)->get();
						// $data['PenelitianID'] = $query2->PenelitianID;
						// $data['NomerSK'] = $id;
						$data1[] = '
						<div class="card px-2 py-2 mx-4 mt-2">
						<p class="h6 font-weight-bold">'.$query2->NIM.' - '.$query1->Nama.'</p>
						<p class="card-text">'.$query2->Judul.'</p>
						</div>
						';
						$NomerSK = form_input(array('name' => 'NomerSK', 'id' => 'NomerSK', 'class' => 'form-control', 'value' => ''));
					}
					
				}
				if(empty($data1))
				{
					$data1 = '';
					$NomerSK = '';
					$kosong = '
					<div class="card-body col-12 text-center" style="height:40vh;">
					<h5>SK Penelitian Belum Bisa Dibuat</h5>
					<button type="button" name="back" class="btn btn-primary btn-sm mt-3" onClick="table_data();">Back Button</button>
					</div>';
				}
			}

			if(empty($kosong))
			{
				$data = array(
					'NomerSK' => $NomerSK,
					'daftar' => $data1
					
				);
			} else {
				$data = array(
					'NomerSK' => $NomerSK,
					'daftar' => $data1,
					'kosong' => $kosong
				);
			}

		} else {
			$data = array(
				'kosong1' => '
				<div class="card-body col-12 text-center" style="height:40vh;">
				<h5>SK Penelitian Belum Bisa Dibuat</h5>
				<button type="button" name="back" class="btn btn-primary btn-sm mt-3" onClick="table_data();">Back Button</button>
				</div>'
			);
		}	

		echo json_encode($data);
	}

	public function save_skripsi()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$rules = array(
			'insert' => array(

				array('field' => 'NomerSK', 'label' => 'NomerSK', 'rules' => 'trim|required')
			)
		);

		$id = $this->input->post('NomerSK');

		$where = array(
			'Status' => 'On Proses',
			'Jenis' => 'Skripsi'
		);

		$query   = $this->penelitian->where($where)->get_all();

		$data = array();

		$code = 0;

		$this->form_validation->set_rules($rules['insert']);

		if ($this->form_validation->run() == true)
		{

			if($this->ion_auth->in_group('kaprodi_TI'))
			{
				foreach($query as $value)
				{
					$where1 = array(
						'NIM' => $value->NIM,
						'Prodi' => 'Teknik Informatika'
					);

					$query1 = $this->mahasiswa->where($where1)->get();
					
					if(!empty($query1))
					{
						$query2 = $this->penelitian->where('NIM', $query1->NIM)->get();
						$data['PenelitianID'] = $query2->PenelitianID;
						$data['NomerSK'] = $id;
						$data1[] = $data;
					}
					
				}
			}
			elseif ($this->ion_auth->in_group('kaprodi_SI')) 
			{
				foreach($query as $value)
				{
					$where1 = array(
						'NIM' => $value->NIM,
						'Prodi' => 'Sistem Informasi'
					);

					$query1 = $this->mahasiswa->where($where1)->get();
					if(!empty($query1))
					{
						$query2 = $this->penelitian->where('NIM', $query1->NIM)->get();
						$data['PenelitianID'] = $query2->PenelitianID;
						$data['NomerSK'] = $id;
						$data1[] = $data;
					}
					
				}
			}



			$this->skpenelitian->insert($data1);

			$error =  $this->db->error();
			if ($error['code'] <> 0) {
				$code = 1;
				$title = 'Warning!';
				$notifications = $error['code'] . ' : ' . $error['message'];
			} else {
				$title = 'Insert!';
				$notifications = 'SK Penelitian Berhasil Dibuat';
			}	

		} else {
			$code = 1;
			$title = 'Warning!';
			$notifications = 'Dicek lagi ya NomerSK nya masing kosong !';
		}	

		

		$notif = ($code == 0) ? json_encode(array('icon' => 'success', 'title' => $title, 'message' => $notifications, 'code' => $code)) : json_encode(array('icon' => 'error', 'title' => $title, 'message' => $notifications, 'code' => $code));
		
		echo $notif;
	}

	public function save_kp()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$rules = array(
			'insert' => array(

				array('field' => 'NomerSK', 'label' => 'NomerSK', 'rules' => 'trim|required')
			)
		);

		$id = $this->input->post('NomerSK');

		$where = array(
			'Status' => 'On Proses',
			'Jenis' => 'Kerja Praktek'
		);

		$query   = $this->penelitian->where($where)->get_all();

		$tahunakademik = $this->tahun->where('Status', 1)->get();

		$data = array();

		$code = 0;

		$this->form_validation->set_rules($rules['insert']);

		if ($this->form_validation->run() == true)
		{

			if($this->ion_auth->in_group('kaprodi_TI'))
			{
				foreach($query as $value)
				{
					$where1 = array(
						'NIM' => $value->NIM,
						'Prodi' => 'Teknik Informatika'
					);

					$query1 = $this->mahasiswa->where($where1)->get();
					
					if(!empty($query1))
					{
						$query2 = $this->penelitian->where('NIM', $query1->NIM)->get();
						$data['PenelitianID'] = $query2->PenelitianID;
						$data['NomerSK'] = $id;
						$data['Prodi'] = 'Teknik Informatika';
						$data['TahunAkademikID'] = $tahunakademik->TahunAkademikID;
						$data['Jenis'] = 'Kerja Praktek';
						$data1[] = $data;
					}
					
				}
			}
			elseif ($this->ion_auth->in_group('kaprodi_SI')) 
			{
				foreach($query as $value)
				{
					$where1 = array(
						'NIM' => $value->NIM,
						'Prodi' => 'Sistem Informasi'
					);

					$query1 = $this->mahasiswa->where($where1)->get();
					if(!empty($query1))
					{
						$query2 = $this->penelitian->where('NIM', $query1->NIM)->get();
						$data['PenelitianID'] = $query2->PenelitianID;
						$data['NomerSK'] = $id;
						$data['Prodi'] = 'Sistem Informasi';
						$data['TahunAkademikID'] = $tahunakademik->TahunAkademikID;
						$data['Jenis'] = 'Kerja Praktek';
						$data1[] = $data;
					}
					
				}
			}

			$this->skpenelitian->insert($data1);

			$error =  $this->db->error();
			if ($error['code'] <> 0) {
				$code = 1;
				$title = 'Warning!';
				$notifications = $error['code'] . ' : ' . $error['message'];
			} else {
				$title = 'Insert!';
				$notifications = 'SK Penelitian Berhasil Dibuat';
			}	

		} else {
			$code = 1;
			$title = 'Warning!';
			$notifications = 'Dicek lagi ya NomerSK nya masing kosong !';
		}	

		

		$notif = ($code == 0) ? json_encode(array('icon' => 'success', 'title' => $title, 'message' => $notifications, 'code' => $code)) : json_encode(array('icon' => 'error', 'title' => $title, 'message' => $notifications, 'code' => $code));
		
		echo $notif;


	}

	public function cetak()
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		}

		if ($this->ion_auth->in_group('kaprodi_SI')) 
		{
            $where = array(
				'Jenis' => $this->input->post('Jenis1'),
				'TahunAkademikID' => $this->input->post('Tahun1'),
				'Prodi' => 'Sistem Informasi'
			);
        }
        if ($this->ion_auth->in_group('kaprodi_TI')) 
        {
            $where = array(
				'Jenis' => $this->input->post('Jenis1'),
				'TahunAkademikID' => $this->input->post('Tahun1'),
				'Prodi' => 'Teknik Informatika'
			);
        }

		$list = $this->skpenelitian->where($where)->get_all();
		$list1 = $this->skpenelitian->where($where)->get();

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

		$pdf->SetFont('Arial', 'B', 12);
		// $pdf->SetMargins(30, 30, 30);
		$pdf->Cell(190, 3, '', 0, 1);
		$pdf->Cell(190, 6.5, 'SURAT KEPUTUSAN', 0, 1, 'C');

		if ($this->ion_auth->in_group('kaprodi_SI')) 
		{
            $pdf->Cell(190, 6.5, 'KETUA PROGRAM STUDI SISTEM INFORMASI', 0, 1, 'C');
        }
        if ($this->ion_auth->in_group('kaprodi_TI')) 
        {
            $pdf->Cell(190, 6.5, 'KETUA PROGRAM STUDI TEKNIK INFORMATIKA', 0, 1, 'C');
        }

		$pdf->Cell(190, 6.5, 'STMIK EL RAHMA', 0, 1, 'C');
		$pdf->Cell(190, 6.5, 'Nomer : '.$list1->NomerSK.'', 0, 1, 'C');
		$pdf->Cell(190, 3, '', 0, 1);
		$pdf->Cell(190, 6.5, 'TENTANG :', 0, 1, 'C');
		$pdf->Cell(190, 6.5, 'PEMBIMBING '.strtoupper($this->input->post('Jenis1')).'', 0, 1, 'C');
		
		$pdf->SetFont('Arial', '', 11);
		$pdf->Cell(190, 3, '', 0, 1);

		if ($this->ion_auth->in_group('kaprodi_SI')) 
		{
            $pdf->Cell(10, 6, 'Ketua Program Studi Sistem Informasi STMIK El Rahma Yogyakarta, setelah ;', 0, 1);
        }
        if ($this->ion_auth->in_group('kaprodi_TI')) 
        {
            $pdf->Cell(10, 6, 'Ketua Program Studi Teknik Informatika STMIK El Rahma Yogyakarta, setelah ;', 0, 1);
        }

		$pdf->Cell(190, 3, '', 0, 1);

		$pdf->SetX(15);
		$pdf->Cell(15, 6, 'Menimbang', 0, 0);

		$pdf->SetX(50);
		$pdf->Cell(0, 6, ':', 0, 0);

		$pdf->SetX(53);
		$pdf->Cell(0, 6, 'Bahwa untuk kelancaran pelaksanaan '.$this->input->post('Jenis1').' perlu adanya surat tugas', 0, 1);

		$pdf->SetX(53);
		$pdf->Cell(0, 6, 'pembimbingan', 0, 1);

		$pdf->Cell(190, 3, '', 0, 1);

		$pdf->SetX(15);
		$pdf->Cell(15, 6, 'Mengingat', 0, 0);

		$pdf->SetX(50);
		$pdf->Cell(0, 6, ':', 0, 0);

		$pdf->SetX(53);
		$pdf->Cell(0, 6, '1. Kurikulum STMIK El Rahma Yogyakarta', 0, 1);

		$pdf->SetX(53);
		$pdf->Cell(0, 6, '2. Peraturan pelaksanaan kegiatan akademik khususnya mengenai '.$this->input->post('Jenis1').'', 0, 1);

		$pdf->SetX(53);
		$pdf->Cell(0, 6, '3. Peraturan-peraturan yang berlaku di Yayasan El Rahma', 0, 1);

		$pdf->Cell(190, 3, '', 0, 1);

		$pdf->SetX(15);
		$pdf->Cell(15, 6, 'Memperhatikan', 0, 0);

		$pdf->SetX(50);
		$pdf->Cell(0, 6, ':', 0, 0);

		$pdf->SetX(53);
		$pdf->Cell(0, 6, 'Hasil pertemuan pimpinan STMIK El Rahma', 0, 1);

		$pdf->SetFont('Arial', 'B', 12);
		$pdf->Cell(190, 12, 'MEMUTUSKAN', 0, 1, 'C');

		$pdf->SetFont('Arial', '', 11);

		$pdf->SetX(15);
		$pdf->Cell(15, 6, 'Menetapkan', 0, 0);

		$pdf->SetX(50);
		$pdf->Cell(0, 6, ':', 0, 0);

		$pdf->SetX(53);
		$pdf->Cell(150, 6, 'Surat tugas sebagai pembimbing '.$this->input->post('Jenis1').' kepada dosen yang ditunjuk untuk', 0, 1, 'FJ');

		$pdf->SetX(53);

		if ($this->ion_auth->in_group('kaprodi_SI')) 
		{
            $pdf->Cell(150, 6, 'mahasiswa program studi Sistem Informasi sebagaimana terlampir. Surat keputusan', 0, 1, 'FJ');
        }
        if ($this->ion_auth->in_group('kaprodi_TI')) 
        {
        	$pdf->Cell(150, 6, 'mahasiswa program studi Teknik Informatika sebagaimana terlampir. Surat keputusan', 0, 1, 'FJ');
        }

		$pdf->SetX(53);
		$pdf->Cell(150, 6, 'ini mulai berlaku sejak tanggal ditetapkan, disampaikan kepada yang bersangkutan', 0, 1, 'FJ');

		$pdf->SetX(53);
		$pdf->Cell(150, 6, 'untuk diketahui dan dilaksanakan sebagaimana mestinya dengan ketentuan akan', 0, 1, 'FJ');

		$pdf->SetX(53);
		$pdf->Cell(150, 6, 'ditinjau kembali apabila dipandang perlu.', 0, 1);

		$pdf->Cell(190, 10, '', 0, 1);

		$pdf->Cell(120, 6, '', 0, 0);
		$pdf->Cell(70, 6, 'Ditetapkan di Yogyakarta', 0, 1, 'C');

		$pdf->Cell(120, 6, '', 0, 0);
		$pdf->Cell(70, 6, 'Tanggal 23 Mei 2020', 0, 1, 'C');

		$pdf->Cell(120, 6, '', 0, 0);

		if ($this->ion_auth->in_group('kaprodi_SI')) 
		{
            $pdf->Cell(70, 6, 'Ketua Prodi Sistem Informasi', 0, 1, 'C');
        }
        if ($this->ion_auth->in_group('kaprodi_TI')) 
        {
        	$pdf->Cell(70, 6, 'Ketua Prodi Teknik Informatika', 0, 1, 'C');
        }

		$pdf->Cell(190, 20, '', 0, 1);

		$pdf->SetFont('Arial', 'U', 11);

		$dsn = $this->dosen->where('NPP', $this->session->userdata('username'))->get();

		$teks = $dsn->Nama;

		$pisah = explode(" ", $teks);

		$jumlah = count($pisah);

		if(!empty($teks))
		{
			if(strlen($teks) <= 39)
			{
				$pdf->Cell(120, 6, '', 0, 0);
				$pdf->Cell(70, 6, $teks, 0, 1, 'C');

			} else {

				$st = "".$pisah[0]." ".$pisah[1]."";

				if($jumlah >= 3)
				{
					$st1 = "".$st." ".$pisah[2]."";

					if($jumlah >= 4)
					{
						$st2 = "".$st1." ".$pisah[3]."";
					}
				}

				if(empty($st2))
				{
					$pdf->Cell(120, 6, '', 0, 0);
					$pdf->Cell(70, 6, $st, 0, 1, 'C');

					$pdf->Cell(120, 6, '', 0, 0);
					$pdf->Cell(70, 6, $pisah[2], 0, 1, 'C');

				} else {

					$no = $jumlah - 2;

					for($i = $no; $i < $jumlah; $i++)
					{
						$data[] = " ".$pisah[$i]."";
					}

					$pdf->Cell(120, 6, '', 0, 0);
					$pdf->Cell(70, 6, $st2, 0, 1, 'C');

					$pdf->Cell(120, 6, '', 0, 0);
					$pdf->Cell(70, 6, implode(" ", $data), 0, 1, 'C');
				}
			}
		}

		$pdf->SetFont('Arial', '', 11);

		$pdf->Cell(120, 6, '', 0, 0);
		$pdf->Cell(70, 6, 'NPP. '.$dsn->NPP.'', 0, 1, 'C');

		$pdf->Cell(190, 10, '', 0, 1);

		$pdf->Cell(10, 6, 'Tembusan :', 0, 1);
		$pdf->Cell(10, 6, '1. Ketua Bidang Akademik', 0, 1);
		$pdf->Cell(10, 6, '2. Dosen Pembimbing', 0, 1);
		$pdf->Cell(10, 6, '3. Arsip', 0, 1);


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

		$pdf->SetFont('Arial', 'B', 11);
		// $pdf->SetMargins(30, 30, 30);
		$pdf->Cell(190, 3, '', 0, 1);
		$pdf->Cell(10, 6, 'Lampiran SK Kaprodi No. '.$list1->NomerSK.'', 0, 1);

		$pdf->Cell(120, 6, '', 0, 1);

		$pdf->SetFont('Arial', 'B', 10);

		$pdf->SetWidths(array(15,25,60,90));

		$pdf->Row(array('No', 'NIM', 'Nama', 'Pembimbing'));

		$pdf->SetFont('Arial', '', 10);

		
		
		$no = 0;

		if(!empty($list))
		{
			foreach ($list as $field) { 
				$query 	= $this->penelitian->where('PenelitianID', $field->PenelitianID)->get();
				$query1 = $this->mahasiswa->where('NIM', $query->NIM)->get();
				$query2 = $this->dosen->where('NPP', $query->NPP)->get();

				$no++;

				$pdf->Row(array($no, $query->NIM, $query1->Nama, $query2->Nama));
			}
		}	

		$tahunakademik = $this->tahun->where('TahunAkademikID', $this->input->post('Tahun1'))->get();

		$akademik = explode("/", $tahunakademik->TahunAkademik);

		if($this->input->post('Jenis1') == 'Kerja Praktek')
		{
			$jns = 'KP';
		} else {
			$jns = 'Skripsi';
		}
		

		if ($this->ion_auth->in_group('kaprodi_SI')) 
		{
            $pdf->Output("SK-".$jns."-".$akademik[1]."-SI.pdf","D");
        }
        if ($this->ion_auth->in_group('kaprodi_TI')) 
        {
            $pdf->Output("SK-".$jns."-".$akademik[1]."-TI.pdf","D");
        }

	}

}
