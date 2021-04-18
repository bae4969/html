<!-- index.php -->
<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <title>Blog</title>
    <style>
/************************************Outer************************************/

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

    body {
        min-width: 520px;
        margin: 0;
        padding: 0;
        background: #181A1B;
        color: #C3C3C3;
        list-style: none;
        font-family: "arial";
    }

    div#main {
        width: 70%;
        margin: 0px auto;
        padding: 20px;
        padding-top: 10px;
    }

/************************************header************************************/

    header {
        width: 100%;
    }

    div#topLeft {
        width: 15%;
        min-width: 80px;
        float: left;
        font-size: 2ex;
        text-align: center;
        color: #C8C3BC;
    }

    div#topRight {
        width: 15%;
        min-width: 80px;
        float: right;
        font-size: 2ex;
        text-align: center;
        color: #C8C3BC;
    }

    div#title {
        width: 600px;
        margin-left: 15%;
        padding-top: 80px;
        padding-bottom: 70px;
        text-align: center;
    }

    div#title img#mainTitle {
        width: calc(100% - 160px);
    }

/************************************aside************************************/

    aside {
        width: 160px;
        margin: 20px;
        padding: 20px;
        float: right;
        background: #222426;
    }

    aside ul#category {
        margin: 20px;
        margin-bottom: 20px;
        padding: 0px;
        list-style: none;
    }

    aside ul li.category {
        margin-top: 30px;
    }

/************************************section************************************/

    section {
        padding: 10px;
        background-color: #1A1C1D;
    }

    div#contents {
        width: calc(100% - 250px);
        padding: 10px;
    }

    div.content {
        width: calc(50% - 40px);
        margin: 10px;
        padding: 10px;
        float: left;
        background: #222426;
        color: white;
    }

/************************************media************************************/

    /* vertical monitor */
    @media screen and (max-width: 1300px) {
        div#main {
            width: calc(100% - 40px);
        }

        div#contents {
            padding: 0px;
            padding-top: 10px;
        }

        div.content {
            width: calc(100% - 40px);
            float: left;
        }
    }

    /* half of vertical monitor */
    @media screen and (max-width: 800px) {
        div#title {
            width: 100%;
            margin-left: 0%;
            padding-top: 50px;
            padding-bottom: 40px;
            text-align: center;
        }

        aside {
            width: calc(100% - 60px);
            margin: 10px;
            float: none;
        }

        div#contents {
            width: 100%;
        }

        div.content {
            width: calc(100% - 40px);
            margin-bottom: 10px;
            float: none;
        }
    }

/************************************after************************************/

    header::after {
        content: "";
        display: block;
        clear: both;
    }

    nav::after {
        content: "";
        display: block;
        clear: both;
    }

    aside::after {
        content: "";
        display: block;
        clear: both;
    }

    div::after {
        content: "";
        display: block;
        clear: both;
    }

    section::after {
        content: "";
        display: block;
        clear: both;
    }

/************************************footer************************************/

    footer {
        margin: 20px;
        padding: 20px;
        padding-left: 40px;
        padding-right: 40px;
        font: size 2px;
        color: darkgray;
    }
    </style>
</head>

<script>

    var isLogin = 'false';

    window.onload = function() {
        isLogin = localStorage.getItem("isLogin");
        if(isLogin == null) isLogin = 'false';
        if(isLogin == 'true')
            document.getElementById("loginTop").innerHTML = "Logout";
        else
            document.getElementById("loginTop").innerHTML = "Login";
    }

    var loginout = function() {
        if (isLogin == 'true') {
            localStorage.setItem("isLogin", false);
            location.href = "index.php";
            alert("logout");
        } else {
            location.href = "login.php";
        }
    }

</script>

<body>
    <div id="main">
        <header>
            <div id="topLeft">
                <a id="homeTop" href="index.php" alt="Home Page">Home</a>
            </div>
            <div id="topRight">
                <label id="loginTop" onclick="loginout()" alt="Login Page"></label>
            </div>
            <div id="title">
                <a href="index.php">
                    <img id="mainTitle" src="res/index/title.png" alt="Index Page" />
                </a>
            </div>
        </header>

        <section>

            <aside>
                <div id=profile>
                    test
                </div>
                <ul id="category">
                    <?php
                        include 'sql/basic.php';

                        parse_str(getenv("QUERY_STRING"), $array);
                        $id = $array["id"];
                        $pw = $array["pw"];

                        $classList = loadClassList($id, $pw);
                        for($i = 0; $i < count($classList); $i++) {
                            echo
                            '<li class="category" >
                                <a href="index.php
                                ?id='.$id.
                                '&pw='.$pw.
                                '&class='.strval($classList[$i]).'">
                                    '.strval($classList[$i]).'
                                </a>
                            </li>';
                        }
                    ?>
                </ul>
            </aside>

            <div id=contents>
                <?php
                    include 'sql/sqlcon.php';

                    for($i = 0; $i < 20; $i++){
                        echo '<div class="content"> <article>';
                        echo 'test tset tset tset';
                        echo '</article> </div>';
                    }
                ?>
            </div>

        </section>

        <footer>
            <p>Contact : bae4969@naver.com</p>
        </footer>
    </div>
</body>

</html>
