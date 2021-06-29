<!-- blog.php -->
<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <title>BWP Dev News</title>
    <link rel="stylesheet" href="css/index.css">

    <script src="/js/basicFunc.js"> </script>
    <script>
        var user;
        var showState = 0;
        var class_index = 0;
        var page = 0;
        var pageCount = 0;
        var loadCount = 0;

        window.onload = function() {
            var params = {};
            location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(str, key, value) { params[key] = value; });
            if(params['class_index'])
                class_index = params['class_index'];
            if(params['page'])
                page = params['page'];
            checkUserInfo();
            initClass();
            initContent();
        }
        window.onresize = function(){
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
                location.href = '/login';
            }
        }

        function checkUserInfo() {
            var xhr = new XMLHttpRequest();
            var url = '/get/userInfo';
            url += '?id=' + getCookie('id');
            url += '&pw=' + getCookie('pw');
            xhr.open('GET', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200){
                    user = JSON.parse(this.responseText);
                    if(user['state'] == 0){
                        document.getElementById("topRight").innerHTML = "로그아웃";
                        document.getElementById("topWrite").innerHTML = "글쓰기";
                    }
                    else {
                        document.getElementById("topRight").innerHTML = "로그인";
                        if(document.getElementById("topWrite") !== null)
                            document.getElementById("topWrite").innerHTML = "";
                    }
                }
            };
            xhr.send();
        }
        function initClass() {
            var xhr = new XMLHttpRequest();
            var url = 'get/class';
            url += '?id=' + getCookie('id');
            url += '&pw=' + getCookie('pw');
            xhr.open('GET', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200){
                    var class_list = JSON.parse(this.responseText);
                    if(class_list['state'] == 0){
                        var aside_ul = document.getElementById('category');
                        for(var i = 0; i < class_list['data'].length; i++){
                            var class_li = document.createElement('li');
                            class_li.className = 'category';
                            class_li.value = class_list['data'][i]['class_index']
                            class_li.innerHTML = class_list['data'][i]['name'];
                            class_li.onclick = function(){
                                class_index = this.value;
                                page = 0;
                                initContent();
                            }
                            aside_ul.appendChild(class_li);
                        }
                    }
                }
            };
            xhr.send();
        }
        function initContent() {
            loadCount = 0;
            var xhr = new XMLHttpRequest();
            var url = 'get/content';
            url += '?id=' + getCookie('id');
            url += '&pw=' + getCookie('pw');
            url += '&class_index=' + class_index;
            url += '&page=' + page;
            xhr.open('GET', url);
            xhr.onreadystatechange = function (){
                if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200){
                    var content_list = JSON.parse(this.response);
                    if(content_list['state'] == 0){
                        var temp_container = document.getElementById('temp');
                        var left_container = document.getElementById('left');
                        var right_container = document.getElementById('right');
                        var page_container = document.getElementById('pages');
                        while(temp_container.hasChildNodes())
                            temp_container.removeChild(temp_container.firstChild);
                        while(left_container.hasChildNodes())
                            left_container.removeChild(left_container.firstChild);
                        while(right_container.hasChildNodes())
                            right_container.removeChild(right_container.firstChild);
                        while(page_container.hasChildNodes())
                            page_container.removeChild(page_container.firstChild);

                        var count = content_list['data']['count'];
                        pageCount = (count - (count % 10)) / 10;
                        if(count % 10 != 0)
                            pageCount += 1;

                        for(var i = 0; i < content_list['data']['content'].length; i++){
                            var container = document.createElement('div');
                            var title = document.createElement('div');
                            var date = document.createElement('div');
                            var writer = document.createElement('div');
                            var hr = document.createElement('hr');
                            var thumbnail_container = document.createElement('div');
                            var thumbnail = document.createElement('img');
                            var summary = document.createElement('div');

                            container.appendChild(title);
                            container.appendChild(date);
                            container.appendChild(writer);
                            container.appendChild(hr);
                            thumbnail_container.appendChild(thumbnail);
                            container.appendChild(thumbnail_container);
                            container.appendChild(summary);
                            temp_container.appendChild(container);

                            container.id = 'content' + i;
                            container.value = content_list['data']['content'][i]['content_index'];
                            if(content_list['data']['content'][i]['state'] < 0)
                                container.className = 'content_ban';
                            else
                                container.className = 'content';
                            container.onclick = function(){location.href = 'reader?content_index=' + this.value;}
                            title.className = 'content_title';
                            title.innerHTML = content_list['data']['content'][i]['title'];
                            date.className = 'content_date';
                            date.innerHTML = content_list['data']['content'][i]['date'];
                            writer.className = 'content_date';
                            writer.innerHTML = 'UID : ' + content_list['data']['content'][i]['user_index'];
                            thumbnail_container.className = 'content_thumbnail_container';
                            thumbnail.className = 'content_thumbnail';
                            thumbnail.src = content_list['data']['content'][i]['thumbnail'];
                            summary.className = 'content_summary';
                            summary.innerHTML = content_list['data']['content'][i]['summary'];
                            
                            if(content_list['data']['content'][i]['thumbnail'] == ''){
                                loadCount+=1;
                                checkLoadContent(content_list['data']['content'].length);
                            }
                            else
                                thumbnail.onload = function(){
                                    loadCount+=1;
                                    checkLoadContent(content_list['data']['content'].length);
                                }
                        }

                        var start = (page - (page % 10)) / 10;
                        for(var i = -4; i < -2; i++){
                            var button = document.createElement('button');
                            page_container.appendChild(button);
                            button.className = 'page';
                            button.value = i;
                            if(i == -4)
                                button.innerHTML = '<<';
                            else
                                button.innerHTML = '<';
                            button.onclick = function(){
                                pageClick(this)
                            }
                        }
                        for(var i = start; i < pageCount && i < 10; i++){
                            var button = document.createElement('button');
                            page_container.appendChild(button);
                            if(i == page)
                                button.id = 'selectedPage';
                            button.className = 'page';
                            button.value = i;
                            button.innerHTML = i + 1;
                            button.onclick = function(){
                                pageClick(this)
                            }
                        }
                        for(var i = -2; i < 0; i++){
                            var button = document.createElement('button');
                            page_container.appendChild(button);
                            button.className = 'page';
                            button.value = i;
                            if(i == -2)
                                button.innerHTML = '>';
                            else
                                button.innerHTML = '>>';
                            button.onclick = function(){
                                pageClick(this)
                            }
                        }
                    }
                }
            };
            xhr.send();
        }
        function checkLoadContent(length){
            if(loadCount >= length){
                showState = 0;
                setContentList();
            }
        }

        function setContentList(){
            var contentSize
                = document.getElementsByClassName('content').length
                + document.getElementsByClassName('content_ban').length;
            var div_temp = document.getElementById('temp');
            var div_left = document.getElementById('left');
            var div_right = document.getElementById('right');

            if(showState != 1 && document.body.offsetWidth < 1600){
                showState = 1;
                div_temp.style.height = 0;
                for(i = 0; i < contentSize; i++)
                    div_temp.appendChild(document.getElementById("content"+i));

                div_left.style.width = '100%';
                div_right.style.width = '0%';
                for(i = 0; i < contentSize; i++)
                    div_left.appendChild(document.getElementById("content"+i));
            }
            else if(showState != 2 && document.body.offsetWidth >= 1600){
                showState = 2;
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

        function pageClick(ele){
            temp_page = page;
            if(ele.value < 0){
                if(ele.value == -4)
                    page -= 10;
                else if(ele.value == -3)
                    page -= 1;
                else if(ele.value == -2)
                    page += 1;
                else if(ele.value == -1)
                    page += 10;

                if(page < 0)
                    page = 0;
                if(page >= pageCount)
                    page = pageCount - 1;
            }
            else
                page = ele.value;

            if(temp_page != page)
                initContent();
        }
    </script>
</head>
<body>
    <div id=main>
        <header>
            <div id=topLeft OnClick='location.href="/index"'>Home</div>
            <div id=topRight onclick=loginoutClick()></div>
            <div id=topWrite OnClick='location.href="writer"'></div>
            <div id=title>
                <img id=mainTitle OnClick='location.href="index"' src="res/title.png" alt="Blog Page" />
            </div>
        </header>
        <section>
            <aside>
                <div id=profile>profile</div>
                <ul id=category></ul>
            </aside>
            <div id=contents>
                <div>
                    <div id=left></div>
                    <div id=right></div>
                </div>
                <div id=pages>
                </div>
                <div id=temp style='height: 0;'>
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
