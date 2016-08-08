#!/usr/bin/php
<?php

/* INCLUDES */

// The QuadrigaAPI class which loads credentials and defines API calls
require_once('qapi.inc');

/* VARIABLES */

$qapi = new QuadrigaAPI();
// The Private API Function calls will load credentials if required.
// Otherwise they could be loaded from a non-default file here:
//$qapi->load_credentials('qcx_acct.txt');

/* API CALLS */

// Get the ticker result for BTC -> CAD
$btc_cad = $qapi->ticker();

// Get balance of BTC from API
$balance = $qapi->balance();
if (isset($balance->fee)) {
    $fee = $balance->fee / 100;
} else {
    $fee = NULL;
}

// Get list of transactions
$xactions = $qapi->user_transactions(NULL, 0, 50, TRUE, 'trades');

/* OUTPUT */

print '--- TICKER ---' . PHP_EOL;
var_dump($btc_cad);
print '--- END TICKER ---' . PHP_EOL;
print '--- BALANCE ---' . PHP_EOL;
var_dump($balance);
print '--- END BALANCE ---' . PHP_EOL;
print '--- TRADES  ---' . PHP_EOL;
var_dump($xactions);
print '--- END TRADES ---' . PHP_EOL;

