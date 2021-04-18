<!-- .php -->
<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <style>
/************************************outer************************************/

    a:link,
    a:visited {
        color: #C3C3C3;
        text-decoration: none;
    }

    a:hover,
    label:hover {
        color: white;
        text-decoration: none;
    }

    html{
        height: 97%;
    }

    body {
        height: 100%;
        min-width: 520px;
        min-height: 520px;
        background: #181A1B;
        margin: 0;
        padding: 0;
        color: white;
        list-style: none;
        font-family: 'arial';
    }

/************************************top************************************/
    
    div#main{
        width: 70%;
        height: 100%;
        padding: 20px;
        padding-top: 10px;
    }

    div#topLeft {
        width: 15%;
        min-width: 80px;
        float: left;
        font-size: 2ex;
        text-align: center;
        color: #C8C3BC;
    }

/************************************input************************************/

    div#inputLayout {
        width: 100%;
        height: 35%;
        position: absolute; left: 50%; top: 50%; 
        transform: translate(-50%, -50%); text-align: center;
        font-size: 2ex;
        color: #C8C3BC;
    }

    div.input{
        width: 100%;
        height: 50px;
        margin-bottom: 30px;
    }

    input.input{
        width: 300px;
        height: 80%;
        font-size: 2.5ex;
        font-weight: 600;
    }

    button#btn_login{
        width: 300px;
        height: 80%;
        font-size: 2.5ex;
        font-weight: 1000;
    }
    
/************************************media************************************/

    @media screen and (max-width: 1300px) {
        div#main {
            width: calc(100% - 40px);
        }
    }

    @media screen and (max-width: 800px) {
        input.input,
        button#btn_login {
            width:90%;
        }
    }

    </style>
    <title>Blog Login</title>
</head>

<script type="text/javascript" src="encode/sha256.js"></script>
<script>

    function loginChecker(id, pw){
        var idHash = sha256(id);
        var pwHash = sha256(pw);
		location.href = "loginCheck.php?id=" + idHash + "&pw=" + pwHash;
    }

    function enter(){
        loginChecker(
            document.getElementById("text_id").value,
            document.getElementById("text_pw").value);
    }

</script>

<body>

    <div id='main'>
        <div id="topLeft">
            <a id="homeTop" href="index.php" alt="Home Page">Home</a>
        </div>

        <div id='inputLayout'>
            <div class='input'>
                <a href="index.php">
                    <img src="res/index/title.png" alt="Index Page" height="50px" />
                </a>
            </div>

            <div class='input'>
                <input id='text_id' class='input' type='text' placeholder='ID'
                    onkeyup="if(window.event.keyCode==13){enter()}" />
            </div>

            <div class='input'>
                <input id='text_pw' class='input' type='password' placeholder='PW'
                    onkeyup="if(window.event.keyCode==13){enter()}" />
            </div>

            <div class='input'>
                <button id='btn_login' onclick='enter()'>Login</button>
            </div>
        </div>
    </div>

</body>

</html>
