<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
define('LANG', 'pl');
class Ceneo extends REST_Controller {
    private $ceneo;
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('CeneoModel');
        $this->ceneo = new CeneoModel();
        
    }
    
    public function xml_get(){
        header('Content-Type: text/xml; charset=utf-8');
        // XML-related routine
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');
        
        $xml->startElement("offers");
        $xml->writeAttribute('xmlns:xsi', 'https://www.w3.org/2001/XMLSchema-instance');
        $xml->writeAttribute('version', '1');
        
        $products = $this->ceneo->get_ceneo_array();
        if(!empty($products)){
            foreach ($products as $product){
            $translation = $product->getTranslation(LANG);
 
            $xml->startElement("o");
            $xml->writeAttribute('id', $product->id);
            $xml->writeAttribute('url', $product->getPermalink());
            $xml->writeAttribute('price', str_replace(',','.', $product->price));
            $xml->writeAttribute('avail', get_option('admin_modules_ceneo_avail'));
            if(!empty($product->weight)){
                $xml->writeAttribute('weight', str_replace(',','.',$product->weight));
            }
            $xml->startElement("cat");
            $xml->writeCData(get_option('admin_modules_ceneo_cat'));
            $xml->fullEndElement();
            $xml->startElement("name");
            $xml->writeCData($translation->name);
            $xml->fullEndElement();
            $xml->startElement("imgs");
            $photos = $product->findAllPhotos();
            if(!empty($photos[0])){
                $xml->startElement("main");
                $xml->writeAttribute('url', $photos[0]->getUrl());
                $xml->fullEndElement();
            }
            if(!empty($photos[1])){
                foreach ($photos as $key=>$photo){
                    if($key == 0){
                        continue;
                    }
                    $xml->startElement("i");
                    $xml->writeAttribute('url', $photo->getUrl());
                    $xml->fullEndElement();
                }
            }
            $xml->fullEndElement();
            $xml->startElement("desc");
            $xml->writeCData(substr($translation->body,0,30000));
            $xml->fullEndElement();
            $this->load->model("ProductAttributesModel");
            $xml->startElement("attrs");
            foreach ($product->attribute_get_list_for_product($product->id) as $atr) {
                $atr_cat = $this->ProductAttributesModel->get_group_with_trans($atr->attributes_group_id)[0];
                $atr_cat_name = $atr_cat->name;
                $xml->startElement("a");
                  $xml->writeAttribute('name', $atr_cat_name);
                  $xml->writeCData($atr->name);
                $xml->fullEndElement();
            }
            $xml->fullEndElement();
            $xml->fullEndElement();
            }
        }
        
        
$xml->fullEndElement();



        $output = $xml->outputMemory(true);
        echo $output;
        die();
    }
}