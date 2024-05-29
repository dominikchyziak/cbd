<?php

class WizerunekTranslationModel extends MY_Model
{
	public $id;
	public $wizerunek_id;
	public $lang;
	public $title;
	public $body;
	public $href;
        public $color1;
        public $color2;
	public $created_at;
	public $updated_at;

	private $_table = 'wizerunki_translations';

	public function __construct($id = null)
	{
		parent::__construct();

		if (!is_null($id)) {
			$this->getById($id);
		}
	}

        /**
         * Wczytuje konkretne tłumaczenie po identyfikatorze
         * @param int $id identyfikator konkretnego tłumaczenia
         */
	public function getById($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get($this->_table);

		foreach ($query->result() as $row) {
			$this->id = $row->id;
			$this->wizerunek_id = $row->wizerunek_id;
			$this->lang = $row->lang;
			$this->title = $row->title;
			$this->body = $row->body;
			$this->href = $row->href;
                        $this->color1 = $row->color1;
                        $this->color2 = $row->color2;
			$this->created_at = $row->created_at;
			$this->updated_at = $row->updated_at;
		}
	}

        /**
         * 
         * @return obj zwraca obiekt WizerunekTranslationModel
         */
	public function findAll()
	{
		$query = $this->db->get($this->_table);
		return $query->result('WizerunekTranslationModel');
	}

	public function insert_trans()
	{
		$res = $this->db->insert($this->_table, [
			'wizerunek_id' => $this->wizerunek_id,
			'lang' => $this->lang,
			'title' => $this->title,
			'body' => $this->body,
			'href' => $this->href,
                        'color1' => $this->color1,
                        'color2' => $this->color2,
			'created_at' => (new DateTime())->format('Y-m-d H:i:s')
		]);

		if ($res) {
			$this->id = $this->db->insert_id();
		}

		return $res;
	}

	public function update_trans()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->update($this->_table, [
			'title' => $this->title,
			'body' => $this->body,
			'href' => $this->href,
                        'color1' => $this->color1,
                        'color2' => $this->color2,
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

	public function findByWizerunekAndLang(WizerunekModel $wizerunek, $lang)
	{
		$this->db->where('wizerunek_id', $wizerunek->id);
		$this->db->where('lang', $lang);

		return $this->db->get($this->_table)->row(0, 'WizerunekTranslationModel');
	}
}
