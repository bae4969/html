<!-- blog.php -->
<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Blog</title>
    <link rel="stylesheet" href="/css/after.css">
    <link rel="stylesheet" href="/css/blog/main_outer.css">
    <link rel="stylesheet" href="/css/blog/main_header.css">
    <link rel="stylesheet" href="/css/blog/main_aside.css">
    <link rel="stylesheet" href="/css/blog/main_contents.css">
    <link rel="stylesheet" href="/css/blog/main_footer.css">

    <?php
        include 'php/blog.php';
        $user = checkUser($_POST['id'], $_POST['pw']);
        $contentList = echoContentList($user['level'], $_POST['page_num'], $_POST['class_index']);
    ?>

    <script src="/js/blog.js"> </script>
    <script>
        window.onload = function() {
            <?php echoMainOnload($user['user_index']) ?>

            setContentList();
        }
        window.onresize = function(){
            setContentList()
        }

        var showState = 0;
        function setContentList(){
            if(showState != 2 && document.body.offsetWidth < 1600){
                showState = 2;
                document.getElementById('temp').style.height = 0;
                for(i = 0; i < <?php echo $contentList[0]; ?>; i++)
                    document.getElementById('temp').appendChild(document.getElementById("content"+i));

                document.getElementById('left').style.width = '100%';
                document.getElementById('right').style.width = '0%';
                for(i = 0; i < <?php echo $contentList[0]; ?>; i++)
                    document.getElementById('left').appendChild(document.getElementById("content"+i));
            }
            else if(showState != 1 && document.body.offsetWidth >= 1600){
                showState = 1;
                document.getElementById('temp').style.height = 0;
                for(i = 0; i < <?php echo $contentList[0]; ?>; i++)
                    document.getElementById('temp').appendChild(document.getElementById("content"+i));

                document.getElementById('left').style.width = '50%';
                document.getElementById('right').style.width = '50%';
                for(i = 0; i < <?php echo $contentList[0]; ?>; i++){
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
        <header> <?php echoHeader($user['user_index'], $user['level']); ?> </header>
        <section>
            <aside>
                <div id=profile> profile </div>
                <ul id=category> <?php echoAsideList($user['level']); ?> </ul>
            </aside>
            <div id=contents>
                <div>
                    <div id=left></div>
                    <div id=right></div>
                </div>
                <div id=pages>
                    <?php echo $contentList[1]; ?>
                </div>
                <div id=temp>
                    <?php echo $contentList[2]; ?>
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
