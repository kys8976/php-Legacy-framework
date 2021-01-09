<?php
class ThisPage extends Page {
	function initialize() {
    }

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function process() {
	}

	function setDisplay() {
        $this->arrData['site_name'] = _SITE_NAME;
        $this->arrData['name'] = $this->reqData['name'];
        return $this->arrData;
	}
}
?>