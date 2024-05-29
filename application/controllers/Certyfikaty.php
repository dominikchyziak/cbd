<?php

class Certyfikaty extends Frontend_Controller
{
	public function index()
	{
		$this->lang->load('certyfikaty');
		$this->load->model('CertyfikatModel');

		// Set defaults.
        $this->set_desc(lang('certyfikaty_desc'));
        $this->set_title(lang('certyfikaty_header'));

		$certyfikaty = $this->CertyfikatModel->findAll();

		$this->layout('Certyfikaty/index', [
			'certyfikaty' => $certyfikaty
		]);
	}
}
