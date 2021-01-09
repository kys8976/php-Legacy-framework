<?php
class ThisPage extends Page {
    function initialize() {
        $this->checkAdmin();
		$this->setLayout("blank");
        $this->objClass = $this->loadClass("Member");
	}

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function process() {
        switch($this->reqData['mode']) {
            case "contract":
            $this->objClass->updateContract($this->reqData);
			$this->arrMesssage['message'] = "약관 수정";
			break;

            case "info":
            $this->objClass->updateInfo($this->reqData);
			$this->arrMesssage['message'] = "각종정보 수정";
			break;
		}
	}

	function setDisplay() {
        putJSMessage($this->arrMesssage['message']." 되었습니다.","dialog_parent_parent_reload");
	}
}
?>
