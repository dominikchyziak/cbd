<?php

function categoryHasChildren($category_id){
    $ci = &get_instance();
    $ci->load->model('OfferCategoryModel');
    $catObj = new OfferCategoryModel();
    return $catObj->has_children($category_id);
}

function countCategoryProducts($category_id){
    $ci = &get_instance();
    $ci->load->model('OfferCategoryModel');
    $catObj = new OfferCategoryModel();
    $products = array();
    $catObj->get_products_down($category_id, $products);
    return count($products);
}