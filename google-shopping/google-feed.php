<?php
require_once 'vendor/autoload.php';


session_start(); //starts a session

$url = $_GET['site_url'];

$client = new Google_Client();
$client->setApplicationName('Google Shopping Feed');
$client->setClientId('664053922721-si93k7g4b8gt349dgo8jjf2d8s5upth0.apps.googleusercontent.com');
$client->setClientSecret('5wUo0wRm_HXdfOJBQZ7mCDKy');
$client->setRedirectUri($url.'/dashboard/settings/?tab=3');
$client->setScopes('https://www.googleapis.com/auth/content');

if (isset($_SESSION['oauth_access_token'])) {

  $client->setAccessToken($_SESSION['oauth_access_token']);

}

$service = new Google_Service_ShoppingContent($client);

$merchantId = $_GET['merchant'];
$products = $service->products->listProducts($merchantId);
$parameters = array();

$datas = array();


foreach ($products->getResources() as $data) {
   $datalist = array();

   $offerid = $data->getOfferId();
   $title = $data->getTitle();
   $description = $data->getDescription();
   $image = $data->getImageLink();
   $price = $data->getPrice()->getValue();
   $sale = $data->getSalePrice();
   $brand = $data->getBrand();
   $color = $data->getColor();
   $sizes = $data->getSizes()[0];

   $datalist = array(
              'offerid' => $offerid,
              'title' => $title,
              'description' => $description,
              'image' => $image,
              'price' => $price,
              'sale' => $sale,
              'brand' => $brand,
              'color' => $color,
              'sizes' => $sizes
            );

    array_push($datas, $datalist);
}
 

$_SESSION['product_list'] = $datas;


?>
