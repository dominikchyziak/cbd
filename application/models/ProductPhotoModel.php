<?php

class ProductPhotoModel extends CI_Model
{
	public $id;
	public $product_id;
	public $order;
	public $name;
	public $created_at;
	public $updated_at;

	public function __construct($id = null)
	{
		parent::__construct();

		if (!is_null($id)) {
			$this->getById($id);
		}
	}

	public function insert()
	{
		$res = $this->db->insert('product_photos', [
			'product_id' => $this->product_id,
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
		$res = $this->db->update('product_photos', [
			'order' => $this->order,
			'name' => $this->name,
			'updated_at' => (new DateTime())->format('Y-m-d H:i:s')
		]);

		return $res;
	}

	public function getById($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('product_photos');

		foreach ($query->result() as $row) {
			$this->id = $row->id;
			$this->product_id = $row->product_id;
			$this->order = $row->order;
			$this->name = $row->name;
			$this->created_at = $row->created_at;
			$this->updated_at = $row->updated_at;
		}
	}

	public function findAll()
	{
		$this->db->order_by('order', 'asc');
		$query = $this->db->get('product_photos');
		return $query->result('ProductPhotoModel');
	}

	public function delete()
	{
		$this->db->where('id', $this->id);
		return $this->db->delete('product_photos');
	}

//        public function redoImage(){
//            if(!file_exists(FCPATH.'uploads/products/'.$this->product_id.'/'.$this->id.'/'.$this->name)){
//                echo 'brak pliku<br>';
//                return 0;
//            }
//            $targetDir = FCPATH.'uploads/products/'.$this->product_id.'/'.$this->id;
//            require_once APPPATH.'third_party/SimpleImage.php';
//
//            $img = new abeautifulsite\SimpleImage($targetDir.'/'.$this->name);
//            $sizes = getimagesize($targetDir.'/'.$this->name);
//                        $x = $sizes[0];
//                        $y = $sizes[1];
//                        if($x > 700){
//                            $y = round((700/$x)*$y);
//                            $x = 700;
//                        }
//
//            $img->adaptive_resize($x, $y)->save($targetDir.'/mini/'.$this->name);
//            
//        }
        
	public function saveImage($file)
	{
		$targetDir = FCPATH.'uploads/products/'.$this->product_id.'/'.$this->id;

		if (!is_dir($targetDir)) {
			mkdir($targetDir, 0777, true);
		}

		$config = [
			'upload_path' => $targetDir,
			'allowed_types' => '*',
			'encrypt_name' => true
		];

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('file')) {
			$data = $this->upload->data();

			require_once APPPATH.'third_party/SimpleImage.php';

			$img = new abeautifulsite\SimpleImage($data['full_path']);

			//$img->best_fit(1920, 1080)->save($data['full_path']);

			if (!is_dir($targetDir.'/mini')) {
				mkdir($targetDir.'/mini', 0777, true);
			}

			$sizes = getimagesize($data['full_path']);
                        $x = $sizes[0];
                        $y = $sizes[1];
                        if($x > 700){
                            $y = round((700/$x)*$y);
                            $x = 700;
                        }

			$img->adaptive_resize($x, $y)->save($targetDir.'/mini/'.$data['file_name']);

			$this->name = $data['file_name'];
			return $this->update();
		}

		return false;
	}

        public function save_from_remote_url($url){
            
                  $targetDir = FCPATH.'uploads/products/'.$this->product_id.'/'.$this->id;

		if (!is_dir($targetDir)) {
			mkdir($targetDir, 0777, true);
		}
                
                require_once APPPATH.'third_party/SimpleImage.php';
                        $name = substr(md5($url),10) .'.jpg';
                        try{
                        $this->downloadFile($url, $targetDir.'/'.$name);
                        
			$img = new abeautifulsite\SimpleImage($targetDir.'/'.$name);

			//$img->best_fit(1920, 1080)->save($data['full_path']);

			if (!is_dir($targetDir.'/mini')) {
				mkdir($targetDir.'/mini', 0777, true);
			}

			$sizes = getimagesize($url);
                        $x = $sizes[0];
                        $y = $sizes[1];
                        if($x > 400){
                            $y = round((400/$x)*$y);
                            $x = 400;
                        }
			$img->adaptive_resize($x, $y)->save($targetDir.'/mini/'.$name);
                        
			$this->name = $name;
			return $this->update();
                        } catch (Exception $error_file_download) {
                            $this->delete();
                        }
        }
        
private function downloadFile($url, $path)
{
    $newfname = $path;
    $file = @fopen ($url, 'rb');
    if ($file) {
        $newf = fopen ($newfname, 'wb');
        if ($newf) {
            while(!feof($file)) {
                fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
            }
        }
    }
    if ($file) {
        fclose($file);
    }
    if ($newf) {
        fclose($newf);
    }
}
	public function getUrl($subdir = null)
	{
		if (!is_null($subdir)) {
			$subdir .= '/';
		}

		return base_url('uploads/products/'.$this->product_id.'/'.$this->id.'/'.$subdir.$this->name);
	}

	public function findAllByProduct(ProductModel $product)
	{
		$this->db->where('product_id', $product->id);
		return $this->findAll();
	}
        
        //opisy do zdjęć
        public function update_description($photo_id, $description, $lang = "pl"){
            $q = $this->db->get_where("duo_photos_translations",array("lang" => $lang, "photo_id" => $photo_id));
            if($q->num_rows()){
                //update
                return $this->db->where(array("photo_id"=>$photo_id, "lang"=> $lang))
                        ->update("duo_photos_translations", array( "description" => $description));
            } else {
                //insert
                return $this->db->insert("duo_photos_translations", array("lang" => $lang, "photo_id" => $photo_id, "description" => $description));
            }
        }
        public function get_description($photo_id, $lang = LANG ){
            $q = $this->db->get_where("duo_photos_translations",array("lang" => $lang, "photo_id" => $photo_id));
            return $q->row();
        }
}
