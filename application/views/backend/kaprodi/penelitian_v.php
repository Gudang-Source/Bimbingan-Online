<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?php echo isset($page_header) ? $page_header : ''; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active"> <?php echo isset($breadcrumb) ? $breadcrumb : ''; ?></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div id="notifications"></div>
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"> </h3>
                    </div><!-- /.card-header -->

                    <div id="table-data">
                        <div class="card-body">
                            <form role="form" method="POST" action="" id="form-filter">
                                <div class="form-group">   
                                    <div class="col-3 text-center mx-auto">
                                        <div class="form-group">
                                            <label>Filter Tahun Akademik</label>
                                            <?php echo form_dropdown('tahun', $opt_tahun, $tahunakademik->TahunAkademikID, 'class="form-control" id="tahun"'); ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table id="table" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Nama Pembimbing</th>
                                            <th>Jenis Penelitian</th>
                                            <th>Judul Penelitian</th>
                                            <th style="width: 90px!important;">Status</th>
                                            <th style="width: 100px!important;">Laporan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Nama Pembimbing</th>
                                            <th>Jenis Penelitian</th>
                                            <th>Judul Penelitian</th>
                                            <th>Status</th>
                                            <th>Laporan</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <form role="form" method="POST" action="" id="form-data">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="table table-borderless">
                                        <table>
                                            <tr>
                                                <th>Nama Mahasiswa</th>
                                                <th>:</th>
                                                <td>
                                                    <p id="NIM1"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>NIM</th>
                                                <th>:</th>
                                                <td>
                                                    <p id="NIM"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Program Studi</th>
                                                <th>:</th>
                                                <td>
                                                    <p id="NIM2"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Nama Pembimbing</th>
                                                <th>:</th>
                                                <td>
                                                    <p id="NPP"></p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="table table-borderless">
                                        <table>
                                            <tr>
                                                <th>Judul Penelitian</th>
                                                <th>:</th>
                                                <td>
                                                    <p id="Judul"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Jenis Penelitian</th>
                                                <th>:</th>
                                                <td>
                                                    <p id="Jenis"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <th>:</th>
                                                <td>
                                                    <div id="Status"></div>
                                                </td>
                                            </tr>
                                            
                                        </table>
                                    </div>
                                </div>

                                <div class="col-12 mb-3"> 
                                    <div class="form-group">
                                        <form role="form" method="POST" action="" id="form-cetak">

                                          <div id="input_cetak"></div>
                                          <button type="button" class="btn btn-danger btn-sm ml-3" id="cetak">Cetak Laporan<i class="fas fa-print ml-2" ></i></button>

                                        </form>
                                    </div>
                                </div>  
                                
                                <div class="col-12 table-responsive">
                                    <div id="proposal"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"><button type="button" name="back" class="btn btn-primary float-right" onClick="table_data();">Back Button</button></div>
                    </form>

                </div><!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->


<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form role="form" method="POST" action="" id="form-view">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>NIM</label>
                                    <p id="NIM_BIM"></p>
                                </div>
                                <div class="form-group">
                                    <label>Nama Mahasiswa</label>
                                    <p id="NIM1_BIM"></p>
                                </div>
                                <div class="form-group">
                                    <label>Nama Pembimbing</label>
                                    <p id="NPP_BIM"></p>
                                </div>
                                <div class="form-group">
                                    <label>Jenis Penelitian</label>
                                    <p id="Jenis_BIM"></p>
                                </div>
                                <div class="form-group">
                                    <label>Judul Penelitian</label>
                                    <p id="Judul_BIM"></p>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <p id="Status_BIM"></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var site_url = site_url() + 'penelitian/';

    var table;
    $(document).ready(function() {

        table_data();

        table = $('#table').DataTable({

            "processing": true,
            "serverSide": true,
            "order": [],

            "ajax": {
                "url": site_url + 'get_penelitian',
                "type": "POST",
                "data": function(data) {
                    data.tahun = $('#tahun').val();
                }
            },

            "columnDefs": [{
                "targets": [0],
                "orderable": false,
            }, ],
        });

        $('#tahun').change(function() { //button filter event click
            table.ajax.reload(); //just reload table
        });

        $('#cetak').click(function() {
            const PenelitianID = $('input[name="PenelitianID"]').val();
            $.ajax({
                url: site_url + 'cetak/',
                type: "POST",
                data: {PenelitianID:PenelitianID},
                success: function(data) {
                    
                    view_data1(localStorage.getItem("PenelitianID"));
                    table.draw(false);    
                    
                }
            });
            // setInterval(list, 1000);
        });

    });

    function table_data() {
        $('#table-data').show();
        $('#form-data').hide();
        $('#form-view').hide();

        $('.card-title').text('Penelitian List');
    }

    function form_view() {
        $('p#hidden').empty();
        $('p#NIM_BIM').empty();
        $('p#NIM1_BIM').empty();
        $('p#NPP_BIM').empty();
        $('p#Jenis_BIM').empty();
        $('p#Judul_BIM').empty();
        $('p#Status_BIM').empty();

        // $('#table-data').hide();
        // $('#form-data').hide();
        $('#form-view').show();

        $('#modal-default').modal('show');
        $('.modal-title').text('View Penelitian');
    }

    function form_data() {
        $('p#hidden').empty();
        $('p#NIM').empty();
        $('p#NIM1').empty();
        $('p#NIM2').empty();
        $('p#NPP').empty();
        $('p#Jenis').empty();
        $('p#Judul').empty();
        $('p#Status').empty();

        $('#table-data').hide();
        $('#form-data').show();
        $('#form-view').hide();
        $('#form-upload').hide();

        $('#modal-default').modal('hide')
        $('.modal-title').text('Detail Penelitian');
    }

    function view_data(id) {
        $.ajax({
            url: site_url + 'view/',
            data: {
                'NIM': id
            },
            cache: false,
            type: "POST",
            success: function(data) {
                form_view();

                data = JSON.parse(data);
                $('p#hidden').html(data.hidden);
                $('p#NIM_BIM').html(data.NIM_BIM);
                $('p#NIM1_BIM').html(data.NIM1_BIM);
                $('p#NPP_BIM').html(data.NPP_BIM);
                $('p#Jenis_BIM').html(data.Jenis_BIM);
                $('p#Judul_BIM').html(data.Judul_BIM);
                $('p#Status_BIM').html(data.Status_BIM);
            }
        });
    }

    function view_data1(id) {
        $.ajax({
            url: site_url + 'form/',
            data: {
                'PenelitianID': id
            },
            cache: false,
            type: "POST",
            success: function(data) {
                form_data();

                data = JSON.parse(data);
                $('p#hidden').html(data.hidden);
                $('p#NIM').html(data.NIM);
                $('p#NIM1').html(data.NIM1);
                $('p#NIM2').html(data.NIM2);
                $('p#NPP').html(data.NPP);
                $('p#Jenis').html(data.Jenis);
                $('p#Judul').html(data.Judul);
                $('#Status').html(data.Status);
                $('#proposal').html(data.proposal);
                $('#input_cetak').html(data.input_cetak);
            }
        });
        localStorage.setItem("PenelitianID", id);
    }
</script>