<?
class ThisPage extends Page {
  	function initialize() {
        $this->checkAdmin();
		$this->setLayout('blank');
        $this->objClass = $this->loadClass("BizProduct");
	}

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function process() {
        switch($this->reqData['mode']) {
            // category
            case "insertCategory":	// 입력
            $this->objClass->insertCategory($this->reqData);
            $this->arrMesssage['message'] = "카테고리가 등록 되었습니다.";
            $this->arrMesssage['action'] = "dialog_parent_reload";
			break;

            case "updateCategory":	// 수정
            $count = $this->objClass->updateCategory($this->reqData);
            $this->arrMesssage['message'] = "카테고리가 수정 되었습니다.";
            $this->arrMesssage['action'] = "dialog_parent_reload";
			break;

			case "deleteCategory":	// 삭제
            $this->reqData['code'] = !empty($this->reqData['code']) ? $this->reqData['code'] : getArrayValue($this->reqData['list']);
            $this->objClass->deleteCategory($this->reqData);
			$this->arrMesssage['message'] = "해당 카테고리가 삭제 되었습니다.";
            $this->arrMesssage['action'] = "dialog_parent_parent_reload";
			break;

            // product
            case "insertProduct":	// 입력
            $this->reqData['mark'] = getArrayValue($this->reqData['mark']);
		    $this->objClass->_checkFile();  // 파일 유효성 체크
            $this->objClass->insertProduct($this->reqData);
		    $this->objClass->_uploadFile(); // 파일 첨부
            $this->arrMesssage['message'] = "상품이 등록 되었습니다.";
            $this->arrMesssage['action'] = "dialog_parent_reload";
            break;

            case "updateProduct":	// 수정
            $this->reqData['mark'] = getArrayValue($this->reqData['mark']);
            $this->objClass->_checkFile();  // 파일 유효성 체크
            $this->objClass->updateProduct($this->reqData);
		    $this->objClass->_uploadFile(); // 파일 첨부
            $this->arrMesssage['message'] = "상품이 수정 되었습니다.";
            $this->arrMesssage['action'] = "dialog_parent_reload";
			break;

            case "deleteProduct":	// 삭제
            $this->reqData['code'] = !empty($this->reqData['code']) ? $this->reqData['code'] : getArrayValue($this->reqData['list']);
            $this->objClass->deleteProduct($this->reqData);
			$this->arrMesssage['message'] = "해당 상품이 삭제 되었습니다.";
            $this->arrMesssage['action'] = "dialog_parent_reload";
			break;
		}
	}

	function setDisplay() {
		putJSMessage($this->arrMesssage['message'], $this->arrMesssage['action']);
	}
}
?>
