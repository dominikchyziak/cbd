<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Merchant extends Frontend_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('merchant/MerchantCategories');
        $this->load->library('merchant/Excel_reader');
        $this->load->model('ProductModel');
        $this->load->model('ProductTranslationModel');
    }

    function index() {
        header('Content-Type: text/xml; charset=utf-8');
        // XML-related routine
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');

        //$mcat = new merchantCategories(0,506);
        $mcat = new merchantCategories(0, 206);

        $products = $this->getList();
        
        $xml->startElement("rss");
        $xml->writeAttribute('version', '2.0');
        $xml->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');

        $xml->startElement("channel");
        $xml->startElement("title");
        $xml->writeCData('Obraz-ze-zdjecia.pl');
        $xml->fullEndElement();
        $xml->startElement("link");
        $xml->writeCData("https://" . $_SERVER['HTTP_HOST']);
        $xml->fullEndElement();
        $xml->startElement("Description");
        $xml->writeCData('Wgraj zdjęcie! Zamawiaj obrazy z Twoich zdjęć. Fotoobrazy pojedyncze, obrazy łączone, tryptyki, pentaptyki, poliptyki.');
        $xml->fullEndElement();
        $i = 0;
        foreach ($products as $product) {
            $xml->startElement("item");
            $xml->writeElement("g:id", $product['id']);
            $xml->writeElement("title", $product['product_name']);
            if ($product['desc'] != '') {
                $xml->startElement("description");
                $xml->writeCData($this->simpleDescription($product['desc']));
                $xml->fullEndElement();
            }
            $merchant_category = $mcat->getCategoryById($product['id_category']);
            $merchant_category = str_replace('&oacute;', 'ó', $merchant_category);
            $merchant_category = str_replace('&Oacute;', 'Ó', $merchant_category);
            $xml->writeElement("g:product_type", $product['category']);
            $xml->writeElement("g:google_product_category", "Dom i ogród &gt; Ozdoby &gt; Dzieła sztuki &gt; Plakaty, nadruki, prace plastyczne"); //$merchant_category);
            $xml->writeElement("link",  $product['link']);
            $foto =$product['photo'];
            if (!empty($foto)) {
                $xml->writeElement("g:image_link", $product['photo']);
            } else {
                $xml->writeElement("g:image_link", "");
            }
            $xml->writeElement("g:condition", 'new');
            $xml->writeElement("g:availability", 'in stock');
            $xml->writeElement("g:price", $product['price'] . ' PLN');
            if ($product['promo_price'])
                $xml->writeElement("g:sale_price", $product['promo_price'] . ' PLN');
            $xml->writeElement("g:brand", $product['manufacturer_name']);
            if (!empty($product_typeg))
                $xml->writeElement("g:product_type", $product_typeg);
            if ($product['ean'])
                $xml->writeElement("g:gtin", $product['ean']);
            if (!empty($product['promo_price'])) {
                $xml->writeElement("g:custom_label_0", 'supercena');
                $xml->writeElement("g:sale_price_effective_date", date("c", strtotime($product['supercena_od'])) . '/' . date("c", strtotime($product['supercena_do'])));
            }
            if ($product['price'] <= 20) {
                $xml->writeElement("g:custom_label_1", '20');
            } elseif ($product['price'] > 20 and $product['price'] <= 50) {
                $xml->writeElement("g:custom_label_1", '50');
            } elseif ($product['price'] > 50 and $product['price'] <= 100) {
                $xml->writeElement("g:custom_label_1", '100');
            } elseif ($product['price'] > 100 and $product['price'] <= 200) {
                $xml->writeElement("g:custom_label_1", '200');
            } elseif ($product['price'] > 200 and $product['price'] <= 300) {
                $xml->writeElement("g:custom_label_1", '300');
            } elseif ($product['price'] > 300 and $product['price'] <= 500) {
                $xml->writeElement("g:custom_label_1", '500');
            } elseif ($product['price'] > 500 and $product['price'] <= 1000) {
                $xml->writeElement("g:custom_label_1", '1000');
            } elseif ($product['price'] > 1000) {
                $xml->writeElement("g:custom_label_1", 'max');
            }

            if ($product['nowosc'])
                $xml->writeElement("g:custom_label_2", 'nowosc');


            $xml->fullEndElement();
        }

        $xml->fullEndElement();
        $xml->fullEndElement();




        $output = $xml->outputMemory(true);
        echo $output;
    }

    public function getList() {
        $product_model = new ProductModel();
        $translation_model = new ProductTranslationModel();
        
        //tablica atrybutów wybranych
        $attributes = $product_model->attribute_get_list(LANG);
        $tmp = array();
        if(!empty($attributes)){
            foreach($attributes as $at){
                $tmp[$at->id] = (array)$at;
            }
        }
        $attr_array = array(
            'baw' => $tmp[9],
            'wernix' => $tmp[10],
            'realization_time' => $tmp[11],
            'bawernix' => array(
                'id' => '9_10',
                'name' => $tmp[9]['name'] . ' + ' . $tmp[10]['name'],
                'value' =>  $tmp[9]['value']*1  +  $tmp[10]['value']*1,
                'description' => $tmp[9]['description'] . ' <br> ' . $tmp[10]['description']
            ),
            'bawczas' => array(
                'id' => '9_11',
                'name' => $tmp[9]['name'] . ' + ' . $tmp[11]['name'],
                'value' =>  $tmp[9]['value']*1  +  $tmp[11]['value']*1,
                'description' => $tmp[9]['description'] . ' <br> ' . $tmp[11]['description']
            ),
            'wernixczas' => array(
                'id' => '10_11',
                'name' => $tmp[10]['name'] . ' + ' . $tmp[11]['name'],
                'value' =>  $tmp[10]['value']*1  +  $tmp[11]['value']*1,
                'description' => $tmp[10]['description'] . ' <br> ' . $tmp[11]['description']
            ),
            'bawernixczas' => array(
                'id' => '9_10_11',
                'name' => $tmp[9]['name'] . ' + ' . $tmp[10]['name'] . ' + ' . $tmp[11]['name'],
                'value' =>  $tmp[9]['value']*1  +  $tmp[10]['value']*1 + $tmp[11]['value'],
                'description' => $tmp[9]['description'] . ' <br> ' . $tmp[10]['description'] . '<br>' . $tmp[11]['description']
            )
        );
        //koniec wybierania atrybutów
        
        $produkty = $product_model->findAllForCmsList();
        foreach ($produkty as $prod) {
            $options_all = $product_model->get_options($prod->id);
            $translation = $translation_model->findByProductAndLang($prod, LANG);
            $product = array();
            $photos = $prod->findAllPhotos();
            if(!empty($photos)){
                $photo = $photos[0]->getUrl();
            } else {
                $photo = '';
            }
            
       
            if(empty($options_all)){
                $product['id'] = $prod->id . '_0';
                $product['product_name'] = $translation->name;
                $product['product_id'] = $prod->id;
                $product['desc'] = $translation->body;
                
                $product['id_category'] = 8;
                $product['link'] = $prod->getPermalink();
                $product['price'] = $prod->getPrice(1);
                $product['promo_price'] = null;
                
                $product['wyprz'] = FALSE;
                $product['nowosc'] = FALSE;
                $product['manufacturer_name'] = "Obraz-ze-zdjecia";
                $product['ean'] = '0';
                $product['category'] = 'Obrazy ze zdjęcia';
                
                $product['photo'] = $photo;
                $products[] = $product;
            } else {
                foreach ($options_all as $option){
                    $op = $product_model->select_option($option->id);
                    $product['id'] = $prod->id . '_' . $op['id'];
                    $product['product_id'] = $prod->id . '_' . $op['id'];
                    $product['product_name'] = $translation->name . ' - ' . $op['name'];
                    $product['desc'] = $translation->body;
                    
                    $product['id_category'] = 8;
                    $product['link'] = $prod->getPermalink();
                    $product['price'] = $prod->getPrice(1) + $op['price_change'];
                    $product['promo_price'] = null;
                    
                    $product['wyprz'] = FALSE;
                    $product['nowosc'] = FALSE;
                    $product['manufacturer_name'] = "Obraz-ze-zdjecia";
                    $product['ean'] = '0';
                    $product['category'] = 'Obrazy ze zdjęcia';
                    
                    $product['photo'] = $photo;
                    $products[] = $product;
                    /// atrybuty dodatkowo
                    foreach($attr_array as $atr){
                        $product['id'] = $prod->id . '_' . $op['id'] . '_' . $atr['id'];
                        $product['product_id'] = $prod->id . '_' . $op['id'] . '_' . $atr['id'];
                        $product['product_name'] = $translation->name . ' - ' . $op['name'] . '_' . $atr['name'];
                        $product['desc'] = $translation->body . ' <br >' . $atr['description'];

                        $product['id_category'] = 8;
                        $product['link'] = $prod->getPermalink();
                        $product['price'] = ($prod->getPrice(1) + $op['price_change']) * (1+($atr['value']/100));
                        $product['promo_price'] = null;

                        $product['wyprz'] = FALSE;
                        $product['nowosc'] = FALSE;
                        $product['manufacturer_name'] = "Obraz-ze-zdjecia";
                        $product['ean'] = '0';
                        $product['category'] = 'Obrazy ze zdjęcia';

                        $product['photo'] = $photo;
                        $products[] = $product;
                    }
                    //atrybuty dodatkowo
                }
            }
        }
        return $products;
    }
    
     public function simpleDescription($string){
         $string=strip_tags($string,'<p><b><strong><a><br><br /><h1><h2><h3><h4><h5><h6><h7>');
         $string = trim(preg_replace('/\s+/', ' ', $string)); 
         return $string;
    }

    
    function links() {
        $all = $this->getList();
        $this->layout("Oferta/links", array('all' => $all));
    }

}
