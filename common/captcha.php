<?php
class ThisPage extends Page {
    function initialize() {
        $this->objClass = $this->loadClass("Captcha");
        $this->objClass->setOption(170,45,4,'png');                  // 옵션 : width,height,문자갯수,파일포맷
        // $this->objClass->setFont(_LIBRARY_DIR."/font/h2gtre.ttf");   // 폰트
        $this->objClass->makeImgFileAuto();                             // 미지정시 랜덤 문자 or makeImgFileAuto('K2P2M')
    }

    function checkParam() {
    }

    function makeJavaScript() {
    }

    function process() {
    }

    function setDisplay() {
    }
}
?>