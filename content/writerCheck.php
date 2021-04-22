<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Login Checking</title>
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
        include "../php/save.php";
        $user = checkUser($_POST['id'], $_POST['pw']);
        $class = getClassLevel($_POST['class_index']);
    ?>

    <script src="/js/main.js"></script>
    <script>
        window.onload = function() {
            <?php
                $ret = submitContent(
                    $user['user_index'],
                    $user['level'],
                    $class['class_index'],
                    $class['read_level'],
                    $class['write_level'],
                    $_POST['title'],
                    $_POST['thumbnail'],
                    $_POST['content']);
            ?>
            if(<?php echo $ret; ?> > 0){
                contentClick(<?php echo $ret; ?>);
            }
            else{
                if(<?php echo $ret; ?> == -1)
                    alert("인증 실패");
                else if(<?php echo $ret; ?> == -2)
                    alert("입력 불가능한 문자열이 포함되어 있습니다.");
                else
                    alert("저장 실패");
            }
        }

    </script>
</head>
<body>
    <div>
        <p id='text'>Writer Checking ...</p>
    </div>
</body>
</html>