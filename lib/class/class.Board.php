<?php
/**
 * Account Board
 *
 */
include_once _CLASS_DIR."/class.UtilFile.php";
class Board extends UtilFile {
	private $objDBH;
	private $table = 'board_data';
    private $arrBoardInfo = array();
    private $strFileName = array();

	function __construct($obj) {
		$this->objDBH = $obj;
	}

    /************************* 게시판 *************************/
    // 게시판 정보 가져오기
    function info($reqData) {
        checkParam($reqData['board_code'], "board_code");

        $this->arrBoardInfo = $this->objDBH->getRow("select * from board where code='".$reqData['board_code']."'");
        return $this->arrBoardInfo;
    }

    // 권한 체크
    function checkAuth($action='list') {
        if ($this->arrBoardInfo['auth_'.$action] != 0) {                    // 권한 체크가 있을때
            if (getLoginLevel() == 0) { // 로그인 전 : 로그인 페이지 이동
                $return_url = urlencode($_SERVER['REQUEST_URI']);
                putJSMessage("", "replace", "?tpf=member/login&return_url=".$return_url);
            }

            if (getLoginLevel() > $this->arrBoardInfo['auth_'.$action]) {   // 권한 체크
                putJSMessage("귀하는 본 사이트에 대한\\n\\n접근 접근 권한이 없습니다.","back");
                exit;
            }
        }
    }

    // 게시판 리스트 가져오기
    function listBoard($reqData) {
        $arrReturn = $this->objDBH->getRows("select * from board order by code");
        return $arrReturn;
    }

    // 게시판 등록된 글수 가져오기
    function getCount() {
        $arrReturn = $this->objDBH->getRows("select board_code,count(*) as count from board_data group by board_code");
        $arrTmp = array();
        if (!empty($arrReturn['list'])) {
            foreach($arrReturn['list'] as $key => $val) {
                $arrTmp[$val['board_code']] = $val['count'];
            }
        }
        return $arrTmp;
    }

    // 등록
	function insertBoard($reqData) {
        $arrParam = array (
            'type'          => $reqData['type'],
            'title'         => $reqData['title'],
            'header'        => $reqData['header'],
            'category'      => $reqData['category'],
            'show_list'     => $reqData['show_list'],
            'show_memo'     => $reqData['show_memo'],
            'limit_title'   => @$reqData['limit_title'],
            'auth_list'     => $reqData['auth_list'],
            'auth_view'     => $reqData['auth_view'],
            'auth_write'    => $reqData['auth_write'],
            'auth_reply'    => $reqData['auth_reply'],
            'auth_update'   => $reqData['auth_update'],
            'auth_memo'     => $reqData['auth_memo'],
            'auth_delete'   => $reqData['auth_delete'],
            'auth_notice'   => $reqData['auth_notice'],
            'is_secret'     => $reqData['is_secret'],
            'is_mass'       => $reqData['is_mass'],
            'is_order'      => $reqData['is_order'],
            'is_captcha'    => $reqData['is_captcha']
        );
        $this->objDBH->insert("board", $arrParam);
        $code = $this->objDBH->getLastId();

        return $code;
	}

	// 수정
	function updateBoard($reqData) {
        $arrParam = array (
            'type'          => $reqData['type'],
            'title'         => $reqData['title'],
            'header'        => $reqData['header'],
            'category'      => $reqData['category'],
            'show_list'     => $reqData['show_list'],
            'show_memo'     => $reqData['show_memo'],
            'limit_title'   => @$reqData['limit_title'],
            'auth_list'     => $reqData['auth_list'],
            'auth_view'     => $reqData['auth_view'],
            'auth_write'    => $reqData['auth_write'],
            'auth_reply'    => $reqData['auth_reply'],
            'auth_update'   => $reqData['auth_update'],
            'auth_memo'     => $reqData['auth_memo'],
            'auth_delete'   => $reqData['auth_delete'],
            'auth_notice'   => $reqData['auth_notice'],
            'is_secret'     => $reqData['is_secret'],
            'is_mass'       => $reqData['is_mass'],
            'is_order'      => $reqData['is_order'],
            'is_captcha'    => $reqData['is_captcha']
        );
        $arrWhere = array(
            'code' => $reqData['board_code']
        );
        $this->objDBH->update("board", $arrParam, $arrWhere);
	}

	// 삭제
	function deleteBoard($reqData) {
        $query = "delete from board where code in (".$reqData['code'].")";
		$this->objDBH->query($query);
	}

    /************************* 게시물 *************************/
    // 정보 가져오기
    function view($reqData) {
        checkParam($reqData['board_code'], "board_code");
        checkParam($reqData['code'], "code");

        // hitting + 1
        $this->objDBH->query("update ".$this->table." set hitting=hitting+1 where code='".$reqData['code']."' and board_code='".$reqData['board_code']."'");

        $arrReturn = $this->objDBH->getRow("select *,to_days(end_date)-to_days(current_date) as diff_event_date from ".$this->table." where code='".$reqData['code']."' and board_code='".$reqData['board_code']."'");

        // 파일 표출
        $arrFile = $this->objDBH->getRows("select code,file_path,file_name,thumbnail_name,orig_name,file_size,file_width,file_height,file_ext,concat('"._USER_URL."',file_path,'/',file_name,'?dummy=',".getDummy().") as url,date_format(reg_date,'%Y-%m-%d %H:%i') as reg_date from attachment where table_name='board_data' and table_code='".$reqData['code']."' order by code");
        if (!empty($arrFile['list'])) {
            $arrReturn['files'] = $arrFile['list'];
        }
        else {
            $arrReturn['files'] = null;
        }
        return $arrReturn;
    }

    // 이전/다음글 가져오기
    function getPrevNext($reqData) {
        checkParam($reqData['board_code'], "board_code");
        checkParam($reqData['code'], "code");

        $arrData = $this->objDBH->getRow("select num from ".$this->table." where code='".$reqData['code']."'");

        // 이전글
        $arrPrev = $this->objDBH->getRow("select code,board_code,title,date_format(reg_date,'%Y.%m.%d') as reg_date from ".$this->table." where board_code='".$reqData['board_code']."' and num < '".$arrData['num']."' and depth='A' order by num desc limit 1");
        if(file_exists(_USER_DIR."/board/".$arrPrev['code'])) {
            $arrPrev['file'] = $arrPrev['code'];
        }

        // 다음글
        $arrNext = $this->objDBH->getRow("select code,board_code,title,date_format(reg_date,'%Y.%m.%d') as reg_date from ".$this->table." where board_code='".$reqData['board_code']."' and num > '".$arrData['num']."' and depth='A' order by num limit 1");
        if(file_exists(_USER_DIR."/board/".$arrNext['code'])) {
            $arrNext['file'] = $arrNext['code'];
        }
        $arrReturn['prev'] = $arrPrev;
        $arrReturn['next'] = $arrNext;

        return $arrReturn;
    }

    // 리스트 가져오기 : list_query 값으로 넘기면 parent 단에서 displayDataList() 실행
    function lists($reqData) {
        checkParam($reqData['board_code'], "board_code");

        $add_where = !empty($reqData['category']) ? " and b.category = '".$reqData['category']."'" : "";
        $add_where .= !empty($reqData['keyword']) ? " and ".$reqData['field']." like '%".$reqData['keyword']."%'" : "";

        $arrReturn['list_query'] = "select b.code,b.num,length(b.depth) as length_depth,b.title,b.name,b.content,b.link,b.category,b.start_date,b.end_date,b.hitting,b.memo_count,b.is_secret,to_days(current_date)-to_days(b.reg_date) as diff_date,to_days(b.end_date)-to_days(current_date) as diff_event_date,date_format(b.reg_date,'%Y-%m-%d %H:%i') as reg_date,date_format(b.reg_date,'%Y-%m-%d') as reg_date_short,ANY_VALUE(concat('"._USER_URL."',a.file_path,'/',a.thumbnail_name,'?dummy=',".getDummy().")) as image_url from ".$this->table." b left join attachment a on (a.table_name='board_data' and b.code=a.table_code) where b.board_code='".$reqData['board_code']."' and b.is_notice='' ".$add_where." group by b.code order by b.num asc";

		return $arrReturn;
    }

    // 리스트 가져오기 : 공지사항
    function listNotice($reqData) {
        checkParam($reqData['board_code'], "board_code");

        $add_where = !empty($reqData['category']) ? " and b.category = '".$reqData['category']."'" : "";
        $add_where .= !empty($reqData['keyword']) ? " and ".$reqData['field']." like '%".$reqData['keyword']."%'" : "";

        $arrData = $this->objDBH->getRows("select b.code,b.num,length(b.depth) as length_depth,b.title,b.name,b.content,b.category,b.hitting,to_days(current_date)-to_days(b.reg_date) as diff_date,date_format(b.reg_date,'%Y-%m-%d %H:%i') as reg_date,date_format(b.reg_date,'%Y-%m-%d') as reg_date_short,ANY_VALUE(concat('"._USER_URL."',a.file_path,'/',a.file_name,'?dummy=',".getDummy().")) as image_url from ".$this->table." b left join attachment a on (a.table_name='board_data' and b.code=a.table_code) where b.board_code='".$reqData['board_code']."' and b.is_notice='y'".$add_where." group by b.code  order by b.num, b.depth asc");

		return $arrData;

    }

    function getMinNum($board_code) {
        $arrBoard = $this->objDBH->getRow("select min(num) as min from ".$this->table." where board_code='".$board_code."'");
		if (!$arrBoard['min']) $arrBoard['min'] = -1;
		else $arrBoard['min']--;
		return $arrBoard['min'];
	}

    // 파일체크
	function _checkFile() {
        $this->setFiles();
        if (!empty($_FILES['file']['name'])) {
            foreach($_FILES['file']['name'] as $key => $val) {
                if (!empty($val)) {
                    $arrResultFile = $this->checkFileBulk("File",$key);
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
                    'table_name' => 'board_data',
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

    // 대용량 파일 (파일첨부)
	function _uploadFilePL($member_code, $file_code) {
        $sql = "update attachment set table_code='".$file_code."' where plupload_id='".@$_COOKIE['plupload_id']."' and plupload_id <> ''";
        $this->objDBH->query($sql);
	}

    // 등록
	function insert($reqData) {
        $this->_checkFile();
        $password = getHash(@$reqData['password']);
        $is_secret = @$reqData['is_secret'];

        if (@$reqData['mode'] == "reply") {  // 답변
            $arrBoardData = $this->objDBH->getRow("select num,depth,is_secret,password from ".$this->table." where board_code='".$reqData['board_code']."' and code='".$reqData['board_data_code']."'");
            $num = $arrBoardData['num'];
            if ($arrBoardData['is_secret'] == 'y') {    // 원본글이 비밀글일때
                $password = $arrBoardData['password'];
                $is_secret = 'y';
            }

            $arrDepth = $this->objDBH->getRow("select depth,right(depth,1) as right_depth from ".$this->table." where board_code='".$reqData['board_code']."' and num='".$num."' and length(depth) = length('".$arrBoardData['depth']."')+1 and locate('".$arrBoardData['depth']."',depth) = 1 order by depth desc limit 1");
            if($arrDepth['depth']) {	// 이미 해당 답글이 있을때
				$depth_head = substr($arrDepth['depth'],0,-1);
				$depth_foot = ++$arrDepth['right_depth'];
				$depth = $depth_head.$depth_foot;
			}
			else {						// 처음 답글일때
				$depth = $arrBoardData['depth']."A";
			}
		}
		else {                              // 글쓰기
            $num = $this->getMinNum($reqData['board_code']);
			$depth = "A";
		}
        $arrParam = array (
            'board_code'    => $reqData['board_code'],
            'num'           => $num,
            'depth'         => $depth,
            'member_code'   => $reqData['member_code'],
            'name'          => $reqData['name'],
            'tel'           => @$reqData['tel'],
            'email'         => @$reqData['email'],
            'link'          => @$reqData['link'],
            'title'         => RemoveXSS($reqData['title']),
            'category'      => @$reqData['category'],
            'start_date'    => @$reqData['start_date'],
            'end_date'      => @$reqData['end_date'],
            'content'       => RemoveXSS($reqData['content']),
            'is_notice'     => @$reqData['is_notice'],
            'is_secret'     => $is_secret,
            'password'      => $password,
            'ip'            => $_SERVER['REMOTE_ADDR'],
            'reg_date'      => "now()"
        );
        $this->objDBH->insert($this->table, $arrParam);
        $code = $this->objDBH->getLastId();

        $this->_uploadFile($reqData['member_code'], $code);
        $this->_uploadFilePL($reqData['member_code'], $code); // 대용량 파일 업로드
		return $code;
	}

	// 수정
	function update($reqData) {
        // 파일 삭제모듈
        if (!empty($reqData['delete_file'])) {
            if (@$reqData['member_level'] != 1) {
                $add_where = " and member_code='".$reqData['member_code']."'";
            }
            $query = "delete from attachment where code in (".$reqData['delete_file'].")".$add_where;
            $this->objDBH->query($query);
        }

        $this->_checkFile();
        $this->_uploadFile($reqData['member_code'], $reqData['board_data_code']);
        $this->_uploadFilePL($reqData['member_code'], $reqData['board_data_code']);
        $arrParam = array (
            'name'      => $reqData['name'],
            'tel'       => @$reqData['tel'],
            'email'     => @$reqData['email'],
            'link'      => @$reqData['link'],
            'title'     => RemoveXSS($reqData['title']),
            'category'  => @$reqData['category'],
            'start_date'    => @$reqData['start_date'],
            'end_date'      => @$reqData['end_date'],
            'content'   => $reqData['content'],
            'is_notice' => $reqData['is_notice'],
            'is_secret' => @$reqData['is_secret']
        );
        $arrWhere = array(
            'code' => $reqData['board_data_code'],
            'board_code' => $reqData['board_code']
        );
        $this->objDBH->update($this->table, $arrParam, $arrWhere);

	}

	// 삭제
	function delete($reqData) {
        $query = "delete from ".$this->table." where code in (".$reqData['code'].") and board_code='".$reqData['board_code']."'";
		$this->objDBH->query($query);
	}

    // 비번 체크
	function checkPassword($reqData) {
        $arrReturn = $this->objDBH->getRow("select password from ".$this->table." where code='".$reqData['board_data_code']."' and board_code='".$reqData['board_code']."'");
        if ($arrReturn['password'] != getHash($reqData['password'])) {
            putJSMessage("비밀번호가 일치하지 않습니다.");
            exit;
        }
    }

    /************************* 댓글 *************************/
    function listMemo($reqData) {
        checkParam($reqData['code'], "board_data_code");

        $arrData = $this->objDBH->getRows("select code,name,content,date_format(reg_date,'%Y-%m-%d %H:%i') as reg_date from board_memo where board_data_code='".$reqData['code']."' order by code");
		return $arrData;
	}

    // 댓글 개수 수정
	function updateMemoCount($board_data_code) {
        $arrBoardMemo = $this->objDBH->getRow("select count(*) as count from board_memo where board_data_code='".$board_data_code."'");

        $arrParam = array (
            'memo_count' => $arrBoardMemo['count']
        );
        $arrWhere = array (
            'code' => $board_data_code
        );
        $this->objDBH->update($this->table, $arrParam, $arrWhere);
	}

    // 등록
    function insertMemo($reqData) {
        $arrParam = array (
            'board_data_code'   => $reqData['board_data_code'],
            'name'              => RemoveXSS($reqData['name']),
            'password'          => getHash($reqData['password']),
            'content'           => RemoveXSS($reqData['content']),
            'ip'                => $_SERVER['REMOTE_ADDR'],
            'reg_date'          => 'now()'
        );
        $this->objDBH->insert("board_memo", $arrParam);
        $code = $this->objDBH->getLastId();

        $this->updateMemoCount($reqData['board_data_code']);

        return $code;
	}

    // 비번 체크
	function checkPasswordMemo($reqData) {
        $arrReturn = $this->objDBH->getRow("select password,content from board_memo where code='".$reqData['code']."'");
        if ($arrReturn['password'] != getHash($reqData['delete_password'])) {
            putJSMessage("비밀번호가 일치하지 않습니다.");
            exit;
        }
    }

    // 삭제
	function deleteMemo($board_data_code, $code) {
        $arrWhere = array (
            'code' => $code
        );
        $this->objDBH->delete("board_memo", $arrWhere);

        $this->updateMemoCount($board_data_code);
	}
}
?>