<?php

class ProductOptionModel extends MY_Model {
   
    private $_table = "duo_shop_options";
    private $_table_translations = "duo_shop_options_translations";


    public $id;
    public $product_id;
    public $name;
    public $description;
    public $weight;
    public $price_change;
    public $old_price;
    public $quantity;
    public $quantity_left;
    public $visibility;
    
    public function __construct() {
        parent::__construct();
        //doliczam ewentualny rabat
        if (!empty($this->session->userdata['login']['user']['discount']) && !empty($this->price_change)) {
            $dsc = $this->session->userdata['login']['user']['discount'];
            $this->price_change = round(($this->price_change - ($dsc / 100) * $this->price_change) * 100) / 100;
        }
    }
}