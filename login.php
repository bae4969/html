<!--login.php -->
<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Login</title>
    <style>
/************************************outer************************************/

    div#topLeft:hover, img#mainTitle {
        color: white;
        text-decoration: none;
        cursor: pointer;
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
        width: 60px;
        margin-left: 7%;
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

<!--********************************script**********************************-->
    
    <script src="/encode/sha256.js"></script>
    <script src="/js/main.js"></script>
    <script>
        function loginChecker(){
            localStorage.setItem("id", sha256(document.getElementById("text_id").value));
            localStorage.setItem("pw", sha256(document.getElementById("text_pw").value));
            var form = getDefaultPostForm('loginCheck');
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</head>
<body>
    <div id='main'>
        <div id="topLeft">
            <div id=topLeft onclick=homeClick()>Home</div>
        </div>
        <div id='inputLayout'>
            <div class='input'>
                <img id=mainTitle onclick=homeClick() src="res/index/title.png" alt="Index Page" height="50px" />
            </div>

            <div class='input'>
                <input id='text_id' class='input' type='text' placeholder='ID'
                    onkeyup="if(window.event.keyCode==13){loginChecker()}" />
            </div>

            <div class='input'>
                <input id='text_pw' class='input' type='password' placeholder='PW'
                    onkeyup="if(window.event.keyCode==13){loginChecker()}" />
            </div>

            <div class='input'>
                <button id='btn_login' onclick='loginChecker()'>Login</button>
            </div>
        </div>
    </div>
</body>
</html>
