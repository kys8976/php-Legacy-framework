<?php
list($menu_main, $menu_sub) = explode('.', $_GET['act']);
$arrMenu[$menu_main] = 'active ';
$arrMenuSub[$menu_sub] = ' class="active"';
?>

<!-- sidebar -->
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?=$arrMenu['Board']?>treeview">
                <a href="#">
                <i class="fa fa-gear"></i> <span>게시판</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li<?=!empty($arrMenuSub['lists']) ? $arrMenuSub['lists'] : ""?>><a href="?tpf=api/index&act=Board.lists"><i class="fa fa-circle-o"></i> 공지사항 리스트</a></li>
                    <li<?=!empty($arrMenuSub['view']) ? $arrMenuSub['view'] : ""?>><a href="?tpf=api/index&act=Board.view"><i class="fa fa-circle-o"></i> 공지사항 상세보기</a></li>
                </ul>
            </li>
        </ul>
    </section>
</aside><!-- /.sidebar -->