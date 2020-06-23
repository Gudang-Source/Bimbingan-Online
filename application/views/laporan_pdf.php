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

</head>

<body>




  	<div class="col-12">

      <h4 class="text-center mb-4">Laporan Bimbingan Penelitian</h4>

      <table class="table table-borderless">

        <tr>
          <th>Nama Mahasiswa</th>
          <td>:</td>
          <td><?php echo $mahasiswa->Nama; ?></td>
        </tr>
        <tr>
          <th>NIM</th>
          <td>:</td>
          <td><?php echo $penelitian->NIM; ?></td>
        </tr>
        <tr>
          <th>Judul Penelitian</th>
          <td>:</td>
          <td><?php echo $penelitian->Judul; ?></td>
        </tr>
        <tr>
          <th>Jenis Penelitian</th>
          <td>:</td>
          <td><?php echo $penelitian->Jenis; ?></td>
        </tr>
        <tr>
          <th>Dosen Pembimbing</th>
          <td>:</td>
          <td><?php echo $dosen->Nama; ?></td>
        </tr>

      </table>

      <table class="table table-bordered">

        <thead>
          <tr class="text-center">
            <th style="width: 20px!important;">No</th>
            <th style="width: 190px!important;">Tanggal</th>
            <th style="width: 130px!important;">BAB Laporan</th>
            <th>Catatan</th>
          </tr>
        </thead>

        <tbody>

          <?php

          if(!empty($proposal))
          {
            $no = 1;
            foreach($proposal as $row)
            {

          ?>

            <tr>
              <td class="text-center"> <?php echo $no++; ?> </td>
              <td class="text-center"> <?php echo format_indo($row->created_at); ?> </td>
              <td class="text-center"> <?php echo $row->NamaBAB; ?> </td>
              <td> <?php echo $row->Keterangan; ?> </td>
            </tr>

          <?php

            }
          }

          ?>

        </tbody>

      </table>      

    </div>


</body>

</html>