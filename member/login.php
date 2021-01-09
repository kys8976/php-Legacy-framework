<?php
class ThisPage extends Page {
	function initialize() {
        $this->setLayout("main");
	}

	function checkParam() {
	}

	function makeJavaScript() {
		$this->addScript("
		function register() {
            if(form.id.value == '') { alert('아이디가 입력되지 않았습니다.'); form.id.focus(); return false;}
            if(form.password.value == '') { alert('패스워드가 입력되지 않았습니다.'); form.password.focus(); return false;}
            document.form.target = 'iframe_process';
			form.submit();
		}
		function onLoad() {
			form.id.focus();
		}
		onload = onLoad;");
	}

	function process() {
	}

	function setDisplay() {
        $arrReturn = array();
        if (isset($this->reqData['return_url'])) {
            $arrReturn['return_url'] = urlencode($this->reqData['return_url']);
        }

        // 아이디 저장
        $arrReturn['save_id'] = "";
        $arrReturn['save_id_checked'] = "";
        if (!empty($_COOKIE['save_id'])) {
            $arrReturn['save_id'] = $_COOKIE['save_id'];
            $arrReturn['save_id_checked'] = " checked";
        }
        return $arrReturn;
	}
}
?>