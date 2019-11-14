<?php
require_once 'vendor/autoload.php';


session_start(); //starts a session
session_unset();

$url = $_GET['site_url'];

$client = new Google_Client();
$client->setApplicationName('Google Shopping Feed');
$client->setClientId('664053922721-si93k7g4b8gt349dgo8jjf2d8s5upth0.apps.googleusercontent.com');
$client->setClientSecret('5wUo0wRm_HXdfOJBQZ7mCDKy');
$client->setRedirectUri($url.'/dashboard/settings/?tab=3');
$client->setScopes('https://www.googleapis.com/auth/content');


if (isset($_GET['code'])) {
  $token = $client->authenticate($_GET['code']);
  $_SESSION['oauth_access_token'] = $token;

} else {
  header('Location: ' . $client->createAuthUrl());

  exit;
}


?>
