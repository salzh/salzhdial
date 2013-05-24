<?php
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/showproduct.class.php");

$productClass = new ShowProductClass();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>图片上传</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="javascript/fun.js"></script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
</style>
<script language="javascript">   
    function userCheck(){   
        var form = document.form1;   
        var url = form.url.value;   
 
        if(url == ''){   
            alert("请先上传图片");   
            return false;   
        }   
        else if(url == '/product/images/no.gif'){   
            alert("请先上传图片！");   
            return false;   
        }   
        return true;   
    }   
</script>  
</head>

<body>
<?
$settypes= isset($_REQUEST['settypes']) ? $_REQUEST['settypes'] : '1';

$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'add';
if($a=="addsave" || $a=="modifysave")
{
	$title= isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
	
 	$url=isset($_REQUEST['url']) ? $_REQUEST['url'] : '/product/images/no.gif';
	$types=  isset($_POST["types"]) ? $_POST["types"]: '0';
 	
	$description= isset($_REQUEST['description']) ? $_REQUEST['description'] : '';

	$ShowProductInfo=new ShowProductInfo();
	$ShowProductInfo->Title=$title;
	$ShowProductInfo->Description=$description;
	$ShowProductInfo->Url=$url;
	$ShowProductInfo->Types=$types;
	$ShowProductInfo->Uid=$_SESSION['usermail'];
}
///添加保存
if($a=="addsave")
{	
	 
	if($productClass->Add($ShowProductInfo)>0)
	{
		echo Msg("操作成功","admin_product.php");
	}

}
$_SESSION["file_info"] = array();

if ($a=="modifysave")
{
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		
	$ShowProductInfo->Id=$id;
	if($id>0)
	{
		$ShowProductInfo->Title=$title;
		$ShowProductInfo->Description=$description;
		$ShowProductInfo->Url=$url;
		$ShowProductInfo->Types=$types;
		$ShowProductInfo->Uid=$_SESSION['usermail'];

		if($productClass->Edit($ShowProductInfo)>0)
		{
			 echo Msg("操作成功","admin_product.php");
		}
		else
		{
			 echo Msg("操作失败","back");
		}
	}
}
?>

<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">产品图片上传<span class="title"> </span></td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
<?php
 
if($a=="add")
{
?>
  <form id="form1" name="form1" method="post" action="?action=addsave" onsubmit="return userCheck();">
    <table width="100%" height="291" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="7%" height="28" bgcolor="#FFFFFF"><div align="center">类型</div></td>
        <td width="93%" height="28" bgcolor="#FFFFFF"><select name="types" id="types">
          <option value="1"  <? if($settypes=="1") { ?> selected="selected" <? } ?>>联合利华</option>
          <option value="2"  <? if($settypes=="2") { ?> selected="selected" <? } ?>>竞品</option>

        </select></td>
      </tr>
      <tr>
        <td height="142" bgcolor="#FFFFFF"><div align="center">图片</div></td>
        <td height="142" bgcolor="#FFFFFF">
		 <table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
												  <td width="50%"><iframe src="up.php" width="500"  scrolling="No" height="100" frameborder="0"></iframe>                                              </td>
												  <td width="50%"><img style="border:1px solid #ccc;" src="images/no.gif" name="img" width="100" height="100" id="img"  onload="javascript:Simg(this,100,100);"   /></td>
												</tr>
												<tr>
												  <td>
												  <input style="display:none" name="url" type="text" id="url" value="/product/images/no.gif" size="80" maxlength="1000" /></td>
												  <td>&nbsp;</td>
												</tr>
											  </table>
							
	    </td>
      </tr>
      <tr style="display:none">
        <td height="28" bgcolor="#FFFFFF"><div align="center">城市</div></td>
        <td height="28" bgcolor="#FFFFFF"><input name="title" type="text" id="title" size="50" maxlength="200" />
        &nbsp; </td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF"><div align="center">图片介绍</div></td>
        <td height="28" bgcolor="#FFFFFF"><input name="description" type="text" id="description" size="50" maxlength="200" />
        &nbsp; </td>
      </tr>      
      
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center"></div></td>
        <td height="40" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存信息" />
        <input type="button" name="Submit2" value="取消" class="bt" onclick="location.href='showProduct.php'" /></td>
      </tr>
    </table>
  </form>
<?
}
?>  
  
  
<?
	if ($a=="modify")
	{
		$id=isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	 	$rs=$productClass->GetModel($id);

?>  
    <form id="form1" name="form1" method="post" action="?action=modifysave">
    <table width="100%" height="277" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="7%" height="32" bgcolor="#FFFFFF"><div align="center">类型</div></td>
        <td width="93%" height="32" bgcolor="#FFFFFF">
		
		<select name="types" id="types">
        	<option value="1" <? if($rs[0]['types']=="1") { ?> selected="selected" <? } ?>>联合利华</option>
      		<option value="2" <? if($rs[0]['types']=="2") { ?> selected="selected" <? } ?>>竞品</option>
		</select>
        <input name="id" type="text" id="id"  value="<?=$rs[0]['id']?>"  style="display:none" />
        &nbsp;</td>
      </tr>
      <tr>
        <td height="143" bgcolor="#FFFFFF"><div align="center">图片</div></td>
        <td height="143" bgcolor="#FFFFFF">
										
											  <table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
												  <td width="50%"><iframe src="up.php" width="500"  scrolling="No" height="100" frameborder="0"></iframe>                                              </td>
												  <td width="50%"><img src="../../<?=$rs[0]['url']?>" name="img" width="100" height="100" id="img"  onload="javascript:Simg(this,100,100);"   /></td>
												</tr>
												<tr>
												  <td>路径：
												  <input name="url" type="text" id="url" value="<?=$rs[0]['url']?>" size="80" maxlength="100" /></td>
												  <td>&nbsp;</td>
												</tr>
											  </table>										  	</td>
      </tr>
      
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">城市</div></td>
        <td height="40" bgcolor="#FFFFFF"><input name="title" type="text" id="title" size="50" maxlength="200"  value="<?=$rs[0]["title"]?>" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF"><div align="center">图片介绍</div></td>
        <td height="28" bgcolor="#FFFFFF"><input name="description" type="text" id="description" size="50" maxlength="200" value="<?=$rs[0]["description"]?>"/>
        &nbsp; </td>
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

</div>



</body>
</html>
