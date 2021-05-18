<!-- content/restoreCheck.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Delete Checking</title>
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
        include "../php/blog.php";
        $user = checkUser($_POST['id'], $_POST['pw']);
        $content_info = getContentInfo($_POST['content_index']);
    ?>

    <script src="/js/blog.js"></script>
    <script>
        window.onload = function() {
            if(<?php echo $content_info['state']; ?> >= 0){
                alert('이미 복구된 글입니다.')
                contentClick(<?php echo $content_info['content_index']; ?>);
            }
            <?php
                $ret = restoreContent(
                    $user['level'],
                    $content_info['content_index']);
            ?>
            if(<?php echo $ret; ?> > 0){
                contentClick(<?php echo $content_info['content_index']; ?>);
            }
            else{
                if(<?php echo $ret; ?> == -1)
                    alert("인증 실패");
                else
                    alert("복구 실패");
                contentClick(<?php echo $content_info['content_index']; ?>);
            }
        }

    </script>
</head>
<body>
    <div>
        <p id='text'>Restore Checking ...</p>
    </div>
</body>
</html>
