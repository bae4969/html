function getDefaultPostForm(url){
    var id = localStorage.getItem('id');
    var pw = localStorage.getItem('pw');
    var form = document.createElement('form');

    form.setAttribute('method', 'post');
    form.setAttribute('action', url);
    document.charset = "utf-8";

    if(id != null && pw != null){
        var hiddenField = document.createElement('input');
        hiddenField.setAttribute('type', 'hidden');
        hiddenField.setAttribute('name', 'id');
        hiddenField.setAttribute('value', id);
        form.appendChild(hiddenField);
        var hiddenField = document.createElement('input');
        hiddenField.setAttribute('type', 'hidden');
        hiddenField.setAttribute('name', 'pw');
        hiddenField.setAttribute('value', pw);
        form.appendChild(hiddenField);
    }

    return form
}

function loginoutClick(user_index) {
    if (user_index > 0) {
        localStorage.setItem("isLogin", false);
        localStorage.removeItem("id");
        localStorage.removeItem("pw");
        sessionStorage.removeItem('class_index');
        sessionStorage.removeItem('title');
        sessionStorage.removeItem('thumbnail');
        sessionStorage.removeItem('content');
        alert("로그아웃");
        homeClick();
    }
    else{
        location.href = '/login';
    }
}

function writeClick(user_index){
    if (user_index > 0){
        var form = getDefaultPostForm('/content/writer');
        document.body.appendChild(form);
        form.submit();
    }
}

function homeClick(){
    var form = getDefaultPostForm('/index');
    document.body.appendChild(form);
    form.submit();
}

function classClick(class_index){
    var form = getDefaultPostForm('/index');
    var hiddenField = document.createElement('input');
    hiddenField.setAttribute('type', 'hidden');
    hiddenField.setAttribute('name', 'class_index');
    hiddenField.setAttribute('value', class_index);
    form.appendChild(hiddenField);
    document.body.appendChild(form);
    form.submit();
}

function contentClick(content_index){
    var form = getDefaultPostForm('/content/reader');
    var hiddenField = document.createElement('input');
    hiddenField.setAttribute('type', 'hidden');
    hiddenField.setAttribute('name', 'content_index');
    hiddenField.setAttribute('value', content_index);
    form.appendChild(hiddenField);
    document.body.appendChild(form);
    form.submit();
}
 