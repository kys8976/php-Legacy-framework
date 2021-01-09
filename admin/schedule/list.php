<?php
class ThisPage extends Page {
    function initialize() {
        $this->checkAdmin();
        $this->objClass = $this->loadClass("UtilSchedule");
        $arrMyConfig = getCFG("MyConfig");
        $this->arrData['arrTimeType'] = $arrMyConfig["TimeType"];
        $this->arrData['arrSchedultTileColor'] = $arrMyConfig["SchedultTileColor"];
        $reqData['type'] = 'nomal';
        $this->arrData['list'] = $this->objClass->lists($reqData);
    }

    function checkParam() {
    }

    function makeJavaScript() {
        if (empty($this->reqData['selected_date'])) {
            $this->reqData['selected_date'] = date('Y-m');
        }
        $this->addScript("
        function register() {
            if(form_register.start_date.value == '') { alert('시작일이 입력되지 않았습니다.'); form_register.start_date.focus(); return false;}
            if(form_register.start_time.value == '') { alert('시작 시간이 입력되지 않았습니다.'); form_register.start_time.focus(); return false;}
            if(form_register.end_date.value == '') { alert('종료일이 입력되지 않았습니다.'); form_register.end_date.focus(); return false;}
            if(form_register.end_time.value == '') { alert('종료 시간이 입력되지 않았습니다.'); form_register.end_time.focus(); return false;}
            if(form_register.title.value == '') { alert('제목이 입력되지 않았습니다.'); form_register.title.focus(); return false;}
            form_register.target = 'iframe_process';
            form_register.submit();
        }
        function deleteSchedule() {
            if(confirm('해당 일정을 정말 삭제하시겠습니까?')) {
				form_register.target = 'iframe_process';
                form_register.mode.value = 'delete';
				form_register.submit();
			}
        }
        function _updateDate(code,start_date,end_date) {
            $.ajax({
                url:'"._API_URL."',
                type:'post',
                dataType:'json',
                data:{
                    method : 'UtilSchedule.updateDate',
                    code : code,
                    start_date : start_date,
                    end_date : end_date
                },
                success:function(data, textStatus, jqXHR){
                },
                error:function(jqXHR, textStatus, errorThrown){
                    console.log(textStatus);
                    // $('#content').val(errorThrown);
                }
            });
        }
        $(function() {
            /* calendar */
            // 초기화
            var current_date = '';

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                titleFormat: {
                    month: 'YYYY년 MMMM',
                    week: 'YYYY년 MMMM D',
                    day: 'YYYY년 MMMM D일 dddd요일'
                },
                allDayText: '시간',
                axisFormat: 'HH:mm',
                timeFormat: 'HH:mm',
                monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
                dayNames: ['일','월','화','수','목','금','토'],
                dayNamesShort: ['일','월','화','수','목','금','토'],
                buttonText: {
                    today: '오늘',
                    month: '월간',
                    week: '주간',
                    day: '일간'
                },
                fixedWeekCount: false,
                selectable: true,
                selectHelper: true,
                editable: true,     // 드래그 수정 가능 여부
                droppable: true,    // drop 가능하게
                // $('#calendar').fullCalendar('removeEvents',event._id);   // 일정 삭제
                eventClick: function(event) {                       // 일정 클릭
                    $('#modalContent').modal({backdrop:'static', show:true});
                    form_register.mode.value = 'update';
                    form_register.code.value = event.code;
                    $('[name=start_date]').val(moment(event.start).format('YYYY-MM-DD'));
                    $('[name=start_time]').val(moment(event.start).format('HH:mm'));
                    $('[name=end_date]').val(moment(event.end).format('YYYY-MM-DD'));
                    $('[name=end_time]').val(moment(event.end).format('HH:mm'));
                    $('[name=title]').val(event.title);
                    $('[name=content]').val(event.content);
                    $('[name=background_color]').val(event.backgroundColor);
                    $('.btn-colorselector').css('background-color',event.backgroundColor);
                    $('#display_reply').css('display','');
                    $('#colorselector').colorselector('setColor', event.backgroundColor);
                },
                dayClick: function(date, allDay, jsEvent, view) {   // 날짜 클릭
                    if (current_date == '') $(this).css('background-color', '#e5e3e3');
                    else {
                        $(current_date).css('background-color','white');
                        $(this).css('background-color','#e5e3e3');
                    }
                    current_date = this;
                    form_register.mode.value = 'insert';
                    $('#modalContent').modal({backdrop:'static', show:true});   // 모달 오픈
                    // input 페이지 init
                    $('[name=title]').val('');
                    $('[name=content]').val('');
                    $('[name=start_date]').val(date.format());
                    $('[name=start_time]').val('09:00');
                    $('[name=end_date]').val(date.format());
                    $('[name=end_time]').val('09:30');
                    $('[name=background_color]').val('#A0522D');
                    $('.btn-colorselector').css('background-color','#A0522D');
                    $('#display_reply').css('display','none');
                },
                eventResize: function(event,dayDelta,minuteDelta,revertFunc) {
                    _updateDate(event.code,event.start.format().replace('T',' '),event.end.format().replace('T',' '));
                },
                eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
                    _updateDate(event.code,event.start.format().replace('T',' '),event.end.format().replace('T',' '));
                },
                // 날짜별 일정 등록
                events: ".$this->arrData['list']."
            });
            $('#calendar').fullCalendar('gotoDate', '".$this->reqData['selected_date']."');

            /* datepicker */
            $( \"#datepicker1,#datepicker2\" ).datepicker({
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
            $('#datepicker1,#datepicker2').datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $('#colorselector').colorselector();
        });");
    }

    function process() {
    }

    function setDisplay() {
        return $this->arrData;
    }
}
?>