<!--login.php -->
<!doctype html>
<html lang=kr>
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
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        font-size: 2ex;
        color: #C8C3BC;
    }

    div.title{
        width: 100%;
        margin-bottom: 30px;
    }

    img#mainTitle{
        width: 330px;
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

/************************************end************************************/
    </style>

<!--********************************script**********************************-->
    <script src="/js/sha256.js"></script>
    <script src="/js/basicFunc.js"></script>
    <script>
        var user;

        function loginClick() {
            var id = sha256(document.getElementById("text_id").value);
            var pw = sha256(document.getElementById("text_pw").value);

            var xhr = new XMLHttpRequest();
            var url = 'get/userInfo';
            url += '?id=' + id;
            url += '&pw=' + pw;
            xhr.open('GET', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE) {
                    if(xhr.status == 200){
                        user = JSON.parse(this.responseText);
                        if(user['state'] == 0){
                            setCookie("id", id, 1);
                            setCookie("pw", pw, 1);
                            location.href = '/index';
                        }
                        else {
                            alert(user['data']['etc']);
                        }
                    }
                    else{
                        alert('Server Error (' + xhr.status + ')');
                    }
                }
            };
            xhr.send();
        }
    </script>
<!--********************************script**********************************-->
</head>
<body>
    <div id='main'>
        <header>
            <div id=topLeft OnClick='location.href="index"'>
                Home
            </div>
        </header>
        <div id='inputLayout'>
            <div class='title'>
                <img id=mainTitle OnClick='location.href="index"' src="res/title.png" alt="Index Page" height="100%" />
            </div>

            <div class='input'>
                <input id='text_id' class='input' type='text' placeholder='ID'
                    onkeyup="if(window.event.keyCode==13){loginClick()}" />
            </div>

            <div class='input'>
                <input id='text_pw' class='input' type='password' placeholder='PW'
                    onkeyup="if(window.event.keyCode==13){loginClick()}" />
            </div>

            <div class='input'>
                <button id='btn_login' onclick='loginClick()'>Login</button>
            </div>
        </div>
    </div>
</body>
</html>
