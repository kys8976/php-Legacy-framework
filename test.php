<?php
if(getenv("REMOTE_ADDR") == "121.140.62.102") { echo "<div align=left><pre>"; var_dump($_POST); echo "</pre>"; die("<br>End</div>");}
?>

<script>
function goResult() {
    window.opener.name = "parentName"; // 부모창의 이름 설정
    document.pay_info.target = "parentName"; // 타켓을 부모창으로 설정
    // document.pay_info.action = "/what/goWhat.do";
    document.pay_info.submit();
    window.close();
}
onload = goResult;
</script>

<form name="pay_info" id="pay_info" method="post" action="http://api.whoisict.com/result.php">
<input type="hidden" name="payment_code" value="3345">
<input type="hidden" name="payment_result" value="우리는 달려간다.">
</form>