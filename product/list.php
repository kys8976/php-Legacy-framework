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

        $this->reqData['status'] = 'y';
        $query = $this->objClass->listProduct($this->reqData);
        $this->arrData['arrProduct'] = $this->displayDataList($query['list_query'],'y');
    }

    function setDisplay() {
        return $this->arrData;
    }
}
?>