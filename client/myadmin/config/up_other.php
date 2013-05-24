<?php

include("../check.php");
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/other.class.php");
include("../../appeditor/fckeditor.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>网站其他栏目管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/fun.js"></script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
 
 


</style>
</head>

<body>
<?
$newsclass= new OtherClass();
$newsDal=new Other();

$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'add';
if($a=="addsave" || $a=="modifysave")
{
	$title=$_POST["title"];
	if($title=='')
	{
		$title='未命名';
	}
	 
	$orderid=cint(trim($_POST['orderid']));
	$content= isset($_REQUEST['content']) ? $_REQUEST['content'] : '';
	 
	$scontent=$_POST["scontent"];
	$times=date('Y-d-m');
	 
	$classid=cint($_POST["bid"]);
	 
	$aa=array();
	$aa=$newsclass->GetBidSid($classid);
	$bid=$aa['bid'];
	$sid=$aa['sid'];
	
	
	$news=new OtherInfo();
	$news->Title=$title;
	$news->Content=$content;
	$news->Scontent=$scontent;
 	$news->Times=$times;
 	$news->OrderId=$orderid;
 	$news->Bid=$bid;
	$news->Sid=$sid;
	$news->Des="";
	$news->KeyWords="";
 	
 	 
}
///添加保存
if($a=="addsave")
{	
	if($newsDal->AddOther($news)>0)
	{
		echo Msg("操作成功","admin_other.php?action=list");
	}
}

if ($a=="modifysave")
{
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	$news->Id=$id;
	if($id>0)
	{
			 
		if($newsDal->EditOther($news)>0)
		{
			 echo Msg("操作成功","admin_other.php?action=list");
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
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">网站其他栏目-添加</span></td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
<?php
 
if($a=="add")
{
?>
  <form id="form1" name="form1" method="post" action="?action=addsave">
    <table width="100%" height="310" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="7%" height="28" bgcolor="#FFFFFF"><div align="center">标 题 </div></td>
        <td width="93%" height="28" bgcolor="#FFFFFF"><input name="title" type="text" id="title" size="50" maxlength="100" />
          &nbsp;排序
          <input name="orderid" type="text" id="orderid" value="1" style="ime-mode:disabled" onKeyPress="if ((event.keyCode<48 || event.keyCode>57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" />
          &nbsp;&nbsp; &nbsp; </td>
      </tr>
      
      <tr>
        <td height="24" bgcolor="#FFFFFF"><div align="center">分类</div></td>
        <td height="24" bgcolor="#FFFFFF">
		<div id=box><div id=box2><?
		echo $newsclass->GetSelect();
		?>
		</div></div>		</td>
      </tr>
      <tr>
        <td height="88" bgcolor="#FFFFFF"><div align="center">简介</div></td>
        <td height="88" bgcolor="#FFFFFF"><textarea name="scontent" id="scontent" style="height:80px; width:400px;overflow-y:auto"></textarea></td>
      </tr>
      <tr>
        <td height="24" bgcolor="#FFFFFF"><div align="center">详细内容</div></td>
        <td height="24" bgcolor="#FFFFFF">
								<?php 
										 
									Editor("content","../","");
								?>								</td>
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
	 	$rs=$newsDal->GetModel($id);

?>  
    <form id="form1" name="form1" method="post" action="?action=modifysave">
    <table width="100%" height="226" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="7%" height="28" bgcolor="#FFFFFF"><div align="center">标 题 </div></td>
        <td width="93%" height="28" bgcolor="#FFFFFF"><input name="title" type="text" id="title"  value="<?=$rs[0]['title']?>" size="50" maxlength="100" />
		<input name="id" type="text" id="id"  value="<?=$rs[0]['id']?>"  style="display:none" />
          &nbsp;排序
          <input name="orderid" type="text" id="orderid"  value="<?=$rs[0]['orderid']?>" style="ime-mode:disabled" onKeyPress="if ((event.keyCode<48 || event.keyCode>57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" />
          &nbsp;&nbsp; </td>
      </tr>
      
      <tr>
        <td height="24" bgcolor="#FFFFFF"><div align="center">分类</div></td>
        <td height="24" bgcolor="#FFFFFF">
		<div id=box><div id=box2><?
		echo $newsclass->GetSelect($rs[0]['bid'],$rs[0]['sid']);
		?>
		</div></div>		</td>
      </tr>
      <tr>
        <td height="88" bgcolor="#FFFFFF"><div align="center">简介</div></td>
        <td height="88" bgcolor="#FFFFFF"><textarea name="scontent" id="scontent" style="height:80px; width:400px;overflow-y:auto"><?=$rs[0]['scontent']?></textarea></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">详细内容</div></td>
        <td height="40" bgcolor="#FFFFFF">
								<?php 
										 
									Editor("content","../",$rs[0]["content"]);
								?>								</td>
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
