<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class BliskapaczkaModel extends MY_Model {

    private $link;
    private $token;
    
    function __construct() {
        parent::__construct();
            
        $this->link = get_option('admin_module_bliskapaczka_link');
        $this->token = get_option('admin_module_bliskapaczka_token');
    }
    
    
        function bliskapaczka_query($type, $point, $data_string = '', $link = 'link', $additional_headers = array()) {
        $ch = curl_init();
        if ($link == 'upload_link') {
            $headers = array(
                "accept: application/json",
                "Authorization: Bearer {$this->token}",
                'content-type: "image/png","image/jpg","image/jpeg","image/gif"',
                'accept-language: pl-PL'
            );
        } else {
            $headers = array(
                "content-type: application/json",
                "Accept: application/json",
                "Authorization: Bearer {$this->token}"
            );
        }
        if ($type == 'POST' || $type == 'PUT') {
            $headers[] = "Content-Length: " . strlen($data_string);
        }
        $headers = array_merge($headers, $additional_headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_URL, $this->link . $point);
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
    
    public function get_health(){
        $res = $this->bliskapaczka_query('GET', 'health');
        return $res;
    }
    
    public function test2($order_id){
        $this->load->model("OrderModel");
        $this->load->model("DeliveryModel");
        $order = $this->OrderModel->get_order($order_id);
        
        $amount = 0;
        foreach($order->items as $item){
            $amount += $item[0];
        }
        $gabaryt = null;
        if($amount < 3){
        $gabarytA = new stdClass();
        $gabarytA->width = get_option('shop_gabarytA_szerokosc');
        $gabarytA->length = get_option('shop_gabarytA_dlugosc');
        $gabarytA->height = get_option('shop_gabarytA_wysokosc');
        $gabarytA->weight = get_option('shop_gabarytA_waga');
        $gabaryt = $gabarytA;
        } else {
        $gabarytC = new stdClass();
        $gabarytC->width = get_option('shop_gabarytC_szerokosc');
        $gabarytC->length = get_option('shop_gabarytC_dlugosc');
        $gabarytC->height = get_option('shop_gabarytC_wysokosc');
        $gabarytC->weight = get_option('shop_gabarytC_waga');
        $gabaryt = $gabarytC;
        }
        $parcel = new stdClass();
        $parcel->insuranceValue = null;
        $parcel->dimensions = $gabaryt;
        
        $data = new stdClass();
        $data->senderFirstName = get_option('shop_first_name');
        $data->senderLastName = get_option('shop_last_name');
        $data->senderPhoneNumber = get_option('shop_phone');
        $data->senderEmail = get_option('shop_email');
        $data->senderStreet = get_option('shop_street');
        $data->senderBuildingNumber = get_option('shop_building_number');
        $data->senderFlatNumber = get_option('shop_flat_number');
        $data->senderPostCode = get_option('shop_post_code');
        $data->senderCity = get_option('shop_city');
        $data->countryCode = "PL";
        //   --------------
        $data->receiverFirstName = $order->first_name;
        $data->receiverLastName = $order->last_name;
        $data->receiverPhoneNumber = $order->phone;
        $data->receiverEmail = $order->email;
        $data->receiverStreet = $order->street;
        $data->receiverBuildingNumber = $order->building_number;
        $data->receiverFlatNumber = !empty($order->flat_number) ? $order->flat_number :"";
        $data->receiverPostCode = $order->zip_code;
        $data->receiverCity = $order->city;
        $data->receiverCountryCode = "PL";
        // ------------------
        $data->operatorName = "RUCH";
//        $data->destinationCode= "KRA010";
//        $data->postingCode = "KRA011";
        $data->deliveryType = "P2P";
        $data->chooseDestinationPoint = true;
        $data->parcels[] = $parcel;
        $data1 = json_encode($data);
        
        $res = $this->bliskapaczka_query('POST', 'v2/order/advice', $data1);
        return $res;
    }
    public function test(){
      $data =  (object) array(
    "senderFirstName" => "Sender",
    "senderLastName" => "Sender",
    "senderPhoneNumber" =>"123456789",
    "senderEmail"=>"sender@example.com",
    "senderStreet"=>"Rynek",
    "senderBuildingNumber"=>"1",
    "senderFlatNumber"=>"1",
    "senderPostCode"=>"00-000",
    "senderCity"=>"Wroclaw",
    "countryCode"=>"PL",
    "receiverFirstName"=>"Receiver",
    "receiverLastName"=>"Receiver",
    "receiverPhoneNumber"=>"987654321",
    "receiverEmail"=>"receiver@example.com",
    "receiverStreet"=>"Aleje Jerozolimskie",
    "receiverBuildingNumber"=>"2A",
    "receiverFlatNumber"=>"3",
    "receiverPostCode" => "11-111",
    "receiverCity"=>"Warszawa",
    "receiverCountryCode"=>"PL",
    "operatorName"=>"INPOST",
    "destinationCode"=>"KRA010",
    "postingCode"=>"KRA011",
    "additionalInformation"=> "Some additional information",
    "parcels"=> array ( (object) array(
      "dimensions" => (object) array(
        "height" => 20,
        "length" => 20,
        "width" => 20,
        "weight" => 20
      ),
      "insuranceValue" => 1200.00
    )),
    "codValue"=>110,
    "codPayoutBankAccountNumber"=>"16102019120000910201486273",
    "deliveryType"=>"P2P"
        );
        $data1 = json_encode($data);
        
        $res = $this->bliskapaczka_query('POST', 'v2/order/advice', $data1);
        return $res;
    }
    
    
    public function service_types(){
        $arr = array(
            'paczka_w_ruchu' => "Paczka w RUCHU",
            'paczka_w_ruchu-pobranie' => "Paczka w RUCHu pobranie",
            'odbior_w_pp' => "Odbior w punkcie PP",
            'odbior_w_pp-pobranie' => "Odbior w punkcie PP pobranie"
//            'paczka48' => "Paczka 48",
//            'paczka48-kurier' => "Paczka 48 pobranie"
        );
        return $arr;
    }
    
    public function get_csv($array) {
        $this->load->model("OrderModel");
        $this->load->model("Delivery_Model");
        $res = '';
        if (!empty($array)) {
            //$df = fopen(getcwd() . '/user_files/test.csv', 'w');
            $df = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
            $i = 1;
            foreach ($array as $order_id) {
                $order = $this->OrderModel->get_order($order_id);
                $delivery = $this->Delivery_Model->get_delivery($order->delivery);
                
                if($delivery['category_id'] != 4){
                    continue;
                }
                
                $data = array();
                $data['senderFirstName'] = get_option('shop_first_name'); //1.
                $data['senderLastName'] = get_option('shop_last_name'); // 2.
                $data['senderPhoneNumber'] = get_option('shop_phone'); // 3.
                $data['senderEmail'] = get_option('shop_email'); // 4.
                $data['senderStreet'] = get_option('shop_street'); // 5.
                $data['senderBuildingNumber'] = get_option('shop_building_number'); // 6.
                $data['senderFlatNumber'] = get_option('shop_flat_number'); //7.
                $data['senderPostCode'] = get_option('shop_post_code'); // 8.
                $data['senderCity'] = get_option('shop_city'); // 9.
                //   --------------
                $data['receiverFirstName'] = $order->first_name; //10.
                $data['receiverLastName'] = $order->last_name; //11.
                $data['receiverEmail'] = $order->email; // 12.
                $phone = str_replace(' ', '', $order->phone);
                $phone = str_replace('+48', '', $phone);
                $data['receiverPhoneNumber'] = $phone;  // 13.
//            $data['receiverStreet'] = $order->street;
//            $data['receiverBuildingNumber'] = $order->building_number;
//            $data['receiverFlatNumber'] = !empty($order->flat_number) ? $order->flat_number : "";
//            $data['receiverPostCode'] = $order->zip_code;
//            $data['receiverCity'] = $order->city;
                //$data['receiverCountryCode'] = "PL";
                // ------------------
                switch ($delivery['special_name']) {
                    case 'paczka_w_ruchu':
                        $data['operatorName'] = "RUCH";       //14.
                        $data['postingCode'] = get_option('shop_ruch_posting_code');        //  15.
                        $data['destinationCode'] = $order->inpost_locker; //  16.
                        break;
                    case 'paczka_w_ruchu-pobranie':
                        $data['operatorName'] = "RUCH";       //14.
                        $data['postingCode'] = get_option('shop_ruch_posting_code');        //  15.
                        $data['destinationCode'] = $order->inpost_locker; //  16.
                        break;
                    case 'odbior_w_pp-pobranie':
                        $data['operatorName'] = "POCZTA";       //14.
                        $data['postingCode'] = get_option('shop_poczta_posting_code');        //  15.
                        $data['destinationCode'] = $order->inpost_locker; //  16.
                        break;
                    case 'odbior_w_pp':
                        $data['operatorName'] = "POCZTA";       //14.
                        $data['postingCode'] = get_option('shop_poczta_posting_code');        //  15.
                        $data['destinationCode'] = $order->inpost_locker; //  16.
                        break;
                    default:
                        $data['operatorName'] = "";       //14.
                        $data['postingCode'] = "";        //  15.
                        $data['destinationCode'] = ""; //  16.
                        break;
                }


                $amount = 0;
                foreach ($order->items as $item) {
                    $amount += $item[0];
                }

                if ($amount < 3) {   // 17.-20.
                    $data['length'] = get_option('shop_gabarytA_dlugosc');
                    $data['width'] = get_option('shop_gabarytA_szerokosc');
                    $data['height'] = get_option('shop_gabarytA_wysokosc');
                    $data['weight'] = get_option('shop_gabarytA_waga');
                } else {
                    $data['length'] = get_option('shop_gabarytC_dlugosc');
                    $data['width'] = get_option('shop_gabarytC_szerokosc');
                    $data['height'] = get_option('shop_gabarytC_wysokosc');
                    $data['weight'] = get_option('shop_gabarytC_waga');
                }
                $data['insuranceValue'] = '';  // 21.
                
                if($order->method == 'upon_receipt'){
                $data['codValue'] = $order->price;    //22.
                $data['codPayoutBankAccountNumber'] = get_option('shop_bank_number');  // 23.
                } else {
                    $data['codValue'] = "";    //22.
                $data['codPayoutBankAccountNumber'] = '';  // 23.
                }
                $data2 = array();
                foreach($data as $key=>$value){
                    $key2 = mb_convert_encoding($key, "ISO-8859-2");
                    $value2 = mb_convert_encoding($value, "ISO-8859-2");
                    $data2[$key2]= $value2;
                }
                if($i == 1){
                    fputcsv($df, array_keys($data2),';');
                }
                $i++;
                fputcsv($df, $data2,';');
            }
            
            rewind($df);
            $output = stream_get_contents($df);
            fclose($df);
            return $output;
        }
        return null;
    }

}