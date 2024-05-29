<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Recruitment extends Backend_Controller {
    private $recruitment;
    function __construct() {
        parent::__construct();
        $this->load->model('RecruitmentModel');
        $this->recruitment = new RecruitmentModel();
        $this->load->vars(['activePage' => 'recruitment']);
    }
    
    function index(){
        $positions = $this->recruitment->get_positions();
        $candidates = $this->recruitment->get_candidates();
        $this->layout('duocms/Recruitment/index', array(
            'positions' => $positions,
            'candidates' => $candidates,
            'recruitment' => $this->recruitment
        ));
    }
    
    function delete_candidate($candidate_id){
        if($this->recruitment->delete_candidate($candidate_id)){
            setAlert('warning','Usunięto kandydata');
        } else {
            setAlert('error','Nie udało się usunąć kandydata');
        }
        redirect(site_url('duocms/recruitment'));
    }
    
    function delete_position($id){
        $res = $this->recruitment->delete_position($id);
        if($res){
            setAlert('warning','Usunięto stanowisko');
        } else {
            setAlert('error','Nie udało się udunąc stanowiska');
        }
        redirect(site_url('duocms/recruitment'));
    }
    
    function ajax_add_position(){
        $this->form_validation->set_rules('name','Nazwa stanowiska', 'required');
        if($this->form_validation->run()){
            $res = $this->recruitment->add_position(array(
                'name' => $this->input->post('name')
            ));
            if($res){
                setAlert('success', 'Dodano nowe stanowisko');
                ajax_res(array('1','Dodano nowe stanowisko','refresh'));
            } else {
                ajax_res(array('0','Nie udało się dodać stanowiska'));
            }
        } else {
            ajax_res(array('0', validation_errors()));
        }
    }
}