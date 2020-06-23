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
                                                            <label>Username</label>
                                                            <p><?php echo isset($username) ? $username : ''; ?></p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <p><?php echo isset($first_name) ? $first_name : ''; ?></p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <p><?php echo isset($email) ? $email : ''; ?></p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Company</label>
                                                            <p><?php echo isset($company) ? $company : ''; ?></p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Phone</label>
                                                            <p><?php echo isset($phone) ? $phone : ''; ?></p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Level</label>
                                                            <p><?php echo isset($level) ? $level : ''; ?></p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Status</label>
                                                            <p><?php echo isset($active) ? $active : ''; ?></p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Last Login</label>
                                                            <p><?php echo isset($last_login) ? $last_login : ''; ?></p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Created On</label>
                                                            <p><?php echo isset($created_on) ? $created_on : ''; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-footer">
                                                <button type="button" name="edit" class="btn btn-primary" onClick="location.href='<?php echo site_url('users/update/' . $id); ?>'">Edit Data</button> &nbsp; &nbsp;
                                                <button type="button" name="back" class="btn btn-primary float-right" onClick="location.href='<?php echo site_url('users'); ?>'">Back Button</button>
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
                                                            <label>Username</label>
                                                            <input class="form-control" name="username" id="username" value="<?php echo isset($username) ? $username : ''; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input class="form-control" name="first_name" id="first_name" value="<?php echo isset($first_name) ? $first_name : ''; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input type="email" class="form-control" name="email" id="email" value="<?php echo isset($email) ? $email : ''; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Password</label>
                                                            <input type="password" class="form-control" name="password" id="password" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Retype Password</label>
                                                            <input type="Password" class="form-control" name="repassword" id="repassword" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Phone</label>
                                                            <input type="text" class="form-control" name="phone" id="phone" value="<?php echo isset($phone) ? $phone : ''; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Group</label>
                                                            <?php
                                                            echo form_dropdown('groups[]', $groups, isset($group) ? set_value('group', $group) : '1', 'class="form-control chosen-select" id="group"');
                                                            ?>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Status</label>
                                                            <?php
                                                            echo form_dropdown('active', $actives, isset($active) ? set_value('active', $active) : '1', 'class="form-control" id="active"');
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-footer">
                                                <input type="submit" name="submit" class="btn btn-primary" value="Submit Data"> &nbsp; &nbsp;
                                                <button type="reset" name="reset" class="btn btn-default">Reset Data</button>
                                                <button type="button" name="back" class="btn btn-primary float-right" onClick="location.href='<?php echo site_url('users'); ?>'">Back Button</button>
                                            </div>
                                        </form>
                                    </div><!-- /.box -->
                                </div>
                            </div>
                        <?php

                            break;

                        case 'addmahasiswa':

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
                                                            <label>Username</label>
                                                            <?php
                                                            echo form_dropdown('username', $mahasiswa, isset($username) ? set_value('username', $username) : '1', 'class="form-control chosen-select" id="username"');
                                                            ?>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input type="email" class="form-control" name="email" id="email" value="<?php echo isset($email) ? $email : ''; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Password</label>
                                                            <input type="password" class="form-control" name="password" id="password" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Retype Password</label>
                                                            <input type="Password" class="form-control" name="repassword" id="repassword" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Phone</label>
                                                            <input type="text" class="form-control" name="phone" id="phone" value="<?php echo isset($phone) ? $phone : ''; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Group</label>
                                                            <?php
                                                            echo form_dropdown('groups[]', $groups, isset($group) ? set_value('group', $group) : '', 'class="form-control chosen-select" id="group"');
                                                            ?>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Status</label>
                                                            <?php
                                                            echo form_dropdown('active', $actives, isset($active) ? set_value('active', $active) : '1', 'class="form-control" id="active"');
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-footer">
                                                <input type="submit" name="submit" class="btn btn-primary" value="Submit Data"> &nbsp; &nbsp;
                                                <button type="reset" name="reset" class="btn btn-default">Reset Data</button>
                                                <button type="button" name="back" class="btn btn-primary float-right" onClick="location.href='<?php echo site_url('users'); ?>'">Back Button</button>
                                            </div>
                                        </form>
                                    </div><!-- /.box -->
                                </div>
                            </div>
                        <?php

                            break;

                        case 'adddosen':
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
                                                            <label>Username</label>
                                                            <?php
                                                            echo form_dropdown('username', $dosen, isset($username) ? set_value('username', $username) : '1', 'class="form-control chosen-select" id="username"');
                                                            ?>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input type="email" class="form-control" name="email" id="email" value="<?php echo isset($email) ? $email : ''; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Password</label>
                                                            <input type="password" class="form-control" name="password" id="password" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Retype Password</label>
                                                            <input type="Password" class="form-control" name="repassword" id="repassword" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Phone</label>
                                                            <input type="text" class="form-control" name="phone" id="phone" value="<?php echo isset($phone) ? $phone : ''; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Group</label>
                                                            <?php
                                                            echo form_dropdown('groups[]', $groups, isset($group) ? set_value('group', $group) : '', 'class="form-control chosen-select" id="group"');
                                                            ?>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Status</label>
                                                            <?php
                                                            echo form_dropdown('active', $actives, isset($active) ? set_value('active', $active) : '1', 'class="form-control" id="active"');
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-footer">
                                                <input type="submit" name="submit" class="btn btn-primary" value="Submit Data"> &nbsp; &nbsp;
                                                <button type="reset" name="reset" class="btn btn-default">Reset Data</button>
                                                <button type="button" name="back" class="btn btn-primary float-right" onClick="location.href='<?php echo site_url('users'); ?>'">Back Button</button>
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
                                                <button id="add" class="btn btn-primary btn-sm" title="Data Add" alt="Data Add" onClick="location.href='<?php echo site_url('users/create'); ?>'"><i class="fas fa-plus"></i> Add Users</button>
                                                <button id="add" class="btn btn-primary btn-sm" title="Data Add" alt="Data Add" onClick="location.href='<?php echo site_url('users/addmahasiswa'); ?>'"><i class="fas fa-plus"></i> Add Users Mahasiswa</button>
                                                <button id="add" class="btn btn-primary btn-sm" title="Data Add" alt="Data Add" onClick="location.href='<?php echo site_url('users/adddosen'); ?>'"><i class="fas fa-plus"></i> Add Users Dosen</button>
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
