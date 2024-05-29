<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Frontend_Controller
{
    public $is_home = TRUE;
    
	public function index()
	{
		$this->load->model('WizerunekModel');
		$wizerunki = $this->WizerunekModel->findAll();
                $this->load->model('NewsModel');
                $news = $this->NewsModel->getFrontendList(LANG, 3);
                //$this->load->model('GalleryModel');
                //$gallery = $this->GalleryModel->findAll2();
                //$this->load->model('OfferCategoryModel');
                //$this->db->limit(6);
                //$categories = $this->OfferCategoryModel->findAllByParent(null);
                $this->load->model('ProductModel');

                $products = $this->ProductModel->findAll();
                //$promo_products2 = $this->ProductModel->findAllPromoProducts2(2);
                //$bestsellers2 =  $this->ProductModel->findAllBestsellerProducts2(3);
                //$new_products2 =  $this->ProductModel->findAllNewProducts(1);
                 
                $session_filters = array(
                    'category_id' =>  array(),
                    'attributes' => array(),
                    'str' =>  ''
                );
                $this->session->set_userdata('filters',$session_filters);
                        

		$this->layout('Home/index', array(
                    'wizerunki' => $wizerunki,
                    'is_home' => $this->is_home,
                  //  'categories' => $categories,
                    'news' => $news,
                    'products' => $products,
                  //  'new_products' => $new_products2,
                  //  'promo_products' => $promo_products2,
                  //  'bestsellers' => $bestsellers2,
                  //  'gallery' => $gallery,
//                    'points' => $points
		));
	}
        
        public function do_upload()
        {
                $config['upload_path']          = './tmp_files/';
                $config['allowed_types']        = 'jpg|png|gif|jpeg';
                $config['max_size']             = 35000;
                $config['max_width']            = 30024;
                $config['max_height']           = 17068;

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload('file_upload'))
                {
                    return array('type'=> 'error', 'error' => $this->upload->display_errors());
                }
                else
                {
                    return array('type' => 'success','upload_data' => $this->upload->data());
                }
        }
        
        public function error404() 
        {
           $this->layout('templates/404page');
        }

}
