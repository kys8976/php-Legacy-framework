<?php
class ThisPage extends Page {
    function initialize() {
        $this->objClass = $this->loadClass("Board");
        $this->arrData['board_info'] = $this->objClass->info($this->reqData);

        // 권한 체크
        $this->objClass->checkAuth('view');
	}

	function checkParam() {
	}

	function makeJavaScript() {
        $memo_script = "";
        if ($this->arrData['board_info']['auth_memo'] != 0) {   // 비회원 권한이 아닐때
            if (!isLogin()) {
                $memo_script = "alert('댓글 입력을 위해서 먼저 로그인을 하세요.');return;";
            }
            else if ($this->arrData['board_info']['auth_memo'] < getLoginLevel()) {
                $memo_script = "alert('귀하는 본 글에 대한 댓글 쓰기 권한이 없습니다.');return;";
            }
        }
        if ($this->arrData['board_info']['is_captcha'] == 'y') {    // 도용방지 코드 체크
            $add_script = "if(form_memo.auth_key.value == '') { alert('보안코드가 입력되지 않았습니다.'); form_memo.auth_key.focus(); return false;}";
        }
        $this->addScript("
        function register() {
            if(form.password.value == '') { alert('비밀번호가 입력되지 않았습니다.'); form.password.focus(); return false;}
            form.target = 'iframe_process';
            form.submit();
        }
        function registerMemo() {".$memo_script.@$add_script."
            if(form_memo.name.value == '') { alert('글쓴이가 입력되지 않았습니다.'); form_memo.name.focus(); return false;}
            if(form_memo.password.value == '') { alert('비밀번호가 입력되지 않았습니다.'); form_memo.password.focus(); return false;}
            if(!checkPassword(form_memo.password.value)) { form_memo.password.focus(); return false;}
            if(form_memo.content.value == '') { alert('내용이 입력되지 않았습니다.'); form_memo.content.focus(); return false;}
            form_memo.target = 'iframe_process';
            form_memo.submit();
        }
        function onclickDelete() {
            layer_open('layer');return false;
        }
        function deleteMemo(code) {
            var password = prompt('해당 댓글을 삭제하시려면 비밀번호를 입력해주세요.');
            if (password != null) {
                form_memo.mode.value = 'deleteMemo';
                form_memo.delete_password.value = password;
                form_memo.code.value = code;
                form_memo.target = 'iframe_process';
                form_memo.submit();
            }
        }
        function checkMemoAuth() {".$memo_script."
        }");
	}

	function process() {
        $this->arrData['data'] = $this->objClass->view($this->reqData);
        if ($this->arrData['data']['is_secret'] == 'y' and getHash(@$this->reqData['password']) != $this->arrData['data']['password']) {
            putJSMessage('귀하는 본글에 대한 접근 권한이 없습니다.');
            exit;
        }
        $this->arrData['memo'] = $this->objClass->listMemo($this->reqData);

        // 이전글 / 다음글
        $this->arrData['extend'] = $this->objClass->getPrevNext($this->reqData);
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>