<?php
/**
*
* API class
* Copyright (c) 2015 MIT
*
* @author	Lim, Sangjun  <porchingman@naver.com>
* @version	$Id: class.API.php,v 1.00 2015/10/17 15:49:47 Sangjun $
* @param	string $param
* @return	array
* @access	public
*/

class API {
    var $objDBH;
    var $objClass;
    var $reqData;
    var $strMethod;
    var $arrData = array();

	function __construct() {
        $this->objDBH = new DB();
        $rawData = $_POST ? $_POST : $_GET;
        $this->reqData = $this->objDBH->escape($rawData);   // real_escape_string 처리
        $this->setAPILog(); // API 로그 찍기
	}

    function loadClass($name) {
        if (empty($name)) returnData(_API_FAIL, "method value is empty");

        list($class_name, $this->strMethod) = explode(".", $name);
        $class_file = _CLASS_DIR."/class.".$class_name.".php";
        if (file_exists($class_file)) {
            include_once $class_file;
            $this->objClass = new $class_name($this->objDBH); // instantiate class
        }
        else returnData(_API_FAIL, "존재하지 않는 method(1차) 입니다.");
    }

    function runMethod() {
        if(!method_exists($this->objClass, $this->strMethod)) returnData(_API_FAIL, "존재하지 않는 method(2차) 입니다.");
        $method = $this->strMethod;
        $arrResult = $this->objClass->$method($this->reqData);

        if (!empty($arrResult['list_query'])) {   // list query 일때 (page 단위로 자료 가져오기)
            $arrResultTmp = $this->displayDataList($arrResult['list_query']);
            if (!empty($arrResult['callback'])) { // call callback function
                $arrResult = $this->objClass->$arrResult['callback']($arrResultTmp);
            }
            else $arrResult = $arrResultTmp;
            if ($arrResult['list'] == "") $arrResult['list'] = null;
        }
        returnData(_API_SUCCESS, $arrResult);
    }

    function displayDataList($query) {
        if(empty($this->reqData['page'])) $page = 1;    // 현재페이지
		else $page = $this->reqData['page'];
        $print_data_count = empty($this->reqData['count']) ? _DISPLAY_DATA_COUNT_MOBILE : $this->reqData['count'];    // 한페이지 표출되는 data 개수
        $offset = ($page - 1) * $print_data_count;	// 현재페이지 이전까지의 데이타수

        $total = $this->objDBH->getNumRows($query); // 총 개수

        $arrData = $this->objDBH->getRows($query." limit ".$offset.", ".$print_data_count);
        $arrData['total'] = $total;

        return $arrData;
    }

    function initListCount() {
        $this->reqData['limit'] = $this->reqData['limit'] ? $this->reqData['limit'] : _LIST_LIMIT;
        $this->reqData['page'] = $this->reqData['page'] ? $this->reqData['page'] : 0;
        $this->reqData['start_count'] = $this->reqData['limit'] * $this->reqData['page'];
    }

    function setAPILog($query="") {
        $fp=fopen(_DOCUMENT_ROOT_DIR."/api/log.txt","a");
        $datetime = date("Y/m/d H:i:s");
        fwrite($fp, $datetime."\n");

        foreach($this->reqData as $key => $val) {
            fwrite($fp, $key." : ".$val."\n");
        }
        if ($query) {
            fwrite($fp, "\nquery : ".$query);
        }
        fwrite($fp, "\n");
    }

    // 순서 변경
    function changeOrder($reqData) {
        $result = "y";
        $order_code = $message = "";

        switch($reqData['table']) {
            case "board_data":  // 게시판
            $add_where = "";
            if(!empty($reqData['category_code'])) {
                $add_where = " and board_code = '".$reqData['category_code']."'";
            }
            $field_name = "num";
            $where_up = $field_name." < '".$reqData['order_code']."'".$add_where." order by ".$field_name." desc limit 1";
            $where_down = $field_name." > '".$reqData['order_code']."'".$add_where." order by ".$field_name." limit 1";
            break;

            case "category":  // category
            $field_name = "order_code";
            $category_length = strlen(@$reqData['category_code']) + _CATEGORY_LENGTH;
            $add_where = " and category_code like '".$reqData['category_code']."%' and length(category_code)='".$category_length."'";
            $where_up = $field_name." < '".$reqData['order_code']."'".$add_where." order by ".$field_name." desc limit 1";
            $where_down = $field_name." > '".$reqData['order_code']."'".$add_where." order by ".$field_name." limit 1";
            break;

            default:			// order by oder_code desc
            $field_name = "order_code";
            $add_where = "";
            if(!empty($reqData['category_code'])) {
                $add_where = " and category_code = '".$reqData['category_code']."'";
            }
            $where_up = $field_name." > '".$reqData['order_code']."'".$add_where." order by ".$field_name." limit 1";
            $where_down = $field_name." < '".$reqData['order_code']."'".$add_where." order by ".$field_name." desc limit 1";
            break;
        }

        if($reqData['direction'] == "up") {	// 상위로의 순서변경
            $arrData = $this->objDBH->getRow("select ".$field_name." from ".$reqData['table']." where ".$where_up);
            if($arrData[$field_name] == NULL) {
                $result = "n";
                $message = "더이상 상위로의 위치 변경은 불가능합니다.";
            }
        }
        else {								// 하위로의 순서변경
            $arrData = $this->objDBH->getRow("select ".$field_name." from ".$reqData['table']." where ".$where_down);
            if($arrData[$field_name] == NULL) {
                $result = "n";
                $message = "더이상 하위로의 위치 변경은 불가능합니다.";
            }
        }

        if ($result == "y") {
            $this->objDBH->query("update ".$reqData['table']." set ".$field_name."=0 where ".$field_name."=".$reqData['order_code'].$add_where);
            $this->objDBH->query("update ".$reqData['table']." set ".$field_name."=".$reqData['order_code']." where ".$field_name."=".$arrData[$field_name].$add_where);
            $this->objDBH->query("update ".$reqData['table']." set ".$field_name."=".$arrData[$field_name]." where ".$field_name."=0".$add_where);
            $order_code = $arrData[$field_name];
        }

        $arrReturn = array();
        $arrReturn['order_code'] = $order_code;
        $arrReturn['result'] = $result;
        $arrReturn['message'] = $message;
        return $arrReturn;
    }
}
?>