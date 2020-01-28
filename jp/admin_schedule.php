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
// GETデータを格納
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
// DBから投稿データを取得
$dbFormData = (!empty($p_id)) ? getInfo($_SESSION['user_id'], $p_id) : '';

// POST送信時処理
//================================
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：'.print_r($_POST,true));
  debug('FILE情報：'.print_r($_FILES,true));
  //変数にユーザー情報を代入 
  $s_date = $_POST['s_date'];
  $eventname = $_POST['eventname'];
  $s_time = $_POST['s_time'];
  $e_time =  $_POST['e_time'];
      

  // 更新の場合はDBの情報と入力情報が異なる場合にバリデーションを行う
  if(empty($dbFormData)){
    //未入力チェック
    validRequired($s_date, 's_date');
  if(empty($_POST['delete'])){
      validRequired($eventname, 'eventname');
  }
    //最大文字数チェック
    //validMaxLen($s_date, 's_date');
    //最大文字数チェック
    validMaxLen($eventname, 'eventname', 20);
  }else{
    if($dbFormData['s_date'] !== $name){
      //未入力チェック
      validRequired($s_date, 's_date');
      //最大文字数チェック
      validMaxLen($s_date, 's_date');
    }
    if($dbFormData['eventname'] !== $eventname){
      //最大文字数チェック
      validMaxLen($eventname, 'eventname', 20);
    }
}
  if(empty($err_msg)){
    debug('バリデーションOKです。');

    //DB接続
    try {
      
        $dbh = dbConnect();
        //DBへ新規登録
        if($_POST['save']){
            $sql = 'insert into schedule (schedule_date, eventname, start_time, end_time ) values (:s_date, :eventname, :s_time,  :e_time)';
            $data = array(':s_date' => $s_date,':eventname' => $eventname, ':s_time' => $s_time,':e_time' => $e_time);
        }elseif($_POST['delete']){
            $sql = ('DELETE FROM schedule WHERE schedule_date = :s_date;');
            $data = array(':s_date' => $s_date);
        }
        
        debug('SQL：'.$sql);
        debug('流し込みデータ：'.print_r($data,true));
      
      
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      // クエリ成功
      if($stmt){
        $_SESSION['msg_success'] = SUC04;
        debug('投稿ページへ遷移します。');
        header("Location:admin_schedule.php"); //投稿ページへ戻る
      }

    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}

?>
<?php
$siteTitle = '行事予定編集';
require('adminhead.php'); 
?>

<body class="page-profEdit page-2colum page-logined">

    <!-- メニュー -->
    <?php
    require('adminheader.php'); 
    ?>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
        <h1 class="page-title">行事予定を登録</h1>
        <h3>行事予定カレンダーに表示される内容を登録します。</h3>
        <?php if(!empty ($e)){echo $err_msg['common'];} ?>

        <!-- Main -->
        <section id="main">
            <div class="form-container">
                <form action="" method="post" class="form" enctype="multipart/form-data" style="width:100%;box-sizing:border-box;">
                    <div class="area-msg">
                        <?php 
              if(!empty($err_msg['common'])) echo $err_msg['common'];
              ?>
                    </div>
                    <label class="<?php if(!empty($err_msg['s_date'])) echo 'err'; ?>">
                        行事日付（削除する場合は削除したい日付を選択）<span class="label-require">必須</span>
                        <input type="date" name="s_date" style = height:30px; value="<?php echo getFormData('s_date'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php 
              if(!empty($err_msg['s_date'])) echo $err_msg['s_date'];
              ?>
                    </div>
                    <label class="<?php if(!empty($err_msg['eventname'])) echo 'err'; ?>">
                        行事名
                        <textarea name="eventname" id="js-count" cols="10" rows="10"><?php echo getFormData('eventname'); ?></textarea>
                    </label>
                    <p class="counter-text"><span id="js-count-view"></span>/20文字</p>
                    <div class="area-msg">
                        <?php 
              if(!empty($err_msg['eventname'])) echo $err_msg['eventname'];
              ?>
                    </div>
                                        <label class="<?php if(!empty($err_msg['s_date'])) echo 'err'; ?>">
                                        開始時間
                                        <input type="time" name="s_time" style = height:30px; value="<?php echo getFormData('s_date'); ?>">
                                        </label>
                                        <label class="<?php if(!empty($err_msg['s_date'])) echo 'err'; ?>">
                                        終了時間
                                        <input type="time" name="e_time" style = height:30px; value="<?php echo getFormData('s_date'); ?>">
                                        </label>
                    <div class="btn-container">
                        <input type="submit" class="btn btn-mid" name="delete" value = "予定を削除する">
                        <input type="submit" class="btn btn-mid" name="save" value="予定を登録する">
                    </div>
                </form>
            </div>
        </section>
<?php require('calender.php'); ?>