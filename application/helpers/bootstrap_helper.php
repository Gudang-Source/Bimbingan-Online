<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Bootstrap helper digunakan untuk memudahkan penggunaan bootstrap pada codeigneter,
 * HTML dan CSS mengacu pada template SB-Admin (bootstrap 3),
 * mungkin tidak akan berjalan dengan baik pada template bootstrap lainnya.
 *
 * @link
 * @copyright Copyright (c) 2014, Herdiesel Santoso <http://nulisapajah.com>
 */


if (!function_exists('set_table')) {
    function set_table($dataTables = false, $class = '')
    {
        $CI =& get_instance();

        $CI->load->library('table');

        $class = !empty($class) ? $class : 'table table-hover';

        $id = ($dataTables == true) ? 'id="dataTables"' : '';

        $tmpl = array(
            'table_open'         => '<table class="' . $class . '" ' . $id . '>',

            'heading_row_start'  => '<tr>',
            'heading_row_end'    => '</tr>',
            'heading_cell_start' => '<th>',
            'heading_cell_end'   => '</th>',

            'row_start'          => '<tr>',
            'row_end'            => '</tr>',
            'cell_start'         => '<td>',
            'cell_end'           => '</td>',

            'row_alt_start'      => '<tr>',
            'row_alt_end'        => '</tr>',
            'cell_alt_start'     => '<td>',
            'cell_alt_end'       => '</td>',

            'table_close'        => '</table>',
        );

        $CI->table->set_template($tmpl);
    }
}

if (!function_exists('set_pagging')) {
    function set_pagging($config = array()){
        
        $CI =& get_instance();

        $CI->load->library('pagination');

        $config['full_tag_open']    = '<div class="box-tools"><ul class="pagination pagination-sm pull-left">';
        $config['full_tag_close']   = '</ul></div>';
        $config['num_tag_open']     = '<li>';
        $config['num_tag_close']    = '</li>';
        $config['cur_tag_open']     = '<li class="disabled"><li class="active"><a href="#"">';
        $config['cur_tag_close']    = '<span class="sr-only"></span></a></li>';
        $config['next_tag_open']    = '<li>';
        $config['next_tagl_close']  = '</li>';
        $config['prev_tag_open']    = '<li>';
        $config['prev_tag_close']   = '</li>';
        $config['first_tag_open']   = '<li>';
        $config['first_tagl_close'] = '</li>';
        $config['last_tag_open']    = '<li>';
        $config['last_tagl_close']  = '</li>';
        $config['next_link']  = '&raquo; ';
        $config['prev_link']  = '&laquo; ';

        $config['first_link'] = 'First';
        $config['last_link']  = 'Last';

        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = floor($choice);

        $CI->pagination->initialize($config);
    }
}

if (!function_exists('action_button')) {
    function action_button($url_view, $url_update, $url_delete)
    {
        return '<button id="view" class="btn btn-info btn-sm btn-circle" onClick="location.href=\'' . $url_view . '\'" title="View Data" alt="View Data"><i class="fas fa-eye"></i></button>
            <button id="update" class="btn btn-warning btn-sm btn-circle" onClick="location.href=\'' . $url_update . '\'" title="Edit Data" alt="Edit Data"><i class="fas fa-pencil-alt"></i></button>
            <button id="delete"  class="btn btn-danger btn-sm btn-circle" onClick="return ConfirmDelete(\'' . $url_delete . '\');" title="Delete Data" alt="Delete Data"><i class="fas fa-trash-alt"></i></button>';
    }
}

if (!function_exists('ajax_button')) {
    function ajax_button($url_view, $url_update, $url_delete)
    {
        return '<button id="view" class="btn btn-info btn-sm btn-circle" onClick="' . $url_view . '" title="View Data" alt="View Data"><i class="fas fa-eye"></i></button>
            <button id="update" class="btn btn-warning btn-sm btn-circle" onClick="' . $url_update . '" title="Edit Data" alt="Edit Data"><i class="fas fa-pencil-alt"></i></button>
            <button id="delete"  class="btn btn-danger btn-sm btn-circle" onClick="' . $url_delete . '" title="Delete Data" alt="Delete Data"><i class="fas fa-trash-alt"></i></button>';
    }
}

if (!function_exists('tombol_button')) {
    function tombol_button($url_tolak, $url_setuju)
    {
        return '<button id="view" class="btn btn-danger btn-sm btn-circle" onClick="' . $url_tolak . '" title="View Data" alt="View Data">Ditolak</i></button>
            <button id="update" class="btn btn-success btn-sm btn-circle" onClick="' . $url_setuju . '" title="Edit Data" alt="Edit Data">Disetujui</button>
            ';
    }
}

if (!function_exists('notifications')) {
    function notifications($type, $message)
    {
        $notification = '';
        switch ($type) {
            case 'success':
                $notification = '<div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                ' . $message . '
            </div>';
                break;
            case 'info':
                $notification = '<div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                ' . $message . '
            </div>';
                break;
            case 'warning':
                $notification = '<div class="alert alert-warning alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                ' . $message . '
            </div>';
                break;

            case 'danger':
            case 'error':
                $notification = '<div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                ' . $message . '
            </div>';
                break;
        }
        return $notification;
    }
}

/* End of file bootstrap_helper.php */
/* Location: ./application/helpers/bootstrap_helper.php */
