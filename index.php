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
                <img src="img/top01.jpg">
                <img src="img/top02.jpg">
                <img src="img/top03.jpg">
                <img src="img/top04.jpg">
            </section>

            <!-- ABOUT -->
            <section id="about">
                <div class="fade">
                        <h1 class="title">ABOUT ME</h1>
                        <div class="container">
                        <div class="text">
                        <p>Lives in Melbourne. I started programming and became freelance Web developer in 2019.</p>
                        <p>Highly motivated, execellent teamplayer and enjoying programming. </p>
                        <h3>My Skills:</h3>
                        <p>HTML5 / CSS3 / JavaScript / PHP / Bootstrap and currently studying Laravel + Vue , React.js</p>
                        <h3>Languages:</h3>
                        <p>English / Japanese</p>
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
                        <p>I am currently working on the following projects -</p>
                        <ul class="projects">
                            <li>Developers Matching service Web app</li>
                            <li>WordPress Theme for Lifehack,beauty and health Blog</li>
                        </ul>
                        <h3><a href="https://y-k-web.com/themesample/">>THEME SAMPLE</a></h3>
                        <a href="https://y-k-web.com/themesample/"><img class="sample" src="img/themesample.png"></a>
                      </div>
                    </div>
                </div>
            </section>        
                    
                    

            <section id="contact">
                <div class="fade">
                    <h1 class="title">CONTACT</h1>
                    <div class="container">
                        <div class="form-container">
                            <p><?php if(!empty($msg)) echo $msg; ?></p>
                            <form method="post">

                                <input type="text" name="email" placeholder="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email'];?>">

                                <input type="text" name="subject" placeholder="Title" value="<?php if(!empty($_POST['subject'])) echo $_POST['subject'];?>">

                                <textarea name="comment" placeholder="Message"><?php if(!empty($_POST['comment'])) echo $_POST['comment'];?></textarea>

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
