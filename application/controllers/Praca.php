<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Praca extends Frontend_Controller {

    public $recruitment;

    public function __construct() {
        parent::__construct();
        $this->load->model('RecruitmentModel');
        $this->recruitment = new RecruitmentModel();
        $home = site_url('/');
        $this->breadcrumbs[] = "<a href=$home>" . (new CustomElementModel('10'))->getField('Strona glowna') . "</a>";
    }

    public function index() {
        $this->lang->load('contact_form');
        $this->breadcrumbs[] = "praca";
        $error_message = '';

        if ($this->input->post('send')) {
            try {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('name', 'Imię i nazwisko', 'required');
                $this->form_validation->set_rules('position', 'Stanowisko', 'required');
                $this->form_validation->set_rules('email', 'Adres e-mail', 'required|valid_email');
                $this->form_validation->set_rules('message', 'Twoje zapytanie', 'required');
                $this->form_validation->set_rules('g-recaptcha-response', 'reCaptcha', 'callback__validate_kapcza');

                $this->form_validation->set_message('_validate_kapcza', lang('cotact_form_error_captcha'));

                if ($this->form_validation->run()) {
                    $this->load->library('email');
                    $this->config->load('email');
                    $from_mail = !empty($this->config->item('smtp_user')) ?  $this->config->item('smtp_user'):  get_option('email_from');
                    $this->email->from($from_mail, get_option('email_from_name'));
                    $this->email->to(get_option('email_from'));
                    $this->email->reply_to($this->input->post('email'));
                    $this->email->subject('Pracownik.');

                    $data = [
                        'name' => $this->input->post('name', true),
                        'phone' => $this->input->post('phone', true),
                        'email' => $this->input->post('email', true),
                        'message' => $this->input->post('message', true)
                    ];

                    $message = $this->load->view('templates/formularz-kontaktowy', $data, true);
                    $this->email->message($message);

                    $config['upload_path'] = FCPATH . 'uploads/cv/';
                    $config['allowed_types'] = "*";
                    $config['max_size'] = '1000';
                    $file_path = '';
                    $file_path2 = '';

                    $allowed = array('gif', 'png', 'jpg', 'pdf', 'doc', 'docx', 'odt');
                    $filename = $_FILES['userfile']['name'];
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    if (!in_array($ext, $allowed)) {
                        throw new Exception('Nieprawidłowy plik CV');
                    }
                    $filename2 = $_FILES['lm']['name'];
                    $ext2 = pathinfo($filename2, PATHINFO_EXTENSION);
                    if (!in_array($ext2, $allowed)) {
                        throw new Exception('Nieprawidłowy plik LM');
                    }


                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload('userfile')) {
                        throw new Exception($this->upload->display_errors());
                    } else {
                        $data = array('upload_data' => $this->upload->data());
                        $this->email->attach($data['upload_data']['full_path']);
                        $file_path = $data['upload_data']['full_path'];
                    }
                    if (!$this->upload->do_upload('lm')) {
                        throw new Exception($this->upload->display_errors());
                    } else {
                        $data = array('upload_data' => $this->upload->data());
                        $this->email->attach($data['upload_data']['full_path']);
                        $file_path2 = $data['upload_data']['full_path'];
                    }

                    $res = $this->email->send();
                    $files_ar = array();
                    if (!empty($file_path)) {
                        // unlink($file_path);
                        $files_ar[] = $file_path;
                    }
                    if (!empty($file_path2)) {
                        // unlink($file_path2);
                        $files_ar[] = $file_path2;
                    }
                    

                    if (!$res) {
                        throw new Exception(lang('contact_form_error_server_not_responding'));
                    }

                    $this->recruitment->add_candidate(array(
                        'candidate_name' => $this->input->post('name', true),
                        'phone' => $this->input->post('phone', true),
                        'email' => $this->input->post('email', true),
                        'position_id' => $this->input->post('position'),
                        'message' => $this->input->post('message', true),
                        'files' => json_encode($files_ar)
                    ));
                    throw new Exception('Wysłano aplikację');
                } else {
                    throw new Exception(validation_errors());
                }
            } catch (Exception $e) {
                $error_message = $e->getMessage();
            }
        }

        $this->load->helper('form');

        $this->load->model('PageModel');
        $page = new PageModel(27);

        // Set defaults.
        $this->set_desc($page->getTranslation(LANG)->body);
        $this->set_title($page->getTranslation(LANG)->title);

        $positions = $this->recruitment->get_positions();
        $this->layout('Praca/index', array(
            'error_message' => $error_message,
            'page' => $page,
            'positions' => $positions
        ));
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

}
