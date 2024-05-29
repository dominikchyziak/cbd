<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Custom_Fields extends Backend_Controller {
    private $cf_obj;
    
    public function __construct() {
        parent::__construct();
        $this->load->model('CustomFieldsModel');
        $this->cf_obj = new CustomFieldsModel();
    }
    
    public function ajax_add_field(){
        $data = $this->input->post();
        $this->form_validation->set_rules('element', 'Element', 'required');
        $this->form_validation->set_rules('element_id', 'Id elementu', 'required');
        $this->form_validation->set_rules('type', 'Typ', 'required');
        $this->form_validation->set_rules('name', 'Nazwa', 'required');
        if($this->form_validation->run()){
            $res = $this->cf_obj->add_field($data['type'], $data['element'], $data['name'], $data['element_id']);
            if($res){
                setAlert('success','Dodano pole');
                ajax_res(array('1','Dodano pole','refresh'));
            } else {
                ajax_res(array('0','Coś poszło nie tak.'));
            }
            
        } else {
            ajax_res(array('0', validation_errors()));
        }
    }
    
    public function ajax_delete_field($field_id){
        $res = $this->cf_obj->delete_field($field_id);
        if($res){
            setAlert('warning', 'Usunięto pole');
            ajax_res(array('2','Usunięto','refresh'));
        } else {
            ajax_res('0', 'Nie udało się usunąć pola');
        }
    }
}