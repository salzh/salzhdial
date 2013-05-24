<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/product.class.php");
include("../check.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新闻分类管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:555px;
	top:46px;
	width:572px;
	height:402px;
	z-index:1;
	background:#fff;
}

#Layer2 {
	position:absolute;
	left:556px;
	top:49px;
	width:585px;
	height:390px;
	z-index:1;
		background:#fff;

}
 
-->
</style>
</head>

<body>
<?php
 

	$productclass= new ProductClass();
	$a="";
	$bid= isset($_REQUEST['bid']) ? $_REQUEST['bid'] : '0';
	$classid= isset($_REQUEST['classid']) ? $_REQUEST['classid'] : '0';
	$classname= isset($_REQUEST['classname']) ? $_REQUEST['classname'] : '未命名';
	$orderid= isset($_REQUEST['orderid']) ? $_REQUEST['orderid'] : '1';
	$keyword= isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
	$des= isset($_REQUEST['des']) ? $_REQUEST['des'] : '';
	$content= isset($_REQUEST['content']) ? $_REQUEST['content'] : '无';
	$pic= isset($_REQUEST['pic']) ? $_REQUEST['pic'] : '';

	$lev= isset($_REQUEST['lev']) ? $_REQUEST['lev'] : '0';
 		
		
	if(isset($_REQUEST['action']))
	{
		$a=$_REQUEST['action'];
	}
?>

<?
	if($a=="addsave")
	{
		
		
		$model=new ProductClassInfo();
		$model->ClassName=$classname;
		$model->OrderId=cint($orderid);
		$model->Content=$content;
		$model->KeyWord=$keyword;
		$model->Des=$des;
		$model->Bid=cint($bid);
		
		$model->Content=$content;
		$model->Pic=$pic;
		
		if($productclass->AddProductClass($model)>0)
		{
			echo Msg("操作成功","?action=list");
		}
		
		
	}
	
	if($a=="modifysave")
	{
	 
		
		$model=new ProductClassInfo();
		$model->ClassName=$classname;
		$model->OrderId=cint($orderid);
		$model->Content=$content;
		$model->KeyWord=$keyword;
		$model->Des=$des;
		$model->Bid=cint($bid);
		$model->Content=$content;
		$model->ClassId=$classid;
		$model->Pic=$pic;
		
		if($productclass->EditProductClass($model)>0)
		{
			echo Msg("操作成功","?action=list");
		}
		
	}
	
	if($a=="del")
	{
		if($lev!='0')
		{
			if(cint($productclass->IfBid($classid))>0)
			{
				echo Msg("该类下有子类,不能进行删除操作","back");
			}
			else
			{
				if($productclass->DelProductClass($classid)>0)
				{
				echo Msg("操作成功","?action=list");
				}
			}
		}
		else
		{
			if($productclass->DelProductClass($classid)>0)
				{
				echo Msg("操作成功","?action=list");
				} 
		}
	}
	
	
	if ($a=="add")
	{
?>
<div id="Layer1">
  <form id="form1" name="form1" method="post" action="?action=addsave">

  <table width="100%" height="375" border="0" cellpadding="1" cellspacing="3" style="border:1px solid #ccc">
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
      <td height="52" bgcolor="#FFFFFF"><div align="center">缩略图</div></td>
      <td height="52" bgcolor="#FFFFFF">   <table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tr>
													  <td width="44%"><iframe src="../up.php" width="400"  scrolling="No" height="100" frameborder="0"></iframe>                                              </td>
													  <td width="56%"><div align="center"><img src="../../pic/logo.jpg" name="img" width="50" height="50" id="img"  onload="javascript:Simg(this,50,50);"   /> 
												        <input name="pic" style="display:none" type="text" id="pic" value="/pic/no.jpg" size="80" maxlength="100" />
												      </div></td>
													</tr>
												  </table>	  </td>
    </tr>
    <tr>
      <td height="52" bgcolor="#FFFFFF"><div align="center">备注说明</div></td>
      <td height="52" bgcolor="#FFFFFF"><textarea name="content" cols="60"  style="height:50px; width:360px;" rows="4" id="content"></textarea></td>
    </tr>
    <tr>
      <td height="40" bgcolor="#FFFFFF"><div align="center">首字母</div></td>
      <td height="40" bgcolor="#FFFFFF"><input name="keyword" type="text" id="keyword" size="10" maxlength="10" /></td>
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
	$_content=$m->Content;
	$_keyWord=$m->KeyWord;
	$_des=$m->Des;
	$_bid=$m->Bid;
	$_pic=$m->Pic;
}	
?>

<div id="Layer2">
  <form id="form1" name="form1" method="post" action="?action=modifysave">

  <table width="99%" height="333" border="0" cellpadding="1" cellspacing="3" style="border:1px solid #ccc">
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
      <td width="12%" height="40" bgcolor="#FFFFFF"><div align="center">分类名</div></td>
      <td width="88%" height="40" bgcolor="#FFFFFF"><input name="classname" type="text" id="classname" size="50" value="<?=$_className?>" maxlength="50" /></td>
    </tr>
    <tr>
      <td height="40" bgcolor="#FFFFFF"><div align="center">排序</div></td>
      <td height="40" bgcolor="#FFFFFF"><input name="orderid" type="text" id="orderid" value="<?=$_orderId?>" size="5" maxlength="5" /></td>
    </tr>
    <tr>
      <td height="52" bgcolor="#FFFFFF"><div align="center">缩略图</div></td>
      <td height="52" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="84%"><iframe src="../up.php" width="400"  scrolling="No" height="100" frameborder="0"></iframe><input name="pic" type="text" id="pic" value="<?=$_pic?>" size="80" maxlength="100"  style="display:none" /></td>
          <td width="16%"><img src="../../<?=$_pic?>" name="img" width="50" height="50" id="img"  onload="javascript:Simg(this,50,50);"></td>
        </tr>
       
      </table></td>
    </tr>
    <tr>
      <td height="52" bgcolor="#FFFFFF"><div align="center">备注说明</div></td>
      <td height="52" bgcolor="#FFFFFF"><textarea name="content" cols="60"  style="height:50px; width:360px;"  rows="4" id="content"><?=$_content?></textarea></td>
    </tr>
    <tr>
      <td height="40" bgcolor="#FFFFFF"><div align="center">首字母</div></td>
      <td height="40" bgcolor="#FFFFFF"><input name="keyword" type="text" id="keyword" size="10" value="<?=$_keyWord?>" maxlength="10" />
        &nbsp; </td>
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
      <td width="38%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">内容分类管理 </span></td>
      <td width="62%" height="45" style="border-bottom:2px #0066CC solid"> 
	  <input type="button" name="Submit2" class="bt" onclick="window.location.href='?action=add'" value="添加分类" />
	 
	  </td>
    </tr>
  </table>
  
    <table width="500" height="38" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="margin-top:3px;">
      <tr>
        <td width="54" bgcolor="#ECF5FF"><div align="center">ID</div></td>
        <td width="267" bgcolor="#ECF5FF"><div align="center">名称</div></td>
        <td width="80" bgcolor="#ECF5FF"><div align="center">添加子类</div></td>
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
        <strong>  [<?=$rs['KeyWord']?>]<?=$rs['ClassName']?></strong>
        </a></div></td>
        <td width="80" bgcolor="#f5f5f5"><div align="center"><a href="?action=add&bid=<?=$rs['ClassId']?>">添加子类</a></div></td>
        <td width="45" bgcolor="#ffffff"><div align="center"><a href="?action=modify&classid=<?=$rs['ClassId']?>&bid=<?=$rs['Bid']?>"><img src="../images/modify.gif" style="border:0px;" /></a></div></td>
        <td width="55" bgcolor="#ffffff"><div align="center">
	 
		<a href='javascript:if(confirm("确实要删除该内容吗,删除后再也找不回?"))location="?action=del&classid=<?=$rs['ClassId']?>&bid=0&lev=1"'><img src="../images/del.gif" style="border:0;display:none" /></a>
	 
		</div></td>
      </tr>
<?
	$list2=$productclass->GetClassList($rs['ClassId']);
	foreach($list2 as $rs2)
		{
?>
  <tr height="28">
        <td width="54" bgcolor="#FFFFFF"><div align="center"><?=$rs2['ClassId']?></div></td>
        <td width="267" bgcolor="#FFFFFF"><div align="left" ><a href="?action=modify&classid=<?=$rs2['ClassId']?>&bid=<?=$rs2['Bid']?>" style="color:#0099CC">——[<?=$rs2['KeyWord']?>]<?=$rs2['ClassName']?>
        </a></div></td>
        <td width="80" bgcolor="#FFFFFF"><div align="center"> <a href="?action=add&bid=<?=$rs2['ClassId']?>">添加子类</a></div></td>
        <td width="45" bgcolor="#FFFFFF"><div align="center"><a href="?action=modify&classid=<?=$rs2['ClassId']?>&bid=<?=$rs2['Bid']?>"><img src="../images/modify.gif" style="border:0px;" /></a></div></td>
        <td width="55" bgcolor="#FFFFFF"><div align="center"><a href='javascript:if(confirm("确实要删除该内容吗,删除后再也找不回?"))location="?action=del&classid=<?=$rs2['ClassId']?>&bid=<?=$rs2['Bid']?>&lev=2"'><img src="../images/del.gif" style="border:0;" /></a></div></td>
    </tr>
	
	
	<?
	$list3=$productclass->GetClassList($rs2['ClassId']);
	foreach($list3 as $rs3)
			{
?>
			  <tr height="28">
					<td width="54" bgcolor="#FFFFFF"><div align="center"><?=$rs3['ClassId']?></div></td>
					<td width="267" bgcolor="#FFFFFF"><div align="left"><a style="padding-left:40px;" href="?action=modify&classid=<?=$rs3['ClassId']?>&bid=<?=$rs3['Bid']?>">└──[<?=$rs3['KeyWord']?>]<?=$rs3['ClassName']?>
					</a></div></td>
					<td width="80" bgcolor="#FFFFFF"><div align="center">  </div></td>
					<td width="45" bgcolor="#FFFFFF"><div align="center"><a href="?action=modify&classid=<?=$rs3['ClassId']?>&bid=<?=$rs3['Bid']?>"><img src="../images/modify.gif" style="border:0px;" /></a></div></td>
					<td width="55" bgcolor="#FFFFFF"><div align="center"><a href='javascript:if(confirm("确实要删除该吗,删除后再也找不回?"))location="?action=del&classid=<?=$rs3['ClassId']?>&bid=<?=$rs3['Bid']?>&lev=0"'><img src="../images/del.gif" style="border:0;"  /></a></div></td>
				</tr>
<?
			}
 
		}
	}
?>  
	 
	  
  </table>
  
   
</div>



</body>
</html>
