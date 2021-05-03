<!-- content/reader.php -->
<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Blog</title>
    <link rel='shortcut icon' href=/res/favicon.ico type=image/x-icon>
    <link rel='icon' href=/res/favicon.ico type=image/x-icon>
    <link rel="stylesheet" href="/css/after.css">
    <link rel="stylesheet" href="/css/main_outer.css">
    <link rel="stylesheet" href="/css/main_header.css">
    <link rel="stylesheet" href="/css/main_aside.css">
    <link rel="stylesheet" href="/css/main_detailContent.css">
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

        function editClick(){
            var form = getDefaultPostForm('/content/edit');
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', 'content_index');
            hiddenField.setAttribute('value', <?php echo $_POST['content_index']; ?>);
            form.appendChild(hiddenField);
            document.body.appendChild(form);
            form.submit();
        }
        
        function deleteClick(){
            var form = getDefaultPostForm('/content/deleteCheck');
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', 'content_index');
            hiddenField.setAttribute('value', <?php echo $_POST['content_index']; ?>);
            form.appendChild(hiddenField);
            document.body.appendChild(form);
            form.submit();
        }
        
        function restoreClick(){
            var form = getDefaultPostForm('/content/restoreCheck');
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', 'content_index');
            hiddenField.setAttribute('value', <?php echo $_POST['content_index']; ?>);
            form.appendChild(hiddenField);
            document.body.appendChild(form);
            form.submit();
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
            <?php echoDetailContent($user['user_index'], $user['level'], $_POST['content_index']); ?>
        </section>
        <footer> <?php echoFooter(); ?> </footer>
    </div>
</body>
</html>