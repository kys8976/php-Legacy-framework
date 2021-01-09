<?php
class ThisPage extends Page {
    function initialize() {
        $this->objClass = $this->loadClass("UtilSchedule");
        $arrMyConfig = getCFG("MyConfig");
        $reqData['type'] = 'nomal';
        $this->arrData['list'] = $this->objClass->lists($reqData);
    }

    function checkParam() {
    }

    function makeJavaScript() {
        if (empty($this->reqData['selected_date'])) {
            $this->reqData['selected_date'] = date('Y-m');
        }
        $this->addScriptFile("//whoisict.com/html/js/libs/admin-lte/bootstrap/js/bootstrap.min.js");
        $this->addScriptFile("https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.7.0/moment.min.js");
        $this->addScriptFile("//whoisict.com/html/js/libs/admin-lte/plugins/fullcalendar/fullcalendar.min.js");
        $this->addScript("
        $(function() {
            /* calendar */
            // 초기화
            var current_date = '';

            $('#calendar').fullCalendar({
                fixedWeekCount: false,
                editable: false,     // 드래그 수정 가능 여부
                droppable: false,    // drop 가능하게
                eventClick: function(event) {                       // 일정 클릭
                    // $('#modalContent').modal({backdrop:'static', show:true});
                    layer_open('viewSchedule');
                    // layer_open('modalContent');
                    $('#start_date').text(moment(event.start).format('YYYY-MM-DD')+' '+moment(event.start).format('HH:mm'));
                    $('#end_date').text(moment(event.end).format('YYYY-MM-DD')+' '+moment(event.end).format('HH:mm'));
                    $('#title').text(event.title);
                    $('#content').html(event.content);
                    // console.log(event.content);
                },
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
                // 날짜별 일정 등록
                events: ".$this->arrData['list']."
            });
            $('#calendar').fullCalendar('gotoDate', '".$this->reqData['selected_date']."');
        });");
    }

    function process() {
    }

    function setDisplay() {
        return $this->arrData;
    }
}
?>