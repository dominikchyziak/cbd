<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class InpostModel extends MY_Model {
    public $token;
    public $organization_id;
    public $inpost_link;
    public $statuses = array();
    
    function __construct() {
        parent::__construct();
        $this->organization_id = get_option('inpost_id');
        $this->inpost_link = get_option('inpost_link');
        $this->token = get_option('inpost_token');
                
    }
    
    function get_statuses(){
        $json = $this->inpost_query('GET', 'statuses', null);
        $obj = json_decode($json);
        if(!empty($obj->items)){
            foreach($obj->items as $item){
                $this->statuses[$item->name] = $item->title;
            }
        }
        return $this->statuses;
    }
    
    function get_points($type = 'parcel_locker',$page = 1){
        return $this->inpost_query('GET', 'points?per_page=1000&type='.$type.'&page='.$page.'&status=Operating', null);
    }
    
    function add_shipment($array){
        $data_string = json_encode($array);
        return $this->inpost_query('POST', 'organizations/'.$this->organization_id.'/shipments', $data_string);
    }
    
    function get_shipments($page = 1){
        return  $this->inpost_query('GET', 'organizations/'.$this->organization_id.'/shipments?page='.$page, null);
    }
    
    function get_shipment($id){
        return $this->inpost_query('GET',  'shipments/'.$id, null);
    }
    
    function delete_shipment($id){
        $res = $this->inpost_query('DELETE', 'shipments/'.$id, null);
        $obj = json_decode($res);
        if($obj == null){
            $this->db->delete('duo_shop_inpost_rel', array('shipment_id' => $id));
            return true;
        } else {
            return false;
        }
    }
    
    function get_labels($array){
        $data_string = json_encode($array);
        return $this->inpost_query('POST', 'organizations/'.$this->organization_id.'/shipments/labels', $data_string);
    }
    
    public function get_label($package_id){
        return $this->inpost_query('GET', 'shipments/'. $package_id . '/label', '');
    }
    
    function inpost_query($type, $point, $data_string){
        $ch = curl_init();
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer ". $this->token
        );
        if($type == 'POST'){
            $headers[] = "Content-Length: " . strlen($data_string);
        }    
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type); 
        curl_setopt($ch, CURLOPT_URL, $this->inpost_link.'/v1/'.$point);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        if($type == 'POST'){ 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
       
        $response_body = curl_exec($ch);
        return $response_body;
    }
    
    function inpost_service_types(){
        $arr = array(
            "inpost_locker_standard" => "Przesyłka paczkomatowa standardowa",
            "inpost_locker_standard-pobranie" => "Przesyłka paczkomatowa pobraniowa",
            "inpost_courier_standard" => "Przesyłka kurierska standardowa",
            "inpost_courier_standard-pobranie" => "Przesyłka kurierska pobraniowa"
            
        );
        return $arr;
    }
    
        public function get_csv($array){
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
                
                if($delivery['category_id'] != 2){
                    continue;
                }
                
                $data = array();
                $data['e-mail'] = $order->email;
                $phone = str_replace(' ', '', $order->phone);
                $phone = str_replace('+48', '', $phone);
                $data['telefon'] = $phone;
                $amount = 0;
                foreach ($order->items as $item) {
                    $amount += $item[0];
                }
                if ($amount < 3) { 
                    $data['rozmiar'] = "A";
                } else {
                    $data['rozmiar']= "C";
                }
                $data['paczkomat'] = $order->inpost_locker;
                $data['numer_referencyjny'] = "Zamowienie nr.".$order->id;
                $data['ubezpieczenie'] = '';
                if($order->method == 'upon_receipt'){
                $data['za_pobraniem'] = $order->price;
                } else {
                    $data['za_pobraniem'] = '';
                }
                $data['imie_i_nazwisko'] = $order->first_name.' '. $order->last_name;
                $data['nazwa_firmy'] = '';
                $flat_number = (!empty($order->flat_number)) ? 'm'.$order->flat_number : '';
                $data['ulica'] = $order->street.' '.$order->building_number.$flat_number;
                $data['kod_pocztowy'] = $order->zip_code;
                $data['miejscowosc'] = $order->city;
                if($delivery['special_name'] == 'inpost_locker_standard' || $delivery['special_name'] == 'inpost_locker_standard-pobranie'){
                $data['typ_przesylki'] = 'paczkomaty';
                } else {
                    $data['typ_przesylki'] = 'kurier';
                }
//                $data2 = array();
//                foreach($data as $key=>$value){
//                    $key2 = mb_convert_encoding($key, "ISO-8859-2");
//                    $value2 = mb_convert_encoding($value, "ISO-8859-2");
//                    $data2[$key2]= $value2;
//                }
                if($i == 1){
                    fputcsv($df, array_keys($data),';');
                }
                $i++;
                fputcsv($df, $data,';');
            }
            
            rewind($df);
            $output = stream_get_contents($df);
            fclose($df);
            return $output;
        }
        return null;
    }
}

