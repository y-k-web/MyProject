<?php
error_reporting(E_ALL & ~E_NOTICE);
//タイムゾーンを設定
date_default_timezone_set('Asia/Tokyo');
//共通変数・関数ファイルを読込み
require('function.php');

//POST送信時処理
//$category = $_POST['category_id'];



debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　トップページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================

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
$siteTitle ='お知らせ';
require('head.php');
?>
<body>
<?php
require('header.php')
?>
      <!-- Main -->
      <section id="main" >
           <h1>PHOTOS</h1>
            <form action="" method="post" class="form" enctype="multipart/form-data" style="width:100%;box-sizing:border-box;">
                    <label class="<?php if(!empty($err_msg['category_id'])) echo 'err'; ?>">
              CHOOSE ALBUM
              <select name="category_id" id="">
                <option value="0" <?php if(getFormData('category_id') == 0 ){ echo 'selected'; } ?> >Choose Album</option>
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
                        <input type="submit" class="btn btn-mid" name="display" value = "Show" style=" float:initial";?>
                </form>
        <div class="search-title">
          <div class="search-left">
            <span class="total-num"><?php echo sanitize($dbPostData['total']); ?></span> Photos
          </div>
          <div class="search-right">
            <span class="num"><?php echo $currentMinNum+1; ?></span> - <span class="num"><?php echo $currentMinNum+$listSpan; ?></span> of <span class="num"><?php echo sanitize($dbPostData['total']); ?> page(s)</span>
          </div>
        </div>
        <div class="panel-list">
         <?php
            foreach($dbPostData['data'] as $key => $val):
          ?>
            <div class="panel-head">
                <a href="<?php echo sanitize($val['albumpic']); ?>" target="_blank" rel="noopener noreferrer"><img class="infoimg" src="<?php echo sanitize($val['albumpic']); ?>" width="<?php echo $img_size_width; ?>" height="<?php echo $img_size_height; ?> " onerror="this.style.display='none'"></a>
            </div>
            <p class="infobody">
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


