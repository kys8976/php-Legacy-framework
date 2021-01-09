<?php
include "lib/config/config.php";
$arrPageInfo = getPageInfo();

if (is_readable($arrPageInfo['exec_path'])) {		// 실행파일(.php) 존재할때
    include $arrPageInfo['exec_path'];
} else {
	if (is_readable($arrPageInfo['template_path'])) {	// TEMPLATE파일(.html)만 존재할때
		include "html.php";
	}
	else {
		print "Not Found File";
		exit;
	}
}

$page_class = "ThisPage";
$page = new $page_class();
$page->initPage($arrPageInfo);
$page->execute();
?>