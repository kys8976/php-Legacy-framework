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
            if(form.title1.value == '') { alert('1등급 명칭이 입력되지 않았습니다.'); form.title1.focus(); return false;}
            if(form.title2.value == '') { alert('2등급 명칭이 입력되지 않았습니다.'); form.title2.focus(); return false;}
            form.target = 'iframe_process';
            form.submit();
        }");
	}

	function process() {
        $this->arrData['data'] = $this->objClass->listLevel($this->reqData);
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>
