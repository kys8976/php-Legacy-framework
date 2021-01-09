<?php
class ThisPage extends Page {
    function initialize() {
        $this->objClass = $this->loadClass("UtilStaff");
    }

    function checkParam() {
    }

    function makeJavaScript() {
    }

    function process() {
        $this->arrData['data'] = $this->objClass->lists($this->reqData);
    }

    function setDisplay() {
        return $this->arrData;
    }
}
?>