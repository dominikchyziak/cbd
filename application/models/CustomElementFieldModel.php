<?php

class CustomElementFieldModel extends CI_Model
{
	public $id;
	public $custom_element_id;
	public $title;
	public $type;
	public $value;
        public $lang;
	public $created_at;
	public $updated_at;

	private $table = 'custom_element_fields';

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
			$this->custom_element_id = $row->custom_element_id;
			$this->title = $row->title;
			$this->type = $row->type;
			$this->value = $row->value;
                        $this->lang = $row->lang;
			$this->created_at = $row->created_at;
			$this->updated_at = $row->updated_at;
		}
	}

	public function findAll()
	{
		$query = $this->db->get($this->table);
		return $query->result('CustomElementFieldModel');
	}

	public function insert()
	{
		$res = $this->db->insert($this->table, [
			'custom_element_id' => $this->custom_element_id,
			'title' => $this->title,
			'type' => $this->type,
			'value' => $this->value,
                        'lang' => $this->lang,
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
			'value' => $this->value,
			'updated_at' => (new DateTime())->format('Y-m-d H:i:s')
		]);

		return $res;
	}

	public function delete()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->delete($this->table);

		return $res;
	}

	public function findAllByCustomElement(CustomElementModel $element)
	{
		$this->db->where('custom_element_id', $element->id);
		return $this->findAll();
	}

	public function __toString()
	{
		return $this->value;
	}
}
