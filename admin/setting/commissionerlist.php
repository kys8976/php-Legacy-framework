<?php
class ThisPage extends Page {
	function initialize() {
        $this->checkAdmin();
        $this->objClass = $this->loadClass("Commissioner");

        $this->arrData['arrSearch'] = array(
			"name"	=> "이름",
			"position"	=> "직책"
		);
        $this->arrData['order_code'] = !empty($this->reqData['order_code']) ? $this->reqData['order_code'] : "";
		if (!empty($this->reqData['keyword'])) {    // 검색키 있을때
            $this->arrData['field'] = $this->reqData['field'];
            $this->arrData['keyword'] = $this->reqData['keyword'];
        }
	}

	function checkParam() {
	}

	function makeJavaScript() {
        $this->addScript("
        function register() {
            if(form_register.name.value == '') { alert('이름이 입력되지 않았습니다.'); form_register.name.focus(); return false;}
            if(form_register.position.value == '') { alert('직책이 입력되지 않았습니다.'); form_register.position.focus(); return false;}
            if(form_register.career.value == '') { alert('Creer가 입력되지 않았습니다.'); form_register.career.focus(); return false;}
            if(form_register.profile.value == '') { alert('Profile이 입력되지 않았습니다.'); form_register.profile.focus(); return false;}
            if (form_register.mode.value == 'insert') {
                if(form_register.file1.value == '') { alert('이미지가 입력되지 않았습니다.'); form_register.file1.focus(); return false;}
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
					method : 'Commissioner.info',
                    code : code
				},
				success:function(data, textStatus, jqXHR){
                    var json_data = data.data;

                    $('form[name=\"form_register\"] #mode').val('update');
                    $('#code').val(code);
                    $('#name').val(json_data.name);
                    $('#position').val(json_data.position);
                    $('#career').val(json_data.career);
                    $('#profile').val(json_data.profile);
                    if(json_data.image_url != '') $('#display_file').css('display','');
                    else $('#display_file').css('display','none');
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
        $this->arrData['data'] = $this->objClass->lists($this->reqData);
        //순서변경
        $this->arrData['onclick_change_up'] = "changeOrder('up','commissioner','?tpf=admin/setting/commissionerlist');";
        $this->arrData['onclick_change_down'] = "changeOrder('down','commissioner','?tpf=admin/setting/commissionerlist');";
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>