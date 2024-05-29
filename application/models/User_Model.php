<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_Model extends MY_Model {

    protected $_table_name = 'users';
    protected $_table_rebate_groups = 'duo_users_rebate_groups';
    public $id;
    public $email;
    public $password;
    public $name;
    public $first_name;
    public $last_name;
    public $phone;
    public $city;
    public $zip_code;
    public $street;
    public $building_number;
    public $type;
    public $discount;
    public $newsletter;
    public static $loggedin;

    public static function loggedin() {
        $CI = & get_instance();

       
        if (!is_object(self::$loggedin) && !empty($CI->session->userdata('login')['user']['id'])) {
            self::$loggedin = User_Model::factory(
                            $CI->session->userdata('login')['user']['id']
            );
        }

        return self::$loggedin;
    }

    public static function isLoggedIn() {
        return User_Model::loggedin()->id > 0;
    }
    
    public function check_pass($email, $password){
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $password = md5($password);

        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $this->db->where('status', 0);
        $User = $this->find();
        if(!empty($User)){
            return $User;
        } else {
            return FALSE;
        }
    }

    public function login($email, $password) {
        $User = $this->check_pass($email, $password);

        if ($User) {
            $login = array(
                'user' => array(
                    'id' => $User->id,
                    'email' => $User->email,
                    'name' => $User->name,
                    'first_name' => $User->first_name,
                    'last_name' => $User->last_name,
                    'phone' => $User->phone,
                    'city' => $User->city,
                    'zip_code' => $User->zip_code,
                    'street' => $User->street,
                    'building_number' => $User->building_number,
                    'type' => $User->type,
                    'discount' => $User->discount,
                    'newsletter' => $User->newsletter
                ),
            );
            $this->session->set_userdata('login', $login);
            $_SESSION['KCFINDER']['disabled'] = FALSE;
            return TRUE;
        }
        return FALSE;
    }

    public function get_list() {
        $q = $this->db->get($this->_table_name)->result();
        return $q;
    }
    public function get_user($id){
        $q = $this->db->get_where($this->_table_name, array('id' => $id))->row();
        return $q;
    }

    public function add_user($username, $password, $first_name = "", $last_name = "", $phone = "", $city = "", $zip_code = "", $street = "", $building_number = "", $type = "0", $status = "0", $discount = 0, $newsletter = 0) {
        $email = strip_tags($username);
        $password = md5($password);
        $code = sha1($password . $username);
        $this->db->insert($this->_table_name, array(
            "email" => $email, 
            "password" => $password, 
            "name" => $email,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "phone" => $phone,
            'city' => $city,
            'zip_code' => $zip_code,
            'street' => $street,
            'building_number' => $building_number,
            "type" => $type,
            "code" => $code,
            "status" => $status,
            "discount" => $discount,
            "newsletter" => $newsletter
        ));
        if($this->db->insert_id()){
            return $code;
        } else {
            return "";
        }
    }
    
    public function activation($code){
        $this->db->where(array("code" => $code, "status" => "1"));
        $res = $this->db->update($this->_table_name, array("status" => 0));
        return $res;
    }
    
    public function remind_pass($email){
        $r = $this->db->get_where($this->_table_name, array("email" =>$email));
        if($r->num_rows()){
            //zmieniam hasło i zwracam nowe
            $new_pass = substr(md5(sha1(date('U'))), 2, rand(12, 18));
            $this->db->where("email",$email)->update($this->_table_name, array("password" => md5($new_pass)));
            return $new_pass;
        } else {
            return FALSE;
        }
    }
    
    public function update_user($id,$username, $password, $first_name = "", $last_name = "", $phone = "",  $city = "", $zip_code = "", $street = "", $building_number = "", $type = "1", $status=null, $discount = 0, $newsletter = 0) {
        $email = strip_tags($username);
        if(!empty($password)){
            $password = md5($password);
            $this->db->where("id", $id)->update($this->_table_name, array(
                "email" => $email, 
                "password" => $password, 
                "name" => $email,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "phone" => $phone,
                'city' => $city,
                'zip_code' => $zip_code,
                'street' => $street,
                'building_number' => $building_number,
                "type" => $type,
                'discount' => $discount,
                'status' => $status,
                'newsletter' => $newsletter
            ));
        } else {
            $password = md5($password);
            $this->db->where("id", $id)->update($this->_table_name, array(
                "email" => $email, 
                "name" => $email,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "phone" => $phone,
                'city' => $city,
                'zip_code' => $zip_code,
                'street' => $street,
                'building_number' => $building_number,
                "type" => $type,
                'status' => $status,
                'discount' => $discount,
                'newsletter' => $newsletter
            ));
        }
        
        
    }

    public function delete_user($user_id) {
        $this->db->delete($this->_table_name, array("id" => $user_id));
    }

    public function logout() {
        $this->session->unset_userdata('login');
        $this->session->sess_destroy();
    }

    public function get_rebate_groups(){
        $q = $this->db->get($this->_table_rebate_groups);
        return $q->result();
    }
    public function add_rebate_group($name, $value){
        $this->db->insert($this->_table_rebate_groups, array(
            'name' => $name,
            'discount' => $value
        ));
        return $this->db->insert_id();
    }
    
    public function update_rebate_group($id, $name, $value){
        $this->db->where('id',$id)->update($this->_table_rebate_groups, array(
            'name' => $name,
            'discount' => $value
        ));
        return true;
    }
    
}
