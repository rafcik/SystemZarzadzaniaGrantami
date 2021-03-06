<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/libraries/Google/autoload.php';

class Auth extends CI_Controller {

	public function __construct() {
        parent::__construct();
		
		$this->load->model('User_model','',TRUE);
    }
	
	public function index()
	{
		$this->load->helper(array('form'));
		$this->load->view('header');
		$this->load->view('login');
		$this->load->view('footer');
	}
	
	public function do_login($email, $password) 
	{
		$result = $this->User_model->login($email, $password);
 
		if($result)
		{
			$sess_array = array();
			
			foreach($result as $row)
			{
				$sess_array = array(
					'id' => $row->id,
					'email' => $row->email,
					'imie' => $row->imie,
					'nazwisko' => $row->nazwisko
				);
			
				$this->session->set_userdata('logged_in', $sess_array);
			}
		
			return TRUE;
		}
		else
		{			
			return FALSE;
		}
	}
	
	public function reg_login() {
		$user_data = $this->session->flashdata('user_data');
		$email = $user_data['email'];
		$password = $user_data['password'];
		
		$this->do_login($email, $password);
		
		redirect('/', 'refresh');
	}
	
	public function login() 
	{
		//This method will have the credentials validation
		$this->load->library('form_validation');
 
		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
		$this->form_validation->set_rules('password', 'Hasło', 'trim|required|xss_clean|callback_check_database');
 
		if($this->form_validation->run() == FALSE)
		{
			$this->load->view('header');
			$this->load->view('login');
			$this->load->view('footer');
		}
		else
		{
			//Go to private area
			redirect('/', 'refresh');
		}
	}
 
	function check_database($password)
	{ 
		$email = $this->input->post('email');
		
		if($this->do_login($email, $password))
		{		
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('check_database', 'Invalid username or password');
			
			return FALSE;
		}
	}
	
	public function calendar() 
	{
		require_once 'application/libraries/calendar_init.php';

		if (isset($_GET['code'])) {		
			$client->authenticate($_GET['code']);  
			$this->session->set_userdata('token', $client->getAccessToken());
			$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
			header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
		}

		// Step 1:  The user has not authenticated we give them a link to login    
		if (!($this->session->userdata('token'))) {
			$authUrl = $client->createAuthUrl();
			
			redirect($authUrl);
			//print "<a class='login' href='$authUrl'>Connect Me!</a>";
		}    

		// Step 3: We have access we can now create our service
		if ($this->session->userdata('token')) {			
			redirect('calendar');
		}				
	}

	public function logout() 
	{
		$this->session->set_userdata('logged_in', false);
		$this->session->set_userdata('token', false);
		redirect('/', 'refresh');
	}
}