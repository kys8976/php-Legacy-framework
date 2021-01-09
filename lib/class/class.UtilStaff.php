<?php
/**
 * Staff Class
 *
 */
include _CLASS_DIR."/class.UtilFile.php";
class UtilStaff {
	private $objDBH;
	private $arrQuery = array();
    private $table= 'staff';
    private $strFileName;

	function __construct($obj) {
		$this->objDBH = $obj;
        $this->objClass = new UtilFile();
	}

    // 정보 가져오기
    function info($reqData) {
        $arrReturn = $this->objDBH->getRow("select * from ".$this->table." where code='".$reqData['code']."'");
        // 파일표출
        $image_url = '';
        if(file_exists(_USER_DIR."/staff/".$reqData['code'])) {
            $image_url = _USER_URL."/staff/".$reqData['code']."?dummy=".getDummy();
        }
        $arrReturn['image_url'] = $image_url;

        return $arrReturn;
    }

    // 리스트 가져오기 : list_query 값으로 넘기면 parent 단에서 displayDataList() 실행
    function lists($reqData) {
        $where = !empty($reqData['keyword']) ? " where ".$reqData['field']." like '%".$reqData['keyword']."%'" : "";

        $arrData = $this->objDBH->getRows("select * from ".$this->table.$where." order by order_code");
        return $arrData;
    }

    function getMinNum() {
        $arrBoard = $this->objDBH->getRow("select min(order_code) as min from ".$this->table);
		if (!$arrBoard['min']) $arrBoard['min'] = -1;
		else $arrBoard['min']--;
		return $arrBoard['min'];
	}

    // 등록
	function insert($reqData) {
        $order_code = $this->getMinNum();
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
            'name'      => $reqData['name'],
            'career'    => $reqData['career'],
            'profile'   => $reqData['profile'],
            'position'  => $reqData['position'],
            'order_code'=>$order_code,
            'reg_date'  => "now()"
        );
        $this->objDBH->insert("staff", $arrParam);
        $code = $this->objDBH->getLastId();

        if ($this->strFileName != '') {
            $this->objClass->uploadFile("file1",_USER_DIR."/staff/".$code);
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
                $this->objClass->uploadFile("file1",_USER_DIR."/staff/".$reqData['code']);
            }
        }

        $arrParam = array (
            'name'      => $reqData['name'],
            'career'    => $reqData['career'],
            'profile'   => $reqData['profile'],
            'position'  => $reqData['position']
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
