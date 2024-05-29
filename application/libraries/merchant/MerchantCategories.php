<?php
  function charsetwin2utf(){
  $tabela = Array(
    "\xb9" => "\xc4\x85", "\xa5" => "\xc4\x84", "\xe6" => "\xc4\x87", "\xc6" => "\xc4\x86",
    "\xea" => "\xc4\x99", "\xca" => "\xc4\x98", "\xb3" => "\xc5\x82", "\xa3" => "\xc5\x81",
    "\xf3" => "\xc3\xb3", "\xd3" => "\xc3\x93", "\x9c" => "\xc5\x9b", "\x8c" => "\xc5\x9a",
    "\x9f" => "\xc5\xba", "\xaf" => "\xc5\xbb", "\xbf" => "\xc5\xbc", "\xac" => "\xc5\xb9",
    "\xf1" => "\xc5\x84", "\xd1" => "\xc5\x83", "\x8f" => "\xc5\xb9");
   return $tabela;
  }
 
  function charsetiso2utf(){
   $tabela = Array(
    "\xb1" => "\xc4\x85", "\xa1" => "\xc4\x84", "\xe6" => "\xc4\x87", "\xc6" => "\xc4\x86",
    "\xea" => "\xc4\x99", "\xca" => "\xc4\x98", "\xb3" => "\xc5\x82", "\xa3" => "\xc5\x81",
    "\xf3" => "\xc3\xb3", "\xd3" => "\xc3\x93", "\xb6" => "\xc5\x9b", "\xa6" => "\xc5\x9a",
    "\xbc" => "\xc5\xba", "\xac" => "\xc5\xbb", "\xbf" => "\xc5\xbc", "\xaf" => "\xc5\xb9",
    "\xf1" => "\xc5\x84", "\xd1" => "\xc5\x83");
   return $tabela;
  }
  
  
 



  function charsetISO88592_2_UTF8($tekst){
   return strtr($tekst, charsetiso2utf());
  }
 
  function charsetUTF8_2_ISO88592($tekst){
   return strtr($tekst, array_flip(charsetiso2utf()));
  }
 
  function charsetWIN1250_2_UTF8($tekst){
   return strtr($tekst, charsetwin2utf());
  }
 
  function charsetUTF8_2_WIN1250($tekst){
   return strtr($tekst, array_flip(charsetwin2utf()));
  }
 
  function charsetISO88592_2_WIN1250($tekst){
   return strtr($tekst, "\xa1\xa6\xac\xb1\xb6\xbc", "\xa5\x8c\x8f\xb9\x9c\x9f");
  }
 
  function charsetWIN1250_2_ISO88592($tekst){
   return strtr($tekst, "\xa5\x8c\x8f\xb9\x9c\x9f", "\xa1\xa6\xac\xb1\xb6\xbc");
 
  }


class MerchantCategories{
    
    public function __construct($start=0,$stop=100){
        $this->start=$start;
        $this->stop=$stop;
        $this->cats=array();
    }
    
    public function getCategoryById($id){
        return 'fotoobrazy';
        if(isset($this->cats[$id])){
            return $this->cats[$id];
        }else{
            $xls = new Spreadsheet_Excel_Reader();
            $xls->setOutputEncoding('iso-8859-2');
            $xls->read(dirname(__FILE__) . '/merchant_category.xls');
            for ($i = $this->start; $i <= $this->stop; $i++) {
                if($xls->sheets[0]['cells'][$i][1]==$id){
                    $j=4;
                    while($j>0){
                        if($xls->sheets[0]['cells'][$i][$j]!='' && $j<50){
                            $cname=$xls->sheets[0]['cells'][$i][$j];
                            $cname=charsetISO88592_2_UTF8($cname);
                            $taxonomy[]=$cname;
                            $j++;
                        }else{
                            $j=false;
                        }
                    }
                    if($taxonomy){
                        $this->cats[$id]=implode(' &gt; ',$taxonomy);
                        return implode(' &gt; ',$taxonomy);
                    }else
                        return false;
                }
            }
        }
    }
}
?>