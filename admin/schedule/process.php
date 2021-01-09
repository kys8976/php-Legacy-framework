<?php
class ThisPage extends Page {
	function initialize() {
		$this->checkAdmin();
		$this->setLayout("blank");
		$this->objClass = $this->loadClass("UtilSchedule");
	}

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function process() {
        switch($this->reqData['mode']) {
			case "insert":	// 등록
            $this->objClass->insert($this->reqData);
            $this->arrMesssage['message'] = "등록";
			break;

            case "update":	// 수정
            $this->objClass->update($this->reqData);
			$this->arrMesssage['message'] = "수정";
			break;

			case "delete":	// 삭제
			$reqData['code'] = $this->reqData['code'] ? $this->reqData['code'] : getArrayValue($this->reqData['list']);
            $this->objClass->delete($reqData);
			$this->arrMesssage['message'] = "삭제";
			break;
		}
	}

	function setDisplay() {
		putJSMessage("일정이 ".$this->arrMesssage['message']." 되었습니다.","parent_replace","index.php?tpf=admin/schedule/list&selected_date=".substr($this->reqData['start_date'],0,7));
	}
}
?>