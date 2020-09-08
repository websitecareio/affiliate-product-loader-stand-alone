<?php
/*
* Affiliate Product Loader
* Version: 1.0.0
* Description: Load affiliate products from XML feeds
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("wcio_apl_config.php");

class wcio_affiliate_product_loader {

      // Construct
      function __construct($pdo) {
            $this->pdo = $pdo;
      }


      function wcio_apl_displayPrice( $price) {

            $price = number_format($price/100, 2, ',', '.');

            return $price;

      }

      // Strip invalid XML characters
      // Thanks to https://github.com/aws/aws-sdk-php/issues/1099#issuecomment-251846122 for providing the fix to invalid characters
      // /\x0A/ => \u000A have been removed from the fix.
      function wcio_apl_strip_characters( $string ) {

            //$string = mb_convert_encoding($string, "utf-8");
            return preg_replace(
                  array(
                        '/\x00/', '/\x01/', '/\x02/', '/\x03/', '/\x04/',
                        '/\x05/', '/\x06/', '/\x07/', '/\x08/', '/\x09/',
                        '/\x0B/','/\x0C/','/\x0D/', '/\x0E/', '/\x0F/', '/\x10/', '/\x11/',
                        '/\x12/','/\x13/','/\x14/','/\x15/', '/\x16/', '/\x17/', '/\x18/',
                        '/\x19/','/\x1A/','/\x1B/','/\x1C/','/\x1D/', '/\x1E/', '/\x1F/'
                  ),
                  array(
                        "\u0000", "\u0001", "\u0002", "\u0003", "\u0004",
                        "\u0005", "\u0006", "\u0007", "\u0008", "\u0009",
                        "\u000B", "\u000C", "\u000D", "\u000E", "\u000F", "\u0010", "\u0011",
                        "\u0012", "\u0013", "\u0014", "\u0015", "\u0016", "\u0017", "\u0018",
                        "\u0019", "\u001A", "\u001B", "\u001C", "\u001D", "\u001E", "\u001F"
                  ),
                  $string
            );

      }


      // Remove all products from the table with prodcuts for feedId
      function wcio_apl_remove_products( $feedId ) {

            $stmt = $this->pdo->prepare("DELETE FROM wcio_apl_products WHERE feedId = :feedId");
            $stmt->execute(array(
                  ":feedId" => (int)$feedId
            ));

      }




      // Save product to database
      function wcio_apl_save_product( $feedId, $feedUrl, $productData ) {

                  $stmt = $this->pdo->prepare("INSERT INTO wcio_apl_products (
                        feedId,
                        feedUrl,
                        productDealer,
                        category,
                        brand,
                        productId,
                        productEan,
                        productName,
                        productDescription,
                        productPrice,
                        productPriceOld,
                        productPriceDiscount,
                        productImage,
                        productUrl,
                        created
                  ) VALUES (
                        :feedId,
                        :feedUrl,
                        :productDealer,
                        :category,
                        :brand,
                        :productId,
                        :productEan,
                        :productName,
                        :productDescription,
                        :productPrice,
                        :productPriceOld,
                        :productPriceDiscount,
                        :productImage,
                        :productUrl,
                        :created
                  )");
                  $stmt->execute(array(
                        ":feedId" => $feedId,
                        ":feedUrl" => $feedUrl,
                        ":productDealer" => $productData->forhandler,
                        ":category" => $productData->kategorinavn,
                        ":brand" => $productData->brand,
                        ":productId" => $productData->produktid,
                        ":productEan" => $productData->ean,
                        ":productName" => $productData->produktnavn,
                        ":productDescription" => $productData->beskrivelse,
                        ":productPrice" => number_format((float)$productData->nypris, 2, '.', '')*100,
                        ":productPriceOld" => $productData->gammelpris,
                        ":productPriceDiscount" => $productData->rabat,
                        ":productImage" => $productData->billedurl,
                        ":productUrl" => $productData->vareurl,
                        ":created" => time()
                  ));

      }




      // Update feed products - If feedId set, then only this feed will be updated
      function wcio_apl_update_feed( $feedId = NULL ) {

            if( $feedId ) {

                  // Fetch feed from DB and update the prodcuts table
                  $stmt = $this->pdo->prepare("SELECT id,feedUrl FROM wcio_apl_feeds WHERE id = :id");
                  $stmt->execute(array(
                        ":id" => $feedId,
                  ));
                  $getData = $stmt->fetchAll();

            } else {

                  // Fetch all feeds from DB and update the prodcuts table
                  $stmt = $this->pdo->prepare("SELECT id,feedUrl FROM wcio_apl_feeds ORDER BY id");
                  $stmt->execute();
                  $getData = $stmt->fetchAll();

            }

            // Loop all feeds
            foreach( $getData as $data ) {

                  $feedId = $data["id"];
                  $feedUrl = $data["feedUrl"];

                  // Remove all products from the product table where feedId is current one
                  $this->wcio_apl_remove_products( $feedId );

                  // Fetch XML feed with curl
                  $curlInit = curl_init();
                  curl_setopt_array($curlInit, [
      	    	      CURLOPT_RETURNTRANSFER => 1,
      	    	      CURLOPT_URL => $feedUrl
      		]);
                  $curlResponse = curl_exec($curlInit);
                  curl_close($curlInit);

                  echo "Loading XML string for feed: $feedId<br>";
                  // XML string to object
                  $xmlResponse = $this->wcio_apl_strip_characters( $curlResponse );
                  $xmlResponse = simplexml_load_string($xmlResponse);

                  // Loop the products and inset into table
                  foreach( $xmlResponse AS $productData ) {

                        // Save the product to database
                        $this->wcio_apl_save_product($feedId, $feedUrl, $productData);

                  }

                  // Update the field "updated" to match current time
                  $stmt = $this->pdo->prepare("UPDATE wcio_apl_feeds SET updated = :updated WHERE id = :id");
                  $stmt->execute(array(
                        ":updated" => time(),
                        ":id" => $feedId
                  ));

                  echo "Feed updated: Id: $feedId Url: $feedUrl<br>";

            }

      }




      // Check if value is allowed or returns a default value
      function wcio_apl_whitelist( $value, $allowedValues, $defaultValue) {

            if ($value === null) {

                  return $defaultValue;

            }

            $key = array_search($value, $allowedValues, true);

            if ($key === false) {

                  return $defaultValue;

            } else {

                  return $value;

            }

      }



      // Get products from database
      function wcio_apl_get_products( $searchColumn, $searchColumnType, $searchColumnValue, $orderBy, $orderByType, $secondaryOperator = " AND ", $secondarySearch, $limitTo = "100", $startFrom = "0") {

            // All $secondarySearch is AND between
      /*      $secondarySearch = array(
                  "productPrice" => array(
                        "operator" => "above", //below,above,equal,like,NOT LIKE
                        "value" => "1",
                  ),
                  "productPrice" => array(
                        "operator" => "below", //below,above,equal,like,NOT LIKE
                        "value" => "15000",
                  ),
                  "productName" => array(
                        "operator" => "NOTLIKE", //below,above,equal,like,NOT LIKE
                        "value" => "",
                  ),
            );

*/
            $secondarySearchOutput = "";
            if( $secondarySearch != "" ) {

                  if (strpos($secondarySearch, ",") !== false) {

                        $secondarySearch = explode(",", $secondarySearch);

                  } else {

                        $secondarySearch = array($secondarySearch);

                  }

                  $secondarySearchCount = count($secondarySearch);
                  $secondarySearchOutput = " ".$this->wcio_apl_whitelist( $secondaryOperator, ["AND","OR"], "AND")." ";

                  $i = "1";
                  foreach( $secondarySearch AS $column => $data ) {

                        $data = explode("_", $data);

                        $column = $data["0"];
                        $value = $data["2"];
                        $afterValue = $data["3"];

                        if( $data["1"] == "ABOVE" ) {
                              $operator = ">";
                        }
                        if( $data["1"] == "BELOW" ) {
                              $operator = "<";
                        }
                        if( $data["1"] == "EQUAL" ) {
                              $operator = "=";
                        }
                        if( $data["1"] == "NOTEQUAL" ) {
                              $operator = "!=";
                        }
                        if( $data["1"] == "LIKE" ) {
                              $operator = "LIKE";
                              $value = "%".$data["2"]."%";
                        }
                        if( $data["1"] == "NOT LIKE" ) {
                              $operator = "NOT LIKE";
                              $value = "%".$data["2"]."%";
                        }

                        $secondarySearchOutput .= " $column $operator '$value' ";

                        if( $i != $secondarySearchCount ) {
                              $secondarySearchOutput .= " $afterValue ";
                              $i++;
                        }
                  }


            }

            // Validate inputs
            $searchColumn = $this->wcio_apl_whitelist( $searchColumn, ["id","productDealer","category","brand","productEan","productName","productPrice","productDiscount","productId"], "productName");

            if($searchColumnType == "LIKE" || $searchColumnType == "NOT LIKE") { $searchColumnValue = "%$searchColumnValue%";}
            $searchColumnType = $this->wcio_apl_whitelist( $searchColumnType, ["LIKE","NOT LIKE","EQUAL","NOTEQUAL"], "LIKE");
            if( $searchColumnType == "EQUAL" ) { $searchColumnType = "="; }
            if( $searchColumnType == "NOTEQUAL" ) { $searchColumnType = "!="; }

            $orderBy = $this->wcio_apl_whitelist( $orderBy, ["id","productDealer","category","brand","productEan","productName","productPrice","productDiscount"], "id");
            $orderByType = $this->wcio_apl_whitelist( $orderByType, ["ASC","DESC",""], "");

            $limitTo = (int)$limitTo;
            $startFrom = (int)$startFrom;

            // Fetch all product matching search
            $stmt = $this->pdo->prepare("SELECT * FROM wcio_apl_products WHERE $searchColumn $searchColumnType lower(:searchColumnValue) $secondarySearchOutput ORDER BY $orderBy $orderByType LIMIT $startFrom, $limitTo");
            $stmt->execute(array(
                  "searchColumnValue" => $searchColumnValue,
            ));
            $getData = $stmt->fetchAll();
            //print_r($stmt->errorInfo()); // For debugging

            return $getData;

      }
}

?>
