<?php

class MapPointModel extends CI_Model
{
	public $id;
	public $lat;
	public $lng;
        public $name;
        public $address;
        public $icon_marker = '';
        public $gallery_id;

        private $table = 'duo_map_points';

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
			$this->lat = $row->lat;
                        $this->lng = $row->lng;
                        $this->name = $row->name;
                        $this->address = $row->address;
                        $this->icon_marker = $row->icon_marker;
                        $this->gallery_id = $row->gallery_id;
		}
	}
        
        public function add(){
            $this->db->insert($this->table, array(
                "lat" => $this->lat,
                "lng" => $this->lng,
                "name" => $this->name,
                "address" => $this->address,
                "icon_marker" => $this->icon_marker,
                "gallery_id" => $this->gallery_id
                ));
            return $this->db->insert_id();
        }

	public function update()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->update($this->table, [
                        "lat" => $this->lat,
                        "lng" => $this->lng,
                        "name" => $this->name,
                        "address" => $this->address,
                        "icon_marker" => $this->icon_marker,
                        "gallery_id" => $this->gallery_id
		]);

		return $res;
	}

	public function delete()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->delete($this->table);

		return $res;
	}
        
        public function getAllMapPoints(){
            $q = $this->db->get($this->table);
            return $q->result();
        }
        
        public function findByGallery($gallery_id){
            $this->db->where('gallery_id', $gallery_id);
            $q = $this->db->get($this->table);
            return $q->row();
        }
}
