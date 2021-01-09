<?php
class ThisPage extends Page {
    function initialize() {
		$this->checkAdmin();
        $this->objClass = $this->loadClass("BizProduct");

        $arrMyConfig = getCFG("MyConfig");
        $this->arrData['arrDisplayStatus'] = $arrMyConfig['DisplayStatus'];

         // 상품내용 default
        $this->arrData['content'] = '
        <table class="table01" >
		<colgroup>
			<col width="10%">
			<col width="10%">
			<col width="10%">
			<col width="10%">
			<col width="10%">
			<col width="10%">
			<col width="10%">
			<col width="10%">
			<col width="10%">
			<col width="10%">
		</colgroup>
		<thead>
        <tr>
            <th>모델명</th>
            <th>소비전력</th>
            <th>색온도</th>
            <th>총광속</th>
            <th>연색지수</th>
            <th>압력전압</th>
            <th>압력전류</th>
            <th>유리관</th>
            <th>지름</th>
            <th>총길이</th>
        </tr>
		</thead>
		<tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
		</tbody>
	    </table>';
	}

	function checkParam() {
	}

	function makeJavaScript() {
        $this->addScript("
        var objEditor = CKEDITOR.replace('content', {
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
        $.fn.modal.Constructor.prototype.enforceFocus = function () {   // bootstrap & ckEdiotr 소스 방지 코드
            modal_this = this
            $(document).on('focusin.modal', function (e) {
                if (modal_this.\$element[0] !== e.target && !modal_this.\$element.has(e.target).length && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_select') && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
                }
            })
        }
        function register() {
            if(form_register.title.value == '') { alert('상품명이 입력되지 않았습니다.'); form_register.title.focus(); return false;}
            if(form_register.content.value == '') { alert('Specifications이 입력되지 않았습니다.'); form_register.content.focus(); return false;}
            if(form_register.mode.value == 'insertProduct') {   // 입력일때
                if(form_register.file1.value == '') { alert('메인 이미지가 입력되지 않았습니다.'); form_register.file1.focus(); return false;}
            }
            form_register.target = 'iframe_process';
            form_register.submit();
        }
        function checkHeight() {
            var height = $(window).height() - 200;
            document.getElementById('iframe_tree').height = height;
            document.getElementById('iframe_list').height = height;
        }
        checkHeight();");
	}

	function process() {
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>