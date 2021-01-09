<?php
class ThisPage extends Page {
	function initialize() {
        $this->objClass = $this->loadClass("Board");
        $this->objCaptcha = $this->loadClass("Captcha");
        $this->arrData['board_info'] = $this->objClass->info($this->reqData);
        $this->setLayout("blank");
	}

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function process() {
        $this->reqData['member_code'] = getLoginCode(); // member_code
        switch($this->reqData['mode']) {
			case "insert":  // 등록
            case "reply":   // 답변
            $this->objClass->checkAuth('write');    // 권한 체크
            if($this->arrData['board_info']['is_captcha'] == 'y' and !$this->objCaptcha->checkCaptcha($this->reqData['auth_key'])) {
                putJSMessage("보안코드가 일치하지 않습니다.");
                exit;
            }
			$this->objClass->insert($this->reqData);
			$this->arrMesssage['message'] = "등록";
			break;

			case "update":  // 수정
            $this->objClass->checkPassword($this->reqData);
            $this->objClass->checkAuth('update');    // 권한 체크
            if($this->arrData['board_info']['is_captcha'] == 'y' and !$this->objCaptcha->checkCaptcha($this->reqData['auth_key'])) {
                putJSMessage("보안코드가 일치하지 않습니다.");
                exit;
            }
            $this->reqData['delete_file'] = preg_replace('/,$/','',$this->reqData['delete_file']);
			$this->objClass->update($this->reqData);
			$this->arrMesssage['message'] = "수정";
			break;

            case "delete":  // 삭제
            $this->objClass->checkPassword($this->reqData);
            $this->objClass->checkAuth('delete');    // 권한 체크
            $this->reqData['code'] = !empty($this->reqData['board_data_code']) ? $this->reqData['board_data_code'] : getArrayValue($this->reqData['list']);
            $this->objClass->delete($this->reqData);
            $this->arrMesssage['message'] = "삭제";
            break;

            case "view":    // 비밀글 보기
            $this->objClass->checkPassword($this->reqData);
            putJSMessage("","parent_replace","?tpf=board/view&board_code=".$this->reqData['board_code']."&code=".$this->reqData['board_data_code']."&password=".$this->reqData['password']);
            exit;
            break;

            case "insertMemo":    // 댓글 등록
            $this->objClass->checkAuth('memo');    // 권한 체크
            if($this->arrData['board_info']['is_captcha'] == 'y' and !$this->objCaptcha->checkCaptcha($this->reqData['auth_key'])) {
                putJSMessage("보안코드가 일치하지 않습니다.");
                exit;
            }
            $this->objClass->insertMemo($this->reqData);
            putJSMessage("댓글이 등록 되었습니다.","parent_replace","?tpf=board/view&board_code=".$this->reqData['board_code']."&code=".$this->reqData['board_data_code']);
            exit;

            case "deleteMemo":    // 댓글 삭제
            $this->objClass->checkAuth('memo');    // 권한 체크
            $this->objClass->checkPasswordMemo($this->reqData);    // 권한 체크
            $this->objClass->deleteMemo($this->reqData['board_data_code'],$this->reqData['code']);
            putJSMessage("댓글이 삭제 되었습니다.","parent_replace","?tpf=board/view&board_code=".$this->reqData['board_code']."&code=".$this->reqData['board_data_code']);
            exit;
            break;
		}
	}

	function setDisplay() {
        putJSMessage($this->arrMesssage['message']." 되었습니다.","parent_replace","?tpf=board/list&board_code=".$this->reqData['board_code']);
	}
}
?>
