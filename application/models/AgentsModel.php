<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AgentsModel extends MY_Model {
    private $table = 'agents';
    
    public function __construct() {
        parent::__construct();
    }
    
    //przyjmuje tablicę z danymi agenta 
    public function insert_agent($args){
        $res = $this->db->insert($this->table,$args);
        return $res;
    }
    //edycja agenta
    public function update_agent($agent_id,$args){
        $res = $this->db->where('id',$agent_id)->update($this->table,$args); 
        return $res;
    }
    
    public function delete_agent($agent_id){
        $res = $this->db->delete($this->table,array('id'=> $agent_id));
        return $res;
    }
    
    public function get_all_agents(){
        $res = $this->db->order_by('city','ASC')->get($this->table);
        return $res->result();
    }
    public function get_agent($agent_id){
        $res = $this->db->get_where($this->table, array('id' => $agent_id));
        return $res->result();
    }
}