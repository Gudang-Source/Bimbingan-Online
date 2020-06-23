<?php

defined('BASEPATH') or exit('No direct script access allowed');

require(FCPATH . 'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Pesandosen extends CI_Controller
{

	protected $page_header = 'My Chat';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Mahasiswa_model' => 'mahasiswa', 'Bimbingan_model' => 'penelitian', 'Dosen_model' => 'dosen', 'Proposal_model' => 'proposal', 'Grouppesan_model' => 'grouppesan', 'Pesan_model' => 'pesan','Penelitian_model' => 'penelitian1'));
		$this->load->library(array('ion_auth', 'form_validation', 'templatedosen'));
		$this->load->helper(array('bootstrap_helper', 'tanggal_helper', 'download'));
	}

	public function index()
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

		$query = $this->penelitian->as_object()->get_all();

		$penelitian = $this->penelitian->where('NPP', $this->session->userdata('username'))->get_all();

		$data['page_header']   = $this->page_header;
		$data['panel_heading'] = 'Daftar Bimbingan';
		$data['page']         = '';
		$data['breadcrumb']         = 'Pesan';
		// $data['bimbingan']         = $penelitian;

		$this->templatedosen->backend('pesan_v', $data);
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

	public function tambah()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

		$data1 = array();

		$penelitian = $this->penelitian->where('NPP', $this->session->userdata('username'))->get_all();
		$name1 = $this->grouppesan->where('Name1', $this->session->userdata('username'))->get_all();
		$name2 = $this->grouppesan->where('Name2', $this->session->userdata('username'))->get_all();

		if(!empty($penelitian))
		{
			if(!empty($name1) && !empty($name2)) 
			{
				$pen = count((array) $name1) + count((array) $name2);
			} 
			elseif (!empty($name1)) 
			{
				$pen = count((array) $name1);
			} 
			elseif (!empty($name2)) 
			{
				$pen = count((array) $name1);
			} else {
				$pen = 0;
			}

			if($pen == count((array)$penelitian))
			{
				$data1[] = '<h5>Data sudah ditambahkan</h5>';
			} else {
				$query1 = $this->grouppesan->as_object()->get_all();

				if (!empty($query1)) {
					foreach ($query1 as $row1) {
						if ($row1->Name1 == $this->session->userdata('username')) {
							$Name[] = $row1->Name2;
						} elseif ($row1->Name2 == $this->session->userdata('username')) {
							$Name[] = $row1->Name1;
						}
					}

					if(!empty($Name))
					{
						$data2 = $this->penelitian->not($Name);

						$penelitian = (object) $data2;
					} else {
						$penelitian = $this->penelitian->where('NPP', $this->session->userdata('username'))->get_all();
					}
					
				} else {
					$penelitian = $this->penelitian->where('NPP', $this->session->userdata('username'))->get_all();
				}

				if (!empty($penelitian)) {
					foreach ($penelitian as $row) {
						$query1 = $this->mahasiswa->where('NIM', $row->NIM)->get();
						$data1[] = '
						<li class="nav-item mb-n4">
	                        <label class="btn btn-defaulth">
	                            <div class="col-2">
	                                <input type="radio" class="form-check-input " name="bimbingan" value="' . $row->NIM . '">
	                            </div>
	                            <div class="col-12 ml-3 text-left">
	                                <p class="mb-n1">' . $row->NIM . '</p>
	                                <p class="h8">' . $query1->Nama . '</p>
	                            </div>
	                        </label>
	                    </li>
					';
					}
				}
			}
		} else {
			$data1[] = '<h5>Data Penelitian Belum Ada</h5>';
		}

		$data['tambah'] = $data1;

		echo json_encode($data);

	}

	public function get_pesan()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

		$query = $this->grouppesan->as_object()->get_all();

		$id = $this->session->userdata('username');
		// $idgroup = $this->input->post('GroupPesanID');

		$data1 = array();

		if (!empty($query)) 
		{
			foreach ($query as $row) {
				if ($row->Name1 == $id) {
					$idgroup = $row->GroupPesanID;
					$url_view   = 'view_data(' . $idgroup . ');';

					$maha = $this->mahasiswa->where('NIM', $row->Name2)->get();
					$nama = $maha->Nama;

					$pendek = explode(" ", $nama);
					if(count($pendek) > 1)
					{
						$data1[] = '
							<button id="listgroup" type="button" class="btn btn-outline-info border-0 col-12" onClick="' . $url_view . '">
								<div class="form-inline">
									<i class="fas fa-user-circle fa-2x my-1"></i>
									<p class="h6 ml-3" style="margin-top: 12px;">' . $pendek[0] . ' ' . $pendek[1] . '</p>
								</div>
							</button>
						';
					} else {
						$data1[] = '
							<button id="listgroup" type="button" class="btn btn-outline-info border-0 col-12" onClick="' . $url_view . '">
								<div class="form-inline">
									<i class="fas fa-user-circle fa-2x my-1"></i>
									<p class="h6 ml-3" style="margin-top: 12px;">' . $pendek[0] . '</p>
								</div>
							</button>
						';
					}
				}
				if ($row->Name2 == $id) {
					$idgroup = $row->GroupPesanID;
					$url_view   = 'view_data(' . $idgroup . ');';

					$maha = $this->mahasiswa->where('NIM', $row->Name1)->get();
					$nama = $maha->Nama;
					$pendek = explode(" ", $nama);
					if(count($pendek) > 1)
					{
						$data1[] = '
							<button id="listgroup" type="button" class="btn btn-outline-info border-0 col-12" onClick="' . $url_view . '">
								<div class="form-inline">
									<i class="fas fa-user-circle fa-2x my-1"></i>
									<p class="h6 ml-3" style="margin-top: 12px;">' . $pendek[0] . ' ' . $pendek[1] . '</p>
								</div>
							</button>
						';
					} else {
						$data1[] = '
							<button id="listgroup" type="button" class="btn btn-outline-info border-0 col-12" onClick="' . $url_view . '">
								<div class="form-inline">
									<i class="fas fa-user-circle fa-2x my-1"></i>
									<p class="h6 ml-3" style="margin-top: 12px;">' . $pendek[0] . '</p>
								</div>
							</button>
						';
					}
				}
			}
		}

		$data['list'] = $data1;

		echo json_encode($data);

	}

	public function formgroup()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

		// $id = $this->input->post('bimbingan');

		$row = array(
			'Name1' => $this->session->userdata('username'),
			'Name2' => $this->input->post('bimbingan')
		);

		// print_r($row);
		// exit();
		$code = 0;
		if($this->input->post('GroupPesanID	') == null)
		{
			$this->grouppesan->insert($row);
			$title = 'Update!';
			$notifications = 'Success Update Data';
		}

		json_encode(array('icon' => 'success', 'title' => $title, 'message' => $notifications, 'code' => $code));
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

	public function view()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) { 
			redirect('auth/login', 'refresh');
		}

		$data1 = array();
		$id = $this->input->post('GroupPesanID');

		$query = $this->grouppesan->where('GroupPesanID', $id)->get();
		$query1 = $this->pesan->where('GroupPesanID', $id)->get_all();

		// print_r($query->Name2);
		// exit();

		if($query->Name1 == $this->session->userdata('username'))
		{
			$maha = $this->mahasiswa->where('NIM', $query->Name2)->get();
			$data['header'] = '<p class="h6 ml-3" style="margin-top: 12px;">'.$maha->Nama.'</p>';

			$where = array(
				'NIM' => $query->Name2,
				'Status' => 'On Proses'
			);

			$penelitian = $this->penelitian1->where($where)->get();
		} 
		elseif($query->Name2 == $this->session->userdata('username')) 
		{
			$maha = $this->mahasiswa->where('NIM', $query->Name1)->get();
			$data['header'] = '<p class="h6 ml-3" style="margin-top: 12px;">' . $maha->Nama . '</p>';

			$where = array(
				'NIM' => $query->Name1,
				'Status' => 'On Proses'
			);
			
			$penelitian = $this->penelitian1->where($where)->get();
		}

		if(!empty($query1))
		{
			foreach($query1 as $row)
			{
				if(empty($row->ProposalID))
				{
					if($row->Name == $this->session->userdata('username'))
					{
						$data1[] = '
						<div class="d-flex justify-content-end mb-2 ml-5" id="chat">
							<div class="card bg-chat border-0 px-2 py-1 ml-5">
								<p class="h7 text-justify text-wrap">
								' . nl2br($row->Pesan) . '
								</p>
								<p class="h7 text-right text-gray mb-n1">' . format_indo($row->created_at) . '</p>
							</div>
						</div>
						';
					} else {
						$data1[] = '
						<div class="d-flex justify-content-start mb-2 mr-5" id="chat">
							<div class="card border-0 px-2 py-1 mr-5">
								<p class="h7 text-wrap text-justify">
								' . $row->Pesan . '
								</p>
								<p class="h7 text-right text-gray mb-n1">' . format_indo($row->created_at) . '</p>
							</div>
						</div>
						';
					}
				} else {
					$proposal2 = $this->proposal->where('ProposalID', $row->ProposalID)->get();

					if($proposal2->Status == 'Revisi')
					{
						$data1[] = '
							<div class="d-flex justify-content-end mb-2 ml-5" id="chat">
	                            <div class="card bg-chat col-8 border-0 px-2 py-1 ml-5">
	                                <div class="row">
	                                    <div class="row col-6">
	                                        <div class="col-12 my-1 mx-1">
	                                            <img src="'.base_url().'assets/img/doc.png" class="img-fluid rounded" alt="">
	                                        </div>
	                                    </div>
	                                    <div class="col-6">
	                                        <p class="mx-2">Revisi Proposal</p>
	                                        <div class="col-12 my-2">
	                                            <a href='.site_url('pesandosen/download/'.$proposal2->NamaFile.'').' class="btn btn-danger btn-sm col-12">Download<i class="fas fa-file-pdf ml-2"></i></a>
	                                        </div>
	                                    </div>
	                                </div>
	                                <p class="h7 text-right text-gray mb-n1">' . format_indo($row->created_at) . '</p>
	                            </div>
	                        </div>
						';
					} else {
						$data1[] = '
							<div class="d-flex justify-content-start mb-2 mr-5" id="chat">
	                            <div class="card col-8 border-0 px-2 py-1 mr-5">
	                                <div class="row">
	                                    <div class="row col-6">
	                                        <div class="col-12 my-1 mx-1">
	                                            <img src="'.base_url().'assets/img/doc.png" class="img-fluid rounded" alt="">
	                                        </div>
	                                    </div>
	                                    <div class="col-6">
	                                        <p class="mx-2">Update Proposal Terbaru '.$proposal2->NamaBAB.'</p>
	                                        <div class="col-12 my-2">
	                                            <a href='.site_url('pesandosen/download/'.$proposal2->NamaFile.'').' class="btn btn-danger btn-sm col-12">Download<i class="fas fa-file-pdf ml-2"></i></a>
	                                        </div>
	                                        <div class="col-12">
	                                            <button type="button" class="btn btn-primary btn-sm col-12" data-toggle="modal" data-target="#modal-default">Catatan</button>
	                                        </div>
	                                    </div>
	                                </div>
	                                <p class="h7 text-right text-gray mb-n1">' . format_indo($row->created_at) . '</p>
	                            </div>
	                        </div>
						';
					}
				}

				
			}
		}

		$proposal1 = $this->proposal->where('PenelitianID' , $penelitian->PenelitianID)->get_all();

		if(!empty($proposal1))
		{
		
			foreach($proposal1 as $pro)
			{
				if($pro->Keterangan == null)
				{
					$proposal['ProposalID'] = $pro->ProposalID;
					$proposal['NamaBAB'] = $pro->NamaBAB;
					$proposal['Status'] = $pro->Status;
					$proposal['NamaFile'] = $pro->NamaFile;
					$proposal['Keterangan'] = $pro->Keterangan;

					break;
				} else {
					$proposal['Keterangan'] = 1;
				}
			}

			if(empty($proposal['Keterangan']))
			{
				$data['catatan'] = '
					<div class="row">
	                    <div class="col-6">
	                        <label>Catatan</label>
	                        <textarea name="catatan" class="form-control" rows="8"></textarea>
	                    </div>
	                    <div class="col-6">
	                    <input type="hidden" name="ProposalID" value='.$proposal['ProposalID'].'>
	                        <div class="form-group">
	                            <label>Nama BAB Laporan</label>
	                            <p>'.$proposal['NamaBAB'].'</p>
	                        </div>
	                        <div class="form-group">
	                            <label>Status</label>
	                            <p>'.$proposal['Status'].'</p>
	                        </div>
	                        <div class="form-group">
	                            <label>File Proposal</label>
	                            <div class="form-group">
	                            	<a href='. site_url('pesandosen/download/'. $proposal['NamaFile'].'').' class="btn btn-danger btn-sm">Download<i class="fas fa-file-pdf ml-2"></i></a>
	                            </div>
	                        </div>
	                    </div>
	                </div>
				';
			} else {
				$data['catatan'] = '<h5>Belum Ada Data Baru</h5>';
			}
		} else {
			$data['catatan'] = '<h5>Belum Ada Data Baru</h5>';
		}

		$data['isipesan'] = $data1;

		$data['GroupPesanID'] = form_hidden('GroupPesanID', $this->input->post('GroupPesanID'));

		echo json_encode($data);
	}

	public function save_pesan()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

		$row = array(
			'GroupPesanID' => $this->input->post('GroupPesanID'),
			'Pesan' => $this->input->post('pesan'),
			'Name' => $this->session->userdata('username')
		);
		
		if($this->input->post('PesanID') == null)
		{
			$this->pesan->insert($row);
		}

		$data['GroupPesanID'] = $this->input->post('GroupPesanID');

		echo json_encode($data);

	}

	public function save_catatan()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('dosen')) {
			redirect('auth/login', 'refresh');
		}

		$id = $this->input->post('ProposalID');

		$row = array(
			'Keterangan' => $this->input->post('catatan')
		);

		$code = 0;

		// print_r($row);
		// exit();

		if(!empty($this->input->post('catatan')))
		{
			$this->proposal->where('ProposalID', $id)->update($row);
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

				$this->table->add_row($noo, $row->NamaBAB, $row->Keterangan, '<p class="btn btn-info btn-sm">' . $row->Status . '</p>', '<a href=' . site_url('proposaldosen/download/' . $row->NamaFile . '') . ' class="btn btn-danger btn-sm">Download<i class="fas fa-file-pdf ml-2"></i></a>');
			}

			$proposal = $this->table->generate();

			$query2 = $this->penelitian
				->with_mahasiswa('fields:Nama, Prodi')
				->with_dosen('fields:Nama')
				->where('PenelitianID', $id)
				->get();

			$data = array();
			if ($query2) {
				$data = array(
					'NIM' => $query2->NIM,
					'NIM1' => $query2->mahasiswa->Nama,
					'NIM2' => $query2->mahasiswa->Prodi,
					'NPP' => $query2->dosen->Nama,
					'Jenis' => $query2->Jenis,
					'Judul' => $query2->Judul,
					'Status' => $query2->Status,
					'proposal' => $proposal
				);
			}

		}

		echo json_encode($data);
	}

	
	
}
