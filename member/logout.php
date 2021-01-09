<?
class ThisPage extends Page {
	function initialize() {
		$this->checkLogin();
	}

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function process() {
		session_destroy();
	}

	function setDisplay() {
		putJSMessage("","replace",_HOME_URL);
	}
}
?>
