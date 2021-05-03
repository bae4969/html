<!-- content/editCheck.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Edit Checking</title>
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
    ?>

    <script src="/js/main.js"></script>
    <script>
        window.onload = function() {
            <?php
                $ret = editContent(
                    $user['user_index'],
                    $_POST['content_index'],
                    $_POST['title'],
                    $_POST['thumbnail'],
                    $_POST['summary'],
                    $_POST['content']);
            ?>
            if(<?php echo $ret; ?> > 0){
                sessionStorage.removeItem('title');
                sessionStorage.removeItem('content');
            }
            else{
                if(<?php echo $ret; ?> == -1)
                    alert("인증 실패");
                else if(<?php echo $ret; ?> == -2)
                    alert("제목이 최대 문자열 길이를 초과했습니다.");
                else if(<?php echo $ret; ?> == -3)
                    alert("썸네일이 최대 문자열 길이를 초과했습니다.");
                else if(<?php echo $ret; ?> == -4)
                    alert("요약이 최대 문자열 길이를 초과했습니다.");
                else if(<?php echo $ret; ?> == -5)
                    alert("내용이 최대 문자열 길이를 초과했습니다.");
                else if(<?php echo $ret; ?> == -6)
                    alert("입력 불가능한 문자열이 포함되어 있습니다.");
                else
                    alert("저장 실패");
            }
            contentClick(<?php echo $_POST['content_index']; ?>);
        }

    </script>
</head>
<body>
    <div>
        <p id='text'>Edit Checking ...</p>
    </div>
</body>
</html>
