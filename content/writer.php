<!-- content/writer.php -->
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
    ?>

    <script src="/js/main.js"> </script>
    <script>
        var submitLeave = false;

        window.onload = function() {
            <?php echoMainOnload($user['user_index']) ?>

            if(sessionStorage.getItem('class_index') !== null){
                var select = document.getElementById("select_class");
                for(i = 0; i < select.length; i++)
                    if(select[i].value == sessionStorage.getItem('class_index'))
                        select[i].selected = true;
            }

            if(sessionStorage.getItem('title') !== null)
                document.getElementById("input_title").value = sessionStorage.getItem('title');

            if(sessionStorage.getItem('thumbnail') !== null)
                document.getElementById("input_thumbnail").value = sessionStorage.getItem('thumbnail');

            if(sessionStorage.getItem('content') !== null)
                document.getElementById("input_content").value = sessionStorage.getItem('content');
        }

        window.onbeforeunload = function(){
            if(submitLeave === false){
                sessionStorage.removeItem('class_index');
                sessionStorage.removeItem('title');
                sessionStorage.removeItem('thumbnail');
                sessionStorage.removeItem('content');
            }
        }

        function submitClick(){
            if(document.getElementById("select_class").value < 1){
                alert('분류를 선택해주세요.');
                return;
            }
            
            submitLeave = true;

            sessionStorage.setItem('class_index', document.getElementById("select_class").value);
            sessionStorage.setItem('title', document.getElementById("input_title").value);
            sessionStorage.setItem('thumbnail', document.getElementById("input_thumbnail").value);
            sessionStorage.setItem('content', document.getElementById("input_content").value);

            var form = getDefaultPostForm('/content/writerCheck');

            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', 'class_index');
            hiddenField.setAttribute('value', document.getElementById("select_class").value);
            form.appendChild(hiddenField);

            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', 'title');
            hiddenField.setAttribute('value', document.getElementById("input_title").value);
            form.appendChild(hiddenField);

            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', 'thumbnail');
            hiddenField.setAttribute('value', document.getElementById("input_thumbnail").value);
            form.appendChild(hiddenField);

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
                <div class='inputBox'>
                    <input id=input_title type='text' placeholder='제목'/>
                </div>
                
                <div class='inputBox'>
                    <select id=select_class>
                        <option value=0>분류 선택</option>
                        <?php echoSelectClassList($user['level']); ?>
                    </select>
                </div>

                <div class='inputBox'>
                    <input id=input_thumbnail type='text' placeholder='대표 사진'/>
                </div>

                <div class='inputBox'>
                    <input id=input_content type='text' placeholder='내용'/>
                </div>

                <div class='inputBox'>
                    <button id='btn_submit' onclick=submitClick()>제출</button>
                </div>
            </div>

        </section>
        <footer> <?php echoFooter(); ?> </footer>
    </div>
</body>
</html>