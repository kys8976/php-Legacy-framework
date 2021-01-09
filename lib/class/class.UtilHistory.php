<?php
/**
 * History Class
 */
class UtilHistory {
	private $objDBH;
	private $arrQuery = array();
    private $table= 'history';

	function __construct($obj) {
		$this->objDBH = $obj;
	}

    function info($reqData) {
        $arrReturn = $this->objDBH->getRow("select * from ".$this->table." where code='".$reqData['code']."'");
        return $arrReturn;
    }

    // 리스트 가져오기 : list_query 값으로 넘기면 parent 단에서 displayDataList() 실행
    function lists($reqData) {
        $arrData = $this->objDBH->getRows("select * from ".$this->table." order by year desc, month desc");
        return $arrData;
    }

    // 연혁등록
	function Insert($reqData) {
        $arrParam = array (
            'year'  => $reqData['year'],
            'month' => $reqData['month'],
            'title' => $reqData['title']
        );
        $this->objDBH->insert("history", $arrParam);
        $code = $this->objDBH->getLastId();
		return $code;
	}

	// 연혁 수정
	function Update($reqData) {
        $arrParam = array (
            'year'  => $reqData['year'],
            'month' => $reqData['month'],
            'title' => $reqData['title']
        );
        $arrWhere = array(
            'code' => $reqData['code']
        );
        $this->objDBH->update("history", $arrParam, $arrWhere);
	}
	// 삭제
	function delete($reqData) {
        $query = "delete from ".$this->table." where code in (".$reqData['code'].")";
        $this->objDBH->query($query);
	}
}
?>
