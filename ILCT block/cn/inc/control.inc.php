<?php
if (isset($index_call)) {
  if (!empty($page)) {
    if (file_exists("./inc/pages/$page.inc.php")) {
      require_once("./inc/pages/$page.inc.php");
    } else {
	  echo "<p>对不起，没有找到所请求的页面！ :(</p>";  
    }
  } else {
    require_once('./inc/pages/home.inc.php');
  }
} else {
  echo "错误: 无效页访问";
}
?>
