<?php

class Allegro extends Backend_Controller {
    
    public $allegro;


    public function __construct() {
        parent::__construct();
        $this->load->model('AllegroModel');
        $this->load->model('OfferCategoryModel');
        $this->load->model('ProductModel');
        $this->allegro = new AllegroModel();
        $this->load->vars(['activePage' => 'allegro']);
    }
    
    public function index(){
        $res = $this->allegro->test();
        echo 'test >>';
        echo json_encode($res);
        die();
    }
    
    public function product($product_id){
        $data = array();
        $data['product_id'] = $product_id;
        $data['auctions'] = $this->allegro->get_auctions($product_id);
        $this->layout('duocms/Shop/Allegro/product', $data);
    }
    
    public function show($offer_id){
        $allegro_session = null;
        $offer_record = $this->allegro->get_auction_record_by_allegro_id($offer_id);
        $data = array();
//        if(!empty($this->session->userdata['allegro'])){
//            $allegro_session = $this->session->userdata['allegro'];
//            if($allegro_session['expired'] < date('U')){
//                $this->session->set_userdata('allegro',null);
//                $data['allegro_session'] = $this->session->userdata['allegro'];
//            } else {
//                $data['allegro_session'] = $allegro_session;
//            }
//        }
        if(get_option('admin_modules_allegro_token_expiration') > date('U')){
            $data['allegro_session'] = array('session' => 1);
        } else {
            $data['allegro_session'] = $allegro_session;
        }
        $offer = $this->allegro->get_offer($offer_id);
        $data['offer'] = json_decode($offer);
        $data['allegro_category_id'] = !empty($data['offer']->category->id) ? $data['offer']->category->id : '';
        $afields = array();
        if(!empty($this->session->userdata['allegro'])){
            $fields_obj = json_decode($this->allegro->get_category_fields($data['offer']->category->id));
            $afields = $fields_obj->parameters;
        }
        $data['fields'] = $afields;
        $data['uploaded_photos'] = $this->allegro->get_uploaded_photos($offer_record->product_id);
        $data['allegro_login_link'] = $this->allegro->get_login_url($offer_id,1); 
        $data['shipping_rates'] = $this->allegro->get_shipping_rates();
        $data['impliedWarranty'] = $this->allegro->get_impliedWarranty();
        $data['returnPolicy'] = $this->allegro->get_returnPolicy();
         $post_data = $this->input->post();
        if(!empty($post_data)){
            $res = $this->allegro->add_offer($post_data, $offer_record->product_id);
            if(!empty($res->id)){
                setAlert('info','Zaktualizowano aukkcję !! Aktywacja może chwilę potrwać');
                redirect(site_url('duocms/allegro/show/'.$offer_id));
            }
            echo json_encode($res);
            die();
        }
        $this->layout('duocms/Shop/Allegro/show', $data);
    }
    
    public function add_auction($product_id){
        $productCategoryObj = new OfferCategoryModel();
        $allegro_id = $productCategoryObj->get_allegro_id_by_product($product_id);
        $allegro_session = null;
        $data = array();
//        if(!empty($this->session->userdata['allegro'])){
//            $allegro_session = $this->session->userdata['allegro'];
//            if($allegro_session['expired'] < date('U')){
//                $this->session->set_userdata('allegro',null);
//                $data['allegro_session'] = $this->session->userdata['allegro'];
//            } else {
//                $data['allegro_session'] = $allegro_session;
//            }
//        }
        if(get_option('admin_modules_allegro_token_expiration') > date('U')){
            $data['allegro_session'] = array('session' => 1);
        } else {
            $data['allegro_session'] = $allegro_session;
        }
        $afields = array();
        if(!empty($this->session->userdata['allegro'])){
            $fields_obj = json_decode($this->allegro->get_category_fields($allegro_id));
            $afields = $fields_obj->parameters;
        }
        $data['fields'] = $afields;
        
        $data['product_id'] = $product_id;
        
        $product = new ProductModel($product_id);
        $data['product'] = $product;
        $data['product_translation'] = $product->getTranslation('pl');
        $data['allegro_category_id'] = $allegro_id;
        $data['error'] = '';
        $data['success'] = '';
        $post_data = $this->input->post();
        if(!empty($post_data)){
            
            $res = $this->allegro->add_offer($post_data, $product_id);
            if(!empty($res->id)){
                setAlert('success','Wystawiono aukkcję !!');
                redirect(site_url('duocms/allegro/show/'.$res->id));
            }
            echo json_encode($res);
            die();
        }
        $data['allegro_login_link'] = $this->allegro->get_login_url($product_id); 
        $data['product_photos'] = $product->findAllPhotos();
        if(!empty($data['product_photos'] && !empty($data['allegro_session']))){
            $tmp_photos = array();
            foreach ($data['product_photos'] as $photo){
                $res = $this->allegro->upload_photo($photo);
            }
        }
        $data['uploaded_photos'] = $this->allegro->get_uploaded_photos($product_id);
        $data['shipping_rates'] = $this->allegro->get_shipping_rates();
        $data['impliedWarranty'] = $this->allegro->get_impliedWarranty();
        $data['returnPolicy'] = $this->allegro->get_returnPolicy();
        $this->layout('duocms/Shop/Allegro/add_auction', $data);
    }
    
    public function allegro_list($page = 0){
        $allegro_session = null;
          if(get_option('admin_modules_allegro_token_expiration') > date('U')){
            $data['allegro_session'] = array('session' => 1);
        } else {
            $data['allegro_session'] = $allegro_session;
        }
        if($this->input->server('REQUEST_METHOD') === 'POST' && !empty($this->input->post("aukcja"))) {
            $aukcje = $this->input->post("aukcja");
            foreach ($aukcje as $aukcja=>$value) {
                $this->allegro->add_from_allegro_no_redirect($aukcja); 
            }
        }
        $data['allegro_login_link'] = $this->allegro->get_login_url(0,0,1); 
        $data['offers'] = $this->allegro->get_offers_list_limit(100, $page);
        $data['page'] = $page;
        $this->layout('duocms/Shop/Allegro/allegro_list', $data);
    }
    
    public function add_from_allegro_no_redirect($allegro_id){
        $this->allegro->add_from_allegro_no_redirect($allegro_id);
    }
    
    public function add_from_allegro($allegro_id){
        $this->add_from_allegro_no_redirect($allegro_id);
        redirect('duocms/allegro/allegro_list');
        die();
    }
    
//    private function add_attributes($product, $auction){
//        $allegro_attributes_json = $this->allegro->get_category_fields($auction->category->id);
//        $allegro_attributes= json_decode($allegro_attributes_json);
//        $this->load->model('ProductAttributesModel');
//        $units = array();
//        foreach($allegro_attributes->parameters as $param_group){
//            if(!$this->ProductAttributesModel->check_if_allegro_group_exist($param_group->id)){
//            $attribute_group = array(
//                'translations' => array(
//                    array(
//                    'lang' => 'pl',
//                    'name' => $param_group->name,
//                    'description' => ''
//                        )
//                ),
//                'allegro' => $param_group->id
//            );
//            $this->ProductAttributesModel->add_group($attribute_group);
//            }
//            $group_id = $this->ProductAttributesModel->find_group_by_allegro_group_id($param_group->id);
//            switch ($param_group->type) {
//                case 'dictionary':
//                    foreach ($param_group->dictionary as $param) {
//                        if(!$this->ProductAttributesModel->check_if_allegro_attr_exist($param->id)){
//                            $duo_attr_id = $this->ProductAttributesModel->attribute_add(0, $group_id , $param->id);
//                            $args = array('pl' => array('name'=> $param->value, 'description'=>''));
//                            $this->ProductAttributesModel->attribute_update($duo_attr_id, 0, $args, $group_id);
//                        }
//                    }
//                    break;
//                case 'float':
//                case 'integer':
//                    $units[$param_group->id] = array ('name' => $param_group->unit );
//                    break;
//                default:
//                    break;
//            }            
//        }
//        foreach ($auction->parameters as $parameter) {
//            if(!empty($parameter->valuesIds)){
//                $attr_id = $this->ProductAttributesModel->find_attr_by_allegro_id($parameter->valuesIds[0]);
//                if(!empty($attr_id)){
//                    $product->attribute_add_to_product($attr_id, $product->id ,null);
//                }
//            }
//            if(!empty($parameter->values)){
//                $attr_val = $parameter->values[0];
//                $attr_group_id = $parameter->id;
//                $group_id = $this->ProductAttributesModel->find_group_by_allegro_group_id($attr_group_id);
//                $unit_name = '';
//                if(!empty($units[$attr_group_id])){
//                $unit_name = $units[$attr_group_id]['name'];}
//                $duo_attr_name = $attr_val.' '.$unit_name;
//                $duo_attr_allegro_id = $attr_group_id.'_'.$attr_val;
//                if(!$this->ProductAttributesModel->check_if_allegro_attr_exist($duo_attr_allegro_id)){
//                    $duo_attr_id = $this->ProductAttributesModel->attribute_add(0, $group_id , $duo_attr_allegro_id);
//                    $args = array('pl' => array('name'=> $duo_attr_name, 'description'=>''));
//                    $this->ProductAttributesModel->attribute_update($duo_attr_id, 0, $args, $group_id);
//                }
//                $attr_id = $this->ProductAttributesModel->find_attr_by_allegro_id($duo_attr_allegro_id);
//                if(!empty($attr_id)){
//                    $product->attribute_add_to_product($attr_id, $product->id ,null);
//                }
//            }
//            $attr_id = null;
//        }
//    }
    
//    public function synchronise_amounts(){
//        $allegro_auctions_json = $this->allegro->get_offers_list();
//        $allegro_auctions = json_decode($allegro_auctions_json);
//        $duocms_auctions = $this->allegro->get_duo_auctions_list();
//        $auctions = array();
//        foreach ($allegro_auctions->items->promoted as $auction){
//            if(in_array($auction->id, $duocms_auctions)){
//                $auctions[] = $auction;
//            }
//        }
//        foreach ($allegro_auctions->items->regular as $auction){
//            if(in_array($auction->id, $duocms_auctions)){
//                $auctions[] = $auction;
//            }
//        }
//        foreach($auctions as $auction){
//             $this->AllegroModel->synchronise_amount($auction);
//        }
//    }
    
    public function allegro_multilist($page = 0){
        $list_json = $this->allegro->get_multivariants_auction_list(15,$page);
        $count = json_decode($list_json)->count;
        $list = json_decode($list_json)->offerVariants;
        
        $allegro_session = null;
        if(get_option('admin_modules_allegro_token_expiration') > date('U')){
            $data['allegro_session'] = array('session' => 1);
        } else {
            $data['allegro_session'] = $allegro_session;
        }
//        if($this->input->server('REQUEST_METHOD') === 'POST' && !empty($this->input->post("aukcja"))) {
//            $aukcje = $this->input->post("aukcja");
//            foreach ($aukcje as $aukcja=>$value) {
//                $this->add_from_allegro_no_redirect($aukcja); 
//            }
//        }
        $data['allegro_login_link'] = $this->allegro->get_login_url(0,0,1); 
        $data['offers'] = $list;
        $data['page'] = $page;
        $this->layout('duocms/Shop/Allegro/allegro_multilist', $data);
    }
    
    
    public function download_packs(){
        $this->load->model('ProductModel');
        $this->load->model('ProductPackModel');
        $this->load->model('ProductAttributesModel');
        $list_json = $this->allegro->get_multivariants_auction_list(50,0);
        $count = json_decode($list_json)->count;
        $pages = ceil($count / 50);
        for($k=0; $k<=$pages-1; $k++){
        $list_json = $this->allegro->get_multivariants_auction_list(50,$k);
        $list = json_decode($list_json)->offerVariants;
        foreach ($list as $l) {
            if(!$this->ProductPackModel->check_if_pack_exist($l->id)){
            $this->ProductPackModel->add_pack($l->name, $l->id);
            }
            $pack = $this->ProductPackModel->get_pack_by_allegro_id($l->id);
            $pack_args['id'] = $pack->id;
            $pack_args['name'] = $pack->name;
            $pack_args['attr_grp_2_id'] = 0;
            $product_list_json = $this->allegro->get_multivariants_auction($l->id);
            $product_list = json_decode($product_list_json);
            $i = 1;
            foreach($product_list->parameters as $params){
                if($params->id == 'color/pattern'){
                    $atr_group_name = 'colorPattern_' . $pack->id;
                    if(!$this->ProductAttributesModel->check_if_allegro_group_exist($atr_group_name)){
                    $attribute_group = array(
                        'translations' => array(
                            array(
                            'lang' => 'pl',
                            'name' => $atr_group_name,
                            'description' => ''
                                )
                        ),
                        'allegro' => $atr_group_name
                    );
                    $this->ProductAttributesModel->add_group($attribute_group);
                    }
                    $group_id = $this->ProductAttributesModel->find_group_by_allegro_group_id($atr_group_name);
                    $pack_args['attr_grp_'.$i.'_id'] = $group_id; 
                } else {
                    $g_id = $this->ProductAttributesModel->find_group_by_allegro_group_id($params->id);
                    $pack_args['attr_grp_'.$i.'_id'] = $g_id;
                }
                $i++;
            }
            $this->ProductPackModel->update_pack($pack_args);
            $products = $product_list->offers;
            
            foreach ($products as $p){
                if(!$this->allegro->check_allegro_product($p->id)){
                    $this->add_from_allegro_no_redirect($p->id);
                }
                $pr = $this->allegro->get_product_by_allegro_auction_id($p->id);
                if(!$this->ProductPackModel->check_product_is_in_pack($pr->id, $pack->id)){
                    $this->ProductPackModel->add_product_to_pack($pr->id, $pack->id);
                }
                if(!empty($p->colorPattern)){
                    $pattern_id = $group_id.'_'.$p->colorPattern;
                    if(!$this->ProductAttributesModel->check_if_allegro_attr_exist($pattern_id)){
                            $duo_attr_id = $this->ProductAttributesModel->attribute_add(0, $group_id , $pattern_id);
                            $args = array('pl' => array('name'=> $p->colorPattern, 'description'=>''));
                            $this->ProductAttributesModel->attribute_update($duo_attr_id, 0, $args, $group_id);
                        }
                    $attr_id = $this->ProductAttributesModel->find_attr_by_allegro_id($pattern_id);
                    if(!empty($attr_id)){
                        $this->ProductModel->attribute_add_to_product($attr_id, $pr->id ,null);
                    }       
                }
            }
        }
        }
        redirect(site_url('duocms/ProductPacks/index'));
//        $data['test1']= $products;
//        $data['test2']= null;
//        $this->layout('duocms/Shop/Allegro/test', $data);
    }
    
        public function delivery_options(){
        $allegro_session = null;
//         if(!empty($this->session->userdata['allegro'])){
//            $allegro_session = $this->session->userdata['allegro'];
//            if($allegro_session['expired'] < date('U')){
//                $this->session->set_userdata('allegro',null);
//                $data['allegro_session'] = $this->session->userdata['allegro'];
//            } else {
//                $data['allegro_session'] = $allegro_session;
//            }
//        }
        if(get_option('admin_modules_allegro_token_expiration') > date('U')){
            $allegro_session = array('session' => 1);
            $data['allegro_session'] = $allegro_session;
        } else {
            $data['allegro_session'] = $allegro_session;
        }
        if(!empty($this->input->post('id'))){
            $this->allegro->clear_all_deliveries();
            $delivery_ids = $this->input->post('delivery');
            $allegro_ids = $this->input->post('id');
            for($i=0;$i<count($allegro_ids);$i++){
                $this->allegro->insert_allegro_delivery($allegro_ids[$i], $delivery_ids[$i]);
            }
        }
        if (!empty($allegro_session)) {
            $dm_allegro_json = $this->allegro->get_delivery_methods();
            $dm_allegro = json_decode($dm_allegro_json)->deliveryMethods;
            $dma = array();
            foreach ($dm_allegro as $dm) {
                $dma[$dm->id] = $dm->name;
            }
            $ships = json_decode($this->AllegroModel->get_shipping_rates())->shippingRates;
            $dost = array();
            foreach ($ships as $s) {
                $dost[] = json_decode($this->AllegroModel->get_shipping_rates_details($s->id));
            }

            $this->load->model("Delivery_Model");
            $cms_delivery = $this->Delivery_Model->get_list_for_dropdown();
        } else {
            $cms_delivery = array();
            $dma = array();
            $dost = array();
        }

        $data['selected_data'] = $this->allegro->get_all_deliveries();
        $data['allegro_login_link'] = $this->allegro->get_login_url(0,0,1); 
        $data['cms_delivery'] = $cms_delivery;
        $data['dma'] = $dma;
        $data['dost'] = $dost;
        $this->layout('duocms/Shop/Allegro/allegro_delivery', $data);
    }

    public function allegro_orders(){
        $this->allegro->download_orders();
//        $regex = "/([\w\s.ąćęłńóśźżŁÓŃĆŻŹĄĘŚ]+)\s{1}([0-9]+[A-Za-z]?){1}\s?[\/mM]?[.]?\s?([0-9]+)?/";
//        $this->load->model("OrderModel");
//        $orders = json_decode($this->allegro->get_allegro_orders());
//        $count = $orders->count;
//        $tcount = $orders->totalCount;
//        foreach ($orders->checkoutForms as $order) {
//            if(!$this->OrderModel->check_if_order_in_db($order->id)){
//            $data = array();
//            $data['allegro_id'] = $order->id;
//            $data['email'] = $order->buyer->email;
//            $data['first_name'] = $order->delivery->address->firstName;
//            $data['last_name'] = $order->delivery->address->lastName;
//            $data['city'] = $order->delivery->address->city;
//            $data['zip_code'] = $order->delivery->address->zipCode;
//            $street = $order->delivery->address->street;
//            $street_table = array();
//            preg_match($regex, $street, $street_table);
//            if(!empty($street_table[2])){
//                $data['street'] = $street_table[1];
//                $data['building_number'] = $street_table[2];
//                $data["flat_number"] = (!empty($street_table[3])) ? $street_table[3] : null;
//            } else {
//                $data['street'] = $street;
//            }
//            $data['delivery'] = $this->allegro->get_delivery_by_allegro_id($order->delivery->method->id)->delivery_id;
//            $data['phone'] = $order->delivery->address->phoneNumber;
//            $data['comment'] = (!empty($order->messageToSeller)) ? 'dodane z allegro, '.$order->messageToSeller :'dodane z allegro';
//            $data['price'] = $order->summary->totalToPay->amount;
//            $data['wieght'] = 1;
//            if($order->payment->type == 'CASH_ON_DELIVERY'){
//                $data['method'] = 'upon_receipt';
//            } else {
//                $data['method'] = 'allegro';
//            }
//            if(!empty($order->delivery->pickupPoint)){
//                $pickup_name = $order->delivery->pickupPoint->name;
//                if(strpos($pickup_name, 'Paczkomat') !== FALSE){
//                    $data['locker'] = trim(explode('Paczkomat', $pickup_name)[1]);
//                } else if(strpos($pickup_name, 'PACZKA w RUCHu:') !== FALSE){
//                    $data['locker'] = trim(explode(':', $pickup_name)[1]);
//                } else {
//                    $data['locker'] = $pickup_name;
//                }
//            }
//            $order_id = $this->OrderModel->add_order_from_allegro($data);
//            foreach($order->lineItems as $item){
//                if(!$this->allegro->check_allegro_product($item->offer->id)){
//                    $this->add_from_allegro_no_redirect($item->offer->id);
//                }
//                $product = $this->allegro->get_product_by_allegro_auction_id($item->offer->id);
//                $this->OrderModel->add_item_to_order($product->id, $item->quantity, $order_id);
//            }
//            
//            }
//        }
//        
    }
    public function end_auction($product_id){
        $allegro_id = $this->allegro->get_allegro_auction_id($product_id);
        if($allegro_id > 0){
            $this->allegro->end_offer($allegro_id);
        } else {
            //TODO TOASTER
        } 
    }
    public function renew_auction($product_id) {
        $allegro_id = $this->allegro->get_allegro_auction_id($product_id);
        if ($allegro_id > 0) {
            $this->allegro->renew_offer($allegro_id);
        } else {
            //TODO TOASTER
        }
    }

    public function edit_auction($product_id){
        $productCategoryObj = new OfferCategoryModel();
        $allegro_id = $productCategoryObj->get_allegro_id_by_product($product_id);
        $allegro_session = null;
        $data = array();
        if(get_option('admin_modules_allegro_token_expiration') > date('U')){
            $data['allegro_session'] = array('session' => 1);
        } else {
            $data['allegro_session'] = $allegro_session;
        }
        
        $afields = array();
        if(!empty($allegro_session)){
            $fields_obj = json_decode($this->allegro->get_category_fields($allegro_id));
            $afields = $fields_obj->parameters;
            $auction_id = $this->allegro->get_allegro_auction_id($product_id);
            if($auction_id > 0){
            $data['auction'] = $this->allegro->get_offer($auction_id);
            } else {
                $data['auction'] = null;
            }
        }
        $data['fields'] = $afields;
        
        $data['product_id'] = $product_id;
        
        $product = new ProductModel($product_id);
        $data['product'] = $product;
        $data['product_translation'] = $product->getTranslation('pl');
        $data['allegro_category_id'] = $allegro_id;
        $data['error'] = '';
        $data['success'] = '';
    
        $data['allegro_login_link'] = $this->allegro->get_login_url($product_id); 
        $all_photos = $product->findAllPhotos();
        $data['product_photos'] = $all_photos; 

        
        $uploaded_photos = $this->allegro->get_uploaded_photos($product_id);
        
        $data['shipping_rates'] = $this->allegro->get_shipping_rates();
        $data['impliedWarranty'] = $this->allegro->get_impliedWarranty();
        $data['returnPolicy'] = $this->allegro->get_returnPolicy();
        
        $data['usedAttributes'] = $this->allegro->get_product_allegro_attributes($product_id);
        $u_photos_id = array();
        if (!empty($uploaded_photos)) {
            foreach ($uploaded_photos as $up) {
                $u_photos_id[] = $up->photo_id;
            }
        }
        $uploaded_photos2 = array();
        foreach($all_photos as $ap){
            if(!in_array($ap->id, $u_photos_id)){
                $this->allegro->upload_photo($ap);
            }
            $uploaded_photos2[] = $this->allegro->find_photo_by_id($ap->id);
        }
        $post_data = $this->input->post();
        if(!empty($post_data)){
            $res = $this->allegro->edit_offer($post_data);
            if(!empty($res->id)){
                setAlert('success','Wyedytowano aukcję!');
                redirect(site_url('duocms/allegro/show/'.$res->id));
            }
            echo json_encode($res);
            die();
        }
        
        $data['auction_data'] = json_decode($this->allegro->get_offer($auction_id));
        $data['uploaded_photos'] = $uploaded_photos2;
        $data['allegro_auction_id'] = !empty($auction_id) ? $auction_id : '';
        $this->layout('duocms/Shop/Allegro/edit_auction', $data);
    }
    
    public function bubacz1337(){
        $res = $this->allegro->get_shipping_rates();
        echo $res;
    }
}

