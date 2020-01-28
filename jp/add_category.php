<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　お知らせ投稿・編集ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');
//タイムゾーンを設定
date_default_timezone_set('Asia/Tokyo');
//================================
// 画面処理
//================================
// DBからカテゴリデータを取得
$dbCategoryData = getCategory();
debug('カテゴリデータ：'.print_r($dbCategoryData,true));

// POST送信時処理
//================================
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：'.print_r($_POST,true));
  debug('FILE情報：'.print_r($_FILES,true));

  //変数にユーザー情報を代入
  $name = $_POST['name'];
  // 更新の場合はDBの情報と入力情報が異なる場合にバリデーションを行う
  if(empty($dbFormData)){
    //未入力チェック
    validRequired($name, 'name');
    //最大文字数チェック
    validMaxLen($name, 'name');
    //if($dbFormData['name'] !== $name){
      //未入力チェック
      validRequired($name, 'name');
      //最大文字数チェック
      validMaxLen($name, 'name');
    }
  }
  if(empty($err_msg)){
    debug('バリデーションOKです。');

    //例外処理
    try {
      // DBへ接続
      $dbh = dbConnect();{
        $sql = 'insert into category ( name, create_date ) values ( :name, :date)';
        $data = array( ':name' => $name,':date' => date('Y-m-d H:i:s'));
        }
      debug('SQL：'.$sql);
      debug('流し込みデータ：'.print_r($data,true));
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      // クエリ成功の場合
      if($stmt){
        $_SESSION['msg_success'] = SUC04;
        debug('投稿ページへ遷移します。');
        header("Location:admin_album.php"); //マイページへ
      }

    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
   }
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = 'カテゴリ追加';
require('adminhead.php'); 
?>
<?php var_dump($_POST); ?>
<body>

    <!-- メニュー -->
    <?php
    require('adminheader.php'); 
    ?>

    <!-- メインコンテンツ -->
    <section id="main" >
    <div id="contents" class="site-width">
        <h1 class="page-title">カテゴリを追加する</h1>
        <!-- Main -->
        <section id="main">
            <div class="form-container">

                <form action="" method="post" class="form" enctype="multipart/form-data" style="width:100%;box-sizing:border-box;">
                                    <label class="<?php if(!empty($err_msg['name'])) echo 'err'; ?>">
                        カテゴリ名<span class="label-require">未入力は送信できません</span>
                        <input type="text" name="name" value="<?php echo getFormData('name'); ?>">
                    </label>
                    <input type="submit" class="btn btn-mid" name="category_post" value = "カテゴリ追加";?>
                </form>
            </div>
        </section>