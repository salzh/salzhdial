<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/user.php");
include("../check.php");

$ac=$_REQUEST['action'];
$ud= new UserDal();
if($ac=="addsave"){
	$email=$_REQUEST['Email'];
	$name= $_REQUEST['Name'];
	//if(trim($email)=='' || trim($pwd)==''){	echo Msg("该登陆名称已被使用","back"); return ;} 
	
	
	$b=$ud->CheckUser($email);
	if($b==1){
		echo Msg("该登陆名称已被使用","list.php?action=list"); 
		return;
	}else{
		$uinfo=new UserInfo();
		$uinfo->Email=$email;
		$uinfo->Name=trim($name);
		$uinfo->Position=$_REQUEST['Position'];
		$uinfo->Group=$_REQUEST['Group'];
		$uinfo->QOC_Role=$_REQUEST['QOC_Role'];
		$uinfo->Region=$_REQUEST['Region'];
		$uinfo->Channel=$_REQUEST['Channel'];
		$rtn=$ud->RegUser($uinfo);
		 echo $rtn==0? Msg("操作失败","back"):Msg("操作成功","list.php?action=list");
		return;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/fun.js"></script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
	</style>
<script type="text/javascript" src="/js/jquery-1.4a2.min.js" charset="utf-8"></script>
<script type="text/javascript">
	var $j=jQuery.noConflict();
	var $Id=function(id){return document.getElementById(id);}
	String.prototype.Trim = function(){return this.replace(/(^\s*)|(\s*$)/g, "");}    
	function check(){
		var uname=$Id("Email").value;
		var upwd=$Id("Name").value;
		if(uname.Trim()==""){alert("请输入登陆名称");$Id("Email").focus();return false;}
		if(upwd.Trim()==""){alert("请输入登录姓名");$Id("Name").focus();return false;}
	}
	</script>
</head>

<body>
<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">用户管理-添加</span></td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
  <form id="form1" name="form1" method="post" action="?action=addsave" onsubmit="return check();">
    <table width="100%" height="279" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td height="34" bgcolor="#FFFFFF" align="center">Email</td>
        <td height="34" bgcolor="#FFFFFF"><input type="text" name="Email" id="Email" maxlength="50" />必填项</td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">Name</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Name" id="Name" value="" maxlength="50" />必填项</td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">Position</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Position" id="Position" value="" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">Group</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Group" id="Group" value="" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">QOC_Role</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="QOC_Role" id="QOC_Role" value="" maxlength="60" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">Region</td>
        <td height="28" bgcolor="#FFFFFF"><?=$ud->GetAreaSelect()?></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF"><div align="center">Channel</div></td>
        <td height="28" bgcolor="#FFFFFF"><?=$ud->GetDep()?></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center"></div></td>
        <td height="40" bgcolor="#FFFFFF"><input type="submit" name="Submit" id="sub_btn" class="bt" value="添加用户" />
        <input type="reset" name="Submit2" value="取消" class="bt" /></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>