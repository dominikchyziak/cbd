<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function compare_agents($a,$b){
        setlocale(LC_ALL, 'pl_PL.utf-8');
        $stra = $a->name;
        $strb = $b->name;
        return strcoll($stra, $strb);
    }
    
class Agents extends Frontend_Controller {

    private $agents;

    public function __construct() {
        parent::__construct();
        $this->load->model('AgentsModel');
        $this->agents = new AgentsModel();
        $home = site_url('/');
        $this->breadcrumbs[] = "<a href=$home>" . (new CustomElementModel('9'))->getField('Strona główna') . "</a>";
    }

    public function index() {
        $site = site_url('agents');
        $this->breadcrumbs[] = "<a href=$site>" . (new CustomElementModel('9'))->getField('Agenci') ."</a>";
        $all_agents = $this->agents->get_all_agents();
        usort($all_agents, "compare_agents");
        $this->layout('Agents/index', array(
            'all_agents' => $all_agents
        ));
    }

}
