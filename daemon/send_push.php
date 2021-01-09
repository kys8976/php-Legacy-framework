<?
include ("../lib/config/config.php");
include _CLASS_DIR."/class.API.php";
include _CLASS_DIR."/class.Push.php";

$objAPI = new API();
$objPush = new Push($objAPI->objDBH);

while(1) {
    $arrJob = $objAPI->objDBH->getRow("select code,title,category_work_code from job where push_status='n' order by code limit 1");
    if ($arrJob['code'] && !empty($arrJob['category_work_code'])) {
        $strWhere = "";
        $arrCategory = array_values(array_filter(explode("|", $arrJob['category_work_code'])));
        foreach($arrCategory as $key => $val) {
            $arrTmp = explode(":", $val);
            if ($arrTmp[0]) $strWhere .= "mc.category_work_code like '%|".$arrTmp[0]."%' or ";
        }
        $where = "(".preg_replace("/ or $/", "", $strWhere).")";

        $arrMember = $objAPI->objDBH->getRows("select mc.member_code,m.token_id,m.type from member m, member_customer mc where ".$where." and m.code=mc.member_code");
        if (count($arrMember['list']) > 0) {
            foreach($arrMember['list'] as $key => $val) {
                // push 보내기
                $arrPushData['member_code'] = $val['member_code'];
                $arrPushData['job_code'] = $arrJob['code'];
                $arrPushData['type'] = 3;
                $arrPushData['message'] = "[맞춤알림] ".$arrJob['title'];
                $objPush->send($arrPushData);
            }
        }

        // status update 하기
        $arrParam = array (
            'push_status' => 'y'
        );
        $arrWhere = array (
            'code' => $arrJob['code']
        );
        $objAPI->objDBH->update("job", $arrParam, $arrWhere);
    }
    sleep(5);
}
?>