<?php


class ProductPackModel extends MY_Model{
    
    public $id;
    public $pack_id;
    public $product_id;

    
    public function get_products_by_pack_id($pack_id){
        $this->db->select('product_id');
        $this->db->where('pack_id', $pack_id);
        $q = $this->db->get('duo_shop_product_pack');
        $products = array();
        if($q->num_rows()>0){
            foreach ($q->result() as $row){
                $products[] = new ProductModel($row->product_id);
            }
        } else {
            $products = -1;
        }
        return $products;
    }
    
    public function count_products_in_pack($pack_id){
        $this->db->select('product_id');
        $this->db->where('pack_id', $pack_id);
        $q = $this->db->get('duo_shop_product_pack');
        return $q->num_rows();
    }
    
    public function get_prod_ids_by_pack_id($pack_id){
        $this->db->select('product_id');
        $this->db->where('pack_id', $pack_id);
        $q = $this->db->get('duo_shop_product_pack');
        $res = null;
        foreach($q->result() as $row){
            $res[] = $row->product_id;
        }
        return $res;
    }
    
    public function get_pack_id_by_product_id($product_id){
        $this->db->select('pack_id');
        $this->db->where('product_id', $product_id);
        $q = $this->db->get('duo_shop_product_pack');
        if($q->num_rows() > 0){
            return $q->result()[0]->pack_id;
        } else {
            return -1;
        }
    }
    
    public function check_if_product_in_pack($product_id, $pack_id){
        $this->db->where('product_id', $product_id);
        $this->db->where('pack_id', $pack_id);
        $q = $this->db->get('duo_shop_product_pack');
        if( $q->num_rows() > 0){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function get_all_attribute_value_of_pack($pack_id, $attribute_group_id){
        $products = $this->get_products_by_pack_id($pack_id);
        $all_atr = array();
        foreach ($products as $product){
            $attributes = $product->attribute_get_list_for_product($product->id);
            foreach ($attributes as $attribute) {
                if($attribute->attributes_group_id == $attribute_group_id) {
                    $all_atr[$attribute->attribute_id] = $attribute->name;
                }
            }
        }
        return $all_atr;
    }
    
    public function check_product_is_in_pack($product_id, $pack_id){
        $this->db->where('product_id', $product_id);
        $this->db->where('pack_id', $pack_id);
        $q = $this->db->get('duo_shop_product_pack');
        if($q->num_rows() > 0){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function add_product_to_pack($product_id, $pack_id){
        $data = array(
            'product_id' => $product_id,
            'pack_id' => $pack_id
        );
        $this->db->insert('duo_shop_product_pack', $data);
    }
    
    public function delete_product_from_pack($product_id, $pack_id){
        $this->db->where('product_id', $product_id);
        $this->db->where('pack_id', $pack_id);
        $this->db->delete('duo_shop_product_pack');
    }


    public function add_pack($name, $allegro_id = -1){
        $data = array(
            'name' => $name,
            'allegro_uid' => $allegro_id,
            'attr_grp_1_id' => 105,
            'attr_grp_2_id' => 106
        );
        $res = $this->db->insert('duo_shop_product_pack_main', $data);
        return $res;
    }
    
    public function update_pack($args){
        $data = array(
            'name' => $args['name'],
            'attr_grp_1_id' => $args['attr_grp_1_id'],
            'attr_grp_2_id' => $args['attr_grp_2_id']
        );
        $this->db->where('id', $args['id']);
        $this->db->update('duo_shop_product_pack_main', $data); 
    }
    
    public function get_all_packs(){
        $q = $this->db->get('duo_shop_product_pack_main');
        return $q->result();
    }
    
    public function get_pack($pack_id){
        $this->db->where('id', $pack_id);
        $q = $this->db->get('duo_shop_product_pack_main');
        if($q->num_rows() > 0){
            return $q->result()[0];
        } else {
            return -1;
        }
    }
    
    public function delete_pack($pack_id){
        $this->db->where('id', $pack_id);
        $q = $this->db->delete('duo_shop_product_pack_main');
        return $q;
    }
    
    
    public function get_attribute_data_for_product_page($product_id){
        $pack_id = $this->get_pack_id_by_product_id($product_id);
        $products = $this->get_products_by_pack_id($pack_id);
        $pack = $this->get_pack($pack_id);
        $all_atr = array();
        if($pack->attr_grp_1_id != 0 && $pack->attr_grp_2_id != 0){
            foreach ($products as $product){
                $attributes = $product->attribute_get_list_for_product($product->id);
                foreach ($attributes as $attribute) {
                    if($attribute->attributes_group_id == $pack->attr_grp_1_id) {
                        $all_atr[1][$attribute->attribute_id][] = array( 'product' => $product, 'attribute' => $attribute);
                    }
                    if($attribute->attributes_group_id == $pack->attr_grp_2_id) {
                        $all_atr[2][$attribute->attribute_id][] = array( 'product' => $product, 'attribute' => $attribute);
                    }
                }
                $all_atr['data'] = '2attr';
            }
            uasort($all_atr[1], array( $this, 'attr_array_sort'));
            uasort($all_atr[2], array( $this, 'attr_array_sort2'));
        }elseif($pack->attr_grp_1_id != 0 && $pack->attr_grp_2_id == 0){
            foreach ($products as $product){
                $attributes = $product->attribute_get_list_for_product($product->id);
                foreach ($attributes as $attribute) {
                    if($attribute->attributes_group_id == $pack->attr_grp_1_id) {
                        $all_atr[1][$attribute->attribute_id][] = array( 'product' => $product, 'attribute' => $attribute);
                    }
                }
                $grp1_name = $this->ProductAttributesModel->get_group($pack->attr_grp_1_id);
                if(strpos($grp1_name->allegro_id, 'colorPattern')!== FALSE){
                    $all_atr['data'] = '1attrImages';
                }else {
                $all_atr['data'] = '1attr';
                }
            }
            uasort($all_atr[1], array( $this, 'attr_array_sort'));
            
        }
        
        return $all_atr;
        
        
    }
    
    public function attr_array_sort($a, $b){
        return strcmp($a[0]['attribute']->name, $b[0]['attribute']->name);
    }
    public function attr_array_sort2($a, $b){
        return strcmp($a[0]['attribute']->id, $b[0]['attribute']->id);
    }
    
    public function check_if_pack_exist($uid){
        $this->db->where('allegro_uid', $uid);
        $q = $this->db->get('duo_shop_product_pack_main');
        if($q->num_rows() > 0){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function get_pack_by_allegro_id($uid){
        $this->db->where('allegro_uid', $uid);
        $q = $this->db->get('duo_shop_product_pack_main');
        if($q->num_rows() > 0){
            return $q->result()[0];
        } else {
            return -1;
        }
    }
}
