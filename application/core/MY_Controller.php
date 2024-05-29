<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

        public $canon_link; // link kanoniczny
	public $meta_title;   // tytuł strony
	public $meta_desc;    // opis strony
        public $keywords;   //słowa kluczowe
	public $scripts;      // array z plikami JS
	public $styles;       // array z plikami CSS
	public $view_file;    // nasze stare $required_file
	public $breadcrumbs;  // ponieważ każda podstrona będzie miała okruchy
	public $msg_error;    // komunikat sesji z błędem
	public $msg_okay;     // komunikat sesji z komunikatem pozytywnym
        public $order_statuses = array(
            '-1' => 'Anulowane',
            '0' => 'Oczekuje',
            '2' => 'Nieopłacony',
            '5' => 'Opłacone',
            '10' => 'Przyjęte do realizacji',
            '15' => 'Zrealizowane',
            '20' => 'Zakończone'
        );

	protected $layout;  // layout główny strony

	public function __construct()
	{
		parent::__construct();

		// Set defaults
                $this->canon_link   = NULL;
		$this->meta_title   = NULL;
		$this->meta_desc    = NULL;
		$this->scripts      = array();
		$this->styles       = array();
		$this->layout       = NULL;
		$this->view_file    = NULL;
		$this->breadcrumbs  = array();
		$this->msg_okay     = $this->getOkay();
		$this->msg_error    = $this->getError();

		define('LANG', $this->config->item('language_abbr'));
	}

	protected function set_title($title)
	{
		$this->meta_title = $title.' - '.$this->meta_title;
	}
        
	protected function set_whole_title($title)
	{
		$this->meta_title = $title;
	}
        
        protected function set_canon_link($link){
            
            $this->canon_link = $link;
        }
        
	protected function set_desc($desc)
	{
		$this->meta_desc = trim(preg_replace('/\s+/', ' ', shorten(htmlentities(strip_tags($desc)), 160)));
	}

	protected function add_js($js)
	{
		if (is_array($js))
			$this->scripts = array_merge($this->scripts, $js);
		else
			$this->scripts[] = $js;
	}

	protected function add_css($css)
	{
		if (is_array($css))
			$this->styles = array_merge($this->styles, $css);
		else
			$this->styles[] = $css;
	}

	protected function set_layout($layout)
	{
		$this->layout = 'layouts/'.$layout;
	}

	protected function layout($view_file, array $data = NULL)
	{
//                $this->load->model('CurrencyModel');
//                $currencies = $this->CurrencyModel->get_acitve_currencies();
		$this->view_file = $view_file;
		$this->load->vars($data);
//                $this->load->vars(array('currenciesForHeader' => $currencies));
		$this->load->view($this->layout);
	}

	public function setError($msg)
	{
		$this->session->set_userdata('msg_error', $msg);
	}

	public function getError()
	{
		$msg = $this->session->userdata('msg_error');
		$this->session->unset_userdata('msg_error');
		return $msg;
	}
	public function setOkay($msg)
	{
		$this->session->set_userdata('msg_okay', $msg);
	}

	public function getOkay()
	{
		$msg = $this->session->userdata('msg_okay');
		$this->session->unset_userdata('msg_okay');
		return $msg;
	}
        
        public function _validate_kapcza($str)
	{
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

require_once APPPATH.'core/Frontend_Controller.php';
require_once APPPATH.'core/Backend_Controller.php';