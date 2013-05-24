<?php
session_start();
header('Content-Type:text/html;charset=utf-8');
include("../include/mysql.class.php");
include("../include/config.php");
include("../class/admin.class.php");
$ma= isset($_REQUEST['p_safecode'])? $_REQUEST['p_safecode'] : '';
if($ma!=$ma) //$SafeCode)
{
		echo "<script language='javascript'>alert('安全码不正确');location.href='login.php';</script>";
}
else
{
 	$u= new AdminDal();
	$uid= isset($_REQUEST['uname'])? $_REQUEST['uname'] : '';
	$uid=trim($uid);
	$pwd= isset($_REQUEST['pwd'])? $_REQUEST['pwd'] : '';

	$yzm= isset($_REQUEST['yzm'])? $_REQUEST['yzm'] : '';
	if($yzm=='')
	{
		echo "<script language='javascript'>alert('请输入验证码');location.href='login.php';</script>";
		exit(0);
	}

	if("".$yzm!="".$_SESSION["sessionRound"])
	{
		echo "<script language='javascript'>alert('验证码不正确');location.href='login.php';</script>";
		exit(0);
	}

	$pwd=md5(trim($pwd));
	 
	$umodel=new AdminInfo();
	$umodel->UserName=$uid;
	$umodel->PassWord=$pwd;
	
	if($u->Login($uid,$pwd)>0)
	{		$m=$u->GetModelByUserName($uid);
			$_SESSION["username"]=$uid;
			$_SESSION["userid"]=$m->id;
			$_SESSION["upuserid"]=$m->upId;
			if($m->StatusId=='0')
			{
				echo "<script language='javascript'>alert('您的账号已被系统锁定，请联系系统管理员。');location.href='login.php';</script>";
				return;
			}
			else if($m->StatusId=='-1')
			{
				echo "<script language='javascript'>alert('您的账号已被上级组织删除，请联系系统管理员。');location.href='login.php';</script>";
				return;
			}

			setcookie("rname", $m->realname,time()+3600*24,"/");
			setcookie("username", $uid,time()+3600*24,"/");
			setcookie("password", $pwd,time()+3600*24,"/");
			header("location:default.php");
	}
	else
	{
		echo "<script language='javascript'>alert('账号密码不正确');location.href='login.php';</script>";
	}
}
 
 
?>