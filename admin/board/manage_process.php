<?php
class ThisPage extends Page {
	var $strMessage;

	function initialize() {
        $this->checkAdmin();
		$this->setLayout("blank");
		$this->objClass = $this->loadClass("Board");
	}

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function process() {
        $this->reqData['member_code'] = getLoginCode(); // 관리자 member_code : 1 셋팅
        switch($this->reqData['mode']) {
			case "insert":
            $this->objClass->insertBoard($this->reqData);
			$this->arrMesssage['message'] = "등록";
			break;

			case "update":        // 정보 수정
            $this->reqData['delete_file'] = preg_replace('/,$/','',$this->reqData['delete_file']);
			$this->objClass->updateBoard($this->reqData);
			$this->arrMesssage['message'] = "수정";
			break;

            case "deleteBoard":        // 정보 수정
            $this->reqData['code'] = !empty($this->reqData['code']) ? $this->reqData['code'] : getArrayValue($this->reqData['list']);
			$this->objClass->deleteBoard($this->reqData);
			$this->arrMesssage['message'] = "삭제";
			break;
		}
	}

	function setDisplay() {
		putJSMessage("게시판이 ".$this->arrMesssage['message']." 되었습니다.","dialog_parent_parent_reload");
	}
}
?>
