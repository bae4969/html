<!-- content/reader.php -->
<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Blog</title>
    <link rel="stylesheet" href="css/after.css">
    <link rel="stylesheet" href="css/outer.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/aside.css">
    <link rel="stylesheet" href="css/detailContent.css">
    <link rel="stylesheet" href="css/footer.css">

    <script src="/js/basicFunc.js"> </script>
    <script>
        var content_index = 1;

        window.onload = function() {
            var params = {};
            location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(str, key, value) { params[key] = value; });
            if(params['content_index'])
                content_index = params['content_index'];
            else{
                alert('잘못된 접근')
                location.href = 'index';
            }
            checkUserInfo();
            initClass();
            initContentDetail();
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
                                location.href = 'index?class_index=' + this.value;
                            }
                            aside_ul.appendChild(class_li);
                        }
                    }
                }
            };
            xhr.send();
        }
        function initContentDetail(){
            var xhr = new XMLHttpRequest();
            var url = 'get/contentDetail';
            url += '?id=' + getCookie('id');
            url += '&pw=' + getCookie('pw');
            url += '&content_index=' + content_index;
            xhr.open('GET', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE){
                    if( xhr.status == 200){
                        var content_detail = JSON.parse(this.responseText);
                        if(content_detail['state'] == 0){
                            var section = document.getElementById('section');

                            var container = document.createElement('div');
                            var title = document.createElement('div');
                            var date = document.createElement('div');
                            var writer = document.createElement('div');
                            var hr = document.createElement('hr');
                            var content = document.createElement('div');

                            container.appendChild(title);
                            container.appendChild(date);
                            container.appendChild(writer);
                            container.appendChild(hr);
                            container.appendChild(content);
                            section.appendChild(container);

                            if(content_detail['data']['content']['state'] < 0)
                                container.id = 'content_ban';
                            else
                                container.id = 'content';

                            title.id = 'content_title';
                            title.innerHTML = content_detail['data']['content']['title'];

                            date.id = 'content_date';
                            date.innerHTML = content_detail['data']['content']['date'];

                            writer.id = 'content_date';
                            writer.innerHTML = content_detail['data']['content']['user_index'];

                            content.id = 'content_content';
                            content.innerHTML = content_detail['data']['content']['content'];
                
                            if(content_detail['data']['canDisable'] > 0){
                                var button = document.createElement('button');
                                container.appendChild(button);
                                button.className = 'content_control';
                                button.innerHTML = '삭제';
                                button.onclick = function(){
                                    disableClick()
                                }
                            }
                            if(content_detail['data']['canEnable'] > 0){
                                var button = document.createElement('button');
                                container.appendChild(button);
                                button.className = 'content_control';
                                button.innerHTML = '복구';
                                button.onclick = function(){
                                    enableClick()
                                }
                            }
                            if(content_detail['data']['canEdit'] > 0){
                                var button = document.createElement('button');
                                container.appendChild(button);
                                button.className = 'content_control';
                                button.innerHTML = '수정';
                                button.onclick = function(){
                                    editClick()
                                }
                            }
                        }
                        else{
                            alert(content_detail['data']);
                            location.href = 'index';
                        }
                    }
                    else alert('Server Error (' + xhr.status + ')');
                }
            };
            xhr.send();
        }

        function editClick(){
            location.href = 'edit?content_index=' + content_index;
        }
        function enableClick(){
            var formData = new FormData();
            formData.append('id', getCookie('id'));
            formData.append('pw', getCookie('pw'));
            formData.append('content_index', content_index);

            var xhr = new XMLHttpRequest();
            var url = 'post/contentEnable';
            xhr.open('POST', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE){
                    if(xhr.status == 200){
                        var result = JSON.parse(this.responseText);
                        if(result['state'] == 0){
                            alert('복구 되었습니다.');
                            location.href = 'reader?content_index=' + content_index;
                        }
                        else alert('잘못된 접근');
                    }
                    else alert('Server Error (' + xhr.status + ')');
                }
            }
            xhr.send(formData);
        }
        function disableClick(){
            var formData = new FormData();
            formData.append('id', getCookie('id'));
            formData.append('pw', getCookie('pw'));
            formData.append('content_index', content_index);

            var xhr = new XMLHttpRequest();
            var url = 'post/contentDisable';
            xhr.open('POST', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE){
                    if(xhr.status == 200){
                        var result = JSON.parse(this.responseText);
                        if(result['state'] == 0){
                            alert('삭제 되었습니다.');
                            location.href = 'reader?content_index=' + content_index;
                        }
                        else alert('잘못된 접근');
                    }
                    else alert('Server Error (' + xhr.status + ')');
                }
            }
            xhr.send(formData);
        }
    </script>
</head>
<body>
    <div id="main">
        <header>
            <div id=topLeft OnClick='location.href="/index"'>Home</div>
            <div id=topRight onclick=loginoutClick()></div>
            <div id=topWrite OnClick='location.href="writer"'></div>
            <div id=title>
                <img id=mainTitle OnClick='location.href="index"' src="res/title.png" alt="Blog Page" />
            </div>
        </header>
        <section id=section>
            <aside>
                <div id=profile>profile</div>
                <ul id=category></ul>
            </aside>
        </section>
        <footer>
            <p>Contact : bae4969@naver.com</br>
            Github : <a class=footer href=https://github.com/bae4969>https://github.com/bae4969</a></p>
        </footer>
    </div>
</body>
</html>