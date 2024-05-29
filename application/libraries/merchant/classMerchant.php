<?php
require_once('credentials.php');
require_once('gshoppingcontent-php/GShoppingContent.php');

class merchant {
    public function __construct() {
        $creds = Credentials::get();
        $this->merchantId=$creds['merchantId'];
        $client = new GSC_Client($creds['merchantId']);
        $client->login($creds['email'], $creds['password']);
        $this->client=$client;
        $this->batch = new GSC_ProductList();
    }
    
    public function prepareInsertProduct($product_info){
        $product_info=$this->defaultVars($product_info);
        $product = new GSC_Product();
        $product->setSKU($product_info['id']);
        $product->setProductLink($product_info['link']);
        $product->setTitle($product_info['title']);
        $product->setDescription($product_info['description']);
        $product->setGoogleProductCategory($product_info['category']);
        $product->setProductType($product_info['product_type']);
        $product->setImageLink($product_info['image']);
        $product->setAdult($product_info['adult']);
        $product->setCondition($product_info['condition']);
        $product->setTargetCountry($product_info['country']);
        $product->setQuantity($product_info['quantity']);
        $product->setAvailability($product_info['avail']);
        $product->setPrice($product_info['price'], $product_info['currency']);
        $product->setContentLanguage($product_info['language']);
        if($product_info['brand'])
            $product->setBrand($product_info['brand']);
        if($product_info['ean'])
            $product->setGtin($product_info['ean']);
        if($product_info['mpn'])
            $product->setMpn($product_info['mpn']);
        $product->setBatchOperation("insert");
        $this->batch->addEntry($product);
    }
    
    public function prepareUpdateProduct($product_info){
        $product_info=$this->defaultVars($product_info);
        $product = new GSC_Product();
        $product->setSKU($product_info['id']);
        $product->setProductLink($product_info['link']);
        $product->setTitle($product_info['title']);
        $product->setDescription($product_info['description']);
        $product->setGoogleProductCategory($product_info['category']);
        $product->setProductType($product_info['product_type']);
        $product->setImageLink($product_info['image']);
        $product->setAdult($product_info['adult']);
        $product->setCondition($product_info['condition']);
        $product->setTargetCountry($product_info['country']);
        $product->setQuantity($product_info['quantity']);
        $product->setAvailability($product_info['avail']);
        $product->setPrice($product_info['price'], $product_info['currency']);
        $product->setContentLanguage($product_info['language']);
        
        if($product_info['brand'])
            $product->setBrand($product_info['brand']);
        if($product_info['ean'])
            $product->setGtin($product_info['ean']);
        if($product_info['mpn'])
            $product->setMpn($product_info['mpn']);
        if($product_info['promo_price'])
            $product->setSalePrice($product_info['promo_price'], $product_info['currency']);
        $product->setBatchOperation("update");
        $editLink = $this->client->getProductUri($product_info['id'], $product_info['country'], $product_info['language'],'online');
        $product->setAtomId($editLink);
        $entry=$this->batch->addEntry($product);
        return $entry;        
    }
    
    public function prepareDeleteProduct($product_id){
        $product = new GSC_Product();
        $product->setBatchOperation("delete");
        $editLink = $this->client->getProductUri($product_id, 'PL', 'pl','online');
        $product->setAtomId($editLink);
        $this->batch->addEntry($product);
    }
    
    
    
    public function getProducts(){
        $productFeed = $this->client->getProducts();
        $products = $productFeed->getProducts();
        foreach($products as $k=>$product){
            $product_list[$k][title]=$product->getTitle();
            $product_list[$k][price]=$product->getPrice();
        }
        return $product_list;
    }
    
    public function przygotujProdukt($row){
        global $category;
        if($row[id_producent]>0){
            $query2 = "SELECT nazwa FROM ".PREFIX."_sklep_producent  WHERE id='".$row[id_producent]."' LIMIT 1";
            $result2 = mysql_query ($query2);
            $row2 = mysql_fetch_array($result2); 
            $producent = $row2[nazwa];
        }
        $dostepnosc=$this->dostepnoscProduktu($row);
        
        $query2 = "SELECT nazwa FROM ".PREFIX."_sklep_produkt_foto  WHERE id_produkt='".$row[id]."' and wiodace='tak' LIMIT 1";
        $result2 = mysql_query ($query2);
        $row2 = mysql_fetch_array($result2); 
        $foto = $row2[nazwa];    
    
        $ceny=Cena($row[id]);
        if($ceny[stara_cena]){
            $cena=$ceny['stara_cena'];
            $cena_promo=$ceny['brutto'];
        }else{
            $cena=$ceny['brutto'];
            $cena_promo=false;
        }
        $row['nazwa']=str_replace('&oacute;','ó',$row['nazwa']);
        $row['nazwa']=str_replace('&Oacute;','Ó',$row['nazwa']);
        $row['opis_szerszy']=htmlspecialchars_decode(str_replace('&Oacute;','Ó',str_replace('&oacute;','ó',strip_tags($row['opis_szerszy']))));
        $row['opis_szerszy']=trim(preg_replace('/\s\s+/', ' ', $row['opis_szerszy']));
        $row['opis_szerszy']=str_replace('&nbsp;',' ',$row['opis_szerszy']);
        $row['opis_szerszy']=str_replace('&middot;','·',$row['opis_szerszy']);
        $row['opis_szerszy']=str_replace('','',$row['opis_szerszy']);
        $row['opis_szerszy']=str_replace('&#8211;','-',$row['opis_szerszy']);
        
        $product_info=array(
            'id'=>$row['id'],
            'link'=>WWWserwis.usunZnaki($row['nazwa'])."-produkt-".$row['id'].".html",
            'title'=>html_entity_decode($row['nazwa']),
            'description'=>$row['opis_szerszy'],
            'image'=>WWWserwis.'foto_shop/'.$foto,
            'product_type'=>trim($this->kategoria_sciezka($row['id_kategoria']),' / '),
            'category'=>$category->getCategoryById($row['id_kategoria']),
            'price'=>$cena,
            'promo_price'=>$cena_promo,
            'quantity'=>$row['szt'],
            'brand'=>$producent,
            'ean'=>$row['numer_prodcent'],
            'avail'=>$dostepnosc
        );
        return $product_info;
    }
    
    public function dostepnoscProduktu($row){
        if($row[publikacja]!='tak' || $row[szt]<=0 || $row[id_dostepnosc]!=1){
            $dostepnosc='out of stock';
        }else{
            $dostepnosc='in stock';
        }
        return $dostepnosc;
    }
    
    public function kategoria_sciezka($id_kat,$sciezka=''){
        $sql = "select nazwa,re_id from ".PREFIX."_sklep_kategoria where id='$id_kat' ";
        $sql_result = mysql_query($sql);
        while ($row = mysql_fetch_array($sql_result)) {
            $sciezka=$row[nazwa].' / '.$sciezka;
            if(!$row[re_id]==0){
                $sciezka=$this->kategoria_sciezka($row[re_id], $sciezka);
            }
            return $sciezka;
        }
    }
    
    public function defaultVars($array){
        $product_info=array(
            'currency'=>'pln',
            'adult'=>'false',
            'condition'=>'new',
            'country'=>'PL',
            'language'=>'pl',
            'quantity'=>'1',
            'avail'=>'in stock'
        );
        foreach($array as $key=>$var){
            $product_info[$key]=$var;
        }
        return $product_info;
    }


}

?>