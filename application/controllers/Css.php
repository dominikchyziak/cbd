<?php

class Css extends Frontend_Controller {

    public function __construct() {
        parent::__construct();

    }

    public function index() {
       
        $this->load->model('GallerySetupModel');

       
        $content = $this->load->view('Css/index', $this->GallerySetupModel->get_css_option_for_view(), true);
        
        header("Content-type: text/css;charset: UTF-8", true);
        echo $content;
        die();
    }
    

}
