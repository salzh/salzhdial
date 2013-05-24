<?php

include("../check.php");
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/video.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>视频管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/fun.js"></script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
</style>
</head>

<body>
<?
$v=new VideoClass();

$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'modify';
if($a=="modifysave")
{
	$pic= isset($_REQUEST['pic']) ? $_REQUEST['pic'] : '';
	$video= isset($_REQUEST['video']) ? $_REQUEST['video'] : '';
	$vinfo=new VideoInfo();
	$vinfo->Pic=$pic;
	$vinfo->Video=$video;
 }
 

if ($a=="modifysave")
{
	$id= 1;
	$vinfo->Id=$id;
	if($id>0)
	{
		if($v->Edit($vinfo)>0)
		{ echo Msg("操作成功","up_video.php");	}
		else
		{ echo Msg("操作失败","back");}
	}
}
?>

<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">视频管理</span></td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
<?
	if ($a=="modify")
	{
		$id=1;
	 	$rs=$v->GetModel($id);
?>
<form id="form1" name="form1" method="post" action="?action=modifysave">
    <table width="100%" height="206" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      
      <tr>
        <td width="7%" height="40" bgcolor="#FFFFFF"><div align="center">封面图</div></td>
        <td width="93%" height="40" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="43%"><iframe src="../up.php" width="500"  scrolling="No" height="100" frameborder="0"></iframe></td>
            <td width="57%"><img src="../../<?=$rs->Pic?>" style="border:1px solid #ccc" name="img" width="100" height="100" id="img"  onload="javascript:Simg(this,100,100);"></td>
          </tr>
          <tr>
            <td><input name="pic" type="text" id="pic" value="<?=$rs->Pic?>" size="80" maxlength="100"  style="display:none" /></td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">视频</div></td>
        <td height="40" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="43%"><iframe src="../up3.php" width="500"  scrolling="No" height="60" frameborder="0"></iframe></td>
            </tr>
          <tr>
            <td>路径：
              <input name="video" type="text" id="video" value="<?=$rs->Video?>" size="80" maxlength="100" /></td>
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

</div>



</body>
</html>
