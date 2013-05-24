<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/product.class.php");
include("../../appeditor/fckeditor.php");
include("../check.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>产品管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/fun.js"></script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
 
 


</style>
</head>

<body>
<?
 	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
 	$picid= isset($_REQUEST['picid']) ? $_REQUEST['picid'] : '';
		$ppic=new ProductPic();
		$ppicinfo=new ProductPicInfo();
		 $pro=new Product();
		$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'add';
 		if($a=="addsave" || $a=="modifysave")
		{
		 
			$orderid=cint(isset($_REQUEST['orderid']) ? $_REQUEST['orderid'] : '0');
			$pic=isset($_POST["pic"]) ? $_POST["pic"] : '/pic/no.jpg';
			$productid=cint(isset($_REQUEST['productid']) ? $_REQUEST['productid'] : '0');
		 
			$ppicinfo->OrderId=$orderid;
			$ppicinfo->ProductId=$productid;
			$ppicinfo->Pic=$pic;
		 
		}
			///添加保存
			if($a=="addsave")
			{	
				 
				if($ppic->AddProductPic($ppicinfo)>0)
				{
					$pro->AddLog($_COOKIE['username'],$productid,$pro->GetTitle($productid),"添加 [".$pro->GetTitle($productid)."] 产品图片信息");	
					echo Msg("操作成功","up_product_pic.php?action=add&id=$productid");
				}
			
			}
				
			if ($a=="modifysave")
			{
				$ppicinfo->Id=$picid;
				if($picid>0)
				{
						 
					if($ppic->EditProductPic($ppicinfo)>0)
					{
						$pro->AddLog($_COOKIE['username'],$productid,$pro->GetTitle($productid),"修改 [".$pro->GetTitle($productid)."] 产品图片信息");	
						 echo Msg("操作成功","up_product_pic.php?action=add&id=$productid");
					}
					else
					{
						 echo Msg("操作失败","back");
					}
				}
			}
			
			if ($a=="del"){
				$picid= isset($_REQUEST['picid']) ? $_REQUEST['picid'] : '0';
				$file= isset($_REQUEST['file']) ? $_REQUEST['file'] : '';
				if($ppic->DelProductPic($picid,$file)){
				$pro->AddLog($_COOKIE['username'],$id,$pro->GetTitle($id),"删除[".$pro->GetTitle($id)."] 产品图片信息");
				echo Msg("操作成功","up_product_pic.php?action=add&id=$id");
				}
			}

	
?>

<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">产品管理&gt;产品图片管理</span></td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
   

 

    <table width="500" height="37" border="0" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
      <tr>
        <td width="100" bgcolor="#EFEFEF"><div align="center"><a href="up_product.php?action=modify&id=<?=$_REQUEST['id']?>" >产品基础信息</a></div></td>
        <td width="100" bgcolor="#0099FF"><div align="center" style="color:#fff; cursor:pointer;" ><a href="up_product_pic.php?id=<?=$_REQUEST['id']?>" style="color:#FFFFFF"><strong>产品图片</strong></a></div></td>
        <td width="100" bgcolor="#efefef"><div align="center" style="color:#999999; cursor:pointer;"  ><a href="up_product_sy.php?id=<?=$_REQUEST['id']?>">实用指南</a></div></td>
        <td width="100" bgcolor="#efefef"><div align="center" style="color:#999999; cursor:pointer;" ><a href="up_product_like.php?id=<?=$_REQUEST['id']?>">相关产品</a></div></td>
        <td width="100" bgcolor="#efefef"><div align="center" style="color:#999999; cursor:pointer;" ><a href="up_product_service.php?id=<?=$_REQUEST['id']?>">服务支持</a></div></td>
      </tr>
    </table>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="10">
		
		<?
			$listpic = $ppic->Getlist(isset($_REQUEST['id']) ? $_REQUEST['id'] : '0',0,20,"");
			if($listpic!=NULL)
			{
			foreach($listpic as $rspic)
			{
			
		?>
		<div style="float:left; width:110px; height:120px; border:1px solid #ccc; margin:3px;">
		 <table width="99" height="119" border="0" align="center" cellpadding="0" cellspacing="0">
           <tr>
             <td width="99" height="72"><div align="center"><a href="up_product_pic.php?id=<?=$id?>&amp;picid=<?=$rspic['id']?>&amp;action=modify"><img src="../../<?=$rspic['pic']?>" width="78" height="78" style="border:0" /></a></div></td>
            </tr>
           <tr>
             <td height="22"><div align="center"><a href="up_product_pic.php?id=<?=$id?>&amp;picid=<?=$rspic['id']?>&amp;action=modify">修改</a>&nbsp;&nbsp;&nbsp;    <a href="?action=del&picid=<?=$rspic['id']?>&id=<?=$id?>&file=<?=$rspic["pic"]?>" onclick="return confirm('确定要删除吗，删除后不可恢复');"> <img src="../images/del.png" style="border:0px;" /></a></div></td>
            </tr>
         </table>
		 </div>
		
		<?
			}
		}
		?>	 <div style="float:left; width:110px;  background:#efefef; line-height:120px; text-align:center; height:120px; border:1px solid #ccc; margin:3px;">
		 	<a href="?action=add&id=<?=$id?>" style="font-size:50px;">添加</a>
		 </div> 
	</td>
      </tr>
    </table><br />
<?
	
 
 	if ($a=="add")
	{	 
	if($ppic->GetCount($id)<11)
	{	 
?>  
    <form id="form1" name="form1" method="post" action="?action=addsave">

    <table width="100%" height="249" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      
      <tr>
        <td height="36" colspan="2" bgcolor="#efefef">添加图片 [最多支持10张] </td>
      </tr>
      <tr>
        <td height="21" bgcolor="#FFFFFF"><div align="center">排序</div></td>
        <td height="21" bgcolor="#FFFFFF"><input name="orderid" type="text" id="orderid"  value="1" style="ime-mode:disabled" onkeypress="if ((event.keyCode&lt;48 || event.keyCode&gt;57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" />
&nbsp;</td>
      </tr>
      <tr>
        <td width="7%" height="140" bgcolor="#FFFFFF"><div align="center">缩略图</div></td>
        <td width="93%" height="140" bgcolor="#FFFFFF">
		    
										   
										  
											  <table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
												  <td width="50%"><iframe src="../up.php" width="500"  scrolling="No" height="100" frameborder="0"></iframe>                                              </td>
												  <td width="50%"><img src="../../pic/no.jpg" name="img" width="100" height="100" border="0" id="img"  onload="javascript:Simg(this,100,100);"   /></td>
												</tr>
												<tr>
												  <td>路径：
												  <input name="pic" type="text" id="pic" value="/pic/no.jpg" size="80" maxlength="100" />
												  <input name="productid" type="hidden" id="productid" value="<?=$id?>" /></td>
												  <td>&nbsp;</td>
												</tr>
											  </table>	    </td>
      </tr>
      
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center"></div></td>
        <td height="40" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存信息" />
        <input type="reset" name="Submit2" value="取消" class="bt" /></td>
      </tr>
    </table>
  </form>

<?
 }

}
?>



<?
	
	if ($a=="modify")
	{
		$picid=isset($_REQUEST['picid']) ? $_REQUEST['picid'] : '0';
	 	$rs=$ppic->GetClassModel($picid);
 		if ($rs->Id!=0)
		{
?>  
    <form id="form1" name="form1" method="post" action="?action=modifysave">

    <table width="100%" height="242" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      
      <tr>
        <td height="29" colspan="2" bgcolor="#EFEFEF">修改图片</td>
      </tr>
      <tr>
        <td height="21" bgcolor="#FFFFFF"><div align="center">排序</div></td>
        <td height="21" bgcolor="#FFFFFF"><input name="orderid" type="text" id="orderid"  value="<?=$rs->OrderId?>" style="ime-mode:disabled" onkeypress="if ((event.keyCode&lt;48 || event.keyCode&gt;57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" />
&nbsp;</td>
      </tr>
      <tr>
        <td width="7%" height="140" bgcolor="#FFFFFF"><div align="center">缩略图</div></td>
        <td width="93%" height="140" bgcolor="#FFFFFF">
		    
										   
										  
											  <table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
												  <td width="50%"><iframe src="../up.php" width="500"  scrolling="No" height="100" frameborder="0"></iframe>                                              </td>
												  <td width="50%"><img src="../../<?=$rs->Pic?>" name="img" width="100" height="100" id="img"  onload="javascript:Simg(this,100,100);"   /></td>
												</tr>
												<tr>
												  <td>路径：
												  <input name="pic" type="text" id="pic" value="<?=$rs->Pic?>" size="80" maxlength="100" />
												  <input name="productid" type="hidden" id="productid" value="<?=$rs->ProductId?>" />
												  
												  <input name="picid" type="hidden" id="productid" value="<?=$rs->Id?>" /></td>
												  <td>&nbsp;</td>
												</tr>
											  </table>	    </td>
      </tr>
      
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center"></div></td>
        <td height="40" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存信息" />
        <input type="reset" name="Submit2" value="取消" class="bt" /></td>
      </tr>
    </table>
  </form>

<?
}

}
?>

</div>



</body>
</html>
