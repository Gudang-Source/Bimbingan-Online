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
                            <div class="form-group">   
                                <div class="col-3 text-center mx-auto">
                                    <div class="form-group">
                                        <label>Filter Tahun Akademik</label>
                                        <?php echo form_dropdown('tahun', $opt_tahun, $tahunakademik->TahunAkademikID, 'class="form-control" id="tahun"'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="table" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Jenis Pengajuan</th>
                                            <th>Judul Penelitian</th>
                                            <th style="width: 100px!important;">Status</th>
                                            <th style="width: 120px!important;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Jenis Pengajuan</th>
                                            <th>Judul Penelitian</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <form role="form" method="POST" action="" id="form-data" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div id="hidden"></div>
                                    <div id="js-config"></div>
                                    <div class="form-group">
                                        <label>NIM</label>
                                        <div id="NIM"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Mahasiswa</label>
                                        <div id="Nama"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Penelitian</label>
                                        <div id="JenisPengajuan"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Judul Penelitian</label>
                                        <div id="JudulPenelitian"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Dosen Pembimbing</label>
                                        <div id="NPP"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" name="submit" id="submit" class="btn btn-primary">Submit Data</button> &nbsp; &nbsp;

                            <button type="button" name="back" class="btn btn-primary float-right" onClick="table_data();">Back Button</button>
                        </div>
                    </form>

                    <form role="form" method="POST" action="" id="form-view">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>NIM</label>
                                        <p id="NIM"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Mahasiswa</label>
                                        <p id="NIM1"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Pengajuan</label>
                                        <p id="JenisPengajuan"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Judul Penelitian</label>
                                        <p id="JudulPenelitian"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <p id="Status"></p>
                                    </div>
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



<script type="text/javascript">
    var site_url = site_url() + 'pengajuandosen/';

    var table;
    $(document).ready(function() {

        table_data();

        table = $('#table').DataTable({

            "processing": true,
            "serverSide": true,
            "order": [],

            "ajax": {
                "url": site_url + 'get_pengajuan',
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

        $('#create').click(function() {
            $.ajax({
                url: site_url + 'form_data/',
                cache: false,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $(".chosen-select").chosen("destroy");
                    form_data();
                    $('.card-title').text('Create Mahasiswa');

                    //data = JSON.parse(data);
                    $('#hidden').html(data.hidden);
                    $('#js-config').html(data.jsConfig);
                    $('#JenisPengajuan').html(data.JenisPengajuan);
                    $('#JudulPenelitian').html(data.JudulPenelitian);

                    $(".chosen-select").chosen();
                }
            });
        });

        $('#submit').click(function() {
            $.ajax({
                url: site_url + 'save_pengajuan/',
                type: "POST",
                data: new FormData($('#form-data')[0]),
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
                            timer: 1500
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

        $('#import').click(function() {
            $.ajax({
                url: site_url + 'upload/',
                type: "POST",
                data: new FormData($('#import-data')[0]),
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.code == 1) {
                        $('#notifications').append(data.message);
                    } else {
                        $('#notifications').append(data.message);
                        table_data();
                        table.draw(false);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / import data');
                }
            });
        });

    });

    function table_data() {
        $('#table-data').show();
        $('#form-data').hide();
        $('#form-view').hide();

        $('.card-title').text('Pengajuan List');
    }

    function form_data() {
        $('#hidden').empty();
        $('#JenisPengajuan').empty();
        $('#JudulPenelitian').empty();
        $('#Status').empty();

        $('#table-data').hide();
        $('#form-data').show();
        $('#form-view').hide();
    }

    function form_view() {
        $('p#hidden').empty();
        $('p#NIM').empty();
        $('p#NIM1').empty();
        $('p#JenisPengajuan').empty();
        $('p#JudulPenelitian').empty();
        $('p#Status').empty();

        $('#table-data').hide();
        $('#form-data').hide();
        $('#form-view').show();

        $('.card-title').text('View Mahasiswa');
    }

    function view_data(id) {
        $.ajax({
            url: site_url + 'view/',
            data: {
                'PengajuanID': id
            },
            cache: false,
            type: "POST",
            success: function(data) {
                form_view();

                data = JSON.parse(data);
                $('p#hidden').html(data.hidden);
                $('p#NIM').html(data.NIM);
                $('p#NIM1').html(data.NIM1);
                $('p#JenisPengajuan').html(data.JenisPengajuan);
                $('p#JudulPenelitian').html(data.JudulPenelitian);
                $('p#Status').html(data.Status);

            }
        });
    }

    function update_data(id) {
        $.ajax({
            url: site_url + 'form_data/',
            data: {
                'PengajuanID': id
            },
            cache: false,
            type: "POST",
            success: function(data) {
                $(".chosen-select").chosen("destroy");
                form_data();
                $('.card-title').text('Tambah Dosen Pembimbing');

                data = JSON.parse(data);
                $('#hidden').html(data.hidden);
                $('#NIM').html(data.NIM);
                $('input[name=NIM]').prop('readonly', true);
                $('#Nama').html(data.Nama);
                $('input[name=Nama]').prop('readonly', true);
                $('#JenisPengajuan').html(data.JenisPengajuan);
                $('input[name=JenisPengajuan]').prop('readonly', true);
                $('#JudulPenelitian').html(data.JudulPenelitian);
                $('input[name=JudulPenelitian]').prop('readonly', true);
                $('#NPP').html(data.NPP);

                $(".chosen-select").chosen();
            }
        });
    }

    function save_setuju(id) {
        Swal.fire({
            title: 'Apa anda yakin ?',
            text: "Anda akan menyetujui judul penelitian ini !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise(function(resolve) {
                    $.ajax({
                        url: site_url + 'save_setuju/',
                        data: {
                            'PengajuanID': id
                        },
                        type: "POST",
                        dataType: 'json'
                    })
                    .done(function(data) {
                        if (data.code == 1) {
                            Swal.fire({
                                icon: data.icon,
                                title: data.title,
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                        else if(data.code == 2) 
                        {
                            Swal.fire({
                                icon: data.icon,
                                title: data.title,
                                text: data.message,
                                showConfirmButton: false,
                                showCloseButton: true
                            // timer: 1500
                            });
                            table_data();
                            table.draw(false);
                        }
                        else {
                            Swal.fire({
                                icon: data.icon,
                                title: data.title,
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            update_data(id)
                        }
                    })
                    .fail(function() {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
                });
            },
            allowOutsideClick: false
        });

    }

    function save_tolak(id) {
        Swal.fire({
            title: 'Apa anda yakin ?',
            text: "Judul penelitian ini tidak sesuai dengan syarat yang ada !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise(function(resolve) {
                    $.ajax({
                        url: site_url + 'save_tolak/',
                        data: {
                            'PengajuanID': id
                        },
                        type: "POST",
                        dataType: 'json'
                    })
                    .done(function(data) {
                        if (data.code == 1) {
                            Swal.fire({
                                icon: data.icon,
                                title: data.title,
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                        else if(data.code == 2) 
                        {
                            Swal.fire({
                                icon: data.icon,
                                title: data.title,
                                text: data.message,
                                showConfirmButton: false,
                                showCloseButton: true
                            // timer: 1500
                        });
                            table_data();
                            table.draw(false);
                        }
                        else {
                            Swal.fire({
                                icon: data.icon,
                                title: data.title,
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            update_data(id)
                        }
                    })
                    .fail(function() {
                        Swal.fire('Oops...', 'Something went wrong with ajax !', 'error');
                    });
                });
            },
            allowOutsideClick: false
        });
    }
</script>