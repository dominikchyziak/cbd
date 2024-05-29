<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {

    protected $_table_name = NULL;
    protected $_pk = 'id';

    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func_array(array($this, $name), $arguments);
        }

        $access_type = substr($name, 0, 3);
        $field = strtolower(
            ltrim(
                preg_replace('/([A-Z])/', strtolower('_$1'), str_replace($access_type, '', $name)),
                '_'
            )
        );

        switch ($access_type) {
            case 'get':
                return $this->$field;
            case 'set':
                $this->$field = array_shift($arguments);
                return $this;
            default:
                ob_end_clean();
                die("Metoda $name nie istnieje w klasie ".get_called_class());
        }
    }

    public static function factory($pk = NULL)
    {
        $className = get_called_class();
        $Class = new $className;

        if ($pk) {
            $res = $Class->findByPk($pk);

            if ($res) {
                $Class = $res;
            }
        }

        return $Class;
    }

    public function find()
    {
        $result = $this->db
            ->get($this->_table_name, 1)
            ->result(get_called_class());

        if ($result) {
            return $result[0];
        }

        return NULL;
    }

    public function findByPk($pk)
    {
        $this->db->where($this->_pk, $pk);

        return $this->find();
    }

    public function findAll()
    {
        return $this->db
            ->get($this->_table_name)
            ->result(get_called_class());
    }

    public function save($data)
    {
        if (isset($data[$this->_pk])) {
            if ($data[$this->_pk]) {
                return $this->update($data);
            }
        }

        return $this->insert($data);
    }

    public function update($data)
    {
        $pk = $data[$this->_pk];
        unset($data[$this->_pk]);
        $this->db->where($this->_pk, $pk);
        $res = $this->db->update($this->_table_name, $data);

        if ($res) {
            return $pk;
        }

        return FALSE;
    }

    public function insert($data)
    {
        $this->before_insert($data);
        $res = $this->db->insert($this->_table_name, $data);

        if ($res) {
            $data[$this->_pk] = $this->db->insert_id();
            $this->after_insert($data);
            return $data[$this->_pk];
        }

        return FALSE;
    }

    public function delete()
    {
        return $this->db->delete($this->_table_name, array(
            $this->_pk => $this->{$this->_pk}
        ));
    }

    public function deleteAll()
    {
        return $this->db->delete($this->_table_name);
    }

    public function getCount()
    {
        return $this->db->count_all_results($this->_table_name);
    }

    public function before_insert(array & $data) {}
    public function after_insert(array & $data) {}

    public function getList()
    {
        $res = $this->findAll();
        $ret = array();

        foreach ($res as $Item) {
            $ret[$Item->{$Item->_pk}] = $Item->name;
        }

        return $ret;
    }

    public function emptyTable()
    {
        return $this->db->truncate($this->_table_name);
    }

    public function compileWhere()
    {
        $where = implode("\n", $this->db->ar_where);
        $this->db->ar_where = array();

        if ($where) {
            $where = "WHERE $where";
        }

        return $where;
    }

    public function compileOrderby()
    {
        $orderby = '';

        if (count($this->db->ar_orderby) > 0) {
            $orderby .= "ORDER BY ";
            $orderby .= implode(', ', $this->db->ar_orderby);

            if ($this->db->ar_order !== FALSE) {
                $orderby .= ($this->db->ar_order == 'desc') ? ' DESC' : ' ASC';
            }
        }

        $this->db->ar_orderby = array();

        return $orderby;
    }

    public function compileLimit()
    {
        $limit = (int) $this->db->ar_limit;
        $offset = (int) $this->db->ar_offset;

        if ($offset == 0) {
            $offset = '';
        } else {
            $offset .= ", ";
        }

        return ($limit) ? "LIMIT ".$offset.$limit : '';
    }
    
    /**
     * Pobiera pole po identyfikatorze pola i identyfikatorze elementu
     * @param int $id id pola
     * @param int $element_id id elementu
     * @param string $lang
     * @return \CustomFieldsModel
     */
    public function getField($id, $element_id, $lang = 'pl'){
        $this->load->model('CustomFieldsModel');
        $cf_obj = new CustomFieldsModel();
        return $cf_obj->get_field($id, $element_id, $lang);
    }
    
}