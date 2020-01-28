<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　トップページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================

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
// DBから商品データを取得
$dbPostData = getInfoList($currentMinNum);
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
    <section id="info">
       <h1>BLOG</h1>
        <div class="search-right">
            <span class="num"> <span class="num"><?php echo sanitize($dbPostData['total']); ?> post(s)</span> of <?php echo $currentMinNum+1; ?></span> - <span class="num"><?php echo $currentMinNum+$listSpan; ?></span>
        </div>
        
        <?php echo $currentMinNum+1; ?></span> - <span class="num"><?php echo $currentMinNum+$listSpan; ?></span>
        
        
        <!--投稿一覧を取得して表示-->
        <div class="panel-list">
            <?php foreach($dbPostData['data'] as $key => $val): ?>
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

            <?php endforeach; ?>
          
          <!--投稿一覧ここまで-->
          
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
    </section>
</body>

</html>
