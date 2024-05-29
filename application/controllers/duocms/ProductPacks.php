<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ProductPacks extends Backend_Controller {

    public $languages = array();
    public $product_obj;
    public $attr_obj;

    public $menu_item = ['product','product_list'];

    public function __construct() {
        parent::__construct();
        $this->load->model("ProductPhotoModel");
        $this->load->model('ProductModel');
        $this->load->model('ProductTranslationModel');
        $this->load->model('OfferCategoryModel');
        $this->load->model("ProductAttributesModel");
        $this->load->model("ProductPackModel");
        
        $this->load->helper('form');
        $langs = get_languages();
        foreach ($langs as $l) {
            $this->languages[] = $l->short;
        }
        $this->load->vars(['activePage' => 'products_pack']);
        
        $this->product_obj = new ProductModel();
        $this->attr_obj = new ProductAttributesModel();
    }
    
    public function test(){
        var_dump($this->ProductPackModel->get_all_attribute_value_of_pack(1, 101));die();
    }
    
    public function index(){
        $packs = $this->ProductPackModel->get_all_packs();
        foreach($packs as $pack){
            $pack->count = $this->ProductPackModel->count_products_in_pack($pack->id);
        }
        $this->layout('duocms/Shop/ProductPack/index', ['packs' => $packs]);
    }
    
    public function edit($pack_id){
        $pack = new ProductPackModel();
        $products = $this->product_obj->get_product_list_for_relations(0);
        $pack_products = $this->ProductPackModel->get_prod_ids_by_pack_id($pack_id);
        
        
        if(!empty($this->input->post('edycja'))){
            
            $data['id'] = $pack_id;
            $data['name'] = $this->input->post('name');
            $data['attr_grp_1_id'] = $this->input->post('attr_grp_1_id');
            $data['attr_grp_2_id'] = $this->input->post('attr_grp_2_id');
            $pack->update_pack($data);
            $rels = $this->input->post('relations');
            if(!empty($rels)){
                foreach($pack_products as $rel_stare){
                    if(!in_array($rel_stare, $rels)){
                        $pack->delete_product_from_pack($rel_stare,$pack_id);
                    }
                }
                foreach($rels as $rel ){  
                    if(!$pack->check_if_product_in_pack($rel, $pack_id)){
                        $pack->add_product_to_pack($rel, $pack_id);
                    }
                }
            } else {
                foreach($pack_products as $rel_stare){
                   $pack->delete_product_from_pack($rel_stare,$pack_id);
                }
            }
        }
        
        $pack_data = $pack->get_pack($pack_id);
        
        $attr_grps = $this->attr_obj->get_groups();
        $attr_groups[0] = 'brak';
        foreach ($attr_grps as $ag) {
            $attr_groups[$ag->attributes_group_id] = $ag->name;
        }
        $pack_products = $this->ProductPackModel->get_prod_ids_by_pack_id($pack_id);
        $this->layout('duocms/Shop/ProductPack/form', array(
            'attr_groups' => $attr_groups,
            'pack' => $pack_data,
            'products' => $products,
            'pack_products'=> $pack_products
        ));
    }
    
    public function delete($id){
        $pack_obj = new ProductPackModel();
        $pack = $pack_obj->get_pack($id);
        if (!$pack->id) {
            show_404();
        }

        $res = $pack_obj->delete_pack($id);

        if ($res) {
            setAlert('info','Produkt został uzunięty.');
        } else {
            setAlert('error','Wystąpił błąd.');
        }

        redirect($this->input->server('HTTP_REFERER'));
    }


    public function ajax_add_pack(){

    $this->form_validation->set_rules('name','Nazwa', 'required');
    if($this->form_validation->run()){
        if(!empty($_POST)){
            
            $name = $this->input->post('name');
            $this->ProductPackModel->add_pack($name);
            
            setAlert('success','Dodano');
            ajax_res(array('1', 'Dodano!','refresh'));
        }
    } else {
        ajax_res(array('0', validation_errors()));
    }
    ajax_res(array('0','Błąd'));
    }
    
}