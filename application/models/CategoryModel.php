<?php

class CategoryModel extends CI_Model
{
    public $id;
    public $parent_id;
    public $order;
    public $image;
    public $created_at;
    public $updated_at;

    private $_table = 'categories';

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
            $this->parent_id = $row->parent_id;
            $this->order = $row->order;
            $this->image = $row->image;
            $this->created_at = $row->created_at;
            $this->updated_at = $row->updated_at;
        }
    }

    public function findAll()
    {
        $this->db->order_by('order', 'asc');
        $query = $this->db->get($this->_table);
        return $query->result(get_called_class());
    }

    public function insert()
    {
        $res = $this->db->insert($this->_table, [
            'parent_id' => $this->parent_id,
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

        $targetDir = FCPATH.'uploads/categories/'.$this->id;

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

            $img->adaptive_resize(800, 500)->save($targetDir.'/mini/'.$data['file_name']);

            $this->image = $data['file_name'];
            return $this->update();
        }

        return false;
    }

    public function update()
    {
        $this->db->where('id', $this->id);
        $res = $this->db->update($this->_table, [
            'parent_id' => $this->parent_id ? : null,
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

        return base_url('uploads/categories/'.$this->id.'/'.$subdir.$this->image);
    }

    public function delete()
    {
        $this->db->where('id', $this->id);
        $res = $this->db->delete($this->_table);

        if ($res) {
            $this->load->helper('file');

            $dir = FCPATH.'uploads/categories/'.$this->id;
            delete_files($dir, true);
            rmdir($dir);
        }

        return $res;
    }

    public function getListForDropdown(CategoryModel $category = null)
    {
        $this->db->where('parent_id', null);

        if (!is_null($category)) {
            $this->db->where('categories.id <>', $category->id);
        }

        $this->db->select('categories.id, category_translations.name');
        $this->db->from($this->_table);
        $this->db->join('category_translations', 'categories.id = category_translations.category_id');
        $this->db->where('category_translations.lang', 'pl');
        $categories = $this->db->get()->result(get_called_class());
        $out = [null => 'brak'];

        foreach ($categories as $category) {
            $out[$category->id] = $category->name;
        }

        return $out;
    }

    public function getListForGalleryDropdown()
    {
        $this->db->select('categories.id, categories.parent_id, category_translations.name');
        $this->db->from($this->_table);
        $this->db->join('category_translations', 'categories.id = category_translations.category_id');
        $this->db->where('category_translations.lang', 'pl');
        $result = $this->db->get()->result(get_called_class());
        $categories = [];
        $out = [null => 'brak'];

        foreach ($result as $category) {
            $categories[$category->id] = $category;
        }

        foreach ($categories as $category) {
            if (isset($categories[$category->parent_id])) {
                if (isset($categories[$category->parent_id]->children)) {
                    $categories[$category->parent_id]->children[] = $category;
                } else {
                    $categories[$category->parent_id]->children = [$category];
                }
            }
        }

        foreach ($categories as $category) {
            if (!$category->parent_id) {
                $out[$category->id] = $category->name;

                if (isset($category->children)) {
                    foreach ($category->children as $category) {
                        $out[$category->id] = '&mdash; '.$category->name;
                    }
                }
            }
        }

        return $out;
    }

    public function findAllByParent(CategoryModel $category = null)
    {
        if (!is_null($category)) {
            $this->db->where('parent_id', $category->id);
        } else {
            $this->db->where('parent_id', null);
        }

        return $this->findAll();
    }

    public function getTranslation($lang)
    {
        $this->load->model('CategoryTranslationModel');

        return $this->CategoryTranslationModel->findByCategoryAndLang($this, $lang);
    }

    public function findAllForHome($offset, $limit)
    {
        $this->db->select('c.*, ct.name, ct.excerpt');
        $this->db->from('categories c');
        $this->db->join('category_translations ct', 'ct.category_id = c.id');
        $this->db->where('ct.lang', LANG);
        $this->db->where('c.parent_id', null);
        $this->db->order_by('c.order', 'ASC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result(get_called_class());
    }

    public function getPermalink()
    {
        return site_url('oferty-lokalowe/'.getAlias($this->id, $this->getTranslation(LANG)->name));
    }
}
