<?php

class ProductModel extends MY_Model {
    
    protected $_table_name = 'duo_products';

    private $_relations_table = 'duo_shop_product_relations';

    private $attributes_table = 'duo_shop_attributes';
    private $attributes_table_translations = 'duo_shop_attributes_translations';
    private $attributes_table_relations = 'duo_shop_attributes_relations';
    

    public $id;
    public $order;
    public $offer_category_id;
    public $type;
    public $price = 0;
    public $options = 0;
    public $quantity = -1;
    public $weight = 0;
    public $new = 0;
    public $promo = 0;
    public $bestseller = 0;
    public $code = '';
    public $active = 0;
    public $amount_updated_at;
    public $created_at;
    public $updated_at;
    public $options_object;
    public $external_id;
    public $vat;
    public $availability;
    public $number_in_package;
    public $always_avaible;
    public $ean;
    public $producent = "";
    public $attributes = array();
    public $prices = array();
    public $categories = array();
    public $tags = '';
    public $slider = 0;
    
    public function __construct($id = null) {
        parent::__construct();
        $this->load->model('ProductOptionModel');
        $this->load->model('UserProductDiscountModel');

        if (!is_null($id)) {
            $this->getById($id);
        }
         //doliczam ewentualny rabat
            if(!empty($this->session->userdata['login']['user']['discount']) && !empty($this->price)){
                $dsc = $this->session->userdata['login']['user']['discount'];
                $this->price = round(($this->price  - ($dsc / 100) * $this->price) * 100) / 100;
            }
    }

    public function insert_product() {
        $res = $this->db->insert('products', [
            'offer_category_id' => $this->offer_category_id ?: null,
            'type' => $this->type ?: null,
            'price' => $this->price,
            'new' => $this->new,
            'weight' => $this->weight,
            'promo' => $this->promo,
            'bestseller' => $this->bestseller, 
            'code' => $this->code,
            'active' => $this->active,
            'quantity' => $this->quantity,
            'availability' => $this->availability,
            'external_id' => $this->external_id,
            'vat' => $this->vat,
            'number_in_package' => $this->number_in_package,
            'always_avaible' => $this->always_avaible,
            'ean' => $this->ean,
            'producent' => $this->producent,
            'tags' => $this->tags,
            'created_at' => (new DateTime())->format('Y-m-d H:i:s'),
            'slider' => $this->slider,
        ]);

        if ($res) {
            $this->id = $this->db->insert_id();
        }
        if(!empty($this->prices)){
            foreach ($this->prices as $currency_id =>$price) {
                $cdata = array(
                    'product_id' => $this->id,
                    'currency_id' => $currency_id,
                    'price' => $price
                );
                $this->db->insert('duo_product_prices', $cdata);
            }
        }
        if(!empty($this->categories)){
            foreach($this->categories as $cats){
                $this->db->insert('products_categories',[
                    'product_id' => $this->id,
                    'category_id' => $cats,
                    'created_at' => (new DateTime())->format('Y-m-d H:i:s')
                ]);
            }
        }
        return $res;
    }

    public function update_product() {
        if(empty($this->id)){ return null; }
        $this->db->where('id', $this->id);
        $res = $this->db->update('products', [
            'offer_category_id' => $this->offer_category_id ?: null,
            'type' => $this->type ?: null,
            'price' => $this->price,
            'weight' => $this->weight,
            'new' => $this->new,
            'promo' => $this->promo,
            'bestseller' => $this->bestseller,
            'producent' => $this->producent,
            'code' => $this->code,
            'active' => $this->active,
            'quantity' => $this->quantity,
            'amount_updated_at' => $this->amount_updated_at,
             'availability' => $this->availability,
            'external_id' => $this->external_id,
            'vat' => $this->vat,
            'number_in_package' => $this->number_in_package,
            'always_avaible' => $this->always_avaible,
            'ean' => $this->ean,
            'tags' => $this->tags,
            'updated_at' => (new DateTime())->format('Y-m-d H:i:s'),
            'slider' => $this->slider
        ]);
        if (!empty($this->prices)) {
            foreach ($this->prices as $currency_id => $price) {
                $this->db->where('product_id', $this->id);
                $this->db->where('currency_id',$currency_id);
                $q5 = $this->db->get('duo_product_prices');
                if($q5->num_rows() > 0){
                $cdata = array(
                    'price' => $price
                );
                $this->db->where('product_id', $this->id);
                $this->db->where('currency_id',$currency_id);
                $this->db->update('duo_product_prices', $cdata);
                } else {
                     $cdata = array(
                    'product_id' => $this->id,
                    'currency_id' => $currency_id,
                    'price' => $price
                );
                $this->db->insert('duo_product_prices', $cdata);
                }
            }
        }
        
  
        if(!empty($this->categories)){
            $this->db->where('product_id', $this->id); 
            $this->db->delete('duo_products_categories');
            foreach($this->categories as $cats){
                $this->db->insert('duo_products_categories',[
                    'product_id' => $this->id,
                    'category_id' => $cats,
                    'created_at' => (new DateTime())->format('Y-m-d H:i:s')
                ]);
            }
        }
        return $res;
    }
    
    public function copy_product($id){
        //kopia produktu
        $this->getById($id);
        $this->db->insert('products', array(
            'order' => $this->order,
            'offer_category_id' => $this->offer_category_id,
            'type' => $this->type,
            'price' => $this->price,
            'weight' => $this->weight,
            'options' => $this->options,
            'new' => $this->new,
            'promo' => $this->promo,
            'quantity' => $this->quantity,
            'bestseller' => $this->bestseller,
            'code' => $this->code,
            'active' => $this->active,
             'availability' => $this->availability,
            'external_id' => $this->external_id,
            'vat' => $this->vat,
            'number_in_package' => $this->number_in_package,
            'always_avaible' => $this->always_avaible,
            'ean' => $this->ean,
            'producent' => $this->producent,
            'slider' => $this->slider,
        ));
        $new_id = $this->db->insert_id();
        //kopia translacji
        $q = $this->db->get_where('products_translations', array('product_id' => $id));
        $res = $q->result();
        if(!empty($res)){
            foreach($res as $r){
                $this->db->insert('products_translations', array(
                    'product_id' => $new_id,
                    'lang' => $r->lang,
                    'name' => $r->name,
                    'format' => $r->format,
                    'slogan' => $r->slogan,
                    'body' => $r->body
                ));
            }
        }
        //kopia opcji
        $q2 = $this->db->get_where('shop_options', array('product_id' => $id));
        $res2 = $q2->result();
        if(!empty($res2)){
            foreach($res2 as $r2) {
                $this->db->insert('shop_options', array(
                    'product_id' => $new_id,
                    'name' => $r2->name,
                    'description' => $r2->description,
                    'weight' => $r2->weight,
                    'price_change' => $r2->price_change,
                    'old_price' => $r2->old_price,
                    'quantity' => $r2->quantity,
                    'quantity_left' => $r2->quantity_left,
                    'visibility' => $r2->visibility
                ));
            }
        }
        //kopia atrybutów
        $q3 = $this->db->get_where('shop_attributes_relations', array('product_id' => $id));
        $res3 = $q3->result();
        if(!empty($res3)){
            foreach($res3 as $r3){
                $this->db->insert('shop_attributes_relations', array(
                    'attribute_id' => $r3->attribute_id,
                    'product_id' => $new_id,
                    'value' => $r3->value
                ));
            }
        }
        //kopiowanie galerii
        /**** Pojawił się problem ponieważ każde id zdjęcia jest w oddzielnym katalogu **/
        /*$old_path = './uploads/products/'.$id;
        $new_path = './uploads/products/'.$new_id;
        $this->recurse_copy($old_path, $new_path);
        
        $q4 = $this->db->get_where('product_photos', array('product_id' => $id));
        $res4 = $q4->result();
        if(!empty($res4)){
            foreach($res4 as $r4){
                $this->db->insert('product_photos', array(
                   'product_id' => $new_id,
                    'order' => $r4->order,
                    'name' => $r4->name
                ));
            }
        }*/
        
             $q5 = $this->db->get_where('product_prices', array('product_id' => $id));
        $res5 = $q5->result();
        if(!empty($res5)){
        foreach($res5 as $r5){
            $cdata = array(
                    'product_id' => $new_id,
                    'currency_id' => $r5->currency_id,
                    'price' => $r5->price
                );
                $this->db->insert('duo_product_prices', $cdata);
        }
        }
        
        $groups = $this->get_details_groups();
        foreach($groups as $group){
            $this->db->insert('duo_product_details_groups',[
            'product_id' => $new_id,
            'name' => $group->name
        ]);
            $n_group_id = $this->db->insert_id();
            foreach($group->details as $detail){
                $this->db->insert('duo_product_details',[
            'price' => $detail->price,
            'name' => $detail->name,
            'group_id' => $n_group_id,
            'val' => $detail->val
        ]);
            }
        }
        if(!empty($this->categories)){
            foreach($this->categories as $cats){
                $this->db->insert('products_categories',[
                    'product_id' => $new_id,
                    'category_id' => $cats,
                    'created_at' => (new DateTime())->format('Y-m-d H:i:s')
                ]);
            }
        }
        return $new_id;
    }
    
    function recurse_copy($src,$dst) { 
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    } 

    public function add_option($args) {
        $res = $this->db->insert('duo_shop_options', $args);
        $this->getById($args['product_id']);
        if ($res) {
            $quantity = $this->quantity == -1 ? $args['quantity'] : $args['quantity'] + $this->quantity;
            $this->db->where('id', $args['product_id'])->update('products', array('options' => 1, 'quantity' => $quantity));
        }
        return $res;
    }
    
    public function edit_option($args){
        $res = $this->db->where('id',$args['id'])->update('duo_shop_options',$args);
        return $res;
    }

    public function get_options($product_id) {
        $res = $this->db->get_where('duo_shop_options', array('product_id' => $product_id, 'visibility' => 1))->result('ProductOptionModel');
        return $res;
    }
    
    public function get_options_admin($product_id) {
        $res = $this->db->get_where('duo_shop_options', array('product_id' => $product_id))->result();
        return $res;
    }

    public function delete_option($option_id, $product_id) {
        $option = $this->select_option($option_id);
        $this->getById($product_id);
        //zmniejszyć ilość produktu o tę opcję jeśli jest go wystarczająco
        if ($option['quantity_left'] <= $this->quantity) {
            $this->db->where('id', $product_id)->update('products', array('quantity' => $this->quantity - $option['quantity_left']));
        }
        $this->db->delete('duo_shop_options', array('id' => $option_id));
        $res = $this->db->get_where('duo_shop_options', array('product_id' => $product_id))->result();
        if (empty($res)) {
            $this->db->where('id', $product_id)->update('products', array('options' => 0));
        } else {
            $this->db->where('id', $product_id)->update('products', array('options' => 1));
        }
        return TRUE;
    }
    
    public function delete_attribute($attribute_id, $product_id){
        $res = $this->db->delete('duo_shop_attributes_relations', array('attribute_id' => $attribute_id, 'product_id' => $product_id));
        return $res;
    }

    public function getById($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('products');

        foreach ($query->result() as $row) {
            $this->id = $row->id;
            $this->order = $row->order;
            $this->offer_category_id = $row->offer_category_id;
            $this->type = $row->type;
//            $this->price = $row->price;
            $this->price = $this->getPrice();
            $this->weight = $row->weight;
            $this->options = $row->options;
            $this->new = $row->new;
            $this->promo = $row->promo;
            $this->bestseller = $row->bestseller;
            $this->producent = $row->producent;
            $this->code = $row->code;
            $this->quantity = $row->quantity;
            $this->active = $row->active;
            $this->amount_updated_at = $row->amount_updated_at;
            $this->created_at = $row->created_at;
            $this->updated_at = $row->updated_at;
            $this->availability = $row->availability;
            $this->external_id = $row->external_id;
            $this->vat = $row->vat;
            $this->number_in_package = $row->number_in_package;
            $this->always_avaible = $row->always_avaible;
            $this->ean = $row->ean;
            $this->tags = $row->tags;
            $this->slider = $row->slider;
        }
        $this->db->where('product_id', $id);
        $query2 = $this->db->get('products_categories')->result();
        if(!empty($query2)){
            foreach($query2 as $r2){
                $this->categories[] = $r2->category_id;
            }
        }
        
    }
    public function change_status($id){
        $q = $this->db->get_where($this->_table_name, array('id' => $id));
        $r = $q->row();
        $active = $r->active;
        $this->db->where('id',$id)->update($this->_table_name, array('active' => !$active));   
        return $active;
    }

    public function findAll() {
		$this->db->where_in('active', '0');
        $this->db->order_by('order', 'asc');
        $query = $this->db->get('products');
        return $query->result('ProductModel');
    }
    
    /*dodane*/
    public function findAllCategory($id) {
        $this->db->where('offer_category_id', $id);
        $query = $this->db->get('products');
        return $query->result('ProductModel');
    }
    
    public function delete() {
        $this->db->where('product_id', $this->id);
        $this->db->delete('products_translations');
        $this->db->where('id', $this->id);
        $res = $this->db->delete('products');

        if ($res) {
            $this->load->helper('file');

            $dir = FCPATH . 'uploads/products/' . $this->id;
            delete_files($dir, true);
            rmdir($dir);
        }

        return $res;
    }

    public function findAllPhotos() {
        $this->load->model('ProductPhotoModel');
        return (new ProductPhotoModel())->findAllByProduct($this);
    }

    public function findAllForCmsList($admin = 0, $limit= null, $offset = null) {
        $this->db->select('products.*, products_translations.name, products_translations.format, products_translations.slogan ');
        $this->db->from('products');
        $this->db->join('products_translations', 'products.id = products_translations.product_id');
        //$this->db->join('products_categories', 'products_categories.product_id = products.id');
//        $this->db->join(
//                'offer_categories_translations','offer_categories_translations.offer_category_id = products_categories.category_id', 'LEFT'
//        );
        $this->db->group_by('products_translations.product_id');
        $this->db->where('products_translations.lang', LANG);
//        $this->db->where("(duo_offer_categories_translations.lang = '". LANG . "' OR duo_offer_categories_translations.lang IS NULL)");
        if(empty($admin)){
            $this->db->where("active",0);
        }
        if(!empty($limit) ){
            $this->db->limit($limit, $offset);
        }
        //$this->db->order_by('category_name', 'asc');
        $this->db->order_by("order", "ASC");
        $this->db->order_by('products_translations.name', 'asc');
        return $this->db->get()->result('ProductModel');
    }
    
        public function findAllForCmsListCount($admin = 0) {
        $this->db->select('products.*, products_translations.name, products_translations.format, products_translations.slogan,');
        $this->db->from('products');
        $this->db->join('products_translations', 'products.id = products_translations.product_id');
        
//        $this->db->join(
//                'offer_categories_translations', 'products.offer_category_id = offer_categories_translations.offer_category_id', 'LEFT'
//        );
        $this->db->where('products_translations.lang', LANG);
//        $this->db->where("(duo_offer_categories_translations.lang = '". LANG . "' OR duo_offer_categories_translations.lang IS NULL)");
        if(empty($admin)){
            $this->db->where("active",0);
        }
        //$this->db->order_by('category_name', 'asc');
        $this->db->order_by("order", "ASC");
        $this->db->order_by('products_translations.name', 'asc');
        return $this->db->get()->num_rows();
    }
    
    public function findAllSoldProducts($limit = 999){
        $this->db->select('products.*, products_translations.name, products_translations.format, products_translations.slogan, offer_categories_translations.name AS category_name');
        $this->db->from('products');
        $this->db->join('products_translations', 'products.id = products_translations.product_id');
        $this->db->join(
                'offer_categories_translations', 'products.offer_category_id = offer_categories_translations.offer_category_id', 'LEFT'
        );
        $this->db->where('products_translations.lang', LANG);
        $this->db->where("(duo_offer_categories_translations.lang = '". LANG . "' OR duo_offer_categories_translations.lang IS NULL)");
        $this->db->where('products.sold >',0);
        $this->db->order_by('sold', 'desc');
        $this->db->limit($limit);
        return $this->db->get()->result('ProductModel');
    }

    public function findAllNewProducts($limit = null){
        $this->db->where('products.new',1);
        if(!empty($limit)){
            $this->db->limit($limit);
        }
        return $this->findAllForCmsList();
    }
        public function findAllNewProducts2($limit = null){
        $producent_attr_id = (new CustomElementModel('19'))->getField('id kategori atrybutu producenta');
        $this->db->select("duo_products.*");
                $this->db->join('duo_shop_attributes_groups','duo_shop_attributes_groups.id = duo_shop_attributes.attributes_group_id');
                $this->db->join('duo_shop_attributes_translations','duo_shop_attributes_translations.attribute_id = duo_shop_attributes.id');
                $this->db->join('duo_shop_attributes_relations' ,'duo_shop_attributes_relations.attribute_id = duo_shop_attributes.id');
                $this->db->join('duo_products', 'duo_products.id = duo_shop_attributes_relations.product_id');
                $this->db->distinct();
                $this->db->where('duo_products.new = 1');
                $this->db->where('duo_shop_attributes.attributes_group_id = '. $producent_attr_id. ' AND lang = "' . LANG .'"');
                   if(!empty($limit)){
            $this->db->limit($limit);
        }
                return $this->db->get('duo_products')->result("ProductModel");
    }
    
    public function findAllPromoProducts2($limit = null){
//        $producent_attr_id = (new CustomElementModel('19'))->getField('id kategori atrybutu producenta');
        $this->db->select("duo_products.*");
//                $this->db->join('duo_shop_attributes_groups','duo_shop_attributes_groups.id = duo_shop_attributes.attributes_group_id');
//                $this->db->join('duo_shop_attributes_translations','duo_shop_attributes_translations.attribute_id = duo_shop_attributes.id');
//                $this->db->join('duo_shop_attributes_relations' ,'duo_shop_attributes_relations.attribute_id = duo_shop_attributes.id');
//                $this->db->join('duo_products', 'duo_products.id = duo_shop_attributes_relations.product_id');
                $this->db->distinct();
                $this->db->where('duo_products.promo = 1');
//                $this->db->where(' lang = "' . LANG .'"');
                   if(!empty($limit)){
            $this->db->limit($limit);
        }
                return $this->db->get('duo_products')->result("ProductModel");
    }
    
    public function findAllBestsellerProducts2($limit = null){
      //        $producent_attr_id = (new CustomElementModel('19'))->getField('id kategori atrybutu producenta');
        $this->db->select("duo_products.*");
//                $this->db->join('duo_shop_attributes_groups','duo_shop_attributes_groups.id = duo_shop_attributes.attributes_group_id');
//                $this->db->join('duo_shop_attributes_translations','duo_shop_attributes_translations.attribute_id = duo_shop_attributes.id');
//                $this->db->join('duo_shop_attributes_relations' ,'duo_shop_attributes_relations.attribute_id = duo_shop_attributes.id');
//                $this->db->join('duo_products', 'duo_products.id = duo_shop_attributes_relations.product_id');
                $this->db->distinct();
                $this->db->where('duo_products.bestseller = 1');
//                $this->db->where(' lang = "' . LANG .'"');
                   if(!empty($limit)){
            $this->db->limit($limit);
        }
                return $this->db->get('duo_products')->result("ProductModel");
    }
    
    public function findAllPromoProducts($limit = null){
        //$this->db->where('products.promo',1);
        if(!empty($limit)){
            $this->db->limit($limit);
        }
        return $this->findAllForCmsList();
    }
    public function findAllBestsellerProducts($limit = null){
        $this->db->where('products.bestseller',1);
        if(!empty($limit)){
            $this->db->limit($limit);
        }
        return $this->findAllForCmsList();
    }

    public function findAllByCategory(ProductCategoryModel $category = null) {
        $this->db->where('offer_category_id', $category->id)->order_by("order", "ASC");
        return $this->findAll();
    }

    public function findAllByCategoryPaging(ProductCategoryModel $category = null, $page = 1) {
        $this->db->where('offer_category_id', $category->id)->order_by("order", "ASC")->limit(100, (($page - 1) * 10));
        return $this->findAll();
    }
//    
//    public function search_by_string($str = '', $lang = 'pl'){
//        if(!empty($str)){
//            $this->db->join('duo_products', 'duo_products.id = duo_products_translations.product_id');
//            $str = str_replace('xspacex', ' ', $str);
//            $str_array = explode(' ', $str);
//            $this->db->where(' REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(duo_products_translations.name,"ł","l"),"ą","a"),"ę","e"),"ż","z"),"ś","s"),"ó","o"),"ń","n") LIKE "%'.$str.'%"');
//            if(count($str_array) == 2){
//                $str = $str_array[1] . ' ' . $str_array[0];
//                $this->db->or_where(' REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(duo_products_translations.name,"ł","l"),"ą","a"),"ę","e"),"ż","z"),"ś","s"),"ó","o"),"ń","n") LIKE "%'.$str.'%"');
//            }
//            $this->db->or_where(' REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(duo_products.code,"ł","l"),"ą","a"),"ę","e"),"ż","z"),"ś","s"),"ó","o"),"ń","n") LIKE "%'.$str.'%"');
//            $this->db->limit(10);
//            $q = $this->db->get_where('duo_products_translations', array('lang' => $lang));
//            return $q->result();
//        } else {
//            return array();
//        }
//    }
      public function search_by_string($str = '', $lang = 'pl'){
        if(!empty($str)){
            $this->db->join('duo_products', 'duo_products.id = duo_products_translations.product_id');
            $str = strtolower($str);
//            $str = str_replace('xspacex', ' ', $str);
            $str_array = explode(' ', $str);
            $this->db->where('duo_products.active', 0);
            $this->db->where(' (duo_products.quantity > 0 OR duo_products.always_avaible = 1) ');
            $or_string = ' (LOWER(duo_products_translations.name) LIKE "%'.$str.'%" ';
            if(count($str_array) == 2){
                $str2 = $str_array[1] . '%' . $str_array[0];
                $str3 = $str_array[0] . '%' . $str_array[1];
                $or_string .= ' OR LOWER(duo_products_translations.name) LIKE "%'.$str2.'%" ';
                $or_string .= ' OR LOWER(duo_products.tags) LIKE "%'.$str2.'%" ';
                $or_string .= ' OR LOWER(duo_products_translations.name) LIKE "%'.$str3.'%" ';
                $or_string .= ' OR LOWER(duo_products.tags) LIKE "%'.$str3.'%" ';
            }
            $or_string .= ' OR LOWER(duo_products.code) LIKE "%'.$str.'%" ';
            $or_string .= ' OR LOWER(duo_products.tags) LIKE "%'.$str.'%" )';
           
            $this->db->where($or_string);
            $this->db->limit(5);
            
            $q = $this->db->get_where('duo_products_translations', array('lang' => $lang));
            return $q->result();
        } else {
            return array();
        }
    }
    //wyszukiwarka produktów
    public function search($categories = array(), $attributes = array(), $str = '', $page = 1, $limit = 24, $sort = null, $min=null, $max= null,$promo=null,$avaible = null, $bestseller = null){
        $q_str = "SELECT duo_products.* "
                . " FROM duo_products";
        $q_str .= " JOIN duo_products_translations ON duo_products_translations.product_id = duo_products.id ";
        $q_str .= " JOIN duo_products_categories ON duo_products_categories.product_id = duo_products.id ";
        $q_str .= " LEFT JOIN duo_product_prices ON duo_product_prices.product_id = duo_products.id ";
        $q_str .= " JOIN duo_offer_categories_translations ON duo_products_categories.category_id = duo_offer_categories_translations.offer_category_id ";
        $q_str .= " LEFT JOIN duo_shop_attributes_relations ON duo_shop_attributes_relations.product_id = duo_products.id "
                . " LEFT JOIN duo_shop_attributes ON duo_shop_attributes.id = duo_shop_attributes_relations.attribute_id ";
        $q_str .= " WHERE duo_products_translations.lang = '".LANG."' AND duo_offer_categories_translations.lang = '". LANG . "'";
        $q_str .= " AND active = 0  ";
        if(!empty($promo)){
            $q_str .= " AND duo_products.promo = 1 ";
        }
        if(!empty($avaible)){
            $q_str .= " AND duo_products.quantity > 0 ";
        }
        if(!empty($bestseller)){
            $q_str .= " AND duo_products.bestseller = 1 ";
        }
        
//        else {
//        if(!empty($min)){
//            $q_str .= " AND duo_product_prices.price >= '". $min. "'";
//        }
//        if(!empty($max)){
//            $q_str .= " AND duo_product_prices.price <= '". $max. "'";
//        }
//        }
        if(!empty($str)){
            $str = strtolower($str);
            $str_array = explode(" ", strtolower($str));
            if(count($str_array) == 2){
                $str1 = $str;
                $str = $str_array[1] . ' ' . $str_array[0];
                $q_str .= " AND (LOWER(duo_products_translations.name) LIKE '%".$str."%' OR LOWER(duo_products_translations.name) LIKE '%".$str1."%' OR LOWER(duo_products.code) LIKE '%".$str."%' "
                        . "OR LOWER(duo_products_translations.name) LIKE '%".$str_array[1]."%".$str_array[0]."%'  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[0]."%".$str_array[1]."%'"
                          . "OR LOWER(duo_products.tags) LIKE '%".$str_array[1]."%".$str_array[0]."%'  OR LOWER(duo_products.tags) LIKE '%".$str_array[0]."%".$str_array[1]."%'"
                        
                        . " ) ";
            } 
            else if(count($str_array) == 3){
                $q_str .= " AND (LOWER(duo_products_translations.name) LIKE '%".$str."%' OR duo_products.code LIKE '%".$str."%' "
                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[0]."%".$str_array[1]."%".$str_array[2]."%'"
                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[2]."%".$str_array[1]."%".$str_array[0]."%'"
                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[1]."%".$str_array[0]."%".$str_array[2]."%'"
                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[2]."%".$str_array[0]."%".$str_array[1]."%'"
                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[1]."%".$str_array[2]."%".$str_array[0]."%'"
                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[0]."%".$str_array[2]."%".$str_array[1]."%'"
                        
                        ." OR LOWER(duo_products.tags) LIKE '%".$str."%' "
                         . "  OR LOWER(duo_products.tags) LIKE '%".$str_array[0]."%".$str_array[1]."%".$str_array[2]."%'"
                         . "  OR LOWER(duo_products.tags) LIKE '%".$str_array[2]."%".$str_array[1]."%".$str_array[0]."%'"
                         . "  OR LOWER(duo_products.tags) LIKE '%".$str_array[1]."%".$str_array[0]."%".$str_array[2]."%'"
                         . "  OR LOWER(duo_products.tags) LIKE '%".$str_array[2]."%".$str_array[0]."%".$str_array[1]."%'"
                         . "  OR LOWER(duo_products.tags) LIKE '%".$str_array[1]."%".$str_array[2]."%".$str_array[0]."%'"
                         . "  OR LOWER(duo_products.tags) LIKE '%".$str_array[0]."%".$str_array[2]."%".$str_array[1]."%'"
                        
                        . " ) ";
            }
            else{
                $q_str .= " AND (LOWER(duo_products_translations.name) LIKE '%".$str."%' OR LOWER(duo_products.code) LIKE '%".$str."%' OR LOWER(duo_products.tags) LIKE '%".$str."%')";
            }
        }
        if(!empty($categories)){
            $q_str .= " AND (";
            $i = 0;
            foreach($categories as $cat_id){               
                if($i != 0){
                    $q_str .= " OR ";
                } else {
                    $i = 1;
                }
                $q_str .= "duo_products_categories.category_id = '".$cat_id."'";
                
            }
            $q_str .= ") ";
        } 
     
        if(!empty($min) && !empty($max)){
            $q_str .= "AND duo_product_prices.price BETWEEN '". $min."' AND '". $max."' ";
        }
        
        
        
        $q_str .= " GROUP BY duo_products.id ";
        
        if(!empty($attributes)){
            $q_str .= "HAVING COUNT(duo_shop_attributes.id) > 0 ";
            //atrybuty wyszukują się grupami
            $attr_obj = new ProductAttributesModel();
            $groups = $attr_obj->get_groups();
            $groups_array = array();
            if(!empty($groups)){
                foreach ($groups as $group){
                    $groups_array[] = array(
                        'group' => $group,
                        'attributes' => $this->attr_obj->get_attributes_by_group($group->attributes_group_id)
                    );
                }
            }
            $tmp_groups = array();
            if(!empty($groups_array)){
               foreach($groups_array as $key1=>$group){
                     if(!empty($group['attributes'])){
                        foreach ($group['attributes'] as $key2=>$attr) {
                            if (in_array($attr->id, $attributes)) {
                                $tmp_groups[$key1]['attributes'][] = $attr->id;
                            }
                        }
                    }
                }
            }
            if(!empty($tmp_groups)){
                foreach ($tmp_groups as $tg){
                    $str5 = '( ';
                    for($j = 0; $j<count($tg['attributes']); $j++){
                        $str5 .=  $tg['attributes'][$j];
                        if( $j == count($tg['attributes'])-1){
                            $str5 .= ' )';
                        } else {
                            $str5 .= ', ';
                        }
                    }
                    $q_str .= " AND sum(case when duo_shop_attributes.id in ". $str5 ." then 1 else 0 end) > 0";
                }
            }
        }
        
        
        if(!empty($sort)){
            switch ($sort) {
                case 'cena_rosnaco':
                    $q_str .= " ORDER BY duo_product_prices.price ASC ";
                    break;
                case 'cena_malejaco':
                    $q_str .= " ORDER BY duo_product_prices.price DESC ";
                    break;
                 case 'alfabetycznie':
                    $q_str .= " ORDER BY duo_products_translations.name ASC ";
                    break;
                 case 'odwrotnie_alfabetycznie':
                    $q_str .= " ORDER BY duo_products_translations.name DESC ";
                    break;
                default:
                    break;
            }
        }else {
          $q_str .= " ORDER BY `order` ASC";
        }
        $q_str .= " LIMIT ".$limit." OFFSET ".$limit*$page;
//                    var_dump($q_str);die();
       // die($q_str);
        $q = $this->db->query($q_str)->result('ProductModel');
        //var_dump($this->db->last_query()); die();
        return $q;
        
    }
    
        public function search_count($categories = array(), $attributes = array(), $str = '', $min = null, $max = null, $promo = null, $avaible = null, $bestseller = null ){
//        $q_str = "SELECT duo_products.* "
//                . " FROM duo_products";
//        $q_str .= " JOIN duo_products_translations ON duo_products_translations.product_id = duo_products.id ";
//        $q_str .= " JOIN duo_product_prices ON duo_product_prices.product_id = duo_products.id ";
//        $q_str .= " JOIN duo_products_categories ON duo_products_categories.product_id = duo_products.id ";
//        $q_str .= " JOIN duo_offer_categories_translations ON duo_products_categories.category_id = duo_offer_categories_translations.offer_category_id ";
//        $q_str .= " LEFT JOIN duo_shop_attributes_relations ON duo_shop_attributes_relations.product_id = duo_products.id "
//                . " LEFT JOIN duo_shop_attributes ON duo_shop_attributes.id = duo_shop_attributes_relations.attribute_id ";
//        $q_str .= " WHERE duo_products_translations.lang = '".LANG."' AND duo_offer_categories_translations.lang = '". LANG . "'";
//        $q_str .= " AND active = 0 ";
//        
//        
//        
//
////        if(!empty($min)){
////            $q_str .= " AND duo_product_prices.price >= '". $min. "'";
////        }
////        if(!empty($max)){
////            $q_str .= " AND duo_product_prices.price <= '". $max. "'";
////        }
//                if(!empty($str)){
//            $str_array = explode(" ", strtolower($str));
//            if(count($str_array) == 2){
//                $str1 = $str;
//                $str = $str_array[1] . ' ' . $str_array[0];
//                $q_str .= " AND (duo_products_translations.name LIKE '%".$str."%' OR duo_products_translations.name LIKE '%".$str1."%' OR duo_products.code LIKE '%".$str."%' "
//                        . "OR duo_products_translations.name LIKE '%".$str_array[1]."%".$str_array[0]."%'  OR duo_products_translations.name LIKE '%".$str_array[0]."%".$str_array[1]."%' ) ";
//            } 
//            else if(count($str_array) == 3){
//                $q_str .= " AND (LOWER(duo_products_translations.name) LIKE '%".$str."%' OR duo_products.code LIKE '%".$str."%' "
//                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[0]."%".$str_array[1]."%".$str_array[2]."%'"
//                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[2]."%".$str_array[1]."%".$str_array[0]."%'"
//                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[1]."%".$str_array[0]."%".$str_array[2]."%'"
//                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[2]."%".$str_array[0]."%".$str_array[1]."%'"
//                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[1]."%".$str_array[2]."%".$str_array[0]."%'"
//                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[0]."%".$str_array[2]."%".$str_array[1]."%'"
//                        
//                        . " ) ";
//            }
//            else{
//                $q_str .= " AND (duo_products_translations.name LIKE '%".$str."%' OR duo_products.code LIKE '%".$str."%')";
//            }
//        }
//        if(!empty($categories)){
//            $q_str .= " AND (";
//            $i = 0;
//            foreach($categories as $cat_id){
//                if($i != 0){
//                    $q_str .= " OR ";
//                } else {
//                    $i = 1;
//                }
//                $q_str .= "duo_products.offer_category_id = '".$cat_id."'";
//                
//            }
//            $q_str .= ") ";
//        } else {
//            return 0;
//        }
//        
//                if(!empty($min) && !empty($max)){
//            $q_str .= "AND duo_product_prices.price BETWEEN '". $min."' AND '". $max."' ";
//        }
//        
//        $q_str .= " GROUP BY duo_products.id ";
//       
//        if(!empty($attributes)){
//             $q_str .= "HAVING COUNT(duo_shop_attributes.id) > 0 ";
//            //atrybuty wyszukują się grupami
//            $attr_obj = new ProductAttributesModel();
//            $groups = $attr_obj->get_groups();
//            $groups_array = array();
//            if(!empty($groups)){
//                foreach ($groups as $group){
//                    $groups_array[] = array(
//                        'group' => $group,
//                        'attributes' => $this->attr_obj->get_attributes_by_group($group->attributes_group_id)
//                    );
//                }
//            }
//            $tmp_groups = array();
//            if(!empty($groups_array)){
//               foreach($groups_array as $key1=>$group){
//                     if(!empty($group['attributes'])){
//                        foreach ($group['attributes'] as $key2=>$attr) {
//                            if (in_array($attr->id, $attributes)) {
//                                $tmp_groups[$key1]['attributes'][] = $attr->id;
//                            }
//                        }
//                    }
//                }
//            }
//            if(!empty($tmp_groups)){
//                foreach ($tmp_groups as $tg){
//                    $str5 = '( ';
//                    for($j = 0; $j<count($tg['attributes']); $j++){
//                        $str5 .=  $tg['attributes'][$j];
//                        if( $j == count($tg['attributes'])-1){
//                            $str5 .= ' )';
//                        } else {
//                            $str5 .= ', ';
//                        }
//                    }
//                    $q_str .= " AND sum(case when duo_shop_attributes.id in ". $str5 ." then 1 else 0 end) > 0";
//                }
//            }
//        }
//        
//        
//        
//        //  $q_str .= " ORDER BY `order` ASC LIMIT 10000";
//        $q = $this->db->query($q_str)->num_rows();
//        return $q;
//        
        $q_str = "SELECT duo_products.* "
                . " FROM duo_products";
        $q_str .= " JOIN duo_products_translations ON duo_products_translations.product_id = duo_products.id ";
        $q_str .= " JOIN duo_products_categories ON duo_products_categories.product_id = duo_products.id ";
        //$q_str .= " JOIN duo_product_prices ON duo_product_prices.product_id = duo_products.id ";
        $q_str .= " JOIN duo_offer_categories_translations ON duo_products_categories.category_id = duo_offer_categories_translations.offer_category_id ";
        $q_str .= " LEFT JOIN duo_shop_attributes_relations ON duo_shop_attributes_relations.product_id = duo_products.id "
                . " LEFT JOIN duo_shop_attributes ON duo_shop_attributes.id = duo_shop_attributes_relations.attribute_id ";
        $q_str .= " WHERE duo_products_translations.lang = '".LANG."' AND duo_offer_categories_translations.lang = '". LANG . "'";
        $q_str .= " AND active = 0  ";
        if(!empty($promo)){
            $q_str .= " AND duo_products.promo = 1 ";
        }
        if(!empty($avaible)){
            $q_str .= " AND duo_products.quantity > 0 ";
        }
         if(!empty($bestseller)){
            $q_str .= " AND duo_products.bestseller = 1 ";
        }
//        else {
//        if(!empty($min)){
//            $q_str .= " AND duo_product_prices.price >= '". $min. "'";
//        }
//        if(!empty($max)){
//            $q_str .= " AND duo_product_prices.price <= '". $max. "'";
//        }
//        }
      if(!empty($str)){
            $str_array = explode(" ", strtolower($str));
            if(count($str_array) == 2){
                $str1 = $str;
                $str = $str_array[1] . ' ' . $str_array[0];
                $q_str .= " AND (duo_products_translations.name LIKE '%".$str."%' OR duo_products_translations.name LIKE '%".$str1."%' OR duo_products.code LIKE '%".$str."%' "
                        . "OR duo_products_translations.name LIKE '%".$str_array[1]."%".$str_array[0]."%'  OR duo_products_translations.name LIKE '%".$str_array[0]."%".$str_array[1]."%'"
                          . "OR duo_products.tags LIKE '%".$str_array[1]."%".$str_array[0]."%'  OR duo_products.tags LIKE '%".$str_array[0]."%".$str_array[1]."%'"
                        
                        . " ) ";
            } 
            else if(count($str_array) == 3){
                $q_str .= " AND (LOWER(duo_products_translations.name) LIKE '%".$str."%' OR duo_products.code LIKE '%".$str."%' "
                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[0]."%".$str_array[1]."%".$str_array[2]."%'"
                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[2]."%".$str_array[1]."%".$str_array[0]."%'"
                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[1]."%".$str_array[0]."%".$str_array[2]."%'"
                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[2]."%".$str_array[0]."%".$str_array[1]."%'"
                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[1]."%".$str_array[2]."%".$str_array[0]."%'"
                         . "  OR LOWER(duo_products_translations.name) LIKE '%".$str_array[0]."%".$str_array[2]."%".$str_array[1]."%'"
                        
                        ." OR LOWER(duo_products.tags) LIKE '%".$str."%' "
                         . "  OR LOWER(duo_products.tags) LIKE '%".$str_array[0]."%".$str_array[1]."%".$str_array[2]."%'"
                         . "  OR LOWER(duo_products.tags) LIKE '%".$str_array[2]."%".$str_array[1]."%".$str_array[0]."%'"
                         . "  OR LOWER(duo_products.tags) LIKE '%".$str_array[1]."%".$str_array[0]."%".$str_array[2]."%'"
                         . "  OR LOWER(duo_products.tags) LIKE '%".$str_array[2]."%".$str_array[0]."%".$str_array[1]."%'"
                         . "  OR LOWER(duo_products.tags) LIKE '%".$str_array[1]."%".$str_array[2]."%".$str_array[0]."%'"
                         . "  OR LOWER(duo_products.tags) LIKE '%".$str_array[0]."%".$str_array[2]."%".$str_array[1]."%'"
                        
                        . " ) ";
            }
            else{
                $q_str .= " AND (duo_products_translations.name LIKE '%".$str."%' OR duo_products.code LIKE '%".$str."%' OR duo_products.tags LIKE '%".$str."%')";
            }
        }
        if(!empty($categories)){
            $q_str .= " AND (";
            $i = 0;
            foreach($categories as $cat_id){               
                if($i != 0){
                    $q_str .= " OR ";
                } else {
                    $i = 1;
                }
                $q_str .= "duo_products_categories.category_id = '".$cat_id."'";
                
            }
            $q_str .= ") ";
        } 
     
        if(!empty($min) && !empty($max)){
            $q_str .= "AND duo_product_prices.price BETWEEN '". $min."' AND '". $max."' ";
        }
        
        
        
        $q_str .= " GROUP BY duo_products.id ";
        
        if(!empty($attributes)){
            $q_str .= "HAVING COUNT(duo_shop_attributes.id) > 0 ";
            //atrybuty wyszukują się grupami
            $attr_obj = new ProductAttributesModel();
            $groups = $attr_obj->get_groups();
            $groups_array = array();
            if(!empty($groups)){
                foreach ($groups as $group){
                    $groups_array[] = array(
                        'group' => $group,
                        'attributes' => $this->attr_obj->get_attributes_by_group($group->attributes_group_id)
                    );
                }
            }
            $tmp_groups = array();
            if(!empty($groups_array)){
               foreach($groups_array as $key1=>$group){
                     if(!empty($group['attributes'])){
                        foreach ($group['attributes'] as $key2=>$attr) {
                            if (in_array($attr->id, $attributes)) {
                                $tmp_groups[$key1]['attributes'][] = $attr->id;
                            }
                        }
                    }
                }
            }
            if(!empty($tmp_groups)){
                foreach ($tmp_groups as $tg){
                    $str5 = '( ';
                    for($j = 0; $j<count($tg['attributes']); $j++){
                        $str5 .=  $tg['attributes'][$j];
                        if( $j == count($tg['attributes'])-1){
                            $str5 .= ' )';
                        } else {
                            $str5 .= ', ';
                        }
                    }
                    $q_str .= " AND sum(case when duo_shop_attributes.id in ". $str5 ." then 1 else 0 end) > 0";
                }
            }
        }
        

        $q = $this->db->query($q_str)->num_rows();
        return $q;
        
    }
public function search3($categories = array(), $attributes = array(), $str = '', $page = 1, $limit = 24, $sort = null){
        $q_str = "SELECT duo_products.id "
                . " FROM duo_products";
        $q_str .= " JOIN duo_products_translations ON duo_products_translations.product_id = duo_products.id ";
        $q_str .= " JOIN duo_products_categories ON duo_products.id = duo_products_categories.product_id ";
        $q_str .= " LEFT JOIN duo_shop_attributes_relations ON duo_shop_attributes_relations.product_id = duo_products.id "
                . " LEFT JOIN duo_shop_attributes ON duo_shop_attributes.id = duo_shop_attributes_relations.attribute_id ";
        $q_str .= " WHERE duo_products_translations.lang = '".LANG."'";
        $q_str .= " AND active = 0 ";
        
        if(!empty($str)){
            $str_array = explode(" ", $str);
            if(count($str_array) == 2){
                $str1 = $str;
                $str = $str_array[1] . ' ' . $str_array[0];
                $q_str .= " AND (duo_products_translations.name LIKE '%".$str."%' OR duo_products_translations.name LIKE '%".$str1."%' OR duo_products.code LIKE '%".$str."%') ";
            } else {
                $q_str .= " AND (duo_products_translations.name LIKE '%".$str."%' OR duo_products.code LIKE '%".$str."%')";
            }
        }
        if(!empty($categories)){
            $q_str .= " AND (";
            $i = 0;
            foreach($categories as $cat_id){
                if($i != 0){
                    $q_str .= " OR ";
                } else {
                    $i = 1;
                }
                $q_str .= "duo_products_categories.category_id = '".$cat_id."'";
                
            }
            $q_str .= ") ";
        }
        
        $q_str .= " GROUP BY duo_products.id ";
//        $q_str .= "HAVING COUNT(duo_shop_attributes.id) > 0 ";
//        if(!empty($attributes)){
//            //atrybuty wyszukują się grupami
//            $attr_obj = new ProductAttributesModel();
//            $groups = $attr_obj->get_groups();
//            $groups_array = array();
//            if(!empty($groups)){
//                foreach ($groups as $group){
//                    $groups_array[] = array(
//                        'group' => $group,
//                        'attributes' => $this->attr_obj->get_attributes_by_group($group->attributes_group_id)
//                    );
//                }
//            }
//            $tmp_groups = array();
//            if(!empty($groups_array)){
//               foreach($groups_array as $key1=>$group){
//                     if(!empty($group['attributes'])){
//                        foreach ($group['attributes'] as $key2=>$attr) {
//                            if (in_array($attr->id, $attributes)) {
//                                $tmp_groups[$key1]['attributes'][] = $attr->id;
//                            }
//                        }
//                    }
//                }
//            }
//            if(!empty($tmp_groups)){
//                foreach ($tmp_groups as $tg){
//                    $str5 = '( ';
//                    for($j = 0; $j<count($tg['attributes']); $j++){
//                        $str5 .=  $tg['attributes'][$j];
//                        if( $j == count($tg['attributes'])-1){
//                            $str5 .= ' )';
//                        } else {
//                            $str5 .= ', ';
//                        }
//                    }
//                    $q_str .= " AND sum(case when duo_shop_attributes.id in ". $str5 ." then 1 else 0 end) > 0";
//                }
//            }
//        }
        
        
        if(!empty($sort)){
            switch ($sort) {
                case 'od_najtanszych':
                    $q_str .= " ORDER BY price ASC ";
                    break;
                case 'od_najdrozszych':
                    $q_str .= " ORDER BY price DESC ";
                default:
                    break;
            }
        }else {
          $q_str .= " ORDER BY `quantity` DESC";
        }
//          $q_str .= " LIMIT ".$limit." OFFSET ".$limit*$page;
        $q = $this->db->query($q_str)->result();
//        echo $this->db->last_query(); die();
        return $q;
        
    }
    public function getUrl($subdir = null) {
        $photos = $this->findAllPhotos();
        $photo = array_shift($photos);
        if(empty($photo)){
            return (new CustomElementModel('25'))->getField('domyslna grafika kafla produktu');
        } else {
            return $photo->getUrl($subdir);
        }
    }

    public function getPermalink(){
        $trans = $this->getTranslation(LANG);
        if(empty($trans->custom_url)){return site_url('produkt/' . getAlias($this->id, $trans->name)); }
        else { return site_url('produkt/' . $trans->custom_url);}    
    }
     public function getBasketLink(){
        $trans = $this->getTranslation(LANG);
        return site_url('dodaj-do-koszyka/' . getAlias($this->id, $trans->name)); 
    }
    
    public function getCategory() {
        $this->load->model('ProductsCategoryModel');
        return new ProductsCategoryModel($this->offer_category_id);
    }

    public function getTranslation($lang) {
        $this->load->model('ProductTranslationModel');

        return $this->ProductTranslationModel->findByProductAndLang($this, $lang);
    }

    public function sort_item($item_id, $position) {
        $this->db->where('id', $item_id);
        $res = $this->db->update('products', [
            'order' => $position
        ]);
        return $res;
    }

   public function add_to_basket($product_id, $quantity = 1, $option = 0, $attributes = array(), $additional = '') { 
        $this->clear_blocked();
        $this->getById($product_id);
        $sess = $this->session->userdata('ses_id');
        if (empty($sess)) {
            $this->session->set_userdata('ses_id', md5(date('U')));
        }
        $ses_id = $this->session->userdata('ses_id');
        if($option > 0){
            $option_values = $this->select_option($option);
            if($option_values['quantity_left'] < $quantity && $option_values['quantity_left'] > -1){
                return FALSE;
            }
        }
        if($this->quantity <= 0 ){ return false; }
        if($this->quantity > -1 && $this->quantity < $quantity){
            return FALSE;
        }
        $basket = $this->session->userdata('basket');
        $old_q = 0;
        if(!empty($basket[$product_id][0])){
            $old_q  = $basket[$product_id][0];
        }
        $this->blocked_product($product_id, $option, $quantity+$old_q, $ses_id);
       
        
        
//        $basket = $this->session->userdata('basket');
//        $old_q = 0;
//        if(!empty($basket[$product_id][0])){
//            $old_q  = $basket[$product_id][0];
//        }
        $basket[$product_id] = [$old_q+$quantity,$attributes,$additional];

        $this->session->set_userdata('basket', $basket);
        return TRUE;
    }
    
    /**
     * Aktualizuje ilość produktów w koszyku
     */
        public function change_basket($product_id, $quantity){
        $sess = $this->session->userdata('ses_id');
        if (empty($sess)) {
            $this->session->set_userdata('ses_id', md5(date('U')));
        }
        $ses_id = $this->session->userdata('ses_id');
        
        $res = $this->blocked_product($product_id, null, $quantity, $ses_id);
        if($res === FALSE){
            return FALSE;
        }
        // $basket = json_decode($this->input->cookie('basket', true), true);
        $basket = $this->session->userdata('basket');
        //$pr = new ProductModel($product_id);
        
        if(isset($basket[$product_id])){
            $basket[$product_id][0] = $quantity;
        } else {
            return false;
        }
        
        $this->session->set_userdata('basket', $basket);
        return TRUE;
    }

    public function select_option($option_id) {
        $q = $this->db->get_where('duo_shop_options', array('id' => $option_id))->result();
        if(!empty($q[0])){
            $this->getById($q[0]->product_id);
            $res = (array) $q[0];
            $res['product_price'] = /*$this->price +*/ $res['price_change'];
        } else {
            $res['product_price'] = $this->price;
        }
        return $res;
    }
    
    public function clear_blocked(){
        $res = $this->db->get_where('shop_blocked_products', array('expired <' => date('Y-m-d H:i:s')))->result();
        if(!empty($res)){
            foreach($res as $r){
                $this->blocked_product($r->product_id, 0, 0, $r->session_id );
                $this->db->delete('shop_blocked_products', array('id' => $r->id));
            }
        }
    }

     public function blocked_product($product_id, $option, $quantity, $ses_id, $to_blocked = 1) {   
        $quantity_product = (new ProductModel($product_id))->quantity;
        if (!empty($option) && $option > 0) {
            $op = $this->select_option($option);
            if ($op['quantity_left'] != -1 && $op['quantity_left'] >= $quantity) {
                $this->db->where('id', $op['id'])->update('shop_options', array('quantity_left' => $op['quantity_left'] - $quantity));
                if($to_blocked){
                    $block_op = $this->db->get_where('shop_blocked_products', array('session_id' => $ses_id, 'product_id' => $product_id, 'option_id' => $option))->result();
                    if (!empty($block_op[0])) {
                        $this->db->where(array('session_id' => $ses_id, 'product_id' => $product_id, 'option_id' => $option))
                                ->update('shop_blocked_products', array('quantity' => $quantity + $block_op[0]->quantity, 'expired' => date('Y-m-d H:i:s',(date('U')+900))));
                    } else {
                        $this->db->insert('shop_blocked_products', array(
                            'session_id' => $ses_id,
                            'product_id' => $product_id,
                            'option_id' => $option,
                            'quantity' => $quantity,
                            'expired' => date('Y-m-d H:i:s',(date('U')+300))
                        ));
                    }
                }
            } else if ($op['quantity'] == -1) {
                //w sumie to nic się nie dzieje bo jest w nieskończoność tego
            } else {
                return FALSE;
            }
        } else {
            
           if($to_blocked){
                $block_op = $this->db->get_where('shop_blocked_products', array('session_id' => $ses_id, 'product_id' => $product_id ))->result();
                if (!empty($block_op[0])) {
                    $blocked = $block_op[0]->quantity;
                    if($quantity <= $quantity_product+$blocked){
                    $this->db->where('id',$product_id)->update('products', array('quantity' => $quantity_product + $blocked  - $quantity ));
                    $this->db->where(array('session_id' => $ses_id, 'product_id' => $product_id ))
                            ->update('shop_blocked_products', array('quantity' => $quantity, 'expired' => date('Y-m-d H:i:s',(date('U')+900))));
                    } else {
                        return FALSE;
                    }
                    
                    } else {
                    $this->db->where('id', $product_id)->update('products', array('quantity' => $quantity_product - $quantity));
                    $this->db->insert('shop_blocked_products', array(
                        'session_id' => $ses_id,
                        'product_id' => $product_id,
                        'option_id' => !empty($option) ? $option : null,
                        'quantity' => $quantity,
                        'expired' => date('Y-m-d H:i:s',(date('U')+300)) 
                    ));
                }
            } else {
                $block_op = $this->db->get_where('shop_blocked_products', array('session_id' => $ses_id, 'product_id' => $product_id, 'option_id' => $option))->result();
                if (!empty($block_op[0])) {
                    $blocked = $block_op[0]->quantity;
                    $change = $quantity - $blocked;
                    if($change <= $quantity_product){
                    $this->db->where('id',$product_id)->update('products', array('quantity' => $quantity_product - $change));
                    $this->db->where(array('session_id' => $ses_id, 'product_id' => $product_id))
                            ->update('shop_blocked_products', array('quantity' => 0, 'expired' => date('Y-m-d H:i:s',(date('U')+900))));
                    } else {
                        return FALSE;
                    }
                    
                }
            }
        }
    }
    public function itemBought($product_id = null, $sess_id = null){
        if(empty($product_id) || empty($sess_id)) { return false; }
         $this->db->where(array('session_id' => $sess_id, 'product_id' => $product_id))->delete('shop_blocked_products');
         return true;
    }
    
        public function getOfferCategory(){
        $this->load->model("OfferCategoryModel");
        
        return new OfferCategoryModel($this->offer_category_id);
    }

    public function save_tmp_file($file_name){
        $db_table = 'duo_tmp_files';
        $this->db->insert($db_table, array('file_name' => $file_name));
        
        //usuwanie starszych niż dwa dni
        $q = $this->db->get_where($db_table,  array('created_at < ' => date('Y-m-d H:i:s', date('U') - 172800)))->result();
        if(!empty($q)){
            foreach($q as $r){
                unlink('./tmp_files/'.$r->file_name);
            }
            $this->db->delete($db_table, array('created_at < ' => date('Y-m-d H:i:s',date('U') - 172800)));
        }
    }
    
    //Atrybuty
    
    
    public function attribute_get_list($lang = 'pl'){
        $q = $this->db->select($this->attributes_table.".id AS id, "
                . "".$this->attributes_table.".value AS value, "
                . "".$this->attributes_table_translations.".name, "
                . "".$this->attributes_table_translations.".description, ")
                ->where('lang',$lang)
                ->join($this->attributes_table_translations, $this->attributes_table_translations.'.attribute_id = '.$this->attributes_table.'.id', 'left')
                ->get($this->attributes_table);
        $res = $q->result();
        return $res;
    }
    
    public function attribute_add_to_product($attribute_id, $product_id, $value){
        $q = $this->db->get_where($this->attributes_table_relations, array('attribute_id' => $attribute_id, 'product_id' => $product_id))->result();
        if(empty($q)){
            $this->db->insert($this->attributes_table_relations, array('attribute_id' => $attribute_id, 'product_id' => $product_id, 'value' => $value));
            return TRUE;
        } else {
            return FALSE;
        }
        
    }
    
    public function attribute_get_list_for_product($product_id, $lang = 'pl'){
        $q = $this->db->where(array($this->attributes_table_translations.'.lang' => $lang, $this->attributes_table_relations.'.product_id' => $product_id))
                ->join($this->attributes_table, $this->attributes_table.'.id = '. $this->attributes_table_relations.'.attribute_id', 'left')
                ->join($this->attributes_table_translations, $this->attributes_table_translations.'.attribute_id = '.$this->attributes_table.'.id', 'left')               
                ->get($this->attributes_table_relations);
        $res = $q->result();
        return $res;
    }
    
    
    
    //Ta funkcja pobiera tablicę identyfikatorów atrybutów
    //i zwraca sume ich wartości
    public function atributes_get_change($attributes){
        if(!empty($attributes)){
            $sum = 0;
            foreach($attributes as $at_id){
                $q = $this->db->get_where($this->attributes_table, array('id' => $at_id))->result();
                if(!empty($q)){
                    $sum += $q[0]->value;
                }
            }
            return $sum;
        } else {
            return 0;
        }
    }
    
    /**
     * Kalkulacja ceny na podstawie produktu, atrybutów i opcji
     * @param type $product_id
     * @param type $option_id
     * @param type $attributes tablica identyfikatorów atrybutów
     * @param type $additional json z dodatkowymi bzdurami
     */
    public function calculate_price($product_id, $option_id = null, $attributes = array(), $additional = null){
        $this->getById($product_id);
        $price = $this->price; 
        if($option_id !== null){
            $option = $this->select_option($option_id);
            $price = $option['product_price'];
        }
        
        //mam cenę bazową teraz biorę pod uwagę atrybuty jeśli są
        $procentage_change = 0;
        $value_change = 0;
//        if(!empty($attributes)){
//            foreach ($attributes as $attribute){
//                $this->db->select("duo_shop_attributes.value as default_value, duo_shop_attributes_relations.value as value");
//                $this->db->where(array(
//                    "product_id" => $product_id,
//                    "attribute_id" => $attribute
//                ));
//                $this->db->join('duo_shop_attributes','duo_shop_attributes.id = duo_shop_attributes_relations.attribute_id');
//                
//                $q = $this->db->get('duo_shop_attributes_relations');
//                $r = $q->row();
//                if($r->value === NULL){
//                    $procentage_change += $r->default_value;
//                } else {
//                    $procentage_change += $r->value;
//                }
//            }
//        }
        
        if($procentage_change > 0){
            $res_price = round($price * (1 + $procentage_change/100) * 100)/100;
        } else {
            $res_price = $price;
        }
        
        if(!empty($additional)){
            $add = json_decode($additional);
            if(!empty($add)){
                $width = !empty($add->mirror_width) ? $add->mirror_width : 0;
                $height = !empty($add->mirror_height) ? $add->mirror_height : 0;
                $extras = $add->mirror_extras;
                
                $area = ($width*1./100.) * ($height*1/100.);
                $res_price = (!empty($area) && $area > 0) ? (new CustomElementModel('22'))->getField('cena za 1m kwadratowy')->value*1 * $area : $price;
                $add_price = 0;
                if(!empty($area) && $area > 0){
                if($area < 0.36){
                    $add_price = (new CustomElementModel('22'))->getField('doplata ponizej 0,36')->value*1;
                } elseif($area < 0.43){
                    $add_price =  (new CustomElementModel('22'))->getField('doplata 0.36 - 0,42')->value*1;
                } elseif($area < 0.61){
                    $add_price =  (new CustomElementModel('22'))->getField('doplata 0,43 - 0,6')->value*1;
                } elseif($area < 0.81){
                    $add_price = (new CustomElementModel('22'))->getField('doplata 0,61 -0,8')->value*1;
                } elseif($area < 1){
                    $add_prices = (new CustomElementModel('22'))->getField('doplata 0,8 - 1')->value*1;
                }
                $res_price += $add_price;
                }
                if(!empty($extras)){
                    foreach($extras as $ex){
                        if(!empty($ex)){
                            $detail = $this->get_detail_by_id($ex);
                            $res_price +=$detail->price;
                        }
                    }
                }
            }
        }
        
        //doliczam ewentualny rabat
        if(!empty($this->session->userdata['login']['user']['discount'])){
            $dsc = $this->session->userdata['login']['user']['discount'];
            $res_price = round(($res_price  - ($dsc / 100) * $res_price) * 100) / 100;
        }
        
        return $res_price;
    }
    public function calculate_price2($product_id, $option_id = null, $attributes = array(), $currency_id, $additional = null){
        $this->getById($product_id);
        $price = $this->getPrice($currency_id); 
        if($option_id !== null && !empty($option_id)){
            $option = $this->select_option($option_id);
            $price = $option['product_price'];
        }
        
        //mam cenę bazową teraz biorę pod uwagę atrybuty jeśli są
        $procentage_change = 0;
//        $value_change = 0;
//        if(!empty($attributes)){
//            foreach ($attributes as $attribute){
//                $this->db->select("duo_shop_attributes.value as default_value, duo_shop_attributes_relations.value as value");
//                $this->db->where(array(
//                    "product_id" => $product_id,
//                    "attribute_id" => $attribute
//                ));
//                $this->db->join('duo_shop_attributes','duo_shop_attributes.id = duo_shop_attributes_relations.attribute_id');
//                
//                $q = $this->db->get('duo_shop_attributes_relations');
//                $r = $q->row();
//                if($r->value === NULL){
//                    $procentage_change += $r->default_value;
//                } else {
//                    $procentage_change += $r->value;
//                }
//            }
//        }
        
        if($procentage_change > 0){
            $res_price = round($price * (1 + $procentage_change/100) * 100)/100;
        } else {
            $res_price = $price;
        }
                if(!empty($additional)){
            $add = json_decode($additional);
            if(!empty($add)){
                $width = !empty($add->mirror_width) ? $add->mirror_width : 0;
                $height = !empty($add->mirror_height) ? $add->mirror_height : 0;
                $extras = $add->mirror_extras;
                
                $area = ($width*1./100.) * ($height*1/100.);
                $res_price = (!empty($area) && $area > 0) ? (new CustomElementModel('22'))->getField('cena za 1m kwadratowy')->value*1 * $area : $price;
                $add_price = 0;
                if(!empty($area) && $area > 0){
                if($area < 0.36){
                    $add_price = (new CustomElementModel('22'))->getField('doplata ponizej 0,36')->value*1;
                } elseif($area < 0.43){
                    $add_price =  (new CustomElementModel('22'))->getField('doplata 0.36 - 0,42')->value*1;
                } elseif($area < 0.61){
                    $add_price =  (new CustomElementModel('22'))->getField('doplata 0,43 - 0,6')->value*1;
                } elseif($area < 0.81){
                    $add_price = (new CustomElementModel('22'))->getField('doplata 0,61 -0,8')->value*1;
                } elseif($area < 1){
                    $add_prices = (new CustomElementModel('22'))->getField('doplata 0,8 - 1')->value*1;
                }
                $res_price += $add_price;
                }
                if(!empty($extras)){
                    foreach($extras as $ex){
                        if(!empty($ex)){
                            $detail = $this->get_detail_by_id($ex);
                            $res_price +=$detail->price;
                        }
                    }
                }
            }
        }
        //doliczam ewentualny rabat
        if(!empty($this->session->userdata['login']['user']['discount'])){
            $dsc = $this->session->userdata['login']['user']['discount'];
            $res_price = round(($res_price  - ($dsc / 100) * $res_price) * 100) / 100;
        }
        return $res_price;
    }
    public function check_product_relation($product1_id, $product2_id){
        $where = '(product1_id = ' . $product1_id .' AND product2_id = ' .$product2_id .') '
                .' OR (product2_id = '. $product1_id .' AND product1_id = '.$product2_id.')';
        $this->db->where($where);
        $q = $this->db->get($this->_relations_table);
        if($q->num_rows() > 0){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function delete_product_relations($product1_id, $product2_id){
        $where = '(product1_id = ' . $product1_id .' AND product2_id = ' .$product2_id .') '
        .' OR (product2_id = '. $product1_id .' AND product1_id = '.$product2_id.')';
        $this->db->where($where);
        $this->db->delete($this->_relations_table);
        
    }
    public function get_product_relations($product_id){
        $this->db->where('product1_id', $product_id);
        $this->db->or_where('product2_id',$product_id);
        $q = $this->db->get($this->_relations_table);
        $res = array();
        foreach ($q->result() as $row){
            if($row->product1_id == $product_id){
                $res[]= $row->product2_id;
            }else {
                $res[] = $row->product1_id;
            }
        }
        return $res;
    }
    
    public function save_product_relations($product1_id, $product2_id){
        $data = array(
            'product1_id' => $product1_id,
            'product2_id' => $product2_id,
            'created_at' => (new DateTime())->format('Y-m-d H:i:s')
        );
        $res = $this->db->insert($this->_relations_table, $data);
        
        return $res;
    }
    
    public function get_product_list_for_relations($product_id){
        $this->db->where('lang', 'pl');
        $this->db->where('product_id <> '.$product_id);
        $q = $this->db->get('duo_products_translations');
        $res = array();
        foreach ($q->result() as $row) {
            $res[$row->product_id] = $row->name;
        }
        return $res;
    }
    
//    public function getBrand(){
//        $producent_attr_id = (new CustomElementModel('19'))->getField('id kategori atrybutow producentow');
//        $this->db->join('duo_shop_attributes_groups','duo_shop_attributes_groups.id = duo_shop_attributes.attributes_group_id');
//        $this->db->join('duo_shop_attributes_translations','duo_shop_attributes_translations.attribute_id = duo_shop_attributes.id');
//        $this->db->join('duo_shop_attributes_relations' ,'duo_shop_attributes_relations.attribute_id = duo_shop_attributes.id');
//        $this->db->where('duo_shop_attributes_relations.product_id', $this->id);
//        $this->db->where('attributes_group_id IN ('. $producent_attr_id.') AND lang = "' . LANG .'"');
//        $q = $this->db->get('duo_shop_attributes');
//        $producer='&nbsp;';
//        if($q->num_rows() > 0)
//        {
//            $producer = $q->result()[0]->name;
//        }
//        return $producer;
//    }
    
    public function getPrice($currency_id = null){
        //2022
        $userSess = $this->session->login;
        $userId = $userSess['user']['id'];
        $user = new User_Model();
        $user->loggedin();
        if ($user->isLoggedIn()) {
            $discount = new UserProductDiscountModel();
            $res = $discount->checkDiscount($userId, $this->id);
            if ($res) {
                return $res;
            }
        }
        //2022
        if(empty($currency_id)){
        $this->db->where('currency_id', get_active_currency()->id);
        } else {
            $this->db->where('currency_id', $currency_id);
        }
        $this->db->where('product_id', $this->id);
        
        $q = $this->db->get('duo_product_prices');
        if ($q->num_rows() > 0) {
            $cena =  $q->result()[0]->price;
            if($cena == 0){
                $details = $this->get_details_groups();
        if(!empty($details)){
            $height = 0;
            $width = 0;
            foreach($details as $det_group){
                if($det_group->name == 'mirror_width'){
                    $width  = $det_group->details[0]->val;
                }
                if($det_group->name == 'mirror_height'){
                    $height  = $det_group->details[0]->val;
                }
            }
           $area = ($width*1./100.) * ($height*1/100.);
                $res_price = (!empty($area) && $area > 0) ? (new CustomElementModel('22'))->getField('cena za 1m kwadratowy')->value*1 * $area : 380;
                $add_price = 0;
                if(!empty($area) && $area > 0){
                if($area < 0.36){
                    $add_price = (new CustomElementModel('22'))->getField('doplata ponizej 0,36')->value*1;
                } elseif($area < 0.43){
                    $add_price =  (new CustomElementModel('22'))->getField('doplata 0.36 - 0,42')->value*1;
                } elseif($area < 0.61){
                    $add_price =  (new CustomElementModel('22'))->getField('doplata 0,43 - 0,6')->value*1;
                } elseif($area < 0.81){
                    $add_price = (new CustomElementModel('22'))->getField('doplata 0,61 -0,8')->value*1;
                } elseif($area < 1){
                    $add_prices = (new CustomElementModel('22'))->getField('doplata 0,8 - 1')->value*1;
                }
                $res_price += $add_price;
                
                return $res_price;
                }
        }
            } else {
                return $cena;
            }
        } else {
            $details = $this->get_details_groups();
        if(!empty($details)){
            $height = 0;
            $width = 0;
            foreach($details as $det_group){
                if($det_group->name == 'mirror_width'){
                    $width  = $det_group->details[0]->val;
                }
                if($det_group->name == 'mirror_height'){
                    $height  = $det_group->details[0]->val;
                }
            }
           $area = ($width*1./100.) * ($height*1/100.);
                $res_price = (!empty($area) && $area > 0) ? (new CustomElementModel('22'))->getField('cena za 1m kwadratowy')->value*1 * $area : 380;
                $add_price = 0;
                if(!empty($area) && $area > 0){
                if($area < 0.36){
                    $add_price = (new CustomElementModel('22'))->getField('doplata ponizej 0,36')->value*1;
                } elseif($area < 0.43){
                    $add_price =  (new CustomElementModel('22'))->getField('doplata 0.36 - 0,42')->value*1;
                } elseif($area < 0.61){
                    $add_price =  (new CustomElementModel('22'))->getField('doplata 0,43 - 0,6')->value*1;
                } elseif($area < 0.81){
                    $add_price = (new CustomElementModel('22'))->getField('doplata 0,61 -0,8')->value*1;
                } elseif($area < 1){
                    $add_prices = (new CustomElementModel('22'))->getField('doplata 0,8 - 1')->value*1;
                }
                $res_price += $add_price;
                
                return $res_price;
                }
        }
        }
    }
    
     public function get_price_range($category_id){
        $currency_id =  get_active_currency()->id;
        $this->db->select('id');
        $this->db->where('duo_products.offer_category_id', $category_id);
        $z = $this->db->get('duo_products');
        
       if($z->num_rows() > 0){
        $this->db->select("MAX(duo_product_prices.price) as max_price, MIN(duo_product_prices.price) as min_price");
        $this->db->join('duo_product_prices', 'duo_product_prices.product_id = duo_products.id');
        $this->db->where('duo_product_prices.currency_id',$currency_id);
        $this->db->where('duo_products.offer_category_id', $category_id);
        $q = $this->db->get('duo_products');
        
       return $q->result()[0]; }
       else {
           $this->load->model('OfferCategoryModel');
           $cats = $this->OfferCategoryModel->get_children_by_parent($category_id);
           
           if(empty($cats)){
               $res = new stdClass();
               $res->max_price = 0;
               $res->min_price = 0;
               return $res;
           }
        $this->db->select("MAX(duo_product_prices.price) as max_price, MIN(duo_product_prices.price) as min_price");
        $this->db->join('duo_product_prices', 'duo_product_prices.product_id = duo_products.id');
        $this->db->where('duo_product_prices.currency_id',$currency_id);
        $this->db->where_in('duo_products.offer_category_id', $cats);
        $q = $this->db->get('duo_products');
        
       return $q->result()[0];
       }
    }
    
    public function get_details_by_group($group_id){
        $this->db->where('group_id', $group_id);
        $this->db->order_by('val asc, name asc');
        $q = $this->db->get('duo_product_details');
        
        return $q->result();
    }
    
     public function get_detail_by_id($detail_id){
        $this->db->where('id', $detail_id);
        $q = $this->db->get('duo_product_details');
        
        return $q->row();
    }
        public function get_detail_group_by_id($detail_id){
        $this->db->where('id', $detail_id);
        $q = $this->db->get('duo_product_details_groups');
        
        return $q->row();
    }
    public function get_details_groups(){
        $this->db->where('product_id', $this->id);
        $q = $this->db->get('duo_product_details_groups');
        $output = $q->result();
        foreach($output as $row){
            $row->details = $this->get_details_by_group($row->id);
        }
        return $output;
    }
    
    public function add_detail_group($args){
       $res =  $this->db->insert('duo_product_details_groups',[
            'product_id' => $this->id,
            'name' => $args['name']
        ]);
       
       return $res;
    }
    
    public function add_detail($args){
        $res = $this->db->insert('duo_product_details',[
            'price' => $args['price'],
            'name' => $args['name'],
            'group_id' => $args['group_id'],
            'val' => $args['val']
        ]);
        return $res;
    }
    
    public function edit_detail($args){
        $this->db->where('id', $args['id']);
        $res = $this->db->update('duo_product_details',[
            'price' => $args['price'],
            'name' => $args['name'],
            'val' => $args['val']
        ]);
        return $res;
    }
    
    public function edit_detail_group($args){
        $this->db->where('id', $args['id']);
        $res = $this->db->update('duo_product_details_groups',[
            'name' => $args['name'],
        ]);
        return $res;
    }
    
    public function delete_detail($detail_id){
        $this->db->where('id', $detail_id);
        $res = $this->db->delete('duo_product_details');
        return $res;
    }
    
    public function get_detail_details($detail_id){
       $detail = $this->get_detail_by_id($detail_id);
       $group = $this->get_detail_group_by_id($detail->group_id);
       
       return $group->name.': '.$detail->name;
    }
    public function findAllByProducent($producent){
        $this->db->where('producent', $producent);
        return $this->findAll();
    }
    public function findAllProducents()
    {		
            $this->db->group_by("producent");
            $query=$this->db->get('products');
            return $query->result('ProductModel');
    }
    
    public function findOneByExternalId($ext_id){
        $this->db->where('external_id', $ext_id);
        $q = $this->db->get($this->_table_name);
        if($q->num_rows() > 0){
            return $q->result('ProductModel')[0];
        } else {
            return null;
        }
    }
    
    public function findAllByExternalIds($external_ids_array){
        $this->db->select('duo_products.id, duo_products.external_id, duo_products.quantity, duo_product_prices.price, custom_fields_translations.value as brutto_a, products.promo');
        $this->db->join('duo_product_prices','duo_product_prices.product_id = duo_products.id','left');
        $this->db->join('duo_custom_fields_translations','duo_custom_fields_translations.element_id_translation = duo_products.id','left');
        $this->db->where("(external_id IN (". join(',',$external_ids_array) .")) AND (duo_custom_fields_translations.field_id = 6 OR duo_custom_fields_translations.field_id IS NULL)");
        $this->db->where([
            'duo_product_price.currency_id = 1'
        ]);
        $q = $this->db->get($this->_table_name);
        if($q->num_rows() > 0){
            return $q->result();
        } else {
            return null;
        }
    }
    
    public function findPhotoModifyDate(){
        $this->db->where('product_id', $this->id);
        $q = $this->db->get('duo_product_external_photos');
        
        if($q->num_rows() > 0){
            return $q->row()->modified;
        } else {
            return null;
        }
    }
    
    public function addExternalPhoto($name = null, $modified = null){
        if(!empty($name)){
            
            $this->db->where('product_id', $this->id);
            $this->db->where('name', $name);
            $q = $this->db->get('duo_product_external_photos');
            
            if($q->num_rows() == 0){
            $args = [
                'name' => $name,
                'modified' => $modified,
                'product_id' => $this->id
            ];
            
            $this->db->insert('duo_product_external_photos', $args);
            }
        }
    }
    
    public function deleteAllExternalPhotos(){
        $this->load->model("ProductPhotoModel");
        if(!empty($this->id)){
//            $this->db->where('product_id', $this->id);
//            $q = $this->db->get('duo_product_external_photos');
//        
//            foreach($q->result() as $res){
//                $photo = new ProductPhotoModel($res->photo_id);
//                if(!empty($photo->id)){
//                    $photo->delete();
//                }
//            }
            $all_photos = $this->findAllPhotos();
            foreach($all_photos as $ap){
                $ap->delete();
            }
            
            
            $this->db->where('product_id', $this->id);
            $this->db->delete('duo_product_external_photos');
        }
    }
    
    public function deleteDuplicateExternalPhotos(){
        $this->db->where('product_id', $this->id);
        $q = $this->db->get('duo_product_external_photos');
        $all_photos = [];
        foreach($q->result() as $res){
            $all_photos[] = $res->photo_id;
        }
        $exceed_photos = $this->findAllPhotos();
        foreach($exceed_photos as $ep){
            if(!in_array($ep->id, $all_photos)){
                $ep->delete();
            }
        }
        
    }
    
    public function findAllExternalPhotos(){
         $this->db->where('product_id', $this->id);
         $this->db->where('photo_id IS NULL');
        $q = $this->db->get('duo_product_external_photos');
        return $q->result();
    }
    
    
    public function findPromoProductsWithCategory(){
        $this->db->select('products.*, duo_products_categories.category_id as cat_id');
//        $this->db->where('promo', 1);
        $this->db->join("duo_products_categories", "duo_products_categories.product_id = duo_products.id ");
        $this->db->group_by("duo_products_categories.category_id");
//        $this->db->order_by("RAND() ASC");
        $q = $this->db->get('duo_products');
        
        $output = [];
//        var_dump($q->result()); die();
        foreach($q->result('ProductModel') as $prod){
            $output[$prod->cat_id] = $prod; 
        }
        
        return $output;
    }
    
    public function findCheapestDelivery(){
        $this->load->model("Delivery_Model");
        $avaible_deliveries = $this->Delivery_Model->get_deliveries(null, $this->weight);
        $max = 99999;
        $max_id = 0;
//        var_dump($avaible_deliveries); die();
        foreach($avaible_deliveries as $ad){
            if($max > $ad->prices[1]['price']){
                $max = $ad->prices[1]['price'];
                $max_id = $ad->id;
            }
        }
        
        return $max;
    }
    
           public function get_friendly_attributes(){
        $attributes_data = $this->attribute_get_list_for_product($this->id);
        $out = [];
        $ids = [];
        foreach($attributes_data as $attr){
            $out[$attr->attributes_group_id]['names'][] = $attr->name;
            if(!in_array($attr->attributes_group_id, $ids)){
                $ids[] = $attr->attributes_group_id;
            }
        }
        if(!empty($ids)){
        $this->db->where_in('attributes_group_id', $ids);
        $q = $this->db->get('duo_shop_attributes_groups_translations');
        if($q->num_rows() > 0){
            foreach($q->result() as $row){
                $out[$row->attributes_group_id]['category_name'] = $row->name;
            }
            return $out;
        } else {
            return [];
        }
        } else {
            return [];
        }
    }
}