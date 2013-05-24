
///-------------------------------图片等比列显示--------------------------
var flags=false; 
function Simg(ImgD,iWidth,iHeight){ 
 var image=new Image(); 
 image.src=ImgD.src; 
 if(image.width>0 && image.height>0){ 
  flags=true; 
  if(image.width/image.height>= iWidth/iHeight){ 
   if(image.width>iWidth){
    ImgD.width=iWidth; 
    ImgD.height=(image.height*iWidth)/image.width; 
   }else{ 
    ImgD.width=image.width;
    ImgD.height=image.height; 
   } 
   ImgD.alt=image.width+"x"+image.height; 
  } 
  else{ 
   if(image.height>iHeight){
    ImgD.height=iHeight; 
    ImgD.width=(image.width*iHeight)/image.height; 
   }else{ 
    ImgD.width=image.width;
    ImgD.height=image.height; 
   } 
   ImgD.alt=image.width+"x"+image.height; 
  } 
 }
}
///-------------------------------------------------------------------



//--------------------------FLASH js 显示--------------------------------

function ShowFlash(FlashSrc,FlashID,FlashWidth,FlashHeight){
	if(gFV()>=6){
		document.write("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='"+FlashWidth+"' height='"+FlashHeight+"'><param name='movie' value='"+FlashSrc+"'><param name='allowScriptAccess' value='sameDomain'><param name='quality' value='high'><param name='wmode' value='transparent'><param name='menu' value='0'><embed src='"+FlashSrc+"' quality='high' name='"+FlashID+"' bgcolor='#ffffff' align='middle' allowScriptAccess='sameDomain' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' wmode='transparent'></object>");
		}else{
			document.write("<FONT color='#FFFFFF'>This content requires Macromedia Flash Player. </FONT><A href='http://www.adobe.com/go/gntray_dl_getflashplayer' class='TopLink' target='_blank'>Get Flash Player</A>")
		}
	}
var fV=0;
var ver=-1;
function gFV(){  //检测是否支持Flash
	var lfv=12;
	var ag=navigator.userAgent.toLowerCase();
	if(ag.indexOf("mozilla/3")!=-1&&ag.indexOf("msie")==-1){
		fV=0;
	}
	if(ver!=-1)return ver;
	if(navigator.plugins!=null&&navigator.plugins.length>0){
		var flashPlugin=navigator.plugins['Shockwave Flash'];
		if(typeof flashPlugin=='object'){
			for(var i=lfv;i>=3;i--){
				if(flashPlugin.description.indexOf(i+'.')!=-1){
					fV=i;
					break;
				}
			}
		}
	}else if(ag.indexOf("msie")!=-1&&parseInt(navigator.appVersion)>=4&&ag.indexOf("win")!=-1&&ag.indexOf("16bit")==-1){
		var doc='<scr'+'ipt language="VBScript"\> \r';
		doc+='On Error Resume Next \r';
		doc+='Dim obFlash \r';
		doc+='For i='+lfv+' To 3 Step -1 \r';
		doc+='   Set obFlash=CreateObject("ShockwaveFlash.ShockwaveFlash." & i) \r';
		doc+='   If IsObject(obFlash) Then \r';
		doc+='      fV=i \r';
		doc+='      Exit For \r';
		doc+='   End If \r';
		doc+='Next \r';
		doc+='</scr'+'ipt\>';
		document.write(doc);
	}else{
		fV=0
	};
	ver=fV;
	return fV;
}
//--------------------------FLASH js 显示--------------------------------


////验证/////////////////

 ////去除空格
function trim(formname) 
{ 
   var tmpchar, i, j, result;
   i = 0;
   tmpchar = formname.charAt(i);
   while (tmpchar == ' ') {
      i ++;
      tmpchar = formname.charAt(i);
   }

   j = formname.length - 1;
   tmpchar = formname.charAt(j);
   while (tmpchar == ' ') {
      j --;
      tmpchar = formname.charAt (j);
   }
   if ( i <= j)
      result = formname.substring(i,j+1);
   else
      result = "";
   return result;
}

//form是否为空的检验
function free(fieldname,formname)
{

 if (formname.value =="")
    {
	  formname.value=trim(formname.value)
	  alert("请输入 "+fieldname+"!");
	  formname.focus();
	  return false;
	}
 else
    return true;
}


//是否为空的检验 单选 ，多选 
function isnull(fieldname,formname)
{
 var obj=document.getElementsByName(formname);
 	var b =false;
    for(var i=0;i< obj.length;i++){
    if(obj[i].checked){
        b = true;
    }
    }
    if(b==false){
        alert("请选择"+fieldname );//提示信息自己修改
 
    }
    return b;
 
}


//检验email地址是否合法
function ismail(string) { 
	var ok=false;
	for (var i=1; i<string.value.length-3;i++) {
		if (string.value.charAt(i) =='@') 
			ok = true;
	}
	for (var j=0; j<string.value.length; j++) {
		if ((((string.value.charAt(j)>='0')&&(string.value.charAt(j)<='9'))||((string.value.charAt(j)>='A')&&(string.value.charAt(j)<='Z'))||((string.value.charAt(j)>='a')&&(string.value.charAt(j)<='z'))||(string.value.charAt(j)=='_')||(string.value.charAt(j)=='@')||(string.value.charAt(j)=='.'))) {
		} else {
			ok = false;
			
		}
	}
	if (ok) {
		return true;
	} else {
		alert("非法电子邮件地址!");
	   string.focus();
   	return false;
	}
}

 //密码检验函数
function password(str1,str2)
{
 if (str1.value == str2.value)//验证两次密码是否一致
 {
  return true;
 } else {
    alert("密码不一致！请重新输入！");
	str1.focus();
	str1.value=''
	str2.value=''
	return false;}
}

//长度栓验
function srtingLen(fieldname,string)
{
	if (string.value.length<5) 
 	{
		alert(fieldname+"必须大于等于5个字节!");
		string.focus();
		return false;
	}
	return true
}

function itsALetter(character)
{
	var rightChar="ABCDEFGHIJKLNMOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890_"
	if (rightChar.indexOf(character)==-1){
		return false
	}
	return true
}

 //内容中不能包括的字符
function validstring(fieldname,string) { 
	for (i=0; i<string.value.length; i++) {
		if(!itsALetter(string.value.charAt(i))){
			alert(fieldname+"的内容不合法！\n"+fieldname+"中只能输入字母，数字或者下划线！");
			string.focus();
			return false;
		}
	}
	return true;
}

//检验是否为数字 
function isnum(fieldname,str)
{ 
   for (var k=0 ; k < str.value.length; k++)
   {
      if ((str.value.charAt(k)>='0')&&(str.value.charAt(k)<='9')){
	  }
	  else {
	    alert("请在"+fieldname+"中输入数字！");
		str.focus();
		return false;
		}
	}
	return true;
}

//是否是时间
function isdatetime(str) 
{ 
	var result=str.match(/^(\d{4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/); 
	if(result==null) return false; 
	var d= new Date(result[1], result[3]-1, result[4], result[5], result[6], result[7]); 
	return (d.getFullYear()==result[1]&&(d.getMonth()+1)==result[3]&&d.getDate()==result[4]&&d.getHours()==result[5]&&d.getMinutes()==result[6]&&d.getSeconds()==result[7]); 
} 

///是否是日期
function isdate(str){ 
	var result=str.match(/^(\d{4})(-|\/)(\d{1,2})\2(\d{1,2})$/); 
	if(result==null) return false; 
	var d=new Date(result[1], result[3]-1, result[4]); 
	return (d.getFullYear()==result[1] && d.getMonth()+1==result[3] && d.getDate()==result[4]); 
} 

///是否是email
function isemail(str,string) 
{ 
	var result=str.match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/); 
	if(result==null){
		return false;
		alert("非法电子邮件地址!");
		string.focus();
} 
return true; 
} 

///删除提示
function Del(str)
{
 	 if (!confirm('确定删除吗?\n\n删除后可能会导致前台数据不能正确显示\n\n该大类下的小类也会被删除')) 
	 {
	 	return;
	 }
	 else
	 {
	 	location.href=str;
	 }
}



