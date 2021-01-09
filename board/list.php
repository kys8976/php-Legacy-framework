<?php
class ThisPage extends Page {
    function initialize() {
        $this->objClass = $this->loadClass("Board");
        $this->arrData['board_info'] = $this->objClass->info($this->reqData);

        // 권한 체크
        $this->objClass->checkAuth();

        // 레이아웃 셋팅
        $this->setFile($this->arrData['board_info']['type']);

        // 네비메뉴 셋팅
        if ($this->arrData['board_info']['category']) {
            $this->arrData['current_url'] = $this->getCurrentUrl('category');
            $this->arrData['arrCategory'] = preg_split('/,/',$this->arrData['board_info']['category']);
        }
        else {
            $this->arrData['display_navi'] = ' hidden';
        }

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
        $this->addScript('
        $(function(){   // web용
            // 답변 글 보이기
            $(".qna-board-list").find("dt").click(function(e){
            e.preventDefault();
                $(".qna-board-list").find("dt").not(this).removeClass("on").next().slideUp(500);
                $(this).addClass("on").next().slideToggle(500,function(){
                    if($(this).css("display") == "none" ){
                        $(this).prev().removeClass("on");
                    }
                });
            });
        });
        $(function(){   // mobile용
            $(".basic-board-list .no-data td").attr("colspan", $(".basic-board-list thead th").length);
            // 답변 글 보이기
            $(".faq-list").find(".f-wrap").click(function(e){
                e.preventDefault();
                $(".faq-list").find(".f-wrap").not(this).removeClass("on").next().slideUp(300);
                $(this).addClass("on").next().slideToggle(300,function(){
                    if($(this).css("display") == "none" ){
                        $(this).prev().removeClass("on");
                    }
                });
            });
            $(".webzine-board-list .no-data td").attr("colspan", $(".webzine-board-list thead th").length);
        });
        function register() {
            if(form.password.value == "") { alert("비밀번호가 입력되지 않았습니다."); form.password.focus(); return false;}
            form.target = "iframe_process";
            form.submit();
        }
        function onclickView(board_code, board_data_code) {
            $(\'[name="board_code"]\').val(board_code);
            $(\'[name="board_data_code"]\').val(board_data_code);
            layer_open(\'layer\');return false;
        }');
    }

    function process() {
        $this->arrData['notice'] = $this->objClass->listNotice($this->reqData);
        $arrReturn = $this->objClass->lists($this->reqData);
        $this->arrData['data'] = $this->displayDataList($arrReturn['list_query'],'y');
        if (!empty($this->arrData['data']['list'])) {
            foreach($this->arrData['data']['list'] as $key => $val) {
                $reply_icon = '';
                if($val['length_depth'] > 1)	{
                    for($j=2; $j<$val['length_depth']; $j++) { $reply_icon .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';}
                    $reply_icon .= '<img src="img/icon_re.gif" alt="답글" class="board-list-re-icon" style="width:5px;" />&nbsp;';
                }
                $this->arrData['data']['list'][$key]['title'] = $reply_icon.$this->arrData['data']['list'][$key]['title'];
                $this->arrData['data']['list'][$key]['content'] = strip_tags($val['content']);
            }
        }
        $this->arrData['board_code'] = $this->reqData['board_code'];
		if(!empty($this->reqData['category'])) $this->arrData['category'] = $this->reqData['category'];
    }

    function setDisplay() {
        return $this->arrData;
    }
}
?>