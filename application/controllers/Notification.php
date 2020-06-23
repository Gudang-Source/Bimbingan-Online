<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Notification extends CI_Controller
{

	protected $page_header = 'Daftar Pengajuan Penelitian';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Pengajuan_model' => 'pengajuan', 'Mahasiswa_model' => 'mahasiswa', 'Dosen_model' => 'dosen', 'Bimbingan_model' => 'bimbingan', 'Penelitian_model' => 'penelitian', 'Grouppesan_model' => 'grouppesan', 'Pesan_model' => 'pesan', 'Proposal_model' => 'proposal'));
		$this->load->library(array('ion_auth', 'form_validation', 'templatekaprodi'));
		$this->load->helper('bootstrap_helper');
	}

	public function cek_notif()
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$id = $this->session->userdata('username');

		// $pengajuan = $this->pengajuan->where('Info', '1')->get();

		$grouppesan = $this->grouppesan->as_object()->get_all();
		$pengajuan = $this->pengajuan->as_object()->get_all();
		// $bimbingan = $this->bimbingan->as_object()->get_all();

		$bimbingan = $this->bimbingan->where('NPP', $id)->get_all();

		$whr = array(
			'NPP' => $id,
			'Info' => '1'
		);

		$bimbingan1 = $this->bimbingan->where($whr)->get();
		
		if(!empty($bimbingan))
		{
			foreach($bimbingan as $value)
			{
				
				$where = array(
					'PenelitianID' => $value->PenelitianID,
					'Status' => 'Laporan Baru',
					'Info' => '1'
				);
				if(!empty($this->proposal->where($where)->get()))
				{
					$proposal = $this->proposal->where($where)->get();
					break;
				}
				
			}
		}

		if(!empty($pengajuan))
		{
			foreach($pengajuan as $row)
			{
				if($this->ion_auth->in_group('kaprodi_TI'))
				{
					$si = array(
						'NIM' => $row->NIM,
						'Prodi' => 'Teknik Informatika'
					);

					if(!empty($this->mahasiswa->where($si)->get()))
					{
						$mahasiswa = $this->mahasiswa->where($si)->get();
						$info = array(
							'NIM' => $mahasiswa->NIM,
							'Status' => 'On Proses',
							'Info' => '1'
						);
					}

					if(!empty($this->pengajuan->where($info)->get()))
					{
						$pengajuan1 = $this->pengajuan->where($info)->get();
						break;
					}
				}
			}
		}

		if(!empty($grouppesan))
		{
			foreach($grouppesan as $row)
			{
				if($row->Name1 == $id)
				{
					$GP = $this->grouppesan->where('Name1', $row->Name1)->get();
					break;
				} 
				elseif($row->Name2 == $id)
				{
					$GP = $this->grouppesan->where('Name2', $row->Name2)->get();
					break;
				}
			}
		}	

		if(!empty($GP))
		{
			if($GP->Name1 == $id)
			{
				$pesan = array(
					'GroupPesanID' => $GP->GroupPesanID,
					'Name' => $GP->Name2,
					'Info' => '1'
				);
			} 
			elseif($GP->Name2 == $id)
			{
				$pesan = array(
					'GroupPesanID' => $GP->GroupPesanID,
					'Name' => $GP->Name1,
					'Info' => '1'
				);
			}
			

			$pesan1 = $this->pesan->where($pesan)->get_all();
		}

		if(!empty($bimbingan1))
		{
			$mhs = $this->mahasiswa->where('NIM', $bimbingan1->NIM)->get();

			$code = 0;
			$data1 = '
			<div class="mb-n2" style="width: 250px;">
				<a href="" class="text-white">
					<p>
						Ada Bimbingan Baru Nih Namanya
						<br>
						<b>'.$mhs->Nama.'</b>
					</p>
				</a>
			</div>
			';
			$title = 'Bimbingan Baru';
			$icon = 'fas fa-users fa-lg';

			$row = array('Info' => '0');

			$this->bimbingan->where('PenelitianID', $bimbingan1->PenelitianID)->update($row);
		}
		elseif(!empty($pengajuan1))
		{
			$mhs = $this->mahasiswa->where('NIM', $pengajuan1->NIM)->get();

			$code = 0;
			$data1 = '
			<div class="mb-n2" style="width: 250px;">
				<a href="" class="text-white">
					<p>
						Ada Pengajuan Baru Nih Dari
						<br>
						<b>'.$mhs->Nama.'</b>
					</p>
				</a>
			</div>
			';
			$title = 'Pengajuan Baru';
			$icon = 'fas fa-file-signature fa-lg';

			$row = array('Info' => '0');

			$this->pengajuan->where('PengajuanID', $pengajuan1->PengajuanID)->update($row);

		} 
		elseif(!empty($pesan1))
		{
			$pesan2 = $this->pesan->where($pesan)->get();
			$group = $this->grouppesan->where('GroupPesanID', $pesan2->GroupPesanID)->get();

			if($group->Name1 == $id)
			{
				$mhs = $this->mahasiswa->where('NIM', $group->Name2)->get();
			} 
			elseif($group->Name2 == $id)
			{
				$mhs = $this->mahasiswa->where('NIM', $group->Name1)->get();
			}

			$code = 0;
			$data1 = '
			<div class="mb-n2" style="width: 250px;">
				<p>
					Ada Pesan Masuk Nih Dari
					<br>
					<b>'.$mhs->Nama.'</b>
				</p>
			</div>
			';
			$title = 'Pesan Masuk';
			$icon = 'fas fa-envelope fa-lg';

			$row = array('Info' => '0');	

			$this->pesan->where($pesan)->update($row);
		}
		elseif(!empty($proposal))
		{
			$penelitian = $this->bimbingan->where('PenelitianID', $proposal->PenelitianID)->get();
			$mhs = $this->mahasiswa->where('NIM', $penelitian->NIM)->get();

			$code = 0;
			$data1 = '
			<div class="mb-n2" style="width: 250px;">
				<p>
					Ada Proposal Baru Nih Dari
					<br>
					<b>'.$mhs->Nama.'</b>
				</p>
			</div>
			';
			$title = 'Proposal Baru';
			$icon = 'fas fa-file-alt fa-lg';

			$row = array('Info' => '0');	

			$this->proposal->where('ProposalID', $proposal->ProposalID)->update($row);
		}

		

		else {
			$code = 1;
			$data1 = '';
			$title = '';
			$icon = '';
		}

		$data = array(
			'code' => $code,
			'body' => $data1,
			'title' => $title,
			'icon' => $icon
		);


		echo json_encode($data);

		// echo "<pre>";
		// print_r($data);
		// exit();

	}

	public function cek_notif_dosen()
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
			redirect('auth/login', 'refresh');
		} 

		$id = $this->session->userdata('username');

		$grouppesan = $this->grouppesan->as_object()->get_all();

		$bimbingan = $this->bimbingan->where('NPP', $id)->get_all();

		$whr = array(
			'NPP' => $id,
			'Info' => '1'
		);

		$bimbingan1 = $this->bimbingan->where($whr)->get();
		
		if(!empty($bimbingan))
		{
			foreach($bimbingan as $value)
			{
				
				$where = array(
					'PenelitianID' => $value->PenelitianID,
					'Status' => 'Laporan Baru',
					'Info' => '1'
				);
				if(!empty($this->proposal->where($where)->get()))
				{
					$proposal = $this->proposal->where($where)->get();
					break;
				}
				
			}
		}

		if(!empty($grouppesan))
		{
			foreach($grouppesan as $row)
			{
				if($row->Name1 == $id)
				{
					$GP = $this->grouppesan->where('Name1', $row->Name1)->get();
					break;
				} 
				elseif($row->Name2 == $id)
				{
					$GP = $this->grouppesan->where('Name2', $row->Name2)->get();
					break;
				}
			}
		}	

		if(!empty($GP))
		{
			if($GP->Name1 == $id)
			{
				$pesan = array(
					'GroupPesanID' => $GP->GroupPesanID,
					'Name' => $GP->Name2,
					'Info' => '1'
				);
			} 
			elseif($GP->Name2 == $id)
			{
				$pesan = array(
					'GroupPesanID' => $GP->GroupPesanID,
					'Name' => $GP->Name1,
					'Info' => '1'
				);
			}
			

			$pesan1 = $this->pesan->where($pesan)->get_all();
		}

		if(!empty($bimbingan1))
		{
			$mhs = $this->mahasiswa->where('NIM', $bimbingan1->NIM)->get();

			$code = 0;
			$data1 = '
			<div class="mb-n2" style="width: 250px;">
				<a href="" class="text-white">
					<p>
						Ada Bimbingan Baru Nih Namanya
						<br>
						<b>'.$mhs->Nama.'</b>
					</p>
				</a>
			</div>
			';
			$title = 'Bimbingan Baru';
			$icon = 'fas fa-users fa-lg';

			$row = array('Info' => '0');

			$this->bimbingan->where('PenelitianID', $bimbingan1->PenelitianID)->update($row);
		}
		elseif(!empty($pesan1))
		{
			$pesan2 = $this->pesan->where($pesan)->get();
			$group = $this->grouppesan->where('GroupPesanID', $pesan2->GroupPesanID)->get();

			if($group->Name1 == $id)
			{
				$mhs = $this->mahasiswa->where('NIM', $group->Name2)->get();
			} 
			elseif($group->Name2 == $id)
			{
				$mhs = $this->mahasiswa->where('NIM', $group->Name1)->get();
			}

			$code = 0;
			$data1 = '
			<div class="mb-n2" style="width: 250px;">
				<p>
					Ada Pesan Masuk Nih Dari
					<br>
					<b>'.$mhs->Nama.'</b>
				</p>
			</div>
			';
			$title = 'Pesan Masuk';
			$icon = 'fas fa-envelope fa-lg';

			$row = array('Info' => '0');	

			$this->pesan->where($pesan)->update($row);
		}
		elseif(!empty($proposal))
		{
			$penelitian = $this->bimbingan->where('PenelitianID', $proposal->PenelitianID)->get();
			$mhs = $this->mahasiswa->where('NIM', $penelitian->NIM)->get();

			$code = 0;
			$data1 = '
			<div class="mb-n2" style="width: 250px;">
				<p>
					Ada Proposal Baru Nih Dari
					<br>
					<b>'.$mhs->Nama.'</b>
				</p>
			</div>
			';
			$title = 'Proposal Baru';
			$icon = 'fas fa-file-alt fa-lg';

			$row = array('Info' => '0');	

			$this->proposal->where('ProposalID', $proposal->ProposalID)->update($row);
		}

		

		else {
			$code = 1;
			$data1 = '';
			$title = '';
			$icon = '';
		}

		$data = array(
			'code' => $code,
			'body' => $data1,
			'title' => $title,
			'icon' => $icon
		);


		echo json_encode($data);

		// echo "<pre>";
		// print_r($data);
		// exit();

	}

	public function cek_notif_mahasiswa()
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->in_group('mahasiswa')) {
			redirect('auth/login', 'refresh');
		} 

		$id = $this->session->userdata('username');

		$grouppesan = $this->grouppesan->as_object()->get_all();

		if(!empty($this->penelitian->where('NIM', $id)->get()))
		{
			$penelitian = $this->penelitian->where('NIM', $id)->get();

			$whr = array(
				'PenelitianID' => $penelitian->PenelitianID,
				'Status' => 'Revisi',
				'Info' => '1'
			);
			$proposal = $this->proposal->where($whr)->get();
		}

		if(!empty($this->pengajuan->where('NIM', $id)->get()))
		{
			$where = array(
				'NIM' => $id,
				'Status' => 'Disetujui',
				'Info' => '1'
			);
			$pengajuan = $this->pengajuan->where($where)->get();
		}

		if(!empty($grouppesan))
		{
			foreach($grouppesan as $row)
			{
				if($row->Name1 == $id)
				{
					$GP = $this->grouppesan->where('Name1', $row->Name1)->get();
					break;
				} 
				elseif($row->Name2 == $id)
				{
					$GP = $this->grouppesan->where('Name2', $row->Name2)->get();
					break;
				}
			}
		}	

		if(!empty($GP))
		{
			if($GP->Name1 == $id)
			{
				$pesan = array(
					'GroupPesanID' => $GP->GroupPesanID,
					'Name' => $GP->Name2,
					'Info' => '1'
				);
			} 
			elseif($GP->Name2 == $id)
			{
				$pesan = array(
					'GroupPesanID' => $GP->GroupPesanID,
					'Name' => $GP->Name1,
					'Info' => '1'
				);
			}
			

			$pesan1 = $this->pesan->where($pesan)->get_all();
		}

		if(!empty($pesan1))
		{
			$pesan2 = $this->pesan->where($pesan)->get();
			$group = $this->grouppesan->where('GroupPesanID', $pesan2->GroupPesanID)->get();

			if($group->Name1 == $id)
			{
				$dos = $this->dosen->where('NPP', $group->Name2)->get();
			} 
			elseif($group->Name2 == $id)
			{
				$dos = $this->dosen->where('NPP', $group->Name1)->get();
			}

			$code = 0;
			$data1 = '
			<div class="mb-n2" style="width: 250px;">
				<p>
					Ada Pesan Masuk Nih Dari
					<br>
					<b>Pak '.$dos->Nama.'</b>
				</p>
			</div>
			';
			$title = 'Pesan Masuk';
			$icon = 'fas fa-envelope fa-lg';

			$row = array('Info' => '0');	

			$this->pesan->where($pesan)->update($row);
		}
		elseif(!empty($proposal))
		{
			$dos1 = $this->dosen->where('NPP', $penelitian->NPP)->get();

			$code = 0;
			$data1 = '
			<div class="mb-n2" style="width: 250px;">
				<p>
					Ada Revisi Proposal Baru Nih Dari
					<br>
					<b>Pak '.$dos1->Nama.'</b>
				</p>
			</div>
			';
			$title = 'Revisi Proposal';
			$icon = 'fas fa-file-alt fa-lg';

			$row = array('Info' => '0');	

			$this->proposal->where('ProposalID', $proposal->ProposalID)->update($row);
		}
		elseif(!empty($pengajuan))
		{
			$penelitian1 = $this->penelitian->where('NIM', $id)->get();

			// $dos2 = $this->dosen->where('NPP', $penelitian1->NPP)->get();

			$code = 0;
			$data1 = '
			<div class="mb-n2" style="width: 250px;">
				<p>
					Pengajuan '.$pengajuan->JenisPengajuan.' Anda Disetujui
					
				</p>
			</div>
			';
			$title = 'Pengajuan Disetujui';
			$icon = 'fas fa-file-signature fa-lg';

			$row = array('Info' => '0');	

			$this->pengajuan->where('PengajuanID', $pengajuan->PengajuanID)->update($row);
		}

		else {
			$code = 1;
			$data1 = '';
			$title = '';
			$icon = '';
		}

		$data = array(
			'code' => $code,
			'body' => $data1,
			'title' => $title,
			'icon' => $icon
		);


		echo json_encode($data);

		// echo "<pre>";
		// print_r($data);
		// exit();

	}

}