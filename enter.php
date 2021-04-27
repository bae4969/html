<!-- loginCheck.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Entrance Checking</title>
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
    ?>

    <script src="/js/main.js"></script>
    <script>
        window.onload = function() {
            homeClick();
        }
    </script>
</head>
<body>
    <div>
        <p id='text'>Entrance Checking ...</p>
    </div>
</body>
</html>
