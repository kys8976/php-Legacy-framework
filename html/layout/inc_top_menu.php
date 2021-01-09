<div id="header">
    <span style="float:right;">
<?php
if (!getLoginId()) {	// 로그인 전
?>
    <a href="?tpf=member/stipulation">회원가입</a> |
    <a href="?tpf=member/login">로그인</a>
<?php
}
else {					// 로그인 후
?>
    <a href="?tpf=member/form">회원정보수정</a> |
    <a href="?tpf=member/logout">로그아웃</a>
<?php
}
?>
    </span>
    <br>

    <h1><a href="/">header</a></h1>
</div>