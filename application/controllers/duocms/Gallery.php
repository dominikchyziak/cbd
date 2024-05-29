<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gallery extends Backend_Controller {

    public $languages = array();

    public function __construct() {
        parent::__construct();
        $langs = get_languages();
        foreach ($langs as $l) {
            $this->languages[] = $l->short;
        }
        $this->load->model('GalleryModel');
        $this->load->vars(['activePage' => 'gallery']);
    }

    public function create() {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $category_id = $this->input->post('category_id');
            $this->load->model('GalleryModel');

            $gallery = new GalleryModel();
            $gallery->category_id = $category_id;
            $gallery->order = $this->input->post('order');
            $gallery->category = $this->input->post('category');
            $gallery->insert_gallery();

            if (!$gallery->id) {
                setAlert('error','Wystąpił błąd.');
                redirect('duocms/gallery/create');
            }

            $this->load->model('GalleryTranslationModel');

            foreach ($this->languages as $lang) {
                $data = $this->input->post($lang);

                $translation = new GalleryTranslationModel();
                $translation->gallery_id = $gallery->id;
                $translation->lang = $lang;
                $translation->name = $data['name'];
                $translation->description = $data['description'];
                $translation->meta_title = !empty($data['meta_title']) ? $data['meta_title'] : '';
                $translation->meta_description = !empty($data['meta_description']) ? $data['meta_description'] : '';
                $translation->custom_url = !empty($data['custom_url']) ? $data['custom_url'] : '';
                
                $translation->insert();
            }

            setAlert('success','Galeria została zapisana.');
            redirect('duocms/gallery/edit/' . $gallery->id);
        }
        
        $this->load->model('ProductModel');
        $product = $this->ProductModel->findAll();
        
        $this->load->model('CategoryModel');

        $categories = (new CategoryModel())->getListForGalleryDropdown();

        $this->load->helper('form');

        $this->layout('duocms/Gallery/create', [
            'categories' => $categories,
            'product' => $product
        ]);
    }

    public function edit($id) {
        $this->menu_item[1] = 'gallery_edit';
        $this->add_css(assets('plugins/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css'));
        $this->add_js(assets('plugins/plupload/js/plupload.min.js'));
        $this->add_js(assets('plugins/plupload/js/jquery.plupload.queue/jquery.plupload.queue.min.js'));
        $this->add_js(assets('plugins/plupload/js/i18n/pl.js'));

        $this->load->model('GalleryModel');

        $gallery = new GalleryModel($id);

        if (!$gallery->id) {
            show_404();
        }

        if ($this->input->server('REQUEST_METHOD') === 'POST' && !$this->input->post("action")) {
            save_custom_fields('gallery', $id);
            $photo_order = $this->input->post('photo_order');

            if ($photo_order) {
                $this->load->model('PhotoModel');

                foreach ($photo_order as $order => $photoId) {
                    $photo = new PhotoModel($photoId);
                    $photo->order = $order;
                    $photo->update();
                }
            }

            $category_id = $this->input->post('category_id');

            $gallery->category_id = $category_id;
            $gallery->order = $this->input->post('order');
            $gallery->category = $this->input->post('category');
            $res = $gallery->update_gallery();

            if (!$res) {
                setAlert('error','Wystąpił błąd.');
                redirect('duocms/gallery/edit/' . $gallery->id);
            }

            $this->load->model('GalleryTranslationModel');

            foreach ($this->languages as $lang) {
                $data = $this->input->post($lang);

                $translation = new GalleryTranslationModel($data['id']);
                $translation->name = $data['name'];
                $translation->description = $data['description'];
                $translation->meta_title = !empty($data['meta_title']) ? $data['meta_title'] : '';
                $translation->meta_description = !empty($data['meta_description']) ? $data['meta_description'] : '';
                $translation->custom_url = !empty($data['custom_url']) ? $data['custom_url'] : '';
                $translation->update();
            }

            setAlert('success','Galeria została zapisana.');
            redirect('duocms/gallery/edit/' . $gallery->id);
        }
        if ($this->input->post("action") === "desc_photo") {
            $photo_id = $this->input->post("id");
            $descriptions = $this->input->post("description");
            $this->load->model("PhotoModel");
            $pM = new PhotoModel();
            foreach ($descriptions as $lang => $description) {
                $pM->update_description($photo_id, $description, $lang);
            }
        }
        
        $this->load->model('ProductModel');
        $product = $this->ProductModel->findAll();
        
        $photos = $gallery->findAllPhotos();

        $this->load->model('CategoryModel');

        $categories = (new CategoryModel())->getListForGalleryDropdown();

        $this->load->helper('form');

        $this->layout('duocms/Gallery/edit', [
            'categories' => $categories,
            'gallery' => $gallery,
            'photos' => $photos,
            'product' => $product
        ]);
    }

    public function save() {
        try {
            $data = $this->input->post('Gallery');

            $id = $this->GalleryModel->save($data);

            if ($id === FALSE)
                throw new Exception('Wystąpił błąd podczas zapisu');

            setAlert('success','Zapisano!');
            redirect('duocms/Gallery/edit/' . $id);
        } catch (Exception $e) {
            setAlert('error',$e->getMessage());
            redirect($this->input->server('HTTP_REFERER'));
        }
    }

    public function delete($id) {
        $res = (new GalleryModel($id))
                ->delete();

        if ($res)
            setAlert('success','Zdjęcie zostało usunięte');
        else
            setAlert('error','Nie można usunąć zdjęcia.');

        redirect($_SERVER['HTTP_REFERER']);
    }

    public function index() {
        $this->load->model('ProductModel');
        
        $galleries = (new GalleryModel())->findAllForcCmsListAdmin();

        $this->layout('duocms/Gallery/index', array(
            'galleries' => $galleries,
        ));
    }

    public function upload_photo() {
        pre($_FILES['file']);

        $discountId = $this->input->post('product_id');

        $this->load->model('GalleryModel');

        $discount = new GalleryModel($discountId);

        if (!$discount->id) {
            show_404();
        }

        $this->load->model('PhotoModel');

        $photo = new PhotoModel();
        $photo->gallery_id = $discount->id;
        $photo->insert();

        if (!$photo->id) {
            show_404();
        }

        $res = $photo->saveImage($_FILES['file']);

        if ($res) {
            echo json_encode(['result' => 1]);
        } else {
            echo json_encode(['result' => 0]);
        }
    }

    public function ajax_delete_photo($id) {
        $this->load->model('PhotoModel');

        $photo = new PhotoModel($id);

        if (!$photo->id) {
            show_404();
        }

        $res = $photo->delete();

        if ($res) {
            echo json_encode(['result' => 1]);
        } else {
            echo json_encode(['result' => 0]);
        }
    }
    
    public function sort(){
        $gallery = new GalleryModel();
        if (!empty($_POST['element'])) {
            $i = 1;
            foreach ($_POST['element'] as $id) {
                $gallery->sort_item($id, $i);
                $i++;
            }
            die();
        }
    }

//    public function bubaczka(){
//        $this->load->model('PhotoModel');
//        $photos = (new PhotoModel())->findAll();
//        foreach($photos as $p){
//            $p->redoImage();
//        }
//        echo 'zrobione';
//    }
}
