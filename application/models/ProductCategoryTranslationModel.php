<?php

class ProductCategoryTranslationModel extends CI_Model
{
	public $id;
	public $name;
	public $category_id;
	private $_table = 'offer_categories_translations';
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
			$this->name = $row->name;
			$this->category_id = $row->category_id;
		}
	}
	public function insert()
	{
		$res = $this->db->insert($this->_table, [
			'name' => $this->name,
			'category_id' => $this->category_id,
			'lang' => $this->lang
		]);
		if ($res) {
			$this->id = $this->db->insert_id();
		}
		return $res;
	}
	public function findByCategoryAndLang(ProductCategoryModel $category, $lang)
	{
		$this->db->where('category_id', $category->id);
		$this->db->where('lang', $lang);

		return $this->db->get($this->_table)->row(0, 'ProductCategoryTranslationModel');
	}
	public function update()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->update($this->_table, [
			'name' => $this->name,
			'lang' => $this->lang
		]);

		return $res;
	}
}
