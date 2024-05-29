<?php

class Gallery extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
        $home = site_url('/');
        $this->breadcrumbs[] = "<a href=$home>" . (new CustomElementModel('10'))->getField('Strona glowna') . "</a>";
    }

//    public function index($category = 2) {
//        $this->load->model('GalleryModel');
//        $this->load->model('GalleryTranslationModel');
//        
//        $gallery = $this->GalleryModel->findAllCategory2($category);
//        if ($category == 2) {
//            $this->breadcrumbs[] =(new CustomElementModel('10'))->getField('realizacje')->value;
//            $this->layout('Gallery/index', [
//                'gallery' => $gallery
//            ]);
//        } elseif ($category == 3) {
//            $this->breadcrumbs[] =(new CustomElementModel('10'))->getField('Zespol')->value;
//            $this->layout('Gallery/index2', [
//                'gallery' => $gallery
//            ]);
//        }
//    }
    
    /*dodane 26.04.2019*/
    public function index($strona = 1) {
        $this->load->model('GalleryModel');
//        $this->load->library('pagination');
        
        $this->load->model('GalleryTranslationModel');
        $this->load->model('MapPointModel');
//        $config = [
//            'base_url' => site_url('realizacje'),
//            'total_rows' => $this->GalleryModel->findAllForcCmsList1(LANG),
//            'per_page' => 9,
//            'use_page_numbers' => true
//        ];
        
//        $this->pagination->initialize($config); 
//        $gallery = $this->GalleryModel->findAllForcCmsListAdmin1(LANG, $config['per_page'], (--$strona)*9);
        
        $galleries = $this->GalleryModel->findAll2();
        
        foreach($galleries as $gal){
            $gal->point = $this->MapPointModel->findByGallery($gal->id);
        }
        
        $this->breadcrumbs[] = "<a href='" . site_url('realizacje') . "'>" . (new CustomElementModel('10'))->getField('Przyklady_wdrozen') . "</a>";
        $this->load->model('GallerySetupModel');
        $gallery_widget['setup'] = $this->GallerySetupModel->get_setup();
        $gallery_widget['modules'] = $this->GallerySetupModel->get_modules_array();
        $gallery_widget['photos'] = null;
        
        
        $this->layout('Gallery/index', [
            'galleries' => $galleries,
            'gallery_widget' => $gallery_widget
//            'meta_title' => $this->GalleryTranslationModel->meta_title,
//            'meta_description' => $this->GalleryTranslationModel->meta_description,
//            'custom_url' => $this->GalleryTranslationModel->custom_url
        ]);
    }
    
    public function show($gallery_id){
        $this->load->model('GalleryModel');
        $this->load->model('GalleryTranslationModel');
        

        $galleryObject = new GalleryModel($gallery_id);
        if(empty($galleryObject->id)) 
        { 
            $this->show404(); 
        } else {
        
        $gallery = $galleryObject->getTranslation(LANG);
        
        if(!empty($gallery->meta_title)){
            $this->set_whole_title($gallery->meta_title);
        }else{
        $this->set_title($gallery->name . ' - Realizacje');
        }
        if(!empty($gallery->meta_description)){
            $this->set_desc($gallery->meta_description);
        }
        
        $this->set_canon_link($galleryObject->getPermalink());
        $this->breadcrumbs[] = "<a href='" . site_url('realizacje') . "'>".(new CustomElementModel('10'))->getField('Przyklady_wdrozen') . "</a>";
        $this->breadcrumbs[] = "<a href='" . $galleryObject->getPermalink() . "'>" . $gallery->name . "</a>";
//        $this->breadcrumbs[] = $gallery->name;
        $this->load->model('GallerySetupModel');
        $gallery_widget['setup'] = $this->GallerySetupModel->get_setup();
        $gallery_widget['modules'] = $this->GallerySetupModel->get_modules_array();
        $gallery_widget['photos'] = $galleryObject->findAllPhotos();

        $photos = $galleryObject->findAllPhotos();

        $this->layout('Gallery/show', [
            'gallery' => $gallery,
            'photos' => $photos,
//            'photos' => $gallery_widget['photos'],
            'gallery_widget' => $gallery_widget
        ]);
    }
    }
}
