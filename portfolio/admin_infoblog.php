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



// POST送信時処理


if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：'.print_r($_POST,true));
  debug('FILE情報：'.print_r($_FILES,true));

  //変数にユーザー情報を代入
  $name = $_POST['name'];
  $comment = $_POST['comment'];
  //画像をアップロードし、パスを格納
  $pic1 = ( !empty($_FILES['pic1']['name']) ) ? uploadImg($_FILES['pic1'],'pic1') : '';
  // 画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
  $pic1 = ( empty($pic1) && !empty($dbFormData['pic1']) ) ? $dbFormData['pic1'] : $pic1;
  
  // 更新の場合はDBの情報と入力情報が異なる場合にバリデーションを行う
  if(empty($dbFormData)){
    //未入力チェック
    validRequired($name, 'name');
    //最大文字数チェック
    validMaxLen($name, 'name');
    //最大文字数チェック
    validMaxLen($comment, 'comment', 500);
  }else{
    if($dbFormData['name'] !== $name){
      //未入力チェック
      validRequired($name, 'name');
      //最大文字数チェック
      validMaxLen($name, 'name');
    }
    if($dbFormData['comment'] !== $comment){
      //最大文字数チェック
      validMaxLen($comment, 'comment', 500);
    }
}
  if(empty($err_msg)){
    debug('バリデーションOKです。');

    //DB接続
    try {
      
        $dbh = dbConnect();
        $sql = 'insert into infoblog (name, comment, pic1, user_id, create_date ) values (:name, :comment, :pic1,  :u_id, :date)';
        $data = array(':name' => $name , ':comment' => $comment, ':pic1' => $pic1, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
        
        
        debug('SQL：'.$sql);
        debug('流し込みデータ：'.print_r($data,true));
      
      
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      // クエリ成功
      if($stmt){
        $_SESSION['msg_success'] = SUC04;
        debug('投稿ページへ遷移します。');
        header("Location:admin_infoblog.php"); //投稿ページへ戻る
      }

    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
// 画面表示用データ取得
//================================
// カレントページのGETパラメータを取得
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1; //デフォルトは１ページめ
// パラメータに不正な値が入っているかチェック
if(!is_int((int)$currentPageNum)){
  error_log('エラー発生:指定ページに不正な値が入りました');
  header("Location:index.php"); //トップページへ
}
// 表示件数
$listSpan = 20;
// 現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan); //1ページ目なら(1-1)*20 = 0 、 ２ページ目なら(2-1)*20 = 20
// DBから投稿データを取得
$dbPostData = getInfoList($currentMinNum);
debug('現在のページ：'.$currentPageNum);
//debug('フォーム用DBデータ：'.print_r($dbFormData,true));
//debug('カテゴリデータ：'.print_r($dbCategoryData,true));

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
$siteTitle = 'お知らせを投稿';
require('adminhead.php'); 
?>
<body class="page-profEdit page-2colum page-logined">

    <!-- メニュー -->
    <?php
    require('adminheader.php'); 
    ?>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
        <h1 class="page-title">BLOG</h1>
        <h3>Scroll down to show previous posts</h3>
        <!-- Main -->
        <section id="main">
            <div class="form-container">
                <form action="" method="post" class="form" enctype="multipart/form-data" style="width:100%;box-sizing:border-box;">
                    <div class="area-msg">
                        <?php 
              if(!empty($err_msg['common'])) echo $err_msg['common'];
              ?>
                    </div>
                    <label class="<?php if(!empty($err_msg['name'])) echo 'err'; ?>">
                        Title<span class="label-require">Required</span>
                        <input type="text" name="name" value="<?php echo getFormData('name'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php 
              if(!empty($err_msg['name'])) echo $err_msg['name'];
              ?>
                    </div>
                    <label class="<?php if(!empty($err_msg['comment'])) echo 'err'; ?>">
                        CONTENT
                        <textarea name="comment" id="js-count" cols="30" rows="10" style="height:150px;"><?php echo getFormData('comment'); ?></textarea>
                    </label>
                    <p class="counter-text"><span id="js-count-view">0</span>/500 chars</p>
                    <div class="area-msg">
                        <?php 
              if(!empty($err_msg['comment'])) echo $err_msg['comment'];
              ?>
                    </div>
                    <div style="overflow:hidden;">
                        <div class="imgDrop-container">
                            Submit Photos
                            <label class="area-drop <?php if(!empty($err_msg['pic1'])) echo 'err'; ?>">
                                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                <input type="file" name="pic1" class="input-file">
                                <img src="<?php echo getFormData('pic1'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic1'))) echo 'display:none;' ?>">
                                Drag＆Drop
                            </label>
                            <div class="area-msg">
                                <?php 
                  if(!empty($err_msg['pic1'])) echo $err_msg['pic1'];
                  ?>
                            </div>
                        </div>
                    </div>
                    <div class="btn-container">
                        <input type="submit" class="btn btn-mid" value="投稿する">
                    </div>
                </form>
            </div>

            <div class="panel-list">
                <?php
            foreach($dbPostData['data'] as $key => $val):
          ?><a href="edit.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&p_id='.$val['id'] : '?p_id='.$val['id']; ?>" class="panel">
                    <div class="panel-title">
                        <table>
                            <tr>
                                <th>
                                    <h1 class="infotitle"><?php echo sanitize($val['name']); ?></h1>
                                </th>
                                <th><?php echo sanitize($val['create_date']); ?></th>
                            </tr>
                            <tr>
                                <td>
                                    <p class="infobody"><?php echo sanitize($val['comment']); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="panel-head">
                        <a href="<?php echo sanitize($val['pic1']); ?>" target="_blank" rel="noopener noreferrer"><img class="infoimg" src="<?php echo sanitize($val['pic1']); ?>" width="<?php echo $img_size_width; ?>" height="<?php echo $img_size_height; ?> " onerror="this.style.display='none'"></a>
                    </div>
                    <hr />

                    <?php
            endforeach;
          ?>
            </div>

        </section>

        <!-- footer -->
        <?php
    require('uploadphoto.php'); 
    ?>
