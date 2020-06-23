<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{

	protected $page_header   = 'Users Management';
	protected $status        = array(0 => 'Not Active', 1 => 'Active');
    protected $column_search = array('email', 'username', 'first_name','phone','company','active'); //field yang diizin untuk pencarian
	public function __construct()
	{
		parent::__construct();
        $this->load->library(array('ion_auth', 'form_validation', 'template', 'pagination', 'table'));
        $this->load->model(array('Mahasiswa_model' => 'mahasiswa','Usersgroup_model' => 'usersgroup', 'Groups_model' => 'groups', 'Dosen_model' => 'dosen'));
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
        $data['panel_heading'] = 'Users List';
        $data['breadcrumb']    = 'Users';
        $this->template->backend('users_v', $data);
    }


   	private function generate_table()
   	{
        $limit = 10;

        if($this->session->flashdata('page')){
            $offset = $this->session->flashdata('offset');
            $this->session->keep_flashdata('q');                   
            redirect(site_url('users/page/'.$offset));
        }
        else{
            $offset = $this->uri->segment(3);            
            $this->session->set_flashdata('offset', $offset);
        }
        
        if(isset($_POST['q'])){
            $q = $this->input->post('q');
            $this->session->set_flashdata('q', $q);
        }
        else{
            $q = $this->session->flashdata('q');
        } 

        if(!empty($q)){
            $where = array();
            foreach ($this->column_search as $value) {
                $where[$value] = $q;
            }
            $this->db->or_where($where);            
            $query = $this->ion_auth_model->limit($limit)->offset($offset)->order_by('created_on', 'DESC')->users()->result();
            
            $this->db->or_where($where);
            $total_rows = $this->ion_auth_model->users()->num_rows();           
        }
        else{
            $query = $this->ion_auth_model->limit($limit)->offset($offset)->order_by('created_on', 'DESC')->users()->result();
            $total_rows = $this->ion_auth_model->users()->num_rows();
        }

        $config['full_tag_open']    = '<div class="box-tools"><ul class="pagination pagination-sm no-margin pull-left">';
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

        $config['base_url']   = base_url() . 'index.php/users/page/';
        $config['total_rows'] = $total_rows;
        $config['per_page']   = $limit;
        $config['uri_segment'] = 3;
             
        $this->pagination->initialize($config);

        $template = array(
            'table_open' => '<table id="dataTables" class="table table-striped table-bordered table-hover">'
        );
		$this->table->set_template($template);

		$this->table->set_heading('No', 'Username', 'Email', 'Name', 'Level', 'Last Login', 'Action');

        $no = 1;



		foreach ($query as $row) {
            $url_view   = site_url('users/view/'.$row->id);
            $url_update = site_url('users/update/'.$row->id);
            $url_delete = site_url('users/delete/'.$row->id);
            $action     = action_button($url_view, $url_update, $url_delete);
            $noo = $no++;

            $usersgroup = $this->usersgroup->where('user_id', $row->id)->get();
            $groups = $this->groups->where('id', $usersgroup->group_id)->get();

			$this->table->add_row($noo, $row->username, $row->email, $row->first_name, $groups->name, $row->last_login, $action);
		}

		$generate_table = $this->table->generate().br(1).$this->pagination->create_links();

		return $generate_table;
   	}


    public function view($id = null)
    {
        if (!$this->ion_auth->logged_in()){            
            redirect('auth/login', 'refresh');
        }
        
        $query = $this->ion_auth->user((int) $id)->row();
        if ($query) {
            $groups     = $this->ion_auth->get_users_groups($query->id)->result();
            $group_name = '';
            foreach ($groups as $group) {
                $group_name .= $group->name . br(1);
            }

            $data['id']         = $query->id;
            $data['username']   = $query->username;
            $data['first_name'] = $query->first_name;
            $data['email']      = $query->email;
            $data['company']    = $query->company;
            $data['phone']      = $query->phone;
            $data['last_login'] = $query->last_login;
            $data['level']    = $group_name;
            $data['active']     = $this->status[$query->active];
            $data['created_on'] = $query->created_on;
        }

        $data['page']          = 'view';
        $data['page_header']   = $this->page_header;
        $data['panel_heading'] = anchor(site_url('users/'), 'Users List') . ' / User View';

         $this->template->backend('users_v', $data);
    }

    public function create()
    {
        if (!$this->ion_auth->logged_in()){            
            redirect('auth/login', 'refresh');
        }

        if (isset($_POST['submit'])) {
            $rule = array(array('field' => 'username', 'label' => 'Username', 'rules' => 'alpha_dash|required|min_length[5]|max_length[20]'),
                array('field' => 'password', 'label' => 'Password', 'rules' => 'alpha_numeric|required'),
                array('field' => 'repassword', 'label' => 'Retype Password', 'rules' => 'alpha_numeric|required|matches[password]'),
                array('field' => 'email', 'label' => 'Email', 'rules' => 'valid_email'));

            $this->form_validation->set_rules($rule);

            if ($this->form_validation->run() == true) {
                $identity_column = $this->config->item('identity', 'ion_auth');

                $email    = strtolower($this->input->post('email'));
                //$identity = ($identity_column === 'email') ? $email : $this->input->post('username');
                $identity = $this->input->post('username');
                $password = $this->input->post('password');
                $groups   = $this->input->post('groups');
                //$group    = is_array($group) ? $group : array($group);

                $additional_data = array(
                    'first_name'    => $this->input->post('first_name'),
                    'company' => $this->input->post('company'),
                    'phone'   => $this->input->post('phone'),
                    'active'  => $this->input->post('active'),
                );

                $result = $this->ion_auth_model->register($identity, $password, $email, $additional_data, $groups);
                if ($result) {
                    $this->session->set_flashdata('success', 'Success Insert Data');
                    redirect(site_url('users'));
                }
            } else {
                $data['username'] = $this->input->post('username');
                $data['first_name'] = $this->input->post('first_name');
                $data['email']    = $this->input->post('email');
                $data['company']  = $this->input->post('company');
                $data['phone']    = $this->input->post('phone');
                $data['group']    = $this->input->post('group');
                $data['active']   = $this->input->post('active');
            }

        }

        $query          = $this->ion_auth->groups()->result();
        $data['groups'] = array();
        foreach ($query as $row) {
            $data['groups'][$row->id] = $row->name;
        }
        $data['actives']       = $this->status;        
        $data['page']          = 'add';
        $data['page_header']   = $this->page_header;
        $data['action']        = site_url('users/create');
        $data['panel_heading'] = anchor(site_url('users/'), 'Users List') . ' / User Add';

         $this->template->backend('users_v', $data);
    }

    public function addmahasiswa()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        if (isset($_POST['submit'])) {
            $rule = array(
                array('field' => 'username', 'label' => 'Username', 'rules' => 'alpha_dash|required|min_length[5]|max_length[20]'),
                array('field' => 'password', 'label' => 'Password', 'rules' => 'alpha_numeric|required'),
                array('field' => 'repassword', 'label' => 'Retype Password', 'rules' => 'alpha_numeric|required|matches[password]'),
                array('field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email')
            );

            $this->form_validation->set_rules($rule);

            if ($this->form_validation->run() == true) {
                $identity_column = $this->config->item('identity', 'ion_auth');

                $maha = $this->mahasiswa->where('NIM', $this->input->post('username'))->get();

                $email    = strtolower($this->input->post('email'));
                //$identity = ($identity_column === 'email') ? $email : $this->input->post('username');
                $identity = $this->input->post('username');
                $password = $this->input->post('password');
                $groups   = $this->input->post('groups');
                //$group    = is_array($group) ? $group : array($group);

                $additional_data = array(
                    'first_name'    => $maha->Nama,
                    'company' => $this->input->post('company'),
                    'phone'   => $this->input->post('phone'),
                    'active'  => $this->input->post('active'),
                );

                $result = $this->ion_auth_model->register($identity, $password, $email, $additional_data, $groups);
                if ($result) {
                    $this->session->set_flashdata('success', 'Success Insert Data');
                    redirect(site_url('users'));
                }
            } else {
                $data['username'] = $this->input->post('username');
                $data['first_name'] = $this->input->post('first_name');
                $data['email']    = $this->input->post('email');
                $data['company']  = $this->input->post('company');
                $data['phone']    = $this->input->post('phone');
                $data['group']    = $this->input->post('group');
                $data['active']   = $this->input->post('active');
            }
        }

        $query          = $this->ion_auth->groups()->result();
        $data['groups'] = array();
        foreach ($query as $row) {
            $data['groups'][$row->id] = $row->name;
        }
        $data['actives']       = $this->status;
        $data['mahasiswa']     = $this->mahasiswa->as_dropdown('Nama')->get_all(); 
        $data['page']          = 'addmahasiswa';
        $data['page_header']   = $this->page_header;
        $data['action']        = site_url('users/addmahasiswa');
        $data['panel_heading'] = anchor(site_url('users/'), 'Users List') . ' / User Add';

        $this->template->backend('users_v', $data);
    }

    public function adddosen()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        if (isset($_POST['submit'])) {
            $rule = array(
                array('field' => 'username', 'label' => 'Username', 'rules' => 'alpha_dash|required|min_length[5]|max_length[20]'),
                array('field' => 'password', 'label' => 'Password', 'rules' => 'alpha_numeric|required'),
                array('field' => 'repassword', 'label' => 'Retype Password', 'rules' => 'alpha_numeric|required|matches[password]'),
                array('field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email')
            );

            $this->form_validation->set_rules($rule);

            if ($this->form_validation->run() == true) {
                $identity_column = $this->config->item('identity', 'ion_auth');

                $maha = $this->dosen->where('NPP', $this->input->post('username'))->get();

                $email    = strtolower($this->input->post('email'));
                //$identity = ($identity_column === 'email') ? $email : $this->input->post('username');
                $identity = $this->input->post('username');
                $password = $this->input->post('password');
                $groups   = $this->input->post('groups');
                //$group    = is_array($group) ? $group : array($group);

                $additional_data = array(
                    'first_name'    => $maha->Nama,
                    'company' => $this->input->post('company'),
                    'phone'   => $this->input->post('phone'),
                    'active'  => $this->input->post('active'),
                );

                $result = $this->ion_auth_model->register($identity, $password, $email, $additional_data, $groups);
                if ($result) {
                    $this->session->set_flashdata('success', 'Success Insert Data');
                    redirect(site_url('users'));
                }
            } else {
                $data['username'] = $this->input->post('username');
                $data['first_name'] = $this->input->post('first_name');
                $data['email']    = $this->input->post('email');
                $data['company']  = $this->input->post('company');
                $data['phone']    = $this->input->post('phone');
                $data['group']    = $this->input->post('group');
                $data['active']   = $this->input->post('active');
            }
        }

        $query          = $this->ion_auth->groups()->result();
        $data['groups'] = array();
        foreach ($query as $row) {
            $data['groups'][$row->id] = $row->name;
        }
        $data['actives']       = $this->status;
        $data['dosen']     = $this->dosen->as_dropdown('Nama')->get_all();
        $data['page']          = 'adddosen';
        $data['page_header']   = $this->page_header;
        $data['action']        = site_url('users/adddosen');
        $data['panel_heading'] = anchor(site_url('users/'), 'Users List') . ' / User Add';

        $this->template->backend('users_v', $data);
    }

    public function update($id = null)
    {
        if (!$this->ion_auth->logged_in()){            
            redirect('auth/login', 'refresh');
        }

        if (isset($_POST['submit'])) {
            $rule = array(array('field' => 'username', 'label' => 'Username', 'rules' => 'alpha_numeric|required|min_length[5]|max_length[20]'),
                array('field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email'));

            if (!empty($_POST['password'])) {
                $rule[] = array('field' => 'password', 'label' => 'Password', 'rules' => 'alpha_numeric|min_length[5]|max_length[20]');
                $rule[] = array('field' => 'repassword', 'label' => 'Retype Password', 'rules' => 'alpha_numeric|required|min_length[5]|max_length[20]|matches[password]');
            }

            $this->form_validation->set_rules($rule);

            if ($this->form_validation->run() == true) {
                /*
                $email    = strtolower($this->input->post('email'));
                $identity = ($identity_column==='email') ? $email : $this->input->post('username');
                 */
                $row = array(
                    'first_name'    => $this->input->post('first_name'),
                    'company' => $this->input->post('company'),
                    'phone'   => $this->input->post('phone'),
                    'active'  => $this->input->post('active'),
                );

                if (!empty($_POST['password'])) {
                    $row['password'] = $this->input->post('password');
                }

                // Only allow updating groups if user is admin
                if ($this->ion_auth->is_admin()) {
                    //Update the groups user belongs to
                    $groups = $this->input->post('groups');

                    if (isset($groups) && !empty($groups)) {

                        $this->ion_auth->remove_from_group('', $id);

                        foreach ($groups as $group) {
                            $this->ion_auth->add_to_group($group, $id);
                        }

                    }
                }

                $result = $this->ion_auth->update($id, $row);
                if ($result) {
                    $this->session->set_flashdata('success', 'Success Update Data');

                    $offset = $this->session->flashdata('offset');
                    $this->session->keep_flashdata('q');                    
                    redirect(site_url('users/page/'.$offset));
                }
            }
        }

        $query          = $this->ion_auth->groups()->result();
        $data['groups'] = array();
        foreach ($query as $row) {
            $data['groups'][$row->id] = $row->name;
        }

        $query = $this->ion_auth->user((int) $id)->row();
        if ($query) {
            $grps   = $this->ion_auth->get_users_groups($query->id)->result();
            $groups = array();
            foreach ($grps as $grp) {
                $groups[] = $grp->id;
            }

            $data['id']       = $query->id;
            $data['username'] = $query->username;
            $data['first_name']     = $query->first_name;
            $data['email']    = $query->email;
            $data['company']  = $query->company;
            $data['phone']    = $query->phone;
            $data['group']    = $groups;
            $data['active']   = $query->active;
            $data['action']   = site_url('users/update/'. $query->id);
        }
        $data['actives'] = $this->status;
        
        $data['page']          = 'update';
        $data['page_header']   = $this->page_header;        
        $data['panel_heading'] = anchor(site_url('users/'), 'Users List') . ' / User Update';

         $this->template->backend('users_v', $data);
    }

    public function delete($id = null)
    {
        if (!$this->ion_auth->logged_in()){            
            redirect('auth/login', 'refresh');
        }

        $offset = $this->session->flashdata('offset');
        $this->session->keep_flashdata('q');

        $result = $this->ion_auth->delete_user($id);
        if ($result) {
            $this->session->set_flashdata('success', 'Success Delete Data');          
            redirect(site_url('users/page/'.$offset));
        }
        $this->session->set_flashdata('error', 'Error Delete Data');
        redirect(site_url('users/page/'.$offset));
    }

}
