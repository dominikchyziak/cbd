<?php

class ProductTranslationModel extends CI_Model
{
	public $id;
	public $product_id;
	public $lang;
	public $name;
        public $format;
        public $slogan;
	public $body;
	public $created_at;
	public $updated_at;
        public $meta_title;
        public $meta_description;
        public $custom_url;

	private $_table = 'products_translations';

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
			$this->product_id = $row->product_id;
			$this->lang = $row->lang;
			$this->name = $row->name;
                        $this->format = $row->format;
                        $this->slogan = $row->slogan;
			$this->body = $row->body;
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
		return $query->result('ProductTranslationModel');
	}

	public function insert()
	{
		$res = $this->db->insert($this->_table, [
			'product_id' => $this->product_id,
			'lang' => $this->lang,
			'name' => $this->name,
                        'format' => $this->format,
                        'slogan' => $this->slogan,
			'body' => $this->body,
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
                        'format' => $this->format,
                        'slogan' =>$this->slogan,
			'body' => $this->body,
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

	public function findByProductAndLang(ProductModel $product, $lang)
	{
		$this->db->where('product_id', $product->id);
		$this->db->where('lang', $lang);

		return $this->db->get($this->_table)->row(0, 'ProductTranslationModel');
	}
        

}
