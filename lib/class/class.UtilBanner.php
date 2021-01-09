<?php
/**
 * Banner Class
 *
 */
include _CLASS_DIR."/class.UtilFile.php";
class UtilBanner {
	private $objDBH;
	private $arrQuery = array();
    private $table= 'banner';
    private $strFileName;

	function __construct($obj) {
		$this->objDBH = $obj;
        $this->objClass = new UtilFile();
	}

    // 메인 롤링 이미지 정보 가져오기
    function info($reqData) {
        $arrReturn = $this->objDBH->getRow("select * from ".$this->table." where code='".$reqData['code']."'");
        // 파일표출
        $image_url = '';
        if(file_exists(_USER_DIR."/banner/".$reqData['code'])) {
            $image_url = _USER_URL."/banner/".$reqData['code']."?dummy=".getDummy();
        }
        $arrReturn['image_url'] = $image_url;

        return $arrReturn;
    }

    function lists($reqData) {
        $arrData = $this->objDBH->getRows("select * from ".$this->table." order by code desc");
        return $arrData;
    }

    // 메인 롤링 이미지 등록
	function insert($reqData) {
        if ($_FILES['file1']['name']) {  // 파일첨부시
            $arrResultFile = $this->objClass->checkFile("Img","file".$i);
            if($arrResultFile['status'] == "FAIL") {
                putJSMessage("[".$arrResultFile['message']."]","back");
                exit;
            }
            else {
                $this->strFileName = 1;
            }
        }
        $arrParam = array (
            'title'     => $reqData['title'],
            'url'       => $reqData['url'],
            'reg_date'  => "now()"
        );
        $this->objDBH->insert("banner", $arrParam);
        $code = $this->objDBH->getLastId();

        if ($this->strFileName != '') {
            $this->objClass->uploadFile("file1",_USER_DIR."/banner/".$code);
        }
		return $code;
	}

    // 수정
	function update($reqData) {
        if ($_FILES['file1']['name']) {  // 파일첨부시
            $arrResultFile = $this->objClass->checkFile("Img","file");
            if($arrResultFile['status'] == "FAIL") {
                putJSMessage("[".$arrResultFile['message']."]","back");
                exit;
            }
            else {
                $this->objClass->uploadFile("file1",_USER_DIR."/banner/".$reqData['code']);
            }
        }

        $arrParam = array (
            'title'     => $reqData['title'],
            'url'       => $reqData['url'],
        );
        $arrWhere = array(
            'code' => $reqData['code']
        );
        $this->objDBH->update($this->table, $arrParam, $arrWhere);
	}

	// 삭제
	function delete($reqData) {
        $query = "delete from ".$this->table." where code in (".$reqData['code'].")";
        $this->objDBH->query($query);

        // code 값 배열로 넘어오는 부분 삭제 하기
		// 파일 삭제
		$arrCode = explode(",",$reqData['code']);
		foreach($arrCode as $key => $val) {
            $file_full_name = _USER_DIR."/banner/".$val;
            if($val and file_exists($file_full_name)) {
                $arrResultFile = $this->objClass->deleteFile($file_full_name);
                if($arrResultFile['status'] == "FAIL") {
                    putJSMessage($arrResultFile['message'],"close");
                }
            }
		}
	}
}
?>
