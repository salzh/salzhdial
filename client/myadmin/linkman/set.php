<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/linkman.class.php");
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
		var uname=$Id("Linkman").value;
		if(uname.Trim()==""){alert("请输入名称");$Id("Linkman").focus();return false;}
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
$GroupId= isset($_REQUEST['GroupId']) ? $_REQUEST['GroupId'] : 0;
$UserId=$_SESSION["userid"];
$Linkman="";
$Company="";
$Dept="";
$Position="";
$Country="";
$Province="";
$City="";
$Address="";
$PostCode="";
$Fax="";
$Tel="";
$HomeTel="";
$Mobi="";
$Email="";
$Url="";
$Description="";


$thisDal= new LinkmanDal();

if($id!="0")
{
	echo $id;
	$thisModel=$thisDal->GetModelById($id);
	$UserId=$thisModel->UserId;
	$GroupId=$thisModel->GroupId;
	$Linkman=$thisModel->Linkman;
	$Company=$thisModel->Company;
	$Dept=$thisModel->Dept;
	$Position=$thisModel->Position;
	$Country=$thisModel->Country;
	$Province=$thisModel->Province;
	$City=$thisModel->City;
	$Address=$thisModel->Address;
	$PostCode=$thisModel->PostCode;
	$Fax=$thisModel->Fax;
	$Tel=$thisModel->Tel;
	$HomeTel=$thisModel->HomeTel;
	$Mobi=$thisModel->Mobi;
	$Email=$thisModel->Email;
	$Url=$thisModel->Url;
	$Description=$thisModel->Description;	
}

if($ac=="addsave"){

	if(trim($_REQUEST['Linkman'])==''){
		echo Msg("操作失败。","back");
	
	return ;} 
	
	$thisModel=new LinkmanInfo();
	$thisModel->UserId=$UserId;
	$thisModel->GroupId=$_REQUEST['GroupId'];
	$thisModel->Linkman=$_REQUEST['Linkman'];
	$thisModel->Company=$_REQUEST['Company'];
	$thisModel->Dept=$_REQUEST['Dept'];
	$thisModel->Position=$_REQUEST['Position'];
	$thisModel->Country=$_REQUEST['Country'];
	$thisModel->Province=$_REQUEST['Province'];
	$thisModel->City=$_REQUEST['City'];
	$thisModel->Address=$_REQUEST['Address'];
	$thisModel->PostCode=$_REQUEST['PostCode'];
	$thisModel->Fax=$_REQUEST['Fax'];
	$thisModel->Tel=$_REQUEST['Tel'];
	$thisModel->HomeTel=$_REQUEST['HomeTel'];
	$thisModel->Mobi=$_REQUEST['Mobi'];
	$thisModel->Email=$_REQUEST['Email'];
	$thisModel->Url=$_REQUEST['Url'];
	$thisModel->Description=$_REQUEST['Description'];


	if($id>0)
	{
		$thisModel->id=$id;
		//$thisModel->UserId=$_SESSION["userid"];
		$rtn=$fun->UpdateModel("t_linkman",$thisModel);
		if ($rtn==0){
		echo Msg("操作失败.","back");
		}
		else{
			echo Msg("操作成功","list.php?action=list&GroupId=".$GroupId);
		} 
		return;		
	}
	else
	{
		//var_dump($ainfo);
		$rtn=$fun->AddModel("t_linkman",$thisModel);
		
		$mainDal=new AddressGroupDal();
		$mainDal->SetLinkmanNum($thisModel->GroupId);
					
		if ($rtn==0){
		echo Msg("操作失败","back");
		}
		else{
			echo Msg("操作成功","list.php?action=list&GroupId=".$GroupId);
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
    <table width="100%" height="534" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">分组名称</td>
        <td height="28" bgcolor="#FFFFFF"><?=$fun->GetComboList("GroupId","",$GroupId,"t_addressgroup","id,GroupName","",""," and UserId=".$_SESSION["userid"])?></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">联系人</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Linkman" id="Linkman" value="<?=$Linkman?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">公司名</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Company" id="Company" value="<?=$Company?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">部门</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Dept" id="Dept" value="<?=$Dept?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">职位</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Position" id="Position" value="<?=$Position?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">国家</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Country" id="Country" value="<?=$Country?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">省/直辖市</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Province" id="Province" value="<?=$Province?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">市/区</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="City" id="City" value="<?=$City?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">地址</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Address" id="Address" value="<?=$Address?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">邮政号码</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="PostCode" id="PostCode" value="<?=$PostCode?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">商务传真</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Fax" id="Fax" value="<?=$Fax?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">商务电话</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Tel" id="Tel" value="<?=$Tel?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">家庭电话</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="HomeTel" id="HomeTel" value="<?=$HomeTel?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">移动电话</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Mobi" id="Mobi" value="<?=$Mobi?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">商务邮箱</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Email" id="Email" value="<?=$Email?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">URL</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Url" id="Url" value="<?=$Url?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td width="13%" height="28" align="center" bgcolor="#FFFFFF">说明</td>
        <td width="87%" height="28" bgcolor="#FFFFFF"><input type="text" name="Description" id="Description" value="<?=$Description?>" maxlength="100" style="width:300px"/></td>
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