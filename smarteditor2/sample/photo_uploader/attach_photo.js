var aResult = [];
var oFileUploader;
var welBtnConfirm = $Element("btn_confirm");
var welBtnCancel= $Element("btn_cancel");
var fnUploadImage = null;


function goStartMode(){
	var sSrc = welBtnConfirm.attr("src")|| "";
	if(sSrc.indexOf("btn_confirm2.png") < 0 ){
		welBtnConfirm.attr("src","./img/btn_confirm2.png");
		fnUploadImage.attach(welBtnConfirm.$value(), "click");
	}
} 
function goReadyMode(){
	var sSrc = welBtnConfirm.attr("src")|| "";
	if(sSrc.indexOf("btn_confirm2.png") >= 0 ){
		fnUploadImage.detach(welBtnConfirm.$value(), "click");
		welBtnConfirm.attr("src","./img/btn_confirm.png");
	}
}

function callFileUploader (){
	oFileUploader = new jindo.FileUploader(jindo.$("uploadInputBox"),{
		sUrl  : '/smarteditor2/sample/photo_uploader/file_uploader.php',
		sCallback : '/smarteditor2/sample/photo_uploader/callback.html',
		sFiletype : "*.jpg;*.png;*.bmp;*.gif",
		sMsgNotAllowedExt : 'JPG, GIF, PNG, BMP 확장자만 가능합니다',
		bAutoUpload : false,
		bAutoReset : true
	}).attach({
		select : function(oCustomEvent) {
			if(oCustomEvent.bAllowed === true){
				goStartMode();
			}else{
				goReadyMode();
				oFileUploader.reset();
			}
		},
		success : function(oCustomEvent) {
			console.log(oCustomEvent.htResult);
			var aResult = []; 
			aResult[0] = oCustomEvent.htResult;
			setPhotoToEditor(aResult); 
			goReadyMode();
			oFileUploader.reset();
			window.close();
		},
		error : function(oCustomEvent) {
			alert(oCustomEvent.htResult.errstr);
		}
	});
}

function uploadImage (e){
	oFileUploader.upload();
}
function closeWindow(){
	window.close();
}

window.onload = function(){
	callFileUploader();
	fnUploadImage = $Fn(uploadImage,this);
	$Fn(closeWindow,this).attach(welBtnCancel.$value(), "click");
};

function setPhotoToEditor(oFileInfo){
	if (!!opener && !!opener.nhn && !!opener.nhn.husky && !!opener.nhn.husky.PopUpManager) {
		opener.nhn.husky.PopUpManager.setCallback(window, 'SET_PHOTO', [oFileInfo]);
	}
}

jindo.$Ajax.prototype.request = function(oData) {
	this._status++;
	var t   = this;
	var req = this._request;
	var opt = this._options;
	var data, v,a = [], data = "";
	var _timer = null;
	var url = this._url;
	this._is_abort = false;

	if( opt.postBody && opt.type.toUpperCase()=="XHR" && opt.method.toUpperCase()!="GET"){
		if(typeof oData == 'string'){
			data = oData;
		}else{
			data = jindo.$Json(oData).toString();	
		}	
	}else if (typeof oData == "undefined" || !oData) {
		data = null;
	} else {
		data = oData;
	}
	
	req.open(opt.method.toUpperCase(), url, opt.async);
	if (opt.sendheader) {
		if(!this._headers["Content-Type"]){
			req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");
		}
		req.setRequestHeader("charset", "utf-8");
		for (var x in this._headers) {
			if(this._headers.hasOwnProperty(x)){
				if (typeof this._headers[x] == "function") 
					continue;
				req.setRequestHeader(x, String(this._headers[x]));
			}
		}
	}
	var navi = navigator.userAgent;
	if(req.addEventListener&&!(navi.indexOf("Opera") > -1)&&!(navi.indexOf("MSIE") > -1)){
		if(this._loadFunc){ req.removeEventListener("load", this._loadFunc, false); }
		this._loadFunc = function(rq){ 
			clearTimeout(_timer);
			_timer = undefined; 
			t._onload(rq); 
		}
		req.addEventListener("load", this._loadFunc, false);
	}else{
		if (typeof req.onload != "undefined") {
			req.onload = function(rq){
				if(req.readyState == 4 && !t._is_abort){
					clearTimeout(_timer); 
					_timer = undefined;
					t._onload(rq);
				}
			};
		} else {
			if(window.navigator.userAgent.match(/(?:MSIE) ([0-9.]+)/)[1]==6&&opt.async){
				var onreadystatechange = function(rq){
					if(req.readyState == 4 && !t._is_abort){
						if(_timer){
							clearTimeout(_timer);
							_timer = undefined;
						}
						t._onload(rq);
						clearInterval(t._interval);
						t._interval = undefined;
					}
				};
				this._interval = setInterval(onreadystatechange,300);

			}else{
				req.onreadystatechange = function(rq){
					if(req.readyState == 4){
						clearTimeout(_timer); 
						_timer = undefined;
						t._onload(rq);
					}
				};
			}
		}
	}

	req.send(data);
	return this;
};
