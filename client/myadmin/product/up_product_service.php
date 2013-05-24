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
 	$picid= isset($_REQUEST['picid']) ? $_REQUEST['picid'] : '0';
		$ppic=new ProductPic();
		$ppicinfo=new ProductPicInfo();
		 $pro=new Product();
		$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'add';
 		if($a=="addsave" || $a=="modifysave")
		{
		 
			$orderid=cint(isset($_REQUEST['orderid']) ? $_REQUEST['orderid'] : '0');
			$title=isset($_POST["title"]) ? $_POST["title"] : '';
			$pic=isset($_POST["pic"]) ? $_POST["pic"] : '';
			$productid=cint(isset($_REQUEST['productid']) ? $_REQUEST['productid'] : '0');
			$ppicinfo->OrderId=$orderid;
			$ppicinfo->ProductId=$productid;
			$ppicinfo->Title=$title;
			$ppicinfo->Pic=$pic;
		}
			///添加保存
			if($a=="addsave")
			{	
				 
				if($ppic->AddProductDownload($ppicinfo)>0)
				{
				$pro->AddLog($_COOKIE['username'],$productid,$pro->GetTitle($productid),"添加 [".$pro->GetTitle($productid)."] 服务支持信息");	
					echo Msg("操作成功","up_product_service.php?action=add&id=$productid");
				}
			
			}

			if ($a=="modifysave")
			{
			
				$ppicinfo->Id=$picid;
				if($picid>0)
				{
						 
					if($ppic->EditProductDownload($ppicinfo)>0)
					{
						$pro->AddLog($_COOKIE['username'],$productid,$pro->GetTitle($productid),"修改 [".$pro->GetTitle($productid)."] 服务支持信息");	
						 echo Msg("操作成功","up_product_service.php?action=add&id=$productid");
					}
					else
					{
						 echo Msg("操作失败","back");
					}
				}
			}
			
			if ($a=="del"){
				$picid= isset($_REQUEST['picid']) ? $_REQUEST['picid'] : '0';
				if($ppic->DelProductDownload($picid)){
				$pro->AddLog($_COOKIE['username'],$id,$pro->GetTitle($id),"删除[".$pro->GetTitle($id)."] 服务支持信息");
				echo Msg("操作成功","up_product_service.php?action=add&id=$id");
				}
			}

	
?>

<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">产品管理&gt;服务支持</span></td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
   

 

    <table width="500" height="37" border="0" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
      <tr>
        <td width="100" bgcolor="#EFEFEF"><div align="center"><a href="up_product.php?action=modify&id=<?=$_REQUEST['id']?>" >产品基础信息</a></div></td>
        <td width="100" bgcolor="#EFEFEF"><div align="center" style="color:#fff; cursor:pointer;" ><a href="up_product_pic.php?id=<?=$_REQUEST['id']?>" ><strong>产品图片</strong></a></div></td>
        <td width="100" bgcolor="#efefef"><div align="center" style="color:#999999; cursor:pointer;"  ><a href="up_product_sy.php?id=<?=$_REQUEST['id']?>" ><strong>实用指南</strong></a></div></td>
        <td width="100" bgcolor="#efefef"><div align="center" style="color:#999999; cursor:pointer;" ><a href="up_product_like.php?id=<?=$_REQUEST['id']?>" >相关产品</a></div></td>
        <td width="100" bgcolor="#0099FF"><div align="center" style="color:#999999; cursor:pointer;" ><a href="up_product_service.php?id=<?=$_REQUEST['id']?>" style="color:#FFFFFF"> <strong>服务支持</strong></a></div></td>
      </tr>
    </table>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="10">	<a href="?action=add&id=<?=$id?>" style="font-size:15px; margin:5px; background:#efefef; text-align:center; display:block; width:100px; height:30px; line-height:30px;">添加</a>
          <table width="600" height="31" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="60" bgcolor="#efefef"><div align="center">排序</div></td>
              <td width="446" bgcolor="#efefef"> 标题 </td>
              <td width="83" height="31" bgcolor="#efefef"><div align="center"> 操作 </div></td>
            </tr>
          </table>
          <br />
		
		<?
			$listpic = $ppic->GetlistDownload($id,0,100,"");
			if($listpic!=NULL)
			{
			foreach($listpic as $rspic)
			{
			
		?>
		 
		 <table width="600" height="29" border="0"  cellpadding="0" cellspacing="0" style="border-bottom:1px solid #ccc;">
           <tr>
             <td width="60"><div align="center">
               <?=$rspic["orderid"]?>
             </div></td>
             <td width="446"><a href="up_product_service.php?id=<?=$id?>&amp;picid=<?=$rspic['id']?>&amp;action=modify">
               <?=$rspic["title"]?>
             </a></td>
             <td width="83" height="29"><div align="left"><a href="up_product_service.php?id=<?=$id?>&amp;picid=<?=$rspic['id']?>&amp;action=modify">&nbsp; 修改</a>&nbsp;&nbsp;&nbsp; <a href="?action=del&picid=<?=$rspic['id']?>&id=<?=$id?>" onclick="return confirm('确定要删除吗，删除后不可恢复');"> 删除</a></div></td>
           </tr>
         </table>
		 
		
		<?
			}
		}
		?> 
	</td>
      </tr>
    </table><br />
<?
	
 
 	if ($a=="add")
	{	 
	  
?>  
    <form id="form1" name="form1" method="post" action="?action=addsave">

    <table width="100%" height="190" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      
      <tr>
        <td height="36" colspan="2" bgcolor="#efefef">添加服务支持</td>
      </tr>
      <tr>
        <td height="21" bgcolor="#FFFFFF"><div align="center">排序</div></td>
        <td height="21" bgcolor="#FFFFFF"><input name="orderid" type="text" id="orderid"  value="1" style="ime-mode:disabled" onkeypress="if ((event.keyCode&lt;48 || event.keyCode&gt;57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" />
&nbsp;</td>
      </tr>
      <tr>
        <td width="7%" height="40" bgcolor="#FFFFFF"><div align="center">名称
            <input name="productid" type="hidden" id="productid" value="<?=$id?>" />
        </div>        </td>
        <td width="93%" bgcolor="#FFFFFF"><input name="title" type="text" id="title" size="80" maxlength="50" /></td>
      </tr>
      
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">上传文件</div></td>
        <td height="40" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%"><iframe src="../up2.php" width="500"  scrolling="No" height="100" frameborder="0"></iframe></td>
            <td width="50%">&nbsp;</td>
          </tr>
          <tr>
            <td>路径：
              <input name="pic" type="text" id="pic" size="80" maxlength="100" /></td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
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
?>



<?
	
	if ($a=="modify")
	{
		$picid=isset($_REQUEST['picid']) ? $_REQUEST['picid'] : '0';
	 	$rs=$ppic->GetClassModelDownload($picid);
 		if ($rs->Id!=0)
		{
?>  
    <form id="form1" name="form1" method="post" action="?action=modifysave">

    <table width="100%" height="164" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      
      <tr>
        <td height="29" colspan="2" bgcolor="#EFEFEF">修改内容</td>
      </tr>
      <tr>
        <td height="21" bgcolor="#FFFFFF"><div align="center">排序</div></td>
        <td height="21" bgcolor="#FFFFFF"><input name="orderid" type="text" id="orderid"  value="<?=$rs->OrderId?>" style="ime-mode:disabled" onkeypress="if ((event.keyCode&lt;48 || event.keyCode&gt;57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" />
&nbsp;</td>
      </tr>
      <tr>
        <td width="7%" height="28" bgcolor="#FFFFFF"><div align="center">名称
          <input name="productid" type="hidden" id="productid" value="<?=$rs->ProductId?>" />
		          <input name="picid" type="hidden" id="picid" value="<?=$rs->Id?>" />
		  
        </div></td>
        <td width="93%" height="28" bgcolor="#FFFFFF"><input name="title" type="text" id="title" value="<?=$rs->Title?>" size="80" maxlength="50" /></td>
      </tr>
      
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">上传文件</div></td>
        <td height="40" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%"><iframe src="../up2.php" width="500"  scrolling="No" height="100" frameborder="0"></iframe></td>
            <td width="50%">&nbsp;</td>
          </tr>
          <tr>
            <td>路径：
              <input name="pic" type="text" id="pic" value="<?=$rs->Pic?>" size="80" maxlength="100" /></td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
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
