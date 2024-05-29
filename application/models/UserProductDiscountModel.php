<?php


class UserProductDiscountModel extends MY_Model
{
    protected $_table_name = "duo_user_product_discount";

    private $id;

    private $userId;

    private $productId;

    private $price;

    private $createdAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param mixed $productId
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getById($id)
    {
//        $id = filter_input('int', $id);
        $res = $this->db->get_where($this->_table_name, array('id' => $id))->result();;
        $this->id = $res[0]->id;
        $this->userId = $res[0]->user;
        $this->productId = $res[0]->product;
        $this->price = $res[0]->price;
        $this->createdAt = $res[0]->created_at;
    }

    public function deleteFromDb($id)
    {
//        $id = filter_input('int', $id);
        $this->db->delete($this->_table_name, array('id' => $id));
    }

    public function createDiscount($userId, $productId, $price)
    {
//        $userId = filter_input('int' , $userId);
//        $productId = filter_input('int', $productId);
//        $price = filter_input('int', $price);
        $this->db->insert($this->_table_name, array('user' => $userId, 'product' => $productId, 'price' => $price));

        return $this->db->insert_id();
    }

    public function updateDiscount($id, $userId, $productId, $price)
    {
        $this->db->where('id', $id)
            ->update($this->_table_name, array('user' => $userId, 'product' => $productId, 'price' => $price));
    }

    public function checkDiscount($userId, $productId)
    {
        $res = $this->db->get_where($this->_table_name, array('user' => $userId, 'product' => $productId))->result();
        if (!empty($res[0])) {
            return $res[0]->price;
        }
        return false;
    }

    public function getIdByUserAndProduct($userId, $productId) {
        $res = $this->db->get_where($this->_table_name, array('user' => $userId, 'product' => $productId))->result();
        if (!empty($res[0])) {
            return $res[0]->id;
        }
        return false;
    }


    public function getPositions(){
        $res = $this->db->get($this->_table_name);
        return $res->result();
    }
}