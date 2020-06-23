<?php

defined('BASEPATH') or exit('No direct script access allowed');

require(FCPATH . 'vendor/autoload.php');

class Kriteria extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('ion_auth', 'form_validation', 'template'));
        $this->load->helper('bootstrap_helper');
    }

    public function index()
    {
        $this->template->backend('tabs_v');
    }

    public function kriteria()
    {
        $this->template->backend('kriteria_v');
    }

    public function profile()
    {
        $this->template->backend('profile_v');
    }

}