<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_Model');
        $this->load->model('Admin_Model');
    }
    
    public function index() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Login', 'required');
        $this->form_validation->set_rules('haslo', 'Hasło', 'required|callback__check_password');
        $this->form_validation->set_rules('g-recaptcha-response', 'reCaptcha', 'callback__validate_kapcza');
        $this->form_validation->set_message('_validate_kapcza', 'Przejdź test reCaptcha');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('duocms/Login/index');
        } else {
            $this->_user_login();
            redirect('duocms/dashboard');
        }
    }
    
    public function _check_password(){
         try {
            $res = $this->Admin_Model->check_pass(
                    $this->input->post('email'), $this->input->post('haslo')
            );

            if ($res == FALSE)
                throw new Exception('Użytkownik lub hasło są nieprawidłowe');

            return TRUE;
        } catch (Exception $e) {
            $this->form_validation->set_message('_check_password', $e->getMessage());
            return FALSE;
        }
    }

    public function _user_login() {
            $res = $this->Admin_Model->login($this->input->post('email'), $this->input->post('haslo'));
            if ($res !== TRUE)
                throw new Exception('Użytkownik lub hasło są nieprawidłowe');

            return TRUE;

    }
}