<div id="latest">
  <center><img src="./img/ajax_loader.gif" alt="Loading ..." /></center>
</div>

<h3><img src="./img/logo.png"/> 快捷导航</h3>

<p>
  <a href="./?page=orphaned">孤立区块</a> - 不在主链中的有效区块列表<br />
  <a href="./?page=mempool">内存池</a> - 当前内存池中的交易列表<br />
  <a href="./?page=peers">连接节点</a> - 当前连接到我们节点的节点列表
</p>

<script language="JavaScript">
function handle_update(response) {
  $('#latest').fadeOut(500, function() {
    $('#latest').html(response);
    $('#latest').fadeIn(500);
  });
}

function update_page() {
  ajax_get('./inc/pages/jobs/latest.inc.php', handle_update, '');
}

$(document).ready(function() {
  update_timer = setInterval(update_page, 60000);
  update_page();
});
</script>
