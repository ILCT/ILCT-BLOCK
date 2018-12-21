<?php
// change level of php error reporting
$error_level = E_ALL;

// enable/disable rpc error reporting
$rpc_debug = true;

// enable/disable the explorer frontend
$site_enabled = true;

// message to show when $site_enabled = false
$offline_msg = 'temporarily down for maintenance';

// install directory ('/' if installed at root)
$install_dir = '/tx/';

// website title
$site_name = 'ILCT TOKEN 区块浏览器';

// default time zone used by server
$time_zone = 'Asia/Shanghai';

// coin currency code
$curr_code = 'ILCT';

// show coinbase tx's on home page
$show_cbtxs = true;

// initial balance of coinbase account
$total_coin = '10000000000';

// Excavation of Coin Base Accounts
$precut_coin = '1200000000';

// Unmined Coin Base Account
$unexploited_coin = '8800000000';

// initial block reward
$first_reward = '243.1';

// unix time when first block was mined
$launch_time = 1544098942;

// number of decimal places
$dec_count = 10;

// number of tx's shown per page
$txper_page = 10;

// RPC client name
$rpc_client = 'scoins';

// RPC username
$rpc_user = '';

// RPC password
$rpc_pass = '';

// address of coinbase account
$cb_address = 'QvacN4Y2t2sDxbeQQPs4Pd13f7agz';

// ignore crap under this line
$inter_prot = (empty($_SERVER['HTTPS'])) ? 'http://' : 'https://';
$base_url = $inter_prot.$_SERVER['HTTP_HOST'].$install_dir;
bcscale($dec_count);
ini_set('display_errors', 1); 
error_reporting($error_level);
date_default_timezone_set($time_zone);
?>
