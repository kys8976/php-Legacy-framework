<?php
class ThisPage extends Page {
    function initialize() {
        $this->checkAdmin();
        $this->objClass = $this->loadClass("UtilPopup");

        $this->arrData['arrSearch'] = array(
			"title"	    => "제목",
		);
		if (!empty($this->reqData['keyword'])) {    // 검색키 있을때
            $this->arrData['field'] = $this->reqData['field'];
            $this->arrData['keyword'] = $this->reqData['keyword'];
        }
        else {
            $this->arrData['field'] = "";
            $this->arrData['keyword'] = "";
        }

        if (!empty($this->reqData['start_date'])) { // 시작일자
            $this->arrData['start_date'] = $this->reqData['start_date'];
        }
        if (!empty($this->reqData['end_date'])) {   // 종료일자
            $this->arrData['end_date'] = $this->reqData['end_date'];
        }

        $arrMyConfig = getCFG("MyConfig");
		$this->arrData['display'] = $arrMyConfig['DisplayStatus'];
        $this->arrData['popup'] = $arrMyConfig['PopupCookie'];
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
            if(form_register.start_date.value == '') { alert('시작일이 입력되지 않았습니다.'); form_register.start_date.focus(); return false;}
            if(form_register.end_date.value == '') { alert('종료일이 입력되지 않았습니다.'); form_register.end_date.focus(); return false;}
            if(form_register.title.value == '') { alert('제목이 입력되지 않았습니다.'); form_register.title.focus(); return false;}
            if(form_register.width.value == '') { alert('크기(가로)가 입력되지 않았습니다.'); form_register.width.focus(); return false;}
            if(form_register.height.value == '') { alert('크기(세로)가 입력되지 않았습니다.'); form_register.height.focus(); return false;}
            if (objEditor.getData().length < 1) {
                alert('내용이 입력되지 않았습니다.');
                objEditor.focus();
                return false;
            }
            form_register.target = 'iframe_process';
            form_register.submit();
        }
        function setData(code) {
			$.ajax({
				url:'"._API_URL."',
				type:'post',
				dataType:'json',
				data:{
					method:'UtilPopup.info',
                    code:code,
				},
				success:function(data, textStatus, jqXHR){
                    var json_data = data.data;
                    $('form[name=\"form_register\"] #mode').val('update');
					$('[name=code]').val(json_data.code);
                    $('[name=start_date]').val(json_data.start_date);
                    $('[name=end_date]').val(json_data.end_date);
					$('[name=left_position]').val(json_data.left_position);
					$('[name=top_position]').val(json_data.top_position);
                    $('[name=width]').val(json_data.width);
					$('[name=height]').val(json_data.height);
                    $('[name=title]').val(json_data.title);
					$('#display_'+json_data.display).prop('checked', true);
                    $('#popup_cookie_'+json_data.popup_cookie).prop('checked', true);
                    $('#popup_sub_title').text('수정');
                    $('#display_reply').css('display','');
                    objEditor.setData(json_data.content);
				},
				error:function(jqXHR, textStatus, errorThrown){
					console.log(textStatus);
					// $('#content').val(errorThrown);
				}
			});
        }
        function onclickInsert() {
            $('#modalContent').modal('show');
            form_register.reset();
            $('#board_sub_title').text('등록');
            $('form[name=\"form_register\"] #mode').val('insert');
            objEditor.setData('');
        }
        function onclickUpdate(code) {
            $('#modalContent').modal({backdrop:'static', show:true});
            setData(code);
        }
        $(function() {
            $(\"#datepicker1,#datepicker2,#datepicker3,#datepicker4\").datepicker({
                dateFormat: 'yy-mm-dd',
                prevText: '이전 달',
                nextText: '다음 달',
                monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
                monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
                dayNames: ['일','월','화','수','목','금','토'],
                dayNamesShort: ['일','월','화','수','목','금','토'],
                dayNamesMin: ['일','월','화','수','목','금','토'],
                showMonthAfterYear: true,
                yearSuffix: '년'
            });
        });
        $(document).ready(function() {
            $('#keyword').keydown(function(event) {
                if(event.keyCode == 13) {
                    form_search.submit();
                }
            });
        });");
	}
	function process() {
		$arrReturn = $this->objClass->lists($this->reqData);
        $this->arrData['data'] = $this->displayDataList($arrReturn['list_query']);
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>