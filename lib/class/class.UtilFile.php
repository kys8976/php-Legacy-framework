<?php
/**
 * UtilFile Class
 *
 */
class UtilFile {
	var $msg;
	var $arrFile;
	var $fileExt;
	// 파일 type별 확장자 정리
	var $arrImgType		= array("jpg","gif","jpeg","png");
    var $arrCommonType  = array("avi","csv","xls","xlsx","doc","docx","html","mov","mp3","mp4","pdf","ppt","pptx","txt","xml","zip");
	var $arrFlashType	= array("swf");
	var $arrMovieType	= array("asf","wmv","mpg","vod");
	var $arrMmusicType	= array("wav","mid","mp3");
	var $arrLogoType	= array("jpg","gif","png","swf");
	var $arrCsvType		= array("csv");
	var $arrFileType	= array("*");

	var $arrDeniedFileExt = array("php","php3","php4","html","htm","inc","pl","xml","asp","jsp","exe","com","vbs","js");
	var $arrDeniedMineType = array("text/plain","text/html","text/xml");

	function __construct() {
		$this->setFiles();
	}

	function setFiles() {
		$this->arrFile = $_FILES;
	}

	function error($msg) {
		$val['status']	= "FAIL";
		$val['message']	= $msg;
		return $val;
	}

	// 업로드 불가 확장자 체크
	function checkDeniedExt($file_name) {
		if (preg_match('/(php[0-9]*|html|htm|inc|pl|xml|asp|jsp|exe|com|vbs|js)(\.|$)/i', $file_name)) {
			@unlink($file['tmp_name']);
			return $this->error("업로드가 제한된 파일 확장자입니다.");
		}
	}

	function checkFile($file_type="Img",$up_file) {	// 파일 체크
		$arrFileName = explode( ".",$this->arrFile[$up_file]['name']);
		$this->fileExt = strtolower(array_pop($arrFileName));
		if($this->fileExt == "") {
			$this->error("파일이 첨부되지 않았습니다.");
		}
		else {
			// 파일 확장자 체크
			if ($file_type != "File") {
				$tmp = "arr".$file_type."Type";
				if(!in_array($this->fileExt,$this->$tmp)) {
					return $this->error("파일 확장자는 ".implode(", ",$this->$tmp)." 만 지원합니다.");
				}
			}

			// 첨부금지 파일 확장자 체크
			if(in_array($this->fileExt,$this->arrDeniedFileExt)) {
				@unlink($this->arrFile[$up_file]['tmp_name']);
				return $this->error("업로드가 제한된 파일 확장자입니다.");
			}

			// mine type 체크
			if(in_array($this->arrFile[$up_file]['type'],$this->arrDeniedMineType)) {
				return $this->error("지원하지 않는 mine type 입니다.");
			}
		}
	}

    function checkFileBulk($file_type="Img",$file_index) {	// 파일 체크
		$arrFileName = explode( ".",$this->arrFile['file']['name'][$file_index]);
		$file_ext = strtolower(array_pop($arrFileName));
		if($file_ext == "") {
			$this->error("파일이 첨부되지 않았습니다.");
		}
		else {
			// 파일 확장자 체크
			if ($file_type != "File") {
				$tmp = "arr".$file_type."Type";
				if(!in_array($file_ext,$this->$tmp)) {
					return $this->error("파일 확장자는 ".implode(", ",$this->$tmp)." 만 지원합니다.");
				}
			}

			// 첨부금지 파일 확장자 체크
			if(in_array($file_ext,$this->arrDeniedFileExt)) {
				@unlink($this->arrFile['file']['tmp_name'][$file_index]);
				return $this->error("업로드가 제한된 파일 확장자입니다.");
			}

			// mine type 체크
			if(in_array($this->arrFile['file']['type'][$file_index],$this->arrDeniedMineType)) {
				return $this->error("지원하지 않는 mine type 입니다.");
			}
		}
	}

	function uploadFile($up_file,$dn_file) {	// 파일 업로드
		if ($this->arrFile[$up_file]['name']) {
			move_uploaded_file($this->arrFile[$up_file]['tmp_name'],$dn_file);

            // 세로 사진 업로드시 사진 방향 잡아주기
            $objExif = @exif_read_data($dn_file);
            if(!empty($objExif['Orientation'])) {
                $objImage = imagecreatefromstring(file_get_contents($dn_file));
                switch($objExif['Orientation']) {
                    case 8:
                        $objImage = imagerotate($objImage,90,0);
                        break;
                    case 3:
                        $objImage = imagerotate($objImage,180,0);
                        break;
                    case 6:
                        $objImage = imagerotate($objImage,-90,0);
                        break;
                }
                imagejpeg($objImage, $dn_file);
                imagedestroy($objImage);
            }
		}
		else {
			return $this->error("[$dn_file] 디렉토리에 파일 업로드를 실패하였습니다.");
		}
	}

    function getFilePath() {
        $folder = "/attachment/".date('Ym');
        if(!is_dir(_USER_DIR.$folder)){
            @mkdir(_USER_DIR.$folder, 0777);
        }
        return $folder;
    }

    function uploadFileBulk($file_index) {	// 파일 업로드
		if ($this->arrFile['file']['name'][$file_index]) {
            $arrFileName = explode(".", $this->arrFile['file']['name'][$file_index]);
		    $file_ext = strtolower(array_pop($arrFileName));
            $file_path = $this->getFilePath();
            $file_name = getMicrotime().".".$file_ext;
            $dn_file = _USER_DIR."/".$file_path."/".$file_name;
            move_uploaded_file($this->arrFile['file']['tmp_name'][$file_index],$dn_file);

            // 세로 사진 업로드시 사진 방향 잡아주기
            if ($file_ext == "jpg" or $file_ext == "jpeg") {
                $objExif = exif_read_data($dn_file);
                if(!empty($objExif['Orientation'])) {
                    $objImage = imagecreatefromstring(file_get_contents($dn_file));
                    switch($objExif['Orientation']) {
                        case 8:
                            $objImage = imagerotate($objImage,90,0);
                            break;
                        case 3:
                            $objImage = imagerotate($objImage,180,0);
                            break;
                        case 6:
                            $objImage = imagerotate($objImage,-90,0);
                            break;
                    }
                    imagejpeg($objImage, $dn_file);
                    imagedestroy($objImage);
                }
            }

            $arrSize = getimagesize($dn_file);
            $arrFileInfo['file_path'] = $file_path;
            $arrFileInfo['file_name'] = $file_name;
            $arrFileInfo['orig_name'] = $this->arrFile['file']['name'][$file_index];
            $arrFileInfo['file_size'] = round(filesize($dn_file)/1024);
            $arrFileInfo['file_width'] = $arrSize[0];
            $arrFileInfo['file_height'] = $arrSize[1];
            $arrFileInfo['file_ext'] = strtolower($file_ext);

            return $arrFileInfo;
		}
		else {
			return $this->error("[$dn_file] 디렉토리에 파일 업로드를 실패하였습니다.");
		}
	}

    /*
    // 썸네일 이미지 만들기
    $this->objFile = $this->loadClass("UtilFile");
    $reqData['orig_path'] = 'D:\APM\htdocs\api.whoisict.com\user\attachment\201611\origin.jpg'; // 원본 파일 경로
    $reqData['target_path'] = 'D:\APM\htdocs\api.whoisict.com\user\attachment\201611\origin_tn.jpg';    // 카세 파일 경로
    $reqData['width'] = '260';      // 가로 사이즈
    $reqData['height'] = '150';     // 세로 사이즈
    $reqData['mode'] = 'ratio';     // ratio : 비율에 맞게, fixed : 고정 크기
    $reqData['background'] = 'n';   // n : 투명, y : 흰색 바탕
    $arrReturn = $this->objFile->makeThumbnail($reqData);
    */
	function makeThumbnail($param) {
        if(empty($param['orig_path'])) return $this->error("원본 파일 경로가 없습니다.");
        if(empty($param['target_path'])) return $this->error("타켓 파일 경로가 없습니다.");
        if(empty($param['width'])) $param['width'] = 300;
        if(empty($param['height'])) $param['height'] = 300;
        if(!in_array(@$param['mode'], array('ratio', 'fixed')))	$param['mode'] = 'ratio';
        if(!in_array(@$param['background'], array('y', 'n'))) $param['background'] = 'n';

        $orig = array();
        $target = array();

        $orig['path'] = _USER_DIR."/".$param['orig_path'];
        $target['path'] = _USER_DIR."/".$param['target_path'];

        $imginfo = getimagesize($orig['path']);
        $orig['mime'] = $imginfo['mime'];

        // 원본 이미지 리소스 호출
        switch($orig['mime']){
            case 'image/jpeg': $orig['img'] = imagecreatefromjpeg($orig['path']);break;
            case 'image/gif' : $orig['img'] = imagecreatefromgif($orig['path']); break;
            case 'image/png' : $orig['img'] = imagecreatefrompng($orig['path']); break;
            case 'image/bmp' : $orig['img'] = imagecreatefrombmp($orig['path']); break;
            // mime 타입이 해당되지 않으면 return false
            default : return $this->error("이미지 파일이 아닙니다."); break;
        }

        // 원본 이미지 크기 / 좌표 초기값
        $orig['w'] = $imginfo[0];
        $orig['h'] = $imginfo[1];
        $orig['x'] = 0;
        $orig['y'] = 0;

        // 썸네일 이미지 좌표 초기값 설정
        $target['x'] = 0;
        $target['y'] = 0;

        // 썸네일 이미지 가로, 세로 비율 계산
        $target['ratio']['w'] = $orig['w'] / $param['width'];
        $target['ratio']['h'] = $orig['h'] / $param['height'];

        switch($param['mode']){
            case 'ratio' :
                // 썸네일 이미지의 비율계산 (가로 == 세로)
                if($target['ratio']['w'] == $target['ratio']['h']){
                    $target['w'] = $param['width'];
                    $target['h'] = $param['height'];
                }
                // 썸네일 이미지의 비율계산 (가로 > 세로)
                elseif($target['ratio']['w'] > $target['ratio']['h']){
                    $target['w'] = $param['width'];
                    $target['h'] = round(($param['width'] * $orig['h']) / $orig['w']);
                }
                // 썸네일 이미지의 비율계산 (가로 < 세로)
                elseif($target['ratio']['w'] < $target['ratio']['h']){
                    $target['w'] = round(($param['height'] * $orig['w']) / $orig['h']);
                    $target['h'] = $param['height'];
                }

                if($param['background'] == 'y'){
                    $target['canvas']['w'] = $param['width'];
                    $target['canvas']['h'] = $param['height'];
                    $target['x'] = $param['width'] > $target['w'] ? ($param['width'] - $target['w']) / 2 : 0;
                    $target['y'] = $param['height'] > $target['h'] ? ($param['height'] - $target['h']) / 2 : 0;
                }
                else{
                    $target['canvas']['w'] = $target['w'];
                    $target['canvas']['h'] = $target['h'];
                }
                break;
            case 'fixed' :
                // 썸네일 이미지의 비율계산 (가로 == 세로)
                if($target['ratio']['w'] == $target['ratio']['h']){
                    $target['w'] = $param['width'];
                    $target['h'] = $param['height'];
                }
                // 썸네일 이미지의 비율계산 (가로 > 세로)
                elseif($target['ratio']['w'] > $target['ratio']['h']){
                    $target['w'] = $orig['w'] / $target['ratio']['h'];
                    $target['h'] = $param['height'];

                    $orig['x'] = ($target['w'] - $param['width']) / 2;
                }
                // 썸네일 이미지의 비율계산 (가로 < 세로)
                elseif($target['ratio']['w'] < $target['ratio']['h']){
                    $target['w'] = $param['width'];
                    $target['h'] = $orig['h'] / $target['ratio']['w'];

                    $target['y'] = 0;
                }
                $target['canvas']['w'] = $param['width'];
                $target['canvas']['h'] = $param['height'];
                break;
        }

        // 썸네일 이미지 리소스 생성
        $target['img'] = imagecreatetruecolor($target['canvas']['w'], $target['canvas']['h']);

        // 배경색 처리
        if(in_array($orig['mime'], array('image/png', 'image/gif'))){
            // 배경 투명 처리
            imagetruecolortopalette($target['img'], false, 255);
            $bgcolor = imagecolorallocatealpha($target['img'], 255, 255, 255, 127);
            imagefilledrectangle($target['img'], 0, 0, $target['canvas']['w'],$target['canvas']['h'], $bgcolor);
        }
        else{
            // 배경 흰색 처리
            $bgclear = imagecolorallocate($target['img'],255,255,255);
            imagefill($target['img'],0,0,$bgclear);
        }

        // 원본 이미지 썸네일 이미지 크기에 맞게 복사
        imagecopyresampled($target['img'] ,$orig['img'] ,$target['x'] ,$target['y'] ,$orig['x'] ,$orig['y'] ,$target['w'] ,$target['h'] ,$orig['w'] ,$orig['h']);
        // imagecopyresampled 함수 사용 불가시 사용
        // imagecopyresized($target['img'] ,$orig['img'] ,$target['x'] ,$target['y'] ,$orig['x'] ,$orig['y'] ,$target['w'] ,$target['h'] ,$orig['w'] ,$orig['h']);

        ImageInterlace($target['img']);

        // 썸네일 이미지 리소스를 기반으로 실제 이미지 생성
        switch($orig['mime']){
            case 'image/jpeg' :	imagejpeg($target['img'], $target['path']);	break;
            case 'image/gif' :	imagegif($target['img'], $target['path']);	break;
            case 'image/png' :	imagepng($target['img'], $target['path']);	break;
            case 'image/bmp' :	imagebmp($target['img'], $target['path']);	break;
        }

        // 원본 이미지 리소스 종료
        imagedestroy($orig['img']);
        // 썸네일 이미지 리소스 종료
        imagedestroy($target['img']);

        // 썸네일 파일경로 존재 여부 확인후 리턴
        return file_exists($target['path']) ? $target['path'] : $this->error("파일 생성에 실패하였습니다.");
    }

	function deleteFile($file_name) {			// 파일 삭제
		if(file_exists("$file_name")) {
			@unlink($file_name);
		}
		else {
			return $this->error("[".$file_name."] 경로와 파일명이 일치하지 않습니다.");
		}
	}

    // 첨부파일 가져오기
	function getFile($file_name, $file_real_name) {
        if(file_exists(_USER_DIR."/".$file_name)) {
			$img_name = getFileIcon($file_ext);
			$file_real_name = base64_encode($file_real_name);
			$download = "<a href=?tpf=common/download&file_real_name=".$file_real_name."&file_name=".$file_name." target=iframe_download style=\"text-decoration:none\">".$img_name;
		}
		return $download;
	}

	// 파일 다운로드 함수 추가
	function download($file_real_name,$file_name) {
		set_time_limit(0);
		$file_full_name = _USER_DIR."/".$file_name;
		$file_size = filesize($file_full_name);
        $file_real_name = iconv('utf-8', 'euc-kr', $file_real_name);

		if(eregi("MSIE 5.5", $_SERVER['HTTP_USER_AGENT'])) { // IE 5.5 버그로 인해 분리함.
			Header("Content-type: application/attachment");
			Header("Content-Disposition: filename=".trim($file_real_name));
		} else {
			Header("Content-type: application/octet-stream");
			Header("Content-Disposition: attachment; filename=".trim($file_real_name));
		}
		Header("Content-Length: ".$file_size);

		$fp=fopen($file_full_name, "r");
		print fread($fp, $file_size);
		fclose($fp);

		exit;
	}
}
?>