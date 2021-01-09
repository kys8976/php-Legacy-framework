<?php
class ThisPage extends Page {
	function initialize() {
        $this->setLayout("admin_iframe");
	}

	function checkParam() {
	}

	function makeJavaScript() {
        $this->addScript("
		function register() {
            if(form.id.value == '') { alert('아이디가 입력되지 않았습니다.'); form.id.focus(); return false;}
            if(form.password.value == '') { alert('패스워드가 입력되지 않았습니다.'); form.password.focus(); return false;}
            form.target = 'iframe_process';
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
        if (!empty($this->reqData['return_url'])) {
            $data['return_url'] = urlencode($this->reqData['return_url']);
            return $data;
        }
	}
}
?>
