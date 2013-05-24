//自动登录失败处理
function fCheckAutoLogin(){
	try{
		if(window.location.href.indexOf('#return') != -1){
			if(gOption["sDomain"] == '163.com'){
				fSetCookie('P_INFO', '', false, gOption["sDomain"]);
			}else{
				fSetCookie('P_INFO', '');
			}
		}
	}catch(e){}
	return;
}

//两周自动登录
function fAutoLogin(){
	try{
		var sSInfo = fGetCookie("S_INFO");
		var sPInfo = fGetCookie("P_INFO");
		var sEmail = "",sTime = "",sState = "",nTimeDiff = 0;
		if(sPInfo){
			var aInfo = sPInfo.split("|");
			sEmail = aInfo[0];
			sTime = aInfo[1];
			sState = aInfo[2];
			nTimeDiff = (new Date()).getTime()-(sTime+"000");
		}
		if(!location.hash && sEmail.indexOf("@"+gOption["sDomain"])>-1 && sState=="1" && nTimeDiff<14*24*60*60*1000){
			window.location.href= gOption["sAutoLoginUrl"];
		}
	}catch(exp){}
}

//浏览器判断跳转
var gbForcepc;
var oAndroidRedirect = {
	sPhoneUrl	:	'',
	sPadUrl		:	''
};
function fCheckBrowser(){
	gbForcepc = fGetQuery("dv") == "pc";
	if(1==2){
		var sUserAgent = navigator.userAgent.toLowerCase();
		var sUrlRedirect;
		var oUrlRedirect = {
			"ipad" : "http://ipad.mail." + gOption["sDomain"] + "/?dv=ipad",
			"pad"	: "http://pad.mail." + gOption["sDomain"] + "/",
			"smart" : "http://smart.mail." + gOption["sDomain"] + "/?dv=smart",
			"m" : "http://m.mail." + gOption["sDomain"] + "/"
		};
		var aClient = ["ipad","iphone os","android","ucweb","rv:1.2.3.4","windows ce","windows mobile","midp"];
		for(var i=0;i<aClient.length;i++){
			if(sUserAgent.indexOf(aClient[i]) != -1){
				switch(aClient[i]){
					case "ipad" : 
						sUrlRedirect = oUrlRedirect["ipad"];
						break;
					case "iphone os" :
						sUrlRedirect = oUrlRedirect["smart"];
						break;
					case "android" :
						oAndroidRedirect = {
							sPhoneUrl	:	oUrlRedirect["smart"],
							sPadUrl		:	oUrlRedirect["pad"]
						};
						DOMREADY(
							function(){
								fGetScript('http://mimg.127.net/index/lib/scripts/android.js');
							}
						);
						return false;
						break;
					default :
						sUrlRedirect = oUrlRedirect["m"];
				}
			//window.location.href = sUrlRedirect;
			}
		}
	}
}

//Html5标签支持
function fHtml5Tag(){
	var aTag = ["aside","figcaption","figure","footer","header","hgroup","nav","section"],i = 0;
	for(i in aTag){document.createElement(aTag[i]);}
}

//COOKIE功能检查
function fCheckCookie(){
	if(!navigator.cookieEnabled){
		alert("您好，您的浏览器设置禁止使用cookie\n请设置您的浏览器，启用cookie功能，再重新登录。");
	}
}

//////////////////////////////////////////////////////////////
//基础功能
//////////////////////////////////////////////////////////////

//获取参数值
function fGetQuery(name){
	var sUrl = window.location.search.substr(1);
	var r = sUrl.match(new RegExp("(^|&)" + name + "=([^&]*)(&|$)"));
	return (r == null ? null : unescape(r[2]));
}

//获取#参数值
function fGetQueryHash(name){
	var sUrl = window.location.hash.substr(1);
	var r = sUrl.match(new RegExp("(^|&)" + name + "=([^&]*)(&|$)"));
	return (r == null ? null : unescape(r[2]));
}

//GetElementById
function $id(sId){
	return document.getElementById(sId);
}

//过滤帐号
function fTrim(str){
	return str.replace(/(^\s*)|(\s*$)/g, "").replace(/(^　*)|(　*$)/g, "");
}

//过滤手机号
function fParseMNum(sNum){
	var sTmpNum = fTrim(sNum);
	return /^0?(13|14|15|18)\d{9}$/.test(sTmpNum);
}

//自动截断对应域email地址
function fCheckAccount(sEmail){
	var sAccount = sEmail;
	var bAt;
	bAt = sAccount.indexOf("@" + gOption["sDomain"]) == -1;
	if(!bAt){
		var aAccountSplit;
		aAccountSplit=sEmail.split("@");
		sEmail=aAccountSplit[0];
	}
	return sEmail;
}

//跨域调用方法
function fGetScript(sUrl){
	var oScript = document.createElement("script");
	oScript.setAttribute("type", "text/javascript");
	oScript.setAttribute("src", sUrl);
	try{oScript.setAttribute("defer", "defer");}catch(e){}
	window.document.body.appendChild(oScript);
}

//获取Cookie
function fGetCookie(sName){
   var sSearch = sName + "=";
   if(document.cookie.length > 0){
      offset = document.cookie.indexOf(sSearch)
      if(offset != -1){
         offset += sSearch.length;
         end = document.cookie.indexOf(";", offset)
         if(end == -1) end = document.cookie.length;
         return unescape(document.cookie.substring(offset, end))
      }
      else return ""
   }
}

//设置Cookie
function fSetCookie(name, value, isForever, domain){
	var sDomain = ";domain=" + (domain || gOption["sCookieDomain"] );
	document.cookie = name + "=" + escape(value) + sDomain + (isForever?";expires="+  (new Date(2099,12,31)).toGMTString():"");
}

//绑定事件监听
function fEventListen(oElement, sName, fObserver, bUseCapture){
	bUseCapture = !!bUseCapture;
	if (oElement.addEventListener){
		oElement.addEventListener(sName, fObserver, bUseCapture);
	}else if(oElement.attachEvent){
		oElement.attachEvent('on' + sName, fObserver);
	}
}

//删除事件监听
function fEventUnlisten(oElement, sName, fObserver, bUseCapture){
	bUseCapture = !!bUseCapture;
	if(oElement.removeEventListener){
		oElement.removeEventListener(sName, fObserver, bUseCapture);
	}else if(oElement.detachEvent){
		oElement.detachEvent('on' + sName, fObserver);
	}
}

//限定范围随机数
function fRandom(nLength){
	return Math.floor(nLength * Math.random());
}

//url参数
function fUrlP(sName,sValue,bIsFirst){
	if(!arguments[2]){bIsFirst = false;}
	if(bIsFirst){		
		return sName + '=' + sValue;
	}else{
		return '&' + sName + '=' + sValue;
	}
}

//同步改变窗口大小与遮罩
function fResize(){
	var nBodyHeight = document.body.offsetHeight,
		nWindowHeight = document.documentElement.clientHeight,
		nResult;
	if(nBodyHeight > nWindowHeight){
		nResult = nBodyHeight;
	}else{
		nResult = nWindowHeight;
	}
	$id("mask").style.height = nResult + "px"
}

//////////////////////////////////////////////////////////////
//具体功能
//////////////////////////////////////////////////////////////

//fFQ
function fFQ(){
	var sFqLf = fGetQuery("fq");
	var bEnableFQ = (/^[0-9]/).test(sFqLf);
	var sFQuid = fGetQuery("uid");
	var bTestMail = (new RegExp("(@"+ gOption["sDomain"] +")$")).test(sFQuid);
	if(bEnableFQ && bTestMail){
		var nFQrandom = (new Date()).getTime();
		fSetCookie("fq",sFqLf+"_"+nFQrandom,false);
		var oImg = document.createElement("img");
		var sImgUrl = "http://count.mail.163.com/beacon/login.gif?uid=" + sFQuid + "&fq=" + nFQrandom + "&lf=" + fGetQuery("fq");
		oImg.setAttribute("src", sImgUrl);
		oImg.setAttribute("alt", "");
		oImg.style.display = "none";
		document.body.appendChild(oImg);
	}
}

//设置starttime的Cookie
function fStartTime(){
	var sSt = fGetCookie("starttime");
	if( sSt == "" ){
		sSt = (new Date()).getTime();
		fSetCookie("starttime",sSt,false);
	}
}

//处理nts_mail_user的Cookie
var gUserInfo = {
	"username" : null,
	"style"    : null,
	"safe"     : null
}
var gVisitorCookie = (function(){
	var _fGetNtsMailUser = function(){
		var sUserInfo = fGetCookie("nts_mail_user");
		if( sUserInfo != undefined ){
			var aTmp = sUserInfo.split(":");			
			if( aTmp.length == 3 ){
				gUserInfo["username"] = aTmp[0];
				gUserInfo["style"] = aTmp[1];
				gUserInfo["safe"] = aTmp[2];
			}
		}
		return;
	},
	_fSetNtsMailUser = function(){
		var sUserInfo = gUserInfo.username + ":" + gUserInfo.style + ":" + gUserInfo.safe;
		_fSetNtsMailCookie("nts_mail_user",sUserInfo,true);
		return;
	},
	_fSetNtsMailCookie = function(name, value, isForever, domain){
		var sDomain = ";domain=" + (domain || gOption["sCookieDomain"] );
		document.cookie = name + "=" + value + sDomain + (isForever?";expires="+  (new Date(2099,12,31)).toGMTString():"");
	};
	return {
		"init" : function(){
			_fGetNtsMailUser();
			return this;
		},
		"saveInfo" : function(){
			_fSetNtsMailUser();
		},
		"loadInfo" : function(){
			_fGetNtsMailUser();
		}
	};
})().init();

//手机号码帐号提示
var gMobileNumMailIsForbidden,
	gMobileNumMailResult,
	gMobileNumMail= (function(){
	var sInterFaceFromMail = "http://mbind.mail.126.com/mbind/qn.do?uid=",
		sInterFaceFromNum = "http://mbind.mail.126.com/mbind/qu.do?pn=",
		sCurAddr,
		sCurNum,
		P_INFO = {},
		sNum,
		sTel, //保存上次查询结果
		sEmail, //保存上次查询结果
		SECSTR = ["xxxxx","xxx"],
		sTimestamp =  Math.round((new Date()).getTime() / 86400000) + "",
		
		//检测是否关闭此手机功能
		_fIsForbidden = function(){ 
			var nTmp = fGetCookie("MTip");
			if( nTmp == 1 ){
				return true;
			}else{
				return false;
			}
		},
		
		//设置关闭手机号码功能
		_fSetForbidden = function(){ 
			fSetCookie("MTip","1",true);
			$id("mobtips").style.display = 'none';
		},
		
		//使用邮箱地址获取手机号码
		_fGetNumFromMail = function(sMail){
			var sUid, nCheckResult;
			sUid = sMail + "@" + gOption["sDomain"];
			nCheckResult = _fCheckAddr(sMail, sUid);
			if(nCheckResult === -1){
				MobCallback({
					"nCode" : "private",
					"sNum" : "invalidMail"
				});
				return;
			}
			if(nCheckResult === 0){
				if(sTel === undefined){
					MobCallback({
						"nCode" : "404"
					});
				}
				else{			
					MobCallback({
						"nCode" : "200",
						"sNum" : sTel
					});
				}
				return;
			}
			sCurAddr = sUid;
			P_INFO["all"] = fGetCookie("P_INFO");
			if(P_INFO["all"] && P_INFO["all"].length > 0){
				P_INFO["uid"] = P_INFO["all"].split("|")[0];
				P_INFO["num"] = P_INFO["all"].split("|")[6];
			}
			if(P_INFO.uid && P_INFO.uid !== sUid){
				_fGetNumFromMailInterFace(sUid);
				return;
			}
			if(P_INFO.num && P_INFO.num.indexOf("&") > -1){
				sNum = P_INFO.num.split("&")[0];
				if( sNum == '' ){			
					MobCallback({
						"nCode" : "404"
					});				
				}else{
					MobCallback({
						"nCode" : "200",
						"sNum" : sNum
					});
				}
				return;
			}
			_fGetNumFromMailInterFace(sUid);
			return;
		},
		
		//新旧手机号码拼装
		_fEncodeNum = function(sMobile){
			var sEnTel = "", sUnEnTel = sMobile;
			if(sUnEnTel.length === 6){ // 前3后3 旧
				sEnTel = sUnEnTel.substr(0, 3) + SECSTR[0] + sUnEnTel.substr(3, 3);
			}
			if(sUnEnTel.length === 8){ // 前4后4 新
				sEnTel = sUnEnTel.substr(0, 4) + SECSTR[1] + sUnEnTel.substr(4, 4);
			}
			return sEnTel;
		},
		
		//使用手机号码获取邮箱地址
		_fGetMailFromNum = function(sMobile){
			var sMobileNum = fTrim(sMobile);
			if(sMobileNum == sCurNum){
				if(sEmail == undefined){
					MobCallback({
						"nCode" : "404"
					});
				}else{
					MobCallback({
						"nCode" : "200",
						"sNum" : sEmail
					});
				}
				return;
			}
			if(fParseMNum(sMobileNum)){
				sCurNum = sMobileNum;
				_fGetMailFromNumInterFace(sMobileNum);
				return;
			}else{
				MobCallback({
					"nCode" : "private",
					"sNum" : "invalidNum"
				});		
			}
		},
		
		//使用邮箱地址获取手机号码接口调用
		_fGetNumFromMailInterFace = function(sUid){
			fGetScript(sInterFaceFromMail + sUid + "&t=" + sTimestamp);
		},
		
		//使用手机地址获取邮箱帐号接口调用
		_fGetMailFromNumInterFace = function(sNum){
			fGetScript(sInterFaceFromNum + sNum + "&t=" + sTimestamp);
		},
		
		//校验帐号
		_fCheckAddr = function(uName, sAddr){
			var rPattern1 = /^[a-zA-Z0-9_\.-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;
			if(!rPattern1.test(sAddr) || fParseMNum(uName)){
				return -1;//地址无效
			}
			if(typeof sCurAddr === "string" && sCurAddr === sAddr){
				return 0;//旧地址
			}
			return 1;
		};
		
		//手机帐号服务回调函数
		window.MobCallback = function(oMobObj){
			var sHtml,
				sMailDomain,
				oMobT = $id("mobtips"),
				oMob_txt = $id("mobtips_txt"),
				oMob_close = $id("mobtips_close");
			try{
				var oMob = oMobObj;
				if(oMob.nCode == "private"){
					gMobileNumMailResult = oMob.sNum;
					if(gMobileNumMailResult == "invalidMail" || gMobileNumMailResult == "invalidNum"){
						oMobT.style.display = 'none';
					}
				}
				if(oMob.nCode == 200){
					if(oMob.sNum && oMob.sNum.length >8){
						sEmail = oMob.sNum;
						gMobileNumMailResult = sEmail;
						sMailDomain = gMobileNumMailResult.split('@')[1];
						if(sMailDomain != gOption.sDomain){
							var sTmpMobMail = sCurNum + '@' + sMailDomain;
							oMobT.style.height = "auto";
							sHtml = '此手机号码的邮箱是<br/><em>' + sTmpMobMail + '</em>，<a style="text-decoration:none;" href="http://email.163.com/index.htm#uid=' + sTmpMobMail + '">点此登录</a>';
						}else{
							sHtml = '此号码已与帐号：<em>' + gMobileNumMailResult + '</em> 绑定';
						}
					}else{
						sTel = oMob.sNum;
						gMobileNumMailResult = _fEncodeNum(sTel);
						sHtml = '用你的手机号 <em>' + gMobileNumMailResult + '</em> 也可登录';
					}
					oMob_txt.innerHTML = sHtml;
					oMobT.style.display = 'block';
				}
				if(oMob.nCode == 404){
					if(ntabOn == 1){
						sHtml = '手机号码也可登录，<a href="http://e.mail.163.com/mobilemail/home.do?from=mail163">免费激活</a>';
						sTel = undefined;
					}else{
						sHtml = '此号码还未手机号登录';				
						sEmail = undefined;
					}
					oMob_txt.innerHTML = sHtml;
					oMobT.style.display = 'block';
				}
			}catch(e){
				oMobT.style.display = 'none';
			}
			return;
		};
		
		return {
			"init" : function(){
				return this;
			},
			"forbidden" : function(){
				gMobileNumMailIsForbidden = _fIsForbidden();
				if(gMobileNumMailIsForbidden){
					return false;
				}else{
					_fSetForbidden();
				}
			},
			"getNumFromMail" : function(sMail){
				gMobileNumMailIsForbidden = _fIsForbidden();
				if(gMobileNumMailIsForbidden){
					return false;
				}else{
					_fGetNumFromMail(sMail);
				}
			},
			"getMailFromNum" : function(sMobile){
				gMobileNumMailIsForbidden = _fIsForbidden();
				if(gMobileNumMailIsForbidden){
					return false;
				}else{
					_fGetMailFromNum(sMobile);
				}
			}
		};
})().init();

//简单http加密登录 RSA

//简单模式登录
window.bGettingAlgorithm = false
function fEnData(data){
	if(!window.RSAKey){
		if(!window.bGettingAlgorithm){
			fGetScript('http://mimg.127.net/index/lib/scripts/algorithm.js');
			window.bGettingAlgorithm = true;
		}
		setTimeout(function(){
			fEnData(data);
		}, 200);
		return;
	}
	//对返回数据进行处理
	if(data == null || data.length == 0){
		alert("参数非法");
		return false;
	}
	var parts = new Array();
	parts = data.split("\n");
	var retcode = parts[0];
	if(retcode == "401"){
		alert("参数非法");
	}else if(retcode == "500"){
		alert("服务端异常");
	}else if(retcode == "200"){
        var exponent = parts[1];
		var modulus = parts[2];
		// var uuid = parts[1];
		var password = $id("pwdInput").value;
		// var rcode = SHA1(base64encode(utf16to8(uuid)) + base64encode(utf16to8(MD5(password))) + rnd);
		var rsa = new RSAKey();
		rsa.setPublic(modulus, exponent);
		var res = rsa.encrypt(MD5(password));
		// window.location.href = "http://reg.163.com/httpLoginVerifySHA1.jsp?"
		window.location.href = "http://reg.163.com/httpLoginVerifyNew.jsp?"
		+ fUrlP('product', gOption.product, true)
		+ fUrlP('rcode', res)
		+ fUrlP('savelogin', $id("savelogin").value)
		+ fUrlP('url', encodeURIComponent(window.sHttpAction))
		+ fUrlP('url2', encodeURIComponent(gOption.url2))
		+ fUrlP('username', $id("idInput").value + "@" + gOption.sDomain)
		;
	}
}

function loginRequest(jsonp){
	var rnd = getRnd($id("idInput").value + "@" + gOption.sDomain);
	// c_url = "http://reg.163.com/services/httpLoginExchgKey?rnd="+rnd;
	// c_url += "&jsonp="+jsonp;
	c_url = 'http://reg.163.com/services/httpLoginExchgKeyNew?rnd=' + rnd;
	c_url += '&jsonp=' + jsonp;
	fGetScript(c_url);
}

function getRnd(s){
	var rnd = base64encode(utf16to8(s));
	return rnd;
}

// documentReady事件支持
var DOMContentLoaded;
var DOMREADY = function(o){
	var DOMREADY = {
		isReady		:	false,
		ready		:	o,
		bindReady	:	function(){
			try{
				if ( document.readyState === "complete" ){
					DOMREADY.isReady = true;
					return setTimeout( DOMREADY.ready, 1 );
				}
				if ( document.addEventListener ){
					document.addEventListener( "DOMContentLoaded", DOMContentLoaded, false );
				}else if( document.attachEvent ){
					document.attachEvent( "onreadystatechange", DOMContentLoaded );
					var toplevel = false;
					try {
						toplevel = window.frameElement == null;
					} catch(e) {}
					if( document.documentElement.doScroll && toplevel ){
						doScrollCheck();
					}
				}
			}catch(e){}
		}
	};
	if( document.addEventListener ){
		DOMContentLoaded = function(){
			document.removeEventListener( "DOMContentLoaded", DOMContentLoaded, false );
			DOMREADY.ready();
		};

	}else if ( document.attachEvent ){
		DOMContentLoaded = function(){
			if ( document.readyState === "complete" ) {
				document.detachEvent( "onreadystatechange", DOMContentLoaded );
				if( DOMREADY.isReady ){
					return;
				}else{
					DOMREADY.isReady = true;
					DOMREADY.ready();
				}
			}
		};
	}
	function doScrollCheck(){
		if( DOMREADY.isReady ){
			return;
		}
		try {
			document.documentElement.doScroll("left");
		}catch(e){
			setTimeout( doScrollCheck, 1);
			return;
		}
		DOMREADY.isReady = true;
		DOMREADY.ready();
	}
	DOMREADY.bindReady();
};

// 121025 URS update
// 121115 URS fixed

var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
function base64encode(str) {
	var out, i, len;
	var c1, c2, c3;
	len = str.length;
	i = 0;
	out = "";
	while (i < len) {
		c1 = str.charCodeAt(i++) & 255;
		if (i == len) {
			out += base64EncodeChars.charAt(c1 >> 2);
			out += base64EncodeChars.charAt((c1 & 3) << 4);
			out += "==";
			break;
		}
		c2 = str.charCodeAt(i++);
		if (i == len) {
			out += base64EncodeChars.charAt(c1 >> 2);
			out += base64EncodeChars.charAt(((c1 & 3) << 4) | ((c2 & 240) >> 4));
			out += base64EncodeChars.charAt((c2 & 15) << 2);
			out += "=";
			break;
		}
		c3 = str.charCodeAt(i++);
		out += base64EncodeChars.charAt(c1 >> 2);
		out += base64EncodeChars.charAt(((c1 & 3) << 4) | ((c2 & 240) >> 4));
		out += base64EncodeChars.charAt(((c2 & 15) << 2) | ((c3 & 192) >> 6));
		out += base64EncodeChars.charAt(c3 & 63);
	}
	return out;
}

function utf16to8(str) {
	var out, i, len, c;
	out = "";
	len = str.length;
	for (i = 0; i < len; i++) {
		c = str.charCodeAt(i);
		if ((c >= 1) && (c <= 127)) {
			out += str.charAt(i);
		} else {
			if (c > 2047) {
				out += String.fromCharCode(224 | ((c >> 12) & 15));
				out += String.fromCharCode(128 | ((c >> 6) & 63));
				out += String.fromCharCode(128 | ((c >> 0) & 63));
			} else {
				out += String.fromCharCode(192 | ((c >> 6) & 31));
				out += String.fromCharCode(128 | ((c >> 0) & 63));
			}
		}
	}
	return out;
}