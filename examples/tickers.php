#!/usr/bin/php
<?php

require_once('../qapi.inc');

$qapi = new QuadrigaAPI();

$books = array('btc_cad', 'btc_usd', 'eth_btc', 'eth_cad');
$tickers = array();

foreach ($books as $book) {
  $tickers[$book] = $qapi->ticker($book);

  print '--- ' . $book . ' ---' . PHP_EOL;
  print 'VWAP: ' . $tickers[$book]->vwap . PHP_EOL;
  print 'High: ' . $tickers[$book]->high . PHP_EOL;
  print 'Low : ' . $tickers[$book]->low . PHP_EOL;
  print 'Bid : ' . $tickers[$book]->bid . PHP_EOL;
  print 'Ask : ' . $tickers[$book]->ask . PHP_EOL;
  print PHP_EOL;
}
