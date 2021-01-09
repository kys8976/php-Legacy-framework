<?
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: X-AMZ-META-TOKEN-ID, X-AMZ-META-TOKEN-SECRET");
header('Content-Type: text/javascript; charset=UTF-8');
include ("../lib/config/config.php");
include _CLASS_DIR."/class.API.php";

$objAPI = new API();
$objAPI->loadClass($objAPI->reqData['method']);
$objAPI->runMethod();   // run method()
?>