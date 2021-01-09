<?php
function getBoardImage($board_code) {
    global $board_title_head, $board_title_image, $left_flash, $left_flash_main, $left_flash_height, $link_law;
    $arrBoardInfo = getCFG("BoardInfo");
    $board_title_head = $arrBoardInfo[$board_code]['board_title_head'];
    $board_title_image = $arrBoardInfo[$board_code]['board_title_image'];
    $left_flash = $arrBoardInfo[$board_code]['left_flash'];
    $left_flash_main = $arrBoardInfo[$board_code]['left_flash_main'];
    $left_flash_height = $arrBoardInfo[$board_code]['left_flash_height'];
    $link_law = $arrBoardInfo[$board_code]['link_law'];
}

function getOrderCode($table) {
	$arrData = getFieldInfo($table,"max(order_code)+1 as order_code","");
	if(!$arrData['order_code']) { $arrData['order_code'] = 1;}
	return $arrData['order_code'];
}

function getPaymentStatus($code) {
	$arrTmp = getCFG("PaymentStatus");
	return $arrTmp[$code];
}

function getPaymentType($code) {
	$arrTmp = getCFG("PaymentType");
	return $arrTmp[$code];
}

function getBankName($code) {
	$arrTmp = getCFG("BankName");
	return $arrTmp[$code];
}

function getAvailableSMSCount($point,$is_sms_multi='') {
	if (!$is_sms_multi) $sms_price = _SMS_PRICE;
	else $sms_price = _SMS_MULTI_PRICE;
	return (int)($point/$sms_price);
}

function getMemberInfo($id='') {
	global $page;
	if (!$id) $id = getMemberId();
	$page->objDBH->query("select * from member where id='".$id."'");
	$arrMember = $page->objDBH->fetch();
	return $arrMember;
}

function getOrderNumber() {
	return date("ymd_His").rand(100,999);
}

function getTmpId() {
	if (!$_COOKIE['tmp_id']) {
		$tmp_id = getOrderNumber();
		setcookie("tmp_id",$tmp_id, 0,"/",_LOGIN_URL);
	}
	else {
		$tmp_id = $_COOKIE['tmp_id'];
	}
	return $tmp_id;
}

function deleteTmpId() {
	setcookie("tmp_id","", 0,"/",_LOGIN_URL);
}

function getFileIcon($file_ext='') {
	switch($file_ext) {
		case "hwp":
		case "doc":
		case "ppt":
		case "xls":
		$img_name = "<img src=./img/icon_".$file_ext.".gif border=0 align=absmiddle>";
		break;

		default:
		$img_name = "<img src=./img/icon_file.gif alt=File border=0 align=absmiddle>";
		break;
	}
	return $img_name;
}

function checkIsAuthBoard($auth_code) {
	if ($auth_code >= getLoginLevel()) return true;
	else return false;
}

function getFilePath($prev_dir,$code) {
	$path = (int)($code / 1000)+1;
	$full_dir = _USER_DIR."/".$prev_dir."/".$path;
	if (!is_dir($full_dir)) {
		$mk_dir ="mkdir ".$full_dir;
		system($mk_dir);
	}
	return $prev_dir."/".$path;
}
?>