<?php
/**
 */

class UtilPopup {
	private $objDBH;
	private $table = 'popup';
    private $strFileName = array();

	function __construct($obj) {
		$this->objDBH = $obj;
	}

	function lists($reqData) {
        $arrWhere = array();
        $add_where = '';
        if(!empty($reqData['start_date'])) $arrWhere[] = "date_format(start_date,'%Y-%m-%d') >= '".$reqData['start_date']."'";
        if(!empty($reqData['end_date'])) $arrWhere[] = "date_format(end_date,'%Y-%m-%d') <= '".$reqData['end_date']."'";
        if(!empty($reqData['keyword'])) $arrWhere[] = $reqData['field']." like '%".$reqData['keyword']."%'";
        if (!empty($arrWhere)) {
            $add_where = "where ".implode(" and ", $arrWhere);
        }

        $arrReturn['list_query'] = "select *, date_format(start_date,'%Y-%m-%d') as start_date_short, date_format(end_date,'%Y-%m-%d') as end_date_short  from ".$this->table.' '.$add_where." order by code desc";
        return $arrReturn;
    }

	function info($reqData) {
        $arrReturn = $this->objDBH->getRow("select *, date_format(start_date,'%Y-%m-%d') as start_date_short, date_format(end_date,'%Y-%m-%d') as end_date_short  from ".$this->table." where code='".$reqData['code']."'");
        return $arrReturn;
    }

	// 등록
	function insert($reqData) {
        $arrParam = array (
			'start_date'    => $reqData['start_date'],
			'end_date'		=> $reqData['end_date'],
            'title'         => $reqData['title'],
            'content'       => $reqData['content'],
            'display'		=> $reqData['display'],
            'popup_cookie'  => $reqData['popup_cookie'],
            'width'         => $reqData['width'],
            'height'        => $reqData['height'],
			'top_position'  => $reqData['top_position'],
			'left_position' => $reqData['left_position'],
            'reg_date'      => "now()"
        );
        $this->objDBH->insert($this->table, $arrParam);
        $code = $this->objDBH->getLastId();

		return $code;
	}

	// 수정
	function update($reqData) {
        $arrParam = array (
            'start_date'    => $reqData['start_date'],
			'end_date'		=> $reqData['end_date'],
            'title'         => $reqData['title'],
            'content'       => $reqData['content'],
            'display'		=> $reqData['display'],
            'popup_cookie'  => $reqData['popup_cookie'],
            'width'         => $reqData['width'],
            'height'        => $reqData['height'],
			'top_position'  => $reqData['top_position'],
			'left_position' => $reqData['left_position']
        );
        $arrWhere = array(
            'code' => $reqData['code'],
        );
        $this->objDBH->update($this->table, $arrParam, $arrWhere);
	}

	// 삭제
	function delete($reqData) {
        $query = "delete from ".$this->table." where code in (".$reqData['code'].")";
		$this->objDBH->query($query);
	}
}
?>