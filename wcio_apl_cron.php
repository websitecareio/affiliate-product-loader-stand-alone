<?php

$cronPass = "zp%HYb%LtNM4FAJ@1%pI6";
echo "Welcome CRON user<br><br>";
if( $_GET['pass'] == $cronPass ) {

      echo "Password correct<br><br>";

      $feedId = ""; // NULL value

      // If we want to update specific feed ID, then use feedId in get.
      if( $_GET['feedId'] ) {
            $feedId = (int)$_GET['feedId'];
      }

      // Load config and class
      require_once("wcio_apl.class.php");
      $wcio_apl = new wcio_affiliate_product_loader($pdo);
      echo $wcio_apl->wcio_apl_update_feed( $feedId );

}
?>
