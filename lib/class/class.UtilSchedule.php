<?php
/**
 */
class UtilSchedule {
	private $objDBH;
	private $table = 'schedule';

	function __construct($obj) {
		$this->objDBH = $obj;
	}

    /************************* 게시판 *************************/
    // 정보 가져오기
    function view($reqData) {
        checkParam($reqData['code'], "code");
        $arrReturn = $this->objDBH->getRow("select * from ".$this->table." where code='".$reqData['code']."'");
        return $arrReturn;
    }

    // 리스트 가져오기 : list_query 값으로 넘기면 parent 단에서 displayDataList() 실행
    function lists($reqData=array()) {
        $where = ' where 1';
        if(!empty($reqData['type'])) $where .= " and type='".$reqData['type']."'";
        if(!empty($reqData['keyword'])) $where .= " and ".$reqData['field']." like '%".$reqData['keyword']."%'";
        $arrData = $this->objDBH->getRows("select code,title,content,start_date as start,end_date as end,background_color as backgroundColor,background_color as borderColor from ".$this->table.$where);
        if (!empty($arrData['list'])) {
            foreach($arrData['list'] as $key => $val) {
                $arrData['list'][$key]['editable'] = true;
                // $arrData['list'][$key]['title'] = '';
            }
            return json_encode($arrData['list']);
        }
        else return '[]';
    }

    // 일자 유효성 검사
    function checkDateTime($reqData) {
        $start_date = strtotime($reqData['start_date'].' '.$reqData['start_time']);
        $end_date = strtotime($reqData['end_date'].' '.$reqData['end_time']);
        if($end_date - $start_date <= 0) {
            putJSMessage("시작날짜-시간보다 종료날짜-시간이 같거나 이전일수 없습니다.");
            exit;
        }
    }

    // 등록
	function insert($reqData) {
        $this->checkDateTime($reqData);
        $arrParam = array (
            'type'     => $reqData['type'],
            'title'     => $reqData['title'],
            'content'   => $reqData['content'],
            'background_color'   => $reqData['background_color'],
            'start_date'=> $reqData['start_date'].' '.$reqData['start_time'],
            'end_date'  => $reqData['end_date'].' '.$reqData['end_time'],
            'reg_date'  => 'now()'
        );
        $this->objDBH->insert($this->table, $arrParam);
        $code = $this->objDBH->getLastId();
		return $code;
	}

	// 수정
	function update($reqData) {
        $this->checkDateTime($reqData);
        $arrParam = array (
            'title'     => $reqData['title'],
            'content'   => $reqData['content'],
            'background_color'   => $reqData['background_color'],
            'start_date'=> $reqData['start_date'].' '.$reqData['start_time'],
            'end_date'  => $reqData['end_date'].' '.$reqData['end_time']
        );
        $arrWhere = array(
            'code' => $reqData['code']
        );
        $this->objDBH->update($this->table, $arrParam, $arrWhere);
	}

    // 수정(날짜-시간)
	function updateDate($reqData) {
        $this->checkDateTime($reqData);
        $arrParam = array (
            'start_date'=> $reqData['start_date'],
            'end_date'  => $reqData['end_date']
        );
        $arrWhere = array(
            'code' => $reqData['code']
        );
        $this->objDBH->update($this->table, $arrParam, $arrWhere);
	}

	// 삭제
	function delete($reqData) {
        $query = "delete from ".$this->table." where code='".$reqData['code']."'";
		$this->objDBH->query($query);
	}
}
?>