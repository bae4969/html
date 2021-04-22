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
        include "php/basic.php";
        $user = checkUser($_POST["id"], $_POST["pw"]);
    ?>

    <script src="/js/main.js"></script>
    <script>
        window.onload = function() {
            if(<?php echo $user["user_index"]; ?> > 0){
                localStorage.setItem("id", <?php echo '"'.$_POST["id"].'"'; ?>);
                localStorage.setItem("pw", <?php echo '"'.$_POST["pw"].'"'; ?>);
                homeClick();
            }
            else{
                if(<?php echo $user["user_index"]; ?> < 0)
                    alert("접근 금지된 유저입니다.");
                else
                    alert("ID 또는 PW가 일치하지 않습니다.");
                    
                localStorage.removeItem("id");
                localStorage.removeItem("pw");
                location.href = "/login";
            }
        }
    </script>
</head>
<body>
    <div>
        <p id='text'>Checking Login ...</p>
    </div>
</body>
</html>