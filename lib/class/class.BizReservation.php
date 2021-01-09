<?php
/**
 * Schedule
 *
 */
 include_once _CLASS_DIR."/class.UtilFile.php";
class BizReservation extends UtilFile {
	private $objDBH;
	private $table = 'reservation';
    private $strThumbnail = array();
    private $strFileName = array();

	function __construct($obj) {
		$this->objDBH = $obj;
	}

    /************************* 예약 현황 *************************/
    /************************* 객실 관리 *************************/
    // 파일체크
	function _checkFile() {
        $this->setFiles();
        // 쎔네일 이미지
        if ($_FILES['file1']['name']) {	// 파일첨부시
            $arrResultFile = $this->checkFile("Img","file1");
            if($arrResultFile['status'] == "FAIL") {
                putJSMessage("[".$arrResultFile['message']."]","back");
                exit;
            }
            else {
                $this->strThumbnail = strtolower($_FILES['file1']['name']);
            }
        }

		// 설명 이미지
        if (!empty($_FILES['file']['name'])) {
            foreach($_FILES['file']['name'] as $key => $val) {
                if (!empty($val)) {
                    $arrResultFile = $this->checkFileBulk("Img",$key);
                    if($arrResultFile['status'] == "FAIL") {
                        putJSMessage("[".$arrResultFile['message']."]","back");
                        exit;
                    }
                    else {
                        $this->strFileName[$key] = strtolower($_FILES['file']['name'][$key]);
                    }
                }
            }
        }
	}

	// 파일첨부
	function _uploadFile($member_code, $file_code) {
        // 쎔네일 이미지
        if (count($this->strThumbnail) > 0) {
            $this->uploadFile("file1",_USER_DIR."/room/".$file_code."_1");
		}

        // 설명 이미지
        if (count($this->strFileName) > 0) {
            foreach($this->strFileName as $key => $val) {
                $arrFileInfo = $this->uploadFileBulk($key);

                if(in_array($arrFileInfo['file_ext'], $this->arrImgType)) {
					// 쎔네일 이미지 생성
                    $arrFileName = explode(".", $arrFileInfo['file_name']);
                    $thumbnail_name = $arrFileName[0]."_tn".".".$arrFileInfo['file_ext'];
                    $reqData['orig_path'] = $arrFileInfo['file_path']."/".$arrFileInfo['file_name'];    // 원본 파일 경로
                    $reqData['target_path'] = $arrFileInfo['file_path']."/".$thumbnail_name;  // 썸네일 파일 경로
                    $reqData['width']   = _THUMBNAIL_WIDTH;      // 가로 사이즈
                    $reqData['height']  = _THUMBNAIL_HEIGHT;      // 세로 사이즈
                    $arrReturn = $this->makeThumbnail($reqData);
				}
                else {
                    $thumbnail_name = '';
                }

                // attachment 정보 저장 하기
                $arrParam = array (
                    'member_code' => $member_code,
                    'table_name' => 'room',
                    'table_code' => $file_code,
                    'file_path' => $arrFileInfo['file_path'],
                    'file_name' => $arrFileInfo['file_name'],
                    'thumbnail_name' => $thumbnail_name,
                    'orig_name' => $arrFileInfo['orig_name'],
                    'file_size' => $arrFileInfo['file_size'],
                    'file_width' => $arrFileInfo['file_width'],
                    'file_height' => $arrFileInfo['file_height'],
                    'file_ext' => $arrFileInfo['file_ext'],
                    'reg_date' => 'now()'
                );
                $this->objDBH->insert("attachment", $arrParam);

                usleep(1);
			}
		}
	}

    // 정보 가져오기
    function viewRoom($reqData) {
        checkParam($reqData['code'], "code");
        $arrReturn = $this->objDBH->getRow("select * from ".$this->table."_room where code='".$reqData['code']."'");

        // 가격정보
        $arrData = $this->objDBH->getRows("select * from ".$this->table."_fee where reservation_room_code='".$reqData['code']."'");
        if (!empty($arrData['list'])) {
            foreach($arrData['list'] as $key => $val) {
                $arrReturn['arrFee']['price'.$val['reservation_period_code'].$val['reservation_weekend_code']] = $val['price'];
            }
        }

        // 썸네일 이미지
        if(file_exists(_USER_DIR."/room/".$reqData['code']."_1")) {
            $arrReturn['thumbnail'] = _USER_URL."/room/".$reqData['code']."_1?dummy=".getDummy();
        }

        // 파일 표출
        $arrFile = $this->objDBH->getRows("select code,file_path,file_name,thumbnail_name,orig_name,file_size,file_width,file_height,file_ext,concat('"._USER_URL."',file_path,'/',file_name,'?dummy=',".getDummy().") as url,date_format(reg_date,'%Y-%m-%d %H:%i') as reg_date from attachment where table_name='room' and table_code='".$reqData['code']."' order by code");
        if (!empty($arrFile['list'])) {
            $arrReturn['files'] = $arrFile['list'];
        }
        else {
            $arrReturn['files'] = null;
        }
        return $arrReturn;
    }

    // 리스트 가져오기
    function listRoom($reqData) {
        $add_where = '';
        if(!empty($reqData['category_code'])) $add_where .= " and category_code = '".$reqData['category_code']."'";
        if(!empty($reqData['keyword'])) $add_where .= " and ".$reqData['field']." like '%".$reqData['keyword']."%'";
        $arrReturn['list_query'] = "select * from ".$this->table."_room where 1".$add_where." order by order_code desc";
        return $arrReturn;
    }

    // 등록
	function insertRoom($reqData) {
        $this->_checkFile();

        // 객실 정보 등록
        $arrParam = array (
            'category_code' => $reqData['category_code'],
            'title'         => $reqData['title'],
            'standard_count'=> $reqData['standard_count'],
            'max_count'     => $reqData['max_count'],
            'add_fee_adult' => skipComma($reqData['add_fee_adult']),
            'add_fee_child' => skipComma($reqData['add_fee_child']),
            'content'       => $reqData['content'],
            'status'        => $reqData['status'],
            'reg_date'      => 'now()'
        );
        $this->objDBH->insert($this->table.'_room', $arrParam);
        $code = $this->objDBH->getLastId();

        $this->_uploadFile($reqData['member_code'], $code);

        // 가격 정보 등록
        $arrData = $this->listPeriodWeekend();
        foreach($arrData['arrPeriod'] as $key => $val) {
            foreach($arrData['arrWeekend'] as $key2 => $val2) {
                $arrParam = array (
                    'price'                     => skipComma($reqData['price'.$val['code'].$val2['code']]),
                    'reservation_period_code'   => $val['code'],
                    'reservation_weekend_code'  => $val2['code'],
                    'reservation_room_code'     => $code
                );
                $this->objDBH->insert($this->table.'_fee', $arrParam);
            }
        }
		return $code;
	}

	// 수정
	function updateRoom($reqData) {
        // 파일 삭제모듈
        if (!empty($reqData['delete_file'])) {
            if (@$reqData['member_level'] != 1) {
                $add_where = " and member_code='".$reqData['member_code']."'";
            }
            $query = "delete from attachment where code in (".$reqData['delete_file'].")".$add_where;
            $this->objDBH->query($query);
        }

        $this->_checkFile();
        $this->_uploadFile($reqData['member_code'], $reqData['room_code']);

        // 객실 정보 수정
        $arrParam = array (
            'category_code' => $reqData['category_code'],
            'title'         => $reqData['title'],
            'standard_count'=> $reqData['standard_count'],
            'max_count'     => $reqData['max_count'],
            'add_fee_adult' => skipComma($reqData['add_fee_adult']),
            'add_fee_child' => skipComma($reqData['add_fee_child']),
            'content'       => $reqData['content'],
            'status'        => $reqData['status']
        );
        $arrWhere = array(
            'code' => $reqData['room_code']
        );
        $this->objDBH->update($this->table.'_room', $arrParam, $arrWhere);

        // 가격 정보 수정
        $arrData = $this->listPeriodWeekend();
        foreach($arrData['arrPeriod'] as $key => $val) {
            foreach($arrData['arrWeekend'] as $key2 => $val2) {
                $arrParam = array (
                    'price' => skipComma($reqData['price'.$val['code'].$val2['code']])
                );
                $arrWhere = array(
                    'reservation_period_code'   => $val['code'],
                    'reservation_weekend_code'  => $val2['code'],
                    'reservation_room_code'     => $reqData['room_code']
                );
                $result = $this->objDBH->update($this->table.'_fee', $arrParam, $arrWhere);
            }
        }
	}

	// 삭제
	function deleteRoom($reqData) {
        $query = "delete from ".$this->table."_room where code in (".$reqData['code'].")";
		$this->objDBH->query($query);
	}

    /************************* 추가 옵션 *************************/
    // 정보 가져오기
    function viewOption($reqData) {
        checkParam($reqData['code'], "code");
        $arrReturn = $this->objDBH->getRow("select * from ".$this->table." where code='".$reqData['code']."'");
        return $arrReturn;
    }

    // 리스트 가져오기 : list_query 값으로 넘기면 parent 단에서 displayDataList() 실행
    function listOption($reqData=array()) {
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

    // 등록
	function insertOption($reqData) {
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
	function updateOption($reqData) {
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

	// 삭제
	function deleteOption($reqData) {
        $query = "delete from ".$this->table." where code='".$reqData['code']."'";
		$this->objDBH->query($query);
	}

    /************************* 기간 설정 *************************/
    // 일자 유효성 검사
    function checkDateTime($reqData) {
        $start_date = strtotime($reqData['start_date'].' '.@$reqData['start_time']);
        $end_date = strtotime($reqData['end_date'].' '.@$reqData['end_time']);
        if($end_date - $start_date < 0) {
            putJSMessage("시작날짜-시간보다 종료날짜-시간이 같거나 이전일수 없습니다.");
            exit;
        }
    }

    // 기간 리스트
    function listDate($reqData=array()) {
        // 구간
        $reqData['is_fee'] = 'y';
        $arrDataPeriod = $this->listPeriod($reqData);
        if (!empty($arrDataPeriod['list'])) {
            foreach($arrDataPeriod['list'] as $key => $val) {
                $arrBackgroundColor[$val['code']] = $val['background_color'];
                $arrTitle[$val['code']] = $val['title'];
            }
        }
        $where = ' where 1';
        if(!empty($reqData['keyword'])) $where .= " and ".$reqData['field']." like '%".$reqData['keyword']."%'";
        $arrDataPeriod = $this->objDBH->getRows("select code,reservation_period_code,concat(start_date,' 09:00') as start,concat(end_date,' 09:30') as end from ".$this->table."_period_data".$where);
        if (!empty($arrDataPeriod['list'])) {
            foreach($arrDataPeriod['list'] as $key => $val) {
                $arrDataPeriod['list'][$key]['title'] = @$arrTitle[$val['reservation_period_code']];
                $arrDataPeriod['list'][$key]['editable'] = true;
                $arrDataPeriod['list'][$key]['backgroundColor'] = @$arrBackgroundColor[$val['reservation_period_code']];
                $arrDataPeriod['list'][$key]['borderColor'] = @$arrBackgroundColor[$val['reservation_period_code']];
            }
        }

        // 이벤트
        $arrDataEvent = $this->listEvent($reqData);
        if (!empty($arrDataEvent['list'])) {
            foreach($arrDataEvent['list'] as $key => $val) {
                $arrBackgroundColor[$val['code']] = $val['background_color'];
                $arrTitle[$val['code']] = $val['title'];
            }
        }
        $arrDataEvent = $this->objDBH->getRows("select code,reservation_event_code,memo,concat(start_date,' 09:00') as start,concat(end_date,' 09:30') as end from ".$this->table."_event_data".$where);
        if (!empty($arrDataEvent['list'])) {
            foreach($arrDataEvent['list'] as $key => $val) {
                $arrDataEvent['list'][$key]['title'] = @$arrTitle[$val['reservation_event_code']];
                $arrDataEvent['list'][$key]['editable'] = true;
                $arrDataEvent['list'][$key]['backgroundColor'] = @$arrBackgroundColor[$val['reservation_event_code']];
                $arrDataEvent['list'][$key]['borderColor'] = @$arrBackgroundColor[$val['reservation_event_code']];
            }
        }

        $arrList = array();
        if(!empty($arrDataPeriod['list'])) $arrList = array_merge($arrList, $arrDataPeriod['list']);
        if(!empty($arrDataEvent['list'])) $arrList = array_merge($arrList, $arrDataEvent['list']);
        if (!empty($arrList)) return json_encode($arrList);
        else return '[]';
    }

    // 기간 등록
	function insertDate($reqData) {
        $this->checkDateTime($reqData);
        if ($reqData['period_code'] == 'event') {   // 이벤트
            $arrParam = array (
                'reservation_event_code'   => $reqData['event_code'],
                'memo'      => $reqData['memo'],
                'start_date'=> $reqData['start_date'],
                'end_date'  => $reqData['end_date']
            );
            $this->objDBH->insert($this->table."_event_data", $arrParam);
        }
        else {                                      // 구간
            $arrParam = array (
                'reservation_period_code'   => $reqData['period_code'],
                'start_date'=> $reqData['start_date'].' '.$reqData['start_time'],
                'end_date'  => $reqData['end_date'].' '.$reqData['end_time']
            );
            $this->objDBH->insert($this->table."_period_data", $arrParam);
        }
        $code = $this->objDBH->getLastId();
        return $code;
	}

	// 기간 수정
	function updateDate($reqData) {
        $this->checkDateTime($reqData);
        if ($reqData['period_code'] == 'event') {   // 이벤트
            $arrParam = array (
                'reservation_event_code'   => $reqData['event_code'],
                'memo'      => $reqData['memo'],
                'start_date'=> $reqData['start_date'],
                'end_date'  => $reqData['end_date']
            );
            $arrWhere = array(
                'code' => $reqData['code']
            );
            $this->objDBH->update($this->table."_event_data", $arrParam, $arrWhere);
        }
        else {                                      // 구간
            $arrParam = array (
                'reservation_period_code'   => $reqData['period_code'],
                'start_date'=> $reqData['start_date'].' '.$reqData['start_time'],
                'end_date'  => $reqData['end_date'].' '.$reqData['end_time']
            );
            $arrWhere = array(
                'code' => $reqData['code']
            );
            $this->objDBH->update($this->table."_period_data", $arrParam, $arrWhere);
        }
	}

    // 기간 삭제
	function deleteDate($reqData) {
        if ($reqData['period_code'] == 'event') {   // 이벤트
            $query = "delete from ".$this->table."_event_data where code='".$reqData['code']."'";
        }
        else {                                      // 구간
            $query = "delete from ".$this->table."_period_data where code='".$reqData['code']."'";
        }
		$this->objDBH->query($query);
	}

    /************************* 구간 설정 *************************/
    // 구간 리스트
    function listPeriod($reqData) {
        $add_where = '';
        if (!empty($reqData['is_fee'])) $add_where = " and title != ''";
        if (!empty($reqData['no_holiday'])) $add_where .= " and code < '"._HOLIDAY_CODE."'";

        $arrReturn = $this->objDBH->getRows("select * from ".$this->table."_period where 1".$add_where." order by code");
        return $arrReturn;
    }

    // 구간 수정
	function updatePeriod($reqData) {
        for($i=2; $i<=9; $i++) {
            $arrParam = array (
                'title'             => $reqData['form_period_title'.$i],
                'background_color'  => $reqData['background_color'.$i]
            );
            $arrWhere = array(
                'code' => $i
            );
            $this->objDBH->update($this->table.'_period', $arrParam, $arrWhere);
        }
	}

    /************************* 주말 설정 *************************/
    // 주말 리스트
    function listWeekend($reqData) {
        $where = '';
        if (!empty($reqData['is_fee'])) $where = " where title != ''";

        $arrReturn = $this->objDBH->getRows("select * from reservation_weekend".$where." order by code");
        return $arrReturn;
    }

    // 주말 수정
	function updateWeekend($reqData) {
        for($i=1; $i<=5; $i++) {
            $arrParam = array (
                'title'     => $reqData['form_weekend_title'.$i],
                'week_index'=> preg_replace('/,$/','',$reqData['weekend'.$i])
            );
            $arrWhere = array(
                'code' => $i
            );
            $this->objDBH->update($this->table.'_weekend', $arrParam, $arrWhere);
        }
	}

    /************************* 이벤트 설정 *************************/
    // 이벤트 리스트
    function listEvent($reqData) {
        $arrReturn = $this->objDBH->getRows("select * from reservation_event order by code");
        return $arrReturn;
    }

    // 이벤트 수정
	function updateEvent($reqData) {
        // if(getenv("REMOTE_ADDR") == "121.140.62.102") { echo "<div align=left><pre>"; var_dump($reqData); echo "</pre>"; die("<br>End</div>");}
        // 삭제
        if (!empty($reqData['delete_event'])) {
            $query = "delete from ".$this->table."_event where code in (".$reqData['delete_event'].")";
            $this->objDBH->query($query);
        }
        // 수정
        foreach($reqData as $key => $val) {
            if (preg_match('/^title/',$key)) {
                $code = preg_replace('/title/','',$key);
                if ($val != '') {
                    $arrParam = array (
                        'title'             => $val,
                        'background_color'  => $reqData['background_color'.$code]
                    );
                    $arrWhere = array(
                        'code' => $code
                    );
                    $this->objDBH->update($this->table.'_event', $arrParam, $arrWhere);
                }
            }
        }
        // 입력
        if (!empty($reqData['form_event_title'])) {
            foreach($reqData['form_event_title'] as $key => $val) {
                if ($val != '') {
                    $arrParam = array (
                        'title'             => $val,
                        'background_color'  => $reqData['background_color'][$key]
                    );
                    $this->objDBH->insert($this->table.'_event', $arrParam);
                }
            }
        }
	}

    // 구간&주말 리스트
    function listPeriodWeekend($reqData=array()) {
        $reqData['is_fee'] = 'y';
        $reqData['no_holiday'] = 'y';
        $arrPeriod = $this->listPeriod($reqData);
        $arrReturn['arrPeriod'] = $arrPeriod['list'];

        $arrWeekend = $this->listWeekend($reqData);
        $arrReturn['arrWeekend'] = $arrWeekend['list'];
        return $arrReturn;
    }
}
?>