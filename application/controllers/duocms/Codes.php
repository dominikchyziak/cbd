<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Codes extends Backend_Controller {
    
    public $langs;
    public $codes;
    
    function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('CodesModel');
        $this->codes = new CodesModel();
        $langs = get_languages();
        foreach ($langs as $l) {
            $this->languages[] = $l->short;
        }
        $this->load->vars(['activePage' => 'codes']);
    }
    
    public function index(){
        $codes = $this->codes->findAll();
        $this->load->model('CurrencyModel');
        $currency = new CurrencyModel();
        $this->layout('duocms/Shop/Codes/index', array(
            'codes' => $codes,
            'currency' => $currency
        ));
    }
    
    public function ajax_add_code(){
        $this->form_validation->set_rules('name','Nazwa','required');
        $this->form_validation->set_rules('code','Kod','required');
        $this->form_validation->set_rules('value','Wartość','required');
        $this->form_validation->set_rules('type','Typ','required');
        if($this->form_validation->run()){
            $args = [
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'value' => $this->input->post('value'),
                'type' => $this->input->post('type'),
                'min_order' => $this->input->post('min_order'),
                'currency_id' => $this->input->post('type') == 0 ? null : $this->input->post('currency_id'),
                'one_time_use' => !empty($this->input->post('one_time_use')) ? 1 : 0,
                'valid_until' => !empty($this->input->post('valid_until')) ? $this->input->post('valid_until') : null
            ];

            $id = $this->input->post('id');
            if(!empty($id)){
                $args['id'] = $id;
                $res = $this->codes->update($args);
                $message = "Zaktualizowano";
            } else {
                $res = $this->codes->insert($args);
                $message = "Dodano kod";
            }
            
            
            if(!$res){
                ajax_res(array('0','Nie udało się dodać kodu, jakiś błąd'));
            } else {
                setAlert('success',$message);
                ajax_res(array('1','Dodano kod.','refresh'));
            }
            
        } else {
            ajax_res(array('0', validation_errors()));
        }
    }
    
    public function delete($id){
        $this->codes->id = $id;
        $res = $this->codes->delete();
        if($res){
            setAlert('warning','Usunięto kod');
        } else {
            setAlert('error','Nie udało się usunąć kodu');
        }
        redirect('duocms/codes');
    }
    
    public function ajax_get_code($id){
        $code = $this->codes->findByPk($id);
        echo json_encode($code);
        die();
    }
   
}