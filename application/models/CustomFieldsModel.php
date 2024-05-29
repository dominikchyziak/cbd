<?php

class CustomFieldsModel extends MY_Model {
    
    /**
     *Tabela główna pól
     * @var string
     */
    public $table = 'duo_custom_fields';
    /**
     * Tabela pomocnicza z tłumaczeniami
     * @var string
     */
    public $table_translation = 'duo_custom_fields_translations';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Dodawanie nowego pola lub aktualizacja istniejącego
     * @param string $type typ pola
     * @param string $element typ elementu np. wizerunek, news, product, offer_category, page
     */
    public function add_field($type, $element, $name, $element_id = NULL){
        if($this->check_field($type, $element, $name, $element_id)){
            return FALSE;
        } else {
            $this->db->insert($this->table, array(
                'type' => $type,
                'element' => $element,
                'name' => $name,
                'element_id' => $element_id
            ));
            return TRUE;
        }
    }
    
    /**
     * Funkcja sprawdza czy taki element już istnieje w bazie
     * @param string $type typ pola np text, ckeditor, image, file
     * @param string $element grupa elementów np wizerunek
     * @param string $name nazwa tego pola
     * @param int $element_id identyfikator elementu
     * @return boolean
     */
    public function check_field($type, $element, $name, $element_id){
        $this->db->where(array(
            'type' => $type,
            'element' => $element,
            'name' => $name,
            'element_id' => $element_id
        ));
        $res = $this->db->get($this->table);
        if(!empty($res->result())) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Aktualizacja tłumaczenia, kiedy nie ma dodaje rekord
     * @param int $field_id identyfikator tego pola
     * @param string $value zawartość tego pola
     * @param string $lang skrót tego języka np 'pl'
     * @return boolean
     */
    public function update_field_lang($field_id, $value, $lang){
        $this->db->where(array(
            'field_id' => $field_id,
            'lang' => $lang
        ));
        $q = $this->db->get($this->table_translation);
        if(!empty($q->result())){
            $this->where(array(
                'field_id' => $field_id,
                'lang' => $lang
            ))->update($this->table_translation, array('value' => $value));
        } else {
            $this->db->insert($this->table_translation, array(
                'field_id' => $field_id,
                'lang' => $lang,
                'value' => $value
            ));
        }
        return TRUE;
    }
    
    /**
     * Pobiera i wypełnia pola danego elementu w określonym języku
     * @param type $element
     * @param type $element_id
     * @param type $lang
     * @return boolean
     */
    public function get_custom_fields_by_lang($element, $element_id, $lang = 'pl'){
        $sql = "SELECT * FROM $this->table WHERE (`element_id` = '".$element_id."' OR `element_id` = 0) AND `element` = '".$element."'";
        //$q = $this->db->or_where(array(
          //  'element_id' => $element_id,
            //'element_id' => 0
        //))->get_where($this->table, array('element' => $element));
        $q = $this->db->query($sql);
        $res = array();
        $qr = $q->result();
        if(!empty($qr)){
            foreach($qr as $r){
                $el = (array)$r;
                $q2 = $this->db->get_where($this->table_translation, array('field_id' => $r->id, 'lang' => $lang, 'element_id_translation' => $element_id))->row();
               
                if(!empty($q2)){
                    $el['value'] = $q2->value;
                } 
                $res[] = (object)$el;
            }
        }
        if(!empty($res)){
            return $res;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Najzwyklejsze usunięcie pola
     * @param int $id identyfikator pola
     */
    public function delete_field($id){
        $res = $this->db->delete($this->table, array('id'=>$id));
        if($res){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Zapisuje tłumaczenie danego pola
     * @param int $field_id identyfikator pola
     * @param int $element_id_translation identyfikator elementu
     * @param string $value zawartość
     * @param string $lang skrót języku, domuślnie polski
     * @return type
     */
    public function save_field($field_id,$element_id_translation, $value, $lang = 'pl'){
        $q = $this->db->get_where($this->table_translation , array('field_id' => $field_id , 'lang' => $lang, 'element_id_translation' => $element_id_translation))->result();
        if(!empty($q)){
           $res =  $this->db->where(array('field_id' => $field_id, 'element_id_translation' => $element_id_translation, 'lang' => $lang))->update($this->table_translation, array('value' => $value));
        } else {
            $res = $this->db->insert($this->table_translation, array('field_id' => $field_id, 'element_id_translation' => $element_id_translation, 'lang' => $lang, 'value' => $value));
        }
        return $res;
    }
    
    /**
     * Zwracam zawartość pola po jego id i id elementu do którego ta zawartość jest przypisana
     * @param int $field_id identyfikator pola
     * @param int $element_id identyfikator elementu
     * @param string $lang skrót języka domyślnie pl dla polskiego
     * @return string
     */
    public function get_field($field_id, $element_id, $lang = 'pl'){
        $q = $this->db->get_where($this->table_translation, array('field_id' => $field_id, 'element_id_translation' => $element_id, 'lang' => $lang));
        
        if(!empty($q->result())){
            $res = $q->row()->value;
        } else {
            $res = false;
        }
        return $res;
    }
}
