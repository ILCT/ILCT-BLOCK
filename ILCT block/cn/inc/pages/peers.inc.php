<h1>连接节点</h1>
<br />

<table class="table table-striped table-condensed">
<tr>
  <th>IP地址</th>
  <th>端口</th>
  <th>服务</th>
  <th>版本</th>
  <th>连接时间</th>
</tr>

<?php
$peers = $_SESSION[$rpc_client]->getpeerinfo();

if (empty($peers)) {
  echo "<tr><td colspan='5'>当前没有连接的节点。</td></tr>";
} else {
	foreach ($peers as $key => $value) {
      $ip_port = explode(':', $value["addr"]);
	  echo "<tr><td>".$ip_port[0]."</td>".
		   "<td>".$ip_port[1]."</td><td>".
		   $value["services"]."</td><td>".
		   $value["version"]."</td><td>".
		   date("Y-m-d h:i:s A",
           $value["conntime"])."</td></tr>";
	}
}
?>
</table>
