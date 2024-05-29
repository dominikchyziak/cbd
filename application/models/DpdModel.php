<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include 'application/libraries/dpd/dpd.class.php';
class DpdModel extends MY_Model {
function __construct() {
        parent::__construct();
  

    $this->load->model('OrderModel');
    }
    
    public function test(){
        $dpd = new DPD();

         $data = array();
         $data["dpdfid"] = 211437;
         $data["receiver_company"] = "";
         $data["receiver_name"] = "Jan Kowlaski";
         $data["receiver_address"] = "ul. Zielonogórska 12";
         $data["receiver_city"] = "Gorzów Wlkp.";
         $data["receiver_postalcode"] = "34234";
         $data["receiver_phone"] = "678 234 456";
         $data["receiver_email"] = "eqeqeq@wp.pl";
         $data["services_cod"] = ""; //"158.23";

         $data["parcel"] = array();

         for ($i=0;$i<=2;$i++) {

         $data["parcel"][$i]["content"] = "Content_".$i;
         $data["parcel"][$i]["customerdata1"] = $i;

         }

         //echo "IN:<pre>";
         //print_r($data);
         //echo "</pre>";

         $result = $dpd->pkgNumsGeneration($data);

         echo "OUT:<pre>";
         print_r($result);
         echo "</pre>"; 

         $result = $dpd->pkgLabelsGeneration($result);
         echo "LABELS:<pre>";
         print_r($result);
         echo "</pre>"; 
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
                
//                if($delivery['category_id'] != 4){
//                    continue;
//                }
                
                $data = array();
                $data['nazwa_odbiorcy'] = $order->first_name.' '.$order->last_name; 
                 $flat = !empty($order->flat_number) ? '/'.$order->flat_number : "";
                $data['adres_odbiorcy'] = $order->street.' '.$order->building_number.$flat;;
                $data['kod_pocztowy_odbiorcy'] = $order->zip_code;
                $data['miasto_odbiorcy'] = $order->city;
                $phone = str_replace(' ', '', $order->phone);
                $phone = str_replace('+48', '', $phone);
                $data['telefon_odbiorcy'] = $phone;
                $data['email_odbiorcy'] = $order->email;
                //   --------------
                
                if($order->method == 'upon_receipt'){
                    $data['kwota_za_pobraniem'] = $order->price;    //22.
                } 
//                else {
//                    $data['kwota_za_pobraniem'] = "";    //22.
//                }
                $data2 = array();
//                foreach($data as $key=>$value){
//                    $key2 = mb_convert_encoding($key, "ISO-8859-2");
//                    $value2 = mb_convert_encoding($value, "ISO-8859-2");
//                    $data2[$key2]= $value2;
//                }
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
