<?php
if (isset($_GET['q'])) {
  $qstr = preg_replace("/[^a-z0-9]/i", '', $_GET['q']);
  $qlen = strlen($qstr);
  if ($qlen >= 64) {
    $tx = $_SESSION[$rpc_client]->getrawtransaction($qstr);
    if (!empty($tx) && empty($_SESSION[$rpc_client]->error)) {
	  redirect("./?tx=$qstr");
	} else {
	  redirect("./?block=$qstr");
	}
  } else {
    if (is_numeric($qstr)) {
	  redirect("./?b=$qstr");
	} else {
	  redirect("./?address=$qstr");
	}
  }
} else {
?>

<h1><img src="./img/logo.png"/> 检索区块链</h1><br />

<form name="search_form" class="form-horizontal" method="get" action="./">
  <h3>检索地址 <small>输入有效地址</small></h3>
  <input type="text" class="long_input" name="address" value="" maxlength="34" />
  <input type="submit" class="btn" value="检索" />
</form>

<form name="search_form" class="form-horizontal" method="get" action="./">
  <h3>检索交易ID <small>输入有效交易ID</small></h3>
  <input type="text" class="long_input" name="tx" value="" maxlength="64" />
  <input type="submit" class="btn" value="检索" />
</form>
  
<form name="search_form" class="form-horizontal" method="get" action="./">
  <h3>检索区块高度 <small>输入有效区块高度</small></h3>
  <input type="text" class="long_input" name="block" value="" maxlength="64" />
  <input type="submit" class="btn" value="检索" />
</form>

<?php
}
?>