<style type="text/css" media="screen">
    .h7 {
        font-size: 14px;
    }

    .h8 {
        font-size: 10px;
    }

    .scroll {
        max-height: 400px;
        overflow-y: auto;
    }

    .bg-back {
        background-color: #E5DDD5;
    }

    .bg-chat {
        background-color: #DCF8C6;
    }
</style>
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
        <div class="col-12 float-right">
            <div class="row">
                <div class="col-4">
                    <div class="card" style="max-height: 430px; height: 100%;">
                        <div class="card-header bg-gradient-lightblue border-0">
                            <div class="row">
                                <h6 class="mt-2 mr-5 pr-4">My Chat</h6>
                                <a class="btn ml-5" data-toggle="collapse" href="#tambah" role="button" aria-expanded="false" aria-controls="tambah" id="create">
                                    <i class="fas fa-plus text-white ml-5 pl-5"></i>
                                </a>
                            </div>
                        </div>
                        <div class="collapse" id="tambah">
                            <div class="card scroll" style="max-height: 338px; height: 100%;">
                                <div class="card-body">

                                    <form role="form" action="" method="post" id="form-tambah">
                                        <ul class="nav">

                                            <div id="listtambah"></div>

                                        </ul>
                                        <button type="button" id="tambah-pesan" class="btn btn-primary btn-sm mt-3" data-toggle="collapse" data-target="#tambah" aria-expanded="false" aria-controls="tambah">Chat Sekarang</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="scroll">
                            <div class="col-12">

                                <form role="form" action="" method="post" id="group">

                                    <div id="grouppesan"></div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card" style="max-height: 430px; height: 100%;">
                        <div class="card-header bg-gradient-lightblue" style="height: 60px;">

                            <div class="form-inline" id="form-inline">
                                <i class="fas fa-user-circle fa-2x my-1"></i>
                                <div id="header" class="mr-5"></div>
                                <button type="button" class="btn btn-default btn-sm" style="margin-left: 49%;" data-toggle="modal" data-target="#modal-default">Catatan</button>
                            </div>

                        </div>
                        <div class="card-body bg-back scroll" style="height: 400px;">

                            <div id="chat-pesan">
                                <div id="isipesan"></div>
                            </div>

                        </div>
                        <div id="form-pesan">
                            <div class="card-footer">

                                <form role="form" action="" method="post" id="save-pesan">
                                    <div class="row">
                                        <div id="GroupPesanID"></div>
                                        <div id="GroupPesanID1"></div>
                                        <div class="col-10">
                                            <textarea name="pesan" id="txtarea" spellcheck="true" class="form-control" rows="1" style="max-height: 300px;"></textarea>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn bg-gradient-lightblue ml-4" id="submit">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" action="" method="post" id="save-catatan">
                <div class="modal-body">
                    <div id="catatan"></div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-update">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    var site_url = site_url() + 'pesandosen/';

    var table;
    $(document).ready(function() {

        table_data();



        $('#grouppesan').ready(function() {
            $.ajax({
                url: site_url + 'get_pesan/',
                cache: false,
                type: "POST",
                dataType: "json",
                success: function(data) {

                    $('#grouppesan').html(data.list);
                }
            });
            // setTimeout(ready(function() {}), 1000)
        });

        $('#save-update').click(function() {
            $.ajax({
                url: site_url + 'save_catatan/',
                type: "POST",
                data: new FormData($('#save-catatan')[0]),
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

        $('#create').click(function() {
            $.ajax({
                url: site_url + 'tambah/',
                cache: false,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    form_tambah();
                    $('#listtambah').html(data.tambah);
                }
            });
        });

        $('#tambah-pesan').click(function() {
            $.ajax({
                url: site_url + 'formgroup/',
                type: "POST",
                data: new FormData($('#form-tambah')[0]),
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    table_data();
                    table.draw(false);

                },
                complete: function(data) {
                    setTimeout(list, 1000);
                    setTimeout(create, 1000);
                }
            });
            // setInterval(list, 1000);
        });

        $('#submit').click(function() {
            $.ajax({
                url: site_url + 'save_pesan/',
                type: "POST",
                data: new FormData($('#save-pesan')[0]),
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    form_view()
                },
                complete: function(data) {
                    setTimeout(view_data(localStorage.getItem("GroupPesanID")), 500);
                }
            });
            document.getElementById('txtarea').value = '';
            $(".bg-back").stop().animate({
                scrollTop: $(".bg-back")[0].scrollHeight
            }, 2000);
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

    function create() {
        $('#create').click(function() {
            $.ajax({
                url: site_url + 'tambah/',
                cache: false,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    form_tambah();
                    $('#listtambah').html(data.tambah);
                }
            });
        });
    }

    function list() {
        $('#grouppesan').ready(function() {
            $.ajax({
                url: site_url + 'get_pesan/',
                cache: false,
                type: "POST",
                dataType: "json",
                success: function(data) {

                    $('#grouppesan').html(data.list);
                }
            });
            // setTimeout(ready(function() {}), 1000)
        });
    }

    function table_data() {
        $('#table-data').show();
        $('#form-data').hide();
        $('#form-view').hide();
        $('#form-inline').hide();
        $('#chat-pesan').hide();
        $('#form-pesan').hide();

        $('.card-title').text('Bimbingan Online');
        $('#modal-default').modal('hide');
    }

    function form_tambah() {
        $('#form-tambah').show();

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
        $('#header').empty();

        $('#form-inline').show();
        $('#chat-pesan').show();
        $('#form-pesan').show();


        // setTimeout(view_data, 500);
    }

    function view_data(id) {
        $.ajax({
            url: site_url + 'view/',
            data: {
                'GroupPesanID': id
            },
            cache: false,
            type: "POST",
            success: function(data) {
                form_view();

                data = JSON.parse(data);
                $('#header').html(data.header);
                $('#GroupPesanID').html(data.GroupPesanID);
                $('#isipesan').html(data.isipesan);
                $('#catatan').html(data.catatan);

            }
        });
        localStorage.setItem("GroupPesanID", id);
        $(".bg-back").stop().animate({
            scrollTop: $(".bg-back")[0].scrollHeight + $(".bg-back")[0].clientHeight
        }, 2000);
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

    function expandTextarea(id) {
        document.getElementById(id).addEventListener('keyup', function() {
            this.style.overflow = 'hidden';
            this.style.height = 0;
            this.style.height = this.scrollHeight + 'px';
        }, false);
    }

    expandTextarea('txtarea');
</script>