<!-- index.php -->
<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Index</title>
    <link rel='shortcut icon' href=/res/favicon.ico type=image/x-icon>
    <link rel='icon' href=/res/favicon.ico type=image/x-icon>
    <link rel="stylesheet" href="/css/after.css">
    <link rel="stylesheet" href="/css/index/main_outer.css">
    <link rel="stylesheet" href="/css/index/main_header.css">
    <link rel="stylesheet" href="/css/index/main_footer.css">

    <?php
        include 'php/index.php';
        $user = checkUser($_POST['id'], $_POST['pw']);
    ?>

    <script src="/js/index.js"> </script>
    <script>
        window.onload = function() {
            <?php echoMainOnload($user['user_index']) ?>
        }
    </script>
</head>
<body>
    <div id=main>
        <header> <?php echoHeader($user['user_index']); ?> </header>
        <nav>
            <img id=blog onclick=blogClick() src="/res/index/index_blog_nav.png" alt="Index Page"/>
        </nav>
        <section>
            <aside>
            </aside>
            <div id=contents>
                <div>
                    <div id=left></div>
                    <div id=right></div>
                </div>
                <div id=temp>
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

