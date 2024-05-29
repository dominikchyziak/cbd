<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Points extends Backend_Controller {

    public $languages = array();

    public function __construct() {
        parent::__construct();
        $langs = get_languages();
        foreach ($langs as $l) {
            $this->languages[] = $l->short;
        }
        $this->load->model('MapPointModel');
        $this->load->vars(['activePage' => 'points']);
    }

    public function create() {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            $point = new MapPointModel();
            
            $point->lat = $this->input->post('lat');
            $point->lng = $this->input->post('lng');
            $point->name = $this->input->post('name');
            $point->address = $this->input->post('address');
            $point->icon_marker = !empty($this->input->post('icon_marker')) ? $this->input->post('icon_marker') : '';
            $point->gallery_id = $this->input->post('gallery_id');
            
            $id = $point->add();
            
            setAlert('success', 'Galeria została zapisana.');
            redirect('duocms/points/edit/' . $id);
        }

        $this->load->model("GalleryModel");
        $galleries = $this->GalleryModel->findAll2();
        $this->layout('duocms/MapPoints/create', [
                'galleries' => $galleries
        ]);
    }

    public function edit($id) {
        $point = new MapPointModel($id);

        if (!$point->id) {
            show_404();
        }

        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            $point->lat = $this->input->post('lat');
            $point->lng = $this->input->post('lng');
            $point->name = $this->input->post('name');
            $point->address = $this->input->post('address');
            $point->icon_marker = !empty($this->input->post('icon_marker')) ? $this->input->post('icon_marker') : '';
            $point->gallery_id = $this->input->post('gallery_id');
            
            $res = $point->update();

            if (!$res) {
                setAlert('error', 'Wystąpił błąd.');
                redirect('duocms/points/edit/' . $point->id);
            }

            setAlert('success', 'Galeria została zapisana.');
            redirect('duocms/points/edit/' . $point->id);
        }
        
        $this->load->model("GalleryModel");
        $galleries = $this->GalleryModel->findAll2();
      
        $this->layout('duocms/MapPoints/edit', [
           'point' => $point,
           'galleries' => $galleries
        ]);
    }

    public function delete($id) {
        $res = (new MapPointModel($id))->delete();

        if ($res)
            setAlert('success', 'Punkt został usunięty');
        else
            setAlert('error', 'Nie można usunąć punktu.');

        redirect($_SERVER['HTTP_REFERER']);
    }

    public function index() {
        $points = (new MapPointModel())->getAllMapPoints();

        $this->layout('duocms/MapPoints/index', array(
            'points' => $points
        ));
    }

}
