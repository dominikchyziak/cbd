<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {

    public function get_product()
    {

        if ($this->input->method(true) !== 'POST') {
            show_404();
        }
        

        $this->load->model('ProductModel');
        $this->load->model('ProductTranslationModel');

        $post = $this->input->post();

        $code_part = $post['code_part'];
        $canvas = $post['canvas'];
        $werniks = $post['werniks'];
        $realization_time = $post['realization_time'];

        $code = $code_part . '-' . $canvas . '-' . $werniks . '-' . $realization_time;
        
        $product_object = new ProductModel();
        $code_data = explode('_',$code_part);

        if($code_data[0] == "PROD"){
            $product_object->getById($code_data[1]);
            $product_object->options_object = $product_object->get_options($product_object->id);
            $option = $product_object->select_option($product_object->options_object[0]->id);
        } else {
            $option = $product_object->select_option($code_data[1]);
            $product_object->getById($option['product_id']);
            $product_object->options_object = $product_object->get_options($product_object->id);
        }

        $product_data = false;
        $translation = (new ProductTranslationModel())->findByProductAndLang($product_object, 'pl');
        $size = explode('x', $option['name']);
        $attributes = array();
        if($canvas == 'BAW'){
            $attributes[] = 9;
        }
        if($werniks == 'WET'){
            $attributes[] = 10;
        }
        if($realization_time == '24h'){
            $attributes[] = 11;
        }
        $res = $product_object->atributes_get_change($attributes);
        
        if (!empty($product_object)) {
            
            $product_data = array(
                'id' => $product_object->id,
                'width' => $size[0],
                'height' => $size[1],
                'old_price' => $option['old_price'] * (1+($res/100)),
                'price' => $option['product_price'] * (1+($res/100)),
                'description' => $translation->body,
                'canvas' => false,
                'varnish' => false,
            );
        }
        
        echo json_encode(array(
            'status' => 'OK',
            'name' => $translation->name,
            'format' => $translation->format,
            'format_cp1' => explode('_', $code_part)[0],
            'product' => $product_data,
        ));
    }
}
