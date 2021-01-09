<?php
class ThisPage extends Page {
	function initialize() {
        $this->checkAdmin();
        $this->objClass = $this->loadClass("UtilHistory");
		$arrMyConfig = getCFG("MyConfig");
        $this->arrData['start_year'] = $arrMyConfig["StartYear"][0];
	}

	function checkParam() {
	}

	function makeJavaScript() {
        $this->addScript("
        function register() {
            if(form_register.year.value == '') { alert('년도가 선택되지 않았습니다.'); form_register.year.focus(); return false;}
            if(form_register.title.value == '') { alert('내용이 선택되지 않았습니다.'); form_register.title.focus(); return false;}
            form_register.target = 'iframe_process';
            form_register.submit();
        }
        function setData(code) {
            $.ajax({
				url:'"._API_URL."',
				type:'post',
				dataType:'json',
				data:{
					method : 'UtilHistory.info',
                    code : code
				},
				success:function(data, textStatus, jqXHR){
                    var json_data = data.data;
                    $('form[name=\"form_register\"] #mode').val('update');
                    $('[name=code]').val(code);
                    $('[name=year]').val(json_data.year);
                    $('[name=month]').val(json_data.month);
                    $('[name=title]').val(json_data.title);
				},
				error:function(jqXHR, textStatus, errorThrown){
					console.log(textStatus);
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
        }");
	}

	function process() {
        $this->arrData['data'] = $this->objClass->lists($this->reqData);

    }


	function setDisplay() {
        return $this->arrData;
	}
}
?>