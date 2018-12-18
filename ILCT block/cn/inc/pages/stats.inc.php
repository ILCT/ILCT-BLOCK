<?php
$balance = $_SESSION[$rpc_client]->listbalances(1, array($cb_address));
$mining_info = $_SESSION[$rpc_client]->getmininginfo();
$tx_stats = $_SESSION[$rpc_client]->gettxoutsetinfo();

$now_time = date("Y-m-d H:i:s e");
$start_time = date("Y-m-d H:i:s e", $launch_time);
$time_diff = get_time_difference($start_time, $now_time);
$coin_supply = remove_ep($tx_stats['total_amount']);
$cb_balance = remove_ep($balance[0]['balance']);
$frac_reman = bcdiv($cb_balance, $total_coin);
$block_rwrd = bcmul($first_reward, $frac_reman);
$l_dat = explode(':', file_get_contents("./db/last_dat"));
$s_dat = explode(':', file_get_contents("./db/stat_dat"));
?>

<h1><img src="./img/logo.png"/> 区块统计</h1><br />

<table class="table table-striped">
<tr><td>
  <b>区块总量:</b></td><td>
  <?php echo float_format($total_coin, 0)." $curr_code"; ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr><tr><td>
  <b>预挖总量:</b></td><td>
  <?php echo float_format($precut_coin, 0).' '.$curr_code; ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr><tr><td>
  <b>未开采余额:</b></td><td>
  <?php echo float_format($unexploited_coin, 0).' '.$curr_code; ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr><tr><td>
  <b>区块奖励:</b></td><td>
  <?php echo $block_rwrd.' '.$curr_code; ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr><tr><td>
  <b>区块统计:</b></td><td>
  <?php echo $mining_info['blocks']; ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr><tr><td>
  <b>交易区块:</b></td><td>
  <?php echo $l_dat[1]; ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr><tr><td>
  <b>交易地址:</b></td><td>
  <?php echo $tx_stats['accounts']; ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr><tr><td>
  <b>输入统计:</b></td><td>
  <?php echo $s_dat[0]; ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr><tr><td>
  <b>输出统计:</b></td><td>
  <?php echo $s_dat[1]; ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr><tr><td>
  <b>区块总输入:</b></td><td>
  <?php echo float_format($s_dat[2], 6).' '.$curr_code; ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr><tr><td>
  <b>区块总输出:</b></td><td>
  <?php echo float_format($s_dat[3], 6).' '.$curr_code; ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr><tr><td>
  <b>区块总费用:</b></td><td>
  <?php echo bcsub($s_dat[2], $s_dat[3]).' '.$curr_code; ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr><tr><td>
  <b>平均区块时间:</b></td><td>
  <?php echo round(($time_diff['seconds']/$mining_info['blocks'])/60, 4).' 分钟'; ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr><tr><td>
  <b>区块难度:</b></td><td>
  <?php echo float_format($mining_info['difficulty'], 6); ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr><tr><td>
  <b>哈希率:</b></td><td>
  <?php echo float_format(bcdiv($mining_info['networkhashps'], '1000000000'), 4).' GH/s'; ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr><tr><td>
  <b>区块运行时间:</b></td><td>
  <?php echo round($time_diff['seconds']/60/60/24, 2).' 天'; ?></td><td>
  </td><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
</td></tr>
</table>
