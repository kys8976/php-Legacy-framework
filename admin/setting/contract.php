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
            if(form_register.privacy.value == '') { alert('개인정보 취급방침이 입력되지 않았습니다.'); form_register.privacy.focus(); return false;}
            if(form_register.provision.value == '') { alert('이용약관이 입력되지 않았습니다.'); form_register.provision.focus(); return false;}
            form_register.target = 'iframe_process';
            form_register.submit();
        }");
	}

	function process() {
        $this->arrData = $this->objClass->getContract();
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>