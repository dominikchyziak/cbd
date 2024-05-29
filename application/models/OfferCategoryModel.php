<?php

class OfferCategoryModel extends MY_Model
{
	public $id;
	public $parent_id;
	public $order;
	public $image;
        public $allegro_id;
        public $filters;
        public $external_id;
        public $external_updated;
	public $created_at;
	public $updated_at;

	public function __construct($id = null)
	{
		parent::__construct();

		if (!is_null($id)) {
			$this->getById($id);
		}
	}

	public function getById($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('offer_categories');

		foreach ($query->result() as $row) {
			$this->id = $row->id;
			$this->parent_id = $row->parent_id;
			$this->order = $row->order;
			$this->image = $row->image;
                        $this->allegro_id = $row->allegro_id;
			$this->created_at = $row->created_at;
			$this->updated_at = $row->updated_at;
                        $this->filters = $row->filters;
                        $this->external_id = $row->external_id;
                        $this->external_updated = $row->external_updated;
		}
	}

	public function findAll()
	{
		$this->db->order_by('order', 'asc');
		$query = $this->db->get('offer_categories');
		return $query->result('OfferCategoryModel');
	}
        
	public function insert_category()
	{
		$res = $this->db->insert('offer_categories', [
			'parent_id' => $this->parent_id,
			'order' => $this->order,
                        'allegro_id' => $this->allegro_id,
			'created_at' => (new DateTime())->format('Y-m-d H:i:s'),
                        'external_id' => $this->external_id,
                        'external_updated' => $this->external_updated
		]);

		if ($res) {
			$this->id = $this->db->insert_id();
		}

		return $res;
	}

	public function saveImage()
	{
		if ($_FILES['image']['error'] !== 0) {
			return true;
		}

		$targetDir = FCPATH.'uploads/offer_categories/'.$this->id;

		if (!is_dir($targetDir)) {
			mkdir($targetDir, 0777, true);
		}

		$config = [
			'upload_path' => $targetDir,
			'allowed_types' => '*',
			'encrypt_name' => true
		];

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('image')) {
			$data = $this->upload->data();

			require_once APPPATH.'third_party/SimpleImage.php';

			$img = new abeautifulsite\SimpleImage($data['full_path']);

			$img->best_fit(1920, 1080)->save($data['full_path']);

			if (!is_dir($targetDir.'/mini')) {
				mkdir($targetDir.'/mini', 0777, true);
			}

			$img->adaptive_resize(330, 330)->save($targetDir.'/mini/'.$data['file_name']);

			$this->image = $data['file_name'];
			return $this->update_category();
		}

		return false;
	}

	public function update_category()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->update('offer_categories', [
			'parent_id' => $this->parent_id ? : null,
			'image' => $this->image,
                        'allegro_id' => $this->allegro_id,
			'order' => $this->order,
                        'filters' => $this->filters,
			'updated_at' => (new DateTime())->format('Y-m-d H:i:s'),
                        'external_id' => $this->external_id,
                        'external_updated' => $this->external_updated
		]);

		return $res;
	}

	public function getUrl($subdir = null)
	{
            $test = strpos($this->image, 'http');
            if($test !== FALSE && $test == 0){
                return $this->image;
            }
            if(!empty($this->image)){
		if (!is_null($subdir)) {
			$subdir .= '/';
		}

		return base_url('uploads/offer_categories/'.$this->id.'/'.$subdir.$this->image);
            } else {
               $res = (new CustomElementModel('9'))->getField('domyslny obrazek');
               return $res;
            }
	}

	public function delete()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->delete('offer_categories');
                
                $this->db->where('offer_category_id', $this->id);
                $this->db->delete('offer_categories_translations');

		if ($res) {
			$this->load->helper('file');

			$dir = FCPATH.'uploads/offer_categories/'.$this->id;
			delete_files($dir, true);
			rmdir($dir);
		}

		return $res;
	}

	public function getListForDropdown(OfferCategoryModel $category = null)
	{
		$this->db->where('parent_id', null);

		if (!is_null($category)) {
			$this->db->where('offer_categories.id <>', $category->id);
		}

		$this->db->select('offer_categories.id, offer_categories_translations.name');
		$this->db->from('duo_offer_categories');
		$this->db->join('offer_categories_translations', 'offer_categories.id = offer_categories_translations.offer_category_id');
		$this->db->where('offer_categories_translations.lang', 'pl');
		$categories = $this->db->get()->result('OfferCategoryModel');
		$out = [null => 'brak'];

		foreach ($categories as $category) {
			$this->input_children_dropdown($out, 0, $category);
		}

		return $out;
	}
        
        public function input_children_dropdown(&$out, $level, $category){
            $this->db->select('offer_categories.id as id, offer_categories_translations.name');
		//$this->db->from('offer_categories');
		$this->db->join('offer_categories_translations', 'offer_categories.id = offer_categories_translations.offer_category_id');
		$this->db->where('offer_categories_translations.lang', 'pl');
            $children = $this->findAllByParent($category);
            $str = '';
            for($i = 0; $i < $level; $i++){
                $str .= '-';
            }
            $out[$category->id] = $str.($category->name); 
            if(!empty($children)){
                foreach ($children as $child){
                    $this->input_children_dropdown($out, $level+1, $child);
                }
                return TRUE;
            } else {
                return FALSE;
            }
        }

	public function getListForProductDropdown()
	{
            $this->db->where('parent_id', null);
		$this->db->select('offer_categories.id, offer_categories.parent_id, offer_categories_translations.name');
		$this->db->from('offer_categories');
		$this->db->join('offer_categories_translations', 'offer_categories.id = offer_categories_translations.offer_category_id');
		$this->db->where('offer_categories_translations.lang', 'pl');
		$result = $this->db->get()->result('OfferCategoryModel');
		$categories = [];
		$out = [null => 'brak'];
                
		foreach ($result as $category) {
                    $this->input_children_dropdown($out, 0, $category);
		}    
		return $out;
	}

	public function findAllByParent(OfferCategoryModel $category = null)
	{
		if (!is_null($category)) {
			$this->db->where('offer_categories.parent_id', $category->id);
		} else {
			$this->db->where('offer_categories.parent_id IS NULL');
		}
                
		return $this->findAll();
	}

	public function getProducts()
	{
		$this->load->model('ProductModel');
		return (new ProductModel())->findAllByCategory($this);
	}

	public function getTranslation($lang)
	{
		$this->load->model('OfferCategoryTranslationModel');

		return $this->OfferCategoryTranslationModel->findByCategoryAndLang($this, $lang);
	}

	public function findAllForHome($offset = null, $limit = null)
	{
		$this->db->select('oc.*, oct.name, oct.body, oct.slogan');
		$this->db->from('offer_categories oc');
		$this->db->join('offer_categories_translations oct', 'oct.offer_category_id = oc.id');
		$this->db->where('oct.lang', LANG);
		$this->db->where('oc.parent_id', null);
		$this->db->order_by('oc.order', 'ASC');
		//$this->db->limit($limit, $offset);
		return $this->db->get()->result('OfferCategoryModel');
	}
        public function findAllForCategory($category_id, $offset = null, $limit = null)
	{
		$this->db->select('oc.*, oct.name, oct.body, oct.body1, oct.slogan');
		$this->db->from('offer_categories oc');
		$this->db->join('offer_categories_translations oct', 'oct.offer_category_id = oc.id');
		$this->db->where('oct.lang', LANG);
		$this->db->where('oc.parent_id', $category_id);
		$this->db->order_by('oc.order', 'ASC');
		//$this->db->limit($limit, $offset);
		return $this->db->get()->result('OfferCategoryModel');
	}
        
        public function getPermalink(){
            $translation = $this->getTranslation(LANG);
            if(empty($translation->custom_url)){
            return site_url('kategoria/'.getAlias($this->id, $translation->name));
        } else {
            return site_url($translation->custom_url);
        }
        }
        
        public function generate_breadcrumbs(&$breadcrumbs, $category){
            
            if(!empty($category->parent_id)){
                $parent = new OfferCategoryModel($category->parent_id);
                $this->generate_breadcrumbs($breadcrumbs,$parent);
            }
//             $this->getById($category->id);
             $translation = $category->getTranslation(LANG);
             $breadcrumbs[] = '<a href="' . $category->getPermalink() . '">' .$translation->name. '</a>';
             return true;
//            if(!empty($category->parent_id)){
//                $this->getById($category->parent_id);
//                //if(isset($category)){
//                    $translation = $this->getTranslation(LANG);
//                    $breadcrumbs[] = '<a href="' . $this->getPermalink() . '">' .$translation->name. '</a>';
//                    $category = $this;
//                //}
//            }
//            
//            if(!empty($category->parent_id)){
//                $this->generate_breadcrumbs($breadcrumbs,$category);
//                return FALSE;
//            } else {
//                return TRUE;
//            }
        }
        
        public function has_children($cat_id){
            $this->db->where('parent_id',$cat_id);
            $q = $this->db->get('offer_categories');
            if($q->num_rows() > 0){
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        public function get_children(&$categories_ids){
            if(!empty($categories_ids)){
                $next = FALSE;
                foreach($categories_ids as $cat_id){
                    if($this->has_children($cat_id)){
                        $q = $this->db->get_where('offer_categories', array('parent_id' => $cat_id));
                        if($q->num_rows() > 0){
                            foreach ($q->result() as $child){
                                if(!in_array($child->id, $categories_ids)){
                                    $categories_ids[] = $child->id;
                                    $next = TRUE;
                                }
                            }
                        }
                    }
                }
                if($next){
                    $this->get_children($categories_ids);
                }
            }
        }
        
        /**
         * pobiera kategorie i zwraca produkty pod tą kategorią
         */
        function get_products_down($category_id, &$products){
            if(empty($category_id)){
                return true;
            }
            $q = $this->db->get_where('duo_products', array(
                'offer_category_id' => $category_id,
                'active' => 0
            ));
            if($q->num_rows()){
                foreach ($q->result() as $pr){
                    $products[$pr->id] = $pr;
                }
            }
            $q2 = $this->db->get_where('duo_offer_categories', array('parent_id' => $category_id));
            if($q2->num_rows() > 0){
                foreach($q2->result() as $cat){
                    $this->get_products_down($cat->id, $products);
                }
            }
            return true;
        }
        
        public function get_allegro_id_by_product($product_id){
            $this->db->where('duo_products.id', $product_id);
            $this->db->join('duo_products','duo_products.offer_category_id = duo_offer_categories.id');
            $q = $this->db->get('duo_offer_categories');
            $res = $q->row();
            return $res->allegro_id;
        }
        
        public function get_duo_cat_id_by_allegro_cat_id($allegro_cat_id){
            $this->db->where('allegro_id', $allegro_cat_id);
            $q = $this->db->get('duo_offer_categories');
            if($q->num_rows() > 0){
                return $q->row()->id;
            } else{
                $new_category = new OfferCategoryModel();
                $new_category->allegro_id = $allegro_cat_id;
                $new_category->insert_category();
                
                $this->load->model("AllegroModel");
                $category_data_josn =$this->AllegroModel->get_allegro_category($allegro_cat_id);
                $category_data = json_decode($category_data_josn);
                $this->load->model("OfferCategoryTranslationModel");
                $tcat = new OfferCategoryTranslationModel();
                $tcat->offer_category_id = $new_category->id;
                $tcat->name = $category_data->name;
                $tcat->lang = 'pl';
                $tcat->insert();
                
                return $new_category->id;
            }
        }
        
        public function find_all_for_menu(){
            $out = $this->findAllByParent(null);
            if(!empty($out)){
                foreach ($out as $o) {
                    $o->children = $this->findAllByParent($o);
                    if(!empty($o->children)){
                        foreach($o->children as $c){
                            $c->children = $this->findAllByParent($c);
                        }
                    }
                }
            }
            return $out;
        }
        
    public function get_children_by_parent($categories_id) {
        $arr = array();
        if (!empty($categories_id)) {
            $this->db->where('parent_id', $categories_id);
            $q = $this->db->get('offer_categories');
            if ($q->num_rows() > 0) {
                foreach ($q->result() as $row) {
                    $id = $row->id;
                    $arr[] = $id;
                    $arr_c = $this->get_children_by_parent($id);
                    $arr = array_merge($arr, $arr_c);
                }
            }
            return $arr;
        } else {
            return $arr;
        }
    }

    
    public function findByExternalId($external_id){
        $this->db->where('external_id', $external_id);
        $q = $this->db->get('offer_categories');
            if ($q->num_rows() > 0) {
                return $q->row();
            } else {
                return null;
            }
    }
}
