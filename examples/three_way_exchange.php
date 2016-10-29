#!/usr/bin/php
<?php

/**
 * This script attempts to perform currency exchanges, with appropriate fees
 * included, in a three-way trade such that the resulting amount is greater
 * than the amount we started with.
 */

function load_ticker($book) {
    global $qapi;

    $x = explode('_', $book);
    $xbook = $x[1] . '_' .  $x[0];

    $values = array(
        $book => array(),
        $xbook => array(),
    );

    $ticker = $qapi->ticker($book);

    // Many of the following may not be referenced, but we are including them
    // all here for the sake of example.

    // Volume Weighted Average Price
    // This will be the same in either direction
    $values[$book]['vwap'] = floatval($ticker->vwap);
    $values[$xbook]['vwap'] = floatval(1 / $ticker->vwap);

    // High and Low
    $values[$book]['high'] = floatval($ticker->high);
    $values[$xbook]['high'] = floatval(1 / $ticker->high);
    $values[$book]['low'] = floatval($ticker->low);
    $values[$xbook]['low'] = floatval(1 / $ticker->low);

    // Bid and Ask
    $values[$book]['bid'] = floatval($ticker->bid);
    $values[$xbook]['bid'] = floatval(1 / $ticker->bid);
    $values[$book]['ask'] = floatval($ticker->ask);
    $values[$xbook]['ask'] = floatval(1 / $ticker->ask);

    // These are what should happen if a Market Order is placed now.
    $values[$book]['likely'] = $values[$book]['bid'];
    $values[$xbook]['likely'] = 1 / $values[$book]['ask'];

    return $values;
}

/* Given three currencies in a numeric array [0,1,2], return
 * the result of exchanging 1.0 of the first currency [0] via
 * the other two (including trading fees).
 */
function three_way($steps = array()) {
    if (count($steps) != 3) { return NULL; }

    global $values, $fees;

    $steps[3] = $steps[0];

    $total = 1;

    for ($i = 0; $i < 3; $i++) {
        $book = $steps[$i] . '_' . $steps[$i+1];
        $last = $total;
        $total = ($last * $values[$book]['likely']);
        $total -= $total * $fees[$book];
    }

    return $total;
}

/* Check the exchange rates, including fees, to determine if a simple
 * three-way exchange results in net gain.
 */
function find_hole() {
    $checks = array(array('btc', 'eth', 'cad'), array('btc', 'cad', 'eth'));
    foreach ($checks as $steps) {
        $end = three_way($steps);
        if ($end > 1.0) {
            print 'FOUND EXCHANGE NET GAIN: 1 ' . join(' -> ', $steps) . ' = ' . $end . PHP_EOL;
        }
    }
}

/* INCLUDES */

// The QuadrigaAPI class which loads credentials and defines API calls
require_once('../qapi.inc');

$qapi = new QuadrigaAPI();

// Get account balance to obtain fees
$balance = $qapi->balance();
$fees = array();
foreach ($balance->fees as $book => $percent) {
    $fees[$book] = $percent * 0.01;
    $x = explode('_', $book);
    $xbook = $x[1] . '_' .  $x[0];
    $fees[$xbook] = $fees[$book];
}

$values = array();
// Get the ticker results
$values = array_merge($values, load_ticker('btc_cad'));
$values = array_merge($values, load_ticker('eth_cad'));
$values = array_merge($values, load_ticker('eth_btc'));

find_hole();
