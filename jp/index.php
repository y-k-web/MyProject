    <?php
require('function.php');
?>
    <?php
$siteTitle ='Yuki Nagatsuka';
require('head.php');
?>

    <body>

        <?php
require('header.php');
?>

        <!--メインコンテンツ-->
        <div id="main">
            <section id="top-baner">
                <img src="../img/top01.jpg">
                <img src="../img/top02.jpg">
                <img src="../img/top03.jpg">
                <img src="../img/top04.jpg">
            </section>

            <!-- ABOUT -->
            <section id="about">
                <div class="fade">
                        <h1 class="title">ABOUT ME</h1>
                        <div class="container">
                        <div class="text">
                        <p>神奈川県川崎市出身、メルボルン在住。フリーランスでWeb制作をしています。</p>
                        <p>2019年から学び始め、歴一年程度になりますが、日々新しいことを学び、スキルを磨いています。 </p>

                        <h3>使用言語/スキル:</h3>
                        <p>HTML5 / CSS3 / JavaScript / PHP / Bootstrap</p>
                        <p>現在、Laravel,Vue.js,React.js勉強中です。</p>
                        <h3>言語:</h3>
                        <p>英語 / 日本語</p>
                      </div>
                    </div>
                </div>
            </section>
            
             <!-- WORKS -->
            <section id="works">
                <div class="fade">
                        <h1 class="title">WORKS</h1>
                        <div class="container">
                        <div class="text">
                        <h3>・Webサイト制作・Webサービス開発
                        </h3>
                        <h3>・WordPressテーマ制作</h3>
                        <p>現在、以下のようなプロジェクトを進めています。</p>
                        <ul class="projects">
                            <li>エンジニア同士のマッチングサービス</li>
                            <li>WordPressブログテーマ制作</li>
                        </ul>
                        <h3><a href="https://y-k-web.com/themesample/">>テーマサンプル</a></h3>
                        <a href="https://y-k-web.com/themesample/"><img class="sample" src="../img/themesample.png"></a>
                        <h3>・動画用BGM・効果音素材制作</h3>
                        <p>制作したBGM素材の一部を公開します。</p>
                        
                        <h3 style="color:gray;">・動画編集(近日開始)</h3>
                        
                      </div>
                    </div>
                </div>
            </section>        

            <section id="contact">
                <div class="fade">
                    <h1 class="title">CONTACT</h1>
                    <div class="container">
                       <p>お仕事の依頼等はこちらのフォーム、Eメール、もしくはツイッターのDMからお願いします。</p>
                        <div class="form-container">
                            <p><?php if(!empty($msg)) echo $msg; ?></p>
                            <form method="post">

                                <input type="text" name="email" placeholder="Eメール" value="<?php if(!empty($_POST['email'])) echo $_POST['email'];?>">

                                <input type="text" name="subject" placeholder="タイトル" value="<?php if(!empty($_POST['subject'])) echo $_POST['subject'];?>">

                                <textarea name="comment" placeholder="内容"><?php if(!empty($_POST['comment'])) echo $_POST['comment'];?></textarea>

                                <input type="submit" value="Send Message">

                            </form>
                            Twitter: @naga_yuu <br />
                            <br />
                        </div>
                    </div>
                </div>
            </section>
            <?php
    require('footer.php');
    ?>

    </body>

    </html>
