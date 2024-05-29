<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GallerySetup extends Backend_Controller {

    public $languages = array();
    public $product_obj;
    public $attr_obj;


    public function __construct() {
        parent::__construct();
        $this->load->model('GallerySetupModel');
        
        $this->load->helper('form');

        $this->load->vars(['activePage' => 'gallery_setup']);
        
    }

    public function index() {
        
        if (!empty($_POST['element'])) {
            $i = 1;
            foreach ($_POST['element'] as $id) {
                $this->GallerySetupModel->sort_item($id, $i);
                $i++;
            }
            die();
        }
        
        if($this->input->post('action') == 'add'){
            $this->GallerySetupModel->add_setup_item($this->input->post('module_id'));
        }
        $setup = $this->GallerySetupModel->get_setup();
        $modules = $this->GallerySetupModel->get_modules_array();
        $this->layout('duocms/GallerySetup/index', [
            'modules' => $modules,
            'setup' => $setup,
            'modules_dropdown' => $this->GallerySetupModel->get_modules_for_droplist()
        ]);
    }


    public function delete($id) {
       $this->GallerySetupModel->delete_setup_item($id);
       
        redirect($this->input->server('HTTP_REFERER'));
    }

    public function css(){
        if(!empty($this->input->post())){
            $pdata = $this->input->post();
            foreach($pdata['id'] as $key => $id){
                $args =['id' => $id, 'value' => $pdata['value'][$key] ];
                $this->GallerySetupModel->update_css_option($args);
            }
        }
        
        $options = $this->GallerySetupModel->get_css_option_array();
        
        $this->layout('duocms/GallerySetup/css', [
            'options' => $options
        ]);
    }
   
    

}
