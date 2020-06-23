<!-- Content Header (Page header) -->
<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>AdminLTE 3 | Dashboard</title>
   <!-- Tell the browser to be responsive to screen width -->
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- Font Awesome -->
   <link href="<?php echo base_url() . 'assets/'; ?>css/bootstrap.min.css" rel="stylesheet">
   <style type="text/css" media="screen">
      .h7 {
         font-size: 14px;
      }

      .h8 {
         font-size: 10px;
      }
   </style>
</head>
<body>


<div class="card mx-auto">
   <div class="row">
      <div class="col-12">
         <img src="assets/img/kop.png" alt="" class="card-img">
      
         <div class="card-header border-0 mt-3" style="background-color: none;">
            <p class="card-text h6 font-weight-bold text-center" style="line-height: 15px;">
               SURAT KEPUTUSAN
            </p>
            <p class="card-text h6 font-weight-bold text-center" style="line-height: 15px;">
               KETUA PROGRAM STUDI SISTEM INFORMASI
            </p>
            <p class="card-text h6 font-weight-bold text-center" style="line-height: 15px;">
               STMIK EL RAHMA
            </p>
            <p class="card-text h6 font-weight-bold text-center" style="line-height: 15px;">
               Nomer : 010/SI/SKep/IX/19
            </p>
            <p class="card-text h6 font-weight-bold text-center mt-4" style="line-height: 15px;">
               TENTANG :
            </p>
            <p class="card-text h6 font-weight-bold text-center" style="line-height: 15px;">
               PEMBIMBING KERJA PRAKTEK
            </p>
         </div>
      
         <p class="card-text h7">Ketua Program Studi Sistem Informasi STMIK El Rahma Yogyakarta, setelah ;</p>
      
         <table class="table h7 table-borderless text-justify">
           <tr>
               <td style="width: 98px!important;">Menimbang</td>
               <td style="width: 5px!important;">:</td>
               <td>Bahwa untuk kelancaran pelaksanaan Skripsi perlu adanya surat tugas pembimbingan</td>
            </tr>
            <tr>
               <td>Mengingat</td>
               <td>:</td>
               <td>1. Kurikulum STMIK El Rahma Yogyakarta<br>
                  2. Peraturan pelaksanaan kegiatan akademik khususnya mengenai Skripsi<br>
                  3. Peraturan-peraturan yang berlaku di Yayasan El Rahma</td>
            </tr>
            <tr>
               <td>Memperhatikan</td>
               <td>:</td>
               <td>Hasil pertemuan pimpinan STMIK El Rahma</td>
            </tr>
            <tr>
               <td colspan="3" class="text-center font-weight-bold">MEMUTUSKAN</td>
            </tr>
            <tr>
               <td>Menetapkan</td>
               <td>:</td>
               <td>Surat tugas sebagai pembimbing Skripsi kepada dosen yang ditunjuk untuk mahasiswa program studi Sistem Informasi sebagaimana terlampir. Surat keputusan ini mulai berlaku sejak tanggal ditetapkan, disampaikan kepada yang bersangkutan untuk diketahui dan dilaksanakan sebagaimana mestinya dengan ketentuan akan ditinjau kembali apabila dipandang perlu.</td>
            </tr>
         </table>
         <p class="text-center h7 float-right">
            Ditetapkan di Yogyakarta<br>
            Tanggal 23 Mei 2020<br>
            Ketua Prodi Sistem Informasi<br><br><br><br>
            <u>Herdiesel Santoso, S.T., S.Kom., M.Cs</u><br>
            NPP. 200910029
         </p>
         <br><br><br><br><br><br><br>
         <p class="h7">
            Tembusan :<br>
            1. Ketua Bidang Akademik<br>
            2. Dosen Pembimbing<br>
            3. Arsip
         </p>
         <img src="assets/img/kop.png" alt="" class="card-img">
         <p class="card-text h7 font-weight-bold mt-5">Lampiran SK Kaprodi No.</p>
         <table class="table table-bordered h7 text-justify">
            <tr class="text-center">
               <th style="width: 20px!important;">No</th>
               <th style="width: 70px!important;">NIM</th>
               <th>Nama</th>
               <th>Pembimbing</th>
            </tr>
            <?php

            if(!empty($sk)):

               $no = 1;

               $this->load->model(array('Mahasiswa_model' => 'mahasiswa', 'Dosen_model' => 'dosen', 'Penelitian_model' => 'penelitian'));

               foreach($sk as $value)
               {
                  $query = $this->penelitian->where('PenelitianID', $value->PenelitianID)->get();
                  $query1 = $this->mahasiswa->where('NIM', $query->NIM)->get();
                  $query2 = $this->dosen->where('NPP', $query->NPP)->get();
            ?>
            <tr>
               <td class="text-center"><?php echo $no++; ?></td>
               <td><?php echo $query->NIM; ?></td>
               <td><?php echo $query1->Nama; ?></td>
               <td><?php echo $query2->Nama; ?></td>
            </tr>
            <?php

               }
            endif;

            ?>
         </table>
      </div>            
   </div>
</div>



</body>
</html>