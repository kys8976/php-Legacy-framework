<?php
$arrMenu = explode("/", $_GET['tpf']);
$arrMenu[1] = "Active";
?>

<div id="sub-menu">
    <div>좌측메뉴</div><br>

<?php
if ($arrMenu[0] == 'board') {       // 게시판
?>
    <ul>
        <li><a href="?tpf=board/list&board_code=1">공지사항</a></li>
        <li><a href="?tpf=board/list&board_code=2">사진 게시판</a></li>
        <li><a href="?tpf=board/list&board_code=3">Q&A</a></li>
    </ul>
<?php
}
?>

<?php
if ($arrMenu[0] == 'schedule') {    // 일정관리
?>
    <ul>
        <li><a href="?tpf=schedule/list">일정 관리</a></li>
    </ul>
<?php
}
?>

<?php
if ($arrMenu[0] == 'company') {     // 회사소개
?>
    <ul>
        <li><a href="?tpf=company/staff">임원진 소개</a></li>
        <li><a href="?tpf=company/history">연혁 소개</a></li>
    </ul>
<?php
}
?>



<?php
if ($arrMenu[0] == 'product') {
    // 상품별 카테고리
    $arrCategory = $arrCategorySub = array();
    // 1차 카테고리
    $arrCategoryTmp = $this->objDBH->getRows("select category_code,title from category where length(category_code)=2 order by order_code");
    if (!empty($arrCategoryTmp['list'])) $arrCategory = $arrCategoryTmp['list'];

    // 2차 카테고리
    $arrCategoryTmp = $this->objDBH->getRows("select category_code,title from category where length(category_code)=4 order by order_code");
    if (!empty($arrCategoryTmp['list'])) {
        foreach($arrCategoryTmp['list'] as $key => $val) {
            $arrCategorySub[substr($val['category_code'],0,2)][] = $val;
        }
    }
?>
    <h2>제품소개</h2>
	<ul class="left_mnu">
<?php
foreach($arrCategory as $key => $val) {

    echo '  <li'; if(substr(@$this->reqData['category_code'],0,2) == @$val['category_code']) { echo ' class="lm_on"';} echo '><a href="?tpf=product/list&category_code='.$val['category_code'].'">'.$val['title'].'</a>';
    echo '      <ul class="l1dep2">';

    if(!empty($arrCategorySub[$val['category_code']])){
        foreach($arrCategorySub[$val['category_code']] as $key2 => $val2) {
            echo '  <li><a href="?tpf=product/list&category_code='.$val2['category_code'].'">- '.$val2['title'].'</a></li>';
        }
    }
    echo '      </ul>
            </li>';
}
?>
	</ul>
<?php
}
?>
</div>