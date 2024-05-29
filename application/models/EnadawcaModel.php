<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include 'application/libraries/ElektronicznyNadawca.php';
class EnadawcaModel extends MY_Model {

    public $en;
    
    function __construct() {
        parent::__construct();
  
    $options['login'] = get_option('admin_module_enadawca_login');
    $options['password'] = get_option('admin_module_enadawca_password');
    $options['trace'] = 1;
    $this->en = new ElektronicznyNadawca("application/libraries/en.wsdl", $options);
    
    $this->load->model('OrderModel');
    }
    
    
    public function getUP(){
        $params = new getUrzedyNadania();
        $urzedy = $this->en->getUrzedyNadania($params);
        return $urzedy->urzedyNadania;
    }
    
    public function getDefaultUP(){
        return get_option('admin_module_enadawca_up_id');
    }
    
    public function getAdress($order_id){
        $order = $this->OrderModel->get_order($order_id);
        $A = new adresType();
        $A->nazwa = $order->first_name;
        $A->nazwa2 = $order->last_name;
        $A->ulica = $order->street;
        $A->numerDomu = $order->building_number;
        if(!empty($order->flat_number)){
            $A->numerLokalu = $order->flat_number;
        }
        $A->miejscowosc = $order->city;
        $A->kodPocztowy = str_replace('-', '', $order->zip_code);
        $A->email = $order->email;
        return $A;
    }
    
    public function getPackage($order_id){
        $A = $this->getAdress($order_id);
        $order = $this->OrderModel->get_order($order_id);
        $this->load->model('Delivery_Model');
        $del = $this->Delivery_Model->get_delivery($order->delivery);
        $P = null;
        switch($del['special_name']){
            case 9:
                $P = new przesylkaBiznesowaType();
                $P->adres = $A;
                $P->masa = round($order->weight*1000);
                $P->wartosc = round($order->price*100);
                $P->guid = strtoupper(substr($order->key, 0, 32));
                break;
            default :
                $P = new przesylkaBiznesowaType();
                $P->adres = $A;
                $P->masa = round($order->weight*1000);
                $P->wartosc = round($order->price*100);
                $P->guid = strtoupper(substr($order->key, 0, 32));
                break;
        }
        return $P;
    }
    public function addPackage($order_id){
        if($this->check_if_package_sent($order_id) > 0){
            return -1;
        }
        $tmp = new addShipment();
        $P = $this->getPackage($order_id);
        $tmp->przesylki[] = $P;
        $this->en->addShipment($tmp);
        $this->save_package_sent($order_id, $P->guid);
        return $P->guid;
    }
    
    public function getNalepka($order_id){
        $guid_t = $this->check_if_package_sent($order_id);
        
        if($guid_t === -1){
            $guid = $this->addPackage($order_id);
        } else {
            $guid = $guid_t;
        }
        $pdf_loc = "./user_files/nalepki/nalepka-".$guid.".pdf";
        if(file_exists($pdf_loc)){
            header("Content-Length: " . filesize ($pdf_loc ) ); 
            header("Content-type: application/pdf"); 
            header("Content-disposition: inline; filename=nalepka.pdf");
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            $filepath = readfile($pdf_loc);
            die();
        }
        $parameters= new getAddresLabelByGuid();
        $parameters->guid = array($guid);
        $retval = $this->en->getAddresLabelByGuid($parameters);
        if(!is_array($retval->content))
        {
            $retval->content = array($retval->content);
        }
        foreach ($retval->content as $c) /* @var $c addressLabelContent */
        {
            
            $h = fopen($pdf_loc, "w");
            fwrite($h, $c->pdfContent);
            fclose($h);
            header("Content-Length: " . filesize ($pdf_loc ) ); 
            header("Content-type: application/pdf"); 
            header("Content-disposition: inline; filename=nalepka.pdf");
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            $filepath = readfile($pdf_loc);
            
        }
    }
    
    public function clear(){
        $par =  new clearEnvelope();
        $this->en->clearEnvelope($par);
    }
    
    public function send(){
        $parameters2 = new sendEnvelope();
        $parameters2->urzadNadania = $this->getDefaultUP();
        $this->en->sendEnvelope($parameters2);
    }
    
    public function test69(){
        $tmp = new addShipment();
        $P = $this->getPackage(101);
        $tmp->przesylki[] = $P;
        $par =  new clearEnvelope();
        $this->en->clearEnvelope($par);
        var_dump($this->en->addShipment($tmp));
        $parameters= new getAddresLabelByGuid();
        $parameters->guid = array($P->guid);

        $retval = $this->en->getAddresLabelByGuid($parameters);
        if(!is_array($retval->content))
        {
            $retval->content = array($retval->content);
        }
        foreach ($retval->content as $c) /* @var $c addressLabelContent */
        {
            $h = fopen("./user_files/nalepka".$c->nrNadania.".pdf", "w");
            fwrite($h, $c->pdfContent);
            fclose($h);
        }
//        $parameters2 = new sendEnvelope();
//        $parameters2->urzadNadania = $this->getDefaultUP();
//        var_dump($this->en->sendEnvelope($parameters2));
        die();
        
    }

    
    public function test2(){
        $params = new getUrzedyNadania();
        $urzedy = $this->en->getUrzedyNadania($params);
        return $urzedy;
    }
    
    public function test3(){
        $a = $this->getPackage(103);
        return $a;
    }
    
    
    public function getPaczkiType(){
        $res = array(
            'minipaczka' => 'MiniPaczka'
//            '1' => "Paczka24, Paczka48",
//            '2' => "Pocztex Ekspres 24",
//            '3' => "Pocztex PROCEDURA",
//            '4' => "Przesyłka nierejestrowana(list zwykły)",
//            '5' => "Przesyłka listowa z zadeklarowaną wartością",
//            '6' => "Przesyłka polecona",
//            '7' => "Przesyłka Firmowa polecona",
//            '8' => "Przesyłka firmowa nierejestrowana",
//            '9' => "Pocztex Kurier 48"
                );
        return $res;
    }
    
    public function check_if_package_sent($order_id){
       $this->db->where('order_id', $order_id);
       $q = $this->db->get('duo_shop_e_nadawca');
       
       if($q->num_rows() > 0){
           return $q->result()[0]->guid;
       } else {
           return -1;
       }
       
    }
    
    public function save_package_sent($order_id, $guid){
        $data = array(
            'order_id' => $order_id,
            'guid' => $guid
        );
        $this->db->insert('duo_shop_e_nadawca', $data);
    }
    
    /**
* Returns a GUIDv4 string
*
* Uses the best cryptographically secure method 
* for all supported pltforms with fallback to an older, 
* less secure version.
*
* @param bool $trim
* @return string
*/
function GUIDv4 ($trim = true)
{
    // Windows
    if (function_exists('com_create_guid') === true) {
        if ($trim === true)
            return trim(com_create_guid(), '{}');
        else
            return com_create_guid();
    }

    // OSX/Linux
    if (function_exists('openssl_random_pseudo_bytes') === true) {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    // Fallback (PHP 4.2+)
    mt_srand((double)microtime() * 10000);
    $charid = strtolower(md5(uniqid(rand(), true)));
    $hyphen = chr(45);                  // "-"
    $lbrace = $trim ? "" : chr(123);    // "{"
    $rbrace = $trim ? "" : chr(125);    // "}"
    $guidv4 = $lbrace.
              substr($charid,  0,  8).$hyphen.
              substr($charid,  8,  4).$hyphen.
              substr($charid, 12,  4).$hyphen.
              substr($charid, 16,  4).$hyphen.
              substr($charid, 20, 12).
              $rbrace;
    return $guidv4;
}


public function get_xml($array) {
        $this->load->model('OrderModel');
        $domtree = new DOMDocument('1.0', 'iso-8859-2');

        /* create the root element of the xml tree */
        $xmlRoot = $domtree->createElement("Nadawca");
        //Miejscowosc="WARSZAWA" Kod="00940" Guid="{19716E46-B412-B723-5AC0-AA46D0192C69}" NIP="5250007313" Zrodlo="NADAWCA"
        $xmlRoot->setAttribute('Struktura', '1.6');
        $xmlRoot->setAttribute('Nazwa', "FH Elżbieta Machaj");
        $xmlRoot->setAttribute('NazwaSkrocona', "FH Elżbiet");
        $xmlRoot->setAttribute('Ulica', "Bora Komorowskiego");
        $xmlRoot->setAttribute('Dom', '1');
        $xmlRoot->setAttribute('Lokal', '2');
        $xmlRoot->setAttribute('Miejscowosc', "TARNÓW");
        $xmlRoot->setAttribute('Kod', "33100");
        $xmlRoot->setAttribute('Guid', "{A8C1D7DB-4781-1613-6135-824B37F6AE57}");
        $xmlRoot->setAttribute('NIP', "1111111111");
        $xmlRoot->setAttribute('Zrodlo', "NADAWCA");
        
        /* append it to the document created */
        $xmlRoot = $domtree->appendChild($xmlRoot);
        
        $xmlZbior = $domtree->createElement('Zbior');
        //<Zbior Nazwa="2018-09-06\1" DataUtworzenia="2018-07-30T13:22:29" Opis="Pwrd by EN 2018-07-30" IloscPrzesylek="3" Guid="{E637335F-F2FF-D9EF-5E00-D211B97E1CF1}">
        $xmlZbior->setAttribute('Nazwa', date('Y-m-d').'\\1');
        $xmlZbior->setAttribute('DataUtworzenia', date('Y-m-d\TH:i:s'));
        $xmlZbior->setAttribute('Opis', "Pwrd by Duocms 2018-06-09");
        $xmlZbior->setAttribute('IloscPrzesylek', 1);
        $xmlZbior->setAttribute('Guid', "{".strtoupper($this->GUIDv4())."}");
        
        $xmlRoot->appendChild($xmlZbior);
        
        foreach($array as $order_id){
            $order = $this->OrderModel->get_order($order_id);
            $przesylka = $domtree->createElement('Przesylka');
            $przesylka->setAttribute('Guid', "{".strtoupper($this->GUIDv4())."}");
            
            $attrybut = $domtree->createElement('Atrybut', '835');
            $attrybut->setAttribute('Typ', '');
            $attrybut->setAttribute('Nazwa', 'Symbol');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', $order->first_name);
            $attrybut->setAttribute('Typ', 'Adresat');
            $attrybut->setAttribute('Nazwa', 'Nazwa');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', $order->last_name);
            $attrybut->setAttribute('Typ', 'Adresat');
            $attrybut->setAttribute('Nazwa', 'NazwaII');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', $order->street);
            $attrybut->setAttribute('Typ', 'Adresat');
            $attrybut->setAttribute('Nazwa', 'Ulica');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', $order->building_number);
            $attrybut->setAttribute('Typ', 'Adresat');
            $attrybut->setAttribute('Nazwa', 'Dom');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', $order->flat_number);
            $attrybut->setAttribute('Typ', 'Adresat');
            $attrybut->setAttribute('Nazwa', 'Lokal');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', $order->city);
            $attrybut->setAttribute('Typ', 'Adresat');
            $attrybut->setAttribute('Nazwa', 'Miejscowosc');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', str_replace('-', '', $order->zip_code));
            $attrybut->setAttribute('Typ', 'Adresat');
            $attrybut->setAttribute('Nazwa', 'Kod');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', 'Polska');
            $attrybut->setAttribute('Typ', 'Adresat');
            $attrybut->setAttribute('Nazwa', 'Kraj');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', $order->inpost_locker);
            $attrybut->setAttribute('Typ', '');
            $attrybut->setAttribute('Nazwa', 'NrNadania');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', '1500');
            $attrybut->setAttribute('Typ', '');
            $attrybut->setAttribute('Nazwa', 'Masa');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', '1');
            $attrybut->setAttribute('Typ', '');
            $attrybut->setAttribute('Nazwa', 'Ilosc');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', 'E1');
            $attrybut->setAttribute('Typ', '');
            $attrybut->setAttribute('Nazwa', 'Strefa');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', 'W');
            $attrybut->setAttribute('Typ', '');
            $attrybut->setAttribute('Nazwa', 'Uslugi');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', '10000');
            $attrybut->setAttribute('Typ', '');
            $attrybut->setAttribute('Nazwa', 'Wartosc');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', 'K');
            $attrybut->setAttribute('Typ', '');
            $attrybut->setAttribute('Nazwa', 'Zawartosc');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', 'N');
            $attrybut->setAttribute('Typ', '');
            $attrybut->setAttribute('Nazwa', 'OplaconeUbezpieczenie');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', '668493078');
            $attrybut->setAttribute('Typ', '');
            $attrybut->setAttribute('Nazwa', 'KontaktPowiadomieniaNadawcy');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', 'M');
            $attrybut->setAttribute('Typ', '');
            $attrybut->setAttribute('Nazwa', 'FormaPowiadomieniaNadawcy');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', 'M');
            $attrybut->setAttribute('Typ', '');
            $attrybut->setAttribute('Nazwa', 'FormaPowiadomieniaAdresata');
            $przesylka->appendChild($attrybut);
            
            $phone = str_replace(" ", '', $order->phone);
            $phone = str_replace("+48", '', $phone);
            $attrybut = $domtree->createElement('Atrybut', $phone);
            $attrybut->setAttribute('Typ', '');
            $attrybut->setAttribute('Nazwa', 'KontaktPowiadomieniaAdresata');
            $przesylka->appendChild($attrybut);
            
            $attrybut = $domtree->createElement('Atrybut', '1');
            $attrybut->setAttribute('Typ', '');
            $attrybut->setAttribute('Nazwa', 'Wersja');
            $przesylka->appendChild($attrybut);
            
            $xmlZbior->appendChild($przesylka);
        }

        return $domtree->saveXML();
        
    }

}