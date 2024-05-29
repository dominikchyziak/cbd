<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include 'application/libraries/sendit/SendIt_pl.php';
class SendItModel extends MY_Model{

    public $sndit;
    private $apikey;
    private $login;
    private $password;
    
    
    function __construct() {
        parent::__construct();
  
    $options['trace'] = 1;
    
    $this->sndit = new SendIt_pl('application/libraries/sendit/snditSandbox.xml', $options);
    $this->load->model('OrderModel');
    
    $this->apikey = get_option('admin_module_sendit_apikey');
    $this->login = get_option('admin_module_sendit_login');
    $passwordInput = get_option('admin_module_sendit_pass');
    if (function_exists('hash') && in_array('sha256', hash_algos())) {
            $passwordOutput = hash('sha256', $passwordInput, true);
        } else if (function_exists('mhash') && is_int(MHASH_SHA256)) {
            $passwordOutput = mhash(MHASH_SHA256, $passwordInput);
        } else {
            throw new \Exception('Undefined function.');
        }
    $this->password = base64_encode($passwordOutput);
    }
    
    public function login(){
        $res = $this->sndit->SPUserLogin($this->apikey, $this->login, $this->password, 'pl');
        $hash = $res[1];
        return $hash;
    }
    
    
    public function test($hash){
        $paczki = new package();
        
        $paczki->width = 30;
        $paczki->height = 20;
        $paczki->depth = 4;
        $paczki->weight = 1;
        $paczki->packsType = 'RECTANGULAR_BOX';
        
        $packages[] = $paczki;
        
        $orderdata = new orderData();
        $orderdata->senderCountryCode = 'PL';
        $orderdata->senderEmail = 'jakub.o@septemonlin.com';
        $orderdata->senderName = 'Jakub O';
        $orderdata->senderCity = 'Grzybowa 37';
        $orderdata->senderCity = 'Biała Podlaska';
        $orderdata->senderPhoneNumber = '668492079';
        $orderdata->senderZipCode = '21-500';
        $orderdata->senderContactPerson = "Jakub O";
        $orderdata->receiverCountryCode = 'PL';
        $orderdata->receiverEmail = 'jakub_o@tlen.pl';
        $orderdata->receiverName = 'Kuba O';
        $orderdata->receiverStreet = 'Warszawska 1';
        $orderdata->receiverCity = 'Biała Podlaska';
        $orderdata->receiverPhoneNumber = '668654654';
        $orderdata->receiverZipCode = '21-500';
        $orderdata->receiverContactPerson = 'Kuba O';
        $orderdata->packages = $packages;
        // not working: $res = $this->sndit->SPPackagesValidate($this->apikey, $hash, $packages, 'PL', 'pl');
        // working: $res = $this->sndit->SPServicesCheck($this->apikey, $hash, '21-500', 'PL', '00-999', 'PL', 0, 'PL');
        // working: $res = $this->sndit->SPOrderRate($this->apikey, $hash, $orderdata, null, 'pl');
        //$res = $this->sndit->SPOrderConfirm($this->apikey, $hash, $orderdata, 'dpd', 'pl');
        return $res;
    }
}