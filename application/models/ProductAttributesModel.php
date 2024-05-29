<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ProductAttributesModel extends MY_Model {

    private $attributes_table = 'duo_shop_attributes';
    private $attributes_table_translations = 'duo_shop_attributes_translations';
    private $attributes_table_relations = 'duo_shop_attributes_relations';
    protected $_table_relations = 'duo_shop_attributes_relations';
    protected $_table_gropus = 'duo_shop_attributes_groups';
    protected $_table_groups_translations = 'duo_shop_attributes_groups_translations';

    function __construct() {
        parent::__construct();
    }

    /**
     * Dodawanie grupy atrybutów
     * @param array $args tablica atrybutów w tym tablica translacji $args['translations'][] = ['lang','name','description']
     */
    public function add_group($args) {
        $translations = $args['translations'];

        if(empty($args['allegro'])){
        $this->db->insert($this->_table_gropus, array('order' => 0));
        } else {
        $this->db->insert($this->_table_gropus, array('order' => 0, 'allegro_id' => $args['allegro']) );    
        }
        $group_id = $this->db->insert_id();

        if (!empty($translations) && !empty($group_id)) {
            foreach ($translations as $translation) {
                $this->db->insert($this->_table_groups_translations, array(
                    'lang' => !empty($translation['lang']) ? $translation['lang'] : 'pl',
                    'attributes_group_id' => $group_id,
                    'name' => !empty($translation['name']) ? $translation['name'] : '',
                    'description' => !empty($translation['description']) ? $translation['description'] : ''
                ));
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * AKtualizacja grupy atrybutów
     * @param int $id identywikator grupy
     * @param array $args jak w przypadku dodawania
     */
    public function update_group($id, $args) {
        $translations = $args['translations'];

        if (!empty($translations) && !empty($id)) {
            foreach ($translations as $translation) {
                $this->db->where(array('attributes_group_id' => $id, 'lang' => $translation['lang']))->update($this->_table_groups_translations, array(
                    'lang' => !empty($translation['lang']) ? $translation['lang'] : 'pl',
                    'name' => !empty($translation['name']) ? $translation['name'] : '',
                    'description' => !empty($translation['description']) ? $translation['description'] : ''
                ));
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_groups($lang = 'pl') {
        $this->db->join($this->_table_groups_translations, $this->_table_groups_translations . '.attributes_group_id = ' . $this->_table_gropus . '.id');
        $this->db->order_by("order", "ASC");
        $q = $this->db->get_where($this->_table_gropus, array('lang' => $lang));
        return $q->result();
    }
    
    public function get_groups2($json, $lang = 'pl') {
        $grp_ids = json_decode($json);
        if(!empty($grp_ids)){
        $this->db->join($this->_table_groups_translations, $this->_table_groups_translations . '.attributes_group_id = ' . $this->_table_gropus . '.id');
        
        $this->db->where_in($this->_table_gropus . '.id', $grp_ids);
        $this->db->where('lang', $lang);
        $this->db->order_by("order", "ASC");
        $q = $this->db->get($this->_table_gropus);
        return $q->result();
        } else return array();
    }
    
    public function get_groups3($category_id, $lang = 'pl') {
        $this->db->distinct();
        $this->db->select('duo_shop_attributes_groups_translations.*, duo_shop_attributes_groups.*');
        $this->db->join($this->_table_groups_translations, $this->_table_groups_translations . '.attributes_group_id = ' . $this->_table_gropus . '.id');
        $this->db->join('duo_shop_attributes', 'duo_shop_attributes.attributes_group_id = duo_shop_attributes_groups.id');
        $this->db->join($this->_table_relations, $this->_table_relations. '.attribute_id = duo_shop_attributes.id' );
        $this->db->join('duo_products', 'duo_products.id = '. $this->_table_relations.'.product_id');
        $this->db->join('duo_products_categories', 'duo_products_categories.product_id = duo_products.id');
        if(!empty($category_id)){
        $categories = [];
        if(!is_array($category_id)){
            $categories = [$category_id];
        } else {
            $categories = $category_id;
        }
        $this->db->where_in('duo_products_categories.category_id', $category_id);
        }
        $this->db->order_by($this->_table_gropus.".order", "ASC");
        $q = $this->db->get_where($this->_table_gropus, array('lang' => $lang));
        return $q->result();
    }
    
    
    public function get_group($id, $lang = 'pl') {
        $this->db->join($this->_table_groups_translations, $this->_table_groups_translations . '.attributes_group_id = ' . $this->_table_gropus . '.id');
        $q = $this->db->get_where($this->_table_gropus, array('lang' => $lang, $this->_table_gropus.'.id' => $id));
        return $q->row();
    }
    public function get_group_with_trans($id){
        $q = $this->db->get_where($this->_table_groups_translations, array(
            'attributes_group_id' =>$id
        ));
        return $q->result();
    }

    /**
     * Usuwam grupę atrybutów ze wszystkimi atrybutami itd
     * @param type $id
     */
    public function delete_group($id) {
        return $this->db->delete($this->_table_gropus, array('id' => $id));
    }

    
    
    //Atrybuty
    public function attribute_add($value, $group_id = 0, $allegro_id=-1) {
        $this->db->insert($this->attributes_table, array(
            'value' => $value,
            'attributes_group_id' => $group_id,
            'allegro_id' => $allegro_id
                ));
        return $this->db->insert_id();
    }

    //$args = array('pl' => [name=>'',description=>'']);
    public function attribute_update($id, $value, $args, $attributes_group_id = 0) {
        $this->db->where('id', $id)->update($this->attributes_table, array('value' => $value, 'attributes_group_id' => $attributes_group_id));
        if (!empty($args)) {
            foreach ($args as $key => $arg) {
                $q = $this->db->get_where($this->attributes_table_translations, array('attribute_id' => $id, 'lang' => $key))->result();
                if (!empty($q)) {
                    $this->db->where(array('attribute_id' => $id, 'lang' => $key))->update($this->attributes_table_translations, $arg);
                } else {
                    $this->db->insert($this->attributes_table_translations, array('attribute_id' => $id, 'lang' => $key, 'name' => $arg['name'], 'description' => $arg['description']));
                }
            }
        }
        return TRUE;
    }

    public function attribute_delete($id) {
        $this->db->delete($this->attributes_table, array('id' => $id));
        return TRUE;
    }

    public function attribute_get_list($lang = 'pl', $group_id = null) {
        if($group_id !== null){
            $this->db->where('attributes_group_id', $group_id);
        }
        $q = $this->db->select($this->attributes_table . ".id AS id, "
                        . "" . $this->attributes_table . ".value AS value, "
                        . "" . $this->attributes_table_translations . ".name, "
                        . "" . $this->attributes_table_translations . ".description, ")
                ->where('lang', $lang)
                ->join($this->attributes_table_translations, $this->attributes_table_translations . '.attribute_id = ' . $this->attributes_table . '.id', 'left')
                ->order_by('order','ASC')
                ->get($this->attributes_table);
        $res = $q->result();
        return $res;
    }
    public function attribute_get_by_id($id){
        $q = $this->db->where('id',$id)->get($this->attributes_table)->result();
        if(!empty($q)){
            $result = (array)$q[0];
            $tmp = array();
            $q2 = $this->db->where(array('attribute_id' => $id))->get($this->attributes_table_translations)->result();
            if(!empty($q2)){
                foreach ($q2 as $trans){
                    $tmp[$trans->lang] = array(
                        'name' => $trans->name,
                        'description' => $trans->description
                    );
                }
            }
            $result['translations'] = $tmp;
        } else {
            $result = array();
        }
        return $result;
    }
    
    public function get_attributes_by_group($group_id, $lang = 'pl'){
        return $this->attribute_get_list($lang, $group_id);
    }

    public function sort_item($item_id, $position) {
        $this->db->where('id', $item_id);
        $res = $this->db->update($this->attributes_table, [
            'order' => $position
        ]);
        return $res;
    }
    
    public function sort_group($group_id, $position){
        $this->db->where('id', $group_id);
        $res = $this->db->update($this->_table_gropus, [
            'order' => $position
        ]);
        return $res;
    }
    
    public function check_if_allegro_group_exist($allegro_id){
        $this->db->where('allegro_id', $allegro_id);
        $q = $this->db->get($this->_table_gropus);
    if($q->num_rows() > 0 ) { return TRUE; }
        else { return FALSE; }
    }
    
    public function check_if_allegro_attr_exist($allegro_id){
            $this->db->where('allegro_id', $allegro_id);
            $q = $this->db->get($this->attributes_table);
            if($q->num_rows() > 0 ) { return TRUE; }
                else { return FALSE; }
    }
    
    public function find_group_by_allegro_group_id($allegro_id){
        $this->db->where('allegro_id', $allegro_id);
        $q = $this->db->get($this->_table_gropus);
        $res = $q->result()[0]->id;
        return $res;
    }
    
    public function find_attr_by_allegro_id($allegro_id){
        $this->db->where('allegro_id', $allegro_id);
        $q = $this->db->get($this->attributes_table);
        $res = $q->result()[0]->id;
        return $res;
    }
    
    public function get_attr_grp_by_offer_category($id){
        $q = "SELECT DISTINCT duo_shop_attributes_groups.id as id, duo_shop_attributes_groups_translations.name as name"
                . " FROM duo_shop_attributes_groups"
                . " JOIN duo_shop_attributes_groups_translations ON duo_shop_attributes_groups_translations.attributes_group_id = duo_shop_attributes_groups.id"
//                . " JOIN duo_shop_attributes ON duo_shop_attributes.attributes_group_id = duo_shop_attributes_groups.id"
              //  . " JOIN duo_shop_attributes_relations ON duo_shop_attributes.id = duo_shop_attributes_relations.attribute_id"
//                . " JOIN duo_products ON duo_products.id = duo_shop_attributes_relations.product_id"
//                . " WHERE duo_products.offer_category_id = ".$id;
        ;
        return $this->db->query($q)->result();
        
    }
    
    public function find_group_by_name($name){
        $this->db->where('lang', 'pl');
        $this->db->where('name', $name);
        $q = $this->db->get($this->_table_groups_translations);
        $res = $q->row();
        if(!empty($res)){
            return $res->attributes_group_id;
        } else {
            return null;
        }
    }
    
    public function find_attribute_by_name_and_group($name, $group_name){
        $group_id = $this->find_group_by_name($group_name);
        if(empty($group_id)){
           return null; 
        }
        $this->db->select('duo_shop_attributes.id');
        $this->db->where('duo_shop_attributes_translations.name', $name);
        $this->db->where('duo_shop_attributes.attributes_group_id', $group_id);
        $this->db->join('duo_shop_attributes_translations', 'duo_shop_attributes.id = duo_shop_attributes_translations.attribute_id');
        $q = $this->db->get('duo_shop_attributes');
        if($q->num_rows() > 0){
            return $q->row()->id;
        } else
        {
            return null;
        }
    }
    
     public function find_attribute_by_name_and_group_id($name, $group_id){
        $this->db->select('duo_shop_attributes.id');
        $this->db->where('duo_shop_attributes_translations.name', $name);
        $this->db->where('duo_shop_attributes.attributes_group_id', $group_id);
        $this->db->join('duo_shop_attributes_translations', 'duo_shop_attributes.id = duo_shop_attributes_translations.attribute_id');
        $q = $this->db->get('duo_shop_attributes');
        if($q->num_rows() > 0){
            return $q->row()->id;
        } else
        {
            return null;
        }
    }
    
    public function find_products_with_attribute($a_id){ 
        $this->db->select('product_id');
        $this->db->where('attribute_id', $a_id);
        $q = $this->db->get('duo_shop_attributes_relations');
        
        return $q->result();
    }
}
