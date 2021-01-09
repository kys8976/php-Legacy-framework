<?
class ThisPage extends Page {
    function initialize() {
        $this->objClass = $this->loadClass("BizProduct");
    }

    function checkParam() {
    }

    function makeJavaScript() {
    }

    function process() {
        $this->arrData['category_code'] = $this->reqData['category_code'];
        $this->arrData['category_name'] = $this->objClass->infoCategoryName($this->reqData);

        $this->arrData['arrProduct'] = $this->objClass->infoProduct($this->reqData);

        // 제품 사진
        $this->arrData['arrProduct']['image'] = _USER_URL.'/product/'.$this->arrData['arrProduct']['code'].'_1?dummy='.getDummy();

        // 마크
        if (!empty($this->arrData['arrProduct']['mark'])) {
            $this->arrData['arrProduct']['mark'] = preg_split('/,/',$this->arrData['arrProduct']['mark']);
        }

        // Product Images
        for($i=2; $i<=6; $i++) {
            if (file_exists(_USER_DIR.'/product/'.$this->arrData['arrProduct']['code'].'_'.$i)) {
                $arrImage[] = _USER_URL.'/product/'.$this->arrData['arrProduct']['code'].'_'.$i.'?dummy='.getDummy();
            }
        }
        if (!empty($arrImage)) {
            $this->arrData['arrProduct']['arrImage'] = $arrImage;
        }

        // Product Size
        for($i=7; $i<=11; $i++) {
            if (file_exists(_USER_DIR.'/product/'.$this->arrData['arrProduct']['code'].'_'.$i)) {
                $arrSize[] = _USER_URL.'/product/'.$this->arrData['arrProduct']['code'].'_'.$i.'?dummy='.getDummy();
            }
        }
        if (!empty($arrSize)) {
            $this->arrData['arrProduct']['arrSize'] = $arrSize;
        }
    }

    function setDisplay() {
        return $this->arrData;
    }
}
?>