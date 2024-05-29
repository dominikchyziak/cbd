<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends Backend_Controller {

    public $user_obj;
    
    public function __construct() {
        parent::__construct();
        $this->load->model("User_model");
        $this->load->vars(['activePage' => 'users']);
        $this->user_obj = new User_Model();
    }

    public function index($action = "", $user_id = "") {
        
        //Dodawanie nowego
        if ($action == 'delete_user' && !empty($user_id)) {
            $this->User_model->delete_user($user_id);
            setAlert('warning','Użytkownik został usunięty.');
                    redirect(site_url('duocms/users/index'));
            
        }

        $data["users"] = $this->User_model->get_list();
        $this->layout('duocms/Users/index', $data);
    }
    
    public function add_edit_user($id = 0){
        $data = array();
        if(!empty($_POST)){
            $this->load->library('form_validation');
            //$this->form_validation->set_rules('name', 'Login', 'required');
            if(empty($id)){
                $this->form_validation->set_rules('email', 'Adres e-mail', 'required|valid_email|is_unique[duo_users.email]');
            }
            $this->form_validation->set_rules('type', 'Typ', 'is_natural');

            if(empty($id) || !empty($_POST['password'])){
                $this->form_validation->set_rules('password_repeat','Powtórz hasło','required');
                $this->form_validation->set_rules('password', 'Hasło', 'required|matches[password_repeat]');
            }
            
            if ($this->form_validation->run()) {
                if (empty($id)) {
                    $this->User_model->add_user(
                            $this->input->post('email'),
                            $this->input->post('password'),
                            $this->input->post('first_name'),
                            $this->input->post('last_name'),
                            $this->input->post('phone'),
                            $this->input->post('city'),
                            $this->input->post('zip_code'),
                            $this->input->post('street'),
                            $this->input->post('building_number'),
                            $this->input->post('type'),
                            $this->input->post('status'),
                            $this->input->post('discount')
                    );
                    setAlert('success','Użytkownik został dodany.');
                    redirect(site_url('duocms/users/add_edit_user/'. $this->db->insert_id()));
                } else {
                    $this->User_model->update_user(
                            $id,
                            $this->input->post('email'),
                            $this->input->post('password'),
                            $this->input->post('first_name'),
                            $this->input->post('last_name'),
                            $this->input->post('phone'),
                            $this->input->post('city'),
                            $this->input->post('zip_code'),
                            $this->input->post('street'),
                            $this->input->post('building_number'),
                            $this->input->post('type'),
                            $this->input->post('status'),
                            $this->input->post('discount')
                    );
                    setAlert('success','Użytkownik został zaktualizowany.');
                    redirect(site_url('duocms/users/add_edit_user/'. $id));
                }
            }
            $data = $this->input->post();
        }
        if(!empty($id)){
            $data = (array)$this->User_model->get_user($id) ;
        }
        
        $rebate_groups = $this->user_obj->get_rebate_groups();
        $data['rebate_groups'] = $rebate_groups;
        $this->layout('duocms/Users/form', $data);
    }
    
    public function rebate_groups(){
       
        $data = $this->input->post();
        if(!empty($data)){
            if(empty($data['id'])){
                $this->form_validation->set_rules('name','Nazwa','required|is_unique[duo_users_rebate_groups.name]|is_unique[duo_users_rebate_groups.discount]');
            } else {
                $this->form_validation->set_rules('name','Nazwa','required');
            }
            if($this->form_validation->run()){
                if(empty($data['id'])){
                    $this->user_obj->add_rebate_group($data['name'], $data['discount']);
                    setAlert('success', 'Dodano grupę rabatową');
                } else {
                    $this->user_obj->update_rebate_group($data['id'], $data['name'], $data['discount']);
                    setAlert('success', 'Zaktualizowano');
                }
            } else {
                setAlert('error', 'Błąd, nazwa i rabat musza być nie puste i unikalne');
            }
        }
        $groups = $this->user_obj->get_rebate_groups();
        $this->layout('duocms/Users/rebate_groups',array(
            'groups' => $groups
        ));
    }

}
