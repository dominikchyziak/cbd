<?php

class WizerunekModel extends MY_Model
{
	public $id;
	public $order;
	public $image;
        public $translation;
	public $created_at;
	public $updated_at;

	private $_table = 'wizerunki';

	public function __construct($id = null)
	{
		parent::__construct();
                $this->load->model('WizerunekTranslationModel');
		if (!is_null($id)) {
			$this->getById($id);
		}
	}

        /**
         * 
         * @param int $id identyfikator konkretnego wizerunku
         * @param string $lang skrót języka, domyślnie pl
         * @return $this zwraca uzupełniony obiekt
         */
	public function getById($id, $lang = 'pl')
	{
		$this->db->where('id', $id);
		$query = $this->db->get($this->_table);

		foreach ($query->result() as $row) {
			$this->id = $row->id;
			$this->order = $row->order;
			$this->image = $row->image;
			$this->created_at = $row->created_at;
			$this->updated_at = $row->updated_at;
		}
                $translation_obj = new WizerunekTranslationModel();
                $this->translation = $translation_obj->findByWizerunekAndLang($this, $lang);
                return $this;
	}

        /**
         * Zwraca wszystkie wizerunki obiektami klasy wizerunku
         * @return array
         */
	public function findAll()
	{
		$this->db->order_by('order', 'asc');
		$query = $this->db->get($this->_table);
		return $query->result('WizerunekModel');
	}

	public function insert_wizerunek()
	{
		$res = $this->db->insert($this->_table, [
			'order' => $this->order,
			'created_at' => (new DateTime())->format('Y-m-d H:i:s')
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

		$targetDir = FCPATH.'uploads/wizerunek/'.$this->id;

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

			$img->adaptive_resize(796, 497)->save($targetDir.'/mini/'.$data['file_name']);

			$this->image = $data['file_name'];
			return $this->update_wizerunek();
		}

		return false;
	}

	public function update_wizerunek()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->update($this->_table, [
			'image' => $this->image,
			'order' => $this->order,
			'updated_at' => (new DateTime())->format('Y-m-d H:i:s')
		]);

		return $res;
	}

	public function getUrl($subdir = null)
	{
		if (!is_null($subdir)) {
			$subdir .= '/';
		}

		return base_url('uploads/wizerunek/'.$this->id.'/'.$subdir.$this->image);
	}

	public function delete()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->delete($this->_table);

		if ($res) {
			$this->load->helper('file');

			$dir = FCPATH.'uploads/wizerunek/'.$this->id;
			delete_files($dir, true);
			rmdir($dir);
		}

		return $res;
	}

	public function getTranslation($lang)
	{
		return $this->WizerunekTranslationModel->findByWizerunekAndLang($this, $lang);
	}
}
