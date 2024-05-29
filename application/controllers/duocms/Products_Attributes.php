<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products_Attributes extends Backend_Controller {

    public $languages = array();
    public $attr_obj;

    public $menu_item = ['product','product_list'];

    public function __construct() {
        parent::__construct();
        $this->load->model("ProductAttributesModel");
        
        $this->load->helper('form');
        $langs = get_languages();
        foreach ($langs as $l) {
            $this->languages[] = $l->short;
        }
        $this->load->vars(['activePage' => 'products']);
        
        $this->attr_obj = new ProductAttributesModel();
    }
    
    public function index(){
        $this->groups();
    }
    
    public function groups(){
        $groups = $this->attr_obj->get_groups();
        $this->layout('duocms/Shop/Attributes/index', array(
            'groups' => $groups
        ));
    }
    
    public function delete_group($id){
        if($this->attr_obj->delete_group($id)){
            setAlert('warning','Usunięto grupę');
        } else {
            setAlert('error','Nie udało się usunąć');
        }
        redirect(site_url('duocms/Products_Attributes/groups'));
    }
    
    public function attributes_create(){
        $this->menu_item = ['attributes','attributes_create'];
        $error_message = null;
        if(!empty($_POST)){
            $data = $this->input->post();
            $id = $this->attr_obj->attribute_add($data['value'], $data['attributes_group']);
            $args = array();
            foreach ($this->languages as $lang){
                $translation = $data[$lang];
                $args[$lang] = $translation;
            }
            $this->attr_obj->attribute_update($id, $data['value'], $args, $data['attributes_group']);
            if(!empty($id)){
                setAlert('success','Atrybut został dodany poprawnie.');
                redirect('duocms/Products_Attributes/attributes_edit/' . $id);
            } else {
                setAlert('error','Nie udało się dodać atrybutu.');
                redirect('duocms/Products_Attributes/attributes_create');
            }
           
        }
        $groups = $this->attr_obj->get_groups();
        $this->layout('duocms/Shop/Attributes/form',[
            'error_message' => $error_message,
            'groups' => $groups
        ]);
    }
    
    //Atrybuty
    public function attributes(){
        $this->menu_item = ['attributes','attributes_list'];
        $groups = $this->attr_obj->get_groups();
        $groups_array = array();
        if(!empty($groups)){
            foreach ($groups as $group){
                $groups_array[] = array(
                    'group' => $group,
                    'attributes' => $this->attr_obj->get_attributes_by_group($group->attributes_group_id)
                );
            }
        }
        $this->layout('duocms/Shop/Attributes/attributes',[
            'attributes' => $groups_array
        ]);
    }
    
    public function ajax_get_attributes_by_group(){
        $id = $this->input->post('group_id');
        $group = $this->attr_obj->get_attributes_by_group($id);
        echo json_encode($group);
        die();
    }
    
    public function attributes_edit($id){
        $this->menu_item = ['attributes','attributes_edit'];
        if(!empty($_POST)){
            $data = $this->input->post();
            $args = array();
            foreach ($this->languages as $lang){
                $translation = $data[$lang];
                $args[$lang] = $translation;
            }
            $this->attr_obj->attribute_update($id, $data['value'], $args, $data['attributes_group']);
            setAlert('success','Atrybut został zaktualizowany poprawnie.');
            redirect('duocms/Products_Attributes/attributes_edit/' . $id);

        }
        
        $attribute = $this->attr_obj->attribute_get_by_id($id);
        $groups = $this->attr_obj->get_groups();
        $this->layout('duocms/Shop/Attributes/form',[
            'attribute' => $attribute,
            'groups' => $groups
        ]);
    }
    
    public function attributes_delete($id){
        $res = $this->attr_obj->attribute_delete($id);
        if($res){
            setAlert('warning','Atrybut został usunięty poprawnie.');
            redirect('duocms/Products_Attributes/attributes');
        }
    }
    
    public function sort(){
        if (!empty($_POST['element'])) {
            $i = 1;
            foreach ($_POST['element'] as $id) {
                $this->attr_obj->sort_item($id, $i);
                $i++;
            }
        }
    }
    
    public function sort_groups(){
            if (!empty($_POST['element'])) {
                $i = 1;
                foreach ($_POST['element'] as $id) {
                    $this->attr_obj->sort_group($id, $i);
                    $i++;
                }
            }
        }
        
    public function add(){
        //$this->load->view('duocms/Shop/form');
    }
    
    public function ajax_add_group(){
        
        $this->form_validation->set_rules('name_pl','Nazwa', 'required');
        if($this->form_validation->run()){
            
            $names = array();
            if(!empty($_POST)){
                $names = $this->input->post();
               
                $translations = array();
                foreach ($names as $key=>$value){
                    $nam_lang = explode('_', $key);
                    if($nam_lang[0] == 'name'){
                        $arg = array();
                        $arg['lang'] = $nam_lang[1];
                        $arg['name'] = $value;
                        $arg['description'] = '';
                        $translations[] = $arg;
                    }
                } 

                $this->attr_obj->add_group(array( 'translations' => $translations ));
                setAlert('success','Dodano');
                ajax_res(array('1', 'Dodano!','refresh'));
            }
        } else {
            ajax_res(array('0', validation_errors()));
        }
        ajax_res(array('0','Błąd'));
    }
    
    public function ajax_edit_group($id){
        $this->form_validation->set_rules('name_pl','Nazwa', 'required');
        if($this->form_validation->run()){
            $names = array();
            if(!empty($_POST)){
                $names = $this->input->post();
               
                $translations = array();
                foreach ($names as $key=>$value){
                    $nam_lang = explode('_', $key);
                    if($nam_lang[0] == 'name'){
                        $arg = array();
                        $arg['lang'] = $nam_lang[1];
                        $arg['name'] = $value;
                        $arg['description'] = '';
                        $translations[] = $arg;
                    }
                } 

                $this->attr_obj->update_group($id,array( 'translations' => $translations ));
                setAlert('info','Zaktualizowano');
                ajax_res(array('1', 'Dodano!','refresh'));
            }
        } else {
            ajax_res(array('0', validation_errors()));
        }
        ajax_res(array('0','Błąd'));
    }
    
    public function ajax_get_group(){
        $id = $this->input->post('group_id');
        $group = $this->attr_obj->get_group_with_trans($id);
        echo json_encode($group);
        die();
    }
    
    public function ajax_get_default_value($attribute_id){
        $attribute = $this->attr_obj->attribute_get_by_id($attribute_id);
        echo !empty($attribute['value']) ? $attribute['value'] : 0;
        die();
    }
    
}