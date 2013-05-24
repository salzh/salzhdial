<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/admin.class.php");
include("../../class/fun.class.php");
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
<script type="text/javascript" src="../javascript/jquery-1.4.1.min.js" charset="utf-8"></script>
<script type="text/javascript">
	var $j=jQuery.noConflict();
	var $Id=function(id){return document.getElementById(id);}
	String.prototype.Trim = function(){return this.replace(/(^\s*)|(\s*$)/g, "");}    
	function check(){
		var uname=$Id("UserName").value;
		var upwd=$Id("PassWord").value;
		var feerate=$Id("FeeRate").value;
		if(uname.Trim()==""){alert("请输入登陆名称");$Id("UserName").focus();return false;}
		if(upwd.Trim()==""){alert("请输入登录密码");$Id("PassWord").focus();return false;}
		//if(feerate.Trim()=="0"){alert("请选择费率");$Id("FeeRate").focus();return false;}
	}
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

$fun=new FunDal();
$ac=$_REQUEST['action'];
$id= isset($_REQUEST['id'])? $_REQUEST['id'] : '0';

$username="";
$password="";
$realname="";
$fax="";
$mobi="";
$tel="";
$email="";
$byname="";
$sendLevel="";
$sendSound="";
$ifDelInfo="";
$StatusId="";
$FeeRate="";

if($id!="0")
{
	$ad= new AdminDal();
	$thisModel=$ad->GetUserNameByUserId($id);

	$username=$thisModel['username'];
	$password="999999";
	$realname=$thisModel['realname'];
	$fax=$thisModel['fax'];
	$mobi=$thisModel['mobi'];
	$tel=$thisModel['tel'];
	$email=$thisModel['email'];
	$byname=$thisModel['byname'];
	$sendLevel=$thisModel['sendLevel'];
	$sendSound=$thisModel['sendSound'];
	$ifDelInfo=$thisModel['ifDelInfo'];
	$StatusId=$thisModel['StatusId'];	
	$FeeRate=$thisModel['FeeRate'];	
}

if($ac=="addsave"){
	$username=$_REQUEST['UserName'];
	$pwd= $_REQUEST['PassWord'];

	if(trim($pwd)==''){
		echo Msg("操作失败。","back");
	
	return ;} 
	
	$ad= new AdminDal();
	$ainfo=new AdminInfo();
	$ainfo->realname=$_REQUEST['realname'];
	$ainfo->fax=$_REQUEST['fax'];
	$ainfo->mobi=$_REQUEST['mobi'];
	$ainfo->tel=$_REQUEST['tel'];
	$ainfo->email=$_REQUEST['email'];
	$ainfo->byname=$_REQUEST['byname'];
	$ainfo->sendLevel=$_REQUEST['sendLevel'];
	$ainfo->sendSound=$_REQUEST['sendSound'];
	$ainfo->ifDelInfo=$_REQUEST['ifDelInfo'];
	$ainfo->FeeRate=$_REQUEST['FeeRate'];
	$ainfo->StatusId=$_REQUEST['StatusId'];

	if($id>0)
	{
		if($pwd!="999999")
		{
			$ainfo->password=md5(trim($pwd));
		}		
		$ainfo->id=$id;
		$rtn=$fun->UpdateModel("t_user",$ainfo);
		if ($rtn==0){
		echo Msg("操作失败.","back");
		}
		else{
			echo Msg("操作成功","rightlist.php?action=list");
		} 
		return;		
	}
	else
	{
		if(trim($username)==''){
			echo Msg("操作失败。","back");
		
		return ;}		
		$b=$ad->CheckUser($username);
		if($b==1){
		echo Msg("该登陆名称已被使用","back");
			 
			return;
		}else{
			$ainfo->username=$username;
			$ainfo->password=md5(trim($pwd));
			$ainfo->upId=$_SESSION["userid"];
			//var_dump($ainfo);
			$rtn=$fun->AddModel("t_user",$ainfo);
			if ($rtn==0){
			echo Msg("操作失败","back");
			}
			else{
				echo Msg("操作成功","rightlist.php?action=list");
			} 
			return;
		}
	}
	
}

?>
<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #218644 solid">&nbsp;<span class="title">员工管理</span></td>
      <td width="25%" height="45" style="border-bottom:2px #218644 solid">&nbsp;</td>
    </tr>
  </table>
  <form id="form1" name="form1" method="post" action="?action=addsave" onsubmit="return check()">
    <table width="100%" height="399" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="13%" height="28" align="center" bgcolor="#FFFFFF">员工账号</td>
        <td width="87%" height="28" bgcolor="#FFFFFF"><input type="text" name="UserName" id="UserName" value="<?=$username?>" maxlength="50" <?=$id!=0?"disabled":""?>/>必填项</td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">登陆密码</td>
        <td height="28" bgcolor="#FFFFFF"><input type="password" name="PassWord" id="PassWord" value="<?=$password?>" maxlength="50" />必填项</td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">员工名称</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="realname" id="realname" value="<?=$realname?>" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">商务传真</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="fax" id="fax" value="<?=$fax?>" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">移动电话</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="mobi" id="mobi" value="<?=$mobi?>" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">商务电话</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="tel" id="tel" value="<?=$tel?>" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">商务邮箱</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="email" id="email" value="<?=$email?>" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">用户别名</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="byname" id="byname" value="<?=$byname?>" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">费率</td>
        <td height="28" bgcolor="#FFFFFF"><?
		if($_SESSION["userid"]==1)
		{
			echo $fun->GetComboList("FeeRate",21,$FeeRate);	
		}
		else
		{
			$FeeRateRecord=$fun->GetList("select FeeRate from t_user where FIND_IN_SET( id, getPatherId(16)) and upId=1");
			$FeeRate=$FeeRateRecord[0];
        	echo $fun->GetComboList("FeeRate",21,$FeeRate,"t_dic","ClassId,ClassName","","disabled");
		}
		?></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">发送级别</td>
        <td height="28" bgcolor="#FFFFFF"><?=$fun->GetComboList("sendLevel",1,$sendLevel)?></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">默认发送提示音</td>
        <td height="28" bgcolor="#FFFFFF"><?=$fun->GetComboList("sendSound",2,$sendSound)?></td>
      </tr>
      <tr>
        <td height="33" bgcolor="#FFFFFF" align="center">删除发送内容权限</td>
        <td height="33" bgcolor="#FFFFFF"><?=$fun->GetRadioList("ifDelInfo",3,$ifDelInfo)?></td>
      </tr>
      <tr>
        <td height="33" bgcolor="#FFFFFF" align="center">账户状态</td>
        <td height="33" bgcolor="#FFFFFF"><?=$fun->GetRadioList("StatusId",4,$StatusId)?></td>
      </tr>      
      <tr>
        <td height="39" bgcolor="#FFFFFF"><div align="center"><input type="hidden" name="id" id="id" value="<?=$id?>" /></div></td>
        <td height="39" bgcolor="#FFFFFF"><input type="submit" name="Submit" id="sub_btn" class="bt" value="保存" />
        <input type="reset" name="Submit2" value="取消" class="bt" /></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>