<?
include _CLASS_DIR."/util/class.File.php";
class ThisPage extends Page {

	function initialize() {
		$this->setLayout("blank");
		$this->objFile = new File();
	}

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function process() {
		$file_real_name = base64_decode(preg_replace("/ /","+",$this->reqData['file_real_name']));
		$this->objFile->download($file_real_name,$this->reqData['file_name']);
	}

	function setDisplay() {
	}
}
?>
