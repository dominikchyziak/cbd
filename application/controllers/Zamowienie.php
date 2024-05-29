<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

 


class Zamowienie extends Frontend_Controller {

    private $product_object;
    private $order_object;
    private $delivery_object;
    public  $attr_obj;
    public $code_obj;
    public $inpost;

    public function __construct() {
        parent::__construct();
        $this->load->model('ProductModel');
        $this->load->model("ProductTranslationModel");
        $this->load->model('OrderModel');
        $this->load->model('Delivery_Model');
        $this->load->model("ProductAttributesModel");
        $this->load->model("CodesModel");
        $this->load->model('InpostModel');
        $this->load->library('email');
        $this->load->library('form_validation');
        $this->product_object = new ProductModel();
        $this->order_object = new OrderModel();
        $this->delivery_object = new Delivery_Model();
        $this->attr_obj = new ProductAttributesModel();
        $this->code_obj = new CodesModel();
        $this->inpost = new InpostModel();
        
        $home = site_url('/');
        $this->breadcrumbs[] = "<a href=$home>".(new CustomElementModel('10'))->getField('Strona glowna')."</a>";
    }


    
    // echo '<script type="text/javascript">
    // gtag('event', 'conversion', {
    //   'value': '{sum_noship}',
    //   'currency': 'PLN',
    //   'transaction_id': '{order_id}'
    // });
    // </script>';

    public function index() {



        $this->lang->load('contact_form');
        $this->lang->load('zamowienie');

        $error_message = '';

        if ($this->input->post('send')) {
            try {
                $this->form_validation->set_rules('name', 'Imię i nazwisko', 'required');
                $this->form_validation->set_rules('email', 'Adres e-mail', 'required|valid_email');
                $this->form_validation->set_rules('address', 'Adres', 'required');
                //$this->form_validation->set_rules('message', 'Treść zamówienia', 'required');
                $this->form_validation->set_rules('accept_term2', 'Oświadczenie', 'required');
                //$this->form_validation->set_rules('captcha', 'Kod z obrazka', 'required|callback__validate_kapcza');
                $this->form_validation->set_rules('g-recaptcha-response', 'reCaptcha', 'callback__validate_kapcza');
                $this->form_validation->set_message('_validate_kapcza', lang('cotact_form_error_captcha'));

                if ($this->form_validation->run()) {
                    $this->email->from(EMAIL_FROM, EMAIL_FROM_NAME);
                    $this->email->to(EMAIL_TO);
                    $this->email->reply_to($this->input->post('email'));
                    $this->email->subject('Zamówienie.');

                    if (!empty($_POST["accept_term3"])) {
                        $marketing = "tak";
                    } else {
                        $marketing = "nie";
                    }

                    $data = [
                        'name' => $this->input->post('name', true),
                        'phone' => $this->input->post('phone', true),
                        'email' => $this->input->post('email', true),
                        'address' => $this->input->post('address', true),
                        'message' => $this->input->post('message', true),
                        'product' => $this->input->post("product", true),
                        'delivery' => $this->input->post("delivery", true),
                        'marketing' => $marketing
                    ];
                    $message = $this->load->view('templates/formularz-zamowienia', $data, true);
                    $this->email->message($message);

                    $res = $this->email->send();

                    if (!$res) {
                        throw new Exception(lang('contact_form_error_server_not_responding'));
                    }

                    throw new Exception(lang('contact_form_message_sent'));
                } else {
                    throw new Exception(validation_errors());
                }
            } catch (Exception $e) {
                $error_message = $e->getMessage();
            }
        }

        $this->load->helper('form');

        $this->load->model('PageModel');
        $page = new PageModel(2);

        // Set defaults.
        $this->set_desc($page->getTranslation(LANG)->body);
        $this->set_title($page->getTranslation(LANG)->title);

        
        $all_products = $this->ProductModel->findAll();
        $products = array();
        foreach ($all_products as $p) {
            $product = $this->ProductTranslationModel->findByProductAndLang($p, LANG);
            $products[$product->name] = $product->name;
        }
        $this->load->model("Delivery_Model");
        $delivery = $this->Delivery_Model->get_list();
        $del = array();
        foreach ($delivery as $d) {
            $del[$d->name] = $d->name;
        }

        $this->layout('zamowienie/index', array(
            'error_message' => $error_message,
            'page' => $page,
            'products' => $products,
            'delivery' => $del
        ));
        
    }

    public function _validate_kapcza($str) {
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = get_option('recaptcha_secret_key');
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = $google_url . "?secret=" . $secret . "&response=" . $str . "&remoteip=" . $ip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  //important for localhost!!!
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);
        //reCaptcha success check
        if ($res['success']) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function get_products($basket, $discount_id = null, $currency_id = null){
        $products = array();
        if(!empty($basket)){
        foreach ($basket as $product_id => $quantity) {
            $data = explode("_", $product_id);
            $p_id = $data[0];
            $op_id = isset($data[1]) ? $data[1] : 0;
            $item_id = !empty($data[2]) ? $data[2] : '';
            $pr = new ProductModel($p_id);
            $photos = $pr->findAllPhotos();
            
            //badanie zmian atrybutów
            $attr_array = $quantity[1];
            $attributs = array();
            if(!empty($attr_array)){
                foreach($attr_array as $attr_id){
                    $attributs[] = $this->attr_obj->attribute_get_by_id($attr_id);
                }
            }

            //koniec badania atrybutów
            
            $option = $pr->select_option($op_id);
//            $price = 0;
//            if(empty($currency_id)){
//            $price = $pr->calculate_price($p_id, $op_id, $quantity[1]);
//            } else {
//            $price = $pr->calculate_price2($p_id, $op_id, $quantity[1], $currency_id);
//            }
            $products[] = array(
                'product_id' => $product_id,
                'attributes' => $attributs,
                'product' => $pr->getTranslation(LANG),
                'quantity' => $quantity[0],
                'option' => $option,
                'photos' => $photos,
                'additional' => !empty($quantity[2]) ? $quantity[2] : null,
                'item_id' => $item_id,
                'product_data' => $pr,
                'price' => (empty($currency_id)) ? $pr->calculate_price($p_id, $op_id, $quantity[1], $quantity[2]) : $pr->calculate_price2($p_id, $op_id, $quantity[1], $currency_id, $quantity[2])
            );
        }
        } else {
            $products = array();
        }
        $products = $this->code_obj->recalculate_products($products, $discount_id);
        
        return $products;
    }

    public function basket(){
        $step = filter_input(INPUT_GET, 'step', FILTER_VALIDATE_INT);
        switch ($step) {
            case 2:
                $this->basket_step2();
                break;
            case 3:
                $this->basket_step3();
                break;
            default:
                $this->basket_step1();
                break;
        }
    }
    
    public function basket_step1() {
        $userdata = array();
        $user_ses_data = !empty($this->session->userdata('login')['user']) ? $this->session->userdata('login')['user'] : null;
        if(!empty($user_ses_data)){
            $userdata = $user_ses_data;
        }
        if(!empty($this->input->post('code'))){
            $code = $this->input->post('code');
            $code_res = $this->code_obj->set_code($userdata['id'], $code);
            if($code_res === TRUE){
                setAlert('success',(new CustomElementModel('13'))->getField('kod poprawny')->value);
            } else if($code_res ===0){
                setAlert('error', (new CustomElementModel('13'))->getField('kod niepoprawny')->value);
            } else if($code_res === 2){
                setAlert('warning',(new CustomElementModel('13'))->getField('kod wykorzystany')->value);
            } else if($code_res == 3){
                setAlert('warning',(new CustomElementModel('13'))->getField('kod zlej waluty')->value);
            } else if($code_res == 4){
                 setAlert('warning',(new CustomElementModel('13'))->getField('kod termin waznosci')->value);
            }
        }
        //$this->breadcrumbs[] = (new CustomElementModel('10'))->getField('Twój koszyk');
        
        //$this->change_basket();
        $basket = json_decode(json_encode($this->session->userdata('basket')), true);
        //$basket = json_decode($this->input->cookie('basket', true),true);
        $products = $this->get_products($basket);
        
        
        
        //liczę sumę ceny koszyka i wagi
        $sum_weight = 0;
        $sum_price = 0;
        foreach($products as $p){
            $sum_price += $p['price']*1*$p['quantity'];
            $sum_weight += !empty($p['option']['weight']) ? $p['option']['weight']*$p['quantity'] : (!empty($p['product_data']->weight) ? $p['product_data']->weight*$p['quantity'] : 0);
        }
        $ac = get_active_currency()->id;
        $deliveries = $this->delivery_object->get_deliveries($sum_price, $sum_weight, LANG);
        $free_delivery=0;
        $initial_free_delivery = null;
        if (!empty($deliveries)){
            $min_delivery = $deliveries[0]->prices[$ac]['max_price'];
            $initial_free_delivery = $deliveries[0];
            foreach ($deliveries as $delivery){ 
                if ($min_delivery > $delivery->prices[$ac]['max_price']){
                    $min_delivery = $delivery->prices[$ac]['max_price'];  
                    $initial_free_delivery = $delivery;
                }
            }
            $free_delivery = $min_delivery-$sum_price;
        }
//        echo $sum_weight;
//        die();
        
        

        $inpost_points = json_decode($this->inpost->get_points());
        $this->layout('zamowienie/basket', array(
            'products' => $products,
            'basket' => $basket,
            'userdata' => $userdata,
            'free_delivery' => $free_delivery,
            'deliveries' => $deliveries,
            'sum_price' => $this->code_obj->recalculate_sum($products, null, 1),
            'sum_weight' => $sum_weight,
            'inpost_points' => $inpost_points,
            'initial_free_delivery' => $initial_free_delivery
        ));
    }
    
    public function basket_step2(){
         $userdata = array();
        $user_ses_data = !empty($this->session->userdata('login')['user']) ? $this->session->userdata('login')['user'] : null;
        if(!empty($user_ses_data)){
            $userdata = $user_ses_data;
        }
        //$this->breadcrumbs[] = (new CustomElementModel('10'))->getField('Twój koszyk');
        
        //$this->change_basket();
        $basket = json_decode(json_encode($this->session->userdata('basket')), true);
        //$basket = json_decode($this->input->cookie('basket', true),true);
        $products = $this->get_products($basket);
        
        if(empty($products)){
            redirect(site_url('koszyk'));
        }
        
        
        //liczę sumę ceny koszyka i wagi
        $sum_weight = 0;
        $sum_price = 0;
        foreach($products as $p){
            $sum_price += $p['price']*1*$p['quantity'];
            $sum_weight += !empty($p['option']['weight']) ? $p['option']['weight']*$p['quantity'] : (!empty($p['product_data']->weight) ? $p['product_data']->weight*$p['quantity'] : 0);
        }
        $ac = get_active_currency()->id;
        $deliveries = $this->delivery_object->get_deliveries($sum_price, $sum_weight, LANG);
        $free_delivery=0;
        $initial_free_delivery = null;
        if (!empty($deliveries)){
            $min_delivery = $deliveries[0]->prices[$ac]['max_price'];
            $initial_free_delivery = $deliveries[0];
            foreach ($deliveries as $delivery){ 
                if ($min_delivery > $delivery->prices[$ac]['max_price']){
                    $min_delivery = $delivery->prices[$ac]['max_price'];  
                    $initial_free_delivery = $delivery;
                }
            }
            $free_delivery = $min_delivery-$sum_price;
        }
        if(!empty($_POST['full_name'])){
            $user_id = isset($userdata['id']) ? $userdata['id'] : null;
            $userdata = $this->input->post();
            //$this->form_validation->set_rules('first_name', (new CustomElementModel('11'))->getField('Imie'), 'required');
            $this->form_validation->set_rules('full_name', (new CustomElementModel('11'))->getField('nazwisko'), 'required');   
            $this->form_validation->set_rules('phone', (new CustomElementModel('11'))->getField('Telefon'), 'required'); 
            if(empty($this->input->post('register'))){
                $this->form_validation->set_rules('email',  (new CustomElementModel('11'))->getField('Email'), 'required|valid_email');
            }
            $this->form_validation->set_rules('city', (new CustomElementModel('11'))->getField('Miasto'), 'required');
            $this->form_validation->set_rules('zip_code', (new CustomElementModel('11'))->getField('kod pocztowy'), 'required');
            $this->form_validation->set_rules('street', (new CustomElementModel('11'))->getField('ulica i nr'), 'required');
            $this->form_validation->set_rules('building_number', (new CustomElementModel('11'))->getField('nr'), 'required');
//            $this->form_validation->set_rules('method', (new CustomElementModel('11'))->getField('Wybierz metodę płatności'), 'required');
//            $this->form_validation->set_rules('delivery', (new CustomElementModel('11'))->getField('Wybierz sposob dostawy'), 'required');
//            $this->form_validation->set_rules('accept_term', (new CustomElementModel('11'))->getField('regulamin'), 'required');
//            $this->form_validation->set_rules('g-recaptcha-response', 'reCaptcha', 'callback__validate_kapcza');
//            $this->form_validation->set_message('_validate_kapcza', 'Przejdź test reCaptcha');

            if(!empty($this->input->post('invoice'))){
                $this->form_validation->set_rules('company_name', 'Nazwa firmy', 'required');
                $this->form_validation->set_rules('company_address', 'Adres firmy', 'required');
                $this->form_validation->set_rules('company_nip', 'NIP', 'required');
            }
            if(!empty($this->input->post('other-shipping-address'))){
                $this->form_validation->set_rules('secondary_full_name', (new CustomElementModel('11'))->getField('nazwisko'), 'required'); 
                $this->form_validation->set_rules('secondary_phone', (new CustomElementModel('11'))->getField('Telefon'), 'required'); 
                $this->form_validation->set_rules('secondary_city', (new CustomElementModel('11'))->getField('Miasto'), 'required');
                $this->form_validation->set_rules('secondary_zip_code', (new CustomElementModel('11'))->getField('kod pocztowy'), 'required');
                $this->form_validation->set_rules('secondary_street', (new CustomElementModel('11'))->getField('ulica i nr'), 'required');
                $this->form_validation->set_rules('secondary_building_number', (new CustomElementModel('11'))->getField('nr'), 'required');
            }
            if(!empty($this->input->post('register'))){
                $this->form_validation->set_rules('password', 'Hasło', 'required');
                $this->form_validation->set_rules('password2', 'Powtórz hasło', 'required|matches[password]');
                $this->form_validation->set_rules('email',  (new CustomElementModel('11'))->getField('Email'), 'required|valid_email|is_unique[duo_users.email]');
            }
            if ($this->form_validation->run()) {
                $data = $this->input->post();
                unset($data['g-recaptcha-response']);
                unset($data['accept_term']);
                unset($data['send']);
                unset($data['term1']);
                unset($data['term2']);
                unset($data['invoice']);
                unset($data['other-shipping-address']);
                
                unset($data['login']);
                
                $full_name_exploded = explode(' ',  $data['full_name']);
                $data['first_name'] = $full_name_exploded[0];
                if(count($full_name_exploded) > 1){
                    $data['last_name'] = implode(' ', array_slice($full_name_exploded, 1));
                } else {
                    $data['last_name'] = '';
                }
                unset($data['full_name']);
                
                if(!empty($data['register'])){
                    $this->load->model('User_Model');
                    $usermodel = new User_Model();
                    $email_user = $data['email'];
                    $code = $usermodel->add_user(
                            $email_user, 
                            $data["password"], 
                            $data["first_name"], 
                            $data["last_name"], 
                            $data['phone'], 
                            "","","","", 1, 1,0,0);
                   
                    //wysyłka maila z kodem 
                    $this->config->load('email');
                    $smtp_email = $this->config->item('smtp_user');
                    $from_mail = !empty($smtp_email) ?  $smtp_email :  get_option('email_from');
                    $this->email->from($from_mail, get_option('email_from_name'));
                    $this->email->to($data['email']);
                    $this->email->subject((new CustomElementModel('15'))->getField('Rejestracja mail tytul'));

                    $data_register_mail = [
                        'code' => $code
                    ];
                    $message = $this->load->view('templates/register', $data_register_mail, true);
                    $this->email->message($message);
                    $res2 = $this->email->send();
                    
                }
                unset($data['register']);
                unset($data['password']); unset($data['password2']);
 
                $key = sha1(date('U'));
                $data['key'] = $key;
                $data['currency_id'] = get_active_currency()->id;
                //pobranie kosztu dostawy
//                $delivery_price = $this->delivery_object->get_price2($data['delivery'],$data['currency_id']);
//                $delivery_max_price = $this->delivery_object->get_max_price($data['delivery'],$data['currency_id']);
                //doliczam ewentualny rabat
                if(!empty($this->session->userdata['login']['user']['discount'])){
                    $data['discount'] = $this->session->userdata['login']['user']['discount'];
                }

                //$price = 0;
                //foreach($products as $product){
                //    $price += !empty($product['option']['price_change']) ? $product['quantity'] * ($product['product_data']->price+$product['option']['price_change']) : $product['quantity'] * $product['product_data']->price;
                //}
//                if( $sum_price > $this->delivery_object->get_max_price($data['delivery'],$data['currency_id']) ){
//                    $delivery_price = 0;
//                }
                $total = $this->code_obj->recalculate_sum($products, null, 1)*1;
//                if($total >= $delivery_max_price*1){
//                    $delivery_price = 0;
//                }
                $data['price'] = $total; //  + $delivery_price*1; //$price; 
               // $data['weight'] = $sum_weight;
                
                $this->session->set_userdata('current_order', $data);
                redirect(site_url('koszyk?step=3'));
                
//                $res = $this->order_object->add($data, $basket, 0, $user_id);
//                
//                //czyszczenie zablokowanych produktów i odejmowanie od ilości
//                $sess = $this->session->userdata('ses_id');
//                if (empty($sess)) {
//                    $this->session->set_userdata('ses_id', md5(date('U')));
//                }
//                $ses_id = $this->session->userdata('ses_id');
////                foreach($basket as $key2=>$item){
////                    $key_data = explode("_", $key2);
////                    $this->product_object->blocked_product($key_data[0], $key_data[1], (0-$item[0]), $ses_id);
////                    $this->product_object->blocked_product($key_data[0], $key_data[1], $item[0], $ses_id, 0);
////                }
//
//                if($res){
//                        foreach($basket as $key2=>$item){
//                    $key_data = explode("_", $key2);
//                     $this->product_object->itemBought($key_data[0], $ses_id);
////                    $this->product_object->blocked_product($key_data[0], $key_data[1], (0-$item[0]), $ses_id);
////                    $this->product_object->blocked_product($key_data[0], $key_data[1], $item[0], $ses_id, 0);
//                }
//                    //email z potwiedzeniem zakupu
//                    $this->config->load('email');
//                    $from_mail = !empty($this->config->item('smtp_user')) ?  $this->config->item('smtp_user'):  get_option('email_from');
//                    $this->email->from($from_mail, get_option('email_from_name'));
//                    $this->email->to(get_option('email_from'));
//                    $this->email->cc($this->input->post('email'));
//                    //$this->email->reply_to($this->input->post('email'));
//                    $this->email->subject( (new CustomElementModel('14'))->getField('status 0')->value);
//                    $delivery = $this->delivery_object->get_delivery($data['delivery']);
//                    $message = $this->load->view('templates/formularz-zamowienia-podsumowanie', 
//                            array(
//                                'products' => $products,
//                                'delivery' => $delivery,
//                                'sum_price' => $total,
//                                'order' => $data,
//                                'order_id'=>$res
//                            ), true);
//                    $this->email->message($message);
//
//                    $res2 = $this->email->send();
//                    
//                    $this->session->set_userdata('basket',array());
//                              $cookie_data = [
//            'name' => 'basket',
//            'value' => json_encode([]),
//            'expire' => 2500000,
//            'secure' => FALSE,
//            'domain' => ADMIN_DOMAIN
//        ];
//        $this->input->set_cookie($cookie_data);
//                    redirect(site_url('zamowienie/summary/'.$res.'/'.$key));
//                }
            }
        }
        $this->layout('zamowienie/basket-step-2', array(
            'products' => $products,
            'basket' => $basket,
            'userdata' => $userdata,
            'free_delivery' => $free_delivery,
            'deliveries' => $deliveries,
            'sum_price' => $this->code_obj->recalculate_sum($products, null, 1),
            'sum_weight' => $sum_weight,
            'inpost_points' => null,
            'initial_free_delivery' => $initial_free_delivery
        ));
    }
    
    public function basket_step3(){
                 $userdata = array();
        $user_ses_data = !empty($this->session->userdata('login')['user']) ? $this->session->userdata('login')['user'] : null;
        if(!empty($user_ses_data)){
            $userdata = $user_ses_data;
        }
        //$this->breadcrumbs[] = (new CustomElementModel('10'))->getField('Twój koszyk');
        
        //$this->change_basket();
        $basket = json_decode(json_encode($this->session->userdata('basket')), true);
        //$basket = json_decode($this->input->cookie('basket', true),true);
        $products = $this->get_products($basket);
        
        
        
        //liczę sumę ceny koszyka i wagi
        $sum_weight = 0;
        $sum_price = 0;
        foreach($products as $p){
            $sum_price += $p['price']*1*$p['quantity'];
            $sum_weight += !empty($p['option']['weight']) ? $p['option']['weight']*$p['quantity'] : (!empty($p['product_data']->weight) ? $p['product_data']->weight*$p['quantity'] : 0);
        }
        $ac = get_active_currency()->id;
        $deliveries = $this->delivery_object->get_deliveries($sum_price, $sum_weight, LANG);
        $free_delivery=0;
        $initial_free_delivery = null;
        if (!empty($deliveries)){
            $min_delivery = $deliveries[0]->prices[$ac]['max_price'];
            $initial_free_delivery = $deliveries[0];
            foreach ($deliveries as $delivery){ 
                if ($min_delivery > $delivery->prices[$ac]['max_price']){
                    $min_delivery = $delivery->prices[$ac]['max_price'];  
                    $initial_free_delivery = $delivery;
                }
            }
            $free_delivery = $min_delivery-$sum_price;
        }
//        echo $sum_weight;
//        die();
         $data = $this->session->userdata('current_order');
        if(empty($products) || empty($data)){
            redirect(site_url('koszyk'));
        }
        if(!empty($_POST['delivery'])){
            $user_id = isset($userdata['id']) ? $userdata['id'] : null;
            $userdata = $this->input->post();

            $this->form_validation->set_rules('method', (new CustomElementModel('11'))->getField('Wybierz metodę płatności'), 'required');
            $this->form_validation->set_rules('delivery', (new CustomElementModel('11'))->getField('Wybierz sposob dostawy'), 'required');
            $this->form_validation->set_rules('accept_term', (new CustomElementModel('11'))->getField('regulamin'), 'required');
            $this->form_validation->set_rules('g-recaptcha-response', 'reCaptcha', 'callback__validate_kapcza');
            $this->form_validation->set_message('_validate_kapcza', 'Przejdź test reCaptcha');

            if ($this->form_validation->run()) {
                $data = $this->session->userdata('current_order');
                
                $data['delivery'] = $this->input->post('delivery');
                $data['method'] = $this->input->post('method');

                $total = $this->code_obj->recalculate_sum($products, null, 1) * 1;
                
                  $delivery_price = $this->delivery_object->get_price2($data['delivery'],$data['currency_id']);
                $delivery_max_price = $this->delivery_object->get_max_price($data['delivery'],$data['currency_id']);
                    
                if ($total >= $delivery_max_price * 1) {
                    $delivery_price = 0;
                }

                $data['price'] += $delivery_price;
                
                $res = $this->order_object->add($data, $basket, 0, $user_id);
                
                //czyszczenie zablokowanych produktów i odejmowanie od ilości
                $sess = $this->session->userdata('ses_id');
                if (empty($sess)) {
                    $this->session->set_userdata('ses_id', md5(date('U')));
                }
                $ses_id = $this->session->userdata('ses_id');
                
                if($res){
                        foreach($basket as $key2=>$item){
                    $key_data = explode("_", $key2);
                     $this->product_object->itemBought($key_data[0], $ses_id);
                    }
                    //email z potwiedzeniem zakupu
                    $this->config->load('email');
                    $from_mail = !empty($this->config->item('smtp_user')) ?  $this->config->item('smtp_user'):  get_option('email_from');
                    $this->email->from($from_mail, get_option('email_from_name'));
                    $this->email->to(get_option('email_from'));
                    $this->email->cc($data['email']);
                    //$this->email->reply_to($this->input->post('email'));
                    $this->email->subject( (new CustomElementModel('14'))->getField('status 0')->value);
                    $delivery = $this->delivery_object->get_delivery($data['delivery']);
                    $message = $this->load->view('templates/formularz-zamowienia-podsumowanie', 
                            array(
                                'products' => $products,
                                'delivery' => $delivery,
                                'sum_price' => $total,
                                'order' => $data,
                                'order_id'=>$res
                            ), true);
                    $this->email->message($message);

                    $res2 = $this->email->send();
                    
                    $this->session->set_userdata('basket',array());
                    $this->session->set_userdata('current_order', []);
//                              $cookie_data = [
//            'name' => 'basket',
//            'value' => json_encode([]),
//            'expire' => 2500000,
//            'secure' => FALSE,
//            'domain' => ADMIN_DOMAIN
//        ];
//        $this->input->set_cookie($cookie_data);
                    redirect(site_url('zamowienie/summary/'.$res.'/'.$data['key']));
                }
            }
        }
        $this->layout('zamowienie/basket-step-3', array(
            'products' => $products,
            'basket' => $basket,
            'userdata' => $userdata,
            'free_delivery' => $free_delivery,
            'deliveries' => $deliveries,
            'sum_price' => $this->code_obj->recalculate_sum($products, null, 1),
            'sum_weight' => $sum_weight,
            'inpost_points' => null,
            'initial_free_delivery' => $initial_free_delivery
        ));
    }
    
    function change_basket(){
        if(!empty($_POST['actualize'])){
            //$basket = json_decode($this->input->cookie('basket', true),true);
            $basket = json_decode(json_encode($this->session->userdata('basket')), true);
            if(!empty($basket)){
                foreach($basket as $key=>$b){
                    $item = $this->input->post($key);
                    if(!empty($item)){
                        $this->product_object->change_basket($key, $item);
                    }
                }
            }
            setAlert('success',(new CustomElementModel('16'))->getField('zaktualizowano koszyk')->value);
        }
    }

 public function delete_item($product_id) {
        $sess = $this->session->userdata('ses_id');
        if (empty($sess)) {
            $this->session->set_userdata('ses_id', md5(date('U')));
        }
        $ses_id = $this->session->userdata('ses_id');
        $ar = explode("_", $product_id);
        $p_id = $ar[0];
        $o_id = isset($ar[1]) ? $ar[1] : null;
        //$basket = json_decode($this->input->cookie('basket', true),true);
        $basket = json_decode(json_encode($this->session->userdata('basket')), true);
        $this->product_object->blocked_product($p_id, $o_id, 0 , $ses_id);
        unset($basket[$product_id]);
        $this->session->set_userdata('basket', $basket);
                  $cookie_data = [
            'name' => 'basket',
            'value' => json_encode($basket),
            'expire' => 2500000,
            'secure' => FALSE,
            'domain' => ADMIN_DOMAIN
        ];
        $this->input->set_cookie($cookie_data);
        setAlert('info',(new CustomElementModel('16'))->getField('usunieto produkt')->value);
        redirect(site_url('koszyk'));
    }
    
    public function summary($order_id, $key = null){
        $this->load->library('Openpayu');
        require_once realpath(dirname(__FILE__)) . '/../libraries/OpenPayU/config.php';
        
        
        
        //$this->breadcrumbs[] = "Podsumowanie zamówienia";
        $basket = $this->order_object->get_basket($order_id);
        $this->order_object->get_order($order_id);
        $products = $this->get_products($basket, $this->order_object->discount_id, $this->order_object->currency_id);
        $this->order_object->get_order($order_id);
        if($key != $this->order_object->key){
            echo 'Brak dostepu';
            die();
        }
        $orders = $this->order_object;
        $delivery = $this->delivery_object->get_delivery($this->order_object->delivery);
        
        if(!empty($_POST['payu'])){
            $order['continueUrl'] = site_url('zamowienie/pay_success'); //customer will be redirected to this page after successfull payment
            $order['notifyUrl'] = site_url('api/payments/payu');
            $order['customerIp'] = $_SERVER['REMOTE_ADDR'];
            $order['merchantPosId'] = OpenPayU_Configuration::getMerchantPosId();
            $order['description'] = (new CustomElementModel('16'))->getField('Tytul zamowienia')->value; 
            $order['currencyCode'] = 'PLN';
            $order['totalAmount'] = round($orders->price * 100) ;
            $order['extOrderId'] = $order_id.'_'.date('U'); //must be unique!
            
            if (!empty($products)) {
                $i = 0;
                $total = 0;
                foreach ($products as $product) {
                    $order['products'][$i]['name'] = $product['product']->name;
                    $order['products'][$i]['quantity'] = $product['quantity'];
                    $order['products'][$i]['unitPrice'] = round($product['price']*100);
                    $total = $product['price']*$product['quantity'];
                    $i++;
                }
                if (!$total > $delivery['prices'][$this->order_object->currency_id]['max_price']) {
                    $order['products'][$i]['name'] = (new CustomElementModel('16'))->getField('Dostawa')->value;
                    $order["products"][$i]['quantity'] = 1;
                    $order["products"][$i]['unitPrice'] = $delivery['prices'][$this->order_object->currency_id]['price'] * 100;
                    $i++;
                }
            }

            //optional section buyer
            $order['buyer']['email'] = $orders->email;
            $order['buyer']['phone'] = $orders->phone;
            $order['buyer']['firstName'] = $orders->first_name;
            $order['buyer']['lastName'] = $orders->last_name;

            $response = OpenPayU_Order::create($order);

            header('Location:'.$response->getResponse()->redirectUri); //You must redirect your client to PayU payment summary page.
        }
        
        if(!empty($_POST['paypal'])){
            $querystring = '';

            $querystring .= "?business=".urlencode(get_option('paypal_email'))."&";

            // Append amount& currency (£) to quersytring so it cannot be edited in html

            //The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
            $querystring .= "item_name=".urlencode((new CustomElementModel('16'))->getField('Tytul zamowienia')->value)."&";
            $querystring .= "amount=".urlencode($orders->price*1)."&";

            //loop for posted values and append to querystring
            foreach($_POST as $key => $value) {
                $value = urlencode(stripslashes($value));
                $querystring .= "$key=$value&";
            }

            // Append paypal return addresses
            $querystring .= "return=".urlencode(stripslashes(site_url('zamowienie/pay_success')))."&";
            $querystring .= "cancel_return=".urlencode(stripslashes(site_url('zamowienie/pay_error')))."&";
            $querystring .= "notify_url=".urlencode(site_url('api/payments/paypal'));

            // Append querystring with custom field
            $querystring .= "&custom=".$order_id;

            // Redirect to paypal IPN
            header('location:' . get_option('paypal_url') . $querystring);
            exit();
        }
        
        $this->load->model('CurrencyModel');
        $currency = $this->CurrencyModel->get_currency($orders->currency_id);
        
        $this->layout('zamowienie/summary', array(
            'products' => $products,
            'order' => $orders,
            'basket' => $basket,
            'delivery' => $delivery,
            'currency' => $currency
        ));
    }
    
    public function pay_success(){
        if(!empty($_GET['error'])){
            redirect(site_url('zamowienie/pay_error'));
        }
        $this->layout('zamowienie/pay_success', array());
    }
    
    public function pay_error(){
        $this->layout('zamowienie/pay_error', array());
    }
    
    public function ajax_get_locker_select(){
        $points = array();
        $new_array = array();
        $i = 1;
        do {
            $new_array = json_decode($this->inpost->get_points('parcel_locker', $i))->items;
            $points = array_merge($points, $new_array);
            $i++;
        } while (!empty($new_array));
        
        $this->load->view('zamowienie/points_select',array(
            'points' => $points
        ));
    }
    
    public function ajax_basket_update(){
        $pdata = $this->input->post();
        if(!empty($pdata)){
            if(!empty($pdata['data'])){
                $basket = json_decode($pdata['data']);
                $valid = true;
                foreach($basket as $row){
                    $t = $this->product_object->change_basket(intval($row->product_id), intval($row->quantity));
                    if(!$t){
                        $valid = false;
                    }
                }
                if($valid){
                    $basket = json_decode(json_encode($this->session->userdata('basket')), true);
                    $products = $this->get_products($basket);

                    //liczę sumę ceny koszyka i wagi
                    $sum_weight = 0;
                    $sum_price = 0;
                    $amount = 0;
                    foreach ($products as $p) {
                        $amount += $p['quantity'];
                        $sum_price += $p['price'] * 1 * $p['quantity'];
                        $sum_weight += !empty($p['option']['weight']) ? $p['option']['weight'] * $p['quantity'] : (!empty($p['product_data']->weight) ? $p['product_data']->weight * $p['quantity'] : 0);
                    }
                    $ac = get_active_currency()->id;
                    $deliveries = $this->delivery_object->get_deliveries($sum_price, $sum_weight, LANG);
                    $free_delivery = 0;
                    $initial_free_delivery = null;
                    if (!empty($deliveries)) {
                        $min_delivery = $deliveries[0]->prices[$ac]['max_price'];
                        $initial_free_delivery = $deliveries[0];
                        foreach ($deliveries as $delivery) {
                            if ($min_delivery > $delivery->prices[$ac]['max_price']) {
                                $min_delivery = $delivery->prices[$ac]['max_price'];
                                $initial_free_delivery = $delivery;
                            }
                        }
                        $free_delivery = $min_delivery - $sum_price;
                    }
                    $userdata = array();
                    $user_ses_data = !empty($this->session->userdata('login')['user']) ? $this->session->userdata('login')['user'] : null;
                    if (!empty($user_ses_data)) {
                        $userdata = $user_ses_data;
                    }
                    $sum_price_recalculated = $this->code_obj->recalculate_sum($products, null, 1);
                    $output = [
                        'error' => 0,
                        'basket_inner' => $this->load->view(
                                'zamowienie/basket-inner',
                                [
                                    'products' => $products,
                                    'basket' => $basket,
                                    'userdata' => $userdata,
                                    'free_delivery' => $free_delivery,
                                    'deliveries' => $deliveries,
                                    'sum_price' => $sum_price_recalculated,
                                    'sum_weight' => $sum_weight,
                                    'initial_free_delivery' => $initial_free_delivery
                                ],
                                true),
                        'basket_delivery_payment' => $this->load->view(
                                'zamowienie/basket-delivery-payment',
                                [
                                    'sum_price' => $sum_price_recalculated,
                                    'deliveries' => $deliveries,
                                ],
                                true
                                ),
                        'amount' => $amount,
                        'sum_price' => $sum_price_recalculated
                    ];
                    die(json_encode($output));
                } else {
                     die(json_encode(['error' => 1]));
                }
                die();
                
            } else{
                die(json_encode(['error' => 1]));
            }
        } else {
            die(json_encode(['error' => 1]));
        }
    }

//    public function bubitest(){
////        $this->session->set_userdata('basket', []);
//        var_dump($this->session->userdata('basket'));
//        die();
//    }


}