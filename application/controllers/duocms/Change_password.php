<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Change_password extends Backend_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->vars(['activePage' => 'change-password']);
	}

	public function index()
	{
		$this->load->helper('form');

		$loginUserdata = $this->session->userdata('login');

		if ($loginUserdata['user']['email'] !== 'duonet') {
			$this->db->where('email <>', 'duonet');
		}

		$usersFound = User_Model::factory()->findAll();
		$userList = array();

        if ($usersFound) {
            foreach ($usersFound as $user) {
                $userList[$user->id] = $user->email;
            }
        }

		$this->layout('duocms/Change_password/index', array(
			'user_list' => $userList
		));
	}

	public function submit()
	{
		try {
			$this->load->library('form_validation');

			$this->form_validation->set_rules('user_id', 'Dla użytkownika', 'required|numeric');
			$this->form_validation->set_rules('haslo', 'hasło', 'required');
			$this->form_validation->set_rules(
				'haslo2',
				'powtórz hasło',
				'required|matches[haslo]|callback__change_password'
			);

			if ($this->form_validation->run()) {
				$this->setOkay('Hasło zostało zmienione.');
			} else {
				throw new Exception(validation_errors());
			}
		} catch (Exception $e) {
			$this->setError(strip_tags($e->getMessage()));
		}

		redirect('duocms/change_password');
	}

	public function _change_password()
	{
		$res = User_Model::factory()->update(array(
			'id' => $this->input->post('user_id'),
			'password' => md5($this->input->post('haslo'))
		));

		if ($res) {
			return true;
		}

		$this->form_validation->set_message(
			'_change_password',
			'Wystąpił błąd podczas zmiany hasła. Spróbuj ponownie.'
		);
	}
}