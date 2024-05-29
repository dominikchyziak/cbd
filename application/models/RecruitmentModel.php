<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class RecruitmentModel extends MY_Model {
    private $table = 'positions';
    private $table_candidates = 'candidate';


    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Dodaje stanowisko pracy
     * @param array $args tablica argumentów na ten moment ['name' => NAZWA]
     * @return boolean
     */
    public function add_position($args){
        $res = $this->db->insert($this->table, $args);
        return $res;
    }
    
    /**
     * Zwraca tablicę stanowisk
     * @return array
     */
    public function get_positions(){
        $res = $this->db->get($this->table);
        return $res->result();
    }
    
    /**
     * Usuwa stanowisko i zwraca bool
     * @param int $id identyfikator stanowiska
     * @return bool
     */
    public function delete_position($id){
        $res = $this->db->delete($this->table, array('id' => $id));
        return $res;
    }
    
    /**
     * Dodaje kandydata do bazy i zwraca jego identyfikator
     * @param array $args pobiera tablicę danych kandydata czyli ['candidate_name' => IMIE_I_NAZWISKO, 'position_id' => ID_STANOWISKA, 'email' => EMAIL, 'phone' => TELEFON, 'message' =>WIADOMOŚĆ, 'files' => JSON_ZE_SCIEZKAMI]
     * @return int identyfikator kandydata
     */
    public function add_candidate($args){
        $this->db->insert($this->table_candidates, $args);
        $res = $this->db->insert_id();
        return $res;
    }
    
    /**
     * Pobieram i zwracam połączoną tablicę pracowników ze stanowiskami
     * @return array
     */
    public function get_candidates(){
        $q = $this->db->select($this->table_candidates.'.*, '.$this->table.'.name as name')
                ->join($this->table_candidates, $this->table_candidates.'.position_id = '.$this->table.'.id')->get($this->table);
        return $q->result();
    }
    
    /**
     * Pobieram konkretnego kandyata
     * @param int $id identyfikator kandydata
     * @return obj 
     */
    public function get_candidate($id){
        $q = $this->db->get_where($this->table_candidates, array('id' => $id));
        return $q->row();
    }
    
    /**
     * Usuwa konkretnego kandydata wraz z jego plikami
     * @param int $id
     * @return bool
     */
    public function delete_candidate($id){
        $candidate = $this->get_candidate($id);
        $files = json_decode($candidate->files);
        if(!empty($files)){
            foreach($files as $path){
                unlink($path);
            }
        }
        $res = $this->db->delete($this->table_candidates, array('id' => $id));
        return $res;
    }
    
    /**
     * Pobiera linki do plików kandydata
     * @param object $candidate obiekt kandydata
     * @return array tablica linków
     */
    public function get_files($candidate){
        $paths = json_decode($candidate->files);
        $links = array();
        if(!empty($paths)){
            foreach($paths as $path){
                $str_ar = explode('/uploads/', $path);
                $links[] = base_url('uploads/'.$str_ar[1]);
            }
        }
        return $links;
    }
}