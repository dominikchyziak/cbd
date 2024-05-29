<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
//define('LANG', 'pl');
class Google extends REST_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('OfferCategoryModel');
        $this->load->model('ProductModel');
    }
    
    public function xml_get($lang){
        define('LANG',$lang);
        header('Content-Type: text/xml; charset=utf-8');
        // XML-related routine
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');
        
        $xml->startElement("feed");
        $xml->writeAttribute('xmlns', 'http://www.w3.org/2005/Atom');
        $xml->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
        
        $xml->writeElement('title', 'doctorscbd.pl');
        $xml->startElement('link');
        $xml->writeAttribute('rel', 'self');
        $xml->writeAttribute('href', 'https://doctorscbd.pl/');
        $xml->endElement();
        $xml->writeElement('updated', date(DATE_ATOM));
        
        
        $products = $this->ProductModel->findAll();
        if(!empty($products)){
            foreach ($products as $product){
            $translation = $product->getTranslation(LANG);
            $photos = $product->findAllPhotos();
            $category_id = $product->offer_category_id;
            $category = new OfferCategoryModel($category_id);
            
            if(empty($translation->name) || empty($translation->body) || empty($photos)){
                continue;
            }
            $xml->startElement("entry");
            $xml->writeElement('g:id', $product->id);
            $xml->startElement("g:title");
            $xml->writeCData($translation->name);
            $xml->fullEndElement();
            $xml->startElement("g:description");
            $xml->writeCData(strip_tags(substr($translation->body,0,30000),null));
            $xml->fullEndElement();
            $xml->startElement('g:link');
            $xml->writeCdata($product->getPermalink());
            $xml->fullEndElement();
            $xml->startElement('g:image_link');
            $xml->writeCdata($photos[0]->getUrl());
            $xml->endElement();
            $xml->writeElement('g:condition', 'new');
            if($product->quantity > 0){
                $xml->writeElement('g:availability', 'in stock');
            } else {
                $xml->writeElement('g:availability', 'out of stock');
            }
            if (LANG!='pl'){
                $xml->writeElement('g:price', str_replace(',','.', $product->getPrice(4)).' EUR');
            }else{
                $xml->writeElement('g:price', str_replace(',','.', $product->getPrice(1)).' PLN');
            }
            $xml->writeElement('g:gtin', $product->code);
            $this->load->model("ProductAttributesModel");
             foreach ($product->attribute_get_list_for_product($product->id) as $atr) {
                $atr_cat = $this->ProductAttributesModel->get_group_with_trans($atr->attributes_group_id)[0];
                $atr_cat_name = $atr_cat->name;
                if($atr_cat_name == 'Marka'){
                    $xml->writeElement('g:brand', $atr->name);
                    break;
                }
             }
            $xml->fullEndElement();
            }
        }
        
        
$xml->fullEndElement();



        $output = $xml->outputMemory(true);
        echo $output;
        die();
    }
}
