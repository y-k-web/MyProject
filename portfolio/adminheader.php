    <body>
    <!-- ヘッダー -->
    <header class="site-header">
        <a href="#">
            <h1>y-k-web admin</h1>
        </a>

        <!--ハンバーガーメニュー-->
        <div id="nav-drawer">
            <input id="nav-input" type="checkbox" class="nav-unshown">
            <label id="nav-open" for="nav-input"><span></span></label>
            <label class="nav-unshown" id="nav-close" for="nav-input"></label>
            <div id="nav-content">
                <ul class="menu">
                    <li class="menu-item"><a href="admin.php">ADMIN TOP</a></li>
                    <li class="menu-item"><a href="index.php" target="_blank" rel="noopener noreferrer">Go to "y-k-web.com"</a></li>
                    <li class="menu-item"><a href="admin_infoblog.php">BLOG EDIT</a></li>
                    <li class="menu-item"><a href="admin_album.php">PHOTOS EDIT</a></li>
                    <li class="menu-item"><a href="signup.php">Sign up</a></li>
                    <li class="menu-item"><a href="withdraw.php">DELETE ACCOUNT</a></li>
                    <li class="menu-item"><a href="passEdit.php">CHANGE PASSWORD</a></li>
                    <li class="menu-item"><a href="logout.php">LOG OUT</a></li>
                    <li class="close"><a href="javascript: void(0);"><span>CLOSE×</span></a></li>
                </ul>
            </div>
        </div>
        <!--PCメニュー-->
        <nav id="top-nav">
            <ul class="pcmenu">
                <li class="pcmenu"><a href="admin.php">ADMIN TOP</a></li>
                <li class="pcmenu"><a href="index.php" target="_blank" rel="noopener noreferrer">Go to "y-k-web"</a></li>
                <li class="pcmenu"><a href="admin_infoblog.php">BLOG EDIT</a></li>
                <li class="pcmenu"><a href="admin_album.php">PHOTOS EDIT</a></li>
                <li class="pcmenu"><a href="signup.php">Sign Up</a>
                <li><a href="withdraw.php" class="btn btn-primary">DELETE ACCOUNT</a></li>
                <li class="pcmenu"><a href="passEdit.php">CHANGE PASSWORD</a></li>
                <li class="pcmenu"><a href="logout.php">LOG OUT</a></li>
            </ul>
        </nav>
    </header>