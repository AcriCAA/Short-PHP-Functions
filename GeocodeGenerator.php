<?php
class Geocode {
   // Request data
   public $address = '';
   public $zip = '';
   public $city = '';
   public $country = '';

   // Response data
   public $latitude = '0.000000000000000';
   public $longitude = '0.000000000000000';
   public $valid = false;
}

class GeocodeGenerator {
   // Request
   private $rAPIToken = '';
   private $rAPIURL = '://maps.googleapis.com/maps/api/geocode/json?';
   private $rResults;
   private $rURL;

   public function __construct($GoogleKey) {
      $this -> rAPIToken = $GoogleKey;
      $this -> rAPIURL = (!$GoogleKey) ? 'https' . $this -> rAPIURL . "key={" . $GoogleKey . "}" : 'http' . $this -> rAPIURL;
   }

   public function getGeo($Geocode) {
      // Build address string
      $rAddress = $Geocode -> address . ', ' . $Geocode -> zip . ' ' . $Geocode -> city . ', ' . $Geocode -> country;
      // Build request URL
      $this -> rURL = $this -> rAPIURL . "&address=" . urlencode($rAddress);
      // Start cURL
      $cURL = curl_init();
      curl_setopt($cURL, CURLOPT_URL, $this -> rURL);
      curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
      // Fetch results
      $rResults = json_decode(curl_exec($cURL));
      if ($rResults && $rResults -> status === 'OK') {
         $this -> rResults = $rResults;
         $Geocode -> address = $rResults -> results[0] -> formatted_address;
         $Geocode -> latitude = $rResults -> results[0] -> geometry -> location -> lat;
         $Geocode -> longitude = $rResults -> results[0] -> geometry -> location -> lng;
         $Geocode -> valid = true;
      } else {
         $Geocode -> address = $rAddress;
         $Geocode -> latitude = '0.000000000000000';
         $Geocode -> longitude = '0.000000000000000';
         $Geocode -> valid = false;
      }
      return $Geocode;
   }

}
?>
