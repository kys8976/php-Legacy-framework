<?php
class ThisPage extends Page {
	function initialize() {
        $this->checkAdmin();
        $this->setLayout("admin_iframe");
        $this->objClass = $this->loadClass("BizProduct");

        $this->arrData['category_code'] = !empty($this->reqData['category_code']) ? $this->reqData['category_code'] : "";
        $this->arrData['order_code'] = !empty($this->reqData['order_code']) ? $this->reqData['order_code'] : "";
        $this->arrData['category_depth'] = $this->getCategoryDepth("category", $this->arrData['category_code']);

        $this->arrData['arrSearch'] = array(
            'title'         => '상품명'
        );

        if (!empty($this->reqData['keyword'])) {    // 검색키 있을때
            $this->arrData['field'] = $this->reqData['field'];
            $this->arrData['keyword'] = $this->reqData['keyword'];
        }
	}

	function checkParam() {
	}

	function makeJavaScript() {
        $category_depth = preg_replace("/href=/","href=#",$this->arrData['category_depth']);
        $this->addScript("
        function setData(code) {
            $.ajax({
				url:'"._API_URL."',
				type:'post',
				dataType:'json',
				data:{
					method:'BizProduct.infoProduct',
                    code:code
				},
				success:function(data, textStatus, jqXHR){
                    var json_data = data.data;
                    console.log(json_data);
                    parent.$('form[name=\"form_register\"] #mode').val('updateProduct');
                    parent.$('#code').val(code);
                    parent.$('[name=title]').val(json_data.title);
                    parent.$('[name=type]').val(json_data.type);
                    parent.$('[name=title_sub]').val(json_data.title_sub);
                    var arrMark = json_data.mark.split(',');
                    parent.$('input:checkbox[id^=\"mark\"]').prop('checked',false);
                    arrMark.forEach(function(value) {
                        var markTag = 'mark'+value;
                        parent.$('input:checkbox[id=\"'+markTag+'\"]').prop('checked',true);
                    });
                    parent.$('[name=certificate_number]').val(json_data.certificate_number);
                    parent.objEditor.setData(json_data.content);
                    parent.$('[name=status]').val(json_data.status);
                    parent.$('#category_depth').text(json_data.category_depth);
                    parent.$(\"span[id^='display_file']\").css('display','none');
                    if (typeof json_data.files == 'object') {
                        $.each(json_data.files, function(index, value) {
                            parent.$('#display_file'+index).css('display','');
                        });
                    }
                    if(json_data.file_name) {
                        var file12_tag = '<a href=\'"._USER_URL."/product/'+json_data.file_name+'\' target=\'_new\'> <button type=\"button\" class=\"btn btn-success btn-xs\">보기</button></a> <button type=\"button\" onclick=\"confirmIframeDelete(\'?tpf=common/image_delete&file_name=product/'+json_data.file_name+'&table=product\');\" class=\"btn btn-danger btn-xs\">삭제</button>';
                        parent.$('#display_file12').html(file12_tag);
                        parent.$('#display_file12').css('display','');
                    }
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
            parent.form_register.mode.value = 'insertProduct';
            parent.form_register.category_code.value = '".$this->arrData['category_code']."';
            parent.$('#category_depth').html('".$category_depth."');
        }
        function onclickUpdate(code) {
            parent.$('#modalContent').modal({backdrop:'static', show:true});
            parent.form_register.reset();
            setData(code);
        }");
	}

	function process() {
        $arrReturn = $this->objClass->listProduct($this->reqData);
        $this->arrData['data'] = $this->displayDataList($arrReturn['list_query']);

        $this->arrData['link_root'] = $this->getCurrentUrl('category_code');
        $this->arrData['onclick_change_up'] = "changeOrder('up','product','?tpf=admin/product/list_sub','".$this->arrData['category_code']."');";
        $this->arrData['onclick_change_down'] = "changeOrder('down','product','?tpf=admin/product/list_sub','".$this->arrData['category_code']."');";

        $category_length = strlen($this->arrData['category_code'])+2;

        // 하위 카테고리 개수 체크
        $this->arrData['sub_category_count'] = $this->objDBH->getNumRows("select * from category where category_code like '".$this->arrData['category_code']."%' and length(category_code)=".$category_length);
	}

	function setDisplay() {
        return $this->arrData;
	}
}
?>