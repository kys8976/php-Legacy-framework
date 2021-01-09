<?
include("../lib/config/config.php");

$page = new Page;

// check

// process
$result = "y";
$message = $order_code = "NULL";

/*
$_POST['order_code'] =1;
$_POST['direction'] = 'up';
$_POST['table'] = "category";
*/

switch($_POST['table']) {
	case "category":	// order by oder_code
	if($_POST['category_code']) {
		$category_length = strlen($_POST['category_code'])+_CATEGORY_LENGTH;
		$add_where = " and category_code like '".$_POST['category_code']."%' and length(category_code)='".$category_length."'";
	}
	else {
		$add_where = " and length(category_code)='"._CATEGORY_LENGTH."'";
	}
	$where_up = "order_code < '".$_POST['order_code']."'".$add_where." order by order_code desc limit 1";
	$where_down = "order_code > '".$_POST['order_code']."'".$add_where." order by order_code limit 1";
	break;

	default:			// order by oder_code desc
	if($_POST['category_code'] != "") {
		$add_where = " and category_code = '".$_POST['category_code']."'";
	}
	if($_POST['is_display'] != "") {
		$add_where .= " and is_display_".$_POST['is_display']." = 'y'";
	}
	$where_up = "order_code > '".$_POST['order_code']."'".$add_where." order by order_code limit 1";
	$where_down = "order_code < '".$_POST['order_code']."'".$add_where." order by order_code desc limit 1";
	break;
}

if($_POST['direction'] == "up") {	// 상위로의 순서변경
	$arrCategory = getFieldInfo($_POST['table'],"order_code",$where_up);

	if(!$arrCategory['order_code']) {
		$result = "n";
		$message = "더이상 상위로의 위치 변경은 불가능합니다.";
	}
}
else {								// 하위로의 순서변경
	$arrCategory = getFieldInfo($_POST['table'],"order_code",$where_down);

	if(!$arrCategory['order_code']) {
		$result = "n";
		$message = "더이상 하위로의 위치 변경은 불가능합니다.";
	}
}

if ($result == "y") {
	$page->objDBH->query("update ".$_POST['table']." set order_code=0 where order_code=".$_POST['order_code']);
	// $query = "update ".$_POST['table']." set order_code=0 where order_code=".$_POST['order_code']."\n";
	$page->objDBH->query("update ".$_POST['table']." set order_code=".$_POST['order_code']." where order_code=".$arrCategory['order_code']);
	// $query .= "update ".$_POST['table']." set order_code=".$_POST['order_code']." where order_code=".$arrCategory['order_code']."\n";
	$page->objDBH->query("update ".$_POST['table']." set order_code=".$arrCategory['order_code']." where order_code=0");
	// $query .= "update ".$_POST['table']." set order_code=".$arrCategory['order_code']." where order_code=0\n";
	$order_code = $arrCategory['order_code'];
}

$fp=fopen("vaccount.log","a");
fwrite($fp,"value : ".$_POST['table']."\ndirection:".$_POST['direction']."\norder_code:".$_POST['order_code']."\ncategory_code:".$_POST['category_code']);

$arrData['order_code'] = $order_code;
$arrData['result'] = $result;
$arrData['message'] = $message;
echo json_encode($arrData);
exit;

$data = "<?xml version='1.0' encoding='utf-8' ?>
<lists>
	<order_code>".$order_code."</order_code>
	<result>".$result."</result>
	<message>".iconv("EUC-KR", "UTF-8", $message)."</message>
</lists>";

header("Content-Encoding: UTF-8");
header("Content-type: application/xml;charset=UTF-8");
echo $data;
// $fp=fopen("vaccount.log","a"); fwrite($fp,"value :: ".$data."\n".$query);
// $fp=fopen("vaccount.log","a"); fwrite($fp,"value2345 :".$add_where.":".$arrCategory['order_code'].":".$result.":".$message."\n".$query);
?>