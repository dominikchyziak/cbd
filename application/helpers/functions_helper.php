<?php

function assets($file) {
    return base_url('assets/' . $file);
}

function pre($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

function prexit($var) {
    pre($var);
    exit;
}

function ensure_dir_exists($dir) {
    if (!is_dir($dir))
        mkdir($dir, 0777, TRUE);
    return is_dir($dir);
}

function get_file_ext($name) {
    $ext = strtolower(preg_replace('/^.*\./', '', $name));
    return $ext;
}

function ensure_name_unique($dir, $name) {
    $ext = get_file_ext($name);
    $name = get_name_no_ext($name);
    $new_name = $name;
    $ds = DIRECTORY_SEPARATOR;
    $i = 1;
    $dir = rtrim($dir, '/');

    while (file_exists($dir . $ds . "$new_name.$ext")) {
        $new_name = "$name-$i";
        $i++;
    }

    return "$new_name.$ext";
}

function get_name_no_ext($name) {
    $ext = get_file_ext($name);
    $name = str_replace(".$ext", '', $name);
    return $name;
}

function getAlias($id, $name) {
    $CI = & get_instance();
    $CI->load->helper('text');

    $name = preg_replace(
            array('/\s+/', '/[^a-z0-9-]/'), array('-', '-'), trim(strtolower(convert_accented_characters(shorten($name, 50))))
    );

    return "$id-$name";
}

function getIdFromAlias($alias) {
    return (int) $alias;
}

function shorten($str, $limit, $hellip = false) {
    if (strlen($str) <= $limit) {
        return $str;
    }

    $pos = strpos($str, ' ', $limit);

    return ($pos ? substr($str, 0, $pos) . ($hellip ? '&hellip;' : '') : $str);
}

function is_current($loc) {
   $current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
   if ($loc == '/') {
        if ($current_url === site_url()) {
            return 'aktywny';
        } else {
            return '';
        }
    }
    $this_url = site_url($loc);
   $x = strpos($current_url, $this_url);
   if ($x !== false || $current_url == $this_url || $current_url == $loc ||
           $current_url == (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $loc ) {
       return "aktywny";
   } else {
       return "";
   }
}

function get_menu($is_home = false, $menu_id = 1, $ul_class = "", $li_class = "") {
    $CI = get_instance();
    $CI->load->model('MenuModel');
    $menuModel = new MenuModel();
    $menu = $menuModel->get_menu(LANG, $menu_id);    
    if (!empty($menu)) {
        echo '<ul class="'. $ul_class . '">';
        foreach ($menu as $item) {
            echo '<li class="';
            echo is_current($item["link"]) . ' ' . $li_class;
            echo '"><a href="';
            if($is_home){
                //echo site_url() . "/";
            }
            if(substr($item["link"],0,4) != 'http'){
               echo site_url($item["link"]);
           } else {
               echo $item['link'];
           }
            echo '">';
            echo $item["name"];
            echo '</a>';
            if (!empty($item["children"])) {
                echo '<ul>';
                $children = $item["children"];
                foreach ($children as $r) {
                    echo '<li ';
                    echo is_current($r["link"]);
                    echo '><a href="';
                    echo $r["link"];
                    echo '">';
                    echo $r["name"];
                    echo '</a>';
                    echo '</li>';
                }
                echo '</ul>';
            }
            echo '</li>';
        }
        echo '</ul>';
    }

}

function get_category_menu(){
    $CI = get_instance(); 
    $CI->load->model('OfferCategoryModel');
    $categories = $CI->OfferCategoryModel->find_all_for_menu();
	
    if(!empty($categories)): ?>
    
    <div class="menu-strony-kategorie-zaw-lista">
        <ul>
           <?php foreach($categories as $category):
           // if ( is_null($category->parent_id)): ?>
               <li><span data-ob="<?= $category->getUrl(); ?>" data-tekst="<?= $category->getTranslation(LANG)->name; ?>" class="zmieniarka"><?= $category->getTranslation(LANG)->name; ?></span></li>   
           <?php //endif; 
           endforeach; ?>  
        </ul>
    </div> 
  
<div class="menu-strony-kategorie-zaw-lista-linki">
    <?php foreach($categories as $category): ?>
        <div class="menu-strony-kategorie-zaw-lista-link-poz">
             <ul>
           <?php 
           foreach($category->children as $z => $children):
                if ($z > 6 && $z % 6 == 1): ?>
             </ul><ul>
                <?php endif; ?>
                    <li><a href="<?= $children->getPermalink() ?>" data-ob="<?= $children->getUrl(); ?>" data-tekst="<?= $children->getTranslation(LANG)->name; ?>" class="zmieniarka"><?= $children->getTranslation(LANG)->name; ?></a></li>           
            <?php endforeach; ?>   
              </ul>  
        </div>	
    <?php endforeach; endif; ?>
</div> <?php
}

/*dodane 25.04.2019*/
function get_name_menu($id) {
    $CI = get_instance();
    $CI->load->model('MenuModel');
    $menuName = new MenuModel();
    $name = $menuName->get_menu_name($id);
    return $name;
    
}


function get_option($key){
    $CI = get_instance();
    $CI->load->model('ConfigurationModel');
    $configuration = new ConfigurationModel();
    $option = $configuration->getOption($key);
    return $option;
}

function set_option($key, $value){
    $CI = get_instance();
    $CI->load->model('ConfigurationModel');
    $configuration = new ConfigurationModel();
    $configuration->updateOption($key, $value);
    return true;
}

function get_languages(){
    $CI = get_instance();
    $CI->load->model('ConfigurationModel');
    $configuration = new ConfigurationModel();
    $option = $configuration->get_languages();
    return $option;
}

//element to co ma się wyświetlić: price, quantity
function basket($element){
    $CI = get_instance();
    $CI->load->model('ProductModel');
    $CI->load->library('session');
    $elements['quantity'] = 0;
    $elements['price'] = 0;
    $basket = json_decode(json_encode($CI->session->userdata('basket')), true);
	$discount = $CI->session->userdata['discount']; 
	
    if(!empty($basket)){
        foreach ($basket as $product_id => $quantity) {
            $item = explode("_", $product_id);
            $p_id = $item[0];
            $option_id = isset($item[1]) ? $item[1] : 0;
            $pr = new ProductModel($p_id);
            if(empty($pr->number_in_package)){
				$elements['quantity'] += $quantity[0];
				if (empty($discount) || (!empty($discount) && $discount['type'] === '1')){
					$elements['price'] += $pr->calculate_price($p_id, $option_id, $quantity[1], $quantity[2]) * $quantity[0];  //$option['product_price']*$quantity[0]* (1+($sum/100));
				} else {
					$elements['price'] += ($pr->calculate_price($p_id, $option_id, $quantity[1], $quantity[2]) * $quantity[0]) * (1- $discount['value']);
				}
            } else {
               $elements['quantity'] += round( ($quantity[0] *1) / ($pr->number_in_package *1));   
            }
        }
    }
	
	if ($element === 'quantity'){
		return $elements['quantity'];
	} else {
		if (empty($discount) || (!empty($discount) && $discount['type'] === '0' )) {
			return $elements['price'];
		} else if (!empty($discount) && $discount['type'] === '1'){
			return $elements['price'] - $discount['value'];
		}
	}
}
function add_viewing_history($product){
    $CI = get_instance();
    $CI->load->library('session');
    $json = $CI->session->userdata('view_history');
    if(!empty($json)){
        $arr = json_decode($json, true);
        if(!in_array($product, $arr)){
        $arr = add_to_stack($arr, $product);
        $CI->session->set_userdata('view_history', json_encode($arr));
        }
    } else {
        $CI->session->set_userdata('view_history', json_encode(array(0=>$product)));
    }
}

function add_to_stack($arr, $product){
    for($i=count($arr)-1; $i >= 0 ;$i--){
       if($i > 6) { continue; }
       $arr[$i+1] = $arr[$i];
    }
    $arr[0] = $product;
    return $arr;
}

function get_viewing_history(){
    $CI = get_instance();
    $CI->load->library('session');
    $json = $CI->session->userdata('view_history');
    return json_decode($json);
}

function in_array_attr($needle, $haystack){
    foreach($haystack as $hs){
        if($hs['product']->id == $needle){
            return true;
        }
    }
    return false;
}

//funkcja sprawdza czy na stronie danego produktu, dany rozmiar powinien byc
//dostepny dla koloru danego produktu
// prod_id - id danego produktu
// needle - id danego ????
// haystack - cala tablica powiazan produkt-atrybut
function in_array_attr2($prod_id, $pp, $haystack){
      foreach ($pp as $p){
        $tmp[] = $p['product']->id;
    }
    if(in_array($prod_id, $tmp)){ return TRUE; }
// szukamy danego koloru
    $kolor_id = null;
    $hay= $haystack[2];
    foreach($hay as $hs){
        foreach($hs as $h){
        if($h['product']->id == $prod_id){
            $kolor_id = $h['attribute']->attribute_id;
            break;
        }
        }
    }
  
    if(!empty($kolor_id)){
        $prods = $haystack[2][$kolor_id];
        foreach($prods as $p){
            if(in_array($p['product']->id, $tmp)){
                return TRUE;
            }
        }
        
    }
    return FALSE;
}

function in_array_attr3($prod_id, $pp, $haystack){
      foreach ($pp as $p){
        $tmp[] = $p['product']->id;
    }
    if(in_array($prod_id, $tmp)){ return TRUE; }
// szukamy danego koloru
    $kolor_id = null;
    $hay= $haystack[1];
    foreach($hay as $hs){
        foreach($hs as $h){
        if($h['product']->id == $prod_id){
            $kolor_id = $h['attribute']->attribute_id;
            break;
        }
        }
    }
  
    if(!empty($kolor_id)){
        $prods = $haystack[1][$kolor_id];
        foreach($prods as $p){
            if(in_array($p['product']->id, $tmp)){
                return TRUE;
            }
        }
        
    }
    return FALSE;
}

function get_attr_link1($prod_id, $pp, $haystack){
          foreach ($pp as $p){
        $tmp[] = $p['product']->id;
    }
    if(in_array($prod_id, $tmp)){ return $prod_id; }
// szukamy danego koloru
    $kolor_id = null;
    $hay= $haystack[2];
    foreach($hay as $hs){
        foreach($hs as $h){
        if($h['product']->id == $prod_id){
            $kolor_id = $h['attribute']->attribute_id;
            break;
        }
        }
    }
  
    if(!empty($kolor_id)){
        $prods = $haystack[2][$kolor_id];
        foreach($prods as $p){
            if(in_array($p['product']->id, $tmp)){
                return $p['product']->getPermalink();
            }
        }
        
    }
    return FALSE;
}
function get_attr_product1($prod_id, $pp, $haystack){
          foreach ($pp as $p){
        $tmp[] = $p['product']->id;
    }
    if(in_array($prod_id, $tmp)){ return null; }
// szukamy danego koloru
    $kolor_id = null;
    $hay= $haystack[2];
    foreach($hay as $hs){
        foreach($hs as $h){
        if($h['product']->id == $prod_id){
            $kolor_id = $h['attribute']->attribute_id;
            break;
        }
        }
    }
  
    if(!empty($kolor_id)){
        $prods = $haystack[2][$kolor_id];
        foreach($prods as $p){
            if(in_array($p['product']->id, $tmp)){
                return $p['product'];
            }
        }
        
    }
    return null;
}
function get_attr_link2($prod_id, $pp, $haystack){
          foreach ($pp as $p){
        $tmp[] = $p['product']->id;
    }
    if(in_array($prod_id, $tmp)){ return $prod_id; }
// szukamy danego koloru
    $kolor_id = null;
    $hay= $haystack[1];
    foreach($hay as $hs){
        foreach($hs as $h){
        if($h['product']->id == $prod_id){
            $kolor_id = $h['attribute']->attribute_id;
            break;
        }
        }
    }
  
    if(!empty($kolor_id)){
        $prods = $haystack[1][$kolor_id];
        foreach($prods as $p){
            if(in_array($p['product']->id, $tmp)){
                return $p['product']->getPermalink();
            }
        }
        
    }
    return FALSE;
}

function currency_menu(){
    $CI = get_instance();
    $CI->load->model('CurrencyModel');
    $currencies = $CI->CurrencyModel->get_acitve_currencies();
    if(!empty($currencies)){
        echo '<ul class="currency-menu">';
        foreach ($currencies as $cur) {
            echo '<li class="'. currency_menu_active_element($cur) .'"><a href="'.$cur->getPermalink().'">'.$cur->name.'</a></li>';
        }
        echo '</ul>';
    }
}

function currency_menu_active_element($currency) {
    $CI = get_instance();
    $CI->load->library('session');
    $active_curr = $CI->session->userdata('user_currency');
    if (!empty($active_curr)) {
        if($active_curr == $currency->id){
            return 'active';
        }
    } else {
        
    }
    return '';
}

function get_active_currency_code(){
    $CI = get_instance();
    $CI->load->library('session');
    $active_curr = $CI->session->userdata('user_currency');
    $CI->load->model('CurrencyModel');
    if(!empty($active_curr)){
        return $CI->CurrencyModel->get_currency($active_curr)->code ;
    } else {
        return $CI->CurrencyModel->get_default_currency()->code;
    }
}

function get_active_currency(){
    $CI = get_instance();
    $CI->load->library('session');
    $active_curr = $CI->session->userdata('user_currency');
    $CI->load->model('CurrencyModel');
    if(!empty($active_curr)){
        return $CI->CurrencyModel->get_currency($active_curr);
    } else {
        return $CI->CurrencyModel->get_default_currency();
    }
}

function gallery_find_module($count){
    $CI = get_instance();
    $CI->load->model('GallerySetupModel');
    return $CI->GallerySetupModel->get_module_by_max($count);
}

function offer_name($name) {
    $tab = explode(" ", $name);
    $output = "<span>".$tab[0]."</span>";
    foreach ($tab as $i => $value) {
        if ($i == 0) {
            continue;
        } 
        $output = $output." ".$value;
    }
    return $output;
}

function phone_name($phone) {
    $tab = explode(" ", $phone);
    $output = "<span>".$tab[0]."</span>";
    foreach ($tab as $i => $value) {
        if ($i == 0) {
            continue;
        } 
        $output = $output." ".$value;
    }
    return $output;
}
//
//function number($number) {
//    $tab = explode(" ", $number);
//    $output = "<span>".$tab[0];
//    $output1 = $tab[1];
//    $output2 = "<sup>".$tab[2]."</sup></span>";
//    
//    return $output." ".$output1."".$output2;
//}

//function news_routing(&$route){
//    $CI = get_instance();
//    $translations = $CI->db->get('duo_news_translations');
//    foreach($translations as $t){
//        if(empty($t->custom_url)){continue;}
//        $route['aktualnosci/'.$t->custom_ulr] = 'news/pokaz/'.$t->news_id;
//    }
//}

function product_name($string){
    $s = explode(' ', $string);
    if(count($s) == 1){
        return $string;
    }
    else {
        return $s[0].' <span>'. implode(' ', array_slice($s, 1, count($s) - 1)). '</span>';
    }
}

function stolarczyk_title($string){
    
    return $string; 
//    $s = explode(' ', $string);
//    if(count($s) == 1){
//        return "<span>$string</span>";
//    }
//    else {
//        return '<span>'.$s[0]. '</span> '. implode(' ', array_slice($s, 1, count($s) - 1));
//    }
}