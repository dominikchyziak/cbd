<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class FacebookModel extends MY_Model {
    
    public $app_id;
    public $app_secret;
    public $page_id;
    public $page_name;
    public $fb;
    
    public function __construct() {
        parent::__construct();
        $this->load->library('Facebook/autoload');
        $this->app_id = get_option('admin_modules_facebook_app_id');
        $this->app_secret = get_option('admin_modules_facebook_app_secret');
        $this->page_id = get_option('admin_modules_facebook_page_id');
        $this->page_name = get_option('admin_modules_facebook_page_name');
        $this->fb = new \Facebook\Facebook([
            'app_id' => $this->app_id,
            'app_secret' => $this->app_secret,
            'default_graph_version' => 'v2.10'
        ]);
    }
    
    public function login(){
        
    }
    
}