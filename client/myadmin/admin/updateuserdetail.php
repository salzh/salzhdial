<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/admin.class.php");
include("../../class/fun.class.php");
$pagetitle="证件管理";
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$pagetitle?></title>
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
$cardType="";
$cardNo="";
$cardPic="";

if($id!="0")
{
	$ad= new AdminDal();
	$thisModel=$ad->GetUserNameByUserId($id);

	$username=$thisModel['username'];
	$realname=$thisModel['realname'];

	$cardType=$thisModel['cardType'];
	$cardNo=$thisModel['cardNo'];
	$cardPic=$thisModel['cardPic'];
		
}

if($ac=="addsave"){
	
	$ad= new AdminDal();
	$ainfo=new AdminInfo();
	$ainfo->cardType=$_REQUEST['cardType'];
	$ainfo->cardNo=$_REQUEST['cardNo'];
	$ainfo->cardPic=$_REQUEST['cardPic'];


	if($id>0)
	{	
		$ainfo->id=$id;
		$rtn=$fun->UpdateModel("t_user",$ainfo);
		if ($rtn==0){
		echo Msg("操作失败.","back");
		}
		else{
			echo Msg("操作成功","updateuserdetail.php");
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
      <td width="50%" height="45" style="border-bottom:2px #218644 solid"><span class="title"><?=$pagetitle?></span></td>
      <td width="50%" height="45" style="border-bottom:2px #218644 solid">&nbsp;</td>
    </tr>
  </table>
  <form id="form1" name="form1" method="post" action="?action=addsave" onsubmit="return check()">
    <table width="100%" height="215" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="12%" height="28" align="center" bgcolor="#FFFFFF">用户账号</td>
        <td width="88%" bgcolor="#FFFFFF"><input name="UserName" type="text" id="UserName" value="<?=$username?>" maxlength="50" disabled="disabled"/></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">用户名称</td>
        <td bgcolor="#FFFFFF"><input type="text" name="realname" id="realname" value="<?=$realname?>" maxlength="20" disabled="disabled"/></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">证件类型</td>
        <td bgcolor="#FFFFFF"><?=$fun->GetComboList("cardType",6,$cardType)?></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">证件号码</td>
        <td bgcolor="#FFFFFF"><input type="text" name="cardNo" id="cardNo" value="<?=$cardNo?>" maxlength="20" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center">证件图片</td>
        <td bgcolor="#FFFFFF">
        <iframe src="../up.php?control=cardPic" width="500"  scrolling="No" height="100" frameborder="0"></iframe>
        
        <br />
        <input type="text" name="cardPic" id="cardPic" value="<?=$cardPic?>" maxlength="100" style="width:300px" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF" align="center"><input type="hidden" name="id" id="id" value="<?=$id?>" /></td>
        <td bgcolor="#FFFFFF"><img style="border:1px solid #ccc;" src="../../<?=$cardPic!=""?$cardPic:"../images/no.gif"?>" name="img" width="100" height="100" id="img"  onload="javascript:Simg(this,500,500);"   /></td>
      </tr>      
      <tr>
        <td height="39" colspan="2" align="center" bgcolor="#FFFFFF"><div align="center" style="width:300px;margin:auto"><input type="submit" name="Submit" id="sub_btn" class="bt" value="保存"/>
        <input type="reset" name="Submit2" value="取消" class="bt" /></div></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>