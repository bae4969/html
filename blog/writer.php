<!-- content/writer.php -->
<!doctype html>
<html lang=ko>
<head>
    <meta charset='utf-8'>
    <title>BWP Dev News</title>
    <link type="text/css" rel="stylesheet" href="css/writeEdit.css">
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
        <section>
            <div id=content>
                <input id=input_title type='text' placeholder='제목 (최대 30자)' oninput='onInput(this, 50)'/>
                <select id=input_class>
                    <option value=0>분류 선택</option>
                </select>
                <textarea id=input_content name=input_content></textarea>
                <button id='btn_submit' onclick=submitClick()>제출</button>
            </div>
        </section>
        <footer>
            <p>Contact : bae4969@naver.com</br>
            Github : <a class=footer href=https://github.com/bae4969>https://github.com/bae4969</a></p>
        </footer>
    </div>

    <script type="text/javascript" src="/smarteditor2/js/HuskyEZCreator.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/basicFunc.js" charset="utf-8"></script>
    <script type="text/javascript">
        var oEditors = [];
        
        window.onload = function() {
            nhn.husky.EZCreator.createInIFrame({
                oAppRef: oEditors,
                elPlaceHolder: "input_content",
                sSkinURI: "/smarteditor2/SmartEditor2Skin.html",
                htParams : {
                    bUseVerticalResizer : false,
                    bUseModeChanger : false,
                },
                fCreator: "createSEditor2"
            });
            checkUserInfo();
            initSelectClass();
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
                        if(user['write_limit'] > 20){
                            alert('하루 글쓰기 수가 초과 되었습니다.')
                            location.href = 'index';
                        }
                    }
                    else {
                        alert('잘못된 접근')
                        location.href = 'index';
                    }
                }
            };
            xhr.send();
        }
        function initSelectClass(){
            var xhr = new XMLHttpRequest();
            var url = 'get/selectClass';
            url += '?id=' + getCookie('id');
            url += '&pw=' + getCookie('pw');
            xhr.open('GET', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200){
                    var class_list = JSON.parse(this.responseText);
                    if(class_list['state'] == 0){
                        var input_class = document.getElementById('input_class');
                        for(var i = 0; i < class_list['data'].length; i++){
                            var option = document.createElement('option');
                            option.value = class_list['data'][i]['class_index']
                            option.innerHTML = class_list['data'][i]['name'];
                            input_class.appendChild(option);
                        }
                    }
                }
            };
            xhr.send();
        }

        function onInput(input, max_length){
            if(input.value.length > max_length){
                alert('최대 문자열 길이는 ' + max_length + 'byte 입니다.')
                return input.value = input.value.substring(0, max_length);
            }
        }

        function submitClick(){
            if(document.getElementById("input_title").value == ''){
                alert('제목을 작성해주세요.')
                return;
            }
            else if(document.getElementById("input_class").value < 1){
                alert('분류를 선택해주세요.');
                return;
            }
            else if(document.getElementById("input_content").value == '<p>&nbsp;</p>'){
                alert('내용을 작성해주세요.')
                return;
            }
            
            oEditors.getById["input_content"].exec("UPDATE_CONTENTS_FIELD", []);

            var formData = new FormData();
            formData.append('id', getCookie('id'));
            formData.append('pw', getCookie('pw'));

            var editorStr = document.getElementById("input_content").value;
            editorStr = editorStr.replaceAll('<div', '<p');
            editorStr = editorStr.replaceAll('</div>', '</p>');

            var editorFrame = document.getElementById('editor_frame');
            var inputFrame = editorFrame.contentWindow.document.getElementById('se2_iframe');
            var imgClass = inputFrame.contentWindow.document.getElementsByClassName('photo');

            var inputArea = inputFrame.contentWindow.document.getElementsByClassName('se2_inputarea')[0];
            var summaryStr = inputArea.innerText.substring(0, 200);
            if(inputArea.innerText.length > 200) summaryStr += '...';
            summaryStr  = summaryStr.replaceAll('\n', ' ');

            if(imgClass.length > 0){
                var path = imgClass[0].src.split('/res/');
                formData.append('thumbnail', '/res/' + path[1]);
            }
            else
                formData.append('thumbnail', '');
            formData.append('title', document.getElementById("input_title").value);
            formData.append('class_index', document.getElementById("input_class").value);
            formData.append('summary', summaryStr);
            formData.append('content', editorStr);

            var xhr = new XMLHttpRequest();
            var url = 'post/contentWrite';
            xhr.open('POST', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE){
                    if(xhr.status == 200){
                        var result = JSON.parse(this.responseText);
                        if(result['state'] == 0)
                            location.href = 'reader?content_index=' + result['data'];
                        else 
                            alert(result['data']);
                    }
                    else alert('Server Error (' + xhr.status + ')');
                }
            }
            xhr.send(formData);
        }
    </script>
</body>
</html>
