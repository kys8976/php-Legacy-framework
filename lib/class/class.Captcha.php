<?php
/**
*
* Captcha : 스팸방지코드
* @version  $Id: class.Captcha.php,v 2.00 2016/08/05 15:49:47 sangjun $
* @access   public
*/
class Captcha {
    private $objDBH;
	private $width;
	private $height;
	private $im;
	private $colorArray;
	private $colorStrArray;
	private $authKey;
	private $authkeyLength;
	private $imgAuthCode;
	private $font;
	private $header;

	function __construct($obj) {
        $this->objDBH = $obj;
	}

    // setting
    function setOption($width=200, $height=50, $authkeyLength=5, $header='png') {
        $this->width = $width;
        $this->height = $height;
		$this->colorArray = array();
		$this->colorStrArray = array();
		$this->authKey = array();
		$this->authkeyLength = $authkeyLength;
		$this->header = $header;
		$this->im = @imagecreate($this->width, $this->height);
		$this->initColor();
	}

    // 자동입력방지 문자 일치 체크
	function checkCaptcha($auth_key="") {
		$ip = $_SERVER['REMOTE_ADDR'];
        $arrCaptcha = $this->objDBH->getRow("select auth_key from captcha where ip='".$ip."'");
        if (strtoupper($auth_key) != $arrCaptcha['auth_key']) return false;
        else {
            $this->objDBH->query("delete from captcha where ip='".$ip."'");
            return true;
        }
	}

	function setFont($font) {
		$this->font = $font;
	}

	function initColor() {
		// 배경색
		$this->setColor(255, 255, 255, 'white');
		$this->setColor(0, 0, 0, 'black');
		$this->setColor(90, 90, 90, 'darkgray');
		$this->setColor(100, 100, 100, 'gray');

		// 문자열색
		$this->setColorStr(0, 0, 0, 'black');
		$this->setColorStr(0, 150, 0, 'green');
		$this->setColorStr(150, 0, 0, 'red');
		$this->setColorStr(0, 0, 150, 'blue');
		$this->setColorStr(93, 55, 102, 'purple');

	}

	function setColor($r, $g, $b, $i) {
		$this->colorArray[$i] = @imagecolorallocate($this->im, $r, $g, $b);
	}

	function setColorStr($r, $g, $b, $i) {
		$this->colorStrArray[$i] = @imagecolorallocate($this->im, $r, $g, $b);
	}

	function getColor() {
		return $this->colorArray;
	}

	function getColorStr() {
		return $this->colorStrArray;
	}

	// 점그리기
	function drawDot($color='black') {
		// 점 좌표
		$cx = $this->width - 1;
		$cy = $this->height - 1;

		$color = $this->colorArray[$color];
		if (!$color) {
			$color = $this->colorArray['black'];
		}

		$dotMax = ($cx*$cy)/4;

		for ($i=0; $i<$dotMax; $i++) {
			$x = rand(1, $cx);
			$y = rand(1, $cy);
			imagesetpixel($this->im, $x, $y, $color);
		}
	}

	// 격자그리기
	function drawGrid($color='black', $numMin=1, $numMax=5) {
		// 시작 위치
		$num = rand($numMin, $numMax);

		$color = $this->colorArray[$color];
		if (!$color) {
			$color = $this->colorArray['black'];
		}

		// 가로 선
		for ($i=$num; $i<=$this->width; $i+=rand(10,15)) {
			imageline($this->im, $i, 0, $i, $this->height, $color);
		}

		//세로 선
		for ($i=$num; $i<=$this->height+10; $i+=rand(10,15)) {
			imageline($this->im, 0, $i, $this->width, $i, $color);
		}
	}

	// 문자열 그리기
	function drawStr() {
		$cx = 20;
		$cy = $this->height / 2 + 10;

		// 폰트
		$font = $this->font;
		$strArray = $this->authKey;
		$strArraySize = $this->authkeyLength;
		$colorStrArray = $this->colorStrArray;

		// 폰트 크기, 간격 계산
		$i = 0;
		$fontMargin  = 5;
		$fontSizeMax = 25;
		$fontSizeSum = $fontSizeMax * ($strArraySize + 1);
		$fontSpace   = ($this->width - ($fontSizeSum + $fontMargin*2)) / ($strArraySize + 1);

		// 문자열 그리기
		for ($i=0; $i<$strArraySize; $i++) {
			if ($i>0) {
				$x += $fontSizeMax + $fontSpace;
			} else {
				$x = $cx + $fontSpace;
			}
			$y = $cy + rand(-8, 8);

			$fontSize = rand(15, 25);
			$fontColor = $colorStrArray[array_rand($colorStrArray)];

			imagettftext($this->im, $fontSize, 0, $x, $y, $fontColor, $font, $strArray[$i]);
		}
	}

	// 인증키 배열 생성
	function setAuthKey($auth_key="") {
        if ($auth_key == "") {
            $randomDigit = range(0,9);
            $randomChar = range('A','Z');

            // $authKey = array_merge($randomDigit, $randomChar);
            $authKey = $randomDigit;
            shuffle($authKey);
            $this->authKey = array_slice($authKey, 0, $this->authkeyLength);
        }
        else $this->authKey = str_split($auth_key);

        $ip = $_SERVER['REMOTE_ADDR'];
        $this->objDBH->query("replace into captcha (ip,auth_key,reg_date) values('".$ip."','".implode($this->authKey)."',now())");
	}

	function makeImgFile() {
		$header = $this->header;
		header("Content-type:image/$header");
		imagepng($this->im);
		imagedestroy($this->im);
	}

	function makeImgFileAuto($auth_key="") {
        if (empty($this->width)) {
            $this->setOption(); // seting default
        }
        if (empty($this->font)) {
            $this->setFont(_LIBRARY_DIR."/font/h2gtre.ttf");
        }
		$this->drawDot('darkgray');
		$this->drawGrid('gray');
        $this->setAuthKey($auth_key);

		$this->drawStr();
		$this->makeImgFile();
	}
}
?>