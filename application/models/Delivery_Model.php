<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Delivery_Model extends MY_Model {

    protected $_table_name = 'shop_delivery';
    protected $_table_translation_name = 'shop_delivery_translations';
    public $id;
    public $category_id;
    public $lang;
    public $price;
    public $name;
    public $special_name;
    public $description;
    
    public function __construct() {
        parent::__construct();
    }

    public function get_list() {
        $q = $this->db->get($this->_table_name)->result();
        $result = array();
        if (!empty($q)) {
            foreach ($q as $r) {
                $result[] = $this->get_delivery($r->id);
            }
        }
        return $result;
    }
    
    public function get_price($id){
        $delivery = $this->get_delivery($id);
        return $delivery['delivery_price'];
    }

    public function get_price2($delivery_id, $currency_id){
        $delivery = $this->get_delivery($delivery_id);
        return $delivery['prices'][$currency_id]['price'];
    }
    public function get_max_price($delivery_id, $currency_id){
        $delivery = $this->get_delivery($delivery_id);
        return $delivery['prices'][$currency_id]['max_price'];
    }
    public function get_delivery($id) {
        $result = array();
        $q = $this->db->get_where($this->_table_name, array('id' => $id))->result();
        if (!empty($q)) {
            $result = (array) $q[0];
            $t = $this->db->get_where($this->_table_translation_name, array(
                        'delivery_id' => $id
                    ))->result();
            if (!empty($t)) {
                $result['translations'] = array();
                foreach ($t as $tr) {
                    $result['translations'][$tr->lang] = array(
                        'name' => $tr->name,
                        'description' => $tr->description,
                        'visibility' => $tr->visibility
                    );
                }
            }
            $result['prices'] = $this->get_prices($id);
        }

        return $result;
    }

    //$args = array('price' => '', max_price, min_weight, max_weight)
    public function add_delivery($args) {
        $this->db->insert($this->_table_name, array(
            'delivery_price' => !empty($args['delivery_price']) ? $args['delivery_price'] : '',
            'weight_min' => $args['weight_min'],
            'weight_max' => $args['weight_max'],
            'max_price' => !empty($args['max_price']) ? $args['max_price'] : '',
            'package_amount' => $args['package_amount'],
            'category_id' => $args['category_id'],
            'special_name' => (!empty($args['special_name'])) ? $args['special_name'] : ''
        ));
        $id = $this->db->insert_id();
        if(!empty($args['prices'])){
            foreach($args['prices'] as $currency_id => $p){
                $data = array(
                    'max_price' =>$args['max_prices'][$currency_id],
                    'price' => $p,
                    'currency_id' => $currency_id,
                    'delivery_id' => $id
                );
                $this->db->insert('duo_shop_delivery_prices', $data);
            }
        }
        return $id;
    }

    public function update_delivery($id, $data, $args) {
        $this->db->where('id', $id)->update($this->_table_name, array(
            'delivery_price' => !empty($data['delivery_price']) ? $data['delivery_price'] : '',
            'weight_min' => $data['weight_min'],
            'weight_max' => $data['weight_max'],
            'max_price' =>  !empty($data['max_price']) ? $data['max_price'] : '',
            'package_amount' => $data['package_amount'],
            'modified_at' => date('Y-m-d H:i:s'),
            'category_id' => $data['category_id'],
            'special_name' => (!empty($data['special_name'])) ? $data['special_name'] : ''
        ));
         if(!empty($data['prices'])){
            foreach($data['prices'] as $currency_id => $p){
                $data1 = array(
                    'max_price' =>$data['max_prices'][$currency_id],
                    'price' => $p
                );
                $this->db->where('currency_id', $currency_id);
                $this->db->where('delivery_id', $id);
                $this->db->update('duo_shop_delivery_prices', $data1);
            }
        }
        if (!empty($args)) {
            foreach ($args as $lang => $trans) {
                $q = $this->db->get_where($this->_table_translation_name, array(
                            'delivery_id' => $id,
                            'lang' => $lang
                        ))->result();
                if (!empty($q)) {
                    $this->db->where(array(
                        'delivery_id' => $id,
                        'lang' => $lang
                    ))->update($this->_table_translation_name, array(
                        'name' => $trans['name'],
                        'description' => $trans['description'],
                        'visibility' => !empty($trans['visibility']) ? '1' : '0',
                        'modified_at' => date('Y-m-d H:i:s')
                    ));
                } else {
                    $this->db->insert($this->_table_translation_name, array(
                        'delivery_id' => $id,
                        'lang' => $lang,
                        'name' => $trans['name'],
                        'description' => $trans['description'],
                        'visibility' => !empty($trans['visibility']) ? '1' : '0'
                    ));
                }
            }
        }
    }

    public function delete_delivery($delivery_id) {
        $this->db->delete($this->_table_name, array("id" => $delivery_id));
    }
    
    public function get_deliveries($price, $weight, $lang = 'pl'){
        $q = $this->db->join($this->_table_translation_name,$this->_table_translation_name.".delivery_id = ". $this->_table_name.".id")
                ->get_where($this->_table_name, array(
            'weight_min <=' => number_format($weight,2,'.', ''),
            'weight_max >=' => number_format($weight,2,'.', ''),
                    'lang' => $lang,
                    'visibility' => 1
        ))->result();
        foreach($q as $res){
            
            $res->prices = $this->get_prices($res->delivery_id);
        }
        return $q;
    }
    
        public function get_list_for_dropdown(){
        $list = $this->get_list();
        $wyn = array();
        foreach($list as $l){
            $wyn[$l['id']]=$l['translations']['pl']['name'].' - '. $l['translations']['pl']['description']; 
        }
        return $wyn;
    }
public function get_delivery_by_special_name($spec_name){
        $this->db->select('id');
        $this->db->where('special_name', $spec_name);
        $q = $this->db->get($this->_table_name);
        $wyn = array();
        if($q->num_rows()>0){
            foreach($q->result() as $res){
                $wyn[] = $res->id;
            }
        }
        return $wyn;
    }
    
    public function get_prices($delivery_id){
        $this->db->where('delivery_id', $delivery_id);
        $q = $this->db->get('duo_shop_delivery_prices');
        
        $res = $q->result();
        $wyn = array();
        if(!empty($res)){
            foreach($res as $r){
                $wyn[$r->currency_id] = array(
                    'max_price' => $r->max_price,
                    'price' => $r->price
                );
            }
        }
        return $wyn;
    }
}
