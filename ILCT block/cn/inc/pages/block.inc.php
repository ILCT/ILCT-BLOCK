<?php
error_reporting(0);
if (isset($_GET['b'])) {
  $bnumb = preg_replace("/[^a-f0-9]/", '', strtolower($_GET['b']));
  $bhash = $_SESSION[$rpc_client]->getblockhash(abs($bnumb));
  if (!empty($bhash)) {
    $block = $_SESSION[$rpc_client]->getblock($bhash);
	$chain_info = "<sup class='main_info'>[主链]</sup>";
  } else {
	$break = true;
  }
} elseif (isset($_GET['block'])) {
  $bhash = preg_replace("/[^a-f0-9]/", '', strtolower($_GET['block']));
  $block = $_SESSION[$rpc_client]->getblock($bhash);
  if (!empty($block)) {
    $chash = $_SESSION[$rpc_client]->getblockhash($block['height']);
	if ($bhash === $chash) {
	  $chain_info = "<sup class='main_info'>[主链]</sup>";
	} else {
	  $chain_info = "<sup class='orphan_info'>[orphan chain]</sup>";
	}
  } else {
	$break = true;
  }
}

if (!isset($break) || rpc_error_check(false)) {
	echo "<h1><a href='./?b=".$block['height']."'>区块 #".$block['height']."</a> $chain_info</h1>";
	echo "<div class='row-fluid'><div class='span5'>";
	echo "<h3>概要:</h3><table class='table table-striped table-condensed'>";
	echo "<tr><td width='100px'><b>版本:</b></td><td>".$block['version']."</td></tr>";
	echo "<tr><td><b>大小:</b></td><td>".round($block['size']/1024, 2)." kB</td></tr>";
	echo "<tr><td><b>交易:</b></td><td>".count($block['tx'])."</td></tr>";
	echo "<tr><td><b>确认:</b></td><td>".$block['confirmations']."</td></tr>";
	echo "<tr><td><b>难度:</b></td><td>".$block['difficulty']."</td></tr>";
	echo "<tr><td><b>随机数:</b></td><td>".$block['nonce']."</td></tr>";
	echo "<tr><td><b>时间:</b></td><td>".date("Y-m-d h:i:s A", $block['time'])."</td></tr>";
	echo "</table>";

	echo '</div><div class="span7">';
	echo '<h3>哈希:</h3><table class="table table-striped">';
	if (isset($block['previousblockhash'])) {
	  echo "<tr><td width='100px'><b>上一个区块:</b></td><td align=\"left\"><a href='./?block=".
	  $block['previousblockhash']."'>".$block['previousblockhash']."</a></td></tr>";
	}
	if (isset($block['nextblockhash'])) {
	  echo "<tr><td><b>下一个区块:</b></td><td><a href='./?block=".
	  $block['nextblockhash']."'>".$block['nextblockhash']."</a></td></tr>";
	}
	echo "<tr><td><b>哈希值:</b></td><td><a href='./?rawblock=".$block['hash']."'>".$block['hash']."</a></td></tr>";
	echo "<tr><td><b>Master哈希:</b></td><td>".$block['accountroot']."</td></tr>";
	echo "<tr><td><b>Merkle根:</b></td><td>".$block['merkleroot']."</td></tr>";
	echo '</table></div></div>';

	echo "<h3>交易信息:</h3>
	<table class='table table-striped'>";

	foreach ($block['tx'] as $key => $txid) {
	  $tx = $_SESSION[$rpc_client]->getrawtransaction($txid,1);
	  if (!empty($tx)) {
		$in_total = 0;
		$out_total = 0;
		
		echo "<tr><td colspan='2'><a href='./?tx=$txid'>$txid</a></td><td colspan='2' style='text-align:right'>".
			 date("Y-m-d h:i:s", $tx['time'])."</td></tr><tr><td style='vertical-align:middle'>";
		

	    if (count($tx['vout']) > 0) {
		  foreach ($tx['vout'] as $key => $value) {
		    $clean_val = remove_ep($value['value']);
		    $in_total = bcadd($in_total, $clean_val);
			 
			 
		   $addss=$value['scriptPubKey']['addresses'];
		   for($i=0;$i<count($addss);$i++){  
		   $ainfo = $_SESSION[$rpc_client]->getaccountaddress($addss[$i]);

		    if ($value['scriptPubKey']['type'] == "pubkey") {
			  echo "新区块奖励<br />";
		    } else {
			  echo "<a href='./?address=".$ainfo."'>".$ainfo.
				   "</a>:&nbsp;<span class='sad_txt'>$clean_val</span>&nbsp;$curr_code<br />";
			 }
		   }
		  }
		} else {
		  $in_total = 0;
		  echo '没有输入(估计没有生成区块)<br />';
		}
		
		
		echo "<br /></td><td style='vertical-align:middle'>
		<i class='icon-arrow-right' style='margin-left:100px;'></i><br /><br />
		</td><td style='vertical-align:middle;'><div style='margin-left:-150px;'>";
		//var_dump($tx['vout']);
		
		foreach ($tx['vout'] as $key => $value) {
		  $clean_val = remove_ep($value['value']);
		  $out_total = bcadd($out_total, $clean_val);
		  //var_dump($value['scriptPubKey']['addresses']);
		  $adds=$value['scriptPubKey']['addresses'];
		  for($i=0;$i<count($adds);$i++){  
		  if (isset($tx['limit'])) {
			echo "输入地址被限制更新为: <span class='happy_txt'>".
				  remove_ep($tx['limit'])."</span>&nbsp;$curr_code<br />";
		  } elseif ($in_total === 0) {
			echo "新区块奖励<br />";
		  } else {
			echo "<a href='./?address=".$adds[$i]."'>".$adds[$i].
				 "</a>:&nbsp;<span class='happy_txt'>$clean_val</span>&nbsp;$curr_code<br />";
		  }
		  }
		  
		  
		}
		
		echo "</div><br /></td><td style='vertical-align:middle'>
		<b>总共:</b>&nbsp;$out_total&nbsp;$curr_code<br /><br /></td></tr>";
	  }
	}
	
	echo "</table>";
} else {
  echo "<p>找不到指定的区块.</p>";
}
?>