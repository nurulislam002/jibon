<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home_models extends CI_Model {
  // add category
  public function add_user($form_data)
  {
    $this->db->insert('users', $form_data);
    if( $this->db->affected_rows() === 1 ){
      return true;
    }
    return false;
  }
}
