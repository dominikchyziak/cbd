<?php

class PartnerModel extends CI_Model
{
	public $id;
	public $order;
	public $image;
        public $url;
	public $created_at;
	public $updated_at;

	private $_table = 'partnerzy';

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
			$this->order = $row->order;
			$this->image = $row->image;
                        $this->url = $row->url;
			$this->created_at = $row->created_at;
			$this->updated_at = $row->updated_at;
		}
	}

	public function findAll()
	{
		$this->db->order_by('order', 'asc');
		$query = $this->db->get($this->_table);
		return $query->result('PartnerModel');
	}

	public function insert()
	{
		$res = $this->db->insert($this->_table, [
			'order' => $this->order,
                        'url' => $this->url,
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

		$targetDir = FCPATH.'uploads/partner/'.$this->id;

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

			$img->best_fit(368, 86)->save($targetDir.'/mini/'.$data['file_name']);

			$this->image = $data['file_name'];
			return $this->update();
		}

		return false;
	}

	public function update()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->update($this->_table, [
			'image' => $this->image,
			'order' => $this->order,
                        'url' => $this->url,
			'updated_at' => (new DateTime())->format('Y-m-d H:i:s')
		]);

		return $res;
	}

	public function getUrl($subdir = null)
	{
		if (!is_null($subdir)) {
			$subdir .= '/';
		}

		return base_url('uploads/partner/'.$this->id.'/'.$subdir.$this->image);
	}

	public function delete()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->delete($this->_table);

		if ($res) {
			$this->load->helper('file');

			$dir = FCPATH.'uploads/partner/'.$this->id;
			delete_files($dir, true);
			rmdir($dir);
		}

		return $res;
	}
}
