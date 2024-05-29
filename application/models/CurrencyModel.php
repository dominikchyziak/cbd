<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CurrencyModel extends MY_Model {

    protected $_table_name = 'shop_currencies';
    public $id;
    public $name;
    public $code;
    public $comment;
    public $visibility;
    
    public function __construct() {
        parent::__construct();
    }

//    public function get_list() {
//        $q = $this->db->get($this->_table_name)->result();
//        $result = array();
//        if (!empty($q)) {
//            foreach ($q as $r) {
//                $result[] = $this->get_delivery($r->id);
//            }
//        }
//        return $result;
//    }
//    
//
//    public function get_delivery($id) {
//        $result = array();
//        $q = $this->db->get_where($this->_table_name, array('id' => $id))->result();
//        if (!empty($q)) {
//            $result = (array) $q[0];
//            $t = $this->db->get_where($this->_table_translation_name, array(
//                        'delivery_id' => $id
//                    ))->result();
//            if (!empty($t)) {
//                $result['translations'] = array();
//                foreach ($t as $tr) {
//                    $result['translations'][$tr->lang] = array(
//                        'name' => $tr->name,
//                        'description' => $tr->description,
//                        'visibility' => $tr->visibility
//                    );
//                }
//            }
//        }
//
//        return $result;
//    }

    public function add_currency($args) {
        $this->db->insert($this->_table_name, array(
            'name' => $args['name'],
            'code' => $args['code'],
            'comment' => $args['comment'],
            'visibility' => $args['visibility']
        ));
        return $this->db->insert_id();
    }

    public function update_currency($id, $args) {
        $this->db->where('id', $id)->update($this->_table_name, array(
            'name' => $args['name'],
            'code' => $args['code'],
            'comment' => $args['comment'],
            'visibility' => $args['visibility']
        ));
   
    }

    public function delete_currency($currency_id) {
        $this->db->delete($this->_table_name, array("id" => $currency_id));
    }
    
    public function get_all_currencies(){
        $q = $this->db->get($this->_table_name);
        return $q->result('CurrencyModel');
    }
    
    public function get_currency($id){
        $this->db->where('id', $id);
        $q = $this->db->get($this->_table_name);
        return $q->result('CurrencyModel')[0];
    }
    
    public function get_acitve_currencies(){
        $this->db->where('visibility', 1);
        $q = $this->db->get($this->_table_name);
        return $q->result('CurrencyModel');
    }
    
    public function getPermalink(){
        return site_url('currency/'.$this->id);
    }
    
    public function get_default_currency(){
        $id = get_option('admin_default_currency');
        $this->db->where('id', $id);
        $q = $this->db->get($this->_table_name);
        return $q->result('CurrencyModel')[0];
    }
    
}
