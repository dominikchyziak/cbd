<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Currency extends Backend_Controller {
    
    public $languages = array();
    public $menu_item = ['delivery', ''];
    public $currency_obj;

    public function __construct() {
        parent::__construct();
        $this->load->model("CurrencyModel");
        $this->load->vars(['activePage' => 'currency']);

        $this->currency_obj = new CurrencyModel();
    }

    public function index() {
        $post_data = $this->input->post();
        if(!empty($post_data)){
            $new_currency = $post_data['waluta'];
            set_option('admin_default_currency', $new_currency);
        }
        $data["currency"] = $this->currency_obj->get_all_currencies();
        $data["acurrency"] = $this->currency_obj->get_acitve_currencies();
        $this->layout('duocms/Shop/Currency/index', $data);
    }

    public function create() {
        if(!empty($_POST)){
            $data = $this->input->post();
            $id = $this->currency_obj->add_currency($data);

                if(!empty($id)){
                    $this->setOkay('Waluta została dodana poprawnie.');
                    redirect('duocms/currency/edit/' . $id);
                } else {
                    $this->setError('Nie udało się dodać waluty.');
                    redirect('duocms/currency/create');
                }
        }   
        
        $this->menu_item[1] = 'currency_create';
        $this->layout('duocms/Shop/Currency/form', [
        ]);
    }

    public function edit($id) {
        if(!empty($_POST)){
            $data = $this->input->post();
            if(!empty($id)){
                $this->currency_obj->update_currency($id, $data);
                if(!empty($id)){
                    $this->setOkay('Waluta została edytowana poprawnie.');
                    redirect('duocms/currency/edit/' . $id);
                } else {
                    $this->setError('Nie udało się edytować waluty.');
                    redirect('duocms/currency/edit/' . $id);
                }
            }
        }
        
        $currency = $this->currency_obj->get_currency($id);
        $this->layout('duocms/Shop/Currency/form', [
            'currency' => $currency
        ]);
    }

    public function delete($id) {
        if (!empty($id)) {
            $this->currency_obj->delete_currency($id);
            $this->setError('Usunięto walutę.');
            redirect('duocms/currency/index');
        }
    }
}
