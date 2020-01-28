<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　お知らせ投稿・編集ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');
// GETデータを格納
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
// DBから投稿データを取得
$dbFormData = (!empty($p_id)) ? getAlbum($_SESSION['user_id'], $p_id) : '';
// DBからカテゴリデータを取得
$dbCategoryData = getCategory();
debug('カテゴリデータ：'.print_r($dbCategoryData,true));

 //パラメータ改ざんチェック
//================================
// GETパラメータはあるが、改ざんされている（URLをいじくった）場合、正しい商品データが取れないのでマイページへ遷移させる
//if(empty($dbFormData)){
  //debug('GETパラメータの商品IDが違います。マイページへ遷移します。');
  //header("Location:admin_infoblog.php"); //マイページへ
//}
// POST送信時処理
//================================
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：'.print_r($_POST,true));
  debug('FILE情報：'.print_r($_FILES,true));
    //変数にユーザー情報を代入
  //$name = $_POST['name'];
  $comment = $_POST['comment'];
  $category = $_POST['category_id'];
  //画像をアップロードし、パスを格納
  $albumpic = ( !empty($_FILES['albumpic']['name']) ) ? uploadAlbum($_FILES['albumpic'],'albumpic') : '';
  // 画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
  $albumpic = ( empty($albumpic) && !empty($dbFormData['albumpic']) ) ? $dbFormData['albumpic'] : $albumpic;
    // 更新の場合はDBの情報と入力情報が異なる場合にバリデーションを行う
  if(empty($dbFormData)){
    //未入力チェック
    //validRequired($albumpic, 'albumpic');
    //最大文字数チェック
    //validMaxLen($name, 'name');
    //セレクトボックスチェック
    validSelect($category, 'category_id');
    //最大文字数チェック
    validMaxLen($comment, 'comment', 500);
  }else{
    //if($dbFormData['name'] !== $name){
      //未入力チェック
      //validRequired($name, 'name');
      //最大文字数チェック
      //validMaxLen($name, 'name');
    //}
    if($dbFormData['category_id'] !== $category){
      //セレクトボックスチェック
      validSelect($category, 'category_id');
    }
    //if($dbFormData['comment'] !== $comment){
      //最大文字数チェック
      //validMaxLen($comment, 'comment', 500);
    //}
  }
  if(empty($err_msg)){
    debug('バリデーションOKです。');

      //DB接続
    try {
      $dbh = dbConnect();
      //DB更新
      if($_POST['edit']){
          debug('DB更新です。');
        $sql = 'UPDATE album SET category_id = :category_id, comment = :comment, albumpic = :albumpic WHERE user_id = :u_id AND id = :p_id';
        $data = array(':category_id' => $category,':comment' => $comment, ':albumpic' => $albumpic, ':u_id' => $_SESSION['user_id'], ':p_id' => $p_id);
     }elseif($_POST['delete']){
          debug('該当データを論理削除');
         $sql = 'UPDATE album SET delete_flg = 1 WHERE user_id = :u_id AND id = :p_id';
         $data = array(':u_id' => $_SESSION['user_id'], ':p_id' => $p_id);
      }
        
      debug('SQL：'.$sql);
      debug('流し込みデータ：'.print_r($data,true));
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      // クエリ成功の場合
      if($stmt){
        $_SESSION['msg_success'] = SUC04;
        debug('投稿ページへ遷移します。');
        unset($p_id);
        header("Location:admin_album.php"); //投稿ページへ戻る
      }
          
    }catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}

?>
<?php
    var_dump($_POST);
?>
<?php
 var_dump($_GET['p_id']); ?>
<?php
$siteTitle = '投稿編集';
require('adminhead.php'); 
?>

<body>

    <!-- メニュー -->
    <?php
    require('adminheader.php'); 
    ?>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
        <h1 class="page-title">記事ID[<?php echo ($p_id); ?>]の投稿を編集</h1>
        <h3>投稿を編集または削除します。</h3>
        <?php if(!empty ($e)){echo $err_msg['common'];} ?>
        <?php var_dump ($err_msg); ?>
        <!-- Main -->
        <section id="main">
            <div class="form-container">
                <form action="" method="post" class="form" enctype="multipart/form-data" style="width:100%;box-sizing:border-box;">
                    <div class="area-msg">
                        <?php 
              if(!empty($err_msg['common'])) echo $err_msg['common'];
              ?>
                    </div>
                    <div style="overflow:hidden;">
                        <div class="imgDrop-container">
                            投稿する画像
                            <label class="area-drop <?php if(!empty($err_msg['albumpic'])) echo 'err'; ?>">
                                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                <input type="file" name="albumpic" class="input-file">
                                <img src="<?php echo getFormData('albumpic'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('albumpic'))) echo 'display:none;' ?>">
                                ドラッグ＆ドロップ
                            </label>
                            <div class="area-msg">
                                <?php 
                  if(!empty($err_msg['albumpic'])) echo $err_msg['albumpic'];
                  ?>
                            </div>
                        </div>
                    </div>
                    <!--<label class="<?php //if(!empty($err_msg['name'])) echo 'err'; ?>">
                        タイトル<span class="label-require">必須</span>
                        <input type="text" name="name" value="<?php //echo getFormData('name'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php 
              //if(!empty($err_msg['name'])) echo $err_msg['name'];
              ?>
                    </div>-->
                    <label class="<?php if(!empty($err_msg['category_id'])) echo 'err'; ?>">
              登録するアルバム<span class="label-require">必須</span>
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
            <div class="area-msg">
              <?php 
              if(!empty($err_msg['category_id'])) echo $err_msg['category_id'];
              ?>
            </div>
                    <label class="<?php if(!empty($err_msg['comment'])) echo 'err'; ?>">
                        コメント
                        <textarea name="comment" id="js-count" cols="30" rows="10" style="height:150px;"><?php echo getFormData('comment'); ?></textarea>
                    </label>
                    <p class="counter-text"><span id="js-count-view">0</span>/500文字</p>
                    <div class="area-msg">
                        <?php 
              if(!empty($err_msg['comment'])) echo $err_msg['comment'];
              ?>
                    </div>
                    <div class="btn-container">
                        <input type="submit" class="btn btn-mid" name = "delete" value="削除する">
                         <input type="submit" class="btn btn-mid" name = "edit" value="更新する">
                    </div>
                </form>
            </div>
        </section>

        <!-- footer -->
        <?php
    require('uploadphoto.php'); 
    ?>