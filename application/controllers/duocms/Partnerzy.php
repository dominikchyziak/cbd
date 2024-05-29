<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Partnerzy extends Backend_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->vars(['activePage' => 'partnerzy']);
	}

	public function index()
	{
		$this->load->model('PartnerModel');

		$partnerModel = new PartnerModel();
		$partnerzy = $partnerModel->findAll();

		$this->layout('duocms/Partnerzy/index', [
			'partnerzy' => $partnerzy
		]);
	}

	public function create()
	{
		$this->load->model('PartnerModel');

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$order = $this->input->post('order', true);

			$partner = new PartnerModel();
			$partner->order = $order;
                        $partner->url = $this->input->post('url');
			$partner->insert();

			if (!$partner->id) {
				$this->setError('Wystąpił błąd.');
				redirect('duocms/partnerzy/create');
			}

			$partner->saveImage();

			$this->setOkay('Partner został zapisany.');
			redirect('duocms/partnerzy/edit/'.$partner->id);
		}

		$this->load->helper('form');

		$this->layout('duocms/Partnerzy/create');
	}

	public function edit($id)
	{
		$this->load->model('PartnerModel');

		$partner = new PartnerModel($id);

		if (!$partner->id) {
			show_404();
		}

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$order = $this->input->post('order', true);

			$partner->order = $order;
                        $partner->url = $this->input->post('url',TRUE);
			$res = $partner->update();

			if ($res) {
				$this->setOkay('Partner został zapisany.');
			} else {
				$this->setError('Wystąpił błąd.');
			}

			$partner->saveImage();

			redirect('duocms/partnerzy/edit/'.$partner->id);
		}

		$this->load->helper('form');

		$this->layout('duocms/Partnerzy/edit', [
			'partnerzy' => $partner
		]);
	}

	public function image_delete($id)
	{
		$this->load->model('PartnerModel');

		$partner = new PartnerModel($id);

		if (!$partner->id) {
			show_404();
		}

		$partner->image = null;
		$res = $partner->update();

		if ($res) {
			$this->setOkay('Zdjęcie zostało usunięte.');
		} else {
			$this->setError('Wystąpił błąd.');
		}

		redirect($this->input->server('HTTP_REFERER'));
	}

	public function delete($id)
	{
		$this->load->model('PartnerModel');

		$partner = new PartnerModel($id);

		if (!$partner->id) {
			show_404();
		}

		$res = $partner->delete();

		if ($res) {
			$this->setOkay('Referencja została usunięta.');
		} else {
			$this->setError('Wystąpił błąd.');
		}

		redirect($this->input->server('HTTP_REFERER'));
	}
}