<?php

class UserProductDiscount extends Backend_Controller
{
    private $discountModel;

    function __construct() {
        parent::__construct();
        $this->load->model('UserProductDiscountModel');
        $this->load->model('User_model');
        $this->load->model('ProductModel');
        $this->load->helper('form');
        $this->load->vars(['activePage' => 'userProductDiscount']);
        $this->discountModel = new UserProductDiscountModel();
        $this->load->vars(['activePage' => 'recruitment']);
    }

    //pobrac wszystkie znizki z bazy zrobic dodawanie nowej znizki pobrac tam produkty i uzytkownikow
    public function index()
    {
        $discounts = $this->discountModel->getPositions();
        $this->layout('duocms/upDiscount/index', [
            'discounts' => $discounts
        ]);
    }

    public function createEdit($id = null)
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $userId = (int)$this->input->post('user');
            $productId = (int)$this->input->post('product');
            $price = (int)$this->input->post('price');
            if (empty($productId) || empty($userId) || empty($price) || $price < 0) {
                $this->setError('Wystąpił błąd.');
                redirect('duocms/UserProductDiscount/create');
            }
            if (empty($id)) {
                if ($this->discountModel->checkDiscount($userId, $productId)) {
                    $this->setError('Taka cena specjalna już istnieje.');
                    redirect('duocms/UserProductDiscount/createEdit');
                }
                $this->discountModel->createDiscount($userId, $productId, $price);
                setAlert('success','Zniżka została dodana.');
                redirect(site_url('duocms/UserProductDiscount/index'));
            } else {
                $res = $this->discountModel->getIdByUserAndProduct($userId, $productId);
                if (!$res || $res == $id) {
                    $this->discountModel->updateDiscount($id, $userId, $productId, $price);
                    setAlert('success','Zniżka została zmodyfikowana.');
                    redirect(site_url('duocms/UserProductDiscount/index'));
                }
                $this->setError('Taka cena specjalna już istnieje.');
                redirect('duocms/UserProductDiscount/createEdit');
            }

        }
        if(!empty($id)){
            $this->discountModel->getById($id);
        }
        $productModel = new ProductModel();
        $users = $this->User_model->get_list();
        $products = $productModel->findAllForCmsList(1);
        $this->layout('duocms/upDiscount/form', [
            'users' => $users,
            'products' => $products,
            'discount' => $this->discountModel
        ]);
    }

    public function update($id)
    {

    }
    public function delete($id)
    {
        $discount = new UserProductDiscountModel();
        $discount->getById($id);

        $discount->deleteFromDb($id);

//        if ($res) {
//            setAlert('info','Produkt został uzunięty.');
//        } else {
//            setAlert('error','Wystąpił błąd.');
//        }

        redirect($this->input->server('HTTP_REFERER'));
    }
}