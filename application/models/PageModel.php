<?php

class PageModel extends CI_Model
{
	public $id;
        public $category;
	public $created_at;
	public $updated_at;

	private $_table = 'pages';

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
                        $this->category = $row->category;
			$this->created_at = $row->created_at;
			$this->updated_at = $row->updated_at;
		}
	}
        
        public function add(){
            $this->db->insert($this->_table, array(
                "created_at" => date('Y-m-d H:i:s'),
                'category' => $this->category
            ));
            return $this->db->insert_id();
        }

	public function update()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->update($this->_table, [
                    'category' => $this->category,
			'updated_at' => (new DateTime())->format('Y-m-d H:i:s')
		]);

		return $res;
	}

	public function delete()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->delete($this->_table);

		return $res;
	}
        
        public function getByCategory($category){
            if(!empty($category)){
            $this->db->where('category', $category);
            }
            $this->db->where('lang','pl');
            $this->db->select($this->_table.'.*, pages_translations.title');
            $this->db->join('pages_translations', $this->_table.'.id = pages_translations.page_id');
            $q = $this->db->get($this->_table);
            return $q->result('PageModel');
        }

	public function getTranslation($lang)
	{
		$this->load->model('PageTranslationModel');

		return $this->PageTranslationModel->findByPageAndLang($this, $lang);
	}
        
        public function getPermalink(){
            $tpage = $this->getTranslation(LANG);
            
            if(empty($tpage->custom_url)){
                return site_url('page/'. getAlias($this->id, $tpage->title));
            } else {
                return site_url($tpage->custom_url);
            }
        }
}
