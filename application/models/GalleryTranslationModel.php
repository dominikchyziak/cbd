<?php

class GalleryTranslationModel extends CI_Model
{
	public $id;
	public $gallery_id;
	public $lang;
	public $name;
        public $description;
	public $created_at;
	public $updated_at;
        public $meta_title;
        public $meta_description;
        public $custom_url;

	private $_table = 'gallery_translations';

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
			$this->gallery_id = $row->gallery_id;
			$this->lang = $row->lang;
			$this->name = $row->name;
                        $this->description = $row->description;
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
		return $query->result('GalleryTranslationModel');
	}

	public function insert()
	{
		$res = $this->db->insert($this->_table, [
			'gallery_id' => $this->gallery_id,
			'lang' => $this->lang,
			'name' => $this->name,
                        'description' => $this->description,
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
                        'description' => $this->description,
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

	public function findByGalleryAndLang(GalleryModel $gallery, $lang)
	{
		$this->db->where('gallery_id', $gallery->id);
		$this->db->where('lang', $lang);

		return $this->db->get($this->_table)->row(0, 'GalleryTranslationModel');
	}
}
