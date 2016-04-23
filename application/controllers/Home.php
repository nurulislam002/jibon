<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct()
{
	parent::__construct();
	$this->is_logged_in();
	$this->load->model('home_models');
}

	public function index()
	{
		//if does't have any user crat user otherwise go to home page
		if($this->db->get('users')->num_rows(0) == 0){
			//$this->create_user();
			$this->load->view('config_user_view');

		}else{
			$attr			=	array(
				'meta_description'			=>	'Meta Description',
			);
			$this->layouts->set_title('Home Page');
			$this->layouts->view_frontend(
				'frontend/home_content',	// container content data
				'frontend/header',				// 	Load header
				'frontend/footer',				//	Load footer
				'',												// 	no layouts
				$attr											// 	pass global parametter
			);
		}


	}


	public function create_user()
	{
		$submit = $this->input->post('add_user_btn');
		$update = $this->input->post('update_user_btn');
		if( !empty( $submit ) )
		{
			$this->form_validation->set_rules('user_name','User Name','trim|min_length[3]|max_length[100]|alpha_numeric|required|xss_clean|is_unique[users.username]', array(
				'min_length'    => 'Username should be minimum 3 charachers.',
				'is_unique'    => 'This Username already have listed. Please try another...',
			));
			$this->form_validation->set_rules('user_email','User Email','trim|valid_email|required|xss_clean|is_unique[users.email]', array(
				'is_unique'    => 'This Email already have listed. Please try another...',
			));
			$this->form_validation->set_rules('access_level','Access Level','required|callback_select_default['.$this->input->post('access_level').'0]');

			$pass = $this->input->post('user_pass');
			$this->form_validation->set_rules('user_pass','Password','trim|xss_clean|required|callback_password_strength_check[$pass]');

			$this->form_validation->set_rules('con_pass','Confirm Password','trim|xss_clean|required|matches[user_pass]');

			$this->form_validation->set_error_delimiters('<span class="validation_error_message">', '</span>');

			if ($this->form_validation->run() == FALSE)
			{
				$this->index();
			}
			else
			{
				$form_data = array(
					'user_id'               => $this->get_unused_id(),
					'username'              => $this->input->post('user_name'),
					'email'                 => $this->input->post('user_email'),
					'auth_level'            => $this->input->post('access_level'),
					'banned'                => '0',
					'passwd'                => $this->authentication->hash_passwd($this->input->post('user_pass')),
					'created_at'            => date('Y-m-d H:i:s'),
				);
				if( $this->home_models->add_user($form_data) ){
					$this->session->set_flashdata('success_message', 'User has been successfully added.');
					redirect('admin');
				}
				else
				{
					$this->session->set_flashdata('error_message', 'User Creation Failed.');
					redirect('dashboard/users/insert-user');
				}
			}
		}
	}
	// randomly unused id
	public function get_unused_id()
	{
	  // Create a random user id between 1200 and 4294967295
	  $random_unique_int = 2147483648 + mt_rand( -2147482448, 2147483647 );

	  // Make sure the random user_id isn't already in use
	  $query = $this->db->where( 'user_id', $random_unique_int )
	  ->get_where( config_item('user_table') );

	  if( $query->num_rows() > 0 )
	  {
	    $query->free_result();

	    // If the random user_id is already in use, try again
	    return $this->get_unused_id();
	  }

	  return $random_unique_int;
	}
	//for Validation function
	//This function works for validation when update unique databse fild
	public function select_default($str, $prem) {
	  if ($str == $prem){
	    $this->form_validation->set_message('select_default', 'Please select user access level');
	    return FALSE;
	  }else{
	    return TRUE;
	  }
	}
	//password strength check
	public function password_strength_check( $password )
	{
	  // Password length
	  $regex = '(?=.{' . config_item('min_chars_for_password') . ',' . config_item('max_chars_for_password') . '})';
	  $error = '<li>At least ' . config_item('min_chars_for_password') . ' characters</li>
	  <li>Not more than ' . config_item('max_chars_for_password') . ' characters</li>';

	  // At least one digit required
	  $regex .= '(?=.*\d)';
	  $error .= '<li>One number</li>';

	  // At least one lower case letter required
	  $regex .= '(?=.*[a-z])';
	  $error .= '<li>One lower case letter</li>';

	  // At least one upper case letter required
	  $regex .= '(?=.*[A-Z])';
	  $error .= '<li>One upper case letter</li>';

	  // No space, tab, or other whitespace chars allowed
	  $regex .= '(?!.*\s)';
	  $error .= '<li>No spaces, tabs, or other unseen characters</li>';

	  // No backslash, apostrophe or quote chars are allowed
	  $regex .= '(?!.*[\\\\\'"])';
	  $error .= '<li>No backslash, apostrophe or quote characters</li>';

	  // One of the following characters must be in the password,  @ # $ % ^ & + =
	  // $regex .= '(?=.*[@#$%^&+=])';
	  // $error .= '<li>One of the following characters must be in the password,  @ # $ % ^ & + =</li>';

	  if( preg_match( '/^' . $regex . '.*$/', $password, $matches ) )
	  {
	    return TRUE;
	  }

	  $this->form_validation->set_message('password_strength_check', $error);

	  return FALSE;
	}

}
