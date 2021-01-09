<?php
class ThisPage extends Page {
    function initialize() {
        $this->checkAdmin();
		$this->setLayout("blank");
        $this->objClass = $this->loadClass("UtilPopup");
	}

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function process() {
        switch($this->reqData['mode']) {
			case "insert":
			$this->objClass->insert($this->reqData);
			$this->arrMesssage['message'] = "등록";
			break;

			case "update":
			$this->objClass->update($this->reqData);
			$this->arrMesssage['message'] = "수정";
			break;

            case "delete":
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
