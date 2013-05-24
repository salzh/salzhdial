<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/recharge.class.php");
include("../../class/admin.class.php");
include("../../class/fun.class.php");
$pagetitle="回收余额管理";
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
		var uname=$Id("username").value;
		if(uname.Trim()==""){alert("请输入回收余额的账号");$Id("username").focus();return false;}
		var uname=$Id("GroupName").value;
		if(uname.Trim()==""){alert("请输入回收余额");$Id("GroupName").focus();return false;}
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
$CustomerMoney="";
$RechargeMoney="";
$Description="";
$OperatorId=$_SESSION["userid"];

$thisDal= new RechargeDal();

if($id!="0")
{
	$UserId=$thisModel->UserId;
	$CreateDate=$thisModel->CreateDate;
	$CustomerMoney=$thisModel->CustomerMoney;
	$RechargeMoney=$thisModel->RechargeMoney;
	$Description=$thisModel->Description;
	$OperatorId=$thisModel->OperatorId;
	$GroupName=$thisModel->GroupName;	
}

if($ac=="addsave"){
	$uname= isset($_REQUEST['uname'])? $_REQUEST['uname'] : '';
	$RechargeMoney= isset($_REQUEST['RechargeMoney'])? $_REQUEST['RechargeMoney'] : '0';
	$Description= isset($_REQUEST['Description'])? $_REQUEST['Description'] : '';
	
	$thisModel=new RechargeInfo();
	$thisModel->UserId=$UserId;
	$thisModel->RechargeMoney=$RechargeMoney;
	$thisModel->Description="[".$_SESSION['username']."从".$uname."回收".$RechargeMoney."元]".$Description;;
	$thisModel->OperatorId=$OperatorId;

	if($id>0)
	{
		$thisModel->id=$id;
		$rtn=$fun->UpdateModel("t_recharge",$thisModel);
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
		$adminDal= new AdminDal();
		$adminInfoParent= $adminDal->GetModelByUserId($_SESSION["userid"]);


		$adminInfo= $adminDal->GetModelByUserName($uname);
		if($adminInfo->id>0)
		{
			$tmp=$fun->GetValue("select getChildLst(".$_SESSION["userid"].")").",e,";
			if(!$fun->checkstr($tmp,",".$adminInfo->id.","))
			{
				echo Msg("操作失败，此账户不是您的下级账户。","back");
				return;
			}
			$allmoney=$adminInfo->voiceMoney;
			if($allmoney<$RechargeMoney)
			{
				echo Msg("操作失败，余额不足。","back");
				return;
			}
			$thisModel->CustomerMoney=$adminInfo->voiceMoney;
			$adminInfo->voiceMoney=$adminInfo->voiceMoney-$RechargeMoney;

			$rtn=$fun->UpdateModel("t_user",$adminInfo);
			$adminInfoParent->voiceMoney=$adminInfoParent->voiceMoney+$RechargeMoney;
			$rtn=$fun->UpdateModel("t_user",$adminInfoParent);		
			$thisModel->TargetId=$adminInfo->id;	
			$rtn=$fun->AddModel("t_recharge",$thisModel);
			if ($rtn==0){
				echo Msg("操作失败","back");
			}
			else{
				echo Msg("操作成功","list.php?action=list");
			} 
			return;
		}
		else
		{
			echo Msg("操作失败,账号不存在","back");
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
    <table width="100%" height="128" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">回收余额账号</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="uname" id="uname" value="<?=$uname?>" maxlength="50" />
          必填项</td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">回收金额</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="RechargeMoney" id="RechargeMoney" value="<?=$RechargeMoney?>" maxlength="50" />
          必填项</td>
      </tr>
      <tr>
        <td width="13%" height="28" align="center" bgcolor="#FFFFFF">说明</td>
        <td width="87%" height="28" bgcolor="#FFFFFF"><input type="text" name="Description" id="Description" value="<?=$Description?>" maxlength="50" /></td>
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