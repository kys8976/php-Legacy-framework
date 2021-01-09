<?php
class ThisPage extends Page {
	function initialize() {
        $this->checkAdmin();
        $this->setLayout("admin_iframe");
        $this->objClass = $this->loadClass("BizProduct");

        $this->arrData['category_code'] = !empty($this->reqData['category_code']) ? $this->reqData['category_code'] : "";
        $this->arrData['order_code'] = !empty($this->reqData['order_code']) ? $this->reqData['order_code'] : "";
	}

	function checkParam() {
	}

	function makeJavaScript() {
        $this->addScript("
        function setData(code) {
            // 회원정보
            $.ajax({
                url:'"._API_URL."',
                type:'post',
                dataType:'json',
                data:{
                    method : 'BizProduct.infoCategory',
                    code : code
                },
                success:function(data, textStatus, jqXHR){
                    var json_data = data.data;
                    parent.$('form[name=\"form_register\"] #mode').val('updateCategory');
                    parent.$('#code').val(code);
                    parent.$('#title').val(json_data.title);
                    parent.$('[name=status]').val(json_data.status);
                },
                error:function(jqXHR, textStatus, errorThrown){
                    console.log(textStatus);
                    // $('#content').val(errorThrown);
                }
            });
        }
        function onclickInsert() {
            parent.$('#modalContent').modal({backdrop:'static', show:true});
            parent.form_register.reset();
            parent.form_register.mode.value = 'insertCategory';
            parent.form_register.category_code.value = '".$this->arrData['category_code']."';
            parent.$('input:radio[name=icon_code]').attr('checked', false);
        }
        function onclickUpdate(code) {
            parent.$('#modalContent').modal({backdrop:'static', show:true});
            setData(code);
        }");
	}

	function process() {
        $category_code = !empty($this->reqData['category_code']) ? $this->reqData['category_code'] : "";
        $category_length = strlen($category_code);
        $category_length += 2;
        $where = " where category_code like '".$category_code."%' and length(category_code)=".$category_length;
        $this->arrData['data'] = $this->objDBH->getRows("select code,category_code,order_code,title,status from category".$where." order by order_code");

        $this->arrData['link_root'] = $this->getCurrentUrl('category_code');
        $this->arrData['category_depth'] = $this->getCategoryDepth("category", $this->arrData['category_code']);
        $this->arrData['onclick_change_up'] = "changeOrder('up','category','?tpf=admin/product/category_sub','".$this->arrData['category_code']."');";
        $this->arrData['onclick_change_down'] = "changeOrder('down','category','?tpf=admin/product/category_sub','".$this->arrData['category_code']."');";
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>