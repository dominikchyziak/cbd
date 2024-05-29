<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {

	public function index()
	{
		$this->load->model('User_Model');
		$this->User_Model->logout();
		redirect('duocms/login');
	}
}