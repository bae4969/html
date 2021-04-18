<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <style>
/************************************outer************************************/

    a:link {
        color: #C3C3C3;
        text-decoration: none;
    }

    a:visited {
        color: #C3C3C3;
        text-decoration: none;
    }

    a:hover {
        color: #C3C3C3;
        text-decoration: none;
    }

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
    <title>Blog Login Checking</title>
</head>

<script>
    window.onload = function() {
        <?php
            include "sql/basic.php";
            parse_str(getenv("QUERY_STRING"), $array);
            $ret = userCheck($array["id"], $array["pw"]);
        ?>
        if(<?php echo $ret[0]; ?> == 1){
            localStorage.setItem("isLogin", true);
		    location.href = "index.php?id=" + <?php echo '"'.$array["id"].'"'; ?> + "&pw=" + <?php echo '"'.(string)$array["pw"].'"'; ?>;
        }
        else{
            alert("Worng ID or PW, check your input");
            localStorage.setItem("isLogin", false);
		    location.href = "login.php";
        }
    }
</script>

<body>
    <div>
        <p id='text'>Login Checking ...</p>
    </div>
</body>
</html>