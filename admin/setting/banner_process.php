<?php
class ThisPage extends Page {
	function initialize() {
        $this->checkAdmin();
		$this->setLayout("blank");
		$this->objClass = $this->loadClass("UtilBanner");
	}

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function process() {
        $this->reqData['member_code'] = getLoginCode(); // 관리자 member_code : 1 셋팅
        switch($this->reqData['mode']) {
            //insert 메인롤링이미지
			case "insert":
            $this->objClass->insert($this->reqData);
			$this->arrMesssage['message'] = "등록";
			break;

            case "update":        // 정보 수정
            $this->objClass->update($this->reqData);
			$this->arrMesssage['message'] = "수정";
			break;

            case "delete":        // 정보 수정
            $this->reqData['code'] = !empty($this->reqData['code']) ? $this->reqData['code'] : getArrayValue($this->reqData['list']);
            $this->objClass->delete($this->reqData);
			$this->arrMesssage['message'] = "삭제";
			break;

		}
	}

	function setDisplay() {
		putJSMessage($this->arrMesssage['message']." 되었습니다.","dialog_parent_parent_reload");
	}
}
?>
