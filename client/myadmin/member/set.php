<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/user.php");
include("../check.php");
$ac=$_REQUEST['action'];
$uid=$_REQUEST['id']; 
$ud=new UserDal();
$uinfo=new UserInfo();
if($ac=="setsave"){
	$uinfo->UserId=$uid;
	 
	$uinfo->Email=$_REQUEST['Email'];
		$uinfo->Name=$_REQUEST['Name'];
		$uinfo->Position=$_REQUEST['Position'];
		$uinfo->Group=$_REQUEST['Group'];
		$uinfo->QOC_Role=$_REQUEST['QOC_Role'];
		$uinfo->Region=$_REQUEST['Region'];
		$uinfo->Channel=$_REQUEST['Channel'];
	$rtn=$ud->EditUserInfo($uinfo);
 	echo $rtn==0?"修改失败!":"修改成功!"."<a href='set.php?id=".$uid."&action=page'>返回</a>";

	return ;
}
$uinfo=$ud->GetModel($uid);
if($uinfo==NULL){ echo Msg("用户不存在","list.php?action=list");
;return;}
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
</head>

<body>
<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">用户管理-修改</span></td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
  <form id="form1" name="form1" method="post" action="?action=setsave">
    <table width="100%" height="280" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td height="25" bgcolor="#FFFFFF" align="center">编号</td>
        <td height="25" bgcolor="#FFFFFF"><?=$uinfo->UserId?>
        <input type="hidden" name="id" value="<?=$uinfo->UserId?>" /></td>
      </tr>
      <tr>
        <td height="24" bgcolor="#FFFFFF" align="center">Email</td>
        <td height="24" bgcolor="#FFFFFF"><?=$uinfo->Email?>
        <input name="Email" type="hidden" id="Email" value="<?=$uinfo->Email?>" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">Name</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Name" id="Name" value="<?=$uinfo->Name?>" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">Position</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Position" id="Position" value="<?=$uinfo->Position?>" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">Group</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Group" id="Group" value="<?=$uinfo->Group?>" maxlength="60" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">QOC_Role</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="QOC_Role" id="QOC_Role" value="<?=$uinfo->QOC_Role?>" maxlength="60" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF"><div align="center">Region</div></td>
        <td height="28" bgcolor="#FFFFFF"><?=$ud->GetAreaSelect($uinfo->Region)?></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">Channel</div></td>
        <td height="40" bgcolor="#FFFFFF"><?=$ud->GetDep($uinfo->Channel)?> </td>
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