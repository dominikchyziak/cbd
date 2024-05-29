<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Offer_categories extends Backend_Controller {
    public $languages = array();
    public $offerCategoryObj;
	public function __construct()
	{
		parent::__construct();
                $langs = get_languages();
                foreach($langs as $l){
                    $this->languages[] = $l->short;
                }
                $this->load->model('OfferCategoryModel');
                $this->offerCategoryObj = new OfferCategoryModel();

		$this->load->vars(['activePage' => 'offer_categories']);
	}

	public function index()
	{
		$colorModel = new OfferCategoryModel();
                $this->db->where('parent_id',null);
		$result = $colorModel->findAll();
		$offer_categories = [];

		foreach ($result as $category) {
                    $this->input_children($offer_categories, 0, $category);
		}

		$this->layout('duocms/Offer_categories/index', [
			'offer_categories' => $offer_categories
		]);
	}
        
        function input_children(&$offer_categories, $level, $category){
            $children = $this->offerCategoryObj->findAllByParent($category);
            $offer_categories[] = array('category' => $category, 'level' => $level); 
            if(!empty($children)){
                foreach ($children as $child){
                    $this->input_children($offer_categories, $level+1, $child);
                }
                return TRUE;
            } else {
                return FALSE;
            }
        }


        

	public function create()
	{
		$this->load->model('OfferCategoryModel');

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$parent_id = $this->input->post('parent_id', true);
			$order = $this->input->post('order', true);
                        $allegro_id = $this->input->post('allegro_id', true);

			$category = new OfferCategoryModel();
			$category->parent_id = $parent_id ? : null;
			$category->order = $order;
                        $category->allegro_id = $allegro_id;
			$category->insert_category();

			if (!$category->id) {
				$this->setError('Wystąpił błąd.');
				redirect('duocms/offer_categories/create');
			}

			$category->saveImage();

			$this->load->model('OfferCategoryTranslationModel');

			foreach ($this->languages as $lang) {
				$data = $this->input->post($lang);

				$translation = new OfferCategoryTranslationModel();
				$translation->offer_category_id = $category->id;
				$translation->lang = $lang;
				$translation->name = $data['name'];
                                $translation->slogan = $data['slogan'];
				$translation->body = $data['body'];
                                $translation->body1 = $data['body1'];
                                $translation->meta_title = !empty($data['meta_title']) ? $data['meta_title'] : '';
                                $translation->meta_description = !empty($data['meta_description']) ? $data['meta_description'] : '';
                                $translation->custom_url = !empty($data['custom_url']) ? $data['custom_url'] : '';
				$translation->insert();
			}

			$this->setOkay('Kategoria została zapisana.');
			redirect('duocms/offer_categories/edit/'.$category->id);
		}

		$parents = (new OfferCategoryModel())->getListForDropdown();

		$this->load->helper('form');

		$this->layout('duocms/Offer_categories/form', [
			'parents' => $parents
		]);
	}

	public function edit($id)
	{
		$this->load->model('OfferCategoryModel');

		$category = new OfferCategoryModel($id);

		if (!$category->id) {
			show_404();
		}

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
                    save_custom_fields('product_category', $id);
			$parent_id = $this->input->post('parent_id', true);
			$order = $this->input->post('order', true);
                        $allegro_id = $this->input->post('allegro_id', true);
                        $atr_grp = $this->input->post('attr');
                        
			$category->parent_id = $parent_id;
			$category->order = $order;
                        $category->allegro_id = $allegro_id;
                        $category->filters = json_encode($atr_grp);
			$res = $category->update_category();

			if ($res) {
				$this->setOkay('Kategoria została zapisana.');
			} else {
				$this->setError('Wystąpił błąd.');
			}

			$category->saveImage();

			$this->load->model('OfferCategoryTranslationModel');

			foreach ($this->languages as $lang) {
				$data = $this->input->post($lang);

				$translation = new OfferCategoryTranslationModel($data['id']);
				$translation->name = $data['name'];
                                $translation->slogan = $data['slogan'];
				$translation->body = $data['body'];
                                $translation->body1 = $data['body1'];
                                $translation->meta_title = !empty($data['meta_title']) ? $data['meta_title'] : '';
                                $translation->meta_description = !empty($data['meta_description']) ? $data['meta_description'] : '';
                                $translation->custom_url = !empty($data['custom_url']) ? $data['custom_url'] : '';
				$translation->update();
			}

			redirect('duocms/offer_categories/edit/'.$category->id);
		}

		$parents = (new OfferCategoryModel())->getListForDropdown($category);

		$this->load->helper('form');
                
                $this->load->model('ProductAttributesModel');
                $atr_grps = $this->ProductAttributesModel->get_attr_grp_by_offer_category($id);
                $attr_grp_for_dropdown = array();
                if(!empty($atr_grps)){
                    foreach ($atr_grps as $ag) {
                        $attr_grp_for_dropdown[$ag->id] = $ag->name;
                    }
                }
		$this->layout('duocms/Offer_categories/form', [
			'category' => $category,
			'parents' => $parents,
                        'attribute_groups' =>$attr_grp_for_dropdown
		]);
	}

	public function image_delete($id)
	{
		$this->load->model('OfferCategoryModel');

		$category = new OfferCategoryModel($id);

		if (!$category->id) {
			show_404();
		}

		$category->image = null;
		$res = $category->update_category();

		if ($res) {
			$this->setOkay('Zdjęcie zostało usunięte.');
		} else {
			$this->setError('Wystąpił błąd.');
		}

		redirect($this->input->server('HTTP_REFERER'));
	}

	public function delete($id)
	{
		$this->load->model('OfferCategoryModel');

		$category = new OfferCategoryModel($id);

		if (!$category->id) {
			show_404();
		}

		$res = $category->delete();

		if ($res) {
			$this->setOkay('Kategoria została usunięta.');
		} else {
			$this->setError('Wystąpił błąd.');
		}

		redirect($this->input->server('HTTP_REFERER'));
	}
        
        public function generate_friendly_url($id){
            $this->load->model('OfferCategoryModel');

		$category = new OfferCategoryModel($id);

		if (!$category->id) {
			show_404();
		}
                $this->load->helper('text');
                $tcat = $category->getTranslation(LANG);
                
                $url = preg_replace(array('/\s+/', '/[^a-z0-9-]/'), array('-', '-'), trim(strtolower(convert_accented_characters(shorten($tcat->name, 160)))));
                
                while(!empty($category->parent_id)){
                    $category = new OfferCategoryModel($category->parent_id);
                    $tcat = $category->getTranslation(LANG);
                    $url = preg_replace(array('/\s+/', '/[^a-z0-9-]/'), array('-', '-'), trim(strtolower(convert_accented_characters(shorten($tcat->name, 160))))).'/'.$url;
                }
                
                echo $url;
        }
}
