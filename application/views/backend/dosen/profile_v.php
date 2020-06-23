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
                                                <div id="Email"></div>
                                            </p>

                                            <strong><i class="fas fa-phone mr-1"></i> Whatsapp</strong>
                                            <p class="text-muted">
                                                <div id="Phone"></div>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-9 mt-4">
                                        <div class="card border-primary mt-2 mb-4">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <p class="card-text">Nama</p>
                                                        <p class="card-text">NPP</p>
                                                        <p class="card-text">Jabatan</p>
                                                    </div>
                                                    <div class="col-7">
                                                        <p class="card-text" id="Nama"></p>
                                                            
                                                        
                                                        <p class="card-text" id="NPP">
                                                        </p>
                                                        <p class="card-text" id="Jabatan">
                                                        </p>
                                                    </div>
                                                    <div class="col-2">
                                                        <button id="create" class="btn btn-primary btn-sm float-right" title="Data Create" alt="Data Create">Edit Data</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


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

                <form role="form" method="POST" action="" id="form-data">
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-lg-12">
                                <div id="hidden"></div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <div id="check"></div>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm" id="submit">Submit</button>
                            </div>

                        </div>
                    </div>
                </form>

                <form role="form" method="POST" action="" id="form-edit">
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-lg-12">
                                <div id="hidden"></div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <div id="email1"></div>
                                </div>
                                <div class="form-group">
                                    <label>No Whatsapp</label>
                                    <div id="Whatsapp"></div>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <div id="password"></div>
                                </div>
                                <div class="form-group">
                                    <label>Retype Password</label>
                                    <div id="repassword"></div>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm" id="submit-edit">Submit</button>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var site_url = site_url() + 'profiledosen/';

    var table;
    $(document).ready(function() {

        table_data();

        $('#table-data').ready(function() {
            $.ajax({
                url: site_url + 'get_profile/',
                cache: false,
                type: "POST",
                dataType: "json",
                success: function(data) {

                    $('p#Nama').html(data.Nama);
                    $('p#NPP').html(data.NPP);
                    $('p#Jabatan').html(data.Jabatan);
                    $('#Email').html(data.Email);
                    $('#Phone').html(data.Phone);
                }
            });
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
                    $('#check').html(data.check);

                    $(".chosen-select").chosen();
                }
            });
        });

        $('#submit').click(function() {
            $.ajax({
                url: site_url + 'check_password/',
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
                            showCloseButton: true
                        });
                    } else {
                        
                        view_edit();
                        // table.draw(false);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire('Warning!', 'Error adding / update data', 'error');
                }
            });
        });

        $('#submit-edit').click(function() {
            $.ajax({
                url: site_url + 'save_edit/',
                type: "POST",
                data: new FormData($('#form-edit')[0]),
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
                        refresh();
                        
                        // table.draw(false);
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

        $('.card-title').text('Profile Users');
        $('#modal-default').modal('hide')
    }

    function form_data() {
        $('#form-data').show();
        $('#form-edit').hide();

        $('#modal-default').modal('show')
        $('.modal-title').text('Check Password');
    }

    function form_edit() {
        $('#form-data').hide();
        $('#form-edit').show();

        $('#modal-default').modal('show')
        $('.modal-title').text('Edit Data');
    }

    function view_edit() {
        $.ajax({
            url: site_url + 'form_edit/',
            cache: false,
            type: "POST",
            dataType: "json",
            success: function(data) {
                form_edit();
                $('#email1').html(data.email1);
                $('#Whatsapp').html(data.Whatsapp);
                $('#password').html(data.password);
                $('#repassword').html(data.repassword);

            }
        });
    }

    function refresh()
    {
        $.ajax({
            url: site_url + 'get_profile/',
            cache: false,
            type: "POST",
            dataType: "json",
            success: function(data) {
                table_data();
                $('p#Nama').html(data.Nama);
                $('p#NPP').html(data.NPP);
                $('p#Jabatan').html(data.Jabatan);
                $('#Email').html(data.Email);
                $('#Phone').html(data.Phone);
            }
        });
    }

</script>