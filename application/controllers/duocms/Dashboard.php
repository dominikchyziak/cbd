<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Backend_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->vars(['activePage' => 'dashboard']);
	}

	public function index()
	{
		$this->layout('duocms/Dashboard/index');
	}
}