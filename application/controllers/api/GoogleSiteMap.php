<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
//define('LANG', 'pl');
class GoogleSiteMap extends REST_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('OfferCategoryModel');
        $this->load->model('ProductModel');
		$this->load->model('NewsModel');
		//$this->load->model('PageModel');
		$this->load->model('PageTranslationModel');
    }
    
    public function xml_get($lang){
        define('LANG',$lang);
        header('Content-Type: text/xml; charset=utf-8');
        // XML-related routine
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');
        
        $xml->startElement("urlset");
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        
		
		//sztywne podstrony
		$xml->startElement("url");
		$xml->writeElement('loc', base_url());
		$xml->fullEndElement();
		
		$xml->startElement("url");
		$xml->writeElement('loc', base_url('koszyk'));
		$xml->fullEndElement();
		
		$xml->startElement("url");
		$xml->writeElement('loc', base_url('rejestracja'));
		$xml->fullEndElement();
		
		$xml->startElement("url");
		$xml->writeElement('loc', base_url('account/login'));
		$xml->fullEndElement();
        
		//produkty
        $products = $this->ProductModel->findAll();
        if(!empty($products)){
            foreach ($products as $product){
				$translation = $product->getTranslation(LANG);
				
				//$category_id = $product->offer_category_id;
				//$category = new OfferCategoryModel($category_id);
				/*
				if(empty($translation->name)){
					continue;
				}
				*/

				$date=date_create($product->updated_at);
				$date_f=date_format($date,"Y-m-d");

				$xml->startElement("url");
					$xml->writeElement('loc', $product->getPermalink());
					$xml->writeElement('lastmod', $date_f);
					$xml->writeElement('priority', '1.0');
				$xml->fullEndElement();
            }
        }
		
		//blog
		$news = $this->NewsModel->findAll(LANG);
        if(!empty($news)){
            foreach ($news as $item){
				//$translation = $item->getTranslation(LANG);

				$date=date_create($item->updated_at);
				$date_f=date_format($date,"Y-m-d");

				$xml->startElement("url");
					$xml->writeElement('loc', $item->getPermalink());
					$xml->writeElement('lastmod', $date_f);
				$xml->fullEndElement();
            }
        }
		
		//podstrony
		$pages = $this->PageTranslationModel->getByLang(LANG);
        if(!empty($pages)){
            foreach ($pages as $item){
				//$translation = $item->getTranslation(LANG);

				$date=date_create($item->updated_at);
				$date_f=date_format($date,"Y-m-d");

				$xml->startElement("url");
					$xml->writeElement('loc', site_url($item->custom_url));
					$xml->writeElement('lastmod', $date_f);
				$xml->fullEndElement();
            }
        }
        
        
$xml->fullEndElement();



        $output = $xml->outputMemory(true);
        echo $output;
        die();
    }
}
