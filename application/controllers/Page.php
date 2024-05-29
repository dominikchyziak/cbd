<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Page extends Frontend_Controller {

    public function __construct() {
        parent::__construct();

        $home = site_url('/');
        $this->breadcrumbs[] = "<a href=$home>" . (new CustomElementModel('10'))->getField('Strona glowna') . "</a>";
    }

    public function index($page_id = 1) {
        $pages = [
   //         '29' => 'zastosowania'
//            '52' => 'support'
            ];
        $this->load->model('PageModel');
        $page = new PageModel($page_id);
        if(empty($page->id)){
            $this->show404();
        } else {
        $translation = $page->getTranslation(LANG);
        $brade_url = site_url("/page/" . getAlias($page_id, htmlspecialchars($translation->title)));
        //$this->breadcrumbs[] = "<a href=$brade_url>" . $translation->title . "</a>";
        $this->set_canon_link($page->getPermalink());
        if (empty($pages[$page_id])) {
            $page_template = 'page';
        } else {
            $page_template = $pages[$page_id];
        }
        $tpage = $page->getTranslation(LANG);
        // Set defaults.
        $this->set_desc($page->getTranslation(LANG)->body);
        $this->set_title($page->getTranslation(LANG)->title);
        if(!empty($tpage->meta_title)){
            $this->set_whole_title($tpage->meta_title);
        }
        if(!empty($tpage->meta_description)){
            $this->set_desc($tpage->meta_description);
        }
        $in_category = array();
        if(!empty($page->category)){
            $in_category = $page->getByCategory($page->category);
        }

        $this->load->model('GallerySetupModel');
        $gallery_widget = $this->GallerySetupModel->get_integrator_data('page', $page_id);
        
        
        $this->layout('Pages/' . $page_template, [
            'page' => $page,
            'in_category' => $in_category,
            'gallery_widget' => $gallery_widget
        ]);
        }
    }
    
//    public function show($page_id = 1) {
////        $pages = ['45' => 'page45'];
//        $this->load->model('PageModel');
//        $page = new PageModel($page_id);
//        $translation = $page->getTranslation(LANG);
//        
//        $brade_url = site_url('/zastosowania');
//        $this->breadcrumbs[] = "<a href=$brade_url>" . (new CustomElementModel('10'))->getField('Zastosowania') . "</a>";
//        
//        if($page_id  == 49) {
//            $site = site_url('/zastosowania/edukacja');
//            $this->breadcrumbs[] = "<a href=$site>" . $translation->title . "</a>";
//        }
//        elseif($page_id  == 50) {
//            $site = site_url('/zastosowania/biznes');
//            $this->breadcrumbs[] = "<a href=$site>" . $translation->title . "</a>";
//        }
//        elseif($page_id  == 51) {
//            $site = site_url('/zastosowania/administracja');
//            $this->breadcrumbs[] = "<a href=$site>" . $translation->title . "</a>";
//        }
//        if(!empty($translation->meta_title)){
//            $this->set_whole_title($translation->meta_title);
//        }
//        if(!empty($translation->meta_description)){
//            $this->set_desc($translation->meta_description);
//        }
////        if (empty($pages[$page_id])) {
////            $page_template = 'page';
////        } else {
////            $page_template = $pages[$page_id];
////        }
//        
//        $this->load->model('GallerySetupModel');
//        $gallery_widget = $this->GallerySetupModel->get_integrator_data('page', $page_id);
//        
//        
//        $this->layout('Pages/show', [
//            'page' => $page,
//            'gallery_widget' => $gallery_widget
//        ]);
//    }

}
