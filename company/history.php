<?php
class ThisPage extends Page {
    private $arrData;

	function initialize() {
		$this->objClass = $this->loadClass("UtilHistory");

	}

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function process() {
        $this->arrData['data'] = $this->objClass->lists($this->reqData);
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>