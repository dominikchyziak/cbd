<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Newsletter extends Backend_Controller {
    
    public $langs;
    public $newsletter;
    public $product;
    
    function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('NewsletterModel');
        $this->load->model('ConfigurationModel');
        $this->load->model('ProductModel');
        $this->product = new ProductModel();
        $this->newsletter = new NewsletterModel();
        $this->configurationModel = new ConfigurationModel();
        $langs = get_languages();
        foreach ($langs as $l) {
            $this->languages[] = $l->short;
        }
        $this->load->vars(['activePage' => 'newsletter']);
    }
    
    public function index(){
        $mailings = $this->newsletter->get_mailings();
        
        //lista produktów promocyjnych
        $products = $this->product->findAllPromoProducts();
        $this->layout('duocms/Newsletter/mails', array(
            'mailings' => $mailings,
            'products' => $products
        ));
    }
    
    public function emails(){
        //lista adresów mailingowych
        $emails = $this->newsletter->get_addresses();
        $this->layout('duocms/Newsletter/emails_list', array(
            'emails' => $emails
        ));
    }
    public function delete_mailing($mailing_id){
        if($this->newsletter->delete_mailing($mailing_id)){
            setAlert('info','Usunięto mailing.');
        } else {
            setAlert('warning','Nie udało się udunąć mailingu');
        }
        redirect(site_url('duocms/Newsletter/index'));
    }
    
    public function config(){
        if(!empty($this->input->post())){
            foreach($_POST as $key=>$value){
                $this->configurationModel->updateOption($key, $value);
            }
            setAlert('success','Zapisano');
        }
        $all_options = $this->configurationModel->getAllOptions('email_conf');
        $this->layout('duocms/Newsletter/config', array(
            'all_options' => $all_options
        ));
    }
    
    public function ajax_unsub(){
        $id = $this->input->post('email_id');
        $email = $this->newsletter->get_email_by_id($id);
        $this->newsletter->changeEmailStatus($email->email);
        ajax_res(array('1','Usunięto subskrypcję'));
    }
    
    public function ajax_sub(){
        $id = $this->input->post('email_id');
        $email = $this->newsletter->get_email_by_id($id);
        $this->newsletter->changeEmailStatus($email->email);
        ajax_res(array('1','Przywrócono subskrypcję'));
    }
    
    public function ajax_add(){
        $this->form_validation->set_rules('subject','Temat','required');
        $this->form_validation->set_rules('content','Treść','required');
        if($this->form_validation->run()){
            $subject = $this->input->post('subject');
            $content = $this->input->post('content');
            $this->newsletter->add_mailing($subject, $content);
            setAlert('success','Dodano!');
            ajax_res(array('1','','refresh'));
        } else {
            ajax_res(array('0', validation_errors()));
        }
        
    }
    
    public function ajax_add_special(){
        $this->form_validation->set_rules('subject','Temat','required');
        $this->form_validation->set_rules('products[]','Produkty','required');
        if($this->form_validation->run()){
            $subject = $this->input->post('subject');
            $products = $this->input->post('products');
            $content = (new CustomElementModel('17'))->getField('mail promocyjny naglowek')->value;
            $content .= '<div style="width:100%; float:left; max-width: 1100px;">';
            if(!empty($products)){
                foreach($products as $prod){
                    $pr = new ProductModel($prod);
                    $trans = $pr->getTranslation(LANG);
                    $content .= $this->load->view('duocms/Newsletter/product_view',array(
                        'product' => $pr,
                        'translation' => $trans
                    ), TRUE);
                }
            }
            $content .= '</div>';
            $content .= (new CustomElementModel('17'))->getField('mail promocyjny stopka')->value;
            $this->newsletter->add_mailing($subject, $content);
            setAlert('success','Dodano!');
            ajax_res(array('1','','refresh'));
        } else {
            ajax_res(array('0', validation_errors()));
        }
        
    }
    
    public function ajax_edit(){
        $this->form_validation->set_rules('subject','Temat','required');
        $this->form_validation->set_rules('content','Treść','required');
        $this->form_validation->set_rules('mail_id','Id maila','required');
        if($this->form_validation->run()){
            $subject = $this->input->post('subject');
            $content = $this->input->post('content');
            $id = $this->input->post('mail_id');
            $this->newsletter->edit_mailing($id,$subject, $content);
            setAlert('info','Zaktualizowano!');
            ajax_res(array('1','','refresh'));
        } else {
            ajax_res(array('0', validation_errors()));
        }
    }
    
    public function ajax_get_mailing($id){
        $mailing = $this->newsletter->get_mailing($id);
        echo ajax_res(array(
            'id' => $mailing->id,
            'title' => $mailing->subject,
            'content' => $mailing->content
        ));
    }
    
    public function ajax_test_mail(){
        $mailing_id = $this->input->post('test_mail_id');
        $email_address = $this->input->post('email');
        $mailing_obj = $this->newsletter->get_mailing($mailing_id);
        $res = $this->newsletter->send_mailing($email_address, $mailing_obj);
        if($res === null){
            setAlert('success','Wysłano mailing testowy.');
            ajax_res(array('success','Wysłano mailing testowy','refresh'));
        } else {
            ajax_res(array('error', $res));
        }
    }
    
    public function ajax_send_mailing($id){
        $res = $this->newsletter->add_to_send($id);
        if($res){
            setAlert('success','Dodano do kolejki');
            ajax_res('1','Dodano do kolejki');
        } else {
            ajax_res('0','Nie udało się dodać do klejki');
        }
    }
    
    public function add_email(){
        
    }
    
    public function unsub($id){
        if(!empty($id)){
            $this->db->where('id', $id);
            $this->db->delete('duo_newsletter_emails');
        }
        
        redirect(site_url('duocms/Newsletter/emails'));
    }
}