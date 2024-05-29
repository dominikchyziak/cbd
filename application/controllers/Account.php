<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Account extends Frontend_Controller {

    public $newsletter;
    
    public function __construct() {
        parent::__construct();
        $this->load->helper("form");
        $this->load->model('User_model');
        $this->load->model('NewsletterModel');
        $this->newsletter = new NewsletterModel();
        $this->set_desc((new CustomElementModel('10'))->getField('konto description'));
        $this->set_title((new CustomElementModel('10'))->getField('konto'));
        
        $home = site_url('/');
        $this->breadcrumbs[] = "<a href=$home>".(new CustomElementModel('10'))->getField('Strona glowna')."</a>";
        //zalogowany do panelu a niezalogowany do rejestracji
    }

    public function index() {
        if(empty(User_Model::loggedin()->id)){
            redirect(site_url("account/login"));
        }
        //$this->breadcrumbs[] = (new CustomElementModel('10'))->getField('konto');
        $this->layout('Account/index', array());
    }

    public function register() {
        $home = site_url('account/register');
        //$this->breadcrumbs[] = "<a href=$home>". (new CustomElementModel('10'))->getField('Rejestracja') . "</a>";

        if(!empty(User_Model::loggedin()->id)){
            redirect(site_url("account"));
        }
        $this->lang->load('contact_form');
        $error_message = '';
        if ($this->input->post('send')) {
            try {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('email',  (new CustomElementModel('11'))->getField('Email'), 'required|valid_email|is_unique[duo_users.email]');
                $this->form_validation->set_rules('password', (new CustomElementModel('11'))->getField('haslo'), 'required');
                $this->form_validation->set_rules('password_repeat', (new CustomElementModel('11'))->getField('Powtorz haslo'), 'required|matches[password]');
                $this->form_validation->set_rules('g-recaptcha-response', 'reCaptcha', 'callback__validate_kapcza');
                $this->form_validation->set_rules('terms', 'Akceptacja regulaminu', 'required');
                $this->form_validation->set_message('_validate_kapcza', lang('cotact_form_error_captcha'));

                if ($this->form_validation->run()) {
                    // Dodawanie usera i wysyłanie linka aktywacyjnego
                    $usermodel = new User_Model();
                    $email_user = $this->input->post('email');
                    $code = $usermodel->add_user(
                            $email_user, 
                            $this->input->post("password"), 
                            $this->input->post("first_name"), 
                            $this->input->post("last_name"), 
                            $this->input->post("phone"), 
                            "","","","", 1, 1,0,
                            !empty($this->input->post('newsletter')) ? '1' : '0');
                    //dodawanie do newslettera jeśli zaakceptował
                    if(!empty($this->input->post('newsletter'))){
                        $this->newsletter->subscribe($email_user);
                    }
                    //wysyłka maila z kodem 
                    $this->config->load('email');
                    $smtp_email = $this->config->item('smtp_user');
                    $from_mail = !empty($smtp_email) ?  $smtp_email :  get_option('email_from');
                    $this->email->from($from_mail, get_option('email_from_name'));
                    $this->email->to($this->input->post("email"));
                    $this->email->subject((new CustomElementModel('15'))->getField('Rejestracja mail tytul'));

                    $data = [
                        'code' => $code
                    ];
                    $message = $this->load->view('templates/register', $data, true);
                    $this->email->message($message);
                    $res = $this->email->send();
                    
                    $this->layout('Account/register_success', array());
                } else {
                    throw new Exception(validation_errors());
                    $this->layout('Account/register', array('error_message' => $error_message));
                }
            } catch (Exception $e) {
                $error_message = $e->getMessage();
                $this->layout('Account/register', array('error_message' => $error_message));
            }
        } else {
            $this->layout('Account/register', array('error_message' => $error_message));
        }
    }
    
    public function activation($code){
        //$this->breadcrumbs[] = (new CustomElementModel('10'))->getField('aktywacja');
    
        //aktywacja konta po kliknięciu linka z maila
        $code = strip_tags($code);
        if(!empty(User_Model::loggedin()->id)){
            redirect(site_url("account"));
        }
        $model = new User_Model();
        $type = $model->activation($code);
        if($type){
            $error_message = (new CustomElementModel('15'))->getField('aktywacja sukces');
        } else {
            $error_message =  (new CustomElementModel('15'))->getField('aktywacja blad');
        }
        
        $this->layout('Account/activation', array('message' => $error_message, 'type' => $type));
    }

    public function login() {
        //$this->breadcrumbs[] =  (new CustomElementModel('10'))->getField('logowanie');
        if(!empty(User_Model::loggedin()->id)){
            redirect(site_url("account"));
        }
        $error_message = '';
        if ($this->input->post('send')) {
            try {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('email', (new CustomElementModel('11'))->getField('Email'), 'required|valid_email');
                $this->form_validation->set_rules('password', (new CustomElementModel('11'))->getField('haslo'), 'required');
                if ($this->form_validation->run()) {
                    $usermodel = new User_Model();
                    $res = $usermodel->login($this->input->post("email"), $this->input->post("password"));
                    if($res){
                        redirect(site_url("account"));
                    } else {
                        throw new Exception((new CustomElementModel('15'))->getField('logowanie blad'));
                    }
                } else {
                    throw new Exception(validation_errors());
                }
            } catch (Exception $e) {
                $error_message = $e->getMessage();
            }
        }
        
        $this->layout('Account/login', array("error_message" => $error_message));
    }
    
    public function logout(){
        $this->load->model('User_Model');
		$this->User_Model->logout();
		redirect(site_url("account/login"));
    }

    public function remind_pass() {
        //$this->breadcrumbs[] = (new CustomElementModel('10'))->getField('przypomnienie hasla');
        if(!empty(User_Model::loggedin()->id)){
            redirect(site_url("account"));
        }
        $error_message = "";
        $success = "";
        if ($this->input->post('send')) {
            
            try {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('email', (new CustomElementModel('11'))->getField('Email'), 'required|valid_email');
                if ($this->form_validation->run()) {
                    $usermodel = new User_Model();
                    $res = $usermodel->remind_pass($this->input->post("email"));
                    if(!empty($res)){
                        //Wysyłam email z nowym hasłem
                    $this->config->load('email');
                    $smtp_email = $this->config->item('smtp_user');
                    $from_mail = !empty($smtp_email) ?  $smtp_email :  get_option('email_from');
                    $this->email->from($from_mail, get_option('email_from_name'));
                    $this->email->to($this->input->post("email"));
                    $this->email->subject((new CustomElementModel('15'))->getField('nowe haslo temat emaila'));

                    $data = [
                        'password' => $res
                    ];
                    $message = $this->load->view('templates/remind_pass', $data, true);
                    $this->email->message($message);
                    $res = $this->email->send();
                        $success = (new CustomElementModel('15'))->getField('nowe haslo sukces');
                    } else {
                        throw new Exception((new CustomElementModel('15'))->getField('nowe haslo blad'));
                    }
                } else {
                    throw new Exception(validation_errors());
                }
            } catch (Exception $e) {
                $error_message = $e->getMessage();
            }
        }
        
        $this->layout('Account/remind_pass', array('error_message' => $error_message, "success" => $success));
    }
    
    public function edit_account(){
        $user = $this->session->login;
        $id = $user['user']['id'];
        $error_message = "";
        if(!empty($_POST)){
   
            $this->form_validation->set_rules('email','Email','required');
            if(!empty($_POST['password'])){
                $this->form_validation->set_rules('password_repeat','Powtórz hasło','required');
                $this->form_validation->set_rules('password', 'Hasło', 'required|matches[password_repeat]');
            }
            
            if ($this->form_validation->run()) {
                    $this->User_model->update_user(
                            $id,
                            $user['user']['email'],
                            $this->input->post('password'),
                            $this->input->post('first_name'),
                            $this->input->post('last_name'),
                            $this->input->post('phone'),
                            $this->input->post('city'),
                            $this->input->post('zip_code'),
                            $this->input->post('street'),
                            $this->input->post('building_number'),
                            $user['user']['type'],
                            $user['user']['status'],
                            $user['user']['discount']
                    );
                    foreach($this->input->post() as $key=>$val){
                        $user['user'][$key] = $val;
                    }
                    if(!empty($this->input->post('newsletter_accept'))){
                        $this->load->model('NewsletterModel');
                        $this->NewsletterModel->subscribeWithCheck($user['user']['email']);
                    }
                    
                    $this->session->set_userdata('login',$user);
                    setAlert('success','Ok.');
                    redirect(site_url('account/edit_account'));
                
            } else {
                $error_message = validation_errors();
            }
            $user['user'] = $this->input->post();
        }
        $user['user']['error_message'] = $error_message;
        $this->breadcrumbs[] = '<a href="' . site_url('account') . '">' . (new CustomElementModel('10'))->getField('konto') . '</a>';
        
        $this->load->model('NewsletterModel');
        $this->db->where('email', $user['user']['email']);
        $user['user']['newsletter_info'] = $this->NewsletterModel->get_addresses();
        
        $this->layout('Account/edit_account', $user['user']);
    }

    public function _validate_kapcza($str) {
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = get_option('recaptcha_secret_key');
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = $google_url . "?secret=" . $secret . "&response=" . $str . "&remoteip=" . $ip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  //important for localhost!!!
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);
        //reCaptcha success check
        if ($res['success']) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function my_orders(){ 
         if(empty(User_Model::loggedin()->id)){
            redirect(site_url("moje-konto"));
        }
        $this->breadcrumbs[] = "<a href='" . site_url('account') . "'>" . (new CustomElementModel('10'))->getField('konto') . "</a>";
        //$this->breadcrumbs[] = (new CustomElementModel('10'))->getField('moje zamowienia'); 
        $this->load->model('ProductModel');
        $this->load->model('OrderModel');
        $order_model = new OrderModel();
        $orders = $order_model->get_orders_user($this->session->login["user"]["email"]);
        $this->layout('Account/my_orders', array("orders" => $orders));
    }
    
        public function transactions_history(){ 
         if(empty(User_Model::loggedin()->id)){
            redirect(site_url("moje-konto"));
        }
        $this->breadcrumbs[] = "<a href='" . site_url('account') . "'>" . (new CustomElementModel('10'))->getField('konto') . "</a>";
        $this->breadcrumbs[] =  (new CustomElementModel('10'))->getField('Historia transakcji'); 
        $this->load->model('ProductModel');
        $this->load->model('OrderModel');
        $order_model = new OrderModel();
        $orders = $order_model->get_orders_user($this->session->login["user"]["email"]);
        foreach($orders as $order){
            $story[$order->id] = $order_model->get_story($order->id);
        }
        $this->layout('Account/transations-history', array("orders" => $orders,"story"=>$story));
    }
    
}
