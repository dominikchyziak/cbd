<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GalleryModel extends MY_Model {

    protected $_table = 'galleries';
    public
            $id,
            $order,
            $category,
            $created_at,
            $updated_at;

    public function __construct($id = null) {
        parent::__construct();

        if (!is_null($id)) {
            $this->getById($id);
        }
    }

    public function delete() {
        $this->db->where('id', $this->id);
        $res = $this->db->delete($this->_table);

        $this->db->where('gallery_id', $this->id);
        $this->db->delete('duo_gallery_translations');
        
        $this->db->where('gallery_id', $this->id);
        $this->db->delete('duo_photos');
        
        if ($res) {
            $this->load->helper('file');

            $dir = FCPATH . 'uploads/galleries/' . $this->id;
            delete_files($dir, true);
            rmdir($dir);
        }

        return $res;
    }

    public function insert_gallery() {
        $res = $this->db->insert($this->_table, [
            'created_at' => (new DateTime())->format('Y-m-d H:i:s'),
            'order' => $this->order,
            'category' => $this->category
        ]);

        if ($res) {
            $this->id = $this->db->insert_id();
        }

        return $res;
    }

    public function update_gallery() {
        $this->db->where('id', $this->id);
        $res = $this->db->update($this->_table, [
            'updated_at' => (new DateTime())->format('Y-m-d H:i:s'),
            'order' => $this->order,
            'category' => $this->category
        ]);

        return $res;
    }

    public function getUrl($subdir = null) {
        $photos = $this->findAllPhotos();
        $photo = array_shift($photos);
        if(!empty($photo)){
            return $photo->getUrl($subdir);
        } else {
            return '';
        }
    }

    public function getPermalink() {   
        $id=$this->id;
        $tgal = $this->getTranslation(LANG);    
        if(empty($tgal->custom_url)){    
            return site_url('realizacje/' . getAlias($id, $tgal->name));
        } else {
            return site_url('realizacje/' . $tgal->custom_url);
        }
    }

    public function findAll() {
        $this->db->order_by('order', 'asc');
        $query = $this->db->get($this->_table);

        return $query->result('GalleryModel');
    }
        public function findAll2() {
        $this->db->where('category >= 0');
        $this->db->or_where('category IS NULL');
        $this->db->order_by('order', 'asc');
        $query = $this->db->get($this->_table);

        return $query->result('GalleryModel');
    }
    public function findAllCategory($category = 1) {
        $this->db->order_by('order', 'asc');
       // $this->db->where('category',$category); 
        $query = $this->db->get($this->_table);

        return $query->result('GalleryModel');
    }
    public function findAllCategory2($category = 1) {
        $this->db->order_by('order', 'asc');
        $this->db->where('category',$category); 
        $query = $this->db->get($this->_table);

        return $query->result('GalleryModel');
    }
    /**
     * Find all galleries with categories for CMS list.
     *
     * @return GalleryModel
     */
    public function findAllForcCmsList($category = 1) {
        $this->db->select('galleries.*, gallery_translations.name');
        $this->db->from($this->_table);
        $this->db->join('gallery_translations', 'galleries.id = gallery_translations.gallery_id');
        $this->db->where('gallery_translations.lang', 'pl');
        $this->db->where('galleries.category', $category);
        $this->db->order_by('galleries.order', 'asc');

        return $this->db->get()->result(get_called_class());
    }
    public function findAllForcCmsListAdmin() {
        $this->db->select('galleries.*, gallery_translations.name');
        $this->db->from($this->_table);
        $this->db->join('gallery_translations', 'galleries.id = gallery_translations.gallery_id');
        $this->db->where('gallery_translations.lang', 'pl');
        $this->db->order_by('galleries.order', 'asc');

        return $this->db->get()->result(get_called_class());
    }
    
    /*dodane 26.04.2019*/
    public function findAllForcCmsList1($lang) {
        $this->db->select('gallery_translations.*, galleries.*');
        $this->db->from($this->_table);
        $this->db->join('gallery_translations', 'galleries.id = gallery_translations.gallery_id');
        $this->db->where('gallery_translations.lang',  $lang);
        $this->db->where('galleries.category >= 0');
        $this->db->order_by('galleries.order', 'desc');

        return $this->db->count_all_results();
    }
    
    /*dodane 26.04.2019*/
    public function findAllForcCmsListAdmin1($lang, $limit = null, $offset = null) {
        $this->db->select('gallery_translations.*, galleries.*');
        $this->db->from($this->_table);
        $this->db->join('gallery_translations', 'galleries.id = gallery_translations.gallery_id');
        $this->db->where('gallery_translations.lang', $lang);
        $this->db->where('galleries.category >= 0');
        $this->db->order_by('galleries.order', 'desc');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result(get_called_class());
    }
  
    public function findAllPhotos() {
        $this->load->model('PhotoModel');
        return (new PhotoModel())->findAllByGallery($this);
    }

    public function findByLimit($limit = 8) {
        $this->db->limit($limit);
        $this->db->order_by('id', 'DESC');
        return $this->findAll();
    }

    public function getById($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->_table);

        foreach ($query->result() as $row) {
            $this->id = $row->id;
            $this->order = $row->order;
            $this->category = $row->category;
            $this->created_at = $row->created_at;
            $this->updated_at = $row->updated_at;
        }
    }

    public function getTranslation($lang) {
        $this->load->model('GalleryTranslationModel');

        return $this->GalleryTranslationModel->findByGalleryAndLang($this, $lang);
    }
    
    /**
     * Sortowanie galerii
     * @param int $item_id
     * @param int $position
     * @return bool
     */
    public function sort_item($item_id, $position) {
        $this->db->where('id', $item_id);
        $res = $this->db->update($this->_table, [
            'order' => $position
        ]);
        return $res;
    }

}
