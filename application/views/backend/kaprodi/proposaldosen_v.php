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
                            <div class="table-responsive">
                                <table id="table" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Nama Dosen</th>
                                            <th>Jenis Penelitian</th>
                                            <th>Judul Penelitian</th>
                                            <th>Laporan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Nama Dosen</th>
                                            <th>Jenis Penelitian</th>
                                            <th>Judul Penelitian</th>
                                            <th>Laporan</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    

                    <form role="form" method="POST" action="" id="form-view">
                        <div class="card-body">
                            <div class="row">
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

<script type="text/javascript">
    var site_url = site_url() + 'proposaldosen/';

    var table;
    $(document).ready(function() {

        table_data();

        table = $('#table').DataTable({

            "processing": true,
            "serverSide": true,
            "order": [],

            "ajax": {
                "url": site_url + 'get_proposal',
                "type": "POST"
            },

            "columnDefs": [{
                "targets": [0],
                "orderable": false,
            }, ],
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
        $('p#NIM').empty();
        $('p#Nama').empty();
        $('p#Prodi').empty();
        $('p#Angkatan').empty();

        $('#table-data').hide();
        $('#form-data').hide();
        $('#form-view').show();

        $('.card-title').text('View Laporan');
    }

    function view_data(id) {
        $.ajax({
            url: site_url + 'view/',
            data: {
                'PenelitianID': id
            },
            cache: false,
            type: "POST",
            success: function(data) {
                form_view();

                data = JSON.parse(data);
                $('p#hidden').html(data.hidden);
                $('#proposal').html(data.proposal);

            }
        });
    }
</script>