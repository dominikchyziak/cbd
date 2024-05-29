<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class News extends Backend_Controller {

    public $languages = array();
    public $facebook;
    
    public function __construct() {
        parent::__construct();
        $langs = get_languages();
        foreach ($langs as $l) {
            $this->languages[] = $l->short;
        }
        $this->load->vars(['activePage' => 'news']);
    }

    public function index() {
        $this->load->model('NewsModel');
        $newsModel = new NewsModel();
        $news = $newsModel->findAll();

        $this->layout('duocms/News/index', [
            'news' => $news
        ]);
    }

    public function create() {
        $this->load->model('NewsModel');
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $started_at = $this->input->post('started_at', true);
            if (empty($started_at)) {
                $started_at['date'] = date('Y-m-d');
                $started_at['hour'] = date('H');
                $started_at['minute'] = date('i');
            }
            $dateFormat = sprintf('%s %s:%s:00', $started_at['date'], $started_at['hour'], $started_at['minute']);

            $news = new NewsModel();
            $news->started_at = (new DateTime($dateFormat))->format('Y-m-d H:i:s');
            $news->category_id = $this->input->post('category_id');
            $news->add();

            if (!$news->id) {
                $this->setError('Wystąpił błąd.');
                redirect('duocms/news/create');
            }

            $this->load->model('NewsTranslationModel');

            foreach ($this->languages as $lang) {
                $data = $this->input->post($lang);

                $translation = new NewsTranslationModel();
                $translation->news_id = $news->id;
                $translation->lang = $lang;
                $translation->title = $data['title'];
                $translation->excerpt = $data['excerpt'];
                $translation->body = $data['body'];
                $translation->image = $data['image'];
                $translation->meta_title = !empty($data['meta_title']) ? $data['meta_title'] : '';
                $translation->meta_description = !empty($data['meta_description']) ? $data['meta_description'] : '';
                $translation->custom_url = !empty($data['custom_url']) ? $data['custom_url'] : '';
                $translation->insert();
            }

            setAlert('success', 'Wpis został zapisany.');
            $facebook_api = get_option('admin_modules_facebook_active');
//            if($facebook_api){
//                redirect('duocms/facebook/add_post/'.$news->id);
//                die();
//            }
            redirect('duocms/news/edit/' . $news->id);
        }

        $this->load->helper('form');

        $this->layout('duocms/News/form');
    }

    public function edit($id) {
        $this->load->model('NewsModel');
        $news = new NewsModel($id);


        if (!$news->id) {
            show_404();
        }

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            save_custom_fields('news', $id);
            $started_at = $this->input->post('started_at', true);
            if (empty($started_at)) {
                $started_at['date'] = date('Y-m-d');
                $started_at['hour'] = date('H');
                $started_at['minute'] = date('i');
            }
            $dateFormat = sprintf('%s %s:%s:00', $started_at['date'], $started_at['hour'], $started_at['minute']);

            $news->started_at = (new DateTime($dateFormat))->format('Y-m-d H:i:s');
            $news->category_id = $this->input->post('category_id');
            $res = $news->edit();

            if ($res) {
                setAlert('success', 'Wpis został zapisany.');
            } else {
                setAlert('error', 'Wystąpił błąd.');
            }

            $this->load->model('NewsTranslationModel');

            foreach ($this->languages as $lang) {
                $data = $this->input->post($lang);

                $translation = new NewsTranslationModel($data['id']);
                $translation->title = $data['title'];
                $translation->excerpt = $data['excerpt'];
                $translation->body = $data['body'];
                $translation->image = $data['image'];
                $translation->meta_title = !empty($data['meta_title']) ? $data['meta_title'] : '';
                $translation->meta_description = !empty($data['meta_description']) ? $data['meta_description'] : '';
                $translation->custom_url = !empty($data['custom_url']) ? $data['custom_url'] : '';
                $translation->update();
            }

            redirect('duocms/news/edit/' . $news->id);
        }

        $this->load->helper('form');

        $this->layout('duocms/News/form', [
            'news' => $news
        ]);
    }

    public function delete($id) {
        $this->load->model('NewsModel');
        $category = new NewsModel($id);

        if (!$category->id) {
            show_404();
        }

        $res = $category->delete();

        if ($res) {
            setAlert('warning', 'Wpis został usunięty.');
        } else {
            setAlert('error', 'Wystąpił błąd.');
        }

        redirect($this->input->server('HTTP_REFERER'));
    }

}
