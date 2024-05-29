<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Agents extends Backend_Controller {
    private $agents;
    function __construct() {
        parent::__construct();
        $this->load->model('AgentsModel');
        $this->agents = new AgentsModel();
        $this->load->vars(['activePage' => 'agents']);
    }
    
    function index(){
        if(!empty($_POST)){
            $args = array(
                'city' => $this->input->post('city'),
                'name' => $this->input->post('name'),
                'address' => $this->input->post('address'),
                'tel' => $this->input->post('tel'),
                'email' => $this->input->post('email')
            );
            $res = $this->agents->insert_agent($args);
            if($res){
                $this->setOkay('Agent został dodany.');	
            }
            header("Refresh:0");
        }
        
        $agents = $this->agents->get_all_agents();
        
        $this->layout('duocms/Agents/index', [
            'agents' => $agents
        ]);
    }
    
    function edit($agent_id){
        if(!empty($_POST)){
            $args = array(
                'city' => $this->input->post('city'),
                'name' => $this->input->post('name'),
                'address' => $this->input->post('address'),
                'tel' => $this->input->post('tel'),
                'email' => $this->input->post('email')
            );
            $res = $this->agents->update_agent($agent_id,$args);
            if($res){
                $this->setOkay('Agent został zaktualizowany.');	
            }
            header("Refresh:0");
        }
        
        $agent = $this->agents->get_agent($agent_id);
        
        $this->layout('duocms/Agents/edit', [
            'agent' => $agent[0]
        ]);
    }
    
    function delete($agent_id){
        if(!empty($agent_id)){
            $res = $this->agents->delete_agent($agent_id);
            if($res){
                $this->setOkay('Agent został usunięty.');	
            }
            header("location: /duocms/agents/index");
        }
    }
}
