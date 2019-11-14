<?php
@session_start();
class GoogleFeedClient {

    private $merchantId;

    private $url;

    public function setMerchant($id) {
        $this->merchantId = $id;
    }
    public function getMerchant() {
        return $this->merchantId;
    }

    public function setURL($u) {
        $this->url = $u;
    }
    public function getURL() {
        return $this->url;
    }

    function getAllProducts() {

     $list = $_SESSION['product_list'];
     return $list;

    }

    function test(){
      echo  $this->getMerchant();
    }
}

 ?>
