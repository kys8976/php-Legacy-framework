<?php
/**
 * Account Setting
 *
 */
include _CLASS_DIR."/class.UtilFile.php";
class Setting {
	function __construct($obj) {
		$this->objDBH = $obj;
	}

    // 정보 가져오기
    function view_notice($reqData) {
        checkParam($reqData['board_list_code'], "board_list_code");
        checkParam($reqData['code'], "code");

        $arrReturn = $this->objDBH->getRow("select * from board where code='".$reqData['code']."'");
        if(file_exists(_USER_DIR."/board/".$reqData['code'])) {
            $arrReturn['file'] = $reqData['code'];
        }
        return $arrReturn;
    }

    // 리스트 가져오기 : list_query 값으로 넘기면 parent 단에서 displayDataList() 실행
    function list_notice($reqData) {
        checkParam($reqData['board_list_code'], "board_list_code");

        $add_where = !empty($reqData['keyword']) ? " and ".$reqData['field']." like '%".$reqData['keyword']."%'" : "";
        $arrReturn['list_query'] = "select code,title,content,name,hitting,date_format(reg_date,'%Y-%m-%d %H:%i') as reg_date from board where board_list_code='".$reqData['board_list_code']."'".$add_where." order by code desc";
        return $arrReturn;
    }

	// 등록
	function insert($reqData) {
        $objFile = new UtilFile();
        if ($_FILES['file']['name']) {  // 파일첨부시
            $arrResultFile = $objFile->checkFile("Img","file");
            if($arrResultFile['status'] == "FAIL") {
                putJSMessage("[".$arrResultFile['message']."]","back");
                exit;
            }
            else {
                $strFileName = strtolower($_FILES['file']['name']);
            }
        }

        $arrParam = array (
            'board_list_code'  => $reqData['board_list_code'],
            'name'          => $reqData['name'],
            'title'         => $reqData['title'],
            'content'       => $reqData['content'],
            'file_name'     => $reqData['file_name'],
            'ip'            => $_SERVER['REMOTE_ADDR'],
            'reg_date'      => "now()"
        );
        $this->objDBH->insert("board", $arrParam);
        $code = $this->objDBH->getLastId();

        if ($strFileName) {
            $objFile->uploadFile("file",_USER_DIR."/Setting/".$code);
        }
		return $code;
	}

	// 수정
	function update($reqData) {
        $objFile = new UtilFile();
        if ($_FILES['file']['name']) {  // 파일첨부시
            $arrResultFile = $objFile->checkFile("Img","file");
            if($arrResultFile['status'] == "FAIL") {
                putJSMessage("[".$arrResultFile['message']."]","back");
                exit;
            }
            else {
                $objFile->uploadFile("file",_USER_DIR."/Setting/".$reqData['code']);
            }
        }

        $arrParam = array (
            'name'      => $reqData['name'],
            'title'     => $reqData['title'],
            'content'   => $reqData['content'],
            'file_name' => $reqData['file_name']
        );
        $arrWhere = array(
            'code' => $reqData['code'],
            'Setting_list_code' => $reqData['Setting_list_code']
        );
        $this->objDBH->update("board", $arrParam, $arrWhere);
	}

	// 삭제
	function delete($reqData) {
		$query = "delete from board where code in (".$reqData['code'].") and board_list_code='".$reqData['board_list_code']."'";
		$this->objDBH->query($query);
	}

    // 알림 설정
	function push($reqData) {
        checkParam($reqData['member_code'], "member_code");
        checkParam($reqData['is_push'], "is_push");

        $arrParam = array (
            'is_push'   => $reqData['is_push']
        );
        $arrWhere = array(
            'code' => $reqData['member_code']
        );
        $this->objDBH->update("member", $arrParam, $arrWhere);
        return "update completed";
	}

    // 보관함 개수
    function message_count($reqData) {
        checkParam($reqData['member_code'], "member_code");

        $count = $this->objDBH->getNumRows("select * from message where member_code='".$reqData['member_code']."' and is_confirm='n'");
        return $count;
    }

    // 보관함
    function message($reqData) {
        checkParam($reqData['member_code'], "member_code");

        $arrReturn['list_query'] = "select code,member_code,job_code,type,title,is_confirm,confirm_date,date_format(reg_date,'%Y-%m-%d %H:%i') as reg_date from message where member_code='".$reqData['member_code']."' order by code desc";
        return $arrReturn;
    }

    // 내 보관함 읽음 action
    function confirm_message($reqData) {
        checkParam($reqData['message_code'], "message_code");
        checkParam($reqData['member_code'], "member_code");

        $arrData = $this->objDBH->getRow("select job_code,title from message where code='".$reqData['message_code']."' and member_code='".$reqData['member_code']."'");
        if ($arrData['job_code']) {
            // 읽음 상태로 변경
            $arrParam = array (
                'is_confirm'    => "y",
                'confirm_date'  => "now()"
            );
            $arrWhere = array(
                'code' => $reqData['message_code'],
                'member_code' => $reqData['member_code']
            );
            $this->objDBH->update("message", $arrParam, $arrWhere);
            return "confirm message";
        }
        else {
            returnData(_API_FAIL, "message_code 값이 유효하지 않습니다.");
        }
    }
}
?>