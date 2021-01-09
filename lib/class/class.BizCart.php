<?php
/**
 * Payment BizCart
 *
 */
class BizCart {
	private $objDBH;
	private $arrMaster;
	private $table = 'cart_list';

	function __construct($obj) {
		$this->objDBH = $obj;
        $this->arrMaster = $this->objDBH->getRow("select * from master");
	}

    // 오늘 본 상품 리스트 저장
	function setTodayProduct($product_code) {
		if ($product_code and !eregi($product_code.",",$_COOKIE['today_view_product'])) {
			$product_code_list = $product_code.",".$_COOKIE['today_view_product'];
			// $product_code_list = eregi_replace(",$","",$product_code_list);
			setcookie("today_view_product",$product_code_list, 0,"/",_LOGIN_URL);
		}
	}

	// 장바구니 담기
	function setCart($reqData) {
        $cart_count = count(@$_COOKIE['cart']);
        if ($cart_count == 0) {
			$index = 0;
		}
		else {
			$index = max(array_keys($_COOKIE['cart']))+1;
		}

        foreach($reqData as $key => $val) {
            setcookie("cart[".$index."][product_code]",$val['product_code'], 0,"/",_LOGIN_URL);
            if (!empty($val['color'])) setcookie("cart[".$index."][color]",$val['color'], 0,"/",_LOGIN_URL);    // 색상
		    setcookie("cart[".$index."][count]",$val['count'], 0,"/",_LOGIN_URL);
            $index++;
        }
	}

	// 장바구니 수정
	function updateCart($reqData) {
        setcookie("cart[".$reqData['cart_index']."][count]",$reqData['count'], 0,"/",_LOGIN_URL);
	}

	// 장바구니 삭제
	function deleteCart($reqData) {
        setcookie("cart[".$reqData['cart_index']."][product_code]","", 0,"/",_LOGIN_URL);
		setcookie("cart[".$reqData['cart_index']."][count]","", 0,"/",_LOGIN_URL);
		@setcookie("cart[".$reqData['cart_index']."][color]","", 0,"/",_LOGIN_URL);
	}

    // 장바구니 개수 가져오기
	function getCartCount() { // default : 추천 사업자 할인 없음
        if (!empty($_COOKIE['cart'])) return count($_COOKIE['cart']);
        else return 0;
	}

	// 장바구니 가져오기
	function getCart() { // default : 추천 사업자 할인 없음
        if (!empty($_COOKIE['cart'])) {
            $index = 1;
			$delivery_price = $product_price = $agency_discount_price = 0;

            foreach($_COOKIE['cart'] as $key => $val) {
				$arrProduct = $this->objDBH->getRow("select title,title_sub,capacity,sale_price from product where code='".$val['product_code']."'");

                $tpl_row['index'] = $index;
				$tpl_row['cart_index'] = $key;
                // 이미지 표출
                if(file_exists(_USER_DIR."/product/".$val['product_code']."_1")) {
                    $image = _USER_URL."/product/".$val['product_code']."_1";
                }
                else {
                    $image = "/images/sub/no_photo.gif";
                }
				$tpl_row['image'] = $image;
				$tpl_row['link_view'] = "?tpf=product/detail&code=".$val['product_code'];
				$tpl_row['title'] = $arrProduct['title'];
				$tpl_row['title_sub'] = $arrProduct['title_sub'];
				$tpl_row['capacity'] = $arrProduct['capacity'];
				if (!empty($val['color'])) $tpl_row['color'] = $val['color'];
				$tpl_row['count'] = $val['count'];
                // $tpl_row['point'] = number_format(getProdutPoint($arrProduct['sale_price']*$val['count']));
				$tpl_row['sale_price'] = number_format($arrProduct['sale_price']);
				$sum = $arrProduct['sale_price']*$val['count'];
                $tpl_row['price'] = number_format($sum);
				$tpl_row['onclick_update_cart_add'] = "updateCart(".$index.",1);";
				$tpl_row['onclick_update_cart_sub'] = "updateCart(".$index.",-1);";
				$tpl_row['onclick_delete_cart'] = "deleteCart(".$index.");";
				$tpl_list[] = $tpl_row;

				$product_price += $sum;
                $agency_discount_price += @$agency_discount;
				if ($index == 1) $goodname = $arrProduct['title'];	// 대표상품명 저장
				$index++;
			}
            $arrResult['cart_count'] = count($tpl_list);    // 장바구니 개수

			// 배송료
			if ($product_price < $this->arrMaster['delivery_limit']) $delivery_price = $this->arrMaster['delivery_price'];
			else $delivery_price = 0;
			$total_price = $product_price + $delivery_price;

			$arrResult['list'] = $tpl_list;
			$arrResult['product_price'] = number_format($product_price);
			$arrResult['delivery_price'] = number_format($delivery_price);
			$arrResult['total_price'] = number_format($total_price - $agency_discount_price);
			$arrResult['agency_discount_price'] = $agency_discount_price ? number_format($agency_discount_price) : '';

			// hidden 값
			if (count($_COOKIE['cart']) > 1) {
				$good_count = count($_COOKIE['cart'])-1;
				$goodname = $goodname." 외 ".$good_count."건";
			}
			$arrResult['goodname'] = $goodname;
		}
        else {
            $arrResult['cart_count'] = 0;
            $arrResult['product_price'] = 0;
			$arrResult['delivery_price'] = 0;
			$arrResult['total_price'] = 0;
            $arrResult['goodname'] = '';
        }
        $arrResult['delivery_price_txt'] = number_format($this->arrMaster['delivery_price']);  // 기본 배송료
        $arrResult['delivery_limit'] = number_format($this->arrMaster['delivery_limit']);      // 무료 배송료 기준 가격 (~이상 무료)

        return $arrResult;
	}

	// 장바구니 비우기
	function resetCart() {
		if (count($_COOKIE['cart']) > 0) {
			foreach($_COOKIE['cart'] as $key => $val) {
				@setcookie("cart[".$key."][product_code]","", 0,"/",_LOGIN_URL);
				@setcookie("cart[".$key."][count]","", 0,"/",_LOGIN_URL);
				@setcookie("cart[".$key."][color]","", 0,"/",_LOGIN_URL);
			}
		}
	}

    // 관심 상품 등록하기
    function registWish($reqData) {
        $arrWishList = $this->objDBH->getRow("select count(*) as count from wish_list where member_code='".getMemberCode()."' and product_code='".$reqData['product_code']."'");
        if ($arrWishList['count'] == 0) {
            $arrParam = array (
                'member_code' => getMemberCode(),
                'product_code' => $reqData['product_code'],
                'reg_date' => 'now()'
            );
            $this->objDBH->insert("wish_list", $arrParam);
        }
    }
}
?>