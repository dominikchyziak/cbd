<?php

class CeneoModel extends MY_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('ProductModel');
    }
    
    public function get_ceneo_array(){
        $product_obj = new ProductModel();
        $products = $product_obj->findAll();
        
        return $products;
    }
    
}
