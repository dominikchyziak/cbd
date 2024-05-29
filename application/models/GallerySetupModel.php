<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GallerySetupModel extends MY_Model {

    protected $_table_modules = 'duo_gallery_modules';
    protected $_table_setup = 'duo_gallery_view_setup';
    protected $_table_css = 'duo_gallery_css_options';
    protected $_table_relations = 'duo_gallery_relations';
    public function __construct() {
        parent::__construct();

    }

    public function delete_setup_item($id) {
        $this->db->where('id', $id);
        $res = $this->db->delete($this->_table_setup);

        return $res;
    }
    public function add_setup_item($module_id, $direction = '0'){
        $new_order = $this->db->get($this->_table_setup)->num_rows();
        $this->db->insert($this->_table_setup,[
            'order_of_element' => $new_order+1,
            'module_id' => $module_id,
            'direction' => $direction
        ]);
    }

    
    /**
     * Sortowanie pozycji
     * @param int $item_id
     * @param int $position
     * @return bool
     */
    public function sort_item($item_id, $position) {
        $this->db->where('id', $item_id);
        $res = $this->db->update($this->_table_setup, [
            'order_of_element' => $position
        ]);
        return $res;
    }

    
    public function get_modules_array(){
        $q = $this->db->get($this->_table_modules);
        $output = [];
        foreach($q->result() as $row){
            $output[$row->id] = $row;
        }
        return $output;
    }
    
    public function get_modules_for_droplist(){
        $q = $this->db->get($this->_table_modules);
        $output = [];
        foreach($q->result() as $row){
            $output[$row->id] = $row->friendly_name;
        }
        return $output;
    }
    public function get_module_by_id($id){
        $this->db->where('id', $id);
        $q = $this->db->get($this->_table_modules);
        return $q->row();
    }
    
    public function get_module_by_max($count){
        $this->db->where('max_number', $count);
        $this->db->order_by('max_number', 'DESC');
        $q = $this->db->get($this->_table_modules);
        return $q->row();
    }
    
    public function get_setup(){
        $this->db->order_by('order_of_element', 'ASC');
        $q = $this->db->get($this->_table_setup);
        
        return $q->result();
    }
    
    public function get_css_option_array(){
         $q = $this->db->get($this->_table_css);
        
        return $q->result();
    }
    
    public function update_css_option($args){
        $data = [ 'value' => $args['value']];
        $this->db->where('id',$args['id']);
        $res = $this->db->update($this->_table_css, $data);
        return $res;
    }
    
    public function get_css_option_for_view(){
        $q = $this->db->get($this->_table_css);
        $output = [];
        foreach($q->result() as $row){
            $output[$row->variable_name] = $row->value;
        }
        return $output;
    }
    
    public function get_integrator_data($item_type, $item_id){
        $this->db->where('item_type', $item_type);
        $this->db->where('item_id', $item_id);
        $q = $this->db->get($this->_table_relations);
        if($q->num_rows() > 0){
            
            $this->load->model('GalleryModel');
            $galleryObject = new GalleryModel($q->row()->gallery_id);
            
            $gallery_widget['setup'] = $this->get_setup();
            $gallery_widget['modules'] = $this->get_modules_array();
            $gallery_widget['photos'] = $galleryObject->findAllPhotos();
            
            return $gallery_widget;
        } else {
            return null;
            
        } 
    }
    
    public function save_relation($item_type, $item_id, $gallery_id) {
        $this->db->where('item_type', $item_type);
        $this->db->where('item_id', $item_id);
        $q = $this->db->get($this->_table_relations);
        if ($q->num_rows() > 0) {
            $relation_id = $q->row()->id;
            $this->db->where('id', $relation_id);
            $this->db->update($this->_table_relations, ['gallery_id' => $gallery_id]);
        } else {
            $this->db->insert($this->_table_relations, [
                'item_type' => $item_type,
                'item_id' => $item_id,
                'gallery_id' => $gallery_id
            ]);
        }
    }
    
    public function get_relation($item_type, $item_id) {
        $this->db->where('item_type', $item_type);
        $this->db->where('item_id', $item_id);
        $q = $this->db->get($this->_table_relations);
        if ($q->num_rows() > 0) {
            return $q->row()->gallery_id;
        } else {
            return 0;
        }
    }

}
