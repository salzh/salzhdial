<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/banner.class.php");
include("../../appeditor/fckeditor.php");
include("../check.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BANNER管理</title>
<link href=".././css/public.css" rel="stylesheet" type="text/css" />

<link href="../../upload/css/default.css" rel="stylesheet" type="text/css" />
 
<script language="javascript" src="../javascript/fun.js"></script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
</style>
</head>

<body>
<?
 $BannerDal=new Banner();

$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'add';
if($a=="addsave" || $a=="modifysave")
{
 	$orderid=cint(trim($_POST['orderid']));
	$url= isset($_REQUEST['url']) ? $_REQUEST['url'] : '';
	$urlname= isset($_REQUEST['urlname']) ? $_REQUEST['urlname'] : '';
	
 	$pic=isset($_REQUEST['pic']) ? $_REQUEST['pic'] : '/images/no.gif';
	$types=  isset($_POST["types"]) ? $_POST["types"]: '';
 	
	$depid= isset($_REQUEST['depid']) ? $_REQUEST['depid'] : '0';
	if($depid!=0)
	{
		$depid= implode(',', $depid);
	}	
	
	 $areaid= isset($_REQUEST['areaid']) ? $_REQUEST['areaid'] : '0';
	 
	 if($areaid!=0)
	{
		$areaid= implode(',', $areaid);
	}	
	
	$Banner=new BannerInfo();
	$Banner->OrderId=$orderid;
	$Banner->Url=$url;
	$Banner->UrlName=$urlname;
	$Banner->Types=$types;
	$Banner->Pic=$pic;
 	$Banner->AreaId=$areaid;
	$Banner->DepId=$depid;
}
///添加保存
if($a=="addsave")
{	
	 
	if($BannerDal->Add($Banner)>0)
	{
		echo Msg("操作成功","admin_banner.php?action=list");
	}

}
$_SESSION["file_info"] = array();

if ($a=="modifysave")
{
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	$Banner->Id=$id;
	if($id>0)
	{
			 
		if($BannerDal->Edit($Banner)>0)
		{
			 echo Msg("操作成功","admin_banner.php?action=list");
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
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">网站BANNER管理<span class="title"> </span></td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
<?php
 
if($a=="add")
{
?>
  <form id="form1" name="form1" method="post" action="?action=addsave">
    <table width="100%" height="291" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="7%" height="28" bgcolor="#FFFFFF"><div align="center">类型</div></td>
        <td width="93%" height="28" bgcolor="#FFFFFF"><select name="types" id="types">
          <option value="网站首页">网站首页</option>
         

        </select>          &nbsp;排序
          <input name="orderid" type="text" id="orderid" value="1" style="ime-mode:disabled" onKeyPress="if ((event.keyCode<48 || event.keyCode>57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" />
          &nbsp;&nbsp; </td>
      </tr>
      <tr>
        <td height="142" bgcolor="#FFFFFF"><div align="center">图片</div></td>
        <td height="142" bgcolor="#FFFFFF">
		 <table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
												  <td width="50%"><iframe src="../up.php" width="500"  scrolling="No" height="100" frameborder="0"></iframe>                                              </td>
												  <td width="50%"><img style="border:1px solid #ccc;" src="../images/no.gif" name="img" width="100" height="100" id="img"  onload="javascript:Simg(this,100,100);"   /></td>
												</tr>
												<tr>
												  <td>路径：
												  <input name="pic" type="text" id="pic" value="/images/no.gif" size="80" maxlength="100" /></td>
												  <td>&nbsp;</td>
												</tr>
											  </table>
							
	    </td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">区域</div></td>
        <td height="40" bgcolor="#FFFFFF">
		<?
		echo $BannerDal->GetAreaList($_SESSION["auth"]);
		?>		</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">部门</div></td>
        <td height="40" bgcolor="#FFFFFF">
		<?
		echo $BannerDal->GetDepList($_SESSION["product"]);
		?>		</td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF"><div align="center">连接地址</div></td>
        <td height="28" bgcolor="#FFFFFF"><input name="url" type="text" id="url" size="50" maxlength="200" />
        &nbsp; 外网地址请加:http:// </td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF"><div align="center">图片标题</div></td>
        <td height="28" bgcolor="#FFFFFF"><input name="urlname" type="text" id="urlname" size="50" maxlength="200" />
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
  
  
<?
	if ($a=="modify")
	{
		$id=isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	 	$rs=$BannerDal->GetModel($id);

?>  
    <form id="form1" name="form1" method="post" action="?action=modifysave">
    <table width="100%" height="277" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="7%" height="32" bgcolor="#FFFFFF"><div align="center">类型</div></td>
        <td width="93%" height="32" bgcolor="#FFFFFF">
		
		<select name="types" id="types">
          <option value="网站首页" <? if($rs[0]['types']=="网站首页") { ?> selected="selected" <? } ?>>网站首页</option>
      
		 
                </select>
        <input name="id" type="text" id="id"  value="<?=$rs[0]['id']?>"  style="display:none" />
          &nbsp;排序
          <input name="orderid" type="text" id="orderid"  value="<?=$rs[0]['orderid']?>" style="ime-mode:disabled" onKeyPress="if ((event.keyCode<48 || event.keyCode>57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" />
        &nbsp;</td>
      </tr>
      <tr>
        <td height="143" bgcolor="#FFFFFF"><div align="center">图片</div></td>
        <td height="143" bgcolor="#FFFFFF">
		    
										  
										   
										   
										 
										
											  <table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
												  <td width="50%"><iframe src="../up.php" width="500"  scrolling="No" height="100" frameborder="0"></iframe>                                              </td>
												  <td width="50%"><img src="../../<?=$rs[0]['pic']?>" name="img" width="100" height="100" id="img"  onload="javascript:Simg(this,100,100);"   /></td>
												</tr>
												<tr>
												  <td>路径：
												  <input name="pic" type="text" id="pic" value="<?=$rs[0]['pic']?>" size="80" maxlength="100" /></td>
												  <td>&nbsp;</td>
												</tr>
											  </table>										  	</td>
      </tr>
      
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">区域</div></td>
        <td height="40" bgcolor="#FFFFFF">
		<?
		echo $BannerDal->GetAreaList($rs[0]['AreaId']);
		?>		</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">部门</div></td>
        <td height="40" bgcolor="#FFFFFF">
		<?
		echo $BannerDal->GetDepList($rs[0]['DepId']);
		?>		</td>
      </tr>
      
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">连接地址</div></td>
        <td height="40" bgcolor="#FFFFFF"><input name="url" type="text" id="url" size="50" maxlength="200"  value="<?=$rs[0]["url"]?>" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF"><div align="center">图片标题</div></td>
        <td height="28" bgcolor="#FFFFFF"><input name="urlname" type="text" id="urlname" size="50" maxlength="200" value="<?=$rs[0]["urlname"]?>"/>
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
