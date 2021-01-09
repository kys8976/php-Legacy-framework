<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
date_default_timezone_set('Asia/Seoul');
session_start();

define ("_DOCUMENT_ROOT_DIR", $_SERVER['DOCUMENT_ROOT']);
define ("_TEMPLATE_ROOT", _DOCUMENT_ROOT_DIR ."/html");
define ("_HOME_URL", "http://".$_SERVER['HTTP_HOST']."");
define ("_LOGIN_URL", $_SERVER['HTTP_HOST']);

define ("_APPL_VARIABLE", "tpf");
define ("_APPL_DIRECTORY_DIVISION", "/");
define ("_APPL_EXEC_EXTENSION", "php");
define ("_APPL_TEMPLATE_EXTENSION", "html");

define ("_LIBRARY_DIR", _DOCUMENT_ROOT_DIR."/lib");
define ("_CLASS_DIR", _LIBRARY_DIR."/class");
define ("_FUNC_DIR"	, _LIBRARY_DIR."/function");
define ("_CONFIG_DIR", _LIBRARY_DIR."/config");
define ("_USER_DIR", _DOCUMENT_ROOT_DIR."/user");
define ("_USER_URL", _HOME_URL."/user");
define ("_CATEGORY_LENGTH", 2);		// category length 2자리

// 사용자 정의
define ("_DISPLAY_DEBUG", true);    // error 발생시 표출 여부
//define ("_ADMIN_EMAIL", "1qkakzk@naver.com");   // 관리자 메일 주소(db error 발생시 수신)
define ("_HASH_SALT", "whois");  // hash salt key
define ("_DISPLAY_DATA_COUNT", 15); // 한페이지 표출되는 data 개수
define ("_DISPLAY_DATA_COUNT_MOBILE", 12); // 한페이지 표출되는 data 개수
define ("_DISPLAY_PAGE_COUNT", 5);  // 표출되는 page 개수
define ("_THUMBNAIL_WIDTH", 295);  // 썸네일 width
define ("_THUMBNAIL_HEIGHT", 188);  // 쎔네일 height

// API 응답 코드 정의
define ("_API_SUCCESS", "00");      // 성공
define ("_API_CERTIFY_FAIL", "11"); // 인증 실패
define ("_API_FAIL", "99");         // 실패
define ("_API_URL", _HOME_URL."/api/process.php");  // 서버단 API END_POINT

define ("_SITE_NAME","framework");
define ("_DB_NAME",	"sin8976");
define ("_DB_USER",	"sin8976");
define ("_DB_PASSWORD",	"lats13524!");
//define ("_DB_IP",	"127.0.0.1");
define ("_DB_IP",	"localhost");
define ("_CUSTOMER_TEL", "01012341234");   // 고객센터 전화번호
define ("_SMS_ID", "sin8976");         // SMS 아이디 (사이트별 SMS 발송건수 구분자 : goldenbell, go-hkc)

include _CLASS_DIR."/class.Page.php";
include _CLASS_DIR."/class.DBMysql.php";

include _FUNC_DIR."/func.common.php";	// 공통 함수
include _FUNC_DIR."/func.user.php";		// 사용자 정의
?>