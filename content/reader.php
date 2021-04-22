<!-- content/reader.php -->
<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Blog</title>
    <link rel="stylesheet" href="/css/after.css">
    <link rel="stylesheet" href="/css/main_outer.css">
    <link rel="stylesheet" href="/css/main_header.css">
    <link rel="stylesheet" href="/css/main_aside.css">
    <link rel="stylesheet" href="/css/main_detailContent.css">
    <link rel="stylesheet" href="/css/main_media.css">
    <link rel="stylesheet" href="/css/main_footer.css">

    <?php
        include '../php/basic.php';
        include '../php/load.php';
        $user = checkUser($_POST['id'], $_POST['pw']);
    ?>

    <script src="/js/main.js"> </script>
    <script>
        window.onload = function() {
            <?php echoMainOnload($user['user_index']) ?>
        }
    </script>
</head>
<body>
    <div id="main">
        <header> <?php echoHeader($user['user_index'], $user['level']); ?> </header>
        <section>
            <aside>
                <div id=profile> profile </div>
                <ul id=category> <?php echoAsideList($user['level']); ?> </ul>
            </aside>
            <div id=content> <?php echoDetailContent($user['level'], $_POST['content_index']); ?> </div>
        </section>
        <footer> <?php echoFooter(); ?> </footer>
    </div>
</body>
</html>