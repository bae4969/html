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
        $user = checkUser($_POST['id'], $_POST['pw']);
        $content = getEditContent($user['user_index'], $_POST['content_index']);
    ?>

    <script src="/js/main.js"> </script>
    <script>
        var submitLeave = false;

        window.onload = function() {
            <?php echoMainOnload($user['user_index']) ?>

            document.getElementById("input_title").value = <?php echo '"'.$content['title'].'";'; ?>
            document.getElementById("input_content").value = <?php echo '"'.$content['content'].'";'; ?>
        }

        window.onbeforeunload = function(){
            if(submitLeave == false){
                sessionStorage.removeItem('title');
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
            else if(document.getElementById("input_content").value == ''){
                alert('내용을 작성해주세요.')
            }

            submitLeave = true;


            var form = getDefaultPostForm('/content/editCheck');

            sessionStorage.setItem('title', document.getElementById("input_title").value);
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', 'content_index');
            hiddenField.setAttribute('value', <?php echo $_POST['content_index']; ?>);
            form.appendChild(hiddenField);

            sessionStorage.setItem('title', document.getElementById("input_title").value);
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', 'title');
            hiddenField.setAttribute('value', document.getElementById("input_title").value);
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
                <textarea id=input_content type='text' placeholder='내용' oninput='onInput(this, 3000)' onkeyup="autoHeight(this);"></textarea>
                <button id='btn_submit' onclick=submitClick()>수정</button>
            </div>
        </section>
        <footer> <?php echoFooter(); ?> </footer>
    </div>
</body>
</html>
