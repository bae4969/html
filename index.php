<!-- index.php -->
<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Index</title>
    <link rel="stylesheet" href="/css/after.css">
    <link rel="stylesheet" href="/css/index/main_outer.css">
    <link rel="stylesheet" href="/css/index/main_header.css">
    <link rel="stylesheet" href="/css/index/main_footer.css">
    <link rel="stylesheet" href="/css/index/main_index.css">

    <?php
        include 'php/index.php';
        $user = checkUser($_POST['id'], $_POST['pw']);
    ?>

    <script src="/js/index.js"> </script>
    <script>
        window.onload = function() {
            <?php echoMainOnload($user['user_index']) ?>

            setContentList();
        }
        window.onresize = function(){
            setContentList()
        }

        var showState = 0;
        var contentSize = 2;
        function setContentList(){
            if(showState != 2 && document.body.offsetWidth < 1600){
                showState = 2;
                document.getElementById('temp').style.height = 0;
                for(i = 0; i < contentSize; i++)
                    document.getElementById('temp').appendChild(document.getElementById("content"+i));

                document.getElementById('left').style.width = '100%';
                document.getElementById('right').style.width = '0%';
                for(i = 0; i < contentSize; i++)
                    document.getElementById('left').appendChild(document.getElementById("content"+i));
            }
            else if(showState != 1 && document.body.offsetWidth >= 1600){
                showState = 1;
                document.getElementById('temp').style.height = 0;
                for(i = 0; i < contentSize; i++)
                    document.getElementById('temp').appendChild(document.getElementById("content"+i));

                document.getElementById('left').style.width = '50%';
                document.getElementById('right').style.width = '50%';
                for(i = 0; i < contentSize; i++){
                    if(document.getElementById('left').offsetHeight > document.getElementById('right').offsetHeight)
                        document.getElementById('right').appendChild(document.getElementById("content"+i));
                    else
                        document.getElementById('left').appendChild(document.getElementById("content"+i));
                }
            }
        }
    </script>
</head>
<body>
    <div id=main>
        <header> <?php echoHeader($user['user_index']); ?> </header>
        <nav>
            <div id=nav>
                <div class=nav_icon onclick=blogClick() >
                    <img class=nav_icon src="/res/index/index_blog_nav.png" alt="blog"/>
                </div>
            </div>
        </nav>
        <section>
            <div id=contents>
                <div>
                    <div id=left></div>
                    <div id=right></div>
                </div>
                <div id=temp>
                    <div id=content0 class=content>
                        <iframe src='widget/weather/weather.php' frameborder=0 scrolling=no style='width: 100%; height: 415px; display: inline-block; border-radius: 10px;'></iframe>
                    </div>
                    <div id=content1 class=content>
                        <iframe src='widget/dust/dust.php' frameborder=0 scrolling=no style='width: 100%; height: 680px; display: inline-block; border-radius: 10px;'></iframe>
                    </div>
                </div>
            </div>
        </section>
        <footer>
            <p>Contact : bae4969@naver.com</br>
            Github : <a class=footer href=https://github.com/bae4969>https://github.com/bae4969</a></p>
        </footer>
    </div>
</body>
</html>

