<?php
class ThisPage extends Page {
	function initialize() {
	}

	function checkParam() {
	}

	function makeJavaScript() {
		$this->addScript("
		function findId() {
            if(form1.name.value == '') { alert('이름이 입력되지 않았습니다.'); form1.name.focus(); return false;}
            if(form1.email.value == '') { alert('이메일이 입력되지 않았습니다.'); form1.email.focus(); return false;}
            form1.target = 'iframe_process';
            form1.submit();
		}
        function findPassword() {
            if(form2.id.value == '') { alert('아이디가 입력되지 않았습니다.'); form2.id.focus(); return false;}
            if(form2.name.value == '') { alert('이름이 입력되지 않았습니다.'); form2.name.focus(); return false;}
            if(form2.email.value == '') { alert('이메일이 입력되지 않았습니다.'); form2.email.focus(); return false;}
            form2.target = 'iframe_process';
			form2.submit();
		}");
	}

	function process() {
	}

	function setDisplay() {
        // return $this->arrData;
	}
}
?>