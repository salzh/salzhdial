/***************************/
//@Author: Adrian "yEnS" Mato Gondelle & Ivan Guardado Castro
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

$(document).ready(function(){
	//global vars
	var form = $("#login");
	
	var username = $("#p_username");
	var pusernameInfo = $("#usernameinfo");
 
	var password = $("#p_password");
	var ppasswordinfo = $("#passwordinfo");
	
	var safecode = $("#p_safecode");
	var psafecodeinfo = $("#safecodeinfo");
	 
 
 
	form.submit(function(){
	 	var obj1=0;obj2=0;obj3=0;
		if(!chusername())
		{
			obj1= 0;
		}
		else
		{
			obj1=1;
		}
		
		if(!chpassword())
		{
			obj= 0;
		}
		if(!chsafecode())
		{
			obj= 0;
		}
		else
		return obj;
	 
	});
	
	 
	
	
	function chusername(){
	
		if(username.val().length < 2){
			username.addClass("error");
			pusernameInfo.text("账号不正确");
			pusernameInfo.addClass("error");
			return false;
		}
		else{
			username.removeClass("error");
			pusernameinfo.removeClass("error");
			return true;
		}
	}
	
	function chpassword(){
		//if it's NOT valid
		if(password.val().length < 2){
			password.addClass("error");
			ppasswordinfo.text("密码不正确");
			ppasswordinfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			password.removeClass("error");
			ppasswordinfo.removeClass("error");
			return true;
		}
	}
	
	function chsafecode(){
		//if it's NOT valid
		if(safecode.val().length < 4){
			safecode.addClass("error");
			psafecodeinfo.text("安全码不正确");
			psafecodeinfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			safecode.removeClass("error");
			psafecodeinfo.removeClass("error");
			return true;
		}
	}
	 
 
	
 
	 
});