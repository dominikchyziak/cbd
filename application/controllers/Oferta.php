<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class oferta extends Frontend_Controller
{
    public  $attr_obj;
    public $product_obj;
    public $productCategoryObj;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ProductModel');
        $this->load->model('ProductCategoryModel');
        $this->load->model('OfferCategoryModel');
        $this->load->model('ProductTranslationModel');
        $this->load->model("ProductAttributesModel");
        // Set defaults
        //$this->set_title('Oferta');

        $this->load->vars(['activePage' => 'oferta']);
        $home = site_url('/');
        $this->breadcrumbs[] = "<a href=$home>" . (new CustomElementModel('10'))->getField('Strona glowna') . "</a>";
        
        $this->attr_obj = new ProductAttributesModel();
        $this->product_obj = new ProductModel();
        $this->productCategoryObj = new OfferCategoryModel();
    }
    
    public function products()
    {
        $this->set_title((new CustomElementModel('10'))->getField('produkty meta title'));
        $this->set_desc((new CustomElementModel('10'))->getField('produkty meta desc'));
        
        $products = $this->ProductModel->findAll();
        
        $this->layout('Oferta/allProducts', [
            'products' => $products,
        ]);
    }

    public function index()
    {
        $oferta = site_url('oferta');
        //$this->breadcrumbs[] = "<a href=$oferta>".(new CustomElementModel('10'))->getField('Oferta')."</a>";
//        $all_products = $this->ProductModel->findAll();
        $products = array();
//        foreach($all_products as $p){
//            $product = $this->ProductTranslationModel->findByProductAndLang($p, LANG);
//            $photos = $p->findAllPhotos();
//            $products[] = array("product"=>$product, "photos"=>$photos);
//        }
     $this->db->where('parent_id is NULL');
                $categories = $this->OfferCategoryModel->findAll();
//                if(!empty($categories)){
//                    foreach($categories as $category){
//                        
//                            $category->children = $this->OfferCategoryModel->findAllByParent($category);
//
//                    }
//                }
//        $products_model = new ProductModel();
//                        $new_products = $products_model->findAllNewProducts(4);
//                $promo_products = $products_model->findAllPromoProducts(4);
//                $bestsellers = $products_model->findAllBestsellerProducts(4);
//                
//                               $bestsellers2 = array();
//                foreach($bestsellers as $bs){
//                    $best['data']= $bs;
//                    $best['product'] = $bs->getTranslation(LANG);
//                    $bestsellers2[] = $best;
//                }
//                 $new_products2 = array();
//                 
//                foreach($new_products as $np){
//                    $nop['data']= $np;
//                    $nop['product'] = $np->getTranslation(LANG);
//                    $new_products2[] = $nop;
//                }
//                $promo_products2 = array();
//                foreach($promo_products as $pp){
//                    $pop['data']= $pp;
//                    $pop['product'] = $pp->getTranslation(LANG);
//                    $promo_products2[] = $pop;
//                }
                
        $this->layout('Oferta/index', [
           // 'products' => $products,
            'categories' => $categories,
//            'new_products' => $new_products2,
//                    'promo_products' => $promo_products2,
//                    'bestsellers' => $bestsellers2
        ]);
    }
    
    /* oferty*/
    public function index2(){
        $oferta = site_url('/nowosci');
        $this->breadcrumbs[] = "<a href=$oferta>" . (new CustomElementModel('10'))->getField('Produkty') . "</a>";
        
        $this->load->model('ProductModel');
        $products_model = new ProductModel();
        
        
        $new_products = $products_model->findAllNewProducts();
        $promo_products = $products_model->findAllPromoProducts();
        foreach ($new_products as $np) {
            $nop['data'] = $np;
            $nop['product'] = $np->getTranslation(LANG);
            $new_products2[] = $nop;
        }
        $promo_products2 = array();
        foreach ($promo_products as $pp) {
            $pop['data'] = $pp;
            $pop['product'] = $pp->getTranslation(LANG);
            $promo_products2[] = $pop;
        }

        
        $this->layout('Oferta/index2', [
            'new_products' => $new_products2,
            'promo_products' => $promo_products2
        ]);
    }
  

    public function product($tag, $option_id = '', $attribute_id = '0'){     
        $this->load->model('ProductModel');
        $this->load->model('OfferCategoryModel');
        //pobieram konkretny produkt
        $product_id = $tag;
        $product = $this->ProductTranslationModel->findByProductAndLang(new ProductModel($product_id), LANG);
        $product_next = $this->ProductTranslationModel->findByProductAndLang(new ProductModel($product_id+1), LANG);
        $product_prev = $this->ProductTranslationModel->findByProductAndLang(new ProductModel($product_id-1), LANG);
        $product2 = new ProductModel($product_id);
        $photos = (new ProductModel($product_id))->findAllPhotos();
        $category = new OfferCategoryModel($product2->offer_category_id);
        $last_viewed_category = $this->session->userdata('last_viewed_category');
        if(in_array($last_viewed_category, $product2->categories)){
            $category = new OfferCategoryModel($last_viewed_category);
        }else {
            $category =  !empty($product2->categories) ? new OfferCategoryModel($product2->categories[0]) : null;
        }
        $oferta = site_url('produkty');
        $this->load->model('ProductCategoryModel');
//        $name_page = $this->ProductCategoryModel->getCategoryNameById($category->id);
//        $name_page = $name_page[0]->name;
//        $category_url = site_url('oferta/kategoria/' . getAlias($category->id, $name_page));
        //$category->generate_breadcrumbs($this->breadcrumbs, $category);
//        $product_brade = site_url('produkt/' . getAlias($product->product_id, $product->name));
        //$this->breadcrumbs[] = "<a href=$product_brade>" . $product->name . "</a>";
//        $this->set_canon_link($product2->getPermalink());
          $oferta = site_url('/produkty');
          $this->breadcrumbs[] = "<a href=$oferta>" . (new CustomElementModel('10'))->getField('produkty naglowek') . "</a>"; //important
//        
//        $parent_category = new OfferCategoryModel($product2->offer_category_id);
        
        
//
//        $this->breadcrumbs[] = "<a href='". $parent_category->getPermalink() ."'>" . $parent_category->getTranslation(LANG)->name . "</a>";
    
        $option = array();
        if(!empty($option_id)){
            $option = $product2->select_option($option_id);
        }

            $this->meta_title = $product->name . ' | ' .  (new CustomElementModel('10'))->getField('domyslne meta-title produktu');
            $this->meta_desc = $this->shorten(preg_replace('/\s+/', ' ', strip_tags($product->body)), 160);
        if(!empty($product->meta_title)){
            $this->set_whole_title($product->meta_title);
        }
        if(!empty($product->meta_description)){
            $this->set_desc($product->meta_description);
        }
        $options = array();
        if($product2->options){
            $options = $product2->get_options($product2->id);
        }
               $used_atributes = [];
        $uattr = $this->product_obj->attribute_get_list_for_product($product2->id, LANG);
            if(!empty($uattr)){
                foreach($uattr as $uat){
                    if(!in_array($uat->attribute_id, $used_atributes)){
                        $used_atributes[] = $uat->attribute_id;
                    }
                }
            }
            
        $groups = $this->attr_obj->get_groups();
        $groups_array = array();
        if(!empty($groups)){
            foreach ($groups as $group){
                $new_attr = [];
                $all_attr = $this->attr_obj->get_attributes_by_group($group->attributes_group_id);
                foreach($all_attr as $atr){
                    if(in_array($atr->id, $used_atributes)){
                        $new_attr[] = $atr;
                    }
                }
                if(!empty($new_attr)){
                $groups_array[] = array(
                    'group' => $group,
                    'attributes' => $new_attr
                );
                }
            }
        }
        
        
        
        if(!empty($this->input->post('product_id'))){
            
            $quantity = $this->input->post('quantity');
            
            $res = $this->product_obj->add_to_basket(
                    $this->input->post('product_id'), 
                    $quantity, 
                    $this->input->post('option'), 
                    $this->input->post('attributes'));
            if($res){
                $message = array(
                    '0' => "success",
                    '1' =>  (new CustomElementModel('16'))->getField('Dodano do koszyka')
                );
                setAlert('success', (new CustomElementModel('16'))->getField('Dodano do koszyka'));
            } else {
                $message = array(
                    '0' => "danger",
                    '1' => (new CustomElementModel('16'))->getField('nie dodano')
                );
                setAlert('success', (new CustomElementModel('16'))->getField('nie dodano'));
            }
        }
        
        //produkty w kategorii
        //$this->db->limit(4);
        //$category_products = (new ProductModel())->search([$product2->categories[0]], [], '', 0, 4, null, null, null);
        //$other_products = (new ProductModel())->search([$product2->categories[0]], [], '', 0, 4, null, null, null, null, 1,1);
//        $products = array();
//        $i = 0;
//        foreach($category_products as $p){
//            $productc = $this->ProductTranslationModel->findByProductAndLang($p, LANG);
//            $photosc = $p->findAllPhotos();
//            $products[] = array("product"=>$productc, "photos"=>$photosc, "data" => $p);
//            $i++;
//            if($i > 3){
//                break;
//            }
//        }
//        if(count($photos) > 1){
//            $photos = array_slice($photos, 1, count($photos) - 1);
//        }
//        if(count($photos) == 1) {
//            $photos = [];
//        }
        if(!empty($photos)){
            $this->load->model('GallerySetupModel');
        $gallery_widget['setup'] = $this->GallerySetupModel->get_setup();
        $gallery_widget['modules'] = $this->GallerySetupModel->get_modules_array();
        $gallery_widget['photos'] = $photos;
        } else {
            $gallery_widget = null;
        }
        
        
//        if (file_exists(FCPATH . 'user_files/images_360/' . $product2->id)) {
//            $photo_360 = array(
//                "exist" => 1,
//                "url" => FCPATH . 'user_files/images_360/' . $product2->id . '/'
//            );
//        } else {
//            $photo_360 = array(
//                "exist" => 0,
//                "url" => FCPATH . 'user_files/images_360/' . $product2->id . '/'
//            );
//        }
        
//        $this->load->model('MapPointModel');
//        $locations = (new MapPointModel())->getAllMapPoints();
        
//        $this->load->model('GalleryModel');
//        $gallery = $this->GalleryModel->findAllCategory2($product2->id);
 
            
        $this->layout('Oferta/product', [
//            'photo_360' => $photo_360,
//            'products' => $products,
            'product' => $product,
            'product_data' => $product2,
           // 'next' =>$product_next,
           // 'prev' => $product_prev,
            'photos' => $photos,
            'options' => $options,
            'option' => $option,
            'message' => !empty($message) ? array($message) : '',
            'price' => !empty($option['price_change']) ? $option['price_change'] : $product2->getPrice(1),
            'query' => '',
            'groups' => $groups_array,
            'attributes' => $product2->get_friendly_attributes(),
            'gallery_widget' => $gallery_widget,
            //'category_products' => $category_products,
            //'other_products' => $other_products,
            'is_product' => true,
        ]);
   
//            $this->layout('Oferta/product_with_calc', [
////            'photo_360' => $photo_360,
//            'products' => $products,
//            'product' => $product,
//            'product_data' => $product2,
//           // 'next' =>$product_next,
//           // 'prev' => $product_prev,
//            'photos' => $photos,
//            'options' => $options,
//            'option' => $option,
//            'message' => !empty($message) ? array($message) : '',
//            'price' => !empty($option['price_change']) ? $option['price_change'] : $product2->price,
//            'query' => '',
//            'groups' => $groups_array,
//            'attributes' => $product2->attribute_get_list_for_product($product2->id, LANG),
//            'gallery_widget' => $gallery_widget,
//            'details' => $product2->get_details_groups(),
//            'used_attributes' => $used_atributes
//        ]);
   
    }
    
    function shorten($str, $limit, $hellip=false)
    {
            if (strlen($str) <= $limit) {
                    return $str;
            }

            $pos = strpos($str, ' ', $limit);

            return ($pos ? substr($str, 0, $pos) . ($hellip ? '&hellip;' : '') : $str);
    }

    public function category($id = null, $strona = 1, $str = null, $ajax = 0)
    {
//       // if($ajax == 0){
//            $this->session->set_userdata('filters',null);
//       // }
//        $session_filters = !empty($this->session->userdata['filters']) ? $this->session->userdata['filters'] : array();
        $s_categories = !empty($session_filters['categories']) ? $session_filters['categories'] : array();
        $s_attributes = !empty($session_filters['attributes']) ? $session_filters['attributes'] : array();
        $s_str = !empty($session_filters['str']) ? $session_filters['str'] : '';
        /*if(!empty($id)){
            $s_categories = [$id];
        } else {
            if($this->input->post('category_id') !== null && empty($this->input->post('search'))){
                $s_categories = $this->input->post('category_id');
            }
        }
        if(!empty($this->input->post('attributes')) && empty($this->input->post('search'))){
            $s_attributes = $this->input->post('attributes');
        }*/
        if(!empty($this->input->post('search'))){
            $s_str = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
        } 
        if(!empty($id)){
            $s_categories = [$id];
            $this->session->set_userdata('last_viewed_category', $id);
        } 
        if (!empty($_GET['categories']) && empty($this->input->post('search'))) {
            $c_str = $_GET['categories'];
            $categories = explode('_', $c_str);
            if (empty($categories) && !empty($c_str)) {
                $categories[0] = $c_str;
            }
            $s_categories = $categories;
        }

        if(!empty($_GET['attributes']) && empty($this->input->post('search'))){
            $a_str = $_GET['attributes'];
            $attributes_table = explode('_', $a_str);
            foreach ($attributes_table as $at) {
                $attributes[] = explode('-', $at)[0];
            }
            if(empty($attributes) && !empty($a_str)){
                $attributes[0] = explode('-', $a_str)[0];
            }
            $s_attributes = $attributes;
        }
        
        if(!empty($_GET['page'])){
            $strona = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        }
//        if(!empty($str)){
//            $s_str = $str;
//        }
//        if(!empty($this->input->post('search'))){
//            $s_str = $this->input->post('search');
//        }
//        $session_filters = array(
//            'category_id' => !empty($s_categories) ? $s_categories : array(),
//            'attributes' => !empty($s_attributes) ? $s_attributes : array(),
//            'str' => !empty($s_str) ? $s_str : ''
//        );
//        $this->session->set_userdata('filters',$session_filters);
        
        $categories = $this->OfferCategoryModel->findAllForHome();
        foreach($categories as $cat){
            $cat->children = $this->OfferCategoryModel->findAllByParent($cat);
        }
//        if($id == null){
//            if(!empty($categories)){
//                $id = $categories[0]->id;
//            } else {
//                $id = '';
//            }
//        }

        $productsCategory = new ProductCategoryModel($id);
        $category = new OfferCategoryModel($id);
        
        $parent_category = null;
        if(!empty($category->parent_id)){
            $parent_category = new OfferCategoryModel($category->parent_id);
            $parent_category = array(
                'url' => $parent_category->getPermalink(),
                'name' => $parent_category->getTranslation(LANG)
            );
        }
        $categoryTrans = $category->getTranslation(LANG);
        if(!empty($categoryTrans->meta_title)){
            $this->set_whole_title($categoryTrans->meta_title);
        } else {
            if($id > 0 ){
            $this->set_title($categoryTrans->name);
            }else{
                $this->set_title((new CustomElementModel('10'))->getField('wyszukiwarka')->value);
            }
        }
        if(!empty($categoryTrans->meta_description)){
            $this->set_desc($categoryTrans->meta_description);
        }
        //$oferta = site_url('oferta');
        //$this->breadcrumbs[] = "<a href=$oferta>Oferta</a>";
        if($id > 0 ){
//            $name_page = $this->ProductCategoryModel->getCategoryNameById($id);
//            $name_page = $name_page[0]->name;
//            $category_url = site_url( '/oferta/kategoria/' . getAlias($id, $name_page));
            $category_url = $category->getPermalink();
            $this->set_canon_link($category_url);
            $category->generate_breadcrumbs($this->breadcrumbs, $category);
        } else {
            $name_page =  (new CustomElementModel('10'))->getField('wyszukiwarka')->value;
            $category_url = site_url('wyszukiwarka');
            //$this->breadcrumbs[] = "<a href='" . $category_url. "'>$name_page</a>";
        }
        
        $category = new OfferCategoryModel($id);
        $this->load->library('pagination');
        $limit = !empty($_GET['per_page']) ? $_GET['per_page'] : '16';
       // if (!is_null($productsCategory->id)) {
       //     $all_products = (new ProductModel())->findAllByCategory($productsCategory);
       // }
       // if(empty($all_products) || !empty($ajax)){
        $sort = null;
        if (!empty($_GET['sort'])) {
            $sort = $_GET['sort'];
        }
        $min = null; $max = null;
        if(!empty($_GET['min'])){
            $min= $_GET['min'];
        }
        if(!empty($_GET['max'])){
            $max= $_GET['max'];
        }
        $avaible = null;
        if(!empty($_GET['only_avaible'])){
            $avaible = 1;
        }
        $promo = null;
        if(!empty($_GET['promo'])){
            $promo = 1;
        }
        if(count($s_categories) == 1){
            $s_categories = array_merge($s_categories  ,$category->get_children_by_parent($s_categories[0]));
        }
        $all_products = (new ProductModel())->search($s_categories, $s_attributes, $s_str, $strona - 1, $limit, $sort, null, null, $promo, $avaible);
       
        $all_products_count = (new ProductModel())->search_count($s_categories, $s_attributes, $s_str, $min, $max, $promo, $avaible);
       // }
        if(empty($id) && $all_products_count == 1){
            redirect($all_products[0]->getPermalink());
        }
        $config = [
            'base_url' => $category_url,
            'total_rows' => $all_products_count,
            'per_page' => $limit,
            'use_page_numbers' => true,
            'full_tag_open' => '<div class="my-pagination"><ul>',
            'full_tag_close' => '</ul></div>',
            'next_link' => '&gt',
            'next_tag_open' => '<li class="next-page">',
            'next_tag_close' =>  '</li>',
            'last_link' => '&gt&gt',
            'last_tag_open' => '<li class="last-page">',
            'last_tag_close' =>  '</li>',
            'first_link' => '&lt&lt',
            'first_tag_open' => '<li class="first-page">',
            'first_tag_close' =>  '</li>',
            'prev_link' => '&lt',
            'prev_tag_open' => '<li class="prev-page">',
            'prev_tag_close' =>  '</li>',
            'reuse_query_string' => true,
            'enable_query_strings' => true,
            'page_query_string' => true,
            'query_string_segment' => 'page'
        ];
//        $config['uri_segment'] = 5;
        $config["cur_page"] = $strona;
//        if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
//        $config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
        $this->pagination->initialize($config);
        

        //if (!is_null($productsCategory->id)) {
        ///        $all_products = (new ProductModel())->findAllByCategoryPaging($productsCategory, $strona);
        //    }
        //if(empty($all_products) || !empty($ajax)){
        //    $all_products = (new ProductModel())->search($s_categories, $s_attributes, $s_str, NULL);
        //}
        $products = array();
        foreach($all_products as $p){
            $product = $this->ProductTranslationModel->findByProductAndLang($p, LANG);
            $photos = $p->findAllPhotos();
            $products[] = array("product"=>$product, "photos"=>$photos, "data" => $p);
        }
        
        if(!empty($_POST['quantity'])){
            $quantity = $this->input->post('quantity');
            $product_id = $this->input->post('product_id');
            
            $res = ProductModel::add_to_basket($product_id,$quantity);
            if($res){
                $message[] = ['success','Dodano do koszyka. <a href="' . site_url('koszyk').'"> Zobacz koszyk</a>'];
            }
        }
        
        $cat_children = $category->findAllForCategory($id);
        $groups = $this->attr_obj->get_groups3($s_categories);   
        
        $groups_array = array();
        if(!empty($groups)){
            foreach ($groups as $group){
                $at = $this->attr_obj->get_attributes_by_group($group->attributes_group_id);
                if(!empty($at)){
                $groups_array[] = array(
                    'group' => $group,
                    'attributes' => $at
                );
                }
            }
        }
      //  lista użytych atrybutów
//        $all_prod = array();
//        if(!empty($s_categories)){
//            foreach ($s_categories as $c2){
//                $this->productCategoryObj->get_products_down($c2, $all_prod);
//            }
//        } else {
//            foreach ($categories as $c2){
//                $this->productCategoryObj->get_products_down($c2->id, $all_prod);
//            }
//        }
        
        $all_products2 = (new ProductModel())->search3($s_categories, $s_attributes, $s_str);
        
        $used_atributes = array();
        foreach ($all_products2 as $aProd){
            $uattr = $this->product_obj->attribute_get_list_for_product($aProd->id, LANG);
            if(!empty($uattr)){
                foreach($uattr as $uat){
                    if(!in_array($uat->attribute_id, $used_atributes)){
                        $used_atributes[] = $uat->attribute_id;
                    }
                }
            }
        }
        
        $catBestsellers =  (new ProductModel())->search($s_categories, null, null, 0, 4, null, null, null, null, null, 1);

//        if(!empty($id)){
            $this->layout('Oferta/cat_show3', [
                'products' => $products,
                'categories' => $categories,
                'category' => $categoryTrans,
                'category_data' => $category,
                'message' => !empty($message) ? $message : '',
                'cat_children' => $cat_children,
                'groups' => $groups_array,
//                'parent_category' => $parent_category,
                'used_attr' => $used_atributes,
                'active_attr' => $s_attributes,
                'search' => empty($id),
                'permalink' => $category_url,
                'pagination_conf' => $config,
                'popular' => $catBestsellers,
                'search_string' => $s_str
            ]);
//        } else {
//            $this->layout('Oferta/cat_show3', [
//                'products' => $products,
//                'categories' => $categories,
//                'category' => $categoryTrans,
//                'message' => !empty($message) ? $message : '',
//                'cat_children' => $cat_children,
//                'groups' => $groups_array,
//                'parent_category' => $parent_category,
//                'used_attr' => $used_atributes,
//                'active_attr' => $s_attributes,
//                'search' => empty($id),
//                'permalink' => $category_url,
//                'pagination_conf' => $config
//            ]);
//        }
    }
    
    
    public function main_search(){
        $pdata = $this->input->post();
        if (!empty($pdata)) {
            $this->session->set_userdata('main_search', $pdata);
        }
        redirect(site_url('wyszukiwarka'));
    }
    
    public function search($str = null, $strona  = 1, $ajax = 0){
        $this->category(null, $strona, !empty($this->session->userdata('main_search')) ? $this->session->userdata('main_search')['search'] : '', $ajax);
        /*$session_filters = !empty($this->session->userdata['filters']) ? $this->session->userdata['filters'] : array();
        $s_categories = !empty($session_filters['categories']) ? $session_filters['categories'] : array();
        $s_attributes = !empty($session_filters['attributes']) ? $session_filters['attributes'] : array();
        $s_str = !empty($session_filters['str']) ? $session_filters['str'] : '';
        if($this->input->post('category_id') !== null || empty($this->input->post('search'))){
            $s_categories = $this->input->post('category_id');
        }
        if($this->input->post('attributes') !== null || empty($this->input->post('search'))){
            $s_attributes = $this->input->post('attributes');
        }
        if($this->input->post('search') !== null){
            $s_str = $this->input->post('search');
        } 
        
        $session_filters = array(
            'category_id' => !empty($s_categories) ? $s_categories : array(),
            'attributes' => !empty($s_attributes) ? $s_attributes : array(),
            'str' => !empty($s_str) ? $s_str : ''
        );
        $this->session->set_userdata('filters',$session_filters);
        
        $categories = $this->OfferCategoryModel->findAllForHome();
        if(empty($s_categories)){
            if(!empty($categories)){
                foreach($categories as $c){
                    $s_categories[] = $c->id;
                }
            }
        }
        
         //tutaj trzeba dodać kategorie dzieci
        $last_cat = end($s_categories);
        end($s_categories);
        $key = key($s_categories);
        unset($s_categories[$key]);
        reset($s_categories);
        $this->productCategoryObj->get_children($s_categories);
        $s_categories[]= $last_cat;
        
        
        $productsCategory = new ProductCategoryModel();
        $category = new OfferCategoryModel();
        $categoryTrans = $category->getTranslation(LANG);
        
        $this->breadcrumbs[] = "<a href='" . site_url('wyszukiwarka'). "'>". (new CustomElementModel('10'))->getField('wyszukiwarka')."</a>";
        
        $this->load->library('pagination');
        
        $all_products = (new ProductModel())->search($s_categories, $s_attributes, $s_str, NULL);
        $config = [
            'base_url' => site_url('wyszukiwarka'),
            'total_rows' => count($all_products),
            'per_page' => 10,
            'use_page_numbers' => true,
        ];
        $config['uri_segment'] = 5;
        $config["cur_page"] = $strona;

        $this->pagination->initialize($config);
        
        $all_products = (new ProductModel())->search($s_categories, $s_attributes, $s_str, $strona);
       
        $products = array();
        if(!empty($all_products)){
            foreach($all_products as $p){
                $product = $this->ProductTranslationModel->findByProductAndLang($p, LANG);
                $photos = $p->findAllPhotos();
                $products[] = array("product"=>$product, "photos"=>$photos, "data" => $p);
            }
        }
       
        
        if(!empty($_POST['quantity'])){
            $quantity = $this->input->post('quantity');
            $product_id = $this->input->post('product_id');
            $res = ProductModel::add_to_basket($product_id,$quantity);
            if($res){
                $message[] = ['success','Dodano do koszyka. <a href="' . site_url('koszyk').'"> Zobacz koszyk</a>'];
            }
        }
        
        if(!empty($id)){
            $cat_children = $category->findAllForCategory($id);
        } else {
            $cat_children = null;
        }
        
        
        $groups = $this->attr_obj->get_groups();
        $groups_array = array();
        if(!empty($groups)){
            foreach ($groups as $group){
                $groups_array[] = array(
                    'group' => $group,
                    'attributes' => $this->attr_obj->get_attributes_by_group($group->attributes_group_id)
                );
            }
        }
        if(!empty($ajax)){
            $this->load->view('Oferta/search_result',[
                'products' => $products,
                'categories' => $categories,
                'category' => $categoryTrans,
                'message' => !empty($message) ? $message : '',
                'cat_children' => $cat_children,
                'groups' => $groups_array,
                'search' => 1
            ]);
        } else {
            //lista użytych atrybutów
            $all_prod = array();
            $this->productCategoryObj->get_products_down('NULL', $all_prod);
            $used_atributes = array();
            foreach ($all_prod as $aProd){
                $uattr = $this->product_obj->attribute_get_list_for_product($aProd->id, LANG);
                if(!empty($uattr)){
                    foreach($uattr as $uat){
                        if(!in_array($uat->attribute_id, $used_atributes)){
                            $used_atributes[] = $uat->attribute_id;
                        }
                    }
                }
            }
            $this->layout('Oferta/cat_show', [
                'products' => $products,
                'categories' => $categories,
                'category' => $categoryTrans,
                'message' => !empty($message) ? $message : '',
                'cat_children' => $cat_children,
                'groups' => $groups_array,
                'search' => 1,
                'used_attr' => $used_atributes
            ]);
        }
        */
    }
    
    public function show_special($type){
        $all_products = array();
        $title = '';
        $special = '';
        if($type == 'new'){
            $all_products = $this->product_obj->findAllNewProducts();
            $title =  (new CustomElementModel('10'))->getField('Nowosci');
        }
        if($type == 'bestseller'){
            $all_products = $this->product_obj->findAllBestsellerProducts();
            $title = (new CustomElementModel('10'))->getField('popularne');;
        }
        if($type == 'promo'){
            $all_products = $this->product_obj->findAllPromoProducts();
            $title = (new CustomElementModel('10'))->getField('Promocje');
        }
        
        //$this->breadcrumbs[] = $title;
        $products = array();
        foreach($all_products as $p){
            $product = $this->ProductTranslationModel->findByProductAndLang($p, LANG);
            $photos = $p->findAllPhotos();
            $products[] = array("product"=>$product, "photos"=>$photos, "data" => $p);
        }
        
        $this->layout('Oferta/show_special',array(
           'products' => $products,
           'title' => $title,
           'special' => $type
        ));
    }
    
    public function ajax_search_prompt($str = null){
        $product2 = new ProductModel();
        $str = $this->input->post('phrase', true);
        $res = $product2->search_by_string($str, LANG);
        $arr = array();
        if(!empty($res)){
            foreach($res as $r){
                $arr[] = array(
                    'name' => $r->name, 
                    'icon' => (new ProductModel($r->product_id))->getUrl('mini')
                    );
            }
        }
        echo json_encode($arr);
        die();
    }
   
    public function select_option(){
        $option_id = $this->input->post('option');
        $option = $this->ProductModel->select_option($option_id);
        echo json_encode($option);
        die();
    }
    
    public function add_to_basket($product_id){
        $p = new ProductModel($product_id);
        if(empty($p->number_in_package)){
        $p->add_to_basket($product_id, 1);
        } else {
            $p->add_to_basket($product_id, $p->number_in_package);
        }
        redirect(site_url('koszyk'));       
    }
    
    public function ajax_calculate_price(){
        $option_id = $this->input->post('option');
        $attributes = $this->input->post('attributes');
        $product_id = $this->input->post('product_id');
        $quantity = $this->input->post('quantity');
        $additional = $this->input->post('additional');
        $res = $this->product_obj->calculate_price($product_id, $option_id, $attributes, $additional);
        $r = array(
            'price' => $res * $quantity
        );
        
        $r['bonus_price'] = $this->product_obj->getField(23, $product_id, LANG) . ' ' . (new CustomElementModel('16'))->getField('waluta')->value;
        if(!($r['bonus_price'] > 0)){
            $r['bonus_price'] = '';
        }
        
        if(!empty($option_id)){
            $option = $this->ProductModel->select_option($option_id);
            $r['description'] = $option['description'];
            $r['quantity_left'] = $option['quantity_left'];
            if($option['old_price'] > 0){
                $r['bonus_price'] = $option['old_price'] . ' ' . (new CustomElementModel('16'))->getField('waluta')->value;
            } else {
                $r['bonus_price'] = '';
            }
        }
        ajax_res($r);
    }
}
