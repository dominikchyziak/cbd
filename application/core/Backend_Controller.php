<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Backend_Controller extends MY_Controller {

    public $left_col_active = array();

    public function __construct() {
        parent::__construct();

        // Set defaults
        $this->meta_title = 'DuoCMS - ' . ADMIN_COMPANY_NAME;

        $this->scripts = array(
            assets('plugins/ckeditor/ckeditor.js?v=1'),
            assets('js/jquery-3.2.0.min.js'),
            assets('duocms/js/bootstrap.min.js'),
            assets('duocms/js/metisMenu.min.js'),
            assets('plugins/jquery-ui/jquery-ui.min.js'),
            assets('js/jquery-migrate-1.2.1.min.js'),
            assets('plugins/toastr/toastr.min.js'),
            assets('duocms/js/bootstrap-multiselect.js'),
            assets('js/jquery.datetimepicker.full.js'),
            assets('duocms/js/custom.js'),
            assets('duocms/js/script.js'),
        );

        $this->styles = array(
            assets('duocms/css/bootstrap.min.css'),
            assets('duocms/css/metisMenu.min.css'),
            assets('plugins/jquery-ui/jquery-ui.min.css'),
            assets('plugins/font-awesome-4.3.0/css/font-awesome.min.css'),
            assets('plugins/toastr/toastr.min.css'),
            assets('duocms/css/bootstrap-multiselect.css'),
            assets('css/jquery.datetimepicker.css'),
            assets('duocms/css/custom.css'),
            assets('duocms/css/style.css'),
        );

        $this->set_layout('admin');

        $this->load->helper('admin');
        $this->load->model('User_Model');
        $this->load->model('Admin_Model');
        
        if (!Admin_Model::isLoggedIn()) {
            redirect('duocms/login?redirect=' . urlencode($this->input->server('REQUEST_URI')));
        }
        if (Admin_Model::loggedin()->type == "1") {
            redirect(site_url("account"));
        }
    }

}
