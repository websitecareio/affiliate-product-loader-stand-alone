<?php
// Load config and class
require_once("wcio_apl.class.php");
$wcio_apl = new wcio_affiliate_product_loader($pdo);


?>
<html lang="en" dir="ltr">
<head>
      <meta charset="utf-8">
<style>
body {
      background: #e4e4e4;
}
.sub-box{
      width: 24% !important;
	position: relative !important;
	display:inline-block;
      margin-top:10px;
}

.sub-box > div:first-of-type {
	background-color: #fff;
}

.sub-box > div{
	border:1px solid rgba(0,0,0,0.3);
	padding-top: 20px;
}

.img-box{
	width: auto !important;
	padding:25px 5px;
	margin-bottom: 10px;
	margin-top: 10px;
	height: 200px;
	text-align: center;
}

.sizedImg{
	max-width: 100% !important;
	max-height: 100% !important;
}

.name-box{
	width:100%;
	height: 25px;
	padding:2px;
	text-align:center;
	overflow: hidden;
}

.name-box > p{
	font-size: 11px;
	font-weight: 700;
}

.category-box{
	width:100%;
	height: 35px;
	padding:2px;
	text-align:center;
	overflow: hidden;
}

.category-box p{
	font-size: 11px;
	font-weight: 700;
}

.price-box{
	width:100%;
	margin-bottom: 50px;
	padding:10px;
	text-align:center;
	justify-content:center;
}

.new-price{
	font-weight: 700;
	margin-right: 5px;
	font-size: 14px !important;
}

.old-price{
	font-weight: 300;
	text-decoration: line-through;
	font-size: 12px !important;
}

.discount-box{
	border-radius: 2px;
	background-color: transparent;
	border: 1px solid #dc5059;
	color: #dc5059;
	line-height: 1;
	position: absolute;
	top: 12px;
	left: 12px;
	padding: 4px;
}

.main-box .discount-box > p {
	font-size: 16px;
	margin: 0;
}

.add_button{
	width:100%;
	height:20%;
	text-align:center;
	position:relative;
}

.add_button_direct{
      text-decoration: none !important;
          border: 1px solid #dbdbdb !important;
          padding: 8px;
          font-size: 23px;
          position: relative;
          width: 84%;
          left: 0;
          bottom: 10px;
          background-color: #48b83b;
          color: white;
          text-align: center;
          box-shadow: none !important;
          right: 0;
          margin: 0px auto;
          display: block;
}

.add_button_direct:hover{
	background-color: #3aaa35;
	color: white;
	transition: none !important;
	box-shadow: none !important;
}
#content {
      width:960px;
      margin:0px auto;
}

</style>
</head>
<body data-gr-c-s-loaded="true">
<div id="content">
<?php
      $products = $wcio_apl->wcio_apl_get_products( "productName", "LIKE", "shaker", "productPrice", "DESC", "AND", "productName_NOT LIKE_booney_AND,productName_NOT LIKE_samleled_AND,productName_NOT LIKE_hængelås_AND", 100, 0);
      // $products = $wcio_apl->wcio_apl_get_products( "id", "NOTEQUAL", "0", "productPrice", "ASC", "productEan_EQUAL_192290288746_OR,productEan_EQUAL_SS-03-19-001-2_OR", 1000, 0);
foreach( $products AS $product) {
 ?>
      <div class="sub-box">
                  <div class="product-id-<?php echo $product["id"]; ?>"><div class="img-box">
				<img src="<?php echo $product["productImage"]; ?>" class="sizedImg">
			</div><div class="category-box">
					<p><?php echo $product["productName"]; ?></p>
				</div><div class="price-box">
  				<p class="price new-price"><?php echo $wcio_apl->wcio_apl_displayPrice($product["productPrice"]); ?> Kr</p></div><div class="ad_button">
	<a href="<?php echo $product["productUrl"]; ?>" rel="nofollow" class="add_button_direct" target="_blank">G&aring; til butik</a>
	</div>
                  </div></div>
                  <?php
}
?>
</div>
</body></html>
