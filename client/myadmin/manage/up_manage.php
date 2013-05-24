<?php

include("../check.php");
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/admin.class.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/fun.js"></script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
 
 


</style>
</head>

<body>
<?
 $ad=new AdminDal();
 $model=new AdminInfo();

$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'add';
if($a=="addsave" || $a=="modifysave")
{

	$username=isset($_REQUEST['username']) ? $_REQUEST['username']:"";
	
	if(trim($_POST['password'])=="")
	{
		$p=trim($_POST['password2']);
	}
	else
	{
		$p=md5(trim($_POST['password']));
	}
	$realname=trim($_POST['realname']);
	$statusid= isset($_REQUEST['statusid']) ? $_REQUEST['statusid'] : '';
 
  
	$model->UserName=$username;
	$model->PassWord=$p;
	$model->StatusId=$statusid;
	$model->RealName=$realname;
	 
 }
///添加保存
if($a=="addsave")
{	
	 if($ad->CheckUser($username)>0)
	{
		echo Msg("账号已存在","back");
		exit;
	}
	if($ad->AddADMIN($model)>0)
	{
		echo Msg("操作成功","admin_manage.php?action=list");
	}

}

if ($a=="modifysave")
{
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	$model->UserId=$id;
	if($id>0)
	{
			 
		if($ad->Edit($model)>0)
		{
			 echo Msg("操作成功","admin_manage.php?action=list");
		}
		else
		{
			 echo Msg("操作失败","back");
		}
	}
}
?>
<script language="javascript">
	function ck(f)
	{
		if(f.username.value=="" || f.username.value.length<4)
		{
			alert("用户名长度过小");
			f.username.focus();
			return false;
		}
		
		if(f.password.value=="" || f.password.value.length<5)
		{
			alert("密码长度过小");
			f.password.focus();
			return false;
		}
		return true;
	}
</script>

<script language="javascript">
	function ck2(f)
	{
		if(f.password.value!="")
		{
			if(f.password.value.length<5)
			{
				alert("密码长度过小");
				f.password.focus();
				return false;
			}
		}
		return true;
	}
</script>
<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">管理员-添加</span></td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
<?php
 
if($a=="add")
{
?>
  <form id="form1" name="form1" method="post" action="?action=addsave" onsubmit="return ck(this);">
    <table width="100%" height="173" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="7%" height="28" bgcolor="#FFFFFF"><div align="center">账号</div></td>
        <td width="93%" height="28" bgcolor="#FFFFFF"><input name="username" type="text" id="username" size="50" maxlength="20" />
        &nbsp; (4-20位长度) </td>
      </tr>
      
      <tr>
        <td height="28" bgcolor="#FFFFFF"><div align="center">密码</div></td>
        <td height="28" bgcolor="#FFFFFF"><input name="password" type="text" id="password" size="50" maxlength="20" />
(5-20位长度) </td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF"><div align="center">姓名</div></td>
        <td height="28" bgcolor="#FFFFFF"><input name="realname" type="text" id="realname" size="50" maxlength="200" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF"><div align="center">状态</div></td>
        <td height="28" bgcolor="#FFFFFF"><input name="statusid" type="radio" value="1" checked="checked" />
          启用&nbsp; <input type="radio" name="statusid" value="0" />
          锁定
          &nbsp; </td>
      </tr>
      
      <tr>
        <td height="39" bgcolor="#FFFFFF"><div align="center"></div></td>
        <td height="39" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存信息" />
        <input type="reset" name="Submit2" value="取消" class="bt" /></td>
      </tr>
    </table>
  </form>
<?
}
?>  
  
  
<?
	if ($a=="modify")
	{
		$id=isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	 	$rs=$ad->GetUserNameByUserId($id);

?>  
    <form id="form1" name="form1" method="post" action="?action=modifysave" onsubmit="return ck2(this);">
    <table width="100%" height="170" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="7%" height="28" bgcolor="#FFFFFF"><div align="center">账号</div></td>
        <td width="93%" height="28" bgcolor="#FFFFFF"><input name="title" type="text" id="title" readonly="true"  value="<?=$rs[0]['username']?>" size="50" maxlength="20" />
		<input name="id" type="text" id="id"  value="<?=$rs[0]['userid']?>"  style="display:none" />
         &nbsp;  (4-20位长度) </td>
      </tr>
      
      <tr>
        <td height="28" bgcolor="#FFFFFF"><div align="center">密码</div></td>
        <td height="28" bgcolor="#FFFFFF"><input name="password" type="text" id="password" size="50" maxlength="20" />
          &nbsp;  (5-20位长度)&nbsp; *密码为空则不修改 
          <input name="password2" type="text" id="password2" style="display:none" value="<?=$rs[0]["password"]?>" size="50" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF"><div align="center">姓名</div></td>
        <td height="28" bgcolor="#FFFFFF">
		<div id=box><div id=box2>
		  <input name="realname" type="text" id="realname" size="50" value="<?=$rs[0]["realname"]?>" maxlength="20" />
		</div></div>		</td>
      </tr>
      
      <tr>
        <td height="33" bgcolor="#FFFFFF"><div align="center">状态</div></td>
        <td height="33" bgcolor="#FFFFFF"><input name="statusid" type="radio" value="1"  <? if($rs[0]["statusid"]==1) { ?>checked="checked" <? } ?> />
启用&nbsp;
			<input type="radio" name="statusid" value="0" <? if($rs[0]["statusid"]=="0") { ?>checked="checked" <? } ?> />
锁定
        &nbsp; </td>
      </tr>
      
      <tr>
        <td height="39" bgcolor="#FFFFFF"><div align="center"></div></td>
        <td height="39" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存信息" />
        <input type="reset" name="Submit2" value="取消" class="bt" /></td>
      </tr>
    </table>
  </form>

<?
}
?>

</div>



</body>
</html>
