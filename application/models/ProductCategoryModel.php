<?php

class ProductCategoryModel extends CI_Model
{
	public $id;
	private $_table = 'offer_categories';
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
		$query = $this->db->get($this->_table);

		foreach ($query->result() as $row) {
			$this->id = $row->id;
		}
	}

	public function findAll()
	{
		$query = $this->db->get($this->_table);
		return $query->result('ProductCategoryModel');
	}
	public function insert()
	{
		$res = $this->db->insert($this->_table, [
		]);

		if ($res) {
			$this->id = $this->db->insert_id();
		}

		return $res;
	}
	public function getBackendList($lang, $limit = null, $offset = null)
	{
		$this->db->select(implode(', ', [
			'offer_categories.id',
			'offer_categories_translations.name',
		]));
		$this->db->from('offer_categories');
		$this->db->join('offer_categories_translations', 'offer_categories.id = offer_categories_translations.offer_category_id');
		$this->db->where('offer_categories_translations.lang', $lang);
		return $this->db->get()->result(get_called_class());
	}
	public function getCategoryNameById($id)
	{
		$this->db->select('offer_categories_translations.name');
		$this->db->from('offer_categories');
		$this->db->join('offer_categories_translations', 'offer_categories.id = offer_categories_translations.offer_category_id');
		$this->db->where('offer_categories.id',$id);
                $this->db->where('lang', LANG);
		return $this->db->get()->result(get_called_class());
	}
	public function getTranslation($lang)
	{
		$this->load->model('ProductCategoryTranslationModel');
		return $this->ProductCategoryTranslationModel->findByCategoryAndLang($this, $lang);
	}
	public function delete()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->delete($this->_table);

		return $res;
	}
	public function update()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->update($this->_table, [
		]);

		return $res;
	}

	public function getDropdownList($lang = 'pl', $limit = null, $offset = null)
	{
		$this->db->select(implode(', ', [
			'offer_categories.id',
			'offer_categories_translations.name',
		]));
		$this->db->from('offer_categories');
		$this->db->join('offer_categories_translations', 'offer_categories.id = offer_categories_translations.offer_category_id');
		$this->db->where('offer_categories_translations.lang', $lang);
		$categories = $this->db->get()->result(get_called_class());
		$out = [null => 'Wybierz kategorie'];

        foreach ($categories as $category) {
            $out[$category->id] = $category->name;
        }
		return $out;
	}
}