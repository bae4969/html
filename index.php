<!-- index page -->
<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Index</title>
    <link rel="stylesheet" href="css/after.css">
    <link rel="stylesheet" href="css/outer.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/index.css">

    <script src="/js/basicFunc.js"> </script>
    <script>
        var user;
        var showState = 0;

        window.onload = function() {
            checkUserInfo();
            setContentList();
        }
        window.onresize = function(){
            setContentList()
        }
        function loginoutClick(){
            if (user['state'] == 0) {
                deleteCookie('id');
                deleteCookie('pw');
                alert("로그아웃");
                location.href = 'index';
            }
            else{
                location.href = 'login';
            }
        }

        function checkUserInfo() {
            var xhr = new XMLHttpRequest();
            var url = 'get/userInfo';
            url += '?id=' + getCookie('id');
            url += '&pw=' + getCookie('pw');
            xhr.open('GET', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200){
                    user = JSON.parse(this.responseText);
                    if(user['state'] == 0){
                        document.getElementById("topRight").innerHTML = "로그아웃";
                    }
                    else {
                        document.getElementById("topRight").innerHTML = "로그인";
                    }
                }
            };
            xhr.send();
        }

        function setContentList(){
            var contentSize = document.getElementsByClassName('content').length;
            var div_temp = document.getElementById('temp');
            var div_left = document.getElementById('left');
            var div_right = document.getElementById('right');

            if(showState != 2 && document.body.offsetWidth < 1600){
                showState = 2;
                div_temp.style.height = 0;
                for(i = 0; i < contentSize; i++)
                    div_temp.appendChild(document.getElementById("content"+i));

                div_left.style.width = '100%';
                div_right.style.width = '0%';
                for(i = 0; i < contentSize; i++)
                    div_left.appendChild(document.getElementById("content"+i));
            }
            else if(showState != 1 && document.body.offsetWidth >= 1600){
                showState = 1;
                div_temp.style.height = 0;
                for(i = 0; i < contentSize; i++)
                    div_temp.appendChild(document.getElementById("content"+i));

                div_left.style.width = '50%';
                div_right.style.width = '50%';
                for(i = 0; i < contentSize; i++){
                    if(div_left.offsetHeight > div_right.offsetHeight)
                        div_right.appendChild(document.getElementById("content"+i));
                    else
                        div_left.appendChild(document.getElementById("content"+i));
                }
            }
        }
    </script>
</head>
<body>
    <div id=main>
        <header>
            <div id=topLeft OnClick='location.href="index"'>Home</div>
            <div id=topRight onclick=loginoutClick()></div>
            <div id=title>
                <img id=mainTitle OnClick='location.href="index"' src="res/title.png" alt="Index Page" />
            </div>
        </header>
        <nav>
            <div id=nav>
                <div class=nav_icon OnClick='location.href="blog/index"' >
                    <img class=nav_icon src="/res/nav_blog.png" alt="blog"/>
                </div>
            </div>
        </nav>
        <section>
            <div id=contents>
                <div>
                    <div id=left></div>
                    <div id=right></div>
                </div>
                <div id=temp>
                    <div id=content0 class=content>
                        <iframe src='widget/weather/index' frameborder=0 scrolling=no style='width: 100%; height: 410px; display: inline-block;'></iframe>
                        <iframe src='widget/dust/index' frameborder=0 scrolling=no style='width: 100%; height: 650px; display: inline-block;'></iframe>
                    </div>
                    <div id=content1 class=content>
                        <iframe src='widget/covid/index' frameborder=0 scrolling=no style='width: 100%; height: 1430px; display: inline-block;'></iframe>
                    </div>
                </div>
            </div>
        </section>
        <footer>
            <p>Contact : bae4969@naver.com</br>
            Github : <a class=footer href=https://github.com/bae4969>https://github.com/bae4969</a></p>
        </footer>
    </div>
</body>
</html>

