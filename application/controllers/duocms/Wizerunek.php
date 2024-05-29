<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Wizerunek extends Backend_Controller {

    public $languages = array();

    public function __construct() {
        parent::__construct();
        $langs = get_languages();
        foreach ($langs as $l) {
            $this->languages[] = $l->short;
        }
        $this->load->vars(['activePage' => 'wizerunek']);
    }

    public function index() {
        $this->load->model('WizerunekModel');

        $wizerunekModel = new WizerunekModel();
        $wizerunki = $wizerunekModel->findAll();

        $this->layout('duocms/Wizerunek/index', [
            'wizerunki' => $wizerunki
        ]);
    }

    public function create() {
        $this->load->model('WizerunekModel');

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $order = $this->input->post('order', true);

            $wizerunek = new WizerunekModel();
            $wizerunek->order = $order;
            $wizerunek->insert_wizerunek();

            if (!$wizerunek->id) {
                setAlert('error', 'Wystąpił błąd.');
                redirect('duocms/wizerunek/create');
            }

            $wizerunek->saveImage();
            $this->load->model('WizerunekTranslationModel');

            foreach ($this->languages as $lang) {
                $data = $this->input->post($lang);

                $translation = new WizerunekTranslationModel();
                $translation->wizerunek_id = $wizerunek->id;
                $translation->lang = $lang;
                $translation->title = !empty($data['title']) ? $data['title'] : '';
                $translation->body = !empty($data['body']) ? $data['body'] : '';
                $translation->href = !empty($data['href']) ? $data['href'] : '';
                $translation->color1 = !empty($data['color1']) ? $data['color1'] : '';
                $translation->color2 = !empty($data['color2']) ? $data['color2'] : '';
                $translation->insert_trans();
            }

            setAlert('success','Wizerunek został zapisany.');
            redirect('duocms/wizerunek/edit/' . $wizerunek->id);
        }

        $this->load->helper('form');
        $this->layout('duocms/Wizerunek/form');
    }

    public function edit($id) {
        $this->load->model('WizerunekModel');
        $wizerunek = new WizerunekModel($id);

        if (!$wizerunek->id) {
            show_404();
        }

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            save_custom_fields('wizerunek', $id);
            $order = $this->input->post('order', true);
            $wizerunek->order = $order;
            $res = $wizerunek->update_wizerunek();

            if ($res) {
                setAlert('success','Wizerunek został zapisany.');
            } else {
                setAlert('error','Wystąpił błąd.');
            }
            $wizerunek->saveImage();
            $this->load->model('WizerunekTranslationModel');

            foreach ($this->languages as $lang) {
                $data = $this->input->post($lang);

                $translation = new WizerunekTranslationModel($data['id']);
                $translation->title = !empty($data['title']) ? $data['title'] : '';
                $translation->body = !empty($data['body']) ? $data['body'] : '';
                $translation->href = !empty($data['href']) ? $data['href'] : '';
                $translation->color1 = !empty($data['color1']) ? $data['color1'] : '';
                $translation->color2 = !empty($data['color2']) ? $data['color2'] : '';
                $translation->update_trans();
            }
            redirect('duocms/wizerunek/edit/' . $wizerunek->id);
        }

        $this->load->helper('form');
        $this->layout('duocms/Wizerunek/form', [
            'wizerunek' => $wizerunek
        ]);
    }

    public function image_delete($id) {
        $this->load->model('WizerunekModel');
        $category = new WizerunekModel($id);
        if (!$category->id) {
            show_404();
        }

        $category->image = null;
        $res = $category->update_wizerunek();
        if ($res) {
            setAlert('success', 'Zdjęcie zostało usunięte.');
        } else {
            setAlert('error', 'Wystąpił błąd.');
        }
        redirect($this->input->server('HTTP_REFERER'));
    }

    public function delete($id) {
        $this->load->model('WizerunekModel');
        $category = new WizerunekModel($id);
        if (!$category->id) {
            show_404();
        }
        $res = $category->delete();
        if ($res) {
            setAlert('success', 'Wizerunek został usunięty.');
        } else {
            setAlert('error', 'Wystąpił błąd.');
        }
        redirect($this->input->server('HTTP_REFERER'));
    }
}
