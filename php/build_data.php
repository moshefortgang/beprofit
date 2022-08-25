<?php
include 'db.php';

$dbhost = 'db';
$dbuser = 'root';
$dbpass = 'my_secret_pw_shh';
$dbname = 'test_db';

$db = new db( $dbhost, $dbuser, $dbpass, $dbname );

$create_tabel = "CREATE TABLE IF NOT EXISTS `data` (
    `order_ID` decimal(13,0) NOT NULL,
    `shop_ID` decimal(11,0) NOT NULL,
    `closed_at` varchar(50) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `total_price` decimal(10,0) NOT NULL,
    `subtotal_price` decimal(10,0) NOT NULL,
    `total_weight` decimal(10,0) NOT NULL,
    `total_tax` decimal(10,0) NOT NULL,
    `currency` varchar(3) NOT NULL,
    `financial_status` varchar(18) NOT NULL,
    `total_discounts` tinyint(1) NOT NULL,
    `name` varchar(5) NOT NULL,
    `processed_at` timestamp NULL DEFAULT NULL,
    `fulfillment_status` varchar(11) NOT NULL,
    `country` varchar(2) DEFAULT NULL,
    `province` varchar(5) DEFAULT NULL,
    `total_production_cost` decimal(10,0) NOT NULL,
    `total_items` decimal(10,0) NOT NULL,
    `total_order_shipping_cost` tinyint(1) NOT NULL,
    `total_order_handling_cost` tinyint(1) NOT NULL,
    PRIMARY KEY (`order_ID`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

try {
    $db->query( $create_tabel );
} catch (Exception $e) {
    die($e->getMessage());
}


$configs = include( 'config.php' );
$username = $configs['api_username'];
$password = $configs['api_password'];

$url = "https://www.become.co/api/rest/test/";
$context = stream_context_create( [
	'http' => [
		'header' => "Authorization: Basic " . base64_encode( "$username:$password" ),
	],
] );
$json = file_get_contents( $url, FALSE, $context );
$data = json_decode( $json, TRUE );
$imploded_fields = implode( '`,`', array_keys( $data['data'][0] ) );

$values = [];
foreach( $data['data'] as $row ) {
	$values[] = '("' . implode( '","', array_values( $row ) ) . '")';
}

$values = implode( ',', array_values( $values ) );
$statement = " INSERT IGNORE INTO `data` (`$imploded_fields`) VALUES $values ";

try {
    $db->query( $statement );
} catch (Exception $e) {
    die($e->getMessage());
}

echo "Data has been successfully imported";
?>