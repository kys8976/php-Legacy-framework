<?php
class Message {
	function alert($msg) {					// 에러메세지 뿌려주기
		echo "
		<script>
		alert('$msg');
		history.back();
		</script>";
		exit;
	}

	function alertClose($msg) {				// 메세지 뿌려주고 현재창 닫기
		echo "
		<script>";
		if($msg) { echo "alert('$msg');";}
		echo "
		window.close();
		</script>";
		exit;
	}

	function alertReplace($msg,$url) {		// 메세지 뿌려주고 해당 디렉토리로 이동하기
		echo "
		<script>";
		if($msg) { echo "alert('$msg');";}
		echo "
		location.replace('$url');
		</script>";
		exit;
	}

	function alertParentReplace($msg,$url) {// 메세지 뿌려주고 부모창 해당 디렉토리로 이동하기
		echo "
		<script>";
		if($msg) { echo "alert('$msg');";}
		echo "
		parent.top.location.replace('$url');
		</script>";
		exit;
	}

	function alertParentReplaceClose($msg,$url) {// 메세지 뿌려주고 현재창 닫고 부모창 이동하기
		echo "
		<script>";
		if($msg) { echo "alert('$msg');";}
		echo "
		parent.opener.location.replace('$url');
		parent.top.close();
		</script>";
		exit;
	}

	function alertReloadClose($msg) {		// 메세지 뿌려주고 현재창 닫고 오프너창 릴로드
		echo "
		<script>
		alert('$msg');
		parent.opener.location.reload();
		parent.top.close();
		</script>";
		exit;
	}
}
?>