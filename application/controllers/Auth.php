<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Auth extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language', 'bootstrap_helper', 'captcha'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}

	/**
	 * Redirect if needed, otherwise display the user list
	 */
	public function index()
	{

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}
		else
		{
			redirect('users', 'refresh');
		}
	}

	/**
	 * Log the user in
	 */
	public function login()
	{
		$this->data['title'] = $this->lang->line('login_heading');

		// validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');
		$this->form_validation->set_rules('captcha', 'Captcha', 'trim|callback_check_captcha|required');

		if ($this->form_validation->run() === TRUE)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool)$this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				if ($this->ion_auth->is_admin()) {
					redirect('/', 'refresh');
				} elseif ($this->ion_auth->in_group('mahasiswa')) {
					redirect('mahasiswa/dashboard', 'refresh');
				} elseif ($this->ion_auth->in_group('kaprodi_SI') || $this->ion_auth->in_group('kaprodi_TI')) {
					redirect('kaprodi/pengajuan', 'refresh');
				} elseif ($this->ion_auth->in_group('dosen')) {
					redirect('dosen/profile', 'refresh');
				}
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'placeholder' => 'admin@admin.com',
				'class' => 'form-control',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				'placeholder' => 'password',
				'class' => 'form-control',
				'id' => 'password',
				'type' => 'password',
			);
			$this->data['captcha'] = array('name' => 'captcha',
				'placeholder' => 'captcha',
				'class' => 'form-control',
				'id' => 'captcha',
				'type' => 'text',
			);
			$config_captcha = array(
				'img_path'  => './captcha/',
				'img_url'  => base_url().'captcha/',
				'img_width'  => 200,
				'img_height' => 50,
				'border' => 0,			
				'font_path' => FCPATH . 'captcha/Verdana.ttf',
				'font_size' => 23,
				'word_length'	=> 6,
				'expiration' => 7200
			);

            // create captcha image
            $cap = create_captcha($config_captcha);
 
            // store image html code in a variable
            $this->data['image'] = $cap['image'];
 
            // store the captcha word in a session
            $this->session->set_userdata('mycaptcha', $cap['word']);

			$this->_render_page('auth/login_p', $this->data);
		}
	}

	public function check_captcha(){
		if ($this->input->post() && ($this->input->post('captcha') == $this->session->userdata('mycaptcha'))) {
			return true;
        }
		else{
			$this->form_validation->set_message('check_captcha', 'Captcha Is Wrong.');
			return false;
		}
	}

	/**
	 * Log the user out
	 */
	public function logout()
	{
		$this->data['title'] = "Logout";

		// log the user out
		$logout = $this->ion_auth->logout();

		// redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('auth/login', 'refresh');
	}


	/**
	 * @param string     $view
	 * @param array|null $data
	 * @param bool       $returnhtml
	 *
	 * @return mixed
	 */
	public function _render_page($view, $data = NULL, $returnhtml = FALSE)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data : $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		// This will return html on 3rd argument being true
		if ($returnhtml)
		{
			return $view_html;
		}
	}

}
