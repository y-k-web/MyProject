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

  $category = $_POST['category_id'];
  
  if(empty($dbFormData)){
    //セレクトボックスチェック
    validSelect($category, 'category_id');
      
  }elseif($dbFormData['category_id'] !== $category){
      //セレクトボックスチェック
      validSelect($category, 'category_id');
    }
  if(empty($err_msg)){
    debug('バリデーションOKです。');

    //例外処理
    try {
      // DBへ接続
      $dbh = dbConnect();
        //新規登録
        debug('カテゴリ削除');
        if($_POST['delete']){
        $sql = ('DELETE FROM category WHERE id = :category_id');
        }
        $data = array(':category_id' => $category);
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
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = 'アルバムへ投稿';
require('adminhead.php'); 
?>
<?php var_dump($_POST); ?>
<body>

    <!-- メニュー -->
    <?php
    require('adminheader.php'); 
    ?>
    <?php var_dump($_POST['category_id']); ?>
    <!-- メインコンテンツ -->
    <section id="main" >
    <div id="contents" class="site-width">
        <h1 class="page-title">アルバムへ画像を投稿</h1>
        <!-- Main -->
        <section id="main">
            <div class="form-container">
                <form action="" method="post" class="form" enctype="multipart/form-data" style="width:100%;box-sizing:border-box;">
                    <label class="<?php if(!empty($err_msg['category_id'])) echo 'err'; ?>">
              削除するアルバムを選ぶ<span class="label-require">必須</span>
              <select name="category_id" id="">
                <option value="0" <?php if(getFormData('category_id') == 0 ){ echo 'selected'; } ?> >選択してください</option>
                <?php
                  foreach($dbCategoryData as $key => $val){
                ?>
                  <option value="<?php echo $val['id'] ?>" <?php if(getFormData('category_id') == $val['id'] ){ echo 'selected'; } ?> >
                    <?php echo $val['name']; ?>
                  </option>
                <?php
                  }
                ?>
              </select>
            </label>
                        <input type="submit" class="btn btn-mid" name="delete" value = "カテゴリ削除";?>
                </form>
            </div>
        </section>

        <!-- footer -->
        <?php
    require('uploadphoto.php'); 
    ?>