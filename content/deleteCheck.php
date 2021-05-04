<!-- content/deleteCheck.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Delete Checking</title>
    <link rel='shortcut icon' href=/res/favicon.ico type=image/x-icon>
    <link rel='icon' href=/res/favicon.ico type=image/x-icon>
    <style>
/************************************outer************************************/

    html{
        height: 97%;
    }

    body {
        height: 100%;
        background: #181A1B;
        margin: 0;
        padding: 0;
        color: white;
        list-style: none;
        font-family: 'arial';
    }

/************************************main************************************/

    div{
        height: 100%;
        height: 20%;
        position: absolute; left: 50%; top: 50%; 
        transform: translate(-50%, -50%); text-align: center;
    }

    p#text{
        font-size: 5ex;
        font-weight: bold;
    }

    </style>

<!--********************************php_script**********************************-->

    <?php
        include "../php/basic.php";
        include "../php/control.php";
        $user = checkUser($_POST['id'], $_POST['pw']);
        $content_info = getContentInfo($_POST['content_index']);
    ?>

    <script src="/js/main.js"></script>
    <script>
        window.onload = function() {
            if(<?php echo $content_info['state']; ?> < 0){
                alert('이미 삭제된 글입니다.')
                homeClick();
            }
            <?php
                $ret = disableContent(
                    $user['user_index'],
                    $user['level'],
                    $content_info['user_index'],
                    $content_info['content_index']);
            ?>
            if(<?php echo $ret; ?> > 0){
                if(<?php echo $user['level']; ?> < 2)
                    contentClick(<?php echo $content_info['content_index']; ?>);
                else
                    homeClick();
            }
            else{
                if(<?php echo $ret; ?> == -1)
                    alert("인증 실패");
                else
                    alert("삭제 실패");
                contentClick(<?php echo $content_info['content_index']; ?>);
            }
        }

    </script>
</head>
<body>
    <div>
        <p id='text'>Delete Checking ...</p>
    </div>
</body>
</html>