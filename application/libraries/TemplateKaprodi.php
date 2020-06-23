<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class TemplateKaprodi{

    protected $ci;

    public function __construct()
    {
        $this->ci = &get_instance(); 
    }

	public function backend($pages, $data = NULL) 
    {
        $folder = 'backend/';

        $tpl['content']     = $this->ci->load->view($folder . 'kaprodi/' . $pages, $data, true);
        $tpl['navbar']      = $this->ci->load->view($folder . 'navbar', null, true);
        $tpl['sidebar']     = $this->ci->load->view($folder . 'sidebar_kaprodi', null, true);
        $tpl['ctrlsidebar'] = $this->ci->load->view($folder . 'ctrlsidebar', null, true);

        $this->ci->load->view($folder . 'templates', $tpl);
	}
}

/* End of file Template.php */
/* Location: ./application/libraries/Template.php */