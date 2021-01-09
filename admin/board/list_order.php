<?php
class ThisPage extends Page {
	function initialize() {
        $this->checkAdmin();
        $this->objClass = $this->loadClass("Board");

        $this->arrData['order_code'] = !empty($this->reqData['order_code']) ? $this->reqData['order_code'] : "";
        $this->arrData['info'] = $this->objClass->info($this->reqData);
        $this->arrData['arrSearch'] = array(
			"b.title"	=> "제목",
			"b.name"	=> "작성자"
		);
		if (!empty($this->reqData['keyword'])) {    // 검색키 있을때
            $this->arrData['field'] = $this->reqData['field'];
            $this->arrData['keyword'] = $this->reqData['keyword'];
        }
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
        $.fn.modal.Constructor.prototype.enforceFocus = function () {
            modal_this = this
            $(document).on('focusin.modal', function (e) {
                if (modal_this.\$element[0] !== e.target && !modal_this.\$element.has(e.target).length && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_select') && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
                    modal_this.\$element.focus()
                }
            })
        }
        function addPrice() {
            $('#list_file').append($('#list_file_tag').html());
        }
        function deleteFile(file_code) {
            $('#'+file_code).css('display','none');
            $('#delete_file').val($('#delete_file').val()+file_code+',');
        }
        function register() {
            if(form_register.name.value == '') { alert('작성자가 입력되지 않았습니다.'); form_register.name.focus(); return false;}
            if(form_register.title.value == '') { alert('제목이 입력되지 않았습니다.'); form_register.title.focus(); return false;}
            if (objEditor.getData().length < 1) {
                alert('내용이 입력되지 않았습니다.');
                objEditor.focus();
                return false;
            }
            form_register.target = 'iframe_process';
            form_register.submit();
        }
        function reply() {
            $('form[name=\"form_register\"] #mode').val('reply');
            $('#board_sub_title').text('답변');
            $('#title').val('RE: '+form_register.title.value);
            $('#display_reply').css('display','none');
            $('#file_list').html('');
        }
        function setData(code) {
            var arrImg = ['png','jpg','jpeg','gif'];
            var arrFile = ['avi','csv','xls','xlsx','doc','docx','html','mov','mp3','mp4','pdf','ppt','pptx','txt','xml','zip'];
            var image = '';
			$.ajax({
				url:'"._API_URL."',
				type:'post',
				dataType:'json',
				data:{
					method:'Board.view',
                    code:code,
                    board_code:".$this->reqData['board_code']."
				},
				success:function(data, textStatus, jqXHR){
                    var json_data = data.data;

                    $('form[name=\"form_register\"] #mode').val('update');
                    $('#board_data_code').val(json_data.code);
                    $('#name').val(json_data.name);
                    $('#title').val(json_data.title);
                    $('#board_sub_title').text('수정');
                    $('#display_reply').css('display','');
                    objEditor.setData(json_data.content);

                    var file_list = '';
                    if (json_data.files != null) {
                        $.each(json_data.files, function(index, value) {
                            if (jQuery.inArray(value.file_ext, arrImg) >= 0) {       // 이미지 파일
                                image = '/user/'+value.file_path+'/'+value.file_name;
                            }
                            else if (jQuery.inArray(value.file_ext, arrFile) >= 0) { // 일반 파일
                                image = '/img/icon/file_'+value.file_ext+'.png';
                            }
                            else {  // 기타 파일
                                image = '/img/icon/file_etc.png';
                            }

                            file_list += '<span id=\"'+value.code+'\" style=\"float:left; position:relative; text-align:center; padding-right:15px;\"><img src=\"'+image+'\" style=\"width:80px; cursor:pointer;\" onclick=\"location.replace(\'?tpf=common/save_as&file_path='+value.file_path+'&file_name='+value.file_name+'&orig_name='+encodeURI(value.orig_name)+'\');\"><img src=\"/img/delete.png\" onclick=\"deleteFile('+value.code+')\" style=\"width:30px;position: absolute;left:43px; top:3px; z-index:10; cursor:pointer;\"></span>';
                        });
                        $('#file_list').html(file_list);
                    }
                    else {
                        $('#file_list').html('');
                    }
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
            $('#display_reply').css('display','none');
            objEditor.setData('');
            $('#file_list').html('');
        }
        function onclick_update(code) {
            $('#modalContent').modal({backdrop:'static', show:true});
            setData(code);
        }
        $(document).ready(function() {
            $('#keyword').keydown(function(event) {
                if(event.keyCode == 13) {
                    form_search.submit();
                }
            });
            $('#memo').keydown(function(event) {
                if(event.keyCode == 13) {
                    // document.getElementById('memo').value = document.getElementById('memo').value + '\\n';
                }
            })
        });");
	}

	function process() {
        $arrReturn = $this->objClass->lists($this->reqData);
        $this->arrData['data'] = $this->displayDataList($arrReturn['list_query']);
        if (!empty($this->arrData['data']['list'])) {
            foreach($this->arrData['data']['list'] as $key => $val) {
                $reply_icon = '';
                if($val['length_depth'] > 1)	{
                    for($j=2; $j<$val['length_depth']; $j++) { $reply_icon .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';}
                    $reply_icon .= '<img src="/img/sub/icon_reply.gif">&nbsp;';
                }
                $this->arrData['data']['list'][$key]['title'] = $reply_icon.$this->arrData['data']['list'][$key]['title'];
            }
        }
        $this->arrData['board_code'] = $this->reqData['board_code'];
        $this->arrData['name'] = getLoginName();

        // 순서 변경
        $this->arrData['onclick_change_up'] = "changeOrder('up','board_data','?tpf=admin/board/list_order&board_code=".$this->reqData['board_code']."',".$this->reqData['board_code'].");";
        $this->arrData['onclick_change_down'] = "changeOrder('down','board_data','?tpf=admin/board/list_order&board_code=".$this->reqData['board_code']."',".$this->reqData['board_code'].");";
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>