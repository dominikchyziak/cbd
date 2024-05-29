<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class NewsletterModel extends MY_Model {

    protected $_table_emails = "duo_newsletter_emails";
    protected $_table_mailings = "duo_newsletter_mailings";
    protected $_table_emails_to_send = 'duo_newsletter_emails_to_send';

    public function __construct() {
        parent::__construct();
    }
    
    public function subscribe($email){
        $data = date('U');
        $key = md5($email.$data);
        $this->db->insert($this->_table_emails, array(
            'key' => $key,
            'email' => $email
        ));
//        $this->load->model('CodesModel');
//        $code = $this->CodesModel->newsletter_autogen_code($email);
//        $subj = (new CustomElementModel('17'))->getField('mail z kodem temat');
//        $content = (new CustomElementModel('17'))->getField('mail z kodem tresc') . $code;
//        $this->send_discount_code($email, $subj, $content);
        return TRUE;
    }
    
    public function subscribeWithCheck($email) {
        $this->db->where('email', $email);
        $q = $this->db->get($this->_table_emails);
        if ($q->num_rows() == 0) {
            $data = date('U');
            $key = md5($email . $data);
            $this->db->insert($this->_table_emails, array(
                'key' => $key,
                'email' => $email
            ));
            return true;
        } else {
            return false;
        }
    }

    public function getUnsubLink($email){
        $q = $this->db->get_where($this->_table_emails, array('email' => $email));
        $r = $q->row();
        return site_url('newsletter/unsubscribe/'.$email.'/'.$r->key);
    }
    
    public function unSubscribe($email, $key){
        $q = $this->db->get_where($this->_table_emails, array('key' => $key, 'email' => $email));
        if($q->num_rows() > 0){
            $r = $q->row();
            $this->db->where('id',$r->id)->update($this->_table_emails, array('blocked' => 1));
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function unsubscribe2($email){
        if(!empty($email)){
             $this->db->where('email', $email);
             $res = $this->db->delete($this->_table_emails);
             return $res;
        } else {
            return false;
        }
    }
    public function get_addresses(){
        $q = $this->db->get($this->_table_emails);
        return $q->result();
    }
    
    public function get_email_by_id($id){
        $q = $this->db->get_where($this->_table_emails, array('id' => $id));
        return $q->row();
    }
    
    public function changeEmailStatus($email){
        $q = $this->db->get_where($this->_table_emails, array('email' => $email));
        if($q->num_rows() > 0){
            $r = $q->row();
            $this->db->where('id',$r->id)->update($this->_table_emails, array('blocked' => !$r->blocked));
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function add_mailing($subject, $content){
        $res = $this->db->insert($this->_table_mailings, array('subject' => $subject, 'content' => $content));
        if($res){
            return TRUE;
        } else {
            return FALSE;
        }
        
    }
    
    public function edit_mailing($id, $subject, $content){
        $res = $this->db->where('id',$id)->update($this->_table_mailings, array('subject' => $subject, 'content' => $content));
        if($res){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function get_mailings(){
        $r = $this->db->get($this->_table_mailings);
        return $r->result();
    }
    public function get_mailing($id){
        $this->db->where('id',$id);
        $q = $this->db->get($this->_table_mailings);
        return $q->row();
    }
    
    public function delete_mailing($mailing_id){
        return $this->db->delete($this->_table_mailings, array('id' => $mailing_id));
    }


    public function get_emails($blocked = null){
        if($blocked !== null){
            $this->db->where('blocked',$blocked);
        }
        $q = $this->db->get($this->_table_emails);
        return $q->result();
    }
    
    public function add_to_send($mailing_id){
        $emails = $this->get_emails(0);
        if(!empty($emails)){
            foreach($emails as $email){
                $this->db->insert($this->_table_emails_to_send, array('email' => $email->email, 'mailing_id' => $mailing_id));
            }
            $this->db->where('id',$mailing_id)->update($this->_table_mailings, array('to_send' => count($emails)));
        }
        return true;
    }
    
    public function send_mailing($email_address, $mailing_obj){
        $args = array(
            'protocol' => get_option('email_protocol'),
            'smtp_host' => get_option('email_smtp_host'),
            'smtp_port' => get_option('email_smtp_port'),
            'smtp_user' => get_option('email_smtp_user'),
            'smtp_pass' => get_option('email_smtp_pass'),
            'name' => get_option('meta_title'),
            'email_to' => $email_address,
            'subject' => $mailing_obj->subject,
            'content' => $mailing_obj->content
        );
        return $this->send_mail($args);
    }
    
    public function send_discount_code($email, $subj, $content){
        $args = array(
            'protocol' => get_option('email_protocol'),
            'smtp_host' => get_option('email_smtp_host'),
            'smtp_port' => get_option('email_smtp_port'),
            'smtp_user' => get_option('email_smtp_user'),
            'smtp_pass' => get_option('email_smtp_pass'),
            'name' => get_option('meta_title'),
            'email_to' => $email,
            'subject' => $subj,
            'content' => $content
        );
        return $this->send_mail($args);
    }
    
    public function send_emails($limit){
        $q = $this->db->limit($limit)->order_by('created_at','ASC')->get($this->_table_emails_to_send);
        if($q->num_rows() > 0){
            $i = 0;
            foreach($q->result() as $r){
                $mailing_obj = $this->get_mailing($r->mailing_id);
                $res = $this->send_mailing($r->email, $mailing_obj);
                if($res == null){
                    $this->db->where('id',$r->mailing_id)->update($this->_table_mailings, array('sended' => ($mailing_obj->sended + 1), 'to_send' => ($mailing_obj->to_send - 1) ));
                    $this->db->delete($this->_table_emails_to_send, array('mailing_id' => $r->mailing_id, 'email' => $r->email));
                    $i++;
                }
            }
            return $i;
        }
        return 0;
    }
    
    private function send_mail($args){
        if(!empty($args['protocol'])){
            $config['protocol'] = $args['protocol'];
        }
        if(!empty($args['smtp_host'])){
            $config['smtp_host'] = $args['smtp_host'];
        }
        if(!empty($args['smtp_port'])){
            $config['smtp_port'] = $args['smtp_port'];
        }
        if(!empty($args['smtp_user'])){
            $config['smtp_user'] = $args['smtp_user'];
        }
        if(!empty($args['smtp_pass'])){
            $config['smtp_pass'] = $args['smtp_pass'];
        }
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n";
        
        
        $this->email->clear();
        $this->email->initialize($config);
        
        $this->email->from($config['smtp_user'], $args['name']);
        $this->email->to($args['email_to']);

        $this->email->subject($args['subject']);
        $this->email->message($args['content']);
        

        $res = $this->email->send();
        if(!$res){
            return $this->email->print_debugger();
        } else {
            return null;
        }
    }
}
