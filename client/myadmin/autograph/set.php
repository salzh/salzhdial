<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/autograph.class.php");
include("../../class/fun.class.php");
$pagetitle="签名管理";
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
		var uname=$Id("AutographName").value;
		if(uname.Trim()==""){alert("请输入名称");$Id("AutographName").focus();return false;}
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
$UserId=$_SESSION["userid"];

$thisDal= new AutographDal();

if($id!="0")
{
	$thisModel=$thisDal->GetModelById($id);
	$UserId=$thisModel->UserId;
	$AutographNo=$thisModel->AutographNo;
	$AutographName=$thisModel->AutographName;
	$AutographPwd=$thisModel->AutographPwd;
	$AutographFile=$thisModel->AutographFile;
	$Description=$thisModel->Description;
}

if($ac=="addsave"){

	if(trim($_REQUEST['AutographName'])==''){
		echo Msg("操作失败。","back");
	
	return ;} 
	
	$AutographNo= isset($_REQUEST['AutographNo'])? $_REQUEST['AutographNo'] : '';
	$AutographName= isset($_REQUEST['AutographName'])? $_REQUEST['AutographName'] : '';
	$AutographPwd= isset($_REQUEST['AutographPwd'])? $_REQUEST['AutographPwd'] : '';
	$AutographFile= isset($_REQUEST['AutographFile'])? $_REQUEST['AutographFile'] : '';
	$Description= isset($_REQUEST['Description'])? $_REQUEST['Description'] : '';
	
	$thisModel=new AutographInfo();
	$thisModel->UserId=$UserId;
	$thisModel->AutographNo=$AutographNo;
	$thisModel->AutographName=$AutographName;
	$thisModel->AutographPwd=$AutographPwd;
	$thisModel->AutographFile=$AutographFile;
	$thisModel->Description=$Description;


	if($id>0)
	{
		$thisModel->id=$id;
		//$thisModel->UserId=$_SESSION["userid"];
		$rtn=$fun->UpdateModel($thisDal->thisTable,$thisModel);
		if ($rtn==0){
		echo Msg("操作失败.","back");
		}
		else{
			echo Msg("操作成功","list.php?action=list");
		} 
		return;		
	}
	else
	{
		//var_dump($ainfo);
		$rtn=$fun->AddModel($thisDal->thisTable,$thisModel);
		if ($rtn==0){
		echo Msg("操作失败","back");
		}
		else{
			echo Msg("操作成功","list.php?action=list");
		} 
		return;
	}
	
}

?>
<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #218644 solid">&nbsp;<span class="title"><?=$pagetitle?></span></td>
      <td width="25%" height="45" style="border-bottom:2px #218644 solid">&nbsp;</td>
    </tr>
  </table>
  <form id="form1" name="form1" method="post" action="?action=addsave" onsubmit="return check()">
    <table width="100%" height="215" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="13%" height="28" align="center" bgcolor="#FFFFFF">签名号</td>
        <td width="87%" height="28" bgcolor="#FFFFFF"><input type="text" name="AutographNo" id="AutographNo" value="<?=$AutographNo?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td width="13%" height="28" align="center" bgcolor="#FFFFFF">签名名称</td>
        <td width="87%" height="28" bgcolor="#FFFFFF"><input type="text" name="AutographName" id="AutographName" value="<?=$AutographName?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td width="13%" height="28" align="center" bgcolor="#FFFFFF">签名密码</td>
        <td width="87%" height="28" bgcolor="#FFFFFF"><input type="text" name="AutographPwd" id="AutographPwd" value="<?=$AutographPwd?>" maxlength="100" style="width:300px"/></td>
      </tr>            
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">签名图像内容</td>
        <td height="28" bgcolor="#FFFFFF">
	<iframe src="../up.php?control=AutographFile" width="500"  scrolling="No" height="100" frameborder="0"></iframe><br />        
        <input type="text" name="AutographFile" id="AutographFile" value="<?=$AutographFile?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF"></td>
        <td height="28" bgcolor="#FFFFFF"><img style="border:1px solid #ccc;" src="../../<?=$AutographFile!=""?$AutographFile:"myadmin/images/no.gif"?>" name="img" width="100" height="100" id="img"  onload="javascript:Simg(this,500,500);"   /></td>
      </tr>      
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">说明</td>
        <td height="28" bgcolor="#FFFFFF"><textarea name="Description" id="Description" style="width:300px"><?=$Description?></textarea></td>
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