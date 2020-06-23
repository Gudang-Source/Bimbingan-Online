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
        <?php
        if ($this->session->flashdata('success')) {
            echo notifications('success', $this->session->flashdata('success'));
        }
        if ($this->session->flashdata('error')) {
            echo notifications('error', $this->session->flashdata('error'));
        }
        if (validation_errors()) {
            echo notifications('warning', validation_errors('<p>', '</p>'));
        }
        ?>
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">

                <?php
                if (isset($page)) {
                    $this->session->set_flashdata('page', $page);
                    $this->session->keep_flashdata('offset');
                    $this->session->keep_flashdata('q');


                    switch ($page) {
                        case 'view':
                ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- general form elements -->
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title"><?php echo isset($panel_heading) ? $panel_heading : ''; ?></h3>
                                        </div><!-- /.box-header -->

                                        <form class="form">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-8">
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <p><?php echo isset($name) ? $name : ''; ?></p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Description</label>
                                                            <p><?php echo isset($description) ? $description : ''; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-footer">
                                                <button type="button" name="edit" class="btn btn-primary" onClick="location.href='<?php echo site_url('users/update/' . $id); ?>'">Edit Data</button> &nbsp; &nbsp;
                                                <button type="button" name="back" class="btn btn-primary float-right" onClick="location.href='<?php echo site_url('groups'); ?>'">Back Button</button>
                                            </div>
                                        </form>
                                    </div><!-- /.box -->
                                </div>
                            </div>
                        <?php
                            break;

                        case 'add':
                        case 'update':
                        ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <!-- general form elements -->
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title"><?php echo isset($panel_heading) ? $panel_heading : ''; ?></h3>
                                        </div><!-- /.box-header -->
                                        <form role="form" method="POST" action="<?php echo $action; ?>">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input class="form-control" name="name" id="name" value="<?php echo isset($name) ? $name : ''; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Description</label>
                                                            <input type="description" class="form-control" name="description" id="description" value="<?php echo isset($description) ? $description : ''; ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-footer">
                                                <input type="submit" name="submit" class="btn btn-primary" value="Submit Data"> &nbsp; &nbsp;
                                                <button type="reset" name="reset" class="btn btn-default">Reset Data</button>
                                                <button type="button" name="back" class="btn btn-primary float-right" onClick="location.href='<?php echo site_url('groups'); ?>'">Back Button</button>
                                            </div>
                                        </form>
                                    </div><!-- /.box -->
                                </div>
                            </div>
                        <?php
                            break;

                        default:
                        ?>
                            <!-- general form elements -->
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title"><?php echo isset($panel_heading) ? $panel_heading : ''; ?></h3>
                                </div><!-- /.box-header -->
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <button id="add" class="btn btn-primary btn-sm" title="Data Add" alt="Data Add" onClick="location.href='<?php echo site_url('groups/create'); ?>'"><i class="fas fa-plus"></i> Add Users</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive" id="table-responsive">
                                        <?php echo isset($table) ? $table : ''; ?>
                                    </div>
                                </div>
            </div><!-- /.box -->
<?php
                            break;
                    }
                }
?>

        </div>
        <!--/.col (right) -->
    </div> <!-- /.row -->
    </div>
</section><!-- /.content -->