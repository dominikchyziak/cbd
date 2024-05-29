<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency extends Frontend_Controller
{
    
	public function index($currency_id)
	{
            $this->load->model('CurrencyModel');
            $currency = $this->CurrencyModel->get_currency($currency_id);
            if(empty($currency->id)){
                setAlert("error", (new CustomElementModel('16'))->getField('blad zmiany waluty')->value);
            } else {
                $this->load->library('session');
                if(!empty($this->session->userdata('discount'))){
                    setAlert("error", (new CustomElementModel('16'))->getField('blad zmiany waluty przez rabat')->value);
                } else {
                $this->session->set_userdata('user_currency', $currency->id);
                setAlert("success", (new CustomElementModel('16'))->getField('pomyslna zmiana waluty')->value);
                }
            }
            if(!empty($_SERVER['HTTP_REFERER'])){
            $url = $_SERVER['HTTP_REFERER'];
            var_dump($url);
            $host = parse_url($url, PHP_URL_HOST);
            if($host == parse_url(site_url(), PHP_URL_HOST)){
                redirect($url);
            } else{
                redirect(site_url('/'));
            } 
            }else {
                redirect(site_url('/'));
            }
	}
        
}
