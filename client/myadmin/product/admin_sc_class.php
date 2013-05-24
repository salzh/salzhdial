<?php
include("../check.php");
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/sc.class.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>市场需求分类</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:566px;
	top:71px;
	width:511px;
	height:191px;
	z-index:1;
	background:#fff;
}

#Layer2 {
	position:absolute;
	left:569px;
	top:72px;
	width:516px;
	height:193px;
	z-index:1;
		background:#fff;

}
 
-->
</style>
</head>

<body>
<?php
	
	$productclass= new SCClass();
	$a="";
	$bid= isset($_REQUEST['bid']) ? $_REQUEST['bid'] : '0';
	$classid= isset($_REQUEST['classid']) ? $_REQUEST['classid'] : '0';
	$classname= isset($_REQUEST['classname']) ? $_REQUEST['classname'] : '未命名';
	$orderid= isset($_REQUEST['orderid']) ? $_REQUEST['orderid'] : '1';
 
 		
	if(isset($_REQUEST['action']))
	{
		$a=$_REQUEST['action'];
	}
?>

<?
	if($a=="addsave")
	{
		
		
		$model=new SCClassInfo();
		$model->ClassName=$classname;
		$model->OrderId=cint($orderid);
		$model->Bid=cint($bid);
 
		
		if($productclass->Add($model)>0)
		{
			echo Msg("操作成功","?action=list");
		}
		
		
	}
	
	if($a=="modifysave")
	{
	 
		
		$model=new SCClassInfo();
		$model->ClassName=$classname;
		$model->OrderId=cint($orderid);
		$model->Bid=cint($bid);
		$model->ClassId=$classid;
		
		if($productclass->Edit($model)>0)
		{
			echo Msg("操作成功","?action=list");
		}
		
	}
	
	if($a=="del")
	{
 			if($productclass->Del($classid)>0)
				{
				echo Msg("操作成功","?action=list");
				}
 
	}
	
	
	if ($a=="add")
	{
?>
<div id="Layer1">
  <form id="form2" name="form2" method="post" action="?action=addsave">

  <table width="100%" height="187" border="0" cellpadding="1" cellspacing="3" style="border:1px solid #ccc">
    <tr>
      <td height="29" colspan="2" bgcolor="#0066CC"><div align="right"><a href="?action=list" style="color:#fff">关闭&nbsp;&nbsp; </a></div></td>
    </tr>
    <tr>
      <td height="20" bgcolor="#FFFFFF"><div align="center">所属类</div></td>
      <td height="20" bgcolor="#FFFFFF">
	  		<?php
				if($bid=="0")
				{
					echo "一级分类";
				}
				else
				{
					echo $productclass->GetClassName($bid);
				}
				$sortId=cint($productclass->GetMaxOrderId());
				
			?>
	        <input name="bid" type="hidden" id="bid" value="<?=$bid?>" /></td>
    </tr>
    <tr>
      <td width="16%" height="40" bgcolor="#FFFFFF"><div align="center">分类名</div></td>
      <td width="84%" height="40" bgcolor="#FFFFFF"><input name="classname" type="text" id="classname" size="50" maxlength="50" /></td>
    </tr>
    <tr>
      <td height="40" bgcolor="#FFFFFF"><div align="center">排序</div></td>
      <td height="40" bgcolor="#FFFFFF"><input name="orderid" type="text" id="orderid" value="<?=$sortId+1?>" size="5" maxlength="5" /></td>
    </tr>
    <tr>
      <td height="40" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="40" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存" /></td>
    </tr>
  </table>
  </form>
</div>

<?
	}
?>



<?php 
if($a=="modify")
{
$classid= isset($_REQUEST['classid']) ? $_REQUEST['classid'] : 0;
$m= $productclass->GetClassModel($classid);
if($m!=NULL)
{

	$_className=$m->ClassName;
	$_classId=$m->ClassId;
	$_orderId=$m->OrderId;
	$_bid=$m->Bid;
}	
?>

<div id="Layer2">
  <form id="form2" name="form2" method="post" action="?action=modifysave">

  <table width="100%" height="180" border="0" cellpadding="1" cellspacing="3" style="border:1px solid #ccc">
    <tr>
      <td height="29" colspan="2" bgcolor="#0066CC"><div align="right"><a href="?action=list" style="color:#fff">关闭&nbsp;&nbsp; </a></div></td>
    </tr>
    <tr>
      <td height="20" bgcolor="#FFFFFF"><div align="center">所属类</div></td>
      <td height="20" bgcolor="#FFFFFF">
	  		<?php
				if($_bid=="0")
				{
					echo "一级分类";
				}
				else
				{
					echo $productclass->GetClassName($_bid);
				}
			 
				
			?>
	        <input name="bid" type="hidden" id="bid" value="<?=$_bid?>" />
			 <input name="classid" type="hidden" id="classid" value="<?=$_classId?>" />		</td>
    </tr>
    <tr>
      <td width="21%" height="40" bgcolor="#FFFFFF"><div align="center">分类名</div></td>
      <td width="79%" height="40" bgcolor="#FFFFFF"><input name="classname" type="text" id="classname" size="50" value="<?=$_className?>" maxlength="50" /></td>
    </tr>
    <tr>
      <td height="40" bgcolor="#FFFFFF"><div align="center">排序</div></td>
      <td height="40" bgcolor="#FFFFFF"><input name="orderid" type="text" id="orderid" value="<?=$_orderId?>" size="5" maxlength="5" /></td>
    </tr>
    <tr>
      <td height="33" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="33" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存" /></td>
    </tr>
  </table>
  </form>
</div>



<?
	}
?>
<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="38%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">市场需求分类 </span></td>
      <td width="62%" height="45" style="border-bottom:2px #0066CC solid"><input type="button" name="Submit2" class="bt" onclick="window.location.href='?action=add'" value="添加分类" /></td>
    </tr>
  </table>
  
    <table width="500" height="38" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="margin-top:3px;">
      <tr>
        <td width="54" bgcolor="#ECF5FF"><div align="center">ID</div></td>
        <td width="267" bgcolor="#ECF5FF"><div align="center">分类名</div></td>
        <td width="45" bgcolor="#ECF5FF"><div align="center">改</div></td>
        <td width="55" bgcolor="#ECF5FF"><div align="center">删</div></td>
      </tr>
  </table>
  <table width="500"  border="0" cellpadding="0" cellspacing="1" bgcolor="#Efefef"  >
 <?php 
 	$list=$productclass->GetClassList();
 	foreach($list as $rs)
	{
 ?>
      <tr height="28" bgcolor="#f5f5f5">
        <td width="54" height="25" bgcolor="#f5f5f5"><div align="center"><?=$rs['ClassId']?></div></td>
        <td width="267" bgcolor="#f5f5f5"><div align="left"><a href="?action=modify&classid=<?=$rs['ClassId']?>&bid=<?=$rs['Bid']?>">
        <strong>  <?=$rs['ClassName']?></strong>
        </a></div></td>
        <td width="45" bgcolor="#ffffff"><div align="center"><a href="?action=modify&classid=<?=$rs['ClassId']?>&bid=<?=$rs['Bid']?>"><img src="../images/modify.gif" style="border:0px;" /></a></div></td>
        <td width="55" bgcolor="#ffffff"><div align="center"><a href='javascript:if(confirm("确实要删除该贴吗,删除后再也找不回?"))location="?action=del&classid=<?=$rs['ClassId']?>&bid=0"'><img src="../images/del.gif" style="border:0;" /></a></div></td>
      </tr>
	     <?
   }
   ?>
   </table>

</div>
 
</body>
</html>
