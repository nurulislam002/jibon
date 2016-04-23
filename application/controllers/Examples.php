<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Community Auth - Examples Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2016, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Examples extends MY_Controller
{

    public function create_user()
    {
        // Customize this array for your user
        $user_data = array(
            'username'   => 'Palash',
            'passwd'     => 'Admin123',
            'email'      => 'mdpalash20@gmail.com',
            'auth_level' => '9', // 9 if you want to login @ examples/index.
        );

        $this->is_logged_in();


        // Load resources
        $this->load->model('examples_model');
        $this->load->model('validation_callables');
        $this->load->library('form_validation');

        $this->form_validation->set_data( $user_data );

        $validation_rules = array(
			array(
				'field' => 'username',
				'label' => 'username',
				'rules' => 'max_length[12]|is_unique[' . config_item('user_table') . '.username]',
                'errors' => array(
                    'is_unique' => 'Username already in use.'
                )
			),
			array(
				'field' => 'passwd',
				'label' => 'passwd',
				'rules' => array(
                    'trim',
                    'required',
                    array(
                        '_check_password_strength',
                        array( $this->validation_callables, '_check_password_strength' )
                    )
                ),
                'errors' => array(
                    'required' => 'The password field is required.'
                )
			),
			array(
                'field'  => 'email',
                'label'  => 'email',
                'rules'  => 'trim|required|valid_email|is_unique[' . config_item('user_table') . '.email]',
                'errors' => array(
                    'is_unique' => 'Email address already in use.'
                )
			),
			array(
				'field' => 'auth_level',
				'label' => 'auth_level',
				'rules' => 'required|integer|in_list[1,6,9]'
			)
		);

		$this->form_validation->set_rules( $validation_rules );

		if( $this->form_validation->run() )
		{
            $user_data['passwd']     = $this->authentication->hash_passwd($user_data['passwd']);
            $user_data['user_id']    = $this->examples_model->get_unused_id();
            $user_data['created_at'] = date('Y-m-d H:i:s');

            // If username is not used, it must be entered into the record as NULL
            if( empty( $user_data['username'] ) )
            {
                $user_data['username'] = NULL;
            }

			$this->db->set($user_data)
				->insert(config_item('user_table'));

			if( $this->db->affected_rows() == 1 )
				echo '<h1>Congratulations</h1>' . '<p>User ' . $user_data['username'] . ' was created.</p>';
		}
		else
		{
			echo '<h1>User Creation Error(s)</h1>' . validation_errors();
		}
    }
}

/* End of file Examples.php */
/* Location: /community_auth/controllers/Examples.php */
