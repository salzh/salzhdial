<?php
include("../include/mysql.class.php");
include("../include/config.php");
include("../class/admin.class.php");
include("check.php");
$ac=$_REQUEST['action'];
$ad=new AdminDal();
$ainfo=new AdminInfo();
if($ac=="setsave"){
	$uid=$_REQUEST['id'];
	$ainfo->id=$uid;
	if(trim($_REQUEST['PassWord'])==""){
		$ainfo->password=$_REQUEST['PassWord2'];
	}else{
		$ainfo->password=md5(trim($_REQUEST['PassWord']));
	}
	$ainfo->realname=$_REQUEST['RealName'];
	$ainfo->StatusId=$_REQUEST['StatusId'];
	$rtn=$ad->Edit($ainfo);
	header('Content-Type:text/html;charset=utf-8');
	
	if($rtn==0){
	 echo Msg("操作失败","back");
	}else
	{
	 echo Msg("操作成功","setadmin.php?action=page");
	}
	return ;
}
$ainfo=$ad->GetModelByUserId($_SESSION['userid']);
if($ainfo==NULL){ echo Msg("信息未找到","back");return;}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员管理</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="javascript/fun.js"></script>
<script type="text/javascript" src="js/jquery-1.4.2.min.js" charset="utf-8"></script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
	</style>
</head>

<body>
<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">管理员管理-修改</span></td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
  <form id="form1" name="form1" method="post" action="?action=setsave">
    <table width="100%" height="184" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="11%" height="32" align="center" bgcolor="#FFFFFF">编号</td>
        <td width="89%" height="32" bgcolor="#FFFFFF"><?=$ainfo->id?>
        <input type="hidden" name="id" value="<?=$ainfo->id?>" /></td>
      </tr>
      <tr>
        <td height="24" bgcolor="#FFFFFF" align="center">登陆名称</td>
        <td height="24" bgcolor="#FFFFFF"><?=$ainfo->username?></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">登陆密码</td>
        <td height="28" bgcolor="#FFFFFF"><input type="password" name="PassWord" id="PassWord" value="" maxlength="50" />&nbsp;留空则不修改密码<input type="hidden" id="PassWordw2" name="PassWord2" value="<?=$ainfo->password?>" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">姓名</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="RealName" id="RealName" value="<?=$ainfo->realname?>" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="25" bgcolor="#FFFFFF" align="center">账户状态</td>
        <td height="25" bgcolor="#FFFFFF"><?=$ainfo->StatusId==1?"正常":"锁定"?>
        <input type="hidden" value="<?=$ainfo->StatusId?>" name="StatusId" /></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center"></div></td>
        <td height="40" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存信息" />
        <input type="reset" name="Submit2" value="取消" class="bt" /></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>