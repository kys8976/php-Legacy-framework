<?php
class ThisPage extends Page {
	function initialize() {
        $this->checkAdmin();
        $this->objClass = $this->loadClass("Member");
	}

	function checkParam() {
	}

	function makeJavaScript() {
        $this->addScript("
        function register() {
            form_register.target = 'iframe_process';
            form_register.submit();
        }");
	}

	function process() {
        $this->arrData = $this->objClass->getInfo();
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>