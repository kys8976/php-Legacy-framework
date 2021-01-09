<?php
class ThisPage extends Page {
	function initialize() {
        $this->objClass = $this->loadClass("Member");
	}

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function process() {
        if (empty($this->reqData['is_receive_email'])) $this->reqData['is_receive_email'] = 'n';
        if (empty($this->reqData['is_receive_sms'])) $this->reqData['is_receive_sms'] = 'n';
        if (!empty($this->reqData['birthday_year'])) $this->reqData['birthday'] = $this->reqData['birthday_year'].'-'.$this->reqData['birthday_month'].'-'.$this->reqData['birthday_day'];
        if (!empty($this->reqData['mobile1'])) $this->reqData['mobile'] = $this->reqData['mobile1'].'-'.$this->reqData['mobile2'].'-'.$this->reqData['mobile3'];
        if (!empty($this->reqData['tel1'])) $this->reqData['tel'] = $this->reqData['tel1'].'-'.$this->reqData['tel2'].'-'.$this->reqData['tel3'];

        switch($this->reqData['mode']) {
            case "checkId": // 아이디 중복확인
            if ($this->objClass->checkAvailableId($this->reqData['id'])) {
                putJSMessage("사용 가능한 아이디 입니다.");
            }
            else {
                putJSMessage("[".$this->reqData['id']."] 이미 사용중인 아이디 입니다.\\n\\n다른 아이디를 사용하시기 바랍니다.");
            }
            break;

            case "checkEmail": // 이메일 중복확인
            if ($this->objClass->checkAvailableEmail($this->reqData['email'])) {
                putJSMessage("사용 가능한 E-mail 입니다.");
            }
            else {
                putJSMessage("[".$this->reqData['email']."] 이미 사용중인 E-mail 입니다.\\n\\n다른 E-mail을 사용하시기 바랍니다.");
            }
            break;

            case "insert":  // 회원가입
            if (!$this->objClass->checkAvailableId($this->reqData['id'])) {
                putJSMessage("[".$this->reqData['id']."] 이미 사용중인 아이디 입니다.\\n\\n다른 아이디를 사용하시기 바랍니다.");
                exit;
            }
            if (!$this->objClass->checkAvailableMobile($this->reqData['mobile'])) {
                putJSMessage("[".$this->reqData['mobile']."] 이미 사용중인 휴대폰 번호 입니다.\\n\\n다른 휴대폰 번호를 입력하시기 바랍니다.");
                exit;
            }
            $result = $this->objClass->insertMember($this->reqData);
            if ($result > 0) {
                putJSMessage("","parent_replace","?tpf=member/result&name=".urlencode($this->reqData['name']));
            }
            else {
                putJSMessage("오류 발생");
                exit;
            }
            break;

            case "update":  // 회원수정
            $this->reqData['member_code'] = getLoginCode();
            $result = $this->objClass->updateMember($this->reqData);
            if ($result > 0) {
                putJSMessage("회원수정이 완료되었습니다.","parent_replace","/");
            }
            else {
                putJSMessage("오류 발생");
                exit;
            }
            break;

            case "find_id":  // 아이디 찾기 (whoismailer.kr : 965)
            $arrMember = $this->objClass->findId($this->reqData);
            if ($arrMember['email']) {
                $arrReplaceInfo = array(
                    "SITE_NAME" => urlencode(_SITE_NAME),
                    "NAME"      => urlencode($this->reqData['name']),
                    "ID"        => $arrMember['id']
                );
                $title = _SITE_NAME." 회원 아이디를 알려드립니다.";
                $content = file_get_contents(_TEMPLATE_ROOT."/member/mail/find_id.html");

                sendMail($arrMember['email'], $title, $content, $arrReplaceInfo);
                putJSMessage("고객님이 가입하신 [".$arrMember['email']."] 메일로 회원 아이디를 발송하여 드렸습니다.");
            }
            else {
                putJSMessage("이름과 이메일로 일치하는 회원이 없습니다.");
            }
            break;

            case "find_password":  // 비밀번호 찾기 (whoismailer.kr : 966)
            $arrMember = $this->objClass->findPassword($this->reqData);
            if ($arrMember['id']) {
                $arrReplaceInfo = array(
                    "SITE_NAME"     => urlencode(_SITE_NAME),
                    "NAME"          => urlencode($this->reqData['name']),
                    "TMP_PASSWORD"	=> $arrMember['tmp_password']
                );
                $title = _SITE_NAME." 임시 비밀번호를 알려드립니다.";
                $content = file_get_contents(_TEMPLATE_ROOT."/member/mail/find_password.html");

                sendMail($arrMember['email'], $title, $content, $arrReplaceInfo);
                putJSMessage("고객님이 가입하신 [".$arrMember['email']."] 메일로 임시 비밀번호를 발송하여 드렸습니다.");
            }
            else {
                putJSMessage("아이디와 이름, 이메일로 일치하는 회원이 없습니다.");
            }
            break;

            case "sendSMS":
            // 문자 발송
            $this->reqData['mobile'] = preg_replace("/-/", "", $this->reqData['mobile']);
            $auth_number = mt_rand(1000, 9999);

            $arrParam = array (
                'mobile'        => $this->reqData['mobile'],
                'auth_number'   => $auth_number,
                'reg_date'      => "now()"
            );
            $this->objDBH->insert("sms_log", $arrParam);

            include _CLASS_DIR."/class.UtilWhoisSmsAPI.php";
            $sms = new UtilWhoisSmsAPI('send');
            $sms->addParam('msg_type', 'sms');          // 메세지 타입
            $sms->addParam('from_no', _CUSTOMER_TEL);   // 발송 번호
            $sms->addParam('message', '['._SITE_NAME.'] 인증코드 ['.$auth_number.']을 입력해 주세요.'); //발송 메세지

            $to_no = array($this->reqData['mobile']);  // 수신번호 형식2
            $sms->addParam('to_no', $to_no); //
            $exec_result = $sms->execute();
            putJSMessage("입력하신 휴대폰 번호로 인증번호를 발송하였습니다.");
            break;

            default:
            break;
        }
	}

	function setDisplay() {
	}
}
?>