<?php
/**
 * upload.php
 *
 * Copyright 2013, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */


// Make sure file is not cached (as it happens for example on iOS devices)
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include "../../lib/config/config.php";
include _CLASS_DIR."/class.UtilFile.php";
$objUtilFile = new UtilFile();
$objDBH = new DB();

/*
// Support CORS
header("Access-Control-Allow-Origin: *");
// other CORS headers if any...
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	exit; // finish preflight CORS requests here
}
*/

// 5 minutes execution time
@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);

// Settings
$cleanupTargetDir = true; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds

// Get a file name
if (isset($_REQUEST["name"])) {
	$fileName = $_REQUEST["name"];
} elseif (!empty($_FILES)) {
	$fileName = $_FILES["file"]["name"];
} else {
	$fileName = uniqid("file_");
}

$arrFileName = explode(".", $fileName);
$file_ext = strtolower(array_pop($arrFileName));
$file_path = $objUtilFile->getFilePath();
$targetDir = _USER_DIR.$file_path;
$microtime = getMicrotime();
$file_name = $microtime.".".$file_ext;
$thumbnail_name = $microtime."_tn.".$file_ext;
$filePath = _USER_DIR.DIRECTORY_SEPARATOR.$file_path.DIRECTORY_SEPARATOR.$file_name;

// Chunking might be enabled
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

// Remove old temp files
if ($cleanupTargetDir) {
	if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
	}

	while (($file = readdir($dir)) !== false) {
		$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

		// If temp file is current file proceed to the next
		if ($tmpfilePath == "{$filePath}.part") {
			continue;
		}

		// Remove temp file if it is older than the max age and is not the current file
		if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
			@unlink($tmpfilePath);
		}
	}
	closedir($dir);
}

// Open temp file
if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
	die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

if (!empty($_FILES)) {
	if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	}

	// Read binary input stream and append it to temp file
	if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
	}
} else {
	if (!$in = @fopen("php://input", "rb")) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
	}
}

while ($buff = fread($in, 4096)) {
	fwrite($out, $buff);
}

@fclose($out);
@fclose($in);

// Check if file has been uploaded
if (!$chunks || $chunk == $chunks - 1) {
    // Strip the temp .part suffix off
	rename("{$filePath}.part", $filePath);
    $arrSize = getimagesize($filePath);
}

// 쎔네일 이미지 생성
if(in_array($file_ext, $objUtilFile->arrImgType)) {
    $reqData['orig_path'] = $file_path."/".$file_name;          // 원본 파일 경로
    $reqData['target_path'] = $file_path."/".$thumbnail_name;   // 썸네일 파일 경로
    $reqData['width']   = _THUMBNAIL_WIDTH;      // 가로 사이즈
    $reqData['height']  = _THUMBNAIL_HEIGHT;      // 세로 사이즈
    $arrReturn = $objUtilFile->makeThumbnail($reqData);
}
else {
    $thumbnail_name = '';
}

// 파일기본정보 저장하기 (attachment)
$arrParam = array (
    'member_code' => getLoginCode(),
    'plupload_id' => $_COOKIE['plupload_id'],
    'table_name' => 'board_data',
    'file_path' => $file_path,
    'file_name' => $file_name,
    'thumbnail_name' => $thumbnail_name,
    'orig_name' => $fileName,
    'file_size' => round(filesize($filePath)/1024),
    'file_width' => $arrSize[0],
    'file_height' => $arrSize[1],
    'file_ext' => $file_ext,
    'reg_date' => 'now()'
);
$objDBH->insert("attachment", $arrParam);

/*
$cart_count = count(@$_COOKIE['attachment']);
if ($cart_count == 0) $index = 0;
else $index = max(array_keys($_COOKIE['attachment']))+1;

setcookie("attachment[".$index."][file_path]",$file_path, 0,"/",_LOGIN_URL);
setcookie("attachment[".$index."][file_name]",$file_name, 0,"/",_LOGIN_URL);
setcookie("attachment[".$index."][orig_name]",$fileName, 0,"/",_LOGIN_URL);
setcookie("attachment[".$index."][file_size]",round(filesize($filePath)/1024), 0,"/",_LOGIN_URL);
setcookie("attachment[".$index."][file_ext]",$file_ext, 0,"/",_LOGIN_URL);
*/

// Return Success JSON-RPC response
die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');