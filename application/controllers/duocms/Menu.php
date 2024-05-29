<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menu extends Backend_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->vars(['activePage' => 'menu']);
    }

    public function index() {
        $this->load->model('MenuModel');

        $menuModel = new MenuModel();
        $menus = $menuModel->get_menus();

        $this->layout('duocms/Menu/index', [
            'menus' => $menus
        ]);
    }
    
    public function add_menu(){
        $this->load->model('MenuModel');
        $menuModel = new MenuModel();
        if(!empty($_POST['name'])){
            $res = $menuModel->add_menu($this->input->post('name'));
            if($res){
                setAlert('success','Menu dodane!<br>Teraz dodaj elementy.');
                redirect(site_url("duocms/menu/get/". $res));
            }
        }
        $this->layout('duocms/Menu/add_menu', []);
    }
    
    public function get($menu_id){
        $this->load->model('MenuModel');
        $menuModel = new MenuModel();
        
        if (!empty($_POST['element'])) {
            $i = 1;
            foreach ($_POST['element'] as $id) {
                $menuModel->sort_item($id, $i);
                $i++;
            }
            die();
        }

        if($this->input->post("action") == "add"){
            $args["parent_id"] = $this->input->post("parent_id");
            $args["order_menu"] = $this->input->post("order_menu");
            foreach ($this->input->post("name") as $key=>$value){
                $args["translations"][$key]["name"] = $value;
            }
            foreach ($this->input->post("link") as $key=>$value){
                $args["translations"][$key]["link"] = $value;
            }
            $menuModel->add_item($args, $menu_id);
            setAlert('success','Dodano!');
        }

        $menu = $menuModel->get_list($menu_id);
        $parents = array("0"=>"Brak");
        if(!empty($menu)){
            foreach($menu as $id=>$r){
                if($r["parent_id"]== "0"){
                    $parents[$id] = $r["name"];
                }
            }
        }

        $links = $this->get_links();
        $this->layout('duocms/Menu/get', [
            'menu' => $menu,
            'parents' => $parents,
            'links' => $links,
            'menu_id' => $menu_id
        ]);
    }
    
    public function edit($id){
        $this->load->model('MenuModel');
        $menuModel = new MenuModel();
         $item  = $menuModel->get_item($id);
        $menu = $menuModel->get_list();
        if($this->input->post("action") == "edit"){
            $args["parent_id"] = $this->input->post("parent_id");
            $args["order_menu"] = $this->input->post("order_menu");
            foreach ($this->input->post("name") as $key=>$value){
                $args["translations"][$key]["name"] = $value;
            }
            foreach ($this->input->post("link") as $key=>$value){
                $args["translations"][$key]["link"] = $value;
            }
            $menuModel->edit_item($id,$args);
            setAlert('success','Pozycja menu została zmieniona.');
            redirect(site_url("duocms/menu/get/".$item['menu_id']));
        }
       
        $parents = array("0"=>"Brak");
        if(!empty($menu)){
            foreach($menu as $id=>$r){
                if($r["parent_id"]== "0"){
                    $parents[$id] = $r["name"];
                }
            }
        }
        $this->layout('duocms/Menu/edit', [
            'item' => $item,
            'parents' => $parents
        ]);
    }
    
    public function delete($id, $menu_id = 1){
        $this->load->model('MenuModel');
        $menuModel = new MenuModel();
        $menuModel->delete_item($id);
        setAlert('warning','Pozycja menu została usunięta.');
        redirect(site_url("/duocms/menu/get/".$menu_id));
    }
    
    public function auto_change(){
        $option = explode('_',$this->input->post('option'));
        $type = $option[0];
        $id = $option[1];
        $types = array('template' => array(),'page' => array(),'productCategory' => array(),'product' => array(),'gallery' => array(),'news' => array());
        $types['template'] = $this->get_templates();
        
        echo json_encode($types[$type][$id]);
    }
    
    public function get_links(){
        $links['template'] = $this->get_templates();
        return $links;
    }
    
    public function get_templates(){
        $templates = array(
            ["Strona główna","/"],
            ["O firmie","o-firmie"],
            ["Kontakt","kontakt"],
            ["Oferta","oferta"],
            ["Promocje","promocje"],
            ["Usługi","uslugi"],
        );
        return $templates;
    }

}
