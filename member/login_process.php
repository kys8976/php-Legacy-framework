<?php
class ThisPage extends Page {
	private $strReturnUrl;
	private $objMember;

	function initialize() {
        $this->objMember = $this->loadClass("Member");
	}

	function checkParam() {
		$this->strReturnUrl = !empty($this->reqData['return_url']) ? urldecode($this->reqData['return_url']) : _HOME_URL;
	}

	function makeJavaScript() {
	}

	function process() {
        if (empty($this->reqData['is_admin'])) $arrMember = $this->objMember->checkLogin($this->reqData);   // 일반인 로그인
        else $arrMember = $this->objMember->checkAdminLogin($this->reqData);                                // 관리자 로그인

        if ($arrMember['name']) {
            $this->objMember->setLastLoginDate($arrMember['code']); // 최근 로그인 날짜 update

			$_SESSION['login_code'] = $arrMember['code'];
			$_SESSION['login_id'] = $this->reqData['id'];
			$_SESSION['login_name'] = $arrMember['name'];
			$_SESSION['login_level'] = $arrMember['level'];
		}
		else {
			putJSMessage("회원 아이디가 없거나 비밀번호가 틀립니다");
			exit;
		}
	}

	function setDisplay() {
        putJSMessage("","parent_replace",$this->strReturnUrl);
	}
}
?>