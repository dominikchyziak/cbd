<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Payments extends REST_Controller {
    private $order_object;
            function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('OrderModel');
        $this->order_object = new OrderModel();
    }

    public function przelewy24_post(){
        $order_id = explode('_',$this->post('p24_session_id'));
        $order_id = $order_id[0];
        $order_price = $this->post('p24_amount')/100;
        $order = $this->order_object->get_order($order_id);
        if($order_price == $order->price){
            $this->order_object->change_status($order_id, 5, TRUE, TRUE);
        }
        
        $session_id = $this->post('p24_session_id');
        $oder_id = $this->post('p24_order_id');
        $amount = $this->post('p24_amount');
        $currency = $this->post('p24_currency');
        $p24_crc = get_option('p24_crc');
        $p24_id = get_option('p24_id');
        $sign = $session_id . '|' . $oder_id . '|' . $amount . '|' . $currency . '|' . $p24_crc;
        $url = get_option('p24_link2');
        $data = array(
            'p24_merchant_id' => $p24_id,
            'p24_pos_id' => $p24_id,
            'p24_session_id' => $session_id,
            'p24_amount' => $amount,
            'p24_currency' => $currency,
            'p24_order_id' => $oder_id,
            'p24_sign' => md5($sign)
        );
// use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) { /* Handle error */
        }
        
        $this->set_response( array("result" => "true"), REST_Controller::HTTP_CREATED);
    }

    public function payu_post(){
        $this->load->library('Openpayu');
        require_once realpath(dirname(__FILE__)) . '/../../libraries/OpenPayU/config.php';
        $body = file_get_contents('php://input');
        $data = trim($body);

        $response = OpenPayU_Order::consumeNotification($data);
        $order_payu = $response->getResponse()->order; //NEW PENDING CANCELED REJECTED COMPLETED WAITING_FOR_CONFIRMATION
        //&& $_SERVER['REMOTE_ADDR'] == "5.134.208.98"
        if($order_payu->status === "COMPLETED" ){
            $order_id = explode('_', $order_payu->extOrderId);
            $order_id = $order_id[0];
            $order_price = $order_payu->totalAmount/100;
            $order = $this->order_object->get_order($order_id);
            if($order_price == $order->price){
                $this->order_object->change_status($order_id, 5, TRUE, TRUE);
            }
        
        }
        header("HTTP/1.1 200 OK");
        die(); 
        $this->set_response( array("result" => "true"), REST_Controller::HTTP_CREATED);
    }
    
    public function paypal_post(){
        $post = $this->post();
        $req = 'cmd=_notify-validate';
        foreach ($post as $key => $value) {
            $value = urlencode(stripslashes($value));
            $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
            $req .= "&$key=$value";
        }
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        
        $order_id = $this->post('custom');
        $order = $this->order_object->get_order($order_id);

        $fp = fsockopen(get_option('paypal_url_verify'), 443, $errno, $errstr, 30);
        if (!$fp) {
        // HTTP ERROR
        } else {
            // Validate payment (Check unique txnid & correct price)
                    $valid_price = $post['mc_gross'] == $order->price;
                    // PAYMENT VALIDATED & VERIFIED!
                    if ( $valid_price && $post['payment_status'] == 'Pending') {
                        $this->order_object->change_status($order_id, 5, TRUE, TRUE);
                    } else {

                    }
            fputs($fp, $header . $req);
        fclose($fp);
        }

        header("HTTP/1.1 200 OK");
        die();
    }
}