<!-- header -->
<header class="main-header">
    <a href="/admin" class="logo"><b><?=_SITE_NAME?> Admin</b></a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        </a>

        <div style="padding:15px 15px; color:#FFF; display:inline-block;"></div>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
                    <span class="hidden-xs"><?=getLoginName()?></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="user-header">
                        <img src="/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
                        <p>
                        <?=getLoginName()?> - Web Administrator
                        <small><?=_SITE_NAME?> 관리자</small>
                        </p>
                    </li>
                    <li class="user-footer">
<!--
                        <div class="pull-left">
                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                        </div>
-->
                        <div class="pull-right">
                            <a href="?tpf=member/logout" class="btn btn-danger btn-flat">Sign out</a>
                        </div>
                    </li>
                </ul>
                </li>
            </ul>
        </div>
    </nav>
</header><!-- /.header -->