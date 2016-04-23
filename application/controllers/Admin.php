<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

	public function __construct()
  {
    parent::__construct();
  }

	public function index()
	{
		if( $this->require_role('admin') ){
			$attr			=	array(
				'meta_description'			=>	'Welcome to dashboard',
			);
			$this->layouts->set_title('Dashboard');
			$this->layouts->view_frontend(
				'backend/content',			// container content data
				'backend/header',				// 	Load header
				'backend/footer',				//	Load footer
				'',											// 	no layouts
				$attr										// 	pass global parametter
			);
		}
	}

}
