<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>General Form</h1>
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
                        <div class="container-fluid bg-white">
                            <div class="card border-0 mx-3 my-4">
                                <div class="card border-0">
                                    <img src="<?php echo base_url() . 'assets/'; ?>img/header4.png" alt="" class="img-fluid rounded-top">
                                </div>
                                <div class="row mx-3">
                                    <div class="col-3">
                                        <div class="card w-75 ml-3 overflow-hidden" style="height: 150px; margin-top: -100px;">
                                            <img src="<?php echo base_url() . 'assets/'; ?>img/computer.png" alt="" class="img-fluid mt-3">
                                        </div>
                                        <div class="ml-3">
                                            <strong><i class="fas fa-envelope mt-3 mr-1"></i> Email</strong>
                                            <p class="text-muted">
                                                Alvianrizky62@gmail.com
                                            </p>

                                            <strong><i class="fas fa-phone mr-1"></i> Whatsapp</strong>
                                            <p class="text-muted">
                                                082157699833
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="card border-primary mt-2 mb-4">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <p class="card-text">Nama</p>
                                                        <p class="card-text">NIM</p>
                                                        <p class="card-text">Program Studi</p>
                                                        <p class="card-text">Angkatan</p>
                                                    </div>
                                                    <div class="col-9">
                                                        <p class="card-text">Muhammad Alvian Rizky
                                                            <button id="create" class="btn btn-primary btn-sm float-right" title="Data Create" alt="Data Create">Edit Data</button>
                                                        </p>
                                                        <p class="card-text">12171568</p>
                                                        <p class="card-text">Teknik Informatika</p>
                                                        <p class="card-text">2017</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                        <label>KodeKriteria</label>
                                        <div id="KodeKriteria"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>NamaKriteria</label>
                                        <div id="NamaKriteria"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Atribut</label>
                                        <div id="Atribut"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Bobot</label>
                                        <div id="Bobot"></div>
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

                </div><!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<script type="text/javascript">
    var site_url = site_url() + 'kriteria/';

    var table;
    $(document).ready(function() {

        table_data();

        table = $('#table').DataTable({

            "processing": true,
            "serverSide": true,
            "order": [],

            "ajax": {
                "url": site_url + 'get_kriteria',
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
                    $('.card-title').text('Create Kriteria');

                    //data = JSON.parse(data);
                    $('#hidden').html(data.hidden);
                    $('#js-config').html(data.jsConfig);
                    $('#KodeKriteria').html(data.KodeKriteria);
                    $('#NamaKriteria').html(data.NamaKriteria);
                    $('#Atribut').html(data.Atribut);
                    $('#Bobot').html(data.Bobot);

                    $(".chosen-select").chosen();
                }
            });
        });

        $('#submit').click(function() {
            $.ajax({
                url: site_url + 'save_kriteria/',
                type: "POST",
                data: new FormData($('#form-data')[0]),
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
                    alert('Error adding / update data');
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

        $('.card-title').text('Profil Mahasiswa');
    }

    function form_data() {
        $('#hidden').empty();
        $('#KriteriaID').empty();
        $('#KodeKriteria').empty();
        $('#NamaKriteria').empty();
        $('#Atribut').empty();
        $('#Bobot').empty();

        $('#table-data').hide();
        $('#form-data').show();
        $('#form-view').hide();
    }

    function form_view() {
        $('p#hidden').empty();
        $('p#KriteriaID').empty();
        $('p#KodeKriteria').empty();
        $('p#NamaKriteria').empty();
        $('p#Atribut').empty();
        $('p#Bobot').empty();

        $('#table-data').hide();
        $('#form-data').hide();
        $('#form-view').show();

        $('.card-title').text('View Kriteria');
    }

    function view_data(id) {
        $.ajax({
            url: site_url + 'view/',
            data: {
                'KriteriaID': id
            },
            cache: false,
            type: "POST",
            success: function(data) {
                form_view();

                data = JSON.parse(data);
                $('p#hidden').html(data.hidden);
                $('p#KriteriaID').html(data.KriteriaID);
                $('p#KodeKriteria').html(data.KodeKriteria);
                $('p#NamaKriteria').html(data.NamaKriteria);
                $('p#Atribut').html(data.Atribut);
                $('p#Bobot').html(data.Bobot);

            }
        });
    }

    function update_data(id) {
        $.ajax({
            url: site_url + 'form_data/',
            data: {
                'KriteriaID': id
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
                $('#KodeKriteria').html(data.KodeKriteria);
                $('input[name=KodeKriteria]').prop('readonly', true);
                $('#NamaKriteria').html(data.NamaKriteria);
                $('#Atribut').html(data.Atribut);
                $('#Bobot').html(data.Bobot);

                $(".chosen-select").chosen();
            }
        });
    }

    function delete_data(id) {
        var agree = confirm("Are you sure you want to delete this item?");
        if (agree) {
            $.ajax({
                url: site_url + 'delete/',
                data: {
                    'KriteriaID': id
                },
                cache: false,
                type: "POST",
                dataType: "JSON", //Tidak Usah Memakai JSON.parse(data);
                success: function(data) {
                    $('#notifications').append(data.message);
                    if (data.code == 0) table.draw(false);
                    table_data();
                }
            });
        } else
            return false;
    }
</script>