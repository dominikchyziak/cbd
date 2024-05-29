<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class OrderModel extends MY_Model {

    protected $_table_name = 'orders';
    protected $_table_second = 'orders_items';
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $city;
    public $zip_code;
    public $street;
    public $building_number;
    public $flat_number;
    public $inpost_locker;
    public $delivery;
    public $delivery_additional;
    public $weight;
    public $method;
    public $comment;
    public $price;
    public $status;
    public $discount_id;
    public $created_at;
    public $modified_at;
    public $key;
    public $items;
    public $currency_id;
     
    public function __construct() {
        parent::__construct();
    }
    
    public function add($args, $basket = array(), $status = 0, $user_id = 0){
        $args['status'] = $status;
        $discount = !empty($this->session->userdata['discount']) ? $this->session->userdata['discount'] : 0;
        if(!empty($discount)){
            $args['discount_id'] = $discount['id'];
        }
        $this->db->insert($this->_table_name, $args);
        $order_id = $this->db->insert_id();
        if(!empty($order_id) && !empty($discount)){
            $this->db->insert('duo_shop_codes_used',array(
                'user_id' => $user_id,
                'code_id' => $discount['id']
            ));
            if($discount['id'] == 1){
                $this->load->model('User_Model');
                $user = $this->User_Model->get_user($user_id);
                $this->db->where('client_email', $user->email);
                $this->db->update('duo_shop_one_time_codes', array('used' => 1));
            }
            $this->session->set_userdata('discount',null);
        }
        $path = "./uploads/orders/".$order_id;
        if(!is_dir($path)) //create the folder if it's not already exists
        {
          mkdir($path,0755,TRUE);
        } 
        if(!empty($basket)){
            foreach($basket as $product_id=>$item){
                $ar = explode("_", $product_id);
                $p_id = $ar[0];
                 $o_id = isset($ar[1]) ? $ar[1] : 0;
                $this->db->insert($this->_table_second, array(
                    'order_id' => $order_id,
                    'product_id' => $p_id,
                    'option_id' => $o_id,
                    'quantity' => $item[0],
                    'attributes' => json_encode($item[1]),
                    'additional' => !empty($item[2]) ?$item[2] : ''
                ) );
                //aktualizacja ilości do bestselerów
                $str = "UPDATE `duo_products` SET `sold` = sold + ".$item[0]."  WHERE `id` = ".$p_id;
                $this->db->query($str);

                if(!empty($item[1]['file_name'])){
                    $file_name = $item[1]['file_name'];
                    copy('./tmp_files/'.$file_name, $path.'/'.$file_name);
                    unlink('./tmp_files/'.$file_name);
                    $this->db->delete('duo_tmp_files', array('file_name' => $file_name));
                }
                
            }
        }
        return $order_id;
    }
    

    
    function copy_files(){
        
    }
    
    public function get_orders(){
        $q = $this->db->select('duo_shop_delivery_translations.name, '.$this->_table_name.'.*')
                ->group_by($this->_table_name.'.id')
                ->order_by($this->_table_name.'.id','DESC')
                ->join('duo_shop_delivery_translations', 'duo_shop_delivery_translations.delivery_id = '.$this->_table_name.'.delivery','left')
                ->get($this->_table_name)->result('OrderModel');
        return $q;
    }
    
    
    public function get_orders2_row_count($status = null, $delivery = null){
            if($status != null){
                $this->db->where('status', $status);
            }
            if(!empty($delivery)){
                $this->db->where('delivery', $delivery);
            }
            $this->db->where('allegro_id', NULL);
        $this->db->select('duo_shop_delivery_translations.name, '.$this->_table_name.'.*')
                ->group_by($this->_table_name.'.id')
                ->order_by($this->_table_name.'.id','DESC')
                ->join('duo_shop_delivery_translations', 'duo_shop_delivery_translations.delivery_id = '.$this->_table_name.'.delivery','left');

                $q = $this->db->get($this->_table_name)->num_rows();
        return $q;
    }
    
    public function get_orders_user($email){
        $q = $this->db->where('email',$email)->order_by('id','DESC')->get($this->_table_name)->result();
        return $q;
    }
    
    
    
    public function get_order($order_id){
        $q = $this->db->get_where($this->_table_name, array('id' => $order_id))->result();
        $this->id = $order_id;
        $this->first_name = $q[0]->first_name;
        $this->last_name = $q[0]->last_name;
        $this->email = $q[0]->email;
        $this->phone = $q[0]->phone;
        $this->city = $q[0]->city;
        $this->zip_code = $q[0]->zip_code;
        $this->street = $q[0]->street;
        $this->building_number = $q[0]->building_number;
        $this->flat_number = $q[0]->flat_number;
        $this->inpost_locker = $q[0]->inpost_locker;
        $this->delivery = $q[0]->delivery;
        $this->delivery_additional = $q[0]->delivery_additional;
        $this->weight = $q[0]->weight;
        $this->method = $q[0]->method;
        $this->comment = $q[0]->comment;
        $this->price = $q[0]->price;
        $this->discount = $q[0]->discount;
        $this->status = $q[0]->status;
        $this->currency_id = $q[0]->currency_id;
        $this->discount_id = $q[0]->discount_id;
        $this->created_at = $q[0]->created_at;
        $this->modified_at = $q[0]->modified_at;
        $this->key = $q[0]->key;
        $this->items = $this->get_basket($order_id);
        $this->company_name = $q[0]->company_name;
        $this->company_nip = $q[0]->company_nip;
        $this->company_address = $q[0]->company_address;
        $this->package_id = $q[0]->package_id;
        return $this;
    }
    
    public function delete_order($id){
        $this->db->delete($this->_table_name, array('id' => $id));
        $this->db->delete($this->_table_second, array('order_id' => $id));
        return TRUE;
    }
    
    //zmiana statusu płatności
    // -1 - Anulowane
    // 0 - Nowe nieopłacone
    // 5 - Opłacone do realizacji
    // 10 - W trakcie realizacji
    // 15 - Zrealizowane
    // 20 - sprawa zamknięta (do archiwum)
    public function change_status($order_id, $new_status = 5, $email_user = 1, $email_admin = 0, $note = ''){
        $this->get_order($order_id);
        $res = $this->db->where('id',$order_id)->update($this->_table_name, array('status' => $new_status));
        $this->db->insert('duo_shop_story', array('order_id' => $order_id, 'new_status' => $new_status, 'note' => $note, 'created_at' => date('Y-m-d H:i:s')));
        //anulowanie zwraca sztuki
        $basket = $this->get_basket($order_id);
//        if($new_status == -1){
//            $this->cancel_order($basket,1);
//        }
//        if($this->status == -1 && $new_status != -1){
//            $this->cancel_order($basket,0);
//        }
        
        if($email_user){
            if($new_status*1 < 20){
                $this->send_mail("user", $new_status, $note);
            }
        }
        if($res && $email_admin){
            if($new_status*1 < 10){
                $this->send_mail("admin", $new_status, $note);
            }
        }
        return $res;
    }
    
    // minus_basket oznacza akcję anulowania i zwrotu sztuk z powrotem do puli 
    private function cancel_order($basket, $minus_basket = 1){
        $this->load->model('ProductModel');
        foreach($basket as $product_id=>$item){
            $data = explode("_", $product_id);
            $p_id = $data[0];
            $op_id = $data[1];
            $quantity = $item;
            $product = new ProductModel($p_id);
            $this->blocked_product($product, $op_id, $quantity, $minus_basket);
        }
    }
    private function blocked_product($product, $option_id, $quantity, $minus_basket){
        if ($option_id > 0) {
            $op = $product->select_option($option_id);
            if($op['quantity_left'] > -1){
                if($minus_basket){
                    $this->db->where('id', $op['id'])->update('shop_options', array('quantity_left' => $op['quantity_left'] + $quantity));
                } else {
                    if($op['quantity_left'] >= $quantity){
                        $this->db->where('id', $op['id'])->update('shop_options', array('quantity_left' => $op['quantity_left'] - $quantity));
                    } else {
                        $this->setError('Za mało produktu by wykonać tę akcję.');
                    }
                }
            }
        } else if ($this->quantity > -1) {
            if($minus_basket){
                $this->db->where('id',$product->id)->update('products', array('quantity' => $product->quantity + $quantity));
            } else {
                if($product->quntity <= $quantity){
                    $this->db->where('id',$product->id)->update('products', array('quantity' => $product->quantity - $quantity));
                } else {
                    $this->setError('Za mało produktu by wykonać tę akcję.');
                }
            } 
        }
    }

    private function send_mail($user_admin = "user", $new_status = 5, $note = ''){
        $CI =& get_instance();
        $CI->load->helper('url', 'language', 'functions', 'form');       
        $CI->load->library('email');
        $CI->load->model('CustomElementModel');
        $CI->load->model('CustomElementFieldModel');
        
        $subject = 'Zmiana statusu zapytania na: ' . (new CustomElementModel('14'))->getField('status '.$new_status)->value;

        $this->config->load('email');
        if($user_admin == "user"){
            $this->config->load('email');
            $from_mail = !empty($this->config->item('smtp_user')) ?  $this->config->item('smtp_user') :  get_option('email_from');
            $CI->email->from($from_mail, get_option('email_from_name'));
            $CI->email->to($this->email);
            $CI->email->subject($subject);
            $message = $CI->load->view('templates/shop/status'.$new_status,  array('order' => $this, 'note' => $note), true);
        }
        if($user_admin == "admin"){
            $CI->email->from($this->email, get_option('email_from_name'));
            $CI->email->to(get_option('email_from'));
            $CI->email->subject($subject);
            $message = $CI->load->view('templates/shop/status'.$new_status, array('order' => $this, 'note' => $note), true);
        }
        $CI->email->message($message);
        $res = $CI->email->send();
        return $res;
    }

    public function get_basket($order_id){
        $q = $this->db->get_where($this->_table_second, array('order_id'=> $order_id))->result();
        $basket = array();
        foreach($q as $item){
            $basket[$item->product_id.'_'.$item->option_id.'_'.$item->id] = [
                $item->quantity,
                (array) json_decode($item->attributes),
                $item->additional
                ];
        }
        return $basket;
    }
    public function get_story($order_id){
        $q = $this->db->get_where('duo_shop_story', array('order_id' => $order_id));
        return $q->result();
    }
    
    public function get_shipment($order_id = null){
        if($order_id == null && $this->id > 0){
            $order_id = $this->id;
        }
        
        $q = $this->db->get_where('duo_shop_inpost_rel', array(
            'order_id' => $order_id
        ));
        if($q->num_rows() > 0){
            $row = $q->row();
            return $row->shipment_id;
        } else {
            return 0;
        }
    }
    
    public function add_shipment($order_id, $shipment_id){
        return $this->db->insert('duo_shop_inpost_rel', array(
            'order_id' => $order_id,
            'shipment_id' => $shipment_id
        ));
    }
    
    // funkcja która przeliczy ile paczek tak naprawdę potrzeba
    public function recalculate_shipment($order_id){
        $order = $this->get_order($order_id);
        $basket = $this->get_basket($order_id);
        $tmp = null;
        foreach ($basket as $product_id => $quantity) {
            $data = explode("_", $product_id);
            $p_id = $data[0];
            $this->load->model("ProductModel");
            $pr = new ProductModel($p_id);
            $tmp[$p_id]= array (
                'quantity' => $quantity[0],
                'weight' => (float) $pr->weight
            );
        }
        $single_package_max_weight =  (new CustomElementModel('18'))->getField('maksymalna waga pojedynczej przesylki')->value;
        $result[0] = array('total_weight' => ((float) 0)) ;
        while(!empty($tmp)){
            
            //search for heaviest
            $max_w = -1;
            $pid_of_max_w = -1;
            foreach ($tmp as $pid => $item) {
                if(((float) $item['weight']) > $max_w){
                    $max_w = (float) $item['weight'];
                    $pid_of_max_w = (int) $pid;
                }
            }
            
            // put heaviest to package
            $quanityty_of_heaviest =  (int) $tmp[$pid_of_max_w]['quantity'];
            for ($i = 0; $i < $quanityty_of_heaviest; $i++){
                
                foreach ($result as $key => $paczka){
                    if($paczka['total_weight'] + $max_w <= $single_package_max_weight){
                        if(!isset($paczka[$pid_of_max_w])){ $paczka[$pid_of_max_w] = 0;}

                        $paczka[$pid_of_max_w] += 1;
                        $paczka['total_weight'] += $max_w;
                        $result[$key]= $paczka;
                    } else{
                        if(!isset($result[($key+1)])){
                            $result[($key+1)] = array('total_weight' => $max_w) ; 
                            $result[($key+1)][$pid_of_max_w] = 1;
                        }
                        
                    }
                }
            }
            unset($tmp[$pid_of_max_w]);
        }
        return $result;
    }
    
    public function get_unique_client_list(){
        $this->db->select('email, first_name, last_name, city, zip_code, street');
        $this->db->distinct();
        $query = $this->db->get($this->_table_name);
        
        return $query->result();
    }

    public function find_amount_sold_since_date($product){
//        if(empty($product->amount_updated_at)){
//            $product->amount_updated_at = $product->created_at;
//        }
        $this->db->select('quantity,'. $this->_table_name.'.created_at');
        $this->db->where($this->_table_second.'.product_id', $product->id);
        $this->db->join($this->_table_second, $this->_table_name.'.id = '.$this->_table_second.'.order_id');
        $q = $this->db->get($this->_table_name);
        $amount = 0;
        foreach($q->result() as $o){
            if($o->created_at > $product->amount_updated_at){
            $amount += $o->quantity;
            }
        }
        return $amount;
        
    }
    
        public function get_all_statuses() {
        $this->db->select('status');
        $this->db->distinct();
        $q = $this->db->get($this->_table_name);
        return $q->result();
    }

    public function get_orders2($status = null, $delivery = null, $limit = null, $page = 1) {
        if ($status != null) {
            $this->db->where('status', $status);
        }
        if (!empty($delivery)) {
            $this->db->where('delivery', $delivery);
        }
        $this->db->where('allegro_id', NULL);
        $this->db->select('duo_shop_delivery_translations.name, ' . $this->_table_name . '.*')
                ->group_by($this->_table_name . '.id')
                ->order_by($this->_table_name . '.id', 'DESC')
                ->join('duo_shop_delivery_translations', 'duo_shop_delivery_translations.delivery_id = ' . $this->_table_name . '.delivery', 'left');
        if (!empty($limit)) {
            $this->db->limit($limit, $limit * $page);
        }
        $q = $this->db->get($this->_table_name)->result('OrderModel');
        return $q;
    }


        public function get_orders_by_delivery_category($category_id){
        $this->db->select('duo_shop_delivery_translations.name, '.$this->_table_name.'.*');
        $this->db->group_by($this->_table_name.'.id');
        $this->db->order_by($this->_table_name.'.id','DESC'); 
        $this->db->join('duo_shop_delivery', 'duo_shop_delivery.id = duo_orders.delivery', 'left');
        $this->db->join('duo_shop_delivery_translations', 'duo_shop_delivery_translations.delivery_id = '.$this->_table_name.'.delivery','left');
        $this->db->where('duo_shop_delivery.category_id' , $category_id);
        $q = $this->db->get('duo_orders');
        
        return $q->result('OrderModel');
    }
    
    
    public function add_order_from_allegro($data){
        $insert = array(
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'city' => $data['city'],
            'zip_code' => $data['zip_code'],
            'street' => $data['street'],
            'building_number' => $data['building_number'],
            'flat_number' => (!empty($data['flat_number'])) ? $data['flat_number'] : null,
            'delivery' => $data['delivery'],
            'phone' => $data['phone'],
            'comment' => $data['comment'],
            'method' => $data['method'],
            'price' => $data['price'],
            'inpost_locker' => $data['locker'],
            'key' => null,
            'allegro_id' => $data['allegro_id'],
            'discount' => 0,
            'status' => 5
        );
        $this->db->insert($this->_table_name, $insert);
        
        return $this->db->insert_id();
    }
    
 public function add_item_to_order($product_id, $quantity, $order_id, $flag = false){
        $data = array(
            'product_id' => $product_id,
            'order_id' => $order_id,
            'quantity' => $quantity,
            'option_id' => 0,
            'attributes' => 'null',
            'additional' => ''
        );
        
        $this->db->insert($this->_table_second, $data);
        if($flag == false){
        $this->db->where('id', $product_id);
        $q = $this->db->get('duo_products');
        $in_stock = $q->result()[0]->quantity;
        if($in_stock >= $quantity){
            $new_stock = $in_stock - $quantity;
        } else {
            $new_stock = 0;
        }
        $this->db->where('id', $product_id);
        $this->db->update('duo_products', array(
            'quantity' => $new_stock
        ));
        }
    }
    
    public function check_if_order_in_db($allegro_id){
        $this->db->where('allegro_id', $allegro_id);
        $q = $this->db->get($this->_table_name);
        
        if($q->num_rows() > 0){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    
    public function update_client_data($id, $args){
        $this->db->where('id', $id);
        $this->db->update($this->_table_name, $args);
    }
    
    public function update_delivery_point($id, $number){
        $args = array(
            'inpost_locker' => $number
        );
        $this->db->where('id', $id);
        $this->db->update($this->_table_name, $args);
    }
}
