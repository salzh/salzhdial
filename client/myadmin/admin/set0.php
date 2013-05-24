<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/admin.class.php");
include("../../class/public.class2.php");
include("../../class/user.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/fun.js"></script>
<script type="text/javascript" src="../javascript/jquery-1.4.1.min.js" charset="utf-8"></script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
	</style>
	<script type="text/javascript">
		var $j=jQuery.noConflict();
		var $Id=function(id){return document.getElementById(id);}
		function selAll(id,cn,type){
			var obj=$j("#"+id).find("."+cn);
			for(var i=0;i<obj.length;i++){
				 if(type==1){
					 obj[i].checked=true;
				 }else{
					 obj[i].checked=obj[i].checked==true?false:true;
				 } 
			}
		}
	</script>
</head>

<body>
<?
$ac=$_REQUEST['action'];
$uid=$_REQUEST['id'];
$ud=new UserDal();
$ad=new AdminDal();
$ainfo=new AdminInfo();
if($ac=="setsave"){
	$ainfo->UserId=$uid;
	if(trim($_REQUEST['PassWord'])==""){
		$ainfo->PassWord=$_REQUEST['PassWord2'];
	}else{
		$ainfo->PassWord=md5(trim($_REQUEST['PassWord']));
	}
	$ainfo->RealName=$_REQUEST['RealName'];
	$ainfo->StatusId=$_REQUEST['StatusId'];
	$ainfo->OperatorId=$_REQUEST['OperatorId'];
	
	$au= isset($_REQUEST['authority']) ? $_REQUEST['authority'] : 'no';
	$po= isset($_REQUEST['product']) ? $_REQUEST['product'] : 'no';

    $ainfo->authority=$_REQUEST['Region'];
    $ainfo->product=$_REQUEST['Channel'];
	$rtn=$ad->Edit($ainfo);
	if ($rtn==0){
		echo Msg("修改失败","back");
	}
	else{
		echo Msg("操作成功","list.php?action=list");
	} 
	return ;
}
$ainfo=$ad->GetModelByUserId($uid);
if($ainfo==NULL){
echo Msg('数据不存在','back');
return;}
?>
<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">管理员管理-修改</span></td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
  <form id="form1" name="form1" method="post" action="?action=setsave">
    <table width="100%" height="329" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="13%" height="40" align="center" bgcolor="#FFFFFF">编号</td>
        <td width="87%" height="40" bgcolor="#FFFFFF"><?=$ainfo->UserId?>
        <input type="hidden" name="id" value="<?=$ainfo->UserId?>" /></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF" align="center">登陆名称</td>
        <td height="40" bgcolor="#FFFFFF"><?=$ainfo->UserName?></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF" align="center">登陆密码</td>
        <td height="40" bgcolor="#FFFFFF"><input type="password" name="PassWord" id="PassWord" value="" maxlength="50" />&nbsp;留空则不修改密码<input type="hidden" id="PassWordw2" name="PassWord2" value="<?=$ainfo->PassWord?>" /></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF" align="center">姓名</td>
        <td height="40" bgcolor="#FFFFFF"><input type="text" name="RealName" id="RealName" value="<?=$ainfo->RealName?>" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF" align="center">账户状态</td>
        <td height="40" bgcolor="#FFFFFF">
        	<input type="radio" name="StatusId" value="1" id="StatusId1" <?=$ainfo->StatusId==1?"checked='checked'":""?>/><label for="StatusId1">正常</label>
        	<input type="radio" name="StatusId" value="0" id="StatusId2" <?=$ainfo->StatusId==0?"checked='checked'":""?>/><label for="StatusId2">锁定</label></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF" align="center"><div align="center">Region</div></td>
        <td height="40" bgcolor="#FFFFFF"><?=$ud->GetAreaSelect($ainfo->authority)?></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF" align="center"><div align="center">Channel</div></td>
        <td height="40" bgcolor="#FFFFFF"><?=$ud->GetDep($ainfo->product)?></td>
      </tr>      
      <tr>
        <td height="40" bgcolor="#FFFFFF" align="center">账户权限</td>
        <td height="40" bgcolor="#FFFFFF">
        	<input type="radio" name="OperatorId" value="0" id="OperatorId1" <?=$ainfo->OperatorId==0?"checked='checked'":""?>/><label for="OperatorId1">系统管理员</label>
        	<input type="radio" name="OperatorId" value="1" id="OperatorId2" <?=$ainfo->OperatorId==1?"checked='checked'":""?>/><label for="OperatorId2">信息管理员</label></td>
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