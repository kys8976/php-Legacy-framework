<?
// phpinfo();
include_once("../lib/config/config.php");
include _CLASS_DIR."/bizprocess/payment/class.Payment.php";
include _CLASS_DIR."/bizprocess/point/class.Point.php";

$page = new Page;
$objPayment = new Payment($page->objDBH);
$objPoint = new Point($page->objDBH);

@extract($_GET);
@extract($_POST);
@extract($_SERVER);

$TEMP_IP = getenv("REMOTE_ADDR");
$PG_IP  = substr($TEMP_IP,0, 10);

/*
// 테스트
$PG_IP = "203.238.37";
$no_oid	= "100503_231758451";
$no_vacct = "56202383195438";
$amt_input= "5860";
*/
// 2010/5/3 신한은행 경우 11:55 분경에 결제하니 개설기간 업무종료 상태라고 나옴.

if( $PG_IP == "203.238.37" || $PG_IP == "210.98.138" ) {	//PG에서 보냈는지 IP로 체크
	$msg_id = $msg_id;             //메세지 타입
	$no_tid = $no_tid;             //거래번호
	$no_oid = $no_oid;             //상점 주문번호
	$id_merchant = $id_merchant;   //상점 아이디
	$cd_bank = $cd_bank;           //거래 발생 기관 코드
	$cd_deal = $cd_deal;           //취급 기관 코드
	$dt_trans = $dt_trans;         //거래 일자
	$tm_trans = $tm_trans;         //거래 시간
	$no_msgseq = $no_msgseq;       //전문 일련 번호
	$cd_joinorg = $cd_joinorg;     //제휴 기관 코드

	$dt_transbase = $dt_transbase; //거래 기준 일자
	$no_transeq = $no_transeq;     //거래 일련 번호
	$type_msg = $type_msg;         //거래 구분 코드
	$cl_close = $cl_close;         //마감 구분코드
	$cl_kor = $cl_kor;             //한글 구분 코드
	$no_msgmanage = $no_msgmanage; //전문 관리 번호
	$no_vacct = $no_vacct;         //가상계좌번호
	$amt_input = eregi_replace("^0","",$amt_input);       //입금금액
	$amt_check = $amt_check;       //미결제 타점권 금액
	$nm_inputbank = $nm_inputbank; //입금 금융기관명
	$nm_input = $nm_input;         //입금 의뢰인
	$dt_inputstd = $dt_inputstd;   //입금 기준 일자
	$dt_calculstd = $dt_calculstd; //정산 기준 일자
	$flg_close = $flg_close;       //마감 전화

	$logfile = fopen("vaccount.log", "a+");
	fwrite( $logfile,"ID_MERCHANT : ".$id_merchant."\r\n");
	fwrite( $logfile,"NO_TID : ".$no_tid."\r\n");
	fwrite( $logfile,"NO_OID : ".$no_oid."\r\n");
	fwrite( $logfile,"NO_VACCT : ".$no_vacct."\r\n");
	fwrite( $logfile,"AMT_INPUT : ".$amt_input."\r\n");
	fwrite( $logfile,"NM_INPUTBANK : ".$nm_inputbank."\r\n");
	fwrite( $logfile,"NM_INPUT : ".$nm_input."\r\n\r\n");
	fclose( $logfile );

	// tno 및 금액 비교
	$arrVaccountHistory = getFieldInfo("vaccount_history","code,member_code,payment_code,total_price,account,status","order_number='".$no_oid."'");
	$arrPayment = getFieldInfo("payment","use_point","code='".$arrVaccountHistory['payment_code']."'");

	if ($arrVaccountHistory['account'] == $no_vacct and $arrVaccountHistory['total_price'] == $amt_input and $arrVaccountHistory['status'] == "ready") {	// 계좌, 가격비교
		$page->objDBH->query("begin");
		// payment 수정
		$objPayment->updatePayment($arrVaccountHistory['payment_code'],"delivery_ready","n");

		// vaccount_history 수정
		$arrReqData = "";
		$arrReqData['code'] = $arrVaccountHistory['code'];
		$arrReqData['status'] = "paid";
		$objPayment->updateVaccountHistory($arrReqData);

		// point 충전
		$arrReqData = "";
		$arrReqData['member_code'] = $arrVaccountHistory['member_code'];
		$arrReqData['payment_code'] = $arrVaccountHistory['payment_code'];
		$arrReqData['total_price'] = $arrVaccountHistory['total_price'];
		$arrReqData['use_point'] = $arrPayment['use_point'];
		$arrOrderList = $objPayment->getOrderList($arrVaccountHistory['payment_code']);
		$arrReqData['cart'] = $arrOrderList['list'];
		$arrReqData['goodname'] = $objPayment->getGoodname($arrVaccountHistory['payment_code']);
		$objPoint->setPointHistory($arrReqData);							// point_history
		$objPoint->updateMemberPoint($arrVaccountHistory['member_code']);// member update point

		if(@in_array(false,array_values($objPayment->arrDBResult))) {
			$page->objDBH->query("rollback");
		}
		else {
			$page->objDBH->query("commit");
			$objPayment->setEmail($arrVaccountHistory['payment_code'],3);	// email 발송(배송대기(입금확인))
			$objPayment->setSms($arrVaccountHistory['payment_code'],16);		// sms 발송(입금 확인)

			makeLog("입금확인",$objPayment->arrQuery);
			echo "OK";	// true 응답
		}
	}
	else {
		// 처리 실패
	}
}
?>