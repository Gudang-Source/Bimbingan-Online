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
                                <div class="row">
                                    <div class="col-lg-6">
                                        <button id="create" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-plus"></i> Tambah Mahasiswa</button>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-import"></i>Import Mahasiswa</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="table" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 110px!important;">Action</th>
                                            <th>No</th>
                                            <th>NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Program Studi</th>
                                            <th>Angkatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th>Action</th>
                                            <th>No</th>
                                            <th>NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Program Studi</th>
                                            <th>Angkatan</th>
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
                                        <label>Program Studi</label>
                                        <div id="Prodi"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Angkatan</label>
                                        <div id="Angkatan"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" name="submit" id="submit" class="btn btn-primary">Submit Data</button> &nbsp; &nbsp;
                            <button type="reset" name="reset" class="btn btn-default">Reset Data</button>

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
                                        <p id="Nama"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Program Studi</label>
                                        <p id="Prodi"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Angkatan</label>
                                        <p id="Angkatan"></p>
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
    var site_url = site_url() + 'mahasiswa/';

    var table;
    $(document).ready(function() {

        table_data();

        table = $('#table').DataTable({

            "processing": true,
            "serverSide": true,
            "order": [],

            "ajax": {
                "url": site_url + 'get_mahasiswa',
                "type": "POST"
            },

            "columnDefs": [{
                "targets": [0],
                "orderable": false,
            }, ],
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
                    $('#NIM').html(data.NIM);
                    $('#Nama').html(data.Nama);
                    $('#Prodi').html(data.Prodi);
                    $('#Angkatan').html(data.Angkatan);

                    $(".chosen-select").chosen();
                }
            });
        });

        $('#submit').click(function() {
            $.ajax({
                url: site_url + 'save_mahasiswa/',
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

        $('.card-title').text('Mahasiswa List');
    }

    function form_data() {
        $('#hidden').empty();
        $('#NIM').empty();
        $('#Nama').empty();
        $('#Prodi').empty();
        $('#Angkatan').empty();

        $('#table-data').hide();
        $('#form-data').show();
        $('#form-view').hide();
    }

    function form_view() {
        $('p#hidden').empty();
        $('p#NIM').empty();
        $('p#Nama').empty();
        $('p#Prodi').empty();
        $('p#Angkatan').empty();

        $('#table-data').hide();
        $('#form-data').hide();
        $('#form-view').show();

        $('.card-title').text('View Mahasiswa');
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
                $('p#NIM').html(data.NIM);
                $('p#Nama').html(data.Nama);
                $('p#Prodi').html(data.Prodi);
                $('p#Angkatan').html(data.Angkatan);

            }
        });
    }

    function update_data(id) {
        $.ajax({
            url: site_url + 'form_data/',
            data: {
                'NIM': id
            },
            cache: false,
            type: "POST",
            success: function(data) {
                $(".chosen-select").chosen("destroy");
                form_data();
                $('.card-title').text('Update Mahasiswa');

                data = JSON.parse(data);
                $('#hidden').html(data.hidden);
                // $('#KriteriaID').html(data.KriteriaID);
                $('#NIM').html(data.NIM);
                $('input[name=NIM]').prop('readonly', true);
                $('#Nama').html(data.Nama);
                $('#Prodi').html(data.Prodi);
                $('#Angkatan').html(data.Angkatan);

                $(".chosen-select").chosen();
            }
        });
    }

    function delete_data(id) {
        Swal.fire({
            title: 'Apa anda yakin ?',
            text: "Data akan dihapus dari database !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise(function(resolve) {
                    $.ajax({
                        url: site_url + 'delete/',
                        data: {
                            'NIM': id
                        },
                        type: "POST",
                        dataType: 'json'
                    })
                    .done(function(data) {
                        Swal.fire({
                            icon: data.icon,
                            title: 'Deleted!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        if (data.code == 0) table.draw(false);
                        table_data();
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