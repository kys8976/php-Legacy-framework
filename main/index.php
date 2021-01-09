<?php
class ThisPage extends Page {
    private $arrData;

	function initialize() {
        if (checkAccessMobile() == true) {  // mobile 접속일때
            $_SESSION['is_mobile'] = 'y';
        }
        else {
            $_SESSION['is_mobile'] = '';
        }

        $this->arrData = array(
            'title' => '메인페이지'
        );
	}

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function process() {
        // 게시판
        $arrBoard = $this->objDBH->getRows("select code,name,title,date_format(reg_date,'%Y.%m.%d') as reg_date from board_data where board_code=1 order by code desc");
        $this->arrData = array_merge($arrBoard, $this->arrData);

        // 팝업
		$arrPopup = $this->objDBH->getRows("select * from popup where display = 'y' and now() between start_date and end_date");
		$this->arrData['popup'] = $arrPopup;

        // 배너
        $this->arrData['banner'] = $this->objDBH->getRows("select code,title,url from banner order by code desc");
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>