<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends CI_Controller
{

	protected $page_header   = 'Groups Management';
	protected $status        = array(0 => 'Not Active', 1 => 'Active');

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('ion_auth', 'form_validation', 'pagination', 'template', 'table'));
		$this->load->helper('bootstrap_helper');
	}

	public function index()
	{  
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $this->page();
	}

    public function page()
    {
        if (!$this->ion_auth->logged_in()){            
            redirect('auth/login', 'refresh');
        }

        $data['page']         = '';
        $data['table']        = $this->generate_table();

        $data['page_header']   = $this->page_header;
        $data['panel_heading'] = 'Groups List';
        $this->template->backend('groups_v', $data);
    }


   	private function generate_table()
   	{
        $query = $this->ion_auth_model->groups()->result();

		$template = array(
		 'table_open' => '<table id="dataTables" class="table table-striped table-bordered table-hover">'
		);
		$this->table->set_template($template);

		$this->table->set_heading('ID', 'Group Name', 'Description', 'Action');


		foreach ($query as $row) {
            $url_view   = site_url('groups/view/'.$row->id);
            $url_update = site_url('groups/update/'.$row->id);
            $url_delete = site_url('groups/delete/'.$row->id);
			$action     = action_button($url_view, $url_update, $url_delete);

			$this->table->add_row($row->id, $row->name, $row->description, $action);
		}

		$generate_table = $this->table->generate();

		return $generate_table;
   	}


    public function view($id = null)
    {
        if (!$this->ion_auth->logged_in()){            
            redirect('auth/login', 'refresh');
        }
        
        $query = $this->ion_auth->group((int) $id)->row();
        if ($query) {
            $data['id']           = $query->id;
            $data['name']         = $query->name;
            $data['description']  = $query->description;
            //$data['active']     = $this->status[$query->active];
        }

        $data['page']          = 'view';
        $data['page_header']   = $this->page_header;
        $data['panel_heading'] = anchor(site_url('groups/'), 'Groups List') . ' / Group View';

         $this->template->backend('groups_v', $data);
    }

    public function create()
    {
        if (!$this->ion_auth->logged_in()){            
            redirect('auth/login', 'refresh');
        }

        if (isset($_POST['submit'])) {
            $rule = array(array('field' => 'name', 'label' => 'Name', 'rules' => 'alpha_dash|required|is_unique[groups.name]'));

            $this->form_validation->set_rules($rule);

            if ($this->form_validation->run() == true) {
                $group = $this->ion_auth->create_group($this->input->post('name'), $this->input->post('description'));

                if(!$group){
                    $data['name']        = $this->input->post('name');
                    $data['description'] = $this->input->post('description');

                    $this->session->set_flashdata('error', $this->ion_auth->messages());
                    redirect(site_url('groups/create'));
                }
                else
                {
                    $this->session->set_flashdata('success', 'Success Insert Data');
                    redirect(site_url('groups'));
                }
            } else {                
                $data['name']        = $this->input->post('name');
                $data['description'] = $this->input->post('description');
            }
        }

        //$data['actives']       = $this->status;        
        $data['page']          = 'add';
        $data['page_header']   = $this->page_header;
        $data['action']        = site_url('groups/create');
        $data['panel_heading'] = anchor(site_url('groups/'), 'Groups List') . ' / Group Add';

         $this->template->backend('groups_v', $data);
    }

    public function update($id = null)
    {
        if (!$this->ion_auth->logged_in()){            
            redirect('auth/login', 'refresh');
        }

        if (isset($_POST['submit'])) {
            $rule = array(array('field' => 'name', 'label' => 'Name', 'rules' => 'alpha_dash|required'));

            $this->form_validation->set_rules($rule);

            if ($this->form_validation->run() == true) {
                $group = $this->ion_auth->update_group($id, $this->input->post('name'), $this->input->post('description'));

                if(!$group){
                    $this->session->set_flashdata('error', $this->ion_auth->messages());
                    redirect(site_url('groups/update/'.$id));
                }
                else
                {
                    $this->session->set_flashdata('success', 'Success Insert Data');
                    redirect(site_url('groups'));
                }
            } 
        }

        $query = $this->ion_auth->group((int) $id)->row();
        if ($query) {
            $data['id']          = $query->id;
            $data['name']        = $query->name;
            $data['description'] = $query->description;
            //$data['active']   = $query->active;
            $data['action']   = site_url('groups/update/'. $query->id);
        }
        //$data['actives'] = $this->status;
        
        $data['page']          = 'update';
        $data['page_header']   = $this->page_header;        
        $data['panel_heading'] = anchor(site_url('groups/'), 'Groups List') . ' / Group Update';

         $this->template->backend('groups_v', $data);
    }

    public function delete($id = null)
    {
        if (!$this->ion_auth->logged_in()){            
            redirect('auth/login', 'refresh');
        }

        $group = $this->ion_auth->delete_group($id);
        if(!$group){
            $this->session->set_flashdata('error', $this->ion_auth->messages());
        }
        else{
            $this->session->set_flashdata('success', 'Success Delete Data');
        }
        redirect(site_url('groups'));
    }

}
