<?php
class ThisPage extends Page {
	function initialize() {
        $arrMyConfig = getCFG("MyConfig");
        $this->arrData['arrMobileType'] = $arrMyConfig['MobileType'];
        $this->arrData['arrTelType'] = $arrMyConfig['TelType'];
	}

	function checkParam() {
	}

	function makeJavaScript() {
        $this->addScriptFile("http://whoisict.com/html/js/jquery-ui.min.js");
        $this->addScriptFile("http://whoisict.com/html/js/s9soft_highlight.js");
        $this->addScript("
        function register(mode) {
            form.mode.value = mode;
            if(form.mode.value == 'insert') {
                if(form.id.value == '') { alert('아이디가 입력되지 않았습니다.'); form.id.focus(); return false;}
                if(!checkId(form.id.value)) { form.id.focus(); return false;}
                if(form.password.value == '') { alert('비밀번호가 입력되지 않았습니다.'); form.password.focus(); return false;}
                if(!checkPassword(form.password.value)) { form.password.focus(); return false;}
                if(form.password_confirm.value == '') { alert('비밀번호 확인이 입력되지 않았습니다.'); form.password_confirm.focus(); return false;}
                if(form.password.value != form.password_confirm.value) { alert('비밀번호와 비밀번호 확인이 일치하지 않습니다.'); form.password.focus(); return false;}
            }
            if(form.name.value == '') { alert('이름이 입력되지 않았습니다.'); form.name.focus(); return false;}
            if(form.email.value != '') {
                if(!checkEmail(form.email.value)) { form.email.focus(); return false;}
            }
            if(form.mobile1.value == '' || form.mobile2.value == '' || form.mobile3.value == '') { alert('휴대폰 번호가 입력되지 않았습니다.'); form.mobile1.focus(); return false;}
            if(!checkMobile(form.mobile1.value+form.mobile2.value+form.mobile3.value)) { form.mobile.focus(); return false;}
            if(form.zipcode.value == '') { alert('주소가 입력되지 않았습니다.'); form.zipcode.focus(); return false;}
            if(form.addr_etc.value == '') { alert('상세주소가 입력되지 않았습니다.'); form.addr_etc.focus(); return false;}
            form.target = 'iframe_process';
            form.submit();
		}
        function onclickCheckId() {
            if(form.id.value == '') { alert('아이디가 입력되지 않았습니다.'); form.id.focus(); return false;}
            if(!checkId(form.id.value)) { form.id.focus(); return false;}
            formID.id.value = form.id.value;
            formID.target = 'iframe_process';
			formID.submit();
		}
        function checkEmailForm() {
            if(form.email.value == '') { alert('이메일이 입력되지 않았습니다.'); form.email.focus(); return false;}
            else {
                if(!checkEmail(form.email.value)) { form.email.focus(); return false;}
            }
            form.mode.value = 'checkEmail';
            form.target = 'iframe_process';
			form.submit();
		}
        function callAddress() {
            $('#address').css('display','');
            $('#address').focus();
        }
        // s9soft 자동 주소완성
        $(document).ready(function() {
            $('#address').click(function(e) {
                if($('#address').val() == '') {
                    return false;
                }
                $(this).autocomplete('search');
            })
            .autocomplete({
                source: function(request,response) {
                    var query = request.term.replace(/(^\s*)|(\s*$)/gi, '');
                    var searced = query;
                    var totalCount = 0;
                    if(query == '') return;
                    $.ajax({
                        url: 'http://addr.whoisict.com:8080/mjuso/s9juso.jsp',
                        dataType: 'jsonp',
                        data: {
                            q: encodeURI(query),
                            gubun: 'all', // all:전체, dro:도로명, jbn:지번
                            sido: 'all',
                            precision: -1,
                            count: 30
                        },
                        success: function(data) {
                            searched = data.query;
                            totalCount = data.total;
                            response(data.items);
                        }
                    });
                },
                minLength: 1,
                delay: 200,
                focus: function(event,ui) {
                    return false;
                },
                select: function(event,ui) {
                    console.log(ui);
                    $('[name=zipcode\]').val(ui.item.guyeok);
                    $('[name=addr]').val(ui.item.doro);
                    $('[name=addr_etc]').focus();
                    return false;
                }
            })
            .autocomplete('instance')._renderItem = function(ul,item) {
                return $('<li>').append('<a>' + multiHighlight(item.label, searched) + '</a>').appendTo(ul);
            };
        });");
	}

	function process() {
        if (isLogin()) {    // 로그인시에는 회원수정폼으로 전환
            $objClass = $this->loadClass("Member");
            $this->arrData['member_code'] = getLoginCode();
            $this->arrData['arrMember'] = $objClass->info($this->arrData);
            $this->arrData['arrMember']['birthday'] = preg_split('/-/',$this->arrData['arrMember']['birthday']);
            $this->arrData['arrMember']['mobile'] = preg_split('/-/',$this->arrData['arrMember']['mobile']);
            $this->arrData['arrMember']['tel'] = preg_split('/-/',$this->arrData['arrMember']['tel']);
            $this->arrData['mode'] = "update";
            $this->arrData['title'] = "수정";
        }
        else {
            $this->arrData['mode'] = "insert";
            $this->arrData['title'] = "가입";
        }
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>