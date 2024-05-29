<?php

class OfferCategoryTranslationModel extends CI_Model
{
	public $id;
	public $offer_category_id;
	public $lang;
	public $name;
        public $slogan;
	public $body;
        public $body1;
	public $created_at;
	public $updated_at;
        public $meta_title;
        public $meta_description;
        public $custom_url;

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
			$this->offer_category_id = $row->offer_category_id;
			$this->lang = $row->lang;
			$this->name = $row->name;
                        $this->slogan = $row->slogan;
			$this->body = $row->body;
                        $this->body1 = $row->body1;
			$this->created_at = $row->created_at;
			$this->updated_at = $row->updated_at;
                        $this->meta_title = $row->meta_title;
                        $this->meta_description = $row->meta_description;
                        $this->custom_url = $row->custom_url;
		}
	}

	public function findAll()
	{
		$query = $this->db->get($this->_table);
		return $query->result('OfferCategoryTranslationModel');
	}

	public function insert()
	{
		$res = $this->db->insert($this->_table, [
			'offer_category_id' => $this->offer_category_id,
			'lang' => $this->lang,
			'name' => $this->name,
                        'slogan' => $this->slogan,
			'body' => $this->body,
                        'body1' => $this->body1,
			'created_at' => (new DateTime())->format('Y-m-d H:i:s'),
                        'meta_title' => $this->meta_title,
                        'meta_description' => $this->meta_description,
                        'custom_url' => $this->custom_url
		]);

		if ($res) {
			$this->id = $this->db->insert_id();
		}

		return $res;
	}

	public function update()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->update($this->_table, [
			'name' => $this->name,
                        'slogan' => $this->slogan,
			'body' => $this->body,
                        'body1' => $this->body1,
			'updated_at' => (new DateTime())->format('Y-m-d H:i:s'),
                        'meta_title' => $this->meta_title,
                        'meta_description' => $this->meta_description,
                        'custom_url' => $this->custom_url
		]);

		return $res;
	}

	public function delete()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->delete($this->_table);

		return $res;
	}

	public function findByCategoryAndLang(OfferCategoryModel $category, $lang)
	{
		$this->db->where('offer_category_id', $category->id);
		$this->db->where('lang', $lang);

		return $this->db->get($this->_table)->row(0, 'OfferCategoryTranslationModel');
	}
}
