<?php
class ThisPage extends Page {
	function initialize() {
        $this->objMember = $this->loadClass("Member");
	}

	function checkParam() {
	}

	function makeJavaScript() {
		$this->addScript("
		function register() {
			if(form.agree1.checked != true) { alert('이용약관에 동의 없이 회원가입을 할수 없습니다'); return false;}
			if(form.agree2.checked != true) { alert('개인정보취급방침에 동의 없이 회원가입을 할수 없습니다'); return false;}
            form.submit();
		}");
	}

	function process() {
        $this->arrData['arrContract'] = $this->objMember->getContract();
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>