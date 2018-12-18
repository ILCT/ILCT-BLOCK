<?php
error_reporting(0); ///201712252137
$tx_id = preg_replace("/[^a-f0-9]/", '', strtolower($_GET['tx']));
$tx = $_SESSION[$rpc_client]->getrawtransaction($tx_id, 1);

if (rpc_error_check(false)) {
	$input_str = '';
	$output_str = '';
	$total_in = 0;
	$total_out = 0;
	
	if (count($tx['vin']) > 0) {
	  foreach ($tx['vin'] as $key => $value) {
	    $clean_val = remove_ep($value['value']);
	    $total_in = bcadd($total_in, $clean_val);
	    if ($value['coinbase'] == true) {
		  $input_str .= "<a href='./?address=".$value['address']."'>TheCoinbaseAccount".
					    "</a> &rarr; <span class='sad_txt'>$clean_val</span> $curr_code (block reward)<br />";
	    } else {
		  $input_str .= "<a href='./?address=".$value['address']."'>".$value['address'].
					    "</a> &rarr; <span class='sad_txt'>$clean_val</span> $curr_code<br />";
	    }
	  }
	} else {
	  $total_in = 0;
	  $input_str = '没有输入（估计没有生成区块）<br />';
	}
		
	foreach ($tx['vout'] as $key => $value) {
	  $clean_val = remove_ep($value['value']);
	  $total_out = bcadd($total_out, $clean_val);
	  if (isset($tx['limit'])) {
		$output_str .= "输入地址提取限制设置为: <span class='happy_txt'>".
					   remove_ep($tx['limit'])."</span> $curr_code<br />";
	  } else {
		$output_str .= "<a href='./?address=".$value['address']."'>".$value['address'].
					   "</a> &larr; <span class='happy_txt'>$clean_val</span> $curr_code<br />";
	  }
	}
	
   echo "<h1>交易详情</h1><br />";
	echo "<table class='table table-striped table-condensed' style='width:auto;'>";
	
	echo "<tr><td><b>交易ID:</b></td><td><a href='./?rawtx=".
		 $tx['txid']."'>".$tx['txid']."</a></td></tr>";
		 
	if (isset($tx['blockhash'])) {
	  echo "<tr><td><b>哈希值:</b></td><td><a href='./?block=".
	       $tx['blockhash']."'>".$tx['blockhash']."</a></td></tr>";
	} else {
	  echo "<tr><td><b>哈希值:</b></td><td>not in a block yet</td></tr>";
	}
	
	$tx_time = isset($tx['time']) ? date("Y-m-d h:i A e", $tx['time']) : 'unknown';
	$confirmations = isset($tx['confirmations']) ? $tx['confirmations'] : '0';
	$tx_message = empty($tx['msg']) ? 'none' : safe_str($tx['msg']);
	$tx_fee = ($total_in === 0) ? '0' : bcsub($total_in, $total_out);
	
	echo "<tr><td><b>发送时间:</b></td><td>$tx_time</td></tr>";
	echo "<tr><td><b>确认次数:</b></td><td>$confirmations</td></tr>";
	echo "<tr><td><b>锁定高度:</b></td><td>".$tx['lockheight']."</td></tr>";
	echo "<tr><td><b>总输入:</b></td><td>$total_in $curr_code</td></tr>";
	echo "<tr><td><b>总输出:</b></td><td>$total_out $curr_code</td></tr>";
	echo "<tr><td><b>费用:</b></td><td>$tx_fee $curr_code</td></tr>";
	echo "<tr><td><b>消息:</b></td><td>$tx_message</td></tr>";
	echo "</table>";
	
	echo "<h3>输入:</h3><p>$input_str</p>";	
	echo "<h3>输出:</h3><p>$output_str</p>";
}
?>
