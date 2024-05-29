<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Newsletter extends Frontend_Controller {

    private $newsletter;

    public function __construct() {
        parent::__construct();
        $this->load->model('NewsletterModel');
        $this->newsletter = new NewsletterModel();
        $home = site_url('/');
        $this->breadcrumbs[] = "<a href=$home>" . (new CustomElementModel('9'))->getField('Strona główna') . "</a>";
    }

    //funkcja ajax zapisu na newsletter
    public function subscribe(){
        if(!empty($_POST)){
            $this->form_validation->set_rules('email','Email','required|valid_email|is_unique[duo_newsletter_emails.email]');
            $this->form_validation->set_message('is_unique', (LANG === 'pl') ? '%s został poprzednio zapisany na newsletter.' : '%s has previously subscribed to the newsletter.');
            if($this->form_validation->run()){
                $email = $this->input->post('email');
                $this->newsletter->subscribe($email);
                $success_message = (new CustomElementModel('17'))->getField('Newsletter po dodaniu')->value;
                ajax_res(array('1',$success_message));
                
            } else {
                $errors = validation_errors();
                ajax_res(array('0',$errors));
            }
        } else {
            ajax_res(array('0',"Błąd!"));
        }
    }
  public function unsubscribe(){
        $res = null;
        $msg = '';
        if (!empty($_POST)) {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            if ($this->form_validation->run()) {
                $mail = $this->input->post('email');
                $res = $this->newsletter->unsubscribe2($mail);
                if ($res) {
                    $msg = 'Email ' . $mail . ' został usunięty z naszej listy.';
                } else {
                    $msg = 'Błąd w usuwaniu ' . $mail . ', prawdopodobnie nie mamy takiego adresu w bazie.';
                }
            } else {
                $msg = validation_errors();
            }
        }

        $this->layout('newsletter/unsub.php', [ 'msg' => $msg ]);
    }
}
