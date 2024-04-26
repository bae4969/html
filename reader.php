<!-- content/reader.php -->
<!doctype html>
<html lang=ko>
<head>
    <meta charset='utf-8'>
    <title>BWP Dev News</title>
    <link type="text/css" rel="stylesheet" href="css/reader.css">
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

    <script type="text/javascript" src="/js/basicFunc.js"> </script>
    <script type="text/javascript">
        var user_info_row;
        var posting_index = -1;

        window.onload = function() {
            var params = {};
            location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(str, key, value) { params[key] = value; });
            if(params['posting_index'])
                posting_index = params['posting_index'];
            else{
                alert('잘못된 접근')
                location.href = 'index';
            }
            verifyLogin();
            initCategoryList();
            initPostingDetail();
        }
        function loginoutClick(){
            if (user_info_row['state'] == 0) {
                deleteCookie('user_id');
                deleteCookie('user_pw');
                alert("로그아웃");
                location.href = 'index';
            }
            else{
                location.href = '/login';
            }
        }

        function verifyLogin() {
            var xhr = new XMLHttpRequest();
            var url = '/get/login_verify';
            url += '?user_id=' + getCookie('user_id');
            url += '&user_pw=' + getCookie('user_pw');
            xhr.open('GET', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState != XMLHttpRequest.DONE) return;
                if (xhr.status != 200) {
                    alert('Server Error (' + xhr.status + ')');
                    return;
                }

                user_info_row = JSON.parse(this.responseText);
                if (user_info_row['state'] == 0) {
                    document.getElementById("topRight").innerHTML = "로그아웃";
                    document.getElementById("topWrite").innerHTML = "글쓰기";
                }
                else {
                    document.getElementById("topRight").innerHTML = "로그인";
                    if(document.getElementById("topWrite") !== null)
                        document.getElementById("topWrite").innerHTML = "";
                }
            };
            xhr.send();
        }
        function initCategoryList() {
            var xhr = new XMLHttpRequest();
            var url = 'get/category_read_list';
            url += '?user_id=' + getCookie('user_id');
            url += '&user_pw=' + getCookie('user_pw');
            xhr.open('GET', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState != XMLHttpRequest.DONE) return;
                if (xhr.status != 200) {
                    alert('Server Error (' + xhr.status + ')');
                    return;
                }
                
                var category_list = JSON.parse(this.responseText);
                if (category_list['state'] != 0) {
                    alert('카테고리 초기화 오류 (' + category_list['state'] + ')');
                    return;
                }


                var aside_ul = document.getElementById('category');
                for(var i = 0; i < category_list['data'].length; i++){
                    var category_li = document.createElement('li');
                    category_li.className = 'category';
                    category_li.value = category_list['data'][i]['category_index']
                    category_li.innerHTML = category_list['data'][i]['category_name'];
                    category_li.onclick = function(){
                        location.href = 'index?category_index=' + this.value;
                    }
                    aside_ul.appendChild(category_li);
                }
            };
            xhr.send();
        }
        function initPostingDetail(){
            var xhr = new XMLHttpRequest();
            var url = 'get/full_posting';
            url += '?user_id=' + getCookie('user_id');
            url += '&user_pw=' + getCookie('user_pw');
            url += '&posting_index=' + posting_index;
            xhr.open('GET', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState != XMLHttpRequest.DONE) return;
                
                if ( xhr.status != 200) {
                    alert('Server Error (' + xhr.status + ')');
                    return;
                }

                var full_posting = JSON.parse(this.responseText);
                if (full_posting['state'] != 0) {
                    alert('Return Error (' + full_posting['state'] + ')');
                    location.href = 'index';
                    return;
                }
                

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

                if(full_posting['data']['posting_state'] > 0)
                    container.id = 'posting_ban';
                else
                    container.id = 'posting';

                title.id = 'posting_title';
                title.innerHTML = full_posting['data']['posting_title'];

                date.id = 'posting_date';
                date.innerHTML = full_posting['data']['posting_first_post_datetime'];

                writer.id = 'posting_writer';
                writer.innerHTML = 'UID : ' + full_posting['data']['user_index'];

                content.id = 'posting_content';
                content.innerHTML = full_posting['data']['posting_content'];
    
                if(full_posting['author'] > 0 && full_posting['data']['posting_state'] == 0) {
                    var button = document.createElement('button');
                    container.appendChild(button);
                    button.className = 'posting_control';
                    button.innerHTML = '삭제';
                    button.onclick = function(){
                        disableClick()
                    }
                }
                if(full_posting['author'] > 0 && full_posting['data']['posting_state'] == 1) {
                    var button = document.createElement('button');
                    container.appendChild(button);
                    button.className = 'posting_control';
                    button.innerHTML = '복구';
                    button.onclick = function(){
                        enableClick()
                    }
                }
                if(full_posting['author'] > 0 && full_posting['data']['posting_state'] == 0) {
                    var button = document.createElement('button');
                    container.appendChild(button);
                    button.className = 'posting_control';
                    button.innerHTML = '수정';
                    button.onclick = function(){
                        editClick()
                    }
                }
            };
            xhr.send();
        }

        function editClick(){
            location.href = 'writer?posting_index=' + posting_index;
        }
        function enableClick(){
            var formData = new FormData();
            formData.append('user_id', getCookie('user_id'));
            formData.append('user_pw', getCookie('user_pw'));
            formData.append('posting_index', posting_index);

            var xhr = new XMLHttpRequest();
            var url = 'post/enable_posting';
            xhr.open('POST', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState != XMLHttpRequest.DONE) return;
                if (xhr.status != 200) {
                    alert('Server Error (' + xhr.status + ')');
                    return;
                }
                    
                var result = JSON.parse(this.responseText);
                if (result['state'] != 0) {
                    alert('잘못된 접근 (' + result['state'] + ')');
                    return;
                }

                
                alert('복구 되었습니다.');
                location.href = 'reader?posting_index=' + posting_index;
            }
            xhr.send(formData);
        }
        function disableClick(){
            var formData = new FormData();
            formData.append('user_id', getCookie('user_id'));
            formData.append('user_pw', getCookie('user_pw'));
            formData.append('posting_index', posting_index);

            var xhr = new XMLHttpRequest();
            var url = 'post/disable_posting';
            xhr.open('POST', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState != XMLHttpRequest.DONE) return;
                if (xhr.status != 200) {
                    alert('Server Error (' + xhr.status + ')');
                    return;
                }

                var result = JSON.parse(this.responseText);
                if (result['state'] != 0) {
                    alert('잘못된 접근 (' + result['state'] + ')');
                    return;
                }


                alert('삭제 되었습니다.');
                location.href = 'reader?posting_index=' + posting_index;
            }
            xhr.send(formData);
        }
    </script>
</body>
</html>