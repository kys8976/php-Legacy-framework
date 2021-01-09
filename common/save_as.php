<?
class ThisPage extends Page {

	function initialize() {
		$this->setLayout("blank");
	}

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function process() {
	}

	function setDisplay() {
        $file_full_name = _USER_DIR.'/'.$this->reqData['file_path'].'/'.$this->reqData['file_name'];
        $file_size = filesize($file_full_name);
        header("Content-Type: application/ms-x-download");
        header("Content-Type: application/octet-stream");
        header("Content-Length: ".$file_size);
        header("Content-Disposition: attachment; filename=".iconv('UTF-8','CP949',urldecode($this->reqData['orig_name'])));
        header("Content-Transfer-Encoding: binary");
        $fh = fopen($file_full_name, "r");
        fpassthru($fh);
        exit;
	}
}
?>
