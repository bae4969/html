<!-- content.php -->
<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <title>Blog</title>
    <style>
/************************************outer************************************/

    img#mainTitle {
        cursor: pointer;
    }

    div#topLeft:hover, div#topRight:hover, div.category:hover {
        color: white;
        text-decoration: none;
        cursor: pointer;
    }

    div.content:hover {
        background-color: #36383A;
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

    img#mainTitle {
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
        margin: 10px 10px 40px 10px;
    }

    ul#category {
        margin: 10px;
        padding: 0px;
        list-style: none;
    }

    li.category {
        margin-bottom: 30px;
        font-size: 1.8ex;
        font-weight: bold;
    }

/************************************section************************************/

    section {
        padding: 10px;
        background-color: #1A1C1D;
    }

    div#content {
        width: calc(100% - 300px);
        margin: 20px 0px 30px 10px;
        padding: 20px;
        display: inline-block;
        vertical-align: top;
        background: #222426;
        color: white;
    }

    div#content_title {
        padding: 30px 8% 0px 8%;
        font-size: 6ex;
        font-weight: bold;
    }

    div#content_date {
        padding: 0px 4% 20px 4%;
        vertical-align: bottom;
        text-align: right;
        font-size: 2ex;
    }

    hr{
        background-color: #C3C3C3;
    }

    div#content_content {
        padding: 10px;
        padding: 30px 0 20px 0;
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

<script src="script/main.js"> </script>
<script>

    var isLogin = 'false';

    window.onload = function() {
        isLogin = localStorage.getItem("isLogin");
        if(isLogin == null) isLogin = 'false';
        document.getElementById("topRight").innerHTML = isLogin == 'true' ? "Logout" : "Login";
    }

</script>

<body>
    <div id="main">
        <header>
            <div id=topLeft onclick=homeClick()>Home</div>
            <div id=topRight onclick=loginout()></div>
            <div id=title>
                <img id=mainTitle onclick=homeClick() src="res/index/title.png" alt="Index Page" />
            </div>
        </header>

        <section>

            <aside>
                <div id=profile>
                    profile
                </div>
                <ul id=category>
                    <?php
                        $id = $_POST["id"];
                        $pw = $_POST["pw"];
                        $classList = loadClassList($id, $pw);

                        for($i = 0; $i < count($classList); $i++) {
                            echo
                            '<li class=category>
                                <div class=category onclick=classClick('.$classList[$i]["class_index"].')>';
                            echo
                                    $classList[$i]["name"];
                            echo
                                '</div>
                            </li>';
                        }
                    ?>
                </ul>
            </aside>

            <div id=content>
                <?php
                    $id = $_POST["id"];
                    $pw = $_POST["pw"];
                    $content_index = $_POST["index"];
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

        </section>

        <footer>
            <p>Contact : bae4969@naver.com</br>
            Github : https://github.com/bae4969</p>
        </footer>
    </div>
</body>

</html>