<!-- content.php -->
<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <title>Blog</title>
    <style>
/************************************outer************************************/

    a:link,
    a:visited {
        color: #C3C3C3;
        text-decoration: none;
    }

    a:hover {
        color: white;
        text-decoration: none;
        cursor: pointer;
    }

    body {
        min-width: 800px;
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
        width: 100%;
        padding-top: 100px;
        padding-bottom: 90px;
        text-align: center;
    }

    div#title img#mainTitle {
        width: 500px;
    }

/************************************aside************************************/

    aside {
        width: 160px;
        margin: 20px;
        padding: 20px;
        float: right;
        background: #222426;
    }

    div#profile{
        margin: 10px;
        margin-bottom: 50px;
    }

    aside ul#category {
        margin: 10px;
        padding: 0px;
        list-style: none;
    }

    aside ul li.category {
        margin-bottom: 20px;
        font-size: 1.8ex;
        font-weight: bold;
    }

/************************************section************************************/

    section {
        padding: 10px;
        background-color: #1A1C1D;
    }

    div#contents {
        width: calc(100% - 260px);
        margin-top: 20px;
    }

    div#content {
        width: calc(100% - 60px);
        margin: 20px;
        margin-top: 0px;
        margin-bottom: 30px;
        padding: 20px;
        display: inline-block;
        vertical-align: top;
        background: #222426;
        color: white;
    }

    div#content_title {
        padding: 8%;
        padding-top: 30px;
        padding-bottom: 0px;
        font-size: 6ex;
        font-weight: bold;
    }

    div#content_date {
        padding: 4%;
        padding-top: 0px;
        padding-bottom: 20px;
        vertical-align: bottom;
        text-align: right;
        font-size: 2ex;
    }

    hr{
        background-color: #C3C3C3;
    }

    div#content_content {
        padding: 10px;
        margin-top: 30px;
        margin-bottom: 20px;
        font-size: 2.2ex;
    }

/************************************media************************************/

    /* vertical monitor */
    @media screen and (max-width: 1300px) {
        div#main {
            width: calc(100% - 40px);
        }

        div#contents {
            width: calc(100% - 250px);
        }
    }

    /* half of vertical monitor */
    /* @media screen and (max-width: 800px) {
        div#title {
            width: 100%;
            margin-left: 0%;
            padding-top: 50px;
            padding-bottom: 40px;
            text-align: center;
        }

        div#title img#mainTitle {
            width: 80%;
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
            margin: 10px;
        }
    } */

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
        padding-left: 40px;
        padding-right: 40px;
        padding-bottom: 10px;
        font: size 2px;
        color: darkgray;
    }
    </style>
</head>

<?php
    include 'sql/sqlcon.php';
    include 'sql/basic.php';
?>

<script>

    var isLogin = 'false';
    var par;

    window.onload = function() {
        var str = location.href;
        var par = window.location.search;
        var index = str.indexOf("?") + 1;
        var lastIndex = str.indexOf("#") > -1 ? str.indexOf("#") + 1 : str.length;
        if (index == 0) {
            var id = localStorage.getItem("id");
            var pw = localStorage.getItem("pw");
            if(id != null & pw !=null){
                location.href = "index?id=" + id + "&pw=" + pw;
            }
        }

        isLogin = localStorage.getItem("isLogin");
        if(isLogin == null) isLogin = 'false';
        if(isLogin == 'true'){
            document.getElementById("loginTop").href = "index";
            document.getElementById("loginTop").innerHTML = "Logout";
        }
        else{
            document.getElementById("loginTop").href = "login";
            document.getElementById("loginTop").innerHTML = "Login";
        }
        //history.replaceState({}, null, location.pathname);
    }

</script>

<body>
    <div id="main">
        <header>
            <div id="topLeft">
                <a id="homeTop" href="index" alt="Home Page">Home</a>
            </div>
            <div id="topRight">
                <a id="loginTop" onclick="loginout()" alt="Login Page"></a>
            </div>
            <div id="title">
                <a href="index">
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
                        parse_str(getenv("QUERY_STRING"), $array);
                        $id = $array["id"];
                        $pw = $array["pw"];
                        $classList = loadClassList($id, $pw);

                        for($i = 0; $i < count($classList); $i++) {
                            echo
                            '<li class="category" >
                                <a href="index?id='.$id.'&pw='.$pw.'&class='.$classList[$i]["class_index"].'">
                                    '.$classList[$i]["name"].'
                                </a>
                            </li>';
                        }
                    ?>
                </ul>
            </aside>

            <div id=contents>
                <div id=content>
                    <?php
                        parse_str(getenv("QUERY_STRING"), $array);
                        $id = $array["id"];
                        $pw = $array["pw"];
                        $content_index = $array["index"];
                        $content = loadDetailContentList($id, $pw, $content_index);
                        echo
                        '<div id=content_title>'
                            .$content["title"].
                        '</div>';
                        echo
                        '<div id=content_date>'
                            .$content["date"].
                        '</div><hr>';
                        echo
                        '<div id=content_content>'
                            .$content["content"].
                        '</div>';
                    ?>
                </div>
            </div>

        </section>

        <footer>
            <p>Contact : bae4969@naver.com</br>
            Github : https://github.com/bae4969</p>
        </footer>
    </div>
</body>

</html>