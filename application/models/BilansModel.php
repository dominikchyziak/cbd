<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class BilansModel extends MY_Model {

    private $ftp_name = "eurosan.pl";
    private $ftp_login = "eurosan_sklepn";
    private $ftp_pass = '!@#$QWer1234';
    
    function __construct() {
        parent::__construct();
            
    }
    
     public function get_file($file){
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, "ftp://".$this->ftp_name.":21/");
                curl_setopt($curl, CURLOPT_FTP_USE_EPSV, 1);
                curl_setopt($curl, CURLOPT_USERPWD, $this->ftp_login.":".$this->ftp_pass);
                curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1) ;
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'RETR '.$file);
                $smth = curl_exec($curl);
                curl_close($curl);
                if($smth === false) { $smth = $this->get_file($file);}
                return $smth;
    }

         public function update_products() {

        $xml = $this->get_file('towar.xml');

        $reader = new XMLReader;
//        $path = site_url("uploads/bilans/towar2.xml");
//        echo $path; die();
//        $reader->open($path);
        $reader->xml($xml);
        $this->load->model("OfferCategoryModel");
        $this->load->model("ProductModel");
        $this->load->model("ProductTranslationModel");
        $this->load->model('CustomFieldsModel');
        $i = 0;
        while ($reader->read() !== FALSE) {
            if ($reader->name === 'tow' && $reader->nodeType === XMLReader::ELEMENT) {
                $name = $reader->getAttribute('Nazwa');
                if (!empty($name)) {
                    $a = new stdClass();
                    $a->ext_id = $reader->getAttribute('ID_TOW');  // +1
                    $a->photos = $reader->getAttribute('Zdjęcia');   // dodatkowa tabela
                    $a->type = $reader->getAttribute('Typ');   //  attr
                    $a->material = $reader->getAttribute('Materiał'); // attr
                    $a->color = $reader->getAttribute('Kolor'); // attr
                    $a->collection = $reader->getAttribute('Kolekcja'); // attr
                    $a->weight = $reader->getAttribute('Ciężar');   // jest
                    $a->vat = $reader->getAttribute('Stawka_VAT'); // +1
                    $a->promo = $reader->getAttribute('Promocja'); // jest
                    $a->brutto_a = $reader->getAttribute('Brutto_A'); // custom field
                    $a->brutto = $reader->getAttribute('Brutto'); // jest
                    $a->avaibility = $reader->getAttribute('Dostępność'); // +1 
                    $a->in_pack = $reader->getAttribute('Ilość_w_opakowaniu'); // +1
                    $a->always_avaible = $reader->getAttribute('Dostępny_zawsze'); // +1
                    $a->quantity = $reader->getAttribute('Stan'); // jest
                    $a->ean = $reader->getAttribute('EAN'); // +1
                    $a->code = $reader->getAttribute('Kod'); // jest
                    $a->ext_category_ids = $reader->getAttribute('ID_GRE'); // jest
                    $a->producent = $reader->getAttribute('Producent');
                    $a->tags = $reader->getAttribute('Słowa_kluczowe'); //Słowa_kluczowe
                    echo $i++ . '<br>';
                    echo $name . '<br>';

                    $product_f = $this->ProductModel->findOneByExternalId($a->ext_id);
//                    var_dump($product_f);
                    if (empty($product_f->id)) {
                        echo 'nie znaleziono prduktu o external_id ' . $a->ext_id;
                        $product = new ProductModel();
                        $product->external_id = $a->ext_id;
                        $product->weight = $a->weight;
                        $product->vat = $a->vat;
                        $product->promo = $a->promo == "T" ? 1 : 0;
                        $product->prices[1] = $a->brutto;
                        $product->availability = $a->avaibility;
                        $product->number_in_package = $a->in_pack;
                        $product->always_avaible = $a->always_avaible == "T" ? 1 : 0;
                        $product->quantity = $a->quantity;
                        $product->ean = $a->ean;
                        $product->code = $a->code;
                        $ext_cats = explode(';', $a->ext_category_ids);
                        $categories = [];
                        foreach ($ext_cats as $ex_c) {
                            $z_id =  $this->OfferCategoryModel->findByExternalId($ex_c)->id;
                            if(!empty($z_id)){
                                $categories[] = $z_id;
                            }
                        }
                        $product->categories = $categories;
                        $product->producent = $a->producent;
                        if (!empty($a->tags)) {
                            $product->tags = $a->tags;
                        }
                        $product->insert_product();
//                    
                        $translation = new ProductTranslationModel();
                        $translation->product_id = $product->id;
                        $translation->lang = 'pl';
                        $translation->name = $name;
                        $translation->format = '';
                        $translation->slogan = '';
                        $translation->body = "";
                        $translation->meta_title = '';
                        $translation->meta_description = '';
                        $translation->custom_url = '';
                        $translation->insert();

                        $this->CustomFieldsModel->save_field(6, $product->id, $a->brutto_a, 'pl');
                        echo "<br> i dodano";
                    } else {
                        echo 'znaleziono prduktu o external_id ' . $a->ext_id;
                        $product = $product_f;
                        $product->weight = $a->weight;
                        $product->vat = $a->vat;
                        $product->promo = $a->promo == "T" ? 1 : 0;
                        $product->prices[1] = $a->brutto;
                        $product->availability = $a->avaibility;
                        $product->number_in_package = $a->in_pack;
                        $product->always_avaible = $a->always_avaible == "T" ? 1 : 0;
                        $product->quantity = $a->quantity;
                        $product->ean = $a->ean;
                        $product->code = $a->code;
                        if (!empty($a->ext_category_ids)) {
                            $ext_cats = explode(';', $a->ext_category_ids);
                            $categories = [];
                            foreach ($ext_cats as $ex_c) {
                                if (!empty($ex_c)) {
                                   $z_id =  $this->OfferCategoryModel->findByExternalId($ex_c)->id;
                            if(!empty($z_id)){
                                $categories[] = $z_id;
                            }
                                }
                            }
                            $product->categories = $categories;
                        }
                        $product->producent = $a->producent;
                        if (!empty($a->tags)) {
                            $product->tags = $a->tags;
                        }
                        $product->update_product();

                        $translation = $product->getTranslation('pl');
                        if(!empty($translation)){
                            $translation->name = $name;
                            $translation->update();
                        } else {
                            $translation = new ProductTranslationModel();
                        $translation->product_id = $product->id;
                        $translation->lang = 'pl';
                        $translation->name = $name;
                        $translation->format = '';
                        $translation->slogan = '';
                        $translation->body = "";
                        $translation->meta_title = '';
                        $translation->meta_description = '';
                        $translation->custom_url = '';
                        $translation->insert();
                            
                        }
                    }

                    echo '<hr>';
                }
            }
        }
    }

    public function bubaczka1337(){
             
       // $xml = $this->get_file('towar.xml');
        
        $reader = new XMLReader;
        $path = site_url("uploads/bilans/towarMulti.xml");
//        echo $path; die();
        $reader->open($path);
     //   $reader->xml($xml);
        $this->load->model("OfferCategoryModel");
        $this->load->model("ProductModel");
        $this->load->model("ProductTranslationModel");
        $this->load->model('CustomFieldsModel');
        $i = 0;
             while ($reader->read() !== FALSE) {
            if ($reader->name === 'tow' && $reader->nodeType === XMLReader::ELEMENT) {
                $name = $reader->getAttribute('Nazwa');
                if (!empty($name)) {
                    $a = new stdClass();
                    $a->ext_id = $reader->getAttribute('ID_TOW');  // +1
                    $a->photos = $reader->getAttribute('Zdjęcia');   // dodatkowa tabela
                    $a->type = $reader->getAttribute('Typ');   //  attr
                    $a->material = $reader->getAttribute('Materiał'); // attr
                    $a->color = $reader->getAttribute('Kolor'); // attr
                    $a->collection = $reader->getAttribute('Kolekcja'); // attr
                    $a->weight = $reader->getAttribute('Ciężar');   // jest
                    $a->vat = $reader->getAttribute('Stawka_VAT'); // +1
                    $a->promo = $reader->getAttribute('Promocja'); // jest
                    $a->brutto_a = $reader->getAttribute('Brutto_A'); // custom field
                    $a->brutto = $reader->getAttribute('Brutto'); // jest
                    $a->avaibility = $reader->getAttribute('Dostępność'); // +1 
                    $a->in_pack = $reader->getAttribute('Ilość_w_opakowaniu'); // +1
                    $a->always_avaible = $reader->getAttribute('Dostępny_zawsze'); // +1
                    $a->quantity = $reader->getAttribute('Stan'); // jest
                    $a->ean = $reader->getAttribute('EAN'); // +1
                    $a->code = $reader->getAttribute('Kod'); // jest
                    $a->ext_category_ids = $reader->getAttribute('ID_GRE'); // jest
                    $a->producent = $reader->getAttribute('Producent');
                    $a->tags = $reader->getAttribute('Słowa_kluczowe'); //Słowa_kluczowe
                    echo $i++ . '<br>';
                    echo $name . '<br>';

                    $product_f = $this->ProductModel->findOneByExternalId($a->ext_id);
//                    var_dump($product_f);
                    if (empty($product_f->id)) {
                        
                        echo 'nie znaleziono prduktu o external_id ' . $a->ext_id;
                        continue;
                        $product = new ProductModel();
                        $product->external_id = $a->ext_id;
                        $product->weight = $a->weight;
                        $product->vat = $a->vat;
                        $product->promo = $a->promo == "T" ? 1 : 0;
                        $product->prices[1] = $a->brutto;
                        $product->availability = $a->avaibility;
                        $product->number_in_package = $a->in_pack;
                        $product->always_avaible = $a->always_avaible == "T" ? 1 : 0;
                        $product->quantity = $a->quantity;
                        $product->ean = $a->ean;
                        $product->code = $a->code;
                        $ext_cats = explode(';', $a->ext_category_ids);
                        $categories = [];
                        foreach ($ext_cats as $ex_c) {
                            $z_id =  $this->OfferCategoryModel->findByExternalId($ex_c)->id;
                            if(!empty($z_id)){
                                $categories[] = $z_id;
                            }
                        }
                        $product->categories = $categories;
                        $product->producent = $a->producent;
                        if (!empty($a->tags)) {
                            $product->tags = $a->tags;
                        }
                        $product->insert_product();
//                    
                        $translation = new ProductTranslationModel();
                        $translation->product_id = $product->id;
                        $translation->lang = 'pl';
                        $translation->name = $name;
                        $translation->format = '';
                        $translation->slogan = '';
                        $translation->body = "";
                        $translation->meta_title = '';
                        $translation->meta_description = '';
                        $translation->custom_url = '';
                        $translation->insert();

                        $this->CustomFieldsModel->save_field(6, $product->id, $a->brutto_a, 'pl');
                        echo "<br> i dodano";
                    } else {
                        echo 'znaleziono prduktu o external_id ' . $a->ext_id;
                        $product = $product_f;
//                        if(!(strtotime('2019-11-13 14:00:00') > strtotime($product->updated_at))) { echo "<br>istnieją nowsze dane<br>"; continue;}
//                        $product->weight = $a->weight;
//                        $product->vat = $a->vat;
//                        $product->promo = $a->promo == "T" ? 1 : 0;
//                        $product->prices[1] = $a->brutto;
//                        $product->availability = $a->avaibility;
//                        $product->number_in_package = $a->in_pack;
//                        $product->always_avaible = $a->always_avaible == "T" ? 1 : 0;
//                        $product->quantity = $a->quantity;
//                        $product->ean = $a->ean;
//                        $product->code = $a->code;
                        if (!empty($a->ext_category_ids)) {
                            $ext_cats = explode(';', $a->ext_category_ids);
                            $categories = [];
                            foreach ($ext_cats as $ex_c) {
                                if (!empty($ex_c)) {
                                   $z_id =  $this->OfferCategoryModel->findByExternalId($ex_c)->id;
                            if(!empty($z_id)){
                                $categories[] = $z_id;
                            }
                                }
                            }
                            $product->categories = $categories;
                        }
//                        $product->producent = $a->producent;
//                        if (!empty($a->tags)) {
//                            $product->tags = $a->tags;
//                        }
                        $product->update_product();
//
//                        $translation = $product->getTranslation('pl');
//                        if(!empty($translation)){
//                            $translation->name = $name;
//                            $translation->update();
//                        } else {
//                            $translation = new ProductTranslationModel();
//                        $translation->product_id = $product->id;
//                        $translation->lang = 'pl';
//                        $translation->name = $name;
//                        $translation->format = '';
//                        $translation->slogan = '';
//                        $translation->body = "";
//                        $translation->meta_title = '';
//                        $translation->meta_description = '';
//                        $translation->custom_url = '';
//                        $translation->insert();
//                            
//                        }
                    }

                    echo '<hr>';
                }
            }
        }
            
            
    }
         public function update_products_tags(){
             
//        $xml = $this->get_file('towar.xml');
        
        $reader = new XMLReader;
        $path = site_url("uploads/bilans/towar3.xml");
//        echo $path; die();
        $reader->open($path);
//        $reader->xml($xml);
        $this->load->model("OfferCategoryModel");
        $this->load->model("ProductModel");
        $this->load->model("ProductTranslationModel");
        $this->load->model('CustomFieldsModel');
        $i = 0;
        while ($reader->read() !== FALSE) {
            if ($reader->name === 'tow' && $reader->nodeType === XMLReader::ELEMENT) {
                $name = $reader->getAttribute('Nazwa');
                if(!empty($name)){
                    $a = new stdClass();
                    $a->ext_id = $reader->getAttribute('ID_TOW');  // +1
                    $a->tags = $reader->getAttribute('Słowa_kluczowe');//Słowa_kluczowe
                    echo $i++.'<br>';
                    echo $name.'<br>';
                    $ext_cats = explode(';', $a->ext_category_ids);
                    $categories = [];
                    foreach($ext_cats as $ex_c){
                        $categories[] = $this->OfferCategoryModel->findByExternalId($ex_c)->id;
                    }
                    $product_f = $this->ProductModel->findOneByExternalId($a->ext_id);
//                    var_dump($product_f);
                    if(empty($product_f->id)){
                     echo 'nie znaleziono prduktu o external_id '. $a->ext_id;
//                    $product = new ProductModel();
//                    $product->external_id = $a->ext_id;
//                    $product->weight = $a->weight;
//                    $product->vat = $a->vat;
//                    $product->promo = $a->promo == "T" ? 1 : 0;
//                    $product->prices[1] = $a->brutto;
//                    $product->availability = $a->avaibility;
//                    $product->number_in_package = $a->in_pack;
//                    $product->always_avaible = $a->always_avaible == "T" ? 1 : 0;
//                    $product->quantity = $a->quantity;
//                    $product->ean = $a->ean;
//                    $product->code = $a->code;
//                    $ext_cats = explode(';', $a->ext_category_ids);
//                    $categories = [];
//                    foreach($ext_cats as $ex_c){
//                        $categories[] = $this->OfferCategoryModel->findByExternalId($ex_c)->id;
//                    }
//                    $product->categories = $categories;
//                    $product->producent = $a->producent;
//                    $product->tags = $a->tags;
//                    $product->insert_product();
////                    
//                    $translation = new ProductTranslationModel();
//                    $translation->product_id = $product->id;
//                    $translation->lang = 'pl';
//                    $translation->name = $name;
//                    $translation->format = '';
//                    $translation->slogan = '';
//                    $translation->body = "";
//                    $translation->meta_title = '';
//                    $translation->meta_description = '';
//                    $translation->custom_url = '';
//                    $translation->insert();
//                    
//                    $this->CustomFieldsModel->save_field(6, $product->id, $a->brutto_a, 'pl');
                     echo "<br> i dodano";
                    } else {
                         echo 'znaleziono prduktu o external_id '. $a->ext_id;
                    $product = new ProductModel($product_f->id);
                    if(!empty($a->tags)){
                    $product->tags = $a->tags;
                    }
                    $product->update_product();
                    
                    }
                    
                    echo '<hr>';
                }
            }
            }
            
            
    }
    public function descriptions1337(){
 $reader = new XMLReader;
        $path = site_url("uploads/bilans/opis14.xml");
//        echo $path; die();
        $reader->open($path);

        $this->load->model("ProductModel");
        $i = 0;
        while ($reader->read() !== FALSE) {
            if ($reader->name === 'opis' && $reader->nodeType === XMLReader::ELEMENT) {
              $desc = $reader->getAttribute('Opis');
              $ext_id = $reader->getAttribute('ID_TOW');
              $product_f = $this->ProductModel->findOneByExternalId($ext_id);
              if(empty($product_f->id)){ continue; }
              if(!empty($desc)){
                  $trans = $product_f->getTranslation('pl');
                  if(!empty($trans->body)){ continue; }
                  $start =  strpos($desc, "<body>");
                  $end =  strpos($desc,"</body>");

                  $opis = substr($desc, $start+6, $end-($start+6));
                  
                  
                  $trans->body = $opis;
                  $trans->update();
                  echo 'Dodano opis do '. $i++ . '-tego produktu.<br>';
              }
              
            }
        }
    }
public function descriptions(){
            $xml = $this->get_file('opis.xml');
        
        $reader = new XMLReader;

        $reader->xml($xml);

        $this->load->model("ProductModel");
        $i = 0;
        while ($reader->read() !== FALSE) {
            if ($reader->name === 'opis' && $reader->nodeType === XMLReader::ELEMENT) {
              $desc = $reader->getAttribute('Opis');
              $ext_id = $reader->getAttribute('ID_TOW');
              $product_f = $this->ProductModel->findOneByExternalId($ext_id);
              if(empty($product_f->id)){ continue; }
              if(!empty($desc)){
                  $trans = $product_f->getTranslation('pl');
//                  if(!empty($trans->body)){ continue; } 
                  $start =  strpos($desc, "<body>");
                  $end =  strpos($desc,"</body>");

                  $opis = substr($desc, $start+6, $end-($start+6));
                  
                  
                  $trans->body = $opis;
                  $trans->update();
                  echo 'Dodano opis do '. $i++ . '-tego produktu.<br>';
              }
              
            }
        }
    }
    public function descriptions2(){
            $xml = $this->get_file('opis.xml');
        
        $reader = new XMLReader;

        $reader->xml($xml);

        $this->load->model("ProductModel");
        $i = 0;
        while ($reader->read() !== FALSE) {
            if ($reader->name === 'opis' && $reader->nodeType === XMLReader::ELEMENT) {
              $desc = $reader->getAttribute('Opis');
              $ext_id = $reader->getAttribute('ID_TOW');
              //$product_f = $this->ProductModel->findOneByExternalId($ext_id);
            //  if(empty($product_f->id)){ continue; }
              if(!empty($desc)){
                 // $trans = $product_f->getTranslation('pl');
//                  if(!empty($trans->body)){ continue; } 
                  $start =  strpos($desc, "<body>");
                  $end =  strpos($desc,"</body>");

                  $opis = addslashes(substr($desc, $start+6, $end-($start+6)));
                  
                  $sql = "UPDATE `duo_products_translations` SET `body` = '$opis' WHERE product_id IN (SELECT `id` FROM `duo_products` WHERE `external_id` = '$ext_id' )";
                  
                  $this->db->query($sql);
                 // $trans->body = $opis;
                  //$trans->update();
                  echo 'Dodano opis do '. $i++ . '-tego produktu.<br>';
              }
              
            }
        }
    }
    public function categories() {
        
         $xml = $this->get_file('grupa.xml');
        $reader = new XMLReader;
        $reader->xml($xml);
       // $path = site_url("uploads/bilans/grupa.xml");
//        echo $path; die();
       // $reader->open($path);
        $this->load->model("OfferCategoryModel");

        while ($reader->read() !== FALSE) {
            if ($reader->name === 'grupa' && $reader->nodeType === XMLReader::ELEMENT) {
                if (!empty($reader->getAttribute('Nazwa'))) {
                    $ext_id = $reader->getAttribute('ID_GRE');
                    $ext_parent_id = $reader->getAttribute('ID_GRE_M'); // - 1 for top-categories
                    $name = $reader->getAttribute('Nazwa');
                    $order = $reader->getAttribute('Pozycja');
                    $modified = $reader->getAttribute('Czas_modyfikacji');
                    $image = $reader->getAttribute('IMG');
                    $description = $reader->getAttribute('Opis');
                    
                    $category = $this->OfferCategoryModel->findByExternalId($ext_id);
                    if (empty($category)) {
                        $category = new OfferCategoryModel();
                        $category->external_id = $ext_id;
                        $category->external_updated = $modified;
                        $category->order = !empty($order) ? $order : 0;
                        $category->image = "http://www.eurosan.pl/b2b/images/" . $image;
                        if ($ext_parent_id == -1) {
                            $category->parent_id = null;
                        } else {
                            $pcategory = $this->OfferCategoryModel->findByExternalId($ext_parent_id);
                            $category->parent_id = $pcategory->id;
                        }

                        $category->insert_category();

                        $this->load->model('OfferCategoryTranslationModel');



                        $translation = new OfferCategoryTranslationModel();
                        $translation->offer_category_id = $category->id;
                        $translation->lang = 'pl';
                        $translation->name = $name;
                        $translation->body = $description;
                        $translation->meta_title = '';
                        $translation->meta_description = '';
                        $translation->custom_url = '';
                        $translation->insert();
                    } else {
                        if(strtotime($modified) > strtotime($category->external_updated)){
                            $category = new OfferCategoryModel($category->id);
                         $category->external_updated = $modified;
                        $category->order = !empty($order) ? $order : 0;
                        $category->image = "http://www.eurosan.pl/b2b/images/" . $image;
                        if ($ext_parent_id == -1) {
                            $category->parent_id = null;
                        } else {
                            $pcategory = $this->OfferCategoryModel->findByExternalId($ext_parent_id);
                            $category->parent_id = $pcategory->id;
                        }
                        
                        $category->update_category();
                        $translation = $category->getTranslation('pl');
                        $translation->name = $name;
                        if(!empty($description)){
                            $translation->body = $description;
                        }
                        
                        $translation->update();
                        
                        }
                    }

                    echo $name . "<br>";
                    echo $modified . "<br>";
                    echo $ext_parent_id . "<br>";
                    echo $ext_id . "<br>";
                    echo $order . '<br>';
                    echo "<hr>";
                }
            }
        }
    }
public function attributes_matching(){
         $xml = $this->get_file('towar.xml');
        $reader = new XMLReader;
        $reader->xml($xml);
        //$path = site_url("uploads/bilans/towar2.xml");
//        echo $path; die();
        //$reader->open($path);
        $this->load->model("ProductModel");
        $this->load->model("ProductAttributesModel");
        
        $i = 0;
        
        while ($reader->read() !== FALSE) {
            if ($reader->name === 'tow' && $reader->nodeType === XMLReader::ELEMENT) {
                $i++;
//                if($i < 2959){ 
//                    continue;
//                }
                $ext_id = $reader->getAttribute('ID_TOW');
                $product_f = $this->ProductModel->findOneByExternalId($ext_id);
                
               if(!empty($product_f->id)){
           
                    $a = new stdClass();
                   //Szerokość="" Długość="" Wysokość=""
                    $a->szer = $reader->getAttribute('Szerokość');
                    $a->dlug = $reader->getAttribute('Długość');
                    $a->wys = $reader->getAttribute('Wysokość');
                    $a->type = $reader->getAttribute('Typ');   //  attr
                    $a->material = $reader->getAttribute('Materiał'); // attr
                    $a->color = $reader->getAttribute('Kolor'); // attr
                    $a->collection = $reader->getAttribute('Kolekcja'); // attr                   
                    $a->producent = $reader->getAttribute('Producent');
                    if(!empty($a->szer)){
                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->szer, 60);
                        var_dump($attr_id);
                        if(empty($attr_id)){
                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 60);
                            $attr_args = array('pl' => ['name' => $a->szer, 'description' => '']);
                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 60); 
                        }
                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
                        echo "<br>";
                    }
                    if(!empty($a->dlug)){
                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->dlug, 7);
                        var_dump($attr_id);
                        if(empty($attr_id)){
                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 7);
                            $attr_args = array('pl' => ['name' => $a->dlug, 'description' => '']);
                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 7);
                        }
                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
                        echo "<br>";
                    }
                    if(!empty($a->wys)){
                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->wys, 86);
                        var_dump($attr_id);
                        if(empty($attr_id)){
                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 86);
                            $attr_args = array('pl' => ['name' => $a->wys, 'description' => '']);
                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 86);
                        }
                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
                        echo "<br>";
                    }
                    if(!empty($a->type)){
                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->type, 68);
                        var_dump($attr_id);
                        if(empty($attr_id)){
                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 68);
                            $attr_args = array('pl' => ['name' => $a->type, 'description' => '']);
                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 68);
                        }
                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
                        echo "<br>";
                    }
                    if(!empty($a->material)){
                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->material, 25);
                        var_dump($attr_id);
                        if(empty($attr_id)){
                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 25);
                            $attr_args = array('pl' => ['name' => $a->material, 'description' => '']);
                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 25);
                        }
                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
                        echo "<br>";
                    }
                    if(!empty($a->color)){
                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->color, 19);
                        var_dump($attr_id);
                        if(empty($attr_id)){
                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 19);
                            $attr_args = array('pl' => ['name' => $a->color, 'description' => '']);
                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 19);
                        }
                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
                        echo "<br>";
                    }
                    if(!empty($a->collection)){
                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->collection, 18);
                        var_dump($attr_id);
                        if(empty($attr_id)){
                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 18);
                            $attr_args = array('pl' => ['name' => $a->collection, 'description' => '']);
                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 18);
                        }
                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
                        echo "<br>";
                    }
                    if(!empty($a->producent)){
                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->producent, 96);
                        var_dump($attr_id);
                        if(empty($attr_id)){
                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 96);
                            $attr_args = array('pl' => ['name' => $a->producent, 'description' => '']);
                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 96);
                        }
                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
                        echo "<br>";
                    }
                    echo 'Produkt '. $i . '-ty przetworzony.';
                    echo '<hr>';
                }
            }
        }
    }
    
    public function photos(){
           $xml = $this->get_file('towar.xml');
        $reader = new XMLReader;
        $reader->xml($xml);
//        echo $path; die();
        //$reader->open($path);
        $this->load->model("OfferCategoryModel");
        $this->load->model("ProductModel");
        $this->load->model("ProductTranslationModel");
        $i = 0;
        while ($reader->read() !== FALSE) {
            if ($reader->name === 'tow' && $reader->nodeType === XMLReader::ELEMENT) {
                $ext_id = $reader->getAttribute('ID_TOW');
                $product_f = $this->ProductModel->findOneByExternalId($ext_id);
                
               if(!empty($product_f->id)){
                    $photos_string = $reader->getAttribute('Zdjęcia');
                    if(!empty($photos_string)){
                        $photo_data = explode(';', $photos_string);
                        $modified = $photo_data[0];
                        
                        $mod_time = strtotime($modified);
                        if($mod_time > strtotime($product_f->findPhotoModifyDate())){
                            $product_f->deleteAllExternalPhotos();
                            for($i = 1; $i < count($photo_data); $i++){
                                 echo $photo_data[$i]."<br>";
                                 $product_f->addExternalPhoto($photo_data[$i], date('Y-m-d H:i:s', $mod_time));
                            }
                            echo '<hr>';
                        }
                    }
                }
            }
        }
    }
     public function photos1337(){
        $reader = new XMLReader;
        $path = site_url("uploads/bilans/towar14.xml");
//        echo $path; die();
        $reader->open($path);
     //   $reader->xml($xml);
        $this->load->model("OfferCategoryModel");
        $this->load->model("ProductModel");
        $this->load->model("ProductTranslationModel");
        $i = 0;
        while ($reader->read() !== FALSE) {
            if ($reader->name === 'tow' && $reader->nodeType === XMLReader::ELEMENT) {
                $ext_id = $reader->getAttribute('ID_TOW');
                $product_f = $this->ProductModel->findOneByExternalId($ext_id);
                
               if(!empty($product_f->id)){
                    $photos_string = $reader->getAttribute('Zdjęcia');
                    if(!empty($photos_string)){
                        $photo_data = explode(';', $photos_string);
                        $modified = $photo_data[0];
                        
                        $mod_time = strtotime($modified);
                        if($mod_time > strtotime($product_f->findPhotoModifyDate())){
                            $product_f->deleteAllExternalPhotos();
                            for($i = 1; $i < count($photo_data); $i++){
                                 echo $photo_data[$i]."<br>";
                                 $product_f->addExternalPhoto($photo_data[$i], date('Y-m-d H:i:s', $mod_time));
                            }
                            echo '<hr>';
                        }
                    }
                }
            }
        }
    }
    public function download_photos(){
        $this->load->model("ProductModel");
        $this->load->model("productPhotoModel");
        
        $products = $this->ProductModel->findAll();
        
        foreach ($products as $p) {
            $ephotos = $p->findAllExternalPhotos();
            if (!empty($ephotos)) {
                foreach ($ephotos as $ep) {
                    $photo = new ProductPhotoModel();
                    $photo->product_id = $p->id;
                    $photo->insert();
                    $photo->save_from_remote_url("http://www.eurosan.pl/b2b/images/" . $ep->name);
                    
                    $this->db->where('id', $ep->id);
                    $this->db->update('duo_product_external_photos', [ 'photo_id' => $photo->id ]);
                    echo 'dodano<br>';
                }
            }
        }
        echo 'skończono<br>';
    }
    
    public function update_status_old20191125() {
        $xml = $this->get_file('stan.xml');
        $reader = new XMLReader;
        $reader->xml($xml);
        $this->load->model('ProductModel');
        $this->load->model('CustomFieldsModel');
        while ($reader->read() !== FALSE) {
            if ($reader->name === 'stan' && $reader->nodeType === XMLReader::ELEMENT) {
                $ext_id = $reader->getAttribute('ID_TOW');
                $product = $this->ProductModel->findOneByExternalId($ext_id);

                if (!empty($product->id)) {
                    $a = new stdClass();
                    $a->promo = $reader->getAttribute('Promocja'); // jest
                    $a->brutto_a = $reader->getAttribute('Brutto_A'); // custom field
                    $a->brutto = $reader->getAttribute('Brutto'); // jest
                    $a->stan = $reader->getAttribute('Stan');
                    
                    
                    $product->promo = $a->promo == "T" ? 1 : 0;
                    $product->prices[1] = $a->brutto;
                    $product->quantity = $a->stan;
                    $product->update_product();
                    $this->CustomFieldsModel->save_field(6, $product->id, $a->brutto_a, 'pl');
                    
                    echo 'produkt uaktualniony';
                } else {
                    echo 'produkt nie odnaleziony';
                }
                echo '<hr>';
            }
        }
    }
    
    public function update_status(){
        echo "Start: " . date('H:i:s') . '<br>';
        $start_time = date('U');
        $start_i = 0;
        $xml = $this->get_file('stan.xml');
        $reader = new XMLReader;
        $reader->xml($xml);
        $this->load->model('ProductModel');
        $this->load->model('CustomFieldsModel');
        
        //Robisz dwie tablice jedną na external ideki a drugą na dane, które następnie zapiszemy
        $ids = [];
        $datas = [];
        $i = 0;
        $products = [];
        while ($reader->read() !== FALSE) {
            $start_i++;
            if ($reader->name === 'stan' && $reader->nodeType === XMLReader::ELEMENT) {
                $ext_id = (int)$reader->getAttribute('ID_TOW');
                if(!empty($ext_id)){
                    $i++;
                    $ids[] = $ext_id;
                    $datas[$ext_id] = [
                        'promo' => $reader->getAttribute('Promocja'), // jest
                        'brutto_a' => $reader->getAttribute('Brutto_A'), // custom field
                        'brutto' => $reader->getAttribute('Brutto'), // jest
                        'stan' => $reader->getAttribute('Stan')
                    ];
                }
            }
            if($i > 1000){
                $products = array_merge($products, $this->ProductModel->findAllByExternalIds($ids));
                $ids = [];
                $i = 0;
            }
        }
        $j = 0;
        foreach ($products as $product){
            if($product->promo != ($datas[$product->external_id]['promo'] == "T" ? 1 : 0) || $product->price != $datas[$product->external_id]['brutto']*1 || 
                    $product->quantity != $datas[$product->external_id]['stan'] || $product->brutto_a != $datas[$product->external_id]['brutto_a']*1){
               $updatedProduct = $this->ProductModel->findOneByExternalId($product->external_id);
               $updatedProduct->promo = $datas[$product->external_id]['promo'] == "T" ? 1 : 0;
               $updatedProduct->prices[1] = $datas[$product->external_id]['brutto'];
               $updatedProduct->quantity = $datas[$product->external_id]['stan'];
               $updatedProduct->update_product();
               $this->CustomFieldsModel->save_field(6, $product->id, $datas[$product->external_id]['brutto_a'], 'pl');
               $j++;
               echo $updatedProduct->id . ' - ' . json_encode($datas[$product->external_id]) . ' - - - - '. json_encode($product) . '<br>';
            }
        }

        echo "Znalezionych produktów: " . count($products) . "<br>";
        echo "Rekordów: ".  $start_i . '<br>';
        echo "Zaminy potrzebuje: " . $j . '<br>';
        echo "Wykonano w: " . (date('U') - $start_time) . '<br>';
        echo "Stop: " . date('H:i:s') . '<br>';
    }
    
 public function update_status2() {
        $xml = $this->get_file('stan.xml');
        $reader = new XMLReader;
        $reader->xml($xml);
        $this->load->model('ProductModel');
        $this->load->model('CustomFieldsModel');
        while ($reader->read() !== FALSE) {
            if ($reader->name === 'stan' && $reader->nodeType === XMLReader::ELEMENT) {
                $ext_id = $reader->getAttribute('ID_TOW');
                if(empty($ext_id)) { continue; }
                //$product = $this->ProductModel->findOneByExternalId($ext_id);
                $sql_1 = "SELECT id FROM duo_products WHERE external_id = ". $ext_id . ";";
                
                $res_1 = $this->db->query($sql_1)->result();
                if(!empty($res_1[0])){
                    $duo_id = $res_1[0]->id;
                //echo $duo_id.'<br>';
                
                $promo = $reader->getAttribute('Promocja') == "T" ? 1 : 0;
                $stan = $reader->getAttribute('Stan');
                $stan1 = !empty($stan) ? $stan : 0;
                $brutto = $reader->getAttribute('Brutto');
                $brutto1 = !empty($brutto) ? $brutto : 0;
                $brutto_a = $reader->getAttribute('Brutto_A');
                $brutto_a1 = !empty($brutto_a) ? $brutto_a : 0;
                
                $sql_2 =" UPDATE `duo_products` SET `promo`= " . $promo . ", `quantity`= ".$stan1 . " WHERE id = " .$duo_id."; ";
                $sql_3 =" UPDATE `duo_product_prices` SET `price` = ". $brutto1 ." WHERE product_id = ". $duo_id ." ; ";
                $sql_4 =" UPDATE `duo_custom_fields_translations` SET `value` = ".$brutto_a1 ." WHERE field_id = 6 AND element_id_translation = ". $duo_id.' ;';
                
                $this->db->query($sql_2 //.$sql_3.$sql_4);
                        );
                $this->db->query($sql_3); $this->db->query($sql_4);
                
                }
//                if (!empty($product->id)) {
//                    $a = new stdClass();
//                    $a->promo = $reader->getAttribute('Promocja'); // jest
//                    $a->brutto_a = $reader->getAttribute('Brutto_A'); // custom field
//                    $a->brutto = $reader->getAttribute('Brutto'); // jest
//                    $a->stan = $reader->getAttribute('Stan');
//                    
//                    
//                    $product->promo = $a->promo == "T" ? 1 : 0;
//                    $product->prices[1] = $a->brutto;
//                    $product->quantity = $a->stan;
//                    $product->update_product();
//                    $this->CustomFieldsModel->save_field(6, $product->id, $a->brutto_a, 'pl');
//                    
//                    echo 'produkt uaktualniony';
//                } else {
//                    echo 'produkt nie odnaleziony';
//                }
                echo '<hr>';
            }
        }
    }
    public function update_status3() {
        $xml = $this->get_file('stan.xml');
        $reader = new XMLReader;
        $reader->xml($xml);
        $this->load->model('ProductModel');
        $this->load->model('CustomFieldsModel');
        while ($reader->read() !== FALSE) {
            if ($reader->name === 'stan' && $reader->nodeType === XMLReader::ELEMENT) {
                $ext_id = $reader->getAttribute('ID_TOW');
                if(empty($ext_id)) { continue; }
                $promo = $reader->getAttribute('Promocja') == "T" ? 1 : 0;
                $stan = $reader->getAttribute('Stan');
                $brutto = $reader->getAttribute('Brutto');
                $brutto_a = $reader->getAttribute('Brutto_A');
                echo $ext_id.'<br>';
                //$product = $this->ProductModel->findOneByExternalId($ext_id);
                $sql_1 = "SELECT duo_products.id as id1 FROM duo_products "
                        . " JOIN duo_product_prices on duo_product_prices.product_id = duo_products.id "
                        . " JOIN duo_custom_fields_translations on duo_custom_fields_translations.element_id_translation = duo_products.id "
                        . " WHERE duo_products.external_id = ". $ext_id . " "
                        . " AND (duo_products.promo <> '$promo' OR duo_products.quantity <> '$stan' "
                        . " OR duo_product_prices.price <> '$brutto' OR (duo_custom_fields_translations.value <> '$brutto_a' AND duo_custom_fields_translations.field_id = 6 ) );";
                
                echo $sql_1.'<br>';
//                $res_1 = $this->db->query($sql_1)->result();
//                if(!empty($res_1[0])){
//                    $duo_id = $res_1[0]->id1;
//                echo $duo_id.'<br>';
                
                
//                $stan1 = !empty($stan) ? $stan : 0;
//                $brutto1 = !empty($brutto) ? $brutto : 0;
//                $brutto_a1 = !empty($brutto_a) ? $brutto_a : 0;
//                
//                $sql_2 =" UPDATE `duo_products` SET `promo`= " . $promo . ", `quantity`= ".$stan1 . " WHERE id = " .$duo_id."; ";
//                $sql_3 =" UPDATE `duo_product_prices` SET `price` = ". $brutto1 ." WHERE product_id = ". $duo_id ." ; ";
//                $sql_4 =" UPDATE `duo_custom_fields_translations` SET `value` = ".$brutto_a1 ." WHERE field_id = 6 AND element_id_translation = ". $duo_id.' ;';
//                
//                $this->db->query($sql_2 //.$sql_3.$sql_4);
//                        );
//                $this->db->query($sql_3); $this->db->query($sql_4);
                
//                }
//                if (!empty($product->id)) {
//                    $a = new stdClass();
//                    $a->promo = $reader->getAttribute('Promocja'); // jest
//                    $a->brutto_a = $reader->getAttribute('Brutto_A'); // custom field
//                    $a->brutto = $reader->getAttribute('Brutto'); // jest
//                    $a->stan = $reader->getAttribute('Stan');
//                    
//                    
//                    $product->promo = $a->promo == "T" ? 1 : 0;
//                    $product->prices[1] = $a->brutto;
//                    $product->quantity = $a->stan;
//                    $product->update_product();
//                    $this->CustomFieldsModel->save_field(6, $product->id, $a->brutto_a, 'pl');
//                    
//                    echo 'produkt uaktualniony';
//                } else {
//                    echo 'produkt nie odnaleziony';
//                }
                echo '<hr>';
            }
        }
    }
//    public function delete_omitted() {
//        $xml = $this->get_file('towar1.xml');
//
//        $reader = new XMLReader;
//        $reader->xml($xml);
//        $this->load->model("ProductModel");
//        $externals = [];
//        while ($reader->read() !== FALSE) {
//            if ($reader->name === 'tow' && $reader->nodeType === XMLReader::ELEMENT) {
//                $ext_id = $reader->getAttribute('ID_TOW');
//                if (!empty($ext_id)) {
//                    $externals[] = $ext_id;
//                }
//            }
//        }
//        $all_products = $this->ProductModel->findAll();
//        
//        foreach($all_products as $ap){
//            if(!in_array($ap->external_id, $externals)){
//                echo 'produkt o id '. $ap->id . ' do usunięcia<br>';
//                $ap->delete();
//            } else {
//                echo 'produkt o id '. $ap->id . ' zostaje<br>';
//            }
//        }
//    }
       public function update_products2() {

        $xml = $this->get_file('towar.xml');

        $reader = new XMLReader;
//        $path = site_url("uploads/bilans/towar2.xml");
//        echo $path; die();
//        $reader->open($path);
        $reader->xml($xml);
        $this->load->model("OfferCategoryModel");
        $this->load->model("ProductModel");
        $this->load->model("ProductTranslationModel");
        $this->load->model('CustomFieldsModel');
//        $i = 0;
        while ($reader->read() !== FALSE) {
            if ($reader->name === 'tow' && $reader->nodeType === XMLReader::ELEMENT) {
                $name = $reader->getAttribute('Nazwa');
                if (!empty($name)) {
                    $ext_id = $reader->getAttribute('ID_TOW');  // +1
                     if(empty($ext_id)) { continue; }
//                    $photos = $reader->getAttribute('Zdjęcia');   // dodatkowa tabela
//                    $type = $reader->getAttribute('Typ');   //  attr
//                    $material = $reader->getAttribute('Materiał'); // attr
//                    $color = $reader->getAttribute('Kolor'); // attr
//                    $collection = $reader->getAttribute('Kolekcja'); // attr
                    $weight = $reader->getAttribute('Ciężar');   // jest
                    $weight1 = !empty($weight) ? $weight : 0;
                    $vat = $reader->getAttribute('Stawka_VAT'); // +1
                    $vat1 = !empty($vat) ? $vat : 0;
                    $promo = $reader->getAttribute('Promocja') == "T" ? 1 : 0; // jest
                    $brutto_a = $reader->getAttribute('Brutto_A'); // custom field
                    $brutto_a1 = !empty($brutto_a) ? $brutto_a : 0;
                    $brutto = $reader->getAttribute('Brutto'); // jest
                    $brutto1 = !empty($brutto) ? $brutto : 0;
                    $avaibility = $reader->getAttribute('Dostępność'); // +1 
                    $in_pack = $reader->getAttribute('Ilość_w_opakowaniu'); // +1
                    $always_avaible = $reader->getAttribute('Dostępny_zawsze') == "T" ? 1 : 0; // +1
                    $quantity = $reader->getAttribute('Stan'); // jest
                    $quantity1 = !empty($quantity) ? $quantity : 0;
                    $ean = $reader->getAttribute('EAN'); // +1
                    $code = $reader->getAttribute('Kod'); // jest
                    $ext_category_ids = $reader->getAttribute('ID_GRE'); // jest
                    $producent = $reader->getAttribute('Producent');
                    $tags = $reader->getAttribute('Słowa_kluczowe'); //Słowa_kluczowe

                $sql_1 = "SELECT id FROM duo_products WHERE external_id = ". $ext_id . ";";
                
                $res_1 = $this->db->query($sql_1)->result();
                if(empty($res_1[0])){
                   
                        
                        $sql_2 = "INSERT INTO `duo_products` (`external_id`, `weight`, `vat`, `promo`, `availability`, `number_in_package`, `always_avaible`, `quantity`, `ean`, `code`, `producent`, `tags` ) "
                                . " VALUES ($ext_id, '$weight1', '$vat1', $promo, '$avaibility', '$in_pack', $always_avaible, '$quantity1', '$ean', '$code', '$producent', '$tags' ); ";
                        $this->db->query($sql_2);
                        $duo_id = $this->db->insert_id();
                        if(empty($duo_id)){ continue; }

                        $ext_cats = explode(';', $ext_category_ids);
                        foreach ($ext_cats as $ex_c) {
                            $z_id =  $this->OfferCategoryModel->findByExternalId($ex_c)->id;
                            if(!empty($z_id)){
                                
                                $sql_category_ids = "INSERT INTO `duo_products_categories` (`product_id`, `category_id`, `created_at`)"
                                        . " VALUES ($duo_id, $z_id, '".(new DateTime())->format('Y-m-d H:i:s')."')";
                                $this->db->query($sql_category_ids);
                            }
                        }
                        
                         $sql_price =" INSERT INTO `duo_product_prices` (`product_id`, `currency_id`, `price`) "
                                 . " VALUSE ($duo_id, '1', '$brutto') ; ";
                         $this->db->query($sql_price);
//                        $product->categories = $categories;
//                        $product->producent = $a->producent;
//                        if (!empty($a->tags)) {
//                            $product->tags = $a->tags;
//                        }
//                        $product->insert_product();
//                    
                        $sql_trans = " INSERT INTO `duo_products_translations` (`product_id`, `lang`, `name`, `created_at`) "
                                . " VALUES ($duo_id, 'pl', '$name', '".(new DateTime())->format('Y-m-d H:i:s'). "'";
                        
                        $this->db->query($sql_trans);


                        //$this->CustomFieldsModel->save_field(6, $product->id, $a->brutto_a, 'pl');
                        
                         $sql_cena_przekreslona ="INSERT INTO `duo_custom_fields_translations`  (`field_id`, `element_id_translation`, `lang`, `value`, `created_at`) "
                                 . "VALUES (6, $duo_id, 'pl', '$brutto_a1', '".(new DateTime())->format('Y-m-d H:i:s')."') ";
                         $this->db->query($sql_cena_przekreslona);
                       // echo "<br> i dodano";
                    } else {
                         $duo_id = $res_1[0]->id;
                        echo 'znaleziono prduktu o external_id ' . $a->ext_id;
                        
                $sql_2 =" UPDATE `duo_products` SET `promo`= " . $promo . ", `quantity`= ".$quantity1 .", `weight` = $weight1,"
                        . " `vat` = $vat, `availability` = '$avaibility', `number_in_package` = '$in_pack',"
                        . " `always_avaible` = '$always_avaible', `quantity` = $quantity, `ean` = '$ean',"
                        . " `code` = '$code', `producent` = '$producent', `tags` = '$tags'  WHERE id = " .$duo_id."; ";
                $sql_3 =" UPDATE `duo_product_prices` SET `price` = ". $brutto1 ." WHERE product_id = ". $duo_id ." ; ";
                $sql_4 =" UPDATE `duo_custom_fields_translations` SET `value` = ".$brutto_a1 ." WHERE field_id = 6 AND element_id_translation = ". $duo_id.' ;';
                        
                       
                        $product->weight = $a->weight;
                        $product->vat = $a->vat;
                        $product->promo = $a->promo == "T" ? 1 : 0;
                        $product->prices[1] = $a->brutto;
                        $product->availability = $a->avaibility;
                        $product->number_in_package = $a->in_pack;
                        $product->always_avaible = $a->always_avaible == "T" ? 1 : 0;
                        $product->quantity = $a->quantity;
                        $product->ean = $a->ean;
                        $product->code = $a->code;
                        if (!empty($a->ext_category_ids)) {
                            $ext_cats = explode(';', $a->ext_category_ids);
                            $categories = [];
                            foreach ($ext_cats as $ex_c) {
                                if (!empty($ex_c)) {
                                   $z_id =  $this->OfferCategoryModel->findByExternalId($ex_c)->id;
                            if(!empty($z_id)){
                                $categories[] = $z_id;
                            }
                                }
                            }
                            $product->categories = $categories;
                        }
                        $product->producent = $a->producent;
                        if (!empty($a->tags)) {
                            $product->tags = $a->tags;
                        }
                        $product->update_product();

                        $translation = $product->getTranslation('pl');
                        if(!empty($translation)){
                            $translation->name = $name;
                            $translation->update();
                        } else {
                            $translation = new ProductTranslationModel();
                        $translation->product_id = $product->id;
                        $translation->lang = 'pl';
                        $translation->name = $name;
                        $translation->format = '';
                        $translation->slogan = '';
                        $translation->body = "";
                        $translation->meta_title = '';
                        $translation->meta_description = '';
                        $translation->custom_url = '';
                        $translation->insert();
                            
                        }
                    }

                    echo '<hr>';
                }
            }
        }
    }
}
