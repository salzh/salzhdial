<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../appeditor/fckeditor.php");
include("../check.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>网站配置</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/fun.js"></script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
 
 


</style>
</head>

<body>
<?
 
$configs=new WebConfig();
	$c=new ConfigInfo();
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'modify';
if($a=="modifysave")
{
 
	 
 
	$content= isset($_REQUEST['content']) ? $_REQUEST['content'] : '';
	$webtitle= isset($_REQUEST['webtitle']) ? $_REQUEST['webtitle'] : '';
	$webname= isset($_REQUEST['webname']) ? $_REQUEST['webname'] : '';
	$weburl= isset($_REQUEST['weburl']) ? $_REQUEST['weburl'] : '';
	$webdes= isset($_REQUEST['webdes']) ? $_REQUEST['webdes'] : '';
	$webkeyword= isset($_REQUEST['webkeyword']) ? $_REQUEST['webkeyword'] : '';
	$webhomemenu= isset($_REQUEST['webhomemenu']) ? $_REQUEST['webhomemenu'] : '';
 

	$c->WebTitle=$webtitle;
	$c->WebUrl=$weburl;
	$c->WebName=$webname;
 	$c->WebKeyWord=$webkeyword;
	$c->WebDes=$webdes;
 	$c->WebFoot=$content;
 	$c->WebHomeMenu=$webhomemenu;
	 
  
		if($configs->EditConfig($c)>0)
		{
			 echo Msg("操作成功","admin_config.php");
		}
		else
		{
			 echo Msg("操作失败","back");
		}
 
}
?>

<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid"><strong>&nbsp;网站基本信息配置</strong></td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
 
  
  
<?
 
	 	$c=$configs->GetConfigModel($_SESSION["userid"]);
 
?>  
    <form id="form1" name="form1" method="post" action="?action=modifysave">
    <table width="100%" height="258" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="11%" height="28" bgcolor="#FFFFFF"><div align="center">网站标题[title]  </div></td>
        <td width="89%" height="28" bgcolor="#FFFFFF"><input name="webtitle" type="text" id="webtitle"  value="<?=$c->WebTitle?>" size="50" maxlength="100" /> 
        &nbsp; </td>
      </tr>
      
      <tr>
        <td height="24" bgcolor="#FFFFFF"><div align="center">网站名称</div></td>
        <td height="24" bgcolor="#FFFFFF">
		 
		  <input name="webname" type="text" id="webname"  value="<?=$c->WebName?>" size="50" maxlength="100" />
		 		</td>
      </tr>
      <tr>
        <td height="21" bgcolor="#FFFFFF"><div align="center">网站地址</div></td>
        <td height="21" bgcolor="#FFFFFF"><input name="weburl" type="text" id="weburl"  value="<?=$c->WebUrl?>" size="50" maxlength="100" /></td>
      </tr>
      <tr style="display:none">
        <td height="40" bgcolor="#FFFFFF"><div align="center">网站面包序</div></td>
        <td height="40" bgcolor="#FFFFFF"><input name="webhomemenu" type="text" id="webhomemenu"  value="<?=$c->WebHomeMenu?>" size="50" maxlength="100" /></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">版权信息</div></td>
        <td height="40" bgcolor="#FFFFFF">
								<?php 
										 
									Editor("content","../",$c->WebFoot);
								?>								</td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF"><div align="center">Keywords</div></td>
        <td height="28" bgcolor="#FFFFFF"><input name="webkeyword" type="text" id="webkeyword" size="50" value="<?=$c->WebKeyWord?>" maxlength="200" /></td>
      </tr>
      <tr>
        <td height="28" bgcolor="#FFFFFF"><div align="center">description</div></td>
        <td height="28" bgcolor="#FFFFFF"><input name="webdes" type="text" id="webdes" size="50" maxlength="200"  value="<?=$c->WebDes?>" /></td>
      </tr>
      
      
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center"></div></td>
        <td height="40" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存信息" />
        <input type="reset" name="Submit2" value="取消" class="bt" /></td>
      </tr>
    </table>
  </form>

 

</div>



</body>
</html>
