<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kontakt extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
        $home = site_url('/');
        $this->breadcrumbs[] = "<a href=$home>" . (new CustomElementModel('10'))->getField('Strona glowna') . "</a>";
    }

    public function index() {
        $this->load->model('PageModel');
        $page = new PageModel(4);
        $tpage = $page->getTranslation(LANG);
        if(!empty($tpage->meta_title)){
            $this->set_whole_title($tpage->meta_title);
        } else {
        
        $this->set_title((new CustomElementModel('10'))->getField('Kontakt'));
        }
        if(!empty($tpage->meta_description)){
            $this->set_desc($tpage->meta_description);
        }else {
            $this->set_desc((new CustomElementModel('10'))->getField('Kontakt opis'));
        }
        
        //$this->breadcrumbs[] = "<a href=" . site_url('/kontakt') . ">" . (new CustomElementModel('10'))->getField('Kontakt') . "</a>";
        $error_message = '';
        $succes = FALSE;
        $this->lang->load('contact_form');

        if ($this->input->post('send')) {
            try {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('name', 'Imię i nazwisko', 'required');
                $this->form_validation->set_rules('email', 'Adres e-mail', 'required|valid_email');
                $this->form_validation->set_rules('message', 'Twoje zapytanie', 'required');
                $this->form_validation->set_rules('g-recaptcha-response', 'reCaptcha', 'callback__validate_kapcza');

                $this->form_validation->set_message('_validate_kapcza', lang('cotact_form_error_captcha'));

                if ($this->form_validation->run()) {
                    $this->config->load('email');
                    $smtp_email = $this->config->item('smtp_user');
                    $from_mail = !empty($smtp_email) ?  $smtp_email :  get_option('email_from');
                    $this->email->from($from_mail, get_option('email_from_name'));
                    $this->email->to(get_option('email_from'));
                    $this->email->reply_to($this->input->post('email'));
                    $this->email->subject('Wiadomość z formularza kontaktowego.');

                    $data = [
                        'name' => $this->input->post('name', true),
                        'phone' => $this->input->post('phone', true),
                        'email' => $this->input->post('email', true),
                        'message' => $this->input->post('message', true),
                    ];
                    $message = $this->load->view('templates/formularz-kontaktowy', $data, true);
                    $this->email->message($message);

                    $res = $this->email->send();

                    if (!$res) {
                        throw new Exception(lang('contact_form_error_server_not_responding'));
                    }
                    $succes = TRUE;
                    throw new Exception(lang('contact_form_message_sent'));
                    } else {
                    throw new Exception(validation_errors());
                    }
                    } catch (Exception $e) {
                    $error_message = $e->getMessage();
                    }
        }

        $this->load->helper('form');

        


        $this->layout('Kontakt/index', array(
            'error_message' => $error_message,
            'page' => $page,
            'succes' => $succes
        ));
    }

    public function _validate_kapcza($str) {
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = get_option('recaptcha_secret_key');
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = $google_url . "?secret=" . $secret . "&response=" . $str . "&remoteip=" . $ip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  //important for localhost!!!
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);
        //reCaptcha success check
        if ($res['success']) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
