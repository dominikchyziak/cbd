<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Frontend_Controller extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
                $this->load->model("User_Model");
		// Set defaults
		$this->meta_title = get_option("meta_title");
		$this->meta_desc = get_option("meta_desc");
                $this->keywords = get_option("meta_keywords");
		$this->scripts = array();
		$this->styles = array();

		$this->set_layout('main');

		$this->load->model('CustomElementModel');
		$this->load->model('CustomElementFieldModel');
                
//                if(empty($this->session->userdata('basket')) && !empty($this->input->cookie('basket', true))){
//                    $this->session->set_userdata('basket', json_decode($this->input->cookie('basket', true)));
//                }
	}
        
        public function show404()
        {
            $this->layout('templates/404page');
        }

}
