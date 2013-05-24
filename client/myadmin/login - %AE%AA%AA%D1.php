<?
session_start();
	include("../include/mysql.class.php");
	include("../include/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$configs['web_name']?>_后台管理系</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="javascript/jquery-1.4.1.min.js"></script>

<script language="javascript">

function checkuser()
{
	if($("#p_username").val()=="")
	{
			$("#tishi").show().html("<h5>账号不能为空</h5>");
			setTimeout('$("#tishi").html("")',2000);  
			return false;
	}
	
	if($("#p_password").val()=="")
	{
			$("#tishi").show().html("<h5>密码不能为空</h5>");
			setTimeout('$("#tishi").html("")',2000);  
			return false;
	}
	
	if($("#p_safecode").val()=="")
	{		$("#tishi").show().html("<h5>安全码不正确</h5>");
			 setTimeout('$("#tishi").html("")',2000);  
			return false;
	}
	return true;
}
$(function(){

	$("#btn").click(function()
	{
		if($("#p_username").val()=="" || +$("#p_password").val() =="" ||  $("#p_safecode").val()=="")
		{
			$("#tishi").show().html("<h5>账号密码不能为空</h5>");
			setTimeout('$("#tishi").html("")',2000);  
		return;
		}
		else
		{
			 $.ajax({
					 url:'checkuser.php',
					 data:'uid='+$("#p_username").val()+'&pwd='+$("#p_password").val()+'&ma='+$("#p_safecode").val(),
					 type:'POST',
					 error:function()
					 {
						$("#tishi").show().html("<h5>加载出错</h5>");
							setTimeout('$("#tishi").html("")',2000);  
					 },
					 success:function(msg)
					 { 
						if(msg=="1")
						{
						  $("#tishi").show().html("加载中");
							setTimeout('$("#tishi").hide(1000)',2000); 
							//location.href='default.php';
							alert(msg);
						}
						else if(msg=="-1")
						  {
							$("#tishi").show().html("<h5>安全码不正确</h5>");
							setTimeout('$("#tishi").html("")',2000);  
						  }
						else
						{
							$("#tishi").show().html("<h5>账号密码不正确</h5>");
							setTimeout('$("#tishi").html("")',2000);  	
						}
					 }
			  });
		  }
	});
 

});
</script>


 <style>
 	body{background-image:url(images/login.jpg);}
 </style>

</head>

<body>
 
		
		
<div class="login">
<form id="login" name="login" method="post" action="checkuser.php" onsubmit="return checkuser(this)" >
<br />

		<div class="login_logo">&nbsp;&nbsp; 
		  <div align="center"><?=$configs['web_name']?>_后台管理系统 </div>
		</div>
		<br> 
		<div id="tishi"></div>
	
		<div class="input">
			<h6>账号：</h6>
			<span><input name="p_username" type="text" id="p_username" size="20" maxlength="25" />
			</span>	
		</div>
		<p style="clear:both"></p>
		 <div class="inputerr" id="usernameinfo"></div>
		<p style="clear:both"></p>
		<div class="input">
		<h6>密码：</h6>
		<span><input name="p_password" type="password" id="p_password" size="20" maxlength="25" />
		</span>
		</div>
		<p style="clear:both"></p>

		 <div class="inputerr" id="passwordinfo"></div>
		
		
		<div class="input">
		<h6>安全码：</h6>
		 <span><input name="p_safecode" type="password" id="p_safecode" size="20" maxlength="20" />
		 </span>
		</div>
		<p style="clear:both"></p>
		 <div class="inputerr" id="safecodeinfo"></div>
		
		<div class="input"  >
	 		<span style="text-align:right;  width:370px; margin-bottom:10px;">
		 			<input type="image"      style="width:130px;cursor:pointer;margin-right:20px; height:40px; border:0px;" src="images/LoginBtn.jpg"   />
		 </span>
		</div>
		
		<div class="safe" style="margin-top:20px;clear:both; background:#CCCCCC; ">
		
		<div style="width:120px; text-align:center;  float:left;"><img src="images/safe.jpg" width="77" height="58" /></div>
		<div style="line-height:20px; padding-top:5px;   font-size:12px;">
			尽量不要在公共场所的（如网吧等)登录您的系统
			<br />
			尽量避免多人使用同一账号<br />
			不定期的修改您的密码，按退出键正常退出
		</div>
	  </div>
  </form>
  
</div> 

<div style="text-align:center; margin-top:5px; color:#fff;">版权所有 &nbsp;<?=$configs['web_name']?></div> 

</body>
</html>
