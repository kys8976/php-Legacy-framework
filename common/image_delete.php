<?
include _CLASS_DIR."/class.UtilFile.php";
class ThisPage extends Page {
	var $objFile;

	function initialize() {
		$this->setLayout("blank");
		$this->objFile =new UtilFile();
	}

	function checkParam() {
		// 정상접근 체크
		if (!$this->reqData['file_name']) {
			putJSMessage("정상적인 접근이 아닙니다.");
		}
	}

	function process() {
        $arrData = $this->objDBH->getRow("select member_code from ".$this->reqData['table']." where code='".$this->reqData['code']."'");
        if ($arrData['member_code'] && $arrData['member_code'] != getLoginCode()) {
            putJSMessage("본 파일에 대한 접근 권한이 없습니다.");
            exit;
        }

        $file_full_name = _USER_DIR."/".$this->reqData['file_name'];
		if(file_exists($file_full_name)) {
			$arrResultFile = $this->objFile->deleteFile($file_full_name);
			if(!empty($arrResultFile['status']) and $arrResultFile['status'] == "FAIL") {
				putJSMessage($arrResultFile['message']);
			}
			else {
				putJSMessage("해당 파일이 삭제되었습니다.","dialog_parent_reload");
			}
		}
        else {
            putJSMessage("유효하지 않은 파일입니다.");
        }
	}

	function setDisplay() {
	}

	function makeJavaScript() {
	}
}
?>