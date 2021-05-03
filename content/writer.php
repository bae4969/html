<!-- content/writer.php -->
<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Blog</title>
    <link rel='shortcut icon' href=/res/favicon.ico type=image/x-icon>
    <link rel='icon' href=/res/favicon.ico type=image/x-icon>
    <link rel="stylesheet" href="/css/after.css">
    <link rel="stylesheet" href="/css/main_outer.css">
    <link rel="stylesheet" href="/css/main_header.css">
    <link rel="stylesheet" href="/css/main_aside.css">
    <link rel="stylesheet" href="/css/main_writeContent.css">
    <link rel="stylesheet" href="/css/main_footer.css">

    <?php
        include '../php/basic.php';
        include '../php/load.php';
        include '../php/control.php';
        $user = checkUser($_POST['id'], $_POST['pw']);
    ?>

    <script type="text/javascript" src="/smarteditor2/js/HuskyEZCreator.js" charset="utf-8"></script>
    <script src="/js/main.js"></script>
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

            if(sessionStorage.getItem('content') !== null)
                document.getElementById("input_content").value = sessionStorage.getItem('content');
        }

        window.onbeforeunload = function(){
            if(submitLeave == false){
                sessionStorage.removeItem('title');
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

            submitLeave = true;
            oEditors.getById["input_content"].exec("UPDATE_CONTENTS_FIELD", []);

            var form = getDefaultPostForm('writerCheck');
            var editorStr = document.getElementById("input_content").value;
            var editorFrame = document.getElementById('editor_frame');
            var inputFrame = editorFrame.contentWindow.document.getElementById('se2_iframe');
            var imgClass = inputFrame.contentWindow.document.getElementsByClassName('photo');

            if(imgClass.length > 0){
                var thumbnail_src = imgClass[0].src;
                var thumbnail_title = imgClass[0].title;

                var hiddenField = document.createElement('input');
                hiddenField.setAttribute('type', 'hidden');
                hiddenField.setAttribute('name', 'thumbnail');
                hiddenField.setAttribute('value', 'src="'+thumbnail_src+'" title="'+thumbnail_title+'"');
                form.appendChild(hiddenField);
            }

            sessionStorage.setItem('title', document.getElementById("input_title").value);
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', 'title');
            hiddenField.setAttribute('value', document.getElementById("input_title").value);
            form.appendChild(hiddenField);

            sessionStorage.setItem('class_index', document.getElementById("input_class").value);
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', 'class_index');
            hiddenField.setAttribute('value', document.getElementById("input_class").value);
            form.appendChild(hiddenField);

            var inputArea = inputFrame.contentWindow.document.getElementsByClassName('se2_inputarea')[0];
            var summaryStr = inputArea.innerText.substring(0, 200);
            if(inputArea.innerText.length > 200) summaryStr += '...';
            summaryStr  = summaryStr.replaceAll('\n\n', ' ');
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', 'summary');
            hiddenField.setAttribute('value', summaryStr);
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
            <div id=content>
                <input id=input_title type='text' placeholder='제목 (최대 30자)' oninput='onInput(this, 30)'/>
                <select id=input_class>
                    <option value=0>분류 선택</option>
                    <?php echoSelectClassList($user['level']); ?>
                </select>
                <textarea id=input_content name=input_content style="width:100%; height:800px; min-width:600px; display:none;"></textarea>
                <button id='btn_submit' onclick=submitClick()>제출</button>
            </div>
        </section>
        <footer> <?php echoFooter(); ?> </footer>
    </div>
    <script type="text/javascript">
        var oEditors = [];
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
    </script>
</body>
</html>
