<?php
/**
 * Payment Class
 *
 */
// include_once _CLASS_DIR."/class.NoticeSend.php";
// class BizPGPayment extends NoticeSend{
class BizPGPayment {
	private $objDBH;
    private $table = 'payment';

	function __construct($obj) {
		$this->objDBH = $obj;
	}

    // 결제금액 위변조 검사
	function checkValidPrice($arrReqData, $objCart) {
        $arrCartInfo = $objCart->getCart();	// 장바구니 정보 가져오기
        if ($arrReqData['total_price'] != skipComma($arrCartInfo['total_price'])) return false;	// 상품금액(장바구니) 검사
        return true;
	}

    // 주문내역 가져오기 (관리자)
    function lists($reqData) {
        $add_where = "";
        if(!empty($reqData['keyword'])) $add_where .= " and ".$reqData['field']." like '%".$reqData['keyword']."%'";
        if(!empty($reqData['payment_status'])) $add_where .= " and p.payment_status = '".$reqData['payment_status']."'";
        if(!empty($reqData['payment_type'])) $add_where .= " and p.payment_type = '".$reqData['payment_type']."'";

        $arrReturn['list_query'] = "select p.*,date_format(p.order_date,'%Y/%m/%d %H:%i') as order_date,m.name,m.mobile from ".$this->table." p, member m where p.member_code=m.code".$add_where." order by code desc";
        $arrReturn['callback'] = "lists_callback";
        return $arrReturn;
    }

    // 주문내역 가져오기 callback 함수
    function lists_callback($reqData) {
        $current_date = date("Y-m-d");
        if (!empty($reqData['list'])) {
            foreach($reqData['list'] as $key => $val) {
                $reqData['list'][$key]['mobile'] = divMobile($val['mobile']);
                $reqData['list'][$key]['total_price'] = number_format($val['total_price']);
            }
        }
        return $reqData;
    }

    // 정보 가져오기
    function info($reqData) {
        $arrMyConfig = getCFG("MyConfig");
        $arrPaymentType = $arrMyConfig['PaymentType'];
        $arrPaymentStatus = $arrMyConfig['PaymentStatus'];

        // 결제정보
        $arrReturn = $this->objDBH->getRow("select p.*,m.name,m.mobile from ".$this->table." p, member m where p.code='".$reqData['code']."' and p.member_code=m.code");
        // $arrReturn['order_mobile'] = divMobile($arrReturn['order_mobile']);
        $arrReturn['total_price'] = number_format($arrReturn['total_price']);
        $arrReturn['receiver_mobile'] = divMobile($arrReturn['receiver_mobile']);
        $arrReturn['payment_type'] = $arrReturn['payment_type'];
        $arrReturn['payment_type_name'] = $arrPaymentType[$arrReturn['payment_type']];

        // 주문정보
        $arrReturn['order_list'] = $this->objDBH->getRows("select * from order_list where payment_code='".$reqData['code']."'");
        return $arrReturn;
    }

    // 결제정보 삭제
	function deletePayment($code) {
        // 주문취소 여부확인
		$is_valid = true;
		$arrData = $this->objDBH->getRows("select payment_status from ".$this->table." where code in (".$code.")");
        foreach($arrData['list'] as $key => $val) {
            if ($val['payment_status'] != "order_cancel") $is_valid = false;
        }

		if ($is_valid == true) {
            $query = "delete from ".$this->table." where code in (".$code.")";
    		$this->objDBH->query($query);
		}
		else {
			putJSMessage("주문취소 상태건만 삭제가 가능합니다.");
			exit;
		}
	}

    // 메모 저장
	function updateMemo($reqData) {
        $arrParam = array (
            'memo' => $reqData['memo']
        );
        $arrWhere = array (
            'code' => $reqData['payment_code']
        );
        $this->objDBH->update($this->table, $arrParam, $arrWhere);
    }

    // 결제정보 변경
	function updatePayment($reqData) {
        $arrMyConfig = getCFG("MyConfig");
        $arrPaymentStatus = $arrMyConfig['PaymentStatus'];

        $arrParam = array (
            'delivery_code' => $reqData['delivery_code'],
            'payment_status' => $reqData['payment_status']
        );
        $arrWhere = array (
            'code' => $reqData['payment_code']
        );
        if ($reqData['payment_status'] == "order_cancel") { // 주문 취소일때 주문취소 일자 기록하기
            $arrParam = array_merge($arrParam, array("order_cancel_date" => 'now()'));
		}
        if (!empty($reqData['is_customer'])) {              // 고객이 주문취소 할경우
            $arrWhere = array_merge($arrWhere, array("member_code" => $reqData['member_code']));
        }
        $this->objDBH->update($this->table, $arrParam, $arrWhere);


        /*
        // app 소유여부 확인 로직 넣은후 구현하기
        // 푸시알림 or SMS 보내기
        include _CLASS_DIR."/class.UtilPush.php";
        $objPush = new UtilPush();

        $arrPayment = $this->objDBH->getRow("select member_code from payment where code='".$reqData['code']."'");

        $arrMember = $this->objDBH->getRow("select code,os,token_id,name from member where code='".$arrPayment['member_code']."' and is_push='y'");
        if ($arrMember['token_id'] != "") {
            $content = "[".$arrMember['name']."]님의 주문이 [".$arrPaymentStatus[$reqData['payment_status']]."] 처리 되었습니다.";

            // push table 저장
            $arrParam = array (
                'member_code'   => $arrPayment['member_code'],
                'send_count'    => 1,
                'type'          => 'payment',
                'link_code'     => $reqData['code'],
                'message'       => $content,
                'reg_date'      => "now()"
            );
            $this->objDBH->insert("push", $arrParam);

            $pushData = array (
                'message'   => $content,
                'type'      => 'payment',
                'link_code' => $reqData['code'] // payment code
            );
            switch($arrMember['os']) {
                case "android":
                $objPush->_send_android($arrMember['token_id'], $pushData);
                break;

                case "ios":
                $objPush->_send_ios($arrMember['token_id'], $pushData);
                break;
            }
        }
        */
	}

    // 무통장 정보 가져오기
    function getBankInfo() {
        $arrData = $this->objDBH->getRow("select bank_account,bank_name,bank_depositor,bank_pay_limit from master");
        return $arrData;
    }

    // 배송비 계산
    function calDeliveryPrice($product_price) {
        $arrData = $this->objDBH->getRow("select delivery_limit,delivery_price from master");
        if ($arrData['delivery_limit'] > $product_price) return $arrData['delivery_price'];
        else return 0;
    }

    // 결제정보 저장
	function setPayment($reqData) {
        checkParam($reqData['member_code'], "member_code");
        checkParam($reqData['payment_type'], "payment_type");
        checkParam($reqData['receiver_name'], "receiver_name");
        checkParam($reqData['receiver_mobile'], "receiver_mobile");
        checkParam($reqData['receiver_addr'], "receiver_addr");

        // 결제정보 입력
        $arrParam = array (
            'member_code'       => $reqData['member_code'],
            'non_member_code'   => @$reqData['non_member_code'],
            'payment_type'      => $reqData['payment_type'],
            'payment_status'    => $reqData['payment_status'],
            'order_number'      => $reqData['order_number'],
            'tno'               => @$reqData['tno'],
            'card_name'         => @$reqData['card_name'],
            'card_number'       => @$reqData['card_number'],
            'bank_account'      => @$reqData['account'],
            'bank_name'         => @$reqData['bank_name'],
            'bank_depositor'    => @$reqData['depositor'],
            'pay_name'          => @$reqData['pay_name'],
            'pay_valid_date'    => @$reqData['pay_valid_date'],
            'total_price'       => $reqData['total_price'],
            'product_price'     => $reqData['product_price'],
            'delivery_price'    => skipComma($reqData['delivery_price']),
            'use_point'         => @$reqData['use_point'],
            'goodname'          => $reqData['goodname'],
            'order_name'        => $reqData['order_name'],
            'order_mobile'      => $reqData['order_mobile'],
            'order_email'       => $reqData['order_email'],
            'receiver_name'     => $reqData['receiver_name'],
            'receiver_zipcode'  => $reqData['receiver_zipcode'],
            'receiver_addr'     => $reqData['receiver_addr'],
            'receiver_mobile'   => $reqData['receiver_mobile'],
            'receiver_email'    => $reqData['receiver_email'],
            'request_message'   => $reqData['request_message'],
            'gift_message'      => @$reqData['gift_message'],
            'order_date'        => "now()",
            'pay_date'          => @$reqData['pay_date']
        );
        $this->objDBH->insert($this->table, $arrParam);
        $payment_code = $this->objDBH->getLastId();

        // 구매내역 입력
        if (count($_COOKIE['cart']) > 0) {
			foreach($_COOKIE['cart'] as $key => $val) {
                $arrProduct = $this->objDBH->getRow("select title,sale_price from product where code='".$val['product_code']."'");
                $price = $arrProduct['sale_price'] * $val['count'];

                if (!empty($val['color'])) $color = $val['color'];
                else $color = '';

				$arrParam = array (
                    'member_code' => $reqData['member_code'],
                    'payment_code' => $payment_code,
                    'product_code' => $val['product_code'],
                    'product_name' => $arrProduct['title'],
                    'color' => $color,
                    'sale_price' => $arrProduct['sale_price'],
                    'count' => $val['count'],
                    'price' => $price,
                    'reg_date' => "now()"
                );
                $this->objDBH->insert("order_list", $arrParam);
			}
		}

        return $payment_code;
	}

    // 결제정보 가져오기
	function getPayment($reqData) {
        $arrResult = $this->objDBH->getRow("select p.*,h.point from ".$this->table." p left join point_history h on (p.code=h.payment_code) where p.code='".$reqData['payment_code']."'");

		return $arrResult;
	}

    // 주문정보 가져오기
	function getOrderList($reqData) {
        $arrOrderList = $this->objDBH->getRows("select o.code as order_list_code,o.product_name,o.color,o.sale_price,o.count,o.point,o.price,p.code from order_list o, product p where o.payment_code='".$reqData['payment_code']."' and o.product_code=p.code");

        if (!empty($arrOrderList['list'])) {
            foreach($arrOrderList['list'] as $key => $val) {
                // 이미지 표출
                if(file_exists(_USER_DIR."/product/".$val['code']."_1")) {
                    $arrOrderList['list'][$key]['image'] = _USER_URL."/product/".$val['code']."_1";
                }
                else {
                    $arrOrderList['list'][$key]['image'] = "/img/sub/no_photo.gif";
                }
            }
        }
        return $arrOrderList;
	}
}
?>