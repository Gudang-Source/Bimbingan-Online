<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    protected $page_header = 'Form Sk Penelitian';

    public function __construct()
    {
        parent::__construct();


        $this->load->model(array('Skpenelitian_model' => 'skpenelitian', 'Mahasiswa_model' => 'mahasiswa', 'Dosen_model' => 'dosen', 'Bimbingan_model' => 'bimbingan', 'Penelitian_model' => 'penelitian', 'Tahunakademik_model'=>'tahun'));
        $this->load->library(array('ion_auth', 'form_validation', 'templatemahasiswa', 'pdf', 'pdf2'));
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
        $data['page']          = '';
        $data['breadcrumb']    = 'Sk Penelitian';

        $this->templatemahasiswa->backend('dashboard_v', $data);
    }

    public function get_sk()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } elseif (!$this->ion_auth->in_group('mahasiswa')) {
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

    public function form()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } elseif (!$this->ion_auth->in_group('kaprodi_SI' || 'kaprodi_TI')) {
            redirect('auth/login', 'refresh');
        }

        $data = array();

        $query1 = $this->skpenelitian->as_object()->get_all();

        if(!empty($query1))
        {
            foreach($query1 as $row)
            {
                $list = array(
                    'PenelitianID' => $row->PenelitianID,
                    'NIM' => $this->session->userdata('username'),
                    'Status' => 'On Proses'
                );
                $penelitian = $this->penelitian->where($list)->get();

                $mahasiswa = $this->mahasiswa->where('NIM', $this->session->userdata('username'))->get();

                if(!empty($penelitian))
                {
                    $where = array(
                        'Jenis' => $penelitian->Jenis,
                        'Prodi' => $mahasiswa->Prodi
                    );
                    break;
                }
            }
        } 

        if(!empty($where))
        {
            $query = $this->skpenelitian->where($where)->get_all();

            if ($query) {
                set_table(true);

                $No = array(
                    'data' => 'No',
                );
                $Nomer = array(
                    'data' => 'Nomer SK'
                );
                $Nama = array(
                    'data' => 'Nama Mahasiswa',
                );
                $Pembimbing = array(
                    'data' => 'Pembimbing',
                );

                $this->table->set_heading($No, $Nomer, $Nama, $Pembimbing);

                $no = 1;

                foreach ($query as $row) {

                    $noo = array(
                        'data' => $no++,

                    );

                    $penelitian = $this->penelitian->where('PenelitianID', $row->PenelitianID)->get();

                    $mhs = $this->mahasiswa->where('NIM',  $penelitian->NIM)->get();
                    $dosen = $this->dosen->where('NPP',  $penelitian->NPP)->get();

                    $this->table->add_row($noo, $row->NomerSK, $mhs->Nama, $dosen->Nama);
                }

                $proposal = $this->table->generate();

                $data['proposal'] = $proposal;

            } else {

                $data['proposal'] = '<h2 class="text-center">SK Penelitian Belum Ada</h2>';
            }

        } else {
            $data['proposal'] = '<h2 class="text-center">SK Penelitian Belum Ada</h2>';
        }

        echo json_encode($data);
    }

    public function cetak()
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } elseif (!$this->ion_auth->in_group('mahasiswa')) {
            redirect('auth/login', 'refresh');
        }

        $query1 = $this->skpenelitian->as_object()->get_all();

        $mhs = $this->mahasiswa->where('NIM', $this->session->userdata('username'))->get();

        $code = 0;

        if(!empty($query1))
        {
            foreach($query1 as $row)
            {
                $list = array(
                    'PenelitianID' => $row->PenelitianID,
                    'NIM' => $this->session->userdata('username'),
                    'Status' => 'On Proses'
                );
                $penelitian = $this->penelitian->where($list)->get();   

                if(!empty($penelitian))
                {
                    break;
                }
            }
        }    

        // print_r($penelitian);
        // exit();

        if(empty($penelitian) || empty($query1))
        {
            $code = 1;
            $title = 'Warning!';
            $notifications = 'Maaf Data Sk Penelitian Anda Belum Ada .';

            echo json_encode(array('icon' => 'error', 'title' => $title, 'message' => $notifications, 'code' => $code));
        } else {
            $sk = $this->skpenelitian->where('PenelitianID', $penelitian->PenelitianID)->get();

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
            $pdf->Cell(190, 6.5, 'KETUA PROGRAM STUDI '.strtoupper($mhs->Prodi).'', 0, 1, 'C');
            $pdf->Cell(190, 6.5, 'STMIK EL RAHMA', 0, 1, 'C');
            $pdf->Cell(190, 6.5, 'Nomer : '.$sk->NomerSK.'', 0, 1, 'C');
            $pdf->Cell(190, 3, '', 0, 1);
            $pdf->Cell(190, 6.5, 'TENTANG :', 0, 1, 'C');
            $pdf->Cell(190, 6.5, 'PEMBIMBING '.strtoupper($penelitian->Jenis).'', 0, 1, 'C');
            
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(190, 3, '', 0, 1);
            $pdf->Cell(10, 6, 'Ketua Program Studi '.$mhs->Prodi.' STMIK El Rahma Yogyakarta, setelah ;', 0, 1);

            $pdf->Cell(190, 3, '', 0, 1);

            $pdf->SetX(15);
            $pdf->Cell(15, 6, 'Menimbang', 0, 0);

            $pdf->SetX(50);
            $pdf->Cell(0, 6, ':', 0, 0);

            $pdf->SetX(53);
            $pdf->Cell(0, 6, 'Bahwa untuk kelancaran pelaksanaan '.$penelitian->Jenis.' perlu adanya surat tugas', 0, 1);

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
            $pdf->Cell(0, 6, '2. Peraturan pelaksanaan kegiatan akademik khususnya mengenai '.$penelitian->Jenis.'', 0, 1);

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
            $pdf->Cell(150, 6, 'Surat tugas sebagai pembimbing '.$penelitian->Jenis.' kepada dosen yang ditunjuk untuk', 0, 1, 'FJ');

            $pdf->SetX(53);
            $pdf->Cell(150, 6, 'mahasiswa program studi '.$mhs->Prodi.' sebagaimana terlampir. Surat keputusan', 0, 1, 'FJ');

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
            $pdf->Cell(70, 6, 'Ketua Prodi '.$mhs->Prodi.'', 0, 1, 'C');

            $pdf->Cell(190, 20, '', 0, 1);

            $pdf->SetFont('Arial', 'U', 11);

            // $dsn = $this->dosen->where('NPP', $penelitian)->get();

               
            $dsn = $this->dosen->where('Jabatan', 'Kaprodi '.$mhs->Prodi.'')->get();   

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
                        // echo $st;
                        // echo $pisah[2];

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

                        // echo $st2;
                        // echo implode(" ", $data);

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
            $pdf->Cell(10, 6, 'Lampiran SK Kaprodi No. '.$sk->NomerSK.'', 0, 1);

            $pdf->Cell(120, 6, '', 0, 1);

            $pdf->SetFont('Arial', 'B', 10);

            $pdf->SetWidths(array(15,25,60,90));

            $pdf->Row(array('No', 'NIM', 'Nama', 'Pembimbing'));

            $pdf->SetFont('Arial', '', 10);

            // $query1 = $this->skpenelitian->as_object()->get_all();

            foreach($query1 as $row)
            {
                $list = array(
                    'PenelitianID' => $row->PenelitianID,
                    'NIM' => $this->session->userdata('username'),
                    'Status' => 'On Proses'
                );
                $penelitian = $this->penelitian->where($list)->get();

                $mahasiswa = $this->mahasiswa->where('NIM', $this->session->userdata('username'))->get();

                if(!empty($penelitian))
                {
                    $where = array(
                        'Jenis' => $penelitian->Jenis,
                        'Prodi' => $mahasiswa->Prodi
                    );
                    break;
                }
            }

            $list = $this->skpenelitian->where($where)->get_all();

            $no = 0;

            if(!empty($list))
            {
                foreach ($list as $field) { 

                    $penelitian = $this->penelitian->where('PenelitianID', $field->PenelitianID)->get();

                    $mhs = $this->mahasiswa->where('NIM',  $penelitian->NIM)->get();
                    $dosen = $this->dosen->where('NPP',  $penelitian->NPP)->get();

                    $no++;

                    $pdf->Row(array($no, $penelitian->NIM, $mhs->Nama, $dosen->Nama));
                }
            }

            $sk = $this->skpenelitian->where('PenelitianID', $penelitian->PenelitianID)->get(); 

            $tahunakademik = $this->tahun->where('TahunAkademikID', $sk->TahunAkademikID)->get();

            $akademik = explode("/", $tahunakademik->TahunAkademik);

            if($penelitian->Jenis == 'Kerja Praktek')
            {
                $jns = 'KP';
            } else {
                $jns = 'Skripsi';
            }

            if($mahasiswa->Prodi == 'Teknik Informatika')
            {
                $pro = 'TI';
            } else {
                $pro = 'SI';
            }

            $pdf->Output("Sk-".$jns."-".$akademik[1]."-".$pro.".pdf","D");

        }


        
    }

}
