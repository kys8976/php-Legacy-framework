<?php
class ThisPage extends Page {
	function initialize() {
		$this->checkAdmin();
        $this->objClass = $this->loadClass("BizProduct");

        $arrMyConfig = getCFG("MyConfig");
        $this->arrData['arrDisplayStatus'] = $arrMyConfig['DisplayStatus'];
	}

	function checkParam() {
	}

	function makeJavaScript() {
        $this->addScript("
        function register() {
            if(form_register.title.value == '') { alert('카테고리명이 입력되지 않았습니다.'); form_register.title.focus(); return false;}
            form_register.target = 'iframe_process';
            form_register.submit();
        }
        function checkHeight() {
            var height = $(window).height() - 200;
            document.getElementById('iframe_tree').height = height;
            document.getElementById('iframe_list').height = height;
        }
        checkHeight();");
	}

	function process() {
	}

	function setDisplay() {
        $this->arrData['category_code'] = !empty($this->reqData['category_code']) ? $this->reqData['category_code'] : "";
        return $this->arrData;
	}
}
?>