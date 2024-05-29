<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pages extends Backend_Controller {

    public $languages = array();

    public function __construct() {
        parent::__construct();
        $langs = get_languages();
        foreach ($langs as $l) {
            $this->languages[] = $l->short;
        }
        $this->load->vars(['activePage' => 'pages']);
        $this->load->model('PageTranslationModel');
        $this->load->model('PageModel');
    }
    
    public function index(){
        $this->get();
    }
    
    public function add(){
        
        $page = new PageModel();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $page->category = $this->input->post('category');
            $res = $page->add();

            if ($res) {
                setAlert('success','Strona została dodana.');
            } else {
                setAlert('error','Wystąpił błąd.');
            }
            $this->load->model('GallerySetupModel');
            $this->GallerySetupModel->save_relation('page', $res, $this->input->post('gallery'));
            
            foreach ($this->languages as $lang) {
                $data = $this->input->post($lang);
                $translation = new PageTranslationModel();
                $translation->page_id = $res;
                $translation->lang = $lang;
                $translation->title = !empty($data['title']) ? $data['title'] : '';
                $translation->body = !empty($data['body']) ? $data['body'] : '';
                $translation->meta_title = !empty($data['meta_title']) ? $data['meta_title'] : '';
                $translation->meta_description = !empty($data['meta_description']) ? $data['meta_description'] : '';
                $translation->custom_url = !empty($data['custom_url']) ? $data['custom_url'] : '';
                $translation->insert();
                
            }
            $page->id = $res;
            
            redirect('duocms/pages/edit/' . $page->id);
        }

        $this->load->helper('form');
        $this->load->model('GalleryModel');
        $igalleries = $this->GalleryModel->findAllCategory2(-1);
        $gallery_dropdown = [ 0 => "brak"];
        foreach($igalleries as $ig){
            $gallery_dropdown[$ig->id] = $ig->getTranslation(LANG)->name;
        }
        $this->layout('duocms/Pages/add', [
            'gallery_dropdown' => $gallery_dropdown
        ]);
    }

    public function edit($id) {
        $page = new PageModel($id);

        if (!$page->id) {
            show_404();
        }
        $this->load->model('GallerySetupModel');
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $page->category = $this->input->post('category');
            save_custom_fields('page', $id);
            $res = $page->update();
            
            if ($res) {
                setAlert('success','Strona została zaktualizowana.');
            } else {
                setAlert('error','Wystąpił błąd.');
            }
            $this->GallerySetupModel->save_relation('page', $page->id, $this->input->post('gallery'));
            
            $this->load->model('PageTranslationModel');

            foreach ($this->languages as $lang) {
                $data = $this->input->post($lang);

                $translation = new PageTranslationModel($data['id']);
                $translation->title = !empty($data['title']) ? $data['title'] : '';
                $translation->body = !empty($data['body']) ? $data['body'] : '';
                $translation->meta_title = !empty($data['meta_title']) ? $data['meta_title'] : '';
                $translation->meta_description = !empty($data['meta_description']) ? $data['meta_description'] : '';
                $translation->custom_url = !empty($data['custom_url']) ? $data['custom_url'] : '';
                $translation->update();
            }

            redirect('duocms/pages/edit/' . $page->id);
        }

        $this->load->helper('form');

        $this->load->model('GalleryModel');
        $igalleries = $this->GalleryModel->findAllCategory2(-1);
        $gallery_dropdown = [0 => 'brak'];
        foreach($igalleries as $ig){
            $gallery_dropdown[$ig->id] = $ig->getTranslation(LANG)->name;
        }
        $gallery = $this->GallerySetupModel->get_relation('page', $page->id);
        $this->layout('duocms/Pages/edit', [
            'page' => $page,
            'gallery_dropdown' => $gallery_dropdown,
            'gallery' => $gallery
        ]);
    }

    public function get() {
        
        $pages = $this->PageModel->getByCategory(null);

        $this->layout('duocms/Pages/get', array(
            'pages' => $pages
        ));
    }
    
    public function delete($id){
        $page = new PageModel($id);
        if($page->delete($id)){
            setAlert('warning','Usunięto podstronę');
        } else {
            setAlert('error','Nie udało się usunąć podstrony');
        }
        redirect(site_url('duocms/pages'));
    }

}
