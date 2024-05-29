<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Klasa przetwarzania konfiguracji
 * @author Daniel Kuczewski <info@septemonline.com>
 */
class ConfigurationModel extends MY_Model {

    /**
     * tabela główna opcji
     */
    protected $_table_name = 'duo_options';
    /**
     * tabela z językami
     */
    protected $_table_languages = 'duo_languages';
    protected $_table_strings = 'duo_strings';
    protected $_table_strings_translations = 'duo_strings_translations';

    /**
     * @param $category string opcjonalnie klucz kategorii, wtedy zwróci tylko z danej kategorii
     * @return Array Zwraca tablicę obiektów id,name,key,value,category,order,visible,created_at,updated_at
     */
    public function getAllOptions($category = ''){
        if(!empty($category)) {
            $this->db->where('category',$category);
        }
        if(ENVIRONMENT != 'development'){
            $this->db->where('visible', 1);
        }
        $q = $this->db->get_where($this->_table_name);
        return $q->result();
    }
    
    /**
     * 
     * @param string $key pobiera klucz konkretnego pola opcji
     * @return string zwraca wartość konkretnej opcji
     */
    public function getOption($key){
        $q = $this->db->get_where($this->_table_name, array("key"=>$key))->row();
        if(!empty($q)){
            return $q->value;
        } else {
            return null;
        }
    }
    
    /**
     * Dodawanie opcji systemowej
     * @param array $args Tablica argumentów opcji name,key,value,category,order,visible
     * @return bool wynik dodania
     */
    public function add_option($args){
        $res = $this->db->insert($this->_table_name, $args);
        return $res;
    }
    
    /**
     * Zapisuje widoczność opcji na podstawie id
     * @param int $id id opcji
     * @param int $visible 1 lub 0 w zależności czy ma być widoczne w panelu
     * @return wynik
     */
    public function save_visible($id, $visible){
        return $this->db->where('id',$id)->update($this->_table_name, array('visible' => $visible));
    }

    public function updateOption($key, $value){
        $q = $this->db->where(array("key"=>$key))->update($this->_table_name, array("value"=>$value));
        return $q;
    }
    
    public function add_lang($short, $name){
        $q = $this->db->insert($this->_table_languages, array("short"=>$short, "name"=>$name));
        //kopiowanie elementów
        $this->copy_lang('duo_custom_element_fields', $short, 'title');
        //kopiowanie kategorii galerii
        $this->copy_lang('duo_category_translations', $short, 'category_id');
        //kopiowanie galeri
        $this->copy_lang('duo_gallery_translations', $short, 'gallery_id');
        //kopiowanie kategorii produktów
        $this->copy_lang('duo_offer_categories_translations', $short, 'offer_category_id');
        //kopiowanie stron
        $this->copy_lang('duo_pages_translations', $short, 'page_id');
        //kopiowanie opisów do zdjęć
        $this->copy_lang('duo_photos_translations', $short, 'photo_id');
        //kopiowanie menu
        $this->copy_lang('duo_menu_translations', $short, 'menu_id');
        //kopiowanie aktualności
        $this->copy_lang('duo_news_translations', $short, 'news_id');
        //kopiowanie wizerunków
        $this->copy_lang('duo_wizerunki_translations', $short, 'wizerunek_id');
        $this->copy_lang('duo_products_translations', $short, 'product_id');
        return $q;
    }
    
    private function copy_lang($table_name, $new_lang, $ident){
        $q_elements = $this->db->get_where($table_name, array('lang'=>'pl'))->result();
        if(!empty($q_elements)){
            foreach($q_elements as $r){
                $q_tmp = $this->db->get_where($table_name, array('lang'=>$new_lang, $ident => $r->$ident))->result();
                if(empty($q_tmp)){
                    $to_add = (array)$r;
                    $to_add['lang'] = $new_lang;
                    $to_add['id'] = null;
                    $this->db->insert($table_name, $to_add);
                }
            }
            
        }
    }


    public function get_languages(){
        $q = $this->db->get($this->_table_languages);
        return $q->result();
    }
    public function delete_language($id){
        $q = $this->db->delete($this->_table_languages, array("id" => $id));
        return $q;
    }
    
    public function add_string($key, $strings){
        $this->db->insert($this->_table_strings, array("key"=>$key, "string"=>$strings["pl"]));
        $id = $this->db->insert_id();
        foreach($strings as $lang=>$string){
            $this->db->insert($this->_table_strings_translations, array(
                "string_id"=>$id, 
                "string"=>$string,
                "lang"=>$lang
            ));
        }
    }
    
    public function get_all_translations(){
        $translations = array();
        $q = $this->db->get($this->_table_strings)->result();
        if(!empty($q)){
            foreach($q as $string){
                $q2 = $this->db->get_where($this->_table_strings_translations, array("string_id" => $string->id))->result();
                foreach($q2 as $trans){
                    $translations[$string->key][$trans->lang] = $trans->string;    
                }
            }
        }
        return $translations;
    }

}
