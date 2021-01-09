<?php
class ThisPage extends Page {
    function initialize() {
        $this->objClass = $this->loadClass("Board");
        $this->arrData['board_info'] = $this->objClass->info($this->reqData);
        if ($this->arrData['board_info']['category'] != '') {
            $this->arrData['board_info']['arrCategory'] = preg_split('/,/',$this->arrData['board_info']['category']);
        }

        // 권한 체크
        if (empty($this->reqData['action'])) {  // 등록
            $this->reqData['action'] = 'write';
            $this->arrData['arrBoard']['name'] = getLoginName();
            $this->arrData['mode'] = "insert";
        }
        else {                                  // 수정/답변
            $this->arrData['arrBoard'] = $this->objClass->view($this->reqData);
            $this->arrData['code'] = $this->reqData['code'];
            $this->arrData['mode'] = $this->reqData['action'];

            if ($this->reqData['action'] == "update") {     // 수정
                $file_list = '';
                if (!empty($this->arrData['arrBoard']['files'])) {
                    $this->objFile = $this->loadClass("UtilFile");
                    foreach($this->arrData['arrBoard']['files'] as $key => $val) {
                        if (in_array($val['file_ext'], $this->objFile->arrImgType)) {   // 이미지 파일
                            $image = '/user/'.$val['file_path'].'/'.$val['file_name'];
                        }
                        else if (in_array($val['file_ext'], $this->objFile->arrCommonType)) {// 일반 파일
                            $image = '/img/icon/file_'.$val['file_ext'].'.png';
                        }
                        else {  // 기타 파일
                            $image = '/img/icon/file_etc.png';
                        }

                        $file_list .= '<span id="'.$val['code'].'" style="float:left; position:relative; text-align:center; padding-right:15px;"><img src="'.$image.'" style="width:80px; cursor:pointer;" onclick="location.replace(\'?tpf=common/save_as&file_path='.$val['file_path'].'&file_name='.$val['file_name'].'&orig_name='.$val['orig_name'].'\');"><img src="/img/delete.png" onclick="deleteFile('.$val['code'].')" style="width:30px;position: absolute;left:43px; top:3px; z-index:10; cursor:pointer;"></span>';
                        if (($key+1) % 6 == 0) $file_list .= "<br><br><br><br><br><br><br><br><br>";
                    }
                }
                $this->arrData['file_list'] = $file_list;
            }
            else if ($this->reqData['action'] == "reply") { // 답변
                $this->arrData['arrBoard']['name'] = getLoginName();
                $this->arrData['arrBoard']['title'] = 'RE: '.$this->arrData['arrBoard']['title'];
                $this->arrData['arrBoard']['content'] = '<br><br>-----------------------------[원글]-----------------------------<br>'.$this->arrData['arrBoard']['content'];
            }
        }
        $this->objClass->checkAuth($this->reqData['action']);
	}

	function checkParam() {
	}

	function makeJavaScript() {
        if ($this->arrData['board_info']['is_captcha'] == 'y') {    // 도용방지 코드 체크
            $add_script = "if(form_register.auth_key.value == '') { alert('보안코드가 입력되지 않았습니다.'); form_register.auth_key.focus(); return false;}";
        }
        $this->addScript("
        if (window.CKEDITOR) {  // CKEDITOR loading 여부 체크 (Web 버젼에서만 사용)
            var objEditor = CKEDITOR.replace('content-editor', {
                height: 300,
                extraPlugins : 'tableresize',
                filebrowserUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                filebrowserImageUploadUrl: '/daemon/ckeditor_upload.php?command=QuickUpload&type=Images'
            });
            CKEDITOR.on('dialogDefinition', function (ev) {
                var dialogName = ev.data.name;
                var dialog = ev.data.definition.dialog;
                var dialogDefinition = ev.data.definition;

                if (dialogName == 'image') {
                    dialog.on('show', function (obj) {
                        this.selectPage('Upload'); //업로드텝으로 시작
                    });
                    dialogDefinition.removeContents('advanced'); // 자세히탭 제거
                    dialogDefinition.removeContents('Link'); // 링크탭 제거
                }
            });
            CKEDITOR.config.allowedContent = true;
        }
        function addFile() {
            $('#list_file').append($('#list_file_tag').html());
        }
        function deleteFile(file_code) {
            $('#'+file_code).css('display','none');
            $('#delete_file').val($('#delete_file').val()+file_code+',');
        }
        function reloadCaptcha() {
            d = new Date();
            $('#displayCaptcha').attr('src', '?tpf=common/captcha&dummy='+d.getTime());
        }
        function register() {
            if(form_register.name.value == '') { alert('작성자가 입력되지 않았습니다.'); form_register.name.focus(); return false;}
            if(form_register.title.value == '') { alert('제목이 입력되지 않았습니다.'); form_register.title.focus(); return false;}
            if (window.CKEDITOR) {
                if (objEditor.getData().length < 1) {
                    alert('내용이 입력되지 않았습니다.');
                    objEditor.focus();
                    return false;
                }
            }
            else {
                if(form_register.content.value == '') { alert('내용이 입력되지 않았습니다.'); form_register.content.focus(); return false;}
            }
            if(form_register.password.value == '') { alert('비밀번호가 입력되지 않았습니다.'); form_register.password.focus(); return false;}".@$add_script."
            if(!checkPassword(form_register.password.value)) { form_register.password.focus(); return false;}
            form_register.target = 'iframe_process';
            form_register.submit();
        }
        function reply() {
            $('form[name=\"form_register\"] #mode').val('reply');
            $('#board_sub_title').text('답변');
            $('#title').val('RE: '+form_register.title.value);
            $('#display_reply').css('display','none');
            $('#file_list').html('');
        }");
	}

	function process() {
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>