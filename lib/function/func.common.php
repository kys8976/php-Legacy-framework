<?php
function getDummy() {
	return time();
}

// excel다운로드
function downloadExcel($file_name, $excel_tag) {
    header( "Content-type: application/vnd.ms-excel");
    header( "Content-type: application/vnd.ms-excel; charset=utf-8");
    header( "Content-Disposition: attachment; filename = ".$file_name);
    header( "Content-Description: PHP4 Generated Data");
    echo "<meta content='application/vnd.ms-excel; charset=UTF-8' name='Content-type'>";
    echo $excel_tag;
}

// 이미지 파일 여부 체크
function checkImage($file_ext) {
    $arrImgType	= array("jpg","gif","jpeg","png");
    if(in_array(strtolower($file_ext), $arrImgType)) return true;
    else return false;
}

// push 알림 보내기
function sendPush($objDBH, $arrPushData) {
    include _CLASS_DIR."/class.Push.php";
    $objPush = new Push($objDBH);
    return $objPush->send($arrPushData);
}

function skipComma($price) {
	return preg_replace("/,/","",$price);
}

// 상품별 적립금 구하기
function getProdutPoint($price) {
	$arrMaster = getFieldInfo("master","point_rate,point_type");
	if ($arrMaster['point_type'] == "b") {		// 일괄
		$point = round($price * $arrMaster['point_rate'] / 100);
	}
	else $point = "";
	return $point;
}

function getArrayValue($arrVal) {
	if (is_array($arrVal)) {
        $tmp = "";
		foreach($arrVal as $key => $val) {
			$tmp .= $val.",";
		}
		return preg_replace("/,$/","",$tmp);
	}
}

function getCategoryDepth($table,$category_code='',$is_link="y",$length=_CATEGORY_LENGTH) {
    if ($category_code) {
		$loop = strlen($category_code);
		for($i=$length; $i<=$loop; $i=$i+$length) {
			$tmp_category_code = substr($category_code,0,$i);
			$arrCategory = getFieldInfo($table,"category_code,title","category_code='".$tmp_category_code."'");

			if ($is_link == "y") $strCategoryDepth .= " > <a href=".getCurrentUrl('show_count')."&category_code=".$arrCategory['category_code'].">".$arrCategory['title']."</a>";
			else $strCategoryDepth .= " > ".$arrCategory['title'];
		}
        $strCategoryDepth = preg_replace("/^ > /","",$strCategoryDepth);
		return $strCategoryDepth;
	}
}

function getCategoryDepthCombo($table,$category_code='',$is_link="y",$length=_CATEGORY_LENGTH) {
    global $page;
    if ($category_code) {
		$loop = strlen($category_code);
        for($i=$length; $i<=$loop; $i=$i+$length) {
			$tmp_category_code = substr($category_code,0,$i);
            $arrCategory = getFieldInfo($table,"category_code,title","category_code='".$tmp_category_code."'");

            if ($i != $loop or $loop == $length) {  // 부모 카테고리
                if ($is_link == "y") $strCategoryDepth .= " > <a href=".getCurrentUrl('show_count')."&category_code=".$arrCategory['category_code'].">".$arrCategory['title']."</a>";
			    else $strCategoryDepth .= " > ".$arrCategory['title'];
            }
            else {              // 형태 카테고리
                $select_tag = "<select onchange=\"location.href='index.php?tpf=product/list&category_code='+this.value\">";
                $parent_category = substr($tmp_category_code,0,-2);
                $page->objDBH->query("select category_code,title from category where category_code like '".$parent_category."%' and length(category_code)=".$loop." and status='y'");
                while($arrData = $page->objDBH->fetch()) {
                    if ($arrData['category_code'] == $category_code) $selected_tag = " selected";
                    else $selected_tag = "";
                    $select_tag .= "<option value='".$arrData['category_code']."'".$selected_tag.">".$arrData['title'];
                }
                $select_tag .= "</select>";

                $strCategoryDepth .= " > ".$select_tag;
            }
		}
        $strCategoryDepth = preg_replace("/^ > /","",$strCategoryDepth);
		return $strCategoryDepth;
	}
}

function getFieldInfo($table,$field='*',$where='') {
	global $page;
	$query = "select ".$field." from ".$table;
	if ($where) $query .= " where ".$where;

    if(getenv("REMOTE_ADDR") == "121.140.62.102") { echo "<div align=left><pre>"; var_dump($query); echo "</pre>"; die("<br>End</div>");}

	$page->objDBH->query($query);
	$arrData = $page->objDBH->fetch();
	return $arrData;
}

function getCFG($file_name,$is_ezin='') {
	$cfg_file = _CONFIG_DIR."/cfg.".$file_name.".ini";
    if (file_exists($cfg_file)) $arrTmp = parse_ini_file($cfg_file,true);
	return $arrTmp;
}

function getMicrotime() {
    $time = explode(' ',microtime());
    return $time[1].substr($time[0],2,6);
}

function getDateShort($date) {
	$timestamp = strtotime($date);
	if ($timestamp) $strReturn = date('y.m.d', $timestamp);
	else $strReturn = "";
	return $strReturn;
}

function getDateLong($date) {
	$timestamp = strtotime($date);
	if ($timestamp) $strReturn = date('Y.m.d H:i:s', $timestamp);
	else $strReturn = "";
	return $strReturn;
}

function getDisplayImageSize($filename,$width,$height) {
	$arrSize = getimagesize($filename);
	if ($width <= $arrSize[0] or $height <= $arrSize[1]) {	// 사진 사이즈가 기준 사이즈보다 클태
		if ($width/$height > $arrSize[0]/$arrSize[1]) return "height=$height";	// height 기준
		else return "width=$width";												// width 기준
	}
	else {
		return "width=".$arrSize[0]." heigth=".$arrSize[1];
	}
}

function getHash($param, $type='sha1') {
	return hash($type, _HASH_SALT.$param);;
}

function getLoginCode() {
    return !empty($_SESSION['login_code']) ? $_SESSION['login_code'] : "";
}

function getLoginId() {
	return !empty($_SESSION['login_id']) ? $_SESSION['login_id'] : "";
}

function getLoginName() {
    return !empty($_SESSION['login_name']) ? $_SESSION['login_name'] : "";
}

function getLoginLevel() {
	if (!empty($_SESSION['login_level'])) return $_SESSION['login_level'];
	else {
        return 0;   // 로그인 전 (비회원)
    }
}

function isLogin() {
	if (getLoginId()) return true;
	else return false;
}

function checkIsAuth($table,$code) {
	global $page;
	$page->query("select member_id from ".$table." where code=".$code);
	$arrData = $page->fetch();

	if ($arrData['member_id'] == getMemberId()) return true;
	else return false;
}

function checkEmail($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
    else return false;
}

function checkMobile($mobile) {
    $mobile = preg_replace("/[^0-9]/", "", $mobile);
    if(preg_match("/^01[0-9]{8,9}$/", $mobile)) return true;
    else return false;
}

function divMobile($mobile, $div = '-') {
    $mobile = preg_replace('/[^0-9]+/', '', $mobile);
    $len = strlen($mobile);
    if ($len > 7) {
        $mobile = implode($div, array(
            substr($mobile, 0, 3),
            substr($mobile, 3, $len - 7),
            substr($mobile, -4),
        ));
    }
    return $mobile;
}

function getPageInfo() {
    $tpf = !empty($_GET[_APPL_VARIABLE]) ? trim($_GET[_APPL_VARIABLE]) : "";
    if ($tpf == "") {
		$arrDir = array("main");
		$file = "index";
	} else {
		$arrDir = explode(_APPL_DIRECTORY_DIVISION, $tpf);
		$file = array_pop($arrDir);
	}

    $mobile_folder = '';
    if (@$_SESSION['is_mobile'] == 'y') {   // mobile 접근일때
        $mobile_folder = 'mobile/';
    }
	$exec_path = _DOCUMENT_ROOT_DIR ."/".implode("/",$arrDir)."/".$file."."._APPL_EXEC_EXTENSION;
	$template_path = _TEMPLATE_ROOT."/".$mobile_folder.implode("/",$arrDir) ."/".$file."."._APPL_TEMPLATE_EXTENSION;

	$arrReturn = array();
	$arrReturn['directory'] = $arrDir;
	$arrReturn['file'] = $file;
	$arrReturn['exec_path'] = $exec_path;
	$arrReturn['template_path'] = $template_path;
    return $arrReturn;
}

function getLayout($menu) {
	switch ($menu) {
		case "blank":
		case "admin":
		case "admin_iframe":
        case "api":
		case "main":
			$layout = "layout_". $menu;
		break;

		default :
			$layout = "layout_sub";
		break;
	};
    return $layout;
}

function stripEmail($email) {
	$specials = Array('?','$','&','\'','"','`','<','>');
    return str_replace($specials, '', $email);
}

function convertUTF2String($str, $lang="") {
	if ($lang == "") $lang = "EUC-KR";
	$lang = strtoupper($lang);
	$lang .= "//IGNORE";
	return iconv('UTF-8', $lang, $str);
}

function show_array($arr) {
	print "<pre style='text-align:left'>". print_r($arr, true) . "</pre>";
}

// instance 메일 송신
function sendMail($receive_email, $title, $content, $arrReplaceInfo='') {
    include_once _CLASS_DIR."/class.UtilHttpSocket.php";

    $mode = "mail.send";
    $objHttp = new UtilHttpSocket($mode);

    $arrParam['send_email']		= _ADMIN_EMAIL;     // 보내는 사람 메일주소
    $arrParam['send_name']		= _SITE_NAME;       // 보내는 사람성명
    $arrParam['receive_email']	= $receive_email;	// 받는사람 메일주소
    $arrParam['title']			= $title;           // 메일 제목
    $arrParam['content']		= $content;         // 메일 내용
    $arrParam['replace_info']	= json_encode($arrReplaceInfo); // 치환 문구

    $objHttp->setParam($arrParam);		// 전달 변수 설정
    $result = $objHttp->send();
    return $result;
}

// SMS 송신 (수신자번호, 메세지, 예약발송시간(default:null, 예:2016-09-30 10:34))
function sendSMS($receive_mobile, $message, $msg_type=0, $send_time='') {
    include_once _CLASS_DIR."/class.UtilHttpSocket.php";

    $mode = "sms.send";
    $objHttp = new UtilHttpSocket($mode);

    if ($msg_type == 0 and mb_strlen($message) > 125) { // 문자수가 클때 자동으로 MMS 전환
        $msg_type = 5;
    }

    $arrParam['method']		    = "SMS.send";       // method
    $arrParam['sms_id']		    = _SMS_ID;          // 발송자(구분자) ID
    $arrParam['msg_type']       = $msg_type;        // 0 : SMS, 5 : MMS
    $arrParam['send_tel']		= _CUSTOMER_TEL;    // 보내는 전화번호
    $arrParam['receive_mobile'] = $receive_mobile;  // 수신자 휴대폰 번호 (복수일때 콤마(,) 구분)
    if ($send_time != '') {
        $arrParam['send_time']= $send_time;   // 예약날짜 (예: 2016-10-30 18:30)
    }
    $arrParam['msg']	        = $message;	        // 문자내용

    $objHttp->setParam($arrParam);		// 전달 변수 설정
    $result = $objHttp->send();
    return $result;
}

function putJSMessage($message="",$action="",$url="") {
	echo "
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
	<script>";
	if ($message) echo "alert('".$message."');";
	switch($action) {
		case "close" :
			echo "window.close();";
		break;
		case "href":
			echo "document.location.href('".$url."');";
		break;
        case "replace":
			echo "document.location.replace('".$url."');";
		break;
		case "opener_href":
			echo "opener.top.location.href('".$url."');";
		break;
        case "opener_replace":
			echo "opener.top.location.replace('".$url."');";
		break;
		case "parent_href":
			echo "parent.location.href('".$url."');";
		break;
        case "parent_replace":
			echo "parent.location.replace('".$url."');";
		break;
		case "opener_href_close":
			echo "opener.top.location.href('".$url."');";
			echo "window.close();";
		break;
        case "opener_replace_close":
			echo "opener.top.location.replace('".$url."');";
			echo "window.close();";
		break;
		case "opener_reload_close":
			echo "opener.location.reload();";
			echo "window.close();";
		break;
		case "parent_reload" :
			echo "parent.location.reload();";
		break;
		case "home" :
			echo "location.href('"._HOME_URL."');";
		break;
		case "dialog_parent_reload":
			echo "parent.location.reload();";
		break;
		case "dialog_list_reload":
			echo "parent.parent.list.location.reload();";
			echo "parent.parent.closeDialog();";
		break;
		case "dialog_list_category_reload":
			echo "parent.parent.list.location.reload();";
			echo "parent.parent.tree.location.reload();";
			echo "parent.parent.closeDialog();";
		break;
		case "dialog_parent_parent_reload":
			echo "parent.parent.location.reload();";
		break;
		case "back":
		echo "history.back();";
		break;
        default :
        break;
	}
	echo "</script>";
}

// 모바일 접속 여부 체크
function checkAccessMobile() {
    $arrMobile = array("iPhone","iPod","IPad","Android","Blackberry","SymbianOS|SCH-M\d+","Opera Mini","Windows CE","Nokia","Sony","Samsung","LGTelecom","SKT","Mobile","Phone");

    for($i=0; $i<count($arrMobile); $i++) {
        if(preg_match("/$arrMobile[$i]/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
            break;
        }
    }
    return false;
}


// return 결과값
function returnData($code=_API_SUCCESS, $data="") {
    if (checkAccessMobile() == true or $code == _API_SUCCESS) { // 모바일 접근 or 성공일때
        include_once _CLASS_DIR."/class.UtilJSON.php";
        $objJSON = new Services_JSON();
        $arrData = array(
            "code"  => $code,
            "data"  => $data
        );
        echo $objJSON->encode($arrData);
    }
    else {  // Web 접근
        if ($code == _API_FAIL) {
            putJSMessage($data);
			exit;
        }
        return null;
    }
    exit;
}

// 파라미터 체크
function checkParam($param, $param_name, $message='') {
    if (empty($message)) $message = " 값이 입력되지 않았습니다.";
    if (empty($param)) returnData(_API_FAIL, $param_name.$message);
}

//xss방지스크립트
function RemoveXSS($val) {
   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
   // this prevents some character re-spacing such as <java\0script>
   // note that you have to handle splits with \n, \r, and \t later since they *are*
   // allowed in some inputs
   $val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);

   // straight replacements, the user should never need these since they're normal characters
   // this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&
   // #X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
   $search = 'abcdefghijklmnopqrstuvwxyz';
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $search .= '1234567890!@#$%^&*()';
   $search .= '~`";:?+/={}[]-_|\'\\';
   for ($i = 0; $i < strlen($search); $i++) {
   // ;? matches the ;, which is optional
   // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

   // &#x0040 @ search for the hex values
      $val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val);
      // with a ;

      // @ @ 0{0,7} matches '0' zero to seven times
      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
   }

   // now the only remaining whitespace attacks are \t, \n, and \r
   $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
   $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
   $ra = array_merge($ra1, $ra2);

   $found = true; // keep replacing as long as the previous round replaced something
   while ($found == true) {
      $val_before = $val;
      for ($i = 0; $i < sizeof($ra); $i++) {
         $pattern = '/';
         for ($j = 0; $j < strlen($ra[$i]); $j++) {
            if ($j > 0) {
               $pattern .= '(';
               $pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?';
               $pattern .= '|(&#0{0,8}([9][10][13]);?)?';
               $pattern .= ')?';
            }
            $pattern .= $ra[$i][$j];
         }
         $pattern .= '/i';
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
         if ($val_before == $val) {
            // no replacements were made, so exit the loop
            $found = false;
         }
      }
   }
   return $val;
}
?>