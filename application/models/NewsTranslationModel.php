<?php

class NewsTranslationModel extends CI_Model
{
	public $id;
	public $news_id;
	public $lang;
	public $title;
	public $excerpt;
	public $body;
	public $created_at;
	public $updated_at;
	public $image;
        public $meta_title;
        public $meta_description;
        public $custom_url;

	private $_table = 'news_translations';

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
			$this->news_id = $row->news_id;
			$this->lang = $row->lang;
			$this->title = $row->title;
			$this->excerpt = $row->excerpt;
			$this->body = $row->body;
			$this->created_at = $row->created_at;
			$this->updated_at = $row->updated_at;
			$this->image = $row->image;
                        $this->meta_title = $row->meta_title;
                        $this->meta_description = $row->meta_description;
                        $this->custom_url = $row->custom_url;
		}
	}

	public function findAll()
	{
		$query = $this->db->get($this->_table);
		return $query->result('NewsTranslationModel');
	}

	public function insert()
	{
		$res = $this->db->insert($this->_table, [
			'news_id' => $this->news_id,
			'lang' => $this->lang,
			'title' => $this->title,
			'excerpt' => $this->excerpt,
			'body' => $this->body,
			'image' => $this->image,
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
			'title' => $this->title,
			'excerpt' => $this->excerpt,
			'body' => $this->body,
			'image' => $this->image,
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

	public function findByNewsAndLang(NewsModel $news, $lang)
	{
		$this->db->where('news_id', $news->id);
		$this->db->where('lang', $lang);

		return $this->db->get($this->_table)->row(0, 'NewsTranslationModel');
	}
}
