<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/ 

$route['default_controller'] = "home";
$route['404_override'] = 'home/error404';

require_once 'database.php';

$dbr['hostname'] = $db['default']['hostname']; 
$dbr['username'] = $db['default']['username'];
$dbr['password'] = $db['default']['password'];
$dbr['database'] = $db['default']['database'];
$conn = new mysqli($dbr['hostname'], $dbr['username'], $dbr['password'], $dbr['database']);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT id, news_id, custom_url FROM duo_news_translations";
$result = $conn->query($sql);
//var_dump($result);
if ($result->num_rows > 0) {
    
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        if(!empty($row['custom_url'])){
        $route['(\w{2})/'.'blog/'.$row['custom_url']] = 'aktualnosci/pokaz/'.$row['news_id'];
    }
    }
}
$result->free();
$sql2 = "SELECT id, page_id, custom_url FROM duo_pages_translations";
$result2 = $conn->query($sql2);
//var_dump($result);
if ($result2->num_rows > 0) {
    
    // output data of each row
    while($row = mysqli_fetch_assoc($result2)) {
        if(!empty($row['custom_url'])){
            switch($row['custom_url']){
                case 'kontakt':
                    $route['(\w{2})/'.'kontakt'] = 'kontakt/index';
                    break;
				case 'contact-us':
                    $route['(\w{2})/'.'contact-us'] = 'kontakt/index';
                    break;
                default:
                    $route['(\w{2})/'.$row['custom_url']] = 'page/index/'.$row['page_id'];
                    break;
            }
        
    }
    }
}
$result2->free();

$sql3 = "SELECT id, gallery_id, custom_url FROM duo_gallery_translations";
$result3 = $conn->query($sql3);
//var_dump($result);
if ($result3->num_rows > 0) {

    // output data of each row
    while ($row = mysqli_fetch_assoc($result3)) {
        if (!empty($row['custom_url'])) {
            $route['galeria/'.$row['custom_url']] = 'gallery/show/' . $row['gallery_id'];
            $route['realizacje/'.$row['custom_url']] = 'gallery/show/' . $row['gallery_id'];
        }
    }
}
$result3->free();

$sql4 = "SELECT id, product_id, custom_url FROM duo_products_translations";
$result4 = $conn->query($sql4);
//var_dump($result);
if ($result4->num_rows > 0) {

    // output data of each row
    while ($row = mysqli_fetch_assoc($result4)) {
        if (!empty($row['custom_url'])) {
            $route['(\w{2})/'.'produkt/'.$row['custom_url']] = 'oferta/product/' . $row['product_id'];
        }
    }
}
$result4->free();

$sql5 = "SELECT id, offer_category_id, custom_url FROM duo_offer_categories_translations";
$result5 = $conn->query($sql5);
//var_dump($result);
if ($result5->num_rows > 0) {

    // output data of each row
    while ($row = mysqli_fetch_assoc($result5)) {
        if (!empty($row['custom_url'])) {
            $route[$row['custom_url']] = 'oferta/category/' . $row['offer_category_id'];
           // $route[$row['custom_url'].'/(:number)'] = 'oferta/category/' . $row['offer_category_id'].'/$1';
        }
    }
}
$result5->free();
$conn->close();
//var_dump($route);
// Jeżeli są wersje językowe
$route['(\w{2})/duocms'] = 'duocms/login';
$route['(\w{2})/o-nas'] = 'page/index/1';

//$route['wspolpraca'] = 'page/index/27';
//$route['platnosci'] = 'page/index/28';
//$route['dostawa'] = 'page/index/29';
//$route['poradnik-klienta'] = 'page/index/47';
//$route['uslugi'] = 'page/index/45';
//$route['cennik'] = 'page/index/46'; 
//$route['mapa-strony'] = 'merchant/links'; 
//$route['o-nas'] = 'page/index/1';
$route['(\w{2})/blog'] = 'aktualnosci/index';
$route['(\w{2})/blog/(:num)'] = 'aktualnosci/index/$2';
$route['(\w{2})/blog/(:num)-(:any)'] = 'aktualnosci/pokaz/$2';
//$route['klienci/odbiorcy-wycen'] = 'aktualnosci/klienci/1';
//$route['klienci/banki'] = 'aktualnosci/klienci/2';
//$route['klienci/moi-klienci'] = 'aktualnosci/klienci/3';
$route['(\w{2})/logowanie'] = 'account/login';
$route['(\w{2})/wyloguj-sie'] = 'account/logout';

//$route['certyfikaty'] = 'page/index/29';


//$route['nowosci'] = 'oferta/index2';

// SHOW_SPECIAL
//$route['nowosci'] = 'oferta/show_special/new';
//$route['promocje'] = 'oferta/show_special/promo';
//$route['popularne'] = 'oferta/show_special/bestseller';

//$route['regulamin'] = 'page/index/32';

$route['(\w{2})/rejestracja'] = 'account/register';
//$route['wyszukiwarka'] = 'oferta/search';
//$route['Wyszukiwarka/ajax_search_prompt'] = 'oferta/ajax_search_prompt';
//$route['Wyszukiwarka/ajax_search_prompt/(:any)'] = 'oferta/ajax_search_prompt/$1';

//$route['nowosci'] = 'oferta/show_special/new';
//$route['promocje'] = 'oferta/show_special/promo';
//$route['bestsellery'] = 'oferta/show_special/bestseller';

//$route['aktualnosci/(:num)-(:any)'] = 'aktualnosci/pokaz/$1';
//$route['aktualnosci'] = 'aktualnosci/index';
//$route['warsztatowo/(:num)-(:any)'] = 'aktualnosci/pokaz/$1';
//$route['warsztatowo'] = 'aktualnosci/index';
//$route['szkolenia'] = 'aktualnosci/index/1';
//$route['konferencje'] = 'aktualnosci/index/2';
//$route['zaplecze_techniczne'] = 'aktualnosci/index/3';
//$route['obiekty'] = 'aktualnosci/index/4';
//$route['referencje'] = 'gallery/show/44';
//$route['referencje'] = 'gallery/show/44';
//$route['promocje'] = 'aktualnosci/promocje';
//$route['oferta/(:num)-(:any)'] = 'oferta/index/$1';
//$route['produkty'] = 'oferta/products_all';
//$route['menu'] = 'oferta/category';
//$route['menu'] = 'oferta/index2';


//$route['pracownia-terminalowa'] = 'page/index/48';
//$route['zastosowania'] = 'page/index/47';
//$route['zastosowania/edukacja'] = 'page/show/49';
//$route['zastosowania/biznes'] = 'page/show/50';
//$route['zastosowania/administracja'] = 'page/show/51';
//$route['support'] = 'page/index/53';
//$route['support/lista-kompatybilnosci'] = 'page/index/52';
//$route['support/download'] = 'page/index/54';
//$route['galeria'] = 'gallery/index';
//$route['realizacje'] = 'gallery/index';
//$route['realizacje/(:num)'] = 'gallery/index/$1';
//$route['realizacje/(:num)-(:any)'] = 'gallery/show/$1';
//$route['wdrozenia/(:num)'] = 'gallery/index/$1';
//$route['wdrozenia/(:num)-(:any)'] = 'gallery/show/$1';

$route['(\w{2})/produkty'] = 'oferta/products';
$route['(\w{2})/produkt/(:num)-(:any)'] = 'oferta/product/$2';

$route['(\w{2})/products'] = 'oferta/products';
$route['(\w{2})/products/(:num)-(:any)'] = 'oferta/product/$2';

//$route['referencje'] = 'gallery/index/2'; 
$route['(\w{2})/dodaj-do-koszyka/(:num)'] = 'oferta/add_to_basket/$2';
$route['(\w{2})/dodaj-do-koszyka/(:num)-(:any)'] = 'oferta/add_to_basket/$2';
$route['(\w{2})/koszyk'] = 'zamowienie/basket';
//$route['zamowienia'] = 'oferta/index';

//$route['currency/(:num)'] = 'currency/index/$1';

//$route['oferta'] = 'oferta/index2';
//
//$route['oferta/category/(:num)'] = 'oferta/category/$1';
//$route['oferta/category/(:num)-(:any)/(:num)'] = 'oferta/category/$1/$1';
//$route['oferta/category/(:num)-(:any)'] = 'oferta/category/$1';
//$route['oferta/(:num)-(:any)'] = 'oferta/category/$1';
//$route['oferta/kategoria/(:num)'] = 'oferta/category/$1';
//$route['oferta/kategoria/(:num)-(:any)/(:num)'] = 'oferta/category/$1/$1';
//$route['kategoria/(:num)-(:any)'] = 'oferta/category/$1';
//$route['oferta/(:num)-(:any)'] = 'oferta/category/$1';
//$route['kategoria/(:num)-(:any)/(:num)'] = 'oferta/category/$1/$3';

$route['(\w{2})/page/(:num)'] = 'page/index/$2';
$route['(\w{2})/page/(:num)-(:any)'] = 'page/index/$2';

$route['(\w{2})/polityka-prywatnosci'] = 'page/index/5'; 
//$route['regulamin'] = 'page/index/26'; 
$route['(\w{2})/moje-konto'] = 'account';
$route['(\w{2})/moje-zamowienia'] = 'account/my_orders';
$route['(\w{2})/historia-transakcji'] = 'account/transactions_history';
//$route['product/(:num)'] = 'oferta/product/$1';
//$route['product/(:num)-(:any)'] = 'oferta/product/$1';
//$route['produkt/(:num)'] = 'oferta/product/$1';

$route['(\w{2})/duocms/products/(:num)'] = 'duocms/products/index/$2';
$route['(\w{2})/duocms/products'] = 'duocms/products/index/1';
$route['(\w{2})/duocms/orders/(:num)'] = 'duocms/orders/index/$2';

// Poniższe odkomentować wyłącznie w przypadku wersji językowych
$route['(\w{2})/(.*)'] = '$2';
$route['(\w{2})'] = $route['default_controller'];


/* End of file routes.php */
/* Location: ./application/config/routes.php */
