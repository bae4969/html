var loginout = function() {
    if (isLogin == 'true') {
        localStorage.setItem("isLogin", false);
        localStorage.removeItem("id");
        localStorage.removeItem("pw");
        alert("로그아웃");
        homeClick();
    }
    else{
        location.href = 'login';
    }
}

var getDefaultPostForm = function(url){
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

var homeClick = function(){
    var form = getDefaultPostForm('index');
    document.body.appendChild(form);
    form.submit();
}

var classClick = function(class_index){
    var form = getDefaultPostForm('index');
    var hiddenField = document.createElement('input');
    hiddenField.setAttribute('type', 'hidden');
    hiddenField.setAttribute('name', 'class');
    hiddenField.setAttribute('value', class_index);
    form.appendChild(hiddenField);
    document.body.appendChild(form);
    form.submit();
}

var contentClick = function(content_index){
    var form = getDefaultPostForm('content');
    var hiddenField = document.createElement('input');
    hiddenField.setAttribute('type', 'hidden');
    hiddenField.setAttribute('name', 'index');
    hiddenField.setAttribute('value', content_index);
    form.appendChild(hiddenField);
    document.body.appendChild(form);
    form.submit();
}
