<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Configuration extends Backend_Controller {
    private $configurationModel;
    
    public function __construct() {
        parent::__construct();
        $this->load->model('ConfigurationModel');
        $this->configurationModel = new ConfigurationModel();
        $this->load->vars(['activePage' => 'configuration']);
    }

    public function index() {
                    
        if(!empty($_POST["meta_title"])){
            foreach($_POST as $key=>$value){
                $this->configurationModel->updateOption($key, $value);
            }
        }
        
        $all_options = $this->configurationModel->getAllOptions('main');
        $this->layout('duocms/Configuration/index', [
            'all_options' => $all_options,
            'conf_name' => 'Konfiguracje główne'
        ]);
    }
    public function main_shop() {

    if (!empty($_POST["shop_first_name"])) {
        foreach ($_POST as $key => $value) {
            $this->configurationModel->updateOption($key, $value);
        }
    }

    $all_options = $this->configurationModel->getAllOptions('main_shop');
    $this->layout('duocms/Configuration/index', [
        'all_options' => $all_options,
        'conf_name' => 'Konfiguracje sklepu'
    ]);
}

    public function languages(){
        $lang_to_add = array(
            "pl" => "Polski",
            "en" => "Angielski",
            "ru" => "Rosyjski",
            "de" => "Niemiecki",
            "fr" => "Francuski"
        );
        
        if(!empty($_POST["add_lang"])){
            $short = $this->input->post("add_lang");
            $name = $lang_to_add[$short];
            $this->configurationModel->add_lang($short, $name);
        }
        $languages = $this->configurationModel->get_languages();
        if(!empty($languages)){
            foreach($languages as $lang){
                unset($lang_to_add[$lang->short]);
            }
        }
        
        $this->layout('duocms/Configuration/languages', [
            'lang_to_add' => $lang_to_add,
            'languages' => $languages
        ]);
    }
    public function delete_lang($id){
        $res = $this->configurationModel->delete_language($id);
        if($res){
            $this->setOkay('Język został usunięty.');	
        }
        redirect('duocms/configuration/languages/'.$id);
    }
    
    public function translations(){
        if(!empty($_POST["action"]) && $this->input->post("action") == "add_string"){
            $this->configurationModel->add_string($this->input->post("key"), $this->input->post("translation"));
        }
        $languages = $this->configurationModel->get_languages();
        $translations = $this->configurationModel->get_all_translations();
        $this->layout('duocms/Configuration/translations', [
            'languages' => $languages,
            'translations' => $translations
        ]);
    }
    
    public function manager(){
        if(ENVIRONMENT != 'development'){
            redirect(site_url('duocms/configuration'));
        }
        
        $all_options = $this->configurationModel->getAllOptions();
        $this->layout('duocms/Configuration/manager', array(
            'all_options' => $all_options
        ));
    }
    
    public function admin_modules(){
        if(ENVIRONMENT != 'development'){
            redirect(site_url('duocms/configuration'));
        }
        
        if(!empty($this->input->post())){
            foreach($_POST as $key=>$value){
                $this->configurationModel->updateOption($key, $value);
            }
            setAlert('success','Zapisano');
        }
        
        $all_options = $this->configurationModel->getAllOptions('admin_modules');
        $this->layout('duocms/Configuration/index', array(
            'all_options' => $all_options,
            'conf_name' => 'Moduły admina'
        ));
    }
    
    public function module_config($type){
        if(ENVIRONMENT != 'development'){
            redirect(site_url('duocms/configuration'));
        }
        
        if(!empty($this->input->post())){
            foreach($_POST as $key=>$value){
                $this->configurationModel->updateOption($key, $value);
            }
            setAlert('success','Zapisano');
        }
        
        if($type == 'payu'){
            $all_options = $this->configurationModel->getAllOptions($type);
        } else {
            $all_options = $this->configurationModel->getAllOptions('admin_module_'.$type);
        }
        
        $this->layout('duocms/Configuration/index', array(
            'all_options' => $all_options,
            'conf_name' => 'Konfiguracja modułu '.$type
        ));
    }

        public function ajax_add_option(){
        $data = $this->input->post();
        $this->form_validation->set_rules('name','Nazwa','required');
        $this->form_validation->set_rules('key','Klucz','required');
        $this->form_validation->set_rules('value','Wartość','required');
        $this->form_validation->set_rules('category','Kategoria','required');
        
        if($this->form_validation->run()){
        $res = $this->configurationModel->add_option($data);
            if($res){
                setAlert('success','Dodano opcję');
                ajax_res(array('1','Dodano opcję','refresh'));
            } else {
                ajax_res(array('0','Nie udało się dodać opcji'));
            }
        } else {
            ajax_res(array('0', validation_errors()));
        }
    }
    
    public function ajax_save_visible(){
        $visible = $this->input->post('visible');
        if(!empty($visible)){
            foreach($visible as $k => $v) {
                $this->configurationModel->save_visible($k, $v);
            }
            ajax_res(array('1','Zaktualizowano'));
        }
        ajax_res(array('0','Wystąpił błąd'));
    }
    
    
    public function ssl_configuration(){
        $ssl_enabled = get_option('ssl_enabled');
        
        $this->layout('duocms/Configuration/ssl', ['status' => $ssl_enabled]);
    }
    
    public function enable_ssl(){
        if(!get_option('ssl_enabled')){
            
            $this->configurationModel->updateOption('ssl_enabled', 1);
            
            $this->db->query("UPDATE `duo_custom_element_fields` SET value = REPLACE(value , 'http://', 'https://') WHERE value LIKE '%http://%';");
            $this->db->query("UPDATE `duo_products_translations` SET body = REPLACE(body , 'http://', 'https://') WHERE body LIKE '%http://%';");
            $this->db->query("UPDATE `duo_pages_translations` SET body = REPLACE(body , 'http://', 'https://') WHERE body LIKE '%http://%';");
            $this->db->query("UPDATE `duo_news_translations` SET body = REPLACE(body , 'http://', 'https://') WHERE body LIKE '%http://%';");
            $this->db->query("UPDATE `duo_news_translations` SET image = REPLACE(image , 'http://', 'https://') WHERE image LIKE '%http://%';");
            $this->db->query("UPDATE `duo_offer_categories_translations` SET body = REPLACE(body , 'http://', 'https://') WHERE body LIKE '%http://%';");
            $this->db->query("UPDATE `duo_custom_fields_translations` SET value = REPLACE(value , 'http://', 'https://') WHERE value LIKE '%http://%';");
        }        
        redirect(site_url('duocms/configuration/ssl_configuration'));
    }
    
    public function disable_ssl(){
         if(get_option('ssl_enabled')){
             
            $this->configurationModel->updateOption('ssl_enabled', 0);
            $this->db->query("UPDATE `duo_custom_element_fields` SET value = REPLACE(value , 'https://', 'http://') WHERE value LIKE '%https://%';");
            $this->db->query("UPDATE `duo_products_translations` SET body = REPLACE(body , 'https://', 'http://') WHERE body LIKE '%https://%';");
            $this->db->query("UPDATE `duo_pages_translations` SET body = REPLACE(body , 'https://', 'http://') WHERE body LIKE '%https://%';");
            $this->db->query("UPDATE `duo_news_translations` SET body = REPLACE(body , 'https://', 'http://') WHERE body LIKE '%https://%';");
            $this->db->query("UPDATE `duo_news_translations` SET image = REPLACE(image , 'https://', 'http://') WHERE image LIKE '%https://%';");
            $this->db->query("UPDATE `duo_offer_categories_translations` SET body = REPLACE(body , 'https://', 'http://') WHERE body LIKE '%https://%';");
            $this->db->query("UPDATE `duo_custom_fields_translations` SET value = REPLACE(value , 'https://', 'http://') WHERE value LIKE '%https://%';");
        }
        
        redirect(site_url('duocms/configuration/ssl_configuration'));
    }
}