<?
include("../include/mysql.class.php");
include("../include/config.php");
include("../class/admin.class.php");
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>控制面版</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" height="62" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td width="29%" valign="middle" background="images/topbg.jpg" style="font-size:18px">&nbsp; <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <?=$configs['web_name']?> 后台管理系统</strong></td>
    <td width="43%" valign="middle" background="images/topbg.jpg" align="right">
	<style>
a.select{color:#F00};/*选中的样式*/
</style>
您好:
	  <?
      if(isset($_COOKIE['username']))
	 {
	 	echo $_COOKIE['username'];
		$u= new AdminDal();
		$m=$u->GetModelByUserId($_SESSION["userid"]);	
		echo "，您的账户余额为".round($m->voiceMoney,2)."元";     
	 }?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 
	</td>
    <td width="28%" height="68" valign="middle" background="images/topbg.jpg" style="text-align:right;">
		<span class="bt"><a href="main.php" target="right">返回桌面</a></span>
		<span class="bt"><a href="setadmin.php?action=page" target="right">账号管理</a></span><span class="bt"><a href="loginout.php" target="right">退出登录</a></span>	</td>
  </tr>
</table>
</body>
</html>
