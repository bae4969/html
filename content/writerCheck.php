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
</head>

<script src="../script/content.js"></script>
<script>

    window.onload = function() {
        <?php
            include "../php/basic.php";
            $ret = 0;
        ?>
        if(<?php echo $ret["valid"]; ?> == 1){
            contentClick($ret["content_id"]);
        }
        else{
            if(<?php echo $user["valid"]; ?> == -1)
                alert("It contains bad input");
            else
                alert("Please login");
        }
    }

</script>

<body>
    <div>
        <p id='text'>Writer Checking ...</p>
    </div>
</body>
</html>