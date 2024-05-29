<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cron extends MY_Controller {
    
    public $langs;
    public $newsletter;
    
    function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('NewsletterModel');
        $this->load->model('ConfigurationModel');
        $this->newsletter = new NewsletterModel();
        $this->configurationModel = new ConfigurationModel();
        $langs = get_languages();
        foreach ($langs as $l) {
            $this->languages[] = $l->short;
        }
    }
    
    public function index(){
        $limit = get_option('email_limit');
        $email_sended = $this->newsletter->send_emails($limit);
        echo '<p>Wysłano maile w ilości '.$email_sended.'</p>';
        echo 'Prace Crona';
    }
    
    public function index2() {
		die();
        $this->load->model("BilansModel");
        $this->config->load('email');
        $smtp_email = $this->config->item('smtp_user');
        $from_mail = !empty($smtp_email) ? $smtp_email : get_option('email_from');
        $this->email->from($from_mail, get_option('email_from_name'));
        $this->email->to('jakub.o@septemonline.com');
        $this->email->subject('Cron Eurosan');

        $this->email->message('prace crona');

        $this->email->send();
        
        $this->BilansModel->categories();
        $this->BilansModel->update_products();
        $this->BilansModel->descriptions();
        $this->BilansModel->attributes_matching();
        $this->BilansModel->photos();
        $this->BilansModel->download_photos();
//                $this->config->load('email');
//        $smtp_email = $this->config->item('smtp_user');
//        $from_mail = !empty($smtp_email) ? $smtp_email : get_option('email_from');
//        $this->email->from($from_mail, get_option('email_from_name'));
//        $this->email->to('jakub.o@septemonline.com');
//        $this->email->subject('Cron Eurosan');
//
//        $this->email->message('prace crona - przed update statusów');

//        $this->email->send();
        $this->BilansModel->update_status();
                $this->config->load('email');
        $smtp_email = $this->config->item('smtp_user');
        $from_mail = !empty($smtp_email) ? $smtp_email : get_option('email_from');
        $this->email->from($from_mail, get_option('email_from_name'));
        $this->email->to('jakub.o@septemonline.com');
        $this->email->subject('Cron Eurosan');

        $this->email->message('skonczone prace crona');

        $this->email->send();
        echo 'test';
    }
    public function index3(){
        $this->load->model("BilansModel");
//                $this->BilansModel->categories();
//        $this->BilansModel->update_products();
//        $this->BilansModel->descriptions();
//        $this->BilansModel->attributes_matching();
//        $this->BilansModel->photos();
//        $this->BilansModel->download_photos();
        $this->BilansModel->update_status3();
    }
}