<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment extends Backend_Controller {

    public $languages = array();
    public $menu_item = ['payment','product_list'];

    public function __construct() {
        parent::__construct();
        $this->load->model('ProductModel');
        $this->load->model('ProductTranslationModel');
        $this->load->model('OfferCategoryModel');
        
        $this->load->helper('form');
        $langs = get_languages();
        foreach ($langs as $l) {
            $this->languages[] = $l->short;
        }
        $this->load->vars(['activePage' => 'products']);
    }

    public function index() {


        $this->layout('duocms/Shop/Payment/index', [
        ]);
    }

  

}
