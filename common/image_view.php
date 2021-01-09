<?
class ThisPage extends Page {
	private $width;
	private $height;
    private $arrData;

	function initialize() {
		$this->setLayout("blank");
		$size = getimagesize(_USER_DIR."/".$this->reqData['file_name']);
        $this->arrData['width'] = $size[0] += 40;
		$this->arrData['height'] = $size[1] += 95;
	}

	function checkParam() {
	}

    function makeJavaScript() {
	}

	function process() {
	}

	function setDisplay() {
		$this->arrData['file_name'] = _USER_URL."/".$this->reqData['file_name'];
        return $this->arrData;
	}
}
?>