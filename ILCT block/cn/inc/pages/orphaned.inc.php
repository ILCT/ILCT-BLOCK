<h1>孤立区块</h1>
<br />

<table class="table table-striped">
<tr>
  <th>区块高度</th>
  <th>生成时间</th>
  <th>区块难度</th>
  <th>随机数</th>
  <th>交易数</th>
  <th>数据量(kB)</th>
</tr>

<?php
$ohdb_handle = fopen('./db/ohashes', "r+");

function get_orph_hash($index) {
  global $ohdb_handle;
  fseek($ohdb_handle, 64*$index);
  return fread($ohdb_handle, 64);
}

$l_dat = explode(':', file_get_contents("./db/last_dat"));

if ($l_dat[2] == 0) {

  echo "<tr><td colspan='6'>我们的节点还没有看到任何孤立区块。</td></tr>";

} else {

  for ($i=0;$i<=$l_dat[2];$i++) {

    $orph_hash = get_orph_hash($i);
    $block[$i] = $_SESSION[$rpc_client]->getblock($orph_hash);
  
    echo "<tr><td><a href='./?block=".$block[$i]['hash'].
    "'>".$block[$i]['height']."</a></td><td>".
    date("Y-m-d h:i A", $block[$i]['time']).
    "</td><td>".$block[$i]['difficulty'].
    "</td><td>".$block[$i]['nonce'].
    "</td><td>".count($block[$i]['tx']).
    "</td><td>".round($block[$i]['size']/1024, 2).
    "</td></tr>";
  }
}

// TODO: add pagination
?>

</table>

<p><b>提示:</b> 这不是孤立区块的详细列表</p>
