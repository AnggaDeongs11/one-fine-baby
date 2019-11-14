<?php
require_once 'vendor/autoload.php';


session_start(); //starts a session


$client = new Google_Client();
$client->setApplicationName('Google Shopping Feed');
$client->setClientId('664053922721-si93k7g4b8gt349dgo8jjf2d8s5upth0.apps.googleusercontent.com');
$client->setClientSecret('5wUo0wRm_HXdfOJBQZ7mCDKy');
$client->setRedirectUri('http://ofb-blog.mrkelly.ninja/google-shopping/google-api.php');
$client->setScopes('https://www.googleapis.com/auth/content');

if (isset($_SESSION['oauth_access_token'])) {
  $client->setAccessToken($_SESSION['oauth_access_token']);

} elseif (isset($_GET['code'])) {
  $token = $client->authenticate($_GET['code']);
  $_SESSION['oauth_access_token'] = $token;
} else {
  header('Location: ' . $client->createAuthUrl());

  exit;
}

$service = new Google_Service_ShoppingContent($client);

$merchantId = $_SESSION['merchant'];
$products = $service->products->listProducts($merchantId);
$parameters = array();

  foreach ($products->getResources() as $product) {
   echo $product->getTitle()."<br>";
  }

?>
