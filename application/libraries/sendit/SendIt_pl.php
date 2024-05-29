<?php
class returnStruct_SPOrderPDF {
  public $status; // string
  public $message; // string
  public $code; // string
  public $orders; // Array
  public $pdf; // base64Binary
}

class returnStruct_SPOrderList {
  public $status; // string
  public $message; // string
  public $code; // string
  public $conditions; // listOrderConditions
  public $orders; // listOrderResults
}

class listOrderConditions {
  public $type; // int
  public $operand; // string
  public $operator; // string
  public $value; // string
  public $orderby; // string
  public $ordertype; // string
  public $limit; // int
  public $offset; // int
}

class listOrderResults {
  public $count; // int
  public $list; // listOrders
}

class returnStruct_SPOrderStatus {
  public $status; // string
  public $message; // string
  public $code; // string
  public $orders; // statOrderData
}

class statOrderDataSingle {
  public $orderNumber; // string
  public $statusNumber; // int
  public $statusInfo; // string
}

class returnStruct_SPOrderConfirmList {
  public $status; // string
  public $message; // string
  public $code; // string
  public $orderNumbers; // Array
}

class returnStruct_SPOrderSave {
  public $status; // string
  public $message; // string
  public $code; // string
  public $pricing; // singleOperatorPricing
  public $address; // rateAddress
  public $orderNumbers; // orderNumbers
}

class returnStruct_SPOrderRate {
  public $status; // string
  public $message; // string
  public $code; // string
  public $pricing; // pricingArray
  public $address; // rateAddress
}

class orderNumberSingle {
  public $status; // string
  public $message; // string
  public $code; // string
  public $orderNo; // string
  public $package; // package
  public $pallet; // package
}

class returnStruct_SPGetOrder {
  public $status; // string
  public $message; // string
  public $code; // string
  public $order; // orderDataOutput
  public $history; // orderHistory
}

class orderDataOutput {
  public $senderCountryCode; // string
  public $senderEmail; // string
  public $senderName; // string
  public $senderStreet; // string
  public $senderCity; // string
  public $senderPhoneNumber; // string
  public $senderZipCode; // string
  public $senderContactPerson; // string
  public $receiverCountryCode; // string
  public $receiverEmail; // string
  public $receiverName; // string
  public $receiverStreet; // string
  public $receiverCity; // string
  public $receiverPhoneNumber; // string
  public $receiverZipCode; // string
  public $receiverContactPerson; // string
  public $kP2; // int
  public $kP5; // int
  public $kP10; // int
  public $kP20; // int
  public $kP30; // int
  public $kP50; // int
  public $kP70; // int
  public $kPal; // int
  public $palletHeight; // int
  public $palletWeight; // int
  public $packA; // int
  public $packB; // int
  public $packC; // int
  public $packCode; // string
  public $sizesData; // sizesData
  public $senderBox; // string
  public $preferredBox; // string
  public $secondaryBox; // string
  public $deliveringCode; // string
  public $CODValue; // float
  public $INS; // float
  public $ROD; // int
  public $SRE; // int
  public $SSE; // int
  public $BYH; // int
  public $H24; // int
  public $deliveryTime; // string
  public $alerts; // alertsData
  public $NSTData; // nstData
  public $comment; // string
  public $content; // string
  public $courierName; // string
  public $LPNumber; // string
  public $trackingCodes; // Array
  public $userID; // int
  public $status; // int
  public $orderHash; // string
  public $orderID; // int
  public $orderNumber; // string
  public $finalNett; // float
  public $finalVAT; // float
  public $finalGross; // float
  public $finalQuantity; // int
  public $currency; // string
  public $invoiceDrawn; // int
  public $protocolNumber; // string
  public $protocolFlag; // int
}

class nstData {
  public $kP2; // int
  public $kP5; // int
  public $kP10; // int
  public $kP20; // int
  public $kP30; // int
  public $kP50; // int
  public $kP70; // int
}

class alertsData {
  public $receive; // alertTarget
  public $courier; // alertTarget
  public $advice; // alertTarget
  public $deliver; // alertTarget
  public $refuse; // alertTarget
}

class alertTarget {
  public $sender; // alertType
  public $receiver; // alertType
}

class alertType {
  public $sms; // int
  public $email; // int
}

class singleSizesData {
  public $width; // int
  public $height; // int
  public $depth; // int
  public $weight; // int
  public $COD; // float
  public $INS; // int
  public $content; // string
}

class orderHistoryStatus {
  public $statusID; // int
  public $statusNumber; // int
  public $statusInfo; // string
  public $date; // string
}

class returnStruct_SPServicesCheck {
  public $status; // string
  public $message; // string
  public $code; // string
  public $services; // listServices
}

class listServicesOperator {
  public $operator; // string
  public $terms; // Array
  public $services; // Array
  public $error; // string
}

class returnStruct_SPPackagesValidate {
  public $status; // string
  public $message; // string
  public $code; // string
  public $packages; // packagesList
}

class validateList {
  public $height; // int
  public $width; // int
  public $depth; // int
  public $weight; // int
  public $packsType; // string
  public $categoryList; // categoryList
}

class categoryPack {
  public $operator; // string
  public $packCategory; // string
  public $NST; // string
  public $error; // string
}

class package {
  public $height; // int
  public $width; // int
  public $depth; // int
  public $weight; // int
  public $packsType; // string
  public $COD; // float
  public $INS; // int
  public $content; // string
}


class singleOperatorPricing {
  public $operator; // string
  public $offline; // int
  public $deliveryTime; // string
  public $rates; // ratesArray
  public $overall; // overall
}

class overall {
  public $nett; // float
  public $vat; // float
  public $gross; // float
  public $quantity; // int
  public $description; // string
}

class singleRate {
  public $courier; // string
  public $userPaymentType; // string
  public $alerts; // alertTypes
  public $products; // productsArray
  public $services; // servicesArray
  public $overall; // overall
  public $messages; // messagesArray
  public $package; // package
  public $pallet; // package
}

class messageDetials {
  public $name; // string
  public $description; // string
}

class serviceOverall {
  public $name; // string
  public $value; // float
  public $nett; // float
  public $vat; // float
  public $gross; // float
  public $quantity; // int
  public $description; // string
}

class productOverall {
  public $name; // string
  public $nett; // float
  public $vat; // float
  public $gross; // float
  public $quantity; // int
  public $description; // string
}

class alertTypes {
  public $alertsSms; // alertsOverall
  public $alertsEmail; // alertsOverall
}

class alertsOverall {
  public $nett; // float
  public $vat; // float
  public $gross; // float
  public $quantity; // int
  public $description; // string
}

class rateResult {
  public $status; // int
  public $desc; // string
  public $error; // string
}

class rateAddress {
  public $senderCountryCode; // string
  public $senderEmail; // string
  public $senderName; // string
  public $senderStreet; // string
  public $senderCity; // string
  public $senderPhoneNumber; // string
  public $senderZipCode; // string
  public $senderContactPerson; // string
  public $receiverCountryCode; // string
  public $receiverEmail; // string
  public $receiverName; // string
  public $receiverStreet; // string
  public $receiverCity; // string
  public $receiverPhoneNumber; // string
  public $receiverZipCode; // string
  public $receiverContactPerson; // string
}

class orderData {
  public $senderCountryCode; // string
  public $senderEmail; // string
  public $senderName; // string
  public $senderStreet; // string
  public $senderCity; // string
  public $senderPhoneNumber; // string
  public $senderZipCode; // string
  public $senderContactPerson; // string
  public $receiverCountryCode; // string
  public $receiverEmail; // string
  public $receiverName; // string
  public $receiverStreet; // string
  public $receiverCity; // string
  public $receiverPhoneNumber; // string
  public $receiverZipCode; // string
  public $receiverContactPerson; // string
  public $packages; // package[]
  public $kPal; // int
  public $palletHeight; // int
  public $palletWeight; // int
  public $palletCOD; // int
  public $palletINS; // int
  public $palletContent; // string
  public $senderBox; // string
  public $preferredBox; // string
  public $ROD; // int
  public $SRE; // int
  public $SSE; // int
  public $BYH; // int
  public $H24; // int
  public $deliveryTime; // string
  public $alerts; // alertTarget
  public $comment; // string
  public $protocolFlag; // int
}

class PointShort {
  public $avaliable; // boolean
  public $id; // string
  public $postingPoint; // boolean
  public $deliveryPoint; // boolean
  public $name; // string
  public $paymentAvailable; // boolean
  public $location; // Location
}

class PointFull {
  public $avaliable; // boolean
  public $id; // string
  public $postingPoint; // boolean
  public $deliveryPoint; // boolean
  public $name; // string
  public $street; // string
  public $streetNo; // string
  public $flatNo; // string
  public $city; // string
  public $postCode; // string
  public $description; // string
  public $paymentPointDesc; // string
  public $paymentAvailable; // boolean
  public $province; // string
  public $county; // string
  public $phone; // string
  public $location; // Location
  public $additionalInfo; // AdditionalInfo
}

class Location {
  public $lat; // string
  public $lng; // string
}

class AdditionalInfo {
  public $expressServiceHours; // ExpressServiceHours
  public $standardServiceHours; // StandardServiceHours
  public $operatingHours; // OperatingHours
}

class Express {
  public $DayOfWeek; // string
  public $PickUpDetails; // ExpressTime
}

class ExpressTime {
  public $PickUpTime; // string
}

class Standard {
  public $DayOfWeek; // string
  public $PickUpDetails; // StandardTime
}

class StandardTime {
  public $PickUpTime; // string
}

class DayInfo {
  public $Day; // string
  public $DayText; // string
  public $OpenHours; // string
  public $CloseHours; // string
  public $BreakFromHours; // string
  public $BreakToHours; // string
}

class returnStruct_SPProtocolGenerate {
  public $status; // string
  public $message; // string
  public $code; // string
  public $protocols; // genProtocolsData
}

class returnStruct_SPGetProtocol {
  public $status; // string
  public $message; // string
  public $code; // string
  public $protocol; // protocolData
}

class returnStruct_SPProtocolList {
  public $status; // string
  public $message; // string
  public $code; // string
  public $conditions; // listProtocolConditions
  public $protocols; // listProtocolResults
}

class returnStruct_SPProtocolPDF {
  public $status; // string
  public $message; // string
  public $code; // string
  public $protocols; // Array
  public $pdf; // base64Binary
}

class genProtocolsDataSingle {
  public $orderNumber; // string
  public $protocolNumber; // string
}

class protocolData {
  public $protocolID; // int
  public $dateAdded; // string
  public $userID; // int
  public $protocolNumber; // string
  public $courierName; // string
  public $senderName; // string
  public $senderStreet; // string
  public $senderZipCode; // string
  public $senderCity; // string
  public $senderCountryCode; // string
  public $senderPhoneNumber; // string
  public $senderEmail; // string
  public $senderContactPerson; // string
  public $orders; // Array
  public $protocolHash; // string
}

class listProtocolConditions {
  public $operand; // string
  public $operator; // string
  public $value; // string
  public $orderby; // string
  public $ordertype; // string
  public $limit; // int
  public $offset; // int
}

class listProtocolResults {
  public $count; // int
  public $list; // listProtocols
}


/**
 * SendIt_pl class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class SendIt_pl extends SoapClient {

  private static $classmap = array(
                                    'returnStruct_SPOrderPDF' => 'returnStruct_SPOrderPDF',
                                    'returnStruct_SPOrderList' => 'returnStruct_SPOrderList',
                                    'listOrderConditions' => 'listOrderConditions',
                                    'listOrderResults' => 'listOrderResults',
                                    'returnStruct_SPOrderStatus' => 'returnStruct_SPOrderStatus',
                                    'statOrderDataSingle' => 'statOrderDataSingle',
                                    'returnStruct_SPOrderConfirmList' => 'returnStruct_SPOrderConfirmList',
                                    'returnStruct_SPOrderSave' => 'returnStruct_SPOrderSave',
                                    'returnStruct_SPOrderRate' => 'returnStruct_SPOrderRate',
                                    'orderNumberSingle' => 'orderNumberSingle',
                                    'returnStruct_SPGetOrder' => 'returnStruct_SPGetOrder',
                                    'orderDataOutput' => 'orderDataOutput',
                                    'nstData' => 'nstData',
                                    'alertsData' => 'alertsData',
                                    'alertTarget' => 'alertTarget',
                                    'alertType' => 'alertType',
                                    'singleSizesData' => 'singleSizesData',
                                    'orderHistoryStatus' => 'orderHistoryStatus',
                                    'returnStruct_SPServicesCheck' => 'returnStruct_SPServicesCheck',
                                    'listServicesOperator' => 'listServicesOperator',
                                    'returnStruct_SPPackagesValidate' => 'returnStruct_SPPackagesValidate',
                                    'validateList' => 'validateList',
                                    'categoryPack' => 'categoryPack',
                                    'package' => 'package',
                                    'singleOperatorPricing' => 'singleOperatorPricing',
                                    'overall' => 'overall',
                                    'singleRate' => 'singleRate',
                                    'messageDetials' => 'messageDetials',
                                    'serviceOverall' => 'serviceOverall',
                                    'productOverall' => 'productOverall',
                                    'alertTypes' => 'alertTypes',
                                    'alertsOverall' => 'alertsOverall',
                                    'rateResult' => 'rateResult',
                                    'rateAddress' => 'rateAddress',
                                    'orderData' => 'orderData',
                                    'PointShort' => 'PointShort',
                                    'PointFull' => 'PointFull',
                                    'Location' => 'Location',
                                    'AdditionalInfo' => 'AdditionalInfo',
                                    'Express' => 'Express',
                                    'ExpressTime' => 'ExpressTime',
                                    'Standard' => 'Standard',
                                    'StandardTime' => 'StandardTime',
                                    'DayInfo' => 'DayInfo',
                                    'returnStruct_SPProtocolGenerate' => 'returnStruct_SPProtocolGenerate',
                                    'returnStruct_SPGetProtocol' => 'returnStruct_SPGetProtocol',
                                    'returnStruct_SPProtocolList' => 'returnStruct_SPProtocolList',
                                    'returnStruct_SPProtocolPDF' => 'returnStruct_SPProtocolPDF',
                                    'genProtocolsDataSingle' => 'genProtocolsDataSingle',
                                    'protocolData' => 'protocolData',
                                    'listProtocolConditions' => 'listProtocolConditions',
                                    'listProtocolResults' => 'listProtocolResults',
                                   );

  public function SendIt_pl($wsdl = "snditSandbox.xml", $options = array()) {
    foreach(self::$classmap as $key => $value) {
      if(!isset($options['classmap'][$key])) {
        $options['classmap'][$key] = $value;
      }
    }
    parent::__construct($wsdl, $options);
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param int $getAddress
   * @param string $lang
   * @return returnStruct_SPGetUser
   */
  public function SPGetUser($apiKey, $userHash, $getAddress, $lang) {
    return $this->__soapCall('SPGetUser', array($apiKey, $userHash, $getAddress, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $login
   * @param string $password
   * @param string $lang
   * @return returnStruct_SPUserLogin
   */
  public function SPUserLogin($apiKey, $login, $password, $lang) {
    return $this->__soapCall('SPUserLogin', array($apiKey, $login, $password, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param string $field
   * @param string $lang
   * @return returnStruct_SPUserField
   */
  public function SPUserField($apiKey, $userHash, $field, $lang) {
    return $this->__soapCall('SPUserField', array($apiKey, $userHash, $field, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param userAddressIn $addressData
   * @param string $lang
   * @return returnStruct_SPAddressAdd
   */
  public function SPAddressAdd($apiKey, $userHash, userAddressIn $addressData, $lang) {
    return $this->__soapCall('SPAddressAdd', array($apiKey, $userHash, $addressData, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param Array $addresses
   * @param string $lang
   * @return returnStruct_SPAddressDelete
   */
  public function SPAddressDelete($apiKey, $userHash, Array $addresses, $lang) {
    return $this->__soapCall('SPAddressDelete', array($apiKey, $userHash, $addresses, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param int $limit
   * @param int $offset
   * @param string $orderBy
   * @param string $orderType
   * @param string $operand
   * @param string $operator
   * @param string $value
   * @param string $lang
   * @return returnStruct_SPAddressList
   */
  public function SPAddressList($apiKey, $userHash, $limit, $offset, $orderBy, $orderType, $operand, $operator, $value, $lang) {
    return $this->__soapCall('SPAddressList', array($apiKey, $userHash, $limit, $offset, $orderBy, $orderType, $operand, $operator, $value, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param int $addressID
   * @param int $flag
   * @param string $lang
   * @return returnStruct_SPGetAddress
   */
  public function SPGetAddress($apiKey, $userHash, $addressID, $flag, $lang) {
    return $this->__soapCall('SPGetAddress', array($apiKey, $userHash, $addressID, $flag, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param string $orderNumber
   * @param string $lang
   * @return returnStruct_SPGetOrder
   */
  public function SPGetOrder($apiKey, $userHash, $orderNumber, $lang) {
    return $this->__soapCall('SPGetOrder', array($apiKey, $userHash, $orderNumber, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param string $senderZipCode
   * @param string $senderCountryCode
   * @param string $receiverZipCode
   * @param string $receiverCountryCode
   * @param int $pallet
   * @param string $lang
   * @return returnStruct_SPServicesCheck
   */
  public function SPServicesCheck($apiKey, $userHash, $senderZipCode, $senderCountryCode, $receiverZipCode, $receiverCountryCode, $pallet, $lang) {
    return $this->__soapCall('SPServicesCheck', array($apiKey, $userHash, $senderZipCode, $senderCountryCode, $receiverZipCode, $receiverCountryCode, $pallet, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param packages $packages
   * @param string $countryCode
   * @param string $lang
   * @return returnStruct_SPPackagesValidate
   */
  public function SPPackagesValidate($apiKey, $userHash, $packages, $countryCode, $lang) {
    return $this->__soapCall('SPPackagesValidate', array($apiKey, $userHash, $packages, $countryCode, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param orderData $orderData
   * @param string $courier
   * @param string $lang
   * @return returnStruct_SPOrderRate
   */
  public function SPOrderRate($apiKey, $userHash, orderData $orderData, $courier, $lang) {
    return $this->__soapCall('SPOrderRate', array($apiKey, $userHash, $orderData, $courier, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param orderData $orderData
   * @param string $courier
   * @param string $lang
   * @return returnStruct_SPOrderSave
   */
  public function SPOrderSave($apiKey, $userHash, orderData $orderData, $courier, $lang) {
    return $this->__soapCall('SPOrderSave', array($apiKey, $userHash, $orderData, $courier, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param orderData $orderData
   * @param string $courier
   * @param string $lang
   * @return returnStruct_SPOrderSave
   */
  public function SPOrderConfirm($apiKey, $userHash, orderData $orderData, $courier, $lang) {
    return $this->__soapCall('SPOrderConfirm', array($apiKey, $userHash, $orderData, $courier, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param Array $orders
   * @param string $lang
   * @return returnStruct_SPOrderConfirmList
   */
  public function SPOrderConfirmList($apiKey, $userHash, Array $orders, $lang) {
    return $this->__soapCall('SPOrderConfirmList', array($apiKey, $userHash, $orders, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param Array $orders
   * @param string $lang
   * @return returnStruct_SPOrderStatus
   */
  public function SPOrderStatus($apiKey, $userHash, Array $orders, $lang) {
    return $this->__soapCall('SPOrderStatus', array($apiKey, $userHash, $orders, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param int $type
   * @param int $limit
   * @param int $offset
   * @param string $orderBy
   * @param string $orderType
   * @param string $operand
   * @param string $operator
   * @param string $value
   * @param string $lang
   * @return returnStruct_SPOrderList
   */
  public function SPOrderList($apiKey, $userHash, $type, $limit, $offset, $orderBy, $orderType, $operand, $operator, $value, $lang) {
    return $this->__soapCall('SPOrderList', array($apiKey, $userHash, $type, $limit, $offset, $orderBy, $orderType, $operand, $operator, $value, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param Array $orders
   * @param string $lang
   * @return returnStruct_SPOrderPDF
   */
  public function SPOrderPDF($apiKey, $userHash, Array $orders, $lang) {
    return $this->__soapCall('SPOrderPDF', array($apiKey, $userHash, $orders, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param string $courier
   * @return list(string $code, string $message, string $status, PointsFull $points)
   */
  public function SPGetPointsFull($apiKey, $userHash, $courier) {
    return $this->__soapCall('SPGetPointsFull', array($apiKey, $userHash, $courier),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param string $courier
   * @return list(string $code, string $message, string $status, PointsShort $points)
   */
  public function SPGetPointsShort($apiKey, $userHash, $courier) {
    return $this->__soapCall('SPGetPointsShort', array($apiKey, $userHash, $courier),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param string $courier
   * @param string $pointID
   * @return list(string $code, string $message, string $status, PointFull $point)
   */
  public function SPGetPoint($apiKey, $userHash, $courier, $pointID) {
    return $this->__soapCall('SPGetPoint', array($apiKey, $userHash, $courier, $pointID),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param Array $orders
   * @param string $lang
   * @return returnStruct_SPProtocolGenerate
   */
  public function SPProtocolGenerate($apiKey, $userHash, Array $orders, $lang) {
    return $this->__soapCall('SPProtocolGenerate', array($apiKey, $userHash, $orders, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param string $protocolNumber
   * @param string $lang
   * @return returnStruct_SPGetProtocol
   */
  public function SPGetProtocol($apiKey, $userHash, $protocolNumber, $lang) {
    return $this->__soapCall('SPGetProtocol', array($apiKey, $userHash, $protocolNumber, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param int $limit
   * @param int $offset
   * @param string $orderBy
   * @param string $orderType
   * @param string $operand
   * @param string $operator
   * @param string $value
   * @param string $lang
   * @return returnStruct_SPProtocolList
   */
  public function SPProtocolList($apiKey, $userHash, $limit, $offset, $orderBy, $orderType, $operand, $operator, $value, $lang) {
    return $this->__soapCall('SPProtocolList', array($apiKey, $userHash, $limit, $offset, $orderBy, $orderType, $operand, $operator, $value, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $apiKey
   * @param string $userHash
   * @param Array $protocols
   * @param string $lang
   * @return returnStruct_SPProtocolPDF
   */
  public function SPProtocolPDF($apiKey, $userHash, Array $protocols, $lang) {
    return $this->__soapCall('SPProtocolPDF', array($apiKey, $userHash, $protocols, $lang),       array(
            'uri' => 'http://api.sandbox-sendit.pl/public.php',
            'soapaction' => ''
           )
      );
  }

}

?>
