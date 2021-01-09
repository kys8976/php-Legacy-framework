<?php
class ThisPage extends Page {

	function initialize() {
		$this->checkAdmin();

        $act = $this->reqData['act'];
        if (!$act) $act = "Job.lists";
        $file = preg_replace("/\./","/",strtolower($act));
        $this->setFile($file);
	}

	function checkParam() {
	}

	function makeJavaScript() {
        $this->addScript("
        function submit() {
            // Serialize the data in the form
            var arrData = $('#form').serializeArray();
            // console.log(arrData);

            $.ajax({
                url: '"._API_URL."',
                type: 'post',
                dataType: 'json',
                data: arrData,
                timeout: 5000,  // timeout : 5초
                success: function(data, textStatus, jqXHR) {
                    jsonPretty = JSON.stringify(data, null, '\t');
                    $('#result_area').val(jsonPretty);
                },
                error: function(xhr, textStatus, errorThrown){
                    $('#result_area').val('request failed : '+errorThrown);
                }

            });
        }");
	}

	function process() {
	}

	function setDisplay() {
        $arrData['act'] = $this->reqData['act'];
        return $arrData;
	}
}
?>