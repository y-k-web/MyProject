<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　マイページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');
?>


<?php
$siteTitle ='長延寺管理画面';
require('adminhead.php');
?>
<body>
<?php
require('adminheader.php')
?>

    <!--メインコンテンツ-->
    <div id="admin-baner">
        <img src="img/top01.jpg">
</div>
        <a href="passEdit.php">管理画面のパスワードを変更する</a>
</body>