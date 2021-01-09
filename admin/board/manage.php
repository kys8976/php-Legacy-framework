<?php
class ThisPage extends Page {
	function initialize() {
        $this->checkAdmin();
        $this->objClass = $this->loadClass("Board");
        $arrMyConfig = getCFG("MyConfig");
        $this->arrData['arrBoardType'] = $arrMyConfig["BoardType"];
	}

	function checkParam() {
	}

	function makeJavaScript() {
        $this->addScript("
        function register() {
            if(form.title.value == '') { alert('제목이 입력되지 않았습니다.'); form.title.focus(); return false;}
            form.target = 'iframe_process';
            form.submit();
        }
        function setData(code) {
        	$.ajax({
				url:'"._API_URL."',
				type:'post',
				dataType:'json',
				data:{
					method:'Board.info',
                    board_code:code
				},
				success:function(data, textStatus, jqXHR){
                    var json_data = data.data;
                    console.log('-----------');
                    console.log(json_data);
                    $('#board_code').val(json_data.code);
                    $('#title').val(json_data.title);
                    $('#header').val(json_data.header);
                    $('[name=type]').val(json_data.type);
                    $('#category').val(json_data.category);
                    $('#show_list_'+json_data.show_list).prop('checked', true);
                    $('#show_memo_'+json_data.show_memo).prop('checked', true);
                    $('[name=auth_list]').val(json_data.auth_list);
                    $('[name=auth_view]').val(json_data.auth_view);
                    $('[name=auth_write]').val(json_data.auth_write);
                    $('[name=auth_reply]').val(json_data.auth_reply);
                    $('[name=auth_update]').val(json_data.auth_update);
                    $('[name=auth_memo]').val(json_data.auth_memo);
                    $('[name=auth_delete]').val(json_data.auth_delete);
                    $('[name=auth_notice]').val(json_data.auth_notice);
                    if(json_data.is_secret == 'y') $('[name=is_secret]').prop('checked', true);
                    else $('[name=is_secret]').prop('checked', false);
                    if(json_data.is_mass == 'y') $('[name=is_mass]').prop('checked', true);
                    else $('[name=is_mass]').prop('checked', false);
                    if(json_data.is_order == 'y') $('[name=is_order]').prop('checked', true);
                    else $('[name=is_order]').prop('checked', false);
                    if(json_data.is_captcha == 'y') $('[name=is_captcha]').prop('checked', true);
                    else $('[name=is_captcha]').prop('checked', false);
				},
				error:function(jqXHR, textStatus, errorThrown){
					console.log(textStatus);
					// $('#content').val(errorThrown);
				}
			});
        }
        function onclickInsert(code) {
            $('#modalRegister').modal({backdrop:'static', show:true});
            form.reset();
            form.mode.value = 'insert';
            $('#show_list_n').prop('checked', true);
            $('#show_memo_n').prop('checked', true);
            $('[name=auth_write]').val(2);
            $('[name=auth_reply]').val(1);
            $('[name=auth_update]').val(2);
            $('[name=auth_memo]').val(2);
            $('[name=auth_delete]').val(2);
            $('[name=auth_notice]').val(1);
            $('[name=is_captcha]').prop('checked', true);
            $('#myModalLabel').text('게시판 등록');
        }
        function onclickView(code) {
            window.open('index.php?tpf=board/list&board_code='+code, '_blank');
        }
        function onclickCopy(code) {
            var board_url = 'index.php?tpf=board/list&board_code='+code;
            var IE=(document.all)?true:false;
            if (IE) {
                if(confirm('이 게시판 주소를 복사하시겠습니까?'))
                window.clipboardData.setData('Text', board_url);
            } else {
                temp = prompt('이 게시판의 URL 입니다. Ctrl+C를 눌러 복사하세요', board_url);
            }
        }
        function onclickUpdate(code) {
            $('#modalRegister').modal({backdrop:'static', show:true});
            form.mode.value = 'update';
            $('#myModalLabel').text('게시판 수정');
            setData(code);
        }");
	}

	function process() {
        $this->arrData['data'] = $this->objClass->listBoard($this->reqData);
        $this->arrData['arrCount'] = $this->objClass->getCount();

        $arrMemberLevel = $this->objDBH->getRows("select * from member_level where title <> '' order by level desc");
        if (!empty($arrMemberLevel['list'])) {
            $arrTmp = array();
            foreach($arrMemberLevel['list'] as $key => $val) {
                $arrTmp[$val['level']] = $val['title'];
            }
            $this->arrData['arrMemberLevel'] = $arrTmp;
        }
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>