<?php
include 'db.php';
$dbhost = 'db';
$dbuser = 'root';
$dbpass = 'my_secret_pw_shh';
$dbname = 'test_db';
$db = new db( $dbhost, $dbuser, $dbpass, $dbname );

$netSales = $db->query( "SELECT SUM(`total_price`) AS total_price FROM `test_db` WHERE financial_status IN ('paid', 'partially_paid')" )->fetchArray();
$productionCosts = $db->query( "SELECT SUM(`total_production_cost`) AS total_production_cost FROM `test_db` WHERE financial_status IN ('paid', 'partially_paid') AND fulfillment_status = 'fulfilled'" )->fetchArray();
$grossProfit = $netSales["total_price"] - $productionCosts["total_production_cost"];

?>
    <!DOCTYPE html>
    <html>
    <head>
        <link rel="stylesheet" href="/style/style.css">
    </head>
    <body>
    <div class="topnav">
        <a href="#">Link</a>
        <a href="#">Link</a>
        <a href="#">Link</a>
    </div>

    <div class="content">
        <h2>BeProfit</h2>
        <div class="grid-container">

            <div class="grid-item">
                <p>
                    Net Sales:
					<?= $netSales["total_price"] ?>
                </p>
            </div>
            <div class="grid-item">
                <p>
                    Production costs:
					<?= $productionCosts["total_production_cost"] ?>
                </p>
            </div>
            <div class="grid-item">
                <p>
                    Gross profit:
					<?= $grossProfit?>
                </p>
            </div>
            <div class="grid-item">
                <p>
                    Gross margin:
					<?= $grossProfit / $netSales["total_price"] * 100 ?> %
                </p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Footer</p>
    </div>

    </body>
    </html>
<?php
