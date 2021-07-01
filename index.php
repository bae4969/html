<!-- index page -->
<!doctype html>
<html lang=ko>
<head>
    <meta charset='utf-8'>
    <title>BWP</title>
    <link type="text/css" charset="UTF-8" rel="stylesheet" href="css/index.css">
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
                        <iframe class=content_iframe src='widget/weather/index' frameborder=0 scrolling=no></iframe>
                    </div>
                    <div id=content1 class=content>
                        <iframe class=content_iframe src='widget/dust/index' frameborder=0 scrolling=no></iframe>
                    </div>
                    <div id=content2 class=content>
                        <iframe class=content_iframe src='widget/covid/index' frameborder=0 scrolling=no></iframe>
                    </div>
                    <div id=content3 class=content>
                        <iframe class=content_iframe src='widget/naver/bitcoin_news' frameborder=0 scrolling=no></iframe>
                    </div>
                </div>
            </div>
        </section>
        <footer>
            <p>Contact : bae4969@naver.com</br>
            Github : <a class=footer href=https://github.com/bae4969>https://github.com/bae4969</a></p>
        </footer>
    </div>
    
    <script type="text/javascript" charset="UTF-8" src="/js/basicFunc.js"> </script>
    <script type="text/javascript">
        var user;
        var showState = 0;
        var content_h_1 = ['80vw', '132vw', '230vw', '104vw', ];
        var content_h_2 = ['40vw', '65vw', '115vw', '57vw', ];
        var content_h_3 = ['30vw', '49vw', '86vw', '41vw', ];

        window.onload = function() {
            checkUserInfo();
            setContentList();
        }
        window.onresize = function(){
            var content = document.getElementsByClassName('content');
            setContentList();
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
            var win_width = window.innerWidth;
            var content = document.getElementsByClassName('content');
            var div_temp = document.getElementById('temp');
            var div_left = document.getElementById('left');
            var div_right = document.getElementById('right');

            if(showState != 1 && win_width <= 1200){
                showState = 1;
                div_temp.style.height = 0;
                for(i = 0; i < content.length; i++){
                    document.getElementById("content"+i).style.height = content_h_1[i];
                    div_temp.appendChild(document.getElementById("content"+i));
                }

                div_left.style.width = '100%';
                div_right.style.width = '0%';
                for(i = 0; i < content.length; i++)
                    div_left.appendChild(document.getElementById("content"+i));
            }
            else if(showState != 2 && win_width > 1200 && win_width <= 1600){
                showState = 2;
                div_temp.style.height = 0;
                for(i = 0; i < content.length; i++){
                    document.getElementById("content"+i).style.height = content_h_2[i];
                    div_temp.appendChild(document.getElementById("content"+i));
                }

                div_left.style.width = '50%';
                div_right.style.width = '50%';
                for(i = 0; i < content.length; i++){
                    if(div_left.offsetHeight > div_right.offsetHeight)
                        div_right.appendChild(document.getElementById("content"+i));
                    else
                        div_left.appendChild(document.getElementById("content"+i));
                }
            }
            else if(showState != 3 && win_width > 1600){
                showState = 3;
                div_temp.style.height = 0;
                for(i = 0; i < content.length; i++){
                    document.getElementById("content"+i).style.height = content_h_3[i];
                    div_temp.appendChild(document.getElementById("content"+i));
                }

                div_left.style.width = '50%';
                div_right.style.width = '50%';
                for(i = 0; i < content.length; i++){
                    if(div_left.offsetHeight > div_right.offsetHeight)
                        div_right.appendChild(document.getElementById("content"+i));
                    else
                        div_left.appendChild(document.getElementById("content"+i));
                }
            }
        }
    </script>
</body>
</html>

