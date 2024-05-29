<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MenuModel extends MY_Model {

	protected $_table_name = 'duo_menu';
        protected $table_translations = 'duo_menu_translations';

        public $id;
        public $menus_id;
        public $parent_id;
	public $name; 
        
        public function add_menu($name){
            $this->db->insert('duo_menus', array("name" => $name));
            return $this->db->insert_id();
        }
        public function get_menus(){
            $res = $this->db->get('duo_menus');
            return $res->result();
        }
        
        /*dodane*/
        public function get_menu_name($id){
            $this->db->select('name');
            $query= $this->db->get_where('duo_menus', array('id' => $id));
            foreach ($query->result() as $row)
            {
                return $row->name;
            }
        }
        
        public function get_list($menu_id = 1){
            $q = $this->db->where('menus_id',$menu_id)->order_by("order_menu","ASC")->get($this->_table_name)->result();
            $result = array();
            foreach ($q as $r){
                $q2 = $this->db->get_where($this->table_translations, array("menu_id" => $r->id));
                $result[$r->id]["parent_id"] = $r->parent_id;
                $result[$r->id]["order_menu"] = $r->order_menu;
                $result[$r->id]["name"] = "";
                $result[$r->id]["link"] = "";
                foreach ($q2->result() as $row){
                    $result[$r->id]["name"] .= " " . $row->name . " [" . $row->lang . "] ";
                    $result[$r->id]["link"] .= " " . $row->link . " [" . $row->lang . "] ";
                }
            }
            return $result;
        }
        
        public function get_item($id){
            $q = $this->db->get_where($this->_table_name, array("id"=>$id))->row();
            $result = array();
            $result["parent_id"] = $q->parent_id;
            $result["order_menu"] = $q->order_menu;
            $result['menu_id'] = $q->menus_id;
            $q2 = $this->db->get_where($this->table_translations, array("menu_id" => $q->id));
            $result["translation"] = array();
            $i = 0;
            foreach ($q2->result() as $row){
                $result["translation"][$i]["name"] = $row->name;
                $result["translation"][$i]["link"] = $row->link;
                $result["translation"][$i]["lang"] = $row->lang;
                $i++;
            }
            return $result;
        }

        //funkcja pobiera tablicę jezykową pozycji menu
        public function add_item($args, $menu_id = 1){
            $this->db->insert($this->_table_name, array(
                "menus_id" => $menu_id,
                "parent_id"=>$args["parent_id"],
                "order_menu" => $args["order_menu"]
            ));
            $id = $this->db->insert_id();
            if(!empty($args["translations"])){
                foreach($args["translations"] as $lang=>$trans){
                    $this->db->insert($this->table_translations, array(
                        "menu_id" => $id,
                        "lang" => $lang,
                        "name" => $trans["name"],
                        "link" => $trans["link"]
                    ));
                }
            }
        }
        
        public function edit_item($id,$args){
            $this->db->where("id",$id)->update($this->_table_name, array(
                "parent_id"=>$args["parent_id"],
                "order_menu" => $args["order_menu"]
            ));

            if(!empty($args["translations"])){
                foreach($args["translations"] as $lang=>$trans){
                    $this->db->where(array("menu_id"=>$id, "lang"=>$lang))
                    ->update($this->table_translations, array(
                        "name" => $trans["name"],
                        "link" => $trans["link"]
                    ));
                }
            }
        }
        
        public function delete_item($id){
            $this->db->delete($this->_table_name, array("id"=>$id));
            $this->db->delete($this->_table_name, array("parent_id"=>$id));
            return TRUE;
        }
        
        //pobiera menu
        public function get_menu($lang = "pl", $menu_id = 1){
            $result = array();
            $q1 = $this->db->query("SELECT $this->_table_name.id AS id, $this->table_translations.link AS link, $this->table_translations.name AS name "
                    . "FROM $this->_table_name "
                    . "JOIN $this->table_translations ON $this->table_translations.menu_id = $this->_table_name.id "
                    . "WHERE $this->_table_name.parent_id = 0 AND $this->table_translations.lang = '$lang' AND $this->_table_name.menus_id = '$menu_id' "
                    . "ORDER BY $this->_table_name.order_menu ASC")->result();
            if(!empty($q1)){
                foreach ($q1 as $r1){
                    $result[$r1->id]["link"] = $r1->link;
                    $result[$r1->id]["name"] = $r1->name;
                    $result[$r1->id]["children"] = array();
                    $q2 = $this->db->query("SELECT  $this->_table_name.id AS id, $this->table_translations.link AS link, $this->table_translations.name AS name "
                            . "FROM $this->_table_name "
                    . "JOIN $this->table_translations ON $this->table_translations.menu_id = $this->_table_name.id "
                    . "WHERE $this->_table_name.parent_id = '$r1->id' AND $this->table_translations.lang = '$lang'  AND $this->_table_name.menus_id = '$menu_id' "
                            . "ORDER BY $this->_table_name.order_menu ASC")->result();
                    if(!empty($q2)){
                        foreach ($q2 as $r2){
                            $result[$r1->id]["children"][$r2->id]["link"] = $r2->link;
                            $result[$r1->id]["children"][$r2->id]["name"] = $r2->name;
                        }
                    }
                }
            }
            return $result;
        }
        
        public function sort_item($item_id, $position){
            $this->db->where('id',$item_id);
            $res = $this->db->update($this->_table_name, [
                'order_menu' => $position
            ]);
            return $res;
        }
}