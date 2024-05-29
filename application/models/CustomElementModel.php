<?php

class CustomElementModel extends CI_Model
{
	public $id;
	public $name;
	public $created_at;
	public $updated_at;

	private $table = 'custom_elements';

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
		$query = $this->db->get($this->table);

		foreach ($query->result() as $row) {
			$this->id = $row->id;
			$this->name = $row->name;
			$this->created_at = $row->created_at;
			$this->updated_at = $row->updated_at;
		}
	}

	public function findAll()
	{
		$query = $this->db->get($this->table);
		return $query->result('CustomElementModel');
	}

	public function insert()
	{
		$res = $this->db->insert($this->table, [
			'name' => $this->name,
			'created_at' => (new DateTime())->format('Y-m-d H:i:s')
		]);

		if ($res) {
			$this->id = $this->db->insert_id();
		}

		return $res;
	}

	public function update()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->update($this->table, [
			'name' => $this->name,
			'updated_at' => (new DateTime())->format('Y-m-d H:i:s')
		]);

		return $res;
	}

	public function delete()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->delete($this->table);

		if ($res) {
			$this->load->helper('file');

			$dir = FCPATH.'uploads/custom_elements/'.$this->id;
			delete_files($dir, true);
			rmdir($dir);
		}

		return $res;
	}

	public function getFields($lang = null)
	{
		$this->load->model('CustomElementFieldModel');
		$fields = (new CustomElementFieldModel())->findAllByCustomElement($this);
		$out = [];

		foreach ($fields as $field) {
			$out[$field->lang][] = $field;
		}

		if ($lang) {
			return $out[$lang];
		}

		return $out;
	}

	public function getField($title, $lang = null)
	{
		if (is_null($lang)) {
                    if(!defined('LANG')){
                        define("LANG", "pl");
                    }
			$lang = LANG;
		}

		if (!isset($this->fields)) {
			$this->fields = [];
			$fields = $this->getFields($lang);

			foreach ($fields as $field) {
				$this->fields[$field->title] = $field;
			}
		}

		foreach ($this->fields as $fieldTitle => $field) {
			if ($fieldTitle === $title) {
				return $field;
			}
		}
	}
}
