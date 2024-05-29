<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Orders extends Backend_Controller {

    public $languages = array();
    public $order_object;
    public $delivery_object;
    public  $attr_obj;
    public $code_obj;

    public function __construct() {
        parent::__construct();
        $langs = get_languages();
        foreach ($langs as $l) {
            $this->languages[] = $l->short;
        }
        $this->load->vars(['activePage' => 'orders']);
        $this->load->model('ProductModel');
        $this->load->model('OrderModel');
        $this->load->model('Delivery_Model');
        $this->load->model('CustomElementModel');
        $this->load->model("ProductAttributesModel");
        $this->load->model("CodesModel");
        $this->load->model("CurrencyModel");
        $this->order_object = new OrderModel();
        $this->delivery_object = new Delivery_Model();
        $this->attr_obj = new ProductAttributesModel();
        $this->code_obj = new CodesModel();
    }

    public function index($page =0) {
        
        $statuses_info = $this->order_object->get_all_statuses();
        $delivery_info = $this->delivery_object->get_list_for_dropdown();
        $currency_info = $this->CurrencyModel->get_all_currencies();
        $currencies = array();
        foreach ($currency_info as $curr) {
            $currencies[$curr->id] = $curr->code;
        }
        $data = $this->input->post();
        $status = null; $delivery = null;
        if(!empty($data) && !empty($data['filtruj'])){
            $status = ($data['statusid'] > -50) ? $data['statusid'] : null;
            $this->session->set_userdata('admin_order_status_filter', $status);
            $delivery = ($data['deliveryid'] > -50) ? $data['deliveryid'] : null;
            $this->session->set_userdata('admin_order_delivery_filter', $delivery);
        }
        else{
            $data['statusid'] = $this->session->userdata('admin_order_status_filter');
            $status = $data['statusid'];
            $data['deliveryid'] = $this->session->userdata('admin_order_delivery_filter');
            $delivery = $data['deliveryid'];
        }
        if(!empty($data) && !empty($data['bliskapaczka'])){
            $arr = $data['order'];
            $array = array ();
            foreach ($arr as $key => $value) {
                $array[] = $key;
            }
//            $this->load->model('BliskapaczkaModel');
//            $res = $this->BliskapaczkaModel->get_csv($array);
//            $filename = 'bliskapaczka'.date("Ymd").'.csv';
//            header('Content-Type: application/csv');
//            header('Content-Disposition: attachment; filename='.$filename);
//            header('Pragma: no-cache');
//            echo $res;
            $this->session->set_userdata('bliskapaczka', json_encode($array));
            redirect('duocms/orders/bliskapaczka');
            die();
        }
        if (!empty($data) && !empty($data['inpost'])) {
            $arr = $data['order'];
            $array = array();
            foreach ($arr as $key => $value) {
                $array[] = $key;
            }
            $this->load->model('InpostModel');
            $res = $this->InpostModel->get_csv($array);
            $filename = 'inpost' . date("Ymd") . '.csv';
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Pragma: no-cache');
            echo $res;
            die();
        }
        if (!empty($data) && !empty($data['minipaczka'])) {
            $arr = $data['order'];
            $array = array();
            foreach ($arr as $key => $value) {
                $array[] = $key;
            }
            $this->session->set_userdata('minipaczka', json_encode($array));
            redirect('duocms/orders/minipaczka');
            die();
        }
        if (!empty($data) && !empty($data['print'])) {
            if(!empty($data['order'])){
            $arr = $data['order'];
            }
            $array = array();
            if (!empty($arr)) {
                foreach ($arr as $key => $value) {
                    $order_obj = new OrderModel();
                    $order = $order_obj->get_order($key);
                    $array[] = $order;
                }
            }
            $this->load->view('duocms/Orders/print_ready', array(
                'orders' => $array
            ));
        } else {
        $this->load->library('pagination');
        $limit = 20;
        $total_rows = $this->order_object->get_orders2_row_count($status, $delivery);
        $config = [
            'base_url' => site_url('duocms/orders'),
            'total_rows' => $total_rows,
            'per_page' => $limit,
            'use_page_numbers' => true
        ];

        $this->pagination->initialize($config);
        
        $delivery_inpost = [];
        $delivery_raw = $this->delivery_object->findAll();
        foreach ($delivery_raw as $row) {
            $delivery_inpost[$row->id] = strpos($row->special_name, 'inpost') !== FALSE;
        }

            $orders = $this->order_object->get_orders2($status, $delivery, $limit, $page);
        $this->layout('duocms/Orders/index', [
            'orders' => $orders,
            'statuses_info' => $statuses_info,
            'delivery_info' => $delivery_info,
            'selected_filters' => $data,
            'currencies' => $currencies,
            'inpost_delivery' => $delivery_inpost
        ]);
        }
    }
    
    public function allegro(){
        $this->load->model('AllegroModel');
        $this->AllegroModel->download_orders();
        redirect(site_url('duocms/orders'));
        die();
    }
    
    public function bliskapaczka(){
        $array = json_decode($this->session->userdata('bliskapaczka'));
        $this->session->set_userdata('bliskapaczka', null);
        $orders1 = array();
        $this->load->model('Delivery_Model');
        if (!empty($array)) {
            $order = null;
            foreach ($array as $order_id) {
                $order = new OrderModel();
                $order = $order->get_order($order_id);
                $order->special_name = $this->delivery_object->get_delivery($order->delivery)['special_name'];
                array_push($orders1, $order);
            }
        }
        $data = $this->input->post();
        if (!empty($data)) {
            $order_obj = new OrderModel();
            $data_array = array();
            foreach ($data['inpost_locker'] as $order_id => $nrlistu) {
                $order_obj->update_delivery_point($order_id, $nrlistu);
                $data_array[] = $order_id;
            }
            $this->load->model('BliskapaczkaModel');
            $res = $this->BliskapaczkaModel->get_csv($data_array);
            $filename = 'bliskapaczka'.date("Ymd").'.csv';
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename='.$filename);
            header('Pragma: no-cache');
            echo $res;
            die();
        }
        $this->layout('duocms/Orders/bliskapaczka', [
            'orders' => $orders1
        ]);
    }
    
    public function minipaczka(){
        $array = json_decode($this->session->userdata('minipaczka'));
        $this->session->set_userdata('minipaczka', null);
        $orders1 = array();
        $this->load->model('Delivery_Model');
        if (!empty($array)) {
            $order = null;
            foreach ($array as $order_id) {
                $order = new OrderModel();
                $order = $order->get_order($order_id);
                $del = $this->Delivery_Model->get_delivery($order->delivery);
                if ($del['special_name'] === 'minipaczka') {
                    array_push($orders1, $order);
                }
            }
        }
        $data = $this->input->post();
        if(!empty($data)){
            $order_obj = new OrderModel();
            $data_array = array();
            foreach ($data['inpost_locker'] as $order_id => $nrlistu) {
                if(strlen($nrlistu) > 5) {
                $order_obj->update_delivery_point($order_id, $nrlistu);
                $data_array[] = $order_id;
                }
            }
            $this->load->model('EnadawcaModel');
            $res = $this->EnadawcaModel->get_xml($data_array);
            $filename = date("ymd") .'_' .date('His'). '_FH0Elzbiet.xml';
        header('Content-Type: text/xml');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        echo $res;
        die();
            
            
        }
        
        $this->layout('duocms/Orders/minipaczka', [
            'orders' => $orders1
        ]);
    }

//    public function index_pp(){
//        $this->load->model("EnadawcaModel");
//        $orders = $this->order_object->get_orders_by_delivery_category(1);
//        $this->layout('duocms/Orders/index_pp', [
//            'orders' => $orders
//        ]);
//    }
//    
//    public function pp_clear(){
//        $this->load->model("EnadawcaModel");
//        $this->EnadawcaModel->clear();
//        redirect(site_url('duocms/orders/index_pp'));
//    }
//    
//    public function pp_send(){
//        $this->load->model("EnadawcaModel");
//        $this->EnadawcaModel->send();
//        redirect(site_url('duocms/orders/index_pp'));
//    }
    
//    public function nalepka($type, $order_id){
//        switch ($type){
//            case 'pp':
//                $this->nalepka_pp($order_id);
//                break;
//        }
//    }
    
//    public function nalepka_pp($order_id){
//        $this->load->model("EnadawcaModel");
//        $this->EnadawcaModel->getNalepka($order_id);
//    }
    
    public function show($order_id){
        if(isset($_POST['new_status'])){
            $new_status = $this->input->post('new_status');
            $mail = $this->input->post('mail');
            $note = $this->input->post('note');
            $this->order_object->change_status($order_id, $new_status, $mail, 0, $note);
            redirect(site_url('duocms/orders/show/'.$order_id));
        }
        
        $basket = $this->order_object->get_basket($order_id);
        $order = $this->order_object->get_order($order_id);
        $products = $this->get_products($basket, $this->order_object->discount_id, $order->discount, $order->currency_id);
        $story = $this->order_object->get_story($order_id);
        $delivery = $this->delivery_object->get_delivery($this->order_object->delivery);
        $shipment_id = $this->order_object->get_shipment($order_id);
        $currency = $this->CurrencyModel->get_currency($order->currency_id);
        $this->layout('duocms/Orders/show', [
            'order' => $order,
            'products' => $products,
            'basket' => $basket,
            'story' => $story,
            'delivery' => $delivery,
            'shipment_id' => $shipment_id,
            'currency' => $currency
        ]);
    }
    public function edit($order_id){
        
        $data= $this->input->post();
        if(!empty($data)){
            $this->order_object->update_client_data($order_id, $data);
        }
        $order = $this->order_object->get_order($order_id);
        $this->layout('duocms/Orders/edit', [
            'order' => $order
        ]);
    }
    public function delete_order($order_id){
        $basket = $this->order_object->get_basket($order_id);
         $this->order_object->change_status($order_id, '-1', 0, 0, '');
         $res = $this->order_object->delete_order($order_id);
         if ($res) {
            $this->setOkay('Zamówienie zostało usunięte.');
        } else {
            $this->setError('Wystąpił błąd.');
        }
        redirect('duocms/orders');
    }
    
    public function get_products($basket, $discount_id = null, $discount = 0, $currency_id = null){
        $products = array();
        foreach ($basket as $product_id => $quantity) {
            $data = explode("_", $product_id);
            $p_id = $data[0];
            $op_id = $data[1];
            $item_id = !empty($data[2]) ? $data[2] : '';
            $pr = new ProductModel($p_id);
            
            //badanie zmian atrybutów
            $attr_array = $quantity[1];
            $attributs = array();
            if(!empty($attr_array)){
                foreach($attr_array as $attr_id){
                    $attributs[] = $this->attr_obj->attribute_get_by_id($attr_id);
                }
            }
            //koniec badania atrybutów
            
            $photos = $pr->findAllPhotos();
            $option = $pr->select_option($op_id);
//            $c_price = $pr->calculate_price($p_id, $op_id, $quantity[1]);
            $c_price = (empty($currency_id)) ? $pr->calculate_price($p_id, $op_id, $quantity[1], $quantity[2]) : $pr->calculate_price2($p_id, $op_id, $quantity[1], $currency_id, $quantity[2]);
            if($discount > 0){
                $c_price = round(($c_price - ($c_price * ($discount / 100))) * 100) / 100;
            }
            $products[] = array(
                'product_id' => $product_id,
                'product_data' => $pr,
                'product' => $pr->getTranslation(LANG),
                'price' => $c_price,
                'quantity' => $quantity[0],
                'option' => $option,
                'photos' => $photos,
                'item_id' => $item_id,
                'additional' => $quantity[2]
            );
        }
        $products = $this->code_obj->recalculate_products($products, $discount_id);
        return $products;
    }
    
    public function clients(){
        $this->load->vars(['activePage' => 'clients']);
        $clients = $this->order_object->get_unique_client_list();
        $this->layout('duocms/Orders/clients', [
            'clients' => $clients
        ]);
    }
     public function inpost_download($order_id){
        $order = (new OrderModel())->get_order($order_id);
        $this->load->model('InpostModel');
        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="inpost.pdf"');
        echo $this->InpostModel->get_label($order->package_id);
        die();
    }
    
    public function inpost_generate($order_id){
        $order = (new OrderModel())->get_order($order_id);
        $delivery = $this->delivery_object->get_delivery($order->delivery);
        $this->load->model('InpostModel');
        
        $receiver = [
            'first_name' => $order->first_name,
            'last_name' => $order->last_name,
            'company_name' => '',
            'email' => $order->email,
            'phone' => $order->phone,
            'address' => [
                'street' => $order->street,
                'building_number' => $order->building_number,
                'city' => $order->city,
                'post_code' => $order->zip_code,
                'country_code' => 'PL'
            ],
        ];
        
        $parcels = [
             [
            "id" => "pack1",
            "dimensions" =>  [
                "length"=> "80",
                "width"=> "360",
                "height"=> "640",
                "unit"=> "mm"
            ],
            "weight"=> [
                "amount"=> "25",
                "unit" => "kg"
            ],
             "is_non_standard" =>  false
        ]
        ]; 
        if(strpos($delivery['special_name'], '-pobranie') != false){
            $cod = [
                'amount' => $order->price,
                'currency' => 'PLN'
            ];
        }
        $inpostData = [
            'parcels' => $parcels,
            'receiver' => $receiver,
            'cod' => isset($cod) ? $cod : null,
            'reference' => 'Order_'. $order->id,
            'service' => $delivery['special_name'],
        ];
        
        
        $response_json = $this->InpostModel->add_shipment($inpostData);
        $response = json_decode($response_json);
        if(!empty($response->id)){
            $this->db->where('id', $order->id);
            $this->db->update('duo_orders',
                    [
                        'package_id' => $response->id
                    ]);  
            
            
                    $this->config->load('email');
                    $smtp_email = $this->config->item('smtp_user');
                    $from_mail = !empty($smtp_email) ?  $smtp_email :  get_option('email_from');
                    $this->email->from($from_mail, get_option('email_from_name'));
                    $this->email->to($order->email);
                    $this->email->subject('Numer listu przewozowego Twojego zamówienia');

                    $data = [
                       'package_id' => $response->id,
                    ];
                    $message = $this->load->view('templates/inpost_confirmation', $data, true);
                    $this->email->message($message);

                    $this->email->send();
                    
        redirect('duocms/orders');
        die();
        } else {
            echo 'blad:'.PHP_EOL.json_encode($response);
        } 
        
        
    }
}
