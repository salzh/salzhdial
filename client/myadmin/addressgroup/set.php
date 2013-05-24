<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/addressgroup.class.php");
include("../../class/fun.class.php");
$pagetitle="地址簿管理";
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
		var uname=$Id("GroupName").value;
		if(uname.Trim()==""){alert("请输入名称");$Id("GroupName").focus();return false;}
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

$GroupName="";
$GroupType="";
$thisDal= new AddressGroupDal();

if($id!="0")
{
	echo $id;
	$thisModel=$thisDal->GetModelById($id);

	$GroupName=$thisModel->GroupName;
	$GroupType=$thisModel->GroupType;
	
}

if($ac=="addsave"){
	$GroupName=$_REQUEST['GroupName'];
	$GroupType=$_REQUEST['GroupType'];
	if(trim($GroupName)==''){
		echo Msg("操作失败。","back");
	
	return ;} 
	
	$thisModel=new AddressGroupInfo();
	$thisModel->GroupName=$GroupName;
	$thisModel->GroupType=$GroupType;
	if($id>0)
	{
		$thisModel->id=$id;
		//$thisModel->UserId=$_SESSION["userid"];
		$rtn=$fun->UpdateModel("t_addressgroup",$thisModel);
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
		$b=$thisDal->CheckName($GroupName,$_SESSION["userid"]);
		if($b==1){
		echo Msg("该名称已被使用","back");
			 
			return;
		}else{
			$thisModel->UserId=$_SESSION["userid"];

			//var_dump($ainfo);
			$rtn=$fun->AddModel("t_addressgroup",$thisModel);
			if ($rtn==0){
			echo Msg("操作失败","back");
			}
			else{
				echo Msg("操作成功","list.php?action=list");
			} 
			return;
		}
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
    <table width="100%" height="99" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="13%" height="28" align="center" bgcolor="#FFFFFF">分组名称</td>
        <td width="87%" height="28" bgcolor="#FFFFFF"><input type="text" name="GroupName" id="GroupName" value="<?=$GroupName?>" maxlength="50" />必填项</td>
      </tr>      
      <tr style="display:none">
        <td height="28" align="center" bgcolor="#FFFFFF">分组类型</td>
        <td height="28" bgcolor="#FFFFFF"><?=$fun->GetRadioList("GroupType",18,$GroupType)?></td>
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