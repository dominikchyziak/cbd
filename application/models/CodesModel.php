<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CodesModel extends MY_Model {
    
    public $_table_name = 'duo_shop_codes';
    public $_table_used_codes = 'duo_shop_codes_used';
    public $_table_one_time_codes = 'duo_shop_one_time_codes';

    public $id;
    public $name;
    public $code;
    public $type;
    public $value;
    public $min_order;

    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Sprawdza kod dla usera i zwraca 0 jeśli kod błędny, 1 kod wykorzystany
     * @param type $user_id
     * @param type $code
     */
    public function verify_code($user_id, $code){
        $q3 = $this->verify_one_time_code($user_id, $code);
        if(!empty($q3)){
            return $q3;
        }
        $q = $this->db->get_where($this->_table_name, array('code' => $code));
        if($q->num_rows() == 0){
            return 0;
        }
        $code_row = $q->row();
        if(!empty($code_row->valid_until) && strtotime($code_row->valid_until) < time() ){
            return 4;
        }
        $q4 = $this->db->get_where($this->_table_used_codes, array('code_id' => $code_row->id));
        if($q4->num_rows() > 0 && $code_row->one_time_use == 1){
            return 2;
        }
		
		/*
		$q2 = $this->db->get_where($this->_table_used_codes, array('code_id' => $code_row->id, 'user_id' => $user_id));
        if($q2->num_rows() > 0){
            return 2;
        } else {
            return $code_row;
        }
		*/
		
		return $code_row;
    }
    public function verify_one_time_code($user_id, $code){
        $q = $this->db->get_where($this->_table_one_time_codes, array('code' => $code));
        if($q->num_rows() == 0){
            return 0;
        }
        $code_row = $q->row();
        if($code_row->used == 1){
            return 2;
        }
        $email = $code_row->client_email;
        $this->load->model('User_Model');
        $user = $this->User_Model->get_user($user_id);
        if( $email == $user->email ){
            return (array)$this->findByPk(1);
            
        } else {
            return 0;
        }
    }
    /**
     * Ustawia kod
     * @param CodesModel $code
     */
    public function set_code($user_id, $code){
        $ver = $this->verify_code($user_id, $code);

        if(!is_object($ver) && in_array($ver, [0,2,4])) {
            return $ver;
        }
        if(!empty($ver->currency_id)){
            if($ver->currency_id != get_active_currency()->id){
                return 3;
            }
        }
        $this->session->set_userdata('discount',(array)$ver);
        return TRUE;
    }
    
    /**
     * Przelicza rabat
     * @param array $products tablica produktów zawierająca tablice assocjacyjną produktu z paramterem price
     */
    public function recalculate_products($products, $discount_id = null, $no_calc_prod = 0){
        $discount = !empty($this->session->userdata['discount']) ? $this->session->userdata['discount'] : null;
        if($discount_id !== null){
            //nadpisuję discount tym z zamówienia
            $discount = (array)$this->findByPk($discount_id);
        }
        if(!empty($discount) && !empty($products)){
            $sum_price = 0;
            $new_products = array();
            foreach($products as $product){
                $sum_price += $product['price']*$product['quantity'];
                $n_product = $product;
                if($discount['type'] == 0 && $no_calc_prod == 0){
                    $n_product['price'] = number_format($product['price'] - $discount['value'] * $product['price'], 2);
                }
                $new_products[] = $n_product;
            }
            if($sum_price >= $discount['min_order'] && $discount['type'] == 0){
                $products = $new_products;
            }
        }
        
        return $products;
    }
    
    public function recalculate_sum($products, $discount_id = null, $no_calc_prod = 0){
        $products = $this->recalculate_products($products, $discount_id, $no_calc_prod);
        $discount = !empty($this->session->userdata['discount']) ? $this->session->userdata['discount'] : null;
        if($discount_id !== null){
            //nadpisuję discount tym z zamówienia
            $discount = (array)$this->findByPk($discount_id);
        }
        $sum_price = 0;
        if(!empty($products)){
            foreach ($products as $product){
                $sum_price += $product['price']*$product['quantity'];
            }
        }
        if(!empty($discount) && !empty($products)){
            if($sum_price >= $discount['min_order'] && $discount['type'] == 1){
                $sum_price -= $discount['value'];
            }
        }
        return number_format($sum_price, 2,'.','');
    }
    
    public function newsletter_autogen_code($email){
        $code = strtoupper(substr(md5(strrev($email. date('U'))), 0, 6));
        $data = array( 
            'client_email' => $email,
            'code' => $code,
            'used' => 0
            );
        $this->db->insert($this->_table_one_time_codes, $data);
     
        return $code;
    }
}