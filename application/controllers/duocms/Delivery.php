<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Delivery extends Backend_Controller {
    
    public $languages = array();
    public $menu_item = ['delivery', ''];
    public $delivery_obj;
    public $order_obj;
    public $inpost;

    public function __construct() {
        parent::__construct();
        $this->load->model("Delivery_Model");
        $this->load->model("InpostModel");
        $this->load->model('OrderModel');
        $langs = get_languages();
        foreach ($langs as $l) {
            $this->languages[] = $l->short;
        }
        $this->load->vars(['activePage' => 'products']);

        $this->delivery_obj = new Delivery_Model();
        $this->inpost = new InpostModel();
        $this->order_obj = new OrderModel();
    }

    public function index() {
        $data["delivery"] = $this->Delivery_Model->get_list();
        $this->load->model("CurrencyModel");
        $data['currencies'] = $this->CurrencyModel->get_acitve_currencies();
        $this->layout('duocms/Shop/Delivery/index', $data);
    }

    public function create() {
        if(!empty($_POST)){
            $data = $this->input->post();
            $id = $this->delivery_obj->add_delivery($data);
            if(!empty($id)){
                $args = array();
                foreach ($this->languages as $lang){
                    $translation = $data[$lang];
                    $args[$lang] = $translation;
                }
                $this->delivery_obj->update_delivery($id, $data, $args);
                if(!empty($id)){
                    $this->setOkay('Opcja dostawy została dodana poprawnie.');
                    redirect('duocms/delivery/edit/' . $id);
                } else {
                    $this->setError('Nie udało się dodać opcji dostawy.');
                    redirect('duocms/delivery/create');
                }
            }
        }
        
        $this->load->model("CurrencyModel");
        $currencies = $this->CurrencyModel->get_acitve_currencies();
        
        $this->menu_item[1] = 'delivery_create';
        $this->layout('duocms/Shop/Delivery/form', [
            'currencies' => $currencies
        ]);
    }

    public function edit($id) {
        if(!empty($_POST)){
            $data = $this->input->post();
            if(!empty($id)){
                $args = array();
                foreach ($this->languages as $lang){
                    $translation = $data[$lang];
                    $args[$lang] = $translation;
                }
                $this->delivery_obj->update_delivery($id, $data, $args);
                if(!empty($id)){
                    $this->setOkay('Opcja dostawy została dodana poprawnie.');
                    redirect('duocms/delivery/edit/' . $id);
                } else {
                    $this->setError('Nie udało się dodać opcji dostawy.');
                    redirect('duocms/delivery/create');
                }
            }
        }
        
        $delivery = $this->delivery_obj->get_delivery($id);
        $this->load->model("CurrencyModel");
        $currencies = $this->CurrencyModel->get_acitve_currencies();
        $prices = $this->delivery_obj->get_prices($id);
        $this->layout('duocms/Shop/Delivery/form', [
            'delivery' => $delivery,
            'currencies' => $currencies,
            'prices' => $prices
        ]);
    }

    public function delete($id) {
        if (!empty($id)) {
            $this->delivery_obj->delete_delivery($id);
            $this->setError('Usunięto opcję dostawy.');
            redirect('duocms/delivery/index');
        }
    }
    
    public function inpost_list($page = 1){
        $shipments = json_decode($this->inpost->get_shipments($page));
        $statuses = $this->inpost->get_statuses();
        $this->layout('duocms/Shop/Delivery/inpost_list', array(
            'shipments' => $shipments,
            'statuses' => $statuses
        ));
    }
    
    public function inpost_show($id){
        $shipment = $this->inpost->get_shipment($id);
        $statuses = $this->inpost->get_statuses();
        $this->layout('duocms/Shop/Delivery/inpost_show', array(
            'shipment' => json_decode($shipment),
            'statuses' => $statuses
        ));
    }
    
    public function inpost_ship_form($order_id = null){
        if($order_id > 0){
            $order = $this->order_obj->get_order($order_id);
        } else {
            $order = null;
        }
        $q = $this->db->get_where('duo_shop_delivery', array('id' => $order->delivery));
        $delivery = $q->row();
        $service = $delivery->special_name;
        $cod = 0;
        if(strpos($service,'-') !== false){
            $strs = explode('-', $service);
            $service = $strs[0];
            $cod = $order->price;
        }
        $parcels_tmp = $this->order_obj->recalculate_shipment($order_id);
        $parcels = array();
        
        $i = 0;
        for($i =0; $i<count($parcels_tmp) ; $i++){
            $parcels[] = array(
                    'id' => $order->id.'_'.$i.'_zam_shop',
                    'template' => 'large',
                    'dimensions' => array(
                        'length' => 400,
                        'width' => 600,
                        'height' => 370,
                        'unit' => 'mm'
                    ),
                    'weight' => array(
                        'amount' => $parcels_tmp[$i]['total_weight'],
                        'unit' => 'kg'
                    )
                );
        }
        $data = array(
            'receiver' => array(
                'first_name' => !empty($order->first_name) ? $order->first_name : '',
                'last_name' => !empty($order->last_name) ? $order->last_name : '',
                'phone' => !empty($order->phone) ? $order->phone : '',
                'email' => !empty($order->email) ? $order->email : '',
                'address' => array(
                    'street' => !empty($order->street) ? $order->street : '',
                    'building_number' => !empty($order->building_number) ? $order->building_number : '',
                    'city' => !empty($order->city) ? $order->city : '',
                    'post_code' => !empty($order->zip_code) ? $order->zip_code : '',
                )
            ),
            'parcels' => $parcels,
            "custom_attributes" => array(
                'target_point' => $order->inpost_locker
            ),
            'insurance' => array(
                'amount' => $cod,
                'currency' => "PLN"
            ),
            'cod' => array(
                'amount' => $cod,
                'currency' => "PLN"
            ),
            "service" => $service
        );
        
        $this->load->view('duocms/Shop/Delivery/inpost_ship_form', array(
            'data' => $data,
            'order_id' => $order_id,
            'parcels_data' => $parcels_tmp
        ));
    }
    
    public function add_inpost_ship($order_id = 0){
        $data = $this->input->post();
        $this->session->set_userdata('ship_form',$data);
       // echo json_encode($data);
        $res = $this->inpost->add_shipment($data);
       // echo $res;
       // die();
        $obj = json_decode($res);
        if(!empty($obj->id)){
            setAlert('success','Dodano przesyłkę');
            $this->order_obj->add_shipment($order_id, $obj->id);
        } else {
            setAlert('warning','Błąd: '.$obj->message);
        }
        //echo $res;
        redirect(site_url('duocms/orders/show/'.$order_id));
        die();
    }
    
    public function delete_inpost($id){
        $res = $this->inpost->delete_shipment($id);
        if($res){
            setAlert('info', 'Anulowano przesyłkę');
        } else {
            setAlert('warning','Nie udało się anulować przesyłki');
        }
        redirect(site_url('duocms/Delivery/inpost_list'));
    }
    
    public function test(){
        $this->load->model("OrderModel");
        $order_obj = new OrderModel();
        $order_obj->recalculate_shipment(73);
    }
    
    public function ajax_get_pp_types_html(){
        $this->load->model("EnadawcaModel");
        $paczki = $this->EnadawcaModel->getPaczkiType();
        $str = '';
        foreach ($paczki as $key=>$p) {
            $str .= "<option value='".$key."'>".$p."</option>";
        }
        echo json_encode($str);
    }
    
    public function ajax_get_inpost_types_html(){
        $paczki = $this->inpost->inpost_service_types();
        $str = '';
        foreach ($paczki as $key=>$p) {
            $str .= "<option value='".$key."'>".$p."</option>";
        }
        echo json_encode($str);
    }
    
//    public function inpost_nalepka($order_id = 0){
//        $array = array(
//            "type" => 'normal',
//            "shipment_ids" => ['101106'],
//            "format" => 'Pdf'
//                );
//        $res = $this->inpost->get_labels($array);
//        var_dump($res);
//    }
        public function ajax_get_bliskapaczka_types_html(){
        $this->load->model("BliskapaczkaModel");
        $paczki = $this->BliskapaczkaModel->service_types();
        $str = '';
        foreach ($paczki as $key=>$p) {
            $str .= "<option value='".$key."'>".$p."</option>";
        }
        echo json_encode($str);
    }
}
