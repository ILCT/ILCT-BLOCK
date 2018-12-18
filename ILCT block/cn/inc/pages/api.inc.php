<?php if (empty($_GET['q'])) { ?>

<h1><img src="./img/logo.png"/> API查询</h1><hr />

<div class="row-fluid">
  <div class="span5">

	<h3>网络数据</h3>
	<p>
	  <a href="./?q=getdifficulty" target="_blank">获取难度</a> - 当前开采难度<br />
	  <a href="./?q=gethashrate" target="_blank">获取散列值</a> - 估计散列值（hash / s)<br />
	  <a href="./?q=getblockcount" target="_blank">获取高度</a> - 当前块高度<br />
	  <a href="./?q=getlasthash" target="_blank">获取最新的哈希</a> - 最新块的哈希
	</p>

	<h3>币种数据</h3>
	<p>
	  <a href="./?q=blockreward" target="_blank">区块奖励</a> - 当前块奖励<br />
	  <a href="./?q=coinsupply" target="_blank">币种供应</a> - 开采的币总数<br />
	  <a href="./?q=unminedcoins" target="_blank">未开采区块</a> - 全部未开采<br />
	  <a href="./?q=runtime" target="_blank">运行时间</a> - 从第一块开始的时间（秒）
	</p>

	<h3>交易数据</h3>
	<p>
	  <a href="./?q=txinput" target="_blank">输入值</a>/哈希 - 总税额输入值<br />
	  <a href="./?q=txoutput" target="_blank">输出值</a>/哈希 - 总税额输出值<br />
	  <a href="./?q=txfee" target="_blank">免税值</a>/哈希 - 免税值（输入-输出）<br />
	  <a href="./?q=txcount" target="_blank">税额数量</a> - 区块链中税额的数量
	</p>

	<h3>地址数据</h3>
	<p>
	  <a href="./?q=addressbalance" target="_blank">地址平衡</a>/ Address / Confs - 地址的平衡<br />
	  <a href="./?q=addresslimit" target="_blank">地址限制</a>/Address/Confs - 地址的提取限制<br />
	  <a href="./?q=addresslastseen" target="_blank">最新使用地址</a>/Address - 块上次使用地址时<br />
	  <a href="./?q=addresscount" target="_blank">地址总数</a> - 非空地址数
	</p>

	<h3>JSON数据</h3>
	<p>
	  <a href="./?q=getinfo" target="_blank">基本信息</a> - 一般信息<br />
	  <a href="./?q=txinfo" target="_blank">哈希信息</a>/TxHash - 交易信息<br />
	  <a href="./?q=addressinfo" target="_blank">地址信息</a>/Address/Confs - 地址信息<br />
	  <a href="./?q=blockinfo" target="_blank">区块信息</a>/BlockHash - 块信息
	</p>

  </div>
  <div class="span7">

	<h2>交易提示</h2>

	<p>以下示例显示用于检查地址余额的正确URL，忽略具有少于3个确认的事务（确认参数始终是可选的，默认值为1）。 所有其他需要1个或多个参数的查询使用相同的arg1和arg2参数名称，如下所示。</p>

	<pre>/?q=addressbalance&amp;arg1=CGTta3M4t3yXu8uRgkKvaWd2d8DQvDPnpL&amp;arg2=3</pre>

	<p>或者如果URL重写是活动的，你可以使用这种更友好的格式:</p>

	<pre>/q/addressbalance/CGTta3M4t3yXu8uRgkKvaWd2d8DQvDPnpL/3</pre>
	
  </div>
</div>

<?php
} else {
  $q = preg_replace("/[^a-z]/", '', strtolower($_GET['q']));
  
  switch ($q) {
    case 'getdifficulty': ////////////////////////////////////////////
	  $mining_info = $_SESSION[$rpc_client]->getmininginfo();
	  $result = $mining_info['difficulty'];
      break;
    case 'gethashrate': ////////////////////////////////////////////
	  $mining_info = $_SESSION[$rpc_client]->getmininginfo();
	  $result = $mining_info['networkhashps'];
      break;
    case 'getblockcount': ////////////////////////////////////////////
	  $mining_info = $_SESSION[$rpc_client]->getmininginfo();
      $result = $mining_info['blocks'];
      break;
    case 'getlasthash': ////////////////////////////////////////////
      $result = $_SESSION[$rpc_client]->getbestblockhash();
      break;	  
    case 'blockreward': ////////////////////////////////////////////
	  $balance = $_SESSION[$rpc_client]->listbalances(1, array($cb_address));
      $cb_balance = remove_ep($balance[0]['balance']);
      $frac_reman = bcdiv($cb_balance, $total_coin);
      $result = bcmul($first_reward, $frac_reman);
      break;
    case 'coinsupply': ////////////////////////////////////////////
      $tx_stats = $_SESSION[$rpc_client]->gettxoutsetinfo();
      $result = remove_ep($tx_stats['total_amount']);
      break;
    case 'unminedcoins': ////////////////////////////////////////////
	  $balance = $_SESSION[$rpc_client]->listbalances(1, array($cb_address));
      $result = remove_ep($balance[0]['balance']);
      break;
    case 'runtime': ////////////////////////////////////////////
      $now_time = date("Y-m-d H:i:s e");
	  $start_time = date("Y-m-d H:i:s e", $launch_time);
	  $time_diff = get_time_difference($start_time, $now_time);
	  $result = $time_diff['seconds'];
      break;  
    case 'txinput': ////////////////////////////////////////////
	  if (empty($_GET['arg1'])) {
	    die('tx hash not specified');
	  } else {
        $tx_id = preg_replace("/[^a-f0-9]/", '', strtolower($_GET['arg1']));
        $tx = $_SESSION[$rpc_client]->getrawtransaction($tx_id, 1);
	    $total_in = '0';
	    if (count($tx['vin']) > 0) {
	      foreach ($tx['vin'] as $key => $value) {
	        $clean_val = remove_ep($value['value']);
	        $total_in = bcadd($total_in, $clean_val);
	      }
	    } else {
	      $total_in = '0';
	    }
	    $result = $total_in;
        break;
	  }
    case 'txoutput': ////////////////////////////////////////////
      $tx_id = preg_replace("/[^a-f0-9]/", '', strtolower($_GET['arg1']));
      $tx = $_SESSION[$rpc_client]->getrawtransaction($tx_id, 1);
	  $total_out = '0';
	  foreach ($tx['vout'] as $key => $value) {
	    $clean_val = remove_ep($value['value']);
	    $total_out = bcadd($total_out, $clean_val);
	  }
	  $result = $total_out;
      break;
    case 'txfee': ////////////////////////////////////////////
      $tx_id = preg_replace("/[^a-f0-9]/", '', strtolower($_GET['arg1']));
      $tx = $_SESSION[$rpc_client]->getrawtransaction($tx_id, 1);
	  $total_in = '0';
	  $total_out = '0';
	  if (count($tx['vin']) > 0) {
	    foreach ($tx['vin'] as $key => $value) {
	      $clean_val = remove_ep($value['value']);
	      $total_in = bcadd($total_in, $clean_val);
	    }
	  } else {
	    $total_in = '0';
	  }
	  foreach ($tx['vout'] as $key => $value) {
	    $clean_val = remove_ep($value['value']);
	    $total_out = bcadd($total_out, $clean_val);
	  }
	  $result = bcsub($total_in, $total_out);
      break;
    case 'txcount': ////////////////////////////////////////////
      $l_dat = explode(':', file_get_contents("./db/last_dat"));
	  $result = $l_dat[1];
      break;  
    case 'addressbalance': ////////////////////////////////////////////
	  if (empty($_GET['arg1'])) {
	    die('address was not specified');
	  } else {
        $address = preg_replace("/[^a-z0-9]/i", '', $_GET['arg1']);
        $confs = empty($_GET['arg2']) ? 1 : (int)$_GET['arg2'];
        $ainfo = $_SESSION[$rpc_client]->listbalances($confs, array($address));
        $result = remove_ep($ainfo[0]['balance']);
        break;
	  }
    case 'addresslimit': ////////////////////////////////////////////
	  if (empty($_GET['arg1'])) {
	    die('address was not specified');
	  } else {
        $address = preg_replace("/[^a-z0-9]/i", '', $_GET['arg1']);
        $confs = empty($_GET['arg2']) ? 1 : (int)$_GET['arg2'];
        $ainfo = $_SESSION[$rpc_client]->listbalances($confs, array($address));
        $result = remove_ep($ainfo[0]['limit']);
        break;
	  }
    case 'addresslastseen': ////////////////////////////////////////////
	  if (empty($_GET['arg1'])) {
	    die('address was not specified');
	  } else {
        $address = preg_replace("/[^a-z0-9]/i", '', $_GET['arg1']);
        $confs = empty($_GET['arg2']) ? 1 : (int)$_GET['arg2'];
        $ainfo = $_SESSION[$rpc_client]->listbalances($confs, array($address));
        $balance = remove_ep($ainfo[0]['balance']);
	    if (clean_number($balance) === '0') {
		  $last_used = 'unknown';
	    } else {
		  $last_used = $ainfo[0]['age'];
	    }
	    $result = $last_used;
		break;
	  }
    case 'addresscount': ////////////////////////////////////////////
      $tx_stats = $_SESSION[$rpc_client]->gettxoutsetinfo();
	  $result = $tx_stats['accounts'];
      break;
    case 'getinfo': ////////////////////////////////////////////
	  $ginfo = $getinfo;
	  unset($ginfo['balance']);
	  unset($ginfo['proxy']);
	  unset($ginfo['keypoololdest']);
	  unset($ginfo['keypoolsize']);
	  unset($ginfo['paytxfee']);
	  header('Content-Type: application/json');
	  echo json_encode($ginfo);
      exit;
    case 'txinfo': ////////////////////////////////////////////
	  if (empty($_GET['arg1'])) {
	    die('tx hash not specified');
	  } else {
        $tx_id = preg_replace("/[^a-f0-9]/", '', strtolower($_GET['arg1']));
        $tinfo = $_SESSION[$rpc_client]->getrawtransaction($tx_id, 1);
        header('Content-Type: application/json');
	    echo json_encode($tinfo);
        exit;
	  }
    case 'addressinfo': ////////////////////////////////////////////
	  if (empty($_GET['arg1'])) {
	    die('address not specified');
	  } else {
        $address = preg_replace("/[^a-z0-9]/i", '', $_GET['arg1']);
        $confs = empty($_GET['arg2']) ? 1 : (int)$_GET['arg2'];
        $ainfo = $_SESSION[$rpc_client]->listbalances($confs, array($address));
	    unset($ainfo[0]['ours']);
	    unset($ainfo[0]['account']);
        header('Content-Type: application/json');
        echo json_encode($ainfo[0]);
        exit;
	  }
    case 'blockinfo': ////////////////////////////////////////////
	  if (empty($_GET['arg1'])) {
	    die('block hash not specified');
	  } else {
        $block = $_SESSION[$rpc_client]->getblock($_GET['arg1']);
        header('Content-Type: application/json');
        echo json_encode($block);
        exit;
	  }
    default: ////////////////////////////////////////////
       die('unknown command');
  }

  if (rpc_error_check() && $result !== '') {
    echo $result;
  }
}
?>
