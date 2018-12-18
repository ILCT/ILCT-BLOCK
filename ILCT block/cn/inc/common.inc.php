<?php
// call required includes
require_once('./lib/common.lib.php');
require_once('./inc/config.inc.php');

// check if website is disabled
if (!$site_enabled) { die($offline_msg); }

// start the session
session_start();

// connect to RPC client
$_SESSION[$rpc_client] = new RPCclient($rpc_user, $rpc_pass);

// get general network info
$getinfo = $_SESSION[$rpc_client]->getinfo();

// save any errors to variable
$rpc_error = $_SESSION[$rpc_client]->error;

// get current page
if (empty($_GET['page'])) {
  if (isset($_GET['address'])) {
    $page = 'address';
	$page_title = "地址 ".$_GET['address'];
  } elseif (isset($_GET['b'])) {
    $page = 'block';
	$page_title = "区块 #".$_GET['b'];
  } elseif (isset($_GET['block'])) {
    $page = 'block';
	$page_title = "区块 ".$_GET['block'];
  } elseif (isset($_GET['tx'])) {
    $page = 'tx';
	$page_title = "交易 ".$_GET['tx'];
  } elseif (isset($_GET['rawtx'])) {
    $page = 'rawtx';
	$page_title = "原始交易 ".$_GET['rawtx'];
  } elseif (isset($_GET['rawblock'])) {
    $page = 'rawblock';
	$page_title = "原始区块 ".$_GET['rawblock'];
  } elseif (isset($_GET['q'])) {
    if (empty($_GET['q'])) {
      $page = 'api';
	  $page_title = "接口";
    } else {
      require_once('./inc/pages/api.inc.php');
	  exit;
	}
  } else {
    $page = 'home';
	$page_title = '首页';
  }
} else {
  $page = urlencode($_GET['page']);
  $title_arr = array('search' => '检索', 
    'orphaned' => '孤立的区块',
    'stats' => '钱包', 'api' => '接口',
	'mempool' => '内存池',
	'peers' => '已连接的同伴'
  );
  if (isset($title_arr[$page])) {
    $page_title = $title_arr[$page];
  } else {
    $page_title = '没有下一页了';
  }
}
?>
