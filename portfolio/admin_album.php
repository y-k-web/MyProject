<?php
error_reporting(E_ALL & ~E_NOTICE);
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

    //例外処理
    try {
      // DBへ接続
      $dbh = dbConnect();
        //新規登録
        debug('DB新規登録です。');
        if($_POST['pic_post']){
        $sql = 'insert into album ( category_id, comment, albumpic, user_id, create_date ) values ( :category, :comment, :albumpic,  :u_id, :date)';
        $data = array( ':category' => $category, ':comment' => $comment, ':albumpic' => $albumpic, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
        //カテゴリ追加
        }elseif($_POST['category_post']){
        $sql = 'insert into category ( name, user_id, create_date ) values ( :name :u_id, :date)';
        $data = array( ':name' => $name,':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
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
 }
// ===========================
//   アルバム表示部分
//==============================
// DBからカテゴリデータを取得
$dbCategoryData = getCategory();
debug('カテゴリデータ：'.print_r($dbCategoryData,true));
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
// DBから商品データを取得
$dbPostData = getAlbumList($currentMinNum);
debug('現在のページ：'.$currentPageNum);
//debug('フォーム用DBデータ：'.print_r($dbFormData,true));
//debug('カテゴリデータ：'.print_r($dbCategoryData,true));

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = 'アルバムへ投稿';
require('adminhead.php'); 
?>
<body>

    <!-- メニュー -->
    <?php
    require('adminheader.php'); 
    ?>

    <!-- メインコンテンツ -->
    <section id="main" >
    <div id="contents" class="site-width">
        <h1 class="page-title">PHOTOS EDIT</h1>
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
                            SELECTED PHOTO
                            <label class="area-drop <?php if(!empty($err_msg['albumpic'])) echo 'err'; ?>">
                                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                <input type="file" name="albumpic" class="input-file">
                                <img src="<?php echo getFormData('albumpic'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('albumpic'))) echo 'display:none;' ?>">
                                Drag and Drop
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
            <a href="add_category.php">カテゴリを追加する</a>
            <a href="delete_category.php">カテゴリを削除する</a>
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
                        <input type="submit" class="btn btn-mid" name="pic_post" value = "投稿する";?>
                    </div>
                </form>
            </div>
            <div>

<?php
$siteTitle ='フォトアルバム'; ?>
      <!-- Main -->
      <section id="main" >
           <h1>長延寺フォトアルバム</h1>
            <form action="" method="post" class="form" enctype="multipart/form-data" style="width:100%;box-sizing:border-box;">
                    <label class="<?php if(!empty($err_msg['category_id'])) echo 'err'; ?>">
              表示するアルバムを選ぶ
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
                        <input type="submit" class="btn btn-mid" name="display" value = "表示する";?>
                </form>
        <div class="search-title">
          <div class="search-left">
            フォト<span class="total-num"><?php echo sanitize($dbPostData['total']); ?></span>枚
          </div>
          <div class="search-right">
            <span class="num"><?php echo $currentMinNum+1; ?></span> - <span class="num"><?php echo $currentMinNum+$listSpan; ?></span>枚 / <span class="num"><?php echo sanitize($dbPostData['total']); ?></span>件中
          </div>
        </div>
        <div class="panel-list">
         <?php
            foreach($dbPostData['data'] as $key => $val):
          ?>
            <div class="panel-head">
                <a href="<?php echo sanitize($val['albumpic']); ?>" target="_blank" rel="noopener noreferrer"><img class="infoimg" src="<?php echo sanitize($val['albumpic']); ?>" width="<?php echo $img_size_width; ?>" height="<?php echo $img_size_height; ?> " onerror="this.style.display='none'"></a>
            </div>
            <a href="albumedit.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&p_id='.$val['id'] : '?p_id='.$val['id']; ?>" class="panel"><p class="infobody">
                <?php echo sanitize($val['comment']); ?></p>
          <?php
            endforeach;
          ?>
        </div>
        
        
       <!--ページネーション-->
       <div class="pagination">
          <ul class="pagination-list">
            <?php
              $pageColNum = 5;
              $totalPageNum = $dbPostData['total_page'];
              // 現在のページが、総ページ数と同じ　かつ　総ページ数が表示項目数以上なら、左にリンク４個出す
              if( $currentPageNum == $totalPageNum && $totalPageNum >= $pageColNum){
                $minPageNum = $currentPageNum - 4;
                $maxPageNum = $currentPageNum;
              // 現在のページが、総ページ数の１ページ前なら、左にリンク３個、右に１個出す
              }elseif( $currentPageNum == ($totalPageNum-1) && $totalPageNum >= $pageColNum){
                $minPageNum = $currentPageNum - 3;
                $maxPageNum = $currentPageNum + 1;
              // 現ページが2の場合は左にリンク１個、右にリンク３個だす。
              }elseif( $currentPageNum == 2 && $totalPageNum >= $pageColNum){
                $minPageNum = $currentPageNum - 1;
                $maxPageNum = $currentPageNum + 3;
              // 現ページが1の場合は左に何も出さない。右に５個出す。
              }elseif( $currentPageNum == 1 && $totalPageNum >= $pageColNum){
                $minPageNum = $currentPageNum;
                $maxPageNum = 5;
              // 総ページ数が表示項目数より少ない場合は、総ページ数をループのMax、ループのMinを１に設定
              }elseif($totalPageNum < $pageColNum){
                $minPageNum = 1;
                $maxPageNum = $totalPageNum;
              // それ以外は左に２個出す。
              }else{
                $minPageNum = $currentPageNum - 2;
                $maxPageNum = $currentPageNum + 2;
              }
            ?>
            <?php if($currentPageNum != 1): ?>
              <li class="list-item"><a href="?p=1">&lt;</a></li>
            <?php endif; ?>
            <?php
              for($i = $minPageNum; $i <= $maxPageNum; $i++):
            ?>
              <li class="list-item <?php if($currentPageNum == $i ) echo 'active'; ?>"><a href="?p=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php
              endfor;
            ?>
            <?php if($currentPageNum != $maxPageNum): ?>
              <li class="list-item"><a href="?p=<?php echo $maxPageNum; ?>">&gt;</a></li>
            <?php endif; ?>
          </ul>
        </div>
        </body>
    </section>

    </div>



            </div>
        </section>

        <!-- footer -->
        <?php
    require('uploadphoto.php'); 
    ?>