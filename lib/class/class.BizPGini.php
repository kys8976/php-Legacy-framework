<?php
// 결제 클래스 (INICIS)
class BizPGini {
	private $netCancel;
	private $authMap;
	private $httpUtil;

	function __construct($obj) {
		$this->objDBH = $obj;
	}
/*
	function initVariable() {	// 초기 셋팅 (금액 위변조 여부 체크 변수셋팅)
		$_SESSION['CHECK_PRICE'] = skipComma($this->objParent->reqData['total_price']);
	}
*/

	function getScriptFile() {
        if (_PG_MODE == "real") return "https://stdpay.inicis.com/stdjs/INIStdPay.js";  // real
        else return "https://stgstdpay.inicis.com/stdjs/INIStdPay.js";                  // dev
	}

	function startPG() {
		return "
        formPay.merchantData.value = $('#formPay :input').serialize();
        INIStdPay.pay('formPay');";
	}

	function getStartScript() {
		$content = "
		function setParams() {
            <!--Card,DirectBank,HPP,Vbank,kpay,Swallet,Paypin,EasyPay,PhoneBill,GiftCard,EWallet-->
            switch(payment_type) {
                case 'card':
                payment_type = 'Card';
                break;

                case 'vacc':
                payment_type = 'Vbank';
                break;

                default:
                break;
            }
            formPay.gopaymethod.value = payment_type;
            formPay.buyertel.value = formPay.receiver_mobile1.value+'-'+formPay.receiver_mobile2.value+'-'+formPay.receiver_mobile3.value;
        }";
		return $content;
	}

	function getHiddenValues($reqData) {
        require_once(_CLASS_DIR.'/pg/'._PG.'/libs/INIStdPayUtil.php');
        $SignatureUtil = new INIStdPayUtil();

        //인증
        $signKey = _PG_KEY;  // 가맹점에 제공된 웹 표준 사인키(가맹점 수정후 고정)
        $timestamp = $SignatureUtil->getTimestamp();    // util에 의해서 자동생성
        $orderNumber = getOrderNumber();                // 가맹점 주문번호(가맹점에서 직접 설정)
        $cardNoInterestQuota = "11-2:3:,34-5:12,14-6:12:24,12-12:36,06-9:12,01-3:4";// 카드 무이자 여부 설정(가맹점에서 직접 설정)
        $cardQuotaBase = "2:3:4:5:6:11:12:24:36";                                   // 가맹점에서 사용할 할부 개월수 설정
        $mKey = $SignatureUtil->makeHash($signKey, "sha256");
        $price = skipComma($reqData['price']);
		$pay_due_day = date('Ymd',time()+(3600*24*_PAY_DUE_DAY_));                // 가상계좌입금기한 신청일+ _DUE_DAY_일

        $params = array(
            "oid" => $orderNumber,
            "price" => $price,
            "timestamp" => $timestamp
        );
        $sign = $SignatureUtil->makeSignature($params, "sha256");
        $siteDomain = "http://".$_SERVER['HTTP_HOST']."/html/product/"._PG; //가맹점 도메인 입력

        $content = '
        <input type="hidden" name="version" value="1.0">
        <input type="hidden" name="mid" value="'._PG_SHOP_ID.'">
        <input type="hidden" name="goodname" value="'.$reqData['goodname'].'">
        <input type="hidden" name="oid" value="'.$orderNumber.'">
        <input type="hidden" name="price" value="'.skipComma($price).'">
        <input type="hidden" name="currency" value="WON">
        <input type="hidden" name="timestamp" value="'.$timestamp.'">
        <input type="hidden" name="signature" value="'.$sign.'">
        <input type="hidden" name="returnUrl" value="http://'.$_SERVER['HTTP_HOST'].'/index.php?tpf=product/buy_process">
        <input type="hidden" name="mKey" value="'.$mKey.'">
        <input type="hidden" name="offerPeriod" value="">
        <input type="hidden" name="acceptmethod" value="HPP(1):no_receipt:va_receipt:vbanknoreg(0):vbank('.$pay_due_day.'):below1000">
        <input type="hidden" name="languageView" value="">
        <input type="hidden" name="charset" value="">
        <input type="hidden" name="payViewType" value="">
        <input type="hidden" name="closeUrl" value="'.$siteDomain.'/close.php">
        <input type="hidden" name="popupUrl" value="'.$siteDomain.'/popup.php">
        <input type="hidden" name="nointerest" value="'.$cardNoInterestQuota.'">
        <input type="hidden" name="quotabase" value="'.$cardQuotaBase.'">
        <input type="hidden" name="vbankRegNo" value="">
        <input type="hidden" name="gopaymethod">    <!-- 결제방법 -->

        <!-- 추가정보 -->
		<input type=hidden name="product_price" value="'.skipComma($reqData['product_price']).'">   <!-- 상품가격 (배송료 제외) -->
		<input type=hidden name="delivery_price" value="'.skipComma($reqData['delivery_price']).'"> <!-- 배송료 -->
		<input type="hidden" name="buyername" value="'.$reqData['member']['name'].'">               <!-- 주문자정보 -->
        <input type="hidden" name="buyertel" value="'.$reqData['member']['mobile'].'">
        <input type="hidden" name="buyeremail" value="'.$reqData['member']['email'].'">
        <input type="hidden" name="merchantData">';

		return $content;
	}

	function payPG() {
		require_once(_CLASS_DIR.'/pg/'._PG.'/libs/INIStdPayUtil.php');
        require_once(_CLASS_DIR.'/pg/'._PG.'/libs/HttpClient.php');
        $util = new INIStdPayUtil();
        $arrResult = array();

        // payment 코드
        $arrPaymethodCode = array(
            "VCard" => "card",          // 카드
            "Card"	=> "card",          // 카드
            "DirectBank"	=> "tran",  // 계좌이체
            "HPP"	=> "hp",            // 휴대폰
            "VBank"	=> "vacc"           // 가상계좌
        );

        // 카드사 코드
        $arrCardCode = array(
            "01" => "외환",
            "03" => "롯데",
            "04" => "현대",
            "06" => "국민",
            "11" => "BC",
            "12" => "삼성",
            "14" => "신한",
            "15" => "한미",
            "16" => "NH",
            "17" => "하나카드",
            "21" => "해외비자",
            "22" => "해외마스터",
            "23" => "JCB",
            "24" => "해외아멕스",
            "25" => "해외다이너스"
        );

        try {
            /* 인증이 성공일 경우만 */
            if (strcmp("0000", $_REQUEST["resultCode"]) == 0) {
                /* 1.전문 필드 값 설정(***가맹점 개발수정***) */
                $mid = $_REQUEST["mid"];     // 가맹점 ID 수신 받은 데이터로 설정
                $signKey = "SU5JTElURV9UUklQTEVERVNfS0VZU1RS"; // 가맹점에 제공된 키(이니라이트키) (가맹점 수정후 고정) !!!절대!! 전문 데이터로 설정금지
                $timestamp = $util->getTimestamp();   // util에 의해서 자동생성
                $charset = "UTF-8";        // 리턴형식[UTF-8,EUC-KR](가맹점 수정후 고정)
                $format = "JSON";        // 리턴형식[XML,JSON,NVP](가맹점 수정후 고정)

                $authToken = $_REQUEST["authToken"];   // 취소 요청 tid에 따라서 유동적(가맹점 수정후 고정)
                $authUrl = $_REQUEST["authUrl"];    // 승인요청 API url(수신 받은 값으로 설정, 임의 세팅 금지)
                $this->netCancel = @$_REQUEST["netCancel"];   // 망취소 API url(수신 받은f값으로 설정, 임의 세팅 금지)
                ///$mKey = $util->makeHash(signKey, "sha256"); // 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)
                $mKey = hash("sha256", $signKey);

                /* 2.signature 생성 */
                $signParam["authToken"] = $authToken;  // 필수
                $signParam["timestamp"] = $timestamp;  // 필수
                $signature = $util->makeSignature($signParam);

                /* 3.API 요청 전문 생성 */
                $this->authMap["mid"] = $mid;   // 필수
                $this->authMap["authToken"] = $authToken; // 필수
                $this->authMap["signature"] = $signature; // 필수
                $this->authMap["timestamp"] = $timestamp; // 필수
                $this->authMap["charset"] = $charset;  // default=UTF-8
                $this->authMap["format"] = $format;  // default=XML

                try {
                    $this->httpUtil = new HttpClient();

                    /* 4.API 통신 시작 */
                    $authResultString = "";
                    if ($this->httpUtil->processHTTP($authUrl, $this->authMap)) {
                        $authResultString = $this->httpUtil->body;
                    } else {
                        echo "Http Connect Error\n".$this->httpUtil->errormsg;
                        throw new Exception("Http Connect Error");
                    }

                    /* 5.API 통신결과 처리(***가맹점 개발수정***) */
                    $resultMap = json_decode($authResultString, true);

                    if (strcmp("0000", $resultMap["resultCode"]) == 0) {    // 결제성공
                        // DB 처리
                        switch($resultMap['payMethod']) {
                            case "Card":				// 신용카드(안심클릭)
                            case "VCard":				// 신용카드(ISP)
                            case "DirectBank":
                            $payment_status = "pay_done";         // 결제상태
                            $pay_valid_date = NULL;                     // 입금만료일자
                            $objTime = new DateTime(@$resultMap['applDate'].@$resultMap['applTime']);
                            $pay_date = $objTime->format('Y-m-d H:i:s');// 결제일
                            break;

                            case "VBank":
                            $payment_status	= "pay_ready";              // 결제상태
                            $objTime = new DateTime(@$resultMap['VACT_Date'].@$resultMap['VACT_Time']);
                            $pay_valid_date = $objTime->format('Y년 m월 d일 H:i');  // 입금만료일자
                            $pay_date = NULL;                           // 결제일
                            break;

                            default:
                            putJSMessage('잘못된 경로로 들어왔습니다.');
                            exit;
                            break;
                        }
                        $result = "y";

                        // 결제결과 return
                        $arrResult['total_price'] = trim($resultMap['TotPrice']);	// 결제금액
                        $arrResult['result'] = $result;										// 결제성공여부
                        $arrResult['result_msg'] = $resultMap['resultMsg'];			// 결제결과
                        $arrResult['payment_type'] = $arrPaymethodCode[$resultMap['payMethod']];// 결제종류
                        $arrResult['order_number'] = $resultMap['MOID'];	// 주문번호
                        $arrResult['tno'] = $resultMap['tid'];			// PG 거래번호

                        $arrResult['card_name'] = @$arrCardCode[@$resultMap['CARD_Code']];	// 신용카드사명
                        $arrResult['card_number'] = @$resultMap['CARD_Num'];				// 신용카드번호
                        $arrResult['app_time'] = @$resultMap['applDate']." ".@$resultMap['applTime'];
                        $arrResult['app_no'] = @$resultMap['applNum'];	// 결제승인번호
                        $arrResult['quota'] = @$resultMap['CARD_Quo'];	// 카드할부기간

                        // 가상계좌 변수
                        $arrResult['bank_name'] = @$resultMap['vactBankName'];  // 은행명
                        $arrResult['account'] = @$resultMap['VACT_Num'];		// 입금계좌번호
                        $arrResult['depositor'] = @$resultMap['VACT_Name'];     // 예금주
                        $arrResult['pay_name'] = @$resultMap['VACT_InputName'];	// 입금자명
                        $arrResult['pay_valid_date'] = $pay_valid_date;          // 입금만료일자
                        $arrResult['pay_date'] = $pay_date;						// 결제일
                        $arrResult['payment_status'] = $payment_status;							// 결제상태
                    } else {    // 결제실패
                        $pay_date = "'NULL'";			// 결제일
			            $payment_status = "pay_fail";
			            $result = "n";
                    }
                } catch (Exception $e) {    // 결제 실패
                    $s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
                    // echo $s;
                    $this->cancelPG();      // 망취소
                }
            } else {
                /* 인증 실패시 */
                echo "<pre>" . var_dump($_REQUEST) . "</pre>";
            }
        } catch (Exception $e) {
            $s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
            echo $s;
        }

        // 로고 저장
        $log = '';
		foreach($_POST as $key => $val) {
            switch($key) {
                case "merchantData":    // 상품정보
                parse_str($val, $arrMerchantData); // 추가정보 parsing
                foreach($arrMerchantData as $key2 => $val2) {
                    $log .= "[".$key2."] = ".$val2."\n";
                }
                break;

                case "authToken":       // authtoken 저장 안함
                break;

                default:                // 그 외 나머지는 저장
                $log .= "[".$key."] = ".$val."\n";
                break;
            }
		}
		$fp=fopen(_CLASS_DIR."/pg/log_pg.txt","a"); fwrite($fp,$log."\n\n");

		return $arrResult;
	}

	function cancelPG() {	// DB연동 실패 or 결제금액 위변조시 강제취소
		/* 망취소 API */
        $netcancelResultString = ""; // 망취소 요청 API url(고정, 임의 세팅 금지)
        $this->httpUtil = new HttpClient();
        if ($this->httpUtil->processHTTP($this->netCancel, $this->authMap)) {
            $netcancelResultString = $this->httpUtil->body;
        } else {
            echo "Http Connect Error\n".$this->httpUtil->errormsg;
            throw new Exception("Http Connect Error");
        }

        echo "## 망취소 API 결과 ##";
        $netcancelResultString = str_replace("<", "&lt;", $$netcancelResultString);
        $netcancelResultString = str_replace(">", "&gt;", $$netcancelResultString);
        echo "<pre>", $netcancelResultString . "</pre>";
	}

	function getEndScript() {
	}
}
?>