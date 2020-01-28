$(function(){
//項目クリックでメニュー非表示
$('#nav-content a[href]').on('click', function(event) {
$('#nav-close').trigger('click');
    });
//Top画像スライド
//（１）ページの概念・初期ページを設定
var page=0;

//（２）イメージの数を最後のページ数として変数化
var lastPage =parseInt($("#top-baner img").length-1);

//（３）最初に全部のイメージを一旦非表示にします
     $("#top-baner img").css("display","none");

//（４）初期ページを表示
          $("#top-baner img").eq(page).css("display","block");

//（５）ページ切換用、自作関数作成
function changePage(){
                         $("#top-baner img").fadeOut(1000);
                         $("#top-baner img").eq(page).fadeIn(1000);
};

//（６）～秒間隔でイメージ切換の発火設定
var Timer;
function startTimer(){
Timer =setInterval(function(){
          if(page === lastPage){
                         page = 0;
                         changePage();
               }else{
                         page ++;
                         changePage();
          };
     },5000);
}
//（７）～秒間隔でイメージ切換の停止設定
function stopTimer(){
clearInterval(Timer);
}

//（８）タイマースタート
startTimer();

window.onload = function() {
  scroll_effect();

  $(window).scroll(function(){
   scroll_effect();
  });

  function scroll_effect(){
   $('.fade').each(function(){
    var elemPos = $(this).offset().top;
    var scroll = $(window).scrollTop();
    var windowHeight = $(window).height();
    if (scroll > elemPos - windowHeight){
     $(this).addClass('scrollin');
    }
   });
  }
};

});