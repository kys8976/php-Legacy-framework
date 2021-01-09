<?php
/**
 * Member Class
 *
 */
include_once(_CLASS_DIR."/class.UtilFile.php");
class Member extends UtilFile {
    private $table = 'member';
    private $strFileName;

	function __construct($obj) {
		$this->objDBH = $obj;
	}

    /************************* 로그인 *************************/
    // login
    function checkLogin($reqData) {
        $arrMember = $this->objDBH->getRow("select code,name,level from ".$this->table." where id='".$reqData['id']."' and password='".getHash($reqData['password'])."' and status='y'");
        if (!$arrMember['code']) {  // 임시 비밀번호 로그인 시도
            $arrMemberTmpPassword = $this->objDBH->getRow("select code from member_tmp_password where id='".$reqData['id']."' and password='".$reqData['password']."' and status='ready' and date_add(reg_date,interval 1 day) > now()");
            if ($arrMemberTmpPassword['code']) {
                $arrMember = $this->objDBH->getRow("select code,name,level from ".$this->table." where id='".$reqData['id']."' and status='y'");
                if ($arrMember['code']) {
                    // 임시 비밀번호 계정 사용으로 변경
                    $arrParam = array (
                        'status' => 'done'
                    );
                    $arrWhere = array (
                        'id' => $reqData['id'],
                        'password' => $reqData['password']
                    );
                    $this->objDBH->update("member_tmp_password", $arrParam, $arrWhere);

                    // 기존 비밀번호 임시비밀번호로 변경
                    $arrParam = array (
                        'password' => getHash($reqData['password'])
                    );
                    $arrWhere = array (
                        'id' => $reqData['id']
                    );
                    $this->objDBH->update($this->table, $arrParam, $arrWhere);
                    return $arrMember;
                }
            }
            return null;
        }
        return $arrMember;
    }

    // admin login
    function checkAdminLogin($reqData) {
        $arrResult = $this->objDBH->getRow("select code,name,level from ".$this->table." where id='".$reqData['id']."' and password='".getHash($reqData['password'])."' and level <= 1 and status='y'");
        return $arrResult;
    }

    // id 중복 확인
    function checkAvailableId($id) {
        $arrMember = $this->objDBH->getRow("select count(*) as count from member where id='".$id."'");
        if ($arrMember['count'] != 0) {
            return false;
        }
        else {
            return true;
        }
    }

    // 휴대폰 중복 확인
    function checkAvailableMobile($mobile) {
        $arrMember = $this->objDBH->getRow("select count(*) as count from member where mobile='".$mobile."'");
        if ($arrMember['count'] != 0) {
            return false;
        }
        else {
            return true;
        }
    }

    // email 중복 확인
    function checkAvailableEmail($email) {
        if (!getLoginId()) {    // 로그인 전
            $arrMember = $this->objDBH->getRow("select count(*) as count from member where email='".$email."'");
            if ($arrMember['count'] != 0) {
                return false;
            }
            else {
                return true;
            }
        }
        else {                  // 로그인 후
            $arrMember = $this->objDBH->getRow("select count(*) as count from member where code != '".getLoginCode()."' and email='".$email."'");
            if ($arrMember['count'] != 0) {
                return false;
            }
            else {
                return true;
            }
        }
    }

    // 최근 접속일 업데이트
    function setLastLoginDate($member_code) {
        $arrParam = array (
            'last_login_date' => 'now()'
        );
        $arrWhere = array(
            'code'  => $member_code
        );
        $this->objDBH->update($this->table, $arrParam, $arrWhere);
        $count = $this->objDBH->getAffected();
        if ($count == 1) return "udpate completed";
	}

    /************************* 회원관리 *************************/
    // 요약 정보 가져오기
    function getInfoBrief($reqData) {
        // 회원 정보
        $arrMember = $this->objDBH->getRow("select name,member_type,level from ".$this->table." where code='".$reqData['member_code']."'");

        // 판매 개수
        $arrProduct = $this->objDBH->getRow("select count(*) as count from product where member_code='".$reqData['member_code']."' and sale_type = 'ing'");
        $arrMember['product']['ing'] = $arrProduct['count'];

        $arrProduct = $this->objDBH->getRow("select count(*) as count from product where member_code='".$reqData['member_code']."' and sale_type = 'done'");
        $arrMember['product']['done'] = $arrProduct['count'];

        return $arrMember;
    }

    // 정보 가져오기
    function info($reqData) {
        $arrReturn = $this->objDBH->getRow("select * from ".$this->table." where code='".$reqData['member_code']."'");
        $arrReturn['mobile'] = divMobile($arrReturn['mobile']);
        return $arrReturn;
    }

    // 리스트 가져오기
    function lists($reqData) {
        $add_where = !empty($reqData['keyword']) ? " where ".$reqData['field']." like '%".$reqData['keyword']."%'" : "";
        $arrReturn['list_query'] = "select code,id,name,mobile,addr,addr_etc,level,status,date_format(reg_date,'%Y/%m/%d %H:%i') as reg_date from ".$this->table.$add_where." order by code desc";
        return $arrReturn;
    }

    // 푸시알림 발송 내역 가져오기
    function listPush($reqData) {
        $add_where = !empty($reqData['keyword']) ? " and ".$reqData['field']." like '%".$reqData['keyword']."%'" : "";
        $arrReturn['list_query'] = "select code,send_count,product_code,content,date_format(reg_date,'%Y/%m/%d %H:%i') as reg_date from push where account_code='".$reqData['account_code']."'".$add_where." order by code desc";
        return $arrReturn;
    }

    // 푸시알림 발송하기
    function sendPush($reqData) {
        include _CLASS_DIR."/class.UtilPush.php";
        $objPush = new UtilPush();

        $add_where = !empty($reqData['keyword']) ? " and ".$reqData['field']." like '%".$reqData['keyword']."%'" : "";
        $reqData['content'] = str_replace("\\r\\n", "\n", $reqData['content']);
        $arrMember = $this->objDBH->getRows("select code,os,token_id,name from member where account_code='".$reqData['account_code']."' and status='y' and is_push='y'".$add_where);
        foreach($arrMember['list'] as $key => $val) {
            if ($val['token_id'] != "") {
                switch($val['os']) {
                    case "android":
                    $objPush->_send_android($val['token_id'], $reqData['content'], $reqData['product_code']);
                    break;

                    case "ios":
                    $objPush->_send_ios($val['token_id'], $reqData['content'], $reqData['product_code']);
                    break;
                }
            }
        }

        $arrParam = array (
            'account_code'  => $reqData['account_code'],
            'send_count'    => $arrMember['total'],
            'product_code'  => $reqData['product_code'],
            'content'       => $reqData['content'],
            'reg_date'      => "now()"
        );
        $this->objDBH->insert("push", $arrParam);
    }

    // 파일체크
	function _checkFile() {
		$this->setFiles();
        if ($_FILES['image']['name']) {	// 파일첨부시
            $arrResultFile = $this->checkFile("Img","image");
            if($arrResultFile['status'] == "FAIL") {
                putJSMessage("[".$arrResultFile['message']."]","back");
                exit;
            }
            else {
                $this->strFileName = strtolower($_FILES['image']['name']);
            }
        }
	}

	// 파일첨부
	function _uploadFile($reqData) {
        if ($this->strFileName != '') {
            $this->uploadFile("image",_USER_DIR."/member/".$reqData['member_code']);
		}
	}

    // 회원가입
	function insertMember($reqData) {
        $arrParam = array (
            'id'            => $reqData['id'],
            'password'      => getHash($reqData['password']),
            'name'          => $reqData['name'],
            'sex'           => @$reqData['sex'],
            'birthday'      => @$reqData['birthday'],
            'email'         => $reqData['email'],
            'mobile'        => $reqData['mobile'],
            'tel'           => @$reqData['tel'],
            'zipcode'       => $reqData['zipcode'],
            'addr'          => $reqData['addr'],
            'addr_etc'      => $reqData['addr_etc'],
            'is_receive_email'  => @$reqData['is_receive_email'],
            'is_receive_sms'    => @$reqData['is_receive_sms'],
            'reg_date'      => "now()"
        );

        $this->objDBH->insert($this->table, $arrParam);
        $code = $this->objDBH->getLastId();
        $reqData['member_code'] = $code;

        return $code;
	}

    // 회원수정
	function updateMember($reqData) {
        $arrParam = array (
            'name'          => $reqData['name'],
            'sex'           => @$reqData['sex'],
            'birthday'      => @$reqData['birthday'],
            'email'         => $reqData['email'],
            'mobile'        => $reqData['mobile'],
            'tel'           => @$reqData['tel'],
            'zipcode'       => $reqData['zipcode'],
            'addr'          => $reqData['addr'],
            'addr_etc'      => $reqData['addr_etc'],
            'is_receive_email'  => @$reqData['is_receive_email'],
            'is_receive_sms'    => @$reqData['is_receive_sms'],
            'update_date'   => "now()"
        );

        // 비번 변경시
        if (!empty($reqData['current_password'])) {
            $arrMember = $this->objDBH->getRow("select password from ".$this->table." where code='".$reqData['member_code']."' and status='y'");
            if ($arrMember['password'] != getHash($reqData['current_password'])) {
                returnData(_API_FAIL, "기존 비밀번호가 일치하지 않습니다.");
            }
            // 비밀번호 입력 체크
            if (empty($reqData['password'])) returnData(_API_FAIL, "비밀번호가 입력되지 않았습니다.");
            // 비밀번호 자리수 체크
            if ($reqData['password'] != $reqData['password_confirm']) returnData(_API_FAIL, "비밀번호가 일치하지 않습니다.");
            $arrParam = array_merge($arrParam, array("password" => getHash($reqData['password'])));
        }

        $arrWhere = array(
            'code'  => $reqData['member_code']
        );
        $this->objDBH->update($this->table, $arrParam, $arrWhere);

        return $reqData['member_code'];
	}

    // 임시비번 생성
	function createTmpPassword($id) {
		// 이미발행된 인증번호 있는지 확인 (1일 이내만 유효)
        $arrData = $this->objDBH->getRow("select password from member_tmp_password where id='".$id."' and status='n' and date_add(reg_date,interval 1 day) > now()");
		if ($arrData['password']) {
			return $arrData['password'];
		}
		else {  // 정보가 없을때 (insert)
			$tmp_password = rand(100000,999999); // 6자리 임시 비밀번호 생성
			$arrParam = array (
                'id' => $id,
                'password' => $tmp_password,
                'reg_date' => 'now()'
            );
            $this->objDBH->insert("member_tmp_password", $arrParam);
			return $tmp_password;
		}
	}

	// 회원 아이디 찾기
	function findId($reqData) {
        checkParam($reqData['email'], "email");

        $arrMember = $this->objDBH->getRow("select id,email from ".$this->table." where name='".$reqData['name']."' and email='".$reqData['email']."' and status='y'");
		if ($arrMember['id']) {
            return $arrMember;
        }
		else {
            returnData(_API_FAIL, "입력하신 정보와 일치하는 회원이 없습니다.");
		}
	}

    // 회원 Password 찾기
	function findPassword($reqData) {
		checkParam($reqData['id'], "id");

        $arrMember = $this->objDBH->getRow("select id,email from ".$this->table." where id='".$reqData['id']."' and name='".$reqData['name']."' and email='".$reqData['email']."' and status='y'");
		if ($arrMember['id']) {
            // 임시 비번 생성
            $arrMember['tmp_password'] = $this->createTmpPassword($reqData['id']);

            return $arrMember;
        }
		else {
            returnData(_API_FAIL, "입력하신 정보와 일치하는 회원이 없습니다.");
		}
	}

    // 회원정보 수정 (Admin)
	function updateAdmin($reqData) {
        $arrParam = array (
            'name'          => $reqData['name'],
            'email'         => $reqData['email'],
            'mobile'        => $reqData['mobile'],
            'zipcode'       => $reqData['zipcode'],
            'addr'          => $reqData['addr'],
            'addr_etc'      => $reqData['addr_etc'],
            'memo'          => $reqData['memo'],
            'level'         => $reqData['level'],
            'status'        => $reqData['status'],
            'update_date'   => "now()"
        );

        // 비번 변경시
        if (!empty($reqData['password'])) {
            // 비밀번호 자리수 체크
            if ($reqData['password'] != $reqData['password_confirm']) returnData(_API_FAIL, "비밀번호가 일치하지 않습니다.");
            $arrParam = array_merge($arrParam, array("password" => getHash($reqData['password'])));
        }

        $arrWhere = array(
            'code'  => $reqData['member_code']
        );
        $this->objDBH->update($this->table, $arrParam, $arrWhere);

        return $reqData['member_code'];
	}

    /************************* 등급 관리 *************************/
    // 등급 리스트 가져오기
	function listLevel() {
        $arrData = $this->objDBH->getRows("select * from member_level order by level");
        $arrReturn['arrLevel'] = $arrData['list'];

        $arrData = $this->objDBH->getRows("select level,count(*) as count from member group by level");
        if (!empty($arrData['list'])) {
            foreach($arrData['list'] as $key => $val) {
                $arrTmp[$val['level']] = $val['count'];
            }
        }
        $arrReturn['arrMember'] = $arrTmp;

        return $arrReturn;
	}

    // 등급 수정하기
    function updateLevel($reqData) {
        for($i=1; $i<=5; $i++) {
            $arrParam = array (
                'title' => $reqData['title'.$i],
            );
            $arrWhere = array (
                'level' => $i
            );
            $this->objDBH->update("member_level", $arrParam, $arrWhere);
        }
        return "udpate completed";
    }

    /************************* 약관 *************************/
    // 약관 가져오기
	function getContract() {
        $arrData = $this->objDBH->getRow("select * from contract");
        return $arrData;
	}

    // 약관 수정하기
    function updateContract($reqData) {
        $arrParam = array (
            'provision'     => $reqData['provision'],
            'privacy'       => $reqData['privacy'],
            'distinguish'   => @$reqData['distinguish'],
            'personal'      => @$reqData['personal'],
            'investment'    => @$reqData['investment'],
            'dealing'       => @$reqData['dealing'],
            'pincipes'      => @$reqData['pincipes']
        );
        $arrWhere = array (
            'code' => 1
        );
        $this->objDBH->update("contract", $arrParam, $arrWhere);
        return "udpate completed";
    }

    /************************* 정보 관리 *************************/
    // 정보 가져오기
	function getInfo() {
        $arrData = $this->objDBH->getRow("select * from info");
        return $arrData;
	}

    // 정보 수정하기
    function updateInfo($reqData) {
        $arrParam = array (
            'bankruptcy_rate'       => $reqData['bankruptcy_rate'],
            'total_repayment_amount'=> $reqData['total_repayment_amount']
        );
        $arrWhere = array (
            'code' => 1
        );
        $this->objDBH->update("info", $arrParam, $arrWhere);
        return "udpate completed";
    }

    /************************* 본인인증 *************************/
    // NICE 본인인증
    function checkPlus() {
        $sitecode = _SITE_CODE;     // NICE로부터 부여받은 사이트 코드
        $sitepasswd = _SITE_PASSWD; // NICE로부터 부여받은 사이트 패스워드

        $authtype = "";     // 없으면 기본 선택화면, X: 공인인증서, M: 핸드폰, C: 카드
        $popgubun 	= "N";  // Y : 취소버튼 있음 / N : 취소버튼 없음
        $customize 	= "";   // 없으면 기본 웹페이지 / Mobile : 모바일페이지
        $reqseq = "REQ_0123456789";     // 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로

        $reqseq = get_cprequest_no($sitecode);

        $returnurl = _HOME_URL."/member/checkplus_success.php";	// 성공시 이동될 URL
        $errorurl = _HOME_URL."/member/checkplus_fail.php";		// 실패시 이동될 URL

        $_SESSION["REQ_SEQ"] = $reqseq;

        // 입력될 plain 데이타를 만든다.
        $plaindata =  "7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
                      "8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
                      "9:AUTH_TYPE" . strlen($authtype) . ":". $authtype .
                      "7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
                      "7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
                      "11:POPUP_GUBUN" . strlen($popgubun) . ":" . $popgubun .
                      "9:CUSTOMIZE" . strlen($customize) . ":" . $customize ;

        $enc_data = get_encode_data($sitecode, $sitepasswd, $plaindata);

        if($enc_data == -1) {
            $returnMsg = "암/복호화 시스템 오류입니다.";
            $enc_data = "";
        }
        else if($enc_data== -2) {
            $returnMsg = "암호화 처리 오류입니다.";
            $enc_data = "";
        }
        else if($enc_data== -3) {
            $returnMsg = "암호화 데이터 오류 입니다.";
            $enc_data = "";
        }
        else if($enc_data== -9) {
            $returnMsg = "입력값 오류 입니다.";
            $enc_data = "";
        }

        if ($enc_data == "") {
            putJSMessage($returnMsg);
            exit;
        }
        else {
            return $enc_data;
        }
    }


    /* 하위 메뉴 구현하기 */
    // 회원탈퇴
	function withdrawMember($reaData) {
		$query = "update ".$this->table."
		set status='n'
		where code in (".$reaData['code'].")";
		$this->objDBH->query($query);

        $this->arrQuery[] = $query;

        $query = "insert into member_withdraw (id,reason,ip,reg_date) values('".$reaData['id']."','".$reaData['reason']."','".getenv("REMOTE_ADDR")."',now())";
		$this->objDBH->query($query);

		$this->arrQuery[] = $query;
	}

	// 회원정보 영구삭제 (Admin)
	function deleteAdminMember($code) {
		$query = "delete from ".$this->table." where code in (".$code.")";
		$this->objDBH->query($query);

		$this->arrQuery[] = $query;
	}
}
?>