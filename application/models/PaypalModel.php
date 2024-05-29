<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PaypalModel extends MY_Model {
    
    private $link = 'https://api.sandbox.paypal.com/v1/';
    private $client_id = 'AV_9ICcA5vKatzJKYZWbQF1E5ashw3eIAhJTNuDtRXs-pwu-IjAhvQOMc-0G-4Txh-JtRuAJBImovHmc';
    private $client_secret = 'EGLbGDhXpX6SsZAPS65MTQdRvx3GGIqrzxQv9FuIJupyYZLIwJ7bJXS14KVIYRdwuHsjBxXsM24qRS6d';
            
    function __construct() {
        parent::__construct();
    }
    
    private function get_token(){
        $headers = array(
            "Accept: application/json",
            "Accept-Language: pl_PL"
        );
        $headers[] = "Content-Length: " . strlen('');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_USERPWD, "$this->client_id:$this->client_secret");
        curl_setopt($ch, CURLOPT_URL, $this->link . 'oauth2/token?grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_POSTFIELDS, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response_body = curl_exec($ch);
        curl_close($ch);
        $token = json_decode($response_body);
        if(!empty($token->access_token)){
            $array_token = array(
                'token' => $token->access_token,
                'expire' => time() + $token->expires_in
            );
            $this->session->set_userdata('paypal_token', $array_token);
        }
        return $token;
    }
    
    public function check_token(){
        $token = null;
        if(!empty($this->session->paypal_token)){
            $token = $this->session->paypal_token;
        }
        if(empty($token) || $token['expire'] < time()){
            $this->get_token();
        }
    }
            
    function paypal_query($type, $point, $data_string = '', $headers = array(), $link = 'link') {
        $this->check_token();
        $ch = curl_init();
        if(empty($headers)){
            $headers = array(
                "accept: application/vnd.allegro.beta.v1+json",
                "Authorization: Bearer {$this->token()}",
                'content-type: "image/png","image/jpg","image/jpeg","image/gif"',
                'accept-language: pl-PL'
            );
        }

        if ($type == 'POST' || $type == 'PUT') {
            $headers[] = "Content-Length: " . strlen($data_string);
        }

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_USERPWD, "AV_9ICcA5vKatzJKYZWbQF1E5ashw3eIAhJTNuDtRXs-pwu-IjAhvQOMc-0G-4Txh-JtRuAJBImovHmc:EGLbGDhXpX6SsZAPS65MTQdRvx3GGIqrzxQv9FuIJupyYZLIwJ7bJXS14KVIYRdwuHsjBxXsM24qRS6d");
        curl_setopt($ch, CURLOPT_URL, $this->$link . $point);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($type == 'POST' || $type == 'PUT') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response_body = curl_exec($ch);
//        $info = curl_getinfo($ch);
//            echo json_encode($info) . '<br>';
    //      echo curl_getinfo($ch, CURLINFO_HTTP_CODE) . '<br>';
        curl_close($ch);
        return $response_body;
    }
}