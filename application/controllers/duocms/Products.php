<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products extends Backend_Controller {

    public $languages = array();
    public $product_obj;
    public $attr_obj;

    public $menu_item = ['product','product_list'];

    public function __construct() {
        parent::__construct();
        $this->load->model("ProductPhotoModel");
        $this->load->model('ProductModel');
        $this->load->model('ProductTranslationModel');
        $this->load->model('OfferCategoryModel');
        $this->load->model("ProductAttributesModel");
        
        $this->load->helper('form');
        $langs = get_languages();
        foreach ($langs as $l) {
            $this->languages[] = $l->short;
        }
        $this->load->vars(['activePage' => 'products']);
        
        $this->product_obj = new ProductModel();
        $this->attr_obj = new ProductAttributesModel();
    }

    public function index($page = 1) {
        $productModel = new ProductModel();
        if (!empty($_POST['element'])) {
            $i = 1;
            foreach ($_POST['element'] as $id) {
                $productModel->sort_item($id, $i);
                $i++;
            }
        }
        $data = $this->input->post();
        if(empty($data)){
            $data['s'] = $this->session->userdata('admin_product_string_search');
            $data['category'] = $this->session->userdata('admin_product_cat_search');
            $data['opt'] = $this->session->userdata('admin_product_opt');
        } else {
            $page = 1;
        }
        //$categories = $this->OfferCategoryModel->findAllForHome();
        $categories = (new OfferCategoryModel())->getListForProductDropdown();
        if(!empty($data['s']) || !empty($data['category']) || !empty($data['opt'])){
            if(!empty($data['category']) && $data['category'] != 'all'){
                $this->db->join('products_categories', 'products_categories.product_id = products.id');
                $this->db->where('duo_products_categories.category_id', $data['category']);
            }
            if(!empty($data['s'])){
                $this->db->where('(code LIKE "%'.$data['s'].'%" OR products_translations.name LIKE "%'. $data['s']. '%")');
            }
            if(!empty($data['opt'])){
                switch($data['opt']){
                    case 'promo':
                        $this->db->where('products.promo', 1);
                    break;
                    case 'bestseller':
                        $this->db->where('products.bestseller', 1);
                    break;
                    case 'new':
                        $this->db->where('products.new', 1);
                    break;
                    default:
                        break;
                }
            }
        }
        $limit = 50;
        $products = $productModel->findAllForCmsList(1, $limit, ($page-1)*$limit);
        if(!empty($data['s']) || !empty($data['category'])){
            if(!empty($data['category']) && $data['category'] != 'all'){
               // $this->db->where('products.offer_category_id', $data['category']);
                $this->db->join('products_categories', 'products_categories.product_id = products.id');
                $this->db->where('duo_products_categories.category_id', $data['category']);
                $this->session->set_userdata('admin_product_cat_search', $data['category']);
            } else {
                $this->session->set_userdata('admin_product_cat_search', 'all');
            }
            if(!empty($data['s'])){
                $this->db->where('(code LIKE "%'.$data['s'].'%" OR products_translations.name LIKE "%'. $data['s']. '%")');
                $this->session->set_userdata('admin_product_string_search', $data['s']);
            } else {
                $this->session->set_userdata('admin_product_string_search', '');
            }
            if(!empty($data['opt'])){
                switch($data['opt']){
                    case 'promo':
                        $this->db->where('products.promo', 1);
                    break;
                    case 'bestseller':
                        $this->db->where('products.bestseller', 1);
                    break;
                    case 'new':
                        $this->db->where('products.new', 1);
                    break;
                    default:
                        break;
                }
                $this->session->set_userdata('admin_product_opt', $data['opt']);
            }
        }
        $this->load->library('pagination');
        $config = [
            'base_url' => site_url('duocms/products'),
            'total_rows' => $productModel->findAllForCmsListCount(1),
            'per_page' => $limit,
            'use_page_numbers' => true,
        ];
//        $config['uri_segment'] = 4;
        
//        $config["cur_page"] = $strona;
//        if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
//        $config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
        $this->pagination->initialize($config);
        $this->layout('duocms/Products/index', [
            'data' => $data,
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function create() {
        $this->menu_item[1] = 'product_create';
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $offer_category_id = $this->input->post('offer_category_id');
            $type = $this->input->post('type');

            $product = new ProductModel();
            $product->offer_category_id = 0;
            $product->new = $this->input->post('new');
            $product->promo = $this->input->post('promo');
            $product->bestseller = $this->input->post('bestseller');
            $product->weight = $this->input->post('weight');
            $product->quantity = $this->input->post('quantity');
            $product->type = $type;
            $product->prices = $this->input->post('price');
            $product->code = $this->input->post('code');
            $product->categories = $this->input->post('categories');
            $product->producent = $this->input->post('producent');
            $product->slider = $this->input->post('slider');
            $product->insert_product();

            if (!$product->id) {
                $this->setError('Wystąpił błąd.');
                redirect('duocms/products/create');
            }

            foreach ($this->languages as $lang) {
                $data = $this->input->post($lang);

                $translation = new ProductTranslationModel();
                $translation->product_id = $product->id;
                $translation->lang = $lang;
                $translation->name = $data['name'];
                $translation->format = $data['format'];
                $translation->slogan = $data['slogan'];
                $translation->body = $data['body'];
                $translation->meta_title = !empty($data['meta_title']) ? $data['meta_title'] : '';
                $translation->meta_description = !empty($data['meta_description']) ? $data['meta_description'] : '';
                $translation->custom_url = !empty($data['custom_url']) ? $data['custom_url'] : '';
                $translation->insert();
            }

            setAlert('success','Produkt został zapisany.');
            redirect('duocms/products/edit/' . $product->id);
        }

        $categories = (new OfferCategoryModel())->getListForProductDropdown();
        
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
        
        $this->load->model("CurrencyModel");
        $currencies = $this->CurrencyModel->get_acitve_currencies();
        
        $this->layout('duocms/Products/form', [ 
            'categories' => $categories,
            'types' => array(),
            'groups' => $groups_array,
            'currencies' => $currencies
        ]);
    }

    public function edit($id) {
        $this->menu_item[1] = 'product_edit';
        $this->add_css(assets('plugins/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css'));
        $this->add_js(assets('plugins/plupload/js/plupload.min.js'));
        $this->add_js(assets('plugins/plupload/js/jquery.plupload.queue/jquery.plupload.queue.min.js'));
        $this->add_js(assets('plugins/plupload/js/i18n/pl.js'));

        $product = new ProductModel($id);
        
        $products = $product->get_product_list_for_relations($product->id);
        $relations = $product->get_product_relations($product->id);
        if (!$product->id) {
            show_404();
        }

        if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->input->post("action") == "add_product") {
            save_custom_fields('product', $id);
            $photo_order = $this->input->post('photo_order');
            if ($photo_order) {

                foreach ($photo_order as $order => $photoId) {
                    $photo = new ProductPhotoModel($photoId);
                    $photo->order = $order;
                    $photo->update();
                }
            }
            
            //produkty powiazane
            $rels = $this->input->post('relations');
            if(!empty($rels)){
                foreach($relations as $rel_stare){
                    if(!in_array($rel_stare, $rels)){
                        $product->delete_product_relations($rel_stare,$product->id);
                    }
                }
                foreach($rels as $rel ){  
                    if(!$product->check_product_relation($rel, $product->id)){
                        $product->save_product_relations($product->id, $rel);
                    }
                }
            } else {
                foreach($relations as $rel_stare){
                   $product->delete_product_relations($rel_stare,$product->id);
                }
            }
            // koniec produkty powiazane

            $offer_category_id = $this->input->post('offer_category_id', true);
            $type = $this->input->post('type', true);

            $product->offer_category_id = $offer_category_id;
            $product->type = $type;
            $product->new = $this->input->post('new');
            $product->weight = $this->input->post('weight');
            
            
            $new_quanity = ($this->input->post('quantity')) *1;
            $old_quanity = ($product->quantity) *1;
            
           $product->quantity = $new_quanity;
//            if($old_quanity !== $new_quanity){
//                $this->load->model("AllegroModel");
//                $allegro_id = $this->AllegroModel->get_allegro_auction_id($product->id);
//                if($allegro_id > 0){
//                    $auction_json = $this->AllegroModel->get_offer($allegro_id);
//                    $auction = json_decode($auction_json);
//                    $res = $this->AllegroModel->synchronise_amount($auction, ($new_quanity-$old_quanity));
//                    $product->quantity = $res;
//                } else {
//                    $product->quantity = $new_quanity;
//                }
//            }
            
            $product->promo = $this->input->post('promo');
            $product->bestseller = $this->input->post('bestseller');
//            $product->price = $this->input->post('price');
            $product->code = $this->input->post('code');
            $product->prices = $this->input->post('price');
            $product->categories = $this->input->post('categories');
            $product->producent = $this->input->post('producent');
            $product->slider = $this->input->post('slider');
            $res = $product->update_product();

            if (!$res) {
                $this->setError('Wystąpił błąd.');
                redirect('duocms/products/edit/' . $product->id);
            }

            foreach ($this->languages as $lang) {
                $data = $this->input->post($lang);

                $translation = new ProductTranslationModel($data['id']);
                $translation->name = $data['name'];
                $translation->format = $data['format'];
                $translation->slogan = $data['slogan'];
                $translation->body = $data['body'];
                $translation->meta_title = !empty($data['meta_title']) ? $data['meta_title'] : '';
                $translation->meta_description = !empty($data['meta_description']) ? $data['meta_description'] : '';
                $translation->custom_url = !empty($data['custom_url']) ? $data['custom_url'] : '';
                $translation->update();
            }

            setAlert('success','Produkt został zapisany.');
            redirect('duocms/products/edit/' . $product->id);
        }
        if ($this->input->post("action") === "desc_photo") {
            $photo_id = $this->input->post("id");
            $descriptions = $this->input->post("description");
            $pM = new ProductPhotoModel();
            foreach ($descriptions as $lang => $description) {
                $pM->update_description($photo_id, $description, $lang);
            }
        }
        if ($this->input->post("action") === "add_option") {
            $args = array(
                'product_id' => $product->id,
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'price_change' => $this->input->post('price_change'),
                'quantity' => $this->input->post('quantity'),
                'quantity_left' => $this->input->post('quantity'),
                'visibility' => $this->input->post('visibility')
            );
            if($product->add_option($args)){
                setAlert('success','Opcja została dodana.');
                redirect('duocms/products/edit/' . $product->id);
            }
        }
        //aktualizacja opcji wszystkich naraz
        if($this->input->post('action') === 'update_options'){
            $data2 = $this->input->post();
            $prices = $data2['price'];
            $old_prices = $data2['old_price'];
            $weights = $data2['weight'];
            $quantities_left = $data2['quantity_left'];
            foreach ($prices as $o_id => $n_price){
                $oargs = array();
                $oargs['id'] = $o_id;
                $oargs['price_change'] = $n_price;
                $oargs['old_price'] = $old_prices[$o_id];
                $oargs['weight'] = $weights[$o_id];
                $oargs['quantity_left'] = $quantities_left[$o_id];
                $this->product_obj->edit_option($oargs);
            }
            setAlert('success','Opcje zaktualizowane.');
             redirect('duocms/products/edit/' . $product->id);
        }
        
        //dodawanie atrybutu
        if($this->input->post('action') === "add_attribute"){
            $res = $this->product_obj->attribute_add_to_product($this->input->post('attribute'), $id, $value);
            if($res){
                setAlert('success','Atrybut został dodany.');
                redirect('duocms/products/edit/' . $product->id);
            } else {
                setAlert('error','Nie dodano atrybutu. <br> Prawdopodobnie już jest dodany.');
                redirect('duocms/products/edit/' . $product->id);
            }
        }
        
        if($this->input->post('action')  === 'add_detail_group'){
            $args = [
                'name' => $this->input->post('name')
            ];
            $res = $product->add_detail_group($args);
            if($res){
                setAlert('success','Grupa detali została dodana.');
                redirect('duocms/products/edit/' . $product->id);
            } else {
                setAlert('error','Nie dodano grupy detali.');
                redirect('duocms/products/edit/' . $product->id);
            }
        }
        
        if($this->input->post('action') === 'add_detail'){
            $args = [
                'name' => $this->input->post('name'),
                'price' => $this->input->post('price'),
                'val' => $this->input->post('val'),
                'group_id' => $this->input->post('group_id')
            ];
            $res = $product->add_detail($args);
            if($res){
                setAlert('success','Grupa detali została dodana.');
                redirect('duocms/products/edit/' . $product->id);
            } else {
                setAlert('error','Nie dodano grupy detali.');
                redirect('duocms/products/edit/' . $product->id);
            }
        }
        
        $categories = (new OfferCategoryModel())->getListForProductDropdown();
        $photos = $product->findAllPhotos();
        $product_options = $product->get_options_admin($product->id);

        $attributes = $this->product_obj->attribute_get_list();
        $product_attributes = $this->product_obj->attribute_get_list_for_product($product->id);
        
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
        $this->load->model("CurrencyModel");
        $currencies = $this->CurrencyModel->get_acitve_currencies();
        $details = $product->get_details_groups();
        $producents=$this->ProductModel->findAllProducents();
        
        $this->layout('duocms/Products/form', [
            'product' => $product,
            'products' => $products,
            'relations' => $relations,
            'photos' => $photos,
            'categories' => $categories,
            'producents' => $producents,
            'types' => array(),
            'product_options' => $product_options,
            'attributes' => $attributes,
            'product_attributes' => $product_attributes,
            'groups' => $groups_array,
            'currencies' => $currencies,
            'details' => $details
        ]);
    }
    
    //kopiowanie produktu
    public function copy_product($id){
        $new_id = $this->product_obj->copy_product($id);
        if(!empty($new_id)){
            setAlert('info','Produkt został skopiowany');
        }
        redirect(site_url('duocms/products/edit/'.$new_id));
    }
    
    public function edit_option($option_id, $product_id){
        $product_model = new ProductModel($product_id);
        $option_data = $product_model->select_option($option_id);
        if(!empty($_POST)){
            $args = $this->input->post();
            $args['id'] = $option_id;
            unset($args['add_option']);
            $product_model->edit_option($args);
            setAlert('info','Opcja została zaktualizowana.');
            redirect('duocms/products/edit/' . $product_id);
        }
        
        $this->layout('duocms/Products/edit_option', [
            'option' => $option_data
        ]);
    }
    
    public function delete_option($option_id, $product_id){
        $product = new ProductModel();
        $res = $product->delete_option($option_id, $product_id);
        if($res){
            $this->setOkay('Opcja została usunięta.');
            redirect('duocms/products/edit/' . $product_id);
        } else {
            echo 'Błąd!';
        }
    }

    public function delete($id) {
        $discount = new ProductModel($id);

        if (!$discount->id) {
            show_404();
        }

        $res = $discount->delete();

        if ($res) {
            setAlert('info','Produkt został uzunięty.');
        } else {
            setAlert('error','Wystąpił błąd.');
        }

        redirect($this->input->server('HTTP_REFERER'));
    }

    public function upload_photo() {
        $discountId = $this->input->post('product_id');
        $discount = new ProductModel($discountId);

        if (!$discount->id) {
            show_404();
        }

        $photo = new ProductPhotoModel();
        $photo->product_id = $discount->id;
        $photo->insert();

        if (!$photo->id) {
            show_404();
        }

        $res = $photo->saveImage($_FILES['file']);

        if ($res) {
            echo json_encode(['result' => 1]);
        } else {
            echo json_encode(['result' => 0]);
        }
    }

    public function ajax_delete_photo($id) {
        $photo = new ProductPhotoModel($id);

        if (!$photo->id) {
            show_404();
        }

        $res = $photo->delete();

        if ($res) {
            echo json_encode(['result' => 1]);
        } else {
            echo json_encode(['result' => 0]);
        }
    }
    
    public function delete_attribute($attribute_id, $product_id){
        $res = $this->product_obj->delete_attribute($attribute_id, $product_id);
        if($res){
            setAlert('info','Usunięto atrybut');
        } else {
            setAlert('error','Nie udało się usunąć atrybutu');
        }
        redirect(site_url('duocms/products/edit/'.$product_id));
    }
    
    //Płatności
    public function payment(){
        $this->layout('duocms/Products/payment',[]);
    }
    
    //zmiana aktywności
    public function change_status($id){
        $r = $this->product_obj->change_status($id);
        echo $r;
        die();
    }
    
    //eksport do pliku xls
    public function export_xls(){
        require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        $spreadsheet->getProperties()->setCreator(get_option('meta_title'))
                ->setLastModifiedBy(get_option('meta_title'))
                ->setTitle(get_option('meta_title') . ' Eksport Prod')
                ->setSubject(get_option('meta_desc'))
                ->setDescription(get_option('meta_desc'));
        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'top' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FFA0A0A0',
                ),
                'endcolor' => array(
                    'argb' => 'FFFFFFFF',
                ),
            ),
        );
        $spreadsheet->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);
        
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Id')
                ->setCellValue("B1", 'Tytuł')
                ->setCellValue("C1", 'Cena')
                ->setCellValue("D1", 'Stan');
        
        $all_products = $this->product_obj->findAllForCmsList(1);
        if(!empty($all_products)){
            $i = 1;
            foreach($all_products as $product){
                $i++;
                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A".$i, $product->id)
                ->setCellValue("B".$i, $product->name)
                ->setCellValue("C".$i, $product->price)
                ->setCellValue("D".$i, $product->quantity);
            }
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="produkty'.date('Y-m-d_His').'.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
        $writer->save('php://output');
        exit;

        die();
    }


    public function edit_detail($detail_id, $product_id){
        $pdata = $this->input->post();
        $prod = new ProductModel($product_id);
        
        if(!empty($pdata)){
            $prod->edit_detail($pdata);
        }
        $detail = $prod->get_detail_by_id($detail_id);
        $this->layout('duocms/Products/edit_detail', [
            'detail' => $detail,
            'product' => $prod
        ]);
    }
    public function edit_detail_group($group_id){
        $pdata = $this->input->post();
        $prod = new ProductModel();
        
        if(!empty($pdata)){
            $prod->edit_detail_group($pdata);
        }
        $detail = $prod->get_detail_group_by_id($group_id);
        $this->layout('duocms/Products/edit_detail_group', [
            'group' => $detail
        ]);
    }
    public function delete_detail($detail_id){
        $this->load->model("ProductModel");
        $prod = new ProductModel();
        
        $prod->delete_detail($detail_id);
        
        redirect($_SERVER['HTTP_REFERER']);
    }
    public function availability() {
        $this->load->model('ProductModel');
        $producents = $this->ProductModel->findAllProducents();
        $data = $this->input->post();
        if (!empty($data)) {
            $availability = $data['availability'];
            $producent=$data['producent'];
            $products=$this->ProductModel->findAllByProducent($producent);
            $this->load->model('CustomFieldsModel');
            $cfm = new CustomFieldsModel();
            foreach ($products as $product) {
                $cfm->save_field(3, $product->id, $availability, $lang = 'pl');
            };
            setAlert('success','Dostępność zmieniona pomyślnie.');
        }
        $this->layout('duocms/Products/availability', [
            'producents'=>$producents,
        ]);
        
    }

}
