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
                        <h3 class="card-title">sss </h3>
                    </div><!-- /.card-header -->

                    <div id="table-data">
                        <div class="card-body">
                            <!-- <form role="form" method="POST" action="" id="form-filter"> -->
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <button id="kerjapraktek" class="btn btn-primary btn-sm" title="Data Create"> SK Penelitian Kerja Praktek<i class="fas fa-plus ml-2"></i></button>

                                            <button id="skripsi" class="btn btn-primary btn-sm" title="Data Create"> SK Penelitian Skripsi<i class="fas fa-plus ml-2"></i></button>

                                            <button type="submit" class="btn btn-danger btn-sm" id="filter">Cetak Laporan<i class="fas fa-print ml-2" ></i></button>



                                        </div>
                                        <?php 

                                        $jenis = array(
                                            'Skripsi' => 'Skripsi',
                                            'Kerja Praktek' => 'Kerja Praktek');
                                        if(!empty($Jenis))
                                        {
                                        ?>
                                        <div class="col-3">
                                            <?php echo form_dropdown('Jenis', $jenis, $Jenis->Jenis, 'class="form-control mt-5" id="Jenis"'); ?>
                                        </div>
                                        <div class="col-3">
                                            <?php echo form_dropdown('tahun', $opt_tahun, $tahunakademik->TahunAkademikID, 'class="form-control mt-5" id="tahun"'); ?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <!-- </form> -->
                            
                            <div class="table-responsive">
                                <table id="table" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nomer SK</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Pembimbing</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Nomer SK</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Pembimbing</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <form role="form" method="POST" action="" id="form-kp" enctype="multipart/form-data">
                        <div class="card-body mt-n3" id="kosong">
                            <div class="row">
                                <div class="col-6 mt-3">

                                    <div id="hidden"></div>
                                    <div id="js-config"></div>

                                    <div class="form-group">
                                        <label>Nomer SK</label>
                                        <div id="NomerSK"></div>
                                    </div>
                                    <div class="col-12">
                                        <button type="button" name="submit" id="submit-kp" class="btn btn-primary btn-sm ml-n2">Submit Data</button> &nbsp; &nbsp;
                                        <button type="reset" name="reset" class="btn btn-default btn-sm">Reset Data</button>

                                        <button type="button" name="back" class="btn btn-primary float-right btn-sm mr-n2" onClick="table_data();">Back Button</button>
                                    </div>

                                </div>
                                <div class="col-6 mt-3 px-5">
                                    <h5>Daftar Penelitian</h5>
                                    <div class="card py-2 scroll" style="max-height: 270px; height: auto;">

                                        <div id="daftar"></div>

                                    </div> 
                                </div>
                            </div>
                        </div>

                    </form>

                    <form role="form" method="POST" action="" id="form-skripsi" enctype="multipart/form-data">
                        <div class="card-body" id="kosong1">
                            <div class="row">
                                <div class="col-6">

                                    <div class="form-group">
                                        <label>Nomer SK 111111</label>
                                        <div id="NomerSK1"></div>
                                    </div>

                                    <div class="col-12">
                                        <button type="button" name="submit" id="submit-skripsi" class="btn btn-primary btn-sm ml-n2">Submit Data</button> &nbsp; &nbsp;
                                        <button type="reset" name="reset" class="btn btn-default btn-sm">Reset Data</button>

                                        <button type="button" name="back" class="btn btn-primary float-right btn-sm mr-n2" onClick="table_data();">Back Button</button>
                                    </div>

                                </div>
                                <div class="col-6 mt-n5 px-5">
                                    <h5>Daftar Penelitian</h5>
                                    <div class="card py-2 scroll" style="max-height: 270px; height: auto;">

                                        <div id="daftar1"></div>

                                    </div> 
                                </div>

                            </div>
                        </div>

                    </form>

                </div><!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

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
                                    <label>Jenis Penelitian</label>
                                    <?php echo form_dropdown('Jenis1', $jenis, $Jenis->Jenis, 'class="form-control" id="Jenis1"'); ?>
                                </div>
                                <div class="form-group">
                                    <label>Tahun Akademik</label>
                                    <?php echo form_dropdown('Tahun1', $opt_tahun, $tahunakademik->TahunAkademikID, 'class="form-control" id="Tahun1"'); ?>
                                </div>
                                <button type="button" class="btn btn-primary" id="cetak">Cetak</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var site_url = site_url() + 'skpenelitian/';

    var table;
    $(document).ready(function() {

        table_data();

        table = $('#table').DataTable({

            "processing": true,
            "serverSide": true,
            "order": [],

            "ajax": {
                "url": site_url + 'get_sk',
                "type": "POST",
                "data": function(data) {
                    data.tahun = $('#tahun').val();
                    data.Jenis = $('#Jenis').val();
                }
            },

            "columnDefs": [{
                "targets": [0],
                "orderable": false,
            }, ],
        });

        $('#tahun,#Jenis').change(function() { //button filter event click
            table.ajax.reload(); //just reload table
        });

        $('#filter').click(function() {
            $.ajax({
                url: site_url + 'filter/',
                cache: false,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    if (data.code == 1) {
                        Swal.fire({
                            icon: data.icon,
                            title: data.title,
                            text: data.message,
                            showConfirmButton: false,
                            showCloseButton: true
                        });
                    } if(data.code == 0) {
                        form_view();
                    }
                }
            });
        });

        $('#cetak').click(function() {
            $.ajax({
                url: site_url + 'cetak/',
                type: "POST",
                data: new FormData($('#form-view')[0]),
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    
                }
            });
            table_data();
        });

        $('#kerjapraktek').click(function() {
            $.ajax({
                url: site_url + 'form_kp/',
                cache: false,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $(".chosen-select").chosen("destroy");
                    form_kp();
                    $('.card-title').text('SK Penelitian Kerja Praktek');

                    $('#NomerSK').html(data.NomerSK);
                    $('#daftar').html(data.daftar);
                    $('#kosong').html(data.kosong);

                    $(".chosen-select").chosen();
                }
            });
        });

        $('#skripsi').click(function() {
            $.ajax({
                url: site_url + 'form_skripsi/',
                cache: false,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $(".chosen-select").chosen("destroy");
                    form_skripsi();
                    $('.card-title').text('SK Penelitian Skripsi');

                    //data = JSON.parse(data);
                    $('#NomerSK1').html(data.NomerSK1);
                    $('#daftar1').html(data.daftar1);
                    $('#kosong1').html(data.kosong1);

                    $(".chosen-select").chosen();
                }
            });
        });

        $('#submit-skripsi').click(function() {
            $.ajax({
                url: site_url + 'save_skripsi/',
                type: "POST",
                data: new FormData($('#form-skripsi')[0]),
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.code == 1) {
                        Swal.fire({
                            icon: data.icon,
                            title: data.title,
                            text: data.message,
                            showConfirmButton: false,
                            showCloseButton: true
                        });
                    } else {
                        Swal.fire({
                            icon: data.icon,
                            title: data.title,
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        table_data();
                        table.draw(false);

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire('Warning!', 'Error adding / update data', 'error');
                }
            });
        });

        $('#submit-kp').click(function() {
            $.ajax({
                url: site_url + 'save_kp/',
                type: "POST",
                data: new FormData($('#form-kp')[0]),
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.code == 1) {
                        Swal.fire({
                            icon: data.icon,
                            title: data.title,
                            text: data.message,
                            showConfirmButton: false,
                            showCloseButton: true
                        });
                    } else {
                        Swal.fire({
                            icon: data.icon,
                            title: data.title,
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        table_data();
                        table.draw(false);

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire('Warning!', 'Error adding / update data', 'error');
                }
            });
        });


    });

    function table_data() {
        $('#table-data').show();
        $('#form-kp').hide();
        $('#form-skripsi').hide();

        $('.card-title').text('Sk Penelitian');
        $('#modal-default').modal('hide');
    }

    function form_view() {
        $('#form-view').show();

        $('#modal-default').modal('show');
        $('.modal-title').text('Filter Sk');
    }

    function form_kp() {
        // $('p#Status').empty();

        $('#table-data').hide();
        $('#form-kp').show();
        $('#form-skripsi').hide();
        $('.modal-title').text('Detail Penelitian');
    }

    function form_skripsi() {
        // $('p#Status').empty();

        $('#table-data').hide();
        $('#form-kp').hide();
        $('#form-skripsi').show();
        $('.modal-title').text('Detail Penelitian');
    }

    
</script>