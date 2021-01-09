<?php
class ThisPage extends Page {
	var $strMessage;

	function initialize() {
		$this->checkAdmin();
		$this->setLayout("blank");
		$this->objClass = $this->loadClass("Member");
	}

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function process() {
        switch($this->reqData['mode']) {
			case "insert":	// 등록
            if (!$this->objClass->checkAvailableId($this->reqData['id'])) {
                putJSMessage("[".$this->reqData['id']."] 이미 사용중인 아이디 입니다.\\n\\n다른 아이디를 사용하시기 바랍니다.");
                exit;
            }
            if (!$this->objClass->checkAvailableMobile($this->reqData['mobile'])) {
                putJSMessage("[".$this->reqData['mobile']."] 이미 사용중인 휴대폰 번호 입니다.\\n\\n다른 휴대폰 번호를 입력하시기 바랍니다.");
                exit;
            }
            $this->reqData['mobile'] = $this->reqData['mobile1'].'-'.$this->reqData['mobile2'].'-'.$this->reqData['mobile3'];
			$this->objClass->insertMember($this->reqData);
            $this->arrMesssage['message'] = "등록";
			break;

            case "update":	// 수정
            $this->reqData['mobile'] = $this->reqData['mobile1'].'-'.$this->reqData['mobile2'].'-'.$this->reqData['mobile3'];
            $this->objClass->updateAdmin($this->reqData);
			$this->arrMesssage['message'] = "수정";
			break;

            case "checkId": // 아이디 중복확인
            if ($this->objClass->checkAvailableId($this->reqData['id'])) {
                putJSMessage("사용 가능한 아이디 입니다.");
                exit;
            }
            else {
                putJSMessage("[".$this->reqData['id']."] 이미 사용중인 아이디 입니다.\\n\\n다른 아이디를 사용하시기 바랍니다.");
                exit;
            }
            break;

            case "sms":     // sms 발송
            $arrReturn = $this->objClass->lists($this->reqData);
            $arrMember = $this->objDBH->getRows($arrReturn['list_query']);
            $arrReceive = array();
            if ($arrMember['total'] > 0) {
                foreach($arrMember['list'] as $key => $val) {
                    $arrReceive[] = $val['mobile'];
                }
            }
            $result = sendSMS(implode($arrReceive,","), $_POST['msg']);
            if ($result['code'] == _API_SUCCESS) {  // 성공일때
                putJSMessage("SMS 발송을 완료하였습니다.\\n[".$result['data']."]\\n서버환경에 따라 다소 시간이 걸릴수 있습니다.","parent_reload");
            }
			exit;
			break;

			case "delete":	// 삭제
			$reqData['code'] = $this->reqData['code'] ? $this->reqData['code'] : getArrayValue($this->reqData['list']);
            $reqData['status'] = "n";
            $this->objClass->updateAdminMember($reqData);
			$this->arrMesssage['message'] = "삭제";
			break;

            case "level":   // 등급관리
            $this->objClass->updateLevel($this->reqData);
            $this->arrMesssage['message'] = "등급이 수정";
            putJSMessage($this->arrMesssage['message']." 되었습니다.","parent_reload");
            exit;
            break;
		}
	}

	function setDisplay() {
		putJSMessage("해당 회원이 ".$this->arrMesssage['message']." 되었습니다.","dialog_parent_parent_reload");
	}
}
?>
