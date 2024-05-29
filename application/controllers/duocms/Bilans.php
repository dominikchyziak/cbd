<?php //

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bilans extends Backend_Controller {

    public function __construct() {
        parent::__construct();
    }
    

    
    public function index() {
//        $this->load->model("ProductModel");
//        $something = new ProductModel();
//        $something->delete();
    }

//    public function categories() {
//        $reader = new XMLReader;
//        $path = site_url("uploads/bilans/grupa.xml");
////        echo $path; die();
//        $reader->open($path);
//        $this->load->model("OfferCategoryModel");
//
//        while ($reader->read() !== FALSE) {
//            if ($reader->name === 'grupa' && $reader->nodeType === XMLReader::ELEMENT) {
//                if (!empty($reader->getAttribute('Nazwa'))) {
//                    $ext_id = $reader->getAttribute('ID_GRE');
//                    $ext_parent_id = $reader->getAttribute('ID_GRE_M'); // - 1 for top-categories
//                    $name = $reader->getAttribute('Nazwa');
//                    $order = $reader->getAttribute('Pozycja');
//                    $modified = $reader->getAttribute('Czas_modyfikacji');
//                    $image = $reader->getAttribute('IMG');
//                    $description = $reader->getAttribute('Opis');
//                    
//                    $category = $this->OfferCategoryModel->findByExternalId($ext_id);
//                    if (empty($category)) {
//                        $category = new OfferCategoryModel();
//                        $category->external_id = $ext_id;
//                        $category->external_updated = $modified;
//                        $category->order = !empty($order) ? $order : 0;
//                        $category->image = "http://www.eurosan.pl/b2b/images/" . $image;
//                        if ($ext_parent_id == -1) {
//                            $category->parent_id = null;
//                        } else {
//                            $pcategory = $this->OfferCategoryModel->findByExternalId($ext_parent_id);
//                            $category->parent_id = $pcategory->id;
//                        }
//
//                        $category->insert_category();
//
//                        $this->load->model('OfferCategoryTranslationModel');
//
//
//
//                        $translation = new OfferCategoryTranslationModel();
//                        $translation->offer_category_id = $category->id;
//                        $translation->lang = 'pl';
//                        $translation->name = $name;
//                        $translation->body = $description;
//                        $translation->meta_title = '';
//                        $translation->meta_description = '';
//                        $translation->custom_url = '';
//                        $translation->insert();
//                    } else {
//                        if(strtotime($modified) > strtotime($category->external_updated)){
//                         $category->external_updated = $modified;
//                        $category->order = !empty($order) ? $order : 0;
//                        $category->image = "http://www.eurosan.pl/b2b/images/" . $image;
//                        if ($ext_parent_id == -1) {
//                            $category->parent_id = null;
//                        } else {
//                            $pcategory = $this->OfferCategoryModel->findByExternalId($ext_parent_id);
//                            $category->parent_id = $pcategory->id;
//                        }
//                        
//                        $category->update_category();
//                        $translation = $category->getTranslation('pl');
//                        $translation->name = $name;
//                        if(!empty($description)){
//                            $translation->body = $description;
//                        }
//                        
//                        $translation->update();
//                        
//                        }
//                    }
//
//                    echo $name . "<br>";
//                    echo $modified . "<br>";
//                    echo $ext_parent_id . "<br>";
//                    echo $ext_id . "<br>";
//                    echo $order . '<br>';
//                    echo "<hr>";
//                }
//            }
//        }
//    }
//    public function products(){
//        $reader = new XMLReader;
//        $path = site_url("uploads/bilans/towar2.xml");
////        echo $path; die();
//        $reader->open($path);
//        $this->load->model("OfferCategoryModel");
//        $this->load->model("ProductModel");
//        $this->load->model("ProductTranslationModel");
//        $this->load->model('CustomFieldsModel');
//        $i = 0;
//        while ($reader->read() !== FALSE) {
//            if ($reader->name === 'tow' && $reader->nodeType === XMLReader::ELEMENT) {
//                $name = $reader->getAttribute('Nazwa');
//                if(!empty($name)){
//                    $a = new stdClass();
//                    $a->ext_id = $reader->getAttribute('ID_TOW');  // +1
//                    $a->photos = $reader->getAttribute('Zdjęcia');   // dodatkowa tabela
//                    $a->type = $reader->getAttribute('Typ');   //  attr
//                    $a->material = $reader->getAttribute('Materiał'); // attr
//                    $a->color = $reader->getAttribute('Kolor'); // attr
//                    $a->collection = $reader->getAttribute('Kolekcja'); // attr
//                    $a->weight = $reader->getAttribute('Ciężar');   // jest
//                    $a->vat = $reader->getAttribute('Stawka_VAT'); // +1
//                    $a->promo = $reader->getAttribute('Promocja'); // jest
//                    $a->brutto_a = $reader->getAttribute('Brutto_A'); // custom field
//                    $a->brutto = $reader->getAttribute('Brutto'); // jest
//                    $a->avaibility = $reader->getAttribute('Dostępność'); // +1 
//                    $a->in_pack = $reader->getAttribute('Ilość_w_opakowaniu'); // +1
//                    $a->always_avaible = $reader->getAttribute('Dostępny_zawsze'); // +1
//                    $a->quantity = $reader->getAttribute('Stan'); // jest
//                    $a->ean = $reader->getAttribute('EAN'); // +1
//                    $a->code = $reader->getAttribute('Kod'); // jest
//                    $a->ext_category_ids = $reader->getAttribute('ID_GRE'); // jest
//                    $a->producent = $reader->getAttribute('Producent');
//                    echo $i++.'<br>';
//                    echo $name.'<br>';
//                    $ext_cats = explode(';', $a->ext_category_ids);
//                    $categories = [];
//                    foreach($ext_cats as $ex_c){
//                        $categories[] = $this->OfferCategoryModel->findByExternalId($ex_c)->id;
//                    }
//                    $product_f = $this->ProductModel->findOneByExternalId($a->ext_id);
////                    var_dump($product_f);
//                    if(empty($product_f->id)){
//                     echo 'nie znaleziono prduktu o external_id '. $a->ext_id;
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
//                    $product->categories = $categories;
//                    $product->producent = $a->producent;
//                    $product->insert_product();
//                    
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
//                    } else {
//                         echo 'znaleziono prduktu o external_id '. $a->ext_id;
//                    $product = $product_f;
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
//                    $product->categories = $categories;
//                    $product->producent = $a->producent;
//                    $product->update_product();
//                    
//                    $translation = $product->getTranslation('pl');
//                    $translation->name = $name;
//                    $translation->format = '';
//                    $translation->slogan = '';
//                    $translation->body = "";
//                    $translation->meta_title = '';
//                    $translation->meta_description = '';
//                    $translation->custom_url = '';
//                    $translation->update();
//                    }
//                    
//                    echo '<hr>';
//                }
//            }
//            }
//    }
//    public function attributes_matching(){
//        $reader = new XMLReader;
//        $path = site_url("uploads/bilans/towar2.xml");
////        echo $path; die();
//        $reader->open($path);
//        $this->load->model("ProductModel");
//        $this->load->model("ProductAttributesModel");
//        
//        $i = 0;
//        
//        while ($reader->read() !== FALSE) {
//            if ($reader->name === 'tow' && $reader->nodeType === XMLReader::ELEMENT) {
//                $i++;
////                if($i < 2959){ 
////                    continue;
////                }
//                $ext_id = $reader->getAttribute('ID_TOW');
//                $product_f = $this->ProductModel->findOneByExternalId($ext_id);
//                
//               if(!empty($product_f->id)){
//           
//                    $a = new stdClass();
//                   //Szerokość="" Długość="" Wysokość=""
//                    $a->szer = $reader->getAttribute('Szerokość');
//                    $a->dlug = $reader->getAttribute('Długość');
//                    $a->wys = $reader->getAttribute('Wysokość');
//                    $a->type = $reader->getAttribute('Typ');   //  attr
//                    $a->material = $reader->getAttribute('Materiał'); // attr
//                    $a->color = $reader->getAttribute('Kolor'); // attr
//                    $a->collection = $reader->getAttribute('Kolekcja'); // attr                   
//                    $a->producent = $reader->getAttribute('Producent');
//                    if(!empty($a->szer)){
//                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->szer, 60);
//                        var_dump($attr_id);
//                        if(empty($attr_id)){
//                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 60);
//                            $attr_args = array('pl' => ['name' => $a->szer, 'description' => '']);
//                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 60);
//                            
//                            
//                        }
//                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
//                        echo "<br>";
//                    }
//                    if(!empty($a->dlug)){
//                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->dlug, 7);
//                        var_dump($attr_id);
//                        if(empty($attr_id)){
//                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 7);
//                            $attr_args = array('pl' => ['name' => $a->dlug, 'description' => '']);
//                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 7);
//                        }
//                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
//                        echo "<br>";
//                    }
//                    if(!empty($a->wys)){
//                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->wys, 86);
//                        var_dump($attr_id);
//                        if(empty($attr_id)){
//                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 86);
//                            $attr_args = array('pl' => ['name' => $a->wys, 'description' => '']);
//                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 86);
//                        }
//                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
//                        echo "<br>";
//                    }
//                    if(!empty($a->typ)){
//                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->typ, 68);
//                        var_dump($attr_id);
//                        if(empty($attr_id)){
//                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 68);
//                            $attr_args = array('pl' => ['name' => $a->typ, 'description' => '']);
//                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 68);
//                        }
//                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
//                        echo "<br>";
//                    }
//                    if(!empty($a->material)){
//                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->material, 25);
//                        var_dump($attr_id);
//                        if(empty($attr_id)){
//                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 25);
//                            $attr_args = array('pl' => ['name' => $a->material, 'description' => '']);
//                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 25);
//                        }
//                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
//                        echo "<br>";
//                    }
//                    if(!empty($a->color)){
//                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->color, 19);
//                        var_dump($attr_id);
//                        if(empty($attr_id)){
//                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 19);
//                            $attr_args = array('pl' => ['name' => $a->color, 'description' => '']);
//                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 19);
//                        }
//                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
//                        echo "<br>";
//                    }
//                    if(!empty($a->collection)){
//                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->collection, 18);
//                        var_dump($attr_id);
//                        if(empty($attr_id)){
//                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 18);
//                            $attr_args = array('pl' => ['name' => $a->collection, 'description' => '']);
//                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 18);
//                        }
//                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
//                        echo "<br>";
//                    }
//                    if(!empty($a->producent)){
//                        $attr_id = $this->ProductAttributesModel->find_attribute_by_name_and_group_id($a->producent, 96);
//                        var_dump($attr_id);
//                        if(empty($attr_id)){
//                            $attr_id = $this->ProductAttributesModel->attribute_add(0, 96);
//                            $attr_args = array('pl' => ['name' => $a->producent, 'description' => '']);
//                            $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, 96);
//                        }
//                        $product_f->attribute_add_to_product($attr_id, $product_f->id, 0);
//                        echo "<br>";
//                    }
//                    echo 'Produkt '. $i . '-ty przetworzony.';
//                    echo '<hr>';
//                }
//            }
//        }
//    }
//    public function photos(){
//        $reader = new XMLReader;
//        $path = site_url("uploads/bilans/towar2.xml");
////        echo $path; die();
//        $reader->open($path);
//        $this->load->model("OfferCategoryModel");
//        $this->load->model("ProductModel");
//        $this->load->model("ProductTranslationModel");
//        $i = 0;
//        while ($reader->read() !== FALSE) {
//            if ($reader->name === 'tow' && $reader->nodeType === XMLReader::ELEMENT) {
//                $ext_id = $reader->getAttribute('ID_TOW');
//                $product_f = $this->ProductModel->findOneByExternalId($ext_id);
//                
//               if(!empty($product_f->id)){
//                    $photos_string = $reader->getAttribute('Zdjęcia');
//                    if(!empty($photos_string)){
//                        $photo_data = explode(';', $photos_string);
//                        $modified = $photo_data[0];
//                        
//                        $mod_time = strtotime($modified);
//                        if($mod_time > strtotime($product_f->findPhotoModifyDate())){
//                            $product_f->deleteAllExternalPhotos();
//                        for($i = 1; $i < count($photo_data); $i++){
//                             echo $photo_data[$i]."<br>";
//                             $product_f->addExternalPhoto($photo_data[$i], date('Y-m-d H:i:s', $mod_time));
//                        }
//                        echo '<hr>';
//                        }
//                    }
//                }
//            }
//        }
//    }
//    
//    public function download_photos(){
//        $this->load->model("ProductModel");
//        $this->load->model("productPhotoModel");
//        
//        $products = $this->ProductModel->findAll();
//        
//        foreach ($products as $p) {
//            $ephotos = $p->findAllExternalPhotos();
//            if (!empty($ephotos)) {
//                foreach ($ephotos as $ep) {
//                    $photo = new ProductPhotoModel();
//                    $photo->product_id = $p->id;
//                    $photo->insert();
//                    $photo->save_from_remote_url("http://www.eurosan.pl/b2b/images/" . $ep->name);
//                    
//                    $this->db->where('id', $ep->id);
//                    $this->db->update('duo_product_external_photos', [ 'photo_id' => $photo->id ]);
//                }
//            }
//        }
//    }
//    
//    public function attributes(){
//         $reader = new XMLReader;
//        $path = site_url("uploads/bilans/cechy.xml");
////        echo $path; die();
//        $reader->open($path);
//        $this->load->model("ProductAttributesModel");
//        while ($reader->read() !== FALSE) {
//            if ($reader->name === 'ROW' && $reader->nodeType === XMLReader::ELEMENT) {
//                $category = $reader->getAttribute('CECHA');
//                $value = $reader->getAttribute('WARTOSC');
//                echo $category . " - " . $value. "<br>";
//               
//                $cat_id = $this->ProductAttributesModel->find_group_by_name($category);
//                var_dump($cat_id); echo '<br>';
//                if(empty($cat_id)){
//                    $args = [];
//                    $args['translations'][] = [
//                    'lang' => 'pl',
//                    'name' => $category
//                        ];     
//                    $this->ProductAttributesModel->add_group($args);
//                    $cat_id = $this->ProductAttributesModel->find_group_by_name($category); 
//                    echo $cat_id."<hr>";
//                }
////                
//                $attr_id = $this->ProductAttributesModel->attribute_add(0, $cat_id);
//                $attr_args = array('pl' => ['name' => $value, 'description' => '']);
//                $this->ProductAttributesModel->attribute_update($attr_id, 0, $attr_args, $cat_id);
//            }
//        }
//    }
//    
//    public function descriptions(){
//         $reader = new XMLReader;
//        $path = site_url("uploads/bilans/opis.xml");
////        echo $path; die();
//        $reader->open($path);
//        $this->load->model("ProductModel");
//        $i = 0;
//        while ($reader->read() !== FALSE) {
//            if ($reader->name === 'opis' && $reader->nodeType === XMLReader::ELEMENT) {
//              $desc = $reader->getAttribute('Opis');
//              $ext_id = $reader->getAttribute('ID_TOW');
//              $product_f = $this->ProductModel->findOneByExternalId($ext_id);
//              if(empty($product_f->id)){ continue; }
//              if(!empty($desc)){
//                  $start =  strpos($desc, "<body>");
//                  $end =  strpos($desc,"</body>");
//
//                  $opis = substr($desc, $start+6, $end-($start+6));
//                  
//                  $trans = $product_f->getTranslation('pl');
//                  $trans->body = $opis;
//                  $trans->update();
//                  echo 'Dodano opis do '. $i++ . '-tego produktu.<br>';
//              }
//              
//            }
//        }
//    }
//    
//    
//    
//     public function prices(){
//        $reader = new XMLReader;
//        $path = site_url("uploads/bilans/towar2.xml");
////        echo $path; die();
//        $reader->open($path);
//        $this->load->model("ProductModel");
//        $this->load->model('CustomFieldsModel');
//        $i = 0;
//        while ($reader->read() !== FALSE) {
//            if ($reader->name === 'tow' && $reader->nodeType === XMLReader::ELEMENT) {
//                $name = $reader->getAttribute('Nazwa');
//                if(!empty($name)){
//                    $a = new stdClass();
//                    $a->ext_id = $reader->getAttribute('ID_TOW');  // +1
////                    $a->photos = $reader->getAttribute('Zdjęcia');   // dodatkowa tabela
////                    $a->type = $reader->getAttribute('Typ');   //  attr
////                    $a->material = $reader->getAttribute('Materiał'); // attr
////                    $a->color = $reader->getAttribute('Kolor'); // attr
////                    $a->collection = $reader->getAttribute('Kolekcja'); // attr
////                    $a->weight = $reader->getAttribute('Ciężar');   // jest
////                    $a->vat = $reader->getAttribute('Stawka_VAT'); // +1
////                    $a->promo = $reader->getAttribute('Promocja'); // jest
//                    $a->brutto_a = $reader->getAttribute('Brutto_A'); // custom field
//                    $a->brutto = $reader->getAttribute('Brutto'); // jest
//                    $a->avaibility = $reader->getAttribute('Dostępność'); // +1 
////                    $a->in_pack = $reader->getAttribute('Ilość_w_opakowaniu'); // +1
////                    $a->always_avaible = $reader->getAttribute('Dostępny_zawsze'); // +1
////                    $a->quantity = $reader->getAttribute('Stan'); // jest
////                    $a->ean = $reader->getAttribute('EAN'); // +1
////                    $a->code = $reader->getAttribute('Kod'); // jest
////                    $a->ext_category_ids = $reader->getAttribute('ID_GRE'); // jest
////                    $a->producent = $reader->getAttribute('Producent');
//                    echo $i++.'<br>';
//                    echo $name.'<br>';
////                    $ext_cats = explode(';', $a->ext_category_ids);
////                    $categories = [];
////                    foreach($ext_cats as $ex_c){
////                        $categories[] = $this->OfferCategoryModel->findByExternalId($ex_c)->id;
////                    }
//                    $product_f = $this->ProductModel->findOneByExternalId($a->ext_id);
////                    var_dump($product_f);
//                    if(empty($product_f->id)){
//                     echo 'nie znaleziono prduktu o external_id '. $a->ext_id;
////                    $product = new ProductModel();
////                    $product->external_id = $a->ext_id;
////                    $product->weight = $a->weight;
////                    $product->vat = $a->vat;
////                    $product->promo = $a->promo == "T" ? 1 : 0;
////                    $product->prices[1] = $a->brutto;
////                    $product->availability = $a->avaibility;
////                    $product->number_in_package = $a->in_pack;
////                    $product->always_avaible = $a->always_avaible == "T" ? 1 : 0;
////                    $product->quantity = $a->quantity;
////                    $product->ean = $a->ean;
////                    $product->code = $a->code;
////                    $product->categories = $categories;
////                    $product->producent = $a->producent;
////                    $product->insert_product();
////                    
////                    $translation = new ProductTranslationModel();
////                    $translation->product_id = $product->id;
////                    $translation->lang = 'pl';
////                    $translation->name = $name;
////                    $translation->format = '';
////                    $translation->slogan = '';
////                    $translation->body = "";
////                    $translation->meta_title = '';
////                    $translation->meta_description = '';
////                    $translation->custom_url = '';
////                    $translation->insert();
//                    } else {
//                         echo 'znaleziono prduktu o external_id '. $a->ext_id;
//                    $product = $product_f;
//
//                    $product->prices[1] = $a->brutto;
//                    $product->update_product();
//                    $this->CustomFieldsModel->save_field(6, $product->id, $a->brutto_a, 'pl');
//                    
//                    }
//                    
//                    echo '<hr>';
//                }
//            }
//            }
//    }
//    
//    
//     public function cats(){
//        $reader = new XMLReader;
//        $path = site_url("uploads/bilans/towar2.xml");
////        echo $path; die();
//        $reader->open($path);
//        $this->load->model("ProductModel");
//        $this->load->model('CustomFieldsModel');
//        $this->load->model("OfferCategoryModel");
//        $i = 0;
//        while ($reader->read() !== FALSE) {
//            if ($reader->name === 'tow' && $reader->nodeType === XMLReader::ELEMENT) {
//                $name = $reader->getAttribute('Nazwa');
//                if(!empty($name)){
//                    $a = new stdClass();
//                    $a->ext_id = $reader->getAttribute('ID_TOW');  // +1
////                    $a->photos = $reader->getAttribute('Zdjęcia');   // dodatkowa tabela
////                    $a->type = $reader->getAttribute('Typ');   //  attr
////                    $a->material = $reader->getAttribute('Materiał'); // attr
////                    $a->color = $reader->getAttribute('Kolor'); // attr
////                    $a->collection = $reader->getAttribute('Kolekcja'); // attr
////                    $a->weight = $reader->getAttribute('Ciężar');   // jest
////                    $a->vat = $reader->getAttribute('Stawka_VAT'); // +1
////                    $a->promo = $reader->getAttribute('Promocja'); // jest
////                    $a->brutto_a = $reader->getAttribute('Brutto_A'); // custom field
////                    $a->brutto = $reader->getAttribute('Brutto'); // jest
////                    $a->avaibility = $reader->getAttribute('Dostępność'); // +1 
////                    $a->in_pack = $reader->getAttribute('Ilość_w_opakowaniu'); // +1
////                    $a->always_avaible = $reader->getAttribute('Dostępny_zawsze'); // +1
////                    $a->quantity = $reader->getAttribute('Stan'); // jest
////                    $a->ean = $reader->getAttribute('EAN'); // +1
////                    $a->code = $reader->getAttribute('Kod'); // jest
//                    $a->ext_category_ids = $reader->getAttribute('ID_GRE'); // jest
////                    $a->producent = $reader->getAttribute('Producent');
//                    echo $i++.'<br>';
//                    echo $name.'<br>';
//                    $ext_cats = explode(';', $a->ext_category_ids);
//                    $categories = [];
//                    foreach($ext_cats as $ex_c){
//                        $categories[] = $this->OfferCategoryModel->findByExternalId($ex_c)->id;
//                    }
//                    $product_f = $this->ProductModel->findOneByExternalId($a->ext_id);
////                    var_dump($product_f);
//                    if(empty($product_f->id)){
//                     echo 'nie znaleziono prduktu o external_id '. $a->ext_id;
////                    $product = new ProductModel();
////                    $product->external_id = $a->ext_id;
////                    $product->weight = $a->weight;
////                    $product->vat = $a->vat;
////                    $product->promo = $a->promo == "T" ? 1 : 0;
////                    $product->prices[1] = $a->brutto;
////                    $product->availability = $a->avaibility;
////                    $product->number_in_package = $a->in_pack;
////                    $product->always_avaible = $a->always_avaible == "T" ? 1 : 0;
////                    $product->quantity = $a->quantity;
////                    $product->ean = $a->ean;
////                    $product->code = $a->code;
////                    $product->categories = $categories;
////                    $product->producent = $a->producent;
////                    $product->insert_product();
////                    
////                    $translation = new ProductTranslationModel();
////                    $translation->product_id = $product->id;
////                    $translation->lang = 'pl';
////                    $translation->name = $name;
////                    $translation->format = '';
////                    $translation->slogan = '';
////                    $translation->body = "";
////                    $translation->meta_title = '';
////                    $translation->meta_description = '';
////                    $translation->custom_url = '';
////                    $translation->insert();
//                    } else {
//                         echo 'znaleziono prduktu o external_id '. $a->ext_id;
//                    $product = $product_f;
//                    $product->categories = $categories;
//                    //$product->prices[1] = $a->brutto;
//                    $product->update_product();
//                    //$this->CustomFieldsModel->save_field(6, $product->id, $a->brutto_a, 'pl');
//                    
//                    }
//                    
//                    echo '<hr>';
//                }
//            }
//            }
//    }
//    
//    
//    
//     public function update_products(){
//        $reader = new XMLReader;
//        $path = site_url("uploads/bilans/towar2.xml");
////        echo $path; die();
//        $reader->open($path);
//        $this->load->model("OfferCategoryModel");
//        $this->load->model("ProductModel");
//        $this->load->model("ProductTranslationModel");
//        $this->load->model('CustomFieldsModel');
//        $i = 0;
//        while ($reader->read() !== FALSE) {
//            if ($reader->name === 'tow' && $reader->nodeType === XMLReader::ELEMENT) {
//                $name = $reader->getAttribute('Nazwa');
//                if(!empty($name)){
//                    $a = new stdClass();
//                    $a->ext_id = $reader->getAttribute('ID_TOW');  // +1
////                    $a->photos = $reader->getAttribute('Zdjęcia');   // dodatkowa tabela
////                    $a->type = $reader->getAttribute('Typ');   //  attr
////                    $a->material = $reader->getAttribute('Materiał'); // attr
////                    $a->color = $reader->getAttribute('Kolor'); // attr
////                    $a->collection = $reader->getAttribute('Kolekcja'); // attr
////                    $a->weight = $reader->getAttribute('Ciężar');   // jest
////                    $a->vat = $reader->getAttribute('Stawka_VAT'); // +1
////                    $a->promo = $reader->getAttribute('Promocja'); // jest
////                    $a->brutto_a = $reader->getAttribute('Brutto_A'); // custom field
////                    $a->brutto = $reader->getAttribute('Brutto'); // jest
////                    $a->avaibility = $reader->getAttribute('Dostępność'); // +1 
//                    $a->in_pack = $reader->getAttribute('Ilość_w_opakowaniu'); // +1
////                    $a->always_avaible = $reader->getAttribute('Dostępny_zawsze'); // +1
////                    $a->quantity = $reader->getAttribute('Stan'); // jest
////                    $a->ean = $reader->getAttribute('EAN'); // +1
////                    $a->code = $reader->getAttribute('Kod'); // jest
////                    $a->ext_category_ids = $reader->getAttribute('ID_GRE'); // jest
//                    $a->producent = $reader->getAttribute('Producent');
//                    echo $i++.'<br>';
//                    echo $name.'<br>';
////                    $ext_cats = explode(';', $a->ext_category_ids);
////                    $categories = [];
////                    foreach($ext_cats as $ex_c){
////                        $categories[] = $this->OfferCategoryModel->findByExternalId($ex_c)->id;
////                    }
//                    $product_f = $this->ProductModel->findOneByExternalId($a->ext_id);
////                    var_dump($product_f);
//                    if(empty($product_f->id)){
//                     echo 'nie znaleziono prduktu o external_id '. $a->ext_id;
////                    $product = new ProductModel();
////                    $product->external_id = $a->ext_id;
////                    $product->weight = $a->weight;
////                    $product->vat = $a->vat;
////                    $product->promo = $a->promo == "T" ? 1 : 0;
////                    $product->prices[1] = $a->brutto;
////                    $product->availability = $a->avaibility;
////                    $product->number_in_package = $a->in_pack;
////                    $product->always_avaible = $a->always_avaible == "T" ? 1 : 0;
////                    $product->quantity = $a->quantity;
////                    $product->ean = $a->ean;
////                    $product->code = $a->code;
////                    $product->categories = $categories;
////                    $product->producent = $a->producent;
////                    $product->insert_product();
////                    
////                    $translation = new ProductTranslationModel();
////                    $translation->product_id = $product->id;
////                    $translation->lang = 'pl';
////                    $translation->name = $name;
////                    $translation->format = '';
////                    $translation->slogan = '';
////                    $translation->body = "";
////                    $translation->meta_title = '';
////                    $translation->meta_description = '';
////                    $translation->custom_url = '';
////                    $translation->insert();
////                    
////                    $this->CustomFieldsModel->save_field(6, $product->id, $a->brutto_a, 'pl');
//                     echo "<br> i dupa";
//                    } else {
//                         echo 'znaleziono prduktu o external_id '. $a->ext_id;
//                    $product = new ProductModel($product_f->id);
////                    $product->external_id = $a->ext_id;
////                    $product->weight = $a->weight;
////                    $product->vat = $a->vat;
////                    $product->promo = $a->promo == "T" ? 1 : 0;
////                    $product->prices[1] = $a->brutto;
////                    $product->availability = $a->avaibility;
//                    $product->number_in_package = $a->in_pack;
////                    $product->always_avaible = $a->always_avaible == "T" ? 1 : 0;
////                    $product->quantity = $a->quantity;
////                    $product->ean = $a->ean;
////                    $product->code = $a->code;
////                    $product->categories = $categories;
//                    $product->producent = $a->producent;
//                    $product->update_product();
//                    
////                    $translation = $product->getTranslation('pl');
////                    $translation->name = $name;
////                    $translation->format = '';
////                    $translation->slogan = '';
////                    $translation->body = "";
////                    $translation->meta_title = '';
////                    $translation->meta_description = '';
////                    $translation->custom_url = '';
////                    $translation->update();
//                    }
//                    
//                    echo '<hr>';
//                }
//            }
//            }
//    }
    
    public function orders(){
        header('Content-Type: text/xml; charset=utf-8');
        // XML-related routine
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');
        
        $xml->startElement("orders");
        
        $this->load->model("OrderModel");
        $this->load->model("Delivery_Model");
        $this->load->model("ProductModel");
        $orders = $this->OrderModel->findAll();
        
        
        if(!empty($orders)){
            foreach ($orders as $order){
 
                $delivery = $this->Delivery_Model->get_delivery($order->delivery);
            $xml->startElement("order");
            $xml->writeAttribute('ID_WEW', $order->id);
            $xml->writeAttribute('Buyer', $order->first_name.' '. $order->last_name);
            $xml->writeAttribute('Buyer_adress', $order->zip_code.' '.$order->city.', '.$order->street.' '.$order->building_number);
            $xml->writeAttribute('Buyer_phone', $order->phone);
            $xml->writeAttribute('Total', number_format($order->price,2, '.', ' '));
            $xml->writeAttribute('Delivery_id', $order->delivery);
            $xml->writeAttribute('Delivery_name', $delivery['translations']['pl']['name']);
            $xml->writeAttribute('Delivery_price', $delivery['prices'][1]['price']);
            $xml->writeAttribute('Data', $order->created_at);
            $xml->writeAttribute('Comment', $order->comment);
            $xml->writeAttribute('Method', $order->method);
            $products = '';
            foreach($order->get_basket($order->id) as $k => $p){
                $product_id = explode('_', $k)[0];
                $product = new ProductModel($product_id);
                
                $products .= $product->external_id.':'.$p[0].';';
            }
            $xml->writeAttribute('Products', $products);
            $xml->fullEndElement();
            }
        }
        
        
        $xml->fullEndElement();


        $output = $xml->outputMemory(true);
        echo $output;
        die();
    }
    
    
//    public function literowki() {
//        $this->load->model('ProductAttributesModel');
//        $this->load->model('ProductModel');
//        $groups = $this->ProductAttributesModel->get_groups();
//        echo '<table><tr><th>Kategoria attrybutu</th><th>Nazwa atrybutu</th><th>Nazwa produktu</th><th>Id produktu</th></tr>';
//        foreach ($groups as $g) {
//            $attributes = $this->ProductAttributesModel->attribute_get_list('pl', $g->id);
////        echo '<hr>'. $g->name . '<hr>';
//            foreach ($attributes as $a) {
////            echo $a->name.'<br>';
//                $products = $this->ProductAttributesModel->find_products_with_attribute($a->id);
//                
//                foreach($products as $p){
//                    if(empty($p)){                        continue;}
//                    $prod = new ProductModel($p->product_id);
//                echo '<tr>';
//                echo '<td>'.$g->name.'</td>';
//                echo '<td>'.$a->name.'</td>';
//                echo '<td>'.$prod->getTranslation('pl')->name.'</td>';
//                echo '<td>'.$prod->external_id.'</td>';
//                echo '</tr>';
//                }
//            }
//        }
//        echo '</table>';
//        die();
//    }

    public function test(){
  $this->load->model("BilansModel");
 //       $this->BilansModel->photos();
       $this->BilansModel->download_photos();

    }
    
//    public function bubisia1337(){
//        $q = "SELECT * FROM `duo_offer_categories` left join `duo_products_categories` on `duo_products_categories`.`category_id` = `duo_offer_categories`.`id` where `duo_offer_categories`.`created_at` > '2019-11-18 09:00' and `duo_products_categories`.`product_id` is NULL";
//    
//        $res = $this->db->query($q)->result();
//        
//        foreach($res as $r){
//            if(empty($r->id)){ continue; }
//                $this->db->where('id', $r->id);
//		$res = $this->db->delete('offer_categories');
//                
//                $this->db->where('offer_category_id', $r->id);
//                $this->db->delete('offer_categories_translations');
//        }
//        echo 'skonczono';
//    }
    
    
    public function delete_duplicates(){
        $this->load->model("ProductModel");
        $all_products = $this->ProductModel->findAll();
        
        foreach($all_products as $k => $ap){
            $ap->deleteDuplicateExternalPhotos();
            echo 'usnięto '. $k . '-ty duplikat<br>';
        }
        echo 'usnięto wszystkie duplikaty';
    }
    
    public function newsletter_emails(){
        header('Content-Type: text/xml; charset=utf-8');
        // XML-related routine
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');
        
        $xml->startElement("emails");
        
        $this->load->model("NewsletterModel");
        $emails = $this->NewsletterModel->get_addresses();
        
        
        if(!empty($emails)){
            foreach ($emails as $email){
                if($email->blocked == 1){ continue; }
            $xml->startElement("email");
            $xml->writeAttribute('email', $email->email);
            $xml->fullEndElement();
            }
        }
        
        
        $xml->fullEndElement();


        $output = $xml->outputMemory(true);
        echo $output;
        die();
    }
    
    
    public function dashboard_products(){
        $this->load->model("BilansModel");
        $this->BilansModel->update_products();
    }
    
    public function dashboard_descriptions(){
        $this->load->model("BilansModel");
        $this->BilansModel->descriptions();
    }
    public function dashboard_descriptions2(){
        $this->load->model("BilansModel");
        $this->BilansModel->descriptions2();
    }
    public function dashboard_categories(){
        $this->load->model("BilansModel");
        $this->BilansModel->categories();
    }
    
    public function dashboard_photos(){
        $this->load->model("BilansModel");
        $this->BilansModel->photos();
        $this->BilansModel->download_photos();
    }
    
        public function dashboard_statuses(){
        $this->load->model("BilansModel");
        $this->BilansModel->update_status();
        //$this->BilansModel->update_status_old20191125();
    }
}
