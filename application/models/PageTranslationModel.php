<?php

class PageTranslationModel extends CI_Model
{
	public $id;
	public $page_id;
	public $lang;
	public $title;
	public $body;
	public $created_at;
	public $updated_at;
        public $meta_title;
        public $meta_description;
        public $custom_url;

	private $_table = 'pages_translations';

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
			$this->page_id = $row->page_id;
			$this->lang = $row->lang;
			$this->title = $row->title;
			$this->body = $row->body;
			$this->created_at = $row->created_at;
			$this->updated_at = $row->updated_at;
                        $this->meta_title = $row->meta_title;
                        $this->meta_description = $row->meta_description;
                        $this->custom_url = $row->custom_url;
		}
	}
        
        public function getByLang($lang){
                $query = $this->db->where('lang',$lang)->get($this->_table);
		return $query->result('PageTranslationModel');
        }

        public function findAll()
	{
		$query = $this->db->get($this->_table);
		return $query->result('PageTranslationModel');
	}
        
        public function insert(){
            $res = $this->db->insert($this->_table,[
                'page_id' => $this->page_id,
                'title' => $this->title,
                'body' => $this->body,
                'lang' => $this->lang,
                'created_at' => (new DateTime())->format('Y-m-d H:i:s'),
                'updated_at' => (new DateTime())->format('Y-m-d H:i:s'),
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'custom_url' => $this->custom_url
            ]);
            
            return $res;
        }
        
        public function update()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->update($this->_table, [
			'title' => $this->title,
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

	public function findByPageAndLang(PageModel $page, $lang)
	{
		$this->db->where('page_id', $page->id);
		$this->db->where('lang', $lang);

		return $this->db->get($this->_table)->row(0, 'PageTranslationModel');
	}
}
