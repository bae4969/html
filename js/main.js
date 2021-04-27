// main.js
// functions for index page and etc

function setCookie(name, val, exp){
    exp = typeof exp !== 'undefined' ? exp : 1;
    var date = new Date();
    date.setTime(date.getTime() + exp * 3600000);
    document.cookie = name + '=' + val + ';expires=' + date.toUTCString() + ';path=/';
}
function getCookie(name, exp){
    exp = typeof exp !== 'undefined' ? exp : 1;
    var value = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    if(value != null) setCookie(name, value[2], exp);
    return value? value[2] : null;
}
function deleteCookie(name){
    document.cookie = name + '=; expires=Thu, 01 Jan 1999 00:00:10 GMT;';
}

function getDefaultPostForm(url){
    var id = getCookie('id');
    var pw = getCookie('pw');
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
        deleteCookie('id');
        deleteCookie('pw');
        sessionStorage.removeItem('title');
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
 