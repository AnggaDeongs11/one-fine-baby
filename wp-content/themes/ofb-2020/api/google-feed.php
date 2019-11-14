<?php

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

      $data = array(
         'merchant_id' => $this->getMerchant()
        );

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_POST, 1);

        curl_setopt($curl, CURLOPT_URL, $this->getURL());

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($curl);

        $info = curl_getinfo($curl);

        curl_close($curl);

        echo $result;

    }

    function test(){
      echo  $this->getMerchant();
    }
}

 ?>
