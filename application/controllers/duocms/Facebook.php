<?php

class Facebook extends Backend_Controller {
    
    public $facebook;


    public function __construct() {
        parent::__construct();
        $this->load->model('FacebookModel');
        $this->load->model('OfferCategoryModel');
        $this->load->model('ProductModel');
        $this->load->model('NewsModel');
        $this->load->model('NewsTranslationModel');
        $this->facebook = new FacebookModel();
        $this->load->vars(['activePage' => 'allegro']);
    }
    
    public function index() {
        

        try {
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $response = $fb->get('/me', '{access-token}');
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $me = $response->getGraphUser();
        echo 'Logged in as ' . $me->getName();
    }
    
    public function login(){
        $params = array('req_perms' => 'manage_pages,publish_pages');
        $helper = $this->facebook->fb->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl(site_url('duocms/facebook/login_callback'), $params);

        header('Location: '. $loginUrl);
        exit;
    }
    
    public function login_callback(){
        $helper = $this->facebook->fb->getRedirectLoginHelper();
        try {
          $accessToken = $helper->getAccessToken();
          // OAuth 2.0 client handler
            $oAuth2Client = $this->facebook->fb->getOAuth2Client();
            // Exchanges a short-lived access token for a long-lived one
            $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            $this->session->set_userdata('facebook_token', $accessToken->getValue());
            $post_id = $this->session->userdata['news_id'];
            setAlert('success','Zweryfikowano Facebook');
            redirect('duocms/news');

        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }
    }
    
    public function add_post($news_id = 0){
        $token = $this->session->userdata['facebook_token'];
        
        if(empty($token)){
            $this->login();
            $this->session->set_userdate('news_id',$news_id);
            die();
        }
        $oAuth2Client = $this->facebook->fb->getOAuth2Client();
        $accessToken = $oAuth2Client->getLongLivedAccessToken($token);
        if($accessToken->isExpired()){
            $this->login();
            $this->session->set_userdate('news_id',$news_id);
            die();
        }    
        
        $pageTokenData = $this->facebook->fb->get("/me/accounts", $token)->getDecodedBody();
        $pageToken = null;

        //check get the token for the desired app
        foreach($pageTokenData['data'] as $account){
          if($account['name'] == $this->facebook->page_name){
            $pageToken = $account['access_token'];
          }
        }
        $token = $pageToken;
        $news = new NewsModel();
        $news->getById($news_id);
        $translation = $news->getTranslation('pl');

         $params = array(
          "access_token" => $token, // see: https://developers.facebook.com/docs/facebook-login/access-tokens/
          "message" => $translation->excerpt,
          "link" => $news->getPermalink()
        );
         if(empty($news->facebook)){
        $res = $this->facebook->fb->post($this->facebook->page_id.'/feed/', $params,	$token);
         }
         else {
             setAlert('error','Post jest już na Facebook');
            redirect('duocms/news/edit/' .$news_id);
            die();
         }
        if($res->getHttpStatusCode() == 200){
            $news->facebook = $res->getDecodedBody()['id'];
            $news->edit();
            setAlert('success','Dodano post na Facebook');
            redirect('duocms/news/edit/' .$news_id);
        }

    }

    public function download_news_from_fb(){
        $token = $this->session->userdata['facebook_token'];
        
        try {
          // Returns a `Facebook\FacebookResponse` object
          $response = $this->facebook->fb->get(
            '/'. $this->facebook->page_id .'/posts?fields=message,full_picture,created_time,link,type',
            $token
          );
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }
        $graphEdge = $response->getGraphEdge();
        
        foreach($graphEdge as $graphNode){
            if(!empty($graphNode->getField('message', ''))){
                if(!$this->NewsModel->checkFbId($graphNode->getField('id', ''))){
                $news = new NewsModel();
                $news->started_at = $graphNode->getField('created_time', '')->format('Y-m-d H:i:s'); 
                $news->facebook = $graphNode->getField('id', '');
                $news->category_id = null;
                $news->add();
                $msg = $graphNode->getField('message', '');
                $translation = new NewsTranslationModel();
                $translation->news_id = $news->id;
                $translation->lang = 'pl';
                $translation->title = $msg;
                if (preg_match('/^.{1,75}\b/s', $msg, $match))
                {
                    $translation->title=$match[0];
                }
                $translation->excerpt = $msg;
                if (preg_match('/^.{1,250}\b/s', $msg, $match))
                {
                    $translation->excerpte=$match[0];
                }
                $translation->body = $msg;
                $translation->image = $graphNode->getField('full_picture', '');
                $translation->insert();

                }
            }
        }
    }
}