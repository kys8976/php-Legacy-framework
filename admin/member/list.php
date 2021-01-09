<?php
class ThisPage extends Page {
	function initialize() {
        $this->checkAdmin();
        $this->objClass = $this->loadClass("Member");
        $arrMyConfig = getCFG("MyConfig");
        $this->arrData['arrMemberStatus'] = $arrMyConfig['MemberStatus'];
        $this->arrData['arrMobileType'] = $arrMyConfig['MobileType'];
		$this->arrData['arrSearch'] = array(
			"name"	    => "이름",
			"mobile"	=> "휴대폰"
		);
		if (!empty($this->reqData['keyword'])) {    // 검색키 있을때
            $this->arrData['field'] = $this->reqData['field'];
            $this->arrData['keyword'] = $this->reqData['keyword'];
        }
        else {
            $this->arrData['field'] = "";
            $this->arrData['keyword'] = "";
        }
	}

	function checkParam() {
	}

	function makeJavaScript() {
        $this->addScriptFile("http://whoisict.com/html/js/jquery-ui.min.js");
        $this->addScriptFile("http://whoisict.com/html/js/s9soft_highlight.js");
		$this->addScript("
        function onclickInsert(code) {
            $('#modalRegister').modal({backdrop:'static', show:true});
            form.mode.value = 'insert';
            $('[name=id]').prop('readonly', false);
            $('#btn_check_id').show();
            $('#display_current_password').hide();
            $('#current_password').show();
            $('#display_status').hide();
            $('#display_last_login_date').hide();
            $('#display_update_date').hide();
            $('#display_reg_date').hide();
            $('#myModalLabel').text('회원 등록');
            form.reset();
        }
        function onclickUpdate(code) {
            $('#modalRegister').modal({backdrop:'static', show:true});
            form.mode.value = 'update';
            $('[name=id]').prop('readonly', true);
            $('#btn_check_id').hide();
            $('#display_current_password').show();
            $('#current_password').hide();
            $('#display_status').show();
            $('#display_last_login_date').show();
            $('#display_update_date').show();
            $('#display_reg_date').show();
            $('#myModalLabel').text('회원 수정');
            setData(code);
        }
        function onclickSMS(code) {
            $('#modalSMS').modal({backdrop:'static', show:true});
        }
        function onclickCheckId() {
            if(form.id.value == '') { alert('아이디가 입력되지 않았습니다.'); form.id.focus(); return false;}
            if(!checkId(form.id.value)) { form.id.focus(); return false;}
            formID.id.value = form.id.value;
            formID.target = 'iframe_process';
			formID.submit();
		}
        function register() {
            var mode = form.mode.value; // mode
            if(mode == 'insert') {   // 등록
                if(form.id.value == '') { alert('아이디가 입력되지 않았습니다.'); form.id.focus(); return false;}
                if(!checkId(form.id.value)) { form.id.focus(); return false;}
                if(form.password.value == '') { alert('비밀번호가 입력되지 않았습니다.'); form.password.focus(); return false;}
                if(!checkPassword(form.password.value)) { form.password.focus(); return false;}
                if(form.password_confirm.value == '') { alert('비밀번호 확인이 입력되지 않았습니다.'); form.password_confirm.focus(); return false;}
                if(form.password.value != form.password_confirm.value) { alert('비밀번호와 비밀번호 확인이 일치하지 않습니다.'); form.password.focus(); return false;}
            }
            else if(mode == 'update') { // 수정
                if(form.password.value != '') { // 현재 비밀번호 입력시
                    if(!checkPassword(form.password.value)) { form.password.focus(); return false;}
                    if(form.password_confirm.value == '') { alert('비밀번호 확인이 입력되지 않았습니다.'); form.password_confirm.focus(); return false;}
                    if(form.password.value != form.password_confirm.value) { alert('비밀번호와 비밀번호 확인이 일치하지 않습니다.'); form.password.focus(); return false;}
                }
            }
            if(form.name.value == '') { alert('이름이 입력되지 않았습니다.'); form.name.focus(); return false;}
            if(form.mobile1.value == '' || form.mobile2.value == '' || form.mobile3.value == '') { alert('휴대폰 번호가 입력되지 않았습니다.'); form.mobile1.focus(); return false;}
            if(!checkMobile(form.mobile1.value+form.mobile2.value+form.mobile3.value)) { form.mobile.focus(); return false;}
            if(form.email.value != '') {
                if(!checkEmail(form.email.value)) { form.email.focus(); return false;}
            }
            if(form.zipcode.value == '') { alert('주소가 입력되지 않았습니다.'); form.zipcode.focus(); return false;}
            if(form.addr_etc.value == '') { alert('상세주소가 입력되지 않았습니다.'); form.addr_etc.focus(); return false;}

            form.target = 'iframe_process';
            form.submit();
        }
        function registerSMS() {
            if(formSMS.msg.value == '') { alert('메세지가 입력되지 않았습니다.'); formSMS.msg.focus(); return false;}
            formSMS.target = 'iframe_process';
            formSMS.submit();
        }
        function setData(code) {
            // 회원정보
            $.ajax({
                url:'"._API_URL."',
                type:'post',
                dataType:'json',
                data:{
                    method : 'Member.info',
                    member_code : code
                },
                success:function(data, textStatus, jqXHR){
                    var json_data = data.data;
                    $('[name=member_code]').val(code);
                    $('[name=id]').val(json_data.id);
                    $('[name=name]').val(json_data.name);
                    var arrMobile = json_data.mobile.split('-');
                    $('[name=mobile1]').val(arrMobile[0]);
                    $('[name=mobile2]').val(arrMobile[1]);
                    $('[name=mobile3]').val(arrMobile[2]);
                    $('[name=email]').val(json_data.email);
                    $('[name=zipcode]').val(json_data.zipcode);
                    $('[name=addr]').val(json_data.addr);
                    $('[name=addr_etc]').val(json_data.addr_etc);
                    $('[name=memo]').val(json_data.memo);
                    $('#last_login_date').text(json_data.last_login_date);
                    $('#update_date').text(json_data.update_date);
                    $('#reg_date').text(json_data.reg_date);
                    $('[name=level]').val(json_data.level);
                    $('[name=status]').val(json_data.status);
                },
                error:function(jqXHR, textStatus, errorThrown){
                    console.log(textStatus);
                    // $('#content').val(errorThrown);
                }
            });
        }
        function deleteMember() {
            if(confirm('해당 회원을 정말 삭제하시겠습니까?')) {
				document.form_list.target = 'iframe_process';
                form.mode.value = 'delete';
				form.submit();
			}
        }
        function checkLimit() {
            var tempText = $(\"textarea[name='msg']\");
            var tempChar = '';  // textarea의 문자를 한글자씩 담는다
            var tempChar2 = ''; // 절삭된 문자들을 담기 위한 변수
            var countChar = 0;  // 한글자씩 담긴 문자를 카운트 한다
            var maxSize = 90;   // 최대값(byte)

            // 글자수 바이트 체크를 위한 반복
            for(var i=0; i<tempText.val().length; i++) {
                tempChar = tempText.val().charAt(i);

                // 한글일 경우 2 추가, 영문일 경우 1 추가
                if(escape(tempChar).length > 4) {
                    countChar += 2;
                } else {
                    countChar++;
                }
            }
            if((countChar) > maxSize) {
                alert('최대 글자수를 초과하였습니다.');
                tempChar2 = tempText.val().substr(0, maxSize-1);
                tempText.val(tempChar2);
            }
            $('#msg_count').text(countChar);
        }
        $('#msg').keyup(function() {
            checkLimit();
        });
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
        $arrReturn = $this->objClass->lists($this->reqData);
        $arrData = $this->objDBH->getRows("select * from member_level where title <> '' order by level");
        $this->arrData['arrLevel'] = $arrData['list'];
        $this->arrData['data'] = $this->displayDataList($arrReturn['list_query']);
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>