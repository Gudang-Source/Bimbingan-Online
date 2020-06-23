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
                                        <button id="create" class="btn btn-primary btn-sm" title="Data Create" alt="Data Create"><i class="fas fa-plus"></i> Tambah Users</button>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal"><i class="fas fa-import"></i>Import Users</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="table" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 110px!important;">Action</th>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Last Login</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th>Action</th>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Last Login</th>
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
                                        <label>Username</label>
                                        <div id="username"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <div id="first_name"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <div id="email"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <div id="password"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Retype Password</label>
                                        <div id="repassword"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <div id="phone"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Groups</label>
                                        <div id="groups"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <div id="active"></div>
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
                                        <label>First Name</label>
                                        <p id="first_name"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <p id="last_name"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Username</label>
                                        <p id="username"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <p id="email"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <p id="phone"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <p id="active"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Last Login</label>
                                        <p id="last_login"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Created On</label>
                                        <p id="created_on"></p>
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
    var site_url = site_url() + 'users1/';

    var table;
    $(document).ready(function() {

        table_data();

        table = $('#table').DataTable({

            "processing": true,
            "serverSide": true,
            "order": [],

            "ajax": {
                "url": site_url + 'get_users',
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
                    $('.card-title').text('Create Users');

                    //data = JSON.parse(data);
                    $('#hidden').html(data.hidden);
                    $('#js-config').html(data.jsConfig);
                    $('#username').html(data.username);
                    $('#first_name').html(data.first_name);
                    $('#email').html(data.email);
                    $('#password').html(data.password);
                    $('#repassword').html(data.repassword);
                    $('#phone').html(data.phone);
                    $('#groups').html(data.groups);
                    $('#active').html(data.active);

                    $(".chosen-select").chosen();
                }
            });
        });

        $('#submit').click(function() {
            $.ajax({
                url: site_url + 'save_users/',
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

        $('.card-title').text('Users List');
    }

    function form_data() {
        $('#hidden').empty();
        $('#username').empty();
        $('#first_name').empty();
        $('#email').empty();
        $('#password').empty();
        $('#repassword').empty();
        $('#phone').empty();
        $('#groups').empty();
        $('#active').empty();

        $('#table-data').hide();
        $('#form-data').show();
        $('#form-view').hide();
    }

    function form_view() {
        $('p#hidden').empty();
        $('p#first_name').empty();
        $('p#username').empty();
        $('p#email').empty();
        $('p#phone').empty();
        $('p#active').empty();
        $('p#last_login').empty();
        $('p#created_on').empty();

        $('#table-data').hide();
        $('#form-data').hide();
        $('#form-view').show();

        $('.card-title').text('View Users');
    }

    function view_data(id) {
        $.ajax({
            url: site_url + 'view/',
            data: {
                'id': id
            },
            cache: false,
            type: "POST",
            success: function(data) {
                form_view();

                data = JSON.parse(data);
                $('p#hidden').html(data.hidden);
                $('p#first_name').html(data.first_name);
                $('p#username').html(data.username);
                $('p#email').html(data.email);
                $('p#phone').html(data.phone);
                $('p#active').html(data.active);
                $('p#last_login').html(data.last_login);
                $('p#created_on').html(data.created_on);

            }
        });
    }

    function update_data(id) {
        $.ajax({
            url: site_url + 'form_data/',
            data: {
                'id': id
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
                // $('#NIM').html(data.NIM);
                // $('input[name=NIM]').prop('readonly', true);
                $('#username').html(data.username);
                $('#first_name').html(data.first_name);
                $('#email').html(data.email);
                $('#password').html(data.password);
                $('#repassword').html(data.repassword);
                $('#phone').html(data.phone);
                $('#groups').html(data.groups);
                $('#active').html(data.status);

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
                    'id': id
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