<?php
class ThisPage extends Page {
	function initialize() {
        $this->checkAdmin();
        $this->objClass = $this->loadClass("UtilBanner");
	}

	function checkParam() {
	}

	function makeJavaScript() {
        $this->addScript("
        function register() {
            if(form_register.title.value == '') { alert('이름이 입력되지 않았습니다.'); form_register.title.focus(); return false;}
            if(form_register.url.value == '') { alert('URL이 입력되지 않았습니다.'); form_register.url.focus(); return false;}
            if (form_register.mode.value == 'insert') {
                if(form_register.file1.value == '') { alert('파일이 입력되지 않았습니다.'); form_register.file1.focus(); return false;}
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
					method : 'UtilBanner.info',
                    code : code
				},
				success:function(data, textStatus, jqXHR){
                    var json_data = data.data;
                    console.log(json_data);
                    $('form[name=\"form_register\"] #mode').val('update');
                    $('[name=code]').val(code);
                    $('[name=title]').val(json_data.title);
                    $('[name=url]').val(json_data.url);
                    if(json_data.image_url != '') $('#display_file').css('display','');
                    else $('#display_file').css('display','none');
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
            $('form[name=\"form_register\"] #mode').val('insert');
            $('#display_file').css('display','none')
        }
        function onclickUpdate(code) {
            $('#modalContent').modal({backdrop:'static', show:true});
            setData(code);
        }
        function alertNo() {
             alert('슬라이드는 5개까지 등록 하실 수 있습니다');
        }

        ");
	}

	function process() {
        $this->arrData['data'] = $this->objClass->lists($this->reqData);
        //5개 갯수 제한을 위한 수 파악
        $this->arrData['count'] =$this->objDBH->getNumRows("select * from banner order by code");
        }

	function setDisplay() {
        return $this->arrData;
	}
}
?>