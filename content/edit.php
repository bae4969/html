<!-- content/edit.php -->
<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Blog</title>
    <link rel="stylesheet" href="/css/after.css">
    <link rel="stylesheet" href="/css/main_outer.css">
    <link rel="stylesheet" href="/css/main_header.css">
    <link rel="stylesheet" href="/css/main_aside.css">
    <link rel="stylesheet" href="/css/main_writeContent.css">
    <link rel="stylesheet" href="/css/main_media.css">
    <link rel="stylesheet" href="/css/main_footer.css">

    <?php
        include '../php/basic.php';
        include '../php/load.php';
        include '../php/save.php';
        $user = checkUser($_POST['id'], $_POST['pw']);
        $content = getEditContent($user['user_index'], $_POST['content_index']);
    ?>

    <script src="/js/main.js"> </script>
    <script>
        var submitLeave = false;

        window.onload = function() {
            <?php echoMainOnload($user['user_index']) ?>

            if(sessionStorage.getItem('class_index') !== null){
                var select = document.getElementById("input_class");
                for(i = 0; i < select.length; i++)
                    if(select[i].value == sessionStorage.getItem('class_index')){
                        select[i].selected = true;
                        break;
                    }
            }

            if(sessionStorage.getItem('title') !== null)
                document.getElementById("input_title").value = sessionStorage.getItem('title');

            if(sessionStorage.getItem('thumbnail') !== null)
                document.getElementById("input_thumbnail").value = sessionStorage.getItem('thumbnail');

            if(sessionStorage.getItem('content') !== null)
                document.getElementById("input_content").value = sessionStorage.getItem('content');
        }

        window.onbeforeunload = function(){
            if(submitLeave == false){
                sessionStorage.removeItem('title');
                sessionStorage.removeItem('thumbnail');
                sessionStorage.removeItem('class_index');
                sessionStorage.removeItem('content');
            }
        }

        function onInput(input, max_length){
            if(input.value.length > max_length){
                alert('최대 문자열 길이는 ' + max_length + 'byte 입니다.')
                return input.value = input.value.substring(0, max_length);
            }
        }

        function autoHeight(textarea) {
            textarea.style.height = "1px";
            textarea.style.height = (16 + textarea.scrollHeight)+"px";
        };

        function onFileUpload(event){
            event.preventDefault();
            let file = event.target.files[0];
        }

        function submitClick(){
            if(document.getElementById("input_title").value == ''){
                alert('제목을 작성해주세요.')
            }
            else if(document.getElementById("input_class").value < 1){
                alert('분류를 선택해주세요.');
                return;
            }
            else if(document.getElementById("input_content").value == ''){
                alert('내용을 작성해주세요.')
            }

            submitLeave = true;


            var form = getDefaultPostForm('/content/writerCheck');

            sessionStorage.setItem('title', document.getElementById("input_title").value);
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', 'title');
            hiddenField.setAttribute('value', document.getElementById("input_title").value);
            form.appendChild(hiddenField);

            //sessionStorage.setItem('thumbnail', document.getElementById("input_thumbnail").value);
            // var hiddenField = document.createElement('input');
            // hiddenField.setAttribute('type', 'hidden');
            // hiddenField.setAttribute('name', 'thumbnail');
            // hiddenField.setAttribute('value', document.getElementById("input_thumbnail").value);
            // form.appendChild(hiddenField);

            sessionStorage.setItem('class_index', document.getElementById("input_class").value);
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', 'class_index');
            hiddenField.setAttribute('value', document.getElementById("input_class").value);
            form.appendChild(hiddenField);

            sessionStorage.setItem('content', document.getElementById("input_content").value);
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', 'content');
            hiddenField.setAttribute('value', document.getElementById("input_content").value);
            form.appendChild(hiddenField);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</head>
<body>
    <div id="main">
        <header> <?php echoHeader($user['user_index'], $user['level']); ?> </header>
        <section>
            <aside>
                <div id=profile> profile </div>
                <ul id=category> <?php echoAsideList($user['level']); ?> </ul>
            </aside>
            <div id=content>
                <input id=input_title type='text' placeholder='제목' oninput='onInput(this, 120)'/>
                <!-- <div id="preview"><div id=photo></div></div>
                <input id=input_thumbnail type=file accept=image/* placeholder='대표 사진' onchange="onFileUpload(this)"/> -->
                <select id=input_class>
                    <option value=0>분류 선택</option>
                    <?php echoSelectClassList($user['level']); ?>
                </select>
                <textarea id=input_content type='text' placeholder='내용' oninput='onInput(this, 3000)' onkeyup="autoHeight(this);"></textarea>
                <button id='btn_submit' onclick=submitClick()>제출</button>
            </div>
        </section>
        <footer> <?php echoFooter(); ?> </footer>
    </div>
</body>
</html>
