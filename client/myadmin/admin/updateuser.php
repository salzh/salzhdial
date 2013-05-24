<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/admin.class.php");
include("../../class/fun.class.php");
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户信息</title>
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
		if(uname.Trim()==""){alert("请输入登陆名称");$Id("UserName").focus();return false;}
		if(upwd.Trim()==""){alert("请输入登录密码");$Id("PassWord").focus();return false;}
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
$id= $_SESSION["userid"];

$username="";
$realname="";
$byname="";
$areaNum="";
$linkman="";
$address="";
$postcode="";
$tel="";
$fax="";
$mobi="";
$email="";
$mainTel="";
$sendLevel="";
$faxMoney="";
$messageMoney="";
$alertMoney="";
$ip="";
$loginNum="";
$LastLogin="";
$createTime="";
$voiceMoney="";


if($id!="0")
{
	$ad= new AdminDal();
	$thisModel=$ad->GetUserNameByUserId($id);

	$username=$thisModel['username'];
	$realname=$thisModel['realname'];
	$byname=$thisModel['byname'];
	$areaNum=$thisModel['areaNum'];
	$linkman=$thisModel['linkman'];
	$address=$thisModel['address'];
	$postcode=$thisModel['postcode'];
	$tel=$thisModel['tel'];
	$fax=$thisModel['fax'];
	$mobi=$thisModel['mobi'];
	$email=$thisModel['email'];
	$mainTel=$thisModel['mainTel'];
	$sendLevel=$thisModel['sendLevel'];
	$faxMoney=$thisModel['faxMoney'];
	$messageMoney=$thisModel['messageMoney'];
	$alertMoney=$thisModel['alertMoney'];
	$ip=$thisModel['ip'];
	$loginNum=$thisModel['loginNum'];
	$LastLogin=$thisModel['LastLogin'];
	$createTime=$thisModel['createTime'];
	$voiceMoney=$thisModel['voiceMoney'];
		
}

if($ac=="addsave"){
	
	$ad= new AdminDal();
	$ainfo=new AdminInfo();

	//$ainfo->username=$_REQUEST['username'];
	//$ainfo->realname=$_REQUEST['realname'];
	$ainfo->byname=$_REQUEST['byname'];
	//$ainfo->areaNum=$_REQUEST['areaNum'];
	//$ainfo->linkman=$_REQUEST['linkman'];
	//$ainfo->address=$_REQUEST['address'];
	//$ainfo->postcode=$_REQUEST['postcode'];
	$ainfo->tel=$_REQUEST['tel'];
	$ainfo->fax=$_REQUEST['fax'];
	$ainfo->mobi=$_REQUEST['mobi'];
	$ainfo->email=$_REQUEST['email'];
	//$ainfo->mainTel=$_REQUEST['mainTel'];
	//$ainfo->sendLevel=$_REQUEST['sendLevel'];
	//$ainfo->faxMoney=$_REQUEST['faxMoney'];
	//$ainfo->messageMoney=$_REQUEST['messageMoney'];
	$ainfo->alertMoney=$_REQUEST['alertMoney'];
	$ainfo->ip=$_REQUEST['ip'];
	//$ainfo->loginNum=$_REQUEST['loginNum'];
	//$ainfo->LastLogin=$_REQUEST['LastLogin'];
	//$ainfo->createTime=$_REQUEST['createTime'];
	//$ainfo->voiceMoney=$_REQUEST['voiceMoney'];

	if($id>0)
	{	
		$ainfo->id=$id;
		$rtn=$fun->UpdateModel("t_user",$ainfo);
		if ($rtn==0){
		echo Msg("操作失败.","back");
		}
		else{
			echo Msg("操作成功","updateuser.php");
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

			//var_dump($ainfo);
			$rtn=$fun->AddModel("t_user",$ainfo);
			if ($rtn==0){
			echo Msg("操作失败","back");
			}
			else{
				echo Msg("操作成功","updateuser.php");
			} 
			return;
		}
	}
	
}

?>
<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="50%" height="45" style="border-bottom:2px #218644 solid">用户信息<span class="title"></span></td>
      <td width="50%" height="45" style="border-bottom:2px #218644 solid">系统信息</td>
    </tr>
  </table>
  <form id="form1" name="form1" method="post" action="?action=addsave" onsubmit="return check()">
    <table width="100%" height="360" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="10%" height="28" align="center" bgcolor="#FFFFFF">用户账号</td>
        <td width="40%" bgcolor="#FFFFFF"><input name="UserName" type="text" id="UserName" value="<?=$username?>" maxlength="50" disabled="disabled"/></td>
        <td width="10%" align="center" bgcolor="#FFFFFF">主叫号码</td>
        <td width="40%" height="28" bgcolor="#FFFFFF"><input type="text" name="mainTel" id="mainTel" value="<?=$mainTel?>" maxlength="20" disabled="disabled"/></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">用户名称</td>
        <td bgcolor="#FFFFFF"><input type="text" name="realname" id="realname" value="<?=$realname?>" maxlength="20" disabled="disabled"/></td>
        <td bgcolor="#FFFFFF" align="center">发送级别</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="sendLevel" id="sendLevel" value="<?=$sendLevel?>" maxlength="20" disabled="disabled"/></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">用户别名</td>
        <td bgcolor="#FFFFFF"><input type="text" name="byname" id="byname" value="<?=$byname?>" maxlength="20" />
          （添加收件人信息时,显示发件人）</td>
        <td bgcolor="#FFFFFF" align="center">传真余额</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="faxMoney" id="faxMoney" value="<?=$faxMoney?>" maxlength="20" disabled="disabled"/></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">所在地区</td>
        <td bgcolor="#FFFFFF"><input type="text" name="areaNum" id="areaNum" value="<?=$areaNum?>" maxlength="20" disabled="disabled"/></td>
        <td bgcolor="#FFFFFF" align="center">短信余额</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="messageMoney" id="messageMoney" value="<?=$messageMoney?>" maxlength="20" disabled="disabled"/></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">联系人</td>
        <td bgcolor="#FFFFFF"><input type="text" name="linkman" id="linkman" value="<?=$linkman?>" maxlength="20" disabled="disabled"/></td>
        <td bgcolor="#FFFFFF" align="center">语音余额</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="voiceMoney" id="voiceMoney" value="<?=round($voiceMoney,2)?>" maxlength="20" disabled="disabled"/></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">地址</td>
        <td bgcolor="#FFFFFF"><input type="text" name="address" id="address" value="<?=$address?>" maxlength="20" disabled="disabled"/></td>
        <td bgcolor="#FFFFFF" align="center">提醒金额</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="alertMoney" id="alertMoney" value="<?=$alertMoney?>" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">邮政号码</td>
        <td bgcolor="#FFFFFF"><input type="text" name="postcode" id="postcode" value="<?=$postcode?>" maxlength="20" disabled="disabled"/></td>
        <td bgcolor="#FFFFFF" align="center">创建日期</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="createTime" id="createTime" value="<?=$createTime?>" maxlength="20" disabled="disabled"/></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">商务传真</td>
        <td bgcolor="#FFFFFF"><input type="text" name="fax" id="fax" value="<?=$fax?>" maxlength="20"/></td>
        <td bgcolor="#FFFFFF" align="center">访问IP地址</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="ip" id="ip" value="<?=$ip?>" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">移动电话</td>
        <td bgcolor="#FFFFFF"><input type="text" name="mobi" id="mobi" value="<?=$mobi?>" maxlength="20" />
          （短信反馈）</td>
        <td bgcolor="#FFFFFF" align="center">登陆次数</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="loginNum" id="loginNum" value="<?=$loginNum?>" maxlength="20" disabled="disabled"/></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">商务电话</td>
        <td bgcolor="#FFFFFF"><input type="text" name="tel" id="tel" value="<?=$tel?>" maxlength="20" /></td>
        <td bgcolor="#FFFFFF" align="center">上一次登陆时间</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="LastLogin" id="LastLogin" value="<?=$LastLogin?>" maxlength="20" disabled="disabled"/></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">商务邮箱</td>
        <td bgcolor="#FFFFFF"><input type="text" name="email" id="email" value="<?=$email?>" maxlength="20" /></td>
        <td bgcolor="#FFFFFF" align="center">&nbsp;</td>
        <td height="28" bgcolor="#FFFFFF">&nbsp;<input type="hidden" name="id" id="id" value="<?=$id?>" /></td>
      </tr>      
      <tr>
        <td height="39" colspan="4" align="center" bgcolor="#FFFFFF"><div align="center" style="width:300px;margin:auto"><input type="submit" name="Submit" id="sub_btn" class="bt" value="保存"/>
        <input type="reset" name="Submit2" value="取消" class="bt" /></div></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>