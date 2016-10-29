QuadrigaAPI - A PHP Class For The QuadrigaCX API v2
===================================================

The QuadrigaAPI class defined in `qapi.inc` defines functions to make
API calls to the QuadrigaCX API version 2 as documented at
https://www.quadrigacx.com/api_info

CREDENTIALS FILE
----------------

Credentials for the API should be placed in a text file to be read
when a Private API Function gets called. The default location for the
file is $HOME/.quadriga.conf but a separate file can be specified by
calling the object's `load_credentials('filename.conf');` function
after initialization. Three values should be defined in this file:

    QUADRIGA_CLIENT_ID=[Your Client ID]
    QUADRIGA_API_KEY=[Your API Key]
    QUADRIGA_SPI_SECRET=[Your API Secret]

FUNCTION NAMES
--------------

All Public and Private API Functions have equivalent function calls in
the class, and they accept the same arguments as the API. The
functions inside the class return data from the API which has been
passed through json_decode() so the returned value is either a PHP
array or string, or FALSE if something failed.

The function `user_transactions()` accepts one additional argument not
defined in the QuadrigaCX API which prunes the results and can return
only funding transactions or only trading transactions for the
account.

Aliases have been created for the following functions:

    bitcoin_deposit_address = btc_in
    bitcoin_withdrawal = btc_out
    ether_deposit_address = eth_in
    ether_withdrawal = eth_in

EXCHANGE FUNCTION
-----------------

A function called "exchange" will attempt to determine if the given
book name is valid. If so, it proceeds with a regular Sell Order (at
Market price). If it determines that the given book name is backwards
(ex "cad_btc") then it checks the ticker value for the appropriate
book and uses the "ask" price to invert the value and reverse the book
name before placing a Buy Order at Market price. This allows you to
"exchange 100 CAD into BTC" which is not directly supported by the
API.
